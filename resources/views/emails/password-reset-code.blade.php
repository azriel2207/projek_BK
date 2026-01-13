<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 20px;
        }
        .code-box {
            background-color: #f8f9fa;
            border: 2px solid #007bff;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code-box .label {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .code-box .code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 14px;
        }
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        .info {
            background-color: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Reset Password</h1>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>

            <p>Kami menerima permintaan untuk mereset password akun Anda. Gunakan kode di bawah ini untuk melanjutkan proses reset password.</p>

            <div class="code-box">
                <div class="label">Kode Reset Password Anda:</div>
                <div class="code">{{ $code }}</div>
            </div>

            <div class="info">
                <strong>ℹ️ Informasi Penting:</strong>
                <ul style="margin: 8px 0; padding-left: 20px;">
                    <li>Kode ini berlaku selama 15 menit</li>
                    <li>Jangan bagikan kode ini kepada siapapun</li>
                    <li>Kode hanya dapat digunakan 3 kali percobaan</li>
                </ul>
            </div>

            <div class="warning">
                <strong>⚠️ Jika Anda tidak melakukan permintaan ini:</strong> Abaikan email ini. Akun Anda akan tetap aman.
            </div>

            <p>Butuh bantuan? Hubungi administrator sistem.</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Sistem BK. Semua hak dilindungi.</p>
            <p>Email ini dikirim ke {{ $user->email }}</p>
        </div>
    </div>
</body>
</html>
