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
    include 'vues/v_listeVisiteurSuivre.php';
    break;

case 'selectionnerMois':
    // On selectionne par défaut le visiteur séléctionné juste avant
    $visiteurSelection = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
    $lesVisiteur = $pdo->getLesVisiteur();
    include 'vues/v_listeVisiteurSuivre.php';
    $lesMois = $pdo->getLesMoisDisponibles($visiteurSelection);
    include 'vues/v_listeMoisSuivre.php';
    break;

case 'voirEtatFrais':
    $lesVisiteur = $pdo->getLesVisiteur();
    $visiteurSelection = filter_input(INPUT_POST, 'lstVisiteur', FILTER_SANITIZE_STRING);
    include 'vues/v_listeVisiteurSuivre.php';
    $lesMois = $pdo->getLesMoisDisponibles($visiteurSelection);
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);   
    include 'vues/v_listeMoisSuivre.php';
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurSelection, $leMois);
    $lesFraisForfait = $pdo->getLesFraisForfait($visiteurSelection, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteurSelection, $leMois);
    $numAnnee = substr($leMois, 0, 4);
    $numMois = substr($leMois, 4, 2);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    include 'vues/v_listeSuivrePaiementFrais.php';
    break;

case 'rembourseFiche':
    $visiteurId = filter_input(INPUT_POST, 'visiteurSelection', FILTER_SANITIZE_STRING);
    $moisSelection = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
    $pdo->majEtatFicheFrais($visiteurId, $moisSelection, 'RB');
    break;

}