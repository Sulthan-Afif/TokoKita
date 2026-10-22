# TokoKita

TokoKita adalah aplikasi toko online berbasis Laravel. Aplikasi ini menyediakan alur belanja untuk pelanggan dan panel administrasi untuk mengelola katalog, stok, konten, serta pesanan.

## Latar Belakang

Usaha yang menjual produk melalui banyak kanal sering kesulitan menjaga informasi produk, ketersediaan stok, dan status pesanan tetap konsisten. Pelanggan juga membutuhkan cara yang mudah untuk melihat katalog, menyimpan barang ke keranjang, melakukan checkout, dan memantau pesanannya.

TokoKita dikembangkan sebagai aplikasi web terpusat yang menghubungkan pengalaman belanja pelanggan dengan pengelolaan operasional toko. Pengunjung dapat melihat katalog, sementara pelanggan terdaftar dapat bertransaksi dan admin dapat mengelola data toko dari satu panel.

## Masalah

- Informasi produk dan stok berisiko tersebar serta sulit diperbarui secara konsisten.
- Pemesanan manual menyulitkan pelanggan dalam mengetahui rincian dan status pesanannya.
- Pengelolaan kategori, konten halaman, dan tampilan beranda membutuhkan tempat administrasi yang terpisah.
- Proses pembayaran dan konfirmasi pesanan perlu terhubung dengan alur order yang dapat dipantau.

## Solusi

TokoKita menyediakan katalog dan detail produk, keranjang belanja, checkout dengan alamat pengiriman, serta riwayat dan detail pesanan pelanggan. Admin memiliki panel untuk mengelola produk, kategori, stok, pesanan, halaman Tentang dan Kontak, statistik beranda, dan slide hero. Pembayaran dapat dihubungkan ke Midtrans; lingkungan lokal juga mendukung simulator pembayaran sesuai konfigurasi aplikasi.

## Fitur

### Pelanggan dan pengunjung

- Beranda, katalog, pencarian/filter produk, dan halaman detail produk.
- Halaman Tentang Kami dan Kontak.
- Registrasi, login, verifikasi email, dan pengelolaan profil.
- Keranjang belanja dan checkout dengan alamat pengiriman.
- Riwayat pesanan, detail pesanan, pembayaran, dan pembatalan sesuai status pesanan.
- Integrasi pembayaran Midtrans Snap serta endpoint notifikasi pembayaran.
- Verifikasi reCAPTCHA pada login dan registrasi.

### Admin

- Dashboard ringkasan toko.
- CRUD produk dan kategori, serta pengelolaan stok.
- Pengelolaan pesanan dan statusnya.
- Pengaturan statistik beranda dan slide hero.
- Pengelolaan halaman Tentang dan Kontak.
- Pengaturan kredensial Midtrans dan uji koneksi.

## Teknologi

- PHP 8.2 atau lebih baru
- Laravel 12
- SQLite (konfigurasi bawaan) atau database lain yang didukung Laravel
- Node.js dan npm
- Vite, Tailwind CSS, Alpine.js, dan Chart.js
- Midtrans Snap (opsional)

## Persyaratan

Pastikan PHP beserta ekstensi Laravel yang dibutuhkan, Composer, Node.js, dan npm telah tersedia. Untuk konfigurasi database non-SQLite, siapkan juga server database terkait.

## Instalasi dan Menjalankan Secara Lokal

1. Pasang dependensi PHP:

   ```bash
   composer install
   ```

2. Buat konfigurasi lokal dan kunci aplikasi:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Di Windows PowerShell, gunakan `Copy-Item .env.example .env` sebagai pengganti `cp`.

3. Periksa `.env`. SQLite digunakan secara bawaan. Pastikan `DB_CONNECTION=sqlite`; jika file database belum ada, buat dengan `New-Item database/database.sqlite -ItemType File` di PowerShell atau `touch database/database.sqlite` di macOS/Linux.

4. Jalankan migrasi dan data awal:

   ```bash
   php artisan migrate --seed
   ```

5. Pasang dependensi frontend dan kompilasi aset:

   ```bash
   npm install
   npm run build
   ```

6. Buat tautan untuk file publik yang diunggah:

   ```bash
   php artisan storage:link
   ```

7. Jalankan server pengembangan:

   ```bash
   php artisan serve
   ```

   Buka alamat yang ditampilkan Artisan (biasanya `http://127.0.0.1:8000`). Untuk pengembangan frontend dengan hot reload, jalankan `npm run dev` pada terminal terpisah.

> Alternatif: Composer menyediakan `composer run setup` untuk memasang dependensi, membuat `.env`, membuat app key, menjalankan migrasi, dan membangun aset. Periksa langkah database sebelum menjalankannya karena skrip tersebut menjalankan migrasi dengan `--force` dan tidak menjalankan seeder.

## Akun Demo

Seeder membuat akun berikut (kata sandi awal keduanya `password`):

| Peran | Email | Kata sandi awal |
| --- | --- | --- |
| Admin | `admin@toko.com` | `password` |
| Pelanggan | `customer@toko.com` | `password` |

Akun demo ditujukan untuk pengembangan lokal. Ganti atau nonaktifkan kredensial demo sebelum aplikasi dipublikasikan. Seeder memastikan email akun admin tetap memiliki peran admin.

## Konfigurasi Opsional

### Midtrans

Isi kredensial sandbox pada `.env` untuk mengaktifkan integrasi:

```dotenv
MIDTRANS_MERCHANT_ID=
MIDTRANS_CLIENT_KEY=
MIDTRANS_SERVER_KEY=
MIDTRANS_IS_PRODUCTION=false
```

Gunakan kunci sandbox selama pengembangan. Kredensial juga dapat dikelola melalui panel admin. Pastikan URL notifikasi Midtrans diarahkan ke endpoint `/midtrans/notification` yang dapat dijangkau oleh Midtrans. Jangan menyimpan kunci produksi di repositori.

### reCAPTCHA

Login dan registrasi memvalidasi respons Google reCAPTCHA. Atur kunci yang sesuai pada `.env` sebelum menggunakan autentikasi:

```dotenv
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
```

### Database selain SQLite

Ubah variabel `DB_*` pada `.env` sesuai koneksi database, lalu jalankan migrasi:

```bash
php artisan migrate --seed
```

## Perintah Pengembangan

```bash
npm run dev             # Vite dengan hot reload
npm run build           # Build aset untuk deployment
php artisan route:list  # Lihat daftar route
php artisan migrate     # Terapkan migrasi
php artisan db:seed     # Isi data awal
```

## Struktur Direktori

| Direktori | Isi |
| --- | --- |
| `app/Http/Controllers` | Controller publik, pelanggan, dan admin |
| `app/Models` | Model dan relasi data |
| `app/Services` | Layanan, termasuk integrasi Midtrans |
| `database/migrations` | Skema database |
| `database/seeders` | Data awal kategori, produk, pengaturan, dan akun demo |
| `resources/views` | Tampilan Blade |
| `routes` | Definisi route web dan autentikasi |
| `public` | Aset publik dan hasil build frontend |

## Catatan Keamanan dan Operasional

- Jangan commit `.env`, kredensial Midtrans, atau secret lain.
- Gunakan `APP_DEBUG=false` dan HTTPS pada lingkungan produksi.
- Pastikan email dapat dikirim jika verifikasi email diaktifkan.
- Konfigurasikan URL publik dan webhook Midtrans sesuai domain deployment.
- Backup database dan file unggahan secara berkala.

## Lisensi

Proyek ini menggunakan kerangka kerja Laravel. Lisensi aplikasi mengikuti ketentuan pemilik repositori.
