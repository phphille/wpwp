<?php
/*
Controller name: Excel
Controller description: Excel functions
*/
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

class JSON_API_Excel_Controller {

  private $createdTeamID = '';

  // TODO: fixa säkerheten här. ska inte kunnas nås utifrån. lagledare får bara skapa säljare

  function create_user($userDetails) {
    global $json_api;

    $loggedInUser = new WP_User( get_current_user_id());
    $loggedInUserRole = $loggedInUser->roles[0];

    // $nonce_id = $json_api->get_nonce_id('excel', 'create_user');

    // if (!wp_verify_nonce($json_api->query->nonce, $nonce_id)) {
    //   $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    // }
    // return $json_api->query->nonce;
    // var_dump($userDetails);
    $user_login       = sanitize_text_field($userDetails[0]);
    $user_pass        = wp_generate_password(8);
    $user_email       = sanitize_text_field($userDetails[1]);
    $first_name       = sanitize_text_field($userDetails[2]);
    $last_name        = sanitize_text_field($userDetails[3]);
    $_address         = sanitize_text_field($userDetails[4]);
    $_zip             = sanitize_text_field($userDetails[5]);
    $_city            = sanitize_text_field($userDetails[6]);
    $_phone           = sanitize_text_field($userDetails[7]);
    $role             = sanitize_text_field($userDetails[8]) == 'lagledare' || sanitize_text_field($userDetails[8]) == 'säljare' ? sanitize_text_field($userDetails[8]) : false;
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
          $this->createdTeamID = $createdUserID;
        }

        //Lägg till användaren till den inloggades userIds görs i user management myplugin_registration_save

