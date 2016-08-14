

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
<?php if(!isset($_GET['user'])) : ?>
<div id="createuserbtns" class="row">
  <div class="col-md-4">
    <form class="createusers_form" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">
      <?php wp_nonce_field('create_user_by_excel','ny-korvare')?>
      <input name="action" value="create_user_by_excel" type="hidden">
    	<!-- <input type="file" name="file" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" /> -->
			<h3>Manuell registrering</h3>
			<p>Du kan skapa användare en i taget med hjälp av vårt formulär. Effektivt om du inte behöver skapa så många användare</p>
      <a class="create-user-manually btn btn-primary">Skapa användare manuellt</a>
			
			<h3>Excel-registrering</h3>
			<p>Har du många användare som ska skapas kan det vara mer effektivt att ta hjälp av ett excelark. Läs till höger om hur du använder dig av denna funktion.</p>
			<div class="fileUpload btn btn-primary">
        <span>Importera lag med excel</span>
        <input type="file" name="file" class="upload" id="create-user-excel" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
      </div>
			<input class="uploadexcel_btn" type="submit" name="submit" value="Skicka excelark" class="doUploadExcel" /> 
			<p></p>
    </form>

  </div>
  <div class="col-md-8">
    <?php the_content(); ?>
  </div>
</div>
<?php endif; ?>

<div class="row">
  <div class="col-sm-5 createFormUser">
			<h3>Skapa en ny Användare</h3>
      <?php
      $updateId = isset($_GET['user']) && is_numeric($_GET['user']) ? $_GET['user'] : null;
      if($updateId){
        $userToUpdate = get_userdata( $updateId );
      }

      if(isset($updateId) && !$userToUpdate){
        $form = 'Användaren finns inte';
      }
      else{
        // $form = '<form class="" action="'.admin_url('admin-ajax.php').'" method="post">
        // '.wp_nonce_field('save_created_user','ny-korvare').'
        // <input name="action" value="save_created_user" type="hidden">
        // <input name="role" value="salesperson" type="hidden">
        //   <label for="">Välj lag:</label>
        //   <select class="" name="_new-team">
        //     <option value="" disabled="disabled" selected="selected">Välj lag</option>';

        $form = '<form class="createuser" action="" method="post">';

        if(!isset($_GET['user']) && get_current_user_role() == 'associationDelegate'){
          $args  = array(
          'role' => 'manager',
          'meta_query' => array(
              array(
                  'key' => 'associationDelegateParentId',
                  'value' => get_current_user_id(),
                  ),
            )
          );
          $wp_user_query = new WP_User_Query($args);
          $resManagers = $wp_user_query->get_results();
          // dump($resManagers);

          $form .= '<div class="input-group selectusertype">
              <select class="" name="prinskorv">
              <option value="" disabled="disabled" selected="selected">Välj användartyp att skapa</option>
              <option value="tjock">Lagledare</option>';
              if($resManagers && !empty($resManagers[0])){
                $form .= '<option value="smal">Säljare</option>';
              }
          $form .= '</select></div>';

          if($resManagers && !empty($resManagers[0])){
            $form .= '<div class="input-group">';
            $form .= '<label class="control-label" for="">Välj lag:</label>';
            $form .=' <select class="" name="team">
                <option value="" disabled="disabled" selected="selected">Välj lag</option>';

            foreach($resManagers as $manager) :

              // if (empty($team)) {
                $team = get_user_meta($manager->ID, 'team', true);
                $selected = $updateId != '' && get_user_meta($userToUpdate->ID, 'team', true) == $team ? 'selected' : '';
                $form .= '<option value="'.$manager->ID.'" '.$selected.'>'.$team.'</option>';
              // }

            endforeach;

            $form .= '
            </select>
            </div>
            <div class="">
              <div class="checkbox">
                <label class="toggle_create_user_new_team"><input type="checkbox" value="1" name="create_user_new_team">Skapa nytt lag</label>
              </div>
            </div>
            <div class="input-group create_user_new_team displayNoneOnInit" >
              <label for="">Nytt lagnamn</label>
              <input type="text" name="new_team" value="">
            </div>';

          }
        }
      }
      echo $form;
      ?>


        <?php   echo  get_current_user_role() == 'manager' ? '<input type="hidden" name="prinskorv" value="smal">' : ''; ?>

        <?php   echo $updateId != '' ? '<input type="hidden" name="antalKorvar" value="'.$userToUpdate->ID.'">' : ''; ?>

          <div class="form-group">
            <label for="">Nytt användarnamn</label>
            <input type="text" class="form-control" name="user_login" value="<?php echo $updateId != '' ? $userToUpdate->user_login : ''; ?>" placeholder="">
          </div>

          <div class="form-group">
            <label for="">E-postadress</label>
            <input type="text" class="form-control" name="user_email" value="<?php echo $updateId != '' ? $userToUpdate->user_email : ''; ?>" placeholder="">
          </div>

          <div class="form-group">
            <label for="">Förnamn</label>
            <input type="text" class="form-control" name="first_name" value="<?php echo $updateId != '' ? $userToUpdate->first_name : ''; ?>" placeholder="">
          </div>

          <div class="form-group">
            <label for="">Efternamn</label>
            <input type="text" class="form-control" name="last_name" value="<?php echo $updateId != '' ? $userToUpdate->last_name : ''; ?>" placeholder="">
          </div>

          <div class="form-group">
            <label for="">Adress</label>
            <input type="text" class="form-control" name="address" value="<?php echo $updateId != '' ? get_user_meta($userToUpdate->ID, 'address', true) : ''; ?>" placeholder="">
          </div>

          <div class="form-group">
            <label for="">Postnummer</label>
            <input type="text" class="form-control" name="postalcode" value="<?php echo $updateId != '' ? get_user_meta($userToUpdate->ID, 'zip', true) : ''; ?>" placeholder="">
          </div>

          <div class="form-group">
            <label for="">Stad</label>
            <input type="text" class="form-control" name="city" value="<?php echo $updateId != '' ? get_user_meta($userToUpdate->ID, 'city', true) : ''; ?>" placeholder="">
          </div>

          <div class="form-group">
            <label for="">Telefonnummer</label>
            <input type="text" class="form-control" name="phone" value="<?php echo $updateId != '' ? get_user_meta($userToUpdate->ID, 'phone', true) : ''; ?>" placeholder="">
          </div>

          <input type="submit" value="<?php echo $updateId != '' ? 'Uppdatera' : 'Skapa'; ?>" class="<?php echo $updateId != '' ? 'doUpdateUser' : 'doCreateUser'; ?>">
        </form>
<!--
      //   $form .= '<form class="" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">
      //     '.wp_nonce_field('create_user_by_excel','ny-korvare').'
      //     <input name="action" value="create_user_by_excel" type="hidden">
      //   	Skapa användare med excelfil: <input type="file" name="file" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
      //   	<input type="submit" name="submit" value="Submit" class="doUploadExcel" />
      //   </form> -->
  </div>
