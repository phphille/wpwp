<?php

add_action('init','get_user_id_from_url');
function get_user_id_from_url() {
    global $wp;
    $wp->add_query_var('user');
}

function save_created_user() {
  if ( empty($_POST) || !wp_verify_nonce($_POST['ny-korvare'],'save_created_user') ) {
    echo 'You targeted the right function, but sorry, your nonce did not verify.';
    die();
  }
  else {
    // do your function here
    $userdata = array(
    'user_login'  =>  sanitize_text_field( $_POST['_new-username'] ),
    'user_email' =>  sanitize_text_field( $_POST['_new-email'] ),
    'first_name' =>  sanitize_text_field( $_POST['_new-firstname'] ),
    'last_name' =>  sanitize_text_field( $_POST['_new-lastname'] ),
    // 'role' =>  sanitize_text_field( $_POST['role'] ),
    'user_registered' => date("Y-m-d H:i:s"),
    'user_pass'   =>  wp_generate_password(8)  // When creating an user, `user_pass` is expected.
    );

    dump($userdata);

    $createdUserId = wp_insert_user( $userdata ) ;
    // var_dump($createdUserId);
    //On success
    if ( ! is_wp_error( $createdUserId ) ) {
      update_user_meta( $createdUserId,'address', sanitize_text_field( $_POST['_new-address'] ) );
      update_user_meta( $createdUserId,'phonenumber', sanitize_text_field( $_POST['_new-phonenumber'] ) );
  		update_user_meta( $createdUserId,'team', sanitize_text_field( $_POST['_new-team'] ) );

      //lägg till användaren till förälderns userids. behövs inte just nu
      // $editableIds = get_user_meta(get_current_user_id(), 'userids', true);
      // dump($editableIds);
      // dump(($editableIds . ',' . $createdUserId));
      // if($editableIds == ''){
      //   update_user_meta( get_current_user_id(), 'userids', $createdUserId);
      // }
      // else {
      //   update_user_meta( get_current_user_id(), 'userids', ($editableIds.','.$createdUserId));
      // }
      //
      //
      $wp_user = new WP_User( get_current_user_id());
      $parentUserRole = $wp_user->roles[0];

      if($_POST['role'] == 'salesPerson' && $parentUserRole == 'associationDelegate'){
        //uppdatera lagledarens id
        $editableIds = explode(',', get_user_meta(get_current_user_id(), 'userids', true));
        foreach ($editableIds as $value) {
          $user = new WP_User( $value);
          if (isset($user->roles[0]) && $user->roles[0] == 'manager' && get_user_meta($value, 'team', true) == $_POST['_new-team']) {

            // var_dump(get_user_meta($value, 'team', true));
            // var_dump(get_current_user_id());
            // var_dump($value);
            $editableIds = get_user_meta($value, 'userids', true) == '' ? $createdUserId : get_user_meta($value, 'userids', true).','.$createdUserId;
            update_user_meta( $value, 'userids', $editableIds);
          }
        }
      }

    }

  }
}

add_action( 'wp_ajax_save_created_user', 'save_created_user' );



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

    //  Loop through each row of the worksheet in turn
    for ($row = 2; $row <= $highestRow; $row++){
        //  Read a row of data into an array
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
        dump($rowData);
        //  Insert row data array into your database of choice here
    }
  }
}
add_action( 'wp_ajax_create_user_by_excel', 'create_user_by_excel' );
