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




add_action('wp_loaded', 'save_company_form');
function save_company_form(){

  if(isset($_POST['do-company'])){
    if(empty($_POST) || !wp_verify_nonce($_POST['ny-stor-korv'],'add_new_company') ){
      print 'Sorry, your nonce did not verify.';
      exit;
    }

    if(
    // preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $_POST['company_name']) &&
    // // checkOrgNbr($_POST['company_org-nbr']) &&
    // preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $_POST['company_address']) &&
    // preg_match("/^([a-zåäöA-ZÅÄÖ]{2}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $_POST['company_city']) &&
    // preg_match("/^[0-9]{3}[ ]?[0-9]{2}$/", $_POST['company_postalcode']) &&
    // is_email($_POST['company_contactMail']) &&
    // preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $_POST['company_contactFirstname']) &&
    // preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $_POST['company_contactLastname']) &&
    // preg_match("/^[0-9+ ]$/", $_POST['company_contactPhone']) &&
    // preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $_POST['company_deliveryFirstname']) &&
    // preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $_POST['company_deliveryLastname']) &&
    // preg_match("/^[0-9+ ]$/", $_POST['company_deliveryPhone']) &&
    get_current_user_role() == 'salesperson'
    ){

      if(isset($_POST['korven']) && is_numeric($_POST['korven'])){
        $post_to_update = get_post($_POST['korven']);

        if($post_to_update->post_author == get_current_user_id()){
          $post = array(
          'ID'           => $post_to_update->ID,
          'post_title' => wp_strip_all_tags( $_POST['company_name'] ),
          'post_type' => 'companies',  // Use a custom post type if you want to
          'post_author' => get_current_user_id()
          );
        }
      }
      else {
        $post = array(
        'post_title' => wp_strip_all_tags( $_POST['company_name'] ),
        'post_type' => 'companies',  // Use a custom post type if you want to
        'post_author' => get_current_user_id()
        );

      }

      $postid = wp_insert_post($post, true);

      if(!isset($postid->errors)){
        update_post_meta($postid, 'companyName', $_POST['company_name']);
        update_post_meta($postid, 'companyOrgNbr', $_POST['company_org-nbr']);
        update_post_meta($postid, 'companyAddress', $_POST['company_address']);
        update_post_meta($postid, 'companyCity', $_POST['company_city']);
        update_post_meta($postid, 'companyPostalcode', $_POST['company_postalcode']);
        update_post_meta($postid, 'companyContactMail', $_POST['company_contactMail']);
        update_post_meta($postid, 'companyContactFirstname', $_POST['company_contactFirstname']);
        update_post_meta($postid, 'companyContactLastname', $_POST['company_contactLastname']);
        update_post_meta($postid, 'companyContactPhone', $_POST['company_contactPhone']);
        update_post_meta($postid, 'companyDeliveryFirstname', $_POST['company_deliveryFirstname']);
        update_post_meta($postid, 'companyDeliveryLastname', $_POST['company_deliveryLastname']);
        update_post_meta($postid, 'companyDeliveryPhone', $_POST['company_deliveryPhone']);


        if(isset($_POST['company_want-delivery']) && $_POST['company_want-delivery'] == '1'){
          update_post_meta($postid, 'companyWantDelivery', 'ja');
        }
        else {
          update_post_meta($postid, 'companyWantDelivery', 'nej');
        }
        wp_reset_postdata();
        wp_redirect( home_url('/registrera-salda-korvlador/') );
        exit;
      }

    }
  }
}










