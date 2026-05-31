<?php include("inc_header.php")?>
<h3>Ganti Password</h3>
<?php 
// Jika tamu sudah login, arahkan kembali ke index
if(isset($_SESSION['user_email']) && $_SESSION['user_email'] != ''){
    header("location:index.php");
    exit();
}

$err    = "";
$sukses = "";

// Mengambil email dan token dari URL dengan aman
$email  = isset($_GET['email']) ? $_GET['email'] : '';
$token  = isset($_GET['token']) ? $_GET['token'] : '';

if($token == '' or $email == ''){
    $err .= "Link tidak valid. Email dan token tidak tersedia.<br>";
}else{
    // Sesuaikan query ke tabel user
    $sql1 ="select * from user where email = '$email' and token_ganti_password = '$token'";
    $q1   = mysqli_query($koneksi,$sql1);
    $n1   = mysqli_num_rows($q1);

    if($n1 < 1){
        $err .= "Link tidak valid. Email dan token tidak sesuai.<br>";
    }
}

if(isset($_POST['submit'])){
    $password   = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if($password == '' or $konfirmasi_password == ''){
        $err .= "Silakan masukkan password serta konfirmasi password.<br>";
    }elseif($konfirmasi_password != $password){
        $err .= "Konfirmasi password tidak sesuai dengan password.<br>";
    }elseif(strlen($password) < 6){
        $err .= "Jumlah karakter yang diperbolehkan untuk password minimal 6 karakter.<br>";
    }

    if(empty($err)){
        // Kosongkan token setelah dipakai dan update password di tabel user
        $sql1 = "update user set token_ganti_password = '', password = md5('$password') where email = '$email'";
        mysqli_query($koneksi,$sql1);
        $sukses = "Password berhasil diganti. Silakan <a href='".url_dasar()."/login.php'>login</a>.";
    }
}
?>

<?php if($err){ echo "<div class='error'>$err</div>";}?>
<?php if($sukses){ echo "<div class='sukses'>$sukses</div>";}?>

<?php if($n1 > 0 && empty($sukses)){ ?>
<form action="" method="POST">
    <table>
        <tr>
            <td class="label">Password Baru</td>
            <td><input type="password" name="password" class="input" /></td>
        </tr>
        <tr>
            <td class="label">Konfirmasi Password</td>
            <td><input type="password" name="konfirmasi_password" class="input" /></td>
        </tr>
        <tr>
            <td></td>
            <td>
            <input type="submit" name="submit" value="Ganti Password" class="tbl-biru"/>
            </td>
        </tr>
    </table>
</form>
<?php } ?>

<?php include("inc_footer.php");?>