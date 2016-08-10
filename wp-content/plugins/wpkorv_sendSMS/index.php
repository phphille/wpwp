<?php
/**
* Plugin Name: Skicka SMS
* Plugin URI:
* Description: Skicka SMS
* Version: 1.0
* Author: WP-korv
**/

function enqueue_sms_script_css() {

  wp_register_script('wp_sms_tabs_bootstrap', plugin_dir_url( __FILE__ ) . '/assets/js/tab.js');
  wp_enqueue_script('wp_sms_tabs_bootstrap');

  wp_register_script('wp_sms_send_sms', plugin_dir_url( __FILE__ ) . '/assets/js/sendSMS.js');
  wp_enqueue_script('wp_sms_send_sms');

  wp_register_style('wp_sms_bootstrap', plugin_dir_url( __FILE__ ) . '/assets/css/bootstrap.min.css');
  wp_enqueue_style('wp_sms_bootstrap');

  wp_register_style('wp_sms_custom_css', plugin_dir_url( __FILE__ ) . '/assets/css/send-sms-main.css');
  wp_enqueue_style('wp_sms_custom_css');

  // wp_enqueue_script( 'adminSms',  '/wp-content/themes/wintherperson/assets/scripts/admin-scripts/adminSms.js');
}
add_action( 'admin_enqueue_scripts', 'enqueue_sms_script_css' );


add_action('admin_menu', 'admin_sms_menu');
function admin_sms_menu() {
	add_menu_page('Skicka SMS', 'Skicka SMS', 'administrator', 'skicka_sms', 'admin_send_sms', 5);
}

function admin_send_sms() {
  ?>

    <div class="col-sm-12">
      <form class="form-inline" action="" method="post">
      <input type="hidden" name="skmosrv" value="<?php echo wp_create_nonce('wpkorv_sendSMS-memsms')?>">
      <h3>Skicka:</h3>

      <div class="col-ms-12">
        <?php wp_editor( '', 'sms_content', ['media_buttons'=> false, 'textarea_name' => 'content', 'teeny' => true]); ?>
      </div>

      <h3>Till:</h3>
      <!-- Nav tabs -->
      <!-- <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#assocation" aria-controls="home" role="tab" data-toggle="tab">Förening</a></li>
        <li role="presentation"><a href="#team" aria-controls="team" role="tab" data-toggle="tab">Lag</a></li>
        <li role="presentation"><a href="#association-delegates" aria-controls="association-delegates" role="tab" data-toggle="tab">Alla Föreningsansvariga</a></li>
        <li role="presentation"><a href="#teamleaders" aria-controls="teamleaders" role="tab" data-toggle="tab">Alla lagledare</a></li>
        <li role="presentation"><a href="#salespersons" aria-controls="salespersons" role="tab" data-toggle="tab">Alla säljare</a></li>
      </ul> -->
      <!-- Tab panes -->
      <!-- <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="assocation">
        </div>
        <div role="tabpanel" class="tab-pane" id="team">
        </div>
        <div role="tabpanel" class="tab-pane" id="association-delegates">...</div>
        <div role="tabpanel" class="tab-pane" id="teamleaders">...</div>
        <div role="tabpanel" class="tab-pane" id="salespersons">...</div>
      </div> -->

      <div class="radio col-sm-3 no-padding-left">
        <label>
          <input type="radio" name="group" value="association">
          Förening
        </label>
        <br>
        <?php
          $args  = array(
          'role' => 'associationDelegate',
          'orderby' => 'display_name',
          );
          $wp_user_query = new WP_User_Query($args);
          $resLeaders = $wp_user_query->get_results();
         ?>

         <select name="association" class="form-control">
           <option value="" disabled="" selected="">Välj en förening</option>
           <?php foreach ($resLeaders as $resLeader) :?>
             <option value="<?php echo $resLeader->ID; ?>"><?php echo get_user_meta($resLeader->ID, 'association', true); ?></option>
           <?php endforeach;?>
         </select>
      </div>


      <div class="radio col-sm-3">
        <label>
          <input type="radio" name="group" value="team">
          Lag
        </label>
        <br>
        <?php
          wp_reset_postdata();
          $args  = array(
          'role' => 'manager',
          );

          $wp_user_query = new WP_User_Query($args);
          $resLeaders = $wp_user_query->get_results();
        ?>
        <select name="team" class="form-control">
          <option value="" disabled="" selected="">Välj ett lag</option>
          <?php foreach ($resLeaders as $resLeader) :?>
            <option value="<?php echo $resLeader->ID; ?>"><?php echo get_user_meta($resLeader->ID, 'team', true); ?></option>
          <?php endforeach;?>
        </select>
      </div>


      <div class="radio col-sm-2">
        <label>
          <input type="radio" name="group" value="associationDelegate">
          Alla Föreningsansvariga
        </label>
      </div>

      <div class="radio col-sm-2">
        <label>
          <input type="radio" name="group" value="manager">
          Alla lagledare
        </label>
      </div>

      <div class="radio col-sm-2">
        <label>
          <input type="radio" name="group" value="salesperson">
          Alla säljare
        </label>
      </div>


      <div class="submit-container col-sm-12">
        <input class="btn btn-default adminSendSms" type="submit" value="Skicka">
      </div>
    </form>
  </div>



<?php
}



// function admin_send_sms_association(){
//   $html = '';
//   if(isset($_POST['assocation']) && is_numeric($_POST['assocation']) && wp_verify_nonce($_POST['forening'],'admin_send_sms_association')){
//     $users = explode(',', get_user_meta($_POST['assocation'], 'userids', true));
//     // dump($users);
//   }
//
//   return $html;
// }
//
// function admin_send_sms_team(){
//
//   if(isset($_POST['team']) && is_numeric($_POST['assocation']) && wp_verify_nonce($_POST['forening'],'admin_send_sms_association')){
//     return do_shortcode('[get-team team="'.$_POST['team'].'" useridsstring="'.$useridsString.'"]');
//   }
// }


