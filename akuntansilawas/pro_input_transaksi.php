<?php
error_reporting(0);

include "lib/config.php";
$tgl            =    $_POST['tgl'];
$id_akun    =    $_POST['id_akun'];
$ref    =    $_POST['ref'];
$ket            =     $_POST['ket'];
$nominal    =     $_POST['nominal'];
$tipe            =    $_POST['tipe'];

$month = mysqli_fetch_assoc(mysqli_query($config->koneksi(),"select month(curdate()) as month1"))['month1'];
$year = mysqli_fetch_assoc(mysqli_query($config->koneksi(),"select year(curdate()) as year1"))['year1'];

if($year == substr($tgl, 0,4) && $month == substr($tgl, 5,2)){
    if ($tipe == "K") {
        $cek = mysqli_fetch_assoc(mysqli_query($config->koneksi(), "SELECT SUM(nominal) as nominal FROM tb_jurnal WHERE tipe='D' AND tgl='$tgl'"))['nominal'];
    
        if ($cek != $nominal) {
            echo "<script>
            window.alert('Nominal tidak sama dengan Debet.');
            window.location.href='".$siteurl."/jurnalumum.php';
            </script>";
        } else {
            mysqli_query($config->koneksi(), "INSERT INTO  `sia`.`tb_jurnal` (
                `id` ,
                `tgl` ,
                `id_akun` ,
                `ket` ,
                `ref` ,
                `nominal` ,
                `tipe`
                )
                VALUES (
                NULL ,  '$tgl',  '$id_akun',  '$ket',  '$ref',  '$nominal',  '$tipe'
                );");
            header('location: jurnalumum.php');
        }
    } else {
        mysqli_query($config->koneksi(), "INSERT INTO  `sia`.`tb_jurnal` (
            `id` ,
            `tgl` ,
            `id_akun` ,
            `ket` ,
            `ref` ,
            `nominal` ,
            `tipe`
            )
            VALUES (
            NULL ,  '$tgl',  '$id_akun',  '$ket',  '$ref',  '$nominal',  '$tipe'
            );");
        header('location: jurnalumum.php');
    }
}else{
    echo "<script>
            window.alert('Harus pada bulan dan tahun saat ini.');
            window.location.href='".$siteurl."/jurnalumum.php';
            </script>";
}
