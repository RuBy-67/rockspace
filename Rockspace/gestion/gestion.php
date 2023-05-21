<?php
session_start();
$user = $_SESSION['username'];

if ($user != "Admin") {
  header('Location: ../home/home.php');
  exit();
}
$filedir = "/var/www/html/rockspace/Rockspace/";
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Rock Space</title>
  <link rel="icon" type="image/png" sizes="20x20" href="../img/logo1.png">
  <!-- Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nova+Square&display=swap" rel="stylesheet">
  <!-- Description -->
  <meta name="description"
    content="Envie de vivre une expérience exceptionnelle et inoubliable ? Rock_Space est là pour réaliser votre rêve : des vacances sur d'autres planètes ! Notre agence vous propose des voyages à bord de nos vaisseaux ultra-modernes, conçues pour offrir un confort incomparable. Avec des cabines spacieuses équipées de toutes les commodités, vous pourrez profiter d'un séjour de luxe dans l'espace.">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../gestion/gestion.css">
  <!--Header footer style-->
  <link rel="stylesheet" href="../style/header_footer.css">
  <script type="text/javascript" src="https://files.coinmarketcap.com/static/widget/currency.js"></script>
</head>

<body>
  <header>
    <div class="flex">
      <div class="hlogo">
        <img src="../img/logo2.png" alt="logo">
        <h1>Rock Space</h1>
      </div>
     <form class="search" action="../Produit/recherche.php">
        <input type="text" method="POST" name="text" class="area" placeholder="Recherchez ici!">
        <input type="submit" name="submit" class="submit" value="Search">
      </form>
    </div>
    <div class="barre">
      <ul class="nav">
        <li><a href="../home/home.php">Home</a></li>
        <li><a href="../Produit/produit.php">Produits</a></li>
        <li><a href="../news/news.php">News</a></li>
        <li><a href="../shiplist/shiplist.php">ShipList</a></li>
        <li><a href="#">Contact</a></li>
        <?php
        // Vérifier si le nom de compte est "Admin"
        if ($user == "Admin") {
          // Afficher le bouton "Gestion"
          echo '<li><a href="../gestion/gestion.php">Gestion</a></li>';
          echo '<li><a href="../gestion/gestion_stock.php">Stock</a></li>';
        }
        ?>
      </ul>

      <ul class="panier-login">
        <li>
          <div class="coinmarketcap-currency-widget" data-currencyid="9291" data-base="USD" data-secondary=""
            data-ticker="false" data-rank="false" data-marketcap="false" data-volume="false" data-statsticker="false"
            data-stats="USD">
          </div>
        </li>
        <?php
        if (isset($_SESSION['username'])) {
          // L'utilisateur est connecté, on affiche son nom
          echo '<li class="submit-login"><a href="../login/user_profile.php">' . $_SESSION['username'] . '</a></li>';
        } else {
          // L'utilisateur n'est pas connecté, on affiche un lien vers la page de connexion
          echo '<li class="submit-login" ><a href="../login/login.php">Login 👽</a></li>';
        }
        ?>
        <li>
          <form method="post" action="
          
          <?php
          if (isset($_SESSION['username'])) {
            // L'utilisateur est connecté, on affiche son nom
            echo '../panier/panier.php';
          } else {
            // L'utilisateur n'est pas connecté, on affiche un lien vers la page de connexion
            echo '../login/login.php';
          }
          ?>">
            <button type="submit" name="submit" class="submit-image">
              <img src="../img/panier.png" alt="Panier">
          </form>
        </li>
      </ul>
    </div>

  </header>

  <main>
    <center>
      <h2 class="title">Informations système</h2>
    </center>
    <div class="file-manager-container">
      <div class="system-info">
        <?php
        // Récupération du taux d'utilisation du CPU
        $cpu_usage = shell_exec("top -bn1 | awk '{print $1}' | tail -n+8 | xargs | awk '{print $1}'");
        $cpu_usage = round($cpu_usage, 2); // Arrondissement à 2 décimales
        
        // Récupération de l'espace disque utilisé et restant
        $disk_free = disk_free_space("/");
        $disk_total = disk_total_space("/");
        $disk_used = $disk_total - $disk_free;
        $disk_used_gb = round($disk_used / (1024 * 1024 * 1024), 2); // Conversion en Go et arrondissement à 2 décimales
        $disk_free_gb = round($disk_free / (1024 * 1024 * 1024), 2); // Conversion en Go et arrondissement à 2 décimales
        
        // Récupération de l'uptime de la machine
        $uptime_seconds = shell_exec("cat /proc/uptime | awk '{print $1}'");
        $uptime_seconds = round($uptime_seconds);
        $uptime_days = floor($uptime_seconds / (60 * 60 * 24));
        $uptime_hours = floor(($uptime_seconds % (60 * 60 * 24)) / (60 * 60));
        $uptime_minutes = floor(($uptime_seconds % (60 * 60)) / 60);
        $uptime_seconds = $uptime_seconds % 60;
        ?>

        <p>CPU usage:
          <?php echo $cpu_usage; ?>%
        </p>
        <p>Espace disque utilisé:
          <?php echo $disk_used_gb; ?> Go
        </p>
        <p>Espace disque restant:
          <?php echo $disk_free_gb; ?> Go
        </p>
        <p>Uptime de la machine:<br>
          <?php echo $uptime_days; ?> jours,
          <?php echo $uptime_hours; ?> heures,
          <?php echo $uptime_minutes; ?> minutes,
          <?php echo $uptime_seconds; ?> secondes
        </p>
      </div>
      <?php
      // Récupération de la liste des programmes et leur uptime
      $processes = shell_exec("ps -eo comm,etimes --sort=start_time");
      $processes_arr = explode("\n", trim($processes));

      // Affichage des programmes et leur uptime
      echo '<div class="systeme actif">';
      echo '<h2>Programme UP</h2>';

      foreach ($processes_arr as $process) {
        $process_info = explode(" ", trim($process), 2);
        $program_name = $process_info[0];
        $uptime_seconds = $process_info[1];

        // Conversion de l'uptime en jours, heures, minutes et secondes
        $uptime_days = floor($uptime_seconds / (60 * 60 * 24));
        $uptime_hours = floor(($uptime_seconds % (60 * 60 * 24)) / (60 * 60));
        $uptime_minutes = floor(($uptime_seconds % (60 * 60)) / 60);
        $uptime_seconds = $uptime_seconds % 60;

        // Affichage du programme et son uptime
        echo '<p>';
        echo '<strong>Programme :</strong> ' . $program_name . '<br>';
        echo 'Uptime : ' . $uptime_days . ' D, ' . $uptime_hours . ' H, ' . $uptime_minutes . ' min, ' . $uptime_seconds . ' s';
        echo '</p>';
      }

      echo '</div>';

      ?>
      <div class="file-manager">
        <?php
        $log_files = array("/var/log/syslog");
        $max_lines = 20;

        foreach ($log_files as $log_file) {
          echo '<h3>' . $log_file . '</h3>';
          echo '<div class="log">';
          if (file_exists($log_file)) {
            $lines = tail($log_file, $max_lines);
            $lines = array_reverse(explode("\n", $lines));
            echo "<pre>" . implode("\n", $lines) . "</pre>";
            echo "</div>";
          } else {
            echo '<p class="error">Le fichier $log_file n`existe pas.</p>';
          }
        }
        function tail($filename, $lines)
        {
          $output = "";
          $buffer = 4096;
          $fsize = filesize($filename);
          $hdl = fopen($filename, 'r');
          fseek($hdl, -$buffer, SEEK_END);
          $pos = ftell($hdl);
          while ($pos > 0 && $lines > 0) {
            $data = fread($hdl, $buffer);
            $output = $data . $output;
            fseek($hdl, -$buffer, SEEK_CUR);
            $pos = ftell($hdl);
            $lines -= substr_count($data, "\n");
          }
          fclose($hdl);
          return $output;
        }
        ?>
      </div>
      <div class="logMySql-manager">
        <?php
        function getMysqlLogs($log_file, $max_lines)
        {
          $logs = '';

          if (file_exists($log_file)) {
            // Lire les dernières lignes du fichier
            $command = 'tail -n ' . $max_lines . ' ' . $log_file;
            $logs = shell_exec($command);
          } else {
            $logs = "Le fichier $log_file n'existe pas.";
          }

          return $logs;
        }

        // Utilisation de la fonction pour récupérer les logs de mysql.log
        $log_file = '/var/log/mysql/mysql.log';
        $max_lines = 20;
        $mysql_logs = getMysqlLogs($log_file, $max_lines);

        // Affichage des logs dans l'interface web
        echo '<h3>' . $log_file . '</h3>';
        echo '<pre>' . $mysql_logs . '</pre>';
        ?>
      </div>
    </div>
    <div class=flexo>
      <div class=file-listing>
        <div class="file-manager-header">
          <form method="post" class="list">
            <label for="path">Chemin voulu :</label>
            <input class="btn-b" type="text" name="path" id="path"
              value="<?php echo isset($_POST['path']) ? htmlspecialchars($_POST['path']) : $filedir; ?>">
            <button class="btn" type="submit">Listing</button>
          </form>
        </div>
        <?php
        function getFileTypeIcon($filepath)
        {
          $extension = pathinfo($filepath, PATHINFO_EXTENSION);
          switch ($extension) {
            case 'png':
              return '🖼️';
            case 'jpeg':
              return '🖼️';
            case 'php':
              return '📜';
            case 'css':
              return '🎨';
            case 'sh':
              return '👾';
            case 'js':
              return '👽';
            case '':
              return is_dir($filepath) ? '📁' : '📜';
            default:
              return '📜';
          }
        }

        function getFileActionsHTML($filepath, $filedir)
        {
          $relativepath = substr($filepath, strlen($filedir));
          $t = $filedir . $relativepath;

          $size = '';
          if (is_file($filepath)) {
            $size = filesize($filepath);
            $size = formatSizeUnits($size);
          } elseif (is_dir($filepath)) {
            $size = getDirectorySize($filepath);
            $size = formatSizeUnits($size);
          }

          return "<span class='file-actions'>
          <span class='file-size'>$size</span>
    <a href='delete_file.php?file=$t'>🗑️</a>
    <a href='http://127.0.0.1/rockspace/Rockspace$relativepath' download>📥</a>
  </span>";
        }

        function getDirectorySize($dir)
        {
          $size = 0;
          $files = scandir($dir);
          foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
              $path = $dir . '/' . $file;
              if (is_file($path)) {
                $size += filesize($path);
              } elseif (is_dir($path)) {
                $size += getDirectorySize($path);
              }
            }
          }
          return $size;
        }

        function formatSizeUnits($size)
        {
          $units = array('B', 'KB', 'MB', 'GB', 'TB');
          $i = 0;
          while ($size >= 1024 && $i < 4) {
            $size /= 1024;
            $i++;
          }
          return round($size, 2) . ' ' . $units[$i];
        }


        if (isset($_POST['path'])) {
          $path = $_POST['path'];
          $dir = opendir($path);
          if (!$dir) {
            echo '<p class="error">Le chemin spécifié n`est pas valide.</p>';
          } else {
            echo "<div class='file-list'>";
            while (($file = readdir($dir)) !== false) {
              if ($file != "." && $file != "..") {
                $filepath = rtrim($path, "/") . "/" . $file;
                $fileTypeIcon = getFileTypeIcon($filepath);
                $fileActionsHTML = getFileActionsHTML($filepath, $filedir);
                echo "<div class='file-item'>";
                echo "<span class='file-name'>" . $fileTypeIcon . " " . $file . "</span>";
                echo $fileActionsHTML;
                echo "</div>";
              }
            }
            closedir($dir);
            echo "</div>";
          }
        }
        ?>
      </div>
      <div class="flex2">
        <div class="file-manager-upload">
          <?php
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
            $relativepath = isset($_POST['path']) ? '' . $_POST['path'] : '';
            $targetDir = "$relativepath";
            $targetFile = $targetDir . '/' . basename($_FILES['fileToUpload']['name']);
            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile)) {
              echo '<p class="green">Fichier bien ajouté dans le repertoire :  ' . $targetDir . ' </p> ';
            } else {
              $lastError = error_get_last();
              echo '<p class="error">Erreur lors du téléchargement du fichier :</p> ' . $lastError['message'] . '</p>';
            }
          }
          ?>
          <form action="" method="post" id="upload-form" enctype="multipart/form-data">
            <div> <input type="hidden" name="path"
                value="<?php echo isset($_POST['path']) ? htmlspecialchars($_POST['path']) : $filedir; ?>"></div>
            <div><input type="file" name="fileToUpload"></div>
            <div><input class="btn" type="submit" value="Uploader"></div>
          </form>
        </div>
        <div class="gift-card">
          <div class="gift-card-list">
            <?php
            // Connexion à la base de données
            $servername = "localhost";
            $username = "Ruben";
            $password = "Ruben";
            $dbname = "RockSpace";

            $conn = new mysqli($servername, $username, $password, $dbname);
            // Vérification de la connexion
            if (!$conn) {
              die("Connection failed: " . mysqli_connect_error());
            }
            // Récupération des données de la table "code_promos"
            $sql = "SELECT code, montant FROM code_promos";
            $result = mysqli_query($conn, $sql);

            // Vérification si la requête a retourné des résultats
            if (mysqli_num_rows($result) > 0) {
              // Affichage des résultats dans un tableau HTML
              echo "<table>";
              echo "<tr><th>Code</th><th>Montant</th><th>Action</th></tr>";
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . $row["code"] . "</td><td>" . $row["montant"] . " T</td><td><form method='post'><input type='hidden' name='delete' value='" . $row["code"] . "'><button class=\"btn\" type='submit'>Supprimer</button></form></td></tr>";
              }
              echo "</table>";

            } else {
              echo "Aucune carte cadeau trouvée";
            }
            // Traitement de la suppression d'une carte cadeau
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
              $code = $_POST['delete'];
              $sql = "DELETE FROM code_promos WHERE code = '$code'";
              if ($conn->query($sql) === TRUE) {
                header('Location: gestion.php');

              } else {
                echo '<p class"error">Erreur lors de la suppression de la carte cadeau : </p>' . $conn->error . '</p>';

              }
            }
            ?>
          </div>
          <div class="add">
            <?php
            // Traitement de l'ajout d'une carte cadeau
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['card']) && isset($_POST['montant'])) {
              $code = $_POST['card'];
              $montant = $_POST['montant'];

              // Vérification si la carte cadeau n'existe pas déjà
              $sql = "SELECT code FROM code_promos WHERE code = '$code'";
              $result = mysqli_query($conn, $sql);
              if (mysqli_num_rows($result) > 0) {
                echo '<p class="error">La carte cadeau existe déjà</p>';
              } else {
                // Insertion de la nouvelle carte cadeau
                $sql = "INSERT INTO code_promos (code, montant) VALUES ('$code', '$montant')";
                if ($conn->query($sql) === TRUE) {
                  header('Location: gestion.php');
                  echo '<p class"green">Carte Ajouté</p>';
                } else {
                  echo '<p class"error">Erreur lors de l`insertion de la carte cadeau :' . $conn->error . '</p>';
                }
              }
            }
            ?>
            <form method="post">
              <input type="text" name="card" id="card" class="btn-a" placeholder="Nouvelle carte cadeau">
              <input type="INT" name="montant" id="montant" class="btn-a" placeholder="Montant">
              <button type="submit" class="btn">Ajouter</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="cmd-list">
      <?php

      // Récupération du nombre total de commandes
      $sql = "SELECT COUNT(*) AS nb_commandes FROM commande";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $nb_commandes = $row["nb_commandes"];

      // Récupération de la somme des montants
      $sqlSum = "SELECT SUM(montant) AS totalMontant FROM commande";
      $resultSum = $conn->query($sqlSum);
      $rowSum = $resultSum->fetch_assoc();
      $totalMontant = $rowSum['totalMontant'];
      $totalMontantFormatted = number_format($totalMontant, 0, ',', ' ');

      // Récupération de la moyenne des montants
      $sqlAvg = "SELECT AVG(montant) AS moyenneMontant FROM commande";
      $resultAvg = $conn->query($sqlAvg);
      $rowAvg = $resultAvg->fetch_assoc();
      $moyenneMontant = $rowAvg['moyenneMontant'];
      $moyenneMontantFormatted = number_format($moyenneMontant, 1, ',', ' ');


      // Récupération des 20 dernières commandes avec les infos du client correspondant
      $sql = "SELECT commande.*, client.nom, client.mail FROM commande JOIN client ON commande.ID_CLIENT = client.ID_CLIENT ORDER BY commande.cmd_id DESC LIMIT 20";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        // Affichage du nombre de commandes
        echo "<div class=\"cmd-list1\">";
        echo "<p>Nombre de Commande : " . $nb_commandes . "</p>";
        echo "<p>Total vente : " . $totalMontantFormatted . " T</p>";
        echo "<p>Moyenne vente : " . $moyenneMontantFormatted . " T</p>";
        echo "<p class='underline'>Liste des commandes passée (20 dernières)</p>";
        echo "</div>";

        // Affichage des commandes
        while ($row = $result->fetch_assoc()) {
          $cmd_id = $row["cmd_id"];
          $road = $row["path"];
          $nom_client = $row["nom"];
          $mail_client = $row["mail"];
          $montant1 = $row["montant"];
          $montant = number_format($montant1, 0, ',', ' ');

          echo "<p>Commande n° $cmd_id passée par $nom_client ($mail_client), montant: $montant T,  <a href=\"$road\" download>Télécharger la facture</a></p>";
          echo '<hr style="background-color: #fbb844;">';
        }
      } else {
        // Affichage d'un message si aucune commande n'a été passée
        echo "<p>Pas de commande effectuée</p>";
      }

      ?>
    </div>
  </main>

  <footer>
    <div class=" footer-container">
      <div class="left-section">
        <ul class="nav">
          <li><a href="#">Contact</a></li>
          <li><a href="#">Legal</a></li>
        </ul>
      </div>
      <div class="center-section">
        <img src="../img/logo2.png" alt="logo">
      </div>
      <div class="right-section">
        <ul>
          <li><a href="#"><img src="../img/i.png" alt="insta"></a></li>
          <li><a href="#"><img src="../img/t.png" alt="twitter"></a></li>
          <li><a href="#"><img src="../img/f.png" alt="facebook"></a></li>
        </ul>
      </div>
    </div>
    <div class="bottom-section">
      <p>© Copyright ---- RuBy-67 @2023
        _ Partiel E5 _ Exclus</p>
    </div>
  </footer>

</body>

</html>