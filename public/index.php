<?php
/**
 * Front controller
 */

/**
 * Composer
 */
require( __DIR__ . '/../vendor/autoload.php' );

/**
 * DB data
 */
if ( file_exists( __DIR__ . '/../db.cfg.php' ) ) {
  include __DIR__ . '/../db.cfg.php';
}
/**
 * Error and Exception handling
 */
error_reporting( E_ALL );
set_error_handler( 'Core\Error::errorHandler' );
set_exception_handler( 'Core\Error::exceptionHandler' );

/**
 * Session
 */
session_start();

/**
 * Routing
 */
$router = new Core\Router();

/**
 * Adds routs
 */
$router->add( '', [ 'controller' => 'Home', 'action' => 'index' ] );
$router->add( 'profile-client', [ 'controller' => 'ProfileClient', 'action' => 'index' ] );
$router->add( 'profile-shop', [ 'controller' => 'ProfileShop', 'action' => 'index' ] );
$router->add( 'cancel', [ 'controller' => 'Cancel', 'action' => 'index' ] );
$router->add( 'code-sms', [ 'controller' => 'CodeSms', 'action' => 'index' ] );
$router->add( 'success', [ 'controller' => 'Success', 'action' => 'index' ] );
$router->add( 'error', [ 'controller' => 'Error', 'action' => 'index' ] );
$router->add( 'phone-number', [ 'controller' => 'PhoneNumber', 'action' => 'index' ] );
$router->add( 'test', [ 'controller' => 'Test', 'action' => 'index' ] );
$router->add( 'shop-admin', [ 'controller' => 'Integration', 'action' => 'index', 'namespace' => 'ShopAdmin' ] );
$router->add( 'shop-admin/integration',
  [ 'controller' => 'Integration', 'action' => 'index', 'namespace' => 'ShopAdmin' ] );
$router->add( 'shop-admin/history', [ 'controller' => 'History', 'action' => 'index', 'namespace' => 'ShopAdmin' ] );
$router->add( 'shop-admin/requisites',
  [ 'controller' => 'Requisites', 'action' => 'index', 'namespace' => 'ShopAdmin' ] );
$router->add( 'shop-admin/staff', [ 'controller' => 'Staff', 'action' => 'index', 'namespace' => 'ShopAdmin' ] );
$router->add( 'shop-admin/login', [ 'controller' => 'Login', 'action' => 'index', 'namespace' => 'ShopAdmin' ] );
$router->add( '{controller}/{action}' );
$router->add( 'shop-admin/{controller}/{action}', [ 'namespace' => 'ShopAdmin' ] );
$router->add( 'shop-admin/{controller}/{id:\d+}/{action}', [ 'namespace' => 'ShopAdmin' ] );


$router->dispatch( $_SERVER['QUERY_STRING'] );
