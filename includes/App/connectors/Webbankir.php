<?php

namespace App\connectors;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Webbankir {
  /**
   * Logger
   *
   * @var object
   */
  private $logger;

  /**
   * API URL
   *
   * @var string
   */
  const API_URL = 'https://demo.webbankir.partners/api-rmk/v1/';

  /**
   * API ID
   *
   * @var int
   */
  private $api_id;

  /**
   * API password
   *
   * @var string
   */
  private $api_password;

  /**
   * HTTP Client
   *
   * @var object
   */
  private $http_client;

  /**
   * Token
   *
   * @var string
   */
  private $token;

  /**
   * Expiration
   *
   * @var string
   */
  private $expiration;

  /**
   * Request data
   *
   * @var array
   */
  public $request_data;

  /**
   * Customer ID
   *
   * @var int
   */
  private $customer_id;

  /**
   * Customer current limit
   *
   * @var float
   */
  private $customer_current_limit;

  /**
   * Confirm customer code
   *
   * @var float
   */
  private $confirm_customer_code = '1111';

  /**
   * Sale ID
   *
   * @var int
   */
  private $sale_id;

  /**
   * Loan period in months
   *
   * @var int
   */
  private $loan_period = 6;

  /**
   * Webbankir constructor
   *
   * @param int $id
   * @param string $password
   * @param array $request_data
   *
   * @throws \Exception
   */
  public function __construct( int $id, string $password, array $request_data ) {
    $this->api_id       = $id;
    $this->api_password = $password;
    $this->request_data = $request_data;

    $this->http_client = new Client( [
      'base_uri' => static::API_URL,
      'headers'  => [
        'Content-Type' => 'application/json'
      ],
      'handler'  => $this->createLoggingHandlerStack()
    ] );
  }

  /**
   * Creates Logging Handler Stack
   *
   * @return HandlerStack
   * @throws \Exception
   */
  private function createLoggingHandlerStack() {
    $this->logger = new Logger( 'WebbankirLog' );
    $this->logger->pushHandler( new StreamHandler( $_SERVER['DOCUMENT_ROOT'] . '/logs/webbankir.log' ) );

    $stack = HandlerStack::create();
    $stack->push( Middleware::log( $this->logger,
      new MessageFormatter( '{method} {uri} HTTP/{version} {req_body} RESPONSE: {code} - {res_body}' ) ) );

    return $stack;
  }

  /**
   * Start
   *
   * @return mixed
   */
  public function start() {

    if ( ! $this->receiveToken() ) {
      return false;
    }

    try {
      $response = $this->http_client->get( "user?phone={$this->request_data['phone']}", [
        'headers' => [
          'Authorization' => "Bearer {$this->token}"
        ]
      ] );

      // Клиент имеется в системе и имеет неизрасходованный предварительно одобренный лимит.
      if ( $response->getStatusCode() === 200 ) {
        $stream = $response->getBody();
        $stream->rewind();
        $result = json_decode( $stream->getContents() );

        $this->customer_id            = $result->data->id;
        $this->customer_current_limit = $result->data->current_limit;

        if ( $this->customer_current_limit > $this->request_data['order_price'] ) {
          return $this->createLoan();
        }
      }

      // Клиент имеется в системе, но не имеет предварительно одобренного лимита.
      if ( $response->getStatusCode() === 204 ) {
        return json_encode( [
          'status'       => 'wait_limit',
          'api_id'       => $this->api_id,
          'api_password' => $this->api_password,
          'request_data' => $this->request_data
        ] );
      }

    } catch ( RequestException $e ) {

      // Заёмщик не найден в системе, требуется направить клиента на интерфейсы подачи заявки на лимит.
      if ( $e->hasResponse() && $e->getResponse()->getStatusCode() === 404 ) {
        return $this->createCustomer();
      }

    }

    return false;
  }

  /**
   * Confirms a loan
   *
   * @param int $customer_id
   * @param int $sale_id
   * @param string $sms_code
   *
   * @return mixed
   */
  public function confirm( int $customer_id, int $sale_id, string $sms_code ) {

    if ( ! $this->receiveToken() ) {
      return false;
    }

    $this->customer_id = $customer_id;
    $this->sale_id     = $sale_id;

    try {
      $response = $this->http_client->patch( "user/{$this->customer_id}/sale/{$this->sale_id}/code/{$sms_code}", [
        'headers' => [
          'Authorization' => "Bearer {$this->token}"
        ]
      ] );

      // код верный, займ подписан
      if ( $response->getStatusCode() === 201 ) {
        return json_encode( [
          'status'        => 'approved',
          'customer_id'   => $this->customer_id,
          'current_limit' => $this->customer_current_limit,
          'sale_id'       => $this->sale_id
        ] );
      }

    } catch ( RequestException $e ) {
      // nothing to do
    }

    return false;
  }

