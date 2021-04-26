function initialiseElemForfaitise(){

    const id = $("#visiteur").val()
    const mois = $("#mois").val()
    $.ajax({
        type: "POST",
        url: 'include/fct.initialisationF.php',
        data:{id: id, mois:mois},
        dataType:"json",
        success:function(data){ 
            if(data.length != 0){
                $("#etape").val(data[0]["quantite"])
                $("#km").val(data[1]["quantite"])
                $("#nuitee").val(data[2]["quantite"])
                $("#repas").val(data[3]["quantite"])  
            }else{
                $("#etape").val(0)
                $("#km").val(0)
                $("#nuitee").val(0)
                $("#repas").val(0)  
            }

        }
     });
}

function initialiseElemNonForfaitise(){

    $(".RowsTable").remove()
    const id = $("#visiteur" ).val()
    const mois = $("#mois").val()
    $.ajax({
        type: "POST",
        url: 'include/fct.initialisationHF.php',
        data:{id: id, mois: mois},
        success:function(data){
     
            if(data.length != 0){
                $("#ZoneInfo2").html("Pas de frais hors forfaits pour cette fiche")
                $("#ZoneInfo2").addClass("displayNone")
                data.forEach(element => {
                CreateHFRow(element)
             });
            }else{
                $("#ZoneInfo2").html("Pas de frais hors forfaits pour cette fiche")

                $("#ZoneInfo2").removeClass("displayNone")
             
            }
        }
     });
}

function initialiseJustificatifs(){

    $("#justif").val(0)
    const id = $("#visiteur" ).val()
    const mois = $("#mois").val()
    $.ajax({
        type: "POST",
        url: 'include/fct.initialisationJ.php',
        data:{id: id, mois: mois},
        success:function(data){
            
            $("#justif").val(data["justificatif"])

        }
     });
}

function initialiseAll(){

    initialiseElemForfaitise()
    initialiseElemNonForfaitise()
    initialiseJustificatifs()
    
}

function CreateHFRow(data){

    /* Création d'une ligne dans le tableau */
    let ligne = document.createElement('tr')
    ligne.className = "RowsTable " + data["id"]
    ligne.id = data["id"]
    /* Creation de la colonne date */
    let dateCol = document.createElement('td')
    dateCol.className = "date-Col";
    /* Creation de l'input à l'interieur de la colonne*/
    let dateInput = document.createElement('input')
    dateInput.className = "date-input form-control";
    dateInput.id = "InputDate"
    dateInput.value = data["date"]
    /* Ajout de l'input dans la colonne*/
    dateCol.appendChild(dateInput)
    /* Ajout de la colonne dans la ligne*/
    ligne.appendChild(dateCol);


    let libelleCol = document.createElement('td')
    libelleCol.className = "libelle-Col";
    let libelleInput = document.createElement('input')
    libelleInput.className = "libelle-input form-control";
    libelleInput.id = "InputLibelle"
    libelleInput.value = data["libelle"]
    libelleCol.appendChild(libelleInput); 
    ligne.appendChild(libelleCol); 



    let montantCol = document.createElement('td')
    montantCol.className = "montant-Col";
    
    let montantInput = document.createElement('input')
    montantInput.className = "montant-input form-control";
    montantInput.value = data["montant"]
    montantInput.id = "Inputmontant"

    montantCol.appendChild(montantInput); 
    ligne.appendChild(montantCol); 

    let actionCol = document.createElement('td')
    actionCol.className = "action-Col";


   /* Création des boutons pour la colonne "action" */
    let btnValider = document.createElement('button')
    btnValider.className = "btnValider btn btn-success";
    btnValider.id = data["id"]
    actionCol.appendChild(btnValider); 
    let spanValider = document.createElement('span')
    spanValider.className = "glyphicon glyphicon-ok";
    btnValider.appendChild(spanValider); 
    btnValider.onclick = function(){
        validerModifHFrais(this.id);
    };

    let btnRefuser = document.createElement('button')
    btnRefuser.className = "btnRefuser btn btn-danger ";
    btnRefuser.id = data["id"]
    actionCol.appendChild(btnRefuser); 
    let spanRefuser = document.createElement('span')
    spanRefuser.className = "glyphicon glyphicon-remove";
    btnRefuser.appendChild(spanRefuser); 
    btnRefuser.onclick = function(){
        refuserHFrais(this.id);
    };

    let btnActualiser = document.createElement('button')
    btnActualiser.className = "btnActualiser btn btn-warning ";
    btnActualiser.id = data["id"]
    actionCol.appendChild(btnActualiser); 
    let spanActualiser = document.createElement('span')
    spanActualiser.className = "glyphicon glyphicon-refresh";
    btnActualiser.appendChild(spanActualiser); 
    btnActualiser.onclick = function(){
        initialiseElemNonForfaitise()
    };

    let btnReporter = document.createElement('button')
    btnReporter.className = "btnReporter btn btn-info";
    btnReporter.id = data["id"]
    actionCol.appendChild(btnReporter);  
    let spanReporter = document.createElement('span')
    spanReporter.className = "glyphicon glyphicon-share-alt";
    btnReporter.appendChild(spanReporter); 
    btnReporter.onclick = function(){
        reporterHFrais(this.id);
    };

    ligne.appendChild(actionCol); 


    let table = document.getElementById("tableValider")
    table.appendChild(ligne); 
}


