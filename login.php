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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login Admin - Balwil Grand Hotel</title>
    <style>
        /* Membuat halaman full screen dan menaruh kotak tepat di tengah-tengah */
        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
        }

        /* Desain Kotak Login Admin Navy Mewah */
        .login-card {
            width: 100%;
            max-width: 400px;
            background-color: #0b132b;
            padding: 40px;
            border-radius: 12px;
            color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            border: 2px solid #c7a668; /* Lis emas khas Balwil */
        }

        .login-card h1 {
            font-size: 26px;
            font-weight: 700;
            color: #c7a668;
            text-align: center;
            margin-bottom: 5px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .login-card .subtitle {
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 30px;
        }

        /* Merapikan Input Form khas Tema Gelap */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #94a3b8;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #334155;
            font-size: 14px;
            background-color: #1c2541;
            color: #ffffff;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: #1c2541;
            color: #ffffff;
            border-color: #c7a668;
            box-shadow: 0 0 8px rgba(199, 166, 104, 0.3);
        }

        /* Tombol Emas Utama */
        .btn-custom {
            width: 100%;
            background-color: #c7a668;
            color: #0b132b;
            padding: 12px;
            border: 2px solid #c7a668;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .btn-custom:hover {
            background-color: transparent;
            color: #c7a668;
        }

        /* Link Kembali Ke Depan */
        .back-link {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            margin-bottom: 0;
        }

        .back-link a {
            color: #c7a668;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h1>Admin Panel</h1>
        <div class="subtitle">Balwil Grand Hotel Management</div>

        <?php if($err){ ?>
        <div class="alert alert-danger py-2 px-3" style="font-size: 14px; border-radius: 6px; border: none; background-color: #ff6b6b; color: #fff;">
            ⚠️ <?php echo $err ?>
        </div>
        <?php } ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username" value="<?php echo $username?>" autocomplete="off" required />
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required />
            </div>
            
            <button type="submit" class="btn-custom" name="Login">Sign In</button>
        </form>

        <div class="back-link">
            <a href="../user/home.php">← Kembali ke Beranda Utama</a>
        </div>
    </div>

</body>
</html>