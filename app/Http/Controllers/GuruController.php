<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Catatan;

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

        return redirect()->route('guru.jadwal')->with('success', 'Jadwal berhasil diperbarui');
    }

    // HAPUS JADWAL
    public function hapusJadwal($id)
    {
        $jadwal = DB::table('janji_konselings')->where('id', $id)->first();

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan');
        }

        DB::table('janji_konselings')->where('id', $id)->delete();

        return redirect()->route('guru.jadwal')->with('success', 'Jadwal berhasil dihapus');
    }

    // FORM TAMBAH CATATAN
    public function tambahCatatanForm($id)
    {
        $jadwal = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name as nama_siswa')
            ->where('janji_konselings.id', $id)
            ->first();

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan');
        }

        return view('guru.catatan-tambah', compact('jadwal'));
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
        DB::table('janji_konselings')
            ->where('id', $id)
            ->update([
                'status' => 'dikonfirmasi',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Janji konseling berhasil dikonfirmasi');
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

        return redirect()->back()->with('success', 'Permintaan berhasil ditolak');
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
        $query = DB::table('users')
            ->where('role', 'siswa')
            ->leftJoin('students', 'students.user_id', '=', 'users.id')
            ->select('users.*', 'students.kelas as kelas');

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

        $siswa = $query->orderBy('users.name', 'asc')->paginate(20)->withQueryString();

        // untuk dropdown kelas — ambil daftar distinct kelas dari tabel students
        $kelasList = DB::table('students')->select('kelas')->whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('guru.siswa', compact('siswa', 'kelasList'));
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

    // Catatan Konseling
    public function daftarCatatan()
    {
        // Data contoh
        $catatan = collect([
            (object)[
                'id' => 1,
                'nama_siswa' => 'Ahmad Rizki',
                'kelas' => 'XII RPL',
                'jenis_konseling' => 'akademik',
                'tanggal' => now()->subDays(2),
                'keluhan' => 'Kesulitan memahami materi matematika',
                'catatan_konselor' => 'Memberikan tips belajar efektif',
                'status' => 'selesai',
            ]
        ]);

        return view('guru.catatan.index', compact('catatan'));
    }

    // public function buatCatatan()
    // {
    //     return view('guru.catatan.buat');
    // }

    public function templateCatatan()
    {
        return view('guru.catatan.template');
    }

    // Laporan & Statistik
    public function laporan()
    {
        $stats = [
            'total_konseling' => DB::table('janji_konselings')->count(),
            'konseling_selesai' => DB::table('janji_konselings')->where('status', 'selesai')->count(),
            'konseling_pending' => DB::table('janji_konselings')->whereIn('status', ['menunggu', 'dikonfirmasi'])->count(),
            'konseling_bulan_ini' => DB::table('janji_konselings')
                ->whereMonth('tanggal', \Carbon\Carbon::now()->month)
                ->count(),
        ];

        $dataPerJenis = DB::table('janji_konselings')
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

    // RIWAYAT SISWA LENGKAP
    public function riwayatSiswa($id)
    {
        $siswa = User::where('id', $id)->where('role', 'siswa')->first();
        
        if (!$siswa) {
            abort(404, 'Siswa tidak ditemukan');
        }

        $riwayatKonseling = DB::table('janji_konselings')
            ->where('user_id', $id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->paginate(20);

        return view('guru.siswa-riwayat', compact('siswa', 'riwayatKonseling'));
    }

    public function buatCatatan()
    {
        $siswaList = User::where('role','siswa')->orderBy('name')->get();
        return view('guru.catatan-create', compact('siswaList'));
    }

    public function simpanCatatan(Request $r)
    {
        $r->validate([
            'user_id' => 'nullable|exists:users,id',
            'tanggal' => 'required|date',
            'isi' => 'required|string',
        ]);

        DB::table('catatan')->insert([
            'user_id' => $r->user_id,
            'tanggal' => $r->tanggal,
            'isi' => $r->isi,
            'guru_bk' => Auth::user()->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('guru.catatan.index')->with('success', 'Catatan berhasil disimpan.');
    }
}