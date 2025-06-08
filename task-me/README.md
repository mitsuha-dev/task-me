TaskMe: Pengelola Tugas Pribadi
TaskMe adalah aplikasi web sederhana yang dirancang untuk membantu pengguna mengelola daftar tugas pribadi mereka secara efisien. Aplikasi ini menyediakan fungsionalitas dasar seperti registrasi dan login pengguna, manajemen profil, serta kemampuan untuk menambah, menandai selesai, dan menghapus tugas. Dibangun dengan teknologi web dasar (PHP, MySQL, HTML, CSS, JavaScript), proyek ini cocok sebagai contoh aplikasi web sederhana tanpa ketergantungan pada framework kompleks.

Daftar Isi
Fitur

Teknologi yang Digunakan

Persyaratan Sistem

Panduan Instalasi dan Pengaturan

1. Kloning Repositori

2. Konfigurasi Database

3. Konfigurasi Aplikasi

Cara Menjalankan Aplikasi

Struktur Folder Proyek

Panduan Penggunaan Aplikasi

Registrasi Akun Baru

Login ke Akun Anda

Menambah Tugas Baru

Menandai Tugas Selesai

Menghapus Tugas

Mengedit Profil

Logout

Pemecahan Masalah (Troubleshooting)

Tugas Tidak Tersimpan/Terlihat

Error Koneksi Database

Halaman Tidak Ditemukan (404)

Pesan Error PHP di Browser

Kontribusi

Lisensi

Developer

Fitur
Autentikasi Pengguna yang Aman:

Registrasi Akun: Pengguna dapat membuat akun baru dengan nama, email, dan kata sandi (disimpan ter-hash).

Login: Pengguna dapat masuk ke akun mereka yang sudah terdaftar.

Logout: Pengguna dapat keluar dari sesi mereka dengan aman.

Manajemen Profil Pengguna:

Edit Nama & Email: Pengguna dapat memperbarui nama dan alamat email mereka.

Ubah Kata Sandi: Pengguna memiliki opsi untuk mengubah kata sandi mereka.

Manajemen Tugas (To-Do List):

Tambah Tugas: Mudah menambahkan tugas baru ke daftar.

Tandai Selesai: Tandai tugas sebagai selesai dengan satu klik.

Hapus Tugas: Hapus tugas yang tidak lagi diperlukan.

Antarmuka Pengguna (UI) Modern & Responsif:

Desain bersih dan intuitif.

Menyesuaikan tampilan dengan baik di berbagai ukuran layar (desktop, tablet, mobile).

Menggunakan ikon dari Font Awesome untuk visual yang lebih baik.

Teknologi yang Digunakan
Proyek ini dibangun menggunakan kombinasi teknologi web dasar yang kuat dan mudah dipelajari:

Backend:

PHP (Hypertext Preprocessor): Bahasa pemrograman sisi server yang bertanggung jawab untuk logika aplikasi, interaksi database, dan manajemen sesi.

MySQL: Sistem manajemen database relasional (RDBMS) yang digunakan untuk menyimpan data pengguna (nama, email, kata sandi ter-hash) dan data tugas (nama tugas, status selesai, ID pengguna terkait).

Frontend:

HTML5: Bahasa markup standar untuk membuat struktur halaman web.

CSS3: Bahasa stylesheet yang digunakan untuk mengatur gaya, tata letak, dan tampilan visual aplikasi, termasuk responsivitas.

JavaScript (Vanilla JS): Digunakan untuk interaksi sisi klien yang sederhana, seperti konfirmasi penghapusan tugas kustom.

Lingkungan Pengembangan:

Laragon: Lingkungan pengembangan lokal yang ringan dan cepat untuk Windows, mencakup Apache (web server), MySQL (database), PHP, dan phpMyAdmin (alat manajemen database).

Dependensi Eksternal (CDN):

