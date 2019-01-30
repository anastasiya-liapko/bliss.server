<?php

namespace App\Models;

use App\PlainRule;
use DateTime;
use PDO;
use Rakit\Validation\Validator;

/**
 * Client model
 */
class Client extends \Core\Model {

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
   * Last name
   *
   * @var string
   */
  private $last_name = '';

  /**
   * First name
   *
   * @var string
   */
  private $first_name = '';

  /**
   * Middle name
   *
   * @var string
   */
  private $middle_name = '';

  /**
   * Birth date
   *
   * @var string
   */
  private $birth_date = '';

  /**
   * Birth place
   *
   * @var string
   */
  private $birth_place = '';

  /**
   * TIN
   *
   * @var mixed
   */
  private $tin = null;

  /**
   * Passport number
   *
   * @var string
   */
  private $passport_number = '';

  /**
   * Passport division code
   *
   * @var string
   */
  private $passport_division_code = '';

  /**
   * Passport issued by
   *
   * @var string
   */
  private $passport_issued_by = '';

  /**
   * Passport issued date
   *
   * @var string
   */
  private $passport_issued_date = '';

  /**
   * Workplace
   *
   * @var string
   */
  private $workplace = '';

  /**
   * Salary
   *
   * @var mixed
   */
  private $salary = null;

  /**
   * Registration zip code
   *
   * @var string
   */
  private $reg_zip_code = '';

  /**
   * Registration city
   *
   * @var string
   */
  private $reg_city = '';

  /**
   * Registration street
   *
   * @var string
   */
  private $reg_street = '';

  /**
   * Registration building
   *
   * @var string
   */
  private $reg_building = '';

  /**
   * Registration apartment
   *
   * @var string
   */
  private $reg_apartment = '';

  /**
   * Is address matched
   *
   * @var int
   */
  private $is_address_matched = 0;

  /**
   * Fact zip code
   *
   * @var string
   */
  private $fact_zip_code = '';

  /**
   * Fact city
   *
   * @var string
   */
  private $fact_city = '';

  /**
   * Fact street
   *
   * @var string
   */
  private $fact_street = '';

  /**
   * Fact building
   *
   * @var string
   */
  private $fact_building = '';

  /**
   * Fact apartment
   *
   * @var string
   */
  private $fact_apartment = '';

  /**
   * ClientInitialData
   *
   * @var string
   */
  private $phone = '';

  /**
   * Email
   *
   * @var string
   */
  private $email = '';

  /**
   * Client constructor
   *
   * @param array $data Initial property values.
   *
   * @return void
   */
  public function __construct( array $data = [] ) {
    foreach ( $data as $key => $value ) {
      if ( $key === 'is_address_matched' ) {
        $this->$key = $value ? 1 : 0;
      } else {
        $this->$key = $value;
      }
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
      'required'    => ':attribute - обязательное поле.',
      'required_if' => ':attribute - обязательное поле.',
      'numeric'     => ':attribute - поле должно содержать только цифры.',
      'regex'       => ':attribute - поле имеет некорректный формат.',
    ] );

    $validator->addValidator( 'plain', new PlainRule() );

