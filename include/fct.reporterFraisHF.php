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
$date = dateFrancaisVersAnglais($date); 

/* SANS procedure stockee
if($pdo->dernierMoisSaisi($idVisiteur) == $mois){
    $pdo->supprimerFraisHorsForfait($idFiche);
    $pdo->CreerNouvelleFicheFrais($idVisiteur,$moisSuivant);  
    $pdo->creeNouveauFraisHorsForfait($idVisiteur,$moisSuivant,$libelle,$date,$montant);   
    $pdo->creeNouvellesLignesFrais($idVisiteur,$moisSuivant);
}else{
    $pdo->supprimerFraisHorsForfait($idFiche);
    $pdo->creeNouveauFraisHorsForfait($idVisiteur,$moisSuivant,$libelle,$date,$montant); 
}*/



// AVEC procedure stockee

$newLignes = $pdo->dernierMoisSaisi($idVisiteur) == $mois ? 1 : 0;

$pdo->query('SET @result = ""');
$req = $pdo->prepare("CALL ReporterFraisHF(?, ?, ?, ?, ?, ?, ?, @result);");

//echo $newLignes."+".$idFiche."+".$idVisiteur."+".$libelle."+".$date."+".$montant."+".$moisSuivant;
/*$req->bindParam(1,$newLignes, PDO::PARAM_BOOL);
$req->bindParam(2,$idFiche, PDO::PARAM_STR_CHAR);
$req->bindParam(3,$idVisiteur, PDO::PARAM_STR_CHAR);
$req->bindParam(4,$libelle, PDO::PARAM_STR);
$req->bindParam(5,$date);
$req->bindParam(6,$montant);
$req->bindParam(7,$moisSuivant, PDO::PARAM_STR_CHAR);*/

$req->bindParam(1,$newLignes ,PDO::PARAM_STR);
$req->bindParam(2,$idFiche,PDO::PARAM_STR);
$req->bindParam(3,$idVisiteur,PDO::PARAM_STR);
$req->bindParam(4,$libelle,PDO::PARAM_STR);
$req->bindParam(5,$date,PDO::PARAM_STR);
$req->bindParam(6,$montant,PDO::PARAM_STR);
$req->bindParam(7,$moisSuivant,PDO::PARAM_STR);

$req->execute();
$req->closeCursor();

if($newLignes){
    $pdo->creeNouvellesLignesFrais($idVisiteur,$moisSuivant);
}

echo "La fiche a bien été reporté ";
?>