function do_send_sms($message, $recipients){
  var_dump($message);
  var_dump($recipients);
  // $nonce = rawurlencode(uniqid());
  // $ts = rawurlencode(time());
  // $key = rawurlencode('OCApjV6SQZ9I9oZGfyCOmcSA');
  // $secret = rawurlencode('t@jTJpsBte0Iys824TlbqQD-hJPWdl^#c2!lu)kM');
  // $uri = 'https://gatewayapi.com/rest/mtsms';
  // $method = 'POST';
  //
  // // OAuth 1.0a - Signature Base String
  // $oauth_params = array(
  //   'oauth_consumer_key' => $key,
  //   'oauth_nonce' => $nonce,
  //   'oauth_signature_method' => 'HMAC-SHA1',
  //   'oauth_timestamp' => $ts,
  //   'oauth_version' => '1.0',
  // );
  // $sbs = $method . '&' . rawurlencode($uri) . '&';
  // $it = new ArrayIterator($oauth_params);
  // while ($it->valid()) {
  //   $sbs .= $it->key() . '%3D' . $it->current();$it->next();
  //   if ($it->valid()) $sbs .= '%26';
  // }
  //
  // // OAuth 1.0a - Sign SBS with secret
  // $sig = base64_encode(hash_hmac('sha1', $sbs, $secret . '&', true));
  // $oauth_params['oauth_signature'] = rawurlencode($sig);
  //
  // // Construct Authorization header
  // $it = new ArrayIterator($oauth_params);
  // $auth = 'Authorization: OAuth ';
  // while ($it->valid()) {
  //   $auth .= $it->key() . '="' . $it->current() . '"';$it->next();
  //   if ($it->valid()) $auth .= ', ';
  // }
  //
  // // Request body
  // $req = array(
  //   'recipients' => $recipients, //array(array('msisdn' => 46709779308)),
  //   'message' => $message,
  // );
  //
  //
  // // Send request with cURL
  // $c = curl_init($uri);
  // curl_setopt($c, CURLOPT_HTTPHEADER, array(
  //     $auth,
  //     'Content-Type: application/json'
  // ));
  // curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($req));
  // curl_exec($c);
}









add_action("wp_ajax_wpkorv_sendSMS", "wpkorv_sendSMS");
add_action("wp_ajax_nopriv_wpkorv_sendSMS", "wpkorv_sendSMS");

function wpkorv_sendSMS(){
  global $wpdb;

  if (wp_verify_nonce($_POST['skmosrv'],'wpkorv_sendSMS-memsms')) {
    $query = '';

    switch ($_POST['group']) {
      case 'association':
        if(is_numeric($_POST['association'])){
          $query =
           "SELECT um1.meta_value AS `msisdn`
            FROM wp_usermeta AS um1
            LEFT JOIN wp_users AS u ON u.ID = um1.user_id
            LEFT JOIN wp_usermeta AS um2 ON u.ID = um2.user_id
            WHERE (um2.meta_value = ".$_POST['association']." AND um2.meta_key = 'associationDelegateParentId'
            AND um1.meta_key = 'phone')
            OR (u.id = ".$_POST['association']." AND um1.meta_key = 'phone')
            GROUP BY u.ID";
        }

        break;
      case 'team':
        if(is_numeric($_POST['team'])){
          $query =
           "SELECT um1.meta_value AS `msisdn`
            FROM wp_usermeta AS um1
            LEFT JOIN wp_users AS u ON u.ID = um1.user_id
            LEFT JOIN wp_usermeta AS um2 ON u.ID = um2.user_id
            WHERE (um2.meta_value = ".$_POST['team']." AND um2.meta_key = 'managerParentId'
            AND um1.meta_key = 'phone')
            OR (u.id = ".$_POST['team']." AND um1.meta_key = 'phone')
            GROUP BY u.ID";
        }
        break;
      case 'associationDelegate':
        $query =
         "SELECT um1.meta_value AS `msisdn`
          FROM wp_usermeta AS um1
          LEFT JOIN wp_users AS u ON u.ID = um1.user_id
          LEFT JOIN wp_usermeta AS um2 ON u.ID = um2.user_id
          WHERE um2.meta_key = 'wp_capabilities'
              AND um2.meta_value LIKE '%associationDelegate%'
          AND um1.meta_key = 'phone'";
        break;
      case 'manager':
        $query =
         "SELECT um1.meta_value AS `msisdn`
          FROM wp_usermeta AS um1
          LEFT JOIN wp_users AS u ON u.ID = um1.user_id
          LEFT JOIN wp_usermeta AS um2 ON u.ID = um2.user_id
          WHERE um2.meta_key = 'wp_capabilities'
              AND um2.meta_value LIKE '%manager%'
          AND um1.meta_key = 'phone'";
        break;
      case 'salesperson':
        $query =
         "SELECT um1.meta_value AS `msisdn`
          FROM wp_usermeta AS um1
          LEFT JOIN wp_users AS u ON u.ID = um1.user_id
          LEFT JOIN wp_usermeta AS um2 ON u.ID = um2.user_id
          WHERE um2.meta_key = 'wp_capabilities'
              AND um2.meta_value LIKE '%salesperson%'
          AND um1.meta_key = 'phone'";
        break;
      default:
        # code...
        break;
    }


    // $wp_user_query = new WP_User_Query($args);
    if($query != '' && $_POST['content'] != ''){
      $results = $wpdb->get_results( $query, 'ARRAY_A' );
      do_send_sms($_POST['content'], $results);
    }
  }

}
