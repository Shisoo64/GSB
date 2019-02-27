<?php
/**
 * Vue Valider les frais forfait
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
?>


<!-- Forfaitisé -->
<hr>
<div class="row">    
    <h2>Valider fiche de frais du mois 
        <?php echo $numMois . '-' . $numAnnee ?>
    </h2>
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <?php
        foreach ($lesFraisForfait as $unFrais) {
        $libelle = htmlspecialchars($unFrais['libelle']);
        $quantite = $unFrais['quantite']; ?>
            <label for="idFrais"><?php echo $libelle ?> : </label>
            <p class="form-control-static"> <?php echo $quantite ?> </p>
        <?php
        }
        ?>
    </div>
</div>


<!-- Hors Forfait -->
<hr>
<div class="row">
    <div class="panel panel-info">
        <div class="panel-heading">Descriptif des éléments hors forfait</div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>  
                    <th class="montant">Montant</th>
                </tr>
            </thead>  
            <tbody>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id']; ?>           
                <tr>
                    <td><p><?php echo $date ?></p></td>
                    <td><p><?php echo $libelle ?></p></td>
                    <td><p><?php echo $montant ?></p></td>
                </tr>
                <?php
            }
            ?>
            </tbody>  
        </table>
        
        <form method="post" 
              action="index.php?uc=suivrePaiementFrais&action=rembourseFiche" 
              role="form">
            <button class="btn btn-success" type="submit" role="form">Valider</button>
            <input name="visiteurSelection" type="hidden" value="<?php echo $visiteurSelection ?>">
            <input name="leMois" type="hidden" value="<?php echo $leMois ?>">
        </form>
        
    </div>
</div>



