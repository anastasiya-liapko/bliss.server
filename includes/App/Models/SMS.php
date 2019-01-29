<?php

namespace App\Models;

use App\Config;
use App\PlainRule;
use App\SmsRu;
use App\Token;
use PDO;
use Rakit\Validation\Validator;
use stdClass;

/**
 * SMS code model
 */
class SMS extends \Core\Model {

  /**
   * Error messages
   *
   * @var array
   */
  private $errors = [];

  /**
   * SMS-code
   *
   * @var string
   */
  private $sms_code = '';

  /**
   * Terms
   *
   * @var string
   */
  private $terms = '';

  /**
   * Token
   *
   * @var string
   */
  private $token = '';

  /**
   * SMS constructor
   *
   * @param array $data Initial property values.
   *
   * @return void
   */
  public function __construct( array $data ) {
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
    $validator = new Validator( [
      'required' => ':attribute - обязательное поле.',
      'regex'    => ':attribute - поле имеет некорректный формат.'
    ] );

    $validator->addValidator( 'plain', new PlainRule() );

    $validation = $validator->validate( [
      'sms_code' => $this->sms_code,
      'terms'    => $this->terms,
      'token'    => $this->token
    ], [
      'sms_code' => 'required|regex:/^\d{4}$/',
      'terms'    => 'required|plain',
      'token'    => 'required|plain',
    ] );

    $validation->setAliases( [
      'sms_code' => 'СМС-код',
      'terms'    => 'Условия',
      'token'    => 'Токен',
    ] );

    $validation->validate();

    if ( $validation->fails() ) {
      $this->errors = $validation->errors()->all();
    }
  }

  /**
   * Check attempts
   *
   * @return void
   */
  private function checkAttempts() {
    if ( isset( $_SESSION['sms_code_attempts'] ) && $_SESSION['sms_code_attempts'] >= 3 ) {
      $this->errors[] = 'Более 3 раз введён неверый код, запросите новый код.';
    }
  }

  /**
   * Checks the sms-code
   *
   * @return bool
   * @throws \Exception
   */
  public function checkCode(): bool {
    $this->validate();

    if ( ! empty( $this->errors ) ) {
      return false;
    }

    $this->checkAttempts();

    if ( empty( $this->errors ) ) {

      if ( isset( $_SESSION['sms_code_attempts'] ) ) {
        $_SESSION['sms_code_attempts'] ++;
      } else {
        $_SESSION['sms_code_attempts'] = 1;
      }

      $token      = new Token( $this->token );
      $token_hash = $token->getHash();

      $sql = 'SELECT * FROM clients_initial_data WHERE token_hash = :token_hash AND sms_code = :sms_code AND sms_code_expires_at >= :now';

      $db   = static::getDB();
      $stmt = $db->prepare( $sql );

      $stmt->bindValue( ':token_hash', $token_hash, PDO::PARAM_STR );
      $stmt->bindValue( ':sms_code', $this->sms_code, PDO::PARAM_STR );
      $stmt->bindValue( ':now', date( 'Y-m-d H:i:s', time() ), PDO::PARAM_STR );

      $stmt->execute();

      if ( $stmt->fetch() ) {
        return true;
      }

      $this->errors[] = 'Код не подходит.';

    }

    return false;
  }

  /**
   * Verifies phone
   *
   * @return bool
   * @throws \Exception
   */
  public function verifyPhone(): bool {
    $token      = new Token( $this->token );
    $token_hash = $token->getHash();

    $db = static::getDB();

    $sql = 'UPDATE clients_initial_data SET verified = 1 WHERE token_hash = :token_hash';

    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':token_hash', $token_hash, PDO::PARAM_STR );

    if ( $stmt->execute() ) {
      return true;
    }

    $this->errors[] = 'Не удалось подтвердить номер телефона.';

    return false;
  }

  /**
   * Sens sms
   *
   * @param string $phone Phone number.
   * @param string $text Text.
   *
   * @return string Status of sending.
   */
  public static function sendSms( $phone, $text ): string {
    if ( $_SERVER['SERVER_NAME'] === 'bliss.local' || $_SERVER['SERVER_NAME'] === 'bliss.alef.im' ) {
      $log_text = $phone . ' - ' . $text . PHP_EOL; // TODO remove it
      file_put_contents( $_SERVER['DOCUMENT_ROOT'] . '/logs/sms.txt', $log_text, FILE_APPEND ); // TODO remove it
    }

    $sms_ru     = new SmsRu( Config::SMS_RU_API_ID );
    $data       = new stdClass();
    $data->to   = preg_replace( '/[-)(\s\+]/', '', $phone );
    $data->text = $text;
    //$data->test = 1; // TODO remove it
    $sms        = $sms_ru->send_one( $data );

    return $sms->status;
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
   * Gets sms-code
   *
   * @return string
   */
  public function getSmsCode(): string {
    return $this->sms_code;
  }

  /**
   * Gets terms
   *
   * @return string
   */
  public function getTerms(): string {
    return $this->terms;
  }

  /**
   * Gets token
   *
   * @return string
   */
  public function getToken(): string {
    return $this->token;
  }
}
