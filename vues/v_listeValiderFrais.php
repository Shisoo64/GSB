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
        <form method="post" 
              action="index.php?uc=validerFrais&action=correctFraisForfait" 
              role="form">
            <fieldset>       
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </div>
                    <?php
                }
                ?>
                <input name="visiteurSelection" type="hidden" value="<?php echo $visiteurSelection ?>">
                <input name="leMois" type="hidden" value="<?php echo $leMois ?>">

            </fieldset>
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-warning" type="reset">Réinitialiser</button>
        </form>
    </div>
</div>


<!-- Hors Forfait -->
<hr>
<div class="row">
    <div class="panel panel-info">
        <div class="panel-heading">Descriptif des éléments hors forfait</div>
        
        <form method="post" 
              action="index.php?uc=validerFrais&action=correctFraisHorsForfait" 
              role="form">
            <table class="table table-bordered table-responsive">

                <thead>
                    <tr>
                        <th class="date">Date</th>
                        <th class="libelle">Libellé</th>  
                        <th class="montant">Montant</th>  
                        <th class="action">&nbsp;</th> 
                    </tr>
                </thead>  
                <tbody>
                    <?php
                    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                        $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                        $date = $unFraisHorsForfait['date'];
                        $montant = $unFraisHorsForfait['montant'];
                        $idFrais = $unFraisHorsForfait['id'];
                        ?>           
                        <tr>

                    <td> 
                        <div class="form-group">
                            <input type="text" id="lesFraisDate" 
                                   name="lesFraisDate[<?php echo $idFrais ?>]"
                                   size="6" maxlength="10" 
                                   value="<?php echo $date ?>" 
                                   class="form-control">
                        </div>

                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" id="lesFraisLibelle" 
                                   name="lesFraisLibelle[<?php echo $idFrais ?>]"
                                   size="20" maxlength="50" 
                                   value="<?php echo $libelle ?>" 
                                   class="form-control">
                        </div>
                    </td>
                    <td><div class="form-group">
                            <input type="text" id="lesFraisMontant" 
                                   name="lesFraisMontant[<?php echo $idFrais ?>]"
                                   size="3" maxlength="6" 
                                   value="<?php echo $montant ?>" 
                                   class="form-control">
                        </div></td>
                    <td>
                        <button class="btn btn-success" type="submit"
                                role="form" name="idFraisCorrect" value="<?php echo $idFrais ?>">Corriger</button>
                        <button class="btn btn-danger" type="submit"
                                role="form" name="idFraisRefus" value="<?php echo $idFrais ?>">Refuser</button>
                        <button class="btn btn-warning" type="reset"
                                >Réinitialiser</button></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>  
                <input name="visiteurSelection" type="hidden" value="<?php echo $visiteurSelection ?>">
                <input name="leMois" type="hidden" value="<?php echo $leMois ?>">

            </table>
        </form>
        
        <form method="post" 
              action="index.php?uc=validerFrais&action=valideFiche" 
              role="form">
            <button class="btn btn-success" type="submit" role="form" >Valider</button>
            
            <input name="visiteurSelection" type="hidden" value="<?php echo $visiteurSelection ?>">
            <input name="leMois" type="hidden" value="<?php echo $leMois ?>">
        </form>
        
    </div>
</div>



