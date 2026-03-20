# Surat Tugas Lapangan - Diskominfo Kota Banjarbaru

Aplikasi ini adalah sistem manajemen internal yang dirancang untuk mengelola proses pengajuan, penelaahan, persetujuan, dan pembuatan surat-surat. Adapun untuk surat yang dihasilkan seperti Surat Telaahan Staf, Nota Dinas, dan Surat Tugas.

Sistem ini dibangun menggunakan Laravel 12, Tailwind CSS 4, Alpine.js, dan PostgreSQL.

## ✨ Fitur Utama

* **Dashboard Statistik & Kalender**: Menampilkan ringkasan data visual (grafik) dan kalender penugasan.
* **Manajemen Role & Permissions**: Sistem otorisasi menggunakan `spatie/laravel-permission` untuk mengatur hak ases pengguna.
* **Master Data**: CRUD (Create, Read, Update, Delete) untuk data pendukung seperti Pangkat & Golongan pegawai.
* **Manajemen Pengguna**: Kemampuan untuk mengelola pengguna yang dapat mengakses sistem.
* **Manajemen Surat Tugas**:
    * Membuat pengajuan surat baru.
    * Melihat daftar dan detail surat.
    * Mengedit dan menghapus surat.
    * Approve Surat Telaah Staf berjenjang, Level 1 = Kabid, level 2 = Kadis.
    * Status surat berjenjang antara lain diajukan, disetujui_kabid, revisi_kabid, ditolak_kabid, disetujui_kadis, revisi_kadis, ditolak_kadis.
* **Alur Pembuatan Surat Tugas**:
    * Kasi membuat Surat Telaah Staf, yang nanti dapat ditinjau dan disetujui oleh Kabid dan Kadis.
    * Pemberian status Surat Telaah Staf antara lain disetujui, direvisi, dan ditolak.
    * Jika status surat = `disetujui_kadis`, maka akan menghasilkan Nota Dinas, dan Surat Tugas.
* **Dokumen PDF Yang Dihasilkan**: Mencetak dokumen seperti Telaah Staf, Nota Dinas, dan Surat Tugas langsung dari data yang ada di sistem.
* **Laporan Rekapitulasi**: Fitur filter dan cetak laporan berkala untuk data Telaahan Staf, Nota Dinas, Surat Tugas, Data Pegawai, dan Aktivitas Pegawai.
* **Log Aktivitas (*Activity Log*)**: Memantau dan merekam seluruh riwayat aktivitas yang dilakukan oleh pengguna di dalam sistem (termasuk pencatatan IP Address) untuk keperluan audit.

## 📸 Tampilan Aplikasi (Login dengan akun super-admin)

* **Gambar 1: Tampilan Halaman Dashboard**:
<img width="1280" height="695" alt="Screen Shot 2026-03-20 at 16 08 20" src="https://github.com/user-attachments/assets/62bdaee0-5c5c-4afe-bafc-92acdd934943" />

* **Gambar 2: Tampilan Halaman Manajemen Role**:
<img width="1280" height="694" alt="Screen Shot 2025-11-01 at 11 45 27" src="https://github.com/user-attachments/assets/9444c525-3631-4dda-85f7-8b28e8bee180" />

* **Gambar 3: Tampilan Halaman Pangkat Golongan**:
<img width="1280" height="694" alt="Screen Shot 2025-11-01 at 11 45 38" src="https://github.com/user-attachments/assets/a352d90c-9503-4d8e-b80b-26919c78acdd" />

* **Gambar 4: Tampilan Halaman Manajemen User**:
<img width="1280" height="694" alt="Screen Shot 2025-11-01 at 11 46 00" src="https://github.com/user-attachments/assets/af6da381-6292-4103-b590-d2312aad35c4" />

* **Gambar 5: Tampilan Halaman Activity Logs**:
<img width="1280" height="695" alt="Screen Shot 2026-03-20 at 16 09 25" src="https://github.com/user-attachments/assets/a5ec6aea-a8ac-48f5-b78f-fc380d96f736" />

* **Gambar 6: Tampilan Halaman Laporan**:
<img width="1280" height="695" alt="Screen Shot 2026-03-20 at 16 09 42" src="https://github.com/user-attachments/assets/930df2a9-c02f-49de-84ff-a39692d7cab2" />

* **Gambar 7: Tampilan Halaman Surat Tugas**:
<img width="1280" height="694" alt="Screen Shot 2025-11-01 at 11 47 05" src="https://github.com/user-attachments/assets/f9dd3451-d3d2-42f5-a51c-187460530cd1" />

## 🛠️ Teknologi yang Digunakan

