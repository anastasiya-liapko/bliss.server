<?php

namespace App;

use App\Models\ShopAdmin;
use App\Models\Login;

/**
 * Authentication
 *
 */
class Auth {

  /**
   * Logins the admin
   *
   * @param ShopAdmin $admin The admin model.
   * @param boolean $remember_me Remember the login if true.
   *
   * @return void
   * @throws
   */
  public static function login( $admin, $remember_me ) {
    session_regenerate_id( true );

    $_SESSION['admin_id'] = $admin->getId();

    if ( $remember_me ) {

      if ( $admin->rememberLogin() ) {
        setcookie( 'remembered_admin', $admin->getRememberToken(), $admin->getExpiryTimestamp(), '/' );
      }
    }
  }

  /**
   * Logout the user
   *
   * @return void
   * @throws \Exception
   */
  public static function logout() {
    // Unset all of the session variables
    $_SESSION = [];

    // Delete the session cookie
    if ( ini_get( 'session.use_cookies' ) ) {
      $params = session_get_cookie_params();

      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
      );
    }

    // Finally destroy the session
    session_destroy();

    static::forgetLogin();
  }

  /**
   * Remember the originally-requested page in the session
   *
   * @return void
   */
  public static function rememberRequestedPage() {
    $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
  }

  /**
   * Get the originally-requested page to return to after requiring login, or default to the homepage
   *
   * @return string
   */
  public static function getReturnToPage() {
    return $_SESSION['return_to'] ?? '/';
  }

  /**
   * Get the current logged-in admin, from the session or the remember-me cookie
   *
   * @return mixed The user model or null if not logged in.
   * @throws \Exception
   */
  public static function getAdmin() {
    if ( isset( $_SESSION['admin_id'] ) ) {
      return ShopAdmin::findByID( $_SESSION['admin_id'] );
    } else {
      return static::loginFromRememberCookie();
    }
  }

  /**
   * Logins the user from a remembered login cookie
   *
   * @return mixed The admin model if login cookie found, false otherwise.
   * @throws \Exception
   */
  protected static function loginFromRememberCookie() {
    $cookie = $_COOKIE['remembered_admin'] ?? false;

    if ( $cookie ) {
      $remembered_login = Login::findByToken( $cookie );

      if ( $remembered_login && ! $remembered_login->hasExpired() ) {
        $admin = $remembered_login->getAdmin();

        static::login( $admin, false );

        return $admin;
      }
    }

    return false;
  }

  /**
   * Forget the remembered login, if present
   *
   * @return void
   * @throws \Exception
   */
  protected static function forgetLogin() {
    $cookie = $_COOKIE['remembered_admin'] ?? false;

    if ( $cookie ) {
      $remembered_login = Login::findByToken( $cookie );

      if ( $remembered_login ) {
        $remembered_login->delete();
      }

      setcookie( 'remembered_admin', '', time() - 3600 );  // set to expire in the past
    }
  }
}
