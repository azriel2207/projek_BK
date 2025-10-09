<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifikasi Email</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3b82f6; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; }
        .code { 
            background: #1e3a8a; 
            color: white; 
            padding: 15px; 
            font-size: 24px; 
            font-weight: bold; 
            text-align: center; 
            letter-spacing: 5px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer { 
            background: #e5e7eb; 
            padding: 20px; 
            text-align: center; 
            font-size: 12px; 
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sistem BK Sekolah</h1>
            <p>Verifikasi Alamat Email Anda</p>
        </div>
        
        <div class="content">
            <h2>Halo, {{ $name }}!</h2>
            <p>Terima kasih telah mendaftar di Sistem Bimbingan Konseling Sekolah. Untuk mengaktifkan akun Anda, silakan gunakan kode verifikasi berikut:</p>
            
            <div class="code">
                {{ $verificationCode }}
            </div>
            
            <p>Masukkan kode ini pada halaman verifikasi untuk menyelesaikan proses pendaftaran.</p>
            <p>Kode ini akan kedaluwarsa dalam 24 jam.</p>
            
            <p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Sistem Bimbingan Konseling Sekolah. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>