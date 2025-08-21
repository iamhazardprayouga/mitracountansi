<?php
if (isset($_POST['simpan'])) {
    include('lib/config.php');
    $id       = $_POST['id'];
    $tgl      = $_POST['tgl'];
    $id_akun  = $_POST['id_akun'];
    $nominal  = $_POST['nominal'];
    $ket      = $_POST['ket'];
    $tipe     = $_POST['tipe'];

    // Update data langsung tanpa validasi kaku
    $update = mysqli_query(
        $config->koneksi(),
        "UPDATE tb_jurnal 
         SET tgl='$tgl', id_akun='$id_akun', nominal='$nominal', ket='$ket', tipe='$tipe' 
         WHERE id='$id'"
    ) or die(mysqli_error($config->koneksi()));

    // Setelah update, cek keseimbangan total debet & kredit di tanggal itu
    $q = mysqli_query($config->koneksi(), 
        "SELECT 
            SUM(CASE WHEN tipe='D' THEN nominal ELSE 0 END) AS total_debet,
            SUM(CASE WHEN tipe='K' THEN nominal ELSE 0 END) AS total_kredit
         FROM tb_jurnal 
         WHERE tgl='$tgl'"
    );
    $cek = mysqli_fetch_assoc($q);

    if ($cek['total_debet'] != $cek['total_kredit']) {
        echo "<script>
            alert('⚠️ Perhatian: Total Debet dan Kredit untuk tanggal $tgl belum seimbang!');
            window.location.href='jurnalumum.php';
        </script>";
    } else {
        header('Location: jurnalumum.php');
        exit;
    }
}
?>
