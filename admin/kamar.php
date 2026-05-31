<?php include("inc_header.php") ?>
<?php
$sukses = "";
// Amankan input kata kunci pencarian
$katakunci = (isset($_GET['katakunci'])) ? mysqli_real_escape_string($koneksi, $_GET['katakunci']) : "";
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// Proses Hapus Data Kamar Fisik
if ($op == 'delete') {
    // Amankan ID sebelum masuk ke query
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $sql1   = "delete from kamar where id_kamar = '$id'";
    $q1     = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses     = "Berhasil menghapus data kamar";
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

    /* Membuat latar belakang luar menjadi gelap pekat transparan agar menyatu */
    body, html {
        background-color: #ffffff !important;
    }

    /* Container Utama - Dibuat FULL WIDTH dan melengkung persis Dashboard */
    .kamar-bg-container {
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
        font-size: 34px; /* Di-zoom sedikit lebih besar */
        font-weight: 800; /* Dipertebal */
        color: #cbb279; 
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 5px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.6);
    }
    
    .page-subtitle {
        font-size: 16px; /* Di-zoom dari 14px */
        color: #94a3b8;
        margin-bottom: 30px;
    }

    /* Bar Pencarian & Tombol (Efek Glassmorphism Kaca Gelap) */
    .action-bar {
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 24px; /* Ditambah paddingnya agar seimbang */
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
        font-size: 16px; /* Di-zoom agar teks inputan jelas */
        padding: 10px 16px !important;
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
        font-weight: 700; /* Dipertebal */
        font-size: 16px; /* Di-zoom dari 14px */
        border: none !important;
        border-radius: 6px !important;
        padding: 10px 24px;
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
        padding: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    /* ==========================================================================
       ZOOM DATA TABEL & ANGKA NOMOR (Diselaraskan dengan Kejelasan Dashboard)
       ========================================================================== */
    .table-kamar-custom {
        color: #e2e8f0 !important;
        margin-bottom: 0;
        vertical-align: middle;
    }
    .table-kamar-custom thead th {
        background-color: rgba(15, 23, 42, 0.4) !important;
        color: #cbb279 !important;
        border-bottom: 2px solid rgba(203, 178, 121, 0.3) !important;
        text-transform: uppercase;
        font-size: 15px; /* Di-zoom dari 13px */
        font-weight: 700;
        letter-spacing: 1px;
        padding: 18px 12px;
    }
    .table-kamar-custom tbody td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        padding: 18px 12px;
        font-size: 17px; /* DATA UTAMA DI-ZOOM (Dari 14px ke 17px agar sangat terbaca) */
        background: transparent !important;
        color: #cbd5e1;
    }
    
    /* Membuat nomor kamar bold murni dan kontras tinggi seperti angka dashboard */
    .table-kamar-custom tbody td b {
        font-size: 19px; /* Angka Nomor Kamar dibuat lebih besar & menonjol */
        color: #ffffff !important;
        font-weight: 700;
    }

    .table-kamar-custom tbody tr:hover td {
        background: rgba(203, 178, 121, 0.05) !important;
        color: #ffffff;
    }

    /* Gaya Lencana Status (DI-ZOOM & DIBIKIN MAKSIMAL KONTRAS) */
    .badge-status {
        font-size: 13px; /* Di-zoom dari 11px */
        font-weight: 700; /* Dipertebal maksimal */
        text-transform: uppercase;
        padding: 8px 16px; /* Diperlebar kotaknya */
        border-radius: 6px !important;
        letter-spacing: 0.5px;
        display: inline-block;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }
    .status-tersedia { 
        background-color: #77cabb !important; 
        color: #0f172a !important; /* Teks dibikin gelap murni agar kontras tinggi di atas hijau pastel */
    }
    .status-terisi { 
        background-color: #ff4a6b !important; 
        color: #ffffff !important; 
    }
    .status-maintenance { 
        background-color: #d39f3e !important; 
        color: #0f172a !important; /* Teks dibikin gelap murni agar tidak kusam di atas warna amber */
    }

    /* TOMBOL AKSI (DI-ZOOM DAN DI-STRETCH LEBIH NYAMAN DIKLIK) */
    .btn-action {
        padding: 8px 18px; /* Ditambah ruang kliknya */
        font-size: 13px; /* Di-zoom dari 11px */
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 6px !important;
        display: inline-block;
        transition: all 0.2s ease;
        letter-spacing: 0.5px;
    }

    .btn-edit-custom {
        background-color: #1e293b !important; 
        color: #38bdf8 !important; 
        border: 1px solid rgba(56, 189, 248, 0.4) !important; 
    }
    .btn-edit-custom:hover {
        background-color: #cbb279 !important;
        color: #0f172a !important;
        border-color: #cbb279 !important;
    }

    .btn-delete-custom {
        background-color: #27161a !important; 
        color: #f43f5e !important; 
        border: 1px solid rgba(244, 63, 94, 0.4) !important; 
    }
    .btn-delete-custom:hover {
        background-color: #f43f5e !important;
        color: #ffffff !important; 
        border-color: #f43f5e !important;
    }

    /* Navigasi Halaman / Pagination Angka Bawah (DI-ZOOM SEPERTI KOTAK MENU) */
    .pagination-custom {
        margin-top: 25px;
    }
    .pagination-custom .page-link {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(203, 178, 121, 0.2) !important;
        color: #ffffff !important; /* Diubah jadi putih cerah */
        border-radius: 6px !important;
        padding: 10px 18px; /* Kotak angka dibuat lebih besar dan mantap */
        font-size: 16px; /* Ukuran angka di-zoom */
        font-weight: 600;
    }
    .pagination-custom .page-link:hover {
        background: #cbb279 !important;
        color: #0f172a !important;
    }
    .pagination-custom .page-item.active .page-link {
        background: #cbb279 !important;
        border-color: #cbb279 !important;
        color: #0f172a !important;
        font-weight: 700;
    }
