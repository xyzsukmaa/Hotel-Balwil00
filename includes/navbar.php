<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balwil Hotel</title>
    <link rel="stylesheet" href="/web-hotel/Balwil00-Hotel/css/style.css">
</head>
<body>


<header style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px 0 15px 0; height: auto; overflow: hidden;">
    
    <div class="logo-container" style="display: flex; justify-content: center; align-items: center; width: 100%; height: 160px; overflow: hidden; margin-bottom: 2px;">
        
        <img src="../assets/logobaru.png" alt="Logo Hotel" class="nav-logo" style="width: 130px; height: auto; display: block; border-radius: 0; transform: scale(4.0); transform-origin: center;">
        
    </div>
    
    <div class="hotel-title" style="font-size: 40px; font-weight: bold; color: #ffffff; text-align: center; margin-bottom: 10px; z-index: 2;">Balwil Grand Hotel</div>

    <nav>
        <ul class="nav-menu">
            <li><a href="/web-hotel/Balwil00-Hotel/user/home.php" class="<?php echo ($current_page == 'home') ? 'active' : ''; ?>">Beranda</a></li>
            <li class="dot">&#9679;</li>
            <li><a href="/web-hotel/Balwil00-Hotel/user/kamar.php" class="<?php echo ($current_page == 'kamar') ? 'active' : ''; ?>">Kamar</a></li>
            <li class="dot">&#9679;</li>
            <li><a href="/web-hotel/Balwil00-Hotel/user/booking.php" class="<?php echo ($current_page == 'booking') ? 'active' : ''; ?>">Booking</a></li>
            <li class="dot">&#9679;</li>
            <li><a href="/web-hotel/Balwil00-Hotel/user/pembayaran.php" class="<?php echo ($current_page == 'pembayaran') ? 'active' : ''; ?>">Pembayaran</a></li>
            <li class="dot">&#9679;</li>
            <li><a href="/web-hotel/Balwil00-Hotel/user/review.php" class="<?php echo ($current_page == 'review') ? 'active' : ''; ?>">Review</a></li>
            <li class="dot">&#9679;</li>
            <li><a href="/web-hotel/Balwil00-Hotel/user/riwayat.php" class="<?php echo ($current_page == 'riwayat') ? 'active' : ''; ?>">Riwayat</a></li>
        </ul>
    </nav>
</header>