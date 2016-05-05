<?php


function get_team($atts) {
  global $wp_roles;

  extract(shortcode_atts(array(
      'team' => null,
      'useridsstring' => null,
   ), $atts));


  $usersIDs = explode(',', $useridsstring);

  // dump($usersIDs);
	$table = '';
	if(in_array($team, $usersIDs)){

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
    </form>';

	}
  else{
    $table = 'finns inte några';
  }

	return $table;
}
add_shortcode('get-team', 'get_team');
