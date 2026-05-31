<?php include("inc_header.php") ?>
<?php
$sukses = "";
$error = "";

// Amankan input kata kunci pencarian
$katakunci = (isset($_GET['katakunci'])) ? mysqli_real_escape_string($koneksi, $_GET['katakunci']) : "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Amankan parameter ID jika ada operasi
if ($op != "") {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
}

// Proses mengubah status pembayaran menjadi Lunas
if ($op == 'setlunas') {
    // 1. Update status di tabel pembayaran admin
    $sql_lunas = "update pembayaran set status_bayar = 'Lunas', tanggal_bayar = now() where id_bayar = '$id'";
    $q_lunas   = mysqli_query($koneksi, $sql_lunas);
    
    if ($q_lunas) {
        // SINKRONISASI: Ambil id_booking terkait untuk meng-update tabel booking milik user
        $q_get_booking = mysqli_query($koneksi, "select id_booking from pembayaran where id_bayar = '$id'");
        $data_bkg = mysqli_fetch_array($q_get_booking);
        if ($data_bkg) {
            $id_bkg_update = $data_bkg['id_booking'];
            // Ikut ubah status di tabel booking user menjadi Lunas
            mysqli_query($koneksi, "update booking set status = 'Lunas' where id_booking = '$id_bkg_update'");
        }
        $sukses = "Status pembayaran berhasil diperbarui menjadi Lunas";
    } else {
        $error = "Gagal memperbarui status pembayaran";
    }
}

// Proses membatalkan status lunas (set menjadi Belum Lunas)
if ($op == 'setbelum') {
    // 1. Update status di tabel pembayaran admin
    $sql_belum = "update pembayaran set status_bayar = 'Belum Lunas', tanggal_bayar = NULL where id_bayar = '$id'";
    $q_belum   = mysqli_query($koneksi, $sql_belum);
    
    if ($q_belum) {
        // SINKRONISASI: Ambil id_booking terkait untuk meng-update tabel booking milik user
        $q_get_booking = mysqli_query($koneksi, "select id_booking from pembayaran where id_bayar = '$id'");
        $data_bkg = mysqli_fetch_array($q_get_booking);
        if ($data_bkg) {
            $id_bkg_update = $data_bkg['id_booking'];
            // Kembalikan status di tabel booking user menjadi Belum Lunas
            mysqli_query($koneksi, "update booking set status = 'Belum Lunas' where id_booking = '$id_bkg_update'");
        }
        $sukses = "Status pembayaran berhasil diperbarui menjadi Belum Lunas";
    } else {
        $error = "Gagal memperbarui status pembayaran";
    }
}
?>

