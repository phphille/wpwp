<?php the_content(); ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <!-- <div class="alert alert-warning" role="alert"><?php // echo __('Användarnamn eller lösenordet stämmer inte, vänligen prova igen!'); ?></div> -->
      <?php
      // if (isset($_GET['mode']) && $_GET['mode'] == 'blocked') {
      //   $errormsg = __('Ditt konto har blivit blockerat. Vänligen kontakta bopoolen för mer information.');
      //   echo '<div class="alert alert-danger blocked" role="alert">'.$errormsg.'</div>';
      // }
      ?>

      <form name="loginform" id="loginform" action="../wp-login.php" method="post">
        <div class="form-group">
          <label class="control-label" for="InputEmail1">Användarnamn</label>
          <input class="form-control" name="log" id="user_login" placeholder="<?php //echo __('Fyll i din epostadress') ?>" type="text">
        </div>
        <div class="form-group">
          <label class="control-label" for="InputPassword">Lösenord</label>
          <input class="form-control" name="pwd" id="user_pass" placeholder="<?php //echo __('Fyll i ditt lösenord') ?>" type="password">
        </div><button class="btn btn-grey login pull-right" id="submit" type="submit">Logga in</button>
        <br><br><br>
        <a href="<?php echo wp_lostpassword_url( get_permalink() ); ?>" class="pull-right">Har du glömt ditt lösenord?</a>
      </form>
    </div>
  </div>
</div>
