<?php
	ini_set('display_errors', 1);
	session_start();
	require_once('include/connect.php');

	// Si le membre est connecté
	if(isset($_SESSION['membre']['id'])) {

		// Si ses données n'ont pas été encore récupérées
		if(!isset($_SESSION['membre']['id_membre_m'])) {

			// On récupère ses données dans un tableau 'membre' en session
			try {
				$bdd = connect();
				$res = pg_query_params($bdd, 'SELECT * FROM membre WHERE id_membre_m = $1', array($_SESSION['membre']['id']));
			} catch(Exception $e) {
				echo('erreur : '. $e);
			}

			$res = pg_fetch_all($res)[0];

			foreach ($res as $key => $value) {
				$_SESSION['membre'][$key] = $value;
			}
		}
	}
?>

<!-- HEADER -->
<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light" id="header">
	<a class="navbar-brand" href="./">

		<!-- LOGO -->
		<img src="image/logo_peoc.png" height="60" style="margin-right: 0.5em;">
	</a>

	<!-- LIENS MENU -->
	<div class="collapse navbar-collapse" id="navLiens">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0" id="navigation">
			<li class="nav-item active">
				<a class="nav-link" href="./">Accueil</a>
			</li>

			<li class="nav-item active">
				<a class="nav-link" href="./">Liste des parcours</a>
			</li>

			<?php if(isset($_SESSION['membre']['id_membre_m'])) { ?>
				<li class="nav-item">

					<?php // TODO: Page mon compte ?>
					<a class="nav-link" href="../">Mon compte</a>
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
				<a href="fonction/deconnexion.php">
					<button type="button" class="btn btn-lg btn-danger">
						Déconnexion
					</button>
				</a>

		<?php } else { ?>

			<div class="mt-2 mt-lg-0">
				<button type="button" class="btn btn-lg btn-warning" data-toggle="modal" data-target="#connexion">
					Connexion
				</button>

				<button type="button" class="btn btn-lg" id="bt_inscription">
					Inscription
				</button>
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
		$.post(
			'fonction/verif_connexion.php',
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