add_action('wp_loaded', 'export_to_excel_as_admin');
function export_to_excel_as_admin(){

    // var_dump($_POST);
    // var_dump(get_current_user_role());
    // var_dump(is_numeric($_POST['do-excel-admin']));
  if(isset($_POST['do-excel-admin']) && get_current_user_role() == 'administrator' && is_numeric($_POST['excel-team'])){
    $team = $_POST['excel-team'];
    $manager = get_userdata( $team );
    $teamSalespersons = get_users(array(
        'meta_key'     => 'managerParentId',
        'meta_value'   => $team,
    ));

    $args = array(
    'post_type' => 'products',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    );

    $korvladsTyper = new WP_Query($args);

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', "Lagledare:")->getStyle('A1')->applyFromArray(array(
        'font'  => array(
          'bold'  => true,
        ),
        // 'alignment' => array(
        //   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        // ),
      )
    );

    $objPHPExcel->getActiveSheet()->setCellValue('A2', "Namn")->getStyle('A2:F2')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValue('B2', "Adress");
    $objPHPExcel->getActiveSheet()->setCellValue('C2', "Postnummer");
    $objPHPExcel->getActiveSheet()->setCellValue('D2', "Ort");
    $objPHPExcel->getActiveSheet()->setCellValue('E2', "Telefon");
    $objPHPExcel->getActiveSheet()->setCellValue('F2', "E-post");

    $objPHPExcel->getActiveSheet()->setCellValue('A3', $manager->first_name.' '.$manager->last_name);
    $objPHPExcel->getActiveSheet()->setCellValue('B3', get_user_meta($manager->ID, 'address', true));
    $objPHPExcel->getActiveSheet()->setCellValue('C3', get_user_meta($manager->ID, 'zip', true));
    $objPHPExcel->getActiveSheet()->setCellValue('D3', get_user_meta($manager->ID, 'city', true));
    $objPHPExcel->getActiveSheet()->setCellValue('E3', get_user_meta($manager->ID, 'phone', true));
    $objPHPExcel->getActiveSheet()->setCellValue('F3', $manager->email);


    $objPHPExcel->getActiveSheet()->setCellValue('A6', "Säljare:")->getStyle('A6')->applyFromArray(array(
        'font'  => array(
          'bold'  => true,
        ),
        // 'alignment' => array(
        //   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        // ),
      )
    );

    $objPHPExcel->getActiveSheet()->setCellValue('A7', "ID")->getStyle('A7:H7')->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValue('B7', "Namn");
    $objPHPExcel->getActiveSheet()->setCellValue('C7', "Adress");
    $objPHPExcel->getActiveSheet()->setCellValue('D7', "Postnummer");
    $objPHPExcel->getActiveSheet()->setCellValue('E7', "Ort");
    $objPHPExcel->getActiveSheet()->setCellValue('F7', "Telefon");
    $objPHPExcel->getActiveSheet()->setCellValue('G7', "E-post");
    $objPHPExcel->getActiveSheet()->setCellValue('H7', "Sålda lådor totalt");
    $col = 8;
    foreach ($korvladsTyper->posts as $typ) {
      $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 7, $typ->post_title)->getStyleByColumnAndRow($col, 7)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
      $products[] = array('artikelNamn' => get_post_meta($typ->ID,'artikelnamn',true), 'pris' => get_post_meta($typ->ID,'pris',true));
      $col++;
    }
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 7, "Summa kr")->getStyleByColumnAndRow($col, 7)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1, 7, "Leverans")->getStyleByColumnAndRow($col+1, 7)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2, 7, "Betalt")->getStyleByColumnAndRow($col+2, 7)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3, 7, "Kommentar")->getStyleByColumnAndRow($col+3, 7)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));



    $totalKorvladorForTypes = [];
    for ($i=0; $i<count($products); $i++) {
      $totalKorvladorForTypes[] = 0;
    }

    $salespersonsRow = '';
    $dataArr = [];
    $companyArr = [];
    foreach($teamSalespersons as $salesperson){
      $dataArr[] = array();
      $index = count($dataArr);

      // $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$salesperson->first_name.' '.$salesperson->last_name.'</a></td>';
      $dataArr[($index-1)][] = $salesperson->ID;
      $dataArr[($index-1)][] = $salesperson->first_name.' '.$salesperson->last_name;
      $dataArr[($index-1)][] = get_user_meta($salesperson->ID, 'address', true);
      $dataArr[($index-1)][] = get_user_meta($salesperson->ID, 'zip', true);
      $dataArr[($index-1)][] = get_user_meta($salesperson->ID, 'city', true);
      $dataArr[($index-1)][] = get_user_meta($salesperson->ID, 'phone', true);
      $dataArr[($index-1)][] = $salesperson->email;

      $totalKorvlador = 0;
      $totalSum = 0;
      $tdKorvlador = '';
      $count = 0;
      $salesPersonsNbrSoldKorvlador = [];
      foreach ($products as $product) {
        $nbrSoldKorvlada = get_user_meta($salesperson->ID, $product['artikelNamn'], true);
        $totalSum += (floatval($nbrSoldKorvlada) * floatval($product['pris']));
        $totalKorvlador += $nbrSoldKorvlada;

        // $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$nbrSoldKorvlada.'</a></td>';
        $salesPersonsNbrSoldKorvlador[] = $nbrSoldKorvlada;
        $totalKorvladorForTypes[$count] += $nbrSoldKorvlada;
      }

      // $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$totalKorvlador.'</a></td>';
      $dataArr[($index-1)][] = $totalKorvlador;
      // $salespersonsRow .= $tdKorvlador;
      foreach ($salesPersonsNbrSoldKorvlador as $value) {
        $dataArr[($index-1)][] = $value;
      }
      // $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$totalSum.'</a></td>';
      $dataArr[($index-1)][] = $totalSum;
      // $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.'</a></td>';
      $dataArr[($index-1)][] = 'saknas';
      // $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.'</a></td>';
      $dataArr[($index-1)][] = 'saknas';
      // $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.'</a></td>';
      $dataArr[($index-1)][] = 'saknas';

      $args = array(
      'post_type' => 'companies',
      'author' =>  $salesperson->ID,
      'post_status' => array('publish', 'pending', 'draft', 'auto-draft'),
      'posts_per_page' => -1,
      );

      $companies = new WP_Query($args);
      if(!empty($companies->posts)){
        // $dataArr[($index-1)][] = array();
        // $indexCompanyArr = count($dataArr[($index-1)]);

        foreach($companies->posts as $company){
          $companyArr[] = array(
            $salesperson->ID,
            get_post_meta($company->ID, 'companyName', true),
            get_post_meta($company->ID, 'companyOrgNbr', true),
            get_post_meta($company->ID, 'companyAddress', true),
            get_post_meta($company->ID, 'companyCity', true),
            get_post_meta($company->ID, 'companyPostalcode', true),
            get_post_meta($company->ID, 'companyContactMail', true),
            get_post_meta($company->ID, 'companyContactFirstname', true),
            get_post_meta($company->ID, 'companyContactLastname', true),
            get_post_meta($company->ID, 'companyContactPhone', true),
            get_post_meta($company->ID, 'companyDeliveryFirstname', true),
            get_post_meta($company->ID, 'companyDeliveryLastname', true),
            get_post_meta($company->ID, 'companyDeliveryPhone', true),
            get_post_meta($company->ID, 'companyWantDelivery', true)
          );
        }
      }
    }

    $row = 8;
    foreach ($dataArr as $arr) {
      $col = 0;
      foreach ($arr as $value) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
        $col++;
      }
      $row++;
    }


    $row++;
    $row++;
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'Företag:')->getStyleByColumnAndRow(0, $row)->applyFromArray(array(
        'font'  => array(
          'bold'  => true,
        ),
        // 'alignment' => array(
        //   'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        // ),
      )
    );
    $row++;
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, 'ID säljare')->getStyleByColumnAndRow(0, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, 'Namn')->getStyleByColumnAndRow(1, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Organisationsnummer')->getStyleByColumnAndRow(2, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'Address')->getStyleByColumnAndRow(3, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, 'Ort')->getStyleByColumnAndRow(4, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'Postnummer')->getStyleByColumnAndRow(5, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, 'E-post')->getStyleByColumnAndRow(6, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, 'Kontakt Förnamn')->getStyleByColumnAndRow(7, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, 'Kontakt Efternamn')->getStyleByColumnAndRow(8, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $row, 'Kontakt Telefon')->getStyleByColumnAndRow(9, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $row, 'Leverans Förnamn')->getStyleByColumnAndRow(10, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $row, 'Leverans Efternamn')->getStyleByColumnAndRow(11, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $row, 'Leverans Telefon')->getStyleByColumnAndRow(12, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $row, 'Vill ha utkörning')->getStyleByColumnAndRow(13, $row)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '689fff'))));
    $row++;
    foreach ($companyArr as $company) {
      $col = 0;
      foreach ($company as $companyVal) {
        // dump($companyVal);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $companyVal);
        $col++;
      }
      // $row++;
    }

    // $managerRow = '<tr>';
    // $managerRow .= '<td><input type="checkbox" name="user[]" value="'.$manager->ID.'"></td>';
    // $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$manager->ID.'">Teamleader '.$manager->first_name.' '.$manager->last_name.'</a></td>';


    // $index = 0;
    // $totalTeamNbrKorvlador = 0;
    // $totalTeamSum = 0;
    // $managerTdProducts ='';
    // foreach ($totalKorvladorForTypes as $totalNbrType) {
    //   $sum = floatval($totalNbrType) * floatval($products[$index]);
    //   $totalTeamNbrKorvlador += $totalNbrType;
    //   $totalTeamSum += $sum;
    //   $managerTdProducts .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$sum.'</a></td>';
    // }
    // $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$totalTeamNbrKorvlador.'</a></td>';
    // $managerRow .= $managerTdProducts;
    // $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$totalTeamSum.'</a></td>';
    // $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'"></a></td>';
    // $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'"></a></td>';
    // $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'"></a></td>';
    // $managerRow .= '</tr>';
    //
    // $table .= $managerRow;
    // $table .= $salespersonsRow;


    // $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="01simple.xls"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;


  }
}
