function mapLoad() {

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

	// MENU DES CARTES
	var cartes = {
	    'Plan': OpenStreetMap,
	    'Satellite': Satellite,
	    'Relief': OpenTopoMap
	};

	// CRÉATION DE LA MAP
	map = L.map('map', {
	    layers: [OpenTopoMap]
	});

	// Coordonnées à l'initialisation de la map
	map.setView([43.7, 2.5], 8);

	// Pour modifier les crédits en bas à droite de la map
	// map.attributionControl.setPrefix('Données cartographiques &copy; Contributeurs d\'<a href="http://openstreetmap.org">OpenStreetMap</a>, <a  href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>');

	// Pour créer une zone de zoom en haut à droite
	// L.control.zoom({
	//      position:'topright'
	// }).addTo(map);

	// Pour appliquer la cartes et les calques sélectionnés
	L.control.activeLayers(cartes).addTo(map);
}
