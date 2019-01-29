<?php

namespace App\Controllers;

use App\Models\ClientInitialData;
use App\Models\SMS;
use App\SiteInfo;
use Core\View;

/**
 * CodeSms controller
 */
class CodeSms extends \Core\Controller {

  /**
   * Checks if the token is valid and phone is exists
   *
   * If validation fails redirects to the error page.
   * If the phone number is verified redirects to the profile client page.
   *
   * @return void
   * @throws \Exception
   */
  protected function before() {
    parent::before();

    if ( ! isset( $_COOKIE['remembered_client'] ) ||
         ! ClientInitialData::isTokenValid( $_COOKIE['remembered_client'] ) ||
         ! ClientInitialData::isPhoneExists( $_COOKIE['remembered_client'] )
    ) {
      $this->redirect( SiteInfo::getSiteURL() . '/error' );
    }

    if ( ClientInitialData::isPhoneVerified( $_COOKIE['remembered_client'] ) ) {
      $this->redirect( SiteInfo::getSiteURL() . '/profile-client' );
    }
  }

  /**
   * Shows the index page
   *
   * If the code_sms_timer cookie is not exists, creates it.
   * Shows the index page with the timer.
   *
   * @return void
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function indexAction() {

    if ( isset( $_COOKIE['code_sms_timer'] ) ) {
      $timer_end = $_COOKIE['code_sms_timer'];
    } else {
      $timer_end        = time() + 180; // 90 seconds from now
      $timer_expires_at = time() + 60 * 60 * 1; // 1 hour from now
      setcookie( 'code_sms_timer', $timer_end, $timer_expires_at, '/' );
    }

    View::renderTemplate( 'CodeSms/index.html', [
      'title'        => 'Код из смс',
      'body_class'   => 'body_code-sms',
      'phone_number' => SiteInfo::PHONE,
      'phone_link'   => SiteInfo::getCleanPhone(),
      'terms_link'   => SiteInfo::getSiteURL() . '/documents/agreements.pdf',
      'form_action'  => SiteInfo::getSiteURL() . '/code-sms/check-code',
      'link_action'  => SiteInfo::getSiteURL() . '/code-sms/get-code',
      'timer_end'    => $timer_end
    ] );
  }

  /**
   * Checks sms-code (Ajax)
   *
   * Forbids not ajax request.
   * If validation not fails redirects to the profile client page.
   * Sends json-response on failure.
   *
   * @return void
   * @throws \Exception
   */
  public function checkCodeAction() {
    $this->forbidNotAjax();

    $data = [
      'sms_code' => $_POST['sms_code'] ?? '',
      'terms'    => $_POST['terms'] ?? '',
      'token'    => $_COOKIE['remembered_client']
    ];

    $sms = new SMS( $data );

    if ( ! $sms->checkCode() || ! $sms->verifyPhone() ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $sms->getErrors() ] );
    }

    $this->redirect( SiteInfo::getSiteURL() . '/profile-client' );
  }

  /**
   * Gets new sms-code (Ajax)
   *
   * Forbids not ajax request.
   * Regenerates the sms-code and sends it to the client.
   * Sends json-response on success or failure.
   *
   * @return void
   * @throws \Exception
   */
  public function getCodeAction() {
    $this->forbidNotAjax();

    $client_initial_data = ClientInitialData::findByToken( $_COOKIE['remembered_client'] );

    if ( ! $client_initial_data->regenerateSmsCode() ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'Не удалось создать код, попробуйте ещё раз.' ] ] );
    }

    if ( ! SMS::sendSms( $client_initial_data->getPhone(), $client_initial_data->getSmsCode() ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'Не удалось отправить код, попробуйте ещё раз.' ] ] );
    }

    $this->sendJsonResponse( [ 'error' => false, 'message' => [ 'Новый код отправлен.' ] ] );
  }
}
