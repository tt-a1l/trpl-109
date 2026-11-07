<?php
session_start();
require '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT email FROM tbl_users
         WHERE email = ? AND password = ?"
    );

    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['user'] = $email;

        // Login pengguna biasa
        header("Location: ../pages/beranda.php");
        exit();
    }

    header("Location: login.php?error=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - ReUsePoli</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>

    <div class="login-box">

        <h1>ReUsePoli</h1>

        <p class="deskripsi">
            Silakan masuk untuk menggunakan aplikasi
        </p>

        <form action="" method="POST">

            <label>Email</label>
            <input type="email" name="email" placeholder="Masukkan email">

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password">

            <p>
                <button class="login-button" type="submit">Login</button>
            </p>

        </form>

        <p class="daftar">
            Belum punya akun?
            <a href="registrasi.php">Daftar sekarang</a>
        </p>

    </div>

</body>

</html>