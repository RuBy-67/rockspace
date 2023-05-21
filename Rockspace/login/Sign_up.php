<?php
session_start(); ?>
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
  <link rel="stylesheet" href="../login/Sign_up.css">

  <!-- style  header_footer -->
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

    <?php

    // Connexion à la base de données
    $servername = "localhost";
    $username = "Ruben";
    $password = "Ruben";
    $dbname = "RockSpace";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
      die("La connexion a échoué: " . $conn->connect_error);
    }

    // Traitement du formulaire d'inscription
    if (isset($_POST['submit'])) {
      // Récupération des données du formulaire
      $nom = $_POST['nom'];
      $prenom = $_POST['prenom'];
      $mail = $_POST['mail'];
      $tel = $_POST['tel'];
      $espece = $_POST['espèce'];
      $login = $_POST['login'];
      $mdp = $_POST['mdp'];
      $mdp_confirm = $_POST['mdp_confirm'];
      $mail_confirm = $_POST['mail_confirm'];
      $cle_public = $_POST['Clé_public'];

      // Vérification que les mots de passe et les mails correspondent
      if ($mdp !== $mdp_confirm) {
        echo "Les mots de passe ne correspondent pas";
      } elseif ($mail !== $mail_confirm) {
        echo "Les adresses mail ne correspondent pas";
      } else {

        // Vérification que le mail n'est pas déjà utilisé
        $sql = "SELECT * FROM client WHERE mail='$mail'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          echo "L'adresse mail est déjà utilisée";
          exit();
        }

        // Vérification que le login n'est pas déjà utilisé
        $sql = "SELECT * FROM login WHERE login='$login'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          echo "Le nom d'utilisateur est déjà utilisé";
          exit();
        }

        // Vérification que l'adresse ETH n'est pas déjà utilisée
        $sql = "SELECT * FROM ETH WHERE Clé_public='$cle_public'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          echo "L'adresse ETH est déjà utilisée";
          exit();
        } else if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $cle_public)) {
          echo "L'adresse ETH n'est pas valide";
          exit();
        }

        // Vérification que le téléphone n'est pas déjà utilisé
        $sql = "SELECT * FROM client WHERE tel='$tel'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          echo "Le numéro de téléphone est déjà utilisé";
          exit();
        }

        // Insertion des données dans la table "client"
        $sql = "INSERT INTO client (nom, prenom, mail, tel, espèce) VALUES ('$nom', '$prenom', '$mail', '$tel', '$espece')";
        $conn->query($sql);

        $id_client = $conn->insert_id; // récupération de l'ID_CLIENT généré automatiquement
    
        // Insertion des données dans la table "ETH"
        $sql = "INSERT INTO ETH (ID_CLIENT, Clé_public) VALUES ('$id_client','$cle_public')";
        $conn->query($sql);

        // Insertion des données dans la table "login"
        // Générer un hachage de mot de passe
        $hashed_password = password_hash($mdp, PASSWORD_DEFAULT);

        $sql = "INSERT INTO login (ID_CLIENT, login, mdp) VALUES ('$id_client','$login', '$hashed_password')";
        $conn->query($sql);

        // Redirection vers la page de profil de l'utilisateur
        header('Location: login.php');

        exit();
      }
      echo "L'utilisateur a été inscrit avec succès.";
    }
    ?>
    <img src="../img/logo1.png" alt="logo">
    <form method="post" action="">
      <div class="container">
        <div class="column">
          <div class="form-container">
            <input type="text" name="nom" class="form" id="nom" required placeholder="Nom">
            <input type="text" name="prenom" class="form" id="prenom" required placeholder="Prénom">
            <input type="text" name="login" class="form" id="login" required placeholder="Login">
            <input type="email" name="mail" class="form" id="mail" required placeholder="Mail">
            <input class="form" type="email" name="mail_confirm" id="mail_confirm" required
              placeholder="Confirmation du mail">
            <input type="tel" name="tel" class="form" id="tel" required placeholder="Téléphone">
          </div>
        </div>
        <div class="column">
          <div class="form-container">
            <input type="password" name="mdp" class="form" id="mdp" required placeholder="Mot de passe">
            <input type="password" class="form" name="mdp_confirm" id="mdp_confirm" required
              placeholder="Confirmation du mot de passe">
            <select id="espèce" class="form" name="espèce" required>
              <option value="">Choisissez une espèce</option>
              <option class="form-select" value="aethyrion">Aethyrion</option>
              <option class="form-select" value="andromedanis">Andromedanis</option>
              <option class="form-select" value="arcturianth">Arcturianth</option>
              <option class="form-select" value="azarathian">Azarathian</option>
              <option class="form-select" value="chronovin">Chronovin</option>
              <option class="form-select" value="cthulhoidae">Cthulhoidae</option>
              <option class="form-select" value="eridanix">Eridanix</option>
              <option class="form-select" value="galaxarion">Galaxarion</option>
              <option class="form-select" value="glorpticon">Glorpticon</option>
              <option class="form-select" value="hypernova">Hypernova</option>
              <option class="form-select" value="joviosaur">Joviosaur</option>
              <option class="form-select" value="krystallinephus">Krystallinephus</option>
              <option class="form-select" value="nekronid">Nekronid</option>
              <option class="form-select" value="nebulost">Nebulost</option>
              <option class="form-select" value="neptunianth">Neptunianth</option>
              <option class="form-select" value="omnicrux">Omnicrux</option>
              <option class="form-select" value="ouroborosian">Ouroborosian</option>
              <option class="form-select" value="quasarid">Quasarid</option>
              <option class="form-select" value="qwolmox">Qwolmox</option>
              <option class="form-select" value="selenorax">Selenorax</option>
              <option class="form-select" value="terrafurisapien">Terrafurisapien</option>
              <option class="form-select" value="traxixton">Traxixton</option>
              <option class="form-select" value="utopion">Utopion</option>
              <option class="form-select" value="vortorax">Vortorax</option>
              <option class="form-select" value="xelaxarian">Xelaxarian</option>
              <option class="form-select" value="xenomorphis">Xenomorphis</option>
              <option class="form-select" value="yggdrasilith">Yggdrasilith</option>
              <option class="form-select" value="zephyrex">Zephyrex</option>
              <option class="form-select" value="zerovultura">Zerovultura</option>
              <option class="form-select" value="zorgonite">Zorgonite</option>
            </select>
            <input class="form" type="text" name="Clé_public" id="Clé_public" required placeholder="Adresse ETH">
            <input class="form-submit" type="submit" name="submit" value="S'inscrire">
          </div>
        </div>
      </div>
    </form>

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
      <p class="fp">© Copyright ---- RuBy-67 @2023
        _ Partiel E5 _ Exclus</p>
    </div>
  </footer>

</body>

</html>