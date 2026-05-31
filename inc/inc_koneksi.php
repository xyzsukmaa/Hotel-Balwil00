<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_hotel";

            //fungsi untuk koneksi 
$koneksi = mysqli_connect($host, $user, $pass, $db);

            //cek koneksi
if(!$koneksi){
    die("gagal terkoneksi: " . mysqli_connect_error()); 
} 
//untuk pengecekan 
/*else {
    echo "koneksi berhasil"; 
}*/
?>