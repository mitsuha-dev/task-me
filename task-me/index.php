<?php
// index.php - File Router Utama

/**
 * File ini berfungsi sebagai "router" utama aplikasi.
 * Ini bertanggung jawab untuk:
 * 1. Memuat konfigurasi database dan memulai sesi.
 * 2. Menentukan halaman mana yang harus ditampilkan berdasarkan parameter URL.
 * 3. Mengelola pengalihan (redirect) berdasarkan status login pengguna.
 * 4. Menyertakan file header, konten halaman yang diminta, dan footer.
 * 5. Menampilkan pesan sukses atau error yang disimpan dalam sesi.
 */

require_once 'database.php'; // Muat koneksi database & mulai sesi PHP

// Tentukan halaman yang akan ditampilkan
// Mengambil nilai dari parameter GET 'page'. Jika tidak ada, default ke 'login'.
$page = $_GET['page'] ?? 'login';

// Cek apakah pengguna sudah login
// Variabel ini akan digunakan untuk logika pengalihan dan tampilan navigasi.
$is_logged_in = isset($_SESSION['user_id']);

// Redirect pengguna yang sudah login dari halaman login/register ke dashboard
// Jika pengguna sudah login dan mencoba mengakses 'login' atau 'register',
// mereka akan dialihkan ke 'dashboard' untuk mencegah akses yang tidak perlu.
if ($is_logged_in && ($page === 'login' || $page === 'register')) {
    header('Location: index.php?page=dashboard');
    exit(); // Penting: Hentikan eksekusi skrip setelah redirect
}

// Redirect pengguna yang belum login dari halaman yang memerlukan login
// Jika pengguna belum login dan mencoba mengakses 'dashboard' atau 'profile',
// mereka akan dialihkan ke 'login' dan diberi pesan error.
if (!$is_logged_in && ($page === 'dashboard' || $page === 'profile')) {
    $_SESSION['error'] = 'Anda harus login untuk mengakses halaman ini.';
    header('Location: index.php?page=login');
    exit(); // Penting: Hentikan eksekusi skrip setelah redirect
}

// Sertakan header (HTML awal, navigasi)
// Bagian ini menghasilkan <head> dan <header> dari halaman.
require_once 'header.php';

// Tampilkan pesan jika ada
// Pesan sukses disimpan dalam $_SESSION['message'] dan pesan error dalam $_SESSION['error'].
// Pesan akan ditampilkan di bagian atas konten utama dan kemudian dihapus dari sesi
// agar tidak muncul lagi setelah refresh halaman.
if (!empty($_SESSION['message'])) {
    echo '<div class="message">' . htmlspecialchars($_SESSION['message']) . '</div>';
    unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
}
if (!empty($_SESSION['error'])) {
    echo '<div class="error">' . htmlspecialchars($_SESSION['error']) . '</div>';
    unset($_SESSION['error']); // Hapus error setelah ditampilkan
}
?>

<main class="container">
    <?php
    // Sertakan konten halaman yang diminta
    // Menggunakan struktur switch-case untuk memuat file PHP yang sesuai
    // berdasarkan nilai variabel $page.
    switch ($page) {
        case 'login':
            require_once 'login.php';
            break;
        case 'register':
            require_once 'register.php';
            break;
        case 'dashboard':
            require_once 'dashboard.php';
            break;
        case 'profile':
            require_once 'profile.php';
            break;
        default:
            // Halaman 404 sederhana jika parameter 'page' tidak dikenali
            echo '<h2 style="text-align:center; color: var(--error-color);">Halaman Tidak Ditemukan (404)</h2><p style="text-align:center;">Halaman yang Anda cari tidak ada.</p>';
            break;
    }
    ?>
</main>

<?php
// Sertakan footer (HTML akhir)
// Bagian ini menghasilkan <footer> dan menutup tag <body> dan <html>.
require_once 'footer.php';
?>
