<?php
session_start();
$_GET['nom'];
$nom = $_GET['nom'];
$user = $_SESSION['username'];
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
  <link rel="stylesheet" href="../Produit/article.css">

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
    <div class="produit">
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
      $sql_recup = "SELECT ID_CLIENT FROM login WHERE login = '$user'";
      $result_recup = $conn->query($sql_recup);
      $rowrecup = $result_recup->fetch_assoc();
      $ID_recup = $rowrecup['ID_CLIENT'];
      // Récupération des informations de l'article
      
      $sqlArticle = "SELECT * FROM articles WHERE nom = '$nom'";
      $resultArticle = $conn->query($sqlArticle);

      if ($resultArticle->num_rows > 0) {
        $rowArticle = $resultArticle->fetch_assoc();
        $nom = $rowArticle['nom'];
        $description = $rowArticle['description'];
        $prix = $rowArticle['prix'];
        $Id_Article = $rowArticle['ID_ARTICLE'];
        $quantiteDisponible = $rowArticle['quantité'];

        // Récupération des informations du transporteur
        $idTransporteur = $rowArticle['ID_TRANS'];
        $sqlTransporteur = "SELECT * FROM transporteur WHERE ID_TRANS = '$idTransporteur'";
        $resultTransporteur = $conn->query($sqlTransporteur);

        if ($resultTransporteur->num_rows > 0) {
          $rowTransporteur = $resultTransporteur->fetch_assoc();
          $nomTransporteur = $rowTransporteur['nom'];
          $imgTransporteur = $rowTransporteur['img'];
          $img2Transporteur = $rowTransporteur['img2'];
          $descTrans = $rowTransporteur['description'];
        } else {
          $nomTransporteur = "Transporteur inconnu";
          $imgTransporteur = "";
          $img2Transporteur = "";
          $descTrans = "";
        }



        // Affichage des informations
        echo '<div class="image-presentation"><img src="' . $rowArticle['image1'] . '" alt="Image de présentation"></div>';
        echo '<div class="nom-information">';
        echo '<div class="h2">';
        echo '<h2>' . $nom . '</h2>';
        // Requête SQL pour calculer la moyenne des notes
        $sql_moyenne_notes = "SELECT AVG(note) AS moyenne_notes FROM commentaire WHERE ID_ARTICLE = $Id_Article";

        // Exécution de la requête
        $result = $conn->query($sql_moyenne_notes);

        if ($result->num_rows > 0) {
          // Récupération du résultat
          $row = $result->fetch_assoc();
          $moyenne_notes = $row['moyenne_notes'];
          $moyenne_formattee = number_format($moyenne_notes, 1);
          echo "<p class='moyenne'>$moyenne_formattee /5 ⭐</p>";
        } else {
          echo "NN";
        }
        echo '</div>';
        echo '<div class="information">';
        echo '<p class="description">' . $description . '</p>';
        echo '<div class="prix-transporteur">';
        echo '<div class="transporteur">';
        echo '<p>Moyen de Transport </p>';
        echo '<p class="nom-transporteur">' . $nomTransporteur . '</p>';
        echo '<div class="img-container">';
        echo '<img class="img-transporteur" src="' . $imgTransporteur . '" alt="Image transporteur">';
        echo '<img class="img-transporteur" src="' . $img2Transporteur . '" alt="Image transporteur">';
        echo '</div>';
        echo ' <p class="description-transporteur">' . substr($descTrans, 0, 100) . ' ...</p>';
        echo '</div>';
        echo '<p class="prix">Prix: ' . $prix . '&nbsp<img src="../img/ternoa.png" alt="ternoa"></p> ';
        echo '<form method="post" action="">';
        echo '<div class="row">';
        echo '<button class="ajout-panier" name="ajout-panier"><img class="img-panier" src="../img/panier.png" alt="Image transporteur">➕</button>';
        echo '<input type="number" class="quantite-input" name="quantite" min="0" max="99" value="1">';
        echo '</div>';
        echo '</form>';
        echo '<p class="quantite-restante">Quantité restante: ' . $quantiteDisponible . '</p>';

        if (isset($_POST['ajout-panier'])) {
          $quantite = $_POST['quantite'];

          if (!empty($ID_recup)) {
            if ($quantite <= $quantiteDisponible) {
              // Requête SQL pour ajouter au panier 
              $sql_Insert = "INSERT INTO panier (ID_CLIENT, ID_ARTICLE, quantite)
          VALUES ('$ID_recup', '$Id_Article', '$quantite')";

              // Exécution de la requête
              if ($conn->query($sql_Insert) === TRUE) {
                echo "<p class='green'>La commande de $quantite a été ajoutée à votre panier avec succès.</p>";
              } else {
                echo "<p class='error'>Erreur lors de l'ajout de la commande au panier : </p>" . $conn->error;
              }
            } else {
              echo "<p class='error'>La quantité insuffisante.</p>";
            }
          } else {
            echo "<p class='error'>Veuillez vous <a href='../login/login.php'>connecter</a> pour pouvoir ajouter des articles au panier.</p>";
          }
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        // Affichage des images d'hôtel
        echo '<div class="image-hotel">';
        echo '<img src="' . $rowArticle['image2'] . '" alt="Image hôtel 1">';
        echo '<img src="' . $rowArticle['image3'] . '" alt="Image hôtel 2">';
        echo '<img src="' . $rowArticle['image4'] . '" alt="Image hôtel 3">';
        echo '</div>';
      } else {
        echo 'Aucun article trouvé.';
      }



      $conn->close();
      ?>


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

      // Supposons que vous avez l'ID de l'article dans une variable $idArticle
      $idArticle = $rowArticle['ID_ARTICLE'];

      // Récupération des commentaires de l'article
      $sql_commentaire = "SELECT * FROM commentaire WHERE ID_ARTICLE = $idArticle";
      $result_commentaire = $conn->query($sql_commentaire);

      if ($result_commentaire->num_rows > 0) {
        echo '<center><h2 class="title">Avis</h2></center>';
        echo '<div class="avis">';

        // Affichage des commentaires existants
        while ($rowCommentaire = $result_commentaire->fetch_assoc()) {
          $ID_COM_CLIENT = $rowCommentaire['ID_CLIENT'];
          $titre_commentaire = $rowCommentaire['titre'];
          $description_commentaire = $rowCommentaire['description'];
          $note_commentaire = $rowCommentaire['note'];
          $date = $rowCommentaire['date'];
          $ID_COMMENTAIRE = $rowCommentaire['ID_COMMENTAIRE'];

          // Récupération du login de l'auteur du commentaire
          $sql_login = "SELECT login FROM login WHERE ID_CLIENT = '$ID_COM_CLIENT'";
          $result_login = $conn->query($sql_login);
          $rowlogin = $result_login->fetch_assoc();
          $login_name = $rowlogin['login'];
          // Vérification de l'utilisateur
      
          $isAuthor = ($user == $login_name); // Vérifie si l'utilisateur est l'auteur du commentaire
          $isAdmin = ($user == 'Admin'); // Vérifie si l'utilisateur est un administrateur
      
          echo '<div class="avis-1">';
          echo '<div class="Titre-note">';
          echo '<p>' . $titre_commentaire . '</p>';
          echo '<p>' . $note_commentaire . ' /5 ⭐</p>';
          echo '</div>';
          echo '<p>' . $description_commentaire . '</p>';
          echo '<div class="bas">';
          echo '<p>Auteur: ' . $login_name . '</p>';
          echo '<p>' . $date . '</p>';
          echo '</div>';
          // Affichage du bouton de suppression si l'utilisateur est l'auteur ou un administrateur
          if ($isAuthor || $isAdmin) {
            echo '<form method="POST" action="">';
            echo '<input type="hidden" name="commentaire_id" value="' . $rowCommentaire['ID_COMMENTAIRE'] . '">';
            echo '<center><button type="submit" name="supprimer-commentaire" class="supprimer-commentaire">Supprimer</button></center>';
            echo '</form>';

          }
          echo '</div>';
        }

        echo '</div>';
      } else {
        echo '<div class="avis">';
        echo '<h2 class="title">Avis</h2>';
        echo '<p>Aucun commentaire trouvé.</p>';
        echo '</div>';
      }

      // Suppression du commentaire
      if (isset($_POST['supprimer-commentaire'])) {
        $commentaire_id = $_POST['commentaire_id'];

        // Requête SQL pour supprimer le commentaire
        $sql_delete_commentaire = "DELETE FROM commentaire WHERE ID_COMMENTAIRE = '$commentaire_id'";

        // Exécution de la requête
        if ($conn->query($sql_delete_commentaire) === TRUE) {
          echo "<p class='green'>Le commentaire a été supprimé avec succès.</p>";
        } else {
          echo "<p class='error'>Erreur lors de la suppression du commentaire :</p> " . $conn->error;
        }
      }

      // Formulaire pour ajouter un avis
      echo '<div class="add-avis">';
      echo '<form class="ajout" method="post" action="">';
      echo '<input type="number" class="note" name="note" placeholder="Note" min="0" max="5">';
      echo '<input type="text" class="add" name="titre" placeholder="Titre">';
      echo '<textarea class="descri" name="description" placeholder="Avis"></textarea>';
      echo '<button type="submit" name="ajouter">Ajouter avis</button>';
      echo '</form>';
      echo '</div>';

      // Traitement de l'ajout d'un avis
      if (isset($_POST['ajouter'])) {
        // Récupération des données du formulaire
        $titre = $_POST['titre'];
        $note = $_POST['note'];
        $description = mysqli_real_escape_string($conn, $_POST['description']);


        // Insertion du nouvel avis dans la table commentaire
        $sqlInsert = "INSERT INTO commentaire (ID_CLIENT, ID_ARTICLE, titre, description, note, date)
                VALUES ('$ID_recup', '$idArticle', '$titre', '$description', '$note', CURRENT_DATE)";
        $conn->query($sqlInsert);
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