<?php

namespace App\Controllers;

use App\Models\Partner;
use App\Models\ShopAdmin;
use App\SiteInfo;
use Core\View;

/**
 * Home controller
 */
class Home extends \Core\Controller {

  /**
   * Shows the index page
   *
   * @return void
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function indexAction() {
    View::renderTemplate( 'Home/index.html', [
      'site_url' => SiteInfo::getSiteURL(),
      'title'        => SiteInfo::NAME,
      'body_class'   => 'body_home',
      'phone_number' => SiteInfo::PHONE,
      'phone_link'   => SiteInfo::getCleanPhone(),
      'partners'     => Partner::getAll()
    ] );
  }
}
