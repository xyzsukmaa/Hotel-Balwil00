<?php
// 1. KONEKSI KE DATABASE (Diselipkan di paling atas tanpa merusak HTML)
$koneksi = mysqli_connect("localhost", "root", "", "db_hotel");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Memanggil navigasi dari folder includes
include '../includes/navbar.php';

// 2. FUNGSI UNTUK MENGHITUNG SISA STOK KAMAR SECARA OTOMATIS
function cekSisaKamar($id_tipe, $koneksi) {
    $query = "SELECT COUNT(*) AS sisa FROM kamar WHERE id_tipe = '$id_tipe' AND status_kamar = 'Tersedia'";
    $eksekusi = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_array($eksekusi);
    return $data['sisa'];
}
?>

<div class="main-content">
    <h2 class="rooms-title">Pilihan Kamar Balwil Grand Hotel</h2>
    <p class="rooms-subtitle">Setiap kamar dirancang khusus untuk memberikan kenyamanan maksimal dan ketenangan selama Anda menginap.</p>

    <div class="rooms-container">

        <?php $stok_std = cekSisaKamar(1, $koneksi); ?>
        <div class="room-card">
            <div class="room-image">
                <img src="../assets/superior.jpg" alt="Standard Room">
            </div>
            <div class="room-details">
                <?php if ($stok_std <= 5 && $stok_std > 0) : ?>
                    <span class="room-tag" style="background-color: #e63946; color: #fff;"> Sisa <?= $stok_std; ?> Kamar!</span>
                <?php elseif ($stok_std == 0) : ?>
                    <span class="room-tag" style="background-color: #6c757d; color: #fff;"> Kamar Penuh</span>
                <?php else : ?>
                    <span class="room-tag">Paling Populer</span>
                <?php endif; ?>

                <h3>Standard Room</h3>
                <p class="room-desc">Kamar minimalis modern yang nyaman, sangat cocok untuk perjalanan atau liburan singkat Anda.</p>
                <ul class="room-features">
                    <li>📐 22 m²</li>
                    <li>👥 2 Dewasa</li>
                    <li>🛏️ Queen Bed</li>
                    <li>🚿 Shower Kamar Mandi</li>
                </ul>
                <div class="room-price-action">
                    <span class="room-price">Rp 550.000<small>/ malam</small></span>
                    <?php if ($stok_std > 0) : ?>
                        <a href="booking.php?id_tipe=1" class="btn-book-room">Pesan Kamar</a>
                    <?php else : ?>
                        <a href="#" class="btn-book-room" style="background: #6c757d; cursor: not-allowed; pointer-events: none;">Habis</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php $stok_sup = cekSisaKamar(2, $koneksi); ?>
        <div class="room-card">
            <div class="room-image">
                <img src="../assets/deluxe.jpg" alt="Superior Room">
            </div>
            <div class="room-details">
                <?php if ($stok_sup <= 5 && $stok_sup > 0) : ?>
                    <span class="room-tag" style="background-color: #e63946; color: #fff;"> Sisa <?= $stok_sup; ?> Kamar!</span>
                <?php elseif ($stok_sup == 0) : ?>
                    <span class="room-tag" style="background-color: #6c757d; color: #fff;"> Kamar Penuh</span>
                <?php else : ?>
                    <span class="room-tag">Pilihan Terbaik</span>
                <?php endif; ?>

                <h3>Superior Room</h3>
                <p class="room-desc">Nikmati ruang yang lebih luas dengan pemandangan laut langsung dari jendela kamar Anda.</p>
                <ul class="room-features">
                    <li>📐 32 m²</li>
                    <li>👥 2 Dewasa</li>
                    <li>🛏️ King Bed</li>
                    <li>🚿 Shower & Balkon</li>
                </ul>
                <div class="room-price-action">
                    <span class="room-price">Rp 850.000<small>/ malam</small></span>
                    <?php if ($stok_sup > 0) : ?>
                        <a href="booking.php?id_tipe=2" class="btn-book-room">Pesan Kamar</a>
                    <?php else : ?>
                        <a href="#" class="btn-book-room" style="background: #6c757d; cursor: not-allowed; pointer-events: none;">Habis</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php $stok_dlx = cekSisaKamar(3, $koneksi); ?>
        <div class="room-card">
            <div class="room-image">
                <img src="../assets/kamarbaru.jpg" alt="Deluxe Room">
            </div>
            <div class="room-details">
                <?php if ($stok_dlx <= 5 && $stok_dlx > 0) : ?>
                    <span class="room-tag" style="background-color: #e63946; color: #fff;"> Sisa <?= $stok_dlx; ?> Kamar!</span>
                <?php elseif ($stok_dlx == 0) : ?>
                    <span class="room-tag" style="background-color: #6c757d; color: #fff;"> Kamar Penuh</span>
                <?php else : ?>
                    <span class="room-tag">Kemewahan Terjangkau</span>
                <?php endif; ?>

                <h3>Deluxe Room</h3>
                <p class="room-desc">Kamar luas dengan interior premium dan fasilitas lengkap, menjamin istirahat malam Anda sangat berkesan.</p>
                <ul class="room-features">
                    <li>📐 42 m²</li>
                    <li>👥 2 Dewasa</li>
                    <li>🛏️ King Bed</li>
                    <li>🚿 Shower & Living room</li>
                </ul>
                <div class="room-price-action">
                    <span class="room-price">Rp 1.250.000<small>/ malam</small></span>
                    <?php if ($stok_dlx > 0) : ?>
                        <a href="booking.php?id_tipe=3" class="btn-book-room">Pesan Kamar</a>
                    <?php else : ?>
                        <a href="#" class="btn-book-room" style="background: #6c757d; cursor: not-allowed; pointer-events: none;">Habis</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php $stok_sut = cekSisaKamar(4, $koneksi); ?>
        <div class="room-card">
            <div class="room-image">
                <img src="../assets/suite.jpg" alt="Suite Room">
            </div>
            <div class="room-details">
                <?php if ($stok_sut <= 5 && $stok_sut > 0) : ?>
                    <span class="room-tag" style="background-color: #e63946; color: #fff;"> Sisa <?= $stok_sut; ?> Kamar!</span>
                <?php elseif ($stok_sut == 0) : ?>
                    <span class="room-tag" style="background-color: #6c757d; color: #fff;"> Kamar Penuh</span>
                <?php else : ?>
                    <span class="room-tag">Kemewahan Mutlak</span>
                <?php endif; ?>

                <h3>Suite Room</h3>
                <p class="room-desc">Kamar kasta tertinggi dengan ruang tamu terpisah, bathtub mewah, dan akses pemandangan laut privat.</p>
                <ul class="room-features">
                    <li>📐 55 m²</li>
                    <li>👥 2 Dewasa, 1 Anak</li>
                    <li>🛏️ Super King Bed</li>
                    <li>🛁 Bathtub & Private Lounge</li>
                </ul>
                <div class="room-price-action">
                    <span class="room-price">Rp 1.650.000<small>/ malam</small></span>
                    <?php if ($stok_sut > 0) : ?>
                        <a href="booking.php?id_tipe=4" class="btn-book-room">Pesan Kamar</a>
                    <?php else : ?>
                        <a href="#" class="btn-book-room" style="background: #6c757d; cursor: not-allowed; pointer-events: none;">Habis</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php $stok_prs = cekSisaKamar(5, $koneksi); ?>
        <div class="room-card">
            <div class="room-image">
                <img src="../assets/presidential.jpg" alt="Presidential Room">
            </div>
            <div class="room-details">
                <?php if ($stok_prs <= 2 && $stok_prs > 0) : ?>
                    <span class="room-tag" style="background-color: #e63946; color: #fff;"> Sisa <?= $stok_prs; ?> Kamar!</span>
                <?php elseif ($stok_prs == 0) : ?>
                    <span class="room-tag" style="background-color: #6c757d; color: #fff;"> Kamar Penuh</span>
                <?php else : ?>
                    <span class="room-tag">Eksklusif Sultan</span>
                <?php endif; ?>

                <h3>Presidential Room</h3>
                <p class="room-desc">Kamar termewah berukuran masif dengan panorama laut lepas 180 derajat langsung dari ranjang tidur Anda.</p>
                <ul class="room-features">
                    <li>📐 85 m²</li>
                    <li>👥 4 Dewasa</li>
                    <li>🛏️ 2 Super King Bed</li>
                    <li>🛁 Jacuzzi & Private Pool Access</li>
                </ul>
                <div class="room-price-action">
                    <span class="room-price">Rp 2.500.000<small>/ malam</small></span>
                    <?php if ($stok_prs > 0) : ?>
                        <a href="booking.php?id_tipe=5" class="btn-book-room">Pesan Kamar</a>
                    <?php else : ?>
                        <a href="#" class="btn-book-room" style="background: #6c757d; cursor: not-allowed; pointer-events: none;">Habis</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div> 
</div>

<?php
// Memanggil footer dari folder includes
include '../includes/footer.php';
?>