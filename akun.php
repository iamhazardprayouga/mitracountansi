<?php
include "lib/config.php";
if (!isset($_SESSION)) {
    session_start();
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$result = mysqli_query($config->koneksi(), "SELECT COUNT(*) AS total FROM tb_akun");
$row = mysqli_fetch_assoc($result);
$total_data = $row['total'];
$total_page = ceil($total_data / $limit);

$akun = mysqli_query($config->koneksi(), "SELECT * FROM tb_akun LIMIT $start, $limit");
?>

<?php include "partials/header.php"; ?>
<?php include "partials/navbar.php"; ?>
<?php include "partials/sidebar.php"; ?>

<!-- Loading Animation -->
<div id="loading-overlay">
  <div class="spinner"></div>
</div>

<div class="content" style="display:none;" id="page-content">
  <div class="container-fluid py-4">

    <!-- JUDUL -->
    <div class="d-flex align-items-center mb-4" data-aos="fade-right">
      <i class="fa fa-book text-primary me-2" style="font-size: 30px;"></i>
      <h2 class="fw-bold text-dark m-0">Daftar Akun Akuntansi</h2>
    </div>

    <div class="row g-4">
      <!-- Form Tambah Akun -->
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card p-4 h-100">
          <h5 class="fw-bold text-secondary mb-3">Tambah Akun Baru</h5>
          <form method="POST" action="pro_tambah_akun.php">
            <div class="mb-3">
              <label class="form-label fw-semibold">Nomor / Kode Akun</label>
              <input type="text" name="kode" required class="form-control rounded-3 shadow-sm">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Akun</label>
              <input type="text" name="nama_akun" required class="form-control rounded-3 shadow-sm">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Posisi Awal Saldo</label>
              <select name="kategori" class="form-select rounded-3 shadow-sm">
                <option value="HL">Debet</option>
                <option value="HT">Kredit</option>
              </select>
            </div>
            <button type="submit" class="btn btn-finance w-100 py-2 rounded-3">
              <i class="fa fa-plus-circle me-2"></i> Tambah Akun
            </button>
          </form>
        </div>
      </div>

      <!-- Tabel Daftar Akun -->
      <div class="col-md-8" data-aos="fade-up" data-aos-delay="200">
        <div class="card p-4">
          <h5 class="fw-bold text-secondary mb-3">Chart of Accounts</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-gradient text-white text-center">
                <tr>
                  <th>No</th>
                  <th>Nama Akun</th>
                  <th>Kode</th>
                  <th>Posisi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $no = $start + 1;
              while ($data_akun = mysqli_fetch_array($akun)) {
                  $pos = ($data_akun['kategori'] == "HL") ? "Debet" : "Kredit";
                  echo "
                  <tr data-aos='fade-up' data-aos-delay='".($no*50)."'>
                    <td class='text-center'>$no</td>
                    <td class='fw-semibold'>$data_akun[nama_akun]</td>
                    <td class='text-center'>
                      <span class='badge bg-primary px-3'>$data_akun[kode]</span>
                    </td>
                    <td class='text-center'>
                      <span class='badge ".($pos=='Debet'?'bg-success':'bg-danger')."'>$pos</span>
                    </td>
                    <td class='text-center'>
                      <a href='editakun.php?id=$data_akun[id]' class='btn btn-sm btn-outline-warning rounded-3 me-1'>
                        <i class='fa fa-edit'></i>
                      </a>
                      <a href='hapus_akun.php?id=$data_akun[id]' class='btn btn-sm btn-outline-danger rounded-3' onclick=\"return confirm('Hapus akun ini?')\">
                        <i class='fa fa-trash'></i>
                      </a>
                    </td>
                  </tr>";
                  $no++;
              }
              ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <nav data-aos="fade-up" data-aos-delay="300">
            <ul class="pagination justify-content-end mt-3">
              <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page-1 ?>">« Prev</a></li>
              <?php endif; ?>

              <?php for ($i = 1; $i <= $total_page; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                  <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>

              <?php if ($page < $total_page): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page+1 ?>">Next »</a></li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Style Khusus -->
<style>
  body {
    background: #f5f7fa;
    font-family: 'Segoe UI', sans-serif;
  }
  .btn-finance {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    border: none;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  .btn-finance:hover {
    background: linear-gradient(135deg, #00f2fe, #4facfe);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
  }
  .card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    background: #ffffffcc;
    backdrop-filter: blur(8px);
    transition: transform 0.3s ease;
  }
  .card:hover {
    transform: translateY(-3px);
  }
  .table-gradient {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
  }
  .table tbody tr {
    transition: all 0.2s ease;
  }
  .table tbody tr:hover {
    background: rgba(0, 242, 254, 0.08);
    transform: scale(1.01);
  }
  .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    border: none;
    color: white;
  }
  .pagination .page-link {
    border-radius: 8px;
    margin: 0 3px;
    color: #4facfe;
    transition: all 0.2s ease;
  }
  .pagination .page-link:hover {
    background: rgba(79,172,254,0.1);
  }
  /* Loading Overlay */
  #loading-overlay {
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:white;
    display:flex;
    justify-content:center;
    align-items:center;
    z-index:9999;
    transition: opacity 0.4s ease;
  }
  .spinner {
    width: 60px;
    height: 60px;
    border: 5px solid rgba(0,0,0,0.1);
    border-top: 5px solid #4facfe;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  @keyframes spin {
    100% { transform: rotate(360deg); }
  }
</style>

<!-- Script animasi loading + AOS -->
<script>
  window.onload = function() {
    document.getElementById("loading-overlay").style.opacity = "0";
    setTimeout(() => {
      document.getElementById("loading-overlay").style.display = "none";
      document.getElementById("page-content").style.display = "block";
      AOS.init({
        duration: 900,
        easing: 'ease-in-out',
        once: true
      });
    }, 300);
  }
</script>

<!-- AOS Library -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

<?php include "partials/footer.php"; ?>
