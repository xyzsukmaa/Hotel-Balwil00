<?php
// Wajib ditaruh di baris paling atas (Baris 1)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SATPAM: Menghadang user yang belum login email-nya
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] == '') {
    echo "<script>
        alert('Anda harus login menggunakan akun tamu terlebih dahulu untuk mengakses halaman pembayaran!');
        window.location.href = 'login.php'; 
    </script>";
    exit();
}

// 1. KONEKSI LANGSUNG
$koneksi = mysqli_connect("localhost", "root", "", "db_hotel");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

include '../includes/navbar.php';

// Ambil ID User dari session login yang aktif
$id_user_login = $_SESSION['user_id'];

// Ambil ID Booking jika dilempar dari halaman riwayat
$id_booking_param = isset($_POST['id_booking']) ? mysqli_real_escape_string($koneksi, $_POST['id_booking']) : '';

// 2. CEK LOGIKA ASAL USUL PENGUNJUNG
if (isset($_POST['proses_booking']) && empty($id_booking_param)) {
    
    // ==========================================
    // JALUR A: KONDISI JIKA DATANG DARI booking.php (Proses Transaksi Baru)
    // ==========================================
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $nohp     = mysqli_real_escape_string($koneksi, $_POST['nohp']);
    $id_tipe  = mysqli_real_escape_string($koneksi, $_POST['id_tipe']);
    $checkin  = mysqli_real_escape_string($koneksi, $_POST['checkin']);
    $checkout = mysqli_real_escape_string($koneksi, $_POST['checkout']);

    // Hitung malam
    $tgl1 = new DateTime($checkin);
    $tgl2 = new DateTime($checkout);
    $durasi = $tgl1->diff($tgl2)->days;
    if ($durasi <= 0) { $durasi = 1; }

    // Cari kamar kosong
    $q_kamar = mysqli_query($koneksi, "SELECT id_kamar, nomor_kamar FROM kamar WHERE id_tipe = '$id_tipe' AND status_kamar = 'TERSEDIA' LIMIT 1");
    $data_kamar = mysqli_fetch_array($q_kamar);

    if ($data_kamar) {
        $id_kamar_dipilih = $data_kamar['id_kamar'];
        $nomor_kamar_tampil = $data_kamar['nomor_kamar'];

        $q_tipe = mysqli_query($koneksi, "SELECT nama_tipe, harga FROM tipe_kamar WHERE id_tipe = '$id_tipe'");
        $data_tipe = mysqli_fetch_array($q_tipe);
        
        $nama_tipe_kamar = $data_tipe['nama_tipe'];
        $harga_per_malam = $data_tipe['harga'];
        $total_awal = $harga_per_malam * $durasi;

        // PERBAIKAN: Menggunakan '$id_user_login' secara dinamis, bukan angka '1' lagi
        $sql_insert = "INSERT INTO booking (id_user, id_kamar, checkin, checkout, total_harga, status) 
                       VALUES ('$id_user_login', '$id_kamar_dipilih', '$checkin', '$checkout', '$total_awal', 'Belum Bayar')";
        
        if (mysqli_query($koneksi, $sql_insert)) {
            $id_booking_baru = mysqli_insert_id($koneksi);
            // Kunci status kamar fisik jadi penuh
            mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'PENUH' WHERE id_kamar = '$id_kamar_dipilih'");
        }
    } else {
        echo "<script>alert('Maaf, Kamar mendadak penuh!'); window.location='booking.php';</script>";
        exit;
    }

} elseif (!empty($id_booking_param)) {

    // ==========================================
    // JALUR B: KONDISI JIKA DATANG DARI riwayat.php (Melanjutkan Transaksi Lama)
    // ==========================================
    $id_booking_baru = $id_booking_param;

    // Tarik data lama dari database agar tidak melakukan INSERT ganda
    $sql_ambil_data = "SELECT booking.*, tipe_kamar.nama_tipe, tipe_kamar.harga, kamar.nomor_kamar 
                       FROM booking 
                       INNER JOIN kamar ON booking.id_kamar = kamar.id_kamar
                       INNER JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe
                       WHERE booking.id_booking = '$id_booking_baru'";
    
    $q_ambil = mysqli_query($koneksi, $sql_ambil_data);
    
    if ($q_ambil && mysqli_num_rows($q_ambil) > 0) {
        $data_lama = mysqli_fetch_array($q_ambil);
        
        $nama_tipe_kamar    = $data_lama['nama_tipe'];
        $nomor_kamar_tampil = $data_lama['nomor_kamar'];
        $total_awal         = $data_lama['total_harga'];
        
        // Hitung ulang durasi malam untuk tampilan struk
        $tgl1 = new DateTime($data_lama['checkin']);
        $tgl2 = new DateTime($data_lama['checkout']);
        $durasi = $tgl1->diff($tgl2)->days;
        if ($durasi <= 0) { $durasi = 1; }
    } else {
        echo "<script>alert('Data Booking tidak ditemukan!'); window.location='riwayat.php';</script>";
        exit;
    }

} else {
    // BACKUP SIMULASI jika diakses tanpa form post
    $nama_tipe_kamar = "Superior Room";
    $nomor_kamar_tampil = "101";
    $durasi = 1;
    $total_awal = 850000;
    $id_booking_baru = 2;
}
?>

