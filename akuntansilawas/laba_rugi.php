<?php
require_once("lib/class/class.config.php");
require_once("lib/class/class.crud.php");

if (!isset($_SESSION)) {
    session_start();
}

$config  = new config();
$crud    = new crud();
$conn    = $config->koneksi();

// =====================
// Total Laba Rugi Global
// =====================
$sql_pendapatan = "
    SELECT SUM(j.nominal) as total 
    FROM tb_jurnal j
    JOIN tb_akun a ON j.id_akun = a.id
    WHERE (a.nama_akun LIKE 'PENDAPATAN%' OR a.nama_akun LIKE 'CASHBACK%')
      AND j.tipe='K'
";
$pendapatan = mysqli_fetch_assoc(mysqli_query($conn, $sql_pendapatan))['total'] ?? 0;

$sql_biaya = "
    SELECT SUM(j.nominal) as total 
    FROM tb_jurnal j
    JOIN tb_akun a ON j.id_akun = a.id
    WHERE a.nama_akun LIKE 'BIAYA%' AND j.tipe='D'
";
$biaya = mysqli_fetch_assoc(mysqli_query($conn, $sql_biaya))['total'] ?? 0;

$laba_rugi = $pendapatan - $biaya;

// =====================
// Filter Tanggal Custom
// =====================
$tgl_awal = $_GET['tgl_awal'] ?? null;
$tgl_akhir = $_GET['tgl_akhir'] ?? null;

$laba_harian = [];
$labels_chart = [];
$pendapatan_chart = [];
$biaya_chart = [];
$laba_chart = [];

if ($tgl_awal && $tgl_akhir) {
    // =====================
    // Laba Harian
    // =====================
    $sql_harian = "
        SELECT 
            DATE(STR_TO_DATE(j.tgl, '%Y-%m-%d')) AS tgl,
            SUM(CASE 
                WHEN (a.nama_akun LIKE 'PENDAPATAN%' OR a.nama_akun LIKE 'CASHBACK%') AND j.tipe = 'K' 
                THEN j.nominal ELSE 0 END) AS pendapatan,
            SUM(CASE 
                WHEN a.nama_akun LIKE 'BIAYA%' AND j.tipe = 'D' 
                THEN j.nominal ELSE 0 END) AS biaya
        FROM tb_jurnal j
        JOIN tb_akun a ON j.id_akun = a.id
        WHERE STR_TO_DATE(j.tgl, '%Y-%m-%d') BETWEEN '$tgl_awal' AND '$tgl_akhir'
        GROUP BY DATE(STR_TO_DATE(j.tgl, '%Y-%m-%d'))
        ORDER BY tgl ASC
    ";
    $q_harian = mysqli_query($conn, $sql_harian);

    while ($row = mysqli_fetch_assoc($q_harian)) {
        $tgl = $row['tgl'];
        $p   = $row['pendapatan'] ?? 0;
        $b   = $row['biaya'] ?? 0;
        $l   = $p - $b;

        $laba_harian[] = [
            'tgl' => $tgl,
            'pendapatan' => $p,
            'biaya' => $b,
            'laba' => $l
        ];

        // Data untuk grafik
        $labels_chart[] = date("d M", strtotime($tgl));
        $pendapatan_chart[] = $p;
        $biaya_chart[] = $b;
        $laba_chart[] = $l;
    }
}
?>

<?php include "partials/header.php"; ?>
<?php include "partials/navbar.php"; ?>
<?php include "partials/sidebar.php"; ?>

<!-- AOS CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Tambahkan di header (jika belum ada) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="content">
    <div class="container-fluid">
        <h2 class="text-center mb-4" data-aos="fade-down">
            <i class="fas fa-chart-line text-primary me-2"></i> Laporan Laba Rugi
        </h2>

        <!-- 🔎 Filter Tanggal -->
        <div class="card shadow mb-4" data-aos="fade-up">
            <div class="card-body">
                <form method="get" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label for="tgl_awal" class="form-label">
                            <i class="fas fa-calendar-day text-primary me-1"></i> Tanggal Awal
                        </label>
                        <input type="date" id="tgl_awal" name="tgl_awal" value="<?= $tgl_awal ?>" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="tgl_akhir" class="form-label">
                            <i class="fas fa-calendar-check text-success me-1"></i> Tanggal Akhir
                        </label>
                        <input type="date" id="tgl_akhir" name="tgl_akhir" value="<?= $tgl_akhir ?>" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($tgl_awal && $tgl_akhir): ?>
            <!-- Grafik Laba Harian -->
            <div class="card mb-4 shadow" data-aos="fade-up">
                <div class="card-body">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-chart-area text-info me-2"></i> 
                        Grafik Laba Harian (<?= date("d M Y", strtotime($tgl_awal)) ?> - <?= date("d M Y", strtotime($tgl_akhir)) ?>)
                    </h4>
                    <canvas id="labaChart"></canvas>
                </div>
            </div>

            <!-- Tabel Laba Harian -->
            <div class="card shadow" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body">
                    <h4 class="text-center mb-3">
                        <i class="fas fa-calendar-alt text-warning me-2"></i> Rincian Laba Harian
                    </h4>
                    <table class="table table-bordered table-hover text-center">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-calendar-day"></i> Tanggal</th>
                                <th><i class="fas fa-coins text-success"></i> Pendapatan</th>
                                <th><i class="fas fa-receipt text-danger"></i> Biaya</th>
                                <th><i class="fas fa-balance-scale text-info"></i> Laba / Rugi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($laba_harian) > 0): ?>
                                <?php foreach ($laba_harian as $row): ?>
                                <tr>
                                    <td><?= date("d M Y", strtotime($row['tgl'])) ?></td>
                                    <td>Rp <?= number_format($row['pendapatan'], 0, ",", ".") ?></td>
                                    <td>Rp <?= number_format($row['biaya'], 0, ",", ".") ?></td>
                                    <td class="<?= $row['laba'] >= 0 ? 'text-success fw-bold' : 'text-danger fw-bold' ?>">
                                        Rp <?= number_format(abs($row['laba']), 0, ",", ".") ?> 
                                        <?= $row['laba'] >= 0 ? '<i class="fas fa-arrow-up ms-1"></i>' : '<i class="fas fa-arrow-down ms-1"></i>' ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fas fa-info-circle me-1"></i> Tidak ada data untuk periode ini
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>


<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init();

<?php if ($tgl_awal && $tgl_akhir): ?>
// Chart.js Config
const ctx = document.getElementById('labaChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels_chart) ?>,
        datasets: [
            {
                label: 'Pendapatan',
                data: <?= json_encode($pendapatan_chart) ?>,
                borderColor: 'rgba(54, 162, 235, 1)',
                fill: false
            },
            {
                label: 'Biaya',
                data: <?= json_encode($biaya_chart) ?>,
                borderColor: 'rgba(255, 99, 132, 1)',
                fill: false
            },
            {
                label: 'Laba / Rugi',
                data: <?= json_encode($laba_chart) ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false
            }
        ]
    },
    options: {
        responsive: true,
        animation: {
            duration: 1500,
            easing: 'easeOutBounce'
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
<?php endif; ?>
</script>

<?php include "partials/footer.php"; ?>
