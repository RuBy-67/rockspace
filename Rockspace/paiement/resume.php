<?php
session_start();
$montant_reduction = $_SESSION['montant'];
$code = $_SESSION['code_promo'];
$user = $_SESSION['username'];
$date_depart = $_SESSION['date_depart'];
$date_retour = $_SESSION['date_retour'];
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
  <link rel="stylesheet" href="../paiement/resume.css">

  <!--Style header footer-->
  <script type="text/javascript" src="https://files.coinmarketcap.com/static/widget/currency.js"></script>
  <link rel="stylesheet" href="../style/header_footer.css">
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
    if ($conn->connect_error) {
      die("Échec de la connexion à la base de données: " . $conn->connect_error);
    }

    // Récupération de l'ID du client à partir de la table login
    $sql_user = "SELECT ID_CLIENT FROM login WHERE login = '$user'";
    $result_user = $conn->query($sql_user);
    $row_user = $result_user->fetch_assoc();
    $ID_client = $row_user['ID_CLIENT'];


    // Récupération des informations du client
    $sql_client = "SELECT ID_CLIENT, nom, prenom, mail, espèce FROM client WHERE ID_CLIENT = '$ID_client'";
    $result_client = $conn->query($sql_client);

    if ($result_client->num_rows > 0) {
      $row_client = $result_client->fetch_assoc();
      $id_client = $row_client['ID_CLIENT'];
      $nom = $row_client['nom'];
      $prenom = $row_client['prenom'];
      $mail = $row_client['mail'];
      $espece = $row_client['espèce'];

      // Enregistrer les informations du client dans la session
      $_SESSION['ID_CLIENT'] = $id_client;
      $_SESSION['nom'] = $nom;
      $_SESSION['prenom'] = $prenom;
      $_SESSION['mail'] = $mail;
      $_SESSION['espece'] = $espece;

      // Afficher le résumé de la commande
      echo '<center><h2 class="title">Résumé de la commande</h2></center>';

      // Coordonnées du client
      echo '<h3>Coordonnées du client</h3>';
      echo '<table class="client">';
      echo '<tr><td>Nom</td><td>Prénom</td><td>Mail</td><td>Espèce</td></tr>';
      echo '<tr><td>' . $nom . '</td><td>' . $prenom . '</td><td>' . $mail . '</td><td>' . $espece . '</td></tr>';
      echo '</table>';
      echo '<a class="input" href="../login/user_profile.php">Modifier</a>';

      // Moyen de paiement
      echo '<h3>Moyen de paiement</h3>';

      // Récupération de la clé publique de paiement ETH du client
      $sql_eth = "SELECT clé_public FROM ETH WHERE ID_CLIENT = '$id_client'";
      $result_eth = $conn->query($sql_eth);

      if ($result_eth->num_rows > 0) {
        $row_eth = $result_eth->fetch_assoc();
        $cle_public = $row_eth['clé_public'];
        $_SESSION['eth'] = $cle_public;
        echo '<p class="payment-method">Clé publique ETH : <span>' . $cle_public . '</span> <br> <a class="input" href="../login/user_profile.php">Modifier</a></p>';
      } else {
        echo '<p>Aucun moyen de paiement enregistré.</p>';
      }

      // Panier du client
      echo '<h3>Panier</h3>';

      // Requête SQL pour récupérer les articles du panier du client
      $sql_panier = "SELECT panier.ID_ARTICLE, panier.quantite, articles.nom, articles.prix, articles.ID_TRANS, articles.image1, articles.image2, articles.image3
                 FROM panier
                 INNER JOIN articles ON panier.ID_ARTICLE = articles.ID_ARTICLE
                 WHERE panier.ID_CLIENT = '$id_client'";

      $result_panier = $conn->query($sql_panier);

      if ($result_panier->num_rows > 0) {
        echo '<table class="cart">';
        echo '<tr><td>Nom</td><td>Prix</td><td>Image</td><td>Quantité</td><td>Transporteur</td><td>Départ</td><td>Image</td></tr>';

        while ($row_panier = $result_panier->fetch_assoc()) {
          $id_article = $row_panier['ID_ARTICLE'];
          $nom_article = $row_panier['nom'];
          $_SESSION['nom_article'] = $nom_article;
          $prix_article = $row_panier['prix'];
          $id_trans = $row_panier['ID_TRANS'];
          $image1 = $row_panier['image1'];
          $image2 = $row_panier['image2'];
          $image3 = $row_panier['image3'];
          $quantite = $row_panier['quantite'];
          $_SESSION['quantite'] = $quantite;
          $prix_total_article = $prix_article * $quantite;

          echo '<tr><td>' . $nom_article . '</td><td>' . $prix_article . ' T</td><td class="img"><img class="hotel" src="' . $image1 . '" alt="' . $nom . '"><img class="hotel" src="' . $image2 . '" alt="' . $nom . '"><img class="hotel" src="' . $image3 . '" alt="' . $nom . '"></td><td>' . $quantite . '</td>';


          $sql_transport = "SELECT nom, img2 FROM transporteur WHERE ID_TRANS = '$id_trans'";
          $result_transport = $conn->query($sql_transport);

          if ($result_transport->num_rows > 0) {
            $row_transport = $result_transport->fetch_assoc();
            $nom_transport = $row_transport['nom'];
            $img2 = $row_transport['img2'];

            $Decollage = "AéroGare Orly-Musk PAR-759, Haute-Algèrie";
            $_SESSION['Decollage'] = $Decollage;
            echo '<td>' . $nom_transport . '</td><td>' . $Decollage . '</td><td class="img"><img class="hotel" src="' . $img2 . '" alt="' . $nom_transport . '"></td>';
          } else {
            echo '<td>NON FOURNIS</td><td>NON FOURNIS</td><td>INCONNUS</td>';
          }
          echo '</tr>';
        }

        echo '</table>';
      } else {
        echo '<p>Le panier est vide.</p>';
        header('Location: ../home/home.php');
      }


      // Date de la commande
      echo ' <h3>Date de Voyage</h3>
<form method="post" action="">
  <table class="date">
    <tr>
      <th>Date de départ : </th>
      <td>
        <input value="2395-06-07" class="choix" type="date" name="date_depart" required>
      </td>
    </tr>
    <tr>
      <th>Date de retour :</th>
      <td>
        <input value="2396-06-07"  class="choix" type="date" name="date_retour" required>
      </td>
    </tr>
  </table>
  <input type="submit" class="submit" name="submit-date" value="Valider les dates">
</form>';


      if (isset($_POST['submit-date'])) {
        // Récupération des valeurs entrées par l'utilisateur
        $date_depart = $_POST['date_depart'];
        $date_retour = $_POST['date_retour'];

        // Enregistrement des valeurs dans des variables de session
        $_SESSION['date_depart'] = $date_depart;
        $_SESSION['date_retour'] = $date_retour;

        echo '<p class="green">Date enregistrée, de ' . $date_depart . ' à ' . $date_retour . ' Préparez vos valises !</p>';
      }


      // Code promo et montant total de la commande
      echo '<h3>Code promo</h3>';
      if (isset($_SESSION['montant']) && isset($_SESSION['code_promo'])) {
        $montant_reduction = $_SESSION['montant'];
        $code_promo = $_SESSION['code_promo'];

        echo '<p class="code-promo">Code promo : ' . $code_promo . '</p>';
        echo '<p class="code-promo">Réduction : ' .  number_format($montant_reduction, 0, ',', ' ')  . '</p>';
        echo '<h3>Montant total de la commande</h3>';
        echo '<p class="total-amount">Prix Avant réduction : ' .  number_format($prix_total_article, 0, ',', ' ') . '</p>';
        $montant_total = $prix_total_article - $montant_reduction;
        $_SESSION['montantTotal'] = $montant_total;
        echo '<p>Montant total : ' .  number_format($montant_total, 0, ',', ' ') . '</p>';
      } else {
        $montant_total = $prix_total_article - $montant_reduction;
        $_SESSION['montantTotal'] = $montant_total;
        echo '<p>Montant total : ' . number_format($montant_total, 0, ',', ' ') . '</p>';
        echo '<p>Aucun code promo appliqué.</p>';
      }

      echo '<div class=button-container>';
      // Bouton de modification de la commande
      echo '<div><a class="command" href="../panier/panier.php">Modifier la commande</a></div>';

      // Bouton de validation de la commande
      echo '<a class="command" href="../paiement/paiement.php">Payer</a></div>';

      // Fermer la connexion à la base de données
      $conn->close();
    } else {
      echo '<p class="error">Client non trouvé.</p>';
    }
    ?>

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