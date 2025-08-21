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

/* ====== PROSES SIMPAN JURNAL MULTI BARIS ====== */
if (isset($_POST['simpan_jurnal'])) {
    $tgl = $_POST['tgl'] ?? date('Y-m-d');
    $ket = $_POST['ket'] ?? '';
    $id_akun  = $_POST['id_akun'] ?? [];
    $debet    = $_POST['debet'] ?? [];
    $kredit   = $_POST['kredit'] ?? [];

    // Nomor jurnal unik
    $ref = 'JR' . time();

    $total_debet = 0;
    $total_kredit = 0;

    // fungsi bersih angka
    function bersihNominal($val) {
        $val = str_replace([',','.',' '], '', $val);
        return is_numeric($val) ? (float)$val : 0;
    }

    for ($i = 0; $i < count($id_akun); $i++) {
        $total_debet  += bersihNominal($debet[$i] ?? 0);
        $total_kredit += bersihNominal($kredit[$i] ?? 0);
    }

    if ($total_debet <= 0 && $total_kredit <= 0) {
        echo "<script>alert('Nominal masih kosong.');window.history.back();</script>";
        exit;
    }
    if (round($total_debet,2) != round($total_kredit,2)) {
        echo "<script>alert('Transaksi tidak balance! Total Debet harus sama dengan Total Kredit.');window.history.back();</script>";
        exit;
    }

    // simpan baris jurnal
    $stmt = $koneksi->prepare("INSERT INTO tb_jurnal (tgl,id_akun,ket,ref,nominal,tipe) VALUES (?,?,?,?,?,?)");

    for ($i = 0; $i < count($id_akun); $i++) {
        $akun = (int)$id_akun[$i];
        $d = bersihNominal($debet[$i] ?? 0);
        $k = bersihNominal($kredit[$i] ?? 0);

        if ($akun <= 0) continue;

        if ($d > 0) {
            $stmt->bind_param("sissds", $tgl, $akun, $ket, $ref, $d, $tipe);
            $tipe = 'D';
            $stmt->execute();
        }
        if ($k > 0) {
            $stmt->bind_param("sissds", $tgl, $akun, $ket, $ref, $k, $tipe);
            $tipe = 'K';
            $stmt->execute();
        }
    }

    $stmt->close();
    header("Location: jurnalumum.php");
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
      <i class="fa fa-book-open text-primary me-3" style="font-size: 34px;"></i>
      <h2 class="fw-bold text-dark mb-0" style="letter-spacing:1px;">Jurnal Umum</h2>
    </div>

    <div class="row g-4">

      <!-- ====== FORM INPUT ====== -->
      <div class="col-md-4" data-aos="zoom-in" data-aos-duration="1000">
        <div class="card shadow-lg border-0 rounded-4 h-100 hover-card">
          <div class="card-header text-white fw-bold rounded-top-4" 
               style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
            <i class="fa fa-plus-circle me-2"></i> Input Transaksi
          </div>
          <div class="card-body">
            <form method="POST" action="">
              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Transaksi</label>
                <input class="form-control rounded-3 shadow-sm" type="date" name="tgl" required 
                       value="<?php echo htmlspecialchars(date('Y-m-d')); ?>">
              </div>

              <div class="mb-3">
                <label class="form-label fw-semibold">Keterangan</label>
                <input type="text" name="ket" class="form-control rounded-3 shadow-sm" 
                       placeholder="Misal: Top Up BRI ke Everpro" required>
              </div>

              <!-- Tabel Input -->
              <div class="table-responsive mb-3">
                <table class="table align-middle table-bordered table-sm shadow-sm" id="jurnalTable">
                  <thead class="table-light">
                    <tr>
                      <th>Akun</th>
                      <th class="text-end">Debet (Rp)</th>
                      <th class="text-end">Kredit (Rp)</th>
                      <th class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <select name="id_akun[]" class="form-select rounded-3 shadow-sm" required>
                          <option value="">-- Pilih Akun --</option>
                          <?php
                          $resAkun = mysqli_query($koneksi, "SELECT id, nama_akun, kode FROM tb_akun ORDER BY kode, nama_akun");
                          while ($r = mysqli_fetch_assoc($resAkun)) {
                              $label = ($r['kode'] ? $r['kode'].' - ' : '').$r['nama_akun'];
                              echo "<option value='".$r['id']."'>".htmlspecialchars($label)."</option>";
                          }
                          ?>
                        </select>
                      </td>
                      <td><input type="number" name="debet[]" class="form-control text-end rounded-3 shadow-sm" step="0.01" min="0"></td>
                      <td><input type="number" name="kredit[]" class="form-control text-end rounded-3 shadow-sm" step="0.01" min="0"></td>
                      <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" onclick="hapusRow(this)">
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot class="table-light">
                    <tr>
                      <th class="text-end">TOTAL</th>
                      <th><input type="text" id="totalDebet" class="form-control text-end bg-light rounded-3" readonly></th>
                      <th><input type="text" id="totalKredit" class="form-control text-end bg-light rounded-3" readonly></th>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 shadow-sm px-3" onclick="tambahRow()">
                  <i class="fa fa-plus"></i> Tambah Baris
                </button>
                <button type="submit" name="simpan_jurnal" class="btn btn-primary rounded-3 shadow-sm px-4" onclick="return cekBalance()">
                  <i class="fa fa-save"></i> Simpan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- ====== LIST JURNAL ====== -->
      <div class="col-md-8" data-aos="fade-left" data-aos-duration="1000">
        <div class="card shadow-lg border-0 rounded-4 h-100 hover-card">
          <div class="card-header bg-dark text-white fw-bold rounded-top-4">
            <i class="fa fa-table me-2"></i> Daftar Jurnal Umum
          </div>
          <div class="card-body">
            <div class="table-responsive" style="max-height: 500px; overflow:auto;">
              <table class="table table-hover align-middle table-striped">
                <thead class="table-dark">
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
                    echo "<tr class='table-primary'>
                            <td class='text-center fw-bold'>{$no}</td>
                            <td>".date('d/m/y', strtotime($h['tgl']))."</td>
                            <td><span class='badge bg-info text-dark shadow-sm'>{$h['ref']}</span></td>
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

                        echo "<tr class='fade-in'>
                                <td></td><td></td><td></td>
                                <td>{$akun}</td>
                                <td class='text-end'>".($d? 'Rp '.number_format($d,0,',','.') : '')."</td>
                                <td class='text-end'>".($k? 'Rp '.number_format($k,0,',','.') : '')."</td>
                                <td>
                                  <a href='editjurnal.php?id=".$v['idjurnal']."' class='btn btn-sm btn-outline-warning rounded-circle'>
                                    <i class='fa fa-edit'></i>
                                  </a>
                                  <a href='hapus_transaksi.php?id=".$v['idjurnal']."' onclick=\"return confirm('Yakin hapus data?');\" 
                                     class='btn btn-sm btn-outline-danger rounded-circle'>
                                    <i class='fa fa-trash'></i>
                                  </a>
                                </td>
                              </tr>";
                    }

                    echo "<tr class='table-light fw-bold'>
                            <td colspan='4' class='text-center'>SUBTOTAL</td>
                            <td class='text-end'>Rp ".number_format($totalD,0,',','.')."</td>
                            <td class='text-end'>Rp ".number_format($totalK,0,',','.')."</td>
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
  </div>
