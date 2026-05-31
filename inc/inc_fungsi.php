<?php
// --- FUNGSI UTAMA (Diperbaiki agar kebal terhadap sub-folder admin) ---
function url_dasar(){
    $direktori = dirname($_SERVER['SCRIPT_NAME']);
    // Jika dipanggil dari dalam folder admin, bersihkan /admin dari komponen URL
    $direktori = str_replace('/admin', '', $direktori);
    
    // Normalisasi backslash jika berjalan di OS Windows lingkungan lokal
    $direktori = str_replace('\\', '', $direktori);
    
    $url_dasar  = "http://".$_SERVER['SERVER_NAME'].$direktori;
    return rtrim($url_dasar, '/');
}

// --- FUNGSI TAMBAHAN ---
function dapatkan_id() {
    return isset($_GET['id']) ? $_GET['id'] : "";
}

function bersihkan_judul($judul){
    $judul_baru      = strtolower($judul);
    $judul_baru      = preg_replace("/[^a-zA-Z0-9\s]/","",$judul_baru);
    $judul_baru      = str_replace(" ","-",$judul_baru);
    return $judul_baru;
}

function set_isi($isi){
    $isi    = str_replace("../gambar/",url_dasar()."/gambar/",$isi);
    return $isi;
}

function maximum_kata($isi,$maximum){
    $array_isi = explode(" ",$isi);
    $array_isi = array_slice($array_isi,0,$maximum);
    $isi = implode(" ",$array_isi);
    return $isi;
}

// --- FUNGSI UNTUK HALAMAN INFO ---
function ambil_gambar_halaman($id_tulisan){
    global $koneksi;
    $sql1 = "SELECT isi FROM halaman WHERE id = '$id_tulisan'";
    $q1   = mysqli_query($koneksi,$sql1);
    $r1   = mysqli_fetch_array($q1);
    $text = isset($r1['isi']) ? $r1['isi'] : '';

    preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $text, $img);
    $gambar = isset($img[1]) ? $img[1] : url_dasar()."/gambar/default.jpg";
    $gambar = str_replace("../gambar/",url_dasar()."/gambar/", $gambar);
    return $gambar;
}

// --- FUNGSI AMBIL DATA HALAMAN ---
function ambil_kutipan($id_halaman) {
    global $koneksi;
    $sql    = "SELECT kutipan FROM halaman WHERE id = '$id_halaman'";
    $q      = mysqli_query($koneksi, $sql);
    $r      = mysqli_fetch_array($q);
    return isset($r['kutipan']) ? $r['kutipan'] : '';
}

function ambil_judul($id_halaman) {
    global $koneksi;
    $sql    = "SELECT judul FROM halaman WHERE id = '$id_halaman'";
    $q      = mysqli_query($koneksi, $sql);
    $r      = mysqli_fetch_array($q);
    return isset($r['judul']) ? $r['judul'] : '';
}

function ambil_isi($id_halaman) {
    global $koneksi;
    $sql    = "SELECT isi FROM halaman WHERE id = '$id_halaman'";
    $q      = mysqli_query($koneksi, $sql);
    $r      = mysqli_fetch_array($q);
    return isset($r['isi']) ? $r['isi'] : '';
}

function buat_link_halaman($id_halaman) {
    return url_dasar() . "/halaman.php?id=" . $id_halaman;
}

// --- FUNGSI UNTUK HOTEL ---
function tipe_kamar_foto($id){
    global $koneksi;
    $sql1   = "SELECT foto FROM tipe_kamar WHERE id_tipe = '$id'";
    $q1     = mysqli_query($koneksi,$sql1);
    $r1     = mysqli_fetch_array($q1);
    $foto   = isset($r1['foto']) ? $r1['foto'] : '';

    // Gunakan path relatif dari root folder asset gambar
    if($foto && file_exists(__DIR__ . "/../gambar/".$foto)){
        return $foto;
    }else{
        return 'kamar_default.jpg'; 
    }
}

// --- FUNGSI EMAIL (PHPMailer - Diperbaiki jalur autoload-nya) ---
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function kirim_email($email_penerima, $nama_penerima, $judul_email, $isi_email){
    $email_pengirim     = "hotelmu@email.com"; 
    $password_pengirim  = "GANTI_DENGAN_APP_PASSWORD_GOOGLE"; 

    // Menggunakan __DIR__ agar mencari folder vendor mundur 1 tingkat dari posisi file fungsi saat ini
    require_once __DIR__ . '/../vendor/autoload.php';
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $email_pengirim;
        $mail->Password   = $password_pengirim;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($email_pengirim, "Hotel Admin");
        $mail->addAddress($email_penerima, $nama_penerima);
        $mail->isHTML(true);
        $mail->Subject = $judul_email;
        $mail->Body    = $isi_email;

        $mail->send();
        return "sukses";
    } catch (Exception $e) {
        return "gagal: {$mail->ErrorInfo}";
    }
}
?>