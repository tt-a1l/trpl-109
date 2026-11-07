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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - ReUsePoli</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <nav class="navbar">
        <h2>ReUsePoli</h2>
        <div class="menu">
            <a href="#">Beranda</a>
            <a href="profil.php">Profil</a>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </nav>

    <main class="container">
        <h1>Temukan Barang yang Kamu Butuhkan</h1>
        <p class="deskripsi-beranda">
            Berbagi barang layak pakai untuk civitas Polibatam.
        </p>

        <form action="beranda.php" method="GET">
            <div class="pencarian">
                <input type="text" name="cari" placeholder="Cari nama atau kategori barang..."
                    value="<?= htmlspecialchars($cari) ?>">

                <button type="submit">Cari</button>

                <?php if ($cari !== '' || $kategori !== ''): ?>
                    <a href="beranda.php" class="tambah-barang">Reset</a>
                <?php endif; ?>
            </div>

            <h2>Kategori</h2>

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
                    <button type="submit" name="kategori" value="<?= htmlspecialchars($item) ?>" style="<?= $kategori === $item
                          ? 'background:#333;color:white;'
                          : '' ?>">
                        <?= htmlspecialchars($item) ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </form>


        <div class="judul-barang">

            <?php if ($cari !== '' || $kategori !== ''): ?>
                <p>
                    Hasil pencarian
                    <?php if ($cari !== ''): ?>
                        untuk <strong><?= htmlspecialchars($cari) ?></strong>
                    <?php endif; ?>

                    <?php if ($kategori !== ''): ?>
                        kategori <strong><?= htmlspecialchars($kategori) ?></strong>
                    <?php endif; ?>.
                    Ditemukan <?= mysqli_num_rows($query) ?> barang.
                </p>
            <?php endif; ?>

            <h2>Barang Tersedia</h2>
            <a href="posting_barang.php" class="tambah-barang">
                + Posting Barang
            </a>
        </div>



        <div class="daftar-barang">

            <?php if ($query && mysqli_num_rows($query) > 0): ?>

                <?php while ($barang = mysqli_fetch_assoc($query)): ?>

                    <div class="card-barang">

                        <div class="foto-barang">
                            <?php if (!empty($barang['foto'])): ?>
                                <img src="../assets/barang/<?= htmlspecialchars(basename($barang['foto'])) ?>"
                                    alt="<?= htmlspecialchars($barang['namabarang']) ?>">
                            <?php else: ?>
                                <span>Belum ada foto</span>
                            <?php endif; ?>
                        </div>

                        <h3>
                            <?= htmlspecialchars($barang['namabarang']) ?>
                        </h3>

                        <p>
                            Kondisi: <?= htmlspecialchars($barang['kondisibarang']) ?>
                        </p>

                        <p class="status">
                            <?= htmlspecialchars($barang['statusbarang']) ?>
                        </p>

                        <a href="detail_barang.php?id=<?= (int) $barang['id_barang'] ?>" class="detail">
                            Lihat Detail
                        </a>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <p>Belum ada barang tersedia untuk ditampilkan.</p>

            <?php endif; ?>

        </div>


    </main>
</body>

</html>