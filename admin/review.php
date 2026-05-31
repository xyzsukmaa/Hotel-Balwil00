<?php include("inc_header.php") ?>
<?php
$sukses = "";
// Amankan input pencarian
$katakunci = (isset($_GET['katakunci'])) ? mysqli_real_escape_string($koneksi, $_GET['katakunci']) : "";
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Proses hapus ulasan jika ada spam atau komentar tidak layak
if ($op == 'delete') {
    // Amankan parameter ID sebelum eksekusi query
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $sql1   = "delete from review where id_review = '$id'";
    $q1     = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses     = "Ulasan berhasil dihapus";
    }
}
?>

<style>
    /* Menghilangkan paksa batasan lebar dari template bawaan jika ada */
    .container, .container-fluid {
        max-width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    /* Membuat latar belakang luar menjadi putih bersih */
    body, html {
        background-color: #ffffff !important;
    }

    /* Container Utama - FULL WIDTH dan melengkung persis Dashboard */
    .review-bg-container {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        color: #e2e8f0;
        margin: 20px auto; 
        width: 96%; 
        max-width: 1400px; 
        padding: 40px;
        border-radius: 16px !important; 
        min-height: calc(100vh - 140px);
        background: linear-gradient(rgba(10, 17, 36, 0.78), rgba(20, 30, 55, 0.88)), url('../assets/kolam.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    /* Judul Halaman Bergaya Emas Dashboard */
    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #cbb279; 
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 5px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.6);
    }
    
    .page-subtitle {
        font-size: 14px;
        color: #94a3b8;
        margin-bottom: 30px;
    }

    /* Bar Pencarian & Tombol (Efek Glassmorphism Kaca Gelap) */
    .action-bar {
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 20px;
        border-radius: 10px !important; 
        border: 1px solid rgba(203, 178, 121, 0.15);
        margin-top: 20px;
        margin-bottom: 25px;
    }

    /* Form Input Kendali */
    .input-search-custom {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(203, 178, 121, 0.25) !important;
        color: #ffffff !important;
        border-radius: 6px !important;
        font-size: 14px;
    }
    .input-search-custom::placeholder {
        color: #64748b;
    }
    .input-search-custom:focus {
        border-color: #cbb279 !important;
        box-shadow: 0 0 8px rgba(203, 178, 121, 0.3) !important;
    }

    /* Tombol Utama Emas Dashboard */
    .btn-gold {
        background: linear-gradient(135deg, #cbb279, #b39a5f) !important;
        color: #0f172a !important;
        font-weight: 600;
        border: none !important;
        border-radius: 6px !important;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }
    .btn-gold:hover {
        background: linear-gradient(135deg, #e1ca94, #cbb279) !important;
        box-shadow: 0 4px 15px rgba(203, 178, 121, 0.35);
        color: #0f172a !important;
    }

    /* Kotak Wadah Tabel (Kaca Transparan Mac) */
    .table-responsive-box {
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 12px !important;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 15px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    /* Gaya Tabel */
    .table-review-custom {
        color: #e2e8f0 !important;
        margin-bottom: 0;
        vertical-align: middle;
    }
    .table-review-custom thead th {
        background-color: rgba(15, 23, 42, 0.4) !important;
        color: #cbb279 !important;
        border-bottom: 2px solid rgba(203, 178, 121, 0.3) !important;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 1px;
        padding: 15px 10px;
    }
    .table-review-custom tbody td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        padding: 15px 10px;
        font-size: 14px;
        background: transparent !important;
        color: #cbd5e1;
    }
    .table-review-custom tbody tr:hover td {
        background: rgba(203, 178, 121, 0.05) !important;
        color: #ffffff;
    }

    /* Desain Visual Bintang Rating */
    .star-filled {
        color: #cbb279; /* Emas menyatu tema */
        text-shadow: 0 0 6px rgba(203, 178, 121, 0.6);
        font-size: 16px;
    }
    .star-empty {
        color: #475569;
        font-size: 16px;
    }
    .rating-text {
        font-size: 12px;
        color: #94a3b8;
        margin-left: 4px;
    }

    /* Keterangan komentar teks */
    .komentar-text {
        font-style: italic;
        color: #e2e8f0;
    }

    /* ==========================================================================
       TOMBOL AKSI (GHOST BORDER STYLE MATCHED)
       ========================================================================== */
    .btn-action {
        padding: 6px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 2px !important;
        display: inline-block;
        transition: all 0.2s ease;
        letter-spacing: 0.5px;
        background: transparent !important;
    }

    /* Hapus/Delete: Pink Coral Red */
    .btn-delete-custom {
        color: #ff5577 !important; 
        border: 1px solid #ff5577 !important; 
    }
    .btn-delete-custom:hover {
        background-color: #ff5577 !important;
        color: #ffffff !important; 
    }

    /* Navigasi Halaman */
    .pagination-custom {
        margin-top: 20px;
    }
    .pagination-custom .page-link {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(203, 178, 121, 0.2) !important;
        color: #94a3b8 !important;
        border-radius: 4px !important;
        padding: 8px 16px;
    }
    .pagination-custom .page-link:hover {
        background: #cbb279 !important;
        color: #0f172a !important;
    }
    .pagination-custom .page-item.active .page-link {
        background: #cbb279 !important;
        border-color: #cbb279 !important;
        color: #0f172a !important;
    }
</style>

<div class="review-bg-container">

    <h1 class="page-title">| Ulasan dan Rating Tamu</h1>
    <div class="page-subtitle">Selamat datang kembali di Panel Moderasi Ulasan, Feedback, dan Penilaian Tamu.</div>
    
    <?php if ($sukses): ?>
        <div class="alert alert-success" style="border-radius: 6px; background: rgba(19, 219, 182, 0.15); color: #13dbb6; border-color: rgba(19, 219, 182, 0.3); margin-top: 15px;">
            🛈 <?php echo $sukses ?>
        </div>
    <?php endif; ?>

    <div class="action-bar">
        <form class="row g-3 align-items-center" method="get">
            <div class="col-auto" style="min-width: 350px;">
                <input type="text" class="form-control input-search-custom" placeholder="Cari Nama Tamu / Isi Ulasan..." name="katakunci" value="<?php echo $katakunci ?>" />
            </div>
            <div class="col-auto">
                <input type="submit" name="cari" value="Cari Ulasan" class="btn btn-gold" />
            </div>
        </form>
    </div>

    <div class="table-responsive-box table-responsive">
        <table class="table table-review-custom">
            <thead>
                <tr>
                    <th class="col-1" style="text-align: center;">#</th>
                    <th>Nama Tamu</th>
                    <th class="col-2">Rating</th>
                    <th>Komentar</th>
                    <th>Tanggal Ulasan</th>
                    <th class="col-1" style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqltambahan = "";
                $per_halaman = 10;
                
                // Pencarian berdasarkan nama tamu atau isi teks komentar
                if ($katakunci != '') {
                    $array_katakunci = explode(" ", $katakunci);
                    for ($x = 0; $x < count($array_katakunci); $x++) {
                        $sqlcari[] = "(user.nama like '%" . $array_katakunci[$x] . "%' or review.komentar like '%" . $array_katakunci[$x] . "%')";
                    }
                    $sqltambahan    = " where " . implode(" or ", $sqlcari);
                }
                
                // Relasi ganda untuk mendapatkan nama pengguna yang memberikan ulasan
                $sql1   = "select review.*, user.nama 
                           from review 
                           left join booking on review.id_booking = booking.id_booking 
                           left join user on booking.id_user = user.id_user 
                           $sqltambahan";
                           
                $page   = isset($_GET['page'])?(int)$_GET['page']:1;
                $mulai  = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
                $q1     = mysqli_query($koneksi,$sql1);
                $total  = mysqli_num_rows($q1);
                $pages  = ceil($total / $per_halaman);
                $nomor  = $mulai + 1;
                
                $sql1   = $sql1." order by review.id_review desc limit $mulai,$per_halaman";
                $q1     = mysqli_query($koneksi, $sql1);
              
                if (mysqli_num_rows($q1) == 0) {
                    echo "<tr><td colspan='6' class='text-center text-muted py-4'>Data ulasan tidak ditemukan.</td></tr>";
                }

                while ($r1 = mysqli_fetch_array($q1)) {
                ?>
                    <tr>
                        <td style="text-align: center; color: #64748b;"><?php echo $nomor++ ?></td>
                        <td><b style="color: #ffffff;"><?php echo $r1['nama'] ?? 'Anonim' ?></b></td>
                        <td>
                            <?php 
                            $bintang = (int)$r1['rating'];
                            for($i = 1; $i <= 5; $i++){
                                if($i <= $bintang){
                                    echo '<span class="star-filled">★</span>';
                                } else {
                                    echo '<span class="star-empty">☆</span>';
                                }
                            }
                            echo "<span class='rating-text'>(" . $bintang . "/5)</span>";
                            ?>
                        </td>
                        <td class="komentar-text">"<?php echo $r1['komentar']; ?>"</td>
                        <td><code><?php echo $r1['tanggal'] ?></code></td>
                        <td style="text-align: center;">
                            <a href="review.php?op=delete&id=<?php echo $r1['id_review'] ?>" class="btn-action btn-delete-custom text-decoration-none" onclick="return confirm('Hapus ulasan dari tamu ini?')">Delete</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation" class="pagination-custom">
        <ul class="pagination mb-0">
            <?php 
            $cari = isset($_GET['cari'])? $_GET['cari'] : "";
            for($i=1; $i <= $pages; $i++){
                $active_class = ($page == $i) ? "active" : "";
                ?>
                <li class="page-item <?php echo $active_class ?>">
                    <a class="page-link" href="review.php?katakunci=<?php echo $katakunci?>&cari=<?php echo $cari?>&page=<?php echo $i ?>"><?php echo $i ?></a>
                </li>
                <?php
            }
            ?>
        </ul>
    </nav>

</div>

<?php include("inc_footer.php") ?>