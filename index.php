<?php include "partials/header.php"; ?>
<?php include "partials/navbar.php"; ?>
<?php include "partials/sidebar.php"; ?>

<style>
  /* Efek Hover Card */
  .dashboard-card {
    border-radius: 20px;
    transition: all 0.4s ease-in-out;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.85);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
  }
  .dashboard-card:hover {
    transform: translateY(-10px) scale(1.03);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
  }

  /* Ikon Animasi Glow */
  .dashboard-card i {
    transition: all 0.3s ease-in-out;
    filter: drop-shadow(0 0 5px rgba(0,0,0,0.2));
  }
  .dashboard-card:hover i {
    transform: rotate(15deg) scale(1.2);
    filter: drop-shadow(0 0 12px rgba(13,110,253,0.5));
  }

  /* Animasi teks */
  .dashboard-card h5 {
    font-weight: bold;
    transition: color 0.3s ease-in-out;
  }
  .dashboard-card:hover h5 {
    color: #0d6efd;
  }

  /* Background Gradien */
  .content {
    background: linear-gradient(135deg, #f3f6ff, #e3ebf7);
    min-height: 100vh;
    padding: 40px 25px;
  }

  /* Gradient text untuk judul */
  .text-gradient {
    background: linear-gradient(90deg, #0d6efd, #6610f2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  /* Samakan ukuran card */
  .card-equal {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  /* Grafik Box */
  .info-section {
    margin-top: 60px;
  }
</style>

<div class="content">
  <div class="container-fluid">
    <!-- Bagian Selamat Datang -->
    <div class="text-center my-5" data-aos="fade-up" data-aos-duration="1000">
      <h1 class="fw-bold text-gradient mb-3">✨ Selamat Datang di <span class="text-primary">MitraCounting</span></h1>
      <p class="lead text-muted">Sistem Informasi Akuntansi modern untuk mengelola keuangan dengan lebih mudah, cepat, dan akurat.</p>
    </div>

   <!-- Dashboard Card -->
<div class="row g-4">
  <!-- Card Akun -->
  <div class="col-md-4" data-aos="zoom-in" data-aos-duration="1000">
    <a href="akun.php" class="text-decoration-none">
      <div class="card p-4 text-center dashboard-card card-equal h-100">
        <i class="fa fa-book fa-3x mb-3 text-primary"></i>
        <h5 class="text-dark">Akun</h5>
        <p class="text-muted">Kelola daftar akun akuntansi dengan mudah.</p>
      </div>
    </a>
  </div>

  <!-- Card Jurnal -->
  <div class="col-md-4" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="150">
    <a href="jurnalumum.php" class="text-decoration-none">
      <div class="card p-4 text-center dashboard-card card-equal h-100">
        <i class="fa fa-file-alt fa-3x mb-3 text-success"></i>
        <h5 class="text-dark">Jurnal Umum</h5>
        <p class="text-muted">Catat transaksi harian perusahaan.</p>
      </div>
    </a>
  </div>

 <!-- Card Neraca -->
<div class="col-md-4" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="300">
  <a href="neraca.php" class="text-decoration-none">
    <div class="card p-4 text-center dashboard-card card-equal h-100">
      <i class="fa fa-balance-scale fa-3x mb-3 text-danger"></i>
      <h5 class="text-dark">Neraca</h5>
      <p class="text-muted">Lihat posisi keuangan perusahaan dengan jelas.</p>
    </div>
  </a>
</div>



    <!-- Tambahan Informasi / Grafik -->
    <div class="info-section">
      <div class="row">
        <!-- Info Singkat -->
        <div class="col-md-6" data-aos="fade-right" data-aos-duration="1000">
          <div class="card p-4 dashboard-card">
            <h5 class="fw-bold mb-3">📊 Informasi Keuangan</h5>
            <p class="text-muted">MitraCounting membantu Anda memantau kondisi keuangan perusahaan dengan akurat. 
            Data transaksi, laporan, dan analisis keuangan selalu up-to-date.</p>
          </div>
        </div>

        <!-- Grafik -->
        <div class="col-md-6" data-aos="fade-left" data-aos-duration="1000">
          <div class="card p-4 dashboard-card">
            <h5 class="fw-bold mb-3">📈 Grafik Transaksi</h5>
            <canvas id="grafikTransaksi" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('grafikTransaksi').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
      datasets: [{
        label: 'Total Transaksi',
        data: [120, 190, 150, 200, 250, 300],
        borderColor: '#0d6efd',
        backgroundColor: 'rgba(13, 110, 253, 0.15)',
        pointBackgroundColor: '#0d6efd',
        pointBorderColor: '#fff',
        pointHoverRadius: 6,
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { 
          display: true,
          labels: { color: '#333', font: { weight: 'bold' } }
        }
      },
      scales: {
        x: { grid: { color: 'rgba(0,0,0,0.05)' } },
        y: { grid: { color: 'rgba(0,0,0,0.05)' }, beginAtZero: true }
      }
    }
  });
</script>

<?php include "partials/footer.php"; ?>
