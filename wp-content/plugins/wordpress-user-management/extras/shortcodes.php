<?php










function parent_create_salesmen($updateId) {

  $edit_user_ids = get_the_author_meta('userids', get_current_user_id());
  // var_dump($edit_user_ids);
  // var_dump(get_current_user_id());
	$users = get_users(array(
			'orderby' => 'display_name',
      'include' => $edit_user_ids,
		)
	);

  $teams = [];
  foreach($users as $user){
    if(!in_array(get_user_meta($user->ID, 'team', true), $teams)){
      $teams[] = get_user_meta($user->ID, 'team', true);
    }
  }

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
      if($teams && !empty($teams[0])){
        $form .= '<label for="">Välj lag:</label>';
        $form .=' <select class="" name="team">
            <option value="" disabled="disabled" selected="selected">Välj lag</option>';

        foreach($teams as $team) :

          if (empty($team)) {
            $selected = $updateId != '' && get_user_meta($userToUpdate->ID, 'team', true) == $team ? 'selected' : '';
            $form .= '<option value="'.$team.'" '.$selected.'>'.$team.'</option>';
          }

        endforeach;

        $form .= '
        </select>';
      }
    }

    $form .= get_current_user_role() == 'manager' ? '<input type="hidden" name="prinskorv" value="lightbulb">' : '';

    $form .= $updateId != '' ? '<input type="hidden" name="antalKorvar" value="'.$userToUpdate->ID.'">' : '';


    $form .= '
      <label for="">Användarnamn</label>
      <input type="text" name="user_login" value="'.($updateId != '' ? $userToUpdate->user_login : '').'">

      <label for="">E-postadress</label>
      <input type="text" name="user_email" value="'.($updateId != '' ? $userToUpdate->user_email : '').'">

      <label for="">Förnamn</label>
      <input type="text" name="first_name" value="'.($updateId != '' ? $userToUpdate->first_name : '').'">

      <label for="">Efternamn</label>
      <input type="text" name="last_name" value="'.($updateId != '' ? $userToUpdate->last_name : '').'">

      <label for="">Adress</label>
      <input type="text" name="address" value="'.($updateId != '' ? get_user_meta($userToUpdate->ID, 'address', true) : '').'">

      <label for="">Postnummer</label>
      <input type="text" name="zip" value="'.($updateId != '' ? get_user_meta($userToUpdate->ID, 'zip', true) : '').'">

      <label for="">Stad</label>
      <input type="text" name="city" value="'.($updateId != '' ? get_user_meta($userToUpdate->ID, 'city', true) : '').'">

      <label for="">Telefonnummer</label>
      <input type="text" name="phone" value="'.($updateId != '' ? get_user_meta($userToUpdate->ID, 'phone', true) : '').'">

      <input type="submit" value="'.($updateId != '' ? 'Uppdatera' : 'Skapa').'" class="'.($updateId != '' ? 'doUpdateUser' : 'doCreateUser').'">
    </form>';

  }

  if($updateId == ''){
    $form .= '<form class="" action="'.admin_url('admin-ajax.php').'" method="post" enctype="multipart/form-data">
      '.wp_nonce_field('create_user_by_excel','ny-korvare').'
      <input name="action" value="create_user_by_excel" type="hidden">
    	Skapa användare med excelfil: <input type="file" name="file" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
    	<input type="submit" name="submit" value="Submit" class="doUploadExcel" />
    </form>';
  }

  return $form;
}
add_shortcode('getCreateFormSalesmen', 'parent_create_salesmen');











function update_logged_in_user_profile() {

  $userInfo = get_userdata( get_current_user_id() );

  $form = '<form class="" action="" method="post">';

  // $form .= get_current_user_role() == 'manager' ? '<input type="hidden" name="prinskorv" value="lightbulb">' : '';


  $form .= '

    <div class="input-group">
      <label for="">E-postadress</label>
      <input type="text" name="user_email" class="form-control" value="'.$userInfo->user_email.'">
    </div>

    <div class="input-group">
    <label for="">Förnamn</label>
    <input type="text" name="first_name" class="form-control" value="'.$userInfo->first_name.'">
    </div>
    <div class="input-group">
    <label for="">Efternamn</label>
    <input type="text" name="last_name" class="form-control" value="'.$userInfo->last_name.'">
    </div>
    <div class="input-group">
    <label for="">Adress</label>
    <input type="text" name="address" class="form-control" value="'.get_user_meta($userInfo->ID, 'address', true).'">
    </div>
    <div class="input-group">
    <label for="">Postnummer</label>
    <input type="text" name="zip" class="form-control" value="'.get_user_meta($userInfo->ID, 'zip', true).'">
    </div>
    <div class="input-group">
    <label for="">Stad</label>
    <input type="text" name="city" class="form-control" value="'.get_user_meta($userInfo->ID, 'city', true).'">
    </div>
    <div class="input-group">
    <label for="">Telefonnummer</label>
    <input type="text" name="phone" class="form-control" value="'.get_user_meta($userInfo->ID, 'phone', true).'">
    </div>

    <div class="input-group">
    <label for="">Lösenord</label>
    <input type="password" name="password" class="form-control" value="">
    </div>
    <div class="input-group">
    <label for="">Repetera lösenord</label>
    <input type="password" name="password2" class="form-control" value="">
    </div>


    <div class="input-group">
    <label for="">Nuvarande lösenord</label>
    <input type="password" name="passwordCurrent" class="form-control" value="">
    </div>


    <input type="submit" value="Uppdatera" class="doUpdateLoggedInUser">
  </form>';

  echo $form;
}
add_shortcode('getUpdateLoggedInUserForm', 'update_logged_in_user_profile');
