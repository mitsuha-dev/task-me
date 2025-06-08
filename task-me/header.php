<?php
// header.php

/**
 * File ini bertanggung jawab untuk:
 * 1. Memuat koneksi database jika belum ada.
 * 2. Menentukan status login pengguna.
 * 3. Menghasilkan bagian awal dokumen HTML (<DOCTYPE>, <head>, <body>, <header>).
 * 4. Menyertakan link CSS dan font eksternal.
 * 5. Menampilkan navigasi yang berbeda berdasarkan status login pengguna.
 */

// Pastikan database.php sudah dimuat untuk variabel $conn dan sesi
// Ini mencegah error jika header.php diakses langsung tanpa melalui index.php
if (!isset($conn)) {
    require_once 'database.php';
}

// Cek apakah pengguna sudah login
// Variabel $is_logged_in akan bernilai true jika 'user_id' ada di sesi, false jika tidak.
$is_logged_in = isset($_SESSION['user_id']);

// Ambil nama pengguna saat ini dari sesi
// Jika login, gunakan nama pengguna dari sesi dan sanitasi untuk keamanan (htmlspecialchars).
// Jika tidak login, string kosong.
$current_user_name = $is_logged_in ? htmlspecialchars($_SESSION['user_name']) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskMe - Pengelola Tugas Anda</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>TaskMe</h1>
        <p>Pengelola Tugas Pribadi Anda</p>
        <nav>
        
            <?php
            // Tampilkan link navigasi berdasarkan status login
            if ($is_logged_in):
                // Jika pengguna sudah login, tampilkan link ke Dashboard, Profil, dan Logout
            ?>
                <a href="index.php?page=dashboard">Dashboard</a>
                <a href="index.php?page=profile">Profil</a>
                <a href="logout.php">Logout</a>
            <?php else:
                // Jika pengguna belum login, tampilkan link ke Login dan Register
            ?>
                <a href="index.php?page=login">Login</a>
                <a href="index.php?page=register">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
        <?php
        // Tampilkan pesan sukses atau error jika ada
        if (isset($_SESSION['message']) && $_SESSION['message'] != '') {
            echo '<div class="message success">' . htmlspecialchars($_SESSION['message']) . '</div>';
            $_SESSION['message'] = ''; // Reset pesan setelah ditampilkan
        }
        if (isset($_SESSION['error']) && $_SESSION['error'] != '') {
            echo '<div class="message error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            $_SESSION['error'] = ''; // Reset pesan setelah ditampilkan
        }
        ?>