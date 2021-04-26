<?php 

header("Content-Type: text/plain");

include("class.pdogsb.inc.php");
include("fct.inc.php");

$pdo = PdoGsb::getPdoGsb();

$id = $_POST["id"];
$mois = $_POST["mois"];

$M = substr($mois,0,2);
$A = substr($mois,3,6);
$mois = $A.$M ;

$pdo->ChangerEtatFicheFraisWithIdVEtMois($id,$mois,"VA");

echo "La fiche a bien été validé. ";
?>
