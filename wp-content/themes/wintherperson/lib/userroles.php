<?php
// hide amdmin bar for all user except admin
// show_admin_bar(false);

// Hook on 'admin_init' to disable all users except admin to view wp-admin
// Redirect non-admin users to home page
add_action( 'admin_init', 'redirect_non_admin_users' );
function redirect_non_admin_users() {
	if ( ! current_user_can( 'manage_options' ) && '/wpkorv/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF'] ) {
		wp_redirect( 'konto' );
		exit;
	}
}

// check if user is blacklisted, logout user > redirect to login with message!
// function blacklisted_user() {
// 	if (get_the_author_meta( '_blacklist', get_current_user_id()) == 'checked') {
// 		wp_logout();
// 		echo '<script>window.location.href = "'.home_url().'/logga-in?mode=blocked";</script>';
// 	}
// }

// add_action('get_header', 'blacklisted_user');

// Add two custom user role, association delegate, manager, salesperson
$result = add_role( 'associationDelegate', 'Föreningsansvarig',array(
    'read' => true,
    'edit_posts' => true,
    'create_posts' => true,
    'publish_posts' => false,
    'edit_pages' => false,
    'edit_others_posts' => false,
    'manage_categories' => false,
    'edit_users' => true,
    'add_users' => true,
    'create_users' => true,
    'delete_users' => true,
  )
);

$result = add_role( 'manager', 'Lagledare',array(
    'read' => true,
    'edit_posts' => true,
    'create_posts' => true,
    'publish_posts' => false,
    'edit_pages' => false,
    'edit_others_posts' => false,
    'manage_categories' => false,
    'edit_users' => true,
    'add_users' => true,
    'create_users' => true,
    'delete_users' => true
  )
);


$result = add_role( 'salesperson', 'Säljare',array(
    'read' => true,
    'edit_posts' => true,
    'create_posts' => false,
    'publish_posts' => false,
    'edit_pages' => false,
    'edit_others_posts' => false,
    'manage_categories' => false,
  )
);

$result = remove_role( 'editor' );
$result = remove_role( 'subscriber' );
$result = remove_role( 'author' );
$result = remove_role( 'contributor' );
add_filter('show_admin_bar', '__return_false');

// function new_user_list_fields( $contactmethods ) {
//     $contactmethods['address'] = 'Adress';
// 		$contactmethods['phonenumber'] = 'Telefonnummer';
// 		$contactmethods['association'] = 'Förening';
// 		$contactmethods['team'] = 'Lag';
// 		$contactmethods['comment'] = 'Intern kommentar';
//     return $contactmethods;
// }
// add_filter( 'user_contactmethods', 'new_user_list_fields', 10, 1 );


function new_modify_user_table( $column ) {
    $column['address'] = 'Adress';
		$column['phonenumber'] = 'Telefonnummer';
		$column['association'] = 'Förening';
		$column['team'] = 'Lag';
		$column['comment'] = 'Intern kommentar';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );


function remove_user_posts_column($column_headers) {
    unset($column_headers['posts']);
    return $column_headers;
}
add_action('manage_users_columns','remove_user_posts_column');

function new_modify_user_table_row( $val, $column_name, $user_id ) {
  $user = get_userdata( $user_id );
	return get_the_author_meta( $column_name, $user_id );
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );

// add_action('manage_users_columns','remove_posts_from_list');
// function remove_posts_from_list($column_headers) {
//   unset($column_headers['posts']);
//   return $column_headers;
// }
