<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\WaliKelasNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class WaliKelasController extends Controller
{
    /**
     * Dashboard Wali Kelas
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get siswa yang dipimpin wali kelas ini
        $siswa = Student::where('wali_kelas_id', $user->id)
            ->with('user', 'waliKelasNotes')
            ->get();

        $stats = [
            'total_siswa' => $siswa->count(),
            'siswa_aktif' => $siswa->where('user.email_verified_at', '!=', null)->count(),
            'total_catatan' => WaliKelasNote::where('wali_kelas_id', $user->id)->count(),
        ];

        return view('wali_kelas.dashboard', compact('siswa', 'stats'));
    }

    /**
     * List semua siswa di kelas wali
     */
    public function daftarSiswa()
    {
        $user = Auth::user();
        $query = Student::where('wali_kelas_id', $user->id)
            ->with('user', 'waliKelasNotes');
        
        // Apply search filter if provided
        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        
        // Sort by nomor_absen
        $siswa = $query->orderBy('nomor_absen', 'asc')->paginate(15);

        return view('wali_kelas.daftar-siswa', compact('siswa'));
    }

    /**
     * Detail siswa
     */
    public function detailSiswa($studentId)
    {
        $user = Auth::user();
        $student = Student::with('user', 'waliKelasNotes', 'behaviors', 'identity')
            ->findOrFail($studentId);

        // Verifikasi wali kelas
        if ($student->wali_kelas_id !== $user->id) {
            return abort(403, 'Unauthorized');
        }

        $catatanWali = WaliKelasNote::where('student_id', $student->id)
            ->where('wali_kelas_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('wali_kelas.detail-siswa', compact('student', 'catatanWali'));
    }

    /**
     * Tambah siswa ke kelas wali
     */
    public function tambahSiswa()
    {
        $user = Auth::user();
        $siswaAvailable = Student::whereNull('wali_kelas_id')
            ->with('user')
            ->get();

        return view('wali_kelas.tambah-siswa', compact('siswaAvailable'));
    }

    /**
     * Store tambah siswa
     */
    public function storeTambahSiswa(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        
        // Verify tidak sudah punya wali kelas lain
        if ($student->wali_kelas_id !== null) {
            return back()->withErrors(['student_id' => 'Siswa sudah memiliki wali kelas']);
        }

        $student->wali_kelas_id = $user->id;
        $student->save();

        Log::info('Student assigned to wali kelas', [
            'wali_kelas_id' => $user->id,
            'student_id' => $student->id
        ]);

        return redirect()->route('wali_kelas.daftar-siswa')
            ->with('success', 'Siswa berhasil ditambahkan ke kelas Anda');
    }

    /**
     * Tambah catatan untuk siswa
     */
    public function tambahCatatan(Request $request, $studentId)
    {
        $user = Auth::user();
        $student = Student::findOrFail($studentId);

        // Verifikasi wali kelas
        if ($student->wali_kelas_id !== $user->id) {
            return abort(403, 'Unauthorized');
        }

        $request->validate([
            'catatan' => 'required|string',
            'tanggal_catatan' => 'required|date',
            'tipe_catatan' => 'required|in:perkembangan,prestasi,masalah,konsultasi',
        ]);

        WaliKelasNote::create([
            'student_id' => $student->id,
            'wali_kelas_id' => $user->id,
            'catatan' => $request->catatan,
            'tanggal_catatan' => $request->tanggal_catatan,
            'tipe_catatan' => $request->tipe_catatan,
        ]);

        Log::info('Wali kelas note created', [
            'wali_kelas_id' => $user->id,
            'student_id' => $student->id
        ]);

        return back()->with('success', 'Catatan berhasil ditambahkan');
    }

    /**
     * Edit catatan
     */
    public function editCatatan(Request $request, $catatanId)
    {
        $user = Auth::user();
        $catatan = WaliKelasNote::findOrFail($catatanId);

        // Verifikasi wali kelas
        if ($catatan->wali_kelas_id !== $user->id) {
            return abort(403, 'Unauthorized');
        }

        $request->validate([
            'catatan' => 'required|string',
            'tanggal_catatan' => 'required|date',
            'tipe_catatan' => 'required|in:perkembangan,prestasi,masalah,konsultasi',
        ]);

        $catatan->update([
            'catatan' => $request->catatan,
            'tanggal_catatan' => $request->tanggal_catatan,
            'tipe_catatan' => $request->tipe_catatan,
        ]);

        Log::info('Wali kelas note updated', [
            'wali_kelas_id' => $user->id,
            'catatan_id' => $catatanId
        ]);

        return back()->with('success', 'Catatan berhasil diupdate');
    }

    /**
     * Hapus catatan
     */
    public function hapusCatatan($catatanId)
    {
        $user = Auth::user();
        $catatan = WaliKelasNote::findOrFail($catatanId);

        // Verifikasi wali kelas
        if ($catatan->wali_kelas_id !== $user->id) {
            return abort(403, 'Unauthorized');
        }

        $catatan->delete();

        Log::info('Wali kelas note deleted', [
            'wali_kelas_id' => $user->id,
            'catatan_id' => $catatanId
        ]);

        return back()->with('success', 'Catatan berhasil dihapus');
    }

    /**
     * Form tambah siswa baru
     */
    public function createSiswa()
    {
        return view('wali_kelas.tambah-siswa-baru');
    }

    /**
     * Store siswa baru
     */
    public function storeSiswaBaru(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis',
            'tgl_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'kelas' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $userData = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
                'phone' => $request->no_hp,
                'class' => $request->kelas,
                'email_verified_at' => now(), // Auto-verify email
            ]);

            // Create student
            $student = Student::create([
                'user_id' => $userData->id,
                'nama_lengkap' => $request->nama_lengkap,
                'nis' => $request->nis,
                'tgl_lahir' => $request->tgl_lahir,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'kelas' => $request->kelas,
                'wali_kelas_id' => $user->id,
            ]);

            // Create student identity
            \App\Models\StudentIdentity::create([
                'student_id' => $student->id,
                'tempat_lahir' => $request->tempat_lahir,
            ]);

            DB::commit();

            Log::info('New student created by wali kelas', [
                'wali_kelas_id' => $user->id,
                'student_id' => $student->id,
                'user_id' => $userData->id,
            ]);

            return redirect()->route('wali_kelas.daftar-siswa')
                ->with('success', 'Siswa baru berhasil ditambahkan. Siswa dapat login dengan email yang terdaftar.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating new student', [
                'wali_kelas_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menambahkan siswa: ' . $e->getMessage()]);
        }
    }

    /**
     * Kelola data diri siswa
     */
    public function kelolaDataDiri($studentId)
    {
        $user = Auth::user();
        $student = Student::with('user', 'identity')->findOrFail($studentId);

        // Verifikasi wali kelas
        if ($student->wali_kelas_id !== $user->id) {
            return abort(403, 'Unauthorized');
        }

        return view('wali_kelas.kelola-data-diri', compact('student'));
    }

    /**
     * Update data diri siswa
     */
    public function updateDataDiri(Request $request, $studentId)
    {
        $user = Auth::user();
        $student = Student::findOrFail($studentId);

        // Verifikasi wali kelas
        if ($student->wali_kelas_id !== $user->id) {
            return abort(403, 'Unauthorized');
        }

        $request->validate([
            'nama_lengkap' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'kelas' => 'nullable|string',
            'no_induk' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'nama_ayah' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'pekerjaan_ayah' => 'nullable|string',
            'pekerjaan_ibu' => 'nullable|string',
            'catatan_khusus' => 'nullable|string',
        ]);

        // Update student
        $student->update($request->only([
            'nama_lengkap', 'tgl_lahir', 'alamat', 'no_hp', 'kelas'
        ]));

        // Update atau create student identity
        $identity = $student->identity ?? new \App\Models\StudentIdentity();
        $identity->student_id = $student->id;
        $identity->fill($request->only([
            'no_induk', 'tempat_lahir', 'nama_ayah', 'nama_ibu',
            'pekerjaan_ayah', 'pekerjaan_ibu', 'catatan_khusus'
        ]));
        $identity->save();

        Log::info('Student data updated by wali kelas', [
            'wali_kelas_id' => $user->id,
            'student_id' => $student->id
        ]);

        return back()->with('success', 'Data siswa berhasil diupdate');
    }
}
