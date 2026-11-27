<?php
require '../config/koneksi.php';
require_once __DIR__ . '/../auth/cek_login.php';
require_once __DIR__ . '/../partials/layout.php';

layout_open('Riwayat Pengajuan', 'user', 'riwayat');
?>

<div class="page-head">
    <div>
        <h1>Riwayat Pengajuan</h1>
        <p>Daftar barang yang pernah kamu ajukan penggunaannya.</p>
    </div>
    <a href="beranda.php" class="btn btn-amber"><?= icon('search', 18) ?> Cari barang lain</a>
</div>

<div class="alert alert-info"><?= icon('info', 18) ?>
    <span>Riwayat ini tersimpan di browser perangkat ini. Perbarui statusnya setelah pemilik merespons lewat WhatsApp.</span>
</div>

<div class="stat-row" id="ringkasan"></div>
<div class="tabs" id="tabs" role="tablist" aria-label="Filter status"></div>
<div class="row-list" id="daftar" aria-live="polite"></div>

<?php
$skrip = '<script src="../script/pengajuan.js"></script>' . "\n"
    . '<script>window.REUSE_USER = ' . json_encode($_SESSION['user'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script>' . "\n"
    . <<<'JS'
<script>
(function () {
    var P = window.ReusePengajuan, U = window.REUSE_USER;
    var filter = 'Semua';
    var $ringkasan = document.getElementById('ringkasan');
    var $tabs = document.getElementById('tabs');
    var $daftar = document.getElementById('daftar');

    var kelasStatus = { Menunggu: 'st-proses', Disetujui: 'st-tersedia', Selesai: 'st-tersedia', Ditolak: 'st-tidak', Dibatalkan: 'st-tidak' };

    function el(tag, cls, html) { var n = document.createElement(tag); if (cls) n.className = cls; if (html != null) n.innerHTML = html; return n; }
    function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; }

    function hitung(list) {
        var c = { Semua: list.length };
        P.STATUS.forEach(function (s) { c[s] = 0; });
        list.forEach(function (x) { c[x.status] = (c[x.status] || 0) + 1; });
        return c;
    }

    function render() {
        var list = P.load(U);
        var c = hitung(list);

        $ringkasan.innerHTML =
            stat('clock', 'amber', c.Menunggu, 'Menunggu respons') +
            stat('check', '', c.Disetujui + c.Selesai, 'Disetujui / selesai') +
            stat('gift', 'blue', c.Semua, 'Total pengajuan');

        $tabs.innerHTML = '';
        ['Semua'].concat(P.STATUS).forEach(function (s) {
            var b = el('button', 'tab' + (filter === s ? ' on' : ''), esc(s) + '<b>' + (c[s] || 0) + '</b>');
            b.type = 'button';
            b.setAttribute('role', 'tab');
            b.setAttribute('aria-selected', filter === s);
            b.onclick = function () { filter = s; render(); };
            $tabs.appendChild(b);
        });

        $daftar.innerHTML = '';
        var tampil = list.filter(function (x) { return filter === 'Semua' || x.status === filter; });

        if (!tampil.length) {
            $daftar.appendChild(el('div', 'empty',
                '<div class="empty-ico"><svg class="ico" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>' +
                '<h3>' + (list.length ? 'Tidak ada pengajuan dengan status ini' : 'Belum ada pengajuan') + '</h3>' +
                '<p>' + (list.length ? 'Pilih status lain untuk melihat pengajuanmu.' : 'Temukan barang yang kamu butuhkan di katalog, lalu ajukan penggunaannya ke pemilik.') + '</p>' +
                '<a href="beranda.php" class="btn btn-amber">Jelajahi katalog</a>'));
            return;
        }

        tampil.forEach(function (x) {
            var card = el('article', 'req-card');
            var thumb = x.foto
                ? '<img src="' + esc(x.foto) + '" alt="">'
                : '<svg class="ico" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="m21 16-5-5-9 9"/></svg>';

            var opsi = P.STATUS.map(function (s) {
                return '<option value="' + s + '"' + (s === x.status ? ' selected' : '') + '>' + s + '</option>';
            }).join('');

            card.innerHTML =
                '<a class="row-thumb" href="detail_barang.php?id=' + encodeURIComponent(x.barangId) + '">' + thumb + '</a>' +
                '<div>' +
                    '<div class="req-head"><h3>' + esc(x.nama) + '</h3>' +
                    '<span class="badge ' + (kelasStatus[x.status] || 'st-proses') + '"><i class="dot"></i>' + esc(x.status) + '</span></div>' +
                    '<div class="req-meta">' +
                        '<span>Pemilik: ' + esc(x.pemilik) + '</span>' +
                        '<span>' + esc(x.kategori) + ' · ' + esc(x.kondisi) + '</span>' +
                        '<span>Diajukan ' + esc(P.formatTanggal(x.tanggal)) + '</span>' +
                    '</div>' +
                    '<div class="req-note"><b>' + esc(x.jenis) + '</b> — ' + esc(x.keperluan) +
                        (x.rencana ? '<br>Rencana pengambilan: ' + esc(x.rencana) : '') + '</div>' +
                '</div>' +
                '<div class="req-side">' +
                    '<label class="form-label" style="margin:0;font-size:12px">Perbarui status</label>' +
                    '<select aria-label="Status pengajuan ' + esc(x.nama) + '">' + opsi + '</select>' +
                    '<a class="btn btn-outline btn-sm" href="detail_barang.php?id=' + encodeURIComponent(x.barangId) + '">Lihat barang</a>' +
                    '<button type="button" class="btn btn-danger btn-sm">Hapus catatan</button>' +
                '</div>';

            card.querySelector('select').onchange = function (ev) { P.setStatus(U, x.id, ev.target.value); render(); };
            card.querySelector('.btn-danger').onclick = function () {
                if (window.confirm('Hapus catatan pengajuan ini dari riwayat?')) { P.remove(U, x.id); render(); }
            };
            $daftar.appendChild(card);
        });
    }

    function stat(ikon, warna, angka, label) {
        var path = { clock: '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>', check: '<path d="m5 12 5 5 9-10"/>',
            gift: '<rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13M5 12v8h14v-8"/>' }[ikon];
        return '<div class="stat-card"><span class="stat-ico ' + warna + '"><svg class="ico" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' + path + '</svg></span><div><strong>' + angka + '</strong><span>' + label + '</span></div></div>';
    }

    render();
})();
</script>
JS;
layout_close($skrip);
