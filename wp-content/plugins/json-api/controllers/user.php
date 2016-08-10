<?php
/*
Controller name: User
Controller description: Create/Update/Delete user data
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    $nonce_id = $json_api->get_nonce_id('user', 'create_user');
    // var_dump($json_api->query->nonce);
    //
    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }

    $user_login       = sanitize_text_field($query['user_login']);
    $user_pass        = isset($query['user_pass']) ? sanitize_text_field($query['user_pass']) : wp_generate_password(8);
    $user_email       = sanitize_text_field($query['user_email']);
    $first_name       = sanitize_text_field($query['first_name']);
    $last_name        = sanitize_text_field($query['last_name']);
    $_phone           = sanitize_text_field($query['phone']);
    $_address         = sanitize_text_field($query['address']);
    $_zip             = sanitize_text_field($query['postalcode']);
    $_city            = sanitize_text_field($query['city']);
    $_team            = sanitize_text_field($query['team']);
    $role             = sanitize_text_field($query['prinskorv']) == 'tjock' || sanitize_text_field($query['prinskorv']) == 'smal' ? sanitize_text_field($query['prinskorv']) : false;
    $new_team_checked = isset($query['create_user_new_team']) ? sanitize_text_field($query['create_user_new_team']) : null;
    $new_team         = sanitize_text_field($query['new_team']);

    if($role == 'tjock')
      $role = 'manager';

    if($role == 'smal')
      $role = 'salesperson';


    $userdata = array(
  		'user_login'	 =>	$user_login,
      'user_pass'    => $user_pass,
      'user_email'	 =>	$user_email,
      'first_name'	 =>	$first_name,
      'last_name'	   =>	$last_name,
      'user_registered' => date("Y-m-d H:i:s"),
      'role'         =>  $role,
    );


    if (isset($user_login) &&
        !empty($user_login) &&
        isset($user_pass) &&
        !empty($user_pass) &&
        isset($user_email) &&
        !empty($user_email) &&
        isset($first_name) &&
        !empty($first_name) &&
        isset($last_name) &&
        !empty($last_name) &&
        isset($_phone) &&
        !empty($_phone)&&
        isset($role) &&
        !empty($role) ) {
      // create new user
      $createdUserID = wp_insert_user( $userdata );
      if ( ! is_wp_error( $createdUserID ) ) {
        update_user_meta( $createdUserID,'phone',  $_phone  );
        update_user_meta( $createdUserID,'address', $_address );
        update_user_meta( $createdUserID,'zip', $_zip );
        update_user_meta( $createdUserID,'city', $_city );
        if(isset($new_team_checked) && trim($new_team) != '' && $role == 'manager'){
    		  update_user_meta( $createdUserID,'team',  $new_team  );
        }
        else {
          update_user_meta( $createdUserID,'team',  $_team  );
        }

        if($role == 'manager'){
          update_user_meta( $createdUserID, 'associationDelegateParentId',  get_current_user_id() );
        }

        //Lägg till användaren till den inloggades userIds görs i user management myplugin_registration_save

        //Om en föreningsansvarig skapat en säljare, uppdatera säljarens lagledares userids
        if($role == 'salesperson' && $loggedInUserRole == 'associationDelegate'){
          $managerEditableIds = get_user_meta($_team, 'userids', true);
          $managerEditableIds = $managerEditableIds == '' ? $createdUserID : $managerEditableIds.','.$createdUserID;
          update_user_meta( $_team, 'userids', $managerEditableIds);
          //Lägg till lagledarens id på säljaren
          update_user_meta( $createdUserID, 'managerParentId',  $_team );
          //Lägg till föreningsansvarigs id på säljaren
          update_user_meta( $createdUserID, 'associationDelegateParentId',  get_current_user_id() );
        }

        //Om en lagledare skapat en säljare, kolla om säljare är kopplad till en föreningsansvarig och i så fall uppdatera föreningsansvarig userids
        if($role == 'salesperson' && $loggedInUserRole == 'manager'){
          //Lägg till lagledarens id på säljaren
          update_user_meta( $createdUserID, 'managerParentId',  get_current_user_id() );
          $associationDelegateParentId = get_user_meta(get_current_user_id(), 'associationDelegateParentId', true);
          //om det lagledaren är kopplad till en föreningsansvarig
          if(!empty($associationDelegateParentId)){
            $associationDelegateEditableIds = get_user_meta($associationDelegateParentId, 'userids', true);
            $associationDelegateEditableIds = $associationDelegateEditableIds == '' ? $createdUserId : $associationDelegateEditableIds.','.$createdUserId;
            //uppdatera föreningsansvarigs userids
            update_user_meta( $associationDelegateParentId, 'userids', $editableIds);
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
    $nonce_id = $json_api->get_nonce_id('user', 'update_user');
    // var_dump($json_api->query->nonce);
    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }

    $user_login       = sanitize_text_field($query['user_login']);
    // $user_pass        = isset($query['user_pass']) ? sanitize_text_field($query['user_pass']) : wp_generate_password(8);
    $user_email       = sanitize_text_field($query['user_email']);
    $first_name       = sanitize_text_field($query['first_name']);
    $last_name        = sanitize_text_field($query['last_name']);
    $_phone           = sanitize_text_field($query['phone']);
    $_address         = sanitize_text_field($query['address']);
    $_zip             = sanitize_text_field($query['postalcode']);
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
      update_user_meta( $userIdToUpdate,'postalcode', $_zip );
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
    $nonce_id = $json_api->get_nonce_id('user', 'delete_user');

    // if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
    //   $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    // }
    // var_dump($users);
    $editableIds = explode(',', get_user_meta(get_current_user_id(), 'userids', true));

    $stop = false;
    foreach ($users as $user) {
      if(!is_numeric($user) || !in_array($user, $editableIds))
        $stop = true;
    }

    // var_dump($stop);
    if(!$stop){
      $res = [];
      foreach ($users as $user) {
        //remove id from parents userids
        $parentIds = [];
        $associationDelegateId = get_user_meta($user, 'associationDelegateParentId', true);
        $managerParentId = get_user_meta($user, 'managerParentId', true);
        if(is_numeric($associationDelegateId)){
          $parentIds[] = $associationDelegateId;
        }
        if(is_numeric($managerParentId)){
          $parentIds[] = $managerParentId;
        }
        // dump($parentIds);
        $dontDeleteUser = false;
        foreach ($parentIds as $parentId) {
          $ids = explode(',', get_user_meta($parentId, 'userids', true));
          $parent = new WP_User( $parentId);
          // echo 'parent role: '.$parent->roles[0].'   '.$parent->user_login;
          // dump($ids);
          if(($key = array_search($user, $ids)) !== false) {
            unset($ids[$key]);
            // dump($ids);
            // dump(implode(',', $ids));
            // dump($parentId);
            // echo '<br>';
            // echo "res:";
            update_user_meta($parentId, 'userids', implode(',', $ids) );

          }
        }

        //delete user
        $delete_user = wp_delete_user( intval($user) );
        if ( ! is_wp_error( $user ) ) {

          $res[] = $delete_user;
        } else {
          $res[] = $delete_user;
        }

        var_dump($delete_user);
      }

      return $res;
    }
  }





  function update_loggedin_user() {
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);
    $nonce_id = $json_api->get_nonce_id('user', 'update_loggedin_user');

    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }

    if(!isset($query['passwordCurrent'])){
      $json_api->error("Current password is missing");
    }
    else {
      $loggedInUser = new WP_User( get_current_user_id());
      if ( !($loggedInUser && wp_check_password( sanitize_text_field($query['passwordCurrent']), $loggedInUser->data->user_pass, $loggedInUser->ID)) )
        $json_api->error("Lösenordet är inte korrekt");
    }


    $user_pass        = isset($query['user_pass']) ? sanitize_text_field($query['user_pass']) : null;
    $user_email       = sanitize_text_field($query['user_email']);
    $first_name       = sanitize_text_field($query['first_name']);
    $last_name        = sanitize_text_field($query['last_name']);
    $_phone           = sanitize_text_field($query['phone']);
    $_address         = sanitize_text_field($query['address']);
    $_zip             = sanitize_text_field($query['postalcode']);
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
      update_user_meta( $updatedUserID,'postalcode', $_zip );
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
    $nonce_id = $json_api->get_nonce_id('user', 'delete_loggedin_user');

    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }

    $loggedInUser = new WP_User( get_current_user_id());
    if ( !($loggedInUser && wp_check_password( sanitize_text_field($query['passwordCurrent']), $loggedInUser->data->user_pass, $loggedInUser->ID)) )
      $json_api->error("Lösenordet är inte korrekt");


    // delete user
    $delete_user = wp_delete_user( $current_user->ID );
    if ( ! is_wp_error( $delete_user ) ) {
      return $delete_user;
    } else {
      return $delete_user;
    }
  }







  function update_salesmans_sales_status() {
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);
    $nonce_id = $json_api->get_nonce_id('user', 'update_salesmans_sales_status');

    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }

    $args = array(
    'post_type' => 'products',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    );
    $queryProducts = new WP_Query($args);
    $productArtikleNames = [];
    if($queryProducts->posts){
      foreach ($queryProducts->posts as $product) {
        $productArtikleNames[] = get_post_meta($product->ID,'artikelnamn',true);
      }
    }

    // dump($productArtikleNames);
    $go = true;
    foreach ($query as $key => $value) {
      if(!is_numeric($value) || !in_array($key, $productArtikleNames) ){
        $go = false;
      }
    }

    if($go){
      foreach ($query as $key => $value) {
        update_user_meta( get_current_user_id(), $key, $value );
      }
      return true;
    }
    else {
      return false;
    }

  }



}

?>
