<?php 
include_once("inc/inc_koneksi.php");
include_once("inc/inc_fungsi.php");

$id = dapatkan_id(); // Pastikan fungsi ini ada di inc_fungsi.php

$sql1   = "SELECT * FROM halaman WHERE id = '$id'";
$q1     = mysqli_query($koneksi, $sql1);
$n1     = mysqli_num_rows($q1);
$r1     = mysqli_fetch_array($q1);

// Gunakan operator null coalescing untuk menghindari error jika data kosong
$judul_halaman = $r1['judul'] ?? '';
?>

<?php include_once("inc_header.php")?>

<?php 
if($n1 < 1){
    echo "<div><p>Maaf, data yang kamu maksud tidak ditemukan :(</p></div>";
} else {
?>
    <p class="deskripsi"><?php echo $r1['kutipan']?></p>
    <h1><?php echo $r1['judul']?></h1>
    <?php echo set_isi($r1['isi'])?>
<?php
}
?>

<?php include_once("inc_footer.php")?>