<?php
/**
* Plugin Name: Exportera till excel
* Plugin URI:
* Description: Exportera användare
* Version: 1.0
* Author: Andy Leverenz
* Author URI: http://justalever.com/
**/

function enqueue_excelscript() {
  wp_enqueue_script( 'adminExcel',  '/wp-content/themes/wintherperson/assets/scripts/admin-scripts/adminExcel.js');
}
add_action( 'admin_enqueue_scripts', 'enqueue_excelscript' );


add_action('admin_menu', 'admin_export_to_excel_menu');
function admin_export_to_excel_menu() {
	add_menu_page('Exportera till excel', 'Exportera till excel', 'administrator', 'exportera-till-excel', 'admin_export_to_excel', 5);
}

function admin_export_to_excel() {

  if( isset( $_GET[ 'tab' ] ) ) {
    $active_tab = $_GET[ 'tab' ];
  }
  else {
    $active_tab = 'foreningar';
  }

  if($active_tab === 'foreningar'){
    $args  = array(
    // search only for Authors role
    'role' => 'associationDelegate',
    // order results by display_name
    'orderby' => 'display_name',
    // // check for two meta_values
    // 'meta_query' => array(
    //     array(
    //         // uses compare like WP_Query
    //         'key' => 'some_user_meta_key',
    //         'value' => 'some user meta value',
    //         'compare' => '>'
    //         ),
    //     array(
    //         // by default compare is '='
    //         'key' => 'some_other_user_meta_key',
    //         'value' => 'some other meta value',
    //         ),
        // add more
    // )
    );
  }
  else {
    $args  = array(
    // search only for Authors role
    'role' => 'manager',
    // order results by display_name
    // 'orderby' => 'display_name',
    // // check for two meta_values
    // 'meta_query' => array(
    //     array(
    //         // uses compare like WP_Query
    //         'key' => 'associationDelegateParentId',
    //         'value' => 'null',
    //         // 'compare' => '='
    //         ),
    //     array(
    //         // by default compare is '='
    //         'key' => 'some_other_user_meta_key',
    //         'value' => 'some other meta value',
    //         ),
        // add more
      // )
    );
  }

  $wp_user_query = new WP_User_Query($args);
  $resLeaders = $wp_user_query->get_results();
  ?>
  <div class="wrap">
  <h2>Exportera till excel</h2>

  <h2 class="nav-tab-wrapper">
      <a href="?page=exportera-till-excel&tab=foreningar" class="nav-tab <?php echo $active_tab == 'foreningar' ? 'nav-tab-active' : ''; ?>">Lag kopplade till förening</a>
      <a href="?page=exportera-till-excel&tab=lag" class="nav-tab <?php echo $active_tab == 'lag' ? 'nav-tab-active' : ''; ?>">Fristående lag</a>
  </h2>





  <?php if($active_tab === 'foreningar') : ?>
  <form method="post" action="">
    <?php wp_nonce_field( 'admin_export_to_excel_association', 'forening'); ?>

    <select name="assocation" onchange="this.form.submit()">
      <option value="" disabled="" selected="">Välj en förening</option>
      <?php foreach ($resLeaders as $resLeader) :?>
        <option value="<?php echo $resLeader->ID; ?>" <?php echo isset($_POST['assocation']) && $_POST['assocation'] == $resLeader->ID ? 'selected' : ''; ?>><?php echo get_user_meta($resLeader->ID, 'association', true); ?></option>
      <?php endforeach;?>
    </select>

    <?php if(isset($_POST['assocation']) && is_numeric($_POST['assocation'])) : ?>
      <?php
        $args  = array(
          'role' => 'manager',
          'meta_query' => array(
            array(
                'key' => 'associationDelegateParentId',
                'value' => $_POST['assocation'],
                ),
          )
        );
        $wp_user_query = new WP_User_Query($args);
        $resLeaders = $wp_user_query->get_results();
      ?>
      <select id="association-team" name="association-team">
        <option value="" disabled="" selected="">Välj ett lag</option>
        <?php foreach ($resLeaders as $resLeader) :?>
          <option value="<?php echo $resLeader->ID; ?>"><?php echo get_user_meta($resLeader->ID, 'team', true); ?></option>
        <?php endforeach;?>
      </select>
    <?php endif; ?>


  </form>

  <?php echo admin_export_to_excel_association(); ?>








  <?php else: ?>

    <form method="post" action="">

      <select name="teams">
        <option value="" disabled="" selected="">Välj ett lag</option>
        <?php foreach ($resLeaders as $resLeader) :?>
            <?php if(get_user_meta($resLeader->ID, 'associationDelegateParentId', true) == ''): ?>
              <option value="<?php echo $resLeader->ID; ?>"><?php echo get_user_meta($resLeader->ID, 'team', true); ?></option>
            <?php endif; ?>
        <?php endforeach;?>
      </select>


    </form>


  <?php endif; ?>



  </div>
  <?php
}





function admin_export_to_excel_association(){
  $html = '';
  if(isset($_POST['assocation']) && is_numeric($_POST['assocation']) && wp_verify_nonce($_POST['forening'],'admin_export_to_excel_association')){
    $users = explode(',', get_user_meta($_POST['assocation'], 'userids', true));
    dump($users);
  }

  return $html;
}

function admin_export_to_excel_team(){

  if(isset($_POST['team']) && is_numeric($_POST['assocation']) && wp_verify_nonce($_POST['forening'],'admin_export_to_excel_association')){
    return do_shortcode('[get-team team="'.$_POST['team'].'" useridsstring="'.$useridsString.'"]');
  }
}
