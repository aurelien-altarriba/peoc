//global variables
var map;
var layersControl;

var fields_ce = ["id","libelle"];
var fields_t = ["id"];

var geojsonCentres = {
	"type": "FeatureCollection",
	"features": []
};
var geojsonTroncons = {
	"type": "FeatureCollection",
	"features": []
};


$(document).ready(function() {

	displayMap();

	//Retrieves data from BDD
	getDataMap();
});



function displayMap(){
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

	// Pour appliquer la cartes et les calques sélectionnés
	//L.control.activeLayers(cartes).addTo(map);
	layersControl = L.control.layers(cartes,{}).addTo(map);

	//remove existing map layers
	map.eachLayer(function(layer){
		//if not the tile layer
		if (typeof layer._url == "undefined"){
			map.removeLayer(layer);
		}
	});
}


function getDataMap(){

	//Centres équestre
	$.ajax("../fonction/recup_data_map.php",{
		data:{
			table: "centre_equestre",
			//fields: fields_centres
			fields: ["id_centre_ce","nom_ce","geom_ce"]
		}
		,
		success: function(data){
			dataMap(data,'C');
		}
	})

	//Tronçons
	$.ajax("../fonction/recup_data_map.php",{
		data:{
			table: "troncon",
			//fields: fields_troncons
			fields: ["id_troncon_t","geom_t"]
		}
		,
		success: function(data){
			dataMap(data,'T');
		}
	})
}


function dataMap(data,typeData){
	console.log("data : " + data);

	//create geojson container object
	var geojson = {
		"type": "FeatureCollection",
		"features": []
	};

	//split data into features
	var dataArray = data.split(", ;");
	dataArray.pop();


	//build geojson features
	dataArray.forEach(function(d){
		d = d.split(", ");
		var fields;

		//feature object container
		if (typeData == 'C'){
			fields = fields_ce;
		}
		else if (typeData == 'T'){
			fields = fields_t;
		}

		var feature = {
				"type": "Feature",
				"properties": {}, //properties object container
				"geometry": JSON.parse(d[fields.length]) //parse geometry
			};

		for (var i=0; i<fields.length; i++){
			feature.properties[fields[i]] = d[i];
		}

		geojson.features.push(feature);
		//console.log(geojson);
		console.log(geojson.features);


		if (typeData == 'C'){
			geojsonCentres.features.push(feature);
		}
		else if (typeData == 'T'){
			geojsonTroncons.features.push(feature);
		}
	});

	//Display data to map
	displayDataMap(typeData);
};


function displayDataMap(typeData){
	console.log(geojsonCentres.features);

	// Initialisation du style de la représentation graphique de chaque élément géographique
	function styleCentre(feature, latlng) {
		/*
		var markerStyle = {
			fillColor: "#CC9900",
			color: "#FFF",
			fillOpacity: 0.5,
			opacity: 0.8,
			weight: 1,
			radius: 8
		};
		*/

		var redIcon = L.icon({
			iconUrl: '../image/leaf-red.png',
			shadowUrl: '../image/leaf-shadow.png',
			iconSize:     [38, 95], // size of the icon
			shadowSize:   [50, 64], // size of the shadow
			iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
			shadowAnchor: [4, 62],  // the same for the shadow
			popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
			});

		//return L.circleMarker(latlng, markerStyle);
		return L.marker(latlng, {icon: redIcon});
	}


	// Initialisation de la popup sur chaque élément géographique
	function onEachFeatureCentre(feature, layer) {
		var html = "";
		for (prop in feature.properties){
			html += prop+": "+feature.properties[prop]+"<br/>";
		};
		layer.bindPopup(html);
	}

	var styleTroncon = {
		"color": "#ff7800",
		"weight": 5,
		"opacity": 0.65
	}

	function onEachFeatureTroncon(feature, layer) {
		var html = "";
		for (prop in feature.properties){
			html += prop+": "+feature.properties[prop]+"<br/>";
		};
		layer.bindPopup(html);
	}


	// Affichage des données
	if (typeData == 'C'){
		var centreLayer = L.geoJson(geojsonCentres, {
			pointToLayer: styleCentre,
			onEachFeature: onEachFeatureCentre
		});
		map.addLayer(centreLayer);
		//map.fitBounds(pointLayer.getBounds());
		layersControl.addOverlay(centreLayer,"Centres équestres");
	}
	else if (typeData == 'T'){
		var tronconLayer = L.geoJson(geojsonTroncons, {
			style : styleTroncon,
			onEachFeature: onEachFeatureTroncon
		});
		map.addLayer(tronconLayer);
		//map.fitBounds(ligneLayer.getBounds());
		layersControl.addOverlay(tronconLayer,"Parcours");
	}
};
