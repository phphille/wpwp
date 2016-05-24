<?php the_content(); ?>

<?php

// if (!class_exists('Social')) {
//   echo  plugins_url();
// 	// load Social if not already loaded
// 	include(plugins_url().'/wordpress-user-management/user-management.php');
// }
  $wp_user = new WP_User( get_current_user_id());
  $user = $wp_user->roles[0];


?>


<?php
if($user == 'associationDelegate' || $user == 'manager'){
  echo do_shortcode('[getCreateFormUser]');
}