* PHP ^8.2
* Laravel 12
* PostgreSQL
* [Spatie Laravel Permission (untuk manajemen role dan hak akses)](https://spatie.be/docs/laravel-permission/v6/introduction)
* [Spatie Activitylog](https://spatie.be/docs/laravel-activitylog/v4/introduction) (Pencatatan log aktivitas)
* Vite (sebagai build tool)
* Tailwind CSS 4
* Alpine.js
* [SweetAlert2](https://sweetalert2.github.io/#download)
* [mPdf](https://mpdf.github.io/)

## 📦 Prasyarat Instalasi

Sebelum memulai, pastikan Anda telah menginstal beberapa software berikut ini:
* PHP (versi ^8.2)
* Composer
* Node.js & NPM (atau Yarn)
* Server Database PostgreSQL

## ⚙️ Cara Instalasi dan Menjalankan Proyek

1.  **Clone repositori** ini:
    ```bash
    git clone https://github.com/mrobbys/surat-tugas-lapangan.git
    ```

2.  **Install dependensi** Composer (PHP) dan NPM (JavaScript):
    ```bash
    composer install
    npm install
    ```

3.  **Salin file `.env.example`** menjadi `.env` baru:
    ```bash
    cp .env.example .env
    ```

4.  **Generate kunci aplikasi** (APP_KEY):
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi file `.env`** untuk koneksi ke database PostgreSQL Anda:
    ```ini
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=surat_tugas_lapangan
    DB_USERNAME=username_postgre_anda
    DB_PASSWORD=password_postgre_anda
    ```

6.  **Jalankan migrasi database**:
    ```bash
    php artisan migrate
    ```

7.  **Jalankan database seeder** (untuk mengisi data awal/dummy):
    ```bash
    php artisan db:seed
    ```

8.  **Run website** (jalankan di dua terminal terpisah):
    ```bash
    # Terminal 1: Menjalankan Vite development server
    npm run dev
    ```
    ```bash
    # Terminal 2: Menjalankan server Laravel
    php artisan serve
    ```

## 🚀 Contoh Penggunaan (Login Awal)

Setelah menjalankan `php artisan db:seed`, Anda dapat login menggunakan akun default di bawah ini yang dibuat oleh `RolePermissionSeeder.php`:

| Role | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **super-admin** | `superadmin@gmail.com` | `Password1.` | Semua hak akses |
| **kasi** | `kasi@gmail.com` | `Password1.` | Akses halaman manajemen surat, tambah surat, edit surat, delete surat, pdf telaah staf, pdf nota dinas, pdf surat tugas. |
| **kabid** | `kabid@gmail.com` | `Password1.` | Akses halaman manajemen surat, approve telaah staf level 1, pdf telaah staf, pdf nota dinas, pdf surat tugas. |
| **kadis** | `kadis@gmail.com` | `Password1.` | Akses halaman manajemen surat, approve telaah staf level 2, pdf telaah staf, pdf nota dinas, pdf surat tugas. |

## 📂 Susunan Proyek (Struktur Direktori)

```plaintext
surat-tugas-lapangan/
├── app/
│   ├── Http/Controllers/  # Logika utama (UserController, RoleController, SuratPerjalananDinasController, dll.)
│   ├── Models/            # Model Eloquent (User, SuratPerjalananDinas, PangkatGolongan)
│   ├── Providers/         # App Service Providers
│   ├── Services/          # Logika bisnis yang dipisahkan (UserService, RoleService, dll.)
│   └── Traits/            # Trait pendukung (seperti LogsActivityWithIp.php)
├── config/                # File konfigurasi (database.php, permission.php, dll.)
├── database/
│   ├── migrations/        # Skema database
│   └── seeders/           # Data awal (RolePermissionSeeder, PangkatGolonganSeeder, dll.)
├── resources/
│   ├── css/               # File CSS (app.css)
│   ├── js/                # File JavaScript
│   └── views/
│       ├── components/    # Komponen Blade (layout, sidebar, modal)
│       ├── pages/         # Halaman utama aplikasi (dashboard, users, roles, surat)
│       └── pdf/           # Template Blade untuk generate PDF (surat-tugas.blade.php, dll.)
├── routes/
│   ├── web.php            # Definisi rute utama
│   ├── auth.php           # Rute otentikasi
│   ├── reports.php        # Rute khusus fitur laporan
│   └── activity.php       # Rute khusus log aktivitas
├── public/                # Aset publik (gambar, dan file hasil build)
├── .env.example           # Template untuk file konfigurasi environment
├── composer.json          # Dependensi PHP (Laravel, Spatie)
└── package.json           # Dependensi JavaScript (Tailwind, Vite)
