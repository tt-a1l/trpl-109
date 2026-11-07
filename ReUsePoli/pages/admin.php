<?php
require '../config/koneksi.php';
require_once __DIR__ . '/../auth/cek_login_admin.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - ReUsePoli</title>

    <link rel="stylesheet" href="../style/style.css">

</head>

<body>
    <nav class="navbar">
        <h2>ReUsePoli - Admin</h2>
        <div class="menu">
            <a href="admin.php">Admin</a>
            <a href="data_pengguna.php">Pengguna</a>
            <a href="../auth/logout.php">Logout</a>
        </div>

    </nav>

    <main class="container">
        <h1>Dashboard Admin</h1>
        <p class="deskripsi-beranda">
            Kelola data dan aktivitas pada aplikasi Penggunaan Barang Kembali.
        </p>

        <div class="dashboard-admin">
            <a href="data_pengguna.php" class="admin-card">
                <h2>Data Pengguna</h2>
                <p>
                    Kelola data pengguna aplikasi.
                </p>

            </a>
 
        </div>
    </main>
</body>
</html>