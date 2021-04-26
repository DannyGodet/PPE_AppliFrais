<?php
include("vues/v_sommaire.php");
if($_SESSION['statut'] == "Comptable"){
	$action = $_REQUEST['action'];
	$idVisiteur = $_SESSION['idVisiteur'];
	
	$DataJson = $pdo->remplirTableau();
    file_put_contents("./include/JSON/suiviFiche.json", $DataJson); 

	switch($action){
		case 'suivreFicheFrais':{
			
		
			include("vues/v_suiviFrais.php");
			break;
		}
	}
}else{
	ajouterErreur("Vous n'avez pas accès à cette page.");
	include("vues/v_erreurs.php");
}
?>

