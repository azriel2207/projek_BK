<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SiswaController extends Controller
{
    /**
     * Dashboard Siswa
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get statistics
        $stats = [
            'janji_menunggu' => DB::table('janji_konselings')
                ->where('user_id', $user->id)
                ->where('status', 'menunggu')
                ->count(),
            
            'janji_hari_ini' => DB::table('janji_konselings')
                ->where('user_id', $user->id)
                ->where('status', 'dikonfirmasi')
                ->whereDate('tanggal', today())
                ->count(),
            
            'total_konseling' => DB::table('janji_konselings')
                ->where('user_id', $user->id)
                ->whereIn('status', ['selesai', 'dikonfirmasi'])
                ->count(),
            
            'konseling_bulan_ini' => DB::table('janji_konselings')
                ->where('user_id', $user->id)
                ->whereBetween('tanggal', [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ])
                ->count(),
            
            'pengingat_konseling' => DB::table('janji_konselings')
                ->where('user_id', $user->id)
                ->where('status', 'menunggu')
                ->count(),
                
            'catatan_baru' => DB::table('catatan')
                ->where('user_id', $user->id)
                ->count(),
        ];
        
        // Get upcoming appointments
        $janjiMendatang = DB::table('janji_konselings')
            ->where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->where('tanggal', '>=', today())
            ->orderBy('tanggal', 'asc')
            ->get();
        
        // Get counseling history
        $riwayatKonseling = DB::table('janji_konselings')
            ->where('user_id', $user->id)
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // Get data per jenis bimbingan
        $totalKonseling = DB::table('janji_konselings')
            ->where('user_id', $user->id)
            ->count();
        
        $dataPerJenis = [];
        if ($totalKonseling > 0) {
            $perJenis = DB::table('janji_konselings')
                ->select('jenis_bimbingan', DB::raw('count(*) as total'))
                ->where('user_id', $user->id)
                ->groupBy('jenis_bimbingan')
                ->get();
            
            foreach ($perJenis as $item) {
                $dataPerJenis[] = [
                    'jenis_bimbingan' => $item->jenis_bimbingan,
                    'total' => $item->total,
                    'persentase' => round(($item->total / $totalKonseling) * 100, 1)
                ];
            }
        }
        
        return view('siswa.dashboard', compact('stats', 'janjiMendatang', 'riwayatKonseling', 'dataPerJenis'));
    }

    /**
     * Riwayat Konseling
     */
    public function riwayatKonseling(Request $request)
    {
        $user = Auth::user();
        
        $query = DB::table('janji_konselings')
            ->where('user_id', $user->id)
            ->whereIn('status', ['dikonfirmasi', 'selesai', 'dibatalkan']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $bulan = $request->bulan; // Format: YYYY-MM
            $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan]);
        }

        $riwayat = $query->orderBy('tanggal', 'desc')->get();

        // Map jenis_bimbingan to text label
        $jenisBimbinganMap = [
            'pribadi' => 'Bimbingan Pribadi',
            'belajar' => 'Bimbingan Belajar',
            'karir' => 'Bimbingan Karir',
            'sosial' => 'Bimbingan Sosial'
        ];

        foreach ($riwayat as $item) {
            $item->jenis_bimbingan_text = $jenisBimbinganMap[$item->jenis_bimbingan] ?? 'Bimbingan Pribadi';
        }

        return view('siswa.riwayat-konseling', compact('riwayat'));
    }

    /**
     * Bimbingan Belajar
     */
    public function bimbinganBelajar()
    {
        $user = Auth::user();
        
        // Ambil daftar guru BK untuk pilihan
        $gurus = DB::table('users')
            ->whereIn('role', ['guru_bk', 'guru'])
            ->select('id', 'name')
            ->get();

        return view('siswa.bimbingan-belajar', compact('gurus'));
    }

    /**
     * Bimbingan Karir
     */
    public function bimbinganKarir()
    {
        $user = Auth::user();
        
        // Ambil daftar guru BK untuk pilihan
        $gurus = DB::table('users')
            ->whereIn('role', ['guru_bk', 'guru'])
            ->select('id', 'name')
            ->get();

        return view('siswa.bimbingan-karir', compact('gurus'));
    }

    /**
     * Detail Riwayat Konseling
     */
    public function detailRiwayatKonseling($id)
    {
        $user = Auth::user();
        
        $detail = DB::table('janji_konselings')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$detail) {
            abort(404, 'Riwayat konseling tidak ditemukan');
        }

        return view('siswa.riwayat-konseling-detail', compact('detail'));
    }

    /**
     * Detail Riwayat Karir
     */
    public function detailRiwayatKarir($id)
    {
        $user = Auth::user();

        $detail = DB::table('janji_konselings')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->where('jenis_bimbingan', 'karir')
            ->where('status', 'selesai')
            ->first();

        if (!$detail) {
            abort(404, 'Riwayat karir tidak ditemukan');
        }

        return view('siswa.riwayat-karir-detail', compact('detail'));
    }

    /**
     * Daftar Catatan dari Guru BK
     */
    public function daftarCatatan()
    {
        $user = Auth::user();
        
        $catatan = DB::table('catatan')
            ->where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        return view('siswa.daftar-catatan', compact('catatan'));
    }

    /**
     * Detail Catatan dari Guru BK
     */
    public function detailCatatan($id)
    {
        $user = Auth::user();
        
        $catatan = DB::table('catatan')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if (!$catatan) {
            abort(404, 'Catatan tidak ditemukan');
        }
        
        // Ambil info janji konseling jika ada janji_id
        $janji = null;
        if ($catatan->janji_id) {
            $janji = DB::table('janji_konselings')
                ->where('id', $catatan->janji_id)
                ->first();
        }
        
        return view('siswa.detail-catatan', compact('catatan', 'janji'));
    }
}