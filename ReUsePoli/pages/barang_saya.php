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
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Barang Saya - ReUsePoli</title>

    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <nav class="navbar">
        <h2>ReUsePoli</h2>
        <div class="menu">
            <a href="beranda.php">Beranda</a>
            <a href="profil.php">Profil</a>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </nav>

    <main class="container">
        <div class="judul-barang">
            <div>
                <h1>Barang Saya</h1>
                <p class="deskripsi-beranda">
                    Kelola barang yang telah kamu posting.
                </p>
            </div>

            <a href="posting_barang.php" class="tambah-barang">
                + Posting Barang
            </a>
        </div>


        <?php if ($query_barang && mysqli_num_rows($query_barang) > 0): ?>

            <div class="daftar-barang">

                <?php while ($barang = mysqli_fetch_assoc($query_barang)): ?>

                    <div class="card-barang">

                        <div class="foto-barang">
                            <?php if (!empty($barang['foto'])): ?>
                                <img src="../assets/barang/<?= htmlspecialchars(basename($barang['foto'])) ?>"
                                    alt="<?= htmlspecialchars($barang['namabarang']) ?>">
                            <?php else: ?>
                                <span>Belum ada foto</span>
                            <?php endif; ?>
                        </div>

                        <div class="info-barang-saya">
                            <h2>
                                <?= htmlspecialchars($barang['namabarang']) ?>
                            </h2>

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
                        </div>

                        <div class="aksi-barang">
                            <a href="edit_barang.php?id=<?= (int) $barang['id_barang'] ?>" class="edit-barang">
                                Edit
                            </a>

                            <form action="hapus_barang.php" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                <input type="hidden" name="id_barang" value="<?= (int) $barang['id_barang'] ?>">

                                <button type="submit" class="hapus-barang">
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <p>Kamu belum memposting barang apa pun.</p>

        <?php endif; ?>


    </main>
</body>

</html>