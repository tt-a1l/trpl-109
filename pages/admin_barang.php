<?php
require '../config/koneksi.php';
require_once __DIR__ . '/../admin/cek_login.php';
require_once __DIR__ . '/../partials/layout.php';

$cari     = trim($_GET['cari'] ?? '');
$kategori = $_GET['kategori'] ?? '';
$status   = $_GET['status_filter'] ?? '';

if (!in_array($kategori, KATEGORI_BARANG, true)) $kategori = '';
if (!in_array($status, ['Tersedia', 'Tidak Tersedia'], true)) $status = '';

$sql = "SELECT * FROM tbl_barang WHERE 1=1";
$tipe = '';
$param = [];
if ($cari !== '') {
    $sql .= " AND (namabarang LIKE ? OR pemilik LIKE ? OR deskripsi LIKE ?)";
    $pola = "%{$cari}%";
    $tipe .= 'sss';
    array_push($param, $pola, $pola, $pola);
}
if ($kategori !== '') { $sql .= " AND kategori = ?"; $tipe .= 's'; $param[] = $kategori; }
if ($status !== '')   { $sql .= " AND statusbarang = ?"; $tipe .= 's'; $param[] = $status; }
$sql .= " ORDER BY id_barang DESC";

$stmt = mysqli_prepare($koneksi, $sql);
if ($param) mysqli_stmt_bind_param($stmt, $tipe, ...$param);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$jumlah = mysqli_num_rows($hasil);

layout_open('Kelola Barang', 'admin', 'barang');
?>

<div class="page-head">
    <div>
        <h1>Kelola Barang</h1>
        <p>Pantau seluruh barang yang dibagikan pengguna dan jaga katalog tetap rapi.</p>
    </div>
</div>

<?php if (($_GET['status'] ?? '') === 'ok'): ?>
    <div class="alert alert-success"><?= icon('check', 18) ?>Perubahan berhasil disimpan.</div>
<?php elseif (($_GET['hapus'] ?? '') === 'sukses'): ?>
    <div class="alert alert-success"><?= icon('check', 18) ?>Barang berhasil dihapus.</div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-error"><?= icon('info', 18) ?>Aksi gagal diproses. Silakan coba lagi.</div>
<?php endif; ?>

<form class="toolbar" method="GET" action="admin_barang.php" role="search">
    <div class="field">
        <?= icon('search', 18) ?>
        <input type="search" name="cari" value="<?= e($cari) ?>" placeholder="Cari nama barang, pemilik, atau deskripsi…" aria-label="Cari barang">
    </div>
    <select name="kategori" aria-label="Kategori" onchange="this.form.submit()">
        <option value="">Semua kategori</option>
        <?php foreach (KATEGORI_BARANG as $k): ?><option value="<?= e($k) ?>" <?= $kategori === $k ? 'selected' : '' ?>><?= e($k) ?></option><?php endforeach; ?>
    </select>
    <select name="status_filter" aria-label="Status" onchange="this.form.submit()">
        <option value="">Semua status</option>
        <option value="Tersedia" <?= $status === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
        <option value="Tidak Tersedia" <?= $status === 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
    </select>
    <button type="submit" class="btn btn-green btn-sm">Cari</button>
    <?php if ($cari !== '' || $kategori !== '' || $status !== ''): ?>
        <a href="admin_barang.php" class="reset-link"><?= icon('x', 14) ?> Reset</a>
    <?php endif; ?>
    <span class="filter-count"><strong><?= $jumlah ?></strong> barang</span>
</form>

<div class="table-wrap">
    <table class="tbl">
        <thead>
            <tr><th>Barang</th><th>Kategori</th><th>Kondisi</th><th>Pemilik</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php if ($jumlah > 0): ?>
            <?php while ($b = mysqli_fetch_assoc($hasil)): $f = url_foto($b['foto']); $ada = $b['statusbarang'] === 'Tersedia'; ?>
                <tr>
                    <td>
                        <div class="cell-item">
                            <span class="row-thumb"><?php if ($f): ?><img src="<?= e($f) ?>" alt=""><?php else: ?><?= icon('image', 20) ?><?php endif; ?></span>
                            <div><b><?= e($b['namabarang']) ?></b><small><?= e($b['deskripsi']) ?></small></div>
                        </div>
                    </td>
                    <td><?= e($b['kategori']) ?></td>
                    <td><span class="badge <?= kelas_kondisi($b['kondisibarang']) ?>"><?= e($b['kondisibarang']) ?></span></td>
                    <td><?= e($b['pemilik']) ?></td>
                    <td><span class="badge <?= kelas_status($b['statusbarang']) ?>"><i class="dot"></i><?= e($b['statusbarang']) ?></span></td>
                    <td>
                        <div class="cell-actions">
                            <form action="ubah_status.php" method="POST" class="inline">
                                <input type="hidden" name="id_barang" value="<?= (int) $b['id_barang'] ?>">
                                <input type="hidden" name="status" value="<?= $ada ? 'Tidak Tersedia' : 'Tersedia' ?>">
                                <input type="hidden" name="kembali" value="admin_barang.php">
                                <button class="btn btn-outline btn-sm" type="submit"><?= icon('refresh', 14) ?> <?= $ada ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                            </form>
                            <form action="admin_hapus_barang.php" method="POST" class="inline"
                                  data-confirm="Hapus barang &quot;<?= e($b['namabarang']) ?>&quot; milik <?= e($b['pemilik']) ?>? Tindakan ini tidak bisa dibatalkan.">
                                <input type="hidden" name="id_barang" value="<?= (int) $b['id_barang'] ?>">
                                <button class="btn btn-danger btn-sm" type="submit"><?= icon('trash', 14) ?> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6"><div class="empty" style="border:0;padding:36px 12px"><div class="empty-ico"><?= icon('search', 30) ?></div><h3>Tidak ada barang</h3><p style="margin-bottom:0">Belum ada barang yang cocok dengan filter ini.</p></div></td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php layout_close(); ?>
