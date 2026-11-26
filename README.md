# ReUsePoli

Platform pendataan dan pertukaran informasi barang bekas di lingkungan Politeknik Negeri Batam.

## Teknologi
- PHP Native
- MySQL
- HTML, CSS, JavaScript
- XAMPP

## Instalasi

### 1. Clone Repository
```bash
cd C:\xampp\htdocs
git clone https://github.com/tt-a1l/trpl-109.git
```

### 2. Jalankan XAMPP
Aktifkan Apache dan MySQL.

### 3. Buat Database
Buka http://localhost/phpmyadmin

Buat database sesuai nama yang digunakan dalam konfigurasi project.

### 4. Import Database
buka menu Import, pilih file database kemudian import:

`database/penggunaanbarangkembali.sql`

### 5. Konfigurasi Database
Sesuaikan konfigurasi koneksi database dengan MySQL lokal masing-masing.

### 6. Jalankan Project
Buka browser:

http://localhost/trpl-109/ReUsePoli/auth/login.php

## Update Project
Untuk mengambil perubahan terbaru:

```bash
git pull origin main
```

## Catatan
Setiap anggota menggunakan database lokal masing-masing.
Perubahan database tidak otomatis tersinkronisasi melalui GitHub.