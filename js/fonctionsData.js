// Pour récupérer les données des centres équestres
function getDataCE(){

	// Supprime les layers dans la liste des centres équestres
	centres_equestres.clearLayers();

	// Récupère les layers des centres équestres en POST
	$.post('/fonction/recup_data_centres_equestres.php',
		function(data) {
			var listeCE = JSON.parse(data);

			// Pour chaque centre équestre
			$.each(listeCE, function(index, CE) {

				// On récupère les coordonnées du marqueur
				var coord = JSON.parse(CE['st_asgeojson'])['coordinates'];

				// On créé le marqueur
				var marqueur_CE = new L.marker([coord[1], coord[0]], {
						icon: L.icon({
							iconUrl: 'image/ce.png',				// URL de l'image
							iconSize: [10, 10],							// Taille de l'image
							popupAnchor: [0, -5]						// Position d'ouverture de la popup
						})
					}
				);

				// On créé le contenu en HTML
				var popup_contenu = '<div class="popup_CE">';
					if(CE['logo_ce']!=null){
						popup_contenu += '<img src="image/logo/'+ CE['logo_ce'] +'"></img>';
					}
					else{
						popup_contenu += '<img src="image/ce_default.png"></img>';
					}
					popup_contenu += '<h2>'+ CE['nom_ce'] +'</h2>'+
					'<hr>'+
					'<div><b>Adresse :</b> '+ CE['adresse_ce'] +' '+ CE['cp_ce'] +' '+ CE['ville_ce'] +' ('+ CE['id_departement_ce'] +')</div>'+
					'<div><b>Tel :</b> '+ CE['tel_ce'] +'</div>'+
					'<div><b>Mail :</b> <a href="mailto:'+ CE['mail_ce'] +'">'+ CE['mail_ce'] +'</a></div>'+
					'<div><b>Site web :</b> <a href="'+ CE['url_ce'] +'" target="_blank">'+ CE['url_ce'] +'</a></div>'+
					'<div><b>Nombre de chevaux :</b> '+ CE['nb_cheval_ce'] +'</div>'+
				'</div>';

        // Ajoute le contenu HTML dans une popup au marqueur
				marqueur_CE.bindPopup(popup_contenu);

        // Ajoute le marqueur à la liste des CE
				centres_equestres.addLayer(marqueur_CE);
			});
		}
	);
}


// Affiche tout les parcours
function getDataParcoursAll() {
	$.post('/fonction/recup_data_parcours_all.php',
		function(data) {
			displayDataParcours(data);
		}
	);
}


// Affiche les parcours selon les filtres sélectionnés
function getDataParcoursFiltre() {

	// Récupération des valeurs du filtre
	var nom_p = document.getElementById('nom_parcours').value;
	var centre_p = document.getElementById('centre').value;
	var dep_p = document.getElementById('departement').value;

	// Initialisation
	var niveau_p = '';
	var cpt = 0;

	// Pour chaque checkbox cochée
	$('#niveau input[type=checkbox]:checked').each(function() {
		if (cpt == 0) {
			niveau_p = $(this).val();
		}
		else{
			niveau_p = niveau_p + " ," + $(this).val();
		}
		cpt++;
	});

	// Récupération des parcours avec le filtre
	$.post('/fonction/recup_data_filtre.php',

		// Valeur des filtres de recherche
		{
			nom: nom_p,
			niveau : niveau_p,
			centre : centre_p,
			departement: dep_p
		},

		function(data) {
			displayDataParcours(data);
		}
	);
}


// Récupération des tronçons d'un parcours
function getDataTroncon(edition = false) {

	// Récupération des paramètres
	var url = new URLSearchParams(location.search);

	// Récupération des tronçons
	$.post('/fonction/recup_data_troncons_parcours.php',

		// Récupération de l'ID du parcours
		{
			id: url.get('id')
		},

		function(data) {
			if (edition != false) {
				displayDataTronconEdition(data);

			} else {
				displayDataTroncon(data);
			}
		}
	);
}

// Récupération des points d'un parcours
// type: "I" pour les points d'intérêt
// 			 "V" pour les points de vigilance
function getDataPoint(type) {

	// Récupération des paramètres
	var url = new URLSearchParams(location.search);

	// Récupération des tronçons
	$.post('/fonction/recup_data_points_parcours.php',

		// Récupération de l'ID du parcours
		{
			id: url.get('id'),
			type: type
		},

		function(data) {
			displayDataPoint(data, type);
		}
	);
}


