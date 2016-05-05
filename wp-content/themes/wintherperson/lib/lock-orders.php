<?php


function custom_post_lock_orders() {
  global $custom_meta_fields;
  $prefix = 'lock-orders_';
  $custom_meta_fields['lock-orders'] = array(
      array(
          'label'=> 'Datum',
          'desc'  => '',
          'id'    => 'lock-orders',
          'class' => '',
          'name' => 'date',
          'type'  => 'text'
      ),
      // array(
      //     'label'=> 'Textarea',
      //     'desc'  => 'A description for the field.',
      //     'id'    => $prefix.'textarea',
      //     'type'  => 'textarea'
      // ),
      // array(
      //     'label'=> 'Checkbox Input',
      //     'desc'  => 'A description for the field.',
      //     'id'    => $prefix.'checkbox',
      //     'type'  => 'checkbox'
      // ),
      // array(
      //     'label'=> 'Select Box',
      //     'desc'  => 'A description for the field.',
      //     'id'    => $prefix.'select',
      //     'type'  => 'select',
      //     'options' => array (
      //         'one' => array (
      //             'label' => 'Option One',
      //             'value' => 'one'
      //         ),
      //         'two' => array (
      //             'label' => 'Option Two',
      //             'value' => 'two'
      //         ),
      //         'three' => array (
      //             'label' => 'Option Three',
      //             'value' => 'three'
      //         )
      //     )
      // )
  );


  $labels = array(
    'name'               => __( 'Lås beställningar'),
    'singular_name'      => __( 'Lås beställningar'),
    'add_new'            => __( 'Ange när beställningar ska låsas' ),
    'add_new_item'       => __( 'Ange när beställningar ska låsas' ),
    'edit_item'          => __( 'Uppdatera när beställningar ska låsas' ),
    'new_item'           => __( 'Ny låsning' ),
    'all_items'          => __( 'Ange när beställningar ska låsas' ),
    'view_item'          => __( 'Se när beställningar ska låsas' ),
    'search_items'       => __( 'Sök när beställningar ska låsas' ),
    'not_found'          => __( 'Tid när beställningar ska låsas har inte angivits' ),
    'not_found_in_trash' => __( 'Inga låsningar finns i papperskorgen' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Lås beställningar'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Innehåller information när beställningar ska låsas',
    'public'        => true,
    'publicly_queryable'  => false,
    'menu_position' => 5,
    'supports'      => array(''),
    'has_archive'   => false,
    'exclude_from_search' => true,
  );
  register_post_type( 'lock-orders', $args );
}
add_action( 'init', 'custom_post_lock_orders' );



// Add the Meta Box
function add_custom_meta_box_lock_orders() {
    add_meta_box(
      'lock-orders_metabox', // $id
      'Beställningar låses', // $title
      'show_custom_meta_box', // $callback
      'lock-orders', // $page
      'normal', // $context
      'high'); // $priority
}
add_action('add_meta_boxes', 'add_custom_meta_box_lock_orders');




function disable_new_posts() {
  $args = array(
  'post_type' => 'lock-orders',
  'posts_per_page' => -1,
  );

  $query = new WP_Query($args);
  if(!empty($query->posts)){
    remove_submenu_page( 'edit.php?post_type=lock-orders', 'post-new.php?post_type=lock-orders' );
  }
  else {
    remove_submenu_page( 'edit.php?post_type=lock-orders', 'edit.php?post_type=lock-orders' );
  }
}
add_action('admin_menu', 'disable_new_posts');




add_filter( 'wp_insert_post_data' , 'modify_post_title' , '99', 2 );

function modify_post_title( $data , $postarr ){
  // var_dump($data);
  if ($data['post_status'] == 'trash' || $data["post_status"] == "auto-draft") { return $data; }

  if($data['post_type'] == 'lock-orders') {
    if(isset($data['ID'])){
      $data['post_title'] = get_post_meta(sanitize_text_field($data['date']), 'date', true);
    }
    else {
      $data['post_title'] = sanitize_text_field($postarr['date']);
    }
    return $data;
  }
  else {
    return $data;
  }
}



function hide_buttons(){
  global $current_screen;

  if($current_screen->id == 'edit-lock-orders' || $current_screen->id == 'lock-orders'){
    $args = array(
    'post_type' => 'lock-orders',
    'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    if(!empty($query->posts)){
      echo '<style>.page-title-action{display: none;}';
    }
  }

}
add_action('admin_head','hide_buttons');
