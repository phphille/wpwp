<?php

function show_custom_meta_box() {
global $custom_meta_fields, $post, $current_screen;
// Use nonce for verification
echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
    // dump(get_post($post->ID));
    // dump(get_post_meta($post->ID));
    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($custom_meta_fields[$current_screen->id] as $field) {

      if($field['name'] == 'heading'){
        echo '<tr><th col-span="2"></th><tr>';
        echo '<tr><th col-span="2">'.$field['label'].'</th><tr>';
      }
      else {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['name'], true);
        // begin a table row with
        echo '<tr>
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                <td>';
                switch($field['type']) {
                    case 'text':
                        echo '<input type="text" name="'.$field['name'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                            <br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    case 'textarea':
                        echo '<textarea name="'.$field['name'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
                            <br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    case 'checkbox':
                        echo '<input type="checkbox" name="'.$field['name'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
                            <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                    break;
                    case 'select':
                        echo '<select name="'.$field['name'].'" id="'.$field['id'].'">';
                        foreach ($field['options'] as $option) {
                            echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
                        }
                        echo '</select><br /><span class="description">'.$field['desc'].'</span>';
                    break;
                } //end switch
        echo '</td></tr>';
      }
    } // end foreach
    echo '</table>'; // end table

}


// Save the Data
function save_custom_meta($post_id) {
  global $custom_meta_fields, $current_screen;

  if(isset($_POST['custom_meta_box_nonce'])){
    $correct_meta_fields = $custom_meta_fields[$current_screen->id];
    // verify nonce
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ($_POST['post_type'] == 'page') {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }

    // loop through fields and save the data
    foreach ($correct_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['name'], true);
        $new = $_POST[$field['name']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['name'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['name'], $old);
        }
    } // end foreach
  }
}
add_action('save_post', 'save_custom_meta');
