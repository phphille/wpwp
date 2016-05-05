<?php


function my_custom_post_product() {
  global $custom_meta_fields;
  $prefix = 'product_';
  $custom_meta_fields['products'] = array(
      array(
          'label'=> 'Ange pris',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'pris',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Artikelnamn',
          'desc'  => 'Produktens artikelnamn, måste var unikt',
          'id'    => '',
          'class' => '',
          'name' => 'artikelnamn',
          'type'  => 'text'
      )
  );


  $labels = array(
    'name'               => __( 'Produkter'),
    'singular_name'      => __( 'Produkt'),
    'add_new'            => __( 'Lägg till ny'),
    'add_new_item'       => __( 'Lägg till ny produkt' ),
    'edit_item'          => __( 'Uppdatera produkt' ),
    'new_item'           => __( 'Ny produkt' ),
    'all_items'          => __( 'Alla Produkter' ),
    'view_item'          => __( 'Se produkter' ),
    'search_items'       => __( 'Sök produkter' ),
    'not_found'          => __( 'Inga produkter funna' ),
    'not_found_in_trash' => __( 'Inga produkter finns i papperskorgen' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Produkter'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Holds our products and product specific data',
    'public'        => true,
    'publicly_queryable'  => false,
    'menu_position' => 5,
    'supports'      => array( 'title', 'editor' ),
    'has_archive'   => true,
  );
  register_post_type( 'products', $args );
}
add_action( 'init', 'my_custom_post_product' );



// Add the Meta Box
function add_custom_meta_box_product() {
    add_meta_box(
        'product_metabox', // $id
        'Pris', // $title
        'show_custom_meta_box', // $callback
        'products', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'add_custom_meta_box_product');
