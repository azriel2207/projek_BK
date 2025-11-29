<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan BK</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #2c5282;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            background-color: #f7fafc;
            padding: 8px;
            border-left: 4px solid #2c5282;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        .stat-card {
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #2c5282;
        }
        .stat-label {
            font-size: 10px;
            color: #718096;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            font-size: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #718096;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN BULANAN BIMBINGAN KONSELING</h1>
        <p>Bulan: {{ $nama_bulan }} {{ $tahun }}</p>
        <p>Dibuat pada: {{ $tanggal_generate }}</p>
    </div>

    <div class="section">
        <h2>STATISTIK UTAMA</h2>
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $total_konseling }}</div>
                <div class="stat-label">Total Konseling</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $selesai }}</div>
                <div class="stat-label">Selesai</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $menunggu }}</div>
                <div class="stat-label">Menunggu</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $dikonfirmasi }}</div>
                <div class="stat-label">Dikonfirmasi</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>DISTRIBUSI JENIS KONSELING</h2>
        @if($jenis_konseling->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Jenis Bimbingan</th>
                    <th>Jumlah</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jenis_konseling as $jenis => $jumlah)
                @php
                    $persentase = $total_konseling > 0 ? ($jumlah / $total_konseling) * 100 : 0;
                @endphp
                <tr>
                    <td>{{ ucfirst($jenis) }}</td>
                    <td>{{ $jumlah }}</td>
                    <td>{{ number_format($persentase, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">Tidak ada data jenis konseling</div>
        @endif
    </div>

    

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Bimbingan Konseling</p>
        <p>© {{ date('Y') }} - Semua Hak Dilindungi</p>
    </div>
</body>
</html>