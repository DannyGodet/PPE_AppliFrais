<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=mysql-danny.alwaysdata.net';
      	private static $bdd='dbname=danny_bdd';   		
      	private static $user='danny_user' ;    		
      	private static $mdp='Apxv4yrn*A' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosVisiteur($login, $mdp){
		$req = "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom, Statut as statut from visiteur 
		where visiteur.login='$login' and visiteur.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}

/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
		
	}
/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le visiteur concerné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}

/**
 * Calcul le mois suivant 
 
 * @param $mois sous la forme aaaamm
 * @return le mois suivant sous la form aaamm
*/	
public function moisSuivant($mois)
{
	$A = intval(substr($mois,0,4));
	$M = intval(substr($mois,4,5));
	
	if($M == 12){
		$M = 1;
		$A++;
	}else{
		$M++;

	}

	$M = strval($M);
	if($M < "10") $M = "0".$M;;

	$A = strval($A);
	$mois = $A.$M ;

	
	return $mois;
}
/**
 * Retourne le dernier mois en cours d'un visiteur
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	/**
	 * Transforme une date au format format anglais aaaa-mm-jj vers le format français jj/mm/aaaa 
	 
	* @param $madate au format  aaaa-mm-jj
	* @return la date au format format français jj/mm/aaaa
	*/
	function datesAnglaisVersFrancais($maDate){
		@list($annee,$mois,$jour)=explode('-',$maDate);
		$date="$jour"."/".$mois."/".$annee;
		return $date;
	}
		
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait 
		values('','$idVisiteur','$mois','$libelle','$dateFr','$montant')";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais){

		$req = PdoGsb::$monPdo->prepare("DELETE FROM lignefraishorsforfait WHERE id = :id") ;

		$req->bindParam(':id', $idFrais, PDO::PARAM_INT);

		$req->execute();
	}
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs, 
		fichefrais.montantValide as montantValide, etat.libelle as libEtat from fichefrais inner join etat on fichefrais.idEtat = etat.id 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois';";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/** 
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur, $mois,$etat
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}

/**
 * Retourne toutes les données pour suivre les fiche de frais
 
 * @return un tableau associatif 
*/
	public function RecupFichesValide(){
		/* with the SQL request we recover the last name First Name of "visiteur", 
		all informations of his "fiche de frais" month, amount, date of last modification
		and the name of "Etat fiche" */

		$req =  'select nom, prenom,mois, montantValide ,dateModif, libelle  from  visiteur INNER JOIN fichefrais ON visiteur.id = fichefrais.idVisiteur INNER JOIN etat ON etat.id = fichefrais.idEtat WHERE idEtat ="VA" OR  idEtat ="RB" ';
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}

/**
 * Returns all the data clean to store them in the JSON file
 * 	- concatenate the name and the first name
 *  - formatting of the month
 
 * @return un tableau associatif 
*/
	public function FicheAuPropre($ficheFrais){

		$i = 0 ;

		foreach($ficheFrais as $value){	
			$visiteur = "".$value["nom"]." ".$value["prenom"];
			
			$A = substr($value["mois"],0,4);
			$M = substr($value["mois"],4,5);
			$mois = $M."/".$A;
 
			$montantValide = $value["montantValide"];
			$dateModif = $value["dateModif"];
			$libelle = $value["libelle"];
			$ficheAuPropre[$i] = ['visiteur' => $visiteur, 'mois' => $mois, 'montantValide' => $montantValide, 'dateModif' => $dateModif, 'libelle' =>  $libelle ];
			$i++;
		}
		
			 
		return $ficheAuPropre; 
	}

/**
 * Transforme les elements du tableau pour les manipuler avec la BDD
 * @return un tableau associatif 
*/
	public function DonneeForBDD($visiteur, $mois){

		$visiteur = explode(" ",$visiteur);

		$nom = $visiteur[0];
		$prenom = $visiteur[1];
 
		$M = substr($mois,0,2);
		$A = substr($mois,3,6);
 
		$mois = $A.$M ;

		$infoBDD = ['nom' => $nom, 'prenom' => $prenom, 'mois' => $mois];
			

		return $infoBDD; 
	}	
	
