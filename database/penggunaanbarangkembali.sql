create database penggunaanbarangkembali;

use penggunaanbarangkembali;


-- STRUKTUR TABLE USER

create table tbl_users (
    id_user int auto_increment primary key,
    namalengkap varchar(200) not null,
    email varchar(200) not null,
    password varchar(200) not null,
    ulangipassword varchar(200) not null,
    nohp bigint(13) not null,
    foto int(11) not null
);


-- STRUKTUR TABLE ADMIN

create table tbl_admin (
    id_admin int auto_increment primary key,
    email varchar(200) not null,
    password varchar(200) not null
);


-- STRUKTUR TABLE BARANG

create table tbl_barang (
    id_barang int auto_increment primary key,
    namabarang varchar(200) not null,
    kategori varchar(200) not null,
    kondisibarang varchar(200) not null,
    statusbarang varchar(200) not null,
    pemilik varchar(200) not null,
    deskripsi text not null,
    foto varchar(200)not null
);
