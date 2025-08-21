<?php
date_default_timezone_set('Asia/Jakarta');
include "lib/config.php";
if (!isset($_SESSION)) {
    session_start();
}

$koneksi = $config->koneksi();

/* ====== PROSES SIMPAN JURNAL MULTI BARIS ====== */
if (isset($_POST['simpan_jurnal'])) {
    $tgl = $_POST['tgl'] ?? date('Y-m-d');
    $ket = $_POST['ket'] ?? '';
    $id_akun  = $_POST['id_akun'] ?? [];
    $debet    = $_POST['debet'] ?? [];
    $kredit   = $_POST['kredit'] ?? [];

    $ref = 'JR' . time();
    $total_debet = 0; $total_kredit = 0;

    function bersihNominal($val) {
        $val = str_replace([',','.',' '], '', $val);
        return is_numeric($val) ? (float)$val : 0;
    }

    for ($i=0; $i<count($id_akun); $i++) {
        $total_debet  += bersihNominal($debet[$i] ?? 0);
        $total_kredit += bersihNominal($kredit[$i] ?? 0);
    }

    if ($total_debet <= 0 && $total_kredit <= 0) {
        echo "<script>alert('Nominal masih kosong.');window.history.back();</script>"; exit;
    }
    if (round($total_debet,2) != round($total_kredit,2)) {
        echo "<script>alert('Transaksi tidak balance!');window.history.back();</script>"; exit;
    }

    $stmt = $koneksi->prepare("INSERT INTO tb_jurnal (tgl,id_akun,ket,ref,nominal,tipe) VALUES (?,?,?,?,?,?)");

    for ($i=0; $i<count($id_akun); $i++) {
        $akun = (int)$id_akun[$i];
        $d = bersihNominal($debet[$i] ?? 0);
        $k = bersihNominal($kredit[$i] ?? 0);

        if ($akun <= 0) continue;

        if ($d > 0) {
            $tipe = 'D';
            $stmt->bind_param("sissds", $tgl, $akun, $ket, $ref, $d, $tipe);
            $stmt->execute();
        }
        if ($k > 0) {
            $tipe = 'K';
            $stmt->bind_param("sissds", $tgl, $akun, $ket, $ref, $k, $tipe);
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
    <div class="d-flex align-items-center mb-4" data-aos="fade-down" data-aos-duration="800">
      <i class="fa fa-plus-circle text-primary me-3" style="font-size: 34px;"></i>
      <h2 class="fw-bold text-dark mb-0">Tambah Transaksi Jurnal</h2>
    </div>

    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-header text-white fw-bold rounded-top-4" 
           style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
        <i class="fa fa-pencil-alt me-2"></i> Form Input Transaksi
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
            <input type="text" name="ket" class="form-control rounded-3 shadow-sm" required>
          </div>

          <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm shadow-sm" id="jurnalTable">
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
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
function tambahRow() {
    var tbody = document.querySelector('#jurnalTable tbody');
    var tr0 = tbody.querySelector('tr');
    var clone = tr0.cloneNode(true);
    clone.querySelectorAll('input').forEach(inp=> inp.value='');
    clone.querySelector('select').selectedIndex = 0;
    tbody.appendChild(clone);
    hitungTotal();
}
function hapusRow(btn) {
    var tbody = document.querySelector('#jurnalTable tbody');
    if (tbody.rows.length <= 1) {
        var tr = btn.closest('tr');
        tr.querySelectorAll('input').forEach(inp=> inp.value='');
        tr.querySelector('select').selectedIndex = 0;
        hitungTotal();
        return;
    }
    btn.closest('tr').remove();
    hitungTotal();
}
function hitungTotal() {
    var d = 0, k = 0;
    document.querySelectorAll("input[name='debet[]']").forEach(i=> d += parseFloat(i.value||0));
    document.querySelectorAll("input[name='kredit[]']").forEach(i=> k += parseFloat(i.value||0));
    document.getElementById('totalDebet').value  = d.toLocaleString('id-ID');
    document.getElementById('totalKredit').value = k.toLocaleString('id-ID');
}
document.addEventListener('input', function(e){
    if (e.target && (e.target.name==='debet[]' || e.target.name==='kredit[]')) hitungTotal();
});
function cekBalance(){
    var totalD=0, totalK=0;
    document.querySelectorAll("input[name='debet[]']").forEach(i=> totalD += parseFloat(i.value||0));
    document.querySelectorAll("input[name='kredit[]']").forEach(i=> totalK += parseFloat(i.value||0));
    if (totalD <= 0 && totalK <= 0) { alert('Nominal masih kosong.'); return false; }
    if (Math.round(totalD*100)/100 !== Math.round(totalK*100)/100) {
        alert('Total Debet dan Total Kredit harus sama!'); return false;
    }
    return true;
}
hitungTotal();
</script>

<?php include "partials/footer.php"; ?>