    $validation = $validator->validate( [
      'last_name'              => $this->last_name,
      'first_name'             => $this->first_name,
      'middle_name'            => $this->middle_name,
      'birth_date'             => $this->birth_date,
      'birth_place'            => $this->birth_place,
      'passport_number'        => $this->passport_number,
      'passport_division_code' => $this->passport_division_code,
      'passport_issued_by'     => $this->passport_issued_by,
      'passport_issued_date'   => $this->passport_issued_date,
      'reg_zip_code'           => $this->reg_zip_code,
      'reg_city'               => $this->reg_city,
      'reg_street'             => $this->reg_street,
      'reg_building'           => $this->reg_building,
      'reg_apartment'          => $this->reg_apartment,
      'is_address_matched'     => $this->is_address_matched,
      'fact_zip_code'          => $this->fact_zip_code,
      'fact_city'              => $this->fact_city,
      'fact_street'            => $this->fact_street,
      'fact_building'          => $this->fact_building,
      'fact_apartment'         => $this->fact_apartment,
      'phone'                  => $this->phone,
      'email'                  => $this->email,
    ], [
      'last_name'              => 'required|plain',
      'first_name'             => 'required|plain',
      'middle_name'            => 'required|plain',
      'birth_date'             => 'required|regex:/^\d{2}.\d{2}.\d{4}$/',
      'birth_place'            => 'required|plain',
      'passport_number'        => 'required|regex:/^\d{2}\s\d{2}\s\d{6}$/',
      'passport_division_code' => 'required|regex:/^\d{3}-\d{3}$/',
      'passport_issued_by'     => 'required|plain',
      'passport_issued_date'   => 'required|regex:/^\d{2}.\d{2}.\d{4}$/',
      'reg_zip_code'           => 'required|plain',
      'reg_city'               => 'required|plain',
      'reg_street'             => 'required|plain',
      'reg_building'           => 'required|plain',
      'reg_apartment'          => 'required|plain',
      'is_address_matched'     => 'default:0',
      'fact_zip_code'          => 'required_if:is_address_matched,0|numeric',
      'fact_city'              => 'required_if:is_address_matched,0',
      'fact_street'            => 'required_if:is_address_matched,0',
      'fact_building'          => 'required_if:is_address_matched,0',
      'fact_apartment'         => 'required_if:is_address_matched,0',
      'phone'                  => 'required|regex:/^7\d{10}$/',
      'email'                  => 'required|email',
    ] );

    $validation->setAliases( [
      'last_name'              => 'Фамилия',
      'first_name'             => 'Имя',
      'middle_name'            => 'Отчество',
      'birth_date'             => 'Дата рождения',
      'birth_place'            => 'Место рождения',
      'passport_number'        => 'Серия и номер паспорта',
      'passport_division_code' => 'Код подразделения',
      'passport_issued_by'     => 'Кем выдан паспорт',
      'passport_issued_date'   => 'Дата выдачи паспорта',
      'reg_zip_code'           => 'Почтовый индекс регистрации',
      'reg_city'               => 'Город регистрации',
      'reg_street'             => 'Улица регистрации',
      'reg_building'           => 'Дом регистрации',
      'reg_apartment'          => 'Квартира регистрации',
      'is_address_matched'     => 'Совпадает с адресом регистрации',
      'fact_zip_code'          => 'Почтовый индекс',
      'fact_city'              => 'Город',
      'fact_street'            => 'Улица',
      'fact_building'          => 'Дом',
      'fact_apartment'         => 'Квартира',
      'phone'                  => 'Номер телефона',
      'email'                  => 'Email',
    ] );

    $validation->validate();

