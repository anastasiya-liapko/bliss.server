<?php

namespace App\Models;

use App\Mail;
use App\PlainRule;
use App\Security;
use App\SiteInfo;
use App\Token;
use App\UniqueRule;
use Core\View;
use PDO;
use Rakit\Validation\Validator;

/**
 * ShopAdmin model
 */
class ShopAdmin extends \Core\Model {

  /**
   * Error messages
   *
   * @var array
   */
  private $errors = [];

  /**
   * ShopAdmin id
   *
   * @var mixed
   */
  private $id = null;

  /**
   * ShopAdmin name
   *
   * @var string
   */
  private $name = '';

  /**
   * ShopAdmin email
   *
   * @var string
   */
  private $email = '';

  /**
   * ShopAdmin password
   *
   * @var string
   */
  private $password = '';

  /**
   * Password hash
   *
   * @var string
   */
  private $password_hash = '';

  /**
   * ShopAdmin phone
   *
   * @var string
   */
  private $phone = '';

  /**
   * Role
   *
   * @var string
   */
  private $role = '';

  /**
   * Shop id
   *
   * @var mixed
   */
  private $shop_id = null;

  /**
   * Active
   *
   * @var int
   */
  private $active = 0;

  /**
   * Remember token
   *
   * @var string
   */
  private $remember_token = '';

  /**
   * Expiry timestamp
   *
   * @var string
   */
  private $expiry_timestamp = '';

  /**
   * ShopAdmin constructor
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
   * @return void
   * @throws \Rakit\Validation\RuleQuashException
   */
  private function validate() {
    $validator = new Validator( [
      'required' => ':attribute - обязательное поле.',
      'numeric'  => ':attribute - поле должно содержать только цифры.',
      'regex'    => ':attribute - поле имеет некорректный формат.'
    ] );

    $db = static::getDB();

    $validator->addValidator( 'unique', new UniqueRule( $db ) );
    $validator->addValidator( 'plain', new PlainRule() );

    $validation = $validator->validate( [
      'name'    => $this->name,
      'email'   => $this->email,
      'phone'   => $this->phone,
      'role'    => $this->role,
      'shop_id' => $this->shop_id,
      'active'  => $this->active,
    ], [
      'name'    => 'required|plain',
      'email'   => 'required|email|unique:shops_admins,email',
      'phone'   => 'regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
      'role'    => 'required|plain',
      'shop_id' => 'required|numeric',
      'active'  => 'numeric',
    ] );

    $validation->setAliases( [
      'name'    => 'Имя',
      'email'   => 'Email',
      'phone'   => 'Телефон',
      'role'    => 'Роль',
      'shop_id' => 'ID магазина',
      'active'  => 'Активирован',
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
      'name'     => $data['name'] ?? '',
      'email'    => $data['email'] ?? '',
      'phone'    => $data['phone'] ?? '',
      'active'   => $data['active'] ?? 0,
      'password' => $data['password'] ?? ''
    ], [
      'name'     => 'required|plain',
      'email'    => 'required|email|unique:shops_admins,email,' . $this->email,
      'phone'    => 'regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/',
      'active'   => 'numeric',
      'password' => 'required'
    ] );

    $validation->setAliases( [
      'name'     => 'Имя',
      'email'    => 'Email',
      'phone'    => 'Телефон',
      'active'   => 'Активирован',
      'password' => 'Пароль',
    ] );

    $validation->validate();

    if ( $validation->fails() ) {
      $this->errors = $validation->errors()->all();

      return false;
    }

    return $validation->getValidData();
  }

