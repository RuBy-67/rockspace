<?php
session_start();
$user = $_SESSION['username'];
if ($user != "Admin") {
  header('Location: ../home/home.php');
  exit();
}
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
  <link rel="stylesheet" href="../gestion/gestion_stock.css">

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
    <div>
      <center>
        <h2 class="title">Gestion Stock</h2>
      </center>

      <div class="stock-article">
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
        // Récupération des articles de la table "article"
        $sql = "SELECT articles.*, transporteur.nom AS transporteur_nom FROM articles JOIN transporteur ON articles.ID_TRANS = transporteur.ID_TRANS";
        $result = $conn->query($sql);

        // Vérification du nombre d'articles
        if ($result->num_rows > 0) {
          // Affichage du tableau
          echo "";
          echo "<table class='Article-list'>";
          echo "<tr><th>Nom</th><th>Quantité</th><th>Prix</th><th>Description</th></th><th>transporteur</th><th>Image</th><th>Image</th><th>Image</th><th>Image</th><th>Action</th></tr>";

          // Affichage des articles
          while ($row = $result->fetch_assoc()) {
            // Récupération des transporteurs de la table "transporteur"
            $sql_transporteur = "SELECT * FROM transporteur";
            $result_transporteur = $conn->query($sql_transporteur);

            echo "<form method='post' class='article-update'><tr>";

            echo "<td><input class='btn-a' name=\"nom[" . $row["ID_ARTICLE"] . "]\" type=\"text\" value=\"" . $row["nom"] . "\" placeholder=\"Nom\"></td>";

            echo "<td><input class='btn-a' name=\"quantité[" . $row["ID_ARTICLE"] . "]\" type=\"number\" value=\"" . $row["quantité"] . "\" placeholder=\"Quantité\"></td>";

            echo "<td><input class='btn-a' name=\"prix[" . $row["ID_ARTICLE"] . "]\" type=\"number\" value=\"" . $row["prix"] . "\" placeholder=\"Prix\"></td>";

            echo "<td><input class='btn-a' id='big' name=\"description[" . $row["ID_ARTICLE"] . "]\" type=\"text\" value=\"" . $row["description"] . "\" placeholder=\"Description\"></td>";


            // Vérification du nombre de transporteurs
            if ($result_transporteur->num_rows > 0) {
              // Affichage de la liste déroulante avec les transporteurs
              echo "<td><select class='btn-a' name=\"ID_TRANS\">";
              while ($row_transporteur = $result_transporteur->fetch_assoc()) {
                echo "<option class='btn-a' name=\"nom_trans[" . $row["ID_ARTICLE"] . "]\" value=\"" . $row_transporteur["ID_TRANS"] . "\"";
                if ($row["ID_TRANS"] == $row_transporteur["ID_TRANS"]) {
                  echo " selected";
                }
                echo ">" . $row_transporteur["nom"] . "</option>";
              }
              echo "</select></td>";
            } else {
              echo "<td><input name=\"\" type=\"text\" value=\"Pas de transporteur trouvé\"></td>";
            }
            echo "<td><a href=\"" . $row["image1"] . "\"><img class='hotel-img' src=\"" . $row["image1"] . "\" width=\"15\" height=\"15\"></a></td>";
            echo "<td><a href=\"" . $row["image2"] . "\"><img class='hotel-img' src=\"" . $row["image2"] . "\" width=\"15\" height=\"15\"></a></td>";
            echo "<td><a href=\"" . $row["image3"] . "\"><img class='hotel-img' src=\"" . $row["image3"] . "\" width=\"15\" height=\"15\"></a></td>";
            echo "<td><a href=\"" . $row["image4"] . "\"><img class='hotel-img' src=\"" . $row["image4"] . "\" width=\"15\" height=\"15\"></a></td>";
            echo "<td><input type='hidden' name='article_id' value='" . $row["ID_ARTICLE"] . "'><button class='maj' type='submit' name='update_{$row["ID_ARTICLE"]}' class='btn'>📤</button><button class='maj-d' type='submit' name='delete_{$row["ID_ARTICLE"]}' class='btn'>🗑️</button></td>";
            echo "</tr></form>";
          }

          echo "<tr><form method='post' class='article-add'>";
          echo "<td><input class='btn-a' name='nom' type='text' placeholder='Nom'></td>";
          echo "<td><input class='btn-a' name='quantite' type='number' placeholder='Quantité'></td>";
          echo "<td><input class='btn-a' name='prix' type='number' placeholder='Prix'></td>";
          echo "<td><input class='btn-a' name='description' type='text' placeholder='Description'></td>";

          // Récupération des transporteurs de la table "transporteur"
          $sql_transpo = "SELECT * FROM transporteur";
          $result_transpo = $conn->query($sql_transpo);
          // Vérification du nombre de transporteurs
          if ($result_transpo->num_rows > 0) {
            // Affichage de la liste déroulante avec les transporteurs
            echo "<td><select class='btn-a' name='ID_TRANS'>";
            while ($row_transpo = $result_transpo->fetch_assoc()) {
              echo "<option class='btn-a' value='" . $row_transpo["ID_TRANS"] . "'>" . $row_transpo["nom"] . "</option>";
            }
            echo "</select></td>";
          } else {
            echo "<td><input name='' type='text' value='Pas de transporteur trouvé'></td>";
          }

          echo "<td><input class='btn-a' name='image1' type='text' placeholder='Lien image 1'></td>";
          echo "<td><input class='btn-a' name='image2' type='text' placeholder='Lien image 2'></td>";
          echo "<td><input class='btn-a' name='image3' type='text' placeholder='Lien image 3'></td>";
          echo "<td><input class='btn-a' name='image4' type='text' placeholder='Lien image 4'></td>";
          echo "<td><button class='maj' type='submit' name='add_article' class='btn'>➕</button></td>";
          echo "</form></tr>";

          echo "</table>";
        } else {
          echo "<p class='error'>Pas d'article trouvé.</p>";
        }

        foreach ($_POST as $key => $value) {
          if (strpos($key, 'update_') === 0) {
            $article_id = substr($key, 7);
            $nom = $_POST['nom'][$article_id];
            $quantité = $_POST['quantité'][$article_id];
            $prix = $_POST['prix'][$article_id];
            $description = $_POST['description'][$article_id];
            $ID_TRANS = intval($_POST['ID_TRANS']);

            $sql = "UPDATE articles SET nom='$nom', quantité='$quantité', prix='$prix', description='$description', ID_TRANS='$ID_TRANS' WHERE ID_ARTICLE='$article_id';";

            $result = $conn->query($sql);

            if ($result) {
              echo "<p class='green'>mis à jour avec succès.</p> ";
            } else {
              echo "<p class='error'>Erreur lors de la mise à jour</p> " . $conn->error;
            }
          }
        }

        foreach ($_POST as $key => $value) {
          if (strpos($key, 'delete_') === 0) {
            $article_id = substr($key, 7);

            $sql = "DELETE FROM articles WHERE ID_ARTICLE='$article_id';";
            $result = $conn->query($sql);

            if ($result) {
              echo "<p class='green'>Ligne suprimmé avec succés.</p> ";
            } else {
              echo "<p class='error'>Echec lors de la supressions</p> " . $conn->error;
            }
          }
        }

        if (isset($_POST['add_article'])) {
          // Récupération des valeurs entrées dans le formulaire
          $nom = $_POST['nom'];
          $quantite = $_POST['quantite'];
          $prix = $_POST['prix'];
          $description = $_POST['description'];
          $ID_TRANS = $_POST['ID_TRANS'];
          $image1 = $_POST['image1'];
          $image2 = $_POST['image2'];
          $image3 = $_POST['image3'];
          $image4 = $_POST['image4'];

          // Vérification que toutes les données ont été saisies
          if (!empty($nom) && !empty($quantite) && !empty($prix) && !empty($description) && !empty($ID_TRANS) && !empty($image1) && !empty($image2) && !empty($image3) && !empty($image4)) {
            // Création de la requête SQL pour ajouter l'article
            $sql = "INSERT INTO articles (nom, quantité, prix, description, ID_TRANS, image1, image2, image3, image4)
            VALUES ('$nom', '$quantite', '$prix', '$description', '$ID_TRANS', '$image1', '$image2', '$image3', '$image4')";

            // Exécution de la requête SQL
            if ($conn->query($sql) === TRUE) {
              echo "L'article a été ajouté avec succès !";
            } else {
              echo "Erreur lors de l'ajout de l'article : " . $conn->error;
            }
          } else {
            echo "Veuillez remplir tous les champs obligatoires.";
          }
        }

        ?>
      </div>


      <div class="stock-ship">
        <?php
        // Récupération des transporteurs
        $sql = "SELECT * FROM transporteur";
        $result_transporteur = $conn->query($sql);

        if ($result_transporteur->num_rows > 0) {
          // Affichage des transporteurs
          echo "<table class='Transporteur-list'>";
          echo "<thead><tr><th>Image</th><th>Nom</th><th>Description</th><th>Image 2</th><th>Hotel_livré</th><th>Action</th></tr></thead>";
          echo "<tbody>";
          while ($row_transporteur = $result_transporteur->fetch_assoc()) {
            echo "<form method='post' class='transporteur_update'><tr>";
            echo "<td><a href=\"" . $row_transporteur["img"] . "\"><img class='trans-img' src=\"" . $row_transporteur["img"] . "\" width=\"30\" height=\"30\"></a></td>";
            echo "<td><input class='btn-a' name=\"nom[" . $row_transporteur["ID_TRANS"] . "]\" type=\"text\" value=\"" . $row_transporteur["nom"] . "\" placeholder=\"Nom\"></td>";
            echo "<td><input class='btn-a'  id='big' name=\"description[" . $row_transporteur["ID_TRANS"] . "]\" type=\"text\" value=\"" . $row_transporteur["description"] . "\" placeholder=\"Description\"></td>";

            echo "<td><a href=\"" . $row_transporteur["img2"] . "\"><img class='hotel-img' src=\"" . $row_transporteur["img2"] . "\" width=\"15\" height=\"15\"></td>";

            // Récupération des articles livrés par le transporteur
            $sql = "SELECT nom FROM articles WHERE ID_TRANS=" . $row_transporteur["ID_TRANS"];
            $result_article = $conn->query($sql);

            if ($result_article->num_rows > 0) {
              echo "<td><ul>";
              while ($row_article = $result_article->fetch_assoc()) {
                echo "<li class='article-livré'>" . $row_article["nom"] . "</li>";
              }
              echo "</ul></td>";
            }

            echo "<td><input type='hidden' name='transporteur_id' value='" . $row_transporteur["ID_TRANS"] . "'><button class='maj' type='submit' name='update_transporteur_{$row_transporteur["ID_TRANS"]}' class='btn'>📤</button><button class='maj-d' type='submit' name='delete_transporteur_{$row_transporteur["ID_TRANS"]}' class='btn'>🗑️</button></td>";
            echo "</tr></form>";
          }

          // Formulaire pour ajouter un transporteur
          echo "<form method='post' class='transporteur_add'><tr>";
          echo "<td><input class='btn-a' name=\"img\" type=\"text\" placeholder=\"Image\"></td>";
          echo "<td><input class='btn-a' name=\"nom\" type=\"text\" placeholder=\"Nom\"></td>";
          echo "<td><input class='btn-a' name=\"description\" type=\"text\" placeholder=\"Description\"></td>";
          echo "<td><input class='btn-a' name=\"img2\" type=\"text\" placeholder=\"Image 2\"></td>";
          echo "<td></td>";
          echo "<td><button class='maj' type='submit' name='add_transporteur' class='btn'>➕</button></td>";
          echo "</form>";
          echo "</tbody></table>";
        } else {
          echo "<p class='error'>Pas de transporteur trouvé.</p>";
        }

        ///update
        foreach ($_POST as $key => $value) {
          if (strpos($key, 'update_transporteur_') === 0) {
            $id_trans = substr($key, 20);
            $tnom = $_POST['nom'][$id_trans];
            $description = $_POST['description'][$id_trans];

            // Vérification que toutes les données ont été saisies
            $sql = "UPDATE transporteur SET nom='$tnom', description='$description' WHERE ID_TRANS='$id_trans'";
            // Exécution de la requête SQL
            if ($conn->query($sql) === TRUE) {
              echo "<p class='green'>Update a été ajouté avec succès !</p>";
            } else {
              echo "<p class='error'>Erreur lors de l'ajout de l'update :</p> " . $conn->error;
            }
          }
        }
        ///delete
        foreach ($_POST as $key => $value) {
          if (strpos($key, 'delete_transporteur_') === 0) {
            $id_trans = substr($key, 20);

            $sql = "DELETE FROM transporteur WHERE ID_TRANS='$id_trans';";
            $result = $conn->query($sql);

            if ($result) {
              echo "<p class='green'>Ligne suprimmé avec succés.</p> ";
            } else {
              echo "<p class='error'>Echec lors de la supressions</p> " . $conn->error;
            }
          }
        }
        ///add
        if (isset($_POST['add_transporteur'])) {
          // Récupération des données du formulaire
          $img = $_POST['img'];
          $nom = $_POST['nom'];
          $description = $_POST['description'];
          $img2 = $_POST['img2'];
          if (!empty($nom) && !empty($quantite) && !empty($prix) && !empty($description) && !empty($ID_TRANS) && !empty($image1) && !empty($image2) && !empty($image3) && !empty($image4)) {
            // Préparation de la requête SQL
            $sql = "INSERT INTO transporteur (img, nom, description, img2) VALUES ('$img', '$nom', '$description', '$img2')";

            // Exécution de la requête SQL
            if ($conn->query($sql) === TRUE) {
              echo "Le transporteur a été ajouté avec succès !";
            } else {
              echo "Erreur lors de l'ajout du transporteur : " . $conn->error;
            }
          } else {
            echo "Veuillez remplir tous les champs obligatoires.";
          }
        }

        ?>

      </div>
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