<style>
    .container, .container-fluid {
        max-width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    body, html {
        background-color: #ffffff !important;
    }

    .pembayaran-bg-container {
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

    .btn-gold {
        background: linear-gradient(135deg, #cbb279, #b39a5f) !important;
        color: #0f172a !important;
        font-weight: 600;
        border: none !important;
        border-radius: 6px !important;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }
    .btn-gold:hover {
        background: linear-gradient(135deg, #e1ca94, #cbb279) !important;
        box-shadow: 0 4px 15px rgba(203, 178, 121, 0.35);
        color: #0f172a !important;
    }

    .table-responsive-box {
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 12px !important;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 15px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .table-pembayaran-custom {
        color: #e2e8f0 !important;
        margin-bottom: 0;
        vertical-align: middle;
    }
    .table-pembayaran-custom thead th {
        background-color: rgba(15, 23, 42, 0.4) !important;
        color: #cbb279 !important;
        border-bottom: 2px solid rgba(203, 178, 121, 0.3) !important;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 1px;
        padding: 15px 10px;
    }
    .table-pembayaran-custom tbody td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        padding: 15px 10px;
        font-size: 14px;
        background: transparent !important;
        color: #cbd5e1;
    }
    .table-pembayaran-custom tbody tr:hover td {
        background: rgba(203, 178, 121, 0.05) !important;
        color: #ffffff;
    }

    .badge-status {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 2px !important;
        letter-spacing: 1px;
        display: inline-block;
        background: transparent !important;
    }
    .status-lunas { 
        border: 1px solid #148087 !important; 
        color: #00e5bc !important; 
        font-weight: 700;
    }
    .status-belum { 
        border: 1px solid #991b1b !important; 
        color: #ff4a6b !important; 
        font-weight: 700;
    }

    .btn-action {
        padding: 6px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 2px !important;
        display: inline-block;
        transition: all 0.2s ease;
        letter-spacing: 0.5px;
        background: transparent !important;
    }

    .btn-lunas-custom {
        color: #ffaa44 !important; 
        border: 1px solid #ffaa44 !important; 
    }
    .btn-lunas-custom:hover {
        background-color: #ffaa44 !important;
        color: #0f172a !important;
    }

    .btn-belum-custom {
        color: #ff5577 !important; 
        border: 1px solid #ff5577 !important; 
    }
    .btn-belum-custom:hover {
        background-color: #ff5577 !important;
        color: #ffffff !important; 
    }

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

<div class="pembayaran-bg-container">

    <h1 class="page-title">| Data Pembayaran Hotel</h1>
    <div class="page-subtitle">Selamat datang kembali di Panel Verifikasi Transaksi Keuangan Tamu.</div>

    <?php if ($sukses): ?>
        <div class="alert alert-success" style="border-radius: 6px; background: rgba(19, 219, 182, 0.15); color: #13dbb6; border-color: rgba(19, 219, 182, 0.3); margin-top: 15px;">
            🛈 <?php echo $sukses ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger" style="border-radius: 6px; background: rgba(244, 63, 94, 0.15); color: #f43f5e; border-color: rgba(244, 63, 94, 0.3); margin-top: 15px;">
            ⚠ <?php echo $error ?>
        </div>
    <?php endif; ?>

    <div class="action-bar">
        <form class="row g-3 align-items-center" method="get">
            <div class="col-auto style-width" style="min-width: 320px;">
                <input type="text" class="form-control input-search-custom" placeholder="Cari Nama / Metode / Status..." name="katakunci" value="<?php echo $katakunci ?>" />
            </div>
            <div class="col-auto">
                <input type="submit" name="cari" value="Cari Pembayaran" class="btn btn-gold" />
            </div>
        </form>
    </div>

    <div class="table-responsive-box table-responsive">
        <table class="table table-pembayaran-custom">
            <thead>
                <tr>
                    <th class="col-1" style="text-align: center;">#</th>
                    <th>ID Booking</th>
                    <th>Nama Tamu</th>
                    <th>Metode</th>
                    <th>Total Bayar</th>
                    <th>Tanggal Bayar</th>
                    <th style="width: 150px; text-align: center;">Status</th>
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
                        $sqlcari[] = "(user.nama like '%" . $array_katakunci[$x] . "%' or pembayaran.metode like '%" . $array_katakunci[$x] . "%' or pembayaran.status_bayar like '%" . $array_katakunci[$x] . "%')";
                    }
                    $sqltambahan    = " and (" . implode(" or ", $sqlcari) . ")";
                }
                
                // FIXED: Menggunakan INNER JOIN agar booking yang belum diisi form pembayarannya otomatis disembunyikan
                $sql1   = "select booking.*, user.nama, pembayaran.id_bayar, pembayaran.metode, pembayaran.tanggal_bayar, pembayaran.status_bayar 
                           from booking 
                           inner join user on booking.id_user = user.id_user 
                           inner join pembayaran on booking.id_booking = pembayaran.id_booking 
                           where 1=1 $sqltambahan";
                           
                $page   = isset($_GET['page'])?(int)$_GET['page']:1;
                $mulai  = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
                $q1     = mysqli_query($koneksi,$sql1);
                $total  = mysqli_num_rows($q1);
                $pages  = ceil($total / $per_halaman);
                $nomor  = $mulai + 1;
                
                $sql1   = "select booking.*, user.nama, pembayaran.id_bayar, pembayaran.metode, pembayaran.tanggal_bayar, pembayaran.status_bayar 
                           from booking 
                           inner join user on booking.id_user = user.id_user 
                           inner join pembayaran on booking.id_booking = pembayaran.id_booking 
                           where 1=1 $sqltambahan 
                           order by booking.id_booking desc limit $mulai,$per_halaman";
                $q1     = mysqli_query($koneksi, $sql1);
              
                if (mysqli_num_rows($q1) == 0) {
                    echo "<tr><td colspan='8' class='text-center text-muted py-4'>Data transaksi pembayaran tidak ditemukan.</td></tr>";
                }

                while ($r1 = mysqli_fetch_array($q1)) {
                ?>
                    <tr>
                        <td style="text-align: center; color: #64748b;"><?php echo $nomor++ ?></td>
                        <td><code style="color: #ffffff; font-weight: bold;">#BKG-<?php echo str_pad($r1['id_booking'], 3, '0', STR_PAD_LEFT); ?></code></td>
                        <td><b style="color: #ffffff;"><?php echo $r1['nama'] ?? 'Tamu Tidak Diketahui' ?></b></td>
                        <td><?php echo $r1['metode'] ?></td>
                        <td style="color: #13dbb6; font-weight: bold;">Rp <?php echo number_format($r1['total_harga'], 0, ',', '.') ?></td>
                        <td><?php echo $r1['tanggal_bayar'] ?? '-' ?></td>
                        <td style="text-align: center;">
                            <?php 
                            if($r1['status_bayar'] == 'Lunas'){
                                echo '<span class="badge-status status-lunas">Lunas</span>';
                            } else {
                                echo '<span class="badge-status status-belum">Belum Lunas</span>';
                            }
                            ?>
                        </td>
                        <td style="text-align: center;">
                            <?php if($r1['status_bayar'] == 'Belum Lunas'){ ?>
                                <a href="pembayaran.php?op=setlunas&id=<?php echo $r1['id_bayar'] ?>" class="btn-action btn-lunas-custom text-decoration-none" onclick="return confirm('Konfirmasi pembayaran ini sudah lunas?')">
                                    Set Lunas
                                </a>
                            <?php } else { ?>
                                <a href="pembayaran.php?op=setbelum&id=<?php echo $r1['id_bayar'] ?>" class="btn-action btn-belum-custom text-decoration-none" onclick="return confirm('Batalkan status lunas pembayaran ini?')">
                                    Set Belum
                                </a>
                            <?php } ?>
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
                    <a class="page-link" href="pembayaran.php?katakunci=<?php echo $katakunci?>&cari=<?php echo $cari?>&page=<?php echo $i ?>"><?php echo $i ?></a>
                </li>
                <?php
            }
            ?>
        </ul>
    </nav>

</div>

<?php include("inc_footer.php") ?>