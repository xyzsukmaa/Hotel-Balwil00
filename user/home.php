<?php 
// 1. Aktifkan session di baris paling pertama agar login terdeteksi
session_start(); 

// 2. Set nama halaman aktif agar menu 'Beranda' di navbar menyala
$current_page = 'home'; 

// 3. Panggil navbar dari folder includes
include('../includes/navbar.php'); 
?>

<div class="dropdown-login-container" style="z-index: 1005;">
    <?php 
    // Cek apakah ada user yang sudah login
    if(isset($_SESSION['user_email']) && $_SESSION['user_email'] != ''){ 
    ?>
        <a href="/web-hotel/Balwil00-Hotel/user/logout.php" style="text-decoration: none;">
            <button class="btn-dropdown-utama" style="background-color: #c7a668; border-color: #c7a668; color: #ffffff;">
                 Logout Akun
            </button>
        </a>
    <?php } else { ?>
        <button class="btn-dropdown-utama">Mulai / Login ▼</button>
        <div class="isi-dropdown-menu">
            <a href="/web-hotel/Balwil00-Hotel/admin/login.php">🔒 Login Admin</a>
            <a href="/web-hotel/Balwil00-Hotel/user/login.php">👤 Login User</a>
            <a href="/web-hotel/Balwil00-Hotel/user/register.php" style="border-top: 1px solid #334155;">✨ Register Akun Baru</a>
        </div>
    <?php } ?>
</div>

<section class="hero-banner" style="position: relative; z-index: 1;">
    <h1 style="color: #ffffff;">Selamat Datang di Balwil Grand Hotel</h1>
    <p style="margin-top: 10px; color: #ffffff; font-size: 18px;">Nikmati Pengalaman Menginap dengan Pemandangan Laut Terbaik</p>
    <br>
    <a href="booking.php" style="display: inline-block; background-color: #ffffff; color: #1ea2ca; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 25px; transition: 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        Pesan Kamar Sekarang
    </a>
</section>

<div class="foto-pembatas-container">
    <img src="../assets/kolam.jpg" alt="Kolam Renang">
    <img src="../assets/spa.jpg" alt="Restoran Mewah">
    <img src="../assets/makan.jpg" alt="Makan">
</div>

<div class="main-content">
    <h2>A best place to enjoy your life</h2>
    <p style="text-align: center; margin-bottom: 40px; color: #c7a668;">Balwil Grand Hotel menawarkan pelayanan berkualitas</p>

    <div class="fasilitas-container" style="display: flex; justify-content: space-between; gap: 20px;">
        <div class="card-fasilitas" style="flex: 1; background-color: #0b132b; padding: 25px; border-radius: 8px; color: white;">
            <h3 style="color: #c7a668; margin-bottom: 10px;">🏊 Kolam Renang</h3>
            <p style="font-size: 14px; opacity: 0.9;">Kolam renang yang menghadap langsung ke lautan yang sangat indah.</p>
        </div>
        <div class="card-fasilitas" style="flex: 1; background-color: #0b132b; padding: 25px; border-radius: 8px; color: white;">
            <h3 style="color: #c7a668; margin-bottom: 10px;">🍽️ Menu Restoran </h3>
            <p style="font-size: 14px; opacity: 0.9;">Hidangan yang dimasak langsung oleh koki internasional terbaik.</p>
        </div>
        <div class="card-fasilitas" style="flex: 1; background-color: #0b132b; padding: 25px; border-radius: 8px; color: white;">
            <h3 style="color: #c7a668; margin-bottom: 10px;"> ⭐ Fasilitas Terbaik</h3>
            <p style="font-size: 14px; opacity: 0.9;">Memiliki banyak fasilitas seperti spa & massage, gym & yoga, surfing lessons, dll .</p>
        </div>
    </div>
</div>

<style>
    /* Mengunci posisi pembungkus di pojok kanan atas */
    .dropdown-login-container {
        position: absolute;
        top: 20px;
        right: 30px;
        display: inline-block;
        z-index: 1005; /* Dinaikkan agar mutlak menang bersaing lapisan dari hero-banner */
        padding-bottom: 15px;
    }

    /* Gaya Tombol Utama Kapsul Emas */
    .btn-dropdown-utama {
        background-color: #c7a668;
        color: #0b132b;
        padding: 8px 18px;
        font-size: 14px;
        font-weight: 600;
        border: 2px solid #c7a668;
        border-radius: 20px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    /* Trik 2: Membuat jembatan transparan tak terlihat antara tombol dan menu */
    .dropdown-login-container::before {
        content: "";
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        height: 15px;
        display: none;
    }
    .dropdown-login-container:hover::before {
        display: block;
    }

    /* Efek Kotak Pilihan Menu */
    .isi-dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        background-color: #0b132b;
        min-width: 180px;
        box-shadow: 0px 8px 16px rgba(0,0,0,0.3);
        border: 1px solid #c7a668;
        border-radius: 8px;
        margin-top: 5px;
        overflow: hidden;
    }

    /* Gaya Tulisan Link Pilihan di Dalam Kotak */
    .isi-dropdown-menu a {
        color: #ffffff;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        font-size: 13px;
        font-weight: 500;
        transition: background-color 0.2s;
    }

    /* Efek Saat Kursor Menyorot Pilihan Link */
    .isi-dropdown-menu a:hover {
        background-color: #1c2541;
        color: #c7a668;
    }

    /* Menampilkan kotak pilihan otomatis saat kursor mendekat */
    .dropdown-login-container:hover .isi-dropdown-menu {
        display: block;
    }

    /* Efek saat tombol utama disentuh */
    .dropdown-login-container:hover .btn-dropdown-utama {
        background-color: #1c2541;
        color: #c7a668;
    }
</style>

<?php 
// 4. Panggil footer
include('../includes/footer.php'); 
?>