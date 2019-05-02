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
		drawMarker: false,
    drawCircle: false,
		drawRectangle: false,
		drawPolygon: false,
		dragMode: false,
		cutPolygon: false,
	});

	// Tableau contenant les objets tronçons de la carte
	tabTroncon = [];

	// A chaque création de tronçon
	map.on('pm:create', (un_troncon) => {

		// On ajoute les coordonnées du tronçon dans le tableau
		tabTroncon.push(un_troncon.layer.getLatLngs());
	});

	// Quand on clique sur le bouton pour enregistrer
	$('#bt_submit_data').on('click', function(e) {

		// Récupération des paramètres
		var url = new URLSearchParams(location.search);

		// Si on modifie le parcours
		if (url.get('id')) {
			var chemin = "/fonction/verif_parcours_modification.php";
		}

		// Si on créé le parcours
		else {
			var chemin = "/fonction/verif_parcours_creation.php";
		}

		// Mise à jour du parcours
		$.post(chemin,
			{
				zs_nom_p: $('#zs_nom_p')[0].value,
				zs_description_p: $('#zs_description_p')[0].value,
				zl_id_niveau_ne: $('#zl_id_niveau_ne')[0].value,
				zl_id_departement_p: $('#zl_id_departement_p')[0].value,
				autonomie_p: $("input[name='autonomie_p']:checked").val(),
				visible_p: $("input[name='visible_p']:checked").val()
			},

			// Quand le PHP a fini d'être exécuté
			function(id_parcours_p){

				// Si il n'y a pas eu d'erreurs à la mise à jour du parcours
				if (Number(id_parcours_p)) {

					// On met à jour les tronçons
					$.post("/fonction/verif_troncon.php",
						{
							listeTroncons: JSON.stringify(tabTroncon)
						},

						// Quand le PHP a fini d'être exécuté
						function(data) {

							alert(data);
							// if (data === "OK") {
							// 	alert('Enregistrement réussi!');
							// }
							//
							// // Sinon on affiche l'erreur
							// else {
							// 	alert(data);
							// }
						}
					);
				}

				// Sinon on affiche l'erreur
				else {
					alert(id_parcours_p);
				}
			}
		);

		// insert des tronçons en ajax
		console.log(tabTroncon);
	});


});
