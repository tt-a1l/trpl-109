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
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang - ReUsePoli</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>

    <nav class="navbar">
        <h2>ReUsePoli</h2>
        <div class="menu">
            <a href="beranda.php">Beranda</a>
            <a href="barang_saya.php">Barang Saya</a>
            <a href="profil.php">Profil</a>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </nav>

    <main class="container">

        <a href="beranda.php" class="kembali">← Kembali</a>

        <div class="daftar-barang">

            <div class="foto-barang">
                <?php if (!empty($barang['foto'])): ?>
                    <img src="../assets/barang/<?= htmlspecialchars(basename($barang['foto'])) ?>"
                        alt="<?= htmlspecialchars($barang['namabarang']) ?>">
                <?php else: ?>
                    <p>Foto barang belum tersedia.</p>
                <?php endif; ?>
            </div>

            <div class="informasi-barang">

                <h1><?= htmlspecialchars($barang['namabarang']) ?></h1>

                <p>
                    <strong>Kategori:</strong>
                    <?= htmlspecialchars($barang['kategori']) ?>
                </p>

                <p>
                    <strong>Kondisi:</strong>
                    <?= htmlspecialchars($barang['kondisibarang']) ?>
                </p>

                <p>
                    <strong>Status:</strong>
                    <?= htmlspecialchars($barang['statusbarang']) ?>
                </p>

                <p>
                    <strong>Pemilik:</strong>
                    <?= htmlspecialchars($barang['pemilik']) ?>
                </p>

                <p>
                    <strong>Kontak Pemilik:</strong>
                    <?= !empty($barang['nohp'])
                        ? htmlspecialchars($barang['nohp'])
                        : 'Kontak belum tersedia' ?>
                </p>

                <h3>Deskripsi</h3>

                <p>
                    <?= !empty($barang['deskripsi'])
                        ? nl2br(htmlspecialchars($barang['deskripsi']))
                        : 'Tidak ada deskripsi.' ?>
                </p>

            </div>

        </div>

    </main>

</body>

</html>