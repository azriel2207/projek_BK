<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\JanjiKonseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'bulan_ini');
        
        // Data statistik
        $totalKonseling = JanjiKonseling::count();
        $kasusSelesai = JanjiKonseling::where('status', 'selesai')->count();
        
        $jenisKonseling = JanjiKonseling::select('jenis_bimbingan', DB::raw('count(*) as total'))
            ->groupBy('jenis_bimbingan')
            ->get();
            
        $statusKonseling = JanjiKonseling::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Statistik untuk testing
        $statistik = [
            'total_konseling' => $totalKonseling,
            'persentase_total' => 12.5,
            'kasus_selesai' => $kasusSelesai,
            'persentase_selesai' => 8.2,
            'rata_rata_waktu' => 45,
            'perubahan_waktu' => 5,
            'tingkat_kepuasan' => 89,
            'perubahan_kepuasan' => 3
        ];

        return view('koordinator.laporan', compact(
            'statistik',
            'jenisKonseling',
            'statusKonseling',
            'periode'
        ));
    }

    /**
     * Generate Laporan Bulanan PDF - SOLUSI SEDERHANA
     */
    public function generateLaporanBulanan(Request $request)
    {
        $bulan = (int) $request->get('bulan', date('m'));
        $tahun = (int) $request->get('tahun', date('Y'));

        // Validasi input
        if ($bulan < 1 || $bulan > 12) {
            return back()->with('error', 'Bulan tidak valid');
        }

        if ($tahun < 2020 || $tahun > 2030) {
            return back()->with('error', 'Tahun tidak valid');
        }

        try {
            // Data untuk laporan bulanan - TANPA CARBON
            $data = $this->getDataLaporanBulanan($bulan, $tahun);
            
            $pdf = Pdf::loadView('koordinator.laporan.bulanan-pdf', $data);
            
            $namaBulan = $this->getNamaBulan($bulan);
            $filename = "laporan-bulanan-{$namaBulan}-{$tahun}.pdf";
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper method untuk data laporan bulanan - REVISI
     */
    private function getDataLaporanBulanan($bulan, $tahun)
    {
        // Format bulan dengan leading zero untuk query
        $bulanFormatted = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        
        $konseling = JanjiKonseling::whereMonth('tanggal', $bulanFormatted)
            ->whereYear('tanggal', $tahun)
            ->get();

        $totalKonseling = $konseling->count();
        $selesai = $konseling->where('status', 'selesai')->count();
        $menunggu = $konseling->where('status', 'menunggu')->count();
        $dikonfirmasi = $konseling->where('status', 'dikonfirmasi')->count();

        $jenisKonseling = $konseling->groupBy('jenis_bimbingan')->map->count();
        $performaGuru = $konseling->whereNotNull('guru_bk')->groupBy('guru_bk')->map->count();

        return [
            'konseling' => $konseling,
            'total_konseling' => $totalKonseling,
            'selesai' => $selesai,
            'menunggu' => $menunggu,
            'dikonfirmasi' => $dikonfirmasi,
            'jenis_konseling' => $jenisKonseling,
            'performa_guru' => $performaGuru,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bulan' => $this->getNamaBulan($bulan),
            'tanggal_generate' => date('d F Y H:i:s')
        ];
    }

    /**
     * Helper method untuk mendapatkan nama bulan - TANPA CARBON
     */
    private function getNamaBulan($bulan)
    {
        $bulan = (int) $bulan;
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $namaBulan[$bulan] ?? 'Tidak Diketahui';
    }

    /**
     * Generate Statistik Trend - REVISI
     */
    public function generateStatistikTrend(Request $request)
    {
        $bulan = $request->get('bulan', 6);
        
        $trendData = JanjiKonseling::where('tanggal', '>=', now()->subMonths($bulan))
            ->select(
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('COUNT(*) as total_konseling'),
                DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as selesai'),
                DB::raw('SUM(CASE WHEN status = "menunggu" THEN 1 ELSE 0 END) as menunggu')
            )
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $trendData,
            'labels' => $trendData->map(function ($item) {
                return $this->getNamaBulanSingkat($item->bulan) . ' ' . $item->tahun;
            })
        ]);
    }

    /**
     * Helper method untuk mendapatkan nama bulan singkat
     */
    private function getNamaBulanSingkat($bulan)
    {
        $bulan = (int) $bulan;
        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        
        return $namaBulan[$bulan] ?? '???';
    }

    /**
     * Generate Performa Guru
     */
    public function generatePerformaGuru(Request $request)
    {
        $performaGuru = JanjiKonseling::select('guru_bk', 
                DB::raw('COUNT(*) as total_konseling'),
                DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as konseling_selesai'),
                DB::raw('ROUND((SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as tingkat_kesuksesan')
            )
            ->whereNotNull('guru_bk')
            ->groupBy('guru_bk')
            ->orderBy('tingkat_kesuksesan', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $performaGuru
        ]);
    }

    /**
     * Generate Laporan Kasus Prioritas - REVISI
     */
    public function generateKasusPrioritas(Request $request)
    {
        $kasusPrioritas = JanjiKonseling::with(['user'])
            ->where(function($query) {
                $query->where('status', 'menunggu')
                      ->orWhere('status', 'dikonfirmasi');
            })
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get()
            ->map(function ($konseling) {
                $hariMenunggu = now()->diffInDays($konseling->created_at);
                
                return [
                    'id' => $konseling->id,
                    'siswa' => $konseling->user->name ?? 'Tidak ada data',
                    'jenis_bimbingan' => $this->getJenisBimbinganLabel($konseling->jenis_bimbingan),
                    'status' => $konseling->status,
                    'tanggal' => $konseling->tanggal->format('d/m/Y'),
                    'waktu' => $konseling->waktu,
                    'keluhan' => Str::limit($konseling->keluhan, 100),
                    'hari_menunggu' => $hariMenunggu,
                    'prioritas' => $this->hitungPrioritas($hariMenunggu)
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $kasusPrioritas,
            'total_kasus' => $kasusPrioritas->count()
        ]);
    }

    /**
     * Helper method untuk label jenis bimbingan
     */
    private function getJenisBimbinganLabel($jenis)
    {
        $labels = [
            'pribadi' => 'Bimbingan Pribadi',
            'belajar' => 'Bimbingan Belajar', 
            'karir' => 'Bimbingan Karir',
            'sosial' => 'Bimbingan Sosial'
        ];
        
        return $labels[$jenis] ?? ucfirst($jenis);
    }

    /**
     * Helper method untuk menghitung prioritas kasus - REVISI
     */
    private function hitungPrioritas($hariMenunggu)
    {
        if ($hariMenunggu > 7) return 'Tinggi';
        if ($hariMenunggu > 3) return 'Sedang';
        return 'Rendah';
    }

    // Method untuk export PDF sederhana (existing)
    public function exportPdf(Request $request)
    {
        return response()->json(['message' => 'PDF export akan diimplementasi']);
    }

    public function statistikTrend(Request $request)
    {
        return response()->json(['message' => 'Statistik trend akan diimplementasi']);
    }

    public function performaGuru()
    {
        return response()->json(['message' => 'Performa guru akan diimplementasi']);
    }

    public function kasusPrioritas()
    {
        return response()->json(['message' => 'Kasus prioritas akan diimplementasi']);
    }

    public function updatePeriode(Request $request)
    {
        $periode = $request->get('periode', 'bulan_ini');
        
        // Data dummy untuk testing
        return response()->json([
            'success' => true,
            'statistik' => [
                'total_konseling' => rand(50, 100),
                'persentase_total' => 12.5,
                'kasus_selesai' => rand(30, 60),
                'persentase_selesai' => 8.2
            ],
            'jenisKonseling' => [],
            'statusKonseling' => []
        ]);
    }
}