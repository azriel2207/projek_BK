<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * Dashboard Siswa
     */
    public function dashboard()
    {
        return view('siswa.dashboard');
    }

    /**
     * Riwayat Konseling
     */
    public function riwayatKonseling()
    {
        $user = Auth::user();
        
        $riwayat = DB::table('janji_konselings')
            ->where('user_id', $user->id)
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('siswa.riwayat-konseling', compact('riwayat'));
    }

    /**
     * Bimbingan Belajar
     */
    public function bimbinganBelajar()
    {
        return view('siswa.bimbingan-belajar');
    }

    /**
     * Bimbingan Karir
     */
    public function bimbinganKarir()
    {
        return view('siswa.bimbingan-karir');
    }
}