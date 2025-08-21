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
      <i class="fa fa-book me-2"></i> Edit Akun
    </h2>

    <?php
    $id = $_GET['id'];
    $show = mysqli_query($config->koneksi(), "SELECT * FROM tb_akun WHERE id='$id'");
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
              <i class="fa fa-edit me-2"></i> Form Edit Akun
            </h5>

            <form method="POST" action="prosesakun.php">
              <input type="hidden" name="id" value="<?php echo $id; ?>">

              <!-- Kode Akun -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Nomor/Kode Akun</label>
                <input type="text" name="kode" value="<?php echo $data_akun['kode']; ?>" class="form-control rounded-pill shadow-sm" disabled>
              </div>

              <!-- Nama Akun -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Nama Akun</label>
                <input type="text" name="nama_akun" value="<?php echo $data_akun['nama_akun']; ?>" required class="form-control rounded-pill shadow-sm">
              </div>

              <!-- Posisi Awal Saldo -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Posisi Awal Saldo</label>
                <select name="kategori" class="form-select rounded-pill shadow-sm">
                  <option value="HL" <?php if ($data_akun['kategori'] == 'HL') echo 'selected'; ?>>Debet</option>
                  <option value="HT" <?php if ($data_akun['kategori'] == 'HT') echo 'selected'; ?>>Kredit</option>
                </select>
              </div>

              <!-- Tombol -->
              <div class="text-center mt-4">
                <button type="submit" name="simpan" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                  <i class="fa fa-save me-2"></i> Simpan
                </button>
                <a href="akun.php" class="btn btn-outline-secondary px-4 py-2 rounded-pill shadow-sm ms-2">
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
