<?php
include "lib/config.php";
if (!isset($_SESSION)) {
    session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
    $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
    $_SESSION['MM_Username'] = NULL;
    $_SESSION['MM_UserGroup'] = NULL;
    $_SESSION['PrevUrl'] = NULL;
    unset($_SESSION['MM_Username']);
    unset($_SESSION['MM_UserGroup']);
    unset($_SESSION['PrevUrl']);

    $logoutGoTo = "../index.php";
    if ($logoutGoTo) {
        header("Location: $logoutGoTo");
        exit;
    }
}
?>

<?php include "partials/header.php"; ?>
<?php include "partials/navbar.php"; ?>
<?php include "partials/sidebar.php"; ?>

<div class="content">
  <div class="container-fluid">

    <!-- Judul Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-right">
      <h2 class="fw-bold text-dark"><i class="fa fa-layer-group me-2"></i> Buku Besar</h2>
    </div>

    <!-- Catatan -->
    <div class="alert alert-info shadow-sm" data-aos="fade-up">
      <i class="fa fa-info-circle me-2"></i>
      <strong>Catatan:</strong> <br>
      <span>- Saldo Debet = Total Debet - Total Kredit</span> <br>
      <span>- Saldo Kredit = Total Kredit - Total Debet</span>
    </div>

    <div class="row g-4">
      <!-- Daftar Akun -->
      <div class="col-md-3" data-aos="fade-right">
        <div class="card border-0 shadow-lg rounded-4 h-100">
          <div class="card-body">
            <h5 class="fw-bold mb-3"><i class="fa fa-book me-2 text-primary"></i> Daftar Akun</h5>
            <div class="list-group">
              <?php
              $data = mysqli_query($config->koneksi(), "SELECT *, COUNT(tb_jurnal.id_akun) AS jumlah_akun, tb_akun.id AS idakun 
                          FROM tb_jurnal 
                          JOIN tb_akun ON tb_jurnal.id_akun=tb_akun.id 
                          GROUP BY tb_jurnal.id_akun");
              $no = 1;
              while ($v = mysqli_fetch_array($data)) {
                echo "<a href='#' class='list-group-item list-group-item-action d-flex justify-content-between align-items-center' 
                          onclick='detail_akun($v[idakun])'>
                          $no. $v[nama_akun] 
                          <span class='badge bg-primary rounded-pill'>$v[jumlah_akun]</span>
                      </a>";
                $no++;
              }
              ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Detail Akun -->
      <div class="col-md-9" data-aos="fade-left">
        <div class="card border-0 shadow-lg rounded-4 h-100">
          <div class="card-body">
            <h5 class="fw-bold mb-3"><i class="fa fa-file-alt me-2 text-success"></i> Detail Akun</h5>
            <div id="detail_akun" class="p-3 border rounded bg-light text-muted">
              <em>Pilih salah satu akun untuk melihat detail transaksi...</em>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->

<script>
  function detail_akun(idakun) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState < 4) {
        document.getElementById("detail_akun").innerHTML = "<div class='text-center p-3'><div class='spinner-border text-primary' role='status'></div><br>Memuat data...</div>";
      }
      if (xhttp.readyState == 4 && xhttp.status == 200) {
        document.getElementById("detail_akun").innerHTML = xhttp.responseText;
      }
    };
    xhttp.open("GET", "assets/ajax-konten/detail_akun_tes.php?idakun=" + idakun, true);
    xhttp.send();
  }
</script>

<?php include "partials/footer.php"; ?>
