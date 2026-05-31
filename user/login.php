<?php 
session_start();

// Panggil file koneksi asli kalian
include("../inc/inc_koneksi.php");

// Kalau tamu sudah login, langsung arahkan balik ke home.php
if(isset($_SESSION['user_email']) && $_SESSION['user_email'] != ''){
    header("location:home.php");
    exit();
}

$email      = "";
$password   = "";
$err        = "";

// PERBAIKAN: Menambahkan tanda kurung yang kurang pada isset()
if(isset($_POST['login'])){
    $email      = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password   = $_POST['password'];

    if($email == '' or $password == ''){
        $err .= "<li>Silakan masukkan email dan password</li>";
    } else {
        // Menyesuaikan query dengan tabel 'user' kalian
        $sql1   = "select * from user where email = '$email'";
        $q1     = mysqli_query($koneksi, $sql1);
        $r1     = mysqli_fetch_array($q1);
        $n1     = mysqli_num_rows($q1);

        if($n1 < 1){
            $err .= "<li>Akun email tidak terdaftar</li>";
        } else {
            // PENTING: Mengecek apakah password di DB berupa MD5 atau teks biasa 'pass123'
            if($r1['password'] == md5($password) || $r1['password'] == $password){
                // Set session jika password cocok
                $_SESSION['user_id']    = $r1['id_user'];
                $_SESSION['user_email'] = $email;
                $_SESSION['user_nama']  = $r1['nama'];
                
                header("location:home.php");
                exit();
            } else {
                $err .= "<li>Password yang Anda masukkan salah</li>";
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
        background-color: #060b13 !important; /* Latar dasar di luar dibikin super gelap (Onyx Black-Blue) */
    }

    /* LATAR BELAKANG LUAR (DIKONTRASTKAN)
       - Menggunakan overlay yang sangat gelap (#060b13) agar gambar fasil6.jpg samar-samar di belakang
    */
    .main-content {
        background: linear-gradient(rgba(6, 11, 19, 0.85), rgba(6, 11, 19, 0.95)), 
                    url('fasil6.jpg') no-repeat center center !important;
        background-size: cover !important; 
        min-height: 100vh; 
        width: 100%;
        display: flex; 
        align-items: center; 
        justify-content: center;
        box-sizing: border-box;
        padding: 40px 20px;
        font-family: 'Montserrat', 'Segoe UI', Helvetica, Arial, sans-serif !important;
    }
    
    .main-content h2, .main-content p, .main-content input, .main-content button, .main-content label {
        font-family: inherit !important;
    }

    /* Style Input Box Putih Terang Sesuai Gambar Admin Panel */
    .input-panel-light {
        width: 100%; 
        padding: 15px 18px; 
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

    /* Tombol Masuk Sistem Emas Solid Tebal Sesuai Referensi Gambar */
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
    <div style="width: 100%; max-width: 460px; background-color: #101f42; padding: 50px 45px; border-radius: 16px; color: #ffffff; box-shadow: 0 20px 50px rgba(14, 81, 112, 0.5), 0 0 25px rgba(199, 166, 104, 0.15); border: 1px solid rgba(199, 166, 104, 0.35); box-sizing: border-box;">
        
        <div style="text-align: center; margin-bottom: 12px; color: #c7a668;">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="filter: drop-shadow(0px 4px 12px rgba(83, 69, 42, 0.25));">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
        </div>
        
        <h2 style="text-align: center; color: #c7a668; margin-bottom: 6px; font-weight: 800; letter-spacing: 3px; font-size: 34px; text-shadow: 0 3px 6px rgba(0,0,0,0.6);">LOGIN USER</h2>
        <p style="text-align: center; font-size: 14px; color: #cbd5e1; margin-bottom: 40px; letter-spacing: 0.8px; font-weight: 500;">Balwil Grand Hotel</p>
        
        <?php if($err){ ?>
            <div style="background-color: #ef4444; color: #ffffff; padding: 12px 15px; border-radius: 6px; margin-bottom: 25px; font-size: 14px; border-left: 4px solid #b91c1c;">
                <ul style="margin: 0; padding-left: 20px; list-style-type: square;"><?php echo $err; ?></ul>
            </div>
        <?php } ?>
        
        <form action="login.php" method="POST">
            <div style="margin-bottom: 24px;">
                <label style="display: block; margin-bottom: 10px; font-size: 12px; color: #cbd5e1; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px;">Alamat Email</label>
                <input type="email" name="email" placeholder="rian.hidayat@email.com" value="<?php echo $email ?>" class="input-panel-light" required>
            </div>
            
            <div style="margin-bottom: 35px;">
                <label style="display: block; margin-bottom: 10px; font-size: 12px; color: #cbd5e1; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px;">Password Access</label>
                <input type="password" name="password" placeholder="••••" class="input-panel-light" required>
            </div>
            
            <button type="submit" name="login" class="btn-panel-gold">Masuk Sistem</button>
        </form>
        
        <p style="text-align: center; margin-top: 35px; font-size: 13px; margin-bottom: 0;">
            <a href="register.php" style="color: #c7a668; text-decoration: none; font-weight: 600; letter-spacing: 0.5px;">← Belum memiliki akun? Daftar di sini</a>
        </p>
    </div>
</div>

<?php include('../includes/footer.php'); ?>