<?php 
session_start();
include("../inc/inc_koneksi.php");

$nama       = "";
$email      = "";
$no_hp      = "";
$password   = "";
$pesan      = "";
$err        = "";

if(isset($_POST['register'])){
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email      = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_hp      = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $password   = $_POST['password'];

    if($nama == '' or $email == '' or $no_hp == '' or $password == ''){
        $err .= "<li>Silakan lengkapi semua isian data</li>";
    } else {
        // Cek apakah email sudah dipakai oleh user lain
        $sql_cek   = "select email from user where email = '$email'";
        $q_cek     = mysqli_query($koneksi, $sql_cek);
        $n_cek     = mysqli_num_rows($q_cek);

        if($n_cek > 0){
            $err .= "<li>Email sudah terdaftar, gunakan email lain</li>";
        } else {
            // Menggunakan enkripsi MD5 agar aman dan sinkron
            $password_md5 = md5($password);
            
            // Query dicocokkan dengan tabel user: nama, email, password, no_hp
            $sql_input    = "insert into user(nama, email, password, no_hp) values ('$nama', '$email', '$password_md5', '$no_hp')";
            $q_input      = mysqli_query($koneksi, $sql_input);
            
            if($q_input){
                $pesan = "Akun berhasil dibuat! Silakan coba login.";
                $nama = ""; $email = ""; $no_hp = ""; 
            } else {
                $err .= "<li>Gagal menyimpan data ke database</li>";
            }
        }
    }
}

include('../includes/navbar.php'); 
?>

<style>
    /* Reset margin global browser */
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background-color: #080d1a !important; /* Dongker pekat hampir hitam */
    }

    /* Latar Belakang Luar Sinematik Mewah */
    .main-content {
        background: linear-gradient(rgba(8, 13, 26, 0.82), rgba(8, 13, 26, 0.92)), 
                    url('fasil6.jpg') no-repeat center center fixed !important;
        background-size: cover !important; 
        min-height: 100vh; 
        width: 100%;
        display: flex; 
        align-items: center; 
        justify-content: center;
        box-sizing: border-box;
        padding: 50px 20px;
        font-family: 'Montserrat', 'Segoe UI', Helvetica, Arial, sans-serif !important;
    }
    
    .main-content h2, .main-content p, .main-content input, .main-content button, .main-content label {
        font-family: inherit !important;
    }

    /* Style Input Box Cerah & Lembut */
    .input-panel-light {
        width: 100%; 
        padding: 14px 18px; 
        border-radius: 8px; 
        border: none; 
        font-size: 15px; 
        background-color: #e2ecf7 !important; 
        color: #0f172a !important; 
        outline: none;
        font-weight: 500;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .input-panel-light:focus {
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(199, 166, 104, 0.5);
    }

    /* Tombol Registrasi Emas Solid Berkelas */
    .btn-panel-gold {
        width: 100%; 
        background-color: #c7a668; 
        color: #0b132b; 
        padding: 16px; 
        border: none; 
        border-radius: 8px; 
        font-weight: 700; 
        cursor: pointer; 
        font-size: 16px; 
        text-transform: uppercase;
        letter-spacing: 2px;
        box-shadow: 0 4px 15px rgba(199, 166, 104, 0.3);
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .btn-panel-gold:hover {
        background-color: #bfa05f;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(199, 166, 104, 0.4);
    }
</style>

<div class="main-content">
    <div style="width: 100%; max-width: 480px; background-color: #111c36; padding: 45px 40px; border-radius: 16px; color: #ffffff; box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), 0 0 40px rgba(199, 166, 104, 0.08); border: 1px solid rgba(199, 166, 104, 0.2); box-sizing: border-box; backdrop-filter: blur(4px);">
        
        <h2 style="text-align: center; color: #c7a668; margin-bottom: 6px; font-weight: 800; letter-spacing: 2px; font-size: 30px; text-shadow: 0 3px 6px rgba(0,0,0,0.6);">✨ REGISTER AKUN</h2>
        <p style="text-align: center; font-size: 14px; color: #94a3b8; margin-bottom: 35px; letter-spacing: 0.5px; font-weight: 500;">Buat akun tamu Balwil Grand Hotel</p>
        
        <?php if($err){ ?>
            <div style="background-color: #ef4444; color: #ffffff; padding: 12px 15px; border-radius: 6px; margin-bottom: 25px; font-size: 14px; border-left: 4px solid #b91c1c;">
                <ul style="margin: 0; padding-left: 20px; list-style-type: square;"><?php echo $err; ?></ul>
            </div>
        <?php } ?>

        <?php if($pesan){ ?>
            <div style="background-color: #10b981; color: #ffffff; padding: 14px 15px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; text-align: center; font-weight: 600; border-left: 4px solid #047857; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
                🎉 <?php echo $pesan; ?> <br style="margin-bottom: 8px;"> 
                <a href="login.php" style="color: #fff; font-weight: 700; text-decoration: underline; display: inline-block; margin-top: 5px; background: rgba(0,0,0,0.2); padding: 4px 12px; border-radius: 4px;">Klik untuk Login →</a>
            </div>
        <?php } ?>
        
        <form action="" method="POST">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 12px; color: #cbd5e1; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px;">Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Rian Hidayat" value="<?php echo $nama ?>" class="input-panel-light" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 12px; color: #cbd5e1; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px;">Alamat Email</label>
                <input type="email" name="email" placeholder="rian@email.com" value="<?php echo $email ?>" class="input-panel-light" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-size: 12px; color: #cbd5e1; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px;">Nomor HP</label>
                <input type="text" name="no_hp" placeholder="081234567890" value="<?php echo $no_hp ?>" class="input-panel-light" required>
            </div>
            
            <div style="margin-bottom: 35px;">
                <label style="display: block; margin-bottom: 8px; font-size: 12px; color: #cbd5e1; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px;">Password</label>
                <input type="password" name="password" placeholder="Masukkan password baru" class="input-panel-light" required>
            </div>
            
            <button type="submit" name="register" class="btn-panel-gold">Daftar Akun</button>
        </form>
        
        <p style="text-align: center; margin-top: 35px; font-size: 13px; margin-bottom: 0;">
            <a href="login.php" style="color: #c7a668; text-decoration: none; font-weight: 600; letter-spacing: 0.5px;">← Sudah punya akun? Login di sini</a>
        </p>
    </div>
</div>

<?php include('../includes/footer.php'); ?>