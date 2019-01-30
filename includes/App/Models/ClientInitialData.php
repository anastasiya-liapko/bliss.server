<?php

namespace App\Models;

use App\PlainRule;
use App\Security;
use App\Token;
use PDO;
use Rakit\Validation\Validator;

/**
 * ClientInitialData model
 */
class ClientInitialData extends \Core\Model {

  /**
   * Error messages
   *
   * @var array
   */
  private $errors = [];

  /**
   * Client id
   *
   * @var mixed
   */
  private $client_id = null;

  /**
   * Client phone
   *
   * @var string
   */
  private $phone = '';

  /**
   * Verified
   *
   * @var int
   */
  private $verified = 0;

  /**
   * Shop id
   *
   * @var mixed
   */
  private $shop_id = null;

  /**
   * Order id
   *
   * @var mixed
   */
  private $order_id = null;

  /**
   * Order price
   *
   * @var mixed
   */
  private $order_price = null;

  /**
   * Callback url
   *
   * @var string
   */
  private $callback_url = '';

  /**
   * Is loan deferred
   *
   * @var int
   */
  private $is_loan_deferred = 0;

  /**
   * Goods
   *
   * @var string
   */
  private $goods = '';

  /**
   * Control sum
   *
   * @var string
   */
  private $control = '';

  /**
   * Request id
   *
   * @var string
   */
  private $request_id = '';

  /**
   * Token
   *
   * @var string
   */
  private $token = '';

  /**
   * Token hash
   *
   * @var string
   */
  private $token_hash = '';

  /**
   * Expiry token timestamp
   *
   * @var string
   */
  private $token_expires_at = '';

  /**
   * Sms code
   *
   * @var string
   */
  private $sms_code = '';

  /**
   * Expiry sms code timestamp
   *
   * @var string
   */
  private $sms_code_expires_at = '';

  /**
   * ClientInitialData constructor
   *
   * @param array $data Initial property values.
   */
  public function __construct( array $data = [] ) {
    foreach ( $data as $key => $value ) {
      $this->$key = $value;
    }
  }

  /**
   * Validates values
   *
   * @return void
   * @throws \Rakit\Validation\RuleQuashException
   */
  private function validate() {
    $validator = new Validator();

    $validator->addValidator( 'plain', new PlainRule() );

    $validation = $validator->validate( [
      'shop_id'          => $this->shop_id,
      'order_id'         => $this->order_id,
      'order_price'      => $this->order_price,
      'callback_url'     => $this->callback_url,
      'is_loan_deferred' => $this->is_loan_deferred,
      'goods'            => $this->goods,
      'control'          => $this->control
    ], [
      'shop_id'          => 'required|numeric',
      'order_id'         => 'required|numeric',
      'order_price'      => 'required|numeric',
      'callback_url'     => 'required|plain',
      'is_loan_deferred' => 'required|numeric',
      'goods'            => 'required|plain',
      'control'          => 'required|plain'
    ] );

    $validation->validate();

    if ( $validation->fails() ) {
      $this->errors = [ 'Запрос иммеет неверные параметры, обратитесь к администратору магазина.' ];
    }
  }

  /**
   * Validates the phone
   *
   * @param string $phone The phone number.
   *
   * @return void
   */
  private function validatePhone( string $phone ) {
    $validator = new Validator( [
      'required' => ':attribute - обязательное поле.',
      'regex'    => ':attribute - поле имеет некорректный формат.',
    ] );

    $validation = $validator->validate( [
      'phone' => $phone
    ], [
      'phone' => 'required|regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
    ] );

    $validation->setAliases( [
      'phone' => 'Номер телефона',
    ] );

    $validation->validate();

    if ( $validation->fails() ) {
      $this->errors = $validation->errors()->all();
    }
  }

  /**
   * Checks the initial data
   *
   * @return void
   */
  private function check() {
    if ( ! $shop = Shop::findById( $this->shop_id ) ) {
      $this->errors[] = 'Магазин не зарегистрирован на нашем сервисе, обратитесь к администратору магазина.';
    } elseif ( ! $shop->getActive() ) {
      $this->errors[] = 'К сожалению, работа с магазином ' . $shop->getCompanyName() . ' временно не доступна.';
    } elseif ( $this->control !== md5( $this->shop_id . $this->order_id . $this->order_price . $this->callback_url . $this->is_loan_deferred . $this->goods . $shop->getSecretKey() ) ) {
      $this->errors[] = 'Секретные ключи не совпадают, обратитесь к администратору магазина.';
    }
  }

