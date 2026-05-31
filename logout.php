<?php
session_start();
unset($_SESSION['user_email']);
unset($_SESSION['user_nama']);
session_destroy();
header("location:index.php");
?>