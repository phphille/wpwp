<?php
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_show_extra_profile_fields($user) { ?>
	<br><hr>
	<h3>Kontaktuppgifter:</h3>
	<table class="form-table">
		<tr>
      <th><label for="address">Adress:</label></th>
			<td><input type="text" name="address" id="address" value="<?php echo esc_attr( get_the_author_meta( 'address', $user->ID ) ); ?>" class="regular-text" /><br /></td>
    </tr>
    <tr>
      <th><label for="phone">Telefonnummer:</label></th>
			<td><input type="text" name="phonenumber" id="phone" value="<?php echo esc_attr( get_the_author_meta( 'phonenumber', $user->ID ) ); ?>" class="regular-text" /><br /></td>
    </tr>
	</table>
	<br><hr>
	<h3>Profiluppgifter</h3>
	<table class="form-table">
		<tr>
      <th><label for="association">Förening:</label></th>
			<!-- <td><input type="text" name="association" id="association" value="<?php // echo esc_attr( get_the_author_meta( 'association', $user->ID ) ); ?>" class="regular-text" /><br /></td> -->
			<td>
					<?php
						$reggedAssociations = [];
						foreach (get_users() as $users => $tempuser) {
							if (get_user_meta($tempuser->ID, 'association', true) != '' && !in_array(get_user_meta($tempuser->ID, 'association', true), $reggedAssociations)) {
								$reggedAssociations[] = get_user_meta($tempuser->ID, 'association', true);
							}
						}
					?>
				<select class="" name="association">
					<option value="">Välj en förening</option>
					<?php foreach ($reggedAssociations as $value) {
						$selected = $value == esc_attr( get_the_author_meta( 'association', $user->ID ) ) ? 'selected' : '';
						echo '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
					} ?>
				</select>
			</td>
    </tr>
		<tr>
			<th><label>Ny förening?</label> <input type="checkbox" name="add_new_association" value="add"></th>
			<td><label for="association">Ny förening:</label> <input type="text" name="new_association" id="association" value="" class="regular-text" /><br /></td>
    </tr>
		<tr>
      <th><label for="team">Lag:</label></th>
			<td>
				<?php
						$reggedTeams = [];
						foreach (get_users() as $users => $tempuser) {
							if (get_user_meta($tempuser->ID, 'team', true) != '' && !in_array(get_user_meta($tempuser->ID, 'team', true), $reggedTeams)) {
								$reggedTeams[] = get_user_meta($tempuser->ID, 'team', true);
							}
						}
					?>
				<select class="" name="team">
					<option value="">Välj ett Lag</option>
					<?php foreach ($reggedTeams as $value) {
						$selected = $value == esc_attr( get_the_author_meta( 'team', $user->ID ) ) ? 'selected' : '';
						echo '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
					} ?>
			</td>
    </tr>
    <tr>
      <th><label>Nytt lag?</label> <input type="checkbox" name="add_new_team" value="add"></th>
			<td><label for="team">Nytt lag:</label> <input type="text" name="new_team" id="team" value="" class="regular-text" /><br /></td>
    </tr>
	</table>
	<br><hr>
	<h3>Intern kommentar</h3>
	<table class="form-table">
    <tr>
			<td><textarea rows="5" cols="10" name="user_comment" class="regular-text" style="resize:vertical;"><?php echo esc_attr( get_the_author_meta( 'user_comment', $user->ID ) ); ?></textarea></td>
    </tr>
	</table>
<?php }


add_action( 'personal_options_update', 'save_user_custom_fields' );
add_action( 'edit_user_profile_update', 'save_user_custom_fields' );


// save custom user fields
function save_user_custom_fields( $user_id ) {
		// check if checkbox is checked or not
		if (isset($_POST['address'])) {
			update_user_meta( $user_id,'address', sanitize_text_field( $_POST['address'] ) );
		} else {
			update_user_meta( $user_id,'address', sanitize_text_field( '' ) );
		}
		// check if checkbox is checked or not
		if (isset($_POST['phonenumber'])) {
			update_user_meta( $user_id,'phonenumber', sanitize_text_field( $_POST['phonenumber'] ) );
		} else {
			update_user_meta( $user_id,'phonenumber', sanitize_text_field( '' ) );
		}
		// check if checkbox is checked or not
		if (isset($_POST['association']) || isset($_POST['new_association'])) {
			$associationValue = isset($_POST['add_new_association']) ? sanitize_text_field( $_POST['new_association'] ) : sanitize_text_field( $_POST['association'] );
			update_user_meta( $user_id,'association', $associationValue );
		} else {
			update_user_meta( $user_id,'association', sanitize_text_field( '' ) );
		}
		// check if checkbox is checked or not
		if (isset($_POST['team']) || isset($_POST['new_team'])) {
			$teamValue = isset($_POST['add_new_team']) ? sanitize_text_field( $_POST['new_team'] ) : sanitize_text_field( $_POST['team'] );
			update_user_meta( $user_id,'team', $teamValue );
		} else {
			update_user_meta( $user_id,'team', sanitize_text_field( '' ) );
		}
		// check if checkbox is checked or not
		if (isset($_POST['user_comment'])) {
			update_user_meta( $user_id,'user_comment', sanitize_text_field( $_POST['user_comment'] ) );
		} else {
			update_user_meta( $user_id,'user_comment', sanitize_text_field( '' ) );
		}

		// update user fields
    // update_user_meta( $user_id,'address', sanitize_text_field( $_POST['address'] ) );
    // update_user_meta( $user_id,'phonenumber', sanitize_text_field( $_POST['phonenumber'] ) );
		// update_user_meta( $user_id,'association', sanitize_text_field( $_POST['association'] ) );
		// update_user_meta( $user_id,'team', sanitize_text_field( $_POST['team'] ) );
		// update_user_meta( $user_id,'user_comment', sanitize_text_field( $_POST['user_comment'] ) );
}
