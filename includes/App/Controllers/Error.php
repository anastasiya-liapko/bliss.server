<?php

namespace App\Controllers;

use App\Models\ClientInitialData;
use App\SiteInfo;
use Core\View;

/**
 * Error controller
 */
class Error extends \Core\Controller {

  /**
   * Shows the index page
   *
   * @return void
   * @throws \Exception
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function indexAction() {
    $args = [
      'title'        => 'Работа с магазином временно недоступна',
      'body_class'   => 'body_error',
      'phone_number' => SiteInfo::PHONE,
      'phone_link'   => SiteInfo::getCleanPhone(),
    ];

    if ( isset( $_COOKIE['remembered_client'] ) && ! empty( $_COOKIE['remembered_client'] ) ) {
      if ( $client_initial_data = ClientInitialData::findByToken( $_COOKIE['remembered_client'] ) ) {
        $args['callback_url'] = $client_initial_data->getCallbackUrl();
      }
    }

    View::renderTemplate( 'Error/index.html', $args );
  }
}
