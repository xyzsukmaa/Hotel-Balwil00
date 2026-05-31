-- ==========================================
-- 1. TABEL KONTEN FRONT-END (WEBSITE)
-- ==========================================

CREATE TABLE halaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    kutipan VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    tgl_isi TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO halaman (id, judul, kutipan, isi) VALUES
(8, 'Selamat Datang di Hotel Kita', 'Kenyamanan Anda Adalah Prioritas Kami', '<p>Nikmati pengalaman menginap tak terlupakan dengan fasilitas modern, pelayanan bintang lima, dan lokasi strategis di pusat kota. Sempurna untuk liburan keluarga maupun perjalanan bisnis Anda.</p>');

CREATE TABLE info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    tgl_isi TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO info (id, judul, isi) VALUES
(1, 'Hotel Kita', '<p>Jl. Raya Perhotelan No. 123, Pusat Kota.</p>'),
(2, 'About', '<p>Kami adalah hotel bintang lima yang berkomitmen memberikan pelayanan terbaik untuk setiap tamu.</p>'),
(3, 'Contact', '<p>Email: info@hotelkita.com<br>Telepon: (021) 12345678</p>'),
(4, 'Social', '<p><b>Instagram:</b> @hotelkita</p>');


-- ==========================================
-- 2. TABEL DATA MASTER HOTEL
-- ==========================================

CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    token_ganti_password VARCHAR(255) NULL, -- Ditambahkan untuk fitur lupa password
    no_hp VARCHAR(15)
);

INSERT INTO user (id_user, nama, email, password, no_hp) VALUES
(1, 'Rian Hidayat', 'rian.hidayat@email.com', 'pass123', '081234567890'),
(2, 'Siti Aminah', 'siti.aminah@email.com', 'pass123', '081234567891'),
(3, 'Budi Santoso', 'budi.santoso@email.com', 'pass123', '081234567892'),
(4, 'Dewi Lestari', 'dewi.lestari@email.com', 'pass123', '081234567893'),
(5, 'Eko Prasetyo', 'eko.prasetyo@email.com', 'pass123', '081234567894'),
(6, 'Fitriani', 'fitriani@email.com', 'pass123', '081234567895'),
(7, 'Guntur Wibowo', 'guntur@email.com', 'pass123', '081234567896'),
(8, 'Hany Handayani', 'hany@email.com', 'pass123', '081234567897'),
(9, 'Ira Maya', 'ira.maya@email.com', 'pass123', '081234567898'),
(10, 'Joko Susilo', 'joko@email.com', 'pass123', '081234567899');

CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admin (id_admin, username, password) VALUES
(1, 'super_admin', MD5('adminpass2026')),
(2, 'resepsionis_a', MD5('stafpass2026'));

CREATE TABLE promo (
    id_promo INT AUTO_INCREMENT PRIMARY KEY,
    kode_promo VARCHAR(20) NOT NULL UNIQUE,
    diskon INT NOT NULL,
    berlaku_sampai DATE NOT NULL
);

INSERT INTO promo (id_promo, kode_promo, diskon, berlaku_sampai) VALUES
(1, 'PROMOHEMAT', 15, '2026-12-31'),
(2, 'DISKONGEDE', 30, '2026-09-30');

CREATE TABLE tipe_kamar (
    id_tipe INT AUTO_INCREMENT PRIMARY KEY,
    nama_tipe VARCHAR(50) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    fasilitas TEXT,
    foto VARCHAR(255) NULL -- Ditambahkan untuk index.php
);

INSERT INTO tipe_kamar (id_tipe, nama_tipe, harga, stok, fasilitas, foto) VALUES
(1, 'Standard', 550000.00, 40, 'Kasur Twin/Double, AC, TV 32 inch, WiFi, Kamar Mandi Shower, Perlengkapan Mandi Dasar, Akses Kolam Renang, Akses Gym', 'tipe_1.jpg'),
(2, 'Superior', 850000.00, 25, 'Kasur Queen, AC, Smart TV 40 inch, WiFi Cepat, Kulkas Mini, Pembuat Kopi/Teh, Kamar Mandi Shower, Akses Kolam Renang, Akses Gym', 'tipe_2.jpg'),
(3, 'Deluxe', 1250000.00, 20, 'Kasur King, AC, Smart TV 43 inch, WiFi Cepat, Area Duduk (Sofa), Minibar, Safe Deposit Box, Balkon, Akses Kolam Renang, Akses Gym', 'tipe_3.jpg'),
(4, 'Suite', 1650000.00, 10, 'Kasur King, Ruang Tamu Terpisah, Smart TV 50 inch, Bathtub, Minibar Premium, Mesin Espresso, Akses Lounge, Akses Kolam Renang, Akses Gym', 'tipe_4.jpg'),
(5, 'Presidential Suite', 2500000.00, 5, '2 Kasur King, Ruang Tamu dan Ruang Makan Terpisah, Private Jacuzzi, Dapur Mini, Layanan Butler 24 Jam, Private Pool, Private Gym Access', 'tipe_5.jpg');


