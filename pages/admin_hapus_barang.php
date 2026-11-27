<?php
/** Hapus barang oleh admin (mengikuti pola hapus_barang.php milik pengguna). */
require_once __DIR__ . '/../admin/cek_login.php';
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_barang.php');
    exit;
}

$id_barang = (int) ($_POST['id_barang'] ?? 0);
if ($id_barang <= 0) {
    header('Location: admin_barang.php?error=invalid');
    exit;
}

$stmt = mysqli_prepare($koneksi, "DELETE FROM tbl_barang WHERE id_barang = ?");
mysqli_stmt_bind_param($stmt, 'i', $id_barang);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    header('Location: admin_barang.php?hapus=sukses');
} else {
    header('Location: admin_barang.php?error=gagal');
}
exit;
