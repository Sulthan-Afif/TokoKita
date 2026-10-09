# TokoKita — Platform Toko Online untuk UMKM

Website toko online berbasis Laravel yang memungkinkan pelanggan berbelanja dengan mudah dan pemilik toko mengelola produk, stok, serta pesanan melalui dashboard admin.

---

## Masalah

Banyak pelaku UMKM ingin menjual produk secara online, tetapi kesulitan mengelola:
- Katalog produk
- Keranjang belanja & checkout
- Pesanan pelanggan
- Stok barang

dalam satu sistem yang terintegrasi.

## Solusi

TokoKita menyediakan platform e-commerce lengkap.  
Pelanggan dapat melihat produk, menambah ke keranjang, checkout, dan melacak pesanan.  
Admin dapat mengelola seluruh operasional toko dari satu dashboard.

## Fitur Utama

**Customer**
- Daftar & detail produk
- Pencarian produk
- Keranjang belanja (tambah, ubah jumlah, hapus)
- Checkout & pembayaran
- Riwayat dan detail pesanan
- Manajemen profil & alamat

**Admin**
- Dashboard admin
- Kelola produk, kategori, dan stok
- Kelola pesanan
- Kelola halaman website & hero slide
- Pengaturan website

## Tech Stack

- Laravel
- PHP
- MySQL
- Tailwind CSS
- Vite
- Alpine.js
- Chart.js

## Demo

🌐 **Live Website:** [https://toko-kita-alpha.vercel.app](https://toko-kita-alpha.vercel.app)

## Instalasi

```bash
git clone https://github.com/SulthanAfif/TokoKita.git
cd TokoKita
composer install
cp .env.example .env
php artisan key:generate
# Atur database di .env
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Repository

📦 [https://github.com/SulthanAfif/TokoKita](https://github.com/SulthanAfif/TokoKita)

---

**TokoKita** — solusi toko online praktis untuk UMKM.  
Dibangun sebagai portfolio oleh KaryaBit Studio.
