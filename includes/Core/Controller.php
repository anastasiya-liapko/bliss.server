<?php

namespace Core;

/**
 * Base controller
 */
abstract class Controller {

  /**
   * Parameters from the matched route
   *
   * @var array
   */
  protected $route_params = [];

  /**
   * Class constructor
   *
   * @param array $route_params Parameters from the route
   *
   * @return void
   */
  public function __construct( $route_params ) {
    $this->route_params = $route_params;
  }

  /**
   * Magic method
   *
   * Called when a non-existent or inaccessible method is
   * called on an object of this class. Used to execute before and after
   * filter methods on action methods. Action methods need to be named
   * with an "Action" suffix, e.g. indexAction, showAction etc.
   *
   * @param string $name Method name.
   * @param array $args Arguments passed to the method.
   *
   * @return void
   * @throws \Exception
   */
  public function __call( $name, $args ) {
    $method = $name . 'Action';

    if ( method_exists( $this, $method ) ) {
      if ( $this->before() !== false ) {
        call_user_func_array( [ $this, $method ], $args );
        $this->after();
      }
    } else {
      throw new \Exception( "Method $method not found in controller " . get_class( $this ) );
    }
  }

  /**
   * Before filter
   *
   * Called before an action method.
   *
   * @return void
   */
  protected function before() {
  }

  /**
   * After filter
   *
   * Called after an action method.
   *
   * @return void
   */
  protected function after() {
  }

  /**
   * Gets the site protocol
   *
   * @return string
   */
  public function getSiteProtocol() {
    return isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' . '://';
  }

  /**
   * Redirect to a different page
   *
   * @param string $url The relative URL.
   *
   * @return void
   */
  public function redirect( $url ) {
    if ( $this->isAjaxRequest() ) {
      $this->sendJsonResponse( [ 'redirect' => $url ] );
    } else {
      header( 'Location: ' . $url, true, 303 );
      exit;
    }
  }

  /**
   * Checks request is ajax
   *
   * @return bool
   */
  public function isAjaxRequest() {
    return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' );
  }

  /**
   * Forbids not ajax request
   *
   * @return void
   *
   * @throws \Exception
   */
  public function forbidNotAjax() {
    if ( ! $this->isAjaxRequest() ) {
      throw new \Exception( 'No route matched.', 404 );
    }
  }

  /**
   * Sends json response
   *
   * @param mixed $data
   *
   * @return void
   */
  public function sendJsonResponse( $data ) {
    header( 'Content-Type: application/json' );
    echo json_encode( $data, JSON_UNESCAPED_UNICODE );
    die();
  }
}