  /**
   * Receives token
   *
   * @return bool
   */
  private function receiveToken() {

    try {
      $response = $this->http_client->get( "cashier/{$this->api_id}/token?password={$this->api_password}" );

      // Запрос обработан успешно.
      if ( $response->getStatusCode() === 200 ) {
        $stream = $response->getBody();
        $stream->rewind();
        $result = json_decode( $stream->getContents() );

        $this->token      = $result->data->token;
        $this->expiration = $result->data->expiration;

        return true;
      }

    } catch ( RequestException $e ) {
      // nothing to do
    }

    return false;
  }

  /**
   * Creates a loan
   *
   * @return bool
   */
  private function createLoan() {
    $goods = unserialize( $this->request_data['goods'] );

    foreach ( $goods as &$item ) {
      $item['operation_type'] = 'loan';
    }

    try {
      $response = $this->http_client->post( "user/{$this->customer_id}/sale", [
        'headers' => [
          'Authorization' => "Bearer {$this->token}",
          'Content-Type'  => 'application/json'
        ],
        'body'    => json_encode( [
          'period'    => $this->loan_period,
          'own_funds' => 0,
          'products'  => $goods
        ] )
      ] );

      // Займ создан, требуется подписание займа кодом из SMS сообщения.
      if ( $response->getStatusCode() === 201 ) {
        $stream = $response->getBody();
        $stream->rewind();
        $result = json_decode( $stream->getContents() );

        $this->sale_id = $result->data->id;

        return $this->sendConfirmLoanCode();
      }

    } catch ( RequestException $e ) {
      // nothing to do
    }

    return false;
  }

  /**
   * Sends a code to confirm a loan
   *
   * @return bool
   */
  private function sendConfirmLoanCode() {

    try {
      $response = $this->http_client->put( "user/{$this->customer_id}/sale/{$this->sale_id}/code", [
        'headers' => [
          'Authorization' => "Bearer {$this->token}"
        ]
      ] );

      // код отправлен на телефон клиента
      if ( $response->getStatusCode() === 201 ) {
        return json_encode( [
          'status'        => 'wait_confirm_loan',
          'customer_id'   => $this->customer_id,
          'current_limit' => $this->customer_current_limit,
          'sale_id'       => $this->sale_id,
          'request_data'  => $this->request_data,
          'terms'         => $this->getTerms()
        ] );
      }

    } catch ( RequestException $e ) {
      // nothing to do
    }

    return false;
  }

  /**
   * Creates a customer
   *
   * @return mixed
   */
  private function createCustomer() {

    try {
      $response = $this->http_client->post( "customer", [
        'headers' => [
          'Authorization' => "Bearer {$this->token}",
          'Content-Type'  => 'application/json'
        ],
        'body'    => json_encode( [
          'first_name'   => $this->request_data['first_name'],
          'last_name'    => $this->request_data['last_name'],
          'middle_name'  => $this->request_data['middle_name'],
          'mobile_phone' => $this->request_data['phone']
        ] )
      ] );

      // Параметры верные, пользователь создан.
      // В момент получения этого кода пользователю отправляется SMS сообщение на его номер телефона.
      if ( $response->getStatusCode() === 201 ) {
        $stream = $response->getBody();
        $stream->rewind();
        $result = json_decode( $stream->getContents() );

        $this->customer_id = $result->data->id;

        return $this->confirmCustomer( $this->confirm_customer_code );
      }

    } catch ( RequestException $e ) {
      // nothing to do
    }

    return false;
  }

  /**
   * Confirms a customer
   *
   * @param string $sms_code
   *
   * @return bool
   */
  private function confirmCustomer( string $sms_code ) {

    try {
      $response = $this->http_client->post( "customer/{$this->customer_id}/code", [
        'headers' => [
          'Authorization' => "Bearer {$this->token}",
          'Content-Type'  => 'application/json',
        ],
        'body'    => json_encode( [
          'sms_code' => $sms_code
        ] )
      ] );

      // Код верный, можно переходить к дальнейшему шагу
      if ( $response->getStatusCode() === 204 ) {
        return $this->sendCustomerPassportData();
      }

    } catch ( RequestException $e ) {
      // nothing to do
    }

    return false;
  }

