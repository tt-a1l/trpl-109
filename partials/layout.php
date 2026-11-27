<?php
require_once __DIR__ . '/helpers.php';

/**
 * Membuka halaman: <head>, navbar, dan <main>.
 *
 * @param string $judul  judul tab
 * @param string $tipe   'user' | 'admin'
 * @param string $aktif  kunci menu aktif
 */
function layout_open(string $judul, string $tipe = 'user', string $aktif = '', string $mainClass = ''): void
{
    ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($judul) ?> - ReUsePoli</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body class="<?= $tipe === 'admin' ? 'is-admin' : '' ?>">

<?php
    if ($tipe === 'admin') {
        $menu = [
            'dashboard' => ['admin.php', 'Dashboard', 'dash'],
            'barang'    => ['admin_barang.php', 'Kelola Barang', 'box'],
            'pengguna'  => ['data_pengguna.php', 'Data Pengguna', 'users'],
        ];
        $beranda = 'admin.php';
        $logout  = '../admin/logout.php';
        $merek   = 'ReUsePoli <small>Admin</small>';
    } else {
        $menu = [
            'beranda' => ['beranda.php', 'Beranda', 'home'],
            'riwayat' => ['riwayat.php', 'Riwayat Pengajuan', 'clock'],
            'barang'  => ['barang_saya.php', 'Barang Saya', 'box'],
            'profil'  => ['profil.php', 'Profil', 'user'],
        ];
        $beranda = 'beranda.php';
        $logout  = '../auth/logout.php';
        $merek   = 'ReUsePoli <small>Campus Reuse</small>';
    }
?>
    <a class="skip-link" href="#konten">Lewati ke konten</a>

    <header class="topbar">
        <div class="topbar-inner">
            <a href="<?= $beranda ?>" class="brand">
                <span class="brand-mark"><?= icon('leaf', 20) ?></span>
                <span class="brand-text"><?= $merek ?></span>
            </a>

            <button class="nav-toggle" id="nav-toggle" aria-label="Buka menu" aria-expanded="false" aria-controls="nav-menu">
                <?= icon('menu', 22) ?>
            </button>

            <nav class="nav-menu" id="nav-menu" aria-label="Navigasi utama">
                <?php foreach ($menu as $kunci => [$href, $label, $ikon]): ?>
                    <a href="<?= $href ?>" class="nav-link <?= $aktif === $kunci ? 'active' : '' ?>"
                       <?= $aktif === $kunci ? 'aria-current="page"' : '' ?>>
                        <?= icon($ikon, 17) ?><span><?= e($label) ?></span>
                    </a>
                <?php endforeach; ?>

                <?php if ($tipe === 'user'): ?>
                    <a href="posting_barang.php" class="btn btn-amber btn-sm nav-cta"><?= icon('gift', 16) ?>Bagikan Barang</a>
                <?php endif; ?>

                <a href="<?= $logout ?>" class="nav-link nav-logout"><?= icon('logout', 17) ?><span>Keluar</span></a>
            </nav>
        </div>
    </header>

    <main id="konten" class="container <?= e($mainClass) ?>">
<?php
}

function layout_close(string $skripTambahan = ''): void
{
    ?>
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <strong>ReUsePoli</strong>
            <span>Platform berbagi barang layak pakai &amp; sumber daya kampus Polibatam. Gratis, tanpa jual-beli.</span>
        </div>
    </footer>

    <script src="../script/ui.js"></script>
<?= $skripTambahan ?>
</body>

</html>
<?php
}