  /**
   * Saves record in data base
   *
   * @return bool
   * @throws \Exception
   */
  public function save(): bool {
    $this->validate();

    if ( ! empty( $this->errors ) ) {
      return false;
    }

    $this->check();

    if ( ! empty( $this->errors ) ) {
      return false;
    }

    $token                     = new Token();
    $token_hash                = $token->getHash();
    $this->token               = $token->getToken();
    $this->token_expires_at    = time() + 60 * 60 * 24; // 24 hours from now
    $this->sms_code            = Security::generateSmsCode();
    $this->sms_code_expires_at = time() + 60 * 30; // 30 minutes from now

    $sql = 'INSERT INTO clients_initial_data (verified, control, shop_id, order_id, order_price, callback_url, is_loan_deferred, goods, token_hash, token_expires_at, sms_code, sms_code_expires_at)
			 VALUES (:verified, :control, :shop_id, :order_id, :order_price, :callback_url, :is_loan_deferred, :goods, :token_hash, :token_expires_at, :sms_code, :sms_code_expires_at)';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':verified', $this->verified, PDO::PARAM_INT );
    $stmt->bindValue( ':control', $this->control, PDO::PARAM_STR );
    $stmt->bindValue( ':shop_id', $this->shop_id, PDO::PARAM_INT );
    $stmt->bindValue( ':order_id', $this->order_id, PDO::PARAM_INT );
    $stmt->bindValue( ':order_price', $this->order_price, PDO::PARAM_STR );
    $stmt->bindValue( ':callback_url', $this->callback_url, PDO::PARAM_STR );
    $stmt->bindValue( ':is_loan_deferred', $this->is_loan_deferred, PDO::PARAM_INT );
    $stmt->bindValue( ':goods', $this->goods, PDO::PARAM_STR );
    $stmt->bindValue( ':token_hash', $token_hash, PDO::PARAM_STR );
    $stmt->bindValue( ':token_expires_at', date( 'Y-m-d H:i:s', $this->token_expires_at ), PDO::PARAM_STR );
    $stmt->bindValue( ':sms_code', $this->sms_code, PDO::PARAM_STR );
    $stmt->bindValue( ':sms_code_expires_at', date( 'Y-m-d H:i:s', $this->sms_code_expires_at ), PDO::PARAM_STR );

    if ( ! $stmt->execute() ) {
      $this->errors[] = 'Не удалось сохранить данные, попробуйте ещё раз.';

      return false;
    }

    return true;
  }

  /**
   * Saves the phone number
   *
   * @param string $phone The phone number.
   *
   * @return bool
   * @throws \Exception
   */
  public function savePhone( string $phone ): bool {
    $this->validatePhone( $phone );

    if ( empty( $this->errors ) ) {
      $this->phone = preg_replace( '/[-)+(\s]/', '', $phone );

      $sql = 'UPDATE clients_initial_data SET phone = :phone WHERE token_hash = :token_hash';

      $db   = static::getDB();
      $stmt = $db->prepare( $sql );

      $stmt->bindValue( ':phone', $this->phone, PDO::PARAM_STR );
      $stmt->bindValue( ':token_hash', $this->token_hash, PDO::PARAM_STR );

      if ( $stmt->execute() ) {
        return true;
      } else {
        $this->errors[] = 'Не удалось сохранить телефон, попробуйте ещё раз.';
      }
    }

    return false;
  }

  /**
   * Saves the client id
   *
   * @param int $client_id The client id.
   *
   * @return bool
   * @throws \Exception
   */
  public function saveClientId( int $client_id ): bool {

    $this->client_id = $client_id;

    $sql = 'UPDATE clients_initial_data SET client_id = :client_id WHERE token_hash = :token_hash';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':client_id', $this->client_id, PDO::PARAM_INT );
    $stmt->bindValue( ':token_hash', $this->token_hash, PDO::PARAM_STR );

    if ( $stmt->execute() ) {
      return true;
    } else {
      $this->errors[] = 'Не удалось сохранить id клиента, попробуйте ещё раз.';
    }

