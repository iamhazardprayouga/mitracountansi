<?php
class crud extends config
{
	public function tes()
	{
		echo "Ada";
	}
	//CRUD Akun	
	public function tambah_akun($nama_akun, $kat, $kode)
	{
		$siteurl	=	$this->siteurl();
		$insert = mysqli_query($this->koneksi(), "INSERT into tb_akun (nama_akun,kategori,kode) values ('$nama_akun','$kat','$kode')");
		if ($insert) {
			echo "<script>alert('Akun telah ditambahkan'); location='$siteurl/?page=tambah_akun';</script>";
		} else {
			echo "<script>alert('Gagal'); llocation='$siteurl/?page=tambah_akun';</script>";
		}
	}

	public function hapus_akun($id)
	{
		$siteurl	=	$this->siteurl();
		$hapus_transaksi = mysqli_query($this->koneksi(), "DELETE from tb_jurnal where id_akun='$id' ");
		if ($hapus_transaksi) {
			$hapus_akun = mysqli_query($this->koneksi(), "DELETE from tb_akun where id='$id' ");
			if ($hapus_akun) {
				echo "<script>alert('Akun dan transaksi dihapus'); location='$siteurl/?page=tambah_akun';</script>";
			} else {
				echo "<script>alert('Gagal menghapus akun'); llocation='$siteurl/?page=tambah_akun';</script>";
			}
		} else {
			echo "<script>alert('Gagal menghapus transaksi'); location='$siteurl/?page=tambah_akun';</script>";
		}
	}
	//--------------------------------------------------------------------------------------------------------------------------------------------------------

	//CRUD transaksi
	public function input_transaksi($tgl, $id_akun, $ket, $nominal, $tipe)
	{
		$siteurl	=	$this->siteurl();
		$insert = mysqli_query($this->koneksi(), "INSERT into tb_jurnal (tgl, id_akun, ket, nominal, tipe) values ('$tgl','$id_akun','$ket','$nominal','$tipe')");
		if ($insert) {
			echo "<script>alert('Transaksi telah diinput'); location='$siteurl/?page=input_transaksi';</script>";
		} else {
			echo "<script>alert('Gagal'); llocation='$siteurl/?page=input_transaksi';</script>";
		}
	}

	public function hapus_transaksi($id)
	{
		$id = $_GET['id'];
		$siteurl	=	$this->siteurl();
		$hapus = mysqli_query($this->koneksi(), "DELETE from tb_jurnal where id='$id' ");
		if ($hapus) {
			echo "<script>alert('Transaksi dihapus') location='$siteurl/?page=input_transaksi';</script>";
		} else {
			echo "<script>alert('Gagal');</script> location='location='$siteurl/?page=input_transaksi'' ";
		}
	}
	//--------------------------------------------------------------------------------------------------------------------------------------------------------------

	//CRUD Buku Besar
	//-------------------------------------------------------------------------------------------------------------------------------------------------------------
}
