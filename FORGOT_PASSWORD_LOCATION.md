# 📍 Letak Fitur Lupa Password

## Akses dari Halaman Login

### Di Welcome Page (Halaman Depan)
- **URL**: `/` (halaman utama)
- **Lokasi di halaman**: 
  - Kolom Password, sebelah kanan ada link **"Lupa password?"**
  - Letaknya di antara label "Password" dan input field

### Di Auth Login Page
- **URL**: `/login` 
- **Lokasi di halaman**: 
  - Sebelah kanan checkbox "Ingat saya" ada link **"Lupa password?"**

---

## Alur Lengkap Fitur

### Step 1: Halaman Lupa Password
- **URL**: `/password/forgot`
- **View**: `resources/views/auth/forgot-password.blade.php`
- **Deskripsi**: 
  - Form input email
  - Button "Kirim Kode Reset"
  - Link kembali ke login
- **Akses dari**: Klik "Lupa password?" di login page

### Step 2: Verifikasi Kode
- **URL**: `/password/verify-code`
- **View**: `resources/views/auth/verify-password-reset.blade.php`
- **Deskripsi**: 
  - Input 6 digit kode yang dikirim ke email
  - Max 3 kali percobaan
  - Kode berlaku 15 menit
  - Button "Verifikasi Kode"
  - Option "Kirim Ulang" atau "Gunakan Email Lain"
- **Otomatis redirect ke sini**: Setelah user submit email di step 1

### Step 3: Reset Password Baru
- **URL**: `/password/reset`
- **View**: `resources/views/auth/reset-password.blade.php`
- **Deskripsi**: 
  - Input password baru (min 8 karakter)
  - Konfirmasi password
  - Password strength indicator
  - Button "Simpan Password Baru"
- **Otomatis redirect ke sini**: Setelah kode berhasil diverifikasi

---

## File yang Perlu Diketahui

### View Files (Frontend)
```
resources/views/auth/
├── forgot-password.blade.php           ← Step 1: Lupa password
├── verify-password-reset.blade.php     ← Step 2: Verifikasi kode
├── reset-password.blade.php            ← Step 3: Reset password baru
├── login.blade.php                     ← Login (sudah ada link lupa password)
└── welcome.blade.php                   ← Homepage (sudah ada link lupa password)

resources/views/emails/
└── password-reset-code.blade.php       ← Template email yang dikirim ke user
```

### Backend Files
```
app/
├── Models/PasswordResetCode.php        ← Model untuk kode reset
├── Http/Controllers/AuthController.php ← Logic untuk forgot password
└── Mail/PasswordResetCodeMail.php      ← Mailable untuk email
```

### Database
```
database/migrations/
└── 2025_01_13_000001_create_password_reset_codes_table.php
```

---

## Testing di Browser

### Cara Testing Lokal:

1. **Buka halaman login**
   ```
   http://localhost:8000/
   ```

2. **Cari link "Lupa password?"**
   - Letaknya di sebelah label Password atau di sebelah kanan checkbox "Ingat saya"

3. **Klik link tersebut**
   - Akan redirect ke `/password/forgot`

4. **Masukkan email terdaftar**
   - Contoh: `siswa@example.com` (pastikan email ada di database users)
   - Klik "Kirim Kode Reset"

5. **Cek kode di email atau console**
   - Jika `MAIL_MAILER=log`: Buka `storage/logs/laravel-*.log` untuk melihat kode
   - Jika sudah setup SMTP: Kode akan di email ke user

6. **Masukkan kode 6 digit**
   - Kode berlaku 15 menit
   - Max 3 kali percobaan

7. **Buat password baru**
   - Min 8 karakter
   - Lihat indikator kekuatan password

8. **Simpan dan login dengan password baru**

---

## Troubleshooting

### ❓ Link "Lupa password?" tidak muncul?
✅ **Solusi**: 
- Refresh halaman (Ctrl+F5 atau Cmd+Shift+R)
- Clear browser cache
- Pastikan server Laravel masih jalan (`php artisan serve`)

### ❓ Email kode tidak diterima?
✅ **Solusi**:
- Jika menggunakan log driver: Check `storage/logs/laravel-*.log`
- Jika SMTP: Pastikan `.env` sudah dikonfigurasi dengan benar
- Cek apakah email ada di database users

### ❓ Kode tidak bisa diverifikasi?
✅ **Solusi**:
- Pastikan kode belum expired (15 menit)
- Cek apakah sudah 3 kali percobaan salah
- Gunakan opsi "Kirim Ulang" untuk request kode baru

### ❓ Masuk dengan password lama tidak bisa?
✅ **Solusi**: 
- Ini normal! Password sudah diganti
- Login dengan password baru yang sudah dibuat

---

## Routes Tersedia

| HTTP | URL | Route Name | Middleware |
|------|-----|-----------|-----------|
| GET | `/password/forgot` | `password.forgot` | guest |
| POST | `/password/send-code` | `password.send-code` | guest |
| GET | `/password/verify-code` | `password.verify-code` | guest |
| POST | `/password/verify-code` | `password.verify-submit` | guest |
| GET | `/password/reset` | `password.reset-form` | guest |
| POST | `/password/reset` | `password.update` | guest |

---

## Direct Links

Jika ingin akses langsung tanpa klik link:

- **Lupa Password**: `http://localhost:8000/password/forgot`
- **Verifikasi Kode**: `http://localhost:8000/password/verify-code`
- **Reset Password**: `http://localhost:8000/password/reset`

---

**Status**: ✅ Semua fitur sudah terimplementasi dan siap digunakan!
