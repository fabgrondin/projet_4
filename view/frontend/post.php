<?php
$title = $post['title'];

ob_start();
?>

<div class="row">
  <div class="panel panel-primary col-md-8 col-md-offset-2">
    <div class="panel-heading">
      <h1><?= $post['title'] ?></h1>
      <p><?= $post['creation_date_fr'] ?></p>
    </div>
    <div class="panel-body">
      <p> <?= nl2br($post['content']) ?></p>
    </div>
  </div>
</div>

<div class="row">
  <div class="panel-group col-md-4 col-md-offset-2">
      <h2>Commentaires</h2>
      <?php
        while ($comment = $comments->fetch()) {
            ?>
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3><?= $comment['author'] ?></h3>
                <p><?= $comment['comment_date_fr'] ?></p>
              </div>
              <div class="panel-body">
                <p><?= nl2br($comment['comment']) ?></p>
              </div>
            </div>
          <?php
        }
       ?>
  </div>
</div>

<div class="row">
  <form action="index.php?action=addComment&amp;id=<?= $post['id'] ?>" method="post" class="col-md-4 col-md-offset-2 well form-horizontal">
    <legend>
      <h2>Ajouter un commentaire</h2>
    </legend>
    <div class="form-group">
      <label for="author" class="control-label col-sm-4">Auteur :</label>
      <div class="col-sm-8">
        <input type="text" class="form-control" name="author" id="author">
      </div>
    </div>

    <div class="form-group">
      <label for="comment" class="control-label col-sm-4">Commentaire :</label>
      <div class="col-sm-8">
        <textarea class="form-control" name="comment" id="comment" cols="30" rows="10"></textarea>
      </div>
    </div>

    <div class="text-right">
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </div>
  </form>
</div>

<?php

$content = ob_get_clean();

require('template.php');