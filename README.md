# Pasarku — Aplikasi E-Commerce Multi Penjual (Laravel)

Aplikasi marketplace sederhana dengan dua peran pengguna:
- **Pembeli** — jelajahi produk, keranjang belanja, checkout, riwayat pesanan.
- **Penjual** — dashboard toko, kelola produk (CRUD + upload gambar), kelola status pesanan masuk.

Dibangun dengan Laravel 11, MySQL, dan Tailwind CSS (desain kustom, bukan template bawaan).

## Fitur

- Autentikasi & registrasi dengan pilihan peran (Pembeli / Penjual)
- Katalog produk publik dengan pencarian, filter kategori, dan pengurutan
- Keranjang belanja & checkout dengan pencatatan alamat, metode pembayaran, catatan
- Riwayat & detail pesanan untuk pembeli
- Dashboard penjual: ringkasan pendapatan, produk terlaris, pesanan terbaru
- CRUD produk penjual lengkap dengan upload gambar dan status aktif/nonaktif
- Kelola status pesanan (Menunggu → Diproses → Dikirim → Selesai / Dibatalkan)
- Data contoh (seeder) untuk 2 penjual, 1 pembeli, 8 kategori, 16 produk

## Kebutuhan Sistem

- PHP >= 8.2
- Composer
- MySQL >= 5.7 / MariaDB
- Ekstensi PHP umum (mbstring, pdo_mysql, openssl, tokenizer, xml, ctype, json)

## Cara Instalasi

```bash
# 1. Ekstrak zip lalu masuk ke folder project
cd pasarku

# 2. Install dependency PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Buat database MySQL terlebih dahulu, misal:
#    CREATE DATABASE pasarku;
# Lalu sesuaikan kredensial di file .env:
#    DB_DATABASE=pasarku
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Jalankan migrasi database + data contoh
php artisan migrate --seed

# 7. Buat symbolic link untuk penyimpanan gambar produk
php artisan storage:link

# 8. Jalankan server
php artisan serve
```

Buka `http://localhost:8000` di browser.

## Akun Demo (hasil seeder)

| Peran   | Email               | Password |
|---------|---------------------|----------|
| Pembeli | buyer@pasarku.test  | password |
| Penjual | seller@pasarku.test | password |
| Penjual | seller2@pasarku.test| password |

## Struktur Peran & Middleware

- Middleware `role:seller` melindungi seluruh rute di bawah prefix `/seller`.
- Pembeli otomatis diarahkan ke halaman utama, penjual diarahkan ke dashboard toko setelah login/registrasi.

## Catatan

- Harga disimpan dalam Rupiah tanpa desimal (integer).
- Gambar produk disimpan di `storage/app/public/products`, pastikan sudah menjalankan `php artisan storage:link`.
- Desain menggunakan Tailwind CSS via CDN (Play CDN) sehingga tidak memerlukan proses build Node.js/npm — cukup jalankan aplikasi langsung.
