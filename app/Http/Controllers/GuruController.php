<?php

namespace App\Http\Controllers;

use App\Models\JanjiKonseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuruController extends Controller
{
    /**
     * Menampilkan dashboard guru BK dengan data terintegrasi
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Ambil semua permintaan konseling yang menunggu konfirmasi
        $permintaanBaru = JanjiKonseling::with('user')
            ->where('status', 'menunggu')
            ->where('tanggal', '>=', now()->format('Y-m-d'))
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu', 'asc')
            ->get();
        
        // Ambil jadwal konseling hari ini yang sudah dikonfirmasi
        $jadwalHariIni = JanjiKonseling::with('user')
            ->where('status', 'dikonfirmasi')
            ->where('tanggal', now()->format('Y-m-d'))
            ->orderBy('waktu', 'asc')
            ->get();
        
        // Ambil riwayat konseling yang sudah selesai
        $riwayatKonseling = JanjiKonseling::with('user')
            ->where('status', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();
        
        // Hitung statistik
        $stats = [
            'permintaan_menunggu' => JanjiKonseling::where('status', 'menunggu')->count(),
            'konseling_hari_ini' => JanjiKonseling::where('status', 'dikonfirmasi')
                ->where('tanggal', now()->format('Y-m-d'))
                ->count(),
            'total_siswa' => JanjiKonseling::distinct('user_id')->count(),
            'selesai_bulan_ini' => JanjiKonseling::where('status', 'selesai')
                ->whereYear('tanggal', now()->year)
                ->whereMonth('tanggal', now()->month)
                ->count(),
        ];
        
        return view('guru.dashboard', compact(
            'user',
            'permintaanBaru',
            'jadwalHariIni',
            'riwayatKonseling',
            'stats'
        ));
    }
    
    /**
     * Konfirmasi permintaan konseling
     */
    public function konfirmasiJanji(Request $request, $id)
    {
        $janji = JanjiKonseling::findOrFail($id);
        
        $janji->update([
            'status' => 'dikonfirmasi',
            'guru_bk' => Auth::user()->name
        ]);
        
        return redirect()->route('guru.dashboard')
            ->with('success', 'Janji konseling berhasil dikonfirmasi');
    }
    
    /**
     * Tambah catatan konselor
     */
    public function tambahCatatan(Request $request, $id)
    {
        $request->validate([
            'catatan_konselor' => 'required|string|min:10'
        ]);
        
        $janji = JanjiKonseling::findOrFail($id);
        
        $janji->update([
            'catatan_konselor' => $request->catatan_konselor,
            'status' => 'selesai'
        ]);
        
        return redirect()->route('guru.dashboard')
            ->with('success', 'Catatan konseling berhasil ditambahkan');
    }
    
    /**
     * Lihat semua permintaan konseling
     */
    public function semuaPermintaan()
    {
        $permintaan = JanjiKonseling::with('user')
            ->where('status', 'menunggu')
            ->orderBy('tanggal', 'asc')
            ->paginate(10);
        
        return view('guru.permintaan', compact('permintaan'));
    }
    
    /**
     * Lihat daftar siswa bimbingan
     */
    public function daftarSiswa()
    {
        // Ambil siswa yang pernah melakukan konseling
        $siswa = JanjiKonseling::with('user')
            ->select('user_id')
            ->distinct()
            ->get()
            ->map(function($item) {
                $user = $item->user;
                $totalKonseling = JanjiKonseling::where('user_id', $user->id)->count();
                $konselingSelesai = JanjiKonseling::where('user_id', $user->id)
                    ->where('status', 'selesai')
                    ->count();
                
                return [
                    'user' => $user,
                    'total_konseling' => $totalKonseling,
                    'konseling_selesai' => $konselingSelesai,
                    'terakhir_konseling' => JanjiKonseling::where('user_id', $user->id)
                        ->orderBy('tanggal', 'desc')
                        ->first()
                ];
            });
        
        return view('guru.daftar-siswa', compact('siswa'));
    }
    
    /**
     * Lihat detail siswa
     */
    public function detailSiswa($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        $riwayatKonseling = JanjiKonseling::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->get();
        
        $statistik = [
            'total' => $riwayatKonseling->count(),
            'selesai' => $riwayatKonseling->where('status', 'selesai')->count(),
            'menunggu' => $riwayatKonseling->where('status', 'menunggu')->count(),
            'dikonfirmasi' => $riwayatKonseling->where('status', 'dikonfirmasi')->count(),
        ];
        
        return view('guru.detail-siswa', compact('user', 'riwayatKonseling', 'statistik'));
    }
    
    /**
     * Lihat jadwal konseling lengkap
     */
    public function jadwalKonseling()
    {
        $jadwal = JanjiKonseling::with('user')
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->where('tanggal', '>=', now()->format('Y-m-d'))
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu', 'asc')
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            });
        
        return view('guru.jadwal-konseling', compact('jadwal'));
    }
}