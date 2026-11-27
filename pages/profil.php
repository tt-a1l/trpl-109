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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>

    <nav class="navbar">
        <a href="beranda.php" class="navbar-brand">
            <span class="brand-icon">Re</span>
            ReUsePoli
        </a>
        <button class="navbar-toggle" id="navbar-toggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <div class="navbar-menu" id="navbar-menu">
            <a href="beranda.php">Beranda</a>
            <a href="barang_saya.php">Barang Saya</a>
            <a href="profil.php" class="active">Profil</a>
            <a href="../auth/logout.php" class="nav-logout">Logout</a>
        </div>
    </nav>

    <main class="container">

        <div class="profil-box">
            <h1>Profil Saya</h1>

            <!-- FOTO PROFIL -->
            <div class="profil-foto">
                <div class="foto-user">
                    <img
                        src="<?= !empty($user['foto'])
                            ? '../assets/profil/' . htmlspecialchars(basename($user['foto']))
                            : '../assets/profil/default.png' ?>"
                        alt="Foto Profil"
                        onerror="this.style.display='none'; this.parentElement.innerHTML='<span style=\'font-size:40px;\'>👤</span>';">
                </div>

                <label for="foto-input" class="foto-label-btn">
                    📷 Ganti Foto
                </label>
            </div>

            <!-- FORM PROFIL (termasuk foto) -->
            <form action="proses_foto_profil.php" method="POST" enctype="multipart/form-data">

                <!-- Input foto disembunyikan, trigger lewat label -->
                <input
                    type="file"
                    id="foto-input"
                    name="foto"
                    accept="image/jpeg,image/png,image/webp"
                    style="display: none;">

                <div class="form-group">
                    <label for="namalengkap">Nama Lengkap</label>
                    <input
                        type="text"
                        id="namalengkap"
                        name="namalengkap"
                        value="<?= htmlspecialchars($user['namalengkap']) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($user['email']) ?>"
                        readonly>
                    <p class="form-hint">Email tidak dapat diubah.</p>
                </div>

                <div class="form-group">
                    <label for="nohp">Nomor Telepon</label>
                    <input
                        type="text"
                        id="nohp"
                        name="nohp"
                        value="<?= htmlspecialchars($user['nohp']) ?>"
                        required>
                </div>

                <button type="submit">💾 Simpan Perubahan</button>

            </form>

            <a href="../auth/logout.php" class="logout">
                🚪 Logout
            </a>

        </div>

    </main>

    <script>
        // Navbar toggle
        const toggle = document.getElementById('navbar-toggle');
        const menu = document.getElementById('navbar-menu');
        if (toggle && menu) {
            toggle.addEventListener('click', function () {
                menu.classList.toggle('open');
            });
        }

        // Preview foto profil sebelum upload
        document.getElementById('foto-input').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    const fotoUser = document.querySelector('.foto-user img');
                    if (fotoUser) {
                        fotoUser.src = ev.target.result;
                        fotoUser.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

</body>

</html>
