// Pour supprimer le tronçon de la liste et sur la carte
function supprimer_troncon(id) {

	// On supprime la ligne dans le tableau des tronçons
	$('#ligne_'+ id).remove();

	// On supprime le tronçon de la map et du tableau des tronçons
	map.removeLayer(map._layers[id]);
	delete tabTroncon[id];
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

// On réinitialise les formulaires
function reset_formulaire() {
	// On vide le formulaire
	$("#form_pi")[0].reset();
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

	var MODIF = false;

	// Récupération des paramètres
	var url = new URLSearchParams(location.search);
	if (url.get('id')) {
		MODIF = true;
	}

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

	// Échelle
	L.control.scale(
		{
			imperial: false,
		}
	).addTo(map);

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

		un_troncon.layer.on('pm:edit', function(e) {
			actualiser_var_troncon(e.target._leaflet_id, e.target);
		});

		creer_ligne_troncon(idTroncon, un_troncon);
	});

	// Quand on clique sur le bouton pour enregistrer
	$('#bt_submit_enregistrer').on('click', function(e) {

		// Récupération des paramètres
		var url = new URLSearchParams(location.search);

		// Si on modifie le parcours
		if (url.get('id')) {
			var chemin = "/fonction/verif_parcours_modification.php";
			var action = 'maj';
		}

		// Si on créé le parcours
		else {
			var chemin = "/fonction/verif_parcours_creation.php";
			var action = 'enregistrer';
		}

		// Mise à jour du parcours
		$.post(chemin,
			{
				zl_id_p: url.get('id'),
				zs_nom_p: $('#zs_nom_p')[0].value,
				zs_description_p: $('#zs_description_p')[0].value,
				zl_id_niveau_ne: $('#zl_id_niveau_ne')[0].value,
				zl_id_departement_p: $('#zl_id_departement_p')[0].value,
				autonomie_p: $("input[name='autonomie_p']:checked").val(),
				visible_p: $("input[name='visible_p']:checked").val()
			},

			// Quand le PHP a fini d'être exécuté
			function(id_parcours_p){

				console.log(id_parcours_p);

				// Si il n'y a pas eu d'erreurs à la mise à jour du parcours
				if (Number(id_parcours_p)) {

					// On met à jour les tronçons
					$.post("/fonction/verif_troncon.php",
						{
							listeTroncons: JSON.stringify(tabTroncon),
							id_parcours: id_parcours_p,
							action: action,
						},

						// Quand le PHP a fini d'être exécuté
						function(data) {
							alert('Parcours enregistré!');
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

	// Si on est en mode modification
	if (MODIF) {

		// CONTRÔLE AJOUT D'UN POINT D'INTÉRÊT
	  L.Control.Watermark = L.Control.extend({
	  	onAdd: function(map) {
	  		this.map = map;
	  		_this = this;

	  		// Création du bouton pour entrer en mode ajout
				var btPointInteret = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom bt_custom_map');

		    btPointInteret.style.backgroundColor = 'white';
		    btPointInteret.style.width = '30px';
		    btPointInteret.style.height = '30px';
				btPointInteret.style.cursor = 'pointer';
				btPointInteret.innerHTML = '<img src="../image/pi.png" style="width: 80%;"/>';
	  		btPointInteret.title = 'Ajouter un point d\'intérêt';
	  		L.DomEvent.addListener(btPointInteret, 'click', L.DomEvent.stopPropagation)
	  			.addListener(btPointInteret, 'click', L.DomEvent.preventDefault)
	  			.addListener(btPointInteret, 'click', this.debutModeOutilAjouter.bind(this));

				return btPointInteret;
	  	},

	  	debutModeOutilAjouter: function () {
				$('#form_pv').hide();
				$('#ajouterMarqueurPV').hide();
				$('#form_pi').show();
				$('#ajouterMarqueurPI').show();

				$('#zs_photo_pi').attr('src', '');
	  		this.map._container.style.cursor = 'crosshair';
	  		$('path.leaflet-interactive').css("cssText", "cursor: crosshair !important;");
	  		this.map.addEventListener('click', this.mapClicAjouter.bind(this));

	  		$("#notif").html("<b>MODE CRÉATION :</b> <br>Cliquez sur la carte pour créer un point d'intérêt").show(200);
	  		$(".leaflet-left").hide();
	  	},

	  	mapClicAjouter: function (e) {
	  		this.map.removeEventListener('click');

	  		$("#notif").hide(200);
	  		$(".leaflet-left").show();

	  		// On affiche le bouton et le titre pour l'ajout
	  		$('#ajouterMarqueurPI').show();
	  		$('#titre_form_marqueur').text('Ajouter le point d\'intérêt');
	  		$('#modifierMarqueurPI').hide();

	  		reset_formulaire();

	  		// On fait apparaître la fenêtre de création d'un marqueur au clic sur la carte
	  		$('#formulaireMarqueur').modal();

	  		// On masque la fenêtre de création d'un marqueur quand on clique à côté
	  		$('#formulaireMarqueur').on('hidden.bs.modal', function () {
	  			$('#ajouterMarqueurPI').unbind('click');
			});

			// Quand le bouton d'ajout du marqueur est cliqué
			$('#ajouterMarqueurPI').bind('click', function(event) {

				// Récupération des champs du formulaire
				var zl_nom_pic = $('#zl_nom_pic').val();
				var zs_url_pi = $('#zs_url_pi').val();
				var zs_description_pi = $('#zs_description_pi').val();
				// var zs_photo_up = $('#zs_photo_up').val();
				var Latitude = e.latlng.lat;
				var Longitude = e.latlng.lng;

				var url = new URLSearchParams(location.search);
				// Si on modifie le parcours
				var Parcours = url.get('id');

				// ENREGISTREMENT DU MARQUEUR VIA AJAX
	    		$.ajax({
	            url : '/fonction/verif_point_interet_creation.php',
	            type : 'POST',
	            dataType : 'text',
	            data: {
	            	zl_nom_pic: zl_nom_pic,
	            	zs_url_pi: zs_url_pi,
	            	zs_description_pi: zs_description_pi,
	            	latitude: Latitude,
	            	longitude: Longitude,
								parcours: Parcours,
	            },
	            success : function(data){
								console.log(data);
								reset_formulaire();

								getDataPoint('I');

	              // On masque le formulaire
	              $('#formulaireMarqueur').modal('hide');
	            }
	        });
			});

			this.map._container.style.cursor = 'grab';
	  		$('path.leaflet-interactive').css("cssText", "cursor: grab !important;");
	  	}
	  });

		// On ajoute la div d'ajout de marqueur sur la carte
	  new L.Control.Watermark({ position: 'topleft'}).addTo(map);



		// CONTRÔLE AJOUT D'UN POINT DE VIGILANCE
	  L.Control.Watermark = L.Control.extend({
	  	onAdd: function(map) {
	  		this.map = map;
	  		_this = this;

	  		// Création du bouton pour entrer en mode ajout
				var btPointInteret = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom bt_custom_map');

		    btPointInteret.style.backgroundColor = 'white';
		    btPointInteret.style.width = '30px';
		    btPointInteret.style.height = '30px';
				btPointInteret.style.cursor = 'pointer';
				btPointInteret.innerHTML = '<img src="../image/pv.png" style="width: 80%;"/>';
	  		btPointInteret.title = 'Ajouter un point de vigilance';
	  		L.DomEvent.addListener(btPointInteret, 'click', L.DomEvent.stopPropagation)
	  			.addListener(btPointInteret, 'click', L.DomEvent.preventDefault)
	  			.addListener(btPointInteret, 'click', this.debutModeOutilAjouter.bind(this));

				return btPointInteret;
	  	},

	  	debutModeOutilAjouter: function () {
				$('#form_pv').show();
				$('#ajouterMarqueurPV').show();
				$('#form_pi').hide();
				$('#ajouterMarqueurPI').hide();

				$('#zs_photo_pi').attr('src', '');
	  		this.map._container.style.cursor = 'crosshair';
	  		$('path.leaflet-interactive').css("cssText", "cursor: crosshair !important;");
	  		this.map.addEventListener('click', this.mapClicAjouter.bind(this));

	  		$("#notif").html("<b>MODE CRÉATION :</b> <br>Cliquez sur la carte pour créer un point de vigilance").show(200);
	  		$(".leaflet-left").hide();
	  	},

	  	mapClicAjouter: function (e) {
	  		this.map.removeEventListener('click');

	  		$("#notif").hide(200);
	  		$(".leaflet-left").show();

	  		// On affiche le bouton et le titre pour l'ajout
	  		$('#ajouterMarqueur').show();
	  		$('#titre_form_marqueur').text('Ajouter le point');
	  		$('#modifierMarqueur').hide();

	  		reset_formulaire();

	  		// On fait apparaître la fenêtre de création d'un marqueur au clic sur la carte
	  		$('#formulaireMarqueur').modal();

	  		// On masque la fenêtre de création d'un marqueur quand on clique à côté
	  		$('#formulaireMarqueur').on('hidden.bs.modal', function () {
	  			$('#ajouterMarqueur').unbind('click');
			});

			// Quand le bouton d'ajout du marqueur est cliqué
			$('#ajouterMarqueur').bind('click', function(event) {

				// Récupération des champs du formulaire
				var zl_nom_pic = $('#zl_nom_pic').val();
				var zs_url_pi = $('#zs_url_pi').val();
				var zs_description_pi = $('#zs_description_pi').val();
				// var zs_photo_up = $('#zs_photo_up').val();
				var Latitude = e.latlng.lat;
				var Longitude = e.latlng.lng;

				// ENREGISTREMENT DU MARQUEUR VIA AJAX
	    		$.ajax({
	            url : '/fonction/verif_point_interet.php',
	            type : 'POST',
	            dataType : 'text',
	            data: {
	            	zl_nom_pic: zl_nom_pic,
	            	zs_url_pi: zs_url_pi,
	            	zs_description_pi: zs_description_pi,
	            	latitude: Latitude,
	            	longitude: Longitude,
	            },
	            success : function(data){
								console.log(data);
								reset_formulaire();

								getDataPoint('V');

	              // On masque le formulaire
	              $('#formulaireMarqueur').modal('hide');
	            }
	        });
			});

			this.map._container.style.cursor = 'grab';
	  		$('path.leaflet-interactive').css("cssText", "cursor: grab !important;");
	  	}
	  });

		// On ajoute la div d'ajout de marqueur sur la carte
	  new L.Control.Watermark({ position: 'topleft'}).addTo(map);
	}

	getDataPoint('I');
	getDataPoint('V');
});
