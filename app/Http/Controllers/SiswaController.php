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

        return view('siswa.riwayat-konseling', compact('riwayat'));
    }

    /**
     * Bimbingan Belajar
     */
    public function bimbinganBelajar()
    {
        $user = Auth::user();
        
        // Get riwayat konseling dengan jenis bimbingan 'belajar'
        $riwayatBelajar = DB::table('janji_konselings')
            ->where('user_id', $user->id)
            ->where('jenis_bimbingan', 'belajar')
            ->whereIn('status', ['dikonfirmasi', 'selesai', 'dibatalkan'])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // Ambil daftar guru BK untuk pilihan
        $gurus = DB::table('users')
            ->whereIn('role', ['guru_bk', 'guru'])
            ->select('id', 'name')
            ->get();

        return view('siswa.bimbingan-belajar', compact('riwayatBelajar', 'gurus'));
    }

    /**
     * Bimbingan Karir
     */
    public function bimbinganKarir()
    {
        $user = Auth::user();
        
        // Get riwayat konseling dengan jenis bimbingan 'karir'
        $riwayatKarir = DB::table('janji_konselings')
            ->where('user_id', $user->id)
            ->where('jenis_bimbingan', 'karir')
            ->whereIn('status', ['dikonfirmasi', 'selesai', 'dibatalkan'])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // Ambil daftar guru BK untuk pilihan
        $gurus = DB::table('users')
            ->whereIn('role', ['guru_bk', 'guru'])
            ->select('id', 'name')
            ->get();

        return view('siswa.bimbingan-karir', compact('riwayatKarir', 'gurus'));
    }
}