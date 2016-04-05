<?php
/**
* Plugin Name: Custom Music Reviews
* Plugin URI: http://elegantthemes.com/
* Description: A custom music review plugin built for example.
* Version: 1.0
* Author: Andy Leverenz
* Author URI: http://justalever.com/
**/


// https://premium.wpmudev.org/blog/creating-wordpress-admin-pages/

add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_menu() {
	add_menu_page( 'Låsa beställningar', 'Lås beställningar', 'manage_options', 'myplugin/myplugin-admin-page.php', 'myplguin_admin_page', 'dashicons-tickets', 6  );
}
