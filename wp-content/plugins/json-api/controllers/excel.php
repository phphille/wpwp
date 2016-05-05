<?php
/*
Controller name: Excel
Controller description: Excel functions
*/

class JSON_API_Excel_Controller {

  function create_user($userDetails) {
    global $json_api;

    $loggedInUser = new WP_User( get_current_user_id());
    $loggedInUserRole = $loggedInUser->roles[0];

    // $nonce_id = $json_api->get_nonce_id('excel', 'create_user');

    // if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
    //   $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    // }
    // return $json_api->query->nonce;

    $user_login       = sanitize_text_field($userDetails[0]);
    $user_pass        = wp_generate_password(8);
    $user_email       = sanitize_text_field($userDetails[1]);
    $first_name       = sanitize_text_field($userDetails[2]);
    $last_name        = sanitize_text_field($userDetails[3]);
    $_address         = sanitize_text_field($userDetails[4]);
    $_zip             = sanitize_text_field($userDetails[5]);
    $_city            = sanitize_text_field($userDetails[6]);
    $_phone           = sanitize_text_field($userDetails[7]);
    $role             = sanitize_text_field($userDetails[8]);
    $_team            = sanitize_text_field($userDetails[9]);

    if($role == 'lagledare')
      $role = 'manager';

    if($role == 'säljare')
      $role = 'salesperson';

    if($role == 'salesperson' || $role == 'manager'){
      $userdata = array(
    		'user_login'	 =>	$user_login,
        'user_pass'    => $user_pass,
        'user_email'	 =>	$user_email,
        'first_name'	 =>	$first_name,
        'last_name'	   =>	$last_name,
        'user_registered' => date("Y-m-d H:i:s"),
        'role'         =>  $role,
      );
    }


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
        // var_dump($role);
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



  function create_user_by_excel(){

    if(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) == 'xlsx' || pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) == 'xls'){
      $inputFileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
      $inputFileName = $_FILES['file']['tmp_name'];

      //  Read your Excel workbook
      try {
          $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
          $objReader = PHPExcel_IOFactory::createReader($inputFileType);
          $objPHPExcel = $objReader->load($inputFileName);
      } catch(Exception $e) {
          die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
      }

      //  Get worksheet dimensions
      $sheet = $objPHPExcel->getSheet(0);
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn();

      $loggedInUser = new WP_User( get_current_user_id());
      $checkTeamNames = $loggedInUser->roles[0] == 'associationDelegate' ? true : false;

      $errors = [];
      $excelUserNames = [];
      $excelMailAddresses = [];
      $errorMsges = [];
      $managerTeam = '';
      //  Loop through each row of the worksheet in turn
      for ($row = 2; $row <= $highestRow; $row++){
        $errorMsg = [];
        //  Read a row of data into an array
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);


        if($checkTeamNames){
          if($rowData[0][8] != 'lagledare' && $rowData[0][8] != 'säljare'){
            $errorMsg[] = 'Personens roll måste vara "lagledare" eller "säljare"';
          }

          if($rowData[0][8] == 'lagledare'){
            $managerTeam = $rowData[0][9];
          }
          else {
            if($rowData[0][9] != $managerTeam){
              $errorMsg[] = 'Säljarens lag stämmer inte överrens med dess ovanstående lagledare';
            }
          }
        }


        if(username_exists( $rowData[0][0] )){
          $errorMsg[] = 'Användarnamnet är upptaget';
        }

        if(in_array( $rowData[0][0] ,$excelUserNames)){
          $errorMsg[] = 'Användarnamnet: '.$rowData[0][0].' är angiven flera gånger i excelfilen';
        }
        else {
          $excelUserNames[] = $rowData[0][0];
        }



        if(email_exists( $rowData[0][1] )){
          $errorMsg[] = 'E-postadressen är redan registerat';
        }

        if(!is_email( $rowData[0][1] )){
          $errorMsg[] = 'E-postadressen är inte giltlig';
        }

        if(in_array( $rowData[0][1] ,$excelMailAddresses)){
          $errorMsg[] = 'E-postadressen: '.$rowData[0][1].' är angiven flera gånger i excelfilen';
        }
        else {
          $excelMailAddresses[] = $rowData[0][1];
        }


        if(!empty($errorMsg)){
          $errorMsges[] = 'Rad '.$row.' innehåller följande fel: '.implode(' ', $errorMsg);
        }


      }


      if(empty($errorMsges)){
        for ($row = 2; $row <= $highestRow; $row++){
          //  Read a row of data into an array
          $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
          if(!$this->create_user($rowData[0])){
            return false;
            break;
          }
        }
      }
      else {
        return $errorMsges;
      }


    }
  }






}
