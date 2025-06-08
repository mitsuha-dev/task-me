<?php
// dashboard.php

/**
 * Nah, file ini adalah jantungnya dashboard kita.
 * Di sini, kita bakal ngatur beberapa hal penting:
 * 1. Pastiin user udah login, kalo belum, suruh login dulu!
 * 2. Ambilin ID dan nama user yang lagi aktif dari sesi mereka.
 * 3. Ngurusin kalo ada user yang nambahin tugas baru.
 * 4. Ngurusin kalo ada user yang nyelesaiin tugasnya.
 * 5. Ngurusin juga kalo ada user yang mau ngehapus tugas.
 * 6. Ambil semua daftar tugas yang dimiliki sama user ini, terus tampilin.
 * 7. Tunjukin form buat nambah tugas baru.
 * 8. Dan terakhir, tampilin deh semua tugas yang udah ada di daftar.
 */

// Pertama-tama, kita cek dulu nih, udah nyambung ke database belum ($conn)?
// Terus, udah ada user ID di sesi belum ($_SESSION['user_id'])?
// Kalo salah satu aja belum ada, berarti user belum login atau ada masalah koneksi.
// Langsung aja kita tendang balik ke halaman login!
if (!isset($conn) || !isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Eh, kamu harus login dulu dong buat ngakses halaman ini.';
    header('Location: index.php?page=login');
    exit(); // Penting nih, biar script berhenti di sini setelah redirect.
}

// Oke, kalo udah aman, kita ambil deh ID sama nama user yang lagi aktif dari sesi.
$current_user_id = $_SESSION['user_id'];
$current_user_name = $_SESSION['user_name'];

// =====================================================================================================
// INI BAGIAN LOGIKA BUAT NGATUR TUGAS-TUGASNYA (masih di dashboard.php)
// =====================================================================================================

// Nah, ini buat ngurusin kalo ada yang nambah tugas baru.
// Kode di bawah ini cuma jalan kalo tombol 'Tambah Tugas' di form itu dipencet (alias, $_POST['add_task'] ada).
if (isset($_POST['add_task'])) {
    $task_name = $_POST['task_name']; // Kita ambil nama tugasnya dari inputan user.

    // Penting nih, cek dulu nama tugasnya kosong apa enggak. Kalo kosong, jangan diterusin!
    if (!empty($task_name)) {
        // Siapin perintah SQL buat masukin tugas baru ke tabel 'tasks'.
        // Kita pake prepared statement biar aman dari serangan SQL injection.
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, task_name) VALUES (?, ?)");
        // Nah, ini buat ngasih tau tipe datanya: 'i' buat integer (user_id), 's' buat string (task_name).
        $stmt->bind_param("is", $current_user_id, $task_name);

        // Coba jalanin perintah SQL-nya. Kalo berhasil...
        if ($stmt->execute()) {
            $_SESSION['message'] = "Asyik, tugas berhasil ditambahkan!";
        } else {
            // Kalo gagal, kasih tau error-nya apa (ini buat debugging aja).
            $_SESSION['error'] = "Yah, gagal nambahin tugas: " . $stmt->error;
        }
        $stmt->close(); // Jangan lupa tutup prepared statement-nya.
    } else {
        $_SESSION['error'] = "Nama tugasnya kok kosong? Diisi dong!";
    }
    // Setelah selesai proses POST (entah berhasil atau gagal),
    // kita langsung redirect balik ke halaman dashboard ini lagi.
    // Ini penting banget buat ngehindarin masalah kalo user nge-refresh halaman setelah submit form.
    header("Location: index.php?page=dashboard");
    exit();
}

// Oke, sekarang giliran ngurusin kalo ada tugas yang mau diselesaiin.
// Blok ini jalan kalo ada link 'Selesai' yang diklik (jadi ada parameter 'complete_task' di URL).
if (isset($_GET['complete_task'])) {
    $task_id = $_GET['complete_task']; // Ambil ID tugasnya dari parameter URL.

    // Siapin perintah SQL buat ngubah status 'is_completed' jadi TRUE.
    // Pastiin juga tugas yang diselesaiin itu emang punya user yang lagi login ya.
    $stmt = $conn->prepare("UPDATE tasks SET is_completed = TRUE WHERE id = ? AND user_id = ?");
    // 'ii' berarti dua parameter integer (ID tugas sama ID user).
    $stmt->bind_param("ii", $task_id, $current_user_id);

    // Jalanin perintahnya.
    if ($stmt->execute()) {
        $_SESSION['message'] = "Sip, tugas berhasil diselesaikan!";
    } else {
        $_SESSION['error'] = "Aduh, gagal nyelesaiin tugas: " . $stmt->error;
    }
    $stmt->close(); // Tutup statement.
    // Redirect balik lagi ke dashboard.
    header("Location: index.php?page=dashboard");
    exit();
}

