<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GuruController extends Controller
{
    public function index()
    {
        // Ambil data permintaan menunggu (collection)
        $permintaanBaru = DB::table('janji_konselings')
            ->where('status', 'menunggu')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Statistik sederhana (sesuaikan dengan struktur DB jika berbeda)
        $stats = [
            'total_siswa' => DB::table('users')->count(),
            'permintaan_menunggu' => $permintaanBaru->count(),
            'konseling_hari_ini' => DB::table('janji_konselings')
                ->whereDate('tanggal', Carbon::today())
                ->where('status', 'konfirmasi')
                ->count(),
            'kasus_aktif' => DB::table('janji_konselings')->where('status', 'aktif')->count(),
            'tindak_lanjut' => DB::table('tindak_lanjuts')->count() ?? 0,
            'selesai_bulan_ini' => DB::table('janji_konselings')
                ->where('status', 'selesai')
                ->whereMonth('tanggal', Carbon::now()->month)
                ->count(),
        ];

        // Jadwal hari ini dan riwayat
        $jadwalHariIni = DB::table('janji_konselings')
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('waktu')
            ->get();

        $riwayatKonseling = DB::table('janji_konselings')
            ->where('status', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.dashboard', compact('stats', 'permintaanBaru', 'jadwalHariIni', 'riwayatKonseling'));
    }
}