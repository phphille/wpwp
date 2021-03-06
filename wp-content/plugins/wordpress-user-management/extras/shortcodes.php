<?php










function create_user_form($updateId) {

  // $edit_user_ids = get_the_author_meta('userids', get_current_user_id());
  // // var_dump($edit_user_ids);
  // // var_dump(get_current_user_id());
	// $users = get_users(array(
	// 		'orderby' => 'display_name',
  //     'include' => $edit_user_ids,
	// 	)
	// );

  if(isset($updateId['updateid'])){
    $userToUpdate = get_userdata( $updateId['updateid'] );
  }

  if(isset($updateId['updateid']) && !$userToUpdate){
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

    $form = '<form class="" action="" method="post">';

    if(get_current_user_role() == 'associationDelegate'){
      $args  = array(
      'role' => 'manager',
      'meta_query' => array(
          array(
              // uses compare like WP_Query
              'key' => 'associationDelegateParentId',
              'value' => get_current_user_id(),
              ),
        )
      );
      $wp_user_query = new WP_User_Query($args);
      $resManagers = $wp_user_query->get_results();
      // dump($resManagers);

      $form .= '<select class="" name="prinskorv">
          <option value="" disabled="disabled" selected="selected">Välj användartyp</option>
          <option value="tjock">Lagledare</option>';
          if($resManagers && !empty($resManagers[0])){
            $form .= '<option value="smal">Säljare</option>';
          }
      $form .= '</select>';

      if($resManagers && !empty($resManagers[0])){
        $form .= '<label for="">Välj lag:</label>';
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
        </select>';
      }

      $form .= '<div class="checkbox">
                  <label class="toggle_create_user_new_team"><input type="checkbox" value="1" name="create_user_new_team">Skapa nytt lag</label>
                </div>
                <div class="create_user_new_team displayNoneOnInit" >
                  <label for="">Nytt lagnamn</label>
                  <input type="text" name="new_team" value="">
                </div>';


    }

    $form .= get_current_user_role() == 'manager' ? '<input type="hidden" name="prinskorv" value="smal">' : '';

    $form .= $updateId != '' ? '<input type="hidden" name="antalKorvar" value="'.$userToUpdate->ID.'">' : '';


    $form .= '
      <div class="form-group">
        <label for="">Användarnamn</label>
        <input type="text" class="form-control" name="user_login" value="'.($updateId != '' ? $userToUpdate->user_login : '').'" placeholder="Användarnamn">
      </div>

      <div class="form-group">
        <label for="">E-postadress</label>
        <input type="text" class="form-control" name="user_email" value="'.($updateId != '' ? $userToUpdate->user_email : '').'" placeholder="E-postadress">
      </div>

      <div class="form-group">
        <label for="">Förnamn</label>
        <input type="text" class="form-control" name="first_name" value="'.($updateId != '' ? $userToUpdate->first_name : '').'" placeholder="Förnamn">
      </div>

      <div class="form-group">
        <label for="">Efternamn</label>
        <input type="text" class="form-control" name="last_name" value="'.($updateId != '' ? $userToUpdate->last_name : '').'" placeholder="Efternamn">
      </div>

      <div class="form-group">
        <label for="">Adress</label>
        <input type="text" class="form-control" name="address" value="'.($updateId != '' ? get_user_meta($userToUpdate->ID, 'address', true) : '').'" placeholder="Adress">
      </div>

      <div class="form-group">
        <label for="">Postnummer</label>
        <input type="text" class="form-control" name="postalcode" value="'.($updateId != '' ? get_user_meta($userToUpdate->ID, 'zip', true) : '').'" placeholder="Postnummer">
      </div>

      <div class="form-group">
        <label for="">Stad</label>
        <input type="text" class="form-control" name="city" value="'.($updateId != '' ? get_user_meta($userToUpdate->ID, 'city', true) : '').'" placeholder="Stad">
      </div>

      <div class="form-group">
        <label for="">Telefonnummer</label>
        <input type="text" class="form-control" name="phone" value="'.($updateId != '' ? get_user_meta($userToUpdate->ID, 'phone', true) : '').'" placeholder="Telefonnummer">
      </div>

      <input type="submit" value="'.($updateId != '' ? 'Uppdatera' : 'Skapa').'" class="'.($updateId != '' ? 'doUpdateUser' : 'doCreateUser').'">
    </form>';

  }

  // if($updateId == ''){
  //   $form .= '<form class="" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">
  //     '.wp_nonce_field('create_user_by_excel','ny-korvare').'
  //     <input name="action" value="create_user_by_excel" type="hidden">
  //   	Skapa användare med excelfil: <input type="file" name="file" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
  //   	<input type="submit" name="submit" value="Submit" class="doUploadExcel" />
  //   </form>';
  // }

  return $form;
}
add_shortcode('getCreateFormUser', 'create_user_form');
