<?php

namespace App\Controllers;

use App\Flash;
use App\Models\Client;
use App\Models\ClientInitialData;
use App\Models\SMS;
use App\SiteInfo;
use Core\View;

/**
 * PhoneNumber controller
 */
class PhoneNumber extends \Core\Controller {

  /**
   * Shows index page
   *
   * Unset the code_sms_timer cookie.
   * Receives data from a post query.
   * Checks and guards input data.
   * If the input is incorrect, sends to the error page.
   * If everything is OK, it shows the page with the form for entering the phone number.
   * And sets the token in the cookies.
   *
   * @return void
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   * @throws \Exception
   */
  public function indexAction() {

    if ( isset( $_COOKIE['code_sms_timer'] ) ) {
      setcookie( 'code_sms_timer', '', time() - 3600, '/' );
    }

    $client_initial_data = new ClientInitialData( $_POST );

    if ( ! $client_initial_data->save() ) {
      $errors = $client_initial_data->getErrors();

      foreach ( $errors as $error ) {
        Flash::addMessage( $error, Flash::WARNING );
      }

      $this->redirect( SiteInfo::getSiteURL() . '/error' );
    }

    setcookie( 'remembered_client', $client_initial_data->getToken(), $client_initial_data->getTokenExpiresAt(), '/' );

    View::renderTemplate( 'PhoneNumber/index.html', [
      'title'        => 'Подтверждение телефона',
      'body_class'   => 'body_phone-number',
      'phone_number' => SiteInfo::PHONE,
      'phone_link'   => SiteInfo::getCleanPhone(),
      'form_action'  => SiteInfo::getSiteURL() . '/phone-number/get-code'
    ] );
  }

  /**
   * Saves the phone and sends a sms-code (Ajax)
   *
   * Forbids not ajax request.
   * Saves the phone number.
   * If a client with the phone number sent exists, save his id in clients_initial_table.
   * Sends the sms-code to the client.
   * If everything is OK, redirects to the code sms page.
   * Sends json-response on failure.
   *
   * @return void
   * @throws \Exception
   */
  public function getCodeAction() {
    $this->forbidNotAjax();

    $client_initial_data = ClientInitialData::findByToken( $_COOKIE['remembered_client'] );

    if ( ! $client_initial_data->savePhone( $_POST['phone'] ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $client_initial_data->getErrors() ] );
    }

    if ( $client = Client::findByPhone( $client_initial_data->getPhone() ) ) {
      $client_initial_data->saveClientId( $client->getId() );
    }

    if ( ! SMS::sendSms( $client_initial_data->getPhone(), $client_initial_data->getSmsCode() ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'Не удалось отправить код, попробуйте ещё раз.' ] ] );
    }

    $this->redirect( SiteInfo::getSiteURL() . '/code-sms' );
  }
}
