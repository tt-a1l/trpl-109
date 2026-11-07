<?php
require '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namalengkap = trim($_POST['namalengkap'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $ulangipassword = $_POST['ulangipassword'] ?? '';
    $nohp = trim($_POST['nohp'] ?? '');
    

    // cek input
    if (
        $namalengkap === '' ||
        $email === '' ||
        $password === '' ||
        $ulangipassword === '' ||
        $nohp === ''
    ) {
        header("Location: registrasi.php?error=kosong");
        exit();
    }

    // cek kesamaan password
    if ($password !== $ulangipassword) {
        header("Location: registrasi.php?error=password");
        exit();
    }

    // cek email yang sudah ada
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT email FROM tbl_users WHERE email = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        header("Location: registrasi.php?error=email");
        exit();
    }

    // Simpan data pengguna
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO tbl_users
        (namalengkap, email, password, ulangipassword, nohp)
        VALUES (?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssss",
        $namalengkap,
        $email,
        $password,
        $ulangipassword,
        $nohp
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.php?success=1");
        exit();
    } else {
        echo "Pendaftaran gagal: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrasi - ReUsePoli</title>

    <link rel="stylesheet" href="../style/style.css">
</head>

<body>

    <div class="register-box">

        <h1>ReUsePoli</h1>

        <p class="deskripsi">
            Buat akun baru
        </p>

        <form action="" method="POST">
            <label>Nama Lengkap</label>
            <input type="text" name="namalengkap" placeholder="Masukkan nama lengkap" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="Masukkan email" required>
            
            <label>Nomor Telepon Aktif</label>
            <input type="text" name="nohp" placeholder="Contoh: 08123xxxxxx" required>

            <label>Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>

            <label>Konfirmasi Password</label>
            <input type="password" id="ulangipassword" name="ulangipassword" placeholder="Masukkan ulang password" required>
            <p id="pesanPassword" style="color: red;"></p>

            <p>
                <button class="login-button" type="submit">Daftar</button>
            </p>
        </form>
        <script src="../script/script.js"></script>

        <p class="daftar">
            Sudah punya akun?
            <a href="login.php">Login</a>
        </p>

    </div>

</body>

</html>