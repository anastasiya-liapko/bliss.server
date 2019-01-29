<?php

namespace App\Controllers\ShopAdmin;

use App\Auth;
use App\Models\IntegrationPlugin;
use App\Models\Shop;
use App\SiteInfo;
use Core\View;

/**
 * Integration controller
 */
class Integration extends \Core\Controller {

  /**
   * Shows the integration page
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

    $shop_id = $current_admin->getShopId();
    $shop    = Shop::findById( $shop_id );

    View::renderTemplate( 'ShopAdmin/Integration/index.html', [
      'title'        => 'Код кнопки',
      'body_class'   => 'body_admin',
      'current_role' => $current_admin->getRole(),
      'plugins'      => IntegrationPlugin::getAll(),
      'shop_id'      => $shop_id,
      'secret_key'   => $shop->getSecretKey()
    ] );
  }
}
