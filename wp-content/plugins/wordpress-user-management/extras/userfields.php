<?php

add_action( 'show_user_profile', 'custom_user_id_fields' );
add_action( 'edit_user_profile', 'custom_user_id_fields' );

function custom_user_id_fields( $user ) { ?>
  <?php if (current_user_can('administrator')) { ?>
    <br><br>
    <hr>
    <h4>Allow user to edit user id's</h4>
    <input type="text" name="userids" id="userids" value="<?php echo esc_attr( get_the_author_meta( 'userids', $user->ID ) ); ?>" class="regular-text" /><br />
  <? } ?>
<?php }

add_action( 'personal_options_update', 'save_custom_user_id_fields' );
add_action( 'edit_user_profile_update', 'save_custom_user_id_fields' );

function save_custom_user_id_fields( $user_id ) {
	if (current_user_can('administrator') ){
  	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
  	update_user_meta( $user_id, 'userids', $_POST['userids'] );
  }
}