-- ==========================================
-- 3. TABEL DATA FISIK KAMAR
-- ==========================================

CREATE TABLE kamar (
    id_kamar INT AUTO_INCREMENT PRIMARY KEY,
    id_tipe INT NOT NULL,
    nomor_kamar VARCHAR(10) NOT NULL UNIQUE,
    status_kamar VARCHAR(20) NOT NULL DEFAULT 'Tersedia',
    FOREIGN KEY (id_tipe) REFERENCES tipe_kamar(id_tipe) ON DELETE RESTRICT
);

INSERT INTO kamar (id_kamar, id_tipe, nomor_kamar, status_kamar) VALUES
(1, 1, 'STD_01', 'Tersedia'), (2, 1, 'STD_02', 'Tersedia'), (3, 1, 'STD_03', 'Tersedia'), (4, 1, 'STD_04', 'Tersedia'), (5, 1, 'STD_05', 'Tersedia'),
(6, 1, 'STD_06', 'Tersedia'), (7, 1, 'STD_07', 'Tersedia'), (8, 1, 'STD_08', 'Tersedia'), (9, 1, 'STD_09', 'Tersedia'), (10, 1, 'STD_10', 'Tersedia'),
(11, 1, 'STD_11', 'Tersedia'), (12, 1, 'STD_12', 'Tersedia'), (13, 1, 'STD_13', 'Tersedia'), (14, 1, 'STD_14', 'Tersedia'), (15, 1, 'STD_15', 'Tersedia'),
(16, 1, 'STD_16', 'Tersedia'), (17, 1, 'STD_17', 'Tersedia'), (18, 1, 'STD_18', 'Penuh'), (19, 1, 'STD_19', 'Tersedia'), (20, 1, 'STD_20', 'Tersedia'),
(21, 1, 'STD_21', 'Tersedia'), (22, 1, 'STD_22', 'Tersedia'), (23, 1, 'STD_23', 'Tersedia'), (24, 1, 'STD_24', 'Tersedia'), (25, 1, 'STD_25', 'Tersedia'),
(26, 1, 'STD_26', 'Tersedia'), (27, 1, 'STD_27', 'Tersedia'), (28, 1, 'STD_28', 'Tersedia'), (29, 1, 'STD_29', 'Tersedia'), (30, 1, 'STD_30', 'Tersedia'),
(31, 1, 'STD_31', 'Tersedia'), (32, 1, 'STD_32', 'Tersedia'), (33, 1, 'STD_33', 'Tersedia'), (34, 1, 'STD_34', 'Maintenance'), (35, 1, 'STD_35', 'Tersedia'),
(36, 1, 'STD_36', 'Tersedia'), (37, 1, 'STD_37', 'Tersedia'), (38, 1, 'STD_38', 'Tersedia'), (39, 1, 'STD_39', 'Tersedia'), (40, 1, 'STD_40', 'Tersedia'),

(41, 2, 'SUP_01', 'Tersedia'), (42, 2, 'SUP_02', 'Tersedia'), (43, 2, 'SUP_03', 'Tersedia'), (44, 2, 'SUP_04', 'Tersedia'), (45, 2, 'SUP_05', 'Tersedia'),
(46, 2, 'SUP_06', 'Tersedia'), (47, 2, 'SUP_07', 'Tersedia'), (48, 2, 'SUP_08', 'Tersedia'), (49, 2, 'SUP_09', 'Tersedia'), (50, 2, 'SUP_10', 'Tersedia'),
(51, 2, 'SUP_11', 'Tersedia'), (52, 2, 'SUP_12', 'Penuh'), (53, 2, 'SUP_13', 'Tersedia'), (54, 2, 'SUP_14', 'Tersedia'), (55, 2, 'SUP_15', 'Tersedia'),
(56, 2, 'SUP_16', 'Tersedia'), (57, 2, 'SUP_17', 'Tersedia'), (58, 2, 'SUP_18', 'Tersedia'), (59, 2, 'SUP_19', 'Tersedia'), (60, 2, 'SUP_20', 'Tersedia'),
(61, 2, 'SUP_21', 'Tersedia'), (62, 2, 'SUP_22', 'Tersedia'), (63, 2, 'SUP_23', 'Tersedia'), (64, 2, 'SUP_24', 'Tersedia'), (65, 2, 'SUP_25', 'Tersedia'),

