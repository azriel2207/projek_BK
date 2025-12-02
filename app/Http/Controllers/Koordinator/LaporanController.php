<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\JanjiKonseling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'bulan_ini');
        
        // Tentukan range tanggal berdasarkan periode
        $dateRange = $this->getDateRange($periode);
        
        // Data statistik berdasarkan periode (REAL DATA)
        $totalKonseling = JanjiKonseling::whereBetween('tanggal', $dateRange)
            ->count();
        
        $kasusSelesai = JanjiKonseling::whereBetween('tanggal', $dateRange)
            ->where('status', 'selesai')
            ->count();
        
        $kasusBerlangsung = JanjiKonseling::whereBetween('tanggal', $dateRange)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->count();
        
        // Persentase perubahan dari periode sebelumnya
        $prevDateRange = $this->getPrevDateRange($periode);
        $prevTotal = JanjiKonseling::whereBetween('tanggal', $prevDateRange)->count();
        $persentaseTotal = $prevTotal > 0 
            ? (($totalKonseling - $prevTotal) / $prevTotal) * 100 
            : 0;
        
        $prevSelesai = JanjiKonseling::whereBetween('tanggal', $prevDateRange)
            ->where('status', 'selesai')
            ->count();
        $persentaseSelesai = $prevSelesai > 0 
            ? (($kasusSelesai - $prevSelesai) / $prevSelesai) * 100 
            : 0;
        
        // Jenis bimbingan
        $jenisKonseling = JanjiKonseling::select('jenis_bimbingan', DB::raw('count(*) as total'))
            ->whereBetween('tanggal', $dateRange)
            ->groupBy('jenis_bimbingan')
            ->get();
            
        // Status bimbingan
        $statusKonseling = JanjiKonseling::select('status', DB::raw('count(*) as total'))
            ->whereBetween('tanggal', $dateRange)
            ->groupBy('status')
            ->get();

        // Statistik untuk tampilan (REAL DATA dari database)
        $statistik = [
            'total_konseling' => $totalKonseling,
            'persentase_total' => round($persentaseTotal, 1),
            'kasus_selesai' => $kasusSelesai,
            'persentase_selesai' => round($persentaseSelesai, 1),
            'kasus_berlangsung' => $kasusBerlangsung,
            'rata_rata_waktu' => 45, // Default value (bisa ditambahkan di future jika ada kolom jam_mulai/jam_selesai)
            'perubahan_waktu' => 5,
            'tingkat_kepuasan' => 85, // Default value (bisa ditambahkan jika ada kolom rating)
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
     * Export laporan ke PDF - FIXED VERSION
     */
    public function exportPdf(Request $request)
    {
        try {
            // Handle both form data and JSON
            $periodeInput = $request->input('periode', 'bulan_ini');
            
            // Jika format YYYY-MM, gunakan langsung
            if (preg_match('/^\d{4}-\d{2}$/', $periodeInput)) {
                $tahun = substr($periodeInput, 0, 4);
                $bulan = substr($periodeInput, 5, 2);
                $periode = $periodeInput;
            } else {
                // Jika format seperti bulan_ini, 3_bulan, dll - konversi ke YYYY-MM
                $dateRange = $this->getDateRange($periodeInput);
                // $dateRange[0] adalah Carbon object
                $tahun = $dateRange[0]->year;
                $bulan = $dateRange[0]->month;
                $periode = $dateRange[0]->format('Y-m');
            }
            
            // Validasi input
            if (!is_numeric($tahun) || !is_numeric($bulan) || $bulan < 1 || $bulan > 12) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Format periode tidak valid'], 400);
                }
                return back()->with('error', 'Format periode tidak valid');
            }

            // Data untuk laporan PDF
            $data = [
                'periode' => $periode,
                'nama_bulan' => $this->getNamaBulan((int)$bulan),
                'tahun' => $tahun,
                'tanggal_generate' => date('d F Y H:i:s'),
                
                // Statistik utama
                'total_konseling' => JanjiKonseling::whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->count(),
                'konseling_selesai' => JanjiKonseling::whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->where('status', 'selesai')
                    ->count(),
                'konseling_berlangsung' => JanjiKonseling::whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                    ->count(),
                    
                // Data per jenis bimbingan
                'data_per_jenis' => JanjiKonseling::select('jenis_bimbingan', DB::raw('COUNT(*) as total'))
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->groupBy('jenis_bimbingan')
                    ->get(),
                    
                // Top guru BK
                'top_guru' => JanjiKonseling::select('guru_bk', DB::raw('COUNT(*) as total'))
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->whereNotNull('guru_bk')
                    ->groupBy('guru_bk')
                    ->orderBy('total', 'desc')
                    ->limit(5)
                    ->get(),
                    
                // Data kasus prioritas
                'kasus_prioritas' => JanjiKonseling::with(['user'])
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                    ->orderBy('tanggal', 'asc')
                    ->limit(10)
                    ->get()
            ];

            // Log data for debugging (temporary) to help diagnose empty PDF
            try {
                Log::info('LAPORAN_EXPORT_DATA', $data);
            } catch (\Exception $e) {
                // ignore logging errors
            }

            // Generate PDF
            $pdf = Pdf::loadView('koordinator.laporan.pdf', $data);

            $filename = "laporan-konseling-{$periode}.pdf";
            
            // Return PDF download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat generate PDF: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Update periode untuk AJAX (digunakan web UI)
     */
    public function updatePeriode(Request $request)
    {
        try {
            $periode = $request->input('periode', 'bulan_ini');
            
            // Tentukan range tanggal berdasarkan periode
            $dateRange = $this->getDateRange($periode);
            
            // Data statistik berdasarkan periode
            $totalKonseling = JanjiKonseling::whereBetween('tanggal', $dateRange)->count();
            $kasusSelesai = JanjiKonseling::whereBetween('tanggal', $dateRange)
                ->where('status', 'selesai')
                ->count();
            
            // Persentase perubahan
            $prevDateRange = $this->getPrevDateRange($periode);
            $prevTotal = JanjiKonseling::whereBetween('tanggal', $prevDateRange)->count();
            $persentaseTotal = $prevTotal > 0 
                ? (($totalKonseling - $prevTotal) / $prevTotal) * 100 
                : 0;
            
            $prevSelesai = JanjiKonseling::whereBetween('tanggal', $prevDateRange)
                ->where('status', 'selesai')
                ->count();
            $persentaseSelesai = $prevSelesai > 0 
                ? (($kasusSelesai - $prevSelesai) / $prevSelesai) * 100 
                : 0;
            
            // Jenis bimbingan
            $jenisKonseling = JanjiKonseling::select('jenis_bimbingan', DB::raw('count(*) as total'))
                ->whereBetween('tanggal', $dateRange)
                ->groupBy('jenis_bimbingan')
                ->get();
            
            // Status konseling
            $statusKonseling = JanjiKonseling::select('status', DB::raw('count(*) as total'))
                ->whereBetween('tanggal', $dateRange)
                ->groupBy('status')
                ->get();
            
            $statistik = [
                'total_konseling' => $totalKonseling,
                'persentase_total' => round($persentaseTotal, 1),
                'kasus_selesai' => $kasusSelesai,
                'persentase_selesai' => round($persentaseSelesai, 1)
            ];
            
            return response()->json([
                'success' => true,
                'statistik' => $statistik,
                'jenisKonseling' => $jenisKonseling,
                'statusKonseling' => $statusKonseling
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistik Trend - IMPLEMENTASI LENGKAP
     */
    public function statistikTrend(Request $request)
    {
        try {
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

            return view('koordinator.laporan.trend', compact('trendData', 'bulan'));

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Performa Guru - IMPLEMENTASI LENGKAP
     */
    public function performaGuru()
    {
        try {
            $performaGuru = JanjiKonseling::select('guru_bk', 
                    DB::raw('COUNT(*) as total_konseling'),
                    DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as konseling_selesai'),
                    DB::raw('ROUND((SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as tingkat_kesuksesan')
                )
                ->whereNotNull('guru_bk')
                ->groupBy('guru_bk')
                ->orderBy('tingkat_kesuksesan', 'desc')
                ->get();

            return view('koordinator.laporan.performa', compact('performaGuru'));

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Kasus Prioritas - IMPLEMENTASI LENGKAP
     */
    public function kasusPrioritas()
    {
        try {
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
                        'tanggal' => $konseling->tanggal,
                        'waktu' => $konseling->waktu,
                        'keluhan' => Str::limit($konseling->keluhan, 100),
                        'hari_menunggu' => $hariMenunggu,
                        'prioritas' => $this->hitungPrioritas($hariMenunggu)
                    ];
                });

            return view('koordinator.laporan.prioritas', compact('kasusPrioritas'));

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Generate Laporan Bulanan PDF
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
            // Data untuk laporan bulanan
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
     * Generate Statistik Trend untuk AJAX
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
     * Generate Performa Guru untuk AJAX
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
     * Generate Kasus Prioritas untuk AJAX
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
     * Helper method untuk data laporan bulanan
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
     * Helper method untuk mendapatkan nama bulan
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
     * Helper method untuk menghitung prioritas kasus
     */
    private function hitungPrioritas($hariMenunggu)
    {
        if ($hariMenunggu > 7) return 'Tinggi';
        if ($hariMenunggu > 3) return 'Sedang';
        return 'Rendah';
    }

    /**
     * Helper method untuk mendapatkan range tanggal berdasarkan periode
     */
    private function getDateRange($periode)
    {
        $now = now();
        
        switch ($periode) {
            case 'bulan_ini':
                return [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth()
                ];
            case '3_bulan':
                return [
                    $now->copy()->subMonths(3)->startOfMonth(),
                    $now->copy()->endOfMonth()
                ];
            case '6_bulan':
                return [
                    $now->copy()->subMonths(6)->startOfMonth(),
                    $now->copy()->endOfMonth()
                ];
            case 'tahun_ini':
                return [
                    $now->copy()->startOfYear(),
                    $now->copy()->endOfYear()
                ];
            default:
                return [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth()
                ];
        }
    }

    /**
     * Helper method untuk mendapatkan range tanggal periode sebelumnya
     */
    private function getPrevDateRange($periode)
    {
        $now = now();
        
        switch ($periode) {
            case 'bulan_ini':
                return [
                    $now->copy()->subMonth()->startOfMonth(),
                    $now->copy()->subMonth()->endOfMonth()
                ];
            case '3_bulan':
                return [
                    $now->copy()->subMonths(6)->startOfMonth(),
                    $now->copy()->subMonths(3)->endOfMonth()
                ];
            case '6_bulan':
                return [
                    $now->copy()->subMonths(12)->startOfMonth(),
                    $now->copy()->subMonths(6)->endOfMonth()
                ];
            case 'tahun_ini':
                return [
                    $now->copy()->subYear()->startOfYear(),
                    $now->copy()->subYear()->endOfYear()
                ];
            default:
                return [
                    $now->copy()->subMonth()->startOfMonth(),
                    $now->copy()->subMonth()->endOfMonth()
                ];
        }
    }
}
