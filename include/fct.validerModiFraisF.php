<?php 

header("Content-Type: text/plain");

include("class.pdogsb.inc.php");
include("fct.inc.php");

$pdo = PdoGsb::getPdoGsb();
$frais = $_POST["table"];

$id = $frais["id"];
$mois = $frais["mois"];

$valetape = $frais["etape"];
$valkm = $frais["km"];
$valNuitee = $frais["nuitee"];
$valrepas = $frais["repas"];


$table = ["ETP" => $valetape,"KM" => $valkm ,"NUI" => $valNuitee , "REP" => $valrepas];

$onModifie = true;

    if($valetape != null){

        if($valkm != null){

            if($valNuitee != null){
                
                if($valrepas != null){

                    foreach($_POST as $t){
                        $t = intval($t);
                        if($t < 0){
                            $onModifie = false;
                        }
                    }
                    if(estTableauEntiers($table) == false){
                        $onModifie = false;
                    }
                    if($onModifie == true){
                        $M = substr($mois,0,2);
                        $A = substr($mois,3,6);
                     
                        $mois = $A.$M ;

                        //faire la modif 

                       $pdo->majFraisForfait($id,$mois,$table);
                       echo "Eléments forfaitisés modifiés. ";
                    }else{
                        echo "Un montant entré n'est pas valide. ";
                    }
                      

                    
                }else{
                    echo "Le champ repas restaurant n'est pas rempli. ";
                }
           
            }else{
                echo "Le champ nuitée hôtel n'est pas rempli. ";
            }
         
        }else{
            echo "Le champ kilométrique n'est pas rempli. ";
        }
       
    }else{
        echo "Le champ étape n'est pas rempli. ";
    }

?>
