<?php

namespace App\Controllers;

use App\SiteInfo;
use Core\View;

/**
 * Test controller
 */
class Test extends \Core\Controller {

  /**
   * Shows the index page
   *
   * @return void
   * @throws \Exception
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function indexAction() {
    $shop_id          = 1;
    $order_id         = 8;
    $order_price      = 3000;
    $callback_url     = 'https://yandex.ru';
    $is_loan_deferred = 0;
    $goods            = [
      [
        'name'          => 'Наушники внутриканальные Sony MDR-EX15LP Black',
        'price'         => 3000,
        'is_returnable' => true
      ]
    ];
    $goods_serialized = serialize( $goods );
    $secret_key       = 'FMNDesQ58G8y4O8bgGPvsEGFPwEe8Gdj';
    $control          = md5( $shop_id . $order_id . $order_price . $callback_url . $is_loan_deferred . $goods_serialized . $secret_key );

    View::renderTemplate( 'Test/index.html', [
      'title'            => 'Тестовая страница',
      'body_class'       => 'body_test',
      'phone_number'     => SiteInfo::PHONE,
      'phone_link'       => SiteInfo::getCleanPhone(),
      'form_action'      => SiteInfo::getSiteURL() . '/phone-number',
      'shop_id'          => $shop_id,
      'order_id'         => $order_id,
      'order_price'      => $order_price,
      'callback_url'     => $callback_url,
      'is_loan_deferred' => $is_loan_deferred,
      'goods'            => $goods_serialized,
      'control'          => $control
    ] );
  }
}
