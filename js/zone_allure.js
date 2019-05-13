// A l'ouverture du formulaire chargement des zones allures déjà existantes
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

  // !!!!!! comment associer la ligne du formulaire avec l'id leaflet du tracé sur la carte ???

}


  // Récupération des positions des markers délimitants la zone sur la carte dans le formulaire html
function capturer_position(){
  $("#zs_latlng1") = ;
  $("#zs_latlng2") = ;
}


// Création de la zone d'allure : formulaire, base de données et carte
function creer_zone(){

  var pointDeb = $("#zs_latlng1").val();
  var pointFin = $("#zs_latlng2").val();
  var type = $("#zl_id_type_za").val();

  if (pointDeb ! undefined && pointDeb != null && pointFin ! undefined && pointFin != null){

    // créer la zone sur la carte
    $.post('/fonction/verif_zone_allure.php',
      {
        action: 1,
        id_parcours: ,
        id_type: type,
        point1 : pointDeb,
        point2 : pointFin
      },

      function(data) {
        displayDataZoneAllure(data,"one",type);
      }
    );

   // renseigner le champ id

  }
   // Message d'erreur Les points délimitants la zone d'allure doivent être renseignés
   else{

   }
}


// Suppression de la zone d'allure : bdd, carte et formulaire
function supprimer_zone(id){

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

  // supprimer la zone de la map


  // supprimer la ligne du formulaire


}
