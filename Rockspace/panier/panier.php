<?php
session_start();
$user = $_SESSION['username']
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
  <link rel="stylesheet" href="../panier/panier.css">

  <!--Style header footer-->
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

    if ($result_user->num_rows > 0) {
      $row_user = $result_user->fetch_assoc();
      $ID_client = $row_user['ID_CLIENT'];
      echo "<center><h2 class='title'>Panier</h2></center>";
      // Requête SQL pour récupérer les articles du panier de l'utilisateur
      $sql_panier = "SELECT panier.ID_PANIER, panier.ID_ARTICLE, panier.quantite, articles.nom, articles.prix, articles.image1, articles.description
                 FROM panier
                 INNER JOIN articles ON panier.ID_ARTICLE = articles.ID_ARTICLE
                 WHERE panier.ID_CLIENT = '$ID_client'";

      $result_panier = $conn->query($sql_panier);

      if ($result_panier->num_rows > 0) {
        echo '<table class="panier">';
        echo '<tr class="Article-panier-titre">';
        echo '<th> </th>';
        echo '<th>Produit</th>';
        echo '<th>Prix-Unité</th>';
        echo '<th>Quantité</th>';
        echo '<th>Prix-total</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        $prix_total = 0;

        while ($row_panier = $result_panier->fetch_assoc()) {
          $id_panier = $row_panier['ID_PANIER'];
          $nom = $row_panier['nom'];
          $prix = $row_panier['prix'];
          $quantite = $row_panier['quantite'];
          $prix_total_article = $prix * $quantite;
          $image1 = $row_panier['image1'];

          echo '<tr class="Article-panier-tab">';
          echo '<td class="img1"><a href="../Produit/article.php?nom=' . urlencode($nom) . '"><img class="hotel" src="' . $image1 . '" alt="' . $nom . '"></a></td>';
          echo '<td class="nom">' . $nom . '</td>';
          echo '<td class="prix1"> <div class="prix"><p>' . $prix . '&nbsp</p><img src="../img/ternoa.png" alt="ternoa" ></div> </td>';
          echo '<td class="quant">';
          echo '<form method="post" action="">';
          echo '<input type="hidden" name="id_panier" value="' . $id_panier . '">';
          echo '<div class="row"><input type="number" class="quantite-input" name="quantite" min="1" max="99" value="' . $quantite . '">';
          echo '<input class="maj" type="submit" name="modifier_quantite" value="📤"></div>';
          echo '</form>';
          echo '</td>';
          echo '<td class="prix2"><div class="prix"><p>' . $prix_total_article . '&nbsp</p><img  src="../img/ternoa.png" alt="ternoa"></div></td>';
          echo '<td>';
          echo '<form method="post" action="">';
          echo '<input type="hidden" name="id_panier" value="' . $id_panier . '">';
          echo '<input class="del" type="submit" name="supprimer_article" value="🗑️">';
          echo '</form>';
          echo '</td>';
          echo '</tr>';

          $prix_total += $prix_total_article;
        }
        echo '</table>';
        echo '<div class="bottom"><div class="prix-total"><p>Prix total : ' . $prix_total . '&nbsp</p><img src="../img/ternoa.png" alt="ternoa"></div>';
        echo '<form method="post" action="">';
        echo '<input class="code-input" type="text" name="code" placeholder="code">';
        echo '<input class="code-submit" type="submit" name="code_promo" value="Soumettre">';
        echo '</form>';
        echo '<div><a class="command" href="../paiement/resume.php">Commander</a></div></div>';

        if (isset($_POST['code_promo'])) {
          $code_promo = $_POST['code'];

          // Requête SQL pour chercher le code promo dans la table code_promos
          $sql_code_promo = "SELECT montant, code FROM code_promos WHERE code = '$code_promo'";
          $result_code_promo = $conn->query($sql_code_promo);

          if ($result_code_promo->num_rows > 0) {
            $row_code_promo = $result_code_promo->fetch_assoc();
            $montant_reduction = $row_code_promo['montant'];
            $code = $row_code_promo['code'];
            $_SESSION['montant'] = $montant_reduction;
            $_SESSION['code_promo'] = $code;

            // Appliquer la réduction sur le prix total
            echo '<p class="code_p">Code promo <span>' . $code . '</span> appliqué : réduction de ' . $montant_reduction . ' <img src="../img/ternoa.png" alt="ternoa" width="20px"></p>';
          } else {
            echo '<p class=error>Code invalide.</p>';
          }
        }

      } else {
        header("Location: ../Produit/produit.php");
      }
    } else {
      echo 'Utilisateur non trouvé.';
    }

    // Modifier la quantité d'un article dans le panier
    if (isset($_POST['modifier_quantite'])) {
      $id_panier = $_POST['id_panier'];
      $quantite = $_POST['quantite'];

      $sql_update_quantite = "UPDATE panier SET quantite = '$quantite' WHERE ID_PANIER = '$id_panier'";
      if ($conn->query($sql_update_quantite) === TRUE) {
        // Mise à jour réussie, vous pouvez afficher un message ou effectuer une autre action si nécessaire
      } else {
        echo 'Erreur lors de la mise à jour de la quantité : ' . $conn->error;
      }
    }

    // Supprimer un article du panier
    if (isset($_POST['supprimer_article'])) {
      $id_panier = $_POST['id_panier'];

      $sql_delete_article = "DELETE FROM panier WHERE ID_PANIER = '$id_panier'";
      if ($conn->query($sql_delete_article) === TRUE) {
        // Suppression réussie, vous pouvez afficher un message ou effectuer une autre action si nécessaire
      } else {
        echo 'Erreur lors de la suppression de l\'article : ' . $conn->error;
      }
    }

    $conn->close();
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