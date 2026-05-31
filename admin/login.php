<?php 
session_start();
if(isset($_SESSION['admin_username']) && $_SESSION['admin_username'] != ''){
    header("location:index.php");
    exit();
}
include("../inc/inc_koneksi.php");

$username   = "";
$password   = "";
$err        = "";

if(isset($_POST['Login'])){
    $username       = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password       = mysqli_real_escape_string($koneksi, $_POST['password']);

    if($username == '' or $password == ''){
        $err    = "Silakan masukkan semua isian";
    }else{
        $sql1   = "select * from admin where username = '$username'";
        $q1     = mysqli_query($koneksi,$sql1);
        $r1     = mysqli_fetch_array($q1);
        $n1     = mysqli_num_rows($q1);

        if($n1 < 1){
            $err = "Username tidak ditemukan";
        }elseif($r1['password'] != md5($password)){
            $err = "Password yang kamu masukkan tidak sesuai";
        }else{
            $_SESSION['admin_username']     = $username;
            header("location:index.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login Admin - Balwil Grand Hotel</title>
    <style>
        /* Background Belakang Full Layar dengan Gradasi Gelap Elegan */
        body {
            background: linear-gradient(135deg, #0b132b 0%, #1c2541 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
        }

        /* Kotak Card Login Versi Premium Glow */
       .login-card {
            position: relative; 
            width: 100%;
            max-width: 420px;
            background: rgba(11, 19, 43, 0.75);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 110px 40px 45px 40px; 
            border-radius: 16px;
            color: #ffffff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), 0 0 15px rgba(199, 166, 104, 0.1);
            border: 1px solid rgba(199, 166, 104, 0.35);
        }

        /* Pembungkus Logo Di-zoom dan Mengambang di Atas Card */
        .admin-logo-container {
            position: absolute;
            top: -60px; 
            left: 50%;
            transform: translateX(-50%); 
            width: 100%;
            text-align: center;
            z-index: 10;
        }

        .admin-logo-container img {
            width: 250px; 
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0px 4px 10px rgba(0, 0, 0, 0.5));
        }

        .login-card h1 {
            font-size: 24px;
            font-weight: 700;
            color: #c7a668;
            text-align: center;
            margin-bottom: 5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .login-card .subtitle {
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 35px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 11px;
            color: #cbd5e1;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* Desain Form Input Halus */
        .form-control {
            width: 100%;
            padding: 13px 16px;
            border-radius: 8px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            font-size: 14px;
            background-color: rgba(28, 37, 65, 0.85);
            color: #ffffff;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: #1c2541;
            color: #ffffff;
            border-color: #c7a668;
            box-shadow: 0 0 10px rgba(199, 166, 104, 0.4);
        }

        /* Tombol Emas Elegan dengan Efek Hover */
        .btn-custom {
            width: 100%;
            background: linear-gradient(135deg, #c7a668 0%, #b39255 100%);
            color: #0b132b;
            padding: 13px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(199, 166, 104, 0.25);
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, #b39255 0%, #c7a668 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(199, 166, 104, 0.4);
            color: #0b132b;
        }

        /* Link Kembali Ke Depan */
        .back-link {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            margin-bottom: 0;
        }

        .back-link a {
            color: rgba(199, 166, 104, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .back-link a:hover {
            color: #c7a668;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        
        <div class="admin-logo-container">
            <img src="../assets/logobaru.png" alt="Logo Balwil">
        </div>

        <h1>Admin Panel</h1>
        <div class="subtitle">Balwil Grand Hotel Management</div>

        <?php if($err){ ?>
        <div class="alert alert-danger py-2 px-3 text-center" style="font-size: 13px; border-radius: 8px; border: none; background-color: #ef4444; color: #fff; margin-bottom: 20px;">
            ⚠️ <?php echo $err ?>
        </div>
        <?php } ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">USERNAME ADMIN</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" value="<?php echo $username?>" autocomplete="off" required />
            </div>
            
            <div class="form-group">
                <label for="password">PASSWORD ACCESS</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required />
            </div>
            
            <button type="submit" class="btn-custom" name="Login">Masuk Sistem</button>
        </form>

        <div class="back-link">
            <a href="../user/home.php">← Kembali ke Beranda Utama</a>
        </div>

    </div>

</body>
</html>