LANGKAH-LANGKAH TESTING :
1. Start XAMPP
2. Buat database dengan nama "penggunaanbarangkembali"
3. buat 2 tabel : 1. tbl_users
                        1. id_user sebagai primary key 
                        2. namalengkap type VARCHAR(220)
                        4. email type VARCHAR(220) 
                        5. password type VARCHAR(220) 
                        6. ulangipassword type VARCHAR(220) 
                        7. nohp type VARCHAR(13) 
                        8. foto type int(11) 
                    2. tbl_barang
                        1. id_barang sebagai primary key
                        2. namabarang VARCHAR(110)
                        2. kategori VARCHAR(110)
                        2. kondisibarang VARCHAR(110)
                        2. statusbarang VARCHAR(110)
                        2. pemilik VARCHAR(110)
                        2. deskripsi TEXT
                        2. foto VARCHAR(110)
4. Buka phpmyadmin = localhost/phpmyadmin
5. Buka di browser ketik = localhost/ReUsePoli/auth/registrasi.php