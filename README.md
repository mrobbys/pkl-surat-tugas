Surat Tugas Lapangan - Diskominfo Kota Banjarbaru

Aplikasi ini adalah sistem manajemen internal yang dirancang untuk mengelola proses pengajuan, penelaahan, persetujuan, dan pembuatan surat-surat. Adapun untuk surat yang dihasilkan seperti Surat Telaahan Staf, Nota Dinas, dan Surat Tugas.

Sistem ini dibangun menggunakan Laravel 12, Tailwind CSS 4, Alpine.js, dan PostgreSQL.

✨ Fitur Utama

•	Manajemen Role & Permissions: Sistem otorisasi menggunakan spatie/laravel-permission untuk mengatur hak ases pengguna.

•	Master Data: CRUD (Create, Read, Update, Delete) untuk data pendukung seperti Pangkat & Golongan pegawai.

•	Manajemen Pengguna: Kemampuan untuk mengelola pengguna yang dapat mengakses sistem.

•	Manajemen Surat Tugas:
	•	Membuat pengajuan surat baru.
	•	Melihat daftar dan detail surat.
	•	Mengedit dan menghapus surat.
	•	Approve Surat Telaah Staf berjenjang, Level 1 = Kabid, level 2 = Kadis.
	•	Status surat tugas berjenjang antara lain diajukan, disetujui_kabid, revisi_kabid, ditolak_kabid, disetujui_kadis, revisi_kadis, ditolak_kadis.

•	Alur Pembuatan Surat Tugas:
	•	Kasi membuat Surat Telaah Staf, yang nanti dapat ditinjau dan disetujui oleh Kabid dan Kadis.
	•	Pemberian status Surat Telaah Staf antara lain disetujui, direvisi, dan ditolak.
	•	Jika status surat = disetujui_kadis, maka akan menghasilkan Nota Dinas, dan Surat Tugas.

•	Dokumen PDF Yang Dihasilkan: Mencetak dokumen seperti Telaah Staf, Nota Dinas, dan Surat Tugas langsung dari data yang ada di sistem.

🛠️ Teknologi yang Digunakan

•	PHP ^8.2
•	Laravel 12
•	PostgreSQL
•	Spatie Laravel Permission (untuk manajemen role dan hak akses)
•	Vite (sebagai build tool)
•	Tailwind CSS 4
•	Alpine.js
•	SweetAlert2 (https://sweetalert2.github.io/#download)
•	mPdf (https://mpdf.github.io/)

📦 Prasyarat Instalasi

Sebelum memulai, pastikan Anda telah menginsal beberapa software berikut ini:
•	PHP (versi ^8.2)
•	Composer
•	Node.js & NPM (atau Yarn)
•	Server Database PostgreSQL

⚙️ Cara Instalasi dan Menjalankan Proyek

1.	Clone Repo github ini:
	git clone https://github.com/mrobbys/surat-tugas-lapangan.git

2.	Install dependensi, gunakan Composer dan NPM:
	composer install
	npm install

3.	Salin file .env.example menjadi .env baru:
	cp .env.example .env

4.	Generate kunci aplikasi (APP_KEY):
	php artisan key:generate
	
5.	Konfigurasi file .env untuk koneksi ke database PostgreSQL:
	DB_CONNECTION=pgsql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=surat_tugas_lapangan
	DB_USERNAME=username_postgre_anda
	DB_PASSWORD=password_postgre_anda

6.	Jalankan migrasi database:
	php artisan migrate
	
7.	Jalankan database seeder:
	php artisan db:seed
	
8.	Run website:
	npm run dev
	php artisan serve
	
🚀 Contoh Penggunaan (Login Awal)

Setelah menjalankan php artisan db:seed, Anda dapat login menggunakan akun default dibawah ini yang dibuat oleh RolePermissionSeeder.php:

Akun Super Admin
•	Email 		:	superadmin@gmail.com
•	Password	:	Password1.
•	Hak Akses	:	Semua hak akses

Akun Kasi
•	Email 		:	kasi@gmail.com
•	Password	:	Password1.
•	Hak Akses	:	Akses halaman manajemen surat, tambah surat, edit surat, delete surat, pdf telaah staf, pdf nota dinas, pdf surat tugas.

Akun Kabid
•	Email 		:	kabid@gmail.com
•	Password	:	Password1.
•	Hak Akses	:	Akses halaman manajemen surat, approve telaah staf level 1, pdf telaah staf, pdf nota dinas, pdf surat tugas.

Akun Kadis
•	Email 		:	kadis@gmail.com
•	Password	:	Password1.
•	Hak Akses	:	Akses halaman manajemen surat, approve telaah staf level 2, pdf telaah staf, pdf nota dinas, pdf surat tugas.

📂 Susunan Proyek (Struktur Direktori)

surat-tugas-lapangan/
├── app/
│   ├── Http/Controllers/  # Logika utama (UserController, RoleController, SuratPerjalananDinasController, dll.)
│   ├── Models/            # Model  Eloquent (User, SuratPerjalananDinas, PangkatGolongan)
│   ├── Providers/         # App Service Providers
│   └── Services/          # Logika bisnis yang dipisahkan (UserService, RoleService, dll.)
├── config/                # File konfigurasi (database.php, permission.php, dll.)
├── database/
│   ├── migrations/        # Skema database
│   └── seeders/           # Data awal (RolePermissionSeeder, PangkatGolonganSeeder, dll.)
├── resources/
│   ├── css/               # File CSS (app.css)
│   ├── js/                # File JavaScript
│   ├── views/
│   │   ├── components/    # Komponen Blade (layout, sidebar, modal)
│   │   ├── pages/         # Halaman utama aplikasi (dashboard, users, roles, surat)
│   │   └── pdf/           # Template Blade untuk generate PDF (surat-tugas.blade.php, dll.)
├── routes/
│   ├── web.php            # Definisi rute utama aplikasi
│   └── auth.php           # Rute untuk otentikasi
├── public/                # Aset publik (gambar, dan file hasil build)
├── .env.example           # Template untuk file konfigurasi environment
├── composer.json          # Dependensi PHP (Laravel, Spatie)
└── package.json           # Dependensi JavaScript (Tailwind, Vite)



