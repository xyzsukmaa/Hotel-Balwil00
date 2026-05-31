<?php include("inc_header.php") ?>
<?php
$id_tipe        = "";
$nomor_kamar    = "";
$status_kamar   = "";

$error      = "";
$sukses     = "";

if(isset($_GET['id'])){
    $id = $_GET['id'];
}else{
    $id = "";
}

// Ambil data kamar kalau mode edit
if($id != ""){
    $sql1   = "select * from kamar where id_kamar = '$id'";
    $q1     = mysqli_query($koneksi,$sql1);
    $r1     = mysqli_fetch_array($q1);
    
    $id_tipe        = $r1['id_tipe'];
    $nomor_kamar    = $r1['nomor_kamar'];
    $status_kamar   = $r1['status_kamar'];

    if($nomor_kamar == ''){
        $error  = "Data kamar tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $id_tipe        = $_POST['id_tipe'];
    $nomor_kamar    = $_POST['nomor_kamar'];
    $status_kamar   = $_POST['status_kamar'];

    if ($id_tipe == '' or $nomor_kamar == '' or $status_kamar == '') {
        $error     = "Silakan lengkapi semua isian.";
    }

    if (empty($error)) {
        if($id != ""){
            $sql1   = "update kamar set id_tipe = '$id_tipe', nomor_kamar = '$nomor_kamar', status_kamar = '$status_kamar' where id_kamar = '$id'";
        }else{
            $sql1       = "insert into kamar(id_tipe, nomor_kamar, status_kamar) values ('$id_tipe', '$nomor_kamar', '$status_kamar')";
        }
        
        $q1         = mysqli_query($koneksi, $sql1);
        if ($q1) {
            $sukses     = "Sukses menyimpan data kamar fisik";
        } else {
            $error      = "Gagal menyimpan data";
        }
    }
}
?>

<style>
    /* 1. Atur background pada body tanpa merusak posisi footer */
    body {
        background: linear-gradient(rgba(11, 19, 43, 0.8), rgba(15, 23, 42, 0.9)), 
                    url('kolam.jpg') no-repeat center center fixed !important;
        background-size: cover !important;
        background-color: #0b132b !important;
        color: #ffffff !important;
        margin: 0 !important;
    }

    /* 2. Pembungkus Utama: Membagi halaman jadi 3 area (atas kosong, tengah form, bawah footer) */
    .page-layout-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 85vh; /* Menggunakan tinggi dinamis agar muat dengan footer bawaan */
        justify-content: center;
        padding: 40px 20px;
    }

    /* 3. Kotak form kaca transparan gelap mewah */
    .admin-form-body {
        background: rgba(15, 23, 42, 0.75) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 40px;
        border-radius: 12px;
        border: 2px solid #c7a668;
        max-width: 700px;
        width: 100%;
        margin: 0 auto; /* Supaya horizontal pas di tengah */
        box-shadow: 0 20px 45px rgba(0,0,0,0.6);
    }

    .admin-form-title {
        color: #c7a668;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 22px;
        margin-bottom: 5px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    .admin-form-subtitle {
        font-size: 13px;
        color: #cbd5e1;
        margin-bottom: 30px;
    }

    .form-label-custom {
        color: #cbd5e1;
        font-weight: 600;
        font-size: 14px;
    }

    /* Input box gelap mewah */
    .form-control-custom {
        background-color: rgba(23, 34, 55, 0.85) !important;
        border: 1px solid #475569 !important;
        color: #ffffff !important;
        padding: 12px 15px;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus {
        border-color: #c7a668 !important;
        box-shadow: 0 0 10px rgba(199, 166, 104, 0.4) !important;
    }

    .form-control-custom option {
        background-color: #0f172a;
        color: #ffffff;
    }

    .button-group-row {
        display: flex;
        gap: 12px;
        margin-top: 25px;
    }

    /* Tombol Simpan Emas */
    .btn-submit-gold {
        background-color: #c7a668;
        color: #0b132b;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        padding: 12px 25px;
        border: 2px solid #c7a668;
        border-radius: 6px;
        transition: all 0.3s ease;
        flex: 2;
    }

    .btn-submit-gold:hover {
        background-color: transparent;
        color: #c7a668;
    }

    /* Tombol Batal/Kembali */
    .btn-cancel-outline {
        background-color: transparent;
        color: #cbd5e1;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        padding: 12px 20px;
        border: 2px solid #475569;
        border-radius: 6px;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        flex: 1;
    }

    .btn-cancel-outline:hover {
        border-color: #ef4444;
        color: #ef4444;
    }

    .alert-danger-custom {
        background-color: #ef4444;
        color: #ffffff;
        border: none;
    }

    .alert-success-custom {
        background-color: #10b981;
        color: #ffffff;
        border: none;
    }
</style>

<div class="page-layout-wrapper">
    <div class="container">
        <div class="admin-form-body">
            <h2 class="admin-form-title">
                <?php echo ($id != "") ? "📝 Edit Data Kamar Fisik" : "🛠️ Input Data Kamar Fisik"; ?>
            </h2>
            <p class="admin-form-subtitle">Panel Manajemen Kamar Real-time - Balwil Grand Hotel</p>

            <?php if ($error) { ?>
                <div class="alert alert-danger-custom py-2 px-3 mb-3" role="alert">⚠️ <?php echo $error ?></div>
            <?php } ?>

            <?php if ($sukses) { ?>
                <div class="alert alert-success-custom py-2 px-3 mb-3" role="alert">🎉 <?php echo $sukses ?></div>
            <?php } ?>

            <form action="" method="post" class="mt-2">
                
                <div class="mb-3 row align-items-center">
                    <label for="id_tipe" class="col-sm-3 col-form-label form-label-custom">Tipe Kamar</label>
                    <div class="col-sm-9">
                        <select name="id_tipe" class="form-control form-control-custom" id="id_tipe">
                            <option value="">- Pilih Tipe Kamar -</option>
                            <?php
                            $sql_tipe = "SELECT id_tipe, nama_tipe FROM tipe_kamar ORDER BY nama_tipe ASC";
                            $q_tipe   = mysqli_query($koneksi, $sql_tipe);
                            while($r_tipe = mysqli_fetch_array($q_tipe)){
                                $selected = ($id_tipe == $r_tipe['id_tipe']) ? "selected" : "";
                                echo "<option value='".$r_tipe['id_tipe']."' $selected>".$r_tipe['nama_tipe']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="nomor_kamar" class="col-sm-3 col-form-label form-label-custom">Nomor Kamar</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-custom" id="nomor_kamar" value="<?php echo $nomor_kamar ?>" name="nomor_kamar" placeholder="Misal: PRS_06, 101, 102">
                    </div>
                </div>

                <div class="mb-4 row align-items-center">
                    <label for="status_kamar" class="col-sm-3 col-form-label form-label-custom">Status Kamar</label>
                    <div class="col-sm-9">
                        <select name="status_kamar" class="form-control form-control-custom" id="status_kamar">
                            <option value="TERSEDIA" <?php if($status_kamar == 'TERSEDIA' || $status_kamar == 'Tersedia') echo 'selected' ?>>TERSEDIA</option>
                            <option value="PENUH" <?php if($status_kamar == 'PENUH' || $status_kamar == 'Terisi') echo 'selected' ?>>PENUH</option>
                            <option value="MAINTENANCE" <?php if($status_kamar == 'MAINTENANCE' || $status_kamar == 'Maintenance') echo 'selected' ?>>MAINTENANCE</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <div class="button-group-row">
                            <a href="kamar.php" class="btn-cancel-outline">Kembali</a>
                            <input type="submit" name="simpan" value="Simpan Kamar" class="btn btn-submit-gold" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("inc_footer.php") ?>