<div class="main-content">
    <div class="main-content halaman-pembayaran">
    <div class="payment-container">
        <h2>Selesaikan Pembayaran Anda</h2>
        <p class="payment-subtitle">Silakan periksa detail pesanan, gunakan kode promo jika ada, dan pilih metode pembayaran.</p>

        <div class="invoice-box">
            <div class="invoice-row">
                <span>Tipe Kamar Selected:</span>
                <strong><?php echo $nama_tipe_kamar; ?> (No. <?php echo $nomor_kamar_tampil; ?> / <?php echo $durasi; ?> Malam)</strong>
            </div>
            <div class="invoice-row">
                <span>Harga Kamar:</span>
                <span>Rp <?php echo number_format($total_awal, 0, ',', '.'); ?></span>
            </div>
            <div class="invoice-row discount-row" id="tampilan-diskon" style="display: none; color: #e63946;">
                <span>Potongan Promo (<span id="persen-diskon">0</span>%):</span>
                <span>- Rp <span id="nominal-diskon">0</span></span>
            </div>
            <hr>
            <div class="invoice-row total-row">
                <span>Total yang Harus Dibayar:</span>
                <span id="total-akhir" style="color: #0b132b; font-size: 20px; font-weight: 700;">Rp <?php echo number_format($total_awal, 0, ',', '.'); ?></span>
            </div>
        </div>

        <form action="proses_pembayaran.php" method="POST" class="payment-form">
            <input type="hidden" name="id_booking" value="<?php echo $id_booking_baru; ?>"> 
            <input type="hidden" name="total_akhir_input" id="total_akhir_input" value="<?php echo $total_awal; ?>">

            <div class="form-group">
                <label for="kode_promo">Punya Kode Promo / Voucher?</label>
                <div class="promo-input-group">
                    <input type="text" id="kode_promo" placeholder="Contoh: LIBURANHAPPY / BALWILHEMAT">
                    <button type="button" id="btn-klaim-promo">Terapkan</button>
                </div>
                <small id="promo-message" style="display:block; margin-top: 5px; font-weight: 600;"></small>
                <input type="hidden" name="promo_terpakai" id="promo_terpakai" value="">
            </div>

            <div class="form-group">
                <label>Pilih Metode Pembayaran</label>
                <div class="payment-methods">
                    <label class="method-option">
                        <input type="radio" name="metode" value="Transfer Bank" required checked>
                        <div class="method-box">
                            <span class="icon">🏦</span>
                            <span>Transfer Bank (BCA / Mandiri)</span>
                        </div>
                    </label>

                    <label class="method-option">
                        <input type="radio" name="metode" value="E-Wallet">
                        <div class="method-box">
                            <span class="icon">📱</span>
                            <span>E-Wallet (OVO, GoPay, Dana)</span>
                        </div>
                    </label>

                    <label class="method-option">
                        <input type="radio" name="metode" value="Kartu Kredit">
                        <div class="method-box">
                            <span class="icon">💳</span>
                            <span>Kartu Kredit / Debit</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="bayar_sekarang" class="btn-bayar-sekarang">Proses & Bayar Sekarang</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('btn-klaim-promo').addEventListener('click', function() {
    var kode = document.getElementById('kode_promo').value.toUpperCase().trim();
    var message = document.getElementById('promo-message');
    var hargaAwal = <?php echo $total_awal; ?>;
    
    var daftarPromo = {
        <?php
        $q_promo = mysqli_query($koneksi, "SELECT kode_promo, diskon FROM promo");
        if($q_promo) {
            while ($p = mysqli_fetch_array($q_promo)) {
                echo "'" . strtoupper($p['kode_promo']) . "': " . $p['diskon'] . ", ";
            }
        }
        ?>
    };

    if (daftarPromo[kode]) {
        var diskonPersen = daftarPromo[kode];
        var potongan = (diskonPersen / 100) * hargaAwal;
        var hargaAkhir = hargaAwal - potongan;

        document.getElementById('tampilan-diskon').style.display = 'flex';
        document.getElementById('persen-diskon').innerText = diskonPersen;
        document.getElementById('nominal-diskon').innerText = potongan.toLocaleString('id-ID');
        document.getElementById('total-akhir').innerText = 'Rp ' + hargaAkhir.toLocaleString('id-ID');
        
        document.getElementById('promo_terpakai').value = kode;
        document.getElementById('total_akhir_input').value = hargaAkhir;

        message.style.color = '#2a9d8f';
        message.innerText = '🎉 Selamat! Kode promo ' + kode + ' berhasil dipasang (Diskon ' + diskonPersen + '%).';
    } else {
        message.style.color = '#e63946';
        message.innerText = '❌ Maaf, kode promo tidak valid atau sudah kedaluwarsa.';
    }
});
</script>

<?php
include '../includes/footer.php';
?>