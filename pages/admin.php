<?php
require '../config/koneksi.php';
require_once __DIR__ . '/../admin/cek_login.php';

$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ReUsePoli</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>

    <nav class="navbar navbar-admin">
        <a href="admin.php" class="navbar-brand">
            <span class="brand-icon">Re</span>
            ReUsePoli Admin
        </a>
        <button class="navbar-toggle" id="navbar-toggle" aria-label="Menu">
            <span style="background: rgba(255,255,255,0.7);"></span>
            <span style="background: rgba(255,255,255,0.7);"></span>
            <span style="background: rgba(255,255,255,0.7);"></span>
        </button>
        <div class="navbar-menu" id="navbar-menu">
            <a href="admin.php" class="<?= $current === 'admin.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="data_pengguna.php" class="<?= $current === 'data_pengguna.php' ? 'active' : '' ?>">Data Pengguna</a>
            <a href="../admin/logout.php" class="nav-logout">Logout</a>
        </div>
    </nav>

    <main class="container">

        <div class="page-header">
            <h1>Dashboard Admin</h1>
            <p>Kelola data dan aktivitas pada aplikasi ReUsePoli.</p>
        </div>

        <div class="dashboard-admin">

            <a href="data_pengguna.php" class="admin-card">
                <div class="admin-card-icon">👥</div>
                <h2>Data Pengguna</h2>
                <p>Lihat dan kelola daftar pengguna yang terdaftar di aplikasi.</p>
                <div class="admin-card-arrow">→</div>
            </a>

            <div class="admin-card" style="opacity: 0.5; cursor: default; pointer-events: none;">
                <div class="admin-card-icon">📦</div>
                <h2>Manajemen Barang</h2>
                <p>Fitur ini akan tersedia di pembaruan berikutnya.</p>
                <div class="admin-card-arrow" style="color: var(--clr-text-muted);">—</div>
            </div>

        </div>

    </main>

    <script>
        const toggle = document.getElementById('navbar-toggle');
        const menu = document.getElementById('navbar-menu');
        if (toggle && menu) {
            toggle.addEventListener('click', function () {
                menu.classList.toggle('open');
            });
        }
    </script>

</body>

</html>
