<?php
date_default_timezone_set('Asia/Jakarta');
include "lib/config.php";
if (!isset($_SESSION)) {
    session_start();
}

$koneksi = $config->koneksi();

/* ====== PROSES LOGOUT ====== */
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if (!empty($_SERVER['QUERY_STRING'])) {
    $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}
if (isset($_GET['doLogout']) && $_GET['doLogout'] === "true") {
    $_SESSION = [];
    session_destroy();
    header("Location: ../index.php");
    exit;
}
?>
<?php include "partials/header.php"; ?>
<?php include "partials/navbar.php"; ?>
<?php include "partials/sidebar.php"; ?>

<div class="content">
  <div class="container-fluid">

    <!-- JUDUL HALAMAN -->
    <div class="d-flex align-items-center mb-4" data-aos="fade-down" data-aos-duration="800">
      <i class="fa fa-book-open text-primary me-3 animate__animated animate__bounceIn" style="font-size: 38px;"></i>
      <h2 class="fw-bold text-dark mb-0" style="letter-spacing:1px;">Jurnal Umum</h2>
    </div>

    <!-- ====== LIST JURNAL ====== -->
    <div class="card shadow-lg border-0 rounded-4 hover-card animate__animated animate__fadeInUp">
      <div class="card-header bg-gradient-primary text-white fw-bold rounded-top-4 d-flex justify-content-between align-items-center py-3 px-4">
        <span><i class="fa fa-table me-2"></i> Daftar Jurnal Umum</span>
        <a href="tambah_jurnal.php" class="btn btn-light btn-sm rounded-3 shadow-sm fw-semibold hover-scale">
          <i class="fa fa-plus"></i> Tambah Transaksi
        </a>
      </div>
      <div class="card-body">
        <div class="table-responsive" style="max-height: 520px; overflow:auto;">
          <table class="table table-hover align-middle table-striped mb-0">
            <thead class="table-dark sticky-top">
              <tr>
                <th>No</th>
                <th>Tgl</th>
                <th>No Jurnal</th>
                <th>Akun</th>
                <th class="text-end">Debet</th>
                <th class="text-end">Kredit</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $qRef = mysqli_query($koneksi, "
                SELECT ref, MIN(tgl) AS tgl
                FROM tb_jurnal
                GROUP BY ref
                ORDER BY MIN(tgl) DESC, ref DESC
            ");
            $no = 1;
            $all_debet = 0; $all_kredit = 0;

            while ($h = mysqli_fetch_assoc($qRef)) {
                echo "<tr class='table-primary animate__animated animate__fadeIn'>
                        <td class='text-center fw-bold'>{$no}</td>
                        <td>".date('d/m/y', strtotime($h['tgl']))."</td>
                        <td><span class='badge bg-info text-dark shadow-sm px-3 py-2'>{$h['ref']}</span></td>
                        <td colspan='4'></td>
                      </tr>";

                $qDetail = mysqli_query($koneksi, "
                    SELECT j.id AS idjurnal, j.nominal, j.tipe, a.nama_akun
                    FROM tb_jurnal j
                    JOIN tb_akun a ON j.id_akun=a.id
                    WHERE j.ref='".$h['ref']."'
                    ORDER BY j.id ASC
                ");

                $totalD = 0; $totalK = 0;
                while ($v = mysqli_fetch_assoc($qDetail)) {
                    $akun = ($v['tipe']=='K')
                        ? "<span class='ms-3 text-secondary'>".$v['nama_akun']."</span>"
                        : $v['nama_akun'];

                    $d = ($v['tipe']=='D') ? $v['nominal'] : 0;
                    $k = ($v['tipe']=='K') ? $v['nominal'] : 0;
                    $totalD += $d; $totalK += $k;

                    echo "<tr class='fade-in-row'>
                            <td></td><td></td><td></td>
                            <td>{$akun}</td>
                            <td class='text-end text-success'>".($d? 'Rp '.number_format($d,0,',','.') : '')."</td>
                            <td class='text-end text-danger'>".($k? 'Rp '.number_format($k,0,',','.') : '')."</td>
                            <td>
                              <a href='editjurnal.php?id=".$v['idjurnal']."' class='btn btn-sm btn-outline-warning rounded-circle me-1 shadow-sm' data-bs-toggle='tooltip' title='Edit Transaksi'>
                                <i class='fa fa-edit'></i>
                              </a>
                              <a href='hapus_transaksi.php?id=".$v['idjurnal']."' onclick=\"return confirm('Yakin hapus data?');\" 
                                 class='btn btn-sm btn-outline-danger rounded-circle shadow-sm' data-bs-toggle='tooltip' title='Hapus Transaksi'>
                                <i class='fa fa-trash'></i>
                              </a>
                            </td>
                          </tr>";
                }

                echo "<tr class='table-light fw-bold'>
                        <td colspan='4' class='text-center'>SUBTOTAL</td>
                        <td class='text-end text-success'>Rp ".number_format($totalD,0,',','.')."</td>
                        <td class='text-end text-danger'>Rp ".number_format($totalK,0,',','.')."</td>
                        <td></td>
                      </tr>";

                $no++;
                $all_debet += $totalD;
                $all_kredit += $totalK;
            }

            echo "<tr class='table-dark text-white fw-bold'>
                    <td colspan='4' class='text-center'>TOTAL</td>
                    <td class='text-end'>Rp ".number_format($all_debet,0,',','.')."</td>
                    <td class='text-end'>Rp ".number_format($all_kredit,0,',','.')."</td>
                    <td></td>
                  </tr>";
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Efek Hover & Animasi -->
<style>
  .hover-card { transition: transform .3s ease, box-shadow .3s ease; }
  .hover-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
  .fade-in-row { animation: fadeIn .6s ease-in-out; }
  @keyframes fadeIn {
    from {opacity:0; transform: translateY(12px);}
    to {opacity:1; transform: translateY(0);}
  }
  .hover-scale { transition: all .25s ease-in-out; }
  .hover-scale:hover { transform: scale(1.08); background:#f8f9fa; color:#000; }
  .bg-gradient-primary {
    background: linear-gradient(135deg, #1d4ed8, #2563eb, #3b82f6);
  }
</style>

<script>
  // Tooltip Bootstrap
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>

<?php include "partials/footer.php"; ?>
