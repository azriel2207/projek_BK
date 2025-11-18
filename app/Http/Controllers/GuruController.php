<?php

namespace App\Http\Controllers;

use App\Models\JanjiKonseling;
use App\Models\User;
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
            ->limit(5)
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
            'total_siswa' => User::where('role', 'siswa')->count(),
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
     * Kelola Jadwal Konseling
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
    
    /**
     * Daftar Siswa Bimbingan
     */
    public function daftarSiswa()
    {
        // Ambil siswa yang pernah melakukan konseling
        $siswaIds = JanjiKonseling::select('user_id')
            ->distinct()
            ->pluck('user_id');
        
        $siswa = User::whereIn('id', $siswaIds)
            ->where('role', 'siswa')
            ->get()
            ->map(function($user) {
                $totalKonseling = JanjiKonseling::where('user_id', $user->id)->count();
                $konselingSelesai = JanjiKonseling::where('user_id', $user->id)
                    ->where('status', 'selesai')
                    ->count();
                $terakhirKonseling = JanjiKonseling::where('user_id', $user->id)
                    ->orderBy('tanggal', 'desc')
                    ->first();
                
                return [
                    'user' => $user,
                    'total_konseling' => $totalKonseling,
                    'konseling_selesai' => $konselingSelesai,
                    'terakhir_konseling' => $terakhirKonseling
                ];
            });
        
        return view('guru.daftar-siswa', compact('siswa'));
    }
    
    /**
     * Detail Siswa
     */
    public function detailSiswa($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            if ($user->role !== 'siswa') {
                return redirect()->route('guru.siswa')
                    ->with('error', 'User bukan siswa');
            }
            
            $riwayatKonseling = JanjiKonseling::where('user_id', $userId)
                ->orderBy('tanggal', 'desc')
                ->get();
            
            $statistik = [
                'total' => $riwayatKonseling->count(),
                'selesai' => $riwayatKonseling->where('status', 'selesai')->count(),
                'menunggu' => $riwayatKonseling->where('status', 'menunggu')->count(),
                'dikonfirmasi' => $riwayatKonseling->where('status', 'dikonfirmasi')->count(),
                'dibatalkan' => $riwayatKonseling->where('status', 'dibatalkan')->count(),
            ];
            
            return view('guru.detail-siswa', compact('user', 'riwayatKonseling', 'statistik'));
        } catch (\Exception $e) {
            return redirect()->route('guru.siswa')
                ->with('error', 'Siswa tidak ditemukan');
        }
    }
    
    /**
     * Daftar Catatan Konseling
     */
    public function daftarCatatan()
    {
        $catatan = JanjiKonseling::with('user')
            ->whereNotNull('catatan_konselor')
            ->where('status', 'selesai')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        
        return view('guru.catatan-konseling', compact('catatan'));
    }
    
    /**
     * Detail Catatan
     */
    public function detailCatatan($id)
    {
        $janji = JanjiKonseling::with('user')->findOrFail($id);
        return view('guru.detail-catatan', compact('janji'));
    }
    
    /**
     * Konfirmasi permintaan konseling
     */
    public function konfirmasiJanji(Request $request, $id)
    {
        try {
            $janji = JanjiKonseling::findOrFail($id);
            
            $janji->update([
                'status' => 'dikonfirmasi',
                'guru_bk' => Auth::user()->name
            ]);
            
            return redirect()->route('guru.dashboard')
                ->with('success', 'Janji konseling berhasil dikonfirmasi');
        } catch (\Exception $e) {
            return redirect()->route('guru.dashboard')
                ->with('error', 'Gagal mengkonfirmasi janji konseling');
        }
    }
    
    /**
     * Tolak permintaan konseling
     */
    public function tolakJanji(Request $request, $id)
    {
        try {
            $request->validate([
                'alasan' => 'required|string|min:10'
            ]);
            
            $janji = JanjiKonseling::findOrFail($id);
            
            $janji->update([
                'status' => 'dibatalkan',
                'catatan_konselor' => 'Ditolak: ' . $request->alasan
            ]);
            
            return redirect()->route('guru.dashboard')
                ->with('success', 'Permintaan konseling berhasil ditolak');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menolak permintaan');
        }
    }
    
    /**
     * Tambah catatan konselor
     */
    public function tambahCatatan(Request $request, $id)
    {
        try {
            $request->validate([
                'catatan_konselor' => 'required|string|min:10'
            ]);
            
            $janji = JanjiKonseling::findOrFail($id);
            
            $janji->update([
                'catatan_konselor' => $request->catatan_konselor,
                'status' => 'selesai'
            ]);
            
            return redirect()->route('guru.dashboard')
                ->with('success', 'Catatan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan catatan');
        }
    }
    
    /**
     * Lihat semua permintaan
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
     * Laporan
     */
    public function laporan()
    {
        $bulanIni = now()->format('Y-m');
        
        $laporan = [
            'total_konseling' => JanjiKonseling::whereMonth('tanggal', now()->month)->count(),
            'selesai' => JanjiKonseling::where('status', 'selesai')->whereMonth('tanggal', now()->month)->count(),
            'dibatalkan' => JanjiKonseling::where('status', 'dibatalkan')->whereMonth('tanggal', now()->month)->count(),
            'menunggu' => JanjiKonseling::where('status', 'menunggu')->count(),
        ];
        
        return view('guru.laporan', compact('laporan'));
    }
    
    /**
     * Statistik
     */
    public function statistik()
    {
        // Data untuk chart
        $chartData = JanjiKonseling::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, COUNT(*) as total')
            ->where('tanggal', '>=', now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();
        
        return view('guru.statistik', compact('chartData'));
    }
}