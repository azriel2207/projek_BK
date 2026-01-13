# 🎯 Panduan Visual Fitur Lupa Password

## 📱 Tampilan Halaman Login

```
┌─────────────────────────────────────────────────────────┐
│                                                         │
│                  🔒 Login Sistem BK                     │
│                                                         │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Email                                            │  │
│  │ [masukkan email anda_________________]           │  │
│  │                                                  │  │
│  │ Password                        [Lupa password?] │◄─┤─ KLIK DI SINI
│  │ [masukkan password_______________]              │  │
│  │                                                  │  │
│  │ ☑ Ingat saya      [Lupa password?]            │  │
│  │                                                  │  │
│  │        [🔓 Masuk ke Sistem]                    │  │
│  │                                                  │  │
│  │ Belum punya akun? [Daftar di sini]            │  │
│  └──────────────────────────────────────────────────┘  │
│                                                         │
└─────────────────────────────────────────────────────────┘

💡 Ada DUA tempat untuk klik "Lupa password?":
   1. Sebelah kanan label "Password" (baru ditambah)
   2. Sebelah kanan checkbox "Ingat saya" (sudah ada)
```

---

## 📍 Lokasi Tepat di Welcome Page

Berdasarkan screenshot login Anda:

```
┌──────────────────────────────┐
│ 🔒 Login Sistem BK           │
├──────────────────────────────┤
│                              │
│ 📧 Email                    │
│ [_____________________]      │
│                              │
│ 🔑 Password    Lupa pwd?◄─┐ │
│ [_____________________]   │ │
│                           │ │
│ ➡️ Masuk ke Sistem        │ │
│                           │ │
│ Belum punya akun?         │ │
│ Daftar di sini            │ │
│                              │
└──────────────────────────────┘

🎯 LINK "Lupa password?" berada di SINI ↑
   (Sebelah kanan Password label)
```

---

## 🔄 Alur Proses Lengkap

```
┌────────────────────┐
│  LOGIN PAGE        │
│  👇 Klik "Lupa     │
│     Password?"     │
└─────────┬──────────┘
          │
          ▼
┌─────────────────────────────────┐
│ STEP 1: LUPA PASSWORD           │
│ /password/forgot                │
├─────────────────────────────────┤
│ Masukkan Email:                 │
│ [siswa@example.com____________] │
│                                 │
│ [📨 Kirim Kode Reset]           │
│ [⬅️ Kembali ke Login]           │
└─────────┬───────────────────────┘
          │ Email dikirim ke inbox
          ▼
┌─────────────────────────────────┐
│ 📧 CEK EMAIL                    │
│ Kode: 123456                    │
│ (Berlaku 15 menit)              │
└─────────┬───────────────────────┘
          │ Copy kode
          ▼
┌─────────────────────────────────┐
│ STEP 2: VERIFIKASI KODE         │
│ /password/verify-code           │
├─────────────────────────────────┤
│ Email: siswa@example.com        │
│ Masukkan 6 Digit Kode:          │
│ [1 2 3 4 5 6]                   │
│                                 │
│ [✅ Verifikasi Kode]            │
│ [🔄 Kirim Ulang]                │
│ [🔗 Gunakan Email Lain]        │
└─────────┬───────────────────────┘
          │ Kode benar
          ▼
┌─────────────────────────────────┐
│ STEP 3: RESET PASSWORD BARU     │
│ /password/reset                 │
├─────────────────────────────────┤
│ Password Baru:                  │
│ [•••••••••••••] [👁️]            │
│ [Strength: ███ Kuat]            │
│                                 │
│ Konfirmasi Password:            │
│ [•••••••••••••] [👁️]            │
│                                 │
│ [💾 Simpan Password Baru]       │
└─────────┬───────────────────────┘
          │ Password berhasil diubah
          ▼
┌─────────────────────────────────┐
│ ✅ SUKSES!                      │
│ Redirect ke LOGIN PAGE          │
│                                 │
│ Login dengan:                   │
│ Email: siswa@example.com        │
│ Password: [password baru]       │
└─────────────────────────────────┘
```

---

## 📂 File Structure

```
resources/views/
├── auth/
│   ├── login.blade.php
│   │   └── Link "Lupa password?" ditambah ✅
│   ├── forgot-password.blade.php          ✨ BARU
│   ├── verify-password-reset.blade.php    ✨ BARU
│   ├── reset-password.blade.php           ✨ BARU
│   └── [file lain]
│
├── emails/
│   └── password-reset-code.blade.php      ✨ BARU
│
└── welcome.blade.php
    └── Login form di homepage
        Link "Lupa password?" ditambah ✅

database/migrations/
└── 2025_01_13_000001_create_password_reset_codes_table.php ✨ BARU

app/
├── Models/PasswordResetCode.php           ✨ BARU
├── Mail/PasswordResetCodeMail.php         ✨ BARU
└── Http/Controllers/AuthController.php
    └── Method baru ditambah:
        - showForgotPasswordForm()
        - sendPasswordResetCode()
        - showVerifyPasswordResetForm()
        - verifyPasswordResetCode()
        - showResetPasswordForm()
        - updatePassword()
```

---

## 🧪 Testing Checklist

Untuk memastikan fitur berfungsi:

- [ ] Akses `http://localhost:8000/`
- [ ] Lihat link "Lupa password?" di halaman login
- [ ] Klik link tersebut
- [ ] Masukkan email terdaftar
- [ ] Klik "Kirim Kode Reset"
- [ ] Cek email/console untuk kode
- [ ] Masukkan kode 6 digit
- [ ] Buat password baru
- [ ] Klik "Simpan Password Baru"
- [ ] Redirect ke login
- [ ] Coba login dengan password lama (GAGAL)
- [ ] Login dengan password baru (BERHASIL ✅)

---

## 🔗 Quick Access URLs

Akses langsung tanpa perlu klik:

| Halaman | URL |
|---------|-----|
| Lupa Password | `http://localhost:8000/password/forgot` |
| Verifikasi Kode | `http://localhost:8000/password/verify-code` |
| Reset Password | `http://localhost:8000/password/reset` |

---

## ⚙️ Konfigurasi Email

### Testing Lokal (Menggunakan Log Driver)
```env
MAIL_MAILER=log
```
✅ Kode akan dicetak di `storage/logs/laravel-*.log`

### Menggunakan Gmail SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=your-email@gmail.com
```

---

## 💡 Tips & Tricks

### Cara melihat kode jika menggunakan log driver:
```bash
# Di terminal, jalankan:
tail -f storage/logs/laravel-*.log

# Atau buka file:
# C:\Users\PC_\projek_bk\storage\logs\laravel-YYYY-MM-DD.log
```

### Demo dengan akun yang sudah ada:
Gunakan email dari database users yang sudah terdaftar

### Security notes:
- Kode 6 digit random
- Berlaku hanya 15 menit
- Max 3 percobaan
- Password di-hash bcrypt
- Session data aman

---

**✅ Fitur Siap Digunakan!**

Cukup refresh halaman dan lihat link "Lupa password?" di login page! 🎉
