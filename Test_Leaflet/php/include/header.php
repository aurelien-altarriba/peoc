<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light" id="header">
	<a class="navbar-brand" href="./">
		<img src="images/port.png" height="60" style="margin-right: 0.5em;border-radius:50%;">
	</a>

	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navLiens" aria-controls="navLiens" aria-expanded="false">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navLiens">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0" id="navigation">
			<li class="nav-item active">
				<a class="nav-link" href="./">Accueil</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="./">Mon compte</a>
			</li>
		</ul>

		<div class="mr-auto mt-2">
			<h1 id="slogan"><span class="initiale">G.P.M.M.</span> : <span class="initiale"></span>Port Marseille-Fos</h1>
		</div>

		<div class="mt-2 mt-lg-0">
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#connexion">
				Connexion
			</button>
			<button type="button" class="btn btn-secondary">Inscription</button>
		</div>

	</div>
</nav>

<!-- Modal -->
<div class="modal fade" id="connexion" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="connexion">Connexion à carto G.P.M.M.</h5>
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