<?php 

header("Content-Type: text/plain");

include("class.pdogsb.inc.php");

$pdo = PdoGsb::getPdoGsb();

$id = $_POST["id"];
    
//fontion qui va supprimer la fiche
  $pdo->supprimerFraisHorsForfait($id);
  echo "L'élément à bien été supprimé. ";

?>
