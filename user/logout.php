<?php
// 1. Wajib aktifkan session dulu agar bisa dihapus
session_start();

// 2. Hancurkan semua data session login yang tersimpan
session_destroy();

// 3. Langsung lempar kembali ke halaman beranda utama
header("location:home.php");
exit();
?>