<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Konseling</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px 10px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
            color: #333;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .stat-box {
            display: table-cell;
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            width: 25%;
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
            font-size: 11px;
        }
        .section-title {
            background-color: #2563eb;
            color: white;
            padding: 10px;
            margin-top: 20px;
            margin-bottom: 15px;
            font-weight: bold;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f3f4f6;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .center {
            text-align: center;
        }
        .highlight {
            background-color: #fef3c7;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin: 5px 0;
        }
        .progress-fill {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 5px;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN BULANAN BIMBINGAN KONSELING</h1>
        <p>Bulan: <strong>{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</strong></p>
        <p>Dibuat pada: {{ $tanggal_generate }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Guru BK:</td>
            <td>{{ $guru_bk }}</td>
        </tr>
    </table>

    <!-- STATISTIK UTAMA -->
    <div class="section-title">STATISTIK UTAMA</div>
    <div class="stats">
        <div class="stat-box">
            <div class="stat-number">{{ $total_konseling }}</div>
            <div class="stat-label">Total Konseling</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $konseling_selesai }}</div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $konseling_pending }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">
                @if($total_konseling > 0)
                    {{ number_format(($konseling_selesai / $total_konseling) * 100, 0) }}%
                @else
                    0%
                @endif
            </div>
            <div class="stat-label">Tingkat Penyelesaian</div>
        </div>
    </div>

    <!-- DISTRIBUSI JENIS KONSELING -->
    <div class="section-title">DISTRIBUSI JENIS KONSELING</div>
    @if($data_per_jenis && count($data_per_jenis) > 0)
        @php
            $colorMap = [
                'pribadi' => '#2563eb',
                'belajar' => '#16a34a',
                'karir' => '#7c3aed',
                'sosial' => '#f97316'
            ];
        @endphp
        @foreach($data_per_jenis as $jenis)
            @php
                $percentage = ($jenis->total / max($total_konseling, 1)) * 100;
                $color = $colorMap[$jenis->jenis_bimbingan] ?? '#6b7280';
            @endphp
            <div style="margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <span style="font-weight: bold; text-transform: capitalize;">{{ $jenis->jenis_bimbingan }}</span>
                    <span style="font-weight: bold;">{{ $jenis->total }} ({{ number_format($percentage, 1) }}%)</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $percentage }}%; background-color: {{ $color }};">
                        {{ number_format($percentage, 1) }}%
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div style="text-align: center; padding: 20px; color: #999;">
            Tidak ada data jenis konseling
        </div>
    @endif

    <!-- STATUS KONSELING -->
    <div class="section-title">STATUS KONSELING</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th style="text-align: center;">Jumlah</th>
                <th style="text-align: center;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statuses = [
                    'selesai' => 'Selesai',
                    'dikonfirmasi' => 'Dikonfirmasi',
                    'menunggu' => 'Menunggu',
                    'dibatalkan' => 'Dibatalkan'
                ];
            @endphp
            @foreach($statuses as $statusKey => $statusLabel)
                @php
                    $count = $detail_konseling->where('status', $statusKey)->count();
                    $pct = $total_konseling > 0 ? ($count / $total_konseling) * 100 : 0;
                @endphp
                <tr>
                    <td>{{ $statusLabel }}</td>
                    <td class="center">{{ $count }}</td>
                    <td class="center">{{ number_format($pct, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- DETAIL KONSELING TERBARU -->
    <div class="section-title">DETAIL KONSELING TERBARU</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Siswa</th>
                <th>Jenis Bimbingan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detail_konseling as $key => $data)
                <tr>
                    <td class="center">{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d M Y') }}</td>
                    <td>{{ $data->siswa_name }}</td>
                    <td style="text-transform: capitalize;">{{ $data->jenis_bimbingan }}</td>
                    <td>
                        <span style="
                            @if($data->status === 'selesai')
                                background-color: #dcfce7; color: #166534;
                            @elseif($data->status === 'dikonfirmasi')
                                background-color: #dbeafe; color: #1e40af;
                            @elseif($data->status === 'menunggu')
                                background-color: #fef3c7; color: #92400e;
                            @elseif($data->status === 'dibatalkan')
                                background-color: #fee2e2; color: #991b1b;
                            @endif
                            padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold;
                        ">
                            {{ ucfirst($data->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">Tidak ada data konseling</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem BK pada {{ $tanggal_generate }}</p>
    </div>
</body>
</html>