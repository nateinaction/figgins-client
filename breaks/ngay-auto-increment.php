<?php

class Figgins_Break {
    public function author() {
      return 'Nate Gay';
    }

    public function break() {
      global $wpdb;
      return $wpdb->query( 'ALTER TABLE ' . $wpdb->posts . ' CHANGE ID ID BIGINT(20) UNSIGNED NOT NULL;' );
    }

    public function check() {
      global $wpdb;
      $result = $wpdb->get_results( 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "' . $wpdb->posts . '" AND EXTRA like "%auto_increment%";' );
      if ( !empty($result) ) {
        return true;
      }
      return false;
    }

    public function fix() {
      global $wpdb;
      $wpdb->delete( $wpdb->posts, array( 'ID' => 0 ) );
      return $wpdb->query( 'ALTER TABLE ' . $wpdb->posts . ' CHANGE ID ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;' );
    }
}
