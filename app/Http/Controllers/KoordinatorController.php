<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KoordinatorController extends Controller
{
    /**
     * Display dashboard for Koordinator BK
     */
    public function dashboard()
    {
        // Semua query dipindahkan dari view ke controller
        $stats = [
            'total_siswa' => DB::table('users')->where('role', 'siswa')->count(),
            'total_guru' => DB::table('users')->where('role', 'guru_bk')->count(),
            'konseling_bulan_ini' => DB::table('janji_konselings')->whereMonth('tanggal', now()->month)->count(),
            'menunggu_konfirmasi' => DB::table('janji_konselings')->where('status', 'menunggu')->count(),
        ];

        $jenisKonseling = DB::table('janji_konselings')
            ->select('jenis_bimbingan', DB::raw('count(*) as total'))
            ->groupBy('jenis_bimbingan')
            ->get();

        $totalAll = $jenisKonseling->sum('total');

        // Process data untuk chart
        $jenisKonselingData = $jenisKonseling->map(function($item) use ($totalAll) {
            $percentage = $totalAll > 0 ? ($item->total / $totalAll) * 100 : 0;
            $colors = [
                'pribadi' => ['bg' => 'blue', 'label' => 'Pribadi'],
                'belajar' => ['bg' => 'green', 'label' => 'Belajar'],
                'karir' => ['bg' => 'purple', 'label' => 'Karir'],
                'sosial' => ['bg' => 'orange', 'label' => 'Sosial']
            ];
            
            return [
                'jenis_bimbingan' => $item->jenis_bimbingan,
                'total' => $item->total,
                'percentage' => $percentage,
                'color' => $colors[$item->jenis_bimbingan] ?? ['bg' => 'gray', 'label' => ucfirst($item->jenis_bimbingan)]
            ];
        });

        $recentActivities = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name')
            ->orderBy('janji_konselings.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('koordinator.dashboard', compact('stats', 'jenisKonselingData', 'recentActivities'));
    }

    // Tambahkan method untuk route yang belum ada
    public function kelolaGuru()
    {
        return view('koordinator.guru');
    }

    public function dataSiswa()
    {
        return view('koordinator.siswa');
    }

    public function laporan()
    {
        return view('koordinator.laporan');
    }

    public function pengaturan()
    {
        return view('koordinator.pengaturan');
    }
}