<?php 
    header("Content-Type: application/json");

    include("class.pdogsb.inc.php");
    

    $pdo = PdoGsb::getPdoGsb();

     $id = $_POST['id'];
     $mois = $_POST['mois'];
     $FraisF = $pdo->GetFraisF($id , $mois);
     

     $donneesJSONF = json_encode($FraisF);
   
     echo $donneesJSONF;

?>