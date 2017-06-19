<?php

class Figgins_Client {
  public function admin_page() {
    add_menu_page( 'Figgins Client', 'Figgins Client', 'manage_options', plugin_basename( __DIR__ . '/admin.php'), '', 'dashicons-welcome-learn-more', 4 );
  }
}
