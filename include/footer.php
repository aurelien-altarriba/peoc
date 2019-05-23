<div id="footer">© Copyright PÉ-OC 2019 <a href="#" data-toggle="modal" data-target="#contact">Contact</a></div>

<!-- POPUP DE CONTACT -->
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contact">Contacter l'équipe PÉ-OC</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <!-- FORMULAIRE -->
        <form id="contactForm" onsubmit="return false;">
          <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" name="nom" class="form-control" id="nom" required>
          </div>
          <div class="form-group">
            <label for="login">Votre mail</label>
            <input type="email" name="mail" class="form-control" id="mail" required>
          </div>
          <div class="form-group">
            <label for="message">Votre message</label>
             <textarea class="form-control" rows="5" id="message" name="message" placeholder="Entrez votre message ici s'il vous plait..."></textarea>
          </div>
          <button id="bt_contact" class="btn btn-success btn-block">Envoyer</button>
          <div id="msgSubmit" class="h3 text-center hidden"></div>
        </form>
      </div>
    </div>
  </div>
</div>
	<script type="text/javascript" src="/js/contacter.js"></script>