    return false;
  }

  /**
   * Saves the request id
   *
   * @param int $request_id The request id.
   *
   * @return bool
   * @throws \Exception
   */
  public function saveRequestId( int $request_id ): bool {

    $this->request_id = $request_id;

    $sql = 'UPDATE clients_initial_data SET request_id = :request_id WHERE token_hash = :token_hash';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':request_id', $this->request_id, PDO::PARAM_INT );
    $stmt->bindValue( ':token_hash', $this->token_hash, PDO::PARAM_STR );

    if ( $stmt->execute() ) {
      return true;
    } else {
      $this->errors[] = 'Не удалось сохранить id клиента, попробуйте ещё раз.';
    }

    return false;
  }

  /**
   * Checks that the token is exists and not expired
   *
   * @param string $token The remembered token.
   *
   * @return bool
   * @throws \Exception
   */
  public static function isTokenValid( string $token ): bool {
    $token      = new Token( $token );
    $token_hash = $token->getHash();

    $sql = 'SELECT * FROM clients_initial_data WHERE token_hash = :token_hash AND token_expires_at >= :now LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':token_hash', $token_hash, PDO::PARAM_STR );
    $stmt->bindValue( ':now', date( 'Y-m-d H:i:s', time() ), PDO::PARAM_STR );

    $stmt->execute();

    return ! ! $stmt->fetch();
  }

  /**
   * Checks that the phone is verified
   *
   * @param string $token The remembered token.
   *
   * @return bool
   * @throws \Exception
   */
  public static function isPhoneVerified( string $token ): bool {
    $token      = new Token( $token );
    $token_hash = $token->getHash();

    $sql = 'SELECT * FROM clients_initial_data WHERE token_hash = :token_hash AND verified = :verified LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':token_hash', $token_hash, PDO::PARAM_STR );
    $stmt->bindValue( ':verified', 1, PDO::PARAM_INT );

    $stmt->execute();

    return ! ! $stmt->fetch();
  }

  /**
   * Checks that the phone is exists
   *
   * @param string $token The remembered token.
   *
   * @return mixed
   * @throws \Exception
   */
  public static function isPhoneExists( string $token ) {
    $token      = new Token( $token );
    $token_hash = $token->getHash();

    $sql = 'SELECT phone FROM clients_initial_data WHERE token_hash = :token_hash LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':token_hash', $token_hash, PDO::PARAM_STR );

    if ( $stmt->execute() ) {
      $result = $stmt->fetch();

      return isset( $result['phone'] ) && ! empty( $result['phone'] );
    }

    return false;
  }

  /**
   * Finds an phone model by token
   *
   * @param string $token Token to search for.
   *
   * @return mixed The phone object if found, false otherwise.
   * @throws \Exception
   */
  public static function findByToken( string $token ) {
    $token      = new Token( $token );
    $token_hash = $token->getHash();

    $sql = 'SELECT * FROM clients_initial_data WHERE token_hash = :token_hash LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':token_hash', $token_hash, PDO::PARAM_STR );

    $stmt->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Regenerates sms code
   *
   * @return bool
   * @throws \Exception
   */
  public function regenerateSmsCode(): bool {
    $_SESSION['sms_code_attempts'] = 0;

    $this->sms_code            = Security::generateSmsCode();
    $this->sms_code_expires_at = time() + 60 * 30; // 30 minutes from now

    $sql = 'UPDATE clients_initial_data SET sms_code = :sms_code, verified = :verified, sms_code_expires_at = :sms_code_expires_at
		        WHERE token_hash = :token_hash';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':token_hash', $this->token_hash, PDO::PARAM_STR );
    $stmt->bindValue( ':verified', 0, PDO::PARAM_STR );
    $stmt->bindValue( ':sms_code', $this->sms_code, PDO::PARAM_STR );
    $stmt->bindValue( ':sms_code_expires_at', date( 'Y-m-d H:i:s', $this->sms_code_expires_at ), PDO::PARAM_STR );

    return $stmt->execute();
  }

  /**
   * Gets errors
   *
   * @return array
   */
  public function getErrors(): array {
    return $this->errors;
  }

  /**
   * Gets client id
   *
   * @return mixed
   */
  public function getClientId() {
    return $this->client_id;
  }

  /**
   * Gets phone
   *
   * @return string
   */
  public function getPhone(): string {
    return $this->phone;
  }

  /**
   * Gets shop id
   *
   * @return mixed
   */
  public function getShopId() {
    return $this->shop_id;
  }

  /**
   * Gets verified
   *
   * @return int
   */
  public function getVerified(): int {
    return $this->verified;
  }

  /**
   * Gets order id
   *
   * @return mixed
   */
  public function getOrderId() {
    return $this->order_id;
  }

  /**
   * Gets order price
   *
   * @return mixed
   */
  public function getOrderPrice() {
    return $this->order_price;
  }

  /**
   * Gets callback url
   *
   * @return string
   */
  public function getCallbackUrl(): string {
    return $this->callback_url;
  }

  /**
   * Get is loan deferred
   *
   * @return int
   */
  public function getIsLoanDeferred(): int {
    return $this->is_loan_deferred;
  }

  /**
   * Goods
   *
   * @return string
   */
  public function getGoods(): string {
    return $this->goods;
  }

  /**
   * Get control
   *
   * @return string
   */
  public function getControl(): string {
    return $this->control;
  }

  /**
   * Gets token
   *
   * @return string
   */
  public function getToken(): string {
    return $this->token;
  }

  /**
   * Gets request id
   *
   * @return string
   */
  public function getRequestId(): string {
    return $this->request_id;
  }

  /**
   * Gets token hash
   *
   * @return string
   */
  public function getTokenHash(): string {
    return $this->token_hash;
  }

  /**
   * Gets token expire
   *
   * @return string
   */
  public function getTokenExpiresAt(): string {
    return $this->token_expires_at;
  }

  /**
   * Gets sms-code
   *
   * @return string
   */
  public function getSmsCode(): string {
    return $this->sms_code;
  }

  /**
   * Gets sms-code expire
   *
   * @return string
   */
  public function getSmsCodeExpiresAt(): string {
    return $this->sms_code_expires_at;
  }
}
