# 🎓 Aplikasi BK (Bimbingan Konseling) – UKK 2526

> **Progress Terakhir:** Setup awal

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
<img src="https://img.shields.io/badge/Laravel-12.x-red" alt="Laravel Version">
<img src="https://img.shields.io/badge/Status-Development-orange" alt="Status">
</p>

---

## 📌 Tentang Proyek

Aplikasi **BK (Bimbingan Konseling)** adalah platform digital berbasis web yang digunakan untuk membantu guru BK, siswa, dan pihak sekolah mengelola layanan konseling dengan lebih efisien dan terdokumentasi.

### 🎯 Tujuan Utama

-   **Mencatat data siswa** (identitas, riwayat, perilaku).
-   **Mengatur jadwal konseling** antara guru BK dan siswa.
-   **Mencatat hasil sesi konseling** & tindak lanjut.
-   **Menyediakan laporan perkembangan** siswa per periode.
-   **Fitur curhat rahasia** agar siswa bisa menyampaikan masalah dengan aman.

### 👥 Pengguna Utama

-   **Siswa:** Mengajukan konseling, melaporkan masalah, atau curhat online.
-   **Guru BK/Konselor:** Menangani laporan, menjadwalkan sesi, memberi rekomendasi.
-   **Wali Kelas/Guru Mapel:** Memberi informasi tambahan tentang siswa.
-   **Orang Tua (Opsional):** Melihat perkembangan anaknya.
-   **Admin Sekolah:** Mengelola user, data, dan hak akses aplikasi.

### 🕒 Waktu Penggunaan

-   Saat siswa menghadapi masalah pribadi, akademik, atau sosial.
-   Saat guru BK mendata laporan kasus siswa.
-   Saat sekolah membutuhkan rekap laporan bulanan/semester.

### 🔑 Fitur Utama

-   🔑 **Login multi-role** (siswa, guru BK, admin).
-   🗓 **Jadwal Konseling** & notifikasi otomatis.
-   📝 **Catatan Hasil Konseling** & tindak lanjut.
-   📢 **Pengaduan/Curhat Rahasia** (bisa anonim).
-   📊 **Laporan & Dashboard Statistik Kasus.**
-   🔗 **Integrasi dengan Sistem Akademik** (opsional).

---

## 🔄 Cara Clone Branch Ini

Gunakan perintah berikut untuk clone hanya branch ini saja:

```bash
git clone --branch aplikasi_bk --single-branch https://github.com/riskiputraalamzah/ukk2526.git aplikasi_bk
```

Lalu masuk ke folder project:

```bash
cd aplikasi_bk
```

---

## 🚀 Cara Menjalankan Aplikasi

Pastikan environment Laravel sudah siap (PHP, Composer, dan database server). Lalu jalankan:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Aplikasi akan berjalan di: `http://127.0.0.1:8000`

---

## 💬 Penutup

Semangat untuk teman-teman kelas 12 RPL yang sedang mengerjakan **UKK 2526**! 💪
Kerjakan dengan teliti, update bagian _Progress Terakhir_ di README ini setiap ada fitur baru, dan jaga kerapihan kode agar mudah dipresentasikan dan dinilai dengan baik. 🚀
