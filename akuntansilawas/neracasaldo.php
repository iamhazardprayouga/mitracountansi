<?php
include "lib/config.php";
date_default_timezone_set('Asia/Jakarta');
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
    <div class="d-flex align-items-center mb-4" data-aos="fade-right">
      <i class="fa fa-balance-scale text-primary me-2" style="font-size: 28px;"></i>
      <h2 class="fw-bold text-primary m-0">Neraca Saldo</h2>
    </div>

    <div class="card shadow-lg border-0 rounded-4" data-aos="zoom-in" data-aos-duration="800">
      <div class="card-body">

        <!-- Scroll Area -->
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;" data-aos="fade-up" data-aos-duration="900">
          <table class="table table-bordered table-hover table-striped align-middle text-center">
            <thead class="bg-gradient-primary text-white sticky-top">
              <tr>
                <th>No</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Debet</th>
                <th>Kredit</th>
              </tr>
            </thead>
            <tbody>
              <?php
              error_reporting(0);

              // Ambil semua data langsung (tanpa pagination)
              $data = mysqli_query(
                  $config->koneksi(),
                  "SELECT *, tb_jurnal.id_akun 'idakun' 
                  FROM tb_jurnal, tb_akun 
                  WHERE tb_jurnal.id_akun=tb_akun.id 
                  GROUP BY tb_jurnal.id_akun"
              );

              $no = 1;
              $ts_debet = 0;
              $ts_kredit = 0;
              while ($d = mysqli_fetch_array($data)) {
                  $total_debet = 0;
                  $total_kredit = 0;
                  $saldo_debet = 0;
                  $saldo_kredit = 0;

                  $neraca = mysqli_query(
                      $config->koneksi(),
                      "SELECT *, tb_jurnal.id_akun 'idakun' 
                       FROM tb_jurnal, tb_akun 
                       WHERE tb_jurnal.id_akun=tb_akun.id 
                       AND tb_jurnal.id_akun='$d[idakun]' "
                  );

                  while ($n = mysqli_fetch_array($neraca)) {
                      switch ($n['tipe']) {
                          case "D":
                              $debet = $n['nominal'];
                              $kredit = 0;
                              break;
                          case "K":
                              $kredit = $n['nominal'];
                              $debet = 0;
                              break;
                      }
                      $total_debet += $debet;
                      $total_kredit += $kredit;

                      switch ($n['kategori']) {
                          case "HL":
                              $saldo_debet = $total_debet - $total_kredit;
                              break;
                          case "HT":
                              $saldo_kredit = $total_kredit - $total_debet;
                              break;
                      }
                  }

                  $ts_kredit += $saldo_kredit;
                  $ts_debet += $saldo_debet;
                  echo "
                    <tr class='hover-row'>
                        <td>$no</td>
                        <td><span class='badge bg-light text-dark px-3 py-2'>$d[kode]</span></td>
                        <td class='text-start ps-3 fw-semibold'>$d[nama_akun]</td>
                        <td class='text-end text-success fw-bold'>Rp " . number_format($saldo_debet, 0, ',', '.') . "</td>
                        <td class='text-end text-danger fw-bold'>Rp " . number_format($saldo_kredit, 0, ',', '.') . "</td>
                    </tr>
                  ";
                  $no++;
              }
              ?>
              <tr class="table-primary fw-bold">
                <td colspan="3" class="text-center">TOTAL</td>
                <td class="text-end">Rp <?php echo number_format($ts_debet, 0, ",", "."); ?></td>
                <td class="text-end">Rp <?php echo number_format($ts_kredit, 0, ",", "."); ?></td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Styling -->
<style>
  thead.bg-gradient-primary {
    background: linear-gradient(45deg, #0d6efd, #6610f2);
  }

  .hover-row {
    transition: all 0.3s ease-in-out;
  }

  .hover-row:hover {
    background: #f0f9ff !important;
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  /* Supaya header tabel tetap terlihat saat scroll */
  thead.sticky-top th {
    position: sticky;
    top: 0;
    z-index: 10;
  }
</style>

<!-- AOS animasi hanya saat pertama muncul -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    once: true,       // animasi hanya sekali saat load
    duration: 800,
  });
</script>

<?php include "partials/footer.php"; ?>