  /**
   * Sends customer passport data
   *
   * @return bool
   */
  private function sendCustomerPassportData() {
    $passport_division_name = $this->getPassportDivisionName( $this->request_data['passport_division_code'] );
    $address_fiases         = $this->getAddress( $this->request_data['reg_city'] . ', ' . $this->request_data['reg_street'] . ', ' . $this->request_data['reg_building'] . ', ' . $this->request_data['reg_apartment'] );

    if ( $passport_division_name !== false && $address_fiases !== false ) {

      try {
        $response = $this->http_client->post( "customer/{$this->customer_id}/passport", [
          'headers' => [
            'Authorization' => "Bearer {$this->token}",
            'Content-Type'  => 'application/json'
          ],
          'body'    => json_encode( [
            'birth_day'         => date( 'Y-m-d', strtotime( $this->request_data['birth_date'] ) ),
            'birth_place'       => $this->request_data['birth_place'],
            'series_and_number' => $this->request_data['passport_number'],
            'division_code'     => $this->request_data['passport_division_code'],
            'issue_date'        => date( 'Y-m-d', strtotime( $this->request_data['passport_issued_date'] ) ),
            'issued_by'         => $passport_division_name,
            'address'           => [
              'postal_code'        => $address_fiases['data']['postal_code'],
              'region'             => $address_fiases['data']['region_kladr_id'],
              'city'               => $address_fiases['data']['city'],
              'settlement'         => $address_fiases['data']['settlement'],
              'street'             => $address_fiases['data']['street'],
              'do_not_have_street' => empty( $address_fiases['data']['street'] ) ? 0 : 1,
              'house'              => $address_fiases['data']['house'],
              'flat'               => $this->request_data['reg_apartment'],
            ],
            'address_fiases'    => [
              'value'              => $address_fiases['value'],
              'region_fias_id'     => $address_fiases['data']['region_fias_id'],
              'city_fias_id'       => $address_fiases['data']['city_fias_id'],
              'settlement_fias_id' => $address_fiases['data']['settlement_fias_id'],
              'street_fias_id'     => $address_fiases['data']['street_fias_id'],
              'house_fias_id'      => $address_fiases['data']['house_fias_id'],
            ]
          ] )
        ] );

        if ( $response->getStatusCode() === 204 ) {
          return $this->sendCustomerSalaryData();
        }

      } catch ( RequestException $e ) {
        // nothing to do
      }
    }

    return false;
  }

  /**
   * Sends customer salary data
   *
   * @return bool
   */
  private function sendCustomerSalaryData() {

    try {
      $response = $this->http_client->post( "customer/{$this->customer_id}/beneficial-and-salary", [
        'headers' => [
          'Authorization' => "Bearer {$this->token}",
          'Content-Type'  => 'application/json'
        ],
        'body'    => json_encode( [
          'salary'     => $this->request_data['salary'],
          'inn'        => $this->request_data['tin'],
          'work_name'  => $this->request_data['workplace'],
          'beneficial' => null
        ] )
      ] );

      if ( $response->getStatusCode() === 204 ) {
        return json_encode( [
          'status'       => 'wait_limit',
          'api_id'       => $this->api_id,
          'api_password' => $this->api_password,
          'request_data' => $this->request_data
        ] );
      }

    } catch ( RequestException $e ) {
      // nothing to do
    }

    return false;
  }

  /**
   * Gets a schedule
   *
   * @return mixed
   */
  private function getTerms() {
    $goods = unserialize( $this->request_data['goods'] );

    foreach ( $goods as &$item ) {
      $item['operation_type'] = 'loan';
    }

    try {
      $response = $this->http_client->patch( "schedule", [
        'headers' => [
          'Authorization' => "Bearer {$this->token}"
        ],
        'body'    => json_encode( [
          'period'   => $this->loan_period,
          'user_id'  => $this->customer_id,
          'products' => $goods,
        ] )
      ] );

      if ( $response->getStatusCode() === 200 ) {
        $stream = $response->getBody();
        $stream->rewind();
        $result = json_decode( $stream->getContents(), true );

        return $result['data'];
      }

    } catch ( RequestException $e ) {
      // nothing to do
    }

    return false;
  }

  /**
   * Gets an address
   *
   * @param string $address
   *
   * @return mixed
   */
  private function getAddress( string $address ) {

    try {
      $response = $this->http_client->get( "address?search=" . urldecode( $address ), [
        'headers' => [
          'Authorization' => "Bearer {$this->token}"
        ]
      ] );

      if ( $response->getStatusCode() === 200 ) {
        $stream = $response->getBody();
        $stream->rewind();
        $result = json_decode( $stream->getContents(), true );

        return $result['data']['suggestions'][0];
      }

    } catch ( RequestException $e ) {
      // nothing to do
    }

    return false;
  }

  /**
   * Gets a passport division name
   *
   * @param string $passport_division_code
   *
   * @return mixed
   */
  private function getPassportDivisionName( string $passport_division_code ) {

    try {
      $response = $this->http_client->get( "passport/division_code/{$passport_division_code}", [
        'headers' => [
          'Authorization' => "Bearer {$this->token}"
        ]
      ] );

      if ( $response->getStatusCode() === 200 ) {
        $stream = $response->getBody();
        $stream->rewind();
        $result = json_decode( $stream->getContents(), true );

        return $result['data']['value'];
      }

    } catch ( RequestException $e ) {
      // nothing to do
    }

    return false;
  }
}
