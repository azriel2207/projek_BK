# 📌 QUICK REFERENCE - EMAIL OTP SYSTEM

## ✅ Status: WORKING

Email OTP verification system sudah berfungsi dengan baik dan email terkirim instant.

---

## 🚀 Quick Start

### 1. Clear Cache (hanya sekali)
```bash
php artisan config:clear
php artisan cache:clear
```

### 2. Start Server
```bash
php artisan serve
```

### 3. Test Registrasi
- URL: http://localhost:8000/register
- Fill form (name, email, password)
- Submit → Email OTP terkirim dalam < 5 detik
- Input OTP di verify-code form
- Done! 🎉

---

## 📧 Email Configuration

| Setting | Value |
|---------|-------|
| Mailer | smtp |
| Host | smtp.gmail.com |
| Port | 587 |
| Encryption | tls |
| Username | azrielanhar4@gmail.com |
| Password | klhn wkiv pskt aupa |
| Queue | sync |
| Status | ✅ Working |

---

## 🔧 Files Modified

```
✅ app/Notifications/SendVerificationCodeEmail.php
   - Removed: ShouldQueue, Queueable
   - Result: Synchronous sending

✅ .env
   - Changed: QUEUE_CONNECTION=database → QUEUE_CONNECTION=sync
   - Result: Instant email delivery
```

---

## 🎯 OTP Flow

```
1. Register → 2. Create User + Code → 3. Send Email (Sync)
   ↓
4. Email terkirim < 5 detik ✅
   ↓
5. User input OTP → 6. Verify → 7. Mark verified
   ↓
8. Redirect Dashboard ✅
```

---

## 📊 Performance

- Email delivery time: **< 5 seconds** ⚡
- Response to user: **Instant** ✅
- No background processing needed: **Yes** ✅
- Reliability: **High** ✅

---

## 🛠️ Common Commands

```bash
# Clear cache
php artisan config:clear && php artisan cache:clear

# Start server
php artisan serve

# View logs
tail -f storage/logs/laravel.log

# Test email (via tinker)
php artisan tinker
> Mail::raw('Test', fn($m) => $m->to('test@gmail.com')->subject('Test'))
```

---

## 🔒 Gmail Requirements

- ✅ 2FA enabled
- ✅ App Password generated (16 char)
- ✅ Correct password in `.env`
- ✅ Less secure apps: Not needed

---

## ⚠️ Troubleshooting

### Email tidak terkirim?
1. Run `php artisan config:clear`
2. Check `.env` QUEUE_CONNECTION=sync
3. Check `.env` MAIL_* config
4. View logs: `tail -f storage/logs/laravel.log`
5. Verify Gmail 2FA enabled

### Rate limiting?
- Resend limit: 3x per 1 menit
- Attempt limit: 5x wrong code
- Code expires: 15 menit

---

## 📝 Important Notes

- **Sync Mode** → Email langsung terkirim, cocok untuk development & small-scale
- **For High Traffic** → Switch to Redis queue + queue worker
- **Gmail** → Menggunakan App Password, bukan password akun biasa

---

## 🎉 Ready to Use

Sistem sudah siap! Email OTP verification working 100%.

---

**Last Updated:** December 1, 2025
**Version:** 1.0
