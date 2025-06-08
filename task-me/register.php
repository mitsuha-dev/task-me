<?php
// register.php

/**
 * File ini bertanggung jawab untuk:
 * 1. Memproses permintaan registrasi pengguna baru (ketika form register disubmit).
 * 2. Memvalidasi input nama, email, dan kata sandi.
 * 3. Memeriksa apakah email sudah terdaftar di database.
 * 4. Menghash kata sandi sebelum menyimpannya ke database.
 * 5. Menyimpan data pengguna baru ke database.
 * 6. Mengalihkan pengguna ke halaman login setelah registrasi berhasil atau menampilkan pesan error.
 * 7. Menampilkan form registrasi HTML.
 */

// Pastikan koneksi database sudah dimuat
// Ini penting agar variabel $conn tersedia untuk operasi database.
if (!isset($conn)) {
    require_once 'database.php';
}

// Proses Registrasi Pengguna
// Blok kode ini akan dieksekusi jika form registrasi disubmit (tombol 'register' ditekan).
if (isset($_POST['register'])) {
    $name = $_POST['name'];         // Ambil nama dari input form
    $email = $_POST['email'];       // Ambil email dari input form
    $password = $_POST['password']; // Ambil kata sandi dari input form

    // Validasi input: Pastikan semua kolom terisi dan format email valid
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Semua kolom harus diisi untuk registrasi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid.";
    } else {
        // Hash kata sandi sebelum menyimpan ke database
        // password_hash() adalah cara yang aman untuk menyimpan kata sandi.
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Periksa apakah email sudah terdaftar di database
        // Menggunakan prepared statement untuk mencegah SQL Injection.
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email); // 's' menandakan parameter adalah string
        $stmt_check->execute();
        $stmt_check->store_result(); // Simpan hasil query untuk memeriksa jumlah baris

        if ($stmt_check->num_rows > 0) {
            // Email sudah terdaftar
            $_SESSION['error'] = "Email ini sudah terdaftar. Silakan gunakan email lain atau login.";
        } else {
            // Email belum terdaftar, masukkan pengguna baru ke database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password); // 'sss' menandakan tiga parameter string

            if ($stmt->execute()) {
                // Registrasi berhasil
                $_SESSION['message'] = "Registrasi berhasil! Silakan login.";
                // Redirect ke halaman login setelah registrasi berhasil.
                header("Location: index.php?page=login");
                exit(); // Penting: Hentikan eksekusi skrip setelah redirect
            } else {
                // Registrasi gagal (misalnya karena masalah database)
                $_SESSION['error'] = "Registrasi gagal: " . $stmt->error;
            }
            $stmt->close(); // Tutup prepared statement untuk insert
        }
        $stmt_check->close(); // Tutup prepared statement untuk cek email
    }
    // Redirect kembali ke halaman register untuk menampilkan pesan error (jika ada)
    header("Location: index.php?page=register");
    exit(); // Penting: Hentikan eksekusi skrip setelah redirect
}
?>

<section id="register-section">
    <h2>Daftar Akun Baru</h2>
    <form action="register.php" method="post">
        <div class="form-group">
            <label for="register_name">Nama:</label>
            <input type="text" id="register_name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="register_email">Email:</label>
            <input type="email" id="register_email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="register_password">Kata Sandi:</label>
            <input type="password" id="register_password" name="password" class="form-control" required>
        </div>
        <div class="form-actions">
            <button type="submit" name="register" class="btn btn-primary">Daftar</button>
        </div>
    </form>
    <p style="text-align: center; margin-top: 1.5rem;">Sudah punya akun? <a href="index.php?page=login" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">Login di sini</a></p>
</section>
