<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\JanjiKonseling;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Catatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    // Alias untuk dashboard
    public function index()
    {
        return $this->dashboard();
    }

    // Dashboard utama
    public function dashboard()
    {
        // Ambil data permintaan menunggu
        $permintaanBaru = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name', 'users.email')
            ->where('janji_konselings.status', 'menunggu')
            ->orderBy('janji_konselings.tanggal', 'asc')
            ->get();

        // Statistik
        $stats = [
            'total_siswa' => DB::table('users')->where('role', 'siswa')->count(),
            'permintaan_menunggu' => $permintaanBaru->count(),
            'konseling_hari_ini' => DB::table('janji_konselings')
                ->whereDate('tanggal', \Carbon\Carbon::today())
                ->where('status', 'dikonfirmasi')
                ->count(),
            'kasus_aktif' => DB::table('janji_konselings')
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->count(),
            'tindak_lanjut' => 0,
            'selesai_bulan_ini' => DB::table('janji_konselings')
                ->where('status', 'selesai')
                ->whereMonth('tanggal', \Carbon\Carbon::now()->month)
                ->count(),
        ];

        // Temporary debug log: capture stats and recent 'selesai' rows
        try {
            $recentSelesai = DB::table('janji_konselings')
                ->where('status', 'selesai')
                ->orderBy('tanggal', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'user_id' => $r->user_id,
                        'tanggal' => (string) $r->tanggal,
                        'status' => $r->status,
                    ];
                })->toArray();

            Log::info('GURU_DASH_STATS', [
                'computed_stats' => $stats,
                'recent_selesai_sample' => $recentSelesai
            ]);
        } catch (\Exception $e) {
            // ignore logging errors
        }

        // Jadwal hari ini
        $jadwalHariIni = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name')
            ->whereDate('janji_konselings.tanggal', \Carbon\Carbon::today())
            ->where('janji_konselings.status', 'dikonfirmasi')
            ->orderBy('janji_konselings.waktu')
            ->get();

        // Riwayat konseling
        $riwayatKonseling = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name')
            ->where('janji_konselings.status', 'selesai')
            ->orderBy('janji_konselings.tanggal', 'desc')
            ->limit(10)
            ->get();



        return view('guru.dashboard', compact('stats', 'permintaanBaru', 'jadwalHariIni', 'riwayatKonseling'));
    }

    // Kelola Jadwal
    public function jadwalKonseling()
    {
        $jadwal = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select(
                'janji_konselings.*',
                'users.name as nama_siswa',
                'users.email as email_siswa'
            )
            ->orderBy('janji_konselings.tanggal', 'desc')
            ->paginate(15);

        return view('guru.jadwal', compact('jadwal'));
    }

    // view tambah jadwal (umum) — kirim daftar siswa untuk dropdown
    public function tambahJadwal()
    {
        $siswaList = User::where('role', 'siswa')->orderBy('name')->get();
        return view('guru.jadwal-tambah', compact('siswaList'));
    }

    // view tambah jadwal khusus untuk satu siswa — kirim selectedSiswa
    public function tambahJadwalForSiswa($id)
    {
        $selectedSiswa = User::where('id', $id)->where('role', 'siswa')->first();
        if (! $selectedSiswa) abort(404);
        return view('guru.jadwal-tambah', compact('selectedSiswa'));
    }

    public function simpanJadwal(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'mulai' => 'required',
            'selesai' => 'nullable',
            'jenis_bimbingan' => 'required',
            'keluhan' => 'nullable|string',
        ]);

        // Format waktu: "mulai - selesai"
        $waktu = $request->mulai;
        if ($request->selesai) {
            $waktu .= ' - ' . $request->selesai;
        }

        DB::table('janji_konselings')->insert([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'waktu' => $waktu,
            'jenis_bimbingan' => $request->jenis_bimbingan,
            'keluhan' => $request->keluhan ?? '',
            'guru_bk' => Auth::user()->name,
            'status' => 'dikonfirmasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('guru.jadwal')->with('success', 'Jadwal berhasil ditambahkan');
    }

    // DETAIL JADWAL
    public function detailJadwal($id)
    {
        $jadwal = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name as nama_siswa', 'users.email as email_siswa')
            ->where('janji_konselings.id', $id)
            ->first();

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan');
        }

        return view('guru.jadwal-detail', compact('jadwal'));
    }

   // EDIT JADWAL
