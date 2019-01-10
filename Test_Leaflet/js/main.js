//global variables
var map;
var layersControl;

var fields = ["id","libelle"];
var autocomplete =[];
//$(document).ready(mapLoad();

var geojsonPoint = {
	"type": "FeatureCollection",
	"features": []
};
var geojsonLigne = {
	"type": "FeatureCollection",
	"features": []
};
var geojsonSurface = {
	"type": "FeatureCollection",
	"features": []
};



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
	var baseMaps = {
	    'Plan': OpenStreetMap,
	    'Satellite': Satellite,
	    'Relief': OpenTopoMap
	};

	// Création de la map
	map = L.map('map', {
	    layers: [OpenStreetMap]
	}).setView([43.36, 5.13], 10);

	// Pour modifier les crédits en bas à droite de la map
	// map.attributionControl.setPrefix('Données cartographiques &copy; Contributeurs d\'<a href="http://openstreetmap.org">OpenStreetMap</a>, <a  href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>');

	// Pour créer une zone de zoom en haut à droite
	L.control.zoom({
		position:'topright'
	}).addTo(map);

	// Pour appliquer la cartes et les calques sélectionnés
	//L.control.activeLayers(cartes).addTo(map);
	layersControl = L.control.layers(baseMaps,{}).addTo(map);

	//remove existing map layers
	map.eachLayer(function(layer){
		//if not the tile layer
		if (typeof layer._url == "undefined"){
			map.removeLayer(layer);
		}
	});

	//Retrieves data from BDD
	getData();

	//Ajout d'event sur la carte
	map.on('click',function(e){
		console.log("test1");
		console.log(e);
	});
}


function getData(){
	//Point
	$.ajax("php/getdata.php",{
		data:{
			table: "g_point",
			fields: fields
			//fields: ["id","libelle"]
		}
		,
		success: function(data){
			mapData(data,'P');
		}
	})

	//Ligne
	$.ajax("php/getdata.php",{
		data:{
			table: "g_ligne",
			fields: fields
		}
		,
		success: function(data){
			mapData(data,'L');
		}
	})

	//Surface
	$.ajax("php/getdata.php",{
		data:{
			table: "g_surface",
			fields: fields
		}
		,
		success: function(data){
			mapData(data,'S');
		}
	})
}


function mapData(data,typeData){
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
		
		//feature object container
		var feature = {
			"type": "Feature",
			"properties": {}, //properties object container
			"geometry": JSON.parse(d[fields.length]) //parse geometry
		};
		
		for (var i=0; i<fields.length; i++){
			feature.properties[fields[i]] = d[i];
		}
		
		
		//add feature names to autocomplete list
		if ($.inArray(feature.properties.libelle, autocomplete) == -1){
			autocomplete.push(feature.properties.libelle);
		}
		

		geojson.features.push(feature);
		//console.log(geojson);
		//console.log(geojson.features);	


		if (typeData == 'P'){
			geojsonPoint.features.push(feature);
		}
		else if (typeData == 'L'){
			geojsonLigne.features.push(feature);
		}
		else if(typeData == 'S'){
			geojsonSurface.features.push(feature);
		}
	});

	console.log(autocomplete);
	//activate autocomplete on libelle input
	$("input[name=featname]").autocomplete({
		source: autocomplete
	});
	


/*
	var geojsonFeature = {
	    "type": "Feature",
	    "properties": {
	        "name": "Coors Field",
	        "amenity": "Baseball Stadium",
	        "popupContent": "This is where the Rockies play!"
	    },
	    "geometry": {
	        "type": "Point",
	        "coordinates": [5.3627,43.4205]
	    }
	};

	console.log(geojsonFeature);

	function onEachFeature2(feature,layer) {
		if (feature.properties && feature.properties.popupContent){
			var html = "";
			html += feature.properties.popupContent+"<br/>";
			layer.bindPopup(html);
		}
	}

	L.geoJson(geojsonFeature, {
		onEachFeature: onEachFeature2
	}).addTo(map);

	//L.geoJson(geojsonFeature).addTo(map);
*/


