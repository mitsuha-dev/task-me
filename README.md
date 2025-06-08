# TaskMe: Pengelola Tugas Pribadi

TaskMe adalah aplikasi web sederhana yang dirancang untuk membantu pengguna mengelola daftar tugas pribadi mereka secara efisien. Aplikasi ini menyediakan fungsionalitas dasar seperti registrasi, login, manajemen tugas, dan pengelolaan profil pengguna.

---

## Daftar Isi

- [Fitur](#fitur)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Panduan Instalasi dan Pengaturan](#panduan-instalasi-dan-pengaturan)
- [Cara Menjalankan Aplikasi](#cara-menjalankan-aplikasi)
- [Struktur Folder Proyek](#struktur-folder-proyek)
- [Panduan Penggunaan Aplikasi](#panduan-penggunaan-aplikasi)
- [Pemecahan Masalah (Troubleshooting)](#pemecahan-masalah-troubleshooting)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)
- [Developer](#developer)

---

## Fitur

**Autentikasi Pengguna yang Aman:**
- Registrasi akun baru (nama, email, kata sandi ter-hash)
- Login
- Logout

**Manajemen Profil Pengguna:**
- Edit nama & email
- Ubah kata sandi

**Manajemen Tugas (To-Do List):**
- Tambah tugas baru
- Tandai tugas sebagai selesai
- Hapus tugas

**Antarmuka Pengguna (UI) Modern & Responsif:**
- Desain bersih dan intuitif
- Responsif di desktop, tablet, dan mobile
- Ikon dari Font Awesome

---

## Teknologi yang Digunakan

### Backend
- **PHP**: Logika aplikasi, interaksi database, manajemen sesi
- **MySQL**: Menyimpan data pengguna dan tugas

### Frontend
- **HTML5**
- **CSS3**
- **JavaScript (Vanilla JS)**

### Lingkungan Pengembangan
- **Laragon**: Untuk Windows, mencakup Apache, MySQL, PHP, phpMyAdmin

### Dependensi Eksternal (CDN)
- **Google Fonts (Inter)**
- **Font Awesome**

---

## Persyaratan Sistem

- Web Server: Apache atau Nginx (Laragon sudah menyediakannya)
- PHP: Versi 7.4 atau lebih tinggi
- MySQL: Versi 5.7 atau lebih tinggi
- Browser Web: Chrome, Firefox, Edge, atau browser modern lainnya

---

## Panduan Instalasi dan Pengaturan

### 1. Kloning Repositori

```bash
git clone https://github.com/your-username/TaskMe.git C:\laragon\www\TaskMe
```
Ganti URL dengan repositori Anda.

Jika download manual: ekstrak ke `C:\laragon\www\TaskMe`.

### 2. Konfigurasi Database

1. **Mulai Laragon** dan pastikan Apache & MySQL berjalan.
2. **Akses phpMyAdmin** di [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. **Buat Database**: `taskme_db`
4. **Jalankan Skrip SQL** berikut di database `taskme_db`:

```sql
CREATE DATABASE IF NOT EXISTS taskme_db;
USE taskme_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    task_name VARCHAR(255) NOT NULL,
    is_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 3. Konfigurasi Aplikasi

Edit `database.php` di root proyek:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Biasanya kosong untuk Laragon
define('DB_NAME', 'taskme_db');
```
Perbarui jika username/password MySQL Anda berbeda.

---

## Cara Menjalankan Aplikasi

1. Pastikan Laragon (Apache & MySQL) berjalan.
2. Buka browser ke: [http://localhost/TaskMe/](http://localhost/TaskMe/)  
   (Ganti `TaskMe` jika nama folder berbeda)

---

## Struktur Folder Proyek

```
TaskMe/
├── index.php         # Router utama
├── database.php      # Konfigurasi database & inisialisasi sesi PHP
├── header.php        # Bagian awal HTML dan navigasi
├── footer.php        # Penutup HTML & info footer
├── login.php         # Form & logika login
├── register.php      # Form & logika registrasi
├── dashboard.php     # Dashboard utama dan manajemen tugas
├── profile.php       # Edit profil & ubah password
├── logout.php        # Logout user
└── style.css         # CSS aplikasi
```

---

## Panduan Penggunaan Aplikasi

1. **Registrasi Akun Baru:**  
   - Klik "Daftar di sini" di halaman login  
   - Isi data, klik "Daftar"

2. **Login:**  
   - Isi email & kata sandi, klik "Login"

3. **Menambah Tugas Baru:**  
   - Isi nama tugas, klik "Tambah Tugas" di Dashboard

4. **Menandai Tugas Selesai:**  
   - Klik ikon centang (✅) di samping tugas

5. **Menghapus Tugas:**  
   - Klik ikon tempat sampah (🗑️), konfirmasi penghapusan

6. **Mengedit Profil:**  
   - Klik "Profil", ubah data, klik "Perbarui Profil"

7. **Logout:**  
   - Klik "Logout" di navigasi

---

## Pemecahan Masalah (Troubleshooting)

### Tugas Tidak Tersimpan/Terlihat
- Pastikan semua <form> action dan <a href> mengarah ke `index.php?page=...`
- Aktifkan error PHP untuk debugging

### Error Koneksi Database
- Pastikan MySQL berjalan dan pengaturan di `database.php` benar

### Halaman Tidak Ditemukan (404)
- Pastikan URL & nama folder benar, Apache berjalan, dan `index.php` ada

### Pesan Error PHP di Browser
- Aktifkan error di atas `index.php`:
  ```php
  <?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  // ... kode lain
  ```
- Cek pesan dan file log error Laragon

---

## Kontribusi

Kontribusi sangat diterima! Fork, buat branch baru, dan ajukan pull request.

---

## Lisensi

Lisensi: MIT. Lihat file `LICENSE` untuk detail.

---

## Developer

Proyek ini dikembangkan oleh:  
**mitsuha.dev**

---
