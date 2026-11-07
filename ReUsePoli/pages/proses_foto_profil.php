
<?php
require_once __DIR__ . '/../auth/cek_login.php';
require_once __DIR__ . '/../config/koneksi.php';

$email = $_SESSION['user'];

// ambil data pengguna yang sedang login
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT foto FROM tbl_users WHERE email = ? LIMIT 1"
);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("Data pengguna tidak ditemukan.");
}

// ambil data form
$namalengkap = trim($_POST['namalengkap'] ?? '');
$nohp = trim($_POST['nohp'] ?? '');

if ($namalengkap === '' || $nohp === '') {
    header('Location: profil.php?error=data');
    exit;
}

// validasi upload jika pengguna memilih foto
$nama_file = $user['foto'] ?? '';
$foto_baru = false;

if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['foto'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        header('Location: profil.php?error=upload');
        exit;
    }

    // Maksimal 2 MB
    if ($file['size'] > 2 * 1024 * 1024) {
        header('Location: profil.php?error=ukuran');
        exit;
    }

    // Validasi tipe berdasarkan isi file
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipe = $finfo->file($file['tmp_name']);

    $tipe_diizinkan = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (
        !isset($tipe_diizinkan[$tipe]) ||
        getimagesize($file['tmp_name']) === false
    ) {
        header('Location: profil.php?error=format');
        exit;
    }

    $folder = __DIR__ . '/../assets/profil/';

    if (!is_dir($folder) && !mkdir($folder, 0755, true)) {
        header('Location: profil.php?error=folder');
        exit;
    }

    $nama_file = bin2hex(random_bytes(16)) . '.' . $tipe_diizinkan[$tipe];

    if (!move_uploaded_file($file['tmp_name'], $folder . $nama_file)) {
        header('Location: profil.php?error=upload');
        exit;
    }

    $foto_baru = true;
}

// simpan nama, nomor HP, dan nama file foto
$stmt_update = mysqli_prepare(
    $koneksi,
    "UPDATE tbl_users
     SET namalengkap = ?, nohp = ?, foto = ?
     WHERE email = ?"
);

mysqli_stmt_bind_param(
    $stmt_update,
    "ssss",
    $namalengkap,
    $nohp,
    $nama_file,
    $email
);

if (!mysqli_stmt_execute($stmt_update)) {
    if ($foto_baru) {
        @unlink(__DIR__ . '/../assets/profil/' . $nama_file);
    }

    header('Location: profil.php?error=database');
    exit;
}

// hapus foto lama setelah database berhasil diperbarui
if ($foto_baru) {
    $foto_lama = basename($user['foto'] ?? '');
    $folder = __DIR__ . '/../assets/profil/';

    if ($foto_lama !== '' && is_file($folder . $foto_lama)) {
        unlink($folder . $foto_lama);
    }
}

header('Location: profil.php?foto=sukses');
exit;
?>
