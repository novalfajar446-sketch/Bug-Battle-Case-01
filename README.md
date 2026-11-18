Studi Kasus 01 (CRUD Manajemen Buku)

Aplikasi web manajemen data buku berbasis **Laravel** dan **MySQL** untuk mata kuliah **Pemrograman Web Lanjut**. Studi kasus ini berfokus pada perbaikan alur Create, Read, Delete, validasi input form, serta pengaturan rute utama.

## Prasyarat Sistem
- **PHP** >= 8.2
- **Composer**
- **MySQL / MariaDB** (XAMPP / Laragon)
- **Git**

## Cara Menjalankan di Lokal

1. **Clone repository**
   ```bash
   git clone https://github.com/novalfajar446-sketch/Bug-Battle-Case-01.git
   cd Bug-Battle-Case-01
   ```

2. **Install dependensi Composer**
   ```bash
   composer install
   ```

3. **Konfigurasi file `.env`**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Buat database baru di MySQL bernama `bug_battle_case_01`, lalu sesuaikan baris konfigurasi database pada `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bug_battle_case_01
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi & Seeding Database**
   ```bash
   php artisan migrate --seed
   ```

6. **Menjalankan Pengujian Otomatis (`PHPUnit`)**
   *(Catatan: Menjalankan pengujian otomatis akan mengosongkan isi tabel database. Jalankan ulang `php artisan db:seed` setelah tes selesai untuk mengisi kembali data awal).*
   ```bash
   php artisan test --filter=BookCrudTest
   ```

7. **Menjalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   Buka browser dan akses **`http://127.0.0.1:8000`**.

---

## Dokumentasi Hasil Pengujian & Tampilan Web

### 1. Pengujian Terminal (`PHPUnit`)
![Hasil PHPUnit Kasus 01](screenshots/ganti-nama-file-test.png)

### 2. Tampilan Aplikasi Web
![Tampilan Web Kasus 01](screenshots/ganti-nama-file-web.png)