Google Fonts (Inter): Untuk tipografi yang modern dan mudah dibaca.

Font Awesome: Untuk ikon-ikon yang mempercantik antarmuka.

Persyaratan Sistem
Untuk menjalankan aplikasi TaskMe ini di lingkungan lokal Anda, Anda memerlukan:

Web Server: Apache atau Nginx (Laragon sudah menyediakannya).

PHP: Versi 7.4 atau lebih tinggi.

MySQL: Versi 5.7 atau lebih tinggi.

Browser Web: Google Chrome, Mozilla Firefox, Microsoft Edge, atau browser modern lainnya.

Direkomendasikan: Menggunakan Laragon untuk lingkungan pengembangan lokal yang terintegrasi dan mudah.

Panduan Instalasi dan Pengaturan
Ikuti langkah-langkah di bawah ini untuk mengatur dan menjalankan proyek TaskMe di lingkungan pengembangan lokal Anda.

1. Kloning Repositori
Jika Anda mengunduh proyek ini dari GitHub, kloning repositori ke direktori www Laragon Anda (atau direktori root web server Anda):

git clone https://github.com/your-username/TaskMe.git C:\laragon\www\TaskMe

Ganti https://github.com/your-username/TaskMe.git dengan URL repositori GitHub Anda yang sebenarnya.
Jika Anda mengunduh proyek secara manual (misalnya, sebagai file ZIP), ekstrak isinya ke direktori TaskMe di dalam C:\laragon\www\ (atau lokasi serupa yang dapat diakses oleh web server Anda).

2. Konfigurasi Database
Mulai Laragon: Buka aplikasi Laragon. Pastikan Apache dan MySQL berjalan (klik tombol Start All).

Akses phpMyAdmin: Buka browser Anda dan navigasikan ke http://localhost/phpmyadmin (atau klik kanan ikon Laragon di taskbar > Menu > Database > phpMyAdmin).

Buat Database: Di phpMyAdmin, klik tab Databases atau opsi New di sidebar. Buat database baru dengan nama taskme_db.

Jalankan Skrip SQL:

Pilih database taskme_db yang baru Anda buat dari daftar di sidebar kiri.

Klik tab SQL.

Salin dan tempel skrip SQL berikut ke dalam kotak teks, lalu klik tombol Go atau Execute untuk menjalankannya:

-- Buat database jika belum ada (opsional, karena sudah dibuat di langkah 3)
CREATE DATABASE IF NOT EXISTS taskme_db;

-- Gunakan database
USE taskme_db;

-- Tabel untuk pengguna
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk tugas
CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    task_name VARCHAR(255) NOT NULL,
    is_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

Ini akan membuat tabel users dan tasks yang diperlukan untuk aplikasi.

3. Konfigurasi Aplikasi
Buka file database.php yang terletak di root folder proyek Anda (misalnya C:\laragon\www\TaskMe\database.php).

Pastikan detail koneksi database (host, username, password, nama database) sesuai dengan pengaturan MySQL di Laragon Anda. Secara default, pengaturan ini sudah sesuai:

// database.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Biasanya kosong untuk Laragon default
define('DB_NAME', 'taskme_db');

Jika Anda telah mengubah username atau password MySQL Anda di Laragon, pastikan untuk memperbarui nilai DB_USER dan DB_PASS di file ini.

Cara Menjalankan Aplikasi
Setelah semua langkah instalasi dan pengaturan selesai, Anda dapat menjalankan aplikasi TaskMe melalui browser web Anda:

Pastikan Laragon Berjalan: Pastikan Apache dan MySQL di Laragon Anda dalam status Running.

Buka Browser: Buka browser web favorit Anda (Chrome, Firefox, dll.).

Akses URL Proyek: Di bilah alamat browser, ketik URL berikut dan tekan Enter:

http://localhost/TaskMe/

Ganti TaskMe jika nama folder proyek Anda di C:\laragon\www\ berbeda.

