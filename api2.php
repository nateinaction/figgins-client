<?php

/*
 * Rest endpoint class
 * Run, Check, Fix
 */
require_once __DIR__ . '/vendor/autoload.php';

use Lcobucci\JWT\Signer\Hmac\Sha256;

class Figgins_Client_API {
 public function register_routes() {
   $version = '1';
   $namespace = 'figgins_client/v' . $version;

   /*
    * Register POST to run a break
    * /wp-json/figgins_client/v1/run/#
    */
   register_rest_route( $namespace, '/poke', array(
     'methods' => WP_REST_Server::CREATABLE,
     'callback' => array( $this, 'mirror_token' ),
     'permission_callback' => array( $this, 'blah' ),
   ));


   /*
    * Register POST to run a break
    * /wp-json/figgins_client/v1/run/#
    */
   register_rest_route( $namespace, '/run/(?P<id>\d+)', array(
     'methods' => WP_REST_Server::CREATABLE,
     'callback' => array( $this, 'run_break' ),
     'permission_callback' => array( $this, 'verify_token' ),
   ));

   /*
    * Register GET to check status of a break
    * /wp-json/figgins_client/v1/check/#
    */

   register_rest_route( $namespace, '/check/(?P<id>\d+)', array(
     array(
       'methods' => WP_REST_Server::READABLE,
       'callback' => array( $this, 'check_break' ),
       'permission_callback' => array( $this, 'verify_token' ),
     )
   ));

   /*
    * Register POST to fix a break
    * /wp-json/figgins_client/v1/fix/#
    */
   register_rest_route( $namespace, '/fix/(?P<id>\d+)', array(
     'methods' => WP_REST_Server::CREATABLE,
     'callback' => array( $this, 'fix_break' ),
     'permission_callback' => array( $this, 'verify_token' ),
   ));

 }

  public function blah() {
    return true;
  }

  public function mirror_token( $request ) {

  }

 /*
  * Verify Token Secret
  * @return bool
  */

  public function verify_token( $token ) {
    $secret = get_option( 'figgins_client_secret' );
    $signer = new Sha256();

    // if token does not match or has not been defined
    return $token->verify($signer, $secret);
  }

 /**
  * Run break #
  * @return bool
  */
 public function run_break( $request ) {
   $details = WpeSandbox::start_break( $request['id'] );
   //wp_logout();
   return rest_ensure_response( $details );
 }

 /**
  * Mark break # as fixed
  * @return bool
  */
 public function mark_complete( $request ) {
   $response = WpeSandbox::complete_break( $request['id'] );
   return rest_ensure_response( $response );
 }

 /**
  * To see the list of breaks, a user must have read rights
  * @return WP_Error|bool
  */
 public function list_breaks_permissions_check( $request ) {
   //return current_user_can( 'read' );
   return true;
 }

 /**
  * To execute breaks or mark them as fixed, a user must have edit rights
  * @return WP_Error|bool
  */
 public function execute_breaks_permissions_check( $request ) {
   //return current_user_can( 'edit_posts' );
   return true;
 }
}

/**
* Register routes class
*/
function register_figgins_client_api() {
   $controller = new Figgins_Client_API();
   $controller->register_routes();
}

add_action( 'rest_api_init', 'register_figgins_client_api' );
