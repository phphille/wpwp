<?php


function custom_post_welcome() {
  global $custom_meta_fields;
  $prefix = 'welcome_';
  $custom_meta_fields['welcome'] = array(
      // array(
      //     'label'=> 'Typ',
      //     'desc'  => '',
      //     'id'    => 'faq-type',
      //     'class' => '',
      //     'name' => 'type',
      //     'type'  => 'text'
      // ),
      array(
          'label'=> 'Typ av användare',
          'desc'  => 'För vilken typ av användare ska se innehållet',
          'id'    => 'welcome-type',
          'name' => 'type',
          'type'  => 'select',
          'options' => array (
              'one' => array (
                  'label' => 'Föreningsansvarig',
                  'value' => 'Föreningsansvarig'
              ),
              'two' => array (
                  'label' => 'Lagledare',
                  'value' => 'Lagledare'
              ),
              'three' => array (
                  'label' => 'Säljare',
                  'value' => 'Säljare'
              )
          )
      )
  );


  $labels = array(
    'name'               => __( 'Välkomsttexter'),
    'singular_name'      => __( 'Välkomsttext'),
    'add_new'            => __( 'Skriv välkomsttext' ),
    'add_new_item'       => __( 'Skriv välkomsttext' ),
    'edit_item'          => __( 'Uppdatera välkomsttext' ),
    'new_item'           => __( 'Ny välkomsttext' ),
    'all_items'          => __( 'Välkomsttexter' ),
    'view_item'          => __( 'Se välkomsttext' ),
    'search_items'       => __( '' ),
    'not_found'          => __( '' ),
    'not_found_in_trash' => __( '' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Välkomsttexter'
  );
  $args = array(
    'labels'              => $labels,
    'description'         => 'Välkomsttexter',
    'public'              => true,
    'publicly_queryable'  => false,
    'menu_position'       => 5,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'supports'            => array('editor' )
  );
  register_post_type( 'welcome', $args );
}
add_action( 'init', 'custom_post_welcome' );




// Add the Meta Box
function add_custom_meta_box_welcome() {
    add_meta_box(
      'welcome_metabox', // $id
      'Användare', // $title
      'show_custom_meta_box', // $callback
      'welcome', // $page
      'normal', // $context
      'high'); // $priority
}
add_action('add_meta_boxes_welcome', 'add_custom_meta_box_welcome');





// ADD NEW COLUMN
function welcome_columns_head($defaults) {
  $defaults['welcome_type'] = 'För vilken typ av användare';
  return $defaults;
}

function welcome_columns_contents($column_name, $post_ID) {
    if ($column_name == 'welcome_type') {
      echo get_post_meta ( $post_ID, 'type', true );
    }
}

add_filter('manage_welcome_posts_columns', 'welcome_columns_head');
add_action('manage_welcome_posts_custom_column', 'welcome_columns_contents', 10, 2);



//
// function disable_new_faq_posts() {
//
//   $args = array(
//   'post_type' => 'faq',
//   'posts_per_page' => -1,
//   );
//
//   $query = new WP_Query($args);
//   if($query->post_count > 0){
//     remove_submenu_page( 'edit.php?post_type=faq', 'post-new.php?post_type=faq' );
//   }
//   else {
//     remove_submenu_page( 'edit.php?post_type=faq', 'edit.php?post_type=faq' );
//   }
// }
// add_action('admin_menu', 'disable_new_faq_posts');
//
//
//
//
//
// function hide_buttons_faq(){
//   global $current_screen;
//   // var_dump($current_screen->id);
//   if($current_screen->id == 'edit-faq' || $current_screen->id == 'faq'){
//     $args = array(
//     'post_type' => 'faq',
//     'posts_per_page' => -1,
//     );
//
//     $query = new WP_Query($args);
//     if(!empty($query->posts)){
//       echo '<style>.page-title-action{display: none;}';
//     }
//   }
//
// }
// add_action('admin_head','hide_buttons_faq');