public function editJadwal($id)
{
    $jadwal = DB::table('janji_konselings')->where('id', $id)->first();
    if (!$jadwal) {
        abort(404);
    }

    $siswaList = User::where('role', 'siswa')->orderBy('name')->get();
    $selectedSiswa = $jadwal->user_id ? User::find($jadwal->user_id) : null;

    return view('guru.jadwal-edit', compact('jadwal', 'siswaList', 'selectedSiswa'));
}

    // TANDAI JADWAL SELESAI
    public function selesaiJadwal($id)
    {
        $jadwal = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->where('janji_konselings.id', $id)
            ->select('janji_konselings.*', 'users.name')
            ->first();

        if (!$jadwal) {
            abort(404, 'Jadwal konseling tidak ditemukan');
        }

        // Load user relationship untuk view
        $jadwal->user = User::find($jadwal->user_id);

        return view('guru.jadwal-selesai', compact('jadwal'));
    }
    // UPDATE JADWAL
    public function updateJadwal(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'mulai' => 'required',
            'selesai' => 'nullable',
            'jenis_bimbingan' => 'required',
            'keluhan' => 'nullable|string',
            'status' => 'required|in:menunggu,dikonfirmasi,selesai,dibatalkan',
        ]);

        // Format waktu
        $waktu = $request->mulai;
        if ($request->selesai) {
            $waktu .= ' - ' . $request->selesai;
        }

        DB::table('janji_konselings')
            ->where('id', $id)
            ->update([
                'user_id' => $request->user_id,
                'tanggal' => $request->tanggal,
                'waktu' => $waktu,
                'jenis_bimbingan' => $request->jenis_bimbingan,
                'keluhan' => $request->keluhan ?? '',
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        // Jika status berubah ke selesai, redirect ke halaman riwayat
        if ($request->status === 'selesai') {
            return redirect()->route('guru.riwayat.index')
                ->with('success', 'Konseling selesai. Riwayat telah ditambahkan.');
        }

        return redirect()->route('guru.jadwal')->with('success', 'Jadwal berhasil diperbarui');
    }

    // HAPUS JADWAL
    public function hapusJadwal($id)
    {
        $jadwal = DB::table('janji_konselings')->where('id', $id)->first();

        if (!$jadwal) {
            return redirect()->route('guru.jadwal')->with('error', 'Jadwal tidak ditemukan');
        }

        // Prevent deletion of completed or archived sessions
        if ($jadwal->status === 'selesai') {
            return redirect()->route('guru.jadwal')
                ->with('error', 'Tidak dapat menghapus jadwal yang sudah selesai. Jadwal telah diarsipkan dan tidak dapat diubah.');
        }

        if ($jadwal->is_archived) {
            return redirect()->route('guru.jadwal')
                ->with('error', 'Tidak dapat menghapus jadwal yang sudah diarsipkan');
        }

        // Only allow deletion of sessions that are not completed
        if (!in_array($jadwal->status, ['menunggu', 'dikonfirmasi', 'dibatalkan'])) {
            return redirect()->route('guru.jadwal')
                ->with('error', 'Status jadwal tidak memungkinkan penghapusan');
        }

        DB::table('janji_konselings')->where('id', $id)->update([
            'status' => 'dibatalkan',
            'is_archived' => false
        ]);

        return redirect()->route('guru.jadwal')->with('success', 'Jadwal berhasil dibatalkan');
    }

    // FORM TAMBAH CATATAN
    public function tambahCatatanForm($id)
    {
        $janji = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name as nama_siswa')
            ->where('janji_konselings.id', $id)
            ->first();

        if (!$janji) {
            abort(404, 'Janji konseling tidak ditemukan');
        }

        // Load relasi untuk akses user data lengkap
        $janji = \App\Models\JanjiKonseling::find($id);

        return view('guru.riwayat.tambah', compact('janji'));
    }

    // Kelola Permintaan
    public function semuaPermintaan()
    {
        $permintaan = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name', 'users.email')
            ->where('janji_konselings.status', 'menunggu')
            ->orderBy('janji_konselings.created_at', 'desc')
            ->paginate(20);

        return view('guru.permintaan', compact('permintaan'));
    }

    public function konfirmasiJanji($id)
    {
        \Log::info('=== KONFIRMASI JANJI ===', [
            'janji_id' => $id,
            'guru_name' => Auth::user()->name
        ]);

        $janji = DB::table('janji_konselings')->where('id', $id)->first();
        
        if (!$janji) {
            \Log::error('Janji not found', ['id' => $id]);
            return redirect()->back()->with('error', 'Janji konseling tidak ditemukan');
        }

        // Update status to dikonfirmasi and ensure guru_bk is set
        DB::table('janji_konselings')
            ->where('id', $id)
            ->update([
                'status' => 'dikonfirmasi',
                'guru_bk' => $janji->guru_bk ?? Auth::user()->name, // Preserve atau update guru_bk
                'updated_at' => now()
            ]);

        \Log::info('Janji confirmed', [
            'janji_id' => $id,
            'user_id' => $janji->user_id,
            'guru_bk' => $janji->guru_bk ?? Auth::user()->name
        ]);

        \Log::info('=== END ===');

        // Redirect to input catatan form instead of back
        return redirect()->route('guru.catatan.input', $id)->with('success', 'Janji konseling berhasil dikonfirmasi. Silakan input catatan hasil konseling.');
    }

    /**
     * Show form untuk input catatan konseling
     */
    public function inputCatatan($id)
    {
        // Get janji dengan user relationship
        $janji = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->where('janji_konselings.id', $id)
            ->select('janji_konselings.*', 'users.name')
            ->first();
        
        if (!$janji) {
            return redirect()->route('guru.dashboard')->with('error', 'Janji konseling tidak ditemukan');
        }

        // Check if status is dikonfirmasi (only allow input on confirmed appointments)
        if ($janji->status !== 'dikonfirmasi') {
            return redirect()->route('guru.dashboard')->with('error', 'Hanya bisa input catatan untuk janji yang sudah dikonfirmasi');
        }

        return view('guru.input-catatan', ['janji' => $janji]);
    }

    /**
     * Save catatan dan rekomendasi untuk janji konseling
     */
    public function saveCatatan(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'isi_catatan' => 'required|string|min:10',
            'rekomendasi' => 'required|string|min:10'
        ], [
            'isi_catatan.required' => 'Catatan hasil konseling harus diisi',
            'isi_catatan.min' => 'Catatan minimal 10 karakter',
            'rekomendasi.required' => 'Rekomendasi harus diisi',
            'rekomendasi.min' => 'Rekomendasi minimal 10 karakter'
        ]);

        // Get janji
        $janji = JanjiKonseling::findOrFail($id);
        
        // Check status
        if ($janji->status !== 'dikonfirmasi') {
            return back()->with('error', 'Hanya bisa input catatan untuk janji yang sudah dikonfirmasi');
        }

        try {
            // Combine catatan dan rekomendasi with separator
            $catatanLengkap = $request->isi_catatan . "\n--- REKOMENDASI ---\n" . $request->rekomendasi;

            // Cek apakah catatan sudah ada
            $existingCatatan = DB::table('catatan')
                ->where('janji_id', $id)
                ->first();

            if ($existingCatatan) {
                // Update existing catatan
                DB::table('catatan')
                    ->where('janji_id', $id)
                    ->update([
                        'isi' => $catatanLengkap,
                        'guru_bk' => Auth::user()->name,
                        'tanggal' => now(),
                        'updated_at' => now()
                    ]);
            } else {
                // Create new catatan
                DB::table('catatan')->insert([
                    'user_id' => $janji->user_id,
                    'janji_id' => $id,
                    'isi' => $catatanLengkap,
                    'guru_bk' => Auth::user()->name,
                    'tanggal' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            \Log::info('Catatan saved successfully', [
                'janji_id' => $id,
                'user_id' => $janji->user_id,
                'guru_bk' => Auth::user()->name
            ]);

            return redirect()->route('guru.jadwal.selesai', $id)
                ->with('success', 'Catatan berhasil disimpan. Sekarang Anda dapat menandai konseling sebagai selesai.');
        } catch (\Exception $e) {
            \Log::error('Error saving catatan', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menyimpan catatan: ' . $e->getMessage())->withInput();
        }
    }

    public function tolakJanji(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|min:10'
        ]);

        DB::table('janji_konselings')
            ->where('id', $id)
            ->update([
                'status' => 'dibatalkan',
                'catatan_konselor' => 'Ditolak: ' . $request->alasan,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Janji konseling berhasil ditolak');
    }

    public function selesaiJanji(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|min:10'
        ]);

        \Log::info('=== SELESAI JANJI ===', [
            'janji_id' => $id,
            'guru_name' => Auth::user()->name,
            'has_catatan' => !empty($request->catatan)
        ]);

        $janji = DB::table('janji_konselings')->where('id', $id)->first();
        
        if (!$janji) {
            \Log::error('Janji not found', ['id' => $id]);
            return redirect()->back()->with('error', 'Janji konseling tidak ditemukan');
        }

        // Update janji status to 'selesai' and ensure guru_bk is set
        DB::table('janji_konselings')
            ->where('id', $id)
            ->update([
                'status' => 'selesai',
                'catatan_konselor' => $request->catatan ?? '',
                'guru_bk' => $janji->guru_bk ?? Auth::user()->name, // Preserve atau update guru_bk
                'updated_at' => now()
            ]);

        \Log::info('Janji marked as selesai', [
            'janji_id' => $id,
            'user_id' => $janji->user_id,
            'guru_bk' => $janji->guru_bk ?? Auth::user()->name
        ]);

        \Log::info('=== END ===');

        return redirect()->back()->with('success', 'Konseling berhasil ditandai selesai');
    }

    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required'
        ]);

        DB::table('janji_konselings')
            ->where('id', $id)
            ->update([
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'status' => 'dikonfirmasi',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Jadwal berhasil diubah');
    }

    // Kelola Siswa
    public function daftarSiswa(\Illuminate\Http\Request $request)
    {
        try {
            // Query siswa dengan students table
            $query = DB::table('users')
                ->where('role', 'siswa')
                ->leftJoin('students', 'students.user_id', '=', 'users.id')
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.phone',
                    'users.class',
                    'students.id as student_id',
                    'students.nis',
                    'students.nama_lengkap',
                    'students.kelas',
                    'students.tgl_lahir',
                    'students.alamat',
                    'students.no_hp',
                    'users.created_at'
                );

            // pencarian nama / email
            if ($search = $request->query('q')) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', '%'.$search.'%')
                      ->orWhere('users.email', 'like', '%'.$search.'%');
                });
            }

            // filter kelas (exact match)
            if ($kelas = $request->query('kelas')) {
                $query->where('students.kelas', $kelas);
            }

            // Order by NIS dan paginate
            $siswa = $query->orderBy('students.nis', 'asc')
                ->paginate(20)
                ->withQueryString();

            // Debug log untuk melihat data yang ter-load
            Log::info('Guru loaded siswa list - DETAIL', [
                'total' => $siswa->total(),
                'count' => $siswa->count(),
                'current_page' => $siswa->currentPage(),
                'guru_id' => Auth::id(),
                'first_item' => $siswa->count() > 0 ? [
                    'id' => $siswa->first()->id,
                    'name' => $siswa->first()->name,
                    'kelas' => $siswa->first()->kelas,
                    'email' => $siswa->first()->email,
                ] : null,
            ]);

            // untuk dropdown kelas — ambil daftar distinct kelas dari tabel students (hanya yang tidak kosong)
            $kelasList = DB::table('students')
                ->select('kelas')
                ->whereNotNull('kelas')
                ->where('kelas', '!=', '') // Filter string kosong
                ->where('kelas', '!=', ' ') // Filter spasi
                ->distinct()
                ->orderBy('kelas')
                ->pluck('kelas');

            return view('guru.siswa', compact('siswa', 'kelasList'));
        } catch (\Exception $e) {
            Log::error('Error loading siswa list in guru', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'guru_id' => Auth::id()
            ]);

            return back()->with('error', 'Gagal memuat data siswa: ' . $e->getMessage());
        }
    }

    public function detailSiswa($id)
    {
        $siswa = DB::table('users')->where('id', $id)->first();
        
        if (!$siswa) {
            abort(404, 'Siswa tidak ditemukan');
        }

        $riwayatKonseling = DB::table('janji_konselings')
            ->where('user_id', $id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.siswa-detail', compact('siswa', 'riwayatKonseling'));
    }

    /**
     * Edit Siswa - Load form edit siswa (sama seperti koordinator)
     */
    public function editSiswa($id)
    {
        try {
            // Query user dengan role siswa, left join dengan students
            $siswa = DB::table('users')
                ->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->where('users.id', $id)
                ->where('users.role', 'siswa')
                ->select(
                    'users.*',
                    'students.id as student_id',
                    'students.nis',
                    'students.nama_lengkap',
                    'students.kelas',
                    'students.tgl_lahir',
                    'students.alamat',
                    'students.no_hp'
                )
                ->first();

            if (!$siswa) {
                abort(404, 'Siswa tidak ditemukan');
            }

            Log::info('Edit siswa form accessed from guru', [
                'user_id' => $id,
                'accessed_by' => Auth::id()
            ]);

            return view('guru.siswa-edit-form', compact('siswa'));
        } catch (\Exception $e) {
            Log::error('Error showing edit siswa form from guru', [
                'error' => $e->getMessage(),
                'user_id' => $id
            ]);

            return back()->with('error', 'Siswa tidak ditemukan');
        }
    }

    /**
     * Update Siswa - Update data siswa (sama logic dengan koordinator)
     */
    public function updateSiswa(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role !== 'siswa') {
                abort(403, 'User bukan siswa');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'nis' => 'required|string|unique:students,nis,' . ($user->student?->id ?? 'NULL'),
                'alamat' => 'required|string',
                'kelas' => 'required|string',
                'tgl_lahir' => 'required|date',
                'no_hp' => 'required|string',
                'password' => 'nullable|min:8|confirmed',
            ]);

            // Update user (hanya kolom yang ada di users table)
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['no_hp'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Update student record
            $siswa = Student::where('user_id', $user->id)->first();
            
            Log::info('Student update from guru - before', [
                'user_id' => $user->id,
                'siswa_exists' => $siswa ? true : false,
                'siswa_id' => $siswa->id ?? null,
                'validated_nis' => $validated['nis'],
                'validated_kelas' => $validated['kelas'],
                'updated_by' => Auth::id(),
            ]);
            
            if ($siswa) {
                $updateResult = $siswa->update([
                    'nama_lengkap' => $validated['name'],
                    'nis' => $validated['nis'],
                    'tgl_lahir' => $validated['tgl_lahir'],
                    'alamat' => $validated['alamat'],
                    'no_hp' => $validated['no_hp'],
                    'kelas' => $validated['kelas'],
                ]);
                
                Log::info('Student updated from guru - after', [
                    'user_id' => $user->id,
                    'update_result' => $updateResult,
                    'nis_in_db' => $siswa->fresh()->nis,
                    'kelas_in_db' => $siswa->fresh()->kelas,
                    'updated_by' => Auth::id(),
                ]);
            } else {
                // Create student record if it doesn't exist
                $createResult = Student::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $validated['name'],
                    'nis' => $validated['nis'],
                    'tgl_lahir' => $validated['tgl_lahir'],
                    'alamat' => $validated['alamat'],
                    'no_hp' => $validated['no_hp'],
                    'kelas' => $validated['kelas'],
                ]);
                
                Log::info('Student created from guru - new', [
                    'user_id' => $user->id,
                    'created_id' => $createResult->id,
                    'nis_in_db' => $createResult->nis,
                    'kelas_in_db' => $createResult->kelas,
                    'created_by' => Auth::id(),
                ]);
            }

            Log::info('Student updated from guru', [
                'user_id' => $user->id,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('guru.siswa')
                ->with('success', 'Siswa berhasil diupdate');

        } catch (\Exception $e) {
            Log::error('Error updating student from guru', [
                'error' => $e->getMessage(),
                'user_id' => $id,
                'updated_by' => Auth::id(),
            ]);

            return back()->withInput()
                ->with('error', 'Gagal mengupdate siswa: ' . $e->getMessage());
        }
    }

    // Catatan Konseling - IMPROVED: Show all selesai janji, not just those with catatan
    public function daftarCatatan()
    {
        \Log::info('=== GURU RIWAYAT KONSELING ===');
        \Log::info('Guru name: ' . Auth::user()->name);
        
        // Query: Get all janji with status 'selesai', with latest catatan if exists
        $catatanFiltered = DB::table('janji_konselings as j')
            ->join('users', 'j.user_id', '=', 'users.id')
            ->leftJoin('catatan as c', function ($join) {
                $join->on('j.id', '=', 'c.janji_id')
                     ->whereRaw('c.id = (SELECT MAX(id) FROM catatan WHERE janji_id = j.id)');
            })
            ->select(
                'j.id as janji_id',
                'c.id as catatan_id',
                'j.tanggal',
                'c.tanggal as catatan_tanggal',
                'c.created_at as catatan_created_at',
                'j.user_id',
                'j.jenis_bimbingan',
                'j.status',
                'users.name as nama_siswa'
            )
            ->where('j.status', 'selesai')
            ->where('j.guru_bk', Auth::user()->name)
            ->orderBy('j.tanggal', 'desc')
            ->orderBy('j.updated_at', 'desc')
            ->paginate(20);

        \Log::info('Janji selesai count: ' . $catatanFiltered->total());
        
        return view('guru.riwayat.index', compact('catatanFiltered'));
    }

    // public function buatCatatan()
    // {
    //     return view('guru.riwayat.buat');
    // }

    public function templateCatatan()
    {
        return view('guru.riwayat.template');
    }

    // Laporan & Statistik
    public function laporan()
    {
        $guruName = Auth::user()->name;
        
        $stats = [
            'total_konseling' => DB::table('janji_konselings')
                ->where('guru_bk', $guruName)
                ->count(),
            'konseling_selesai' => DB::table('janji_konselings')
                ->where('guru_bk', $guruName)
                ->where('status', 'selesai')
                ->count(),
            'konseling_pending' => DB::table('janji_konselings')
                ->where('guru_bk', $guruName)
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->count(),
            'konseling_bulan_ini' => DB::table('janji_konselings')
                ->where('guru_bk', $guruName)
                ->whereMonth('tanggal', \Carbon\Carbon::now()->month)
                ->whereYear('tanggal', \Carbon\Carbon::now()->year)
                ->count(),
        ];

        $dataPerJenis = DB::table('janji_konselings')
            ->where('guru_bk', $guruName)
            ->select('jenis_bimbingan', DB::raw('count(*) as total'))
            ->groupBy('jenis_bimbingan')
            ->get();

        return view('guru.laporan', compact('stats', 'dataPerJenis'));
    }

    public function statistik()
    {
        // Data untuk grafik per bulan
        $perBulan = DB::table('janji_konselings')
            ->select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('tanggal', \Carbon\Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Data untuk grafik per status
        $perStatus = DB::table('janji_konselings')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        return view('guru.statistik', compact('perBulan', 'perStatus'));
    }

    // Export laporan ke PDF
    public function exportPdf(Request $request)
    {
        try {
            $periode = $request->input('periode', 'bulan');
            $from = $request->input('from');
            $to = $request->input('to');

            // Inisialisasi query - FILTER BERDASARKAN GURU YG LOGIN
            $query = DB::table('janji_konselings')
                ->where('guru_bk', Auth::user()->name);

            // Tentukan rentang tanggal berdasarkan periode
            $now = \Carbon\Carbon::now();
            
            if ($periode === 'custom' && $from && $to) {
                $query->whereBetween('tanggal', [$from, $to]);
                $periode_label = "Dari " . date('d M Y', strtotime($from)) . " hingga " . date('d M Y', strtotime($to));
            } elseif ($periode === 'minggu') {
                $query->whereBetween('tanggal', [
                    $now->copy()->startOfWeek(),
                    $now->copy()->endOfWeek()
                ]);
                $periode_label = "Minggu " . date('d M Y', strtotime($now->startOfWeek())) . " - " . date('d M Y', strtotime($now->endOfWeek()));
            } elseif ($periode === 'bulan') {
                $query->whereMonth('tanggal', $now->month)
                    ->whereYear('tanggal', $now->year);
                $periode_label = $this->getNamaBulan($now->month) . " " . $now->year;
            } elseif ($periode === 'tahun') {
                $query->whereYear('tanggal', $now->year);
                $periode_label = "Tahun " . $now->year;
            } else {
                $periode_label = "Laporan Konseling";
            }

            // Data untuk laporan
            $data = [
                'periode' => $periode_label,
                'tanggal_generate' => date('d F Y H:i:s'),
                'guru_bk' => Auth::user()->name,
                
                'total_konseling' => (clone $query)->count(),
                'konseling_selesai' => (clone $query)->where('status', 'selesai')->count(),
                'konseling_pending' => (clone $query)->whereIn('status', ['menunggu', 'dikonfirmasi'])->count(),
                
                'data_per_jenis' => (clone $query)
                    ->select('jenis_bimbingan', DB::raw('COUNT(*) as total'))
                    ->groupBy('jenis_bimbingan')
                    ->get(),
                    
                'detail_konseling' => (clone $query)
                    ->join('users', 'janji_konselings.user_id', '=', 'users.id')
                    ->select('janji_konselings.*', 'users.name as siswa_name')
                    ->orderBy('janji_konselings.tanggal', 'desc')
                    ->limit(50)
                    ->get(),
            ];

            // Generate PDF
            $pdf = Pdf::loadView('guru.laporan-pdf', $data);

            return $pdf->download('Laporan-Konseling-' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Helper function untuk nama bulan
    private function getNamaBulan($bulan)
    {
        $bulanIndonesia = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulanIndonesia[$bulan] ?? '';
    }

      // EDIT KELAS SISWA - FORM
    public function editKelas($id)
{
    $siswa = User::where('id', $id)->where('role', 'siswa')->first();
    
    if (!$siswa) {
        abort(404, 'Siswa tidak ditemukan');
    }

    // Daftar kelas yang tersedia (diperbaiki)
    $kelasList = [
        'X RPL', 'X TKR', 'X TITL', 'X TPM',
        'XI RPL', 'XI TKR', 'XI TITL', 'XI TPM',
        'XII RPL', 'XII TKR', 'XII TITL', 'XII TPM'
    ];

    return view('guru.siswa-edit-kelas', compact('siswa', 'kelasList'));
}

    // UPDATE KELAS SISWA
    public function updateKelas(Request $request, $id)
    {
        $request->validate([
            'class' => 'required|string|max:50'
        ]);

        $siswa = User::where('id', $id)->where('role', 'siswa')->first();
        
        if (!$siswa) {
            abort(404, 'Siswa tidak ditemukan');
        }

        // Update kelas siswa
        DB::table('users')
            ->where('id', $id)
            ->update([
                'class' => $request->class,
                'updated_at' => now()
            ]);

        return redirect()->route('guru.siswa.detail', $id)
            ->with('success', 'Kelas siswa berhasil diperbarui.');
    }


    // RIWAYAT SISWA LENGKAP - IMPROVED: Show all janji tidak hanya yang punya catatan
    public function riwayatSiswa($id)
    {
        $siswa = User::where('id', $id)->where('role', 'siswa')->first();
        
        if (!$siswa) {
            abort(404, 'Siswa tidak ditemukan');
        }

        \Log::info('=== RIWAYAT SISWA ===', [
            'siswa_id' => $id,
            'siswa_name' => $siswa->name,
            'guru_name' => Auth::user()->name
        ]);

        // Show all janji for this siswa, with optional catatan
        $riwayatKonseling = DB::table('janji_konselings as j')
            ->leftJoin('catatan as c', function ($join) {
                $join->on('j.id', '=', 'c.janji_id')
                     ->whereRaw('c.id = (SELECT MAX(id) FROM catatan WHERE janji_id = j.id)');
            })
            ->select(
                'j.id as janji_id',
                'c.id as catatan_id',
                'j.tanggal',
                'j.waktu',
                'j.jenis_bimbingan',
                'j.keluhan',
                'j.status',
                'j.guru_bk',
                'c.tanggal as catatan_tanggal',
                'c.created_at as catatan_created_at'
            )
            ->where('j.user_id', $id)
            ->where('j.guru_bk', Auth::user()->name)
            ->whereIn('j.status', ['dikonfirmasi', 'selesai'])
            ->orderBy('j.tanggal', 'desc')
            ->orderBy('j.updated_at', 'desc')
            ->paginate(20);

        \Log::info('Riwayat siswa count: ' . $riwayatKonseling->total());

        return view('guru.siswa-riwayat', compact('siswa', 'riwayatKonseling'));
    }

    // FORM BUAT CATATAN
    public function buatCatatan()
    {
        try {
            // Dapatkan siswa yang memiliki konseling dengan guru ini
            $siswas = DB::table('users')
                ->where('role', 'siswa')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('janji_konselings')
                        ->whereColumn('janji_konselings.user_id', 'users.id')
                        ->where('janji_konselings.guru_bk', Auth::user()->name)
                        ->where('status', 'selesai');
                })
                ->orderBy('name')
                ->get();

            return view('guru.riwayat-create', compact('siswas'));
        } catch (\Exception $e) {
            Log::error('Error loading create catatan form', [
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Gagal memuat form catatan');
        }
    }

    // SIMPAN CATATAN
    public function simpanCatatan(Request $request)
    {
        $request->validate([
            'janji_id' => 'required|exists:janji_konselings,id',
            'isi_catatan' => 'required|string|min:10',
            'rekomendasi' => 'nullable|string',
        ]);

        try {
            // Get janji data to get user_id
            $janji = DB::table('janji_konselings')->where('id', $request->janji_id)->first();
            
            if (!$janji) {
                return back()->with('error', 'Janji konseling tidak ditemukan.');
            }

            // Combine isi_catatan and rekomendasi into one field
            $isi_lengkap = $request->isi_catatan;
            if ($request->rekomendasi) {
                $isi_lengkap .= "\n\n--- REKOMENDASI ---\n" . $request->rekomendasi;
            }

            // Insert catatan
            DB::table('catatan')->insert([
                'user_id' => $janji->user_id,
                'janji_id' => $request->janji_id,
                'tanggal' => now()->format('Y-m-d'),
                'isi' => $isi_lengkap,
                'guru_bk' => Auth::user()->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update status janji menjadi 'selesai'
            DB::table('janji_konselings')
                ->where('id', $request->janji_id)
                ->update([
                    'status' => 'selesai',
                    'updated_at' => now()
                ]);

            return redirect()->route('guru.riwayat.index')->with('success', 'Catatan konseling berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error saving catatan', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            return back()->withInput()->with('error', 'Gagal menyimpan catatan: ' . $e->getMessage());
        }
    }

    // DETAIL CATATAN - DIPERBAIKI
    public function detailCatatan($id)
    {
        try {
            // Coba cari di tabel catatan
            $catatan = DB::table('catatan')
                ->join('users', 'catatan.user_id', '=', 'users.id')
                ->select('catatan.*', 'users.name as nama_siswa', 'users.email')
                ->where('catatan.id', $id)
                ->where('catatan.guru_bk', Auth::user()->name)
                ->first();

            if ($catatan) {
                // Jika catatan ditemukan di tabel catatan
                $result = (object) [];
                
                // Salin semua field dari catatan
                foreach (get_object_vars($catatan) as $k => $v) {
                    $result->{$k} = $v;
                }

                // Cari janji terkait jika ada janji_id
                if ($catatan->janji_id) {
                    $janji = DB::table('janji_konselings')
                        ->where('id', $catatan->janji_id)
                        ->first();
                } else {
                    // Cari janji berdasarkan user_id dan tanggal
                    $janji = DB::table('janji_konselings')
                        ->where('user_id', $catatan->user_id)
                        ->whereDate('tanggal', $catatan->tanggal)
                        ->orderBy('created_at', 'desc')
                        ->first();
                }

                // Map janji fields jika ditemukan
                if ($janji) {
                    $result->waktu = $janji->waktu ?? null;
                    $result->jenis_bimbingan = $janji->jenis_bimbingan ?? null;
                    $result->keluhan = $janji->keluhan ?? null;
                    $result->status = $janji->status ?? 'selesai';
                } else {
                    // Jika tidak ada janji, isi dengan fallback
                    $result->waktu = null;
                    $result->jenis_bimbingan = null;
                    $result->keluhan = null;
                    $result->status = 'selesai';
                }

                return view('guru.riwayat.detail', ['catatan' => $result]);
            }

            // Jika tidak ditemukan di tabel catatan, cari di tabel janji_konselings
            $catatanJanji = DB::table('janji_konselings')
                ->join('users', 'janji_konselings.user_id', '=', 'users.id')
                ->select(
                    'janji_konselings.*',
                    'users.name as nama_siswa',
                    'users.email'
                )
                ->where('janji_konselings.id', $id)
                ->where('janji_konselings.guru_bk', Auth::user()->name)
                ->first();

            if ($catatanJanji) {
                // Jika data dari janji_konselings, langsung kirim ke view riwayat.detail
                return view('guru.riwayat.detail', ['catatan' => $catatanJanji]);
            }

            abort(404, 'Catatan tidak ditemukan');

        } catch (\Exception $e) {
            Log::error('Error loading detail catatan', [
                'error' => $e->getMessage(),
                'catatan_id' => $id
            ]);
            return back()->with('error', 'Gagal memuat detail catatan');
        }
    }

    // Helper untuk mapping jenis_bimbingan ke label yang digunakan di view
    private function mapJenisBimbinganLabel($jenis)
    {
        if (!$jenis) return 'Umum';

        $map = [
            'belajar' => 'Akademik',
            'karir' => 'Karir',
            'pribadi' => 'Personal',
            'sosial' => 'Sosial',
        ];

        return $map[$jenis] ?? ucfirst($jenis);
    }

    /**
     * EDIT CATATAN - DIPERBAIKI
     */
    public function editCatatan($id)
    {
        try {
            $catatan = DB::table('catatan')
                ->join('users', 'catatan.user_id', '=', 'users.id')
                ->select('catatan.*', 'users.name as nama_siswa')
                ->where('catatan.id', $id)
                ->where('catatan.guru_bk', Auth::user()->name)
                ->first();

            if (!$catatan) {
                abort(404, 'Catatan tidak ditemukan');
            }

            return view('guru.riwayat.edit', compact('catatan'));
        } catch (\Exception $e) {
            Log::error('Error loading catatan for edit', [
                'error' => $e->getMessage(),
                'catatan_id' => $id
            ]);

            return back()->with('error', 'Gagal memuat catatan: ' . $e->getMessage());
        }
    }

    /**
     * UPDATE CATATAN - DIPERBAIKI
     */
    public function updateCatatan(Request $request, $id)
    {
        try {
            $request->validate([
                'isi_catatan' => 'required|string|min:10',
                'rekomendasi' => 'nullable|string',
                'tanggal' => 'required|date'
            ]);

            // Cek apakah catatan milik guru yang login
            $catatan = DB::table('catatan')
                ->where('id', $id)
                ->where('guru_bk', Auth::user()->name)
                ->first();

            if (!$catatan) {
                abort(404, 'Catatan tidak ditemukan');
            }

            // Gabung isi_catatan dan rekomendasi dengan separator
            $isiCombined = $request->isi_catatan;
            if ($request->rekomendasi) {
                $isiCombined .= "\n--- REKOMENDASI ---\n" . $request->rekomendasi;
            }

            DB::table('catatan')
                ->where('id', $id)
                ->update([
                    'isi' => $isiCombined,
                    'tanggal' => $request->tanggal,
                    'updated_at' => now()
                ]);

            Log::info('Catatan updated', [
                'catatan_id' => $id,
                'guru_id' => Auth::id()
            ]);

            return redirect()->route('guru.riwayat.index')
                ->with('success', 'Catatan berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating catatan', [
                'error' => $e->getMessage(),
                'catatan_id' => $id
            ]);

            return back()->withInput()
                ->with('error', 'Gagal mengupdate catatan: ' . $e->getMessage());
        }
    }

    /**
     * HAPUS CATATAN - DIPERBAIKI
     */
    public function hapusCatatan($id)
    {
        try {
            // Cek apakah catatan milik guru yang login
            $catatan = DB::table('catatan')
                ->where('id', $id)
                ->where('guru_bk', Auth::user()->name)
                ->first();

            if (!$catatan) {
                abort(404, 'Catatan tidak ditemukan');
            }

            DB::table('catatan')->where('id', $id)->delete();

            Log::info('Catatan deleted', [
                'catatan_id' => $id,
                'guru_id' => Auth::id()
            ]);

            return redirect()->route('guru.riwayat.index')
                ->with('success', 'Catatan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting catatan', [
                'error' => $e->getMessage(),
                'catatan_id' => $id
            ]);

            return back()->with('error', 'Gagal menghapus catatan: ' . $e->getMessage());
        }
    }

    /**
     * Daftar Guru BK - DIPERBAIKI (tambah filter guru yang bukan diri sendiri)
     */
    public function daftarGuru(Request $request)
    {
        try {
            // Hanya koordinator BK yang bisa mengakses fitur ini
            if (Auth::user()->role !== 'koordinator_bk' && Auth::user()->role !== 'koordinator') {
                abort(403, 'Anda tidak memiliki akses ke fitur ini. Hanya koordinator BK yang dapat mengelola daftar guru.');
            }

            $query = DB::table('users')
                ->whereIn('role', ['guru_bk', 'guru'])
                ->where('id', '!=', Auth::id()) // Exclude current user
                ->select('id', 'name', 'email', 'role', 'phone', 'created_at');

            // Filter pencarian berdasarkan nama atau email
            if ($search = $request->input('search')) {
                $search = trim($search);
                if (!empty($search)) {
                    $query->where(function($q) use ($search) {
                        $q->where('users.name', 'like', '%' . $search . '%')
                          ->orWhere('users.email', 'like', '%' . $search . '%')
                          ->orWhere('users.phone', 'like', '%' . $search . '%');
                    });
                }
            }

            $daftarGuru = $query->orderBy('created_at', 'desc')
                ->paginate(20)
                ->withQueryString();

            return view('guru.daftar-guru', compact('daftarGuru'));
        } catch (\Exception $e) {
            Log::error('Error loading daftar guru', [
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Gagal memuat daftar guru');
        }
    }

    /**
     * Detail Guru BK - DIPERBAIKI
     */
    public function detailGuru($id)
    {
        try {
            // Pastikan bukan melihat profil sendiri
            if ($id == Auth::id()) {
                return redirect()->route('guru.editProfile');
            }

            $guru = User::where('id', $id)
                ->whereIn('role', ['guru_bk', 'guru'])
                ->firstOrFail();

            // Ambil data counselor jika ada
            $counselor = Counselor::where('user_id', $id)->first();

            return view('guru.detail-guru', compact('guru', 'counselor'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('guru.guru')
                ->with('error', 'Guru tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error loading detail guru', [
                'error' => $e->getMessage(),
                'guru_id' => $id
            ]);
            return back()->with('error', 'Gagal memuat detail guru');
        }
    }

    /**
     * PROFIL GURU BK - EDIT PROFIL SENDIRI
     * Guru BK dapat mengedit profil mereka sendiri
     */
    public function editProfile()
    {
        try {
            $user = Auth::user();
            
            // Ambil data counselor yang terkait
            $counselor = Counselor::where('user_id', $user->id)->first();
            
            return view('guru.profile-edit', compact('user', 'counselor'));
        } catch (\Exception $e) {
            Log::error('Error loading guru profile edit form', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return back()->with('error', 'Gagal memuat form edit profil');
        }
    }
    
    /**
     * UPDATE PROFIL GURU BK
     * Update data guru BK dengan sinkronisasi otomatis ke User dan Counselor table
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = User::findOrFail(Auth::id());
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone' => 'required|string|unique:users,phone,' . $user->id,
                'nip' => 'nullable|string',
                'specialization' => 'nullable|string',
                'office_hours' => 'nullable|string',
                'password' => 'nullable|min:8|confirmed',
            ]);
            
            // Update user data
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ];
            
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }
            
            // Update User
            $user->update($updateData);
            
            // Update atau buat Counselor record
            $counselor = Counselor::where('user_id', $user->id)->first();
            
            if ($counselor) {
                $counselor->update([
                    'nama_lengkap' => $validated['name'],
                    'nip' => $validated['nip'] ?? $counselor->nip,
                    'no_hp' => $validated['phone'],
                    'specialization' => $validated['specialization'] ?? $counselor->specialization,
                    'office_hours' => $validated['office_hours'] ?? $counselor->office_hours,
                ]);
            } else {
                // Buat record counselor baru jika belum ada
                Counselor::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $validated['name'],
                    'nip' => $validated['nip'] ?? null,
                    'no_hp' => $validated['phone'],
                    'specialization' => $validated['specialization'] ?? null,
                    'office_hours' => $validated['office_hours'] ?? null,
                ]);
            }
            
            Log::info('Guru BK profile updated by self', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($validated)
            ]);
            
            return redirect()->route('guru.editProfile')
                ->with('success', 'Profil Anda berhasil diperbarui');
                
        } catch (\Exception $e) {
            Log::error('Error updating guru profile', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return back()->withInput()
                ->with('error', 'Gagal mengupdate profil: ' . $e->getMessage());
        }
    }

    /**
     * Simpan Jadwal Konseling untuk Siswa Tertentu
     */
    public function simpanJadwalForSiswa(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'mulai' => 'required',
            'selesai' => 'nullable',
            'jenis_bimbingan' => 'required',
            'keluhan' => 'nullable|string',
        ]);

        try {
            // Format waktu: "mulai - selesai"
            $waktu = $request->mulai;
            if ($request->selesai) {
                $waktu .= ' - ' . $request->selesai;
            }

            DB::table('janji_konselings')->insert([
                'user_id' => $id,
                'tanggal' => $request->tanggal,
                'waktu' => $waktu,
                'jenis_bimbingan' => $request->jenis_bimbingan,
                'keluhan' => $request->keluhan ?? '',
                'guru_bk' => Auth::user()->name,
                'status' => 'dikonfirmasi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('guru.siswa.riwayat', $id)
                ->with('success', 'Jadwal konseling berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error saving jadwal for siswa', [
                'error' => $e->getMessage(),
                'siswa_id' => $id,
                'guru_id' => Auth::id()
            ]);

            return back()->withInput()
                ->with('error', 'Gagal menyimpan jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Catat Data Siswa - Form untuk merekam perilaku/riwayat siswa
     */
    public function catatDataSiswaForm($studentId)
    {
        try {
            $student = Student::with('user')->findOrFail($studentId);
            
            return view('guru.catat-data-siswa', compact('student'));
        } catch (\Exception $e) {
            Log::error('Error loading catat data siswa form', [
                'error' => $e->getMessage(),
                'student_id' => $studentId
            ]);
            return back()->with('error', 'Siswa tidak ditemukan');
        }
    }

    /**
     * Simpan data siswa (perilaku/riwayat)
     */
    public function simpanDataSiswa(Request $request, $studentId)
    {
        try {
            $student = Student::findOrFail($studentId);

            $request->validate([
                'kategori' => 'required|in:akademik,perilaku,kesehatan,sosial,kehadiran,lainnya',
                'deskripsi' => 'required|string|min:10',
                'tanggal_kejadian' => 'required|date',
                'status' => 'required|in:aktif,resolved,monitoring',
            ]);

            \App\Models\StudentBehavior::create([
                'student_id' => $student->id,
                'recorded_by' => Auth::id(),
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'tanggal_kejadian' => $request->tanggal_kejadian,
                'status' => $request->status,
            ]);

            Log::info('Student behavior recorded', [
                'student_id' => $student->id,
                'recorded_by' => Auth::id(),
                'kategori' => $request->kategori
            ]);

            return redirect()->route('guru.siswa.detail', $student->user_id)
                ->with('success', 'Data siswa berhasil dicatat');
        } catch (\Exception $e) {
            Log::error('Error saving student data', [
                'error' => $e->getMessage(),
                'student_id' => $studentId
            ]);
            return back()->withInput()->with('error', 'Gagal menyimpan data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Lihat riwayat/perilaku siswa
     */
    public function lihatRiwayatSiswa($studentId)
    {
        try {
            $student = Student::with('user', 'behaviors', 'identity')->findOrFail($studentId);
            
            $behaviors = \App\Models\StudentBehavior::where('student_id', $student->id)
                ->with('recordedBy')
                ->latest()
                ->paginate(10);

            return view('guru.riwayat-siswa', compact('student', 'behaviors'));
        } catch (\Exception $e) {
            Log::error('Error loading student behaviors', [
                'error' => $e->getMessage(),
                'student_id' => $studentId
            ]);
            return back()->with('error', 'Gagal memuat riwayat siswa');
        }
    }

    /**
     * Edit data siswa (perilaku)
     */
    public function editRiwayatSiswa($behaviorId)
    {
        try {
            $behavior = \App\Models\StudentBehavior::findOrFail($behaviorId);
            
            // Verifikasi guru yang mencatat
            if ($behavior->recorded_by !== Auth::id()) {
                return abort(403, 'Unauthorized');
            }

            return view('guru.edit-riwayat-siswa', compact('behavior'));
        } catch (\Exception $e) {
            Log::error('Error loading behavior edit form', [
                'error' => $e->getMessage(),
                'behavior_id' => $behaviorId
            ]);
            return back()->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Update riwayat siswa
     */
    public function updateRiwayatSiswa(Request $request, $behaviorId)
    {
        try {
            $behavior = \App\Models\StudentBehavior::findOrFail($behaviorId);
            
            if ($behavior->recorded_by !== Auth::id()) {
                return abort(403, 'Unauthorized');
            }

            $request->validate([
                'kategori' => 'required|in:akademik,perilaku,kesehatan,sosial,kehadiran,lainnya',
                'deskripsi' => 'required|string|min:10',
                'tanggal_kejadian' => 'required|date',
                'status' => 'required|in:aktif,resolved,monitoring',
            ]);

            $behavior->update([
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'tanggal_kejadian' => $request->tanggal_kejadian,
                'status' => $request->status,
            ]);

            Log::info('Student behavior updated', [
                'behavior_id' => $behaviorId,
                'student_id' => $behavior->student_id,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('guru.siswa.riwayat', $behavior->student_id)
                ->with('success', 'Data siswa berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating student behavior', [
                'error' => $e->getMessage(),
                'behavior_id' => $behaviorId
            ]);
            return back()->withInput()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus riwayat siswa
     */
    public function hapusRiwayatSiswa($behaviorId)
    {
        try {
            $behavior = \App\Models\StudentBehavior::findOrFail($behaviorId);
            
            if ($behavior->recorded_by !== Auth::id()) {
                return abort(403, 'Unauthorized');
            }

            $studentId = $behavior->student_id;
            $behavior->delete();

            Log::info('Student behavior deleted', [
                'behavior_id' => $behaviorId,
                'student_id' => $studentId,
                'deleted_by' => Auth::id()
            ]);

            return redirect()->route('guru.siswa.riwayat', $studentId)
                ->with('success', 'Data siswa berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting student behavior', [
                'error' => $e->getMessage(),
                'behavior_id' => $behaviorId
            ]);
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

};