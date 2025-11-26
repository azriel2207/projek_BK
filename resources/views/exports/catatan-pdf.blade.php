<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Catatan Konseling</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #333; }
        .header p { margin: 5px 0; color: #666; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background-color: #f8f9fa; border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table td { border: 1px solid #ddd; padding: 8px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; }
        .badge-selesai { background-color: #d4edda; color: #155724; }
        .badge-proses { background-color: #fff3cd; color: #856404; }
        .badge-dijadwalkan { background-color: #e2e3e5; color: #383d41; }
        .summary { margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; }
        .footer { margin-top: 30px; text-align: center; color: #666; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CATATAN KONSELING SISWA</h1>
        <p>Sistem Bimbingan dan Konseling</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    @if($catatan->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Siswa</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Status</th>
                <th>Hasil Konseling</th>
            </tr>
        </thead>
        <tbody>
            @foreach($catatan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->siswa->name }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->tanggal_konseling->format('d/m/Y') }}</td>
                <td>{{ ucfirst($item->jenis_konseling) }}</td>
                <td>
                    <span class="badge badge-{{ $item->status }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
                <td>{{ $item->hasil_konseling ? substr($item->hasil_konseling, 0, 50) . '...' : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Ringkasan Statistik</h3>
        <p>Total Catatan: <strong>{{ $catatan->count() }}</strong></p>
        <p>Selesai: <strong>{{ $catatan->where('status', 'selesai')->count() }}</strong></p>
        <p>Dalam Proses: <strong>{{ $catatan->where('status', 'proses')->count() }}</strong></p>
        <p>Dijadwalkan: <strong>{{ $catatan->where('status', 'dijadwalkan')->count() }}</strong></p>
    </div>
    @else
    <div style="text-align: center; padding: 40px;">
        <p>Tidak ada data catatan konseling untuk ditampilkan.</p>
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari Sistem Counseling</p>
    </div>
</body>
</html>