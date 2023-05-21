<?php
session_start();
$user = $_SESSION['username'];

if ($user != "Admin") {
  header('Location: ../home/home.php');
  exit();
}

$file = $_GET['file'];
if (file_exists($file) && is_writable($file)) {
  unlink($file);
  header("Location: gestion.php");
  exit();
} else {
  echo "Le fichier ne peut pas être supprimé." . $file;
}
exit();

?>