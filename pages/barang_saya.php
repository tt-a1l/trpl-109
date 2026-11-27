<?php
require '../config/koneksi.php';
require_once __DIR__ . '/../auth/cek_login.php';

$email = $_SESSION['user'];

$email_aman = mysqli_real_escape_string($koneksi, $email);

$query_user = mysqli_query(
    $koneksi,
    "SELECT namalengkap FROM tbl_users WHERE email = '$email_aman'"
);

$user = mysqli_fetch_assoc($query_user);
$pemilik = $user['namalengkap'];

$pemilik_aman = mysqli_real_escape_string($koneksi, $pemilik);

$query_barang = mysqli_query(
    $koneksi,
    "SELECT * FROM tbl_barang
     WHERE pemilik = '$pemilik_aman'
     ORDER BY id_barang DESC"
);

$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Saya - ReUsePoli</title>
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
            <a href="barang_saya.php" class="active">Barang Saya</a>
            <a href="profil.php">Profil</a>
            <a href="../auth/logout.php" class="nav-logout">Logout</a>
        </div>
    </nav>

    <main class="container">

        <div class="section-header">
            <div>
                <h1 class="section-title">Barang Saya</h1>
                <p class="section-desc">Kelola barang yang telah kamu posting.</p>
            </div>
            <a href="posting_barang.php" class="tambah-barang">+ Posting Barang</a>
        </div>

        <?php if ($query_barang && mysqli_num_rows($query_barang) > 0): ?>

            <div class="barang-saya-list">

                <?php while ($barang = mysqli_fetch_assoc($query_barang)): ?>

                    <div class="barang-saya-card">

                        <!-- FOTO THUMBNAIL -->
                        <div class="foto-barang-saya">
                            <?php if (!empty($barang['foto'])): ?>
                                <img
                                    src="../assets/barang/<?= htmlspecialchars(basename($barang['foto'])) ?>"
                                    alt="<?= htmlspecialchars($barang['namabarang']) ?>">
                            <?php else: ?>
                                <span>📦<br>Belum ada foto</span>
                            <?php endif; ?>
                        </div>

                        <!-- INFO BARANG -->
                        <div class="info-barang-saya">
                            <h2><?= htmlspecialchars($barang['namabarang']) ?></h2>

                            <p>
                                <span class="badge badge-kategori"><?= htmlspecialchars($barang['kategori']) ?></span>
                            </p>

                            <p style="margin-top: 6px;">
                                <strong>Kondisi:</strong> <?= htmlspecialchars($barang['kondisibarang']) ?>
                            </p>

                            <p>
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
                            </p>
                        </div>

                        <!-- TOMBOL AKSI -->
                        <div class="aksi-barang">
                            <a href="edit_barang.php?id=<?= (int) $barang['id_barang'] ?>" class="edit-barang">
                                ✏️ Edit
                            </a>

                            <form
                                action="hapus_barang.php"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus barang ini? Tindakan ini tidak bisa dibatalkan.')">
                                <input type="hidden" name="id_barang" value="<?= (int) $barang['id_barang'] ?>">
                                <button type="submit" class="hapus-barang">🗑 Hapus</button>
                            </form>
                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>Belum ada barang yang diposting</h3>
                <p>Mulai berbagi barang layak pakai yang tidak lagi kamu gunakan.</p>
                <a href="posting_barang.php" class="tambah-barang">+ Posting Barang Pertama</a>
            </div>

        <?php endif; ?>

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
