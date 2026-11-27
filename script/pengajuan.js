/* ReUsePoli — penyimpanan riwayat pengajuan (sisi browser)
 *
 * Catatan: database saat ini belum memiliki tabel pengajuan, jadi riwayat
 * disimpan di localStorage browser per akun. Struktur data dibuat sederhana
 * agar mudah dipindahkan ke tabel database di kemudian hari.
 */
window.ReusePengajuan = (function () {
    'use strict';

    var STATUS = ['Menunggu', 'Disetujui', 'Selesai', 'Ditolak', 'Dibatalkan'];

    function key(user) { return 'reusepoli_pengajuan_v1:' + user; }

    function load(user) {
        try {
            var raw = window.localStorage.getItem(key(user));
            var arr = raw ? JSON.parse(raw) : [];
            return Array.isArray(arr) ? arr : [];
        } catch (e) { return []; }
    }

    function save(user, list) {
        try { window.localStorage.setItem(key(user), JSON.stringify(list)); return true; }
        catch (e) { return false; }
    }

    function add(user, entry) {
        var list = load(user);
        entry.id = Date.now().toString(36) + Math.random().toString(36).slice(2, 6);
        entry.tanggal = new Date().toISOString();
        entry.status = 'Menunggu';
        list.unshift(entry);
        return save(user, list.slice(0, 200)) ? entry : null;
    }

    function setStatus(user, id, status) {
        var list = load(user);
        list.forEach(function (x) { if (x.id === id) x.status = status; });
        save(user, list);
    }

    function remove(user, id) {
        save(user, load(user).filter(function (x) { return x.id !== id; }));
    }

    function formatTanggal(iso) {
        var d = new Date(iso);
        if (isNaN(d)) return '-';
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) +
            ', ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    return { STATUS: STATUS, load: load, add: add, setStatus: setStatus, remove: remove, formatTanggal: formatTanggal };
})();
