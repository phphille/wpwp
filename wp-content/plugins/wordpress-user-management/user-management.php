<?php
/**
 * Plugin Name: Wordpress User Management
 * Plugin URI: http://github.com/philipehsing
 * Description: Give specific users control over other specific users
 * Version: 1.0
 * Author: Philip Essy-Ehsing
 * Author URI: http://github.com/philipehsing
 * License: GPLv2 or later
 */

 include_once 'extras/userfields.php';
 include_once 'extras/shortcodes.php';
 $new_user_id = isset($new_user_id) ? $new_user_id : '';
 do_action('user_register', $new_user_id);
 add_action( 'user_register', 'myplugin_registration_save', 10, 1 );


 function myplugin_registration_save( $new_user_id ) {

    $userids = get_the_author_meta('userids', get_current_user_id());
    $userids .= empty($userids) ? $new_user_id : ','.$new_user_id;

    update_user_meta( get_current_user_id(),'userids', $userids );
 }

/*
 * Let editors manage users, and run this only once.
 */
class easy_user_management {

    // Add our filters
    function user_management() {
        add_filter('easy_editable_roles', array( &$this, 'easy_editable_roles' ));
        add_filter('map_meta_cap', array( &$this, 'map_meta_cap' ), 10, 4);

        if (get_option('user_capabillity') != 'done') {

          // Editor capabillitys
          $edit_editor = get_role('editor');
          $edit_editor->add_cap('edit_users');
          $edit_editor->add_cap('list_users');
          $edit_editor->add_cap('create_users');
          $edit_editor->add_cap('add_users');
          $edit_editor->add_cap('delete_users');

          update_option('user_capabillity', 'done');
        }
    }

    // Remove 'Administrator' from the list of roles if the current user is not an admin
    function easy_editable_roles($roles) {
        if (isset($roles['administrator']) && !current_user_can('administrator')) {
            unset($roles['administrator']);
        }
        return $roles;
    }

    // If editor is trying to edit or delete, dont allow
    function map_meta_cap($caps, $cap, $user_id, $args) {
        switch ($cap) {
            case 'edit_user':
            case 'remove_user':
            case 'promote_user':
                if (isset($args[0]) && $args[0] == $user_id)
                    break;
                elseif (!isset($args[0]))
                    $caps[] = 'do_not_allow';
                $other = new WP_User(absint($args[0]));
                if ($other->has_cap('administrator')) {
                    if (!current_user_can('administrator')) {
                        $caps[] = 'do_not_allow';
                    }
                } else if (current_user_can('administrator')){
                  break;
                } else {
                    $can_user_edit = false;
                    // get user id's and explode to array
                    $user_edit_ids = get_the_author_meta('userids', $user_id);
                    $array         = explode(",", $user_edit_ids);

                    // loop to see if the user has rights to edit users
                    foreach ($array as $value) {
                        if ((int) $value == $args[0]) {
                            $can_user_edit = true;
                        }
                    }

                    // if user cannot edit, disable edit function!!!
                    if (!$can_user_edit) {
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
            case 'delete_user':
            case 'delete_users':
                if (!isset($args[0]))
                    break;
                $other = new WP_User(absint($args[0]));
                if ($other->has_cap('administrator')) {
                    if (!current_user_can('administrator')) {
                        $caps[] = 'do_not_allow';
                    }
                }
                break;
            default:
                break;
        }
        return $caps;

    }

}

$easy_user_management = new easy_user_management();
