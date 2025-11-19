<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class KoordinatorController extends Controller
{
    /**
     * Dashboard Koordinator BK
     */
    public function dashboard()
    {
        return view('koordinator.dashboard');
    }

    /**
     * Kelola Guru BK
     */
    public function guru()
    {
        $guruBK = DB::table('users')
            ->where('role', 'guru_bk')
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view('koordinator.guru', compact('guruBK'));
    }

    public function tambahGuru()
    {
        return view('koordinator.guru-tambah');
    }

    public function simpanGuru(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:15',
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru_bk',
            'phone' => $request->phone,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('koordinator.guru')->with('success', 'Guru BK berhasil ditambahkan');
    }

    public function editGuru($id)
    {
        $guru = DB::table('users')->where('id', $id)->where('role', 'guru_bk')->first();
        
        if (!$guru) {
            abort(404, 'Guru BK tidak ditemukan');
        }

        return view('koordinator.guru-edit', compact('guru'));
    }

    public function updateGuru(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:15',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        return redirect()->route('koordinator.guru')->with('success', 'Data Guru BK berhasil diupdate');
    }

    public function hapusGuru($id)
    {
        DB::table('users')->where('id', $id)->where('role', 'guru_bk')->delete();

        return redirect()->route('koordinator.guru')->with('success', 'Guru BK berhasil dihapus');
    }

    /**
     * Data Siswa
     */
    public function siswa()
    {
        $siswa = DB::table('users')
            ->where('role', 'siswa')
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view('koordinator.siswa', compact('siswa'));
    }

    public function detailSiswa($id)
    {
        $siswa = DB::table('users')->where('id', $id)->where('role', 'siswa')->first();
        
        if (!$siswa) {
            abort(404, 'Siswa tidak ditemukan');
        }

        $riwayatKonseling = DB::table('janji_konselings')
            ->where('user_id', $id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('koordinator.siswa-detail', compact('siswa', 'riwayatKonseling'));
    }

    /**
     * Laporan
     */
    public function laporan()
    {
        $stats = [
            'total_siswa' => DB::table('users')->where('role', 'siswa')->count(),
            'total_guru' => DB::table('users')->where('role', 'guru_bk')->count(),
            'total_konseling' => DB::table('janji_konselings')->count(),
            'konseling_selesai' => DB::table('janji_konselings')->where('status', 'selesai')->count(),
            'konseling_pending' => DB::table('janji_konselings')->whereIn('status', ['menunggu', 'dikonfirmasi'])->count(),
            'konseling_bulan_ini' => DB::table('janji_konselings')
                ->whereMonth('tanggal', Carbon::now()->month)
                ->count(),
        ];

        $perJenis = DB::table('janji_konselings')
            ->select('jenis_bimbingan', DB::raw('count(*) as total'))
            ->groupBy('jenis_bimbingan')
            ->get();

        $perBulan = DB::table('janji_konselings')
            ->select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('tanggal', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('koordinator.laporan', compact('stats', 'perJenis', 'perBulan'));
    }

    /**
     * Pengaturan
     */
    public function pengaturan()
    {
        return view('koordinator.pengaturan');
    }

    public function updatePengaturan(Request $request)
    {
        // Simpan pengaturan sistem
        // Implementasi sesuai kebutuhan

        return redirect()->route('koordinator.pengaturan')->with('success', 'Pengaturan berhasil disimpan');
    }
}