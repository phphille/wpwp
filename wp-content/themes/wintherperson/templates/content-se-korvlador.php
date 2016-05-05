<?php the_content(); ?>



<?php if(get_current_user_role() == 'associationDelegate'):
  $html = '';
  $useridsString = get_user_meta(get_current_user_id(), 'userids', true);
  $userIDs = explode(',',$useridsString);
    foreach ($userIDs as $userID) {
      $user = new WP_User( $userID );
      if($user->roles[0] == 'manager'){
        $selected = '';
        if(isset($_POST['team']) && $userID == $_POST['team']){
          $selected = 'selected';
        }
        $html .= '<option value="'.$userID.'" '.$selected.'>'.get_user_meta($userID, 'team', true).'</option>';

      }
    }
  ?>

  <form method="POST" action="<?php the_permalink(); ?>">
    <select name="team" onchange="this.form.submit()">
      <option value="" disabled selected>Välj lag</option>
        <?php echo $html; ?>
    </select>
  </form>

  <?php
    if(isset($_POST['team']) && is_numeric($_POST['team'])){
      echo do_shortcode('[get-team team="'.$_POST['team'].'" useridsstring="'.$useridsString.'"]');
    }
   ?>

<?php endif; ?>


<?php


// echo do_shortcode('[authors]');

?>
