<?php


// Add a widget in WordPress Dashboard
function widget_companies_function() {
  $args = array(
    'numberposts' => 10,
    'offset' => 0,
    'category' => 0,
    'orderby' => 'post_date',
    'order' => 'DESC',
    // 'include' => ,
    // 'exclude' => ,
    // 'meta_key' => ,
    // 'meta_value' =>,
    'post_type' => 'companies',
    'post_status' => 'draft, publish, future, pending, private',
    'suppress_filters' => true
  );

  $recent_posts = wp_get_recent_posts( $args );

  $html = '<table class="wp-list-table widefat fixed striped posts">
  <tr>
  <th>Företag</th>
  <th>Registrerat</th>
  </tr>';
  // dump($recent_posts);

  foreach ($recent_posts as $post) {
    $html .= '<tr><td>'.$post['post_title'].'</td><td>'.explode(' ',$post['post_date'])[0].'</td></tr>';
  }

  $html .= '</table>';

  echo $html;
}
function add_companies_widget_to_dashboard() {
	wp_add_dashboard_widget('wp_dashboard_widget', 'De senaste registrerade företagen', 'widget_companies_function');
}
add_action('wp_dashboard_setup', 'add_companies_widget_to_dashboard' );
