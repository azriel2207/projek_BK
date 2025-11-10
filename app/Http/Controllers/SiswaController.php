<?php

namespace App\Http\Controllers;

use App\Models\JanjiKonseling;
use App\Models\BimbinganBelajar;
use App\Models\BimbinganKarir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    // Dashboard Siswa
    public function dashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'janji_aktif' => JanjiKonseling::where('user_id', $user->id)
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->count(),
            'total_riwayat' => JanjiKonseling::where('user_id', $user->id)
                ->whereIn('status', ['selesai', 'dibatalkan'])
                ->count(),
            'materi_belajar' => 8, // Static for now
            'materi_karir' => 6,   // Static for now
        ];

        return view('dashboard.siswa', compact('stats'));
    }

    // Riwayat Konseling
    public function riwayatKonseling()
    {
        $user = Auth::user();
        $riwayat = JanjiKonseling::where('user_id', $user->id)
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('siswa.riwayat-konseling', compact('riwayat'));
    }

  // Bimbingan Belajar - Konsep Baru
public function bimbinganBelajar()
{
    // Data untuk form konsultasi belajar
    $guruBK = [
        [
            'nama' => 'Ibu Siti Rahayu, S.Pd',
            'spesialisasi' => 'Spesialis Akademik & Belajar',
            'status' => 'tersedia'
        ],
        [
            'nama' => 'Bpk. Budi Santoso, M.Psi', 
            'spesialisasi' => 'Spesialis Motivasi & Konsentrasi',
            'status' => 'tersedia'
        ]
    ];

    return view('siswa.bimbingan-belajar', compact('guruBK'));
}

// Bimbingan Karir - Konsep Baru  
public function bimbinganKarir()
{
    // Data untuk form konsultasi karir
    $spesialisKarir = [
        [
            'nama' => 'Bpk. Ahmad, M.Pd',
            'spesialisasi' => 'Spesialis Bimbingan Karir',
            'pengalaman' => '15+ tahun pengalaman'
        ],
        [
            'nama' => 'Ibu Diana, S.Psi',
            'spesialisasi' => 'Spesialis Psikologi Karir', 
            'pengalaman' => 'Expert assessment minat'
        ]
    ];

    return view('siswa.bimbingan-karir', compact('spesialisKarir'));
}
}