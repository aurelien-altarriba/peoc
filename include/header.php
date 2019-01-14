<?php
	ini_set('display_errors', 1);
	session_start();
	require_once('./include/connect.php');

	$_SESSION['membre']['id'] = 2;

	// Récupération des données du membre connecté dans un tableau 'membre'
	if(isset($_SESSION['membre']['id'])) {
		if(!isset($_SESSION['membre']['id_membre_m'])) {
			try {
				$bdd = connect();
				$res = pg_query_params($bdd, 'SELECT * FROM membre WHERE id_membre_m = $1', array($_SESSION['membre']['id']));
			} catch(Exception $e) {
				echo('erreur : '. $e);
			}

			$res = pg_fetch_all($res);

			foreach ($res[0] as $key => $value) {
				$_SESSION['membre'][$key] = $value;
			}
		}
	}
?>

<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light" id="header">
	<a class="navbar-brand" href="./">
		<img src="./image/logo_peoc.png" height="60" style="margin-right: 0.5em;">
	</a>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navLiens" aria-controls="navLiens" aria-expanded="false">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navLiens">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0" id="navigation">
			<li class="nav-item active">
				<a class="nav-link" href="./">Accueil</a>
			</li>

			<?php if(isset($_SESSION['id_membre'])) { ?>
				<li class="nav-item">
				<a class="nav-link" href="./">Mon compte</a>
				</li>
			<?php } ?>
		</ul>

		<div class="mr-auto mt-2">
			<h1 id="slogan"><span class="initiale">P</span>romenades <span class="initiale">É</span>questres en <span class="initiale">OC</span>citanie</h1>
		</div>

		<div class="mt-2 mt-lg-0">
			<?php if(isset($_SESSION['id_membre'])) {
				echo("<h2>Bienvenue </h2>");
			} else { ?>
				<button type="button" class="btn btn-lg btn-warning" data-toggle="modal" data-target="#connexion">
					Connexion
				</button>
				<button type="button" class="btn btn-lg btn-danger">Inscription</button>
			<?php } ?>
		</div>

	</div>
</nav>

<!-- Modal -->
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
				<form>
					<div class="form-group">
						<label for="login">Login</label>
						<input type="text" class="form-control" id="login">
					</div>

					<div class="form-group">
						<label for="mdp">Mot de passe</label>
						<input type="password" class="form-control" id="mdp">
					</div>

					<button type="submit" class="btn btn-success btn-block">Se connecter</button>
				</form>
			</div>

			<div class="modal-footer">
				<a href="">Login ou mot de passe oublié ?</a>
			</div>
		</div>
	</div>
</div>
