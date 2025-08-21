<?php

include "lib/config.php";
$nama_akun    =    $_POST['nama_akun'];
$kat        =    $_POST['kategori'];
$kode        =    $_POST['kode'];

$cek = mysqli_query($config->koneksi(), "SELECT kode FROM tb_akun WHERE kode='$kode' OR nama_akun='$nama_akun'");

if (mysqli_num_rows($cek) > 0) {
    echo "<script>
    window.alert('Kode atau nama akun sudah ada, gunakan kode atau nama yang lain.');
    window.location.href='" . $siteurl . "/akun.php';
    </script>";
} else {
    mysqli_query($config->koneksi(), "INSERT INTO  `sia`.`tb_akun` (
        `id` ,
        `nama_akun` ,
        `kategori` ,
        `kode`
        )
        VALUES (
        NULL ,  '$nama_akun',  '$kat',  '$kode'
        );");
    header('location: akun.php');
}
