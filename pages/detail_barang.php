<?php
require '../config/koneksi.php';
require_once __DIR__ . '/../auth/cek_login.php';

// ambil ID barang dari URL
$id_barang = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_barang <= 0) {
    header('Location: beranda.php');
    exit;
}

// ambil detail barang dan kontak pemilik
$query = mysqli_query(
    $koneksi,
    "SELECT tbl_barang.*, tbl_users.nohp
     FROM tbl_barang
     LEFT JOIN tbl_users
        ON tbl_barang.pemilik = tbl_users.namalengkap
     WHERE tbl_barang.id_barang = $id_barang
     LIMIT 1"
);

$barang = mysqli_fetch_assoc($query);

if (!$barang) {
    header('Location: beranda.php');
    exit;
}

// Siapkan nomor WA
$nohp_wa = '';
if (!empty($barang['nohp'])) {
    $nohp_clean = preg_replace('/[^0-9]/', '', $barang['nohp']);
    if (strlen($nohp_clean) > 0 && $nohp_clean[0] === '0') {
        $nohp_clean = '62' . substr($nohp_clean, 1);
    }
    $nohp_wa = $nohp_clean;
}

$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($barang['namabarang']) ?> - ReUsePoli</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>

    <nav class="navbar">
        <a href="beranda.php" class="navbar-brand">
            <span class="brand-icon">Re</span>
            ReUsePoli
        </a>
        <button class="navbar-toggle" id="navbar-toggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <div class="navbar-menu" id="navbar-menu">
            <a href="beranda.php">Beranda</a>
            <a href="barang_saya.php">Barang Saya</a>
            <a href="profil.php">Profil</a>
            <a href="../auth/logout.php" class="nav-logout">Logout</a>
        </div>
    </nav>

    <main class="container">

        <a href="beranda.php" class="kembali">← Kembali ke Beranda</a>

        <div class="detail-barang-box">

            <!-- FOTO -->
            <div class="foto-detail">
                <?php if (!empty($barang['foto'])): ?>
                    <img
                        src="../assets/barang/<?= htmlspecialchars(basename($barang['foto'])) ?>"
                        alt="<?= htmlspecialchars($barang['namabarang']) ?>">
                <?php else: ?>
                    <div class="foto-detail-placeholder">
                        <span>📦</span>
                        Foto belum tersedia
                    </div>
                <?php endif; ?>
            </div>

            <!-- INFORMASI -->
            <div class="informasi-barang">

                <span class="badge badge-kategori" style="margin-bottom: 12px;">
                    <?= htmlspecialchars($barang['kategori']) ?>
                </span>

                <h1><?= htmlspecialchars($barang['namabarang']) ?></h1>

                <div class="info-row">
                    <span class="info-label">Kondisi</span>
                    <span class="info-value"><?= htmlspecialchars($barang['kondisibarang']) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <?php
                        $statusClass = match($barang['statusbarang']) {
                            'Tersedia' => 'badge-tersedia',
                            'Tidak Tersedia' => 'badge-tidak-tersedia',
                            default => 'badge-proses'
                        };
                        ?>
                        <span class="badge <?= $statusClass ?>">
                            <?= htmlspecialchars($barang['statusbarang']) ?>
                        </span>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Pemilik</span>
                    <span class="info-value"><?= htmlspecialchars($barang['pemilik']) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Kontak</span>
                    <span class="info-value">
                        <?= !empty($barang['nohp'])
                            ? htmlspecialchars($barang['nohp'])
                            : '<em>Belum tersedia</em>' ?>
                    </span>
                </div>

                <!-- DESKRIPSI -->
                <div class="deskripsi-box">
                    <p class="deskripsi-box-label">📝 Deskripsi Barang</p>
                    <p>
                        <?= !empty($barang['deskripsi'])
                            ? nl2br(htmlspecialchars($barang['deskripsi']))
                            : '<em>Tidak ada deskripsi.</em>' ?>
                    </p>
                </div>

                <!-- TOMBOL WA -->
                <?php if (!empty($nohp_wa)): ?>
                    <a
                        href="https://wa.me/<?= htmlspecialchars($nohp_wa) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="wa-btn">
                        💬 Hubungi Pemilik via WhatsApp
                    </a>
                <?php elseif (!empty($barang['nohp'])): ?>
                    <div class="alert alert-warning mt-16" style="margin-bottom:0;">
                        📱 Kontak pemilik: <?= htmlspecialchars($barang['nohp']) ?>
                    </div>
                <?php endif; ?>

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
