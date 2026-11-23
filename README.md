# TRPL PBL-109

<!-- QUERY SQL PENGGUNAAN BARANG KEMBALI (ReUsePoli) -->

create database penggunaanbarangkembali;

use penggunaanbarangkembali;

create table tbl_user (
    id_user int auto_increment primary key,
    namalengkap varchar(200) not null,
    email varchar(200) not null,
    password varchar(200) not null,
    ulangipassword varchar(200) not null,
    nohp bigint(13) not null,
    foto int(11) not null
);

create table tbl_admin (
    id_admin int auto_increment primary key,
    email varchar(200) not null,
    password varchar(200) not null
);

create table tbl_barang (
    id_barang int auto_increment primary key,
    namabarang varchar(200) not null,
    kategori varchar(200) not null,
    kondisibarang varchar(200) not null,
    status varchar(200) not null,
    pemilik varchar(200) not null,
    deskripsi text not null,
    foto varchar(200)not null
);