/*
change l'etat de la fiche de frais en fonction de l'etat entré en argument 
*/
public function ChangerEtatFicheFrais($nomVisiteur, $prenomVisiteur,$mois,$etat){

	$req = PdoGsb::$monPdo->prepare("update ficheFrais inner join visiteur on visiteur.id = ficheFrais.idVisiteur
	set idEtat = :etat, dateModif = now() 
	where visiteur.nom = :nomVisiteur and visiteur.prenom = :prenomVisiteur and fichefrais.mois = :mois");

	$req->bindParam(':etat', $etat, PDO::PARAM_STR_CHAR);
	$req->bindParam(':nomVisiteur', $nomVisiteur, PDO::PARAM_STR_CHAR);
	$req->bindParam(':prenomVisiteur', $prenomVisiteur, PDO::PARAM_STR_CHAR);
	$req->bindParam(':mois', $mois, PDO::PARAM_STR_CHAR);

	$req->execute();
}


/**
 * retourne true si la ficheFrais n'est pas deja en "etatVoulu" sinon false 
 * @return un booleen
*/
public function changementValable($nomVisiteur, $prenomVisiteur,$mois,$etatVoulu){

	$result = false;
	echo $nomVisiteur." ".$prenomVisiteur." ".$mois;
	$req = PdoGsb::$monPdo->prepare("select idEtat from  visiteur INNER JOIN fichefrais ON visiteur.id = fichefrais.idVisiteur INNER JOIN etat ON etat.id = fichefrais.idEtat where visiteur.nom = :nomVisiteur and visiteur.prenom = :prenomVisiteur and fichefrais.mois = :mois");

	$req->bindParam(':nomVisiteur', $nomVisiteur, PDO::PARAM_STR_CHAR);
	$req->bindParam(':prenomVisiteur', $prenomVisiteur, PDO::PARAM_STR_CHAR);
	$req->bindParam(':mois', $mois, PDO::PARAM_STR_CHAR);
	
	$req->execute();
	$ligne = $req->fetch();

	if($ligne["idEtat"] != $etatVoulu){
			$result = true ;
	}
		return $result ; 

}

/**
 * retourne les dates dans lesquelles ils existent au moins une fiche de frais
 * @return un tableau associatif 
*/
public function getAllDate(){

	$req =  "select DISTINCT mois from  fichefrais
	
	ORDER BY mois DESC";
	
		$res = PdoGsb::$monPdo->query($req);
		$dates = $res->fetchAll();
		$datesAuPropre= [];
		$i = 0;
		/* Mise au propre des dates pour la combobox*/ 
		foreach($dates as $d ){

			$A = substr($d["mois"],0,4);
			$M = substr($d["mois"],4,5);
			$mois = $M."/".$A;

			$datesAuPropre[$i] = ['mois' => $mois];
			$i++;
	
		}

	return $datesAuPropre;

}


/*
 * retourne les dates dans lesquelles ils existent au moins une fiche de frais avec l'etat 'CL'
 * @return un tableau associatif 
*/
public function getAllDateFicheCL(){

	$req =  "select DISTINCT mois from  fichefrais 
	WHERE  idEtat IN('CL','CR')
	ORDER BY mois DESC";
	
		$res = PdoGsb::$monPdo->query($req);
		$dates = $res->fetchAll();
		$datesAuPropre= [];
		$i = 0;
		/* Mise au propre des dates pour la combobox*/ 
		foreach($dates as $d ){

			$A = substr($d["mois"],0,4);
			$M = substr($d["mois"],4,5);
			$mois = $M."/".$A;

			$datesAuPropre[$i] = ['mois' => $mois];
			$i++;
	
		}

	return $datesAuPropre;

}

/**
 * Remplit le fichier JSON nécessaire au tableau sur la page SuivieFrais

*/
public function remplirTableau(){

	$ficheFrais= array();
	// on recupere les donnes pour la table
	
	$ficheFrais= $this->RecupFichesValide();
	// on met en form les donnees recuperer 
	$ficheFraisPropre = $this->FicheAuPropre($ficheFrais);
	// on envoie ces données dans un fichier json pour le manipuler avec le tableau 
	$DataJson = json_encode($ficheFraisPropre);

	return $DataJson;

}

/**
 * retourne tous les visiteurs
 * @return un tableau associatif,id nomVisiteur , PrenomVisiteur
*/
public function getAllVisiteurs(){
	
	$req =  "select DISTINCT id, nom, prenom from  visiteur ORDER BY nom DESC";
	
		$res = PdoGsb::$monPdo->query($req);
		$visiteurs = $res->fetchAll();
		$visiteursEnTableau= [];
		$i = 0;
		/* Mise au propre des dates pour la combobox*/ 
		foreach($visiteurs as $v ){
			$id = $v["id"];
			$nom = $v["nom"];
			$prenom = $v["prenom"];

			$visiteursEnTableau[$i] = ['id' => $id,'nom' => $nom ,'prenom' => $prenom];
			$i++;
	
		}

	return $visiteursEnTableau;

}

/**
 * retourne tous les frais forfaitisé ayant en état CL ou CR de l'utilisateur et du mois entré en paramètre
 * @param $idVisiteur
 * @param $mois sous la forme mm/aaaa
*/
public function GetFraisF($idVisiteur,$mois){
	
	$M = substr($mois,0,2);
	$A = substr($mois,3,6);
 
	$mois = $A.$M ;

	$req = PdoGsb::$monPdo->prepare("
	select idFraisForfait, quantite 
	from lignefraisforfait 
	INNER JOIN fichefrais ON lignefraisforfait.idVisiteur = fichefrais.idVisiteur 
	AND lignefraisforfait.mois = fichefrais.mois 
	where lignefraisforfait.idVisiteur =  :idVisiteur AND lignefraisforfait.mois = :mois AND idEtat IN('CL','CR')
	");

	$req->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR_CHAR);
	$req->bindParam(':mois', $mois, PDO::PARAM_STR_CHAR);


		$req->execute();
		$visiteurs = $req->fetchAll();

	return $visiteurs;

}

/**
 * retourne tous les frais non forfaitisé ayant en état CL ou CR de l'utilisateur et du mois entré en paramètre
 * @param $idVisiteur
 * @param $mois sous la forme mm/aaaa
*/
public function GetFraisHF($idVisiteur,$mois){
	
	$M = substr($mois,0,2);
	$A = substr($mois,3,6);
 
	$mois = $A.$M ;

	$req = PdoGsb::$monPdo->prepare("
	select id, date, libelle, montant 
	from lignefraishorsforfait 
	INNER JOIN fichefrais ON lignefraishorsforfait.idVisiteur = fichefrais.idVisiteur 
	AND lignefraishorsforfait.mois = fichefrais.mois 
	where lignefraishorsforfait.idVisiteur =  :idVisiteur AND lignefraishorsforfait.mois = :mois AND idEtat IN('CL','CR')
	");

	$req->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR_CHAR);
	$req->bindParam(':mois', $mois, PDO::PARAM_STR_CHAR);
		
	$req->execute();
	$horsFrais = $req->fetchAll();

	return $horsFrais;

}


/**
 * Modifie la ligne de frais Hors forfait passé en paramètre
*/
public function ValiderModifFicheHF($id, $date,$libelle,$montant){


	$req = PdoGsb::$monPdo->prepare("UPDATE lignefraishorsforfait
	SET date = :mois , libelle = :libelle, montant = :montant
	WHERE id = :id") ;


	$req->bindParam(':mois', $date, PDO::PARAM_STR);
	$req->bindParam(':libelle', $libelle, PDO::PARAM_STR);
	$req->bindParam(':montant', $montant, PDO::PARAM_STR);
	$req->bindParam(':id', $id, PDO::PARAM_INT);

	$req->execute();

}


/**
 * Supprime la ligne de frais Hors forfait ayant l'id passé en paramètre
*/
public function RefuserModifFicheHF($id){


	$req = PdoGsb::$monPdo->prepare("DELETE FROM lignefraishorsforfait
	WHERE id = :id") ;

	$req->bindParam(':id', $id, PDO::PARAM_INT);

	$req->execute();
}

/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme mm/aaaa
 * @return le nombre entier de justificatifs 
*/
public function getNbjustificatifs2($idVisiteur, $mois){

	$M = substr($mois,0,2);
	$A = substr($mois,3,6);
 
	$mois = $A.$M ;

	$just = $this->getNbjustificatifs($idVisiteur, $mois);

	return $just;
}

/*
change l'etat de la fiche de frais concerné par les arguments en fonction de l'etat entré en argument 
*/
public function ChangerEtatFicheFraisWithIdVEtMois($idVisiteur ,$mois,$etat){

	$req = PdoGsb::$monPdo->prepare("update fichefrais 
	set idEtat = :etat, dateModif = now() 
	where fichefrais.idVisiteur = :idVisiteur and fichefrais.mois = :mois");
	//update fichefrais set idEtat = 'VA', dateModif = now() where fichefrais.idVisiteur = 'a17' and fichefrais.mois = "202011"

	$req->bindParam(':etat', $etat, PDO::PARAM_STR_CHAR);
	$req->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR_CHAR);
	$req->bindParam(':mois', $mois, PDO::PARAM_STR_CHAR);
	
	$req->execute();
}
/*
Créer la fiche de frais du mois suivant pour l'utilisateur en argument
 * @param $idVisiteur 
*/
public function CreerNouvelleFicheFrais($idVisiteur){

	$mois = $this->moisSuivant($this->dernierMoisSaisi($idVisiteur));

	$req = PdoGsb::$monPdo->prepare("
	INSERT INTO fichefrais 
	VALUES (:idVisiteur, :mois,0,0,now(),'CR')
	");

	$req->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR_CHAR);
	$req->bindParam(':mois', $mois, PDO::PARAM_STR_CHAR);

	$req->execute();
}




}


?>