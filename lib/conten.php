<?php
if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case '':
			if (!file_exists("halamanutama.php")) die("Utama tidak ada!");
			include "halamanutama.php";
			break;
		default:
			if (!file_exists("halamanutama.php")) die("Utama tidak ada!");
		case "tambah_akun":
			include("lib/view/tambah_akun.php");
			break;
		case "input_transaksi":
			include("lib/view/input_transaksi.php");
			break;
		case "buku_besar":
			include("lib/view/buku_besar.php");
			break;
		case "neraca_saldo":
			include("lib/view/neraca_saldo.php");
			break;
	}
} else {
	if (!file_exists("halamanutama.php")) die("Utama tidak ada!");
	include "halamanutama.php";
}
