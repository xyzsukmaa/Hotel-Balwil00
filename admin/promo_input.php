<?php include("inc_header.php") ?>
<?php
$kode_promo     = "";
$diskon         = "";
$berlaku_sampai = "";
$error          = "";
$sukses         = "";

if(isset($_GET['id'])){
    $id = $_GET['id'];
}else{
    $id = "";
}

if($id != ""){
    $sql1   = "select * from promo where id_promo = '$id'";
    $q1     = mysqli_query($koneksi,$sql1);
    $r1     = mysqli_fetch_array($q1);
    $kode_promo     = $r1['kode_promo'];
    $diskon         = $r1['diskon'];
    $berlaku_sampai = $r1['berlaku_sampai'];

    if($kode_promo == ''){
        $error  = "Data promo tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $kode_promo     = $_POST['kode_promo'];
    $diskon         = $_POST['diskon'];
    $berlaku_sampai = $_POST['berlaku_sampai'];
    
    if ($kode_promo == '' or $diskon == '' or $berlaku_sampai == '') {
        $error     = "Silakan masukkan semua data promo dengan lengkap.";
    }

    if (empty($error)) {
        if($id != ""){
            $sql1   = "update promo set kode_promo = '$kode_promo', diskon='$diskon', berlaku_sampai='$berlaku_sampai' where id_promo = '$id'";
        }else{
            $sql1       = "insert into promo(kode_promo, diskon, berlaku_sampai) values ('$kode_promo', '$diskon', '$berlaku_sampai')";
        }
        
        $q1         = mysqli_query($koneksi, $sql1);
        if ($q1) {
            $sukses     = "Sukses menyimpan data promo";
        } else {
            $error      = "Gagal memasukkan data";
        }
    }
}
?>

<style>
    /* 1. Background gambar kolam dengan overlay gelap */
    body {
        background: linear-gradient(rgba(11, 19, 43, 0.8), rgba(15, 23, 42, 0.9)), 
                    url('kolam.jpg') no-repeat center center fixed !important;
        background-size: cover !important;
        background-color: #0b132b !important;
        color: #ffffff !important;
        margin: 0 !important;
    }

    /* 2. Pembungkus Utama untuk memposisikan kotak pas di tengah vertical */
    .page-layout-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 85vh;
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
        margin: 0 auto;
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

    /* Input box gelap mewah termasuk untuk input date picker */
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

    /* Memastikan icon kalender beradaptasi dengan warna tema gelap */
    .form-control-custom::-webkit-calendar-picker-indicator {
        filter: invert(1) sepia(50%) saturate(1000%) hue-rotate(15deg);
        cursor: pointer;
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
                <?php echo ($id != "") ? "📝 Edit Data Promo" : "🛠️ Input Data Promo"; ?>
            </h2>
            <p class="admin-form-subtitle">Panel Manajemen Promo Eksklusif - Balwil Grand Hotel</p>

            <?php if ($error) { ?>
                <div class="alert alert-danger-custom py-2 px-3 mb-3" role="alert">⚠️ <?php echo $error ?></div>
            <?php } ?>

            <?php if ($sukses) { ?>
                <div class="alert alert-success-custom py-2 px-3 mb-3" role="alert">🎉 <?php echo $sukses ?></div>
            <?php } ?>

            <form action="" method="post" class="mt-2">
                
                <div class="mb-3 row align-items-center">
                    <label for="kode_promo" class="col-sm-3 col-form-label form-label-custom">Kode Promo</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-custom" id="kode_promo" value="<?php echo $kode_promo ?>" name="kode_promo" placeholder="Misal: PROMOHEMAT">
                    </div>
                </div>

                <div class="mb-3 row align-items-center">
                    <label for="diskon" class="col-sm-3 col-form-label form-label-custom">Diskon (%)</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control form-control-custom" id="diskon" value="<?php echo $diskon ?>" name="diskon" placeholder="Misal: 15">
                    </div>
                </div>

                <div class="mb-4 row align-items-center">
                    <label for="berlaku_sampai" class="col-sm-3 col-form-label form-label-custom">Berlaku Sampai</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control form-control-custom" id="berlaku_sampai" value="<?php echo $berlaku_sampai ?>" name="berlaku_sampai">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <div class="button-group-row">
                            <a href="promo.php" class="btn-cancel-outline">Kembali</a>
                            <input type="submit" name="simpan" value="Simpan Promo" class="btn btn-submit-gold" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include("inc_footer.php") ?>