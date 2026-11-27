<?php
require '../config/koneksi.php';
require_once __DIR__ . '/../auth/cek_login.php';

// ambil input pencarian dan kategori dari URL
$cari = trim($_GET['cari'] ?? '');
$kategori = trim($_GET['kategori'] ?? '');

// query dasar: hanya barang tersedia
$sql = "SELECT * FROM tbl_barang WHERE statusbarang = 'Tersedia'";

// filter pencarian nama atau kategori
if ($cari !== '') {
    $sql .= " AND (namabarang LIKE ? OR kategori LIKE ?)";
}

// filter kategori yang dipilih
if ($kategori !== '') {
    $sql .= " AND kategori = ?";
}

$sql .= " ORDER BY id_barang DESC";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Gagal menyiapkan pencarian barang.");
}

// siapkan parameter pencarian
if ($cari !== '' && $kategori !== '') {
    $pola = "%{$cari}%";
    mysqli_stmt_bind_param($stmt, "sss", $pola, $pola, $kategori);
} elseif ($cari !== '') {
    $pola = "%{$cari}%";
    mysqli_stmt_bind_param($stmt, "ss", $pola, $pola);
} elseif ($kategori !== '') {
    mysqli_stmt_bind_param($stmt, "s", $kategori);
}

mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);

$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - ReUsePoli</title>
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
            <a href="beranda.php" class="<?= $current === 'beranda.php' ? 'active' : '' ?>">Beranda</a>
            <a href="barang_saya.php" class="<?= $current === 'barang_saya.php' ? 'active' : '' ?>">Barang Saya</a>
            <a href="profil.php" class="<?= $current === 'profil.php' ? 'active' : '' ?>">Profil</a>
            <a href="../auth/logout.php" class="nav-logout">Logout</a>
        </div>
    </nav>

    <main class="container">

        <div class="page-header">
            <h1>Temukan Barang yang Kamu Butuhkan</h1>
            <p class="deskripsi-beranda">Berbagi barang layak pakai untuk civitas Polibatam.</p>
        </div>

        <!-- PENCARIAN & FILTER -->
        <div class="search-box">
            <form action="beranda.php" method="GET">

                <div class="pencarian">
                    <input
                        type="text"
                        name="cari"
                        placeholder="🔍  Cari nama atau kategori barang..."
                        value="<?= htmlspecialchars($cari) ?>">

                    <button type="submit">Cari</button>

                    <?php if ($cari !== '' || $kategori !== ''): ?>
                        <a href="beranda.php" class="reset-link">✕ Reset</a>
                    <?php endif; ?>
                </div>

                <p class="kategori-heading">Filter Kategori</p>

                <div class="kategori">
                    <?php
                    $daftar_kategori = [
                        'Buku',
                        'Alat Tulis',
                        'Elektronik',
                        'Furniture',
                        'Lainnya'
                    ];
                    ?>

                    <?php foreach ($daftar_kategori as $item): ?>
                        <button
                            type="submit"
                            name="kategori"
                            value="<?= htmlspecialchars($item) ?>"
                            class="<?= $kategori === $item ? 'active-chip' : '' ?>">
                            <?= htmlspecialchars($item) ?>
                        </button>
                    <?php endforeach; ?>
                </div>

            </form>
        </div>

        <!-- HASIL PENCARIAN INFO -->
        <?php if ($cari !== '' || $kategori !== ''): ?>
            <div class="hasil-info">
                Menampilkan hasil
                <?php if ($cari !== ''): ?>
                    untuk <strong>"<?= htmlspecialchars($cari) ?>"</strong>
                <?php endif; ?>
                <?php if ($kategori !== ''): ?>
                    — kategori <strong><?= htmlspecialchars($kategori) ?></strong>
                <?php endif; ?>
                &nbsp;·&nbsp; <strong><?= mysqli_num_rows($query) ?></strong> barang ditemukan
            </div>
        <?php endif; ?>

        <!-- HEADER DAFTAR BARANG -->
        <div class="judul-barang">
            <h2>Barang Tersedia</h2>
            <a href="posting_barang.php" class="tambah-barang">+ Posting Barang</a>
        </div>

        <!-- GRID BARANG -->
        <div class="daftar-barang">

            <?php if ($query && mysqli_num_rows($query) > 0): ?>

                <?php while ($barang = mysqli_fetch_assoc($query)): ?>

                    <div class="card-barang">

                        <div class="foto-barang">
                            <?php if (!empty($barang['foto'])): ?>
                                <img
                                    src="../assets/barang/<?= htmlspecialchars(basename($barang['foto'])) ?>"
                                    alt="<?= htmlspecialchars($barang['namabarang']) ?>"
                                    loading="lazy">
                            <?php else: ?>
                                <div class="foto-placeholder">
                                    <span>📦</span>
                                    Belum ada foto
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <span class="badge badge-kategori"><?= htmlspecialchars($barang['kategori']) ?></span>

                            <h3><?= htmlspecialchars($barang['namabarang']) ?></h3>

                            <p>Kondisi: <?= htmlspecialchars($barang['kondisibarang']) ?></p>

                            <?php
                            $statusClass = match($barang['statusbarang']) {
                                'Tersedia' => 'badge-tersedia',
                                'Tidak Tersedia' => 'badge-tidak-tersedia',
                                default => 'badge-proses'
                            };
                            ?>
                            <span class="badge <?= $statusClass ?>" style="margin-top: 4px;">
                                <?= $barang['statusbarang'] === 'Tersedia' ? '✅' : '❌' ?>
                                <?= htmlspecialchars($barang['statusbarang']) ?>
                            </span>

                            <a href="detail_barang.php?id=<?= (int) $barang['id_barang'] ?>" class="detail">
                                Lihat Detail →
                            </a>
                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="empty-state">
                    <div class="empty-state-icon">🗃️</div>
                    <h3>Belum ada barang tersedia</h3>
                    <p>
                        <?php if ($cari !== '' || $kategori !== ''): ?>
                            Tidak ada barang yang sesuai dengan pencarianmu. Coba kata kunci lain.
                        <?php else: ?>
                            Jadilah yang pertama berbagi barang layak pakai!
                        <?php endif; ?>
                    </p>
                    <a href="posting_barang.php" class="tambah-barang">+ Posting Barang</a>
                </div>

            <?php endif; ?>

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

        // Aktifkan chip kategori yang sedang aktif
        document.querySelectorAll('.kategori button').forEach(btn => {
            if (btn.classList.contains('active-chip')) {
                btn.style.background = 'var(--clr-primary)';
                btn.style.color = 'white';
                btn.style.borderColor = 'var(--clr-primary)';
            }
        });
    </script>

</body>

</html>
