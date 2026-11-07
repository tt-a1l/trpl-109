
<?php
require_once __DIR__ . '/../auth/cek_login.php';
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: barang_saya.php');
    exit;
}

$id_barang = isset($_POST['id_barang'])
    ? (int) $_POST['id_barang']
    : 0;

if ($id_barang <= 0) {
    header('Location: barang_saya.php?error=invalid');
    exit;
}

// pastikan barang milik pengguna yang sedang login
$email = $_SESSION['user'];
$email_aman = mysqli_real_escape_string($koneksi, $email);

$query_user = mysqli_query(
    $koneksi,
    "SELECT namalengkap FROM tbl_users WHERE email = '$email_aman' LIMIT 1"
);

$user = mysqli_fetch_assoc($query_user);

if (!$user) {
    header('Location: barang_saya.php?error=user');
    exit;
}

$pemilik = mysqli_real_escape_string($koneksi, $user['namalengkap']);

$sql = "DELETE FROM tbl_barang
        WHERE id_barang = $id_barang
        AND pemilik = '$pemilik'";

$result = mysqli_query($koneksi, $sql);

if ($result && mysqli_affected_rows($koneksi) > 0) {
    header('Location: barang_saya.php?hapus=sukses');
    exit;
}

header('Location: barang_saya.php?error=gagal');
exit;
?>
