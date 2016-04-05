<?php
/*
Controller name: User
Controller description: Create/Update/Delete user data
*/

class JSON_API_User_Controller {

  function login_user() {
    global $json_api;
    require_once(ABSPATH.'wp-admin/includes/user.php' );

    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);

    $username       = $query['username'];
    $password       = $query['password'];

    $login_user = wp_login( $username, $password, '');
    // login user
    if ( ! is_wp_error( $login_user ) ) {
      return $login_user;
    } else {
      return $login_user;
    }
  }



  function create_user() {
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);
    $loggedInUser = new WP_User( get_current_user_id());
    $loggedInUserRole = $loggedInUser->roles[0];

    $user_login       = sanitize_text_field($query['user_login']);
    $user_pass        = isset($query['user_pass']) ? sanitize_text_field($query['user_pass']) : wp_generate_password(8);
    $user_email       = sanitize_text_field($query['user_email']);
    $first_name       = sanitize_text_field($query['first_name']);
    $last_name        = sanitize_text_field($query['last_name']);
    $_phone           = sanitize_text_field($query['phone']);
    $_address         = sanitize_text_field($query['address']);
    $_zip             = sanitize_text_field($query['zip']);
    $_city            = sanitize_text_field($query['city']);
    $_team            = sanitize_text_field($query['team']);
    $role             = sanitize_text_field($query['prinskorv']);

    $userdata = array(
  		'user_login'	 =>	$user_login,
      'user_pass'    => $user_pass,
      'user_email'	 =>	$user_email,
      'first_name'	 =>	$first_name,
      'last_name'	   =>	$last_name,
      'user_registered' => date("Y-m-d H:i:s"),
      'role'         =>  $role == 'tjock' ? 'manager' : 'salesperson',
    );


    if (isset($user_login) && !empty($user_login) && isset($user_pass) && !empty($user_pass) && isset($user_email) && !empty($user_email) && isset($first_name) && !empty($first_name) && isset($last_name) && !empty($last_name) && isset($_phone) && !empty($_phone)) {
      // create new user
      $createdUserID = wp_insert_user( $userdata );
      if ( ! is_wp_error( $createdUserID ) ) {
        update_user_meta( $createdUserID,'phone',  $_phone  );
        update_user_meta( $createdUserID,'address', $_address );
        update_user_meta( $createdUserID,'zip', $_zip );
        update_user_meta( $createdUserID,'city', $_city );
    		update_user_meta( $createdUserID,'team',  $_team  );
        if($role == 'manager'){
          update_user_meta( $createdUserID, 'associationDelegateParentId',  get_current_user_id() );
        }

        //Lägg till användaren till den inloggades userIds görs i user management myplugin_registration_save

        //Om en föreningsansvarig skapat en säljare, uppdatera säljarens lagledares userids
        if($role == 'salesperson' && $loggedInUserRole == 'associationDelegate'){
          $editableIds = explode(',', get_user_meta(get_current_user_id(), 'userids', true));
          foreach ($editableIds as $value) {
            $user = new WP_User( $value);
            if (isset($user->roles[0]) && $user->roles[0] == 'manager' && get_user_meta($value, 'team', true) == $_team) {
              $editableIds = get_user_meta($value, 'userids', true) == '' ? $createdUserId : get_user_meta($value, 'userids', true).','.$createdUserId;
              update_user_meta( $value, 'userids', $editableIds);
              //Lägg till lagledarens id på säljaren
              update_user_meta( $createdUserID, 'managerParentId',  $value );
              //Lägg till föreningsansvarigs id på säljaren
              update_user_meta( $createdUserID, 'associationDelegateParentId',  get_current_user_id() );
            }
          }
        }

        //Om en lagledare skapat en säljare, kolla om säljare är kopplad till en föreningsansvarig och i så fall uppdatera föreningsansvarig userids
        if($role == 'salesperson' && $loggedInUserRole == 'manager'){
          //Lägg till lagledarens id på säljaren
          update_user_meta( $createdUserID, 'managerParentId',  get_current_user_id() );

          $associationDelegateParentId = get_user_meta(get_current_user_id(), 'associationDelegateParentId', true);
          if(!empty($associationDelegateParentId)){
            $editableIds = get_user_meta($associationDelegateParentId, 'userids', true) == '' ? $createdUserId : get_user_meta($associationDelegateParentId, 'userids', true).','.$createdUserId;
            update_user_meta( get_current_user_id(), 'userids', $editableIds);
            //Lägg till föreningsansvarigs id på säljaren
            update_user_meta( $createdUserID, 'associationDelegateParentId',  $associationDelegateParentId );
          }
        }
        return true;
      }
      else {
      	return $createdUserID;
      }
    }
    else {
      return false;
    }
  }




  function update_user() {
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);


    $user_login       = sanitize_text_field($query['user_login']);
    // $user_pass        = isset($query['user_pass']) ? sanitize_text_field($query['user_pass']) : wp_generate_password(8);
    $user_email       = sanitize_text_field($query['user_email']);
    $first_name       = sanitize_text_field($query['first_name']);
    $last_name        = sanitize_text_field($query['last_name']);
    $_phone           = sanitize_text_field($query['phone']);
    $_address         = sanitize_text_field($query['address']);
    $_zip             = sanitize_text_field($query['zip']);
    $_city            = sanitize_text_field($query['city']);
    $_team            = sanitize_text_field($query['team']);
    $role             = sanitize_text_field($query['prinskorv']);
    $userIdToUpdate   = sanitize_text_field($query['antalKorvar']);


    $editableIds = explode(',', get_user_meta(get_current_user_id(), 'userids', true));

    $userdata = array(
      'ID'           => (in_array($userIdToUpdate, $editableIds) && is_numeric($userIdToUpdate) ? $userIdToUpdate : '' ),
  		'user_login'	 =>	$user_login,
      // 'user_pass'    => $user_pass,
      'user_email'	 =>	$user_email,
      'first_name'	 =>	$first_name,
      'last_name'	   =>	$last_name,
      'role'         => ($role == 'tjock' ? 'manager' : 'salesperson'),
    );



    // update user
    $updatedUserID = wp_update_user( $userdata );
    if ( ! is_wp_error( $updatedUserID ) ) {
      $prevTeamVal = $userdata['role'] == 'manager' ? get_user_meta($userIdToUpdate, 'team', true) : '';

      update_user_meta( $userIdToUpdate,'phone',  $_phone  );
      update_user_meta( $userIdToUpdate,'address', $_address );
      update_user_meta( $userIdToUpdate,'zip', $_zip );
      update_user_meta( $userIdToUpdate,'city', $_city );
      update_user_meta( $userIdToUpdate,'team',  $_team  );

      if($userdata['role'] == 'manager' && $prevTeamVal != $_team ){
        $editableIds = explode(',', get_user_meta($userIdToUpdate, 'userids', true));
        foreach ($editableIds as $user ) {
          update_user_meta( $userIdToUpdate,'team',  $_team  );
        }
      }

      return true;
    } else {
      return $update_user;
    }
  }



  function delete_user() {
    global $json_api;
    require_once(ABSPATH.'wp-admin/includes/user.php' );
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);
    $users = $query['user'];

    $editableIds = explode(',', get_user_meta(get_current_user_id(), 'userids', true));
    $stop = false;
    foreach ($users as $user) {
      if(!is_numeric($user) || !in_array($user, $editableIds))
        $stop = true;
    }

    // var_dump($users);
    // $parentIds = [];
    // $parentIds[] = get_user_meta($users[0], 'associationDelegateParentId', true);
    // var_dump(get_user_meta($parentIds[0], 'userids', true));
    // $ids = explode(',', get_user_meta($parentIds[0], 'userids', true) );
    // var_dump('innan');
    // var_dump($ids);
    // if(($key = array_search($user, $ids)) !== false) {
    //   unset($ids[$key]);
    //   var_dump('efter');
    //   var_dump(implode(',', $ids));
    //   // update_user_meta($parentId[0], 'userids', implode(',', $ids));
    // }

    if(!$stop){
      $res = [];
      foreach ($users as $user) {
        //remove id from parents userids
        $parentIds = [];
        $parentIds[] = get_user_meta($user, 'associationDelegateParentId', true);
        $parentIds[] = get_user_meta($user, 'managerParentId', true);

        foreach ($parentIds as $parentId) {
          if(is_numeric($parentId)){
            $ids = explode(',', get_user_meta($parentId, 'userids', true));

            if(($key = array_search($user, $ids)) !== false) {
              unset($ids[$key]);
              update_user_meta($parentId, 'userids', implode(',', $ids));
            }
          }
        }


        //delete user
        $delete_user = wp_delete_user( $user );
        if ( ! is_wp_error( $user ) ) {
          $res[] = $delete_user;
        } else {
          $res[] = $delete_user;
        }

      }

      return $res;
    }
  }





  function update_loggedin_user() {
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);


    // $user_login       = sanitize_text_field($query['user_login']);
    $user_pass        = isset($query['user_pass']) ? sanitize_text_field($query['user_pass']) : null;
    $user_email       = sanitize_text_field($query['user_email']);
    $first_name       = sanitize_text_field($query['first_name']);
    $last_name        = sanitize_text_field($query['last_name']);
    $_phone           = sanitize_text_field($query['phone']);
    $_address         = sanitize_text_field($query['address']);
    $_zip             = sanitize_text_field($query['zip']);
    $_city            = sanitize_text_field($query['city']);



    $userdata = array(
      'ID'           => get_current_user_id(),
  		// 'user_login'	 =>	$user_login,
      'user_pass'    => $user_pass,
      'user_email'	 =>	$user_email,
      'first_name'	 =>	$first_name,
      'last_name'	   =>	$last_name,
    );



    // update user
    $updatedUserID = wp_update_user( $userdata );
    if ( ! is_wp_error( $updatedUserID ) ) {
      update_user_meta( $updatedUserID,'phone',  $_phone  );
      update_user_meta( $updatedUserID,'address', $_address );
      update_user_meta( $updatedUserID,'zip', $_zip );
      update_user_meta( $updatedUserID,'city', $_city );
      update_user_meta( $updatedUserID,'team',  $_team  );
      return true;
    } else {
      return $update_user;
    }
  }


  function delete_loggedin_user() {
    global $json_api;
    require_once(ABSPATH.'wp-admin/includes/user.php' );
    $current_user = wp_get_current_user();

    // delete user
    $delete_user = wp_delete_user( $current_user->ID );
    if ( ! is_wp_error( $delete_user ) ) {
      return $delete_user;
    } else {
      return $delete_user;
    }
  }



}

?>
