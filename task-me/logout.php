<?php
// logout.php

/**
 * File ini bertanggung jawab untuk:
 * 1. Memulai sesi PHP (jika belum dimulai) agar dapat mengakses dan menghancurkan sesi.
 * 2. Menghapus semua variabel sesi.
 * 3. Menghancurkan sesi sepenuhnya.
 * 4. Mengalihkan pengguna kembali ke halaman login dengan pesan logout.
 */

session_start(); // Pastikan sesi dimulai agar fungsi-fungsi sesi dapat digunakan

// Hapus semua variabel sesi
// Ini akan menghapus semua data yang disimpan dalam array $_SESSION.
session_unset();

// Hancurkan sesi
// Ini akan menghancurkan data sesi di server dan menghapus cookie sesi di browser pengguna.
session_destroy();

// Redirect ke halaman login
// Mengatur pesan sukses di sesi sebelum redirect untuk memberitahu pengguna bahwa mereka telah logout.
$_SESSION['message'] = 'Anda telah berhasil logout.';
header("Location: index.php?page=login");
exit(); // Penting: Hentikan eksekusi skrip setelah redirect
?>
