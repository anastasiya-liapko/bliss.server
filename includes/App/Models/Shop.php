<?php

namespace App\Models;

use App\Mail;
use App\PlainRule;
use App\Security;
use App\SiteInfo;
use App\UniqueRule;
use Core\View;
use PDO;
use Rakit\Validation\Validator;

/**
 * Shop model
 */
class Shop extends \Core\Model {

  /**
   * Error messages
   *
   * @var array
   */
  private $errors = [];

  /**
   * ID
   *
   * @var mixed
   */
  private $id = null;

  /**
   * Type
   *
   * @var string
   */
  private $type = '';

  /**
   * Company name
   *
   * @var string
   */
  private $company_name = '';

  /**
   * ShopAdmin last name
   *
   * @var string
   */
  private $last_name = '';

  /**
   * ShopAdmin first name
   *
   * @var string
   */
  private $first_name = '';

  /**
   * ShopAdmin middle name
   *
   * @var string
   */
  private $middle_name = '';

  /**
   * Tin
   *
   * @var string
   */
  private $tin = '';

  /**
   * Description
   *
   * @var string
   */
  private $dsc = '';

  /**
   * Email
   *
   * @var string
   */
  private $email = '';

  /**
   * Active
   *
   * @var int
   */
  private $active = 0;

  /**
   * Secret key
   *
   * @var string
   */
  private $secret_key = '';

  /**
   * Shop constructor
   *
   * @param array $data Initial property values.
   *
   * @return void
   */
  public function __construct( array $data = [] ) {
    foreach ( $data as $key => $value ) {
      $this->$key = $value;
    }
  }

  /**
   * Validates values
   *
   * @throws \Rakit\Validation\RuleQuashException
   * @return void
   */
  private function validate() {
    $validator = new Validator( [
      'required' => ':attribute - обязательное поле.',
      'numeric'  => ':attribute - поле должно содержать только цифры.'
    ] );

    $db = static::getDB();

    $validator->addValidator( 'unique', new UniqueRule( $db ) );
    $validator->addValidator( 'plain', new PlainRule() );

    $validation = $validator->validate( [
      'type'         => $this->type,
      'company_name' => $this->company_name,
      'last_name'    => $this->last_name,
      'first_name'   => $this->first_name,
      'middle_name'  => $this->middle_name,
      'tin'          => $this->tin,
      'dsc'          => $this->dsc,
      'email'        => $this->email,
      'active'       => $this->active
    ], [
      'type'         => 'required|plain',
      'company_name' => 'required|plain',
      'last_name'    => 'required|plain',
      'first_name'   => 'required|plain',
      'middle_name'  => 'required|plain',
      'tin'          => 'required|numeric|unique:shops,tin',
      'dsc'          => 'plain',
      'email'        => 'email|unique:shops_admins,email',
      'active'       => 'numeric'
    ] );

    $validation->setAliases( [
      'type'         => 'Тип ораганизации',
      'company_name' => 'Название организации',
      'last_name'    => 'Фамилия',
      'first_name'   => 'Имя',
      'middle_name'  => 'Отчество',
      'tin'          => 'ИНН',
      'dsc'          => 'ИНН',
      'email'        => 'Email',
      'active'       => 'Активирован',
    ] );

    $validation->validate();

    if ( $validation->fails() ) {
      $this->errors = $validation->errors()->all();
    }
  }

  /**
   * Validates update data
   *
   * @param array $data
   *
   * @throws \Rakit\Validation\RuleQuashException
   * @return mixed
   */
  private function validateUpdateData( array $data ) {
    $validator = new Validator( [
      'required' => ':attribute - обязательное поле.',
      'numeric'  => ':attribute - поле должно содержать только цифры.'
    ] );

    $db = static::getDB();

    $validator->addValidator( 'unique', new UniqueRule( $db ) );
    $validator->addValidator( 'plain', new PlainRule() );

    $validation = $validator->validate( [
      'type'         => $data['type'] ?? '',
      'company_name' => $data['company_name'] ?? '',
      'last_name'    => $data['last_name'] ?? '',
      'first_name'   => $data['first_name'] ?? '',
      'middle_name'  => $data['middle_name'] ?? '',
      'tin'          => $data['tin'] ?? '',
      'dsc'          => $data['dsc'] ?? '',
      'active'       => $data['active'] ?? 0,
    ], [
      'type'         => 'required|plain',
      'company_name' => 'required|plain',
      'last_name'    => 'required|plain',
      'first_name'   => 'required|plain',
      'middle_name'  => 'required|plain',
      'tin'          => 'required|numeric|unique:shops,tin,' . $this->tin,
      'dsc'          => 'plain',
      'active'       => 'numeric'
    ] );

    $validation->setAliases( [
      'type'         => 'Тип ораганизации',
      'company_name' => 'Название организации',
      'last_name'    => 'Фамилия',
      'first_name'   => 'Имя',
      'middle_name'  => 'Отчество',
      'tin'          => 'ИНН',
      'dsc'          => 'Описание',
      'active'       => 'Активирован',
    ] );

    $validation->validate();

    if ( $validation->fails() ) {
      $this->errors = $validation->errors()->all();

      return false;
    }

    return $validation->getValidData();
  }

