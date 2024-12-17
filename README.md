<<<<<<< HEAD
# Lokanesia

Aplikasi web berbasis peta untuk menemukan tempat wisata lokal di Indonesia.

## Deskripsi

Lokanesia adalah platform yang membantu pengguna menemukan dan mengeksplorasi tempat wisata lokal di Indonesia. Aplikasi ini menyediakan informasi lengkap, peta interaktif, ulasan pengguna, dan panduan perjalanan untuk pengalaman wisata yang menyenangkan.

## Fitur Utama

- Peta interaktif menggunakan Leaflet dan OpenStreetMap
- Pencarian tempat wisata dengan filter:
  - Berdasarkan kategori (Wisata Alam, Budaya, Sejarah, dll)
  - Berdasarkan rentang harga tiket
  - Berdasarkan rating pengunjung
  - Berdasarkan lokasi
- Sistem autentikasi pengguna
- Manajemen rencana perjalanan
- Ulasan dan rating tempat wisata
- Navigasi ke lokasi wisata
- Rekomendasi tempat wisata berdasarkan lokasi

## Kategori Wisata

- Wisata Alam (Pantai, Gunung, Danau, dll)
- Wisata Budaya (Museum, Galeri Seni, dll)
- Wisata Sejarah (Candi, Benteng, dll)
- Wisata Hiburan (Taman Bermain, Pusat Perbelanjaan)
- Wisata Kuliner (Pasar Tradisional, Pusat Kuliner)
- Wisata Religi (Masjid, Pura, Gereja, dll)

## Teknologi yang Digunakan

- Backend: PHP 7.4+
- Database: MongoDB 4.4+
- Frontend: 
  - Bootstrap 5.3
  - Leaflet.js untuk peta interaktif
  - Font Awesome untuk ikon
  - jQuery untuk AJAX
- API:
  - OpenStreetMap untuk peta dasar
  - OpenRouteService untuk navigasi

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
```env
APP_NAME=Lokanesia
APP_ENV=development
APP_URL=http://localhost:8000

# Database MongoDB
MONGODB_URI=mongodb://localhost:27017
MONGODB_DB=lokanesia_db

# JWT untuk autentikasi
JWT_SECRET=your_jwt_secret_key
JWT_EXPIRATION=86400

# Konfigurasi Upload
UPLOAD_MAX_SIZE=5242880
ALLOWED_EXTENSIONS=jpg,jpeg,png,gif
```

5. Jalankan migrasi database:
```bash
php migrate.php
```

6. Pastikan direktori public/uploads dapat ditulis:
```bash
chmod -R 775 public/uploads
```

7. Jalankan server development:
```bash
php -S localhost:8000 -t public public/router.php
```

## Penggunaan

1. Buka aplikasi melalui browser: http://localhost:8000
2. Daftar akun baru atau masuk dengan akun yang ada
3. Mulai menjelajahi tempat wisata di Indonesia:
   - Gunakan fitur pencarian dengan filter kategori
   - Lihat tempat wisata di peta interaktif
   - Baca ulasan dari pengunjung lain
   - Simpan tempat favorit
   - Buat rencana perjalanan
   - Berikan ulasan untuk tempat yang telah dikunjungi

## Struktur Direktori

```
lokanesia/
├── app/
│   ├── Controllers/    # Controller aplikasi
│   ├── Models/         # Model database
│   └── Core/           # Kelas inti aplikasi
├── config/            # File konfigurasi
├── public/           # Direktori publik
│   ├── assets/      # Asset statis (CSS, JS, gambar)
│   └── uploads/     # Upload pengguna
├── views/           # Template view
├── routes/          # Definisi routing
└── vendor/         # Dependensi composer
```

## Kontribusi

Silakan berkontribusi dengan membuat pull request. Untuk perubahan besar, harap buka issue terlebih dahulu untuk mendiskusikan perubahan yang diinginkan.

## Lisensi

[MIT License](LICENSE)

## Kontak

Email: team@lokanesia.com
Website: https://lokanesia.com
=======
# lokanesia
>>>>>>> 8baec7d5e09d0af33f9ea50dae9db6896f8f831b
