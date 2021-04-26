<?php 
    include("class.pdogsb.inc.php");

    header("Content-Type: application/json");

    $pdo = PdoGsb::getPdoGsb();

     $id = $_POST['id'];
     $mois = $_POST['mois'];

     $FraisHF = $pdo->GetFraisHF($id , $mois);
     $i = 0;
     $ListeFicheHorsfrais = [];
     foreach($FraisHF as $HF ){
         
         $id = $HF["id"];
         $date = $HF["date"];
         $libelle = $HF["libelle"];
         $montant = $HF["montant"];

         $date = $pdo->datesAnglaisVersFrancais($date) ;

         $ListeFicheHorsfrais[$i] = ['id' => $id,'date' => $date ,'libelle' => $libelle,"montant" => $montant ];
         $i++;
 
     }

    $donneesJSONHF = json_encode($ListeFicheHorsfrais);

    echo $donneesJSONHF;
?>