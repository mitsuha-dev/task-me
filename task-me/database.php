<?php
// database.php

/**
 * Nah, file ini tugasnya penting banget!
 * Dia yang ngurusin semua hal yang berhubungan sama koneksi ke database kita:
 * 1. Ngatur detail-detail buat nyambung ke database MySQL.
 * 2. Bikin koneksinya beneran jalan.
 * 3. Mulai sesi PHP, biar kita bisa simpen data user sementara.
 * 4. Nyiapin tempat di sesi buat nampung pesan sukses atau pesan error.
 */

// Oke, pertama-tama kita atur dulu nih konfigurasi buat database kita.
// Ini ibarat alamat rumah database kita.
// Pastiin nilai-nilai di bawah ini udah sesuai sama pengaturan di komputer kamu ya (misalnya kalo pake Laragon, XAMPP, atau MAMP).
define('DB_HOST', 'localhost'); // Ini alamat server database kita, biasanya 'localhost' kalo lagi di komputer sendiri.
define('DB_USER', 'root');      // Nama pengguna buat login ke database, umumnya 'root' buat Laragon/XAMPP.
define('DB_PASS', '');          // Kata sandi database. Kalo di Laragon/XAMPP, default-nya sering kosong aja.
define('DB_NAME', 'taskme_db'); // Ini nama database yang mau kita pake. Jangan lupa pastiin database ini udah kamu buat di MySQL ya!

// Sekarang, saatnya kita coba nyambungin ke database pake gaya MySQLi yang Object-Oriented.
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Nah, kita cek nih, koneksinya berhasil apa enggak?
// Kalo gagal nyambung, wah bahaya! Langsung aja kita berhentiin semua script
// dan tampilin pesan error-nya biar kita tau ada apa.
if ($conn->connect_error) {
    die("Aduh, koneksi ke database gagal total: " . $conn->connect_error);
}

// Oke, koneksi database udah beres. Sekarang kita mulai sesi PHP!
// Fungsi `session_start()` ini WAJIB banget dipanggil di awal setiap file
// yang butuh pake data sesi user (kayak nyimpen status login, nama user, dll.).
// Ini penting biar data user bisa "diinget" dari satu halaman ke halaman lain.
session_start();

// Terakhir, kita inisialisasi atau siapin tempat buat pesan-pesan di sesi.
// Pesan ini bakal kita pake buat ngasih info ke user, entah itu "tugas berhasil ditambah!"
// atau "password salah!".
// Kita pake operator `??` (null coalescing) biar variabelnya selalu ada,
// jadi nggak error kalo pertama kali diakses.
$_SESSION['message'] = $_SESSION['message'] ?? ''; // Ini buat nampung pesan sukses.
$_SESSION['error'] = $_SESSION['error'] ?? '';     // Kalo ini buat nampung pesan error.
?>