<?php


function custom_post_faq() {

  $labels = array(
    'name'               => __( 'FAQ'),
    'singular_name'      => __( 'FAQ'),
    'add_new'            => __( 'Skriv FAQ' ),
    'add_new_item'       => __( 'Skriv FAQ' ),
    'edit_item'          => __( 'Uppdatera FAQ' ),
    'new_item'           => __( 'Ny FAQ' ),
    'all_items'          => __( 'FAQ' ),
    'view_item'          => __( 'Se FAQ' ),
    'search_items'       => __( '' ),
    'not_found'          => __( '' ),
    'not_found_in_trash' => __( '' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'FAQ'
  );
  $args = array(
    'labels'              => $labels,
    'description'         => 'FAQ',
    'public'              => true,
    'publicly_queryable'  => false,
    'menu_position'       => 5,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'supports'            => array( 'title', 'editor' )
  );
  register_post_type( 'faq', $args );
}
add_action( 'init', 'custom_post_faq' );



// //Add the Meta Box
// function add_custom_meta_box_faq() {
//     add_meta_box(
//         'faq_metabox', // $id
//         '', // $title
//         'show_custom_meta_box', // $callback
//         'faq', // $page
//         'normal', // $context
//         'high'); // $priority
// }
// add_action('add_meta_boxes', 'add_custom_meta_box_faq');
//



function disable_new_faq_posts() {

  $args = array(
  'post_type' => 'faq',
  'posts_per_page' => -1,
  );

  $query = new WP_Query($args);
  if($query->post_count > 0){
    remove_submenu_page( 'edit.php?post_type=faq', 'post-new.php?post_type=faq' );
  }
  else {
    remove_submenu_page( 'edit.php?post_type=faq', 'edit.php?post_type=faq' );
  }
}
add_action('admin_menu', 'disable_new_faq_posts');





function hide_buttons_faq(){
  global $current_screen;
  // var_dump($current_screen->id);
  if($current_screen->id == 'edit-faq' || $current_screen->id == 'faq'){
    $args = array(
    'post_type' => 'faq',
    'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    if(!empty($query->posts)){
      echo '<style>.page-title-action{display: none;}';
    }
  }

}
add_action('admin_head','hide_buttons_faq');
