<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$id_client = $_SESSION['ID_CLIENT'];
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
$mail = $_SESSION['mail'];
$espece = $_SESSION['espece'];
$date_depart = $_SESSION['date_depart'];
$date_retour = $_SESSION['date_retour'];
$user = $_SESSION['username'];
$montantTotal = $_SESSION['montantTotal'];
$eth = $_SESSION['eth'];
$quantite = $_SESSION['quantite'];
$Decollage = $_SESSION['Decollage'];

$pdfPath = '../paiement/pdf/' . $date_depart . $user . $id_client . '.pdf';
$pdfPath1 = '/var/www/html/rockspace/Rockspace/paiement/pdf/' . $date_depart . $user . $id_client . '.pdf';

// Connexion à la base de données
$servername = "localhost";
$username = "Ruben";
$password = "Ruben";
$dbname = "RockSpace";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Requête d'insertion dans la table "commande"
$sqlInsertCommande = "INSERT INTO commande (ID_CLIENT, montant, path) VALUES (?, ?, ?)";
$stmtInsertCommande = $conn->prepare($sqlInsertCommande);
$stmtInsertCommande->bind_param("sss", $id_client, $montantTotal, $pdfPath);
if ($stmtInsertCommande->execute() !== TRUE) {
  echo "Erreur lors de l'insertion de la commande : " . $stmtInsertCommande->error;
  $conn->close();
  exit;
}

// Requête pour récupérer les articles du panier avec leur nom
$sqlPanier = "SELECT p.quantite, a.nom, a.ID_TRANS, a.prix
              FROM panier p
              JOIN articles a ON p.ID_ARTICLE = a.ID_ARTICLE
              WHERE p.ID_CLIENT = ?";
$stmtPanier = $conn->prepare($sqlPanier);
$stmtPanier->bind_param("s", $id_client);
$stmtPanier->execute();
$resultPanier = $stmtPanier->get_result();
if (!$resultPanier) {
  echo "Erreur lors de la récupération du panier : " . $conn->error;
  $conn->close();
  exit;
}

require_once('/var/www/TCPDF-main/tcpdf.php');

// Création de l'objet TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

// Définition des informations du document
$pdf->SetCreator('RockSpace');
$pdf->SetAuthor('RockSpace');
$pdf->SetTitle('Facture_RockSpace');
$pdf->SetSubject('Facture de commande');
$pdf->SetKeywords('facture, commande');

// Ajout d'une nouvelle page
$pdf->AddPage();
// Ajout de l'image du logo
$pdf->Image('../img/logo2.png', 10, 10, 30, 0, 'PNG');

// Ajout du nom du site
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'RockSpace', 0, 1, 'C');
$pdf->Ln(10);

// Ajout du contenu
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Facture  ' . $nom, 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Fait le : ' . date('d/m/Y'), 0, 1, 'R');
$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Adresse de paiement : ', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, $eth, 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'A : ' . $nom . ' ' . $prenom, 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Commande', 0, 1, 'L');
$pdf->Ln(3);

$pdf->SetFont('helvetica', '', 11);
while ($row = $resultPanier->fetch_assoc()) {
  $quantite = $row['quantite'];
  $trans = $row['ID_TRANS'];
  $prix = $row['prix'];

  $pdf->Cell(0, 10, 'Article : ' . $row['nom'], 0, 1, 'L');
  $pdf->Cell(0, 10, 'Quantité : ' . $quantite, 0, 1, 'L');
  $prix_art = $quantite * $prix;
  $pdf->Cell(0, 10, 'Prix : ' . number_format($prix_art, 0, ',', ' ') . ' T', 0, 1, 'R');

  // Requête pour récupérer les transporteurs du panier avec leur nom
  $sqlTrans = "SELECT nom FROM transporteur WHERE ID_TRANS = ?";
  $stmtTrans = $conn->prepare($sqlTrans);
  $stmtTrans->bind_param("s", $trans);
  $stmtTrans->execute();
  $resultTrans = $stmtTrans->get_result();
  if (!$resultTrans) {
    $conn->close();
    exit;
  }

  if ($rowTrans = $resultTrans->fetch_assoc()) {
    $nom_trans = $rowTrans['nom'];
    $pdf->Cell(0, 10, 'Transporteur : ' . $nom_trans, 0, 1, 'L');
    $pdf->Cell(0, 10, '__________________________________________________________________________________________________________________________', 0, 1, 'C');
  }
}

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Date départ : ' . $date_depart, 0, 1, 'L');
$pdf->SetFont('helvetica', 'I', 10);
$pdf->Cell(0, 10, '-->' . $Decollage, 0, 1, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Date retour : ' . $date_retour, 0, 1, 'L');
$pdf->SetFont('helvetica', 'I', 10);
$pdf->Cell(0, 10, '-->' . $Decollage, 0, 1, 'L');
$pdf->SetFont('helvetica', 'I', 12);
$pdf->Cell(0, 10, 'Prix total : ' . number_format($montantTotal, 0, ',', ' ') . ' T', 0, 1, 'R');
$pdf->SetFont('helvetica', 'I', 12);
$pdf->Cell(0, 10, 'Merci pour votre commande!', 0, 1, 'C');

// Génération du PDF et enregistrement côté serveur
$pdf->Output($pdfPath1, 'F');
// Affichage du PDF
$pdf->Output($pdfPath, 'I');

// Requête pour récupérer les informations du panier du client
$sqlPanier = "SELECT ID_ARTICLE, quantite FROM panier WHERE ID_CLIENT = ?";
$stmtPanier = $conn->prepare($sqlPanier);
$stmtPanier->bind_param("s", $id_client);
$stmtPanier->execute();
$resultPanier = $stmtPanier->get_result();

// Vérification si le panier existe
if ($resultPanier->num_rows > 0) {
  // Parcours des enregistrements du panier
  while ($rowPanier = $resultPanier->fetch_assoc()) {
    $idArticle = $rowPanier['ID_ARTICLE'];
    $quantitePanier = $rowPanier['quantite'];

    // Requête pour mettre à jour la quantité dans la table "articles"
    $sqlUpdateQuantiteArticle = "UPDATE articles SET quantité = quantité - ? WHERE ID_ARTICLE = ?";
    $stmtUpdateQuantiteArticle = $conn->prepare($sqlUpdateQuantiteArticle);
    $stmtUpdateQuantiteArticle->bind_param("is", $quantitePanier, $idArticle);
    $stmtUpdateQuantiteArticle->execute();
  }

  // Suppression du panier du client
  $sqlDeletePanier = "DELETE FROM panier WHERE ID_CLIENT = ?";
  $stmtDeletePanier = $conn->prepare($sqlDeletePanier);
  $stmtDeletePanier->bind_param("s", $id_client);
  $stmtDeletePanier->execute();
}


$conn->close();

?>