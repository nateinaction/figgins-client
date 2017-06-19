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

    /*
     * Register POST to list available breaks
     * /wp-json/figgins_client/v1/list/
     */
    register_rest_route( $namespace, '/list', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => array( $this, 'list_breaks' ),
       'permission_callback' => array( $this, 'verify_token' ),
    ));

    /*
     * Register POST to run a break
     * /wp-json/figgins_client/v1/run/
     */
    register_rest_route( $namespace, '/run', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => array( $this, 'run_break' ),
       'permission_callback' => array( $this, 'verify_token' ),
    ));

    /*
     * Register POST to check a break
     * /wp-json/figgins_client/v1/run/
     */
    register_rest_route( $namespace, '/check', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => array( $this, 'check_break' ),
       'permission_callback' => array( $this, 'verify_token' ),
    ));

    /*
     * Register POST to fix a break
     * /wp-json/figgins_client/v1/fix/
     */
    register_rest_route( $namespace, '/fix', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => array( $this, 'fix_break' ),
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

  public function list_breaks( $request ) {
    $directory = plugin_dir_path( __FILE__ ) . '/breaks';
    $handle = opendir( $directory );
    $directory_contents = array();
    while ( false !== ($entry = readdir($handle)) ) {
      if ($entry != '.' && $entry != '..' ) {
        array_push( $directory_contents, $entry );
      }
    }
    return $directory_contents;
  }

  public function run_break( $request ) {
    $break = self::get_break( $request );
    if (!$break) {
      return 'This break does not exist';
    }
    return $break->break();
  }

  public function check_break( $request ) {
    $break = self::get_break( $request );
    if (!$break) {
      return 'This break does not exist';
    }
    return $break->check();
  }

  public function fix_break( $request ) {
    $break = self::get_break( $request );
    if (!$break) {
      return 'This break does not exist';
    }
    return $break->fix();
  }

  private function get_break( $request ) {
    $filename = $request['filename'];
    $directory = plugin_dir_path( __FILE__ ) . '/breaks';
    $location = $directory . '/' . $filename;

    if ( file_exists($location) ) {
      require_once $location;
      return new Figgins_Break();
    }
    return false;
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
