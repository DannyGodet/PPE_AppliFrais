<?php 

header("Content-Type: text/plain");

include("class.pdogsb.inc.php");
include("fct.inc.php");

$pdo = PdoGsb::getPdoGsb();
$table = $_POST["table"];

    if($table["InputDate"] != null){

        if(estDateValide($table["InputDate"]) ){

            if($table["InputLibelle"] != null){
                
                if($table["Inputmontant"] != null){

                    if($table["Inputmontant"] > 0 ){
            
                       $date = dateFrancaisVersAnglais($table["InputDate"]);
                        //faire la modif 
                       $pdo->ValiderModifFicheHF($table["id"],$date,$table["InputLibelle"],$table["Inputmontant"] );
                       echo "L'élément à bien été modifié. ";

                    }else{
                        echo "Le montant entré n'est pas valide. ";
                    }
                    
                }else{
                    echo "Le champ Montant n'est pas rempli. ";
                }
           
            }else{
                echo "Le champ Libelle n'est pas rempli. ";
            }
         
        }else{
            echo "La date entré n'est pas valide. ";
        }
       
    }else{
        echo "Le champ Date n'est pas rempli. ";
    }

?>
