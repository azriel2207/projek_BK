<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: white;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #1d4ed8;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .warning {
            background-color: #fef3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verifikasi Email Anda</h1>
            <p>Sistem BK Sekolah</p>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $notifiable->name }}</strong>,</p>
            
            <p>Terima kasih telah mendaftar di Sistem BK Sekolah. Untuk menyelesaikan proses registrasi, silakan verifikasi email Anda dengan mengklik tombol di bawah:</p>
            
            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="button">Verifikasi Email</a>
            </div>
            
            <p>Atau gunakan link ini:</p>
            <p style="word-break: break-all; background-color: #f5f5f5; padding: 10px; border-radius: 3px;">
                {{ $actionUrl }}
            </p>
            
            <div class="warning">
                <strong>⚠️ Perhatian:</strong>
                <p>Link verifikasi ini berlaku selama 60 menit. Jika link telah kadaluarsa, Anda dapat meminta link verifikasi baru dari halaman login.</p>
            </div>
            
            <p>Jika Anda tidak mendaftar akun ini, abaikan email ini.</p>
            
            <p>Salam hormat,<br>
            <strong>Tim Sistem BK Sekolah</strong></p>
        </div>
        
        <div class="footer">
            <p>Ini adalah email otomatis. Silakan tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Sistem BK Sekolah. Hak cipta dilindungi.</p>
        </div>
    </div>
</body>
</html>
