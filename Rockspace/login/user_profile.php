<?php
session_start();
$user = $_SESSION['username']
  ?>

<?php
// Connexion à la base de données
$servername = "localhost";
$username = "Ruben";
$password = "Ruben";
$dbname = "RockSpace";

$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_POST['logout'])) {
  // Déconnexion de l'utilisateur
  session_start();
  session_unset();
  session_destroy();
  header('Location: ../home/home.php');
  exit;
}


// Vérification de la connexion
if ($conn->connect_error) {
  die("La connexion a échoué: " . $conn->connect_error);
}

// Récupération de l'ID_CLIENT du client à partir de la table login
$sql = "SELECT ID_CLIENT FROM login WHERE login='$user'";
$result = $conn->query($sql);
if ($result === false) {
  echo "Erreur lors de l'exécution de la requête : " . $conn->error;
  header('Location:  login.php');
}
if ($result->num_rows == 0) {
  header('Location:  login.php');
}

$row = $result->fetch_assoc();
$id_client = $row["ID_CLIENT"];

// Récupération des informations du client à partir de la table Client
$sql = "SELECT * FROM client WHERE ID_CLIENT=$id_client";
$result = $conn->query($sql);
if ($result === false) {
  echo "Erreur lors de l'exécution de la requête : " . $conn->error;
  exit();
}
if ($result->num_rows == 0) {
  echo "Aucun client trouvé avec cet ID.";
  exit();
}


// Stockage des informations dans des variables
$row = $result->fetch_assoc();
$espece = $row["espèce"];
$nom = $row["nom"];
$prenom = $row["prenom"];
$mail = $row["mail"];
$tel = $row["tel"];
$image = $row["image"];
$img = $row["lien_image"];


// Récupération de ETH du client à partir de la table  ETH
$sql = "SELECT * FROM ETH WHERE ID_CLIENT=$id_client";
$result = $conn->query($sql);
if ($result === false) {
  echo "Erreur lors de l'exécution de la requête : " . $conn->error;
  exit();
}
if ($result->num_rows == 0) {
  echo "Aucun client trouvé avec cet ID.";
  exit();
}