/*
if (typeData == 'P'){
		var mapDataLayer = L.geoJson(geojsonPoint, {
			pointToLayer: function(feature, latlng) {
				var markerStyle = {
					fillColor: "#CC9900",
					color: "#FFF",
					fillOpacity: 0.5,
					opacity: 0.8,
					weight: 1,
					radius: 8
				};
				return L.circleMarker(latlng, markerStyle);
			},
			onEachFeature: function(feature, layer) {
				var html = "";
				for (prop in feature.properties){
					html += prop+": "+feature.properties[prop]+"<br/>";
				};
				layer.bindPopup(html);
			}
		}).addTo(map);
	}
	else if (typeData == 'L'){
		var lineStyle = {
			"color": "#ff7800",
			"weight": 5,
			"opacity": 0.65
		}

		L.geoJson(geojsonLigne, {
			style : lineStyle
		}).addTo(map);	
	}
	else if(typeData == 'S'){
		L.geoJson(geojsonSurface, {
			style : function(feature){
				switch (feature.properties.id){
					case '1': return {color: "#ff0000"};
					case '2': return {color: "#0000ff"};
				}
			}
		}).addTo(map);
	}
*/

	//Display data to map
	displayData(typeData);
};



function displayData(typeData){
	console.log(geojsonPoint.features);

	function stylePoint(feature, latlng) {
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

	function onEachFeaturePoint(feature, layer) {
		var html = "";
		for (prop in feature.properties){
			html += prop+": "+feature.properties[prop]+"<br/>";
		};
		layer.bindPopup(html);
	}

	var lineStyle = {
		"color": "#ff7800",
		"weight": 5,
		"opacity": 0.65
	}

	function onEachFeatureLigne(feature, layer) {
		var html = "";
		for (prop in feature.properties){
			html += prop+": "+feature.properties[prop]+"<br/>";
		};
		layer.bindPopup(html);
	}

	function onEachFeatureStyleSurface(feature){
		switch (feature.properties.id){
			case '1': return {color: "#ff0000"};
			case '2': return {color: "#0000ff"};
		}
	}

	function onEachFeatureSurface(feature, layer){
		var html = "";
		for (prop in feature.properties){
			html += prop+": "+feature.properties[prop]+"<br/>";
		};
		layer.bindPopup(html);
	}


	if (typeData == 'P'){
		var pointLayer = L.geoJson(geojsonPoint, {
			pointToLayer: stylePoint,
			onEachFeature: onEachFeaturePoint
		});
		map.addLayer(pointLayer);
		//map.fitBounds(pointLayer.getBounds());
		layersControl.addOverlay(pointLayer,"Points");
	}
	else if (typeData == 'L'){
		var ligneLayer = L.geoJson(geojsonLigne, {
			style : lineStyle,
			onEachFeature: onEachFeatureLigne
		});
		map.addLayer(ligneLayer);
		//map.fitBounds(ligneLayer.getBounds());
		layersControl.addOverlay(ligneLayer,"Lignes");	
	}
	else if(typeData == 'S'){
		var surfaceLayer = L.geoJson(geojsonSurface, {
			style : onEachFeatureStyleSurface,
			onEachFeature: onEachFeatureSurface
		});
		map.addLayer(surfaceLayer);
		//map.fitBounds(surfaceLayer.getBounds());
		layersControl.addOverlay(surfaceLayer,"Surfaces");	
	}
};



function submitQuery(){
	//remove existing map layers
	map.eachLayer(function(layer){
		//if not the tile layer
		if (typeof layer._url == "undefined"){
			map.removeLayer(layer);
		}
	});

	//get the form data
	var formdata = $("form").serializeArray();
	
	//add to data request object
	var data = {
		table: "g_point",
		fields: fields
	};
	formdata.forEach(function(dataobj){
		data[dataobj.name] = dataobj.value;
	});

	console.log(data);

	//call the php script
	$.ajax("php/getData.php", {
		data: data,
		success: function(data){
			mapData(data,'P');
		}
	})
};
