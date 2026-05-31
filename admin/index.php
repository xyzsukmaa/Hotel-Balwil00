<?php include("inc_header.php")?>

<?php
// PROTEKSI: Cek apakah session admin_username sudah ada. 
if (!isset($_SESSION['admin_username'])) {
    header("location:login.php");
    exit();
}

// Ambil data statistik dari database
$q_pending = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM booking WHERE status = 'Pending'");
$r_pending = mysqli_fetch_array($q_pending);

$q_verifikasi = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM booking WHERE status = 'Menunggu Verifikasi'");
$r_verifikasi = mysqli_fetch_array($q_verifikasi);

$q_kamar = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kamar WHERE status_kamar = 'Tersedia'");
$r_kamar = mysqli_fetch_array($q_kamar);

$q_pendapatan = mysqli_query($koneksi, "SELECT SUM(total_harga) AS total FROM booking WHERE status = 'Confirmed'");
$r_pendapatan = mysqli_fetch_array($q_pendapatan);
$pendapatan = $r_pendapatan['total'] ?? 0;
?>

<style>
    /* Mengubah Container Utama Dashboard */
    .dashboard-bg-container {
        font-family: 'Segoe UI', Roboto, sans-serif;
        color: #ffffff;
        
        /* ATUR JARAK DI SINI: */
        margin-top: 15px; /* Memberikan celah putih sedikit di bagian atas antara navbar & foto */
        margin-left: 10px; /* Menggeser dashboard agak ke kanan sedikit sesuai maumu */
        margin-right: 10px;
        
        padding: 40px;
        border-radius: 12px; /* Membuat sudut background foto melengkung rapi */
        min-height: calc(100vh - 140px); /* Menyesuaikan tinggi agar tetap proporsional */
        
        /* Memanggil foto kolam.jpg dari folder assets */
        background: linear-gradient(rgba(11, 19, 43, 0.75), rgba(28, 37, 65, 0.85)), url('../assets/kolam.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .dashboard-title {
        font-size: 32px;
        font-weight: 700;
        color: #c7a668; /* Aksen Emas Balwil */
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 5px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    .dashboard-subtitle {
        color: #e2e8f0;
        font-size: 15px;
        margin-bottom: 35px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.5);
    }

    /* Grid layout untuk Kotak Informasi Statistik */
    .stat-grid {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 40px;
    }

    /* Efek Kaca Transparan Mewah (Glassmorphism) */
    .stat-card {
        flex: 1;
        min-width: 220px;
        background: rgba(11, 19, 43, 0.65);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 25px 20px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(199, 166, 104, 0.2); 
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* Hover Glowing Pulse & Lift Up */
    .stat-card:hover {
        transform: translateY(-6px);
        background: rgba(11, 19, 43, 0.8);
        border-color: rgba(199, 166, 104, 0.8);
        box-shadow: 0 15px 35px rgba(199, 166, 104, 0.25), 0 0 15px rgba(199, 166, 104, 0.15);
    }

    .stat-label {
        margin: 0;
        color: #cbd5e1;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-value {
        font-size: 34px;
        font-weight: 700;
        margin: 15px 0 0 0;
        line-height: 1;
    }

    /* Pewarnaan Angka Neon Glowing */
    .color-pending { color: #ff6b6b; text-shadow: 0 0 12px rgba(255, 107, 107, 0.4); }
    .color-verif { color: #f39c12; text-shadow: 0 0 12px rgba(243, 156, 18, 0.4); }
    .color-kamar { color: #2ec4b6; text-shadow: 0 0 12px rgba(46, 196, 182, 0.4); }
    .color-omset { color: #c7a668; text-shadow: 0 0 12px rgba(199, 166, 104, 0.4); font-size: 26px; margin-top: 22px; }

    /* Kotak Petunjuk Operasional Bawah */
    .instruction-box {
        background: rgba(11, 19, 43, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-left: 5px solid #c7a668;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        border-top: 1px solid rgba(255,255,255,0.05);
        border-right: 1px solid rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .instruction-box h3 {
        color: #c7a668;
        font-size: 19px;
        font-weight: 600;
        margin-top: 0;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
    }

    .instruction-box p {
        color: #e2e8f0;
        line-height: 1.7;
        font-size: 14px;
        margin: 0;
    }
</style>

<div class="dashboard-bg-container">
    
    <h1 class="dashboard-title">Dashboard</h1>
    <p class="dashboard-subtitle">
        Selamat datang kembali, <b><?php echo $_SESSION['admin_username']?></b> di Panel Utama Administrasi Hotel.
    </p>

    <div class="stat-grid">
        
        <div class="stat-card">
            <h4 class="stat-label">⏳ Booking Pending</h4>
            <p class="stat-value color-pending"><?php echo $q_pending ? $r_pending['total'] : '0' ?></p>
        </div>
        
        <div class="stat-card">
            <h4 class="stat-label">🔍 Perlu Verifikasi</h4>
            <p class="stat-value color-verif"><?php echo $q_verifikasi ? $r_verifikasi['total'] : '0' ?></p>
        </div>

        <div class="stat-card">
            <h4 class="stat-label">🏨 Kamar Tersedia</h4>
            <p class="stat-value color-kamar">
                <?php echo $q_kamar ? $r_kamar['total'] : '0' ?> 
                <span style="font-size: 14px; color: #94a3b8; font-weight: 400;">/ 100 unit</span>
            </p>
        </div>

        <div class="stat-card">
            <h4 class="stat-label">💰 Total Pendapatan</h4>
            <p class="stat-value color-omset">Rp <?php echo number_format($pendapatan, 0, ',', '.') ?></p>
        </div>
        
    </div>

    <div class="instruction-box">
        <h3>📋 Petunjuk Operasional Resepsionis</h3>
        <p>
            Gunakan menu navigasi di atas untuk mengelola data master hotel dan mengontrol penuh transaksi tamu. Jika indikator pada angka <b style="color: #f39c12; text-shadow: 0 0 5px rgba(243,156,18,0.3);">Perlu Verifikasi</b> bertambah, harap segera buka menu manajemen pembayaran untuk memeriksa kesesuaian bukti transfer dana masuk, lalu konfirmasi status pesanan menjadi <i style="color: #2ec4b6; font-weight: 600;">Confirmed</i> agar kamar resmi dialokasikan oleh sistem.
        </p>
    </div>

</div>

<?php include("inc_footer.php")?>