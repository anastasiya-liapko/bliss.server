<?php

namespace App\Controllers;

use App\Auth;
use App\Models\ShopAdmin;
use App\Models\Shop;
use App\SiteInfo;
use Core\View;

/**
 * ProfileShop controller
 */
class ProfileShop extends \Core\Controller {

  /**
   * Shows the index page
   *
   * @return void
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function indexAction() {
    View::renderTemplate( 'ProfileShop/index.html', [
      'title'        => 'Анкета магазина',
      'body_class'   => 'body_profile-shop',
      'phone_number' => SiteInfo::PHONE,
      'phone_link'   => SiteInfo::getCleanPhone(),
      'form_action'  => SiteInfo::getSiteURL() . '/profile-shop/create-account'
    ] );
  }

  /**
   * Creates the shop account and the admin account
   *
   * Forbids not ajax request.
   * Creates the shop account and the admin account.
   * Sends the email with a login and a password to the admin.
   * Sends json-response on success or failure.
   *
   * Creates the
   *
   * @return void
   * @throws \PHPMailer\PHPMailer\Exception
   * @throws \Rakit\Validation\RuleQuashException
   */
  public function createAccountAction() {
    $this->forbidNotAjax();

    $shop = new Shop( $_POST );

    if ( ! $shop->create() ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $shop->getErrors() ] );
    }

    $admin_data = [
      'name'    => $_POST['last_name'] . ' ' . $_POST['first_name'] . ' ' . $_POST['middle_name'],
      'email'   => $_POST['email'] ?? '',
      'shop_id' => $shop->getId(),
      'role'    => 'admin',
      'active'  => 1
    ];

    $admin = new ShopAdmin( $admin_data );

    if ( ! $admin->create() ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $admin->getErrors() ] );
    }

    if ( ! $admin->sendAuthEmail() ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $admin->getErrors() ] );
    }

    Auth::login( $admin, true );

    $this->redirect( SiteInfo::getSiteURL() . '/shop-admin/integration' );
  }
}
