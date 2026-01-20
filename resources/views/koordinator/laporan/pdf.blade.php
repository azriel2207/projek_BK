<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Konseling BK - {{ $periode }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body { 
            font-family: 'Calibri', 'Arial', sans-serif; 
            margin: 0;
            padding: 18px;
            font-size: 10px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            max-width: 850px;
            margin: 0 auto;
        }
        .letterhead {
            text-align: center;
            border-bottom: 3px solid #1a1a1a;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 1px;
            letter-spacing: 0.5px;
        }
        .school-info {
            font-size: 9px;
            color: #555;
            margin-bottom: 1px;
        }
        .letter-header {
            margin-bottom: 15px;
        }
        .letter-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 9px;
        }
        .letter-meta-left {
            flex: 1;
        }
        .letter-meta-right {
            flex: 1;
            text-align: right;
        }
        .meta-item {
            margin-bottom: 1px;
        }
        .meta-label {
            display: inline-block;
            width: 75px;
            font-weight: bold;
        }
        .recipients {
            margin-bottom: 12px;
            font-size: 9px;
            line-height: 1.6;
        }
        .recipients-title {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .letter-body {
            margin-bottom: 12px;
            text-align: justify;
            line-height: 1.6;
            font-size: 9px;
        }
        .letter-opening {
            margin-bottom: 8px;
        }
        .section { 
            margin-bottom: 12px;
        }
        .section-title { 
            background-color: #0051a5;
            color: white;
            padding: 6px 8px;
            border-left: 4px solid #003d7a;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-container {
            width: 100%;
            margin-bottom: 12px;
        }
        .stat-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10px;
            border: 1px solid #0051a5;
        }
        .stat-table th {
            background-color: #fff3cd;
            color: #333;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #0051a5;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .stat-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #0051a5;
            font-size: 11px;
        }
        .stat-table td.stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #0051a5;
            background-color: #f0f7ff;
        }
        .stat-box { 
            display: none;
        }
        .stat-number { 
            display: none;
        }
        .stat-label { 
            display: none;
        }
        .table { 
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
            border: 1px solid #999;
        }
        .table th { 
            background-color: #0051a5;
            color: white;
            padding: 6px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
            letter-spacing: 0.2px;
            border: 1px solid #0051a5;
        }
        .table td { 
            border: 1px solid #ddd;
            padding: 5px 6px;
            text-align: left;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        .table tbody tr:nth-child(even) {
            background-color: #fff;
        }
        .percentage {
            color: #0051a5;
            font-weight: bold;
        }
        .closing-text {
            margin-top: 10px;
            font-size: 9px;
            text-align: justify;
            line-height: 1.6;
        }
        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-box {
            text-align: center;
            width: 180px;
        }
        .signature-box p {
            margin: 1px 0;
            font-size: 9px;
        }
        .signature-title {
            font-weight: bold;
            margin-bottom: 35px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-nip {
            font-size: 8px;
            color: #666;
        }
        .info-box {
            background-color: #f0f7ff;
            border-left: 4px solid #0051a5;
            padding: 6px;
            margin-bottom: 10px;
            font-size: 9px;
            border-radius: 2px;
        }
        .highlight-green {
            color: #27ae60;
            font-weight: bold;
        }
        .highlight-orange {
            color: #e67e22;
            font-weight: bold;
        }
        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #999;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Letterhead -->
        <div class="letterhead">
            <div class="school-name">SMK BIMBINGAN KONSELING</div>
            <div class="school-info">Jl. Pendidikan No. 123, Medan</div>
            <div class="school-info">Telepon: (061) 123456 | Email: bk@smkbk.sch.id</div>
        </div>

        <!-- Letter Metadata -->
        <div class="letter-meta">
            <div class="letter-meta-left">
                <div class="meta-item">
                    <span class="meta-label">Nomor</span>: 00{{ date('m') }}/BK/{{ date('m') }}/{{ date('Y') }}
                </div>
                <div class="meta-item">
                    <span class="meta-label">Lampiran</span>: 1 (satu) berkas
                </div>
                <div class="meta-item">
                    <span class="meta-label">Perihal</span>: <strong>Laporan Konseling Bimbingan</strong>
                </div>
            </div>
            <div class="letter-meta-right">
                <div class="meta-item">Sidoarjo, {{ date('d F Y') }}</div>
            </div>
        </div>

        <!-- Recipients -->
        <div class="recipients">
            <div class="recipients-title">Kepada Yth.</div>
            <div>Kepala Sekolah</div>
            <div>SMK Antartika 1 Sidoarjo</div>
            <div>Di Sidoarjo</div>
        </div>

        <!-- Letter Opening -->
        <div class="letter-body">
            <div class="letter-opening">
                <strong>Assalamu'alaikum Warahmatullahi Wabarakatuh</strong>
            </div>

            <p style="text-align: justify; margin-bottom: 12px;">
                Dengan hormat, kami sampaikan laporan hasil pelaksanaan kegiatan Bimbingan dan Konseling pada bulan <strong>{{ $nama_bulan }} {{ $tahun }}</strong>. 
                Laporan ini berisi ringkasan statistik, analisis data konseling, serta performa guru BK selama periode tersebut.
            </p>
        </div>

        <!-- Data Sections -->
        <div class="section">
            <div class="section-title">A. RINGKASAN STATISTIK UTAMA</div>
            <div class="stat-container">
                <table class="stat-table">
                    <thead>
                        <tr>
                            <th>Total Konseling</th>
                            <th>Konseling Selesai</th>
                            <th>Data Konseling di Batalkan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="stat-value">{{ $total_konseling }}</td>
                            <td class="stat-value highlight-green">{{ $konseling_selesai }}</td>
                            <td class="stat-value highlight-orange">{{ $konseling_dibatalkan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($data_per_jenis->count() > 0)
        <div class="section">
            <div class="section-title">B. ANALISIS KONSELING BERDASARKAN JENIS BIMBINGAN</div>
            <div class="info-box">
                Distribusi konseling berdasarkan kategori jenis bimbingan selama periode {{ $nama_bulan }} {{ $tahun }}:
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 10%;">No</th>
                        <th style="width: 50%;">Jenis Bimbingan</th>
                        <th style="width: 20%; text-align: center;">Jumlah</th>
                        <th style="width: 20%; text-align: center;">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php $nomor = 1; @endphp
                    @foreach($data_per_jenis as $item)
                    <tr>
                        <td>{{ $nomor }}</td>
                        <td>{{ ucfirst($item->jenis_bimbingan) }}</td>
                        <td style="text-align: center;">{{ $item->total }}</td>
                        <td class="percentage" style="text-align: center;">
                            @if($total_konseling > 0)
                                {{ number_format(($item->total / $total_konseling) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </td>
                    </tr>
                    @php $nomor++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($top_guru->count() > 0)
        <div class="section">
            <div class="section-title">C. GURU BK PALING AKTIF DAN PRODUKTIF</div>
            <div class="info-box">
                Peringkat guru BK berdasarkan jumlah konseling yang telah dilaksanakan pada periode ini:
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 10%;">No</th>
                        <th style="width: 70%;">Nama Guru BK</th>
                        <th style="width: 20%; text-align: center;">Jumlah Konseling</th>
                    </tr>
                </thead>
                <tbody>
                    @php $nomor = 1; @endphp
                    @foreach($top_guru as $item)
                    <tr>
                        <td>{{ $nomor }}</td>
                        <td>{{ $item->guru_bk ?: 'Tidak ada data' }}</td>
                        <td style="text-align: center;"><strong>{{ $item->total }}</strong></td>
                    </tr>
                    @php $nomor++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Closing -->
        <div class="closing-text">
            <p>
                Demikian laporan ini kami sampaikan sebagai bentuk transparansi dan akuntabilitas pelaksanaan program Bimbingan dan Konseling. 
                Kami berharap dapat terus meningkatkan kualitas layanan bimbingan konseling untuk mendukung perkembangan siswa secara optimal.
            </p>
            <p style="margin-top: 12px;">
                Atas perhatian dan dukungan Bapak/Ibu, kami ucapkan terima kasih.
            </p>
            <p style="margin-top: 10px;">
                <strong>Wassalamu'alaikum Warahmatullahi Wabarakatuh</strong>
            </p>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <p class="signature-title">KEPALA SEKOLAH</p>
                <p class="signature-name">_______________________</p>
                <p class="signature-nip">NIP: _____________________</p>
            </div>
        </div>

        <div class="footer">
            <p style="margin-top: 10px;">Laporan ini dibuat secara otomatis oleh Sistem Bimbingan Konseling SMK</p>
            <p>&copy; {{ date('Y') }} - SMK Bimbingan Konseling</p>
        </div>
    </div>
</body>
</html>