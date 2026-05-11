<?php
session_start();

if (!isset($_SESSION['nama']) || $_SESSION['nama'] !== 'admin' || !isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

require 'koneksi.php';

$id = (int) $_GET['id'];
$pesan = "";

$stmt_get = $conn->prepare("SELECT nama FROM users WHERE id = ?");
$stmt_get->bind_param("i", $id);
$stmt_get->execute();
$stmt_get->bind_result($nama_lama);
$stmt_get->fetch();
$stmt_get->close();

if (empty($nama_lama)) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_baru     = trim($_POST['nama']);
    $password_baru = $_POST['password'];

    $hashed_password_baru = password_hash($password_baru, PASSWORD_BCRYPT);

    $stmt_update = $conn->prepare("UPDATE users SET nama = ?, password = ? WHERE id = ?");
    $stmt_update->bind_param("ssi", $nama_baru, $hashed_password_baru, $id);

    if ($stmt_update->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $pesan = "Gagal menyimpan: " . $stmt_update->error;
    }
    $stmt_update->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Pengguna</title>
</head>
<body>
    <h2>Edit Data Pengguna</h2>

    <?php if ($pesan != "") echo "<p>$pesan</p>"; ?>

    <form method="POST" action="">
        <label>Nama Pengguna:</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($nama_lama) ?>" required><br><br>

        <label>Password Baru:</label><br>
        <input type="password" name="password" placeholder="Masukkan password baru" required><br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>
    <br>
    <a href="dashboard.php"><button>Batal</button></a>
</body>
</html>
