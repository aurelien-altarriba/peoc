// On réinitialise les formulaires
function reset_formulaire() {
	// On vide le formulaire
	$("#form_pv")[0].reset();
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

	// ZONES ALLURE
	zone_allure = new L.LayerGroup();

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
		'Points de vigilance': points_vigilance,
		'Zones d\'allure': zone_allure
	};

	// CRÉATION DE LA MAP
	map = L.map('map', {
    layers: [Satellite, troncons, points_interet, points_vigilance, zone_allure]
	});

	// Coordonnées à l'initialisation de la map (Occitanie)
	map.setView([43.7, 2.5], 8);

	// Pour appliquer la cartes et les calques sélectionnés
	layersControl = L.control.layers(cartes, menu_data).addTo(map);

	// Stocke la lat/long dans les variables lat et lng
	map.on('click', function(e) {
		var lat = e.latlng.lat;
		$("#zs_latitude_pv").val(lat);
		var lng = e.latlng.lng;
		$("#zs_longitude_pv").val(lng);
	});

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
			$('#modifierMarqueurPI').hide();

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
  		$('#ajouterMarqueurPV').show();
  		$('#titre_form_marqueur').text('Ajouter le point de vigilance');
  		$('#modifierMarqueurPV').hide();

  		reset_formulaire();

  		// On fait apparaître la fenêtre de création d'un marqueur au clic sur la carte
  		$('#formulaireMarqueur').modal();

  		// On masque la fenêtre de création d'un marqueur quand on clique à côté
  		$('#formulaireMarqueur').on('hidden.bs.modal', function () {
  			$('#ajouterMarqueurPV').unbind('click');
		});

		// Quand le bouton d'ajout du marqueur est cliqué
		$('#ajouterMarqueurPV').bind('click', function(event) {

			// Récupération des champs du formulaire
			var zs_dt_debut_pv = $('#zs_dt_debut_pv').val();
			var zs_dt_fin_pv = $('#zs_dt_fin_pv').val();
			var zs_categorie_pv = $('#zs_categorie_pv').val();
			var zs_description_pv = $('#zs_description_pv').val();
			// var zs_photo_up = $('#zs_photo_up').val();
			var Latitude = e.latlng.lat;
			var Longitude = e.latlng.lng;

			var url = new URLSearchParams(location.search);
			// Si on modifie le parcours
			var Parcours = url.get('id');

			// ENREGISTREMENT DU POINT DE VIGILANCE VIA AJAX
  		$.ajax({
        url : '/fonction/verif_point_vigilance_creation.php',
        type : 'POST',
        dataType : 'text',
        data: {
        	zs_dt_debut_pv: zs_dt_debut_pv,
        	zs_dt_fin_pv: zs_dt_fin_pv,
        	zs_categorie_pv: zs_categorie_pv,
					zs_description_pv: zs_description_pv,
        	latitude: Latitude,
        	longitude: Longitude,
					parcours: Parcours,
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

	// Charge les données sur la carte
	getDataZoneAllure('all',null);
	getDataTroncon();
	getDataPoint('I');
	getDataPoint('V');
});
