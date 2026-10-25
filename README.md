# ModifLab

ModifLab adalah aplikasi web konfigurator modifikasi mobil dan motor berbasis Laravel. Pengguna dapat memilih kendaraan, mencoba kombinasi warna dan komponen secara interaktif, melihat estimasi harga, lalu menyimpan hasilnya ke garasi digital.

## Fitur

- Katalog mobil dan motor
- Preview modifikasi interaktif
- Pilihan warna, velg, body kit, spoiler, dan performa
- Kalkulasi estimasi harga otomatis
- Registrasi dan autentikasi pengguna
- Garasi untuk menyimpan hasil konfigurasi
- Dashboard admin untuk kendaraan dan komponen
- Tampilan responsif untuk desktop dan perangkat seluler

## Teknologi

- Laravel 13
- PHP 8.3+
- MySQL
- Blade, JavaScript, dan Vite

## Instalasi

```bash
git clone https://github.com/Rivthon/modif_kendaraan.git
cd modif_kendaraan
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Buat database MySQL bernama `db_modif_kendaraan`, sesuaikan koneksi database pada `.env`, kemudian jalankan:

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

Aplikasi dapat dibuka melalui `http://127.0.0.1:8000`.

## Akun Demo

| Peran | Email | Kata sandi |
| --- | --- | --- |
| Admin | `admin@modiflab.test` | `password` |
| Pengguna | `demo@modiflab.test` | `password` |

> Akun demo hanya ditujukan untuk lingkungan pengembangan. Ganti atau hapus sebelum aplikasi digunakan di production.

## Pengujian

```bash
php artisan test
```
