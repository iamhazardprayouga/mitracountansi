<?php

include "lib/config.php";
$id    =    $_GET['id'];
mysqli_query($config->koneksi(), "DELETE FROM  `sia`.`tb_akun` WHERE `tb_akun`.`id` = $id;");
header('location: akun.php');
