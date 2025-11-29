<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Konseling BK - {{ $periode }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            font-size: 12px;
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #333; 
            padding-bottom: 10px; 
        }
        .header h1 { 
            margin: 0; 
            color: #333; 
            font-size: 18px;
        }
        .header p { 
            margin: 5px 0; 
            color: #666; 
            font-size: 12px;
        }
        .section { 
            margin-bottom: 20px; 
        }
        .section h2 { 
            background-color: #f8f9fa; 
            padding: 8px; 
            border-left: 4px solid #007bff; 
            font-size: 14px;
            margin-bottom: 10px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
            font-size: 10px;
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 6px; 
            text-align: left; 
        }
        .table th { 
            background-color: #f8f9fa; 
            font-weight: bold;
        }
        .stat-box { 
            display: inline-block; 
            width: 30%; 
            margin-right: 3%; 
            background: #f8f9fa; 
            padding: 10px; 
            border-radius: 5px; 
            text-align: center; 
            margin-bottom: 10px;
        }
        .stat-number { 
            font-size: 18px; 
            font-weight: bold; 
            color: #007bff; 
        }
        .stat-label { 
            font-size: 10px; 
            color: #666; 
        }
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            color: #666; 
            font-size: 10px; 
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KONSELING BIMBINGAN KONSELING</h1>
        <p>Periode: {{ $nama_bulan }} {{ $tahun }}</p>
        <p>Dibuat pada: {{ $tanggal_generate }}</p>
    </div>

    <div class="section">
        <h2>📊 STATISTIK UTAMA</h2>
        <div class="stat-box">
            <div class="stat-number">{{ $total_konseling }}</div>
            <div class="stat-label">Total Konseling</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $konseling_selesai }}</div>
            <div class="stat-label">Konseling Selesai</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $konseling_berlangsung }}</div>
            <div class="stat-label">Sedang Berlangsung</div>
        </div>
    </div>

    @if($data_per_jenis->count() > 0)
    <div class="section">
        <h2>📈 DATA PER JENIS BIMBINGAN</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Jenis Bimbingan</th>
                    <th>Jumlah</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data_per_jenis as $item)
                <tr>
                    <td>{{ ucfirst($item->jenis_bimbingan) }}</td>
                    <td>{{ $item->total }}</td>
                    <td>
                        @if($total_konseling > 0)
                            {{ number_format(($item->total / $total_konseling) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($top_guru->count() > 0)
    <div class="section">
        <h2>👨‍🏫 TOP GURU BK TERAKTIF</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Guru</th>
                    <th>Jumlah Konseling</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_guru as $item)
                <tr>
                    <td>{{ $item->guru_bk ?: 'Tidak ada data' }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Bimbingan Konseling</p>
        <p>&copy; {{ date('Y') }} - SMK Bimbingan Konseling</p>
    </div>
</body>
</html>