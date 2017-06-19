<?php

class Figgins_Client_Admin {

  // check for POST to page
  public function post_exists( $post_name ) {
    return isset($_POST[ $post_name ]);
  }

  // update option in database
  public function update_success( $post_name ) {
    return update_option( 'figgins_client_secret', $_POST[ $post_name ], false );
  }

  // update message
  public function update_message( $post_name, $previous_secret ) {
    if ( self::post_exists( $post_name ) ) {
      if ( self::update_success( $post_name ) ) {
        return '<br><p style="color:green;font-weight:800;">Application ID set, thank you!</p>';
      }
      if ( $previous_secret ==  $_POST[ $post_name ] ) {
        return '<br><p style="color:#555d66;font-weight:800;">Application ID not updated, no change detected.</p>';
      }
      return '<br><p style="color:red;font-weight:800;">Application ID not updated.</p>';
    }
    return '';
  }

  public function display_admin_page() {
    $post_name = 'app_id';
    $previous_secret = get_option( 'figgins_client_secret' );
    $post_message = self::update_message( $post_name, $previous_secret );
    $secret = get_option( 'figgins_client_secret' );
    $html = '';

    $html = '<br>';
    $html .= '<form method="post">';

    if ($secret) {
      $html .= '<h3>Application ID: </h3>';
      $html .= '<input type="text" value="' . $secret . '" name="' . $post_name . '">';
      $html .= '<input type="submit" value="Edit">';
    } else {
      $html .= '<h3>Please set the Application ID: </h3>';
      $html .= 'Application ID: ';
      $html .= '<input type="text" name="' . $post_name . '">';
      $html .= '<input type="submit" value="Set">';
    }

    // update figgins_client_secret option in DB
    $html .= $post_message;

    $html .= '</form>';
    return $html;
  }
}

//delete_option( 'figgins_client_secret' );
echo Figgins_Client_Admin::display_admin_page();
