<?php
// 1. KONEKSI KE DATABASE
$koneksi = mysqli_connect("localhost", "root", "", "db_hotel");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Memanggil navigasi dari folder includes
include '../includes/navbar.php';

// 2. PROSES INSERT DATA KETIKA TOMBOL DIKLIK
if (isset($_POST['kirim_review'])) {
    // Ambil data dari form (Tanpa kolom 'nama' agar tidak error lagi)
    $id_booking = mysqli_real_escape_string($koneksi, $_POST['id_booking']);
    $rating     = mysqli_real_escape_string($koneksi, $_POST['rating']);
    $komentar   = mysqli_real_escape_string($koneksi, $_POST['komentar']);
    $tanggal    = date('Y-m-d'); // Mengambil tanggal hari ini secara otomatis

    // Query diperbarui: Hanya memasukkan kolom yang pasti ada di tabel review kamu
    $sql_insert = "INSERT INTO review (id_booking, rating, komentar, tanggal) 
                   VALUES ('$id_booking', '$rating', '$komentar', '$tanggal')";

    if (mysqli_query($koneksi, $sql_insert)) {
        echo "<script>
                alert('🎉 Terima kasih! Ulasan Anda berhasil dikirim.');
                window.location = 'review.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('❌ Gagal mengirim ulasan, silakan coba lagi.');</script>";
    }
}
?>

<style>
    .form-review-container {
        max-width: 600px;
        margin: 40px auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .form-review-container h2 {
        color: #0b132b;
        margin-bottom: 8px;
    }
    .form-review-subtitle {
        color: #5c677d;
        font-size: 14px;
        margin-bottom: 25px;
    }
    .star-rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
        margin-top: 5px;
    }
    .star-rating-input input {
        display: none;
    }
    .star-rating-input label {
        font-size: 30px;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }
    .star-rating-input input:checked ~ label,
    .star-rating-input label:hover,
    .star-rating-input label:hover ~ label {
        color: #ffb703;
    }
    .btn-kirim-review {
        background: #0b132b;
        color: #fff;
        border: none;
        padding: 12px 25px;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .btn-kirim-review:hover {
        background: #1c2541;
    }
</style>

<div class="main-content">
    <div class="form-review-container">
        <h2>Tulis Ulasan Anda</h2>
        <p class="form-review-subtitle">Bagikan pengalaman seru Anda selama menginap di Balwil Grand Hotel.</p>

        <form action="" method="POST" class="booking-form">
            
            <div class="form-group">
                <label for="id_booking">Pilih Kamar yang Pernah Anda Pesan</label>
                <select id="id_booking" name="id_booking" required>
                    <option value="" disabled selected>-- Pilih riwayat kode booking Anda --</option>
                    <?php
                    // Mengambil data booking yang berstatus 'Sudah Bayar'
                    $sql_get_bkg = "SELECT booking.id_booking, tipe_kamar.nama_tipe 
                                    FROM booking
                                    INNER JOIN kamar ON booking.id_kamar = kamar.id_kamar
                                    INNER JOIN tipe_kamar ON kamar.id_tipe = tipe_kamar.id_tipe
                                    WHERE booking.status = 'Sudah Bayar'";
                    $q_get_bkg = mysqli_query($koneksi, $sql_get_bkg);
                    while($bkg = mysqli_fetch_array($q_get_bkg)) {
                        echo "<option value='".$bkg['id_booking']."'>#BKG-".str_pad($bkg['id_booking'], 3, '0', STR_PAD_LEFT)." (".$bkg['nama_tipe'].")</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Berikan Rating Bintang</label>
                <div class="star-rating-input">
                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5">★</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                </div>
            </div>

            <div class="form-group">
                <label for="komentar">Ulasan / Komentar</label>
                <textarea id="komentar" name="komentar" rows="5" placeholder="Tuliskan pendapat Anda tentang pelayanan, kebersihan, atau fasilitas kami..." required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ddd; font-family: inherit; resize: vertical;"></textarea>
            </div>

            <div class="form-actions" style="margin-top: 20px;">
                <button type="submit" name="kirim_review" class="btn-kirim-review">Kirim Ulasan Resmi</button>
            </div>
        </form>
    </div>
</div>

<?php
include '../includes/footer.php';
?>