</div>

<!-- Efek Hover Card -->
<style>
  .hover-card { transition: transform .3s ease, box-shadow .3s ease; }
  .hover-card:hover { transform: translateY(-6px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
  .fade-in { animation: fadeIn .6s ease-in-out; }
  @keyframes fadeIn {
    from {opacity:0; transform: translateY(10px);}
    to {opacity:1; transform: translateY(0);}
  }
</style>

<?php include "partials/footer.php"; ?>

<!-- JS -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
// tambah / hapus baris input
function tambahRow() {
    var tbody = document.querySelector('#jurnalTable tbody');
    var tr0 = tbody.querySelector('tr');
    var clone = tr0.cloneNode(true);
    clone.querySelectorAll('input').forEach(function(inp){ inp.value=''; });
    clone.querySelector('select').selectedIndex = 0;
    tbody.appendChild(clone);
    hitungTotal();
}
function hapusRow(btn) {
    var tbody = document.querySelector('#jurnalTable tbody');
    if (tbody.rows.length <= 1) {
        var tr = btn.closest('tr');
        tr.querySelectorAll('input').forEach(function(inp){ inp.value=''; });
        tr.querySelector('select').selectedIndex = 0;
        hitungTotal();
        return;
    }
    btn.closest('tr').remove();
    hitungTotal();
}
function hitungTotal() {
    var d = 0, k = 0;
    document.querySelectorAll("input[name='debet[]']").forEach(function(i){ d += parseFloat(i.value||0); });
    document.querySelectorAll("input[name='kredit[]']").forEach(function(i){ k += parseFloat(i.value||0); });
    document.getElementById('totalDebet').value  = d.toLocaleString('id-ID');
    document.getElementById('totalKredit').value = k.toLocaleString('id-ID');
}
document.addEventListener('input', function(e){
    if (e.target && (e.target.name === 'debet[]' || e.target.name === 'kredit[]')) hitungTotal();
});
function cekBalance(){
    var totalD = 0, totalK = 0;
    document.querySelectorAll("input[name='debet[]']").forEach(function(i){ totalD += parseFloat(i.value||0); });
    document.querySelectorAll("input[name='kredit[]']").forEach(function(i){ totalK += parseFloat(i.value||0); });
    if (totalD <= 0 && totalK <= 0) { alert('Nominal masih kosong.'); return false; }
    if (Math.round(totalD*100)/100 !== Math.round(totalK*100)/100) {
        alert('Total Debet dan Total Kredit harus sama!');
        return false;
    }
    return true;
}
hitungTotal();
</script>

<?php include "partials/footer.php"; ?>
