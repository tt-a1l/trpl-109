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
            <a href="profil.php">Profil</a>
            <a href="../auth/logout.php" class="nav-logout">Logout</a>
        </div>
    </nav>

    <main class="container">

        <a href="beranda.php" class="kembali">← Kembali</a>

        <div class="posting-box">
            <h1>Posting Barang</h1>
            <p class="deskripsi-beranda">Masukkan informasi barang yang ingin kamu bagikan.</p>

            <form action="proses_posting.php" method="POST" enctype="multipart/form-data" style="margin-top: 24px;">

                <div class="form-group">
                    <label for="namabarang">Nama Barang</label>
                    <input
                        type="text"
                        id="namabarang"
                        name="namabarang"
                        placeholder="Contoh: Buku Pemrograman Python"
                        required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select id="kategori" name="kategori" required>
                            <option value="">Pilih kategori</option>
                            <option value="Buku">Buku</option>
                            <option value="Alat Tulis">Alat Tulis</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kondisibarang">Kondisi Barang</label>
                        <select id="kondisibarang" name="kondisibarang" required>
                            <option value="">Pilih kondisi</option>
                            <option value="Sangat Baik">Sangat Baik</option>
                            <option value="Baik">Baik</option>
                            <option value="Layak Pakai">Layak Pakai</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi Barang</label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="5"
                        placeholder="Jelaskan kondisi barang, ukuran, atau info penting lainnya..."
                        required></textarea>
                </div>

                <div class="form-group">
                    <label for="foto">Foto Barang</label>
                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        accept="image/*"
                        required>
                    <p class="form-hint">Format JPG, PNG, atau WEBP. Ukuran maksimal 15 MB.</p>

                    <div class="foto-preview-wrap">
                        <img id="foto-preview" src="" alt="Preview foto" />
                    </div>
                </div>

                <button type="submit">📤 Posting Barang</button>

            </form>
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

        // Preview foto sebelum upload
        document.getElementById('foto').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('foto-preview');
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    preview.src = ev.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                preview.src = '';
            }
        });
    </script>

</body>

</html>
