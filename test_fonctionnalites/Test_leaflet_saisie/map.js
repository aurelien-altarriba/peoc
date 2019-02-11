// objet config avec propriétés
var config = {};
config.zoom = 13;
config.lat = 43.212808;
config.lng = 2.350216;

// leaflet map object
var map;

// control map object
var baseMaps = {};
var overlayMaps = {};

// création d'un objet leaflet Popup vide
var popup = L.popup();


/**
* @function createMap
* @param string html containerId
* @return leaflet object map
*/
function createMap(containerId){
  // L.map == Librairie Leaflet fonction map()
  // L.map('container HTML') dans lequel on va charger la carte
  // vue initiale de la carte avec zoom
  map = L.map(containerId).setView([config.lat, config.lng], config.zoom);
  return map;
}


/**
* @function createLayer
* @param string url
* @param json options
* @return leaflet object layer
*/
//function createLayer(url, maxZoom, attribution){
function createLayer(url, options){
  //Fond de carte MapBox
 /*
  L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
  attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
  maxZoom: 20,
  attribution : "&copy; Openstreetmap France | &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>"
  }).addTo(map);
  */

  var layerOptions = {};
  // test pour vérifier que l'option maxZoom a bien été définie et passée en option à la fonction
  if (typeof options.maxZoom !== "undefined"){
    layerOptions.maxZoom = options.maxZoom;
  };

  if (typeof options.attribution !== "undefined"){
    layerOptions.attribution = options.attribution;
  };

  var layer = L.tileLayer(url,layerOptions)
  return layer;
}



/**
* @function createControlLayer
* @param null
* @return leaflet control layer object
*/
function createControlLayer(){
  var ctrl = L.control.layers(baseMaps, overlayMaps);
  return ctrl;
}





