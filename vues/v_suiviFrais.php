<script src="./include/js/suiviFrais.js"></script>
<div id="contenu">
    <h1>Suivi des fiches de frais</h1>
    <button type="button" onclick="SuivreVisiteur(select(),'Validée et mise en paiement');" id="btnPaiement" class="btn-suivi btn btn-info">Mettre en paiement </button>
    <button type="button" onclick="SuivreVisiteur(select(),'Remboursée');" id="btnRemboursement" class="btn-suivi btn btn-success">Confirmer remboursement </button>
    
    <div class="bloc">
    <h2>Selectionnez une date :</h2>
    <div id ="ZoneInfo" class="alert displayNone" role="alert">
    </div>

    <?php 
    ?>
    <form action="index.php?" methode="POST">
        <select id="select" onchange="filter()" class="form-control" name="filterAlgorithm">
       <?php
            $lesDates = $pdo->getAllDate();
        
            foreach($lesDates as $d){
                echo '<option value="'.$d["mois"].'">'.$d["mois"].'</option>';
            }
            
       ?>

    
    </select>
    <table class="tbl table w-auto" id="table" 
        data-toggle="table" 
        data-url="./include/JSON/suiviFiche.json" 
        data-search ="true" 
        data-pagination="true" 
        data-click-to-select="true">
            <thead>               
                <tr class="columnTable">
                    <th data-field="state" data-checkbox="true" name="selectVisiteur"></th>
                    <th  data-field="visiteur" data-sortable="true">Visiteur</th>
                    <th  data-field="mois" data-sortable="true">Mois(Année/Mois)</th>
                    <th  data-field="montantValide" data-sortable="true">Montant(€)</th>
                    <th data-field="dateModif" data-sortable="true">Date de modification</th>
                    <th  data-field="libelle" data-sortable="true">Etat fiche</th>
                </tr>
            </thead>
        </table>
    </form>
      
    </div>
</div>



