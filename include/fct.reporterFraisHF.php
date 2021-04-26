<?php 

header("Content-Type: text/plain");

include("class.pdogsb.inc.php");
include("fct.inc.php");

$pdo = PdoGsb::getPdoGsb();

$idVisiteur = $_POST["idVisiteur"];
$idFiche = $_POST["idFiche"];
$mois = $_POST["mois"];
$table = $_POST["table"];

$date = $table["InputDate"];
$libelle = $table["InputLibelle"];
$montant = $table["Inputmontant"];



$M = substr($mois,0,2);
$A = substr($mois,3,6);
$mois = $A.$M ;
$moisSuivant = $pdo->moisSuivant($mois); 


if($pdo->dernierMoisSaisi($idVisiteur) == $mois){
    $pdo->supprimerFraisHorsForfait($idFiche);
    $pdo->CreerNouvelleFicheFrais($idVisiteur);   
    $pdo->creeNouveauFraisHorsForfait($idVisiteur,$moisSuivant,$libelle,$date,$montant);   
    $pdo->creeNouvellesLignesFrais($idVisiteur,$moisSuivant);
}else{
    $pdo->supprimerFraisHorsForfait($idFiche);
    $pdo->creeNouveauFraisHorsForfait($idVisiteur,$moisSuivant,$libelle,$date,$montant); 
}

echo "La fiche a bien été reporté ";
?>
