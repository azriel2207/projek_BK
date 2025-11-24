<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuruController extends Controller
{

    
    // Alias untuk dashboard
    public function index()
    {
        return $this->dashboard();
    }

    // Dashboard utama
    public function dashboard()
    {
        // Ambil data permintaan menunggu
        $permintaanBaru = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name', 'users.email')
            ->where('janji_konselings.status', 'menunggu')
            ->orderBy('janji_konselings.tanggal', 'asc')
            ->get();

        // Statistik
        $stats = [
            'total_siswa' => DB::table('users')->where('role', 'siswa')->count(),
            'permintaan_menunggu' => $permintaanBaru->count(),
            'konseling_hari_ini' => DB::table('janji_konselings')
                ->whereDate('tanggal', Carbon::today())
                ->where('status', 'dikonfirmasi')
                ->count(),
            'kasus_aktif' => DB::table('janji_konselings')
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->count(),
            'tindak_lanjut' => 0,
            'selesai_bulan_ini' => DB::table('janji_konselings')
                ->where('status', 'selesai')
                ->whereMonth('tanggal', Carbon::now()->month)
                ->count(),
        ];

        // Jadwal hari ini
        $jadwalHariIni = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name')
            ->whereDate('janji_konselings.tanggal', Carbon::today())
            ->where('janji_konselings.status', 'dikonfirmasi')
            ->orderBy('janji_konselings.waktu')
            ->get();

        // Riwayat konseling
        $riwayatKonseling = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name')
            ->where('janji_konselings.status', 'selesai')
            ->orderBy('janji_konselings.tanggal', 'desc')
            ->limit(10)
            ->get();

        return view('guru.dashboard', compact('stats', 'permintaanBaru', 'jadwalHariIni', 'riwayatKonseling'));
    }

    // Kelola Jadwal
    public function jadwalKonseling()
    {
        $jadwal = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select(
                'janji_konselings.*',
                'users.name as nama_siswa',
                'users.email as email_siswa'
            )
            ->orderBy('janji_konselings.tanggal', 'desc')
            ->paginate(15);

        return view('guru.jadwal', compact('jadwal'));
    }

    public function tambahJadwal()
    {
        return view('guru.jadwal-tambah');
    }

    public function simpanJadwal(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'jenis_bimbingan' => 'required',
        ]);

        DB::table('janji_konselings')->insert([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'jenis_bimbingan' => $request->jenis_bimbingan,
            'keluhan' => $request->keluhan ?? '',
            'guru_bk' => Auth::user()->name,
            'status' => 'dikonfirmasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('guru.jadwal')->with('success', 'Jadwal berhasil ditambahkan');
    }

    // Kelola Permintaan
    public function semuaPermintaan()
    {
        $permintaan = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name', 'users.email')
            ->where('janji_konselings.status', 'menunggu')
            ->orderBy('janji_konselings.created_at', 'desc')
            ->paginate(20);

        return view('guru.permintaan', compact('permintaan'));
    }

    public function konfirmasiJanji($id)
    {
        DB::table('janji_konselings')
            ->where('id', $id)
            ->update([
                'status' => 'dikonfirmasi',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Janji konseling berhasil dikonfirmasi');
    }

    public function tolakJanji(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|min:10'
        ]);

        DB::table('janji_konselings')
            ->where('id', $id)
            ->update([
                'status' => 'dibatalkan',
                'catatan_konselor' => 'Ditolak: ' . $request->alasan,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Permintaan berhasil ditolak');
    }

    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required'
        ]);

        DB::table('janji_konselings')
            ->where('id', $id)
            ->update([
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'status' => 'dikonfirmasi',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Jadwal berhasil diubah');
    }

    // Kelola Siswa
    public function daftarSiswa()
    {
        $siswa = DB::table('users')
            ->where('role', 'siswa')
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view('guru.siswa', compact('siswa'));
    }

    public function detailSiswa($id)
    {
        $siswa = DB::table('users')->where('id', $id)->first();
        
        if (!$siswa) {
            abort(404, 'Siswa tidak ditemukan');
        }

        $riwayatKonseling = DB::table('janji_konselings')
            ->where('user_id', $id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.siswa-detail', compact('siswa', 'riwayatKonseling'));
    }

    // Catatan Konseling
    public function daftarCatatan()
    {
        $catatan = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name as nama_siswa')
            ->where('janji_konselings.status', 'selesai')
            ->orderBy('janji_konselings.tanggal', 'desc')
            ->paginate(15);

        return view('guru.catatan', compact('catatan'));
    }

    public function tambahCatatan(Request $request, $id)
    {
        $request->validate([
            'catatan_konselor' => 'required|string|min:10'
        ]);

        DB::table('janji_konselings')
            ->where('id', $id)
            ->update([
                'catatan_konselor' => $request->catatan_konselor,
                'status' => 'selesai',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Catatan berhasil disimpan');
    }

    public function detailCatatan($id)
    {
        $catatan = DB::table('janji_konselings')
            ->join('users', 'janji_konselings.user_id', '=', 'users.id')
            ->select('janji_konselings.*', 'users.name as nama_siswa', 'users.email')
            ->where('janji_konselings.id', $id)
            ->first();

        if (!$catatan) {
            abort(404, 'Catatan tidak ditemukan');
        }

        return view('guru.catatan-detail', compact('catatan'));
    }

    // Laporan & Statistik
    public function laporan()
    {
        $stats = [
            'total_konseling' => DB::table('janji_konselings')->count(),
            'konseling_selesai' => DB::table('janji_konselings')->where('status', 'selesai')->count(),
            'konseling_pending' => DB::table('janji_konselings')->whereIn('status', ['menunggu', 'dikonfirmasi'])->count(),
            'konseling_bulan_ini' => DB::table('janji_konselings')
                ->whereMonth('tanggal', Carbon::now()->month)
                ->count(),
        ];

        $dataPerJenis = DB::table('janji_konselings')
            ->select('jenis_bimbingan', DB::raw('count(*) as total'))
            ->groupBy('jenis_bimbingan')
            ->get();

        return view('guru.laporan', compact('stats', 'dataPerJenis'));
    }

    public function statistik()
    {
        // Data untuk grafik per bulan
        $perBulan = DB::table('janji_konselings')
            ->select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('tanggal', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Data untuk grafik per status
        $perStatus = DB::table('janji_konselings')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        return view('guru.statistik', compact('perBulan', 'perStatus'));
    }
}