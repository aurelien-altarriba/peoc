$(document).ready(function() {

  // récupération des zones allures sur la carte
  $.post('/fonction/verif_zone_allure.php',
    {
      action: 0,
      id_parcours:
    },

    function(data) {
      displayDataZoneAllure(data,"all","");
    }
  );



}



function capturer_position(){
  //Récupération des variables globales de la carte
  $("#zs_latlng1") = ;
  $("#zs_latlng2") = ;
}



function creer_zone(){

  var pos_deb = $("#zs_latlng1").val();
  var pos_fin = $("#zs_latlng2").val();
  var type = $("#zl_id_type_za").val();

  if (pos_deb ! undefined and pos_deb != null and pos_fin ! undefined and pos_fin != null){

    // créer la zone sur la carte
    $.post('/fonction/verif_zone_allure.php',
      {
        action: 1,
        id_parcours: ,
        id_type: type,
        point1 : pos_deb,
        point2 : pos_fin
      },

      function(data) {
        displayDataZoneAllure(data,"one",type);
      }
    );

   // renseigner le champ id

 }
 // Les points délimitants la zone d'allure doivent être renseignés
 else{

 }

}



function supprimer_zone(id){
  // supprimer la zone de la map



  // supprimer la zone de la base de données
  if(id ! undefined && id != null){
    $.post('/fonction/verif_zone_allure.php',
      {
        action: 2,
        id : id
      },

      function(data) {

      }
    );
  }

  // supprimer la ligne du formulaire


}



// Affichage des zones allure sur la carte
function displayDataZoneAllure(data,mode,tp) {

	// Supprime les zones allure de la carte (variable globale donc au cas où)
	zoneAllure.clearLayers();

	// Récupération des données en JSON
	var liste_za = JSON.parse(data);

	// Pour chaque tronçons
	$.each(liste_za, function(index, une_zoneAllure) {

		// Tableau contenant les coordonnées du tronçons
		var trace_za = [];
    var type_za;

		// On récupère les coordonnées du troncon
		var coords = JSON.parse(une_zoneAllure['st_asgeojson'])['coordinates'];

    if (mode=="all"){
      type_za = une_zoneAllure['id_type_za']
    }
    else{
      type_za = tp;
    }
		// Calculer couleur selon niveau difficulté
		if (type_za== 1) {
			couleur = 'black';
		}
		else if (type_za == 2) {
			couleur = 'black';
		}
		else if (type_za == 3) {
			couleur = 'black';
		}
    else if (type_za == 4) {
      couleur = 'black';
    }
    else{
      couleur = 'gray';
    }

		// Pour chaque coordonnées dans le troncon
		$.each(coords, function(index2, ligne) {
			// On la stocke dans les tableaux
			trace_za.push([ligne[1], ligne[0]]);
		});

		// Création du polyline du troncon sur la carte
		var polyline = L.polyline(trace_za, {color: couleur, opacity: 0.5, weight: 20});

		// Ajout du polyline à la liste des parcours
		zoneAllure.addLayer(polyline);
	});

}
