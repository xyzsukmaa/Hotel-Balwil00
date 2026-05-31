<?php
// 1. PASANG SATPAM DI BARIS PALING ATAS
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menghadang user yang belum login email-nya
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] == '') {
    echo "<script>
        alert('Anda harus login menggunakan akun tamu terlebih dahulu untuk melihat riwayat pemesanan!');
        window.location.href = 'login.php'; 
    </script>";
    exit();
}

// 2. KONEKSI LANGSUNG KE DATABASE
$koneksi = mysqli_connect("localhost", "root", "", "db_hotel");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil ID User yang sedang login dari session
$id_user_login = $_SESSION['user_id'];

// Memanggil navigasi dari folder includes
include '../includes/navbar.php';
?>

<div class="main-content">
    <h2 class="history-title">Riwayat Pemesanan Kamar</h2>
    <p class="history-subtitle">Pantau seluruh status reservasi dan riwayat menginap Anda di Balwil Grand Hotel.</p>

    <div class="history-container">
        <div class="table-responsive">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>ID Booking</th>
                        <th>Tipe Kamar</th>
                        <th>Tanggal Menginap</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // FIXED QUERY: Sekarang ditambahkan kondisi WHERE agar hanya mengambil data milik user yang sedang login
                    $sql_riwayat = "SELECT booking.*, tipe_kamar.nama_tipe, kamar.nomor_kamar, tipe_kamar.id_tipe, 
                                           pembayaran.status_bayar, pembayaran.metode
                                    FROM booking
                                    INNER JOIN kamar ON booking.id_kamar = kamar.id_kamar
                                    INNER JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe
                                    LEFT JOIN pembayaran ON booking.id_booking = pembayaran.id_booking
                                    WHERE booking.id_user = '$id_user_login'
                                    ORDER BY booking.id_booking DESC";

                    $q_riwayat = mysqli_query($koneksi, $sql_riwayat);

                    if (mysqli_num_rows($q_riwayat) == 0) {
                        echo "<tr><td colspan='6' style='text-align:center; padding: 20px; color:#888;'>Belum ada riwayat pemesanan kamar.</td></tr>";
                    }

                    while ($row = mysqli_fetch_array($q_riwayat)) {
                        $checkin_format  = date('d M Y', strtotime($row['checkin']));
                        $checkout_format = date('d M Y', strtotime($row['checkout']));
                        
                        // Validasi status berdasarkan tabel pembayaran admin
                        $status_bayar   = $row['status_bayar'] ?? 'Belum Lunas';
                        $status_booking = $row['status'];
                    ?>
                        <tr>
                            <td><strong>#BKG-<?php echo str_pad($row['id_booking'], 3, '0', STR_PAD_LEFT); ?></strong></td>
                            <td><?php echo $row['nama_tipe']; ?> (No. <?php echo $row['nomor_kamar']; ?>)</td>
                            <td>
                                <span class="date-text">📅 <?php echo $checkin_format; ?></span>
                                <small class="date-sep">s/d</small>
                                <span class="date-text"><?php echo $checkout_format; ?></span>
                            </td>
                            <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <?php 
                                // Penentuan Badge Status yang Akurat
                                if ($status_bayar == 'Lunas' || $status_booking == 'Lunas') {
                                    echo '<span class="badge badge-lunas">Lunas</span>';
                                } elseif ($status_booking == 'Menunggu Verifikasi') {
                                    echo '<span class="badge" style="background-color: #f59e0b; color: white; padding: 5px 10px; border-radius: 4px;">Menunggu Verifikasi</span>';
                                } else {
                                    echo '<span class="badge badge-belum">Belum Lunas</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                // LOGIKA AKSI: Selama status bayar di admin BELUM LUNAS, paksa user untuk Bayar!
                                if ($status_bayar == 'Belum Lunas' && $status_booking != 'Menunggu Verifikasi') : 
                                ?>
                                    <form action="pembayaran.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id_booking" value="<?php echo $row['id_booking']; ?>">
                                        <input type="hidden" name="proses_booking" value="1">
                                        <input type="hidden" name="id_tipe" value="<?php echo $row['id_tipe']; ?>">
                                        <input type="hidden" name="checkin" value="<?php echo $row['checkin']; ?>">
                                        <input type="hidden" name="checkout" value="<?php echo $row['checkout']; ?>">
                                        
                                        <button type="submit" style="background: #e63946; color: white; border: none; padding: 6px 12px; cursor: pointer; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                            💳 Bayar
                                        </button>
                                    </form>
                                <?php elseif ($status_booking == 'Menunggu Verifikasi') : ?>
                                    <span style="color: #f59e0b; font-size: 12px; font-weight: bold;">⏳ Proses Mengecek</span>
                                <?php else : ?>
                                    <span style="color: #2a9d8f; font-weight: bold; font-size: 12px;">✔ Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php 
                    } 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>