(66, 3, 'DLX_01', 'Tersedia'), (67, 3, 'DLX_02', 'Tersedia'), (68, 3, 'DLX_03', 'Tersedia'), (69, 3, 'DLX_04', 'Tersedia'), (70, 3, 'DLX_05', 'Tersedia'),
(71, 3, 'DLX_06', 'Tersedia'), (72, 3, 'DLX_07', 'Tersedia'), (73, 3, 'DLX_08', 'Tersedia'), (74, 3, 'DLX_09', 'Tersedia'), (75, 3, 'DLX_10', 'Tersedia'),
(76, 3, 'DLX_11', 'Penuh'), (77, 3, 'DLX_12', 'Tersedia'), (78, 3, 'DLX_13', 'Tersedia'), (79, 3, 'DLX_14', 'Tersedia'), (80, 3, 'DLX_15', 'Tersedia'),
(81, 3, 'DLX_16', 'Tersedia'), (82, 3, 'DLX_17', 'Tersedia'), (83, 3, 'DLX_18', 'Tersedia'), (84, 3, 'DLX_19', 'Maintenance'), (85, 3, 'DLX_20', 'Tersedia'),

(86, 4, 'SUT_01', 'Tersedia'), (87, 4, 'SUT_02', 'Tersedia'), (88, 4, 'SUT_03', 'Tersedia'), (89, 4, 'SUT_04', 'Tersedia'), (90, 4, 'SUT_05', 'Tersedia'),
(91, 4, 'SUT_06', 'Tersedia'), (92, 4, 'SUT_07', 'Penuh'), (93, 4, 'SUT_08', 'Tersedia'), (94, 4, 'SUT_09', 'Tersedia'), (95, 4, 'SUT_10', 'Tersedia'),

(96, 5, 'PRS_01', 'Tersedia'), (97, 5, 'PRS_02', 'Tersedia'), (98, 5, 'PRS_03', 'Tersedia'), (99, 5, 'PRS_04', 'Penuh'), (100, 5, 'PRS_05', 'Tersedia');


-- ==========================================
-- 4. TABEL TRANSAKSI (BOOKING, BAYAR, REVIEW)
-- ==========================================

CREATE TABLE booking (
    id_booking INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_kamar INT NOT NULL,
    id_promo INT NULL,
    checkin DATE NOT NULL,
    checkout DATE NOT NULL,
    total_harga DECIMAL(12,2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Pending',
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_kamar) REFERENCES kamar(id_kamar) ON DELETE RESTRICT,
    FOREIGN KEY (id_promo) REFERENCES promo(id_promo) ON DELETE SET NULL
);

INSERT INTO booking (id_booking, id_user, id_kamar, id_promo, checkin, checkout, total_harga, status) VALUES
(1, 1, 18, 1, '2026-06-01', '2026-06-03', 595000.00, 'Confirmed'),
(2, 2, 76, NULL, '2026-06-05', '2026-06-06', 850000.00, 'Confirmed'),
(3, 3, 99, 2, '2026-06-10', '2026-06-12', 2800000.00, 'Confirmed'),
(4, 4, 52, NULL, '2026-06-12', '2026-06-15', 1650000.00, 'Confirmed'),
(5, 5, 92, NULL, '2026-06-20', '2026-06-22', 2600000.00, 'Pending'),
(6, 6, 5, NULL, '2026-06-25', '2026-06-26', 350000.00, 'Canceled');

CREATE TABLE pembayaran (
    id_bayar INT AUTO_INCREMENT PRIMARY KEY,
    id_booking INT NOT NULL,
    metode VARCHAR(50) NOT NULL,
    status_bayar VARCHAR(30) NOT NULL DEFAULT 'Belum Bayar',
    tanggal_bayar DATE,
    FOREIGN KEY (id_booking) REFERENCES booking(id_booking) ON DELETE CASCADE
);

INSERT INTO pembayaran (id_bayar, id_booking, metode, status_bayar, tanggal_bayar) VALUES
(1, 1, 'Transfer Bank', 'Lunas', '2026-05-30'),
(2, 2, 'E-Wallet', 'Lunas', '2026-06-05'),
(3, 3, 'Kartu Kredit', 'Lunas', '2026-06-09'),
(4, 4, 'Transfer Bank', 'Lunas', '2026-06-11'),
(5, 5, 'E-Wallet', 'Belum Lunas', NULL);

CREATE TABLE review (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_booking INT NOT NULL,
    rating INT NOT NULL,
    komentar TEXT,
    tanggal DATE NOT NULL,
    FOREIGN KEY (id_booking) REFERENCES booking(id_booking) ON DELETE CASCADE
);

INSERT INTO review (id_review, id_booking, rating, komentar, tanggal) VALUES
(1, 1, 4, 'Kamarnya bersih, fasilitas kolam renang dekat.', '2026-06-04'),
(2, 2, 5, 'Nyaman sekali dapet kamar lantai atas.', '2026-06-07');