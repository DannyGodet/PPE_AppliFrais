function select(){
    let i = 1
    let visiteursSelect = []
    let r = document.getElementsByClassName("Selected");
    r.forEach(element => {
        
        let nom = element.children[1].firstChild.nodeValue
        let date = element.children[2].firstChild.nodeValue
        let etat = element.children[5].firstChild.nodeValue

        visiteursSelect[i] = {'visiteur' : nom, 'mois' : date, 'etat' : etat }
			
        i++
	});

    return visiteursSelect

}


 function SuivreVisiteur(ficheSelect, action){
        //avant d'envoyer les données on verifie l'etat de la page n'est pas deja celle de l'"action" 
        let possible = true

        ficheSelect.forEach(element => {

          if(element['etat'] == action){
              possible = false;
          }

        });

        if(ficheSelect.length === 0 ){

          $("#ZoneInfo").html("Veuillez selectionnez une fiche")
          if($("#ZoneInfo").hasClass("alert-success")){
  
            $("#ZoneInfo").removeClass("alert-success")
         }
         $("#ZoneInfo").addClass("alert-danger")
         $("#ZoneInfo").removeClass("displayNone")
        }else{
          if(possible == true ){

            $.ajax({
              type: "POST",
              url: 'include/fct.mettreAJourEtatFiche.php',
              data:{visiteur: ficheSelect ,act: action},
              success:function(){
                 var $table = $('#table')
                 $table.bootstrapTable('refresh')
                 $("#ZoneInfo").html(action +" Validé  ")
  
                 if($("#ZoneInfo").hasClass("alert-danger")){
  
                    $("#ZoneInfo").removeClass("alert-danger")
  
                 }
                 $("#ZoneInfo").addClass("alert-success")
  
                 $("#ZoneInfo").removeClass("displayNone")
              }
           });
  
          }else{
            /* Afficher l'erreur*/
            $("#ZoneInfo").html("Une fiche selectionnée est déjà "+ action)
  
            if($("#ZoneInfo").hasClass("alert-success")){
  
              $("#ZoneInfo").removeClass("alert-success")
  
           }
           $("#ZoneInfo").addClass("alert-danger")
  
           $("#ZoneInfo").removeClass("displayNone")
  
          }
        }
        
 }

 
/*fonction permettant de filtrer les fiche par mois */
function filter(){

  $table = $("#table");

  var filterAlgorithm = $('[name="filterAlgorithm"]').val()

    $table.bootstrapTable('refreshOptions', {
      filterOptions: {
        filterAlgorithm: "and"
      }
    })

    $table.bootstrapTable('filterBy', {
      mois: $('[name="filterAlgorithm"]').val()
    })
}