Aplikasi TaskMe akan terbuka, dan Anda akan melihat halaman login.

Struktur Folder Proyek
Proyek TaskMe diatur dalam struktur folder yang sederhana dan modular, dengan semua file utama berada di direktori root untuk kemudahan akses:

TaskMe/
├── index.php         # File utama (router) yang memuat halaman lain berdasarkan parameter URL.
├── database.php      # Konfigurasi koneksi ke database MySQL dan inisialisasi sesi PHP.
├── header.php        # Berisi bagian awal HTML (doctype, head, pembukaan body) dan navigasi utama aplikasi.
├── footer.php        # Berisi bagian akhir HTML (penutup body) dan informasi footer/developer.
├── login.php         # Tampilan formulir login dan logika PHP untuk proses autentikasi login.
├── register.php      # Tampilan formulir registrasi dan logika PHP untuk proses pendaftaran akun baru.
├── dashboard.php     # Tampilan utama setelah login, termasuk formulir tambah tugas, daftar tugas, dan logika manajemen tugas (tambah, tandai selesai, hapus).
├── profile.php       # Tampilan formulir edit profil pengguna dan logika PHP untuk memperbarui nama, email, dan kata sandi.
├── logout.php        # Skrip PHP sederhana untuk menghancurkan sesi pengguna dan melakukan logout.
└── style.css         # File CSS yang berisi semua gaya visual dan responsif untuk antarmuka aplikasi.

Panduan Penggunaan Aplikasi
Berikut adalah langkah-langkah dasar untuk menggunakan aplikasi TaskMe:

Registrasi Akun Baru
Akses aplikasi di http://localhost/TaskMe/.

Jika Anda berada di halaman login, klik tautan "Daftar di sini" atau navigasi ke http://localhost/TaskMe/?page=register.

Isi formulir pendaftaran dengan Nama, Email, dan Kata Sandi Anda.

Klik tombol "Daftar".

Jika berhasil, Anda akan melihat pesan sukses dan diarahkan kembali ke halaman login.

Login ke Akun Anda
Akses aplikasi di http://localhost/TaskMe/.

Isi formulir login dengan Email dan Kata Sandi yang sudah Anda daftarkan.

Klik tombol "Login".

Jika berhasil, Anda akan melihat pesan selamat datang dan diarahkan ke halaman Dashboard.

Menambah Tugas Baru
Setelah login, Anda akan berada di halaman Dashboard.

Di bagian "Tambahkan Tugas Baru:", masukkan nama tugas di kolom input.

Klik tombol "Tambah Tugas".

Tugas baru Anda akan muncul di daftar tugas di bawahnya.

Menandai Tugas Selesai
Di daftar tugas pada Dashboard, cari tugas yang ingin Anda tandai selesai.

Klik ikon centang (✅) di samping tugas tersebut.

Tugas akan ditandai sebagai selesai (dengan garis coret dan warna latar belakang yang berbeda).

Menghapus Tugas
Di daftar tugas pada Dashboard, cari tugas yang ingin Anda hapus.

Klik ikon tempat sampah (🗑️) di samping tugas tersebut.

Sebuah kotak konfirmasi akan muncul. Klik "Ya, Hapus" untuk melanjutkan atau "Tidak" untuk membatalkan.

Jika dikonfirmasi, tugas akan dihapus dari daftar Anda.

Mengedit Profil
Setelah login, klik tautan "Profil" di navigasi atas atau navigasi ke http://localhost/TaskMe/?page=profile.

Anda dapat mengubah Nama dan Email Anda.

Jika Anda ingin mengubah kata sandi, isi kolom "Kata Sandi Baru". Kosongkan jika tidak ingin mengubahnya.

Klik tombol "Perbarui Profil".

Anda akan melihat pesan sukses jika pembaruan berhasil.

Logout
Klik tautan "Logout" di navigasi atas.

