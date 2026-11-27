<?php
session_start();
require '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT email FROM tbl_admin
         WHERE email = ? AND password = ?"
    );

    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin'] = $email;

        // Login pengguna biasa
        header("Location: ../pages/admin.php");
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
    <title>Login Admin - ReUsePoli</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <div class="auth-wrapper auth-wrapper-admin">
        <div class="auth-card">

            <div class="auth-logo">
                <div class="auth-logo-mark">Re</div>
                <div>
                    <span class="admin-badge">🛡 Panel Administrator</span>
                </div>
                <h1>ReUsePoli Admin</h1>
                <p>Masuk menggunakan akun administrator</p>
            </div>

            <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
                <div class="alert alert-error">
                    ⚠️ Email atau password admin salah.
                </div>
            <?php endif; ?>

            <form action="" method="POST">

                <div class="form-group">
                    <label for="email">Email Admin</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Masukkan email admin"
                        required
                        autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password admin"
                        required
                        autocomplete="current-password">
                </div>

                <button type="submit" class="auth-submit" style="background: #1E7040;">Masuk sebagai Admin</button>

            </form>

        </div>
    </div>
</body>

</html>
