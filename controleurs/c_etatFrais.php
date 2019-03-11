<?php
/**
 * Gestion de l'affichage des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
require "./includes/fpdf.php";
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = $_SESSION['idVisiteur'];
switch ($action) {
    
case 'genererPDF':
    
    
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
    $moisASelectionner = $leMois;
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    
    
    $buffer = ob_get_clean();
    
    class PDF extends FPDF
    {
    // Page header
    function Header()
    {
        // Logo
        $this->Image('./images/logo.jpg',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(40,10,'Fiche de frais',1,0,'C');
        // Line break
        $this->Ln(25);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
    }

    // Instanciation of inherited class
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Helvetica','',12);
    
    $pdf->SetFillColor(51,122,183);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(100,6,'Fiche de frais du : ' . $numMois . '-' . $numAnnee,0,1,'L',true);
    $pdf->Ln(1);
    $pdf->SetTextColor(0,0,0);
    $pdf->Write(5,utf8_decode('Nom : ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom']));
    $pdf->Ln();
    $pdf->Write(5,utf8_decode('Etat : ' . $libEtat . ' depuis le ' . $dateModif));
    $pdf->Ln();
    $pdf->Write(5,utf8_decode('Montant validé : ' . $montantValide));
    $pdf->Ln(15);
    
    
    $pdf->SetFillColor(217,237,247);
    $pdf->Cell(160,6,utf8_decode('Frais forfaitisés :'),0,1,'L',true);
    $pdf->Ln(2);
    // Header
    foreach($lesFraisForfait as $unFraisForfait){
        $pdf->Cell(40,7,utf8_decode($unFraisForfait['libelle']),1);   
    }

    $pdf->Ln();
    // Data
    foreach($lesFraisForfait as $unFraisForfait){
        $pdf->Cell(40,6,$unFraisForfait['quantite'],1);       
    }
    $pdf->Ln(15);
    
    
    $pdf->SetFillColor(217,237,247);
    $pdf->Cell(160,6,'Frais hors-forfait :',0,1,'L',true);
    $pdf->Ln(2);
    // Header
    $pdf->Cell(25,7,'Date',1);
    $pdf->Cell(105,7,utf8_decode('Libellé'),1);
    $pdf->Cell(30,7,'Montant',1);
    $pdf->Ln();
    // Data
    foreach($lesFraisHorsForfait as $unFraisHorsForfait){
        $pdf->Cell(25,6,$unFraisHorsForfait['date'],1);
        $pdf->Cell(105,6,utf8_decode(htmlspecialchars($unFraisHorsForfait['libelle'])),1);
        $pdf->Cell(30,6,$unFraisHorsForfait['montant'],1);
        $pdf->Ln();
    }
    $pdf->Ln(15);
    
    
    $pdf->Output();
    
    
    
    
    break;
case 'selectionnerMois':
    $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
    // Afin de sélectionner par défaut le dernier mois dans la zone de liste
    // on demande toutes les clés, et on prend la première,
    // les mois étant triés décroissants
    $lesCles = array_keys($lesMois);
    $moisASelectionner = $lesCles[0];
    include 'vues/v_listeMois.php';
    break;
case 'voirEtatFrais':
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
    $moisASelectionner = $leMois;
    include 'vues/v_listeMois.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_etatFrais.php';
}
