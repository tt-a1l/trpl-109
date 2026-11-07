<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "penggunaanbarangkembali";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql = "SELECT * FROM tbl_users";
$result = mysqli_query($koneksi, $sql);
$rowCount = mysqli_num_rows($result);
if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    $user = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namalengkap = mysqli_real_escape_string($koneksi, $_POST['namalengkap']);
    $nohp = mysqli_real_escape_string($koneksi, $_POST['nohp']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $updateSql = "UPDATE tbl_users SET namalengkap = '$namalengkap', nohp = '$nohp' WHERE email = '$email'";

    if (mysqli_query($koneksi, $updateSql)) {
        echo "<script>alert('Perubahan berhasil disimpan!'); window.location='beranda.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan perubahan: " . mysqli_error($koneksi) . "');</script>";
    }
}
session_start();

$email = $_SESSION['user'] ?? '';

if ($email === '') {
    header('Location: ../auth/login.php');
    exit;
}

$email_aman = mysqli_real_escape_string($koneksi, $email);

$sql = "SELECT * FROM tbl_users WHERE email = '$email_aman' LIMIT 1";
$result = mysqli_query($koneksi, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("Data pengguna tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - ReUsePoli</title>
    <link rel="stylesheet" href="../style/style.css">

</head>

<body>

    <nav class="navbar">

        <h2>ReUsePoli</h2>

        <div class="menu">
            <a href="beranda.php">Beranda</a>
            <a href="barang_saya.php">Barang Saya</a>
            <a href="../auth/logout.php">Logout</a>
        </div>

    </nav>

    <main class="container">
        <div class="profil-box">
            <h1>Profil Saya</h1>

            <div class="profil-foto">
                <div class="foto-user">
                    <img src="<?= !empty($user['foto'])
                        ? '../assets/profil/' . htmlspecialchars(basename($user['foto']))
                        : '../assets/profil/default.png' ?>" alt="Foto Profil">
                </div>

                <input type="file" name="foto" accept="image/jpeg,image/png,image/webp">
            </div>


            <form action="proses_foto_profil.php" method="POST" enctype="multipart/form-data">
                <label>Nama Lengkap</label>
                <input type="text" name="namalengkap" value="<?= htmlspecialchars($user['namalengkap']) ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>

                <label>No. HP</label>
                <input type="text" name="nohp" value="<?= htmlspecialchars($user['nohp']) ?>" required>

                <button type="submit">Simpan Perubahan</button>
            </form>

            <a href="../auth/logout.php" class="logout">
                Logout
            </a>
        </div>
    </main>
</body>

</html>