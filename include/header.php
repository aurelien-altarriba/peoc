<?php
	ini_set('display_errors', 1);
	session_start();

	require_once($_SERVER['DOCUMENT_ROOT'] ."/include/config.php");
	require_once($_SERVER['DOCUMENT_ROOT'] ."/include/connect.php");

	// Si le membre est connecté
	if(!empty($_SESSION['membre']['id'])) {

		// Si ses données n'ont pas été encore récupérées
		if(empty($_SESSION['membre']['id_membre_m'])) {
			$bdd = connect();

			// On récupère ses données dans un tableau 'membre' en session
			try {
				$res = pg_query_params($bdd, 'SELECT * FROM membre WHERE id_membre_m = $1', array($_SESSION['membre']['id']));
				$res = pg_fetch_all($res)[0];
			} catch(Exception $e) {
				echo('erreur : '. $e);
			}

			// On assigne les données au tableau de session
			foreach ($res as $key => $value) {
				$_SESSION['membre'][$key] = $value;
			}

			// VÉRIFICATION SI CENTRE ÉQUESTRE
			try {
				$verif_ce = pg_query_params($bdd, 'SELECT * FROM centre_equestre WHERE id_membre_ce = $1', array($_SESSION['membre']['id']));
				$verif_ce = pg_fetch_all($verif_ce)[0];
			} catch(Exception $e) {
				echo('erreur : '. $e);
			}

			// Si il dirige un centre équestre
			if(!empty($verif_ce['id_membre_ce'])) {

				// On assigne les données au tableau de session
				foreach ($verif_ce as $key => $value) {
					$_SESSION['membre']['ce'][$key] = $value;
				}

				// On lui assigne le type centre_equestre
				$_SESSION['membre']['type'][] = 'centre_equestre';
			}

			// VÉRIFICATION SI CAVALIER
			try {
				$verif_cavalier = pg_query_params($bdd, 'SELECT * FROM cavalier WHERE id_membre_c = $1', array($_SESSION['membre']['id']));
				$verif_cavalier = pg_fetch_all($verif_cavalier)[0];
			} catch(Exception $e) {
				echo('erreur : '. $e);
			}

			// Si c'est un cavalier
			if(!empty($verif_cavalier['id_membre_c'])) {

				// On assigne les données au tableau de session
				foreach ($verif_cavalier as $key => $value) {
					$_SESSION['membre'][$key] = $value;
				}

				// On lui assigne le type cavalier
				$_SESSION['membre']['type'][] = 'cavalier';
			}
		}
	}
?>

<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light" id="header">
	<a class="navbar-brand" href="/">

		<!-- LOGO -->
		<img src="/image/logo_peoc.png" height="50" style="margin: 0.5em;">
	</a>

	<!-- LIENS MENU -->
	<div class="collapse navbar-collapse" id="navLiens">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0" id="navigation">
				<li class="nav-item">
					<div class="dropdown">
					  <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdown_parcours" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					    Parcours
					  </a>
					  <div class="dropdown-menu" aria-labelledby="dropdown_parcours">
							<a class="dropdown-item" href="/page/parcours_liste_all.php">Tous les parcours</a>
							<?php if(isset($_SESSION['membre']['id_membre_m'])) { ?>
						    <a class="dropdown-item" href="/page/parcours_liste_perso.php">Mes parcours</a>
						    <a class="dropdown-item" href="/page/parcours_edition.php">Créer un parcours</a>
						    <a class="dropdown-item" href="/page/parcours_liste_effectue.php">Parcours effectués</a>
							<?php } ?>
					  </div>
					</div>
				</li>

				<li class="nav-item">
					<div class="dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" id="dropdown_centre_equestre" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Centres Équestres
						</a>
						<div class="dropdown-menu" aria-labelledby="dropdown_centre_equestre">
							<a class="dropdown-item" href="/page/centre_equestre_liste_all.php">Tous les centres équestres</a>
						</div>
					</div>
				</li>

				<?php if(isset($_SESSION['membre']['id_membre_m'])) { ?>
					<li class="nav-item">
						<div class="dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" id="dropdown_profil" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							 Mon profil
						 </a>
						 <div class="dropdown-menu" aria-labelledby="dropdown_profil">
								<a class="dropdown-item" href="/page/profil.php">Mon profil</a>
								<?php if(isset($_SESSION['membre']['ce']['id_centre_ce']) && in_array("centre_equestre", $_SESSION['membre']['type'])) { ?>
									<a class="dropdown-item" href="/page/centre_equestre.php">Mon centre équestre</a>
								<?php } ?>
							</div>
						</div>
					</li>
				<?php } ?>
		</ul>

		<!-- SLOGAN -->
		<div class="mr-auto mt-2">
			<h1 id="slogan"><span class="initiale">P</span>romenades <span class="initiale">É</span>questres en <span class="initiale">OC</span>citanie</h1>
		</div>

		<!-- BOUTONS (DE)CONNEXION / INSCRIPTION -->
		<?php // Si le membre est connecté
			if(isset($_SESSION['membre']['id_membre_m'])) { ?>

				<div id="msg_bienvenue">Bienvenue <?php echo($_SESSION['membre']['prenom_m']); ?></div>
				<a href="/fonction/deconnexion.php">
					<button type="button" class="btn btn-lg btn-danger">
						Déconnexion
					</button>
				</a>

		<?php } else { ?>

			<div class="mt-2 mt-lg-0">
				<button type="button" class="btn btn-lg btn-warning" data-toggle="modal" data-target="#connexion">
					Connexion
				</button>

				<a href="/page/inscription.php">
					<button type="button" class="btn btn-lg" id="bt_inscription">
						Inscription
					</button>
				</a>
			</div>

		<?php } ?>
	</div>
</nav>

<!-- POPUP DE CONNEXION -->
<div class="modal fade" id="connexion" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">

				<h5 class="modal-title" id="connexion">Connexion à PÉ-OC</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>

			<div class="modal-body">

				<!-- MESSAGE D'ERREUR -->
				<div id="erreur_login"></div>

				<!-- FORMULAIRE -->
				<form onsubmit="return false;">
					<div class="form-group">
						<label for="login">Identifiant</label>
						<input type="text" name="login" class="form-control" id="login" required>
					</div>

					<div class="form-group">
						<label for="mdp">Mot de passe</label>
						<input type="password" name="mdp" class="form-control" id="mdp" required>
					</div>

					<button id="bt_connexion" class="btn btn-success btn-block">Se connecter</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
// Connexion en AJAX
$(document).ready(function(){
	$("#bt_connexion").on("click", function(e) {
		$.post('/fonction/verif_connexion.php',
			{
				login : $("#login").val(),
				mdp : $("#mdp").val()
			},
			function(data) {
				if(data === 'OK') {
					location.reload();
				} else {
					$("#erreur_login").text(data);
				}
			},
			'text'
		);
		e.preventDefault();
	});
});
</script>