// Stockage des informations ETH dans des variables
$row = $result->fetch_assoc();
$eth = $row["Clé_public"]; ?>

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
  <link rel="stylesheet" href="../login/user_profile.css">

  <!---Header footer css--->
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
    <div class="container">
      <div class="col">
        <?php
        //Affichage de l'image
        if (file_exists($target_file)) {
          echo '<img class="preview" src="' . $target_file . '">';
        } else {
          echo '<img class="preview" src="../img/default_id.png">';
        }
        ?>
        <!--<input class="file" class="pdp" type="file" id="photo" name="photo" accept="image/png, image/jpeg"
                    onchange="previewImage()">-->
      </div>
    </div>
    <h1 class="title">Profil</h1>
    <?php
    // Mise à jour des informations du client dans les table
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $new_nom = $_POST["nom"];
      $new_prenom = $_POST["prenom"];
      $new_mail = $_POST["mail"];
      $new_tel = $_POST["tel"];

      $new_eth = $_POST["eth"];
      $mdp = $_POST["mdp"];
      /* $photo = $POST["photo"];
      Définition du chemin où sera stockée l'image
      $target_dir = "../login/pdp/";
      $target_file = $target_dir . "$user.png";
      Suppression de l'ancienne image si elle existe
      if (file_exists($target_file)) {
      unlink($target_file);
      }
      Enregistrement de la nouvelle image si elle a été uploadée
      if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
      $temp_file = $_FILES["photo"]["tmp_name"];
      $new_file = $target_file;
      move_uploaded_file($temp_file, $new_file);
      }*/
      //hachage password
      $hashed_password = password_hash($mdp, PASSWORD_DEFAULT);

      // Vérification que l'adresse ETH n'est pas déjà utilisée
      $sql = "SELECT * FROM ETH WHERE ID_CLIENT != $id_client  AND Clé_public='$new_eth'";
      $resulteth = $conn->query($sql);
      if ($resulteth->num_rows > 0) {
        echo '<p class="error">L`adresse ETH est déjà utilisée</p>';
        exit();
      } else if ($new_eth !== '' && !preg_match('/^0x[a-fA-F0-9]{40}$/', $new_eth)) {
        echo '<p class="error">L`adresse ETH n`est pas valide</p>';
        exit();
      }

      // Vérification que le mail n'est pas déjà utilisé
      $sql = "SELECT * FROM client WHERE ID_CLIENT != $id_client AND mail='$new_mail'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        echo '<p class="error">L`adresse mail est déjà utilisée</p>';
        exit();
      }

      $sql = "UPDATE client SET";
      if (!empty($new_nom)) {
        $sql .= " nom='$new_nom',";
      } else {
        $sql .= " nom='$nom',";
      }
      if (!empty($new_prenom)) {
        $sql .= " prenom='$new_prenom',";
      }
      if (!empty($new_mail)) {
        $sql .= " mail='$new_mail',";
      }
      if (!empty($new_tel)) {
        $sql .= " tel='$new_tel',";
      }
      // if (!empty($target_file)) {
      // $sql .= " lien_image='$target_file',";
      //  }
      $sql = rtrim($sql, ","); // Supprime la dernière virgule
      $sql .= " WHERE ID_CLIENT=$id_client;";

      $sql2 = "UPDATE ETH SET";
      if (!empty($new_eth)) {
        $sql2 .= " Clé_public='$new_eth',";
      } else {
        $sql2 .= " Clé_public='$eth',";
      }
      $sql2 = rtrim($sql2, ","); // Supprime la dernière virgule
      $sql2 .= " WHERE ID_CLIENT=$id_client;";

      $sql3 = "UPDATE login SET";
      if (!empty($mdp)) {
        $sql3 .= " mdp='$hashed_password',";
      } else {
        $sql3 .= " login='$user',";
      }
      $sql3 = rtrim($sql3, ","); // Supprime la dernière virgule
      $sql3 .= " WHERE ID_CLIENT=$id_client;";

      // Concatène les trois requêtes en une seule en ajoutant des points-virgules
      $sql_all = $sql . $sql2 . $sql3;


      if ($conn->multi_query($sql_all) === false) {
        echo '<p class="error">Erreur lors de la mise à jour des informations :</p> ' . $conn->error;
      } else {
        echo '<p class="green">Les informations ont été mises à jour.</p>';
      }
    }
    ?>

    <form class="modif" method="post" action="user_profile.php" enctype="multipart/form-data">
      <div class="row">
        <div class="col">
          <label for="nom">Nom</label>
          <input class="input-modif" type="text" id="nom" name="nom" placeholder="<?php echo $nom; ?>"> <br>
          <label for="prenom">Prénom</label>
          <input class="input-modif" class="input-modif" type="text" id="prenom" name="prenom"
            placeholder="<?php echo $prenom; ?>"><br>
          <label for="mail">E-mail</label>
          <input class="input-modif" type="email" id="mail" name="mail" placeholder="<?php echo $mail; ?>"><br>
          <label for="login">Login</label>
          <input class="input-modif" type="text" id="login" name="login" value="<?php echo $user; ?>" readonly><br>
          <label for="telephone">Téléphone</label>
          <input class="input-modif" type="text" id="telephone" name="telephone" placeholder="0<?php echo $tel; ?>"><br>
          <label for="mdp">Password</label>
          <input class="input-modif" type="password" id="mdp" name="mdp" placeholder="Nouveau Mot de passe">
          <br>
          <label for="espece">Espèce</label>
          <input class="input-modif" type="text" id="Espèce" name="Espèce" value="<?php echo $espece; ?>" readonly><br>
          <label for="eth">Adresse ETH</label>
          <input class="input-modif-eth" type="text" id="eth" name="eth" value="<?php echo $eth; ?>"><br>
        </div>
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
    <form action="" method="POST">
      <input type="submit" name="logout" class="btn " value="Déconnexion">
    </form>

    </div>
    <div class="commande">
      <?php
      // Requête pour récupérer les informations des commandes
      $sql = "SELECT * FROM commande WHERE ID_CLIENT=$id_client";
      $result = mysqli_query($conn, $sql);

      // Vérification si la requête a retourné des résultats
      if (mysqli_num_rows($result) == 0) {
        // Si la requête ne retourne aucun résultat, afficher un message
        echo '<p class="commande-list"></p>Aucune commande passée</p>';
      } else {
        // Si la requête retourne des résultats, afficher le nombre de commandes
        echo '<p class="commande-list">Nombre de commandes : <strong>' . mysqli_num_rows($result) . '</strong></p><br><br>';

        // Parcourir les résultats de la requête
        while ($row = mysqli_fetch_assoc($result)) {
          // Afficher le path de chaque commande
          echo '<p class="commande-list" >Commande ' . $row["cmd_id"] . ' : <a href="' . $row["path"] . '">Télécharger / Consulter</a><br></p>';
        }
      }
      ?>
    </div>
  </main>

  <footer>
    <div class="footer-container">
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