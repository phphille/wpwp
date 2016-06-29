<?php
  $loggedInmMenu = '';
  if(is_user_logged_in()){
    $wp_user = new WP_User( get_current_user_id());
    $user = $wp_user->roles[0];

    if($user == 'associationDelegate' || $user == 'manager'){
      $loggedInmMenu .= '
      <a href="skapa-anvandare">Skapa användare</a>
      <a href="se-korvlador">Korvlådor</a>
      ';
    }

    if($user == 'salesperson'){
      $loggedInmMenu .= '
      <a href="registrera-salda-korvlador">Korvlådor</a>
      ';
    }

    $loggedInmMenu .= '<a href="hem-inloggad">Hem</a>';
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

    $imgargs = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'post_status' => 'inherit',
        'posts_per_page' => 100,
        'orderby' => 'asc'
    );

		$header_logo = "";
		$social_at = "";
		$social_fb = "";
    $query_images = new WP_Query( $imgargs );
    $images = array();
    foreach ( $query_images->posts as $image) {
        $images[]= $image->guid;
				$post_title = $image->post_title;
			switch($post_title) {
				case "logo":
					$header_logo = $image->guid;
					break;
				case "social_at":
					$social_at = $image->guid;
					break;
				case "social_f":
					$social_fb = $image->guid;
					break;
			}
    }
			?>

      <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
					<div id="header-logo" class="visible-sm visible-xs header-logo_mobile">
						<a href="#"><img class="img-responsive" src="<?php echo $header_logo; ?>" alt="wp logo"></a>
				</div>

					<div id="header-logo" class="hidden-sm hidden-xs header-logo_nonmobile">
						<div class="login_actions">
							<?php echo $loggedInmMenu; ?>
							<?php if($loggedInmMenu != ''): ?>
								<a href="<?php echo wp_logout_url(); ?>">Logga ut</a>
							<?php else: ?>
								<a href="login" class="login" id="login_btn">Logga In</a>
							<?php endif; ?>
						</div>
						
						<div class="social_icons">
							<a href="mailto:info@wpknackwurst.se"><img src="<?php echo $social_at; ?>" alt="email"></a>
							<a href="http://www.facebook.com/pages/Korvlådan/1376704122619120"><img src="<?php echo $social_fb; ?>" alt="facebook"></a>	
						</div>
						<a href="#"><img class="img-responsive" src="<?php echo $header_logo; ?>" alt="wp logo"></a>
				</div>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>

          <?php wp_nav_menu($args);?>
      </div>
      </nav>
    </nav>
  </div>
</header>
