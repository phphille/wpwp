

<?php

// if (!class_exists('Social')) {
//   echo  plugins_url();
// 	// load Social if not already loaded
// 	include(plugins_url().'/wordpress-user-management/user-management.php');
// }
  $wp_user = new WP_User( get_current_user_id());
  $user = $wp_user->roles[0];

  if($user != 'associationDelegate' && $user != 'manager'){
    wp_redirect( 'hem-inloggad' );
  }

?>

<div class="row">
  <div class="col-md-4">
    <form class="" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">
      <?php wp_nonce_field('create_user_by_excel','ny-korvare')?>
      <input name="action" value="create_user_by_excel" type="hidden">
    	<!-- <input type="file" name="file" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" /> -->
    	<!-- <input type="submit" name="submit" value="Submit" class="doUploadExcel" /> -->
      <div class="fileUpload btn btn-primary">
        <span>Importera lag med excel</span>
        <input type="file" name="file" class="upload" id="create-user-excel" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
      </div>
    </form>

    <a class="btn btn-primary create-user-manually">Skapa användare manuellt</a>
  </div>
  <div class="col-md-8">
    <?php the_content(); ?>
  </div>
</div>

<div class="row">

  <div class="col-sm-12 createFormUser">

    <?php echo do_shortcode('[getCreateFormUser]'); ?>
  </div>
</div>
