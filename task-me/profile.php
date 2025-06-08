<?php
// profile.php

/**
 * File ini bertanggung jawab untuk:
 * 1. Memastikan pengguna sudah login sebelum mengakses halaman ini.
 * 2. Mengambil ID, nama, dan email pengguna saat ini dari sesi.
 * 3. Memproses permintaan pembaruan profil (ketika form disubmit).
 * 4. Memvalidasi input nama dan email baru.
 * 5. Memeriksa apakah email baru sudah digunakan oleh pengguna lain.
 * 6. Menghash kata sandi baru jika pengguna ingin mengubahnya.
 * 7. Memperbarui data pengguna di database.
 * 8. Mengalihkan pengguna kembali ke halaman profil atau menampilkan pesan error.
 * 9. Menampilkan form edit profil HTML.
 */

// Pastikan koneksi database sudah dimuat dan pengguna sudah login
// Jika $conn tidak ada atau 'user_id' tidak ada di sesi, pengguna akan dialihkan ke halaman login.
if (!isset($conn) || !isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Anda harus login untuk mengakses halaman ini.';
    header('Location: index.php?page=login');
    exit(); // Hentikan eksekusi skrip setelah redirect
}

// Ambil ID, nama, dan email pengguna saat ini dari sesi
$current_user_id = $_SESSION['user_id'];
$current_user_name = $_SESSION['user_name'];
$current_user_email = $_SESSION['user_email'];

// Proses Update Profil
// Blok ini dieksekusi jika form update profil disubmit (tombol 'update_profile' ditekan).
if (isset($_POST['update_profile'])) {
    $new_name = $_POST['new_name'];         // Ambil nama baru dari input form
    $new_email = $_POST['new_email'];       // Ambil email baru dari input form
    $new_password = $_POST['new_password']; // Ambil kata sandi baru (opsional, bisa kosong)

    // Validasi input: Pastikan nama dan email tidak kosong
    if (empty($new_name) || empty($new_email)) {
        $_SESSION['error'] = "Nama dan email tidak boleh kosong.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        // Validasi format email baru
        $_SESSION['error'] = "Format email baru tidak valid.";
    } else {
        // Periksa apakah email baru sudah digunakan oleh pengguna lain
        // Prepared statement untuk mencari pengguna lain dengan email yang sama.
        $stmt_check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt_check_email->bind_param("si", $new_email, $current_user_id); // 's' untuk string (email), 'i' untuk integer (user_id)
        $stmt_check_email->execute();
        $stmt_check_email->store_result(); // Simpan hasil query untuk memeriksa jumlah baris

        if ($stmt_check_email->num_rows > 0) {
            // Email baru sudah digunakan oleh pengguna lain
            $_SESSION['error'] = "Email baru sudah digunakan oleh pengguna lain.";
        } else {
            // Bangun query SQL UPDATE secara dinamis
            $update_sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
            $params = [$new_name, $new_email, $current_user_id];
            $types = "ssi"; // Tipe parameter: string, string, integer

            // Jika kata sandi baru diisi, tambahkan ke query UPDATE dan parameter
            if (!empty($new_password)) {
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash kata sandi baru
                $update_sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
                $params = [$new_name, $new_email, $hashed_new_password, $current_user_id];
                $types = "sssi"; // Tipe parameter baru: string, string, string, integer
            }

            // Prepared statement untuk memperbarui profil
            $stmt = $conn->prepare($update_sql);
            // Menggunakan operator spread (...) untuk melewatkan array $params sebagai argumen terpisah
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                // Update sesi dengan nama dan email baru
                $_SESSION['user_name'] = $new_name;
                $_SESSION['user_email'] = $new_email;
                $_SESSION['message'] = "Profil berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal memperbarui profil: " . $stmt->error;
            }
            $stmt->close(); // Tutup prepared statement
        }
        $stmt_check_email->close(); // Tutup prepared statement untuk cek email
    }
    // Redirect kembali ke halaman profil untuk menampilkan pesan (sukses atau error)
    header("Location: index.php?page=profile");
    exit(); // Penting: Hentikan eksekusi skrip setelah redirect
}
?>

<section id="profile-section" class="profile-section">
    <h2>Edit Profil Anda</h2>
    <form action="profile.php" method="post">
        <div class="form-group">
            <label for="new_name">Nama:</label>
            <input type="text" id="new_name" name="new_name" class="form-control" value="<?php echo htmlspecialchars($current_user_name); ?>" required>
        </div>
        <div class="form-group">
            <label for="new_email">Email:</label>
            <input type="email" id="new_email" name="new_email" class="form-control" value="<?php echo htmlspecialchars($current_user_email); ?>" required>
        </div>
        <div class="form-group">
            <label for="new_password">Kata Sandi Baru (kosongkan jika tidak ingin mengubah):</label>
            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Isi untuk mengubah kata sandi">
        </div>
        <div class="form-actions">
            <button type="submit" name="update_profile" class="btn btn-primary">Perbarui Profil</button>
        </div>
    </form>
</section>
