// Pour supprimer le tronçon de la liste et sur la carte
function supprimer_troncon(id) {

	// On supprime la ligne dans le tableau des tronçons
	$('#ligne_'+ id).remove();

	// On supprime le troncçon de la map et du tableau des tronçons
	map.removeLayer(map._layers[id]);
	delete tabTroncon['id_' + id];
}

function actualiser_var_troncon(idTroncon, un_troncon) {
	if (un_troncon.layer) {
		var data = un_troncon.layer.getLatLngs();
	} else {
		var data = un_troncon.getLatLngs();
	}

	tabTroncon[idTroncon] = {
		'data': data,
		'position': $('#zs_num_position_t'+ idTroncon).val(),
		'duree_estimee': $('#zs_duree_estime_t'+ idTroncon).val(),
		'type': $('#zl_id_type_t'+ idTroncon).val(),
		'niveau': $('#zl_id_niveau_nt'+ idTroncon).val(),
	}
}

function creer_ligne_troncon(id_troncon, troncon, param = false) {

	// On ajoute un formulaire pour le tronçon
	$.post('/form/troncon.php',
		{
			id: id_troncon,
			pos: _pos_troncon,
		},
		function(data) {
			$('#contenuTroncon')
				.append(data)
				.change(function() {
					actualiser_var_troncon(id_troncon, troncon);
				});

			if (param) {
				console.log(param);
				$('#zs_num_position_t'+ id_troncon).val(param['num_position_t']);
				$('#zs_duree_estime_t'+ id_troncon).val(param['duree_estime_t']);
				$('#zl_id_type_t'+ id_troncon).val(param['id_type_t']);
				$('#zl_id_niveau_nt'+ id_troncon).val(param['id_niveau_t']);
			}

			actualiser_var_troncon(id_troncon, troncon);
			_pos_troncon++;
		}
	)
}

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
		removalMode: false,
	});

	// Objet contenant les coordonnées des troncons
	tabTroncon = {};

	// Pour incrémenter la position des tronçons
	_pos_troncon = 1;

	// À chaque création de tronçon sur la carte
	map.on('pm:create', (un_troncon) => {
		var idTroncon = un_troncon.layer._leaflet_id;

		creer_ligne_troncon(idTroncon, un_troncon);
	});

	// Quand on clique sur le bouton pour enregistrer
	$('#bt_submit_enregistrer').on('click', function(e) {

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

					alert(id_parcours_p);

					// On met à jour les tronçons
					$.post("/fonction/verif_troncon.php",
						{
							listeTroncons: JSON.stringify(tabTroncon),
							id_parcours: id_parcours_p,
							action: 'enregistrer',
						},

						// Quand le PHP a fini d'être exécuté
						function(data) {

							alert(data);
							console.log(data);

						}
					);
				}

				// Sinon on affiche l'erreur
				else {
					alert(id_parcours_p);
				}
			}
		);
	});
});
