<<<<<<< HEAD
# Lokanesia

Aplikasi web berbasis peta untuk menemukan tempat wisata lokal di Indonesia.

## Deskripsi

Lokanesia adalah platform yang membantu pengguna menemukan dan mengeksplorasi tempat wisata lokal di Indonesia. Aplikasi ini menyediakan informasi lengkap, peta interaktif, ulasan pengguna, dan panduan perjalanan untuk pengalaman wisata yang menyenangkan.

## Fitur Utama

- Peta interaktif menggunakan OpenStreetMap
- Pencarian tempat wisata dengan filter
- Sistem autentikasi pengguna
- Manajemen rencana perjalanan
- Ulasan dan rating tempat wisata
- Navigasi ke lokasi wisata
- Rekomendasi tempat wisata berdasarkan lokasi

## Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MongoDB 4.4 atau lebih tinggi
- Composer
- Web server (Apache/Nginx)
- Browser modern dengan dukungan JavaScript

## Instalasi

1. Clone repositori ini:
```bash
git clone https://github.com/username/lokanesia.git
cd lokanesia
```

2. Install dependensi PHP menggunakan Composer:
```bash
composer install
```

3. Salin file .env.example ke .env dan sesuaikan konfigurasi:
```bash
cp .env.example .env
```

4. Sesuaikan konfigurasi di file .env:
- Atur koneksi MongoDB
- Tambahkan API key OpenRouteService
- Sesuaikan konfigurasi aplikasi lainnya

5. Jalankan migrasi database:
```bash
php migrate.php
```

6. Pastikan direktori public/uploads dapat ditulis:
```bash
chmod -R 775 public/uploads
```

7. Arahkan web server ke direktori public sebagai document root

## Penggunaan

1. Buka aplikasi melalui browser
2. Daftar akun baru atau masuk dengan akun yang ada
3. Mulai menjelajahi tempat wisata di Indonesia
4. Gunakan fitur pencarian dan filter untuk menemukan tempat wisata
5. Buat rencana perjalanan dan simpan tempat favorit
6. Berikan ulasan dan rating untuk tempat yang telah dikunjungi

## Kontribusi

Silakan berkontribusi dengan membuat pull request. Untuk perubahan besar, harap buka issue terlebih dahulu untuk mendiskusikan perubahan yang diinginkan.

## Lisensi

[MIT License](LICENSE)

## Kontak

Email: team@lokanesia.com 
=======
# lokanesia
>>>>>>> c15cf6410d0d2033aa3f6cbcf782be61fa16f464
