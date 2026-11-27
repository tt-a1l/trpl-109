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
    <title>Daftar - ReUsePoli</title>
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
                <h1>Buat Akun Baru</h1>
                <p>Bergabung dan mulai berbagi barang</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] === 'kosong'): ?>
                    <div class="alert alert-error">⚠️ Semua kolom wajib diisi.</div>
                <?php elseif ($_GET['error'] === 'password'): ?>
                    <div class="alert alert-error">⚠️ Konfirmasi password tidak cocok.</div>
                <?php elseif ($_GET['error'] === 'email'): ?>
                    <div class="alert alert-error">⚠️ Email ini sudah terdaftar. Silakan gunakan email lain.</div>
                <?php endif; ?>
            <?php endif; ?>

            <form action="" method="POST" id="form-registrasi">

                <p class="form-section-label">Informasi Pribadi</p>

                <div class="form-group">
                    <label for="namalengkap">Nama Lengkap</label>
                    <input
                        type="text"
                        id="namalengkap"
                        name="namalengkap"
                        placeholder="Masukkan nama lengkap"
                        required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Masukkan email aktif"
                        required>
                </div>

                <div class="form-group">
                    <label for="nohp">Nomor Telepon Aktif</label>
                    <input
                        type="text"
                        id="nohp"
                        name="nohp"
                        placeholder="Contoh: 08123xxxxxx"
                        required>
                </div>

                <p class="form-section-label">Keamanan Akun</p>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Buat password"
                        required>
                </div>

                <div class="form-group">
                    <label for="ulangipassword">Konfirmasi Password</label>
                    <input
                        type="password"
                        id="ulangipassword"
                        name="ulangipassword"
                        placeholder="Ketik ulang password"
                        required>
                    <p id="pesanPassword" style="color: var(--clr-red); font-size: 13px; margin-top: 5px; min-height: 18px;"></p>
                </div>

                <button type="submit" class="auth-submit">Daftar Sekarang</button>

            </form>

            <p class="auth-footer">
                Sudah punya akun?
                <a href="login.php">Login di sini</a>
            </p>

        </div>
    </div>

    <script src="../script/script.js"></script>
</body>

</html>
