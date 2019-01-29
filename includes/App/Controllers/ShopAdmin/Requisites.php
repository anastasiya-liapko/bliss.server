<?php

namespace App\Controllers\ShopAdmin;

use App\Auth;
use App\Models\Shop;
use App\SiteInfo;
use Core\View;

/**
 * Integration controller
 */
class Requisites extends \Core\Controller {

  /**
   * Shows the requisites page
   *
   * @return void
   * @throws \Exception
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function indexAction() {
    $current_admin = Auth::getAdmin();

    if ( ! $current_admin || $current_admin->getActive() !== 1 ) {
      $this->redirect( SiteInfo::getSiteURL() . '/shop-admin/login' );
    }

    if ( $current_admin->getRole() !== 'admin' ) {
      throw new \Exception( 'No route matched.', 404 );
    }

    $shop_id = $current_admin->getShopId();
    $shop    = Shop::findById( $shop_id );

    View::renderTemplate( 'ShopAdmin/Requisites/index.html', [
      'title'        => 'Реквизиты магазина',
      'body_class'   => 'body_admin',
      'current_role' => $current_admin->getRole(),
      'form_action'  => SiteInfo::getSiteURL() . '/shop-admin/requisites/update',
      'type'         => $shop->getType(),
      'last_name'    => $shop->getLastName(),
      'first_name'   => $shop->getFirstName(),
      'middle_name'  => $shop->getMiddleName(),
      'tin'          => $shop->getTin(),
      'company_name' => $shop->getCompanyName(),
      'status'       => $shop->getActive() ? 'активен' : 'не активен'
    ] );
  }

  /**
   * Update the requisites
   *
   * @return void
   * @throws \Exception
   */
  public function updateAction() {
    $this->forbidNotAjax();

    $current_admin = Auth::getAdmin();

    if ( ! $current_admin || $current_admin->getRole() !== 'admin' || $current_admin->getActive() !== 1 ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'У вас нет прав на это действие.' ] ] );
    }

    $shop_id = $current_admin->getShopId();
    $shop    = Shop::findById( $shop_id );

    if ( ! $shop->update( $_POST ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $shop->getErrors() ] );
    }

    $shop->sendUpdateRequisitesEmail();

    $this->sendJsonResponse( [ 'error' => false, 'message' => [ 'Данные изменены успешно.' ] ] );
  }
}