// Fonction d'affichage des parcours
function displayDataParcours(data) {

	// Classe de cluster des polyline
	L.polylineCluster = L.Polyline.extend({
		_originalInitialize: L.Polyline.prototype.initialize,

		initialize: function (bounds, options) {
			this._originalInitialize(bounds, options);
			this._latlng = this.getBounds().getCenter();
		},

		getLatLng: function () {
			return this._latlng;
		},

		// Ne pas supprimer
		setLatLng: function () {}
	});

	// Supprime les layers dans la liste des parcours
	parcours.clearLayers();

	// Vide la liste des parcours
	$("#resParcours .list-group").html("");

	// Récupération des données en JSON
	var liste_parcours = JSON.parse(data);

	// Pour chaque parcours
	$.each(liste_parcours, function(index, un_parcours) {

		// On ajoute le parcours à la liste
		$("#resParcours .list-group").append(
		'<a href="/page/parcours.php?id='+ un_parcours['id_parcours_p'] +'">'+
			'<li class="list-group-item niveau'+ un_parcours['id_niveau_p'] +'" id="parcours'+ un_parcours['id_parcours_p'] +'">'+
				un_parcours['nom_p'] +
				'<span class="badge badge-primary badge-pill">'+ un_parcours['comment'] +' 💬</span>'+
			'</li>'+
		'</a>');

		// Tableau contenant les coordonnées des tronçons
		var trace_parcours = [];

		// Pour chaque troncon dans le parcours
		$.each(un_parcours['troncons'], function(index2, troncon) {

			// On récupère les coordonnées du troncon
			var coords = JSON.parse(troncon['st_asgeojson'])['coordinates'];

			// Pour chaque coordonnées dans le troncon
			$.each(coords, function(index3, ligne) {

				// On la stocke dans le tableau
				trace_parcours.push([ligne[1], ligne[0]]);
			});
		});

		// Création du polyline du parcours
		var polyline = new L.polylineCluster(trace_parcours, {color:'red'});

		polyline.niveau = un_parcours['id_niveau_p'];
		polyline.on('click', function(event) {
			document.location.href = "/page/parcours.php?id=" + un_parcours['id_parcours_p'];
		});

		// Ajout du polyline à la liste des parcours
		parcours.addLayer(polyline);
	});
}


// Affichage des tronçons sur la carte
function displayDataTroncon(data) {

	// Supprime les tronçons de la carte (variable globale donc au cas où)
	troncons.clearLayers();

	// Récupération des données en JSON + tableau des coordonnées (pour zoom automatique)
	var liste_troncons = JSON.parse(data);
	var tab_coord = [];

	// Déclaration des variables qui vont contenir le premier et dernier point d'un tronçon
	var debut = null;
	var fin = null;

	// Pour chaque tronçons
	$.each(liste_troncons, function(index, un_troncon) {

		// Tableau contenant les coordonnées du tronçons
		var trace_troncon = [];

		// On récupère les coordonnées du troncon
		var coords = JSON.parse(un_troncon['st_asgeojson'])['coordinates'];

		// Calculer couleur selon niveau difficulté
		if (un_troncon['id_niveau_t'] == 1) {
			couleur = 'yellow';
		}
		else if (un_troncon['id_niveau_t'] == 2) {
			couleur = 'orange';
		}
		else if (un_troncon['id_niveau_t'] == 3) {
			couleur = 'red';
		}
		else {
			couleur = 'grey';
		}

		// Pour chaque coordonnées dans le troncon
		$.each(coords, function(index2, ligne) {

			if(debut === null) {
				debut = [ligne[1], ligne[0]];
			}

			fin = [ligne[1], ligne[0]];

			// On la stocke dans les tableaux
			trace_troncon.push([ligne[1], ligne[0]]);
			tab_coord.push(trace_troncon);
		});

		// Création du polyline du troncon sur la carte
		var polyline = L.polyline(trace_troncon, {color: couleur});

		// Ajout du polyline à la liste des parcours
		troncons.addLayer(polyline);
	});

	// On créé les marqueurs de debut et de fin de parcours
	var marqueur_depart = new L.marker(debut, {
			icon: L.icon({
				iconUrl: '/image/depart_parcours_4.png',			// URL de l'image
				iconSize: [40, 40],														// Taille de l'image
				popupAnchor: [0, 0]														// Position d'ouverture de la popup
			})
		}
	);

	var marqueur_fin = new L.marker(fin, {
			icon: L.icon({
				iconUrl: '/image/logo_arrivee_petit.png',			// URL de l'image
				iconSize: [30, 30],														// Taille de l'image
				popupAnchor: [0, 0]														// Position d'ouverture de la popup
			})
		}
	);
	map.addLayer(marqueur_depart);
	map.addLayer(marqueur_fin);

	// Zoom sur le parcours
	map.fitBounds(tab_coord);
}


