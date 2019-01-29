<?php

namespace App;

use Rakit\Validation\Rule;

/**
 * Class PlainRule
 */
class PlainRule extends Rule {

  /**
   * Message
   *
   * @var string
   */
  protected $message = ':attribute - поле не должно содержать HTML-теги';


  /**
   * PlainRule constructor
   *
   */
  public function __construct() {
  }

  /**
   * Checks
   *
   * @param $value
   *
   * @return bool
   */
  public function check( $value ): bool {
    return $value === strip_tags( $value );
  }
}
