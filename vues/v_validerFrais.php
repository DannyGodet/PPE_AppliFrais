<div id="contenu" >
    <h1 class="titleValiderFrais">Valider des fiches de frais</h1>
    <div class="selectFiche">
        <div class="SelectVisiteur">
            <label for="visiteur">Visiteur :</label>
        <select name="visiteur" onchange="initialiseAll()" id="visiteur">
            <?php
        $Visiteurs = $pdo->getAllVisiteurs();
                foreach($Visiteurs as $v){
                    echo '<option value="'.$v["id"].'">'.$v["nom"].' '.$v["prenom"].'</option>';
                }
        ?>
        </select>
        </div>
        
        <div class="SelectMois">
        <label for="mois">Mois :</label>
        <select name="mois" onchange="initialiseAll()" id="mois">
            <?php
                $lesMois = $pdo->getAllDate();
                foreach($lesMois as $d){
                    echo '<option value="'.$d["mois"].'">'.$d["mois"].'</option>';
                }
            ?>

        </select>
        </div>
    </div>

    <div class="blocValiderFrais">
    <h1>Eléments forfaitisés</h1>
    <div id ="ZoneInfo1" class="alert alert-info displayNone" role="alert">
        </div>
        <div class="ElemF" id="ElemF">
            <div class="inputElemF">
                <label for="etape">Forfait Etape</label>
                <input value="0" type="text" class="form-control" id="etape">
            </div>
            <div class="inputElemF">
                <label for="km">Frais Kilométrique</label>
                <input value="0" type="text" class="form-control" id="km">
            </div>
            <div class="inputElemF">
                <label for="nuite">Nuitée Hôtel</label>
                <input value="0" type="text" class="form-control" id="nuitee">
            </div>
            <div class="inputElemF">
                <label for="repas">Repas Restaurant</label>
                <input value="0" type="text" class="form-control" id="repas">
            </div>

                
           
            <button type="button" id="btnCorriger" onclick="validerModifFrais()" class="btn-elem-forfait btn btn-success ">Corriger</button>
            <button type="button" id="btnPaiement" onclick="initialiseElemForfaitise()" class="btn-elem-forfait btn btn-info">Réinitialiser</button>
        </div>
        <div class="ElemHF " id="ElemHF">
            <h1>Eléments non forfaitisés</h1>
            <div id ="ZoneInfo2" class="alert alert-info displayNone" role="alert">
        </div>


            <table id="tableValider" class="table tableValider">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Libelle</th>
                        <th scope="col">Montant</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>



        </div>
    </div>
    <div id ="ZoneInfo3" class="alert alert-info displayNone" role="alert">
        </div>
    <div class="zoneValiderFiche">
    
        <div class="jusificatif">
            <label for="justif">Nombre de justificatifs :</label>
           
            <input name="justif" class="form-control" id="justif" type="number" name="num"  >  

        </div>
        <button onclick="ValiderJustif()" class="validerJustif btn btn-success ">Valider</button>
        <button onclick="initialiseJustificatifs()" class="reinitialiserJustif  btn btn-info ">Réinitialiser</button>
    </div>
    <div class="BlocValiderFiche">
        <button onclick="ValiderFicheFrais()" class="btnValiderFiche btn btn-success  ">
            Valider la fiche de frais
        </button>
    </div>



</div>
<script src="./include/js/validerFrais.js" ></script>

<script type="text/javascript">
window.onload = function (){ 
    initialiseAll()
};
</script>