// Fonction d'affichage des points sur la carte
function displayDataPoint(data, type) {

	// Récupération des données en JSON
	var liste_points = JSON.parse(data);

	if (type == "I") {

		// Supprime les points d'intérêt sur la carte
		points_interet.clearLayers();

		$.each(liste_points, function(index, point) {

			var coord = JSON.parse(point['st_asgeojson'])['coordinates'];

			// On créé le marqueur
			var marqueur = new L.marker([coord[1], coord[0]], {
					icon: L.icon({
						iconUrl: '/image/pi_1.png',			// URL de l'image
						iconSize: [24, 35],							// Taille de l'image
						popupAnchor: [0, -10]						// Position d'ouverture de la popup
					})
				}
			);

			// On créé le contenu en HTML
			var popup_contenu = '<center class="popup_PT" style="margin-top: 2em;">';
			if (point['photo_pi'] != null) {
				popup_contenu += '<img src="../image/photo_pi/'+ point['photo_pi'] +'"></img>';
			}
			else {
				popup_contenu += '<img src="../image/pi2.png"></img>';
			}

      popup_contenu += ('<h6 style="margin-bottom: 0.2em;">'+
          point['nom_pic'] +
        '</h6>'+
        '<div style="margin-top: 0.5em;">'+
          point['description_pi'] +
        '</div>'+
				'<div style="margin-top: 0.5em;">'+
          '<a href="'+ point['url_pi'] +'" target="_blank">'+ point['url_pi'] +'</a>'+
        '</div>'+
	    '</center>'+
	    '<div class="modifierMarqueurPI"><i class="fas fa-edit"><span style="display: none;">'+ point['id_interet_pi'] +'</span></i></div>'+
	    '<div class="supprimerMarqueurPI"><i class="fas fa-trash-alt"><span style="display: none;">'+ point['id_interet_pi'] +'</span></i></div>'+
	    '<div class="deplacerMarqueurPI"><i class="fas fa-arrows-alt"><span style="display: none;">'+ point['id_interet_pi'] +'</span></i></div>');


			// Ajoute le contenu HTML dans une popup au marqueur
			marqueur.bindPopup(popup_contenu);

			// Ajoute le marqueur à la liste des points d'intérêt
			points_interet.addLayer(marqueur);
		});

	} else if (type == "V") {

		// Supprime les points de vigilance sur la carte
		points_vigilance.clearLayers();

		$.each(liste_points, function(index, point) {

			var coord = JSON.parse(point['st_asgeojson'])['coordinates'];

			// On créé le marqueur
			var marqueur = new L.marker([coord[1], coord[0]], {
					icon: L.icon({
						iconUrl: '/image/pv.png',				// URL de l'image
						iconSize: [25, 25],							// Taille de l'image
						popupAnchor: [0, -10]						// Position d'ouverture de la popup
					})
				}
			);

			var popup_contenu = '<center class="popup_PT" style="margin-top: 2em;">';
			if (point['photo_pv'] != null) {
				popup_contenu += '<img src="../image/photo_pv/'+ point['photo_pv'] +'"></img>';
			}
			else {
				popup_contenu += '<img src="../image/pv2.png"></img>';
			}
			popup_contenu += ('<h6 style="margin-bottom: 0.2em;">'+
          point['nom_pvc'] +
        '</h6>'+
        '<div style="margin-top: 0.5em;">'+
          point['description_pv'] +
				'</div>'+
				'<div style="margin-top: 0.5em;">'+
					'<div><b>Date de début :</b> '+ point['dt_debut_pv'] +'</div>'+
					'<div><b>Date de fin :</b> '+ point['dt_fin_pv'] +'</div>'+
					'<div><b>Signalé par :</b> '+ point['nom_m'] + ' ' + point['prenom_m'] +'</div>'+
					'<div><b>Le :</b> '+ point['dt_creation_pv'] +'</div>'+
        '</div>'+
	    '</center>'+
	    '<div class="modifierMarqueurPV"><i class="fas fa-edit"><span style="display: none;">'+ point['id_vigilance_pv'] +'</span></i></div>'+
	    '<div class="supprimerMarqueurPV"><i class="fas fa-trash-alt"><span style="display: none;">'+ point['id_vigilance_pv'] +'</span></i></div>'+
	    '<div class="deplacerMarqueurPV"><i class="fas fa-arrows-alt"><span style="display: none;">'+ point['id_vigilance_pv'] +'</span></i></div>');


			// Ajoute le contenu HTML dans une popup au marqueur
			marqueur.bindPopup(popup_contenu);

			// Ajoute le marqueur à la liste des points d'intérêt
			points_vigilance.addLayer(marqueur);
		});

	}
}