  /**
   * Creates the account for a shop
   *
   * @return bool
   * @throws \Rakit\Validation\RuleQuashException
   */
  public function create() {

    $this->validate();

    if ( empty( $this->errors ) ) {
      $this->secret_key = Security::generatePassword( 32, false );

      $db = static::getDB();

      $stmt = $db->prepare( '
        INSERT INTO shops (type, company_name, last_name, first_name, middle_name, tin, dsc, active, secret_key) 
        VALUES (:type, :company_name, :last_name, :first_name, :middle_name, :tin, :dsc, :active, :secret_key)'
      );

      $stmt->bindValue( ':type', $this->type, PDO::PARAM_STR );
      $stmt->bindValue( ':company_name', $this->company_name, PDO::PARAM_STR );
      $stmt->bindValue( ':last_name', $this->last_name, PDO::PARAM_STR );
      $stmt->bindValue( ':first_name', $this->first_name, PDO::PARAM_STR );
      $stmt->bindValue( ':middle_name', $this->middle_name, PDO::PARAM_STR );
      $stmt->bindValue( ':tin', $this->tin, PDO::PARAM_STR );
      $stmt->bindValue( ':dsc', $this->dsc, PDO::PARAM_STR );
      $stmt->bindValue( ':active', $this->active, PDO::PARAM_INT );
      $stmt->bindValue( ':secret_key', $this->secret_key, PDO::PARAM_STR );

      if ( $stmt->execute() ) {
        $this->id = $db->lastInsertId();

        return true;
      } else {
        $this->errors[] = 'Не удалось сохранить данные о магазине, попробуйте ещё раз.';
      }
    }

    return false;
  }

  /**
   * Updates the account for a shop
   *
   * @param array $data
   *
   * @return bool
   * @throws \Rakit\Validation\RuleQuashException
   */
  public function update( array $data ) {

    if ( $valid_data = $this->validateUpdateData( $data ) ) {

      $this->type         = $valid_data['type'];
      $this->company_name = $valid_data['company_name'];
      $this->last_name    = $valid_data['last_name'];
      $this->first_name   = $valid_data['first_name'];
      $this->middle_name  = $valid_data['middle_name'];
      $this->tin          = $valid_data['tin'];
      $this->dsc          = $valid_data['dsc'];
      $this->active       = $valid_data['active'];

      $db = static::getDB();

      $stmt = $db->prepare( '
        UPDATE shops SET type = :type, company_name = :company_name, last_name = :last_name, first_name = :first_name, middle_name = :middle_name, tin = :tin, dsc = :dsc, active = :active
        WHERE id = :id'
      );

      $stmt->bindValue( ':type', $this->type, PDO::PARAM_STR );
      $stmt->bindValue( ':company_name', $this->company_name, PDO::PARAM_STR );
      $stmt->bindValue( ':last_name', $this->last_name, PDO::PARAM_STR );
      $stmt->bindValue( ':first_name', $this->first_name, PDO::PARAM_STR );
      $stmt->bindValue( ':middle_name', $this->middle_name, PDO::PARAM_STR );
      $stmt->bindValue( ':tin', $this->tin, PDO::PARAM_STR );
      $stmt->bindValue( ':dsc', $this->dsc, PDO::PARAM_STR );
      $stmt->bindValue( ':active', 0, PDO::PARAM_INT );
      $stmt->bindValue( ':id', $this->id, PDO::PARAM_INT );

      if ( $stmt->execute() ) {
        return true;
      }

      $this->errors[] = 'Не удалось обновить данные, попробуйте ещё раз.';
    }

    return false;
  }

  /**
   * Finds the shop model by the id
   *
   * @param string $id
   *
   * @return mixed The shop object if found, false otherwise.
   */
  public static function findById( string $id ) {
    $sql = 'SELECT * FROM shops WHERE id = :id LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':id', $id, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Sends the email to the bliss admin for change requisites
   *
   * @return bool
   * @throws \PHPMailer\PHPMailer\Exception
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function sendUpdateRequisitesEmail(): bool {
    $args = [
      'shop_id'      => $this->id,
      'company_name' => $this->company_name,
    ];

    $text = View::getTemplate( 'ShopAdmin/Requisites/shop_requisites_email.txt', $args );
    $html = View::getTemplate( 'ShopAdmin/Requisites/shop_requisites_email.html', $args );

    return Mail::send( SiteInfo::INFO_EMAIL, 'Запрос на изменение реквизитов магазина', $text, $html );
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
   * Gets id
   *
   * @return mixed
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Gets type
   *
   * @return string
   */
  public function getType(): string {
    return $this->type;
  }

  /**
   * Gets company name
   *
   * @return string
   */
  public function getCompanyName(): string {
    return $this->company_name;
  }

  /**
   * Gets last name
   *
   * @return string
   */
  public function getLastName(): string {
    return $this->last_name;
  }

  /**
   * Gets first
   *
   * @return string
   */
  public function getFirstName(): string {
    return $this->first_name;
  }

  /**
   * Gets middle name
   *
   * @return string
   */
  public function getMiddleName(): string {
    return $this->middle_name;
  }

  /**
   * Gets tin
   *
   * @return string
   */
  public function getTin(): string {
    return $this->tin;
  }

  /**
   * Gets descriptions
   *
   * @return string
   */
  public function getDsc(): string {
    return $this->dsc;
  }

  /**
   * Gets active
   *
   * @return int
   */
  public function getActive(): int {
    return $this->active;
  }

  /**
   * Gets secret key
   *
   * @return string
   */
  public function getSecretKey(): string {
    return $this->secret_key;
  }
}
