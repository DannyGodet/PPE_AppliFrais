<?php 

header("Content-Type: text/plain");

include("class.pdogsb.inc.php");

$pdo = PdoGsb::getPdoGsb();

$id = $_POST["id"];
$mois = $_POST["mois"];
$nbrJustif = $_POST["nbr"]; 

$M = substr($mois,0,2);
$A = substr($mois,3,6);
$mois = $A.$M ;

$pdo->majNbJustificatifs($id,$mois,$nbrJustif);

?>
