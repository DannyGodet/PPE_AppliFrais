<?php 
include("class.pdogsb.inc.php");
include("fct.inc.php");

$pdo = PdoGsb::getPdoGsb();

if(isset($_POST['visiteur'])) {
    if($_POST['act'] == "Remboursée"){
        for($i = 1 ; $i < count($_POST['visiteur']) ; $i++){

            $visiteur = $_POST["visiteur"][$i]["visiteur"];
            $mois = $_POST["visiteur"][$i]["mois"];
            $visiteur = $pdo->DonneeForBDD($visiteur,$mois);
            $nom = $visiteur["nom"];
            $prenom = $visiteur["prenom"];
            $mois = $visiteur["mois"];
           // on va remettre les valeurs du tableau modifiées à leurs etat d'origine pour manipuler la base de données
          
              $pdo->ChangerEtatFicheFrais($nom,$prenom,$mois,"RB");

        }
        $DataJson = $pdo->remplirTableau();
        file_put_contents("../include/JSON/suiviFiche.json", $DataJson); 
    }
    elseif($_POST['act'] == "Validée et mise en paiement"){

        //pour chaque visiteurs selctionnez
        for($i = 1 ; $i < count($_POST['visiteur']) ; $i++){

            $visiteur = $_POST["visiteur"][$i]["visiteur"];
            $mois = $_POST["visiteur"][$i]["mois"];
            /* On remet les données au format de la base de données */
            $visiteur = $pdo->DonneeForBDD($visiteur,$mois);
            $nom = $visiteur["nom"];
            $prenom = $visiteur["prenom"];
            $mois = $visiteur["mois"];
           // on va remettre les valeurs du tableau modifiées à leurs etat d'origine pour manipuler la base de données

              //On change l'état de la fiche par "VA" -> "Validée et mise en paiement"
            $pdo->ChangerEtatFicheFrais($nom,$prenom,$mois,"VA");

        }
        $DataJson = $pdo->remplirTableau();
        file_put_contents("../include/JSON/suiviFiche.json", $DataJson); 
    }

}
?>
