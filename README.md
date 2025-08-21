# MitraAkuntansi

**MitraAkuntansi** adalah aplikasi berbasis website untuk membantu pencatatan dan pengelolaan akuntansi secara sederhana dan praktis.  
Aplikasi ini cocok digunakan oleh UMKM maupun organisasi kecil yang membutuhkan sistem akuntansi dasar.

---

## 📌 Fitur Utama
- 📒 **Jurnal Umum** — mencatat semua transaksi keuangan harian  
- 📘 **Buku Besar** — menampilkan mutasi akun-akun secara detail  
- 📊 **Neraca Saldo** — membantu menyusun saldo akhir setiap akun  
- 🧾 **Akun** — pengelolaan daftar akun (chart of accounts)  
- 💰 **Laba Rugi** — laporan keuangan sederhana untuk melihat profit atau kerugian  

---

## ⚙️ Cara Memasang Aplikasi

```bash
# 1. Clone Repository
git clone https://github.com/dwiprayoga10/mitracountansi.git

# 2. Pindahkan ke Folder XAMPP
# Salin folder hasil clone ke:
C:/xampp/htdocs/mitracountansi

# 3. Import Database
# - Buka phpMyAdmin
# - Buat database baru, misalnya: akuntansi
# - Import file sia.sql yang ada di repo ini

# 4. Konfigurasi Koneksi Database
# Edit file lib/config.php lalu sesuaikan:
$host = "localhost";
$user = "root";
$pass = "";
$db   = "akuntansi";

# 5. Jalankan Aplikasi
# Buka browser dan akses:
http://localhost/mitracountansi

