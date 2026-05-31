<?php
// Wajib ditaruh di baris 1 sebelum kode lainnya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SATPAM: Menghadang user yang belum login email-nya
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] == '') {
    echo "<script>
        alert('Anda harus login menggunakan akun tamu terlebih dahulu untuk memesan kamar!');
        window.location.href = 'login.php'; 
    </script>";
    exit();
}

// 1. KONEKSI LANGSUNG
$koneksi = mysqli_connect("localhost", "root", "", "db_hotel");

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// 2. Memanggil navbar bawaan user
include '../includes/navbar.php';
?>
<div class="main-content">
    <div class="booking-container">
        <h2>Formulir Pemesanan Kamar</h2>
        <p class="booking-subtitle">Silakan isi data diri dan pilih tipe kamar yang Anda inginkan.</p>
        
        <form action="pembayaran.php" method="POST" class="booking-form">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama sesuai KTP" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" placeholder="contoh@email.com" required>
                </div>
                <div class="form-group">
                    <label for="nohp">Nomor Telepon / WA</label>
                    <input type="tel" id="nohp" name="nohp" placeholder="08xxxxxxxxxx" required>
                </div>
            </div>

            <div class="form-group">
                <label for="tipe_kamar">Pilih Tipe Kamar</label>
                <select id="tipe_kamar" name="id_tipe" required>
                    <option value="" disabled selected>-- Pilih tipe kamar yang tersedia --</option>
                    <?php
                    // SQL menghitung sisa kamar berstatus 'Tersedia'
                    $sql_tipe = "SELECT tipe_kamar.*, 
                                COUNT(kamar.id_kamar) AS sisa_stok 
                                FROM tipe_kamar 
                                LEFT JOIN kamar ON tipe_kamar.id_tipe = kamar.id_tipe AND kamar.status_kamar = 'Tersedia' 
                                GROUP BY tipe_kamar.id_tipe";
                                
                    $q_tipe = mysqli_query($koneksi, $sql_tipe);
                    while ($row = mysqli_fetch_array($q_tipe)) {
                        
                        // JIKA KAMAR HABIS (0): Tampilkan info [PENUH] di dropdown dan buat tidak bisa dipilih (disabled)
                        if ($row['sisa_stok'] <= 0) {
                            echo "<option value='' disabled>".$row['nama_tipe']." (Rp ".number_format($row['harga'], 0, ',', '.')." / malam) - [PENUH]</option>";
                        } 
                        // JIKA KAMAR MASIH ADA: Hanya tampilkan nama dan harga (Sisa stok disembunyikan)
                        else {
                            echo "<option value='".$row['id_tipe']."'>".$row['nama_tipe']." (Rp ".number_format($row['harga'], 0, ',', '.')." / malam)</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="checkin">Tanggal Check-In</label>
                    <input type="date" id="checkin" name="checkin" required>
                </div>
                <div class="form-group">
                    <label for="checkout">Tanggal Check-Out</label>
                    <input type="date" id="checkout" name="checkout" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="proses_booking" class="btn-konfirmasi">Lanjut ke Pembayaran</button>
            </div>
        </form>
    </div>
</div>

<?php
include '../includes/footer.php';
?>