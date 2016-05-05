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
      <a href="se-korvlador">Korvlådor</a>
      ';
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

    $imgargs = array(
        'post_type' => 'attachment',
        'post_mime_type' =>'image',
        'post_status' => 'inherit',
        'posts_per_page' => 5,
        'orderby' => 'rand'
    );

		$header_logo = "";
    $query_images = new WP_Query( $imgargs );
    $images = array();
    foreach ( $query_images->posts as $image) {
        $images[]= $image->guid;
				$post_title = $image->post_title;
			if($post_title == "logo") {
				$header_logo = $image->guid;
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

          <?php echo $loggedInmMenu; ?>
          <?php if($loggedInmMenu != ''): ?>
            <a href="<?php echo wp_logout_url(); ?>">Logga ut</a>
          <?php else: ?>
            <a href="login" class="login" id="login_btn">Logga In</a>
          <?php endif; ?>
      </div>
      </nav>
    </nav>
  </div>
</header>
