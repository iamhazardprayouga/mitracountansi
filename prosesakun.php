<?php
if (isset($_POST['simpan'])) {
  include('lib/config.php');
  $id       = $_POST['id'];
  $nama_akun  =   $_POST['nama_akun'];
  $kat        =   $_POST['kategori'];
  $kode       =   $_POST['kode'];
  $update = mysqli_query($config->koneksi(), "UPDATE tb_akun SET kode='$kode', nama_akun='$nama_akun', kategori='$kat' WHERE id='$id'") or die('haha lucu');

  if ($update) {
    ?>
    <script language="JavaScript">
      alert('Data Berhasil Di update');
      document.location = 'akun.php';
    </script>
<?php
  } else {
    echo 'Gagal menyimpan data!';
    echo '<a href="editakun.php?id=' . $id . '">Kembali</a>';
  }
} else {
  echo '<script>window.history.back()</script>';
}
?>