<?php include("inc_header.php") ?>
<?php
$sukses = "";
// Amankan input pencarian
$katakunci = (isset($_GET['katakunci'])) ? mysqli_real_escape_string($koneksi, $_GET['katakunci']) : "";
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// hapus data booking
if ($op == 'delete') {
    // Amankan parameter ID
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // opsional: kalau mau hapus booking, hapus data pembayaran dan review terkait dulu biar gak error foreign key
    @mysqli_query($koneksi, "delete from pembayaran where id_booking = '$id'");
    @mysqli_query($koneksi, "delete from review where id_booking = '$id'");
    
    $sql1   = "delete from booking where id_booking = '$id'";
    $q1     = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses     = "Berhasil menghapus data pesanan";
    }
}
?>

<style>
    /* Menghilangkan paksa batasan lebar dari template bawaan jika ada */
    .container, .container-fluid {
        max-width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    /* Membuat latar belakang luar menjadi putih bersih */
    body, html {
        background-color: #ffffff !important;
    }

    /* Container Utama - FULL WIDTH Melengkung Mewah Senada Dashboard */
    .booking-bg-container {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        color: #e2e8f0;
        margin: 20px auto; 
        width: 96%; 
        max-width: 1400px; 
        padding: 40px;
        border-radius: 16px !important; 
        min-height: calc(100vh - 140px);
        background: linear-gradient(rgba(10, 17, 36, 0.78), rgba(20, 30, 55, 0.88)), url('../assets/kolam.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    /* Judul Halaman Bergaya Emas Dashboard */
    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #cbb279; 
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 5px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.6);
    }
    
    .page-subtitle {
        font-size: 14px;
        color: #94a3b8;
        margin-bottom: 30px;
    }

    /* Bar Pencarian (Efek Glassmorphism Kaca Gelap) */
    .action-bar {
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 20px;
        border-radius: 10px !important; 
        border: 1px solid rgba(203, 178, 121, 0.15);
        margin-top: 20px;
        margin-bottom: 25px;
    }

    /* Form Input Kendali */
    .input-search-custom {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(203, 178, 121, 0.25) !important;
        color: #ffffff !important;
        border-radius: 6px !important;
        font-size: 14px;
    }
    .input-search-custom::placeholder {
        color: #64748b;
    }
    .input-search-custom:focus {
        border-color: #cbb279 !important;
        box-shadow: 0 0 8px rgba(203, 178, 121, 0.3) !important;
    }

    /* Tombol Utama Emas */
    .btn-gold {
        background: linear-gradient(135deg, #cbb279, #b39a5f) !important;
        color: #0f172a !important;
        font-weight: 600;
        border: none !important;
        border-radius: 6px !important;
        padding: 8px 25px;
        transition: all 0.3s ease;
    }
    .btn-gold:hover {
        background: linear-gradient(135deg, #e1ca94, #cbb279) !important;
        box-shadow: 0 4px 15px rgba(203, 178, 121, 0.35);
        color: #0f172a !important;
    }

    /* Kotak Wadah Tabel (Kaca Transparan Mac) */
    .table-responsive-box {
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 12px !important;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 15px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    /* Gaya Tabel */
    .table-booking-custom {
        color: #e2e8f0 !important;
        margin-bottom: 0;
        vertical-align: middle;
    }
    .table-booking-custom thead th {
        background-color: rgba(15, 23, 42, 0.4) !important;
        color: #cbb279 !important;
        border-bottom: 2px solid rgba(203, 178, 121, 0.3) !important;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 1px;
        padding: 15px 10px;
    }
    .table-booking-custom tbody td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        padding: 15px 10px;
        font-size: 14px;
        background: transparent !important;
        color: #cbd5e1;
    }
    .table-booking-custom tbody tr:hover td {
        background: rgba(203, 178, 121, 0.05) !important;
        color: #ffffff;
    }

    /* Keterangan nomor kamar kecil */
    .room-sub-text {
        color: #94a3b8 !important;
        font-size: 12px;
        display: block;
        margin-top: 2px;
    }

    /* ==========================================================================
       GAYA LENCANA STATUS BOOKING (WARNA MEWAH, FLAT, TEGAS, 2 WARNA)
       ========================================================================== */
    .badge-status {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 4px !important;
        letter-spacing: 0.5px;
        display: inline-block;
        border: none !important;
    }
    /* Confirmed: Hijau Toska Neon Dashboard */
    .status-confirmed { 
        background-color: #00e5bc !important; 
        color: #0a1124 !important; 
    }
    /* Pending: Kuning Amber/Emas Petunjuk */
    .status-pending { 
        background-color: #ffb830 !important; 
        color: #0a1124 !important; 
    }
    /* Canceled: Pink Merah Indikator */
    .status-canceled { 
        background-color: #ff4a6b !important; 
        color: #ffffff !important; 
    }
    /* Status Lainnya: Abu-abu Muted Luxury */
    .status-default {
        background-color: #475569 !important;
        color: #ffffff !important;
    }

    /* ==========================================================================
       TOMBOL AKSI (NAVY LUXURY STYLE)
       ========================================================================== */
    .btn-action {
        padding: 6px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 6px !important;
        display: inline-block;
        transition: all 0.2s ease;
        letter-spacing: 0.5px;
    }
    /* Ubah Status (Edit) */
    .btn-edit-custom {
        background-color: #1e293b !important; 
        color: #38bdf8 !important; 
        border: 1px solid rgba(56, 189, 248, 0.3) !important; 
    }
    .btn-edit-custom:hover {
        background-color: #cbb279 !important;
        color: #0f172a !important;
        border-color: #cbb279 !important;
    }
    /* Hapus (Delete) */
    .btn-delete-custom {
        background-color: #27161a !important; 
        color: #f43f5e !important; 
        border: 1px solid rgba(244, 63, 94, 0.3) !important; 
    }
    .btn-delete-custom:hover {
        background-color: #f43f5e !important;
        color: #ffffff !important; 
        border-color: #f43f5e !important;
    }

    /* Navigasi Halaman */
    .pagination-custom {
        margin-top: 20px;
    }
    .pagination-custom .page-link {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(203, 178, 121, 0.2) !important;
        color: #94a3b8 !important;
        border-radius: 4px !important;
        padding: 8px 16px;
    }
    .pagination-custom .page-link:hover {
        background: #cbb279 !important;
        color: #0f172a !important;
    }
    .pagination-custom .page-item.active .page-link {
        background: #cbb279 !important;
        border-color: #cbb279 !important;
        color: #0f172a !important;
    }
</style>

<div class="booking-bg-container">

    <h1 class="page-title">| Data Booking Hotel</h1>
    <div class="page-subtitle">Selamat datang kembali di Panel Utama Administrasi Reservasi Kamar.</div>

    <?php if ($sukses) { ?>
        <div class="alert alert-success" style="border-radius: 6px; background: rgba(19, 219, 182, 0.15); color: #13dbb6; border-color: rgba(19, 219, 182, 0.3); margin-top: 15px;">
            🛈 <?php echo $sukses ?>
        </div>
    <?php } ?>

    <div class="action-bar">
        <form class="row g-3 align-items-center" method="get">
            <div class="col-auto style-width" style="min-width: 320px;">
                <input type="text" class="form-control input-search-custom" placeholder="Cari Nama Tamu atau Status Transaksi..." name="katakunci" value="<?php echo $katakunci ?>" />
            </div>
            <div class="col-auto">
                <input type="submit" name="cari" value="Cari Pesanan" class="btn btn-gold" />
            </div>
        </form>
    </div>

    <div class="table-responsive-box table-responsive">
        <table class="table table-booking-custom">
            <thead>
                <tr>
                    <th class="col-1" style="text-align: center;">#</th>
                    <th>Nama Tamu</th>
                    <th>Kamar</th>
                    <th>Tgl Check-in</th>
                    <th>Tgl Check-out</th>
                    <th>Total Harga</th>
                    <th style="text-align: center; width: 140px;">Status</th>
                    <th class="col-2" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqltambahan = "";
                $per_halaman = 10;
                
                if ($katakunci != '') {
                    $array_katakunci = explode(" ", $katakunci);
                    for ($x = 0; $x < count($array_katakunci); $x++) {
                        $sqlcari[] = "(user.nama like '%" . $array_katakunci[$x] . "%' or booking.status like '%" . $array_katakunci[$x] . "%')";
                    }
                    $sqltambahan    = " where " . implode(" or ", $sqlcari);
                }
                
                $sql1   = "select booking.*, user.nama, kamar.nomor_kamar, tipe_kamar.nama_tipe 
                           from booking 
                           left join user on booking.id_user = user.id_user 
                           left join kamar on booking.id_kamar = kamar.id_kamar 
                           left join tipe_kamar on kamar.id_tipe = tipe_kamar.id_tipe 
                           $sqltambahan";
                           
                $page   = isset($_GET['page'])?(int)$_GET['page']:1;
                $mulai  = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
                $q1     = mysqli_query($koneksi,$sql1);
                $total  = mysqli_num_rows($q1);
                $pages  = ceil($total / $per_halaman);
                $nomor  = $mulai + 1;
                
                $sql1   = $sql1." order by booking.id_booking desc limit $mulai,$per_halaman";
                $q1     = mysqli_query($koneksi, $sql1);
              
                if (mysqli_num_rows($q1) == 0) {
                    echo "<tr><td colspan='8' class='text-center text-muted py-4'>Data reservasi tidak ditemukan.</td></tr>";
                }

                while ($r1 = mysqli_fetch_array($q1)) {
                ?>
                    <tr>
                        <td style="text-align: center; color: #64748b;"><?php echo $nomor++ ?></td>
                        <td><b style="color: #ffffff;"><?php echo $r1['nama'] ?></b></td>
                        <td>
                            <span style="font-weight: 600;"><?php echo $r1['nama_tipe'] ?></span>
                            <span class="room-sub-text">No. Kamar: <?php echo $r1['nomor_kamar'] ?? 'Belum diset' ?></span>
                        </td>
                        <td><?php echo $r1['checkin'] ?></td>
                        <td><?php echo $r1['checkout'] ?></td>
                        <td style="color: #cbb279; font-weight: 600;">Rp <?php echo number_format($r1['total_harga'], 0, ',', '.') ?></td>
                        <td style="text-align: center;">
                            <?php 
                            if($r1['status'] == 'Confirmed'){
                                echo '<span class="badge-status status-confirmed">Confirmed</span>';
                            } elseif($r1['status'] == 'Pending') {
                                echo '<span class="badge-status status-pending">Pending</span>';
                            } elseif($r1['status'] == 'Canceled') {
                                echo '<span class="badge-status status-canceled">Canceled</span>';
                            } else {
                                echo '<span class="badge-status status-default">'.$r1['status'].'</span>';
                            }
                            ?>
                        </td>
                        <td style="text-align: center;">
                            <a href="booking_edit.php?id=<?php echo $r1['id_booking']?>" class="btn-action btn-edit-custom text-decoration-none me-1">Ubah Status</a>

                            <a href="booking.php?op=delete&id=<?php echo $r1['id_booking'] ?>" class="btn-action btn-delete-custom text-decoration-none" onclick="return confirm('Hapus data pesanan ini secara permanen?')">Delete</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation" class="pagination-custom">
        <ul class="pagination mb-0">
            <?php 
            $cari = isset($_GET['cari'])? $_GET['cari'] : "";
            for($i=1; $i <= $pages; $i++){
                $active_class = ($page == $i) ? "active" : "";
                ?>
                <li class="page-item <?php echo $active_class ?>">
                    <a class="page-link" href="booking.php?katakunci=<?php echo $katakunci?>&cari=<?php echo $cari?>&page=<?php echo $i ?>"><?php echo $i ?></a>
                </li>
                <?php
            }
            ?>
        </ul>
    </nav>

</div>

<?php include("inc_footer.php") ?>