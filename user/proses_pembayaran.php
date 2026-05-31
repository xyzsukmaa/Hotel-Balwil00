<?php
// 1. KONEKSI KE DATABASE
$koneksi = mysqli_connect("localhost", "root", "", "db_hotel");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 2. CEK APAKAH TOMBOL SUDAH DIKLIK
if (isset($_POST['bayar_sekarang'])) {
    // Tangkap data inputan hidden dan radio button dari halaman pembayaran
    $id_booking   = mysqli_real_escape_string($koneksi, $_POST['id_booking']);
    $total_akhir  = mysqli_real_escape_string($koneksi, $_POST['total_akhir_input']);
    $metode_bayar = mysqli_real_escape_string($koneksi, $_POST['metode']);
    $promo_sukses = mysqli_real_escape_string($koneksi, $_POST['promo_terpakai']);

    // 3. UPDATE STATUS DI TABEL BOOKING ADMIN
    // Status diubah menjadi 'Menunggu Verifikasi' agar terbaca di Dashboard Admin & Riwayat User
    $sql_update_booking = "UPDATE booking SET 
                           total_harga = '$total_akhir', 
                           status = 'Menunggu Verifikasi' 
                           WHERE id_booking = '$id_booking'";

    if (mysqli_query($koneksi, $sql_update_booking)) {
        
        // [OPSIONAL] Jika kamu punya tabel pembayaran tersendiri untuk mencatat riwayat metode:
        // Di sini kita sekalian masukkan ke tabel pembayaran biar datanya sinkron
        $sql_invoice = "INSERT INTO pembayaran (id_booking, metode, status_bayar, tanggal_bayar) 
                        VALUES ('$id_booking', '$metode_bayar', 'Belum Diverifikasi', NOW())";
        mysqli_query($koneksi, $sql_invoice);

        // 4. JIKA SUKSES, LEMPAR USER KE HALAMAN RIWAYAT
        echo "<script>
                alert('🎉 Pembayaran menggunakan " . $metode_bayar . " berhasil diproses! Silakan tunggu verifikasi admin.');
                window.location = 'riwayat.php'; 
              </script>";
    } else {
        // Jika query gagal dijalankan
        echo "<script>
                alert('❌ Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                window.location = 'pembayaran.php';
              </script>";
    }
} else {
    // Jika user mencoba masuk ke file ini langsung tanpa klik tombol bayar
    header("Location: booking.php");
    exit;
}
?>