<?php

include "lib/config.php";
$id    =    $_GET['id'];
mysqli_query($config->koneksi(), "DELETE FROM  `sia`.`tb_jurnal` WHERE `tb_jurnal`.`id` = $id;");
header('location: jurnalumum.php');
