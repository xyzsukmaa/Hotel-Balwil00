<?php include("inc_header.php") ?>
<?php
$nama_tipe  = "";
$harga      = "";
$stok       = "";
$fasilitas  = "";
$foto       = "";
$foto_name  = "";

$error      = "";
$sukses     = "";

if(isset($_GET['id'])){
    $id = $_GET['id'];
}else{
    $id = "";
}

// Mengambil data tipe kamar kalau sedang mode edit
if($id != ""){
    $sql1   = "select * from tipe_kamar where id_tipe = '$id'";
    $q1     = mysqli_query($koneksi,$sql1);
    $r1     = mysqli_fetch_array($q1);
    
    $nama_tipe  = $r1['nama_tipe'];
    $harga      = $r1['harga'];
    $stok       = $r1['stok'];
    $fasilitas  = $r1['fasilitas'];
    $foto       = $r1['foto'];

    if($nama_tipe == ''){
        $error  = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $nama_tipe  = $_POST['nama_tipe'];
    $harga      = $_POST['harga'];
    $stok       = $_POST['stok'];
    $fasilitas  = $_POST['fasilitas'];

    if ($nama_tipe == '' or $harga == '' or $stok == '' or $fasilitas == '') {
        $error     = "Silakan lengkapi semua data tipe kamar.";
    }

    // Logika upload foto
    if($_FILES['foto']['name']){
        $foto_name = $_FILES['foto']['name'];
        $foto_file = $_FILES['foto']['tmp_name'];

        $detail_file = pathinfo($foto_name);
        $foto_ekstensi = strtolower($detail_file['extension']);
        $ekstensi_yang_diperbolehkan = array("jpg","jpeg","png","gif");
        
        if(!in_array($foto_ekstensi,$ekstensi_yang_diperbolehkan)){
            $error = "Ekstensi foto yang diperbolehkan hanya jpg, jpeg, png, dan gif";
        }
    }

    if (empty($error)) {
        if($foto_name){
            $direktori = "../gambar";

            // Hapus foto lama kalau ada
            if($foto != "") {
                @unlink($direktori."/$foto"); 
            }

            // Bikin nama file unik
            $foto_name = "kamar_".time()."_".$foto_name;
            move_uploaded_file($foto_file, $direktori."/".$foto_name);

            $foto = $foto_name;
        }else{
            $foto_name = $foto; // Pakai foto lama kalau tidak ada upload baru
        }

        // Query update atau insert
        if($id != ""){
            $sql1   = "update tipe_kamar set nama_tipe = '$nama_tipe', harga = '$harga', stok = '$stok', fasilitas = '$fasilitas', foto = '$foto_name' where id_tipe = '$id'";
        }else{
            $sql1       = "insert into tipe_kamar(nama_tipe, harga, stok, fasilitas, foto) values ('$nama_tipe', '$harga', '$stok', '$fasilitas', '$foto_name')";
        }
        
        $q1         = mysqli_query($koneksi, $sql1);
        if ($q1) {
            $sukses     = "Sukses menyimpan data tipe kamar";
        } else {
            $error      = "Gagal menyimpan data";
        }
    }
}
?>

<h1>Input Data Tipe Kamar</h1>
<div class="mb-3 row">
    <a href="tipe_kamar.php"><< Kembali ke daftar tipe kamar</a>
</div>

<?php if ($error) { ?>
    <div class="alert alert-danger" role="alert"><?php echo $error ?></div>
<?php } ?>

<?php if ($sukses) { ?>
    <div class="alert alert-primary" role="alert"><?php echo $sukses ?></div>
<?php } ?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3 row">
        <label for="nama_tipe" class="col-sm-2 col-form-label">Nama Tipe</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="nama_tipe" value="<?php echo $nama_tipe ?>" name="nama_tipe" placeholder="Misal: Deluxe Room">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="harga" class="col-sm-2 col-form-label">Harga per Malam</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" id="harga" value="<?php echo $harga ?>" name="harga">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="stok" class="col-sm-2 col-form-label">Stok Total</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" id="stok" value="<?php echo $stok ?>" name="stok">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="foto" class="col-sm-2 col-form-label">Foto Kamar</label>
        <div class="col-sm-10">
            <?php 
            if($foto){
                echo "<img src='../gambar/$foto' style='max-height:100px; max-width:100px; margin-bottom:10px;'/>";
            }
            ?>
            <input type="file" class="form-control" id="foto" name="foto">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="fasilitas" class="col-sm-2 col-form-label">Fasilitas</label>
        <div class="col-sm-10">
            <textarea name="fasilitas" class="form-control" id="summernote"><?php echo $fasilitas ?></textarea>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
        </div>
    </div>
</form>

<?php include("inc_footer.php") ?>