<?php

$envPath = __DIR__ . '/../.env';

if (!file_exists($envPath)) {
    die('File .env tidak ditemukan. Salin .env.example menjadi .env');
}

$env = parse_ini_file($envPath);

if ($env === false) {
    die('Gagal membaca konfigurasi .env');
}

function env($key, $default = null)
{
    global $env;
    return $env[$key] ?? $default;
}