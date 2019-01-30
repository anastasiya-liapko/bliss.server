<?php

namespace App\Models;

use PDO;

class MFO extends \Core\Model {

  /**
   * Gets mfo for shop
   *
   * @param int $shop_id
   * @param float $loan_sum
   * @param int $can_loan_deferred
   *
   * @return array
   */
  public static function getForShop( int $shop_id, float $loan_sum, int $can_loan_deferred = 0 ) {
    $sql = 'SELECT mfo.id, mfo.name, mfo.phone, mfo.email, mfo.class_connector, mfo.api_id, mfo.api_password FROM mfo 
            INNER JOIN mfo_shop_cooperation 
            ON mfo.id = mfo_shop_cooperation.mfo_id 
            AND mfo_shop_cooperation.shop_id = :shop_id 
            AND :loan_sum >= mfo.min_loan_sum
            AND :loan_sum <= mfo.max_loan_sum
            AND mfo.can_loan_deferred = :can_loan_deferred';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':shop_id', $shop_id, PDO::PARAM_INT );
    $stmt->bindValue( ':loan_sum', $loan_sum, PDO::PARAM_STR );
    $stmt->bindValue( ':can_loan_deferred', $can_loan_deferred, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $stmt->execute();

    return $stmt->fetchAll();
  }
}