function validerModifHFrais(id){

    let ligne = document.getElementById(id)
    input = ligne.getElementsByTagName("input")
    let table = {};
    table["id"] = ligne.id
    input.forEach(element => {
         table[element.id] = element.value
    });

    $.ajax({
        type: "POST",
        url: 'include/fct.validerModiFraisHF.php',
        data:{table: table},
        dataType : "text",
        success:function(data){
 
            $("#ZoneInfo2").html(data)

            $("#ZoneInfo2").removeClass("displayNone")
          
        }
     });
}

function refuserHFrais(id){
    $.ajax({
        type: "POST",
        url: 'include/fct.refuserModiFraisHF.php',
        data:{id: id},
        dataType : "text",
        success:function(data){

            initialiseElemNonForfaitise()

            $("#ZoneInfo2").html(data)

            $("#ZoneInfo2").removeClass("displayNone")
            
          
        }
     });
}

function reporterHFrais(idFiche){

    let ligne = document.getElementById(idFiche)
    input = ligne.getElementsByTagName("input")
    let table = {};
    
    input.forEach(element => {
         table[element.id] = element.value
    });

    const idVisiteur = $("#visiteur").val()
    const mois = $("#mois").val()
 

    $.ajax({
        type: "POST",
        url: 'include/fct.reporterFraisHF.php',
        data:{idVisiteur: idVisiteur,idFiche: idFiche,mois:mois, table: table},
        success:function(data){
            initialiseElemNonForfaitise()
            
            $("#ZoneInfo2").html(data)
    
            $("#ZoneInfo2").removeClass("displayNone")
            
          
        }
     });
}

function validerModifFrais(){
    const id = $("#visiteur").val()
    const mois = $("#mois").val()

    let ligne = document.getElementById("ElemF")
    let input = ligne.getElementsByTagName("input")

    let table = {};
    table["id"] = id
    table["mois"] = mois
  
    input.forEach(element => {
        table[element.id] = element.value
   });

   $.ajax({
    type: "POST",
    url: 'include/fct.validerModiFraisF.php',
    data:{table: table},
    dataType : "text",
    success:function(data){

        $("#ZoneInfo1").html(data)

        $("#ZoneInfo1").removeClass("displayNone")
      
    }
 });
}

function ValiderFicheFrais(){
    const id = $("#visiteur").val()
    const mois = $("#mois").val()
    
    $.ajax({
        type: "POST",
        url: 'include/fct.validerFicheFrais.php',
        data:{id: id, mois: mois},
        dataType : "text",
        success:function(data){
            initialiseAll()
            $("#ZoneInfo1").html(data)
    
            $("#ZoneInfo1").removeClass("displayNone")
          
        }
     });


}




function ValiderJustif(){
    const id = $("#visiteur").val()
    const mois = $("#mois").val()
    
    $.ajax({
        type: "POST",
        url: 'include/fct.validerJustif.php',
        data:{id: id, mois: mois,nbr:  $("#justif").val()},
        success:function(){
    
            $("#ZoneInfo3").html("Nombre de justificatifs modifié. ")
    
            $("#ZoneInfo3").removeClass("displayNone")
          
        }
     });
}
