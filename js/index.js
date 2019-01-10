// Pour que la liste déroulante soit à la bonne taille
$(document).ready(function() {
  $("#resParcours .list-group").css("max-height", ($("#colonneGauche").height()-$("#filtres").height()));
});
