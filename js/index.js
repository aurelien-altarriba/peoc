// Fonction pour détecter le changement de la hauteur de la fenêtre
function onElementHeightChange(elm, callback){
  var lastHeight = elm.clientHeight, newHeight;
  (function run(){
    newHeight = elm.clientHeight;
    if( lastHeight != newHeight )
      callback();
    lastHeight = newHeight;

    if( elm.onElementHeightChangeTimer )
      clearTimeout(elm.onElementHeightChangeTimer);

    elm.onElementHeightChangeTimer = setTimeout(run, 200);
  })();
}

// Pour que la liste déroulante soit à la bonne taille
$(document).ready(function() {
  $("#resParcours .list-group").css("max-height", ( $("#colonneGauche").height() - ($("#filtres").height() + 80) ));
  $("#resParcours .list-group").show();

  onElementHeightChange(document.body, function(){
    $("#resParcours .list-group").css("max-height", ( $("#colonneGauche").height() - ($("#filtres").height() + 80) ));
  });
});