// Fonction d'affichage des points déplaçables sur la carte
function displayDataTronconEdition(data) {
	// Supprime les tronçons de la carte (variable globale donc au cas où)
	troncons.clearLayers();

	// Récupération des données en JSON + tableau des coordonnées (pour zoom automatique)
	var liste_troncons = JSON.parse(data);
	var tab_coord = [];

	// Déclaration des variables qui vont contenir le premier et dernier point d'un tronçon
	var debut = null;
	var fin = null;

	// Pour chaque tronçons
	$.each(liste_troncons, function(index, un_troncon) {

		// Tableau contenant les coordonnées du tronçons
		var trace_troncon = [];

		// On récupère les coordonnées du troncon
		var coords = JSON.parse(un_troncon['st_asgeojson'])['coordinates'];

		// Pour chaque coordonnées dans le troncon
		$.each(coords, function(index2, ligne) {

			if(debut === null) {
				debut = [ligne[1], ligne[0]];
			}

			fin = [ligne[1], ligne[0]];

			// On la stocke dans les tableaux
			trace_troncon.push([ligne[1], ligne[0]]);
			tab_coord.push(trace_troncon);
		});

		// Création du polyline du troncon sur la carte
		var polyline = L.polyline(trace_troncon);

		// Ajout du polyline à la liste des parcours
		troncons.addLayer(polyline);

		var id_troncon = troncons.getLayerId(polyline);

		var troncon_map = troncons.getLayer(id_troncon);

		troncon_map.on('pm:edit', function(e) {
			actualiser_var_troncon(e.target._leaflet_id, e.target);
		});

		creer_ligne_troncon(id_troncon, troncon_map, un_troncon);
	});

	// Zoom sur le parcours
	map.fitBounds(tab_coord);
}



// Récupération des zones allures d'un parcours
function getDataZoneAllure(mode,id_za,edition=false) {
	// Récupération des paramètres
	var url = new URLSearchParams(location.search);

	// Récupération des tronçons
	$.post('/fonction/recup_data_zones_allure_parcours.php',

		// Récupération de l'ID du parcours
		{
			id_parcours: url.get('id'),
			mode: mode,
			id_za: id_za
		},

		function(data) {
			displayDataZoneAllure(data,mode,edition);
		}
	);
}



// Affichage des zones allure sur la carte
function displayDataZoneAllure(data,mode,edition) {
	// Supprime les zones allure de la carte (variable globale donc au cas où)
	if (mode=="all"){
		zone_allure.clearLayers();
	}

	// Récupération des données en JSON
	var liste_za = JSON.parse(data);

	// Pour chaque zone allure
	$.each(liste_za, function(index, une_zoneAllure) {

		// Tableau contenant les coordonnées de la zone allure
		var trace_za = [];
    var type_za;
		var nom_za;

		// On récupère les coordonnées de la zone allure
		var coords = JSON.parse(une_zoneAllure['st_asgeojson'])['coordinates'];

    if (mode=="all" || mode=="one" ){
      type_za = une_zoneAllure['id_type_za'];
			nom_za = une_zoneAllure['nom_ta'];
    }

		// Calculer couleur selon niveau difficulté
		if (type_za== 1) {
			couleur = 'black';
		}
		else if (type_za == 2) {
			couleur = 'black';
		}
		else if (type_za == 3) {
			couleur = 'black';
		}
    else if (type_za == 4) {
      couleur = 'black';
    }
    else{
      couleur = 'grey';
    }

		// Pour chaque coordonnées dans la zone allure
		$.each(coords, function(index2, ligne) {
			// On la stocke dans les tableaux
			trace_za.push([ligne[1], ligne[0]]);
		});

		// Création du polyline du troncon sur la carte
		var polyline = L.polyline(trace_za, {color: couleur, opacity: 0.5, weight: 20}).bindTooltip(nom_za, {direction: top, permanent: true, opacity: 0.5});

		if (edition == true){
			var popup_contenu = ('<center class="popup_PT" style="margin-top: 2em;">'+
				'<img src="../image/za2.png" width="100px"></img>'+
				'<h6 style="margin-bottom: 0.2em;">'+
					une_zoneAllure['nom_ta'] +
				'</h6>'+
			'</center>'+
			'<div class="supprimerZA"><i class="fas fa-trash-alt"><span style="display: none;">'+ une_zoneAllure['id_zone_za'] +'</span></i></div>');


			// Ajoute le contenu HTML dans une popup au marqueur
			polyline.bindPopup(popup_contenu);
		}

		// Ajout du polyline à la liste des parcours
		zone_allure.addLayer(polyline);
	});
}
