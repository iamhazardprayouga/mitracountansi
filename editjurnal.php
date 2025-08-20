<?php
include "lib/config.php";
if (!isset($_SESSION)) {
    session_start();
}

// ** Logout **
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
    <h2 class="mb-4 fw-bold text-primary d-flex align-items-center" data-aos="fade-right">
      <i class="fa fa-balance-scale me-2"></i> Edit Jurnal Transaksi
    </h2>

    <?php
    $id = $_GET['id'];
    $show = mysqli_query($config->koneksi(), "SELECT * FROM tb_jurnal WHERE id='$id'");
    if (mysqli_num_rows($show) == 0) {
        echo '<script>window.history.back()</script>';
    } else {
        $data_akun = mysqli_fetch_assoc($show);
    }
    ?>

    <div class="row">
      <div class="col-md-6 offset-md-3" data-aos="zoom-in" data-aos-duration="1000">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-body p-4">

            <h5 class="card-title fw-bold text-center text-primary mb-4">
              <i class="fa fa-edit me-2"></i> Form Edit Jurnal
            </h5>

            <form method="POST" action="prosesjurnal.php">
              <input type="hidden" name="id" value="<?php echo $id; ?>">

              <!-- Tanggal -->
              <div class="mb-3">
                <label for="tgl_transaksi" class="form-label fw-semibold">Tanggal Transaksi</label>
                <input type="date" name="tgl" id="tgl_transaksi" value="<?php echo $data_akun['tgl']; ?>" class="form-control rounded-pill shadow-sm">
              </div>

              <!-- Akun -->
              <div class="mb-3">
                <label for="id_akun" class="form-label fw-semibold">Pilih Akun</label>
                <select name="id_akun" id="id_akun" class="form-select rounded-pill shadow-sm">
                  <?php
                  $data = mysqli_query($config->koneksi(), "SELECT * FROM tb_akun");
                  while ($row = mysqli_fetch_array($data)) {
                      $selected = ($row['id'] == $data_akun['id_akun']) ? "selected" : "";
                      echo "<option value='{$row['id']}' $selected>{$row['nama_akun']}</option>";
                  }
                  ?>
                </select>
              </div>

              <!-- Nominal -->
              <div class="mb-3">
                <label for="nominal" class="form-label fw-semibold">Nominal</label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="number" name="nominal" id="nominal" value="<?php echo $data_akun['nominal']; ?>" class="form-control rounded-end shadow-sm">
                </div>
              </div>

              <!-- Keterangan -->
              <div class="mb-3">
                <label for="ket" class="form-label fw-semibold">Keterangan</label>
                <input type="text" name="ket" id="ket" value="<?php echo $data_akun['ket']; ?>" class="form-control rounded-pill shadow-sm">
              </div>

              <!-- Tipe -->
              <div class="mb-3">
                <label for="tipe" class="form-label fw-semibold">Tipe</label>
                <select name="tipe" id="tipe" class="form-select rounded-pill shadow-sm">
                  <option value="D" <?php if ($data_akun['tipe'] == 'D') echo 'selected'; ?>>Debet</option>
                  <option value="K" <?php if ($data_akun['tipe'] == 'K') echo 'selected'; ?>>Kredit</option>
                </select>
              </div>

              <!-- Tombol Simpan -->
              <div class="text-center mt-4">
                <button type="submit" name="simpan" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                  <i class="fa fa-save me-2"></i> Simpan Transaksi
                </button>
                <a href="jurnal.php" class="btn btn-outline-secondary px-4 py-2 rounded-pill shadow-sm ms-2">
                  <i class="fa fa-times me-2"></i> Batal
                </a>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include "partials/footer.php"; ?>
