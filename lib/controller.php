<?php
require_once("config.php");
$page	=	$_GET['page'];
$action	=	$_GET['action'];

if ($page == "akun") {
	switch ($action) {
		case "insert":
			$nama_akun	=	$_POST['nama_akun'];
			$kat				=	$_POST['kategori'];
			$kode			=	$_POST['kode_akun'];
			$crud->tambah_akun($nama_akun, $kat, $kode);
			break;
		case "delete":
			$id	=	$_GET['id'];
			$crud->hapus_akun($id);
			break;
	}
}

if ($page == "transaksi") {
	switch ($action) {
		case "insert":
			$tgl			=	$_POST['tgl'];
			$id_akun	=	$_POST['id_akun'];
			$ket			= 	$_POST['ket'];
			$nominal	= 	$_POST['nominal'];
			$tipe			=	$_POST['tipe'];

			$crud->input_transaksi($tgl, $id_akun, $ket, $nominal, $tipe);
			break;

		case "hapus_transaksi":
			$id = $_GET['id'];
			$hapus = mysqli_query($config->koneksi(), "DELETE from tb_jurnal where id='$id' ");
			if ($hapus) {
				header("location:$siteurl/?page=input_transaksi");
			} else {
				echo "<script>alert('Gagal')</script>";
				header("location:$siteurl/?page=input_transaksi");
			}
			break;
	}
}
