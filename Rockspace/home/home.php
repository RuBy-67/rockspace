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

// Vérifier la connexion
if ($conn->connect_error) {
  die("La connexion a échoué: " . $conn->connect_error);
}

// Fonction pour récupérer les informations des deux articles avec la quantité la plus faible
function getArticlesMinQuantite($conn)
{
  // Requête SQL pour récupérer les informations des deux articles avec la quantité la plus faible
  $sql = "SELECT nom, description, image1, prix FROM articles WHERE quantité > 0 ORDER BY quantité ASC LIMIT 1";

  $result = $conn->query($sql);

  // Vérifier s'il y a des résultats
  if ($result->num_rows > 0) {
    // Retourner les informations des articles
    $articles = array();
    while ($row = $result->fetch_assoc()) {
      $articles[] = array("nom" => $row["nom"], "description" => $row["description"], "image1" => $row["image1"], "prix" => $row["prix"]);
    }
    return $articles;
  } else {
    return "Aucun résultat trouvé.";
  }
}

function recupererCommentaireRecent($conn)
{
  // Requête SQL pour récupérer le commentaire le plus récent avec les informations supplémentaires
  $sql_commentaire_recent = "
    SELECT c.*, a.image1, a.nom AS nom_article, l.login
    FROM commentaire c
    INNER JOIN articles a ON c.ID_ARTICLE = a.ID_ARTICLE
    INNER JOIN login l ON c.ID_CLIENT = l.ID_CLIENT
    ORDER BY c.ID_COMMENTAIRE DESC
    LIMIT 1
  ";

  // Exécution de la requête
  $result = $conn->query($sql_commentaire_recent);

  if ($result->num_rows > 0) {
    // Récupération du résultat
    $row = $result->fetch_assoc();
    return $row;
  } else {
    return null;
  }
}
// Appel de la fonction pour récupérer les informations des deux articles les moins disponibles
$articles = getArticlesMinQuantite($conn);
$commentaireRecent = recupererCommentaireRecent($conn);
// Fermer la connexion



$conn->close();
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
    content="Envie de vivre une expérience exceptionnelle et inoubliable ? Rock_Space est là pour réaliser votre rêve ! Notre agence vous propose des voyages à bord de nos vaisseaux ultra-modernes, conçues pour offrir un confort incomparable. Avec des cabines spacieuses équipées de toutes les commodités, vous pourrez profiter d'un séjour de luxe dans l'espace.">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../home/home.css">
  <!-- style produit, par défaut -->
  <link rel="stylesheet" href="../style/product_default.css">

  <!-- style footer / header -->
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
    <!-- prmeière page resumé -->
    <div class="container-desc">
      <img src="../../ship/orion/img1.png" alt="Image" class="image-princ">
      <div class="text-princ">
        <p class="pa">Envie de vivre une expérience exceptionnelle et inoubliable ? Rock_Space est là pour réaliser
          votre rêve : depuis plus de 200 ans ! depuis <strong>2194</strong> ! <br>
          Notre agence vous propose des voyages à bord de nos fusées ultra-modernes, conçues pour offrir un confort
          incomparable. Avec des cabines spacieuses équipées de toutes les commodités, vous pourrez profiter d'un séjour
          de luxe dans l'espace. <br><br>
          Mais ce n'est pas tout ! Une fois arrivé sur place, vous serez accueilli dans nos hôtels de prestige,
          spécialement conçus pour répondre à vos exigences les plus élevées en matière de confort et de relaxation.
          Profitez du plaisir de découvrir de nouvelles destinations, tout en profitant de la meilleure hospitalité de
          l'espace. <br>
          Et parce que nous savons que votre temps est précieux, nos voyages vers les planètes les plus proches ne
          prennent que quelques heures grâce à notre technologie de pointe. Imaginez-vous explorer d'autres mondes, sans
          avoir à supporter de longs trajets épuisants.
          Alors, n'hésitez plus, vivez l'aventure de votre vie avec Rock_Space. <br><br>Réservez dès maintenant votre
          voyage spatial de rêve !<a href="../Produit/produit.php ">📎</a></p>
      </div>
    </div>


    <!-- Voyage Phare -->
    <div class="container">
      <div class="voyage">
        <h2 class="title">Voyage Phare</h2>
        <div class="items">
          <a href="../Produit/article.php?nom=<?php echo urlencode($articles[0]['nom']); ?>">
            <div class="item">
              <div class="image">
                <img src="<?php echo $articles[0]['image1']; ?>" alt="Image 1">
                <span class="price">
                  <?php echo number_format($articles[0]['prix'], 0, ',', ' '); ?>
                  <img src="../img/ternoa.png" alt="ternoa">
                </span>
              </div>
              <div class="description">
                <p>
                  <?php echo "<strong>" . $articles[0]['nom'] . "<br> <br> </strong>" . substr($articles[0]['description'], 0, 110) . "..."; ?>
                </p>
              </div>
            </div>
          </a>
        </div>
      </div>


      <!--Avis-->
      <div class="avis">
        <h2 class="title">Dernier Avis</h2>
        <a class="item-large"
          href="<?php echo '../Produit/article.php?nom=' . urlencode($commentaireRecent['nom_article']); ?>">
          <div>
            <div class="image">
              <img src="<?php echo $commentaireRecent['image1']; ?>" alt="Image 1">
              <p>
                <?php echo $commentaireRecent['nom_article']; ?>
              </p>
            </div>
            <div class="description">
              <div class=titre_note>
                <p>
                  <?php echo $commentaireRecent['titre']; ?>
                </p>
                <p>
                  <?php echo $commentaireRecent['note']; ?> /5 ⭐
                </p>
              </div>
              <p>
                <?php echo $commentaireRecent['description']; ?>
              </p>
              <div class="com-bottom">
                <p>
                  <?php echo $commentaireRecent['login']; ?>
                </p>
                <p>
                  <?php echo $commentaireRecent['date']; ?>
                </p>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>


    <!--RS-->
    <div class="social-media-section">
      <center>
        <h2 class="title">Nos Réseaux</h2>
      </center>
      <div class="social-media-container">
        <a href="#"><img src="../img/R1.png" alt="rgfg"></a>
        <a href="#"><img src="../img/R2.png" alt="Twigfdgter"></a>
        <a href="#"><img src="../img/R3.png" alt="Ingstagrgam"></a>
        <a href="#"><img src="../img/R4.png" alt="Youdube"></a>
        <a href="#"><img src="../img/R5.png" alt="inkedIn"></a>
        <a href="#"><img src="../img/R6.png" alt="Pontirest"></a>
        <a href="#"><img src="../img/R7.png" alt="ymblr"></a>
        <a href="#"><img src="../img/R8.png" alt="tedit"></a>
        <a href="#"><img src="../img/R9.png" alt="apcha"></a>
      </div>
    </div>

    </div>
  </main>






  <!-- Footer -->
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