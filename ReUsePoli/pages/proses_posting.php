<?php
require '../config/koneksi.php';
require_once __DIR__ . '/../auth/cek_login.php';

// pastikan data dikirim melalui form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: posting_barang.php");
    exit;
}

// ambil data pengguna yang sedang login
$email = $_SESSION['user'];

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

// ambil data barang dari form
$namabarang = trim($_POST['namabarang'] ?? '');
$kategori = $_POST['kategori'] ?? '';
$kondisibarang = $_POST['kondisibarang'] ?? '';
$statusbarang = 'Tersedia';
$deskripsi = trim($_POST['deskripsi'] ?? '');

// validasi data form
$kategori_valid = [
    'Buku',
    'Alat Tulis',
    'Elektronik',
    'Furniture',
    'Lainnya'
];

$kondisi_valid = [
    'Sangat Baik',
    'Baik',
    'Layak Pakai'
];

if (
    $namabarang === '' ||
    !in_array($kategori, $kategori_valid, true) ||
    !in_array($kondisibarang, $kondisi_valid, true) ||
    $deskripsi === ''
) {
    exit("Data barang tidak valid. Periksa kembali form.");
}

// lokasi penyimpanan foto
$folder = __DIR__ . '/../assets/barang/';

if (!is_dir($folder) && !mkdir($folder, 0775, true)) {
    exit("Folder penyimpanan foto gagal dibuat.");
}

// periksa upload foto
if (
    !isset($_FILES['foto']) ||
    $_FILES['foto']['error'] !== UPLOAD_ERR_OK
) {
    exit("Foto barang wajib diunggah.");
}

$tmp = $_FILES['foto']['tmp_name'];
$ukuran = $_FILES['foto']['size'];

// Maksimal ukuran foto 2 MB
if ($ukuran > 5 * 1024 * 1024) {
    exit("Ukuran foto maksimal 5 MB.");
}

// periksa apakah file benar-benar gambar
$info = getimagesize($tmp);

if ($info === false) {
    exit("File yang diunggah bukan gambar yang valid.");
}

// tentukan format berdasarkan isi gambar
$ekstensi_valid = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp'
];

$mime = $info['mime'];

if (!isset($ekstensi_valid[$mime])) {
    exit("Format foto harus JPG, PNG, atau WEBP.");
}

// buat nama file unik
$nama_file = bin2hex(random_bytes(16))
    . '.'
    . $ekstensi_valid[$mime];

$tujuan = $folder . $nama_file;

// pindahkan foto ke folder barang
if (!move_uploaded_file($tmp, $tujuan)) {
    exit("Gagal menyimpan foto barang.");
}

// simpan data barang dan nama file foto ke database
$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO tbl_barang
    (namabarang, kategori, kondisibarang, statusbarang,
     pemilik, deskripsi, foto)
    VALUES (?, ?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "sssssss",
    $namabarang,
    $kategori,
    $kondisibarang,
    $statusbarang,
    $pemilik,
    $deskripsi,
    $nama_file
);

if (mysqli_stmt_execute($stmt)) {
    header("Location: barang_saya.php");
    exit;
}

// jika database gagal menyimpan, hapus file yang sudah diunggah
if (file_exists($tujuan)) {
    unlink($tujuan);
}

exit("Gagal menyimpan barang: " . mysqli_error($koneksi));
?>