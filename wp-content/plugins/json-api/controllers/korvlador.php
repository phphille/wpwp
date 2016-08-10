<?php


/*
Controller name: Korvlador
Controller description: Korvlador functions
*/
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

class JSON_API_Korvlador_Controller {

  function update_sold_korvs(){
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);
    $nonce_id = $json_api->get_nonce_id('korvlador', 'update_sold_korvs');

    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id) || get_current_user_role() != 'salesperson') {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }

    $userID = get_current_user_id();
    $product = get_post_meta($query['korvlada'],null,true);

    $newNbr = false;

    if($product && preg_match('/^([1-9]{1}|[1-9]{1}[0-9]{1,})$/', $query['nbrProducts'])){
      $previousNbrOfSelectedProduct = get_user_meta($userID, 'korvlada-'.$query['korvlada'], true);
      if($query['add'] == 'true'){
        $newNbr = $previousNbrOfSelectedProduct ? $query['nbrProducts'] + $previousNbrOfSelectedProduct : $query['nbrProducts'];
        if($previousNbrOfSelectedProduct){
          update_user_meta($userID, 'korvlada-'.$query['korvlada'], $newNbr);
        }
        else {
          update_user_meta($userID, 'korvlada-'.$query['korvlada'], $newNbr);
        }
      }
      else {
        if($previousNbrOfSelectedProduct){
          $newNbr = ($previousNbrOfSelectedProduct - $query['nbrProducts']) < 0 ? 0 : $previousNbrOfSelectedProduct - $query['nbrProducts'];
          update_user_meta($userID, 'korvlada-'.$query['korvlada'], $newNbr);
        }
      }

      $res = array('id' => $query['korvlada'], 'quantity' => $newNbr, 'title' => get_the_title( $query['korvlada'] ) );
      return json_encode($res);
    }
  }







  function check_user_can_remove_sold_item(){
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);
    $nonce_id = $json_api->get_nonce_id('korvlador', 'check_user_can_remove_sold_item');

    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id) || get_current_user_role() != 'salesperson') {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }

    $nbr = get_user_meta(get_current_user_id(), 'korvlada-'.$query['korvlada'], true);

    return is_numeric($nbr) && $nbr > 0 ? true : false;

  }








  function save_company(){
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);
    $nonce_id = $json_api->get_nonce_id('korvlador', 'save_company');

    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id) || get_current_user_role() != 'salesperson') {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }

    $userID = get_current_user_id();

    if(isset($query)){
      $korvladorAreOk = true;
      foreach ($query as $key => $value ) {
        if(strpos($key, 'company-korvlada-nbr') !== false){
          $words = explode('-', $key);
          $korvladaID = $words[count($words)-1];
          if(!is_numeric($korvladaID) || !get_post($korvladaID) || !is_numeric($value) || $value < 1){
            $korvladorAreOk = false;
          }
        }
      }
      if(
      // preg_match("/^([a-zåäöüA-ZÅÄÖÜ0-9]{1}[a-zåäöüA-ZÅÄÖÜ0-9.,?:\-/()&+– ]{1,})$/", $query['company_name']) &&
      // // checkOrgNbr($query['company_org-nbr']) &&
      // preg_match("/^([a-zåäöüA-ZÅÄÖÜ0-9]{1}[a-zåäöüA-ZÅÄÖÜ0-9.,?:\-/()&+– ]{1,})$/", $query['company_address']) &&
      // preg_match("/^([a-zåäöüA-ZÅÄÖÜ0-9]{1}[a-zåäöüA-ZÅÄÖÜ \-]{1,})$/", $query['company_city']) &&
      // preg_match("/^[0-9]{3}[ ]?[0-9]{2}$/", $query['company_postalcode']) &&
      // is_email($query['company_contactMail']) &&
      // preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $query['company_contactFirstname']) &&
      // preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $query['company_contactLastname']) &&
      // preg_match("/^[0-9+ \-]{1,}$/", $query['company_contactPhone']) &&
      // preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $query['company_deliveryFirstname']) &&
      // preg_match("/^([a-zåäöA-ZÅÄÖ]{1}|[a-zåäöA-ZÅÄÖ]{1}[a-zåäöA-ZÅÄÖ ]{1,})$/", $query['company_deliveryLastname']) &&
      // preg_match("/^[0-9+ \-]{1,}$/", $query['company_deliveryPhone']) &&
      get_current_user_role() == 'salesperson' &&
      $korvladorAreOk
      ){



        if(isset($query['company_korven']) && is_numeric($query['company_korven'])){
          $post_to_update = get_post($query['company_korven']);

          if($post_to_update->post_author == get_current_user_id()){
            $post = array(
            'ID'           => $post_to_update->ID,
            'post_title' => wp_strip_all_tags( $query['company_name'] ),
            'post_type' => 'companies',  // Use a custom post type if you want to
            'post_author' => get_current_user_id()
            );
          }
        }
        else {
          $post = array(
          'post_title' => wp_strip_all_tags( $query['company_name'] ),
          'post_type' => 'companies',
          'post_author' => get_current_user_id()
          );
        }


        $postid = wp_insert_post($post, true);
        if(is_numeric($postid)){
          update_post_meta($postid, 'companyOrgNbr', $query['company_orgNbr']);
          update_post_meta($postid, 'companyAddress', $query['company_address']);
          update_post_meta($postid, 'companyCity', $query['company_city']);
          update_post_meta($postid, 'companyPostalcode', $query['company_postalcode']);
          update_post_meta($postid, 'companyMail', $query['company_mail']);
          update_post_meta($postid, 'companyPhone', $query['company_phone']);
          update_post_meta($postid, 'companyRecipientName', $query['company_recipientName']);
          update_post_meta($postid, 'companyRecipientMail', $query['company_recipientMail']);
          update_post_meta($postid, 'companyRecipientPhone', $query['company_recipientPhone']);
          foreach ($query as $key => $value ) {
            if(strpos($key, 'company-korvlada-nbr') !== false){
              $words = explode('-', $key);
              $korvladaID = $words[count($words)-1];
              $korvlada = 'korvlada-'.$words[count($words)-1];
              update_post_meta($postid, $korvlada, $value);
            }
          }

          // if(isset($query['company_want-delivery']) && $query['company_want-delivery'] == '1'){
          //   update_post_meta($postid, 'companyWantDelivery', 'ja');
          // }
          // else {
          //   update_post_meta($postid, 'companyWantDelivery', 'nej');
          // }
        }

        return $postid;
      }
    }
  }



  function get_company(){
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array();
    $query = wp_parse_args($url['query']);
    $nonce_id = $json_api->get_nonce_id('korvlador', 'get_company');
    if (!wp_verify_nonce($json_api->query->nonce, $nonce_id) || get_current_user_role() != 'salesperson') {
      $json_api->error("Your 'nonce' value was incorrect. Use the 'get_nonce' API method.");
    }

    $returnVal = false;
    if(is_numeric($query['id'])){
      $company = get_post($query['id']);
      if($company->post_author == get_current_user_id()){
        $postMeta = get_post_meta($company->ID);
        $postMeta['companyName'] = $company->post_title;
        $postMeta['companyKorven'] = $company->ID;
        $returnVal = json_encode($postMeta);
      }
    }
    // var_dump($returnVal);
    return $returnVal;
  }



}