</style>

<div class="kamar-bg-container">

    <h1 class="page-title">| Data Keterediaan Kamar Hotel</h1>
    <div class="page-subtitle">Selamat datang kembali di Panel Manajemen Kamar Real-time.</div>
    
    <?php if ($sukses): ?>
        <div class="alert alert-success" style="border-radius: 6px; background: rgba(19, 219, 182, 0.15); color: #13dbb6; border-color: rgba(19, 219, 182, 0.3); margin-top: 15px; font-size: 16px; font-weight: 600;">
            🛈 <?php echo $sukses ?>
        </div>
    <?php endif; ?>

    <div class="action-bar d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <form class="d-flex w-100" method="get" style="max-width: 450px;">
            <input type="text" class="form-control input-search-custom me-2" placeholder="Cari Nomor atau Tipe Kamar..." name="katakunci" value="<?php echo $katakunci ?>" />
            <input type="submit" name="cari" value="Cari" class="btn btn-gold" />
        </form>
        
        <a href="kamar_input.php" class="w-100 w-md-auto text-decoration-none">
            <button type="button" class="btn btn-gold w-100 d-flex align-items-center justify-content-center gap-2">
                <span style="font-size: 22px; font-weight: 700; color: #ffffff; line-height: 1;">&#43;</span> Tambah Kamar Baru
            </button>
        </a>
    </div>

    <div class="table-responsive table-responsive-box">
        <table class="table table-kamar-custom">
            <thead>
                <tr>
                    <th class="col-1">#</th>
                    <th>Nomor Kamar</th>
                    <th>Tipe Kamar</th>
                    <th>Status</th>
                    <th class="col-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqltambahan = "";
                $per_halaman = 10; 
                
                if ($katakunci != '') {
                    $array_katakunci = explode(" ", $katakunci);
                    for ($x = 0; $x < count($array_katakunci); $x++) {
                        $sqlcari[] = "(kamar.nomor_kamar like '%" . $array_katakunci[$x] . "%' or tipe_kamar.nama_tipe like '%" . $array_katakunci[$x] . "%')";
                    }
                    $sqltambahan    = " where " . implode(" or ", $sqlcari);
                }
                
                $sql1   = "select kamar.*, tipe_kamar.nama_tipe from kamar left join tipe_kamar on kamar.id_tipe = tipe_kamar.id_tipe $sqltambahan";
                $page   = isset($_GET['page'])?(int)$_GET['page']:1;
                $mulai  = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
                $q1     = mysqli_query($koneksi,$sql1);
                $total  = mysqli_num_rows($q1);
                $pages  = ceil($total / $per_halaman);
                $nomor  = $mulai + 1;
                
                $sql1   = $sql1." order by kamar.id_kamar desc limit $mulai,$per_halaman";
                $q1     = mysqli_query($koneksi, $sql1);
              
                while ($r1 = mysqli_fetch_array($q1)) {
                ?>
                    <tr>
                        <td><?php echo $nomor++ ?></td>
                        <td><b><?php echo $r1['nomor_kamar'] ?></b></td>
                        <td><?php echo $r1['nama_tipe'] ?></td>
                        <td>
                            <?php 
                            if($r1['status_kamar'] == 'Tersedia'){
                                echo '<span class="badge-status status-folders status-tersedia">Tersedia</span>';
                            } elseif($r1['status_kamar'] == 'Terisi') {
                                echo '<span class="badge-status status-folders status-terisi">Terisi</span>';
                            } else {
                                echo '<span class="badge-status status-folders status-maintenance">Maintenance</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="kamar_input.php?id=<?php echo $r1['id_kamar']?>" class="text-decoration-none">
                                <span class="btn-action btn-edit-custom">Edit</span>
                            </a>

                            <a href="kamar.php?op=delete&id=<?php echo $r1['id_kamar'] ?>" class="text-decoration-none" onclick="return confirm('Yakin mau menghapus data kamar ini?')">
                                <span class="btn-action btn-delete-custom">Delete</span>
                            </a>
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
                    <a class="page-link" href="kamar.php?katakunci=<?php echo $katakunci?>&cari=<?php echo $cari?>&page=<?php echo $i ?>"><?php echo $i ?></a>
                </li>
                <?php
            }
            ?>
        </ul>
    </nav>

</div>

<?php include("inc_footer.php") ?>