Anda akan diarahkan kembali ke halaman login dengan pesan bahwa Anda telah berhasil logout.

Pemecahan Masalah (Troubleshooting)
Jika Anda mengalami masalah saat menjalankan atau menggunakan aplikasi TaskMe, berikut adalah beberapa tips pemecahan masalah umum:

Tugas Tidak Tersimpan/Terlihat
Penyebab Paling Umum: Formulir POST atau link GET mengarah ke file PHP secara langsung (dashboard.php) alih-alih melalui router utama (index.php).

Solusi: Pastikan semua action di formulir (<form action="...">) dan href di tautan (<a href="...">) di dashboard.php mengarah ke index.php?page=dashboard (atau index.php?page=profile untuk profil, dll.). Ini memastikan bahwa database.php (yang menginisialisasi koneksi $conn) selalu dimuat sebelum logika pemrosesan data dijalankan.

Periksa Error PHP: Aktifkan tampilan error PHP (lihat di bawah) dan perhatikan pesan error yang muncul saat Anda mencoba menambah/mengedit/menghapus tugas.

Error Koneksi Database
Pesan Error: Anda mungkin melihat pesan seperti "Koneksi database gagal: Access denied for user..." atau "Unknown database 'taskme_db'".

Solusi:

Pastikan MySQL Berjalan: Buka Laragon dan pastikan MySQL dalam status Running.

Periksa database.php: Buka file database.php dan pastikan DB_HOST, DB_USER, DB_PASS, dan DB_NAME sudah benar sesuai dengan pengaturan MySQL Anda di Laragon.

Database Ada: Pastikan database taskme_db sudah benar-benar dibuat di phpMyAdmin.

Tabel Ada: Pastikan tabel users dan tasks sudah dibuat di dalam taskme_db.

Halaman Tidak Ditemukan (404)
Pesan Error: Browser menampilkan "Not Found" atau "404".

Solusi:

URL Benar: Pastikan URL yang Anda ketik di browser sudah benar (misalnya http://localhost/TaskMe/).

Nama Folder: Pastikan nama folder proyek di C:\laragon\www\ sama persis dengan nama di URL (case-sensitive di beberapa sistem).

Apache Berjalan: Pastikan Apache di Laragon dalam status Running.

File index.php Ada: Pastikan file index.php ada di dalam folder TaskMe.

Pesan Error PHP di Browser
Pesan Error: Baris kode PHP yang aneh muncul di browser, atau pesan seperti "Undefined variable", "Fatal error", dll.

Solusi:

Aktifkan Tampilan Error Penuh (untuk Debugging):
Untuk melihat detail error, tambahkan baris berikut di paling atas file index.php (setelah <?php):

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ... kode lainnya

Penting: Hapus baris ini setelah debugging selesai karena menampilkan error di lingkungan produksi adalah risiko keamanan.

Baca Pesan Error: Pesan error PHP biasanya sangat informatif. Ia akan memberitahu Anda:

Jenis error: (misalnya Undefined variable, Fatal error).

File yang bermasalah: (misalnya C:\laragon\www\TaskMe\dashboard.php).

Nomor baris: Di mana error itu terjadi.
Fokus pada informasi ini untuk menemukan dan memperbaiki masalah.

Cek Log Error Laragon: Jika error tidak muncul di browser, periksa file log error PHP Laragon (biasanya di C:\laragon\bin\php\php-x.x.x\logs\php_error.log).

Kontribusi
Kontribusi dalam bentuk perbaikan bug, penambahan fitur, atau peningkatan kode sangat diterima. Silakan fork repositori ini, buat branch baru, dan kirim pull request dengan perubahan Anda.

Lisensi
Proyek ini dilisensikan di bawah Lisensi MIT. Anda dapat menemukan detail lengkap lisensi di file LICENSE di repositori ini.

Developer
Proyek ini dikembangkan oleh:

mitsuha.dev