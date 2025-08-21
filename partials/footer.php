<!-- Footer -->
<footer class="text-center py-4 mt-5 bg-light border-top" data-aos="fade-up">
  <div class="container">
    <p class="mb-1 fw-bold text-primary">✨ MitraCounting</p>
    <small class="text-muted">
      &copy; <?php echo date("Y"); ?> MitraCounting. Website Akuntansi Mitraku
    </small>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,
    once: true
  });

  // Sidebar Toggle
  document.addEventListener("DOMContentLoaded", function () {
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");
    const icon = sidebarToggle.querySelector("i");

    if (sidebarToggle && sidebar) {
      sidebarToggle.addEventListener("click", function () {
        sidebar.classList.toggle("active");

        // ganti ikon hamburger <-> close
        if (sidebar.classList.contains("active")) {
          icon.classList.remove("fa-bars");
          icon.classList.add("fa-times"); // ikon close
        } else {
          icon.classList.remove("fa-times");
          icon.classList.add("fa-bars"); // ikon hamburger
        }
      });
    }
  });
</script>
</body>
</html>
