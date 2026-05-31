<?php include("inc_header.php")?>
<h3>Lupa Password</h3>
<?php 
// Jika tamu sudah login, arahkan ke beranda
if(isset($_SESSION['user_email']) && $_SESSION['user_email'] != ''){
    header("location:index.php");
    exit();
}

$err    = "";
$sukses = "";
$email  = "";

if(isset($_POST['submit'])){
    $email  = $_POST['email'];
    if($email == ''){
        $err = "Silakan masukkan alamat email Anda.";
    }else{
        $sql1 = "select * from user where email = '$email'";
        $q1   = mysqli_query($koneksi,$sql1);
        $n1   = mysqli_num_rows($q1);

        if($n1 < 1){
            $err = "Email: <b>$email</b> tidak ditemukan di sistem kami.";
        }
    }

    if(empty($err)){
        // Buat token acak
        $token_ganti_password   = md5(rand(0,1000));
        
        // Atur isi email
        $judul_email            = "Permintaan Ganti Password - Hotel Kita";
        $isi_email              = "Seseorang meminta untuk melakukan perubahan password untuk akun Anda. Silakan klik link di bawah ini untuk mengatur ulang password:<br/><br/>";
        $isi_email             .= "<a href='".url_dasar()."/ganti_password.php?email=$email&token=$token_ganti_password'>Klik Di Sini Untuk Ganti Password</a><br/><br/>";
        $isi_email             .= "Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.";
        
        // Panggil fungsi kirim email
        kirim_email($email, $email, $judul_email, $isi_email);

        // Simpan token ke database user
        $sql1     = "update user set token_ganti_password = '$token_ganti_password' where email = '$email'";
        mysqli_query($koneksi,$sql1);
        
        $sukses  ="Link ganti password sudah dikirimkan. Silakan cek kotak masuk atau folder spam email Anda.";
    }
}
?>

<?php if($err){ echo "<div class='error'>$err</div>";}?>
<?php if($sukses){ echo "<div class='sukses'>$sukses</div>";}?>

<?php if(empty($sukses)){ ?>
<form action="" method="POST">
    <table>
        <tr>
            <td class="label">Email Akun</td>
            <td><input type="text" name="email" class="input" value="<?php echo $email ?>" placeholder="Masukkan email Anda" /></td>
        </tr>
        <tr>
            <td></td>
            <td>
            <input type="submit" name="submit" value="Kirim Link Reset" class="tbl-biru"/>
            </td>
        </tr>
    </table>
</form>
<?php } ?>

<?php include("inc_footer.php");?>