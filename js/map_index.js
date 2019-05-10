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

	// CENTRES ÉQUESTRES
	centres_equestres = new L.featureGroup();

	// SVG CLUSTER
	function svgCluster(child) {

		var niveau = [];
		var total = 0;

		// Pour chaque parcours
		$.each(child.getAllChildMarkers(), function(index, un_parcours) {

			// Si le tableau du niveau n'existe pas, on l'initialise à 0
			if (!niveau.hasOwnProperty(un_parcours.niveau)) {
				niveau[un_parcours.niveau] = 0;
			}

			// On incrémente de 1
			niveau[un_parcours.niveau]++;
			total++;
		});

		var html = '<div class="camembert">';

		// Pour chaque niveau
		$.each(niveau, function(index, value) {
			if (value !== undefined) {
				html = html + '<div class="pie niveau'+ index +'"></div>';
			}
		});

		html = html + '</div>';

		return html;
	}

	// SVG à laisser
	// PARCOURS
	parcours = L.markerClusterGroup({
		// iconCreateFunction: function(cluster) {
		// 	return L.divIcon({ html: svgCluster(cluster), className: 'pointCamembert' });
		// }
	});

	// MENU DES CARTES
	var cartes = {
    'Plan': OpenStreetMap,
    'Satellite': Satellite,
    'Relief': OpenTopoMap
	};

	// MENU DES DATAS
	var menu_data = {
		'Centres équestres': centres_equestres,
		'Parcours': parcours
	};

	// CRÉATION DE LA MAP
	map = L.map('map');

	// Coordonnées à l'initialisation de la map (Occitanie)
	map.setView([43.7, 2.5], 8);

	// Pour appliquer la cartes et les calques sélectionnés
	//L.control.activeLayers(cartes).addTo(map);
	layersControl = L.control.layers(cartes, menu_data).addTo(map);

	// Calcul de la taille des marqueurs
	map.on('zoomend', function() {
		var currentZoom = (map.getZoom() + 2);

		centres_equestres.eachLayer(function(layer) {
			return layer.setIcon( L.icon({
				iconUrl: '/image/ce.png',											// URL de l'image
				iconSize: [currentZoom, currentZoom],					// Taille de l'image
				popupAnchor: [0, ((currentZoom / 2) * -1)]		// Position d'ouverture de la popup
			}))
		});
	});

	// Charge les données sur la carte
	getDataCE();
	getDataParcoursAll();

	// Chargement des cartes
	map.addLayer(centres_equestres);
	map.addLayer(Satellite);
	map.addLayer(parcours);
});

// layers: [Satellite, centres_equestres, parcours]
