<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php', // Theme customizer
  'lib/nav.php', //bootstrap nav
  'lib/dashboard.php',
  'lib/userroles.php',
  'lib/userprofiles.php',
  'lib/postrequests.php',
  'lib/custom-post-functions.php',
  'lib/products.php',
  'lib/companies.php',
  'lib/lock-orders.php',
  'lib/faq.php',
  'lib/korvlador.php',
  'lib/phpexcel-1.8/classes/phpexcel.php'
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);


function dump($array) {
  echo "<pre>" . htmlentities(print_r($array, 1)) . "</pre>";
}

function get_current_user_role() {
  $currentUser = new WP_User( get_current_user_id());
  return $currentUser->roles[0];
}

function my_enqueue($hook) {
    // if ( 'edit.php' != $hook ) {
    //     return;
    // }
  wp_register_style(
          'jquery-ui-datepicker',
          'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/pepper-grinder/jquery-ui.min.css'
      );
      wp_enqueue_style( 'jquery-ui-datepicker' );
  wp_enqueue_script( 'datepicker',  '/wp-content/themes/wintherperson/assets/scripts/admin-scripts/admin.js', wp_enqueue_script('jquery-ui-datepicker') );
}
add_action( 'admin_enqueue_scripts', 'my_enqueue' );
