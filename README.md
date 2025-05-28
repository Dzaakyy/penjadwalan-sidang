<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/Dzaakyy/penjadwalan-sidang/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Sistem Penjadwalan Sidang

<!-- ![Banner](https://via.placeholder.com/800x200.png?text=Sistem+Penjadwalan+Sidang)   -->
*Sistem Penjadwalan Sidang - Aplikasi manajemen jadwal sidang berbasis Laravel dan MySQL.*

**Sistem Penjadwalan Sidang** adalah aplikasi web yang dikembangkan menggunakan framework **Laravel** dengan basis data **MySQL**. Aplikasi ini dirancang untuk mempermudah pengelolaan jadwal sidang, termasuk sidang Praktik Kerja Lapangan (PKL), Seminar Proposal (Sempro), dan Tugas Akhir (TA), serta mendukung proses bimbingan untuk PKL dan TA. Aplikasi ini menggunakan **Tailwind CSS** untuk styling dan **Vite** untuk build frontend modern.

## Fitur Utama
- **Penjadwalan Sidang**: Mengatur jadwal untuk sidang PKL, Sempro, dan TA dengan mudah.
- **Manajemen Bimbingan**: Mendukung proses bimbingan untuk PKL dan TA, termasuk pencatatan progres.
- **Notifikasi Jadwal**: Memberikan pemberitahuan kepada dosen dan mahasiswa terkait jadwal sidang.
- **Manajemen Pengguna**: Mengelola data mahasiswa, dosen, dan staf administrasi.
- **Laporan Sidang**: Menyediakan laporan hasil sidang untuk keperluan dokumentasi.

## Teknologi yang Digunakan
- **Framework**: Laravel (PHP)
- **Database**: MySQL
- **Frontend**: Blade (Laravel), Tailwind CSS, HTML, JavaScript
- **Build Tools**: Vite
- **Tools**: Visual Studio Code, XAMPP, Composer, npm, Git

## Prasyarat
Untuk menjalankan proyek ini secara lokal, pastikan Anda telah menginstal:
- [PHP](https://www.php.net/downloads.php) (versi 8.0 atau lebih tinggi)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/downloads/)
- [XAMPP](https://www.apachefriends.org/index.html) atau server lokal lainnya
- [Node.js](https://nodejs.org/) (versi 18 atau lebih tinggi)
- [npm](https://www.npmjs.com/)
- [Git](https://git-scm.com/downloads)

## Instalasi
1. **Clone repositori**:
   ```bash
   git clone https://github.com/Dzaakyy/penjadwalan-sidang.git
   ```
2. **Masuk ke direktori proyek**:
   ```bash
   cd penjadwalan-sidang
   ```
3. **Instal dependensi PHP**:
   ```bash
   composer install
   ```
4. **Instal dependensi frontend**:
   ```bash
   npm install
   ```
5. **Konfigurasi environment**:
   - Salin file `.env.example` menjadi `.env`:
     ```bash
     cp .env.example .env
     ```
   - Sesuaikan konfigurasi database di file `.env` (misalnya, nama database, username, dan password).
6. **Buat database MySQL**:
   - Buat database bernama `penjadwalan_sidang` di MySQL.
   - Jalankan migrasi untuk membuat tabel:
     ```bash
     php artisan migrate
     ```
   - (Opsional) Impor data awal dengan seeder, jika ada:
     ```bash
     php artisan db:seed
     ```
7. **Generate application key**:
   ```bash
   php artisan key:generate
   ```
8. **Build aset frontend dengan Vite**:
   ```bash
   npm run dev
   ```
   Atau untuk build produksi:
   ```bash
   npm run build
   ```
9. **Jalankan server lokal**:
   ```bash
   php artisan serve
   ```
10. Buka aplikasi di browser: `http://localhost:8000`.

## Struktur Proyek
```
penjadwalan-sidang/
├── app/                # Logika aplikasi (Models, Controllers, dll.)
├── bootstrap/          # Inisialisasi Laravel
├── config/             # Konfigurasi aplikasi
├── database/           # Migrasi dan seeder database
├── node_modules/       # Dependensi frontend (dari npm)
├── public/             # Aset publik (CSS, JS, gambar)
├── resources/          # View (Blade) dan aset frontend
├── routes/             # Definisi rute aplikasi
├── storage/            # Log, cache, dan file yang dihasilkan
├── tests/              # Tes unit dan fitur
├── vendor/             # Dependensi PHP (dari Composer)
├── .env.example        # Contoh file konfigurasi
├── .gitignore          # File yang diabaikan oleh Git
├── artisan             # CLI Laravel
├── composer.json       # Konfigurasi dependensi PHP
├── composer.lock       # Kunci dependensi PHP
├── package.json        # Konfigurasi dependensi frontend
├── package-lock.json   # Kunci dependensi frontend
├── phpunittest.xml     # Konfigurasi tes unit PHP
├── postcss.config.js   # Konfigurasi PostCSS
├── tailwind.config.js  # Konfigurasi Tailwind CSS
├── vite.config.js      # Konfigurasi Vite
└── README.md           # Dokumentasi proyek
```

## Dokumentasi Tambahan
- [Dokumentasi Laravel](https://laravel.com/docs)
- [Laravel Bootcamp](https://bootcamp.laravel.com)
- [Laracasts](https://laracasts.com)
- [Panduan Tailwind CSS](https://tailwindcss.com/docs)
- [Panduan Vite](https://vitejs.dev/)
- [Panduan MySQL](https://dev.mysql.com/doc/)

## Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).