  /**
   * Creates the admin for a shop
   *
   * @return bool
   * @throws \Rakit\Validation\RuleQuashException
   */
  public function create() {

    $this->validate();

    if ( empty( $this->errors ) ) {
      $this->password      = Security::generatePassword();
      $this->password_hash = password_hash( $this->password, PASSWORD_DEFAULT );
      $this->phone         = preg_replace( '/[-)(\s]/', '', $this->phone );

      $db = static::getDB();

      $stmt = $db->prepare( '
        INSERT INTO shops_admins (name, email, password_hash, phone, role, shop_id, active) 
        VALUES (:name, :email, :password_hash, :phone, :role, :shop_id, :active)'
      );

      $stmt->bindValue( ':name', $this->name, PDO::PARAM_STR );
      $stmt->bindValue( ':email', $this->email, PDO::PARAM_STR );
      $stmt->bindValue( ':password_hash', $this->password_hash, PDO::PARAM_STR );
      $stmt->bindValue( ':phone', $this->phone, PDO::PARAM_STR );
      $stmt->bindValue( ':role', $this->role, PDO::PARAM_STR );
      $stmt->bindValue( ':shop_id', $this->shop_id, PDO::PARAM_INT );
      $stmt->bindValue( ':active', $this->active, PDO::PARAM_INT );

      if ( $stmt->execute() ) {
        $this->id = $db->lastInsertId();

        return true;
      } else {
        $this->errors[] = 'Не удалось сохранить данные, попробуйте ещё раз.';
      }
    }

    return false;
  }

  /**
   * Update the admin
   *
   * @param array $data
   *
   * @return bool|int
   * @throws \Rakit\Validation\RuleQuashException
   */
  public function update( array $data ) {

    if ( $valid_data = $this->validateUpdateData( $data ) ) {
      $this->name  = $valid_data['name'];
      $this->email = $valid_data['email'];
      $this->phone = preg_replace( '/[-)(\s]/', '', $valid_data['phone'] );

      if ( $this->role !== 'admin' ) {
        $this->active = $valid_data['active'];
      }

      if ( $valid_data['password'] !== $this->password_hash ) {
        $this->password      = $valid_data['password'];
        $this->password_hash = password_hash( $this->password, PASSWORD_DEFAULT );
      }

      $db = static::getDB();

      $stmt = $db->prepare( '
        UPDATE shops_admins SET name = :name, email = :email, password_hash = :password_hash, phone = :phone, active = :active 
        WHERE id = :id'
      );

      $stmt->bindValue( ':name', $this->name, PDO::PARAM_STR );
      $stmt->bindValue( ':email', $this->email, PDO::PARAM_STR );
      $stmt->bindValue( ':password_hash', $this->password_hash, PDO::PARAM_STR );
      $stmt->bindValue( ':phone', $this->phone, PDO::PARAM_STR );
      $stmt->bindValue( ':active', $this->active, PDO::PARAM_INT );
      $stmt->bindValue( ':id', $this->id, PDO::PARAM_INT );

      if ( $stmt->execute() ) {
        return true;
      }

      $this->errors[] = 'Не удалось обновить данные, попробуйте ещё раз.';
    }

    return false;
  }

  /**
   * Sends the email to the admin with an auth info
   *
   * @return bool
   * @throws \PHPMailer\PHPMailer\Exception
   * @throws \Exception
   */
  public function sendAuthEmail(): bool {
    $args = [
      'site_name' => SiteInfo::NAME,
      'email'     => $this->email,
      'password'  => $this->password
    ];

    $text = View::getTemplate( 'ProfileShop/admin_registration_email.txt', $args );
    $html = View::getTemplate( 'ProfileShop/admin_registration_email.html', $args );

    if ( ! Mail::send( $this->email, 'Данные для авторизации в системе ' . SiteInfo::NAME, $text, $html ) ) {
      $this->errors[] = 'Не удалось отправить Email. Обратитесь к администратору сайта.';

      return false;
    }

    return true;
  }