    if ( $validation->fails() ) {
      $this->errors = $validation->errors()->all();
    }
  }

  /**
   * Validates the age
   *
   * @return void
   * @throws \Exception
   */
  private function validateAge() {
    $born = DateTime::createFromFormat( 'd.m.Y', $this->birth_date );

    if ( $born->diff( new DateTime )->format( '%y' ) < 18 ) {
      $this->errors[] = [ 'Вы должны быть старше 18 лет' ];
    }
  }

  /**
   * Validates the passport issued date
   *
   * @return void
   * @throws \Exception
   */
  private function validatePassportIssuedDate() {
    $issued_date = DateTime::createFromFormat( 'd.m.Y', $this->passport_issued_date );

    if ( $issued_date < DateTime::createFromFormat( 'd.m.Y', '01.10.1997' ) ) {
      $this->errors[] = [ 'Паспорт должен быть выдан не позднее 1 октября 1997 года' ];
    }

    if ( $issued_date > new DateTime( 'tomorrow' ) ) {
      $this->errors[] = [ 'Паспорт не может быть выдан в будущем' ];
    }
  }

  /**
   * Checks that the client is exists
   *
   * @return bool
   */
  private function isClientExists(): bool {
    $sql = 'SELECT * FROM clients WHERE phone = :phone LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':phone', $this->phone, PDO::PARAM_STR );

    $stmt->execute();

    if ( $result = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
      $this->id = $result['id'];

      return true;
    }

    return false;
  }

  /**
   * Creates or update the client record
   *
   * @return bool
   * @throws \Exception
   */
  public function save(): bool {
    $this->validate();

    if ( ! empty( $this->errors ) ) {
      return false;
    }

    $this->validateAge();
    $this->validatePassportIssuedDate();

    if ( empty( $this->errors ) ) {

      $insert = false;

      if ( $this->isClientExists() ) {
        $sql = 'UPDATE clients SET last_name = :last_name, first_name = :first_name, middle_name = :middle_name, birth_date = :birth_date, birth_place = :birth_place, tin = :tin, salary = :salary, passport_number = :passport_number, passport_division_code = :passport_division_code, passport_issued_by = :passport_issued_by, passport_issued_date = :passport_issued_date, workplace = :workplace, reg_zip_code = :reg_zip_code, reg_city = :reg_city, reg_street = :reg_street, reg_building = :reg_building, reg_apartment = :reg_apartment, is_address_matched = :is_address_matched, fact_zip_code = :fact_zip_code, fact_city = :fact_city, fact_street = :fact_street, fact_building = :fact_building, fact_apartment = :fact_apartment, email = :email WHERE phone = :phone';
      } else {
        $insert = true;

        $sql = 'INSERT INTO clients (last_name, first_name, middle_name, birth_date, birth_place, tin, passport_number, passport_division_code, passport_issued_by, passport_issued_date, workplace, salary, reg_zip_code, reg_city, reg_street, reg_building, reg_apartment, is_address_matched, fact_zip_code, fact_city, fact_street, fact_building, fact_apartment, email, phone) VALUES (:last_name, :first_name, :middle_name, :birth_date, :birth_place, :tin, :passport_number, :passport_division_code, :passport_issued_by, :passport_issued_date, :workplace, :salary, :reg_zip_code, :reg_city, :reg_street, :reg_building, :reg_apartment, :is_address_matched, :fact_zip_code, :fact_city, :fact_street, :fact_building, :fact_apartment, :email, :phone)';
      }

      $this->birth_date           = date( 'Y-m-d H:i:s', strtotime( $this->birth_date ) );
      $this->passport_issued_date = date( 'Y-m-d H:i:s', strtotime( $this->passport_issued_date ) );

      $db   = static::getDB();
      $stmt = $db->prepare( $sql );

      $stmt->bindValue( ':last_name', $this->last_name, PDO::PARAM_STR );
      $stmt->bindValue( ':first_name', $this->first_name, PDO::PARAM_STR );
      $stmt->bindValue( ':middle_name', $this->middle_name, PDO::PARAM_STR );
      $stmt->bindValue( ':birth_date', $this->birth_date, PDO::PARAM_STR );
      $stmt->bindValue( ':birth_place', $this->birth_place, PDO::PARAM_STR );
      $stmt->bindValue( ':tin', $this->tin, PDO::PARAM_INT );
      $stmt->bindValue( ':passport_number', preg_replace( '/\s/', '', $this->passport_number ), PDO::PARAM_STR );
      $stmt->bindValue( ':passport_division_code', preg_replace( '/-/', '', $this->passport_division_code ),
        PDO::PARAM_STR );
      $stmt->bindValue( ':passport_issued_by', $this->passport_issued_by, PDO::PARAM_STR );
      $stmt->bindValue( ':passport_issued_date', $this->passport_issued_date, PDO::PARAM_STR );
      $stmt->bindValue( ':reg_zip_code', $this->reg_zip_code, PDO::PARAM_STR );
      $stmt->bindValue( ':reg_city', $this->reg_city, PDO::PARAM_STR );
      $stmt->bindValue( ':reg_street', $this->reg_street, PDO::PARAM_STR );
      $stmt->bindValue( ':reg_building', $this->reg_building, PDO::PARAM_STR );
      $stmt->bindValue( ':workplace', $this->workplace, PDO::PARAM_STR );
      $stmt->bindValue( ':salary', $this->salary, PDO::PARAM_INT );
      $stmt->bindValue( ':reg_apartment', $this->reg_apartment, PDO::PARAM_STR );
      $stmt->bindValue( ':is_address_matched', $this->is_address_matched, PDO::PARAM_INT );
      $stmt->bindValue( ':fact_zip_code', $this->fact_zip_code, PDO::PARAM_STR );
      $stmt->bindValue( ':fact_city', $this->fact_city, PDO::PARAM_STR );
      $stmt->bindValue( ':fact_street', $this->fact_street, PDO::PARAM_STR );
      $stmt->bindValue( ':fact_building', $this->fact_building, PDO::PARAM_STR );
      $stmt->bindValue( ':fact_apartment', $this->fact_apartment, PDO::PARAM_STR );
      $stmt->bindValue( ':email', $this->email, PDO::PARAM_STR );
      $stmt->bindValue( ':phone', $this->phone, PDO::PARAM_STR );

      if ( $stmt->execute() ) {

        if ( $insert ) {
          $this->id = $db->lastInsertId();
        }

        return true;
      } else {
        $this->errors[] = 'Не удалось сохранить данные, попробуйте ещё раз.';
      }
    }

    return false;
  }

  /**
   * Finds an client model by id
   *
   * @param int $id
   *
   * @return mixed The client object if found, false otherwise.
   */
  public static function findByID( int $id ) {
    $sql = 'SELECT * FROM clients WHERE id = :id LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':id', $id, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Finds an client model by the phone
   *
   * @param string $phone The client phone.
   *
   * @return mixed The client object if found, false otherwise.
   */
  public static function findByPhone( string $phone ) {
    $sql = 'SELECT * FROM clients WHERE phone = :phone LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':phone', $phone, PDO::PARAM_STR );

    $stmt->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
    $stmt->execute();

    return $stmt->fetch();
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
   * Gets last name
   *
   * @return string
   */
  public function getLastName(): string {
    return $this->last_name;
  }

  /**
   * Gets first name
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
   * Gets birth date
   *
   * @return string
   */
  public function getBirthDate(): string {
    return $this->birth_date;
  }

  /**
   * Gets birth place
   *
   * @return string
   */
  public function getBirthPlace(): string {
    return $this->birth_place;
  }

  /**
   * Gets tin
   *
   * @return mixed
   */
  public function getTin() {
    return $this->tin;
  }

  /**
   * Gets passport number
   *
   * @return string
   */
  public function getPassportNumber(): string {
    return $this->passport_number;
  }

  /**
   * Gets passport division code
   *
   * @return string
   */
  public function getPassportDivisionCode(): string {
    return $this->passport_division_code;
  }

  /**
   * Gets passport issued by
   *
   * @return string
   */
  public function getPassportIssuedBy(): string {
    return $this->passport_issued_by;
  }

  /**
   * Gets passport issued date
   *
   * @return string
   */
  public function getPassportIssuedDate(): string {
    return $this->passport_issued_date;
  }

  /**
   * Gets workplace
   *
   * @return string
   */
  public function getWorkplace(): string {
    return $this->workplace;
  }

  /**
   * Gets salary
   *
   * @return mixed
   */
  public function getSalary() {
    return $this->salary;
  }

  /**
   * Gets registration zip code
   * @return mixed
   */
  public function getRegZipCode() {
    return $this->reg_zip_code;
  }

  /**
   * Gets registration city
   *
   * @return string
   */
  public function getRegCity(): string {
    return $this->reg_city;
  }

  /**
   * Gets registration street
   *
   * @return string
   */
  public function getRegStreet(): string {
    return $this->reg_street;
  }

  /**
   * Gets registration building
   *
   * @return string
   */
  public function getRegBuilding(): string {
    return $this->reg_building;
  }

  /**
   * Gets registration apartment
   *
   * @return string
   */
  public function getRegApartment(): string {
    return $this->reg_apartment;
  }

  /**
   * Gets is address matched
   *
   * @return int
   */
  public function getIsAddressMatched(): int {
    return $this->is_address_matched;
  }

  /**
   * Gets fact zip code
   *
   * @return mixed
   */
  public function getFactZipCode() {
    return $this->fact_zip_code;
  }

  /**
   * Gets fact city
   *
   * @return string
   */
  public function getFactCity(): string {
    return $this->fact_city;
  }

  /**
   * Gets fact street
   *
   * @return string
   */
  public function getFactStreet(): string {
    return $this->fact_street;
  }

  /**
   * Gets fact building
   *
   * @return string
   */
  public function getFactBuilding(): string {
    return $this->fact_building;
  }

  /**
   * Gets fact apartment
   *
   * @return string
   */
  public function getFactApartment(): string {
    return $this->fact_apartment;
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
   * Gets email
   *
   * @return string
   */
  public function getEmail(): string {
    return $this->email;
  }
}
