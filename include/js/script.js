var index = 0;
// initialisation du catalogue
var visiteurs = [];
/*
function getXMLHttpRequest() {
  var xhr = null;
  if (window.XMLHttpRequest || window.ActiveXObject) {
      if (window.ActiveXObject) {
          try {
              xhr = new ActiveXObject("Msxml2.XMLHTTP");
          } catch(e) {
              xhr = new ActiveXObject("Microsoft.XMLHTTP");
          }
      } else {
          xhr = new XMLHttpRequest(); 
      }
  } else {
      alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
      return null;
  }
  return xhr;
}

function executerRequete() {
  // on vérifie si le catalogue a déjà été chargé pour n'exécuter la requête AJAX
  // qu'une seule fois
  if (visiteurs.length === 0) {
      // on récupère un objet XMLHttpRequest
      var xhr = getXMLHttpRequest();
      // on réagit à l'événement onreadystatechange
      xhr.onreadystatechange = function() {
          // test du statut de retour de la requête AJAX
          if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
              // on désérialise le catalogue et on le sauvegarde dans une variable
              visiteurs = JSON.parse(xhr.responseText);
           
          }
      }
      // la requête AJAX : lecture de data.json
      xhr.open("GET","http://localhost/PPE/include/JSON/suiviFiche.json", true);
      xhr.send();
  } 
}
*/
//executerRequete();



function operateFormatter(value, row, index) {
  return [
    '<a class="like" href="javascript:void(0)" title="Like">',
    '<i class="fa fa-heart"></i>',
    '</a>  ',
    '<a class="remove" href="javascript:void(0)" title="Remove">',
    '<i class="fa fa-trash"></i>',
    '</a>'
  ].join('')
}

window.operateEvents = {
  'click .like': function (e, value, row, index) {
    alert('You click like action, row: ' + JSON.stringify(row))
  },
  'click .remove': function (e, value, row, index) {
    $table.bootstrapTable('remove', {
      field: 'id',
      values: [row.id]
    })
  }
}



//var Data = JSON.getJSON('./JSON/suiviFiche.json');
/*
var request = new XMLHttpRequest();
request.open("GET","../JSON/suiviFiche.json", true);
request.responseType = 'json';
request.send(null);

let jsonfile = JSON.parse()
/*
request.onload = function() {
  var Visiteurs = request.response;
  Visiteur(Visiteurs);
  showHeroes(Visiteurs);
}
function Visiteur(jsonObj) {
  for (let i = 0; i < jsonObj.length; index++) {
    const element = array[i];
    
  }
}

let JSON = import("./JSON/suiviFiche.json");

//let jsonfile = JSON.parse(JSON)
alert(Object.keys(JSON))
//console.log(Object.values(JSON)[0].mois)

$('#table').bootstrapTable({
  pagination: true,
  search: true,
  columns: [{
    field: 'Visiteurs',
    title: 'Visiteurs'
  }, {
    field: 'Mois',
    title: 'Mois(aaaa-mm)'
  }, {
    field: 'Montant',
    title: 'Montant'
  },
  {
    field: 'modification',
    title: 'Date de modicification'
  },
  {
    field: 'Etat',
    title: 'Etat fiche'
  },
  {
    field: 'Action',
    title: 'Action'
  }
],

})

*/