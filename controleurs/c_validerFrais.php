<?php
include("vues/v_sommaire.php");
if($_SESSION['statut'] == "Comptable"){
	$action = $_REQUEST['action'];
	$idVisiteur = $_SESSION['idVisiteur'];

	switch($action){
		case 'ValiderFicheFrais':{
			include("vues/v_validerFrais.php");
			break;
		}
	}

}else{
	ajouterErreur("Vous n'avez pas accès à cette page.");
	include("vues/v_erreurs.php");
}
?>