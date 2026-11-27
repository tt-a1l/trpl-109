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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">

            <div class="auth-logo">
                <div class="auth-logo-mark">Re</div>
                <h1>ReUsePoli</h1>
                <p>Berbagi barang, kurangi limbah bersama</p>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
                <div class="alert alert-error">
                    ⚠️ Email atau password salah. Silakan coba lagi.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                <div class="alert alert-success">
                    ✅ Akun berhasil dibuat! Silakan login.
                </div>
            <?php endif; ?>

            <form action="" method="POST">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Masukkan email kamu"
                        required
                        autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password">
                </div>

                <button type="submit" class="auth-submit">Masuk</button>

            </form>

            <p class="auth-footer">
                Belum punya akun?
                <a href="registrasi.php">Daftar sekarang</a>
            </p>

        </div>
    </div>
</body>

</html>
