<?php the_content(); ?>
<?php $currentUserRole = get_current_user_role(); ?>

  <div class="row">


    <div class="col-md-5">
      <h1>Välkommen</h1>
      <?php
        $args = array(
        'post_type' => 'welcome',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        );

        $query = new WP_Query($args);

        foreach ($query->posts as $post) {
          if(($currentUserRole == 'salesperson' && get_post_meta($post->ID, 'type', true) == 'Säljare') ||
            ($currentUserRole == 'associationDelagate' && get_post_meta($post->ID, 'type', true) == 'Föreningsansvarig') ||
            ($currentUserRole == 'manager' && get_post_meta($post->ID, 'type', true) == 'Lagledare')){
            echo $post->post_content;
          }
        }
          wp_reset_postdata();
      ?>
    </div>


    <div class="col-md-7">
      <h1>FAQ</h1>
      <?php
        $args = array(
        'post_type' => 'faq',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        );

        $query = new WP_Query($args);
        // dump($query->posts);
        foreach ($query->posts as $post) {
          if(get_post_meta($post->ID, 'type', true) == 'Alla'){
          echo '<div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title">'.$post->post_title.'</h3>
                  </div>
                  <div class="panel-body">
                    '.$post->post_content.'
                  </div>
                </div>';
          }
        }
        foreach ($query->posts as $post) {
          if(($currentUserRole == 'salesperson' && get_post_meta($post->ID, 'type', true) == 'Säljare') ||
            ($currentUserRole == 'associationDelagate' && get_post_meta($post->ID, 'type', true) == 'Föreningsansvarig') ||
            ($currentUserRole == 'manager' && get_post_meta($post->ID, 'type', true) == 'Lagledare')){
            echo '<div class="panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title">'.$post->post_title.'</h3>
                    </div>
                    <div class="panel-body">
                      '.$post->post_content.'
                    </div>
                  </div>';
          }
        }
        wp_reset_postdata();
      ?>
    </div>
  </div>
