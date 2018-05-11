<?php
$title="Connexion";

ob_start();
?>
  <div class="row justify-content-center">
    <div class="card col-md-4" id="login_form">
      <h2 class="card-header text-white bg-primary">Accès à la zone administrative</h2>
      <div class="card-body">
        <form action="index.php?action=login" method="post">
          <div class="form-group">
            <label for="pseudo">Pseudo :</label>
            <input type="text" name="pseudo" id="pseudo" class="form-control">
          </div>
          <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" class="form-control">
          </div>
          <div class="text-right">
            <button type="submit" class="btn btn-primary">Connexion</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
$content=ob_get_clean();

require('template.php');
