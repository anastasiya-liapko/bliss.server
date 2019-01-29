<?php

namespace App\Models;

use App\Token;
use PDO;

/**
 * Remembered login model
 */
class Login extends \Core\Model {

  /**
   * Finds a remembered login model by the token
   *
   * @param string $token The remembered login token.
   *
   * @return mixed Remembered login object if found, false otherwise.
   * @throws \Exception
   */
  public static function findByToken( string $token ) {
    $token      = new Token( $token );
    $token_hash = $token->getHash();

    $sql = 'SELECT * FROM remembered_logins WHERE token_hash = :token_hash';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':token_hash', $token_hash, PDO::PARAM_STR );

    $stmt->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Gets the admin model associated with this remembered login
   *
   * @return ShopAdmin The user model.
   */
  public function getAdmin() {
    return ShopAdmin::findByID( $this->admin_id );
  }

  /**
   * Sees if the remember token has expired or not, based on the current system time
   *
   * @return boolean True if the token has expired, false otherwise.
   */
  public function hasExpired() {
    return strtotime( $this->expires_at ) < time();
  }

  /**
   * Deletes this model
   *
   * @return void
   */
  public function delete() {
    $sql = 'DELETE FROM remembered_logins WHERE token_hash = :token_hash';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':token_hash', $this->token_hash, PDO::PARAM_STR );

    $stmt->execute();
  }
}
