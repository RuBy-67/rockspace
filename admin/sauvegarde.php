<?php
// Dossier à sauvegarder
$sourceDir = '/var/www/html/rockspace';

// Dossier de stockage des sauvegardes
$targetDir = '/var/www/html/sauvegarde/stockage_sauvegarde';

// Nom du fichier de sauvegarde
$backupFilename = 'backup_' . date('Y-m-d') . '.zip';

// Chemin complet du fichier de sauvegarde
$backupFilePath = $targetDir . '/' . $backupFilename;

// Limite du nombre de sauvegardes
$maxBackups = 10;

// Création de l'archive zip de sauvegarde
$zip = new ZipArchive();
if ($zip->open($backupFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
  $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceDir), RecursiveIteratorIterator::LEAVES_ONLY);

  foreach ($files as $name => $file) {
    if (!$file->isDir()) {
      $filePath = $file->getRealPath();
      $relativePath = substr($filePath, strlen($sourceDir) + 1);
      $zip->addFile($filePath, $relativePath);
    }
  }

  $zip->close();

  echo 'Sauvegarde créée avec succès.';

  // Supprimer les sauvegardes dépassant la limite
  $backups = glob($targetDir . '/backup_*.zip');
  if (count($backups) > $maxBackups) {
    $oldestBackups = array_slice($backups, 0, -(int) $maxBackups);
    foreach ($oldestBackups as $backup) {
      unlink($backup);
    }
  }
} else {
  echo 'Erreur lors de la création de la sauvegarde.';
}

// Afficher la liste des sauvegardes disponibles
$backups = glob($targetDir . '/backup_*.zip');
if (!empty($backups)) {
  echo '<h3>Liste des sauvegardes disponibles :</h3>';
  echo '<ul>';
  foreach ($backups as $backup) {
    $backupFilename = basename($backup);
    echo "<li><a href='download.php?file=" . urlencode($backupFilename) . "'>" . $backupFilename . "</a></li>";
  }
  echo '</ul>';
}
?>