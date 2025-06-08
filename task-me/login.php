<?php
// login.php

/**
 * File ini bertanggung jawab untuk:
 * 1. Memproses permintaan login pengguna (ketika form login disubmit).
 * 2. Memvalidasi input email dan kata sandi.
 * 3. Mengambil data pengguna dari database.
 * 4. Memverifikasi kata sandi yang dimasukkan dengan hash kata sandi di database.
 * 5. Mengatur variabel sesi setelah login berhasil.
 * 6. Mengalihkan pengguna ke halaman dashboard atau menampilkan pesan error.
 * 7. Menampilkan form login HTML.
 */

// Pastikan koneksi database sudah dimuat
// Ini penting agar variabel $conn tersedia untuk operasi database.
if (!isset($conn)) {
    require_once 'database.php';
}

// Proses Login Pengguna
// Blok kode ini akan dieksekusi jika form login disubmit (tombol 'login' ditekan).
if (isset($_POST['login'])) {
    $email = $_POST['email'];       // Ambil email dari input form
    $password = $_POST['password']; // Ambil kata sandi dari input form

    // Validasi input: Pastikan email dan kata sandi tidak kosong
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email dan kata sandi harus diisi untuk login.";
    } else {
        // Ambil pengguna dari database berdasarkan email
        // Menggunakan prepared statement untuk mencegah SQL Injection.
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email); // 's' menandakan parameter adalah string
        $stmt->execute();
        $result = $stmt->get_result(); // Dapatkan hasil query

        // Periksa apakah ada pengguna dengan email tersebut
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc(); // Ambil baris data pengguna sebagai array asosiatif
            // Verifikasi kata sandi
            // password_verify() membandingkan kata sandi yang dimasukkan dengan hash yang tersimpan.
            if (password_verify($password, $user['password'])) {
                // Login berhasil
                // Simpan ID, nama, dan email pengguna di sesi untuk digunakan di halaman lain.
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['message'] = "Selamat datang kembali, " . htmlspecialchars($user['name']) . "!";
                // Redirect ke halaman dashboard setelah login berhasil.
                header("Location: index.php?page=dashboard");
                exit(); // Penting: Hentikan eksekusi skrip setelah redirect
            } else {
                // Kata sandi salah
                $_SESSION['error'] = "Email atau kata sandi salah.";
            }
        } else {
            // Email tidak ditemukan di database
            $_SESSION['error'] = "Email atau kata sandi salah.";
        }
        $stmt->close(); // Tutup prepared statement
    }
    // Redirect kembali ke halaman login untuk menampilkan pesan error (jika ada)
    header("Location: index.php?page=login");
    exit(); // Penting: Hentikan eksekusi skrip setelah redirect
}
?>

<section id="login-section">
    <h2>Login ke TaskMe</h2>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="login_email">Email:</label>
            <input type="email" id="login_email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="login_password">Kata Sandi:</label>
            <input type="password" id="login_password" name="password" class="form-control" required>
        </div>
        <div class="form-actions">
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </div>
    </form>
    <p style="text-align: center; margin-top: 1.5rem;">Belum punya akun? <a href="index.php?page=register" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Daftar di sini</a></p>
</section>
