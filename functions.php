<?php
/*
Plugin Name: Figgins Client
Version:     0.0.1
Author:      Nate Gay
Author URI:  https://worldpeace.io/
License:     GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
*/

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/api.php';

function figgins_client_admin_page() {
  add_menu_page( 'Figgins Client', 'Figgins Client', 'manage_options', plugin_basename( __DIR__ . '/admin.php'), '', 'dashicons-welcome-learn-more', 4 );
}
add_action( 'admin_menu', 'figgins_client_admin_page' );
