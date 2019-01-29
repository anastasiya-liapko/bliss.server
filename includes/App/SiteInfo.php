<?php

namespace App;

/**
 * SiteInfo class
 */
class SiteInfo {

  /**
   * Site name
   */
  const NAME = 'Bliss';

  /**
   * Site phone number
   */
  const PHONE = '8(800) 00-000-00';

  /**
   * Site info email
   */
  const INFO_EMAIL = 'info@bliss.ru';

  /**
   * Get clean site phone number
   *
   * @return mixed
   */
  public static function getCleanPhone() {
    return str_replace( [ '(', ')', ' ', '-' ], '', static::PHONE );
  }

  /**
   * Get the site protocol
   *
   * @return string
   */
  public static function getSiteProtocol() {
    return isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' . '://';
  }

  /**
   * Get the site url
   *
   * @return string
   */
  public static function getSiteURL() {
    return static::getSiteProtocol() . $_SERVER['HTTP_HOST'];
  }
}
