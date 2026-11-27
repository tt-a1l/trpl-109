/* ReUsePoli — interaksi UI umum (navbar mobile, dialog, preview foto) */
(function () {
    'use strict';

    // Navbar mobile
    var toggle = document.getElementById('nav-toggle');
    var menu = document.getElementById('nav-menu');
    if (toggle && menu) {
        toggle.addEventListener('click', function () {
            var open = menu.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    // Buka / tutup <dialog> lewat atribut data
    document.querySelectorAll('[data-open-dialog]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var d = document.getElementById(btn.getAttribute('data-open-dialog'));
            if (d && typeof d.showModal === 'function') d.showModal();
        });
    });
    document.querySelectorAll('[data-close-dialog]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var d = btn.closest('dialog');
            if (d) d.close();
        });
    });
    document.querySelectorAll('dialog').forEach(function (d) {
        d.addEventListener('click', function (ev) {
            if (ev.target === d) d.close(); // klik latar = tutup
        });
    });

    // Preview foto sebelum diunggah
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        var img = document.getElementById(input.getAttribute('data-preview'));
        if (!img) return;
        input.addEventListener('change', function (ev) {
            var file = ev.target.files[0];
            if (file && file.type.indexOf('image/') === 0) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    img.src = e.target.result;
                    img.parentElement.classList.add('has-image');
                };
                reader.readAsDataURL(file);
            } else {
                img.removeAttribute('src');
                img.parentElement.classList.remove('has-image');
            }
        });
    });

    // Konfirmasi sebelum aksi berbahaya
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (ev) {
            if (!window.confirm(form.getAttribute('data-confirm'))) ev.preventDefault();
        });
    });
})();