// Terakhir, ini buat ngurusin kalo ada tugas yang mau dihapus.
// Sama kayak sebelumnya, blok ini jalan kalo ada link 'Hapus' yang diklik.
if (isset($_GET['delete_task'])) {
    $task_id = $_GET['delete_task']; // Ambil ID tugasnya dari URL.

    // Siapin perintah SQL buat ngehapus tugas dari tabel 'tasks'.
    // Lagi-lagi, pastiin yang dihapus itu tugas punya user yang lagi login.
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    // 'ii' buat dua parameter integer.
    $stmt->bind_param("ii", $task_id, $current_user_id);

    // Jalanin perintahnya.
    if ($stmt->execute()) {
        $_SESSION['message'] = "Yeaay, tugas berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Duh, gagal ngehapus tugas: " . $stmt->error;
    }
    $stmt->close(); // Tutup statement.
    // Redirect balik lagi ke dashboard.
    header("Location: index.php?page=dashboard");
    exit();
}

// Oke, sekarang waktunya ambil semua daftar tugas punya user yang lagi login.
$tasks = []; // Buat wadah kosong dulu buat nampung tugas-tugasnya.
// Pastiin $conn (koneksi database) itu ada sebelum kita jalanin query.
// Seharusnya sih udah ada dari awal, tapi nggak ada salahnya dicek lagi.
if (isset($conn)) {
    // Siapin perintah SQL buat ngambil semua tugas punya user ini.
    // Kita urutin dari yang terbaru dulu ya.
    $stmt = $conn->prepare("SELECT id, task_name, is_completed FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $current_user_id); // 'i' buat ID user (integer).
    $stmt->execute();
    $result = $stmt->get_result(); // Ambil hasilnya.

    // Kita looping deh satu per satu baris hasil query-nya.
    // Tiap baris kita masukin ke array $tasks.
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    $stmt->close(); // Tutup statement-nya ya.
} else {
    // Kalo sampe sini dan $conn nggak ada, berarti ada masalah serius nih.
    // Seharusnya redirect di awal udah nanganin ini.
    $_SESSION['error'] = "Waduh, ada kesalahan koneksi database pas mau ngambil tugas.";
}
?>

<section id="dashboard-section">
    <h2>Selamat Datang, <?php echo htmlspecialchars($current_user_name); ?>!</h2>
    
    <div class="add-task-form">
        <form action="index.php?page=dashboard" method="post">
            <div class="form-group">
                <label for="task_name">Pengen nambah tugas baru apa nih?</label>
                <input type="text" id="task_name" name="task_name" class="form-control" placeholder="Contoh: Belajar PHP & SQL" required>
            </div>
            <div class="form-actions">
                <button type="submit" name="add_task" class="btn btn-primary">Tambah Tugas</button>
            </div>
        </form>
    </div>

    <?php
    // Kita cek dulu nih, daftar tugasnya kosong apa enggak?
    if (empty($tasks)):
    ?>
        <p style="text-align: center; margin-top: 2rem; color: var(--dark-gray);">Hmmm, kamu belum punya tugas nih. Yuk, tambahin tugas pertamamu!</p>
    <?php
    // Kalo ada tugasnya, baru deh kita tampilin.
    else:
    ?>
        <ul class="task-list">
            <?php
            // Kita looping nih setiap tugas yang ada di array $tasks.
            // Terus kita tampilin satu per satu sebagai item di daftar.
            foreach ($tasks as $task):
            ?>
                <li class="task-item <?php echo $task['is_completed'] ? 'completed' : ''; ?>">
                    <div class="task-item-content">
                        <span class="task-name"><?php echo htmlspecialchars($task['task_name']); ?></span>
                    </div>
                    <div class="task-actions">
                        <?php
                        // Tombol 'Selesai' cuma kita tampilin kalo tugasnya belum selesai ya.
                        if (!$task['is_completed']):
                        ?>
                            <a href="index.php?page=dashboard&complete_task=<?php echo $task['id']; ?>" class="btn btn-success" title="Tandai Selesai"> <i class="fas fa-check"></i>
                            </a>
                        <?php endif; ?>
                        <a href="index.php?page=dashboard&delete_task=<?php echo $task['id']; ?>" class="btn btn-danger" title="Hapus Tugas" onclick="return confirm('Yakin nih mau hapus tugas ini? Nanti nyesel lho!');"> <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>