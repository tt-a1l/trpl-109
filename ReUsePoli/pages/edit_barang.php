<?php
require '../config/koneksi.php';
require_once __DIR__ . '/../auth/cek_login.php';

$email = $_SESSION['user'];

// ambil nama pemilik dari akun login
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT namalengkap FROM tbl_users WHERE email = ?"
);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$user) {
    exit("Data pengguna tidak ditemukan.");
}

$pemilik = $user['namalengkap'];

$kategori_valid = [
    'Buku', 'Alat Tulis', 'Elektronik', 'Furniture', 'Lainnya'
];

$kondisi_valid = [
    'Sangat Baik', 'Baik', 'Layak Pakai'
];

$folder = __DIR__ . '/../assets/barang/';

// proses simpan perubahan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_barang = filter_input(
        INPUT_POST, 'id_barang', FILTER_VALIDATE_INT
    );

    $namabarang = trim($_POST['namabarang'] ?? '');
    $kategori = $_POST['kategori'] ?? '';
    $kondisibarang = $_POST['kondisibarang'] ?? '';
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if (
        !$id_barang ||
        $namabarang === '' ||
        !in_array($kategori, $kategori_valid, true) ||
        !in_array($kondisibarang, $kondisi_valid, true) ||
        $deskripsi === ''
    ) {
        exit("Data tidak valid. Periksa kembali form.");
    }

    // pastikan barang benar-benar milik akun login
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT foto FROM tbl_barang
         WHERE id_barang = ? AND pemilik = ?"
    );
    mysqli_stmt_bind_param($stmt, "is", $id_barang, $pemilik);
    mysqli_stmt_execute($stmt);
    $barang_lama = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$barang_lama) {
        exit("Barang tidak ditemukan atau bukan milik akunmu.");
    }

    //foto lama tetap digunakan jika tidak memilih foto baru
    $foto_barang = $barang_lama['foto'];
    $foto_baru = null;

    // Proses upload jika pengguna memilih foto baru
    if (
        isset($_FILES['foto_barang']) &&
        $_FILES['foto_barang']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES['foto_barang']['error'] !== UPLOAD_ERR_OK) {
            exit("Upload foto gagal.");
        }

        $tmp = $_FILES['foto_barang']['tmp_name'];
        $ukuran = $_FILES['foto_barang']['size'];

        if ($ukuran > 15 * 1024 * 1024) {
            exit("Ukuran foto maksimal 15 MB.");
        }

        $info = getimagesize($tmp);

        if (!$info) {
            exit("File yang diunggah bukan gambar valid.");
        }

        $ekstensi_valid = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        $mime = $info['mime'];

        if (!isset($ekstensi_valid[$mime])) {
            exit("Format foto harus JPG, PNG, atau WEBP.");
        }

        if (!is_dir($folder) && !mkdir($folder, 0775, true)) {
            exit("Folder penyimpanan foto gagal dibuat.");
        }

        $nama_file = bin2hex(random_bytes(16))
            . '.' . $ekstensi_valid[$mime];

        $tujuan = $folder . $nama_file;

        if (!move_uploaded_file($tmp, $tujuan)) {
            exit("Gagal menyimpan foto baru.");
        }

        $foto_baru = $nama_file;
        $foto_barang = $nama_file;
    }

    //simpan perubahan data barang
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE tbl_barang
         SET namabarang = ?, kategori = ?, kondisibarang = ?,
             deskripsi = ?, foto = ?
         WHERE id_barang = ? AND pemilik = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssis",
        $namabarang,
        $kategori,
        $kondisibarang,
        $deskripsi,
        $foto_barang,
        $id_barang,
        $pemilik
    );

    if (mysqli_stmt_execute($stmt)) {
        // hapus foto lama hanya setelah perubahan berhasil
        if (
            $foto_baru !== null &&
            !empty($barang_lama['foto_barang'])
        ) {
            $foto_lama_path = $folder . basename(
                $barang_lama['foto_barang']
            );

            if (is_file($foto_lama_path)) {
                unlink($foto_lama_path);
            }
        }

        header("Location: barang_saya.php");
        exit;
    }

    // jika database gagal, hapus foto baru yang sudah terunggah
    if ($foto_baru !== null && is_file($folder . $foto_baru)) {
        unlink($folder . $foto_baru);
    }

    exit("Gagal menyimpan perubahan.");
}

// ambil barang untuk ditampilkan di form
$id_barang = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_barang) {
    header("Location: barang_saya.php");
    exit;
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM tbl_barang
     WHERE id_barang = ? AND pemilik = ?"
);
mysqli_stmt_bind_param($stmt, "is", $id_barang, $pemilik);
mysqli_stmt_execute($stmt);
$barang = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$barang) {
    exit("Barang tidak ditemukan atau bukan milik akunmu.");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Penggunaan Barang Kembali</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>

    <nav class="navbar">
        <h2>BarangKembali</h2>

        <div class="menu">
            <a href="beranda.php">Beranda</a>
            <a href="barang_saya.php">Barang Saya</a>
            <a href="riwayat.html">Riwayat</a>
            <a href="profil.php">Profil</a>
        </div>
    </nav>

    <main class="container">
        <a href="barang_saya.php" class="kembali">
            ← Kembali
        </a>

        <div class="posting-box">
            <h1>Edit Barang</h1>

            <p class="deskripsi-beranda">
                Ubah informasi barang yang telah kamu posting.
            </p>

            <form
                action="edit_barang.php?id=<?= (int) $barang['id_barang'] ?>"
                method="POST"
                enctype="multipart/form-data">

                <input
                    type="hidden"
                    name="id_barang"
                    value="<?= (int) $barang['id_barang'] ?>">

                <label>Nama Barang</label>
                <input
                    type="text"
                    name="namabarang"
                    value="<?= htmlspecialchars($barang['namabarang']) ?>"
                    required>

                <label>Kategori</label>
                <select name="kategori" required>
                    <?php foreach ($kategori_valid as $pilihan): ?>
                        <option
                            value="<?= htmlspecialchars($pilihan) ?>"
                            <?= $barang['kategori'] === $pilihan ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pilihan) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Kondisi Barang</label>
                <select name="kondisibarang" required>
                    <?php foreach ($kondisi_valid as $pilihan): ?>
                        <option
                            value="<?= htmlspecialchars($pilihan) ?>"
                            <?= $barang['kondisibarang'] === $pilihan ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pilihan) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Deskripsi Barang</label>
                <textarea
                    name="deskripsi"
                    rows="5"
                    required><?= htmlspecialchars($barang['deskripsi']) ?></textarea>

                <label>Foto Barang Saat Ini</label>

                <?php if (!empty($barang['foto_barang'])): ?>
                    <img
                        src="../assets/barang/<?= rawurlencode(basename($barang['foto_barang'])) ?>"
                        alt="Foto barang saat ini"
                        style="width: 150px; max-width: 100%; height: auto;">
                <?php else: ?>
                    <p>Belum ada foto barang.</p>
                <?php endif; ?>

                <label>Ganti Foto Barang (Opsional)</label>
                <input
                    type="file"
                    name="foto_barang"
                    accept="image/jpeg,image/png,image/webp">

                <small>
                    Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                    Kosongkan jika tidak ingin mengganti foto.
                </small>

                <button type="submit">
                    Simpan Perubahan
                </button>

            </form>
        </div>
    </main>

</body>
</html>