        //Om en föreningsansvarig skapat en säljare, uppdatera säljarens lagledares userids
        if($role == 'salesperson' && $loggedInUserRole == 'associationDelegate'){
          $managerEditableIds = get_user_meta($this->createdTeamID, 'userids', true);
          $managerEditableIds = $managerEditableIds == '' ? $createdUserID : $managerEditableIds.','.$createdUserID;
          update_user_meta( $this->createdTeamID, 'userids', $managerEditableIds);
          //Lägg till lagledarens id på säljaren
          update_user_meta( $createdUserID, 'managerParentId', $this->createdTeamID);
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






  function get_table_for_excel_admin(){
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);

    $loggedInUser = new WP_User( get_current_user_id());
    $loggedInUserRole = $loggedInUser->roles[0];

    // $nonce_id = $json_api->get_nonce_id('excel', 'create_user');

    // if (!wp_verify_nonce($json_api->query->nonce, $nonce_id) || $loggedInUserRole != 'administrator' || !is_numeric($query['association-team'])) {
    //   $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    // }
    // var_dump($query);
   $team = $query['association-team'];

    $usersIDs = explode(',', get_user_meta($query['association-team'], 'userids', true));

  	$table = '';
  	if($usersIDs){

      $manager = get_userdata( $team );
      $teamSalespersons = get_users(array(
          'meta_key'     => 'managerParentId',
          'meta_value'   => $team,
      ));
      // dump($teamSalespersons);
      $args = array(
      'post_type' => 'products',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      );

      $korvladsTyper = new WP_Query($args);

      // dump($teamSalespersons);
  		wp_enqueue_style('author-list', plugin_dir_url(__FILE__) . '/css/author-list.css');

  		$table .= '
      <form class="" action="" method="post">
      <input type="hidden" name="excel-team" value="'.$team.'">
      <table class="table table-hover">
        <tr>
          <th><input type="checkbox" name="select-User"></th>
          <th>Lag</th>
          <th>Sålda lådor totalt</th>';
          $products = [];
          foreach ($korvladsTyper->posts as $typ) {
            $products[] = array('artikelNamn' => get_post_meta($typ->ID,'artikelnamn',true), 'pris' => get_post_meta($typ->ID,'pris',true));
            $table .='<th>'.$typ->post_title.'</th>';
          }
      $table .= '
        <th>Summa kr</th>
        <th>Leverans</th>
        <th>Betalt</th>
        <th>Kommentar</th>
      </tr>
      ';

      //skapa en array som innehåller lagets antal sålda lådor för varje produkt
      $totalKorvladorForTypes = [];
      for ($i=0; $i<count($products); $i++) {
        $totalKorvladorForTypes[] = 0;
      }

      $salespersonsRow = '';
  			foreach($teamSalespersons as $salesperson) :

          // if($author->ID == get_current_user_id()) break;
          // $subUser = new WP_User( $subUserID );
            // $archive_url = get_author_posts_url($author->ID);
  					// $table .= get_avatar($author->user_email, 60);
            // $table .= '<a href="'. $archive_url . '" title="'. $author->display_name . '">' . $author->display_name . '</a>';
  					// $table .= '<p class="author-bio">' . get_user_meta($author->ID, 'description', true) . '</p>';
  					// $table .= '<p class="author-archive"><a href="'. $archive_url . '" title="' . __('View all posts by ', 'pippin') . $author->display_name . '">' . __('View author\'s posts', 'pippin') . '</a></p>';
            // if(!empty($subUser->data) && $subUser->ID != get_current_user_id()){
              $salespersonsRow .= '<tr>';
              $salespersonsRow .= '<td><input type="checkbox" name="user[]" value="'.$salesperson->ID.'"></td>';
              $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$salesperson->first_name.' '.$salesperson->last_name.'</a></td>';

              $totalKorvlador = 0;
              $totalSum = 0;
              $tdKorvlador = '';
              $count = 0;
              foreach ($products as $product) {
                $nbrSoldKorvlada = get_user_meta($salesperson->ID, $product['artikelNamn'], true);
                $totalSum += (floatval($nbrSoldKorvlada) * floatval($product['pris']));
                $totalKorvlador += $nbrSoldKorvlada;
                $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$nbrSoldKorvlada.'</a></td>';

                $totalKorvladorForTypes[$count] += $nbrSoldKorvlada;
              }

              $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$totalKorvlador.'</a></td>';
              $salespersonsRow .= $tdKorvlador;
              $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$totalSum.'</a></td>';
              $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.'</a></td>';
              $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.'</a></td>';
              $salespersonsRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.'</a></td>';
    				  $salespersonsRow .= '</tr>';
            // }
  			endforeach;


      $managerRow = '<tr>';
      $managerRow .= '<td><input type="checkbox" name="user[]" value="'.$manager->ID.'"></td>';
      $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$manager->ID.'">Teamleader '.$manager->first_name.' '.$manager->last_name.'</a></td>';


      $index = 0;
      $totalTeamNbrKorvlador = 0;
      $totalTeamSum = 0;
      $managerTdProducts ='';
      foreach ($totalKorvladorForTypes as $totalNbrType) {
        $sum = floatval($totalNbrType) * floatval($products[$index]);
        $totalTeamNbrKorvlador += $totalNbrType;
        $totalTeamSum += $sum;
        $managerTdProducts .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$sum.'</a></td>';
      }
      $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$totalTeamNbrKorvlador.'</a></td>';
      $managerRow .= $managerTdProducts;
      $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'">'.$totalTeamSum.'</a></td>';
      $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'"></a></td>';
      $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'"></a></td>';
      $managerRow .= '<td><a href="'.get_home_url().'/uppdatera-anvandare/?user='.$salesperson->ID.'"></a></td>';
      $managerRow .= '</tr>';

      $table .= $managerRow;
      $table .= $salespersonsRow;

  		$table .= '</table>
      <input type="submit" value="Ta bort" class="doDeleteUser">
      <input type="submit" name="do-excel-admin" value="Exportera">
      </form>';

  	}
    else{
      $table = 'finns inte några';
    }

  	return $table;



  }






}
