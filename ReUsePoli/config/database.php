<?php

require_once __DIR__ . '/env.php';

$koneksi = new mysqli(
    env('DB_HOST'),
    env('DB_USER'),
    env('DB_PASSWORD'),
    env('DB_NAME'),
    (int) env('DB_PORT', 3306)
);

if ($koneksi->connect_error) {
    die('Koneksi database gagal');
}

$koneksi->set_charset('utf8mb4');