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
							iconUrl: 'image/ce.png',			// URL de l'image
							iconSize: [10, 10],							// Taille de l'image
							popupAnchor: [0, -5]						// Position d'ouverture de la popup
						})
					}
				);

				// On créé le contenu en HTML
				var popup_contenu =
				'<div class="popup_CE">'+
					'<img src="image/logo/'+ CE['logo_ce'] +'"></img>'+
					'<h2>'+ CE['nom_ce'] +'</h2>'+
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
				iconUrl: '/image/depart_parcours.png',			// URL de l'image
				iconSize: [25, 25],												// Taille de l'image
				popupAnchor: [0, 0]											// Position d'ouverture de la popup
			})
		}
	);

	var marqueur_fin = new L.marker(fin, {
			icon: L.icon({
				iconUrl: '/image/arrivee_parcours.png',			// URL de l'image
				iconSize: [25, 25],												// Taille de l'image
				popupAnchor: [0, 0]											// Position d'ouverture de la popup
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
						iconUrl: '/image/pi.png',			// URL de l'image
						iconSize: [20, 15],							// Taille de l'image
						popupAnchor: [0, -10]						// Position d'ouverture de la popup
					})
				}
			);

			// On créé le contenu en HTML
			var popup_contenu = '<div class="popup_PT">';
			if(point['photo_pi']!=null){
				popup_contenu += '<img src="../image/photo_pi/'+ point['photo_pi'] +'"></img>';
			}
			else{
			 	popup_contenu += '<img src="../image/pi2.png"></img>';
			}
			popup_contenu += '<h2>'+ point['nom_pic'] +'</h2>'+
			 	'<hr>'+
			 	'<div><b>Description :</b> '+ point['description_pi'] +'</div>'+
			 	'<div><b>Site web :</b> <a href="'+ point['url_pi'] +'" target="_blank">'+ point['url_pi'] +'</a></div>'+
			 '</div>';

			 console.log(popup_contenu);

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
						iconUrl: '/image/pv.png',			// URL de l'image
						iconSize: [20, 20],							// Taille de l'image
						popupAnchor: [0, -10]						// Position d'ouverture de la popup
					})
				}
			);

			// On créé le contenu en HTML
			var popup_contenu = '<div class="popup_PT">';
			console.log(point['photo_pv']);
			if(point['photo_pv']!=null){
				popup_contenu += '<img src="../image/photo_pv/'+ point['photo_pv'] +'"></img>';
			}
			else{
			 	popup_contenu += '<img src="../image/pv2.png"></img>';
			}
			 popup_contenu += '<h2>'+ point['nom_pvc'] +'</h2>'+
			 '<hr>'+
			 '<div><b>Description :</b> '+ point['description_pv'] +'</div>'+
			 '<div><b>Date de début :</b> '+ point['dt_debut_pv'] +'</div>'+
			 '<div><b>Date de fin :</b> '+ point['dt_fin_pv'] +'</div>'+
			 '<div><b>Signalé par :</b> '+ point['id_membre_pv'] + ' - ' + point['nom_m'] + ' ' + point['prenom_m'] +'</div>'+
			 '<div><b>Le :</b> '+ point['dt_creation_pv'] +'</div>'+
			'</div>';

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

	// Affichage des noeuds de chaque tronçon
	var iconT = L.icon({
		iconUrl: '/image/marker.png',
		shadowUrl: '/image/marker-shadow.png',
		iconSize: [15, 15]
	});
	L.Icon.Default.imagePath = "/image/";
	$.each(tab_coord, function(keyT,valTroncon){
		$.each(valTroncon, function(keyP,valPoint){
			//console.log(valPoint);
			var marker = L.marker([valPoint[0],valPoint[1]],{"title":"point "+keyP,"icon":iconT,"draggable":true}).addTo(map);
		});
	});


	// Zoom sur le parcours
	map.fitBounds(tab_coord);
}







// Récupération des zones allures d'un parcours
function getDataZoneAllure(edition = false) {

	// Récupération des paramètres
	var url = new URLSearchParams(location.search);

	// Récupération des tronçons
	$.post('/fonction/verif_zone_allure.php',

		// Récupération de l'ID du parcours
		{
			action: 0,
			id: url.get('id')
		},

		function(data) {
			if (edition != false) {
				//displayDataZoneAllureEdition(data);

			} else {
				displayDataZoneAllure(data);
			}
		}
	);
}



// Affichage des zones allure sur la carte
function displayDataZoneAllure(data,mode,tp) {

	// Supprime les zones allure de la carte (variable globale donc au cas où)
	zone_allure.clearLayers();

	// Récupération des données en JSON
	var liste_za = JSON.parse(data);

	// Pour chaque tronçons
	$.each(liste_za, function(index, une_zoneAllure) {

		// Tableau contenant les coordonnées du tronçons
		var trace_za = [];
    var type_za;

		// On récupère les coordonnées du troncon
		var coords = JSON.parse(une_zoneAllure['st_asgeojson'])['coordinates'];

    if (mode=="all"){
      type_za = une_zoneAllure['id_type_za']
    }
    else{
      type_za = tp;
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

		// Pour chaque coordonnées dans le troncon
		$.each(coords, function(index2, ligne) {
			// On la stocke dans les tableaux
			trace_za.push([ligne[1], ligne[0]]);
		});

		// Création du polyline du troncon sur la carte
		var polyline = L.polyline(trace_za, {color: couleur, opacity: 0.5, weight: 20});

		// Ajout du polyline à la liste des parcours
		zone_allure.addLayer(polyline);
	});

}
*/
