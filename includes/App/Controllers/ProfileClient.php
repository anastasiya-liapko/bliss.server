<?php

namespace App\Controllers;

use App\Models\Client;
use App\Models\ClientInitialData;
use App\Models\Request;
use App\Models\ShopAdmin;
use App\SiteInfo;
use Core\View;

/**
 * Customer profile controller
 */
class ProfileClient extends \Core\Controller {

  /**
   * Checks if the token is valid and phone is verified
   *
   * If validation fails redirects to the code sms page.
   *
   * @return void
   * @throws \Exception
   */
  public function before() {
    parent::before();

    if ( ! isset( $_COOKIE['remembered_client'] ) ||
         ! ClientInitialData::isTokenValid( $_COOKIE['remembered_client'] ) ||
         ! ClientInitialData::isPhoneVerified( $_COOKIE['remembered_client'] )
    ) {
      $this->redirect( SiteInfo::getSiteURL() . '/code-sms' );
    }
  }

  /**
   * Shows the index page
   *
   * Unset the code_sms_timer cookie.
   * Identifies the client by phone number and order.
   * If the client previously registered displays his data.
   * If there is a request to credit organizations, it shows a modal window with a timer.
   * If the loan is approved redirects to the success page.
   * If the loan is denied redirects to the cancel page.
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

    $args = [
      'title'        => 'Анкета покупателя',
      'body_class'   => 'body_profile',
      'phone_number' => SiteInfo::PHONE,
      'phone_link'   => SiteInfo::getCleanPhone(),
      'form_action'  => SiteInfo::getSiteURL() . '/profile-client/make-credit-application',
    ];

    $client_initial_data = ClientInitialData::findByToken( $_COOKIE['remembered_client'] );

    if ( $client_id = $client_initial_data->getClientId() ) {

      $shop_id  = $client_initial_data->getShopId();
      $order_id = $client_initial_data->getOrderId();

      if ( $request = Request::findForClient( $client_id, $shop_id, $order_id ) ) {
        $request_status = $request->getStatus();
        $timer_end      = strtotime( $request->getTimeStart(), time() ) + 180;

        switch ( $request_status ) {
          case 'pending':
            if ( time() > $timer_end ) {
              $args['modal_no_response_open'] = true;
            } else {
              $args['modal_send_request_open'] = true;
              $args['timer_enable']            = true;
              $args['timer_end']               = $timer_end * 1000;
              $args['progressbar_end_after']   = 180;
            }
            break;
          case 'cancel':
            $args['modal_no_response_open'] = true;
            break;
          case 'manual':
            $args['modal_wait_response_open'] = true;
            break;
          case 'approved':
            $this->redirect( SiteInfo::getSiteURL() . '/success' );
            break;
          case 'declined':
            $this->redirect( SiteInfo::getSiteURL() . '/cancel' );
            break;
        }
      }
    }

    if ( $client = Client::findByPhone( $client_initial_data->getPhone() ) ) {
      $args['client'] = [
        'last_name'              => $client->getLastName(),
        'first_name'             => $client->getFirstName(),
        'middle_name'            => $client->getMiddleName(),
        'birth_date'             => date( 'Y-m-d', strtotime( $client->getBirthDate() ) ),
        'birth_place'            => $client->getBirthPlace(),
        'passport_number'        => $client->getPassportNumber(),
        'passport_division_code' => $client->getPassportDivisionCode(),
        'passport_issued_by'     => $client->getPassportIssuedBy(),
        'passport_issued_date'   => date( 'Y-m-d', strtotime( $client->getPassportIssuedDate() ) ),
        'reg_zip_code'           => $client->getRegZipCode(),
        'reg_city'               => $client->getRegCity(),
        'reg_street'             => $client->getRegStreet(),
        'reg_building'           => $client->getRegBuilding(),
        'reg_apartment'          => $client->getRegApartment(),
        'is_address_matched'     => $client->getIsAddressMatched(),
        'fact_zip_code'          => $client->getFactZipCode(),
        'fact_city'              => $client->getFactCity(),
        'fact_street'            => $client->getFactStreet(),
        'fact_building'          => $client->getFactBuilding(),
        'fact_apartment'         => $client->getFactApartment(),
        'email'                  => $client->getEmail()
      ];
    }

    View::renderTemplate( 'ProfileClient/index.html', $args );
  }

  /**
   * Makes credit application (Ajax)
   *
   * Forbids not ajax request.
   * Saves the client data.
   * Saves the client id in the clients_initial_data table.
   * If the application has already been created to show the error.
   * Creates the new request and show the modal.
   *
   * @return void
   * @throws \Exception
   */
  public function makeCreditApplicationAction() {
    $this->forbidNotAjax();

    $client_initial_data = ClientInitialData::findByToken( $_COOKIE['remembered_client'] );

    $_POST['phone'] = $client_initial_data->getPhone();

    $client = new Client( $_POST );

    if ( ! $client->save() ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $client->getErrors() ] );
    }

    $client_id = $client->getId();

    if ( ! $client_initial_data->saveClientId( $client_id ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $client_initial_data->getErrors() ] );
    }

    $shop_id  = $client_initial_data->getShopId();
    $order_id = $client_initial_data->getOrderId();

    if ( Request::findForClient( $client_id, $shop_id, $order_id ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'Заявка уже создана' ] ] );
    }

    $request = new Request( [
      'client_id'        => $client->getId(),
      'shop_id'          => $client_initial_data->getShopId(),
      'order_id'         => $client_initial_data->getOrderId(),
      'order_price'      => $client_initial_data->getOrderPrice(),
      'goods'            => $client_initial_data->getGoods(),
      'is_loan_deferred' => $client_initial_data->getIsLoanDeferred(),
    ] );

    if ( ! $request->create() ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $request->getErrors() ] );
    }

    $this->sendJsonResponse( [
      'error'     => false,
      'openModal' => [ 'id' => '#js-modalSendRequest' ],
      'timer'     => [
        'id'          => '#js-profileClientTimer',
        'end'         => ( time() + 180 ) * 1000,
        'onFinish'    => [
          'closeModal' => [ 'id' => '#js-modalSendRequest' ],
          'openModal'  => [ 'id' => '#js-modalNoResponse' ]
        ],
        'progressBar' => [
          'id'       => '#js-profileClientProgressBar',
          'endAfter' => 180,
        ]
      ],
    ] );
  }

  /**
   * Cancels the credit application (Ajax)
   *
   * Forbids not ajax request.
   * Cancels the credit application, removes token cookie, and redirects to the callback url.
   *
   * @return void
   * @throws \Exception
   */
  public function cancelCreditApplicationAction() {
    $this->forbidNotAjax();

    $client_initial_data = ClientInitialData::findByToken( $_COOKIE['remembered_client'] );
    $client_id           = $client_initial_data->getClientId();
    $shop_id             = $client_initial_data->getShopId();
    $order_id            = $client_initial_data->getOrderId();

    $request = Request::findForClient( $client_id, $shop_id, $order_id );

    if ( ! $request->changeStatus( 'cancel', date( 'Y-m-d H:i:s', time() ) ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $request->getErrors() ] );
    }

    setcookie( 'remembered_client', '', time() - 3600, '/' );

    $this->redirect( $client_initial_data->getCallbackUrl() );
  }

  /**
   * Waits the credit application (Ajax)
   *
   * Forbids not ajax request.
   * Change the status of credit application.
   * Sends an email to admins of a shop.
   * Close a current modal and open a new modal.
   *
   * @return void
   * @throws \Exception
   */
  public function waitCreditApplicationAction() {
    $this->forbidNotAjax();

    $client_initial_data = ClientInitialData::findByToken( $_COOKIE['remembered_client'] );
    $client_id           = $client_initial_data->getClientId();
    $shop_id             = $client_initial_data->getShopId();
    $order_id            = $client_initial_data->getOrderId();

    $request = Request::findForClient( $client_id, $shop_id, $order_id );

    if ( ! $request->changeStatus( 'manual', date( 'Y-m-d H:i:s', time() ) ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $request->getErrors() ] );
    }

    if ( $admin_emails = ShopAdmin::getEmailsByShopId( $shop_id ) ) {
      Request::sendApplicationEmail( $request->getId() );
    }

    $this->sendJsonResponse( [
      'error'      => false,
      'closeModal' => [ 'id' => '#js-modalNoResponse' ],
      'openModal'  => [ 'id' => '#js-modalWaitResponse' ],
    ] );
  }
}
