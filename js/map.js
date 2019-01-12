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
	L.control.activeLayers(cartes).addTo(map);


	//Retrieves data from BDD
	getData();
});


function getData(){
	
	//Centres équestre
	$.ajax("./fonction/recup_data_map.php",{
		data:{
			table: "centre_equestre",
			//fields: fields_centres
			fields: ["id_centre_ce","nom_ce","geom_ce"]
		}
		,
		success: function(data){
			mapData(data,'C');
		}
	})

	//Tronçons
	$.ajax("./fonction/recup_data_map.php",{
		data:{
			table: "troncon",
			//fields: fields_troncons
			fields: ["id_troncon_t","geom_t"]
		}
		,
		success: function(data){
			mapData(data,'T');
		}
	})
}


function mapData(data,typeData){
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
	displayData(typeData);
};



function displayData(typeData){
	console.log(geojsonCentres.features);

	function styleCentre(feature, latlng) {
		var markerStyle = {
			fillColor: "#CC9900",
			color: "#FFF",
			fillOpacity: 0.5,
			opacity: 0.8,
			weight: 1,
			radius: 8
		};
		return L.circleMarker(latlng, markerStyle);
	}

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



	if (typeData == 'C'){
		var pointLayer = L.geoJson(geojsonCentres, {
			pointToLayer: styleCentre,
			onEachFeature: onEachFeatureCentre
		});
		map.addLayer(pointLayer);
		//map.fitBounds(pointLayer.getBounds());
		//layersControl.addOverlay(pointLayer,"Points");
	}
	else if (typeData == 'T'){
		var ligneLayer = L.geoJson(geojsonTroncons, {
			style : styleTroncon,
			onEachFeature: onEachFeatureTroncon
		});
		map.addLayer(ligneLayer);
		//map.fitBounds(ligneLayer.getBounds());
		//layersControl.addOverlay(ligneLayer,"Lignes");	
	}
};
