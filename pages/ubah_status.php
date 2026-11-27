<?php
/**
 * Ubah status ketersediaan barang (Tersedia <-> Tidak Tersedia).
 * - Pemilik hanya boleh mengubah barang miliknya.
 * - Admin boleh mengubah semua barang.
 * Tidak mengubah struktur database (hanya kolom statusbarang yang sudah ada).
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/koneksi.php';

$halaman_valid = ['barang_saya.php', 'admin_barang.php', 'admin.php'];
$kembali = $_POST['kembali'] ?? 'barang_saya.php';
if (!in_array($kembali, $halaman_valid, true)) {
    $kembali = 'barang_saya.php';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $kembali");
    exit;
}

$id_barang = (int) ($_POST['id_barang'] ?? 0);
$status    = $_POST['status'] ?? '';

if ($id_barang <= 0 || !in_array($status, ['Tersedia', 'Tidak Tersedia'], true)) {
    header("Location: $kembali?error=invalid");
    exit;
}

$sebagai_admin = isset($_SESSION['admin']);

if ($sebagai_admin && str_starts_with($kembali, 'admin')) {
    $stmt = mysqli_prepare($koneksi, "UPDATE tbl_barang SET statusbarang = ? WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmt, 'si', $status, $id_barang);
} elseif (isset($_SESSION['user'])) {
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE tbl_barang b
         JOIN tbl_users u ON u.namalengkap = b.pemilik
         SET b.statusbarang = ?
         WHERE b.id_barang = ? AND u.email = ?"
    );
    mysqli_stmt_bind_param($stmt, 'sis', $status, $id_barang, $_SESSION['user']);
} else {
    header('Location: ../auth/login.php');
    exit;
}

mysqli_stmt_execute($stmt);
$ok = mysqli_stmt_errno($stmt) === 0;

header("Location: $kembali?" . ($ok ? 'status=ok' : 'error=gagal'));
exit;
