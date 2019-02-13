$(document).ready(function() {

	// CARTES
	var OpenStreetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; Contributeurs d\'<a href="http://openstreetmap.org/copyright">OSM</a> - <a href="http://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>'
	});

	var Satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
		maxZoom: 19
	});

	var OpenTopoMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; Contributeurs d\'<a href="https://openstreetmap.org/copyright">OSM</a> & © <a href="http://opentopomap.org">OpenTopoMap</a> - <a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>'
	});

	// TRONCONS
	troncons = new L.LayerGroup();

	// POINTS D'INTERÊTS
	points_interet = new L.featureGroup();

  // POINTS DE VIGILANCE
	points_vigilance = new L.featureGroup();

	// MENU DES CARTES
	var cartes = {
    'Plan': OpenStreetMap,
    'Satellite': Satellite,
    'Relief': OpenTopoMap
	};

	// MENU DES DATAS
	var menu_data = {
		'Parcours': troncons,
    'Points d\'interêt': points_interet,
		'Points de vigilance': points_vigilance
	};

	// CRÉATION DE LA MAP
	map = L.map('map', {
    layers: [Satellite, troncons, points_interet, points_vigilance]
	});

	// Coordonnées à l'initialisation de la map (Occitanie)
	map.setView([43.7, 2.5], 8);

	// Pour appliquer la cartes et les calques sélectionnés
	layersControl = L.control.layers(cartes, menu_data).addTo(map);

	// Ajout du pluggin de dessin "pm"
	map.pm.addControls({
    position: 'topleft',
    drawCircle: false,
	});

	// map.on('pm:edit', function(e) {
  // var coords = e.layer.getLatLngs();
  // console.log(coords);
	// });

	map.on('pm:create',(e) {
  	e.layer.on('pm:edit', ({ layer }) => {
    // layer has been edited
		var coords = e.layer.getLatLngs();
    console.log(coords);
  	})
	});




});
