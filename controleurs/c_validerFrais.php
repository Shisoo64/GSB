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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
switch ($action) {
case 'selectionnerVisiteur':
    $lesVisiteur = $pdo->getLesVisiteur();
    include 'vues/v_listeVisiteurValider.php';
    break;

case 'selectionnerMois':
    // On selectionne par défaut le visiteur séléctionné juste avant
    $visiteurSelection = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
    $lesVisiteur = $pdo->getLesVisiteur();
    include 'vues/v_listeVisiteurValider.php';
    $lesMois = $pdo->getLesMoisEtat($visiteurSelection, 'CL');
    include 'vues/v_listeMoisValider.php';
    break;

case 'voirEtatFrais':
    $lesVisiteur = $pdo->getLesVisiteur();
    $visiteurSelection = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
    include 'vues/v_listeVisiteurValider.php';
    $lesMois = $pdo->getLesMoisEtat($visiteurSelection, 'CL');
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);   
    include 'vues/v_listeMoisValider.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurSelection, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($visiteurSelection, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteurSelection, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_listeValiderFrais.php';
    break;

case 'correctFraisForfait':
    $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    $visiteurId = filter_input(INPUT_POST, 'visiteurSelection', FILTER_SANITIZE_STRING);
    $moisSelection = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
    if (lesQteFraisValides($lesFrais)) {
        $pdo->majFraisForfait($visiteurId, $moisSelection, $lesFrais);
    } else {
        ajouterErreur('Les valeurs des frais doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    
    /*Rechargement des paramètres*/
    $lesVisiteur = $pdo->getLesVisiteur();
    $visiteurSelection = $visiteurId;
    include 'vues/v_listeVisiteurValider.php';
    $lesMois = $pdo->getLesMoisEtat($visiteurSelection, 'CL');
    $leMois = $moisSelection;   
    include 'vues/v_listeMoisValider.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurSelection, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($visiteurSelection, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteurSelection, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_listeValiderFrais.php';
    
    break;

case 'correctFraisHorsForfait':
    $idFraisCorrect = filter_input(INPUT_POST, 'idFraisCorrect', FILTER_SANITIZE_STRING);
    $idFraisRefus = filter_input(INPUT_POST, 'idFraisRefus', FILTER_SANITIZE_STRING);
    $visiteurId = filter_input(INPUT_POST, 'visiteurSelection', FILTER_SANITIZE_STRING);
    $moisSelection = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
    $lesFraisDate = filter_input(INPUT_POST, 'lesFraisDate', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    $lesFraisLibelle = filter_input(INPUT_POST, 'lesFraisLibelle', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    $lesFraisMontant = filter_input(INPUT_POST, 'lesFraisMontant', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    if($idFraisCorrect != null){
        $idFrais = $idFraisCorrect;
        $pdo->majFraisHorsForfait($visiteurId, $moisSelection, $idFrais, $lesFraisDate, $lesFraisLibelle, $lesFraisMontant); 
    }else if($idFraisRefus != null){
        $idFrais = $idFraisRefus;
        $pdo->modifieStatutRefuse($idFrais, $visiteurId, $moisSelection, $lesFraisLibelle);
    }
    
    /*Rechargement des paramètres*/
    $lesVisiteur = $pdo->getLesVisiteur();
    $visiteurSelection = $visiteurId;
    include 'vues/v_listeVisiteurValider.php';
    $lesMois = $pdo->getLesMoisDisponibles($visiteurSelection);
    $leMois = $moisSelection;   
    include 'vues/v_listeMoisValider.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurSelection, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($visiteurSelection, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteurSelection, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_listeValiderFrais.php';
    
    break;

case 'valideFiche':
    $visiteurId = filter_input(INPUT_POST, 'visiteurSelection', FILTER_SANITIZE_STRING);
    $moisSelection = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
    
    $montantTotal = 0;
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurId, $moisSelection);
    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
        $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
        $montant = $unFraisHorsForfait['montant'];
        if (strpos($libelle, 'REFUSE :') === false) {
            $montantTotal += $montant;
        }
    }
    
    $pdo->valideFicheFrais($visiteurId, $moisSelection, $montantTotal);
    break;

}