<?php

/*
 * Rest endpoint class
 * Run, Check, Fix
 */

require_once __DIR__ . '/vendor/autoload.php';

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class Figgins_Client_API {
  public function register_routes() {
    $version = '1';
    $namespace = 'figgins_client/v' . $version;

    /*
     * Register POST to run a break
     * /wp-json/figgins_client/v1/poke/#
     */
    register_rest_route( $namespace, '/poke', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => array( $this, 'hello_world' ),
      'permission_callback' => array( $this, 'verify_token' ),
    ));

  }

  public function hello_world() {
    return 'Hello, world!';
  }

  public function mirror_token( $request ) {
    return $request['token'];
  }

  public function verify_token( $request ) {
    $token = (new Parser())->parse((string) $request['token']);
    $secret = get_option( 'figgins_client_secret' );
    $signer = new Sha256();

    // if token does not match or has not been defined
    return $token->verify($signer, $secret);
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
