<?php


function custom_post_company() {
  global $custom_meta_fields;
  $prefix = 'company_';
  $custom_meta_fields['companies'] = array(
      array(
          'label'=> 'Org. nr',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyOrgNbr',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Adress',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyAddress',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Postnummer',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyPostalcode',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Stad',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyCity',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Epostadress företaget',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyContactMail',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Kontaktperson - företag',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'heading',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Förnamn',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyContactFirstname',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Efternamn',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyContactLastname',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Telefon',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyContactPhone',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Kontaktperson - utkörning',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'heading',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Förnamn',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyDeliveryFirstname',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Efternamn',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyDeliveryLastname',
          'type'  => 'text'
      ),
      array(
          'label'=> 'Telefon',
          'desc'  => '',
          'id'    => '',
          'class' => '',
          'name' => 'companyDeliveryPhone',
          'type'  => 'text'
      ),
      // array(
      //     'label'=> 'Textarea',
      //     'desc'  => 'A description for the field.',
      //     'id'    => $prefix.'textarea',
      //     'type'  => 'textarea'
      // ),
      array(
          'label'=> 'Vill ha utkörning',
          'desc'  => '',
          'id'    => $prefix.'checkbox',
          'name' => 'companyWantDelivery',
          'type'  => 'checkbox'
      ),
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
    'name'               => __( 'Företag'),
    'singular_name'      => __( 'Företag'),
    'add_new'            => __( 'Lägg till ny' ),
    'add_new_item'       => __( 'Lägg till nytt företag' ),
    'edit_item'          => __( 'Uppdatera företag' ),
    'new_item'           => __( 'Nytt företag' ),
    'all_items'          => __( 'Alla företag' ),
    'view_item'          => __( 'Se företag' ),
    'search_items'       => __( 'Sök företag' ),
    'not_found'          => __( 'Inga företag funna' ),
    'not_found_in_trash' => __( 'Inga företag finns i papperskorgen' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Företag'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Innehåller alla företag och dess information',
    'public'        => true,
    'publicly_queryable'  => false,
    'menu_position' => 5,
    'supports'      => array( 'title' ),
    'has_archive'   => true,
  );
  register_post_type( 'companies', $args );
}
add_action( 'init', 'custom_post_company' );



// Add the Meta Box
function add_custom_meta_box_company() {
    add_meta_box(
        'company_metabox', // $id
        'Info', // $title
        'show_custom_meta_box', // $callback
        'companies', // $page
        'normal', // $context
        'high'); // $priority
}
add_action('add_meta_boxes', 'add_custom_meta_box_company');
