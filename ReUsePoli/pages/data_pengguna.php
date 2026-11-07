
<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../auth/cek_login_admin.php';

// ambil semua data pengguna
$query = mysqli_query(
    $koneksi,
    "SELECT id_user, namalengkap, email, nohp
     FROM tbl_users
     ORDER BY id_user DESC"
);

if (!$query) {
    die("Gagal mengambil data pengguna.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengguna - ReUsePoli Admin</title>
    <link rel="stylesheet" href="../style/style.css">

    <style>
        .tabel-pengguna {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        .tabel-pengguna th,
        .tabel-pengguna td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .tabel-pengguna th {
            background: #333;
            color: white;
        }

        .tabel-pengguna tr:hover {
            background: #f5f5f5;
        }

        .area-tabel {
            overflow-x: auto;
            background: white;
            padding: 15px;
            border-radius: 10px;
        }

        .jumlah-pengguna {
            margin-top: 15px;
            color: #555;
        }

        @media (max-width: 700px) {
            .tabel-pengguna {
                min-width: 650px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <h2>ReUsePoli - Admin</h2>
        <div class="menu">
            <a href="admin.php">Admin</a>
            <a href="pengguna.php">Pengguna</a>
            <a href="../auth/logout.php">Logout</a>
        </div>
    </nav>

    <main class="container">
        <h1>Data Pengguna</h1>
        <p class="deskripsi-beranda">
            Daftar pengguna yang terdaftar di aplikasi ReUsePoli.
        </p>

        <p class="jumlah-pengguna">
            Total pengguna: <strong><?= mysqli_num_rows($query) ?></strong>
        </p>

        <div class="area-tabel">
            <table class="tabel-pengguna">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>No. HP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($pengguna = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($pengguna['namalengkap'] ?? '') ?></td>
                                <td><?= htmlspecialchars($pengguna['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($pengguna['nohp'] ?? '') ?></td>
                                <td><?= htmlspecialchars($pengguna['alamat'] ?? '') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Belum ada data pengguna.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