  /**
   * Remembers the login
   *
   * By inserting a new unique token into the remembered_logins table for this admin record.
   *
   * @return bool True if the login was remembered successfully, false otherwise.
   * @throws \Exception
   */
  public function rememberLogin(): bool {
    $token                = new Token();
    $token_hash           = $token->getHash();
    $this->remember_token = $token->getToken();

    $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now

    $sql = 'INSERT INTO remembered_logins (token_hash, admin_id, expires_at) 
            VALUES (:token_hash, :admin_id, :expires_at)';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':token_hash', $token_hash, PDO::PARAM_STR );
    $stmt->bindValue( ':admin_id', $this->id, PDO::PARAM_INT );
    $stmt->bindValue( ':expires_at', date( 'Y-m-d H:i:s', $this->expiry_timestamp ), PDO::PARAM_STR );

    return $stmt->execute();
  }

  /**
   * Deletes the admin
   *
   * This does not allow to remove the user with the role of admin
   *
   * @param int $id The admin id.
   *
   * @return bool
   */
  public static function delete( int $id ) {
    $sql = 'DELETE FROM shops_admins WHERE id = :id AND NOT role = :role';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
    $stmt->bindValue( ':role', 'admin', PDO::PARAM_INT );

    return $stmt->execute();
  }

  /**
   * Authenticates an admin by email and password
   *
   * It also checks if admin is active.
   *
   * @param string $email Email address.
   * @param string $password Password.
   *
   * @return mixed The admin object or false if authentication fails.
   */
  public static function authenticate( string $email, string $password ) {
    $admin = static::findByEmail( $email );

    if ( $admin ) {
      if ( password_verify( $password, $admin->getPasswordHash() ) && $admin->getActive() === 1 ) {
        return $admin;
      }
    }

    return false;
  }

  /**
   * Finds an admin model by email address
   *
   * @param string $email Email address to search for.
   *
   * @return mixed The admin object if found, false otherwise.
   */
  public static function findByEmail( string $email ) {
    $sql = 'SELECT * FROM shops_admins WHERE email = :email LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':email', $email, PDO::PARAM_STR );

    $stmt->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Finds an admin model by ID
   *
   * @param string $id The admin ID.
   *
   * @return mixed The admin object if found, false otherwise.
   */
  public static function findByID( string $id ) {
    $sql = 'SELECT * FROM shops_admins WHERE id = :id LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':id', $id, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Gets admins by the shop id
   *
   * @param int $shop_id
   *
   * @return mixed Array of results, false otherwise.
   */
  public static function getAllByShopId( int $shop_id ) {
    $sql = 'SELECT * FROM shops_admins WHERE shop_id = :shop_id';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':shop_id', $shop_id, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $stmt->execute();

    return $stmt->fetchAll();
  }

  /**
   * Gets emails by shop id
   *
   * @param int $shop_id The shop id to search for.
   *
   * @return mixed
   */
  public static function getEmailsByShopId( int $shop_id ) {
    $sql = 'SELECT email FROM shops_admins WHERE shop_id = :shop_id';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':shop_id', $shop_id, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_COLUMN, 0 );
    $stmt->execute();

    return $stmt->fetchAll();
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
   * Gets ID
   *
   * @return mixed
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Gets name
   *
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * Gets email
   *
   * @return string
   */
  public function getEmail(): string {
    return $this->email;
  }

  /**
   * Gets password
   *
   * @return string
   */
  public function getPassword(): string {
    return $this->password;
  }

  /**
   * Gets password hash
   *
   * @return string
   */
  public function getPasswordHash(): string {
    return $this->password_hash;
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
   * Gets role
   *
   * @return string
   */
  public function getRole(): string {
    return $this->role;
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
   * Gets active
   *
   * @return int
   */
  public function getActive(): int {
    return $this->active;
  }

  /**
   * Gets remember token
   *
   * @return string
   */
  public function getRememberToken(): string {
    return $this->remember_token;
  }

  /**
   * Gets expire timestamp
   *
   * @return string
   */
  public function getExpiryTimestamp(): string {
    return $this->expiry_timestamp;
  }
}
