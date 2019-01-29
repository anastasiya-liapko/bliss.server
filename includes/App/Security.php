<?php

namespace App;

/**
 * Security class
 */
class Security {

  /**
   * Creates the passwords
   *
   * @param int $length Length of the password.
   * @param bool $special_chars Includes special chars in the password.
   * @param bool $extra_special_chars Includes extra special chars in the password.
   *
   * @return string $password Password.
   */
  public static function generatePassword(
    int $length = 12,
    bool $special_chars = true,
    bool $extra_special_chars = false
  ): string {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    if ( $special_chars ) {
      $chars .= '!@#$%^&*()';
    }

    if ( $extra_special_chars ) {
      $chars .= '-_ []{}<>~`+=,.;:/?|';
    }

    $password = '';

    for ( $i = 0; $i < $length; $i ++ ) {
      $password .= substr( $chars, rand( 0, strlen( $chars ) - 1 ), 1 );
    }

    return $password;
  }

  /**
   * Creates the sms code
   *
   * @param int $length Length of the code.
   *
   * @return string $code Code.
   */
  public static function generateSmsCode( int $length = 4 ): string {
    $chars = '0123456789';

    $code = '';

    for ( $i = 0; $i < $length; $i ++ ) {
      $code .= substr( $chars, rand( 0, strlen( $chars ) - 1 ), 1 );
    }

    return $code;
  }
}
