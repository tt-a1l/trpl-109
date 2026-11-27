<?php
/**
 * ReUsePoli — helper tampilan (UI saja).
 * Tidak mengubah logika backend / database.
 */

const KATEGORI_BARANG = ['Buku', 'Alat Tulis', 'Elektronik', 'Furniture', 'Lainnya'];
const KONDISI_BARANG  = ['Sangat Baik', 'Baik', 'Layak Pakai'];

function e($teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

/** Ikon SVG inline (gaya garis) */
function icon(string $nama, int $ukuran = 20, string $kelas = ''): string
{
    static $path = [
        'home'     => '<path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'plus'     => '<path d="M12 5v14M5 12h14"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'box'      => '<path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/>',
        'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'users'    => '<circle cx="9" cy="8" r="3.5"/><path d="M2 20a7 7 0 0 1 14 0"/><circle cx="17" cy="9" r="2.5"/><path d="M17 14a5 5 0 0 1 5 5"/>',
        'logout'   => '<path d="M9 21H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h4M16 17l5-5-5-5M21 12H9"/>',
        'pin'      => '<path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'check'    => '<path d="m5 12 5 5 9-10"/>',
        'shield'   => '<path d="M12 3 4 6v6c0 4.5 3.2 8 8 9 4.8-1 8-4.5 8-9V6z"/><path d="m9 12 2 2 4-4"/>',
        'book'     => '<path d="M4 19V5a2 2 0 0 1 2-2h13v15H6a2 2 0 0 0-2 2 2 2 0 0 0 2 2h13"/><path d="M9 7h6"/>',
        'pen'      => '<path d="m4 20 1-4L16.5 4.5a2.1 2.1 0 0 1 3 3L8 19z"/><path d="m14 7 3 3"/>',
        'cpu'      => '<rect x="6" y="6" width="12" height="12" rx="2"/><path d="M9 2v4M15 2v4M9 18v4M15 18v4M2 9h4M2 15h4M18 9h4M18 15h4"/>',
        'sofa'     => '<path d="M5 11V8a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3v3"/><path d="M3 13a2 2 0 0 1 4 0v2h10v-2a2 2 0 0 1 4 0v5H3z"/><path d="M6 18v2M18 18v2"/>',
        'grid'     => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
        'layers'   => '<path d="m12 3 9 5-9 5-9-5z"/><path d="m3 13 9 5 9-5"/><path d="m3 17.5 9 5 9-5"/>',
        'chat'     => '<path d="M21 12a8 8 0 0 1-11.7 7L3 21l2-5.6A8 8 0 1 1 21 12z"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'back'     => '<path d="M19 12H5M11 6l-6 6 6 6"/>',
        'x'        => '<path d="M6 6l12 12M18 6 6 18"/>',
        'trash'    => '<path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3"/>',
        'menu'     => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'gift'     => '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M5 12v8h14v-8"/><path d="M12 8C9 8 7 7 7.5 5.2 8.2 3 11.5 4 12 8zm0 0c3 0 5-1 4.5-2.8C15.8 3 12.5 4 12 8z"/>',
        'leaf'     => '<path d="M5 19c0-9 5-14 15-14 0 10-5 15-14 15"/><path d="M5 19c3-5 6-8 10-10"/>',
        'image'    => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="m21 16-5-5-9 9"/>',
        'info'     => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8v.01"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'phone'    => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
        'refresh'  => '<path d="M20 11a8 8 0 0 0-14.5-4M4 4v4h4M4 13a8 8 0 0 0 14.5 4M20 20v-4h-4"/>',
        'dash'     => '<path d="M4 13h6V4H4zM14 20h6V11h-6zM14 8h6V4h-6zM4 20h6v-3H4z"/>',
        'upload'   => '<path d="M12 16V4M7 9l5-5 5 5M4 20h16"/>',
        'lock'     => '<rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>',
    ];

    $isi = $path[$nama] ?? $path['box'];
    $cls = 'ico' . ($kelas !== '' ? ' ' . e($kelas) : '');

    return '<svg class="' . $cls . '" width="' . $ukuran . '" height="' . $ukuran
        . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"'
        . ' stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $isi . '</svg>';
}

function ikon_kategori(string $kategori): string
{
    return match ($kategori) {
        'Buku'       => 'book',
        'Alat Tulis' => 'pen',
        'Elektronik' => 'cpu',
        'Furniture'  => 'sofa',
        'Lainnya'    => 'grid',
        default      => 'layers',
    };
}

function url_foto(?string $foto): string
{
    return empty($foto) ? '' : '../assets/barang/' . rawurlencode(basename($foto));
}

function kelas_status(string $status): string
{
    return match ($status) {
        'Tersedia'       => 'st-tersedia',
        'Tidak Tersedia' => 'st-tidak',
        default          => 'st-proses',
    };
}

function kelas_kondisi(string $kondisi): string
{
    return match ($kondisi) {
        'Sangat Baik' => 'kd-prima',
        'Baik'        => 'kd-baik',
        default       => 'kd-layak',
    };
}

/** Penjelasan singkat kondisi barang (membantu calon pengguna menilai) */
function arti_kondisi(string $kondisi): string
{
    return match ($kondisi) {
        'Sangat Baik' => 'Hampir seperti baru, nyaris tanpa tanda pemakaian.',
        'Baik'        => 'Berfungsi penuh, ada sedikit tanda pemakaian.',
        default       => 'Masih berfungsi dan layak dipakai, tanda pemakaian terlihat jelas.',
    };
}

function inisial(string $nama): string
{
    $nama = trim($nama);
    return $nama === '' ? '?' : mb_strtoupper(mb_substr($nama, 0, 1));
}

/** Ambil nama lengkap pengguna yang sedang login (read-only). */
function nama_pengguna_login(mysqli $koneksi): string
{
    $email = $_SESSION['user'] ?? '';
    if ($email === '') {
        return '';
    }
    $stmt = mysqli_prepare($koneksi, "SELECT namalengkap FROM tbl_users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return $row['namalengkap'] ?? '';
}

/** Kartu barang (dipakai di beranda & "barang serupa") */
function kartu_barang(array $b, string $namaLogin = ''): string
{
    $foto   = url_foto($b['foto'] ?? '');
    $milikku = $namaLogin !== '' && $b['pemilik'] === $namaLogin;
    $tersedia = $b['statusbarang'] === 'Tersedia';
    $ringkas = trim((string) ($b['deskripsi'] ?? ''));
    if (mb_strlen($ringkas) > 80) {
        $ringkas = mb_substr($ringkas, 0, 80) . '…';
    }

    ob_start(); ?>
    <article class="item-card <?= $tersedia ? '' : 'is-unavailable' ?>">
        <a class="item-media" href="detail_barang.php?id=<?= (int) $b['id_barang'] ?>" aria-label="Lihat <?= e($b['namabarang']) ?>">
            <?php if ($foto): ?>
                <img src="<?= e($foto) ?>" alt="<?= e($b['namabarang']) ?>" loading="lazy">
            <?php else: ?>
                <span class="media-empty"><?= icon('image', 36) ?>Belum ada foto</span>
            <?php endif; ?>
            <span class="pill pill-kondisi <?= kelas_kondisi($b['kondisibarang']) ?>"><?= e($b['kondisibarang']) ?></span>
            <span class="pill pill-status <?= kelas_status($b['statusbarang']) ?>">
                <i class="dot"></i><?= e($b['statusbarang']) ?>
            </span>
        </a>
        <div class="item-body">
            <div class="item-owner">
                <span class="avatar avatar-sm"><?= e(inisial($b['pemilik'])) ?></span>
                <span class="owner-name"><?= $milikku ? 'Barangmu' : e($b['pemilik']) ?></span>
                <span class="chip chip-light"><?= icon(ikon_kategori($b['kategori']), 13) ?><?= e($b['kategori']) ?></span>
            </div>
            <h3><?= e($b['namabarang']) ?></h3>
            <p class="item-desc"><?= e($ringkas !== '' ? $ringkas : 'Tidak ada deskripsi.') ?></p>
            <a class="btn btn-amber btn-block" href="detail_barang.php?id=<?= (int) $b['id_barang'] ?>">
                <?= $tersedia ? 'Lihat &amp; Ajukan' : 'Lihat Detail' ?> <?= icon('arrow', 16) ?>
            </a>
        </div>
    </article>
    <?php
    return ob_get_clean();
}
