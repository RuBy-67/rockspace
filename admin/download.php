<?php
$backupDir = '/var/www/html/sauvegarde/stockage_sauvegarde';

if (isset($_GET['file'])) {
  $filename = $_GET['file'];
  $filepath = $backupDir . '/' . $filename;

  if (file_exists($filepath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    exit;
  } else {
    echo 'Le fichier demandé n\'existe pas.';
  }
} else {
  echo 'Paramètre de fichier manquant.';
}
?>