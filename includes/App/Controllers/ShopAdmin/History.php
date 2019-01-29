<?php

namespace App\Controllers\ShopAdmin;

use App\Auth;
use App\Models\Request;
use App\Pagination;
use App\SiteInfo;
use Core\View;

/**
 * Integration controller
 */
class History extends \Core\Controller {

  /**
   * Shows the history page
   *
   * @return void
   * @throws \Exception
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function indexAction() {
    $current_admin = Auth::getAdmin();

    if ( ! $current_admin || $current_admin->getActive() !== 1 ) {
      $this->redirect( SiteInfo::getSiteURL() . '/shop-admin/login' );
    }

    $shop_id        = $current_admin->getShopId();
    $total          = Request::getTotalCountByShopId( $shop_id );
    $total_items    = $total['count'];
    $items_per_page = 50;
    $current_page   = isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ? (int) $_GET['page'] : 1;
    $url_pattern    = SiteInfo::getSiteURL() . '/shop-admin/history?page=(:num)';
    $padding        = $items_per_page * ( $current_page - 1 );

    $pagination = new Pagination( $total_items, $items_per_page, $current_page, $url_pattern );

    View::renderTemplate( 'ShopAdmin/History/index.html', [
      'title'        => 'История заказов',
      'body_class'   => 'body_admin',
      'current_role' => $current_admin->getRole(),
      'requests'     => Request::getAllRequestsByShopId( $shop_id, $padding, $items_per_page ),
      'statistic'    => Request::getStatisticByShopId( $shop_id ),
      'pagination'   => $pagination
    ] );
  }

  /**
   * Shows the edit page
   *
   * @return void
   * @throws \Exception
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function editAction() {
    $current_admin = Auth::getAdmin();

    if ( ! $current_admin || $current_admin->getActive() !== 1 ) {
      $this->redirect( SiteInfo::getSiteURL() . '/shop-admin/login' );
    }

    $shop_id    = $current_admin->getShopId();
    $request_id = $this->route_params['id'];

    View::renderTemplate( 'ShopAdmin/History/edit.html', [
      'title'        => 'Редактирование',
      'body_class'   => 'body_admin',
      'current_role' => $current_admin->getRole(),
      'form_action'  => SiteInfo::getSiteURL() . '/shop-admin/history/' . $request_id . '/update',
      'request'      => Request::getRequestByShopId( $request_id, $shop_id ),
    ] );
  }

  /**
   * Update action
   *
   * @throws \Exception
   */
  public function updateAction() {
    $this->forbidNotAjax();

    $current_admin = Auth::getAdmin();

    if ( ! $current_admin || $current_admin->getActive() !== 1 ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'У вас нет прав на это действие.' ] ] );
    }

    $request_id = $this->route_params['id'];
    $shop_id    = $current_admin->getShopId();

    $request = Request::findForShop( $request_id, $shop_id );

    if ( ! $request->changeTrackingId( $_POST['tracking_id'] ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $request->getErrors() ] );
    }

    $this->redirect( SiteInfo::getSiteURL() . '/shop-admin/history' );
  }
}
