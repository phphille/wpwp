<?php the_content(); ?>

<?php

// if (!class_exists('Social')) {
//   echo  plugins_url();
// 	// load Social if not already loaded
// 	include(plugins_url().'/wordpress-user-management/user-management.php');
// }
$userID = get_query_var('user');
$editableIds = explode(',', get_user_meta(get_current_user_id(), 'userids', true));

if(!in_array($userID, $editableIds) ){
  // global $wp_query;
  // $wp_query->set_404();
  // status_header( 404 );
  // get_template_part( 404 ); exit();
  echo "kan inte uppdatera användaren";
}
else {
  echo do_shortcode('[getCreateFormSalesmen updateId="'.$userID.'"]');
}
?>
