<?php
session_start();
$user = $_SESSION['username'];
header('Location: gestion_stock.php');
?>