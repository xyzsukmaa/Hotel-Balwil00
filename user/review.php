<?php
// 1. KONEKSI LANGSUNG KE DATABASE
$koneksi = mysqli_connect("localhost", "root", "", "db_hotel");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Memanggil navigasi dari folder includes
include '../includes/navbar.php';
?>

<div class="main-content">
    <h2 class="review-title">Ulasan & Testimoni Tamu</h2>
    <p class="review-subtitle">Apa kata mereka yang telah merasakan pengalaman menginap mewah di Balwil Grand Hotel?</p>

    <div class="reviews-grid">
        
        <?php 
        // 2. QUERY UTAMA: Mengambil data review asli dari database
        // Catatan: Sesuaikan nama kolom & tabel di bawah ini dengan database kamu jika berbeda
        $sql_review = "SELECT review.*, tipe_kamar.nama_tipe, booking.id_user 
                       FROM review
                       INNER JOIN booking ON review.id_booking = booking.id_booking
                       INNER JOIN kamar ON booking.id_kamar = kamar.id_kamar
                       INNER JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe
                       ORDER BY review.id_review DESC";

        $q_review = mysqli_query($koneksi, $sql_review);

        // Jika belum ada ulasan sama sekali di database
        if (mysqli_num_rows($q_review) == 0) {
            echo "<p style='grid-column: 1/-1; text-align: center; color: #888; padding: 40px 0;'>Belum ada ulasan dari tamu.</p>";
        }

        // Loop data dari MySQL
        while ($rev = mysqli_fetch_array($q_review)) {
            
            // Format tanggal agar rapi (Contoh: 04 Jun 2026)
            $tanggal_review = date('d M Y', strtotime($rev['tanggal']));
            
            // Mengakali nama tamu: jika di tabel review belum menyimpan nama manual, 
            // kita bisa pakai simulasi nama dari ID User atau kolom nama yang ada di tabel booking/user kamu.
            // Di sini kita contohkan mengambil kolom 'nama' (sesuaikan jika kolomnya bernama 'nama_user' dll)
            $nama_tamu = isset($rev['nama']) ? $rev['nama'] : "Tamu Hotel #" . $rev['id_user'];
        ?>
            <div class="review-card">
                <div class="review-header">
                    <div class="user-info">
                        <h3><?php echo $nama_tamu; ?></h3>
                        <span class="stayed-room">🛋️ Menginap di <?php echo $rev['nama_tipe']; ?></span>
                    </div>
                    <span class="review-date"><?php echo $tanggal_review; ?></span>
                </div>

                <div class="review-stars">
                    <?php 
                    // Menampilkan jumlah bintang dinamis sesuai angka rating di database (1-5)
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rev['rating']) {
                            echo '<span class="star filled">★</span>'; // Bintang emas kuning
                        } else {
                            echo '<span class="star">★</span>'; // Bintang abu-abu kosong
                        }
                    }
                    ?>
                </div>

                <p class="review-text">"<?php echo $rev['komentar']; ?>"</p>
            </div>
        <?php 
        } 
        ?>

    </div>

    <div class="add-review-section">
        <h3>Sudah Selesai Menginap?</h3>
        <p>Bagikan pengalaman berharga Anda selama berada di hotel kami.</p>
        <button class="btn-tulis-review" onclick="location.href='input_review.php'">Tulis Ulasan Anda</button>
    </div>
</div>

<?php
// Memanggil footer dari folder includes
include '../includes/footer.php';
?>