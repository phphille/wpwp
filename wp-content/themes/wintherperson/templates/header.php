<?php
  $loggedInmMenu = '';
  if(is_user_logged_in()){
    $wp_user = new WP_User( get_current_user_id());
    $user = $wp_user->roles[0];

    if($user == 'associationDelegate' || $user == 'manager'){
      $loggedInmMenu .= '
      <a href="skapa-anvandare">Skapa användare</a>
      <a href="se-anvandare">Se användare</a>
      ';
    }

    if($user == 'salesperson'){
      $loggedInmMenu .= '
      <a href="registrera-salda-korvlador">Registrera sålda korvlådor</a>
      <a href="registrera-foretagskund">Registrera företagskund</a>';
    }

    $loggedInmMenu .= '<a href="konto">Konto</a>';
  }


 ?>

<header class="banner">
  <div class="container">
    <nav class="nav-primary">
      <?php
        $args = [
          'theme_location'    => 'primary_navigation',
          'depth'             => 2,
          'container'         => 'div',
          'container_class'   => 'collapse navbar-collapse',
          'container_id'      => 'bs-example-navbar-collapse-1',
          'menu_class'        => 'nav navbar-nav',
          'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
          'walker'            => new wp_bootstrap_navwalker()
          ];
      ?>

      <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          </div>

          <?php wp_nav_menu($args);?>

          <?php echo $loggedInmMenu; ?>
          <?php if($loggedInmMenu != ''): ?>
            <a href="<?php echo wp_logout_url(); ?>">Logout</a>
          <?php else: ?>
            <a href="login" class="login" id="login_btn">Logga In</a>
          <?php endif; ?>
      </div>
      </nav>
    </nav>
  </div>
</header>
