<?php

namespace App\Controllers\ShopAdmin;

use App\Auth;
use App\Models\ShopAdmin;
use App\SiteInfo;
use Core\View;

/**
 * Integration controller
 */
class Login extends \Core\Controller {

  /**
   * Shows the login page
   *
   * @return void
   * @throws \Exception
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function indexAction() {
    Auth::logout();

    View::renderTemplate( 'ShopAdmin/Login/index.html', [
      'title'       => 'Авторизация',
      'body_class'  => 'body_admin',
      'form_action' => SiteInfo::getSiteURL() . '/shop-admin/login/auth'
    ] );
  }

  /**
   * Auth action
   *
   * @return void
   */
  public function AuthAction() {
    $this->forbidNotAjax();

    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ( $current_admin = ShopAdmin::authenticate( $email, $password ) ) {
      Auth::login( $current_admin, true );
      $this->redirect( SiteInfo::getSiteURL() . '/shop-admin/integration' );
    }

    $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'В доступе отказано.' ] ] );
  }
}
