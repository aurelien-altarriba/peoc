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

	// Charge les données sur la carte
	getDataTroncon();
	getDataPoint('I');
	getDataPoint('V');








	// A REVOIR TRI ET ORDRE : dessin des tronçons
	// tableau des coordonnées des markers
	var tronconHtml = [];
	var tronconMap = [];
	// position temporaire d'un marker dans troncon (utilisé quand drag and drop)
	var posMarker;
	// groupe de marker
	var layerGrp = L.layerGroup();
	layerGrp.addTo(map);
	// tracé liant les markers (tronçon)
	var polyline;


	// Création des markers
	function onMapClick(e) {
		var lat = e.latlng.lat;
		var lng = e.latlng.lng;

		var pos = tronconMap.length;
		var marker = L.marker([lat,lng],{"title":"point "+pos,"draggable":true}).on('dragstart', onPointDragS).on('dragend', onPointDragE).on('dragstart', onPointDragS);
		layerGrp.addLayer(marker);
		idMarker = layerGrp.getLayerId(marker);

		tronconMap.push([lat,lng]);
		tronconHtml.push([idMarker,lat,lng]);
		if (tronconMap.length == 2){
			polyline = L.polyline(tronconMap, {color: 'red'}).addTo(map);
		}
		else if (tronconMap.length > 2){
			map.removeLayer(polyline);
			polyline = L.polyline(tronconMap, {color: 'red'}).addTo(map);
		}

		$("ul#idLatLng").append("<li id=\"point_"+pos+"\" data-idMarker="+idMarker+" data-lat="+lat+" data-lng="+lng+" data-pos="+pos+">Point"+" "+(pos+1)+" : "+lat+", "+lng+"</li><button class=\"moins\" type=\"button\" data-idMarker="+idMarker+" value="+pos+">-</button>");
	}

	// Modification d'un marker début
	function onPointDragS(e) {
		var lat = e.target._latlng.lat;
		var lng = e.target._latlng.lng;
		for (var i = 0; i < tronconHtml.length; i++) {
			if (tronconHtml[i][1]==lat && tronconHtml[i][2]==lng){
				posMarker = i;
			}
		}
	}

	// Modification d'un marker fin
	function onPointDragE(e) {
		var lat = e.target._latlng.lat;
		var lng = e.target._latlng.lng;

		tronconHtml[posMarker][1]=lat;
		tronconHtml[posMarker][2]=lng;
		tronconMap[posMarker][0]=lat;
		tronconMap[posMarker][1]=lng;

		if (tronconMap.length >= 2){
			map.removeLayer(polyline);
			polyline = L.polyline(tronconMap, {color: 'red'}).addTo(map);
		}

		var pointHtml = document.getElementById("point_"+posMarker);
		pointHtml.setAttribute("data-lat",lat);
		pointHtml.setAttribute("data-lng",lng);
		pointHtml.innerHTML = "Point "+(posMarker+1)+" : "+lat+", "+lng;
	}

	// Suppression d'un marker
	 $(document).on("click","button.moins",function(){
					var pos = $(this).attr('value');
					var idMarker = $(this).attr('data-idMarker');

					tronconHtml.splice(pos,1);
					tronconMap.splice(pos,1);
					layerGrp.removeLayer(idMarker);

					$("ul#idLatLng").empty();
					for (var i = 0; i < tronconHtml.length; i++) {
						var idMarker = tronconHtml[i][0];
						var lat = tronconHtml[i][1];
						var lng = tronconHtml[i][2];
						$("ul#idLatLng").append("<li id=\"point_"+i+"\" data-idMarker="+idMarker+" data-lat="+lat+" data-lng="+lng+" data-pos="+i+">Point"+" "+(i+1)+" : "+lat+", "+lng+"</li><button class=\"moins\" type=\"button\" data-idMarker="+idMarker+" value="+i+">-</button>");
					}

					map.removeLayer(polyline);
					if (tronconMap.length >= 2){
						polyline = L.polyline(tronconMap, {color: 'red'}).addTo(map);
					}
		});



	 $(document).on("click","button#save",function(){
		 if (tronconMap.length>1){
			 var lineString =  "ST_GeomFromText( 'LINESTRING(";
			 for (var i = 0; i < tronconMap.length; i++) {
				 if (i > 0){
					 lineString += ",";
				 }
				 lineString +=  tronconMap[i][1]+" "+tronconMap[i][0];
			 }
			 lineString += ")', 3857))";
			 console.log(lineString);

			 $.ajax("./php/save_data_troncon.php",{
							data:{
								geom : lineString

							}
							,
							success: function(data){
							}
				});
			}
		});


	 $(document).on("click","button#new",function(){
		 $("ul#idLatLng").empty();
		 tronconHtml = [];
		 tronconMap = [];
		 map.removeLayer(polyline);
		 layerGrp.clearLayers();
	 });

	 //création point
	 map.on('click', onMapClick);
});
