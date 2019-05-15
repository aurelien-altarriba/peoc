$(document).ready(function() {

  // Récupération des paramètres
  var url = new URLSearchParams(location.search);

  // Si on modifie le parcours
  if (url.get('id')) {
    var id = url.get('id');
  }

  // On récupère les tronçons du parcours
  $.post('/fonction/recup_data_troncons_parcours.php',
    {
      id: id
    },
    function(data) {
      displayDataTronconEdition(data);
    }
  )
});
