
<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../admin/cek_login.php';

// ambil semua data pengguna
$query = mysqli_query(
    $koneksi,
    "SELECT id_user, namalengkap, email, nohp
     FROM tbl_users
     ORDER BY id_user DESC"
);

if (!$query) {
    die("Gagal mengambil data pengguna.");
}

$total = mysqli_num_rows($query);
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengguna - ReUsePoli Admin</title>
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
            <a href="../auth/logout.php" class="nav-logout">Logout</a>
        </div>
    </nav>

    <main class="container">

        <div class="page-header">
            <h1>Data Pengguna</h1>
            <p>Daftar pengguna yang terdaftar di aplikasi ReUsePoli.</p>
        </div>

        <div class="stats-strip">
            <span class="stat-badge">👥 Total Pengguna: <strong><?= $total ?></strong></span>
        </div>

        <div class="area-tabel">
            <table class="tabel-pengguna">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>No. HP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($pengguna = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($pengguna['namalengkap'] ?? '') ?></td>
                                <td><?= htmlspecialchars($pengguna['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($pengguna['nohp'] ?? '-') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr class="tabel-empty">
                            <td colspan="4">
                                <div style="text-align:center; padding: 20px; color: var(--clr-text-muted);">
                                    👥 Belum ada data pengguna.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
