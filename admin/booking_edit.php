<?php include("inc_header.php") ?>
<?php
$error  = "";
$sukses = "";

$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : "";

if ($id == "") {
    echo "<script>window.location='booking.php';</script>";
    exit();
}

$sql1 = "SELECT booking.*, user.nama, user.no_hp, kamar.nomor_kamar, tipe_kamar.nama_tipe 
         FROM booking 
         LEFT JOIN user ON booking.id_user = user.id_user 
         LEFT JOIN kamar ON booking.id_kamar = kamar.id_kamar 
         LEFT JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
         WHERE id_booking = '$id'";
$q1   = mysqli_query($koneksi, $sql1);
$r1   = mysqli_fetch_array($q1);

if (!$r1) {
    $error = "Data pesanan tidak ditemukan di database.";
}

if (isset($_POST['simpan'])) {
    $status_baru = mysqli_real_escape_string($koneksi, $_POST['status']);
    $id_kamar_baru = mysqli_real_escape_string($koneksi, $_POST['id_kamar']);
    $id_kamar_lama = $r1['id_kamar'];

    if (($status_baru == 'Confirmed' || $status_baru == 'Check-in') && ($id_kamar_baru == '' || $id_kamar_baru == '0')) {
        $error = "GAGAL: Kamu WAJIB memilih Nomor Kamar di menu dropdown jika statusnya Confirmed atau Check-in!";
    } else {
        // Jika lolos validasi, lanjut update tabel booking
        $sql_update = "UPDATE booking SET status = '$status_baru', id_kamar = '$id_kamar_baru' WHERE id_booking = '$id'";
        $q_update   = mysqli_query($koneksi, $sql_update);

        if ($q_update) {
            $sukses = "Status dan nomor kamar berhasil diperbarui.";
            
            // 1. Bebaskan kamar LAMA jika admin memindahkan tamu ke kamar lain
            if ($id_kamar_lama != '' && $id_kamar_lama != '0' && $id_kamar_lama != $id_kamar_baru) {
                 mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'Tersedia' WHERE id_kamar = '$id_kamar_lama'");
            }

            // 2. Sinkronisasi status kamar BARU
            if ($id_kamar_baru != '' && $id_kamar_baru != '0') {
                if ($status_baru == 'Confirmed' || $status_baru == 'Check-in') {
                    mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'Terisi' WHERE id_kamar = '$id_kamar_baru'");
                } elseif ($status_baru == 'Canceled' || $status_baru == 'Check-out') {
                    mysqli_query($koneksi, "UPDATE kamar SET status_kamar = 'Tersedia' WHERE id_kamar = '$id_kamar_baru'");
                }
            }

            // Refresh tampilan di layar
            $r1['status'] = $status_baru;
            $r1['id_kamar'] = $id_kamar_baru;
            
            $q_kamar_refresh = mysqli_query($koneksi, "SELECT nomor_kamar FROM kamar WHERE id_kamar = '$id_kamar_baru'");
            if($r_kamar_refresh = mysqli_fetch_array($q_kamar_refresh)) {
                $r1['nomor_kamar'] = $r_kamar_refresh['nomor_kamar'];
            }

        } else {
            $error = "Gagal memperbarui status pesanan.";
        }
    }
}
?>

<h1>Ubah Status Pesanan</h1>
<div class="mb-3 row">
    <a href="booking.php"><< Kembali ke daftar pesanan</a>
</div>

<?php if ($error) { ?>
    <div class="alert alert-danger" role="alert"><?php echo $error ?></div>
<?php } ?>

<?php if ($sukses) { ?>
    <div class="alert alert-success" role="alert"><?php echo $sukses ?></div>
<?php } ?>

<?php if ($r1) { ?>
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-secondary text-white">
        Detail Pesanan #<?php echo $r1['id_booking'] ?>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th width="200">Nama Tamu</th>
                <td>: <?php echo $r1['nama'] ?> (HP: <?php echo $r1['no_hp'] ?>)</td>
            </tr>
            <tr>
                <th>Tipe & No. Kamar</th>
                <td>: 
                    <?php echo $r1['nama_tipe'] ?? 'Tipe Belum Diset' ?> 
                    <b>(No. Kamar: <?php echo $r1['nomor_kamar'] ?? 'Belum diset' ?>)</b>
                </td>
            </tr>
            <tr>
                <th>Tanggal Menginap</th>
                <td>: <?php echo $r1['checkin'] ?> s/d <?php echo $r1['checkout'] ?></td>
            </tr>
            <tr>
                <th>Total Tagihan</th>
                <td>: <b>Rp <?php echo number_format($r1['total_harga'], 0, ',', '.') ?></b></td>
            </tr>
        </table>
    </div>
</div>

<form action="" method="post" class="border p-4 bg-light rounded">
    
    <!-- TAMBAHAN: DROPDOWN PILIH NOMOR KAMAR FISIK -->
    <div class="mb-3 row">
        <label for="id_kamar" class="col-sm-2 col-form-label"><b>Penetapan Kamar</b></label>
        <div class="col-sm-6">
            <select name="id_kamar" class="form-control" id="id_kamar">
                <option value="">- Belum Ada Kamar Fisik yang Dipilih -</option>
                <?php
                // Hanya tampilkan kamar yang "Tersedia" ATAU kamar yang memang sedang dipakai oleh ID Booking ini
                $sql_k = "SELECT kamar.*, tipe_kamar.nama_tipe 
                          FROM kamar 
                          LEFT JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe 
                          WHERE kamar.status_kamar = 'Tersedia' OR kamar.id_kamar = '".$r1['id_kamar']."' 
                          ORDER BY tipe_kamar.nama_tipe ASC, kamar.nomor_kamar ASC";
                $q_k = mysqli_query($koneksi, $sql_k);
                
                while($rk = mysqli_fetch_array($q_k)){
                    $selected = ($r1['id_kamar'] == $rk['id_kamar']) ? 'selected' : '';
                    echo "<option value='".$rk['id_kamar']."' $selected>".$rk['nama_tipe']." - Kamar No. ".$rk['nomor_kamar']."</option>";
                }
                ?>
            </select>
            <small class="text-muted">Pilih nomor kamar fisik untuk diberikan ke tamu.</small>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="status" class="col-sm-2 col-form-label"><b>Update Status</b></label>
        <div class="col-sm-4">
            <select name="status" class="form-control" id="status">
                <option value="Pending" <?php if($r1['status'] == 'Pending') echo 'selected' ?>>Pending</option>
                <option value="Menunggu Verifikasi" <?php if($r1['status'] == 'Menunggu Verifikasi') echo 'selected' ?>>Menunggu Verifikasi</option>
                <option value="Confirmed" <?php if($r1['status'] == 'Confirmed') echo 'selected' ?>>Confirmed</option>
                <option value="Check-in" <?php if($r1['status'] == 'Check-in') echo 'selected' ?>>Check-in</option>
                <option value="Check-out" <?php if($r1['status'] == 'Check-out') echo 'selected' ?>>Check-out</option>
                <option value="Canceled" <?php if($r1['status'] == 'Canceled') echo 'selected' ?>>Canceled</option>
            </select>
        </div>
    </div>

    <div class="mb-3 row mt-4">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <input type="submit" name="simpan" value="Simpan Pembaruan" class="btn btn-primary px-4" />
        </div>
    </div>
</form>
<?php } ?>

<?php include("inc_footer.php") ?>