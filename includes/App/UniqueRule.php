<?php

namespace App;

use PDO;
use Rakit\Validation\Rule;

/**
 * Class UniqueRule
 */
class UniqueRule extends Rule {

  /**
   * Message
   *
   * @var string
   */
  protected $message = ':attribute :value уже зарегистрирован.';

  /**
   * Params
   *
   * @var array
   */
  protected $fillableParams = [ 'table', 'column', 'except' ];

  /**
   * PDO
   *
   * @var PDO
   */
  protected $pdo;

  /**
   * UniqueRule constructor
   *
   * @param PDO $pdo
   */
  public function __construct( PDO $pdo ) {
    $this->pdo = $pdo;
  }

  /**
   * Checks
   *
   * @param $value
   *
   * @return bool
   */
  public function check( $value ): bool {
    // Make sure required parameters exists.
    $this->requireParameters( [ 'table', 'column' ] );

    // Getting parameters.
    $column = $this->parameter( 'column' );
    $table  = $this->parameter( 'table' );
    $except = $this->parameter( 'except' );

    if ( $except && $except == $value ) {
      return true;
    }

    // Do query.
    $stmt = $this->pdo->prepare( "SELECT COUNT(*) AS count FROM `{$table}` WHERE `{$column}` = :value" );
    $stmt->bindParam( ':value', $value );
    $stmt->execute();
    $data = $stmt->fetch( PDO::FETCH_ASSOC );

    // True for valid, false for invalid.
    return intval( $data['count'] ) === 0;
  }
}
