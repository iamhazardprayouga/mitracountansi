<?php
include "lib/config.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php include "partials/header.php"; ?>
<?php include "partials/navbar.php"; ?>
<?php include "partials/sidebar.php"; ?>

<!-- Loading Animation -->
<div id="loading-overlay">
  <div class="spinner"></div>
</div>

<div class="content" style="display:none;" id="page-content">
  <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center">

    <div class="card p-5 shadow-lg" data-aos="zoom-in" style="max-width:600px; width:100%;">
      <div class="text-center mb-4">
        <i class="fa fa-book text-primary" style="font-size:50px;"></i>
        <h3 class="fw-bold text-dark mt-3">Tambah Akun Baru</h3>
        <p class="text-muted">Isi form di bawah untuk menambahkan akun baru ke dalam Chart of Accounts.</p>
      </div>

      <form method="POST" action="pro_tambah_akun.php" autocomplete="off">
        <!-- Nomor / Kode Akun -->
        <div class="mb-3" data-aos="fade-right" data-aos-delay="100">
          <label class="form-label fw-semibold">Nomor / Kode Akun</label>
          <input type="text" name="kode" required 
                 class="form-control rounded-3 shadow-sm" 
                 placeholder="Contoh: 101">
        </div>

        <!-- Nama Akun -->
        <div class="mb-3" data-aos="fade-left" data-aos-delay="200">
          <label class="form-label fw-semibold">Nama Akun</label>
          <input type="text" name="nama_akun" required 
                 class="form-control rounded-3 shadow-sm" 
                 placeholder="Contoh: Kas Besar">
        </div>

        <!-- Posisi Awal Saldo -->
        <div class="mb-4" data-aos="fade-up" data-aos-delay="300">
          <label class="form-label fw-semibold">Posisi Awal Saldo</label>
          <select name="kategori" class="form-select rounded-3 shadow-sm" required>
            <option value="" disabled selected>-- Pilih Posisi Saldo --</option>
            <option value="HL">Debet</option>
            <option value="HT">Kredit</option>
          </select>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between">
          <a href="akun.php" class="btn btn-outline-secondary rounded-3 px-4">
            <i class="fa fa-arrow-left me-2"></i> Kembali
          </a>
          <button type="submit" class="btn btn-finance rounded-3 px-4">
            <i class="fa fa-check-circle me-2"></i> Simpan Akun
          </button>
        </div>
      </form>
    </div>

  </div>
</div>

<!-- Style tambahan -->
<style>
  body {
    background: linear-gradient(135deg,#eef2f3,#ffffff);
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
    border-radius: 20px;
    backdrop-filter: blur(8px);
    background: #ffffffcc;
    transition: transform 0.3s ease;
  }
  .card:hover {
    transform: translateY(-3px);
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

<!-- Script loading + AOS -->
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

<?php include "partials/footer.php"; ?>
