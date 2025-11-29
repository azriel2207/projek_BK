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
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Konseling</h1>
        <p>Sistem BK (Bimbingan Konseling)</p>
        <p>Periode: <strong>{{ $periode }}</strong></p>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Guru BK:</td>
            <td>{{ $guru_bk }}</td>
        </tr>
        <tr>
            <td class="info-label">Tanggal Laporan:</td>
            <td>{{ $tanggal_generate }}</td>
        </tr>
    </table>

    <!-- Statistik Ringkas -->
    <div class="stats">
        <div class="stat-box">
            <div class="stat-number">{{ $total_konseling }}</div>
            <div class="stat-label">Total Konseling</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $konseling_selesai }}</div>
            <div class="stat-label">Konseling Selesai</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $konseling_pending }}</div>
            <div class="stat-label">Konseling Pending</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">
                @if($total_konseling > 0)
                    {{ number_format(($konseling_selesai / $total_konseling) * 100, 1) }}%
                @else
                    0%
                @endif
            </div>
            <div class="stat-label">Tingkat Penyelesaian</div>
        </div>
    </div>

    <!-- Data Per Jenis Bimbingan -->
    <div class="section-title">Konseling Per Jenis Bimbingan</div>
    <table>
        <thead>
            <tr>
                <th>Jenis Bimbingan</th>
                <th style="text-align: center;">Jumlah</th>
                <th style="text-align: center;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data_per_jenis as $jenis)
                <tr>
                    <td style="text-transform: capitalize;">{{ $jenis->jenis_bimbingan }}</td>
                    <td class="center">{{ $jenis->total }}</td>
                    <td class="center">
                        @if($total_konseling > 0)
                            {{ number_format(($jenis->total / $total_konseling) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Detail Konseling -->
    <div class="section-title">Detail Konseling</div>
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
                    <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d M Y H:i') }}</td>
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
                            padding: 4px 8px; border-radius: 4px; font-size: 10px;
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
