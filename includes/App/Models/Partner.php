<?php

namespace App\Models;

use App\PlainRule;
use PDO;
use Rakit\Validation\Validator;

/**
 * Partner model
 */
class Partner extends \Core\Model {

  /**
   * Error messages
   *
   * @var array
   */
  private $errors = [];

  /**
   * Record id
   *
   * @var mixed
   */
  private $id = null;

  /**
   * Name
   *
   * @var string
   */
  private $name = '';

  /**
   * Image url
   *
   * @var string
   */
  private $img_url = '';

  /**
   * URL
   *
   * @var string
   */
  private $url = '';

  /**
   * Orderby
   *
   * @var int
   */
  private $orderby = 0;

  /**
   * Partner constructor
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
      'name'    => $this->name,
      'img_url' => $this->img_url,
      'url'     => $this->url,
      'orderby' => $this->orderby
    ], [
      'name'    => 'required|plain',
      'img_url' => 'required|plain',
      'url'     => 'required|plain',
      'orderby' => 'numeric'
    ] );

    $validation->setAliases( [
      'name'    => 'Название',
      'img_url' => 'Ссылка на логотип',
      'url'     => 'Ссылка на сайт',
      'orderby' => 'Порядковый номер'
    ] );

    $validation->validate();

    if ( $validation->fails() ) {
      $this->errors = $validation->errors()->all();
    }
  }

  /**
   * Creates partner
   *
   * @return bool
   * @throws \Rakit\Validation\RuleQuashException
   */
  public function create() {
    $this->validate();

    if ( empty( $this->errors ) ) {
      $sql = 'INSERT INTO partners (name, img_url, url, orderby) VALUES (:name, :img_url, :url, :orderby)';

      $db   = static::getDB();
      $stmt = $db->prepare( $sql );

      $stmt->bindValue( ':name', $this->name, PDO::PARAM_STR );
      $stmt->bindValue( ':img_url', $this->img_url, PDO::PARAM_STR );
      $stmt->bindValue( ':url', $this->url, PDO::PARAM_STR );
      $stmt->bindValue( ':orderby', $this->orderby, PDO::PARAM_INT );

      if ( $stmt->execute() ) {
        $this->id = $db->lastInsertId();

        return true;
      } else {
        $this->errors[] = 'Не удалось создать запись, попробуйте ещё раз.';
      }
    }

    return false;
  }

  /**
   * Gets all records
   *
   * @return mixed Array of results, false otherwise.
   */
  public static function getAll() {
    $sql = 'SELECT * FROM partners';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );
    $stmt->setFetchMode( PDO::FETCH_ASSOC );
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
   * Gets id
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
   * Gets img url
   *
   * @return string
   */
  public function getImgUrl(): string {
    return $this->img_url;
  }

  /**
   * Gets url
   *
   * @return string
   */
  public function getUrl(): string {
    return $this->url;
  }

  /**
   * Gets orderby
   *
   * @return int
   */
  public function getOrderby(): int {
    return $this->orderby;
  }
}
