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
require_once __DIR__ . '/figgins-client-class.php';

add_action( 'admin_menu', 'Figgins_Client::admin_page' );
