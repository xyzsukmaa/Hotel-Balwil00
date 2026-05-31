<?php
// Memulai session PHP untuk melacak status login admin
session_start();

// Validasi keamanan: Jika session username admin tidak ada, tendang paksa kembali ke halaman login
if (!isset($_SESSION['admin_username'])) {
    header("location:login.php");
    exit();
}
include "../inc/inc_koneksi.php";
include "../inc/inc_fungsi.php";

$page_name = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Hotel - Balwil Grand Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
    
    <link rel="stylesheet" href="/web-hotel/Balwil00-Hotel/css/admin-style.css">
    <style>
        body {
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        
        /* WARNA ASLI & UKURAN PRESISI FULL SCREEN (TANPA ROUNDED) */
        .navbar-custom {
            background-color: #0b132b !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            border-bottom: 3px solid #c7a668; /* Garis aksen emas bawah navbar */
            padding: 12px 0;
            position: fixed; /* Membuat navbar tetap terkunci di atas saat di-scroll */
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            border-radius: 0 !important; /* Menghilangkan efek melengkung agar ukurannya pas di layar */
        }
        
        /* Judul Brand Admin */
        .navbar-custom .navbar-brand {
            color: #c7a668 !important; /* Warna Emas */
            font-weight: bold;
            font-size: 22px;
            letter-spacing: 1px;
        }
        
        /* Warna Menu Navigasi Default */
        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-size: 14px; /* Sedikit disesuaikan agar muat dengan tambahan menu baru */
            transition: 0.3s;
            padding: 8px 12px !important;
            margin: 0 2px;
        }
        
        /* Efek Hover Menu */
        .navbar-custom .nav-link:hover {
            color: #c7a668 !important;
        }
        
        /* Gaya Khusus untuk Menu yang Sedang Aktif */
        .navbar-custom .nav-link.active-admin {
            color: #ffffff !important;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
        }
        
        /* Tombol Logout */
        .navbar-custom .nav-logout {
            color: #ff6b6b !important;
            font-weight: bold;
            border: 1px solid #ff6b6b;
            border-radius: 6px;
            padding: 6px 16px !important;
            transition: 0.3s;
        }
        
        .navbar-custom .nav-logout:hover {
            background-color: #ff6b6b;
            color: #ffffff !important;
        }
        
        /* Jarak Konten Utama agar Pas di Bawah Navbar Fixed */
        .admin-content-wrapper {
            margin-top: 95px; 
            padding-bottom: 40px;
        }

        /* Merapikan Judul Halaman Utama Admin */
        h1 {
            color: #0b132b;
            font-weight: 700;
            margin-top: 10px;
            margin-bottom: 25px;
            border-left: 5px solid #c7a668;
            padding-left: 15px;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4 px-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Admin Balwil Grand Hotel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="navbar-nav me-auto">
                        <a class="nav-link <?php echo ($page_name == 'index.php') ? 'active-admin' : ''; ?>" href="index.php">Dashboard</a>
                        <a class="nav-link <?php echo ($page_name == 'kamar.php' || $page_name == 'kamar_input.php') ? 'active-admin' : ''; ?>" href="kamar.php">Kamar</a>
                        <a class="nav-link <?php echo ($page_name == 'tipe_kamar.php' || $page_name == 'tipe_kamar_input.php') ? 'active-admin' : ''; ?>" href="tipe_kamar.php">Tipe Kamar</a>
                        <a class="nav-link <?php echo ($page_name == 'booking.php' || $page_name == 'booking_edit.php') ? 'active-admin' : ''; ?>" href="booking.php">Pemesanan</a>
                        <a class="nav-link <?php echo ($page_name == 'pembayaran.php') ? 'active-admin' : ''; ?>" href="pembayaran.php">Pembayaran</a>
                        <a class="nav-link <?php echo ($page_name == 'promo.php' || $page_name == 'promo_input.php') ? 'active-admin' : ''; ?>" href="promo.php">Promo</a>
                        <a class="nav-link <?php echo ($page_name == 'user.php') ? 'active-admin' : ''; ?>" href="user.php">Tamu</a>
                        <a class="nav-link <?php echo ($page_name == 'review.php') ? 'active-admin' : ''; ?>" href="review.php">Ulasan</a>
                        <a class="nav-link <?php echo ($page_name == 'halaman.php' || $page_name == 'halaman_input.php') ? 'active-admin' : ''; ?>" href="halaman.php">Halaman</a>
                    </div>
                    <div class="navbar-nav ms-auto">
                        <a class="nav-link nav-logout" href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container-fluid px-4 admin-content-wrapper">