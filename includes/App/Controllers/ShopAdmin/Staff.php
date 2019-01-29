<?php

namespace App\Controllers\ShopAdmin;

use App\Auth;
use App\Models\ShopAdmin;
use App\SiteInfo;
use Core\View;

/**
 * Integration controller
 */
class Staff extends \Core\Controller {

  /**
   * Shows the staff page
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

    if ( $current_admin->getRole() !== 'admin' ) {
      throw new \Exception( 'No route matched.', 404 );
    }

    $shop_id = $current_admin->getShopId();

    View::renderTemplate( 'ShopAdmin/Staff/index.html', [
      'title'         => 'Персонал',
      'body_class'    => 'body_admin',
      'current_role'  => $current_admin->getRole(),
      'delete_action' => SiteInfo::getSiteURL() . '/shop-admin/staff/delete',
      'admins'        => ShopAdmin::getAllByShopId( $shop_id )
    ] );
  }

  /**
   * Shows the add page
   *
   * @return void
   * @throws \Exception
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public function addAction() {
    $current_admin = Auth::getAdmin();

    if ( ! $current_admin || $current_admin->getActive() !== 1 ) {
      $this->redirect( SiteInfo::getSiteURL() . '/shop-admin/login' );
    }

    if ( $current_admin->getRole() !== 'admin' ) {
      throw new \Exception( 'No route matched.', 404 );
    }

    View::renderTemplate( 'ShopAdmin/Staff/add.html', [
      'title'        => 'Добававление',
      'body_class'   => 'body_admin',
      'form_action'  => SiteInfo::getSiteURL() . '/shop-admin/staff/create',
      'current_role' => $current_admin->getRole(),
    ] );
  }

  /**
   * Create action
   *
   * @throws \Exception
   */
  public function createAction() {
    $this->forbidNotAjax();

    $current_admin = Auth::getAdmin();

    if ( ! $current_admin || $current_admin->getRole() !== 'admin' || $current_admin->getActive() !== 1 ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'У вас нет прав на это действие.' ] ] );
    }

    $shop_id = $current_admin->getShopId();

    $data = [
      'name'    => $_POST['name'] ?? '',
      'email'   => $_POST['email'] ?? '',
      'phone'   => $_POST['phone'] ?? '',
      'active'  => $_POST['active'] ?? '',
      'shop_id' => $shop_id,
      'role'    => 'manager'
    ];

    $admin = new ShopAdmin( $data );

    if ( ! $admin->create() ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $admin->getErrors() ] );
    }

    $admin->sendAuthEmail();

    $this->redirect( SiteInfo::getSiteURL() . '/shop-admin/staff' );
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

    if ( $current_admin->getRole() !== 'admin' ) {
      throw new \Exception( 'No route matched.', 404 );
    }

    $admin_id = $this->route_params['id'];
    $admin    = ShopAdmin::findByID( $admin_id );

    View::renderTemplate( 'ShopAdmin/Staff/edit.html', [
      'title'         => 'Редактирование',
      'body_class'    => 'body_admin',
      'current_role'  => $current_admin->getRole(),
      'form_action'   => SiteInfo::getSiteURL() . '/shop-admin/staff/' . $admin_id . '/update',
      'name'          => $admin->getName(),
      'email'         => $admin->getEmail(),
      'password_hash' => $admin->getPasswordHash(),
      'phone'         => $admin->getPhone(),
      'role'          => $admin->getRole(),
      'active'        => $admin->getActive()
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

    if ( ! $current_admin || $current_admin->getRole() !== 'admin' || $current_admin->getActive() !== 1 ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'У вас нет прав на это действие.' ] ] );
    }

    $admin_id = $this->route_params['id'];
    $admin    = ShopAdmin::findByID( $admin_id );

    if ( ! $admin->update( $_POST ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => $admin->getErrors() ] );
    }

    $this->redirect( SiteInfo::getSiteURL() . '/shop-admin/staff' );
  }

  /**
   * Delete action
   *
   * @throws \Exception
   */
  public function deleteAction() {
    $this->forbidNotAjax();

    $current_admin = Auth::getAdmin();

    if ( ! $current_admin || $current_admin->getRole() !== 'admin' || $current_admin->getActive() !== 1 ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'У вас нет прав на это действие.' ] ] );
    }

    $id        = $_POST['id'] ?? '';
    $remove_id = $_POST['remove_id'] ?? '';

    if ( $current_admin->getId() === $id ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'Нельзя удалить администратора магазина.' ] ] );
    }

    if ( ! ShopAdmin::delete( $id ) ) {
      $this->sendJsonResponse( [ 'error' => true, 'message' => [ 'Не удалось удалить запись, попробуйте ещё раз.' ] ] );
    }

    $this->sendJsonResponse( [
      'error'         => false,
      'removeElement' => [
        'id' => $remove_id,
      ]
    ] );
  }
}
