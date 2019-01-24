// Pour récupérer les données des centres équestres
function getDataCE(){

	// Supprime les layers dans la liste des centres équestres
	centres_equestres.clearLayers();

	// Récupère les layers des centres équestres en POST
	$.post(
		'fonction/recup_data_centres_equestres.php',
		function(data) {
			var listeCE = JSON.parse(data);

			// Pour chaque centre équestre
			$.each(listeCE, function(index, CE) {

				// On récupère les coordonnées du marqueur
				var coord = JSON.parse(CE['st_asgeojson'])['coordinates'];

				// On créé le marqueur
				var marqueur_CE = new L.marker([coord[1], coord[0]], {
						icon: L.icon({
							iconUrl: '../image/ce.png',			// URL de l'image
							iconSize: [20, 20],							// Taille de l'image
							popupAnchor: [0, -10]						// Position d'ouverture de la popup
						})
					}
				);

				// On créé le contenu en HTML
				var popup_contenu =
				'<div class="popup_CE">'+
					'<img src="../image/logo/'+ CE['logo_ce'] +'"></img>'+
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

// TODO: affichage carte (récup) + création liens parcours liste
function getDataParcoursAll() {
	parcours.clearLayers();

	$.post(
		'fonction/recup_data_parcours_all.php',
		function(data) {
			var liste_parcours = JSON.parse(data);

			// Pour chaque parcours
			$.each(liste_parcours, function(index, parcours) {

				// On ajoute le parcours à la liste
				$("#resParcours .list-group").append(
				'<li class="list-group-item list-group-item-warning" id="parcours'+ parcours['id_parcours_p'] +'">'+
					parcours['nom_p'] +
					'<span class="badge badge-primary badge-pill">7 💬</span>'+
				'</li>');

				// Tableau contenant les coordonnées des tronçons
				var trace_parcours = [];

				// Pour chaque troncon dans le parcours
				$.each(parcours['troncons'], function(index2, troncon) {

					// On récupère les coordonnées du troncon
					var coords = JSON.parse(troncon['st_asgeojson'])['coordinates'];

					// Pour chaque coordonnées dans le troncon
					$.each(coords, function(index3, ligne) {

						// On la stocke dans le tableau
						trace_parcours.push([ligne[1], ligne[0]]);
					});

					var polyline = L.polyline(trace_parcours, {color:'red'}).addTo(map);
				});
			});
		}
	);
}