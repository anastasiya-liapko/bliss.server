<?php

namespace App\Controllers;

use App\SiteInfo;
use Core\View;

/**
 * Success controller
 */
class Success extends \Core\Controller {

  /**
   * Shows the index page
   *
   * @return void
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function indexAction() {
    View::renderTemplate( 'Success/index.html', [
      'title'        => 'Вам одобрили кредит',
      'body_class'   => 'body_success',
      'phone_number' => SiteInfo::PHONE,
      'phone_link'   => SiteInfo::getCleanPhone(),
    ] );
  }
}
