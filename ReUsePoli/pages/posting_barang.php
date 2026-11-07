<?php
require '../config/koneksi.php';
require_once __DIR__ . '/../auth/cek_login.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Posting Barang - ReUsePoli</title>

    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <nav class="navbar">
        <h2>ReUsePoli</h2>
         <div class="menu">
            <a href="beranda.php">Beranda</a>
            <a href="barang_saya.php">Barang Saya</a>
            <a href="profil.php">Profil</a>
        </div>

    </nav>
    <main class="container">
        <a href="beranda.php" class="kembali">
            ← Kembali
        </a>
        <div class="posting-box">
            <h1>Posting Barang</h1>
            <p class="deskripsi-beranda">
                Masukkan informasi barang yang ingin kamu berikan.
            </p>

        <form action="proses_posting.php" method="POST" enctype="multipart/form-data">

            <label>Nama Barang</label>
            <input
                type="text"
                name="namabarang"
                placeholder="Contoh: Buku Pemrograman Python"
                required>

            <label>Kategori</label>
            <select name="kategori" required>
                <option value="">Pilih kategori</option>
                <option value="Buku">Buku</option>
                <option value="Alat Tulis">Alat Tulis</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Furniture">Furniture</option>
                <option value="Lainnya">Lainnya</option>
            </select>

            <label>Kondisi Barang</label>
            <select name="kondisibarang" required>
                <option value="">Pilih kondisi barang</option>
                <option value="Sangat Baik">Sangat Baik</option>
                <option value="Baik">Baik</option>
                <option value="Layak Pakai">Layak Pakai</option>
            </select>

            <label>Deskripsi Barang</label>
            <textarea
                name="deskripsi"
                rows="5"
                placeholder="Jelaskan kondisi atau informasi barang"
                required>
            </textarea>

            <label>Foto Barang</label>
            <input type="file" name="foto" accept="image/*" required>

        <button type="submit">
            Posting Barang
        </button>

        </form>
    </div>
    </main>
</body>
</html>