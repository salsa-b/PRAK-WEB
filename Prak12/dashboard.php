<?php
session_start();

if (!isset($_SESSION['nama'])) {
    header("Location: auth.php");
    exit();
}

require 'koneksi.php';

$nama_session = $_SESSION['nama'];

if (isset($_GET['hapus']) && $nama_session === 'admin') {
    $id_hapus = (int) $_GET['hapus'];
    $stmt_hapus = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt_hapus->bind_param("i", $id_hapus);
    $stmt_hapus->execute();
    $stmt_hapus->close();
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            margin: 20px;
            font-family: Times New Roman, serif;
            font-size: 14px;
            padding: 15px;
        }
        h1, h2, h3, p, a, button {
            font-family: Times New Roman, serif;
        }
        .selamat-datang {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        button {
            padding: 2px 8px;
            font-size: 13px;
            cursor: pointer;
        }
        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 10px 0;
        }
        h3 {
            font-size: 14px;
            font-weight: bold;
            margin: 12px 0 8px 0;
        }
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 5px 14px;
            text-align: left;
        }
        th {
            background-color: #fff;
            font-weight: bold;
            text-align: center;
        }
        td:first-child {
            width: 30px;
            text-align: left;
        }
        td:nth-child(2) {
            width: 80px;
        }
        td:nth-child(3) {
            text-align: center;
            width: 100px;
        }
    </style>
</head>
<body>
    <div class="selamat-datang">Selamat Datang, <?= htmlspecialchars($nama_session) ?>!</div>
    <a href="logout.php"><button>Logout</button></a>
    <hr>

    <?php if ($nama_session === 'admin'): ?>

        <h3>Menu Admin: Kelola Pengguna</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT id, nama FROM users ORDER BY id DESC");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>"><button>Edit</button></a>
                        <a href="dashboard.php?hapus=<?= $row['id'] ?>"
                           onclick="return confirm('Yakin hapus pengguna ini?')"><button>Hapus</button></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php endif; ?>
</body>
</html>
