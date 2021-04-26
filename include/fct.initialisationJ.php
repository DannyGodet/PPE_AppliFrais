<?php 
    include("class.pdogsb.inc.php");

    header("Content-Type: application/json");

    $pdo = PdoGsb::getPdoGsb();

     $id = $_POST['id'];
     $mois = $_POST['mois'];

     $NbrJustifi = $pdo->getNbjustificatifs2($id , $mois);

     $table = ['justificatif' => $NbrJustifi];

    $donneesJSONJ = json_encode($table);

    echo $donneesJSONJ;

?>