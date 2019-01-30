<?php

namespace App\Models;

use App\Mail;
use App\PlainRule;
use App\SiteInfo;
use Core\View;
use PDO;
use Rakit\Validation\Validator;

class Request extends \Core\Model {

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
   * Client id
   *
   * @var mixed
   */
  private $client_id = null;

  /**
   * Shop id
   *
   * @var mixed
   */
  private $shop_id = null;

  /**
   * Order id
   *
   * @var mixed
   */
  private $order_id = null;

  /**
   * Order price
   *
   * @var mixed
   */
  private $order_price = null;

  /**
   * Goods
   *
   * @var string
   */
  private $goods = '';

  /**
   * Status of request
   *
   * @var string
   */
  private $status = '';

  /**
   * Mfo id
   *
   * @var mixed
   */
  private $mfo_id = null;

  /**
   * Link to conditions
   *
   * @var string
   */
  private $conditions_url = '';

  /**
   * Is loan deferred
   *
   * @var int
   */
  private $is_loan_deferred = 0;

  /**
   * Is loan received
   *
   * @var int
   */
  private $is_loan_received = 0;

  /**
   * Is order received
   *
   * @var int
   */
  private $is_order_received = 0;

  /**
   * Tracking id
   *
   * @var string
   */
  private $tracking_id = '';

  /**
   * Time start
   *
   * @var string
   */
  private $time_start = '';

  /**
   * Time finish
   *
   * @var string
   */
  private $time_finish = '';

  /**
   * Request constructor
   *
   * @param array $data Initial property values.
   */
  public function __construct( array $data = [] ) {
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
      'numeric'  => ':attribute - поле должно содержать только цифры.'
    ] );

    $validator->addValidator( 'plain', new PlainRule() );

    $validation = $validator->validate( [
      'client_id'         => $this->client_id,
      'shop_id'           => $this->shop_id,
      'order_id'          => $this->order_id,
      'order_price'       => $this->order_price,
      'goods'             => $this->goods,
      'status'            => $this->status,
      'mfo_id'            => $this->mfo_id,
      'conditions_url'    => $this->conditions_url,
      'is_loan_deferred'  => $this->is_loan_deferred,
      'is_loan_received'  => $this->is_loan_received,
      'is_order_received' => $this->is_order_received,
      'tracking_id'       => $this->tracking_id,
      'time_start'        => $this->time_start,
      'time_finish'       => $this->time_finish,
    ], [
      'client_id'         => 'required|numeric',
      'shop_id'           => 'required|numeric',
      'order_id'          => 'required|numeric',
      'order_price'       => 'required|numeric',
      'goods'             => 'required|plain',
      'status'            => 'plain',
      'mfo_id'            => 'numeric',
      'conditions_url'    => 'plain',
      'is_loan_deferred'  => 'required|numeric',
      'is_loan_received'  => 'numeric',
      'is_order_received' => 'numeric',
      'tracking_id'       => 'plain',
      'time_start'        => 'plain',
      'time_finish'       => 'plain',
    ] );

    $validation->setAliases( [
      'client_id'         => 'ID клиента',
      'shop_id'           => 'ID магазина',
      'order_id'          => 'ID заказа',
      'order_price'       => 'Сумма заказа',
      'goods'             => 'Товары',
      'status'            => 'Статус заявки',
      'mfo_id'            => 'ID мфо',
      'conditions_url'    => 'URL-ссылка для возврата',
      'is_loan_deferred'  => 'Отложенный ли кредит',
      'is_loan_received'  => 'Выдали ли деньги МФО',
      'is_order_received' => 'Получен ли товар клиентом',
      'tracking_id'       => 'Код отслеживания посылки',
      'time_start'        => 'Время начала обработки заявки',
      'time_finish'       => 'Время завершения обработки заявки',
    ] );

    $validation->validate();

    if ( $validation->fails() ) {
      $this->errors = $validation->errors()->all();
    }
  }

  /**
   * Validates tracking id
   *
   * @param string tracking_id The tracking id.
   *
   * @throws \Rakit\Validation\RuleQuashException
   * @return void
   */
  private function validateTrackingId( string $tracking_id ) {
    $validator = new Validator();

    $validator->addValidator( 'plain', new PlainRule() );

    $validation = $validator->validate( [
      'tracking_id' => $tracking_id
    ], [
      'tracking_id' => 'plain',
    ] );

    $validation->setAliases( [
      'tracking_id' => 'Код отслеживания посылки'
    ] );

    $validation->validate();

    if ( $validation->fails() ) {
      $this->errors = $validation->errors()->all();
    }
  }

  /**
   * Creates request
   *
   * @return bool
   * @throws \Rakit\Validation\RuleQuashException
   */
  public function create() {
    $this->validate();

    if ( empty( $this->errors ) ) {
      $this->status     = 'pending';
      $this->time_start = date( 'Y-m-d H:i:s', time() );

      $sql = 'INSERT INTO requests (client_id, shop_id, order_id, order_price, goods, status, is_loan_deferred, time_start) VALUES (:client_id, :shop_id, :order_id, :order_price, :goods, :status, :is_loan_deferred, :time_start)';

      $db   = static::getDB();
      $stmt = $db->prepare( $sql );

      $stmt->bindValue( ':client_id', $this->client_id, PDO::PARAM_INT );
      $stmt->bindValue( ':shop_id', $this->shop_id, PDO::PARAM_INT );
      $stmt->bindValue( ':order_id', $this->order_id, PDO::PARAM_INT );
      $stmt->bindValue( ':order_price', $this->order_price, PDO::PARAM_STR );
      $stmt->bindValue( ':goods', $this->goods, PDO::PARAM_STR );
      $stmt->bindValue( ':status', $this->status, PDO::PARAM_STR );
      $stmt->bindValue( ':is_loan_deferred', $this->is_loan_deferred, PDO::PARAM_INT );
      $stmt->bindValue( ':time_start', $this->time_start, PDO::PARAM_STR );

      if ( $stmt->execute() ) {
        $this->id = $db->lastInsertId();

        return true;
      } else {
        $this->errors[] = 'Не удалось создать заявку, попробуйте ещё раз.';
      }
    }

    return false;
  }

  /**
   * Changes status of the request
   *
   * Changes status and finish time of the request.
   *
   * @param string $status Status of the request.
   * @param string $time_finish Optional. Time finish.
   *
   * @return bool
   */
  public function changeStatus( string $status, string $time_finish = null ): bool {
    $this->status      = $status;
    $this->time_finish = $time_finish;

    $sql = 'UPDATE requests SET status = :status, time_finish = :time_finish WHERE id = :id';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':status', $this->status, PDO::PARAM_STR );
    $stmt->bindValue( ':time_finish', $this->time_finish, PDO::PARAM_STR );
    $stmt->bindValue( ':id', $this->id, PDO::PARAM_INT );

    if ( $stmt->execute() ) {
      return true;
    } else {
      $this->errors[] = 'Не удалось обновить статус заявки, попробуйте ещё раз.';
    }

    return false;
  }

  /**
   * Changes tracking id
   *
   * @param string $tracking_id The tracking id.
   *
   * @return bool
   * @throws \Rakit\Validation\RuleQuashException
   */
  public function changeTrackingId( string $tracking_id ): bool {
    $this->validateTrackingId( $tracking_id );

    if ( empty( $this->errors ) ) {

      $this->tracking_id = $tracking_id;

      $sql = 'UPDATE requests SET tracking_id = :tracking_id WHERE id = :id';

      $db   = static::getDB();
      $stmt = $db->prepare( $sql );

      $stmt->bindValue( ':tracking_id', $this->tracking_id, PDO::PARAM_STR );
      $stmt->bindValue( ':id', $this->id, PDO::PARAM_INT );

      if ( $stmt->execute() ) {
        return true;
      } else {
        $this->errors[] = 'Не удалось сохранить код отслеживания, попробуйте ещё раз.';
      }
    }

    return false;
  }

  /**
   * Finds an request model for a client
   *
   * @param int $client_id Client id
   * @param int $shop_id Shop id
   * @param int $order_id Order id
   *
   * @return mixed The request object if found, false otherwise.
   * @throws \Exception
   */
  public static function findForClient( int $client_id, int $shop_id, int $order_id ) {
    $sql = 'SELECT * FROM requests WHERE client_id = :client_id AND shop_id = :shop_id AND order_id = :order_id LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':client_id', $client_id, PDO::PARAM_INT );
    $stmt->bindValue( ':shop_id', $shop_id, PDO::PARAM_INT );
    $stmt->bindValue( ':order_id', $order_id, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Finds the request model for a shop
   *
   * @param int $id
   * @param int $shop_id
   *
   * @return mixed The request object if found, false otherwise.
   */
  public static function findForShop( int $id, int $shop_id ) {
    $sql = 'SELECT * FROM requests WHERE id = :id AND shop_id = :shop_id LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
    $stmt->bindValue( ':shop_id', $shop_id, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_CLASS, get_called_class() );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Gets info of the request include a client info
   *
   * @param int $id
   *
   * @return mixed
   */
  public static function getFullRequestInfo( int $id ) {
    $sql = 'SELECT a.id, a.client_id, a.shop_id, a.order_id, a.order_price, a.goods, a.status, a.is_loan_deferred, b.last_name, b.first_name, b.middle_name, b.birth_date, b.birth_place, b.tin, b.passport_number, b.passport_division_code, b.passport_issued_by, b.passport_issued_date, b.workplace, b.salary, b.reg_zip_code, b.reg_city, b.reg_street, b.reg_building, b.reg_apartment, b.is_address_matched, b.fact_zip_code, b.fact_city, b.fact_street, b.fact_building, b.fact_apartment, b.email, b.phone FROM requests AS a INNER JOIN clients AS b WHERE a.id = :id AND a.client_id = b.id LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':id', $id, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Gets total count of requests by the shop id
   *
   * @param int $shop_id
   *
   * @return mixed Array of results, false otherwise.
   */
  public static function getTotalCountByShopId( int $shop_id ) {
    $sql = 'SELECT COUNT(*) AS count FROM requests WHERE shop_id = :shop_id';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':shop_id', $shop_id, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Gets requests statistics by the shop id
   *
   * @param int $shop_id
   *
   * @return mixed Array of results, false otherwise.
   */
  public static function getStatisticByShopId( int $shop_id ) {
    $sql = 'SELECT SUM(order_price) AS sum, COUNT(id) as amount FROM requests 
            WHERE shop_id = :shop_id
            UNION ALL
            SELECT SUM(order_price) AS sum, COUNT(id) as amount FROM requests 
            WHERE shop_id = :shop_id AND status = :status_declined
            UNION ALL
            SELECT SUM(order_price) AS sum, COUNT(id) as amount FROM requests 
            WHERE shop_id = :shop_id AND status = :status_approved';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':shop_id', $shop_id, PDO::PARAM_INT );
    $stmt->bindValue( ':status_declined', 'declined', PDO::PARAM_INT );
    $stmt->bindValue( ':status_approved', 'approved', PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $stmt->execute();

    return $stmt->fetchAll();
  }

  /**
   * Gets the request by the id and the shop id
   *
   * @param int $id
   * @param int $shop_id
   *
   * @return mixed Array of results, false otherwise.
   */
  public static function getRequestByShopId( int $id, int $shop_id ) {
    $sql = 'SELECT * FROM requests WHERE id = :id AND shop_id = :shop_id LIMIT 1';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':id', $id, PDO::PARAM_INT );
    $stmt->bindValue( ':shop_id', $shop_id, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $stmt->execute();

    return $stmt->fetch();
  }

  /**
   * Gets requests by the shop id
   *
   * @param int $shop_id
   * @param int $padding
   * @param int $limit
   *
   * @return mixed Array of results, false otherwise.
   */
  public static function getAllRequestsByShopId( int $shop_id, int $padding = 0, int $limit = 50 ) {
    $sql = 'SELECT * FROM requests WHERE shop_id = :shop_id ORDER BY id DESC LIMIT :padding, :limit';

    $db   = static::getDB();
    $stmt = $db->prepare( $sql );

    $stmt->bindValue( ':shop_id', $shop_id, PDO::PARAM_INT );
    $stmt->bindValue( ':padding', $padding, PDO::PARAM_INT );
    $stmt->bindValue( ':limit', $limit, PDO::PARAM_INT );

    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $stmt->execute();

    return $stmt->fetchAll();
  }

  /**
   * Sends the email to the admin with an application info
   *
   * @param int $request_id Request id.
   *
   * @return bool
   * @throws \PHPMailer\PHPMailer\Exception
   * @throws \Exception
   */
  public static function sendApplicationEmail( int $request_id ): bool {
    $args = [ 'request_id' => $request_id ];

    $text = View::getTemplate( 'ProfileClient/solve_request_email.txt', $args );
    $html = View::getTemplate( 'ProfileClient/solve_request_email.html', $args );

    return Mail::send( SiteInfo::INFO_EMAIL, 'Заявка на кредит требует вмешательства', $text, $html );
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
   * Gets client id
   *
   * @return mixed
   */
  public function getClientId() {
    return $this->client_id;
  }

  /**
   * Gets shop id
   *
   * @return mixed
   */
  public function getShopId() {
    return $this->shop_id;
  }

  /**
   * Gets order id
   *
   * @return mixed
   */
  public function getOrderId() {
    return $this->order_id;
  }

  /**
   * Gets order price
   *
   * @return mixed
   */
  public function getOrderPrice() {
    return $this->order_price;
  }

  /**
   * Gets goods
   *
   * @return string
   */
  public function getGoods(): string {
    return $this->goods;
  }

  /**
   * Gets status
   *
   * @return string
   */
  public function getStatus(): string {
    return $this->status;
  }

  /**
   * Gets MFO id
   *
   * @return mixed
   */
  public function getMfoId() {
    return $this->mfo_id;
  }

  /**
   * Gets link to conditions
   *
   * @return string
   */
  public function getConditionsUrl(): string {
    return $this->conditions_url;
  }

  /**
   * Gets is loan deferred
   *
   * @return int
   */
  public function getIsLoanDeferred(): int {
    return $this->is_loan_deferred;
  }

  /**
   * Gets is loan received
   *
   * @return int
   */
  public function getIsLoanReceived(): int {
    return $this->is_loan_received;
  }

  /**
   * Gets is order received
   *
   * @return int
   */
  public function getIsOrderReceived(): int {
    return $this->is_order_received;
  }

  /**
   * Gets tracking id
   *
   * @return string
   */
  public function getTrackingId(): string {
    return $this->tracking_id;
  }

  /**
   * Gets time start
   *
   * @return string
   */
  public function getTimeStart(): string {
    return $this->time_start;
  }

  /**
   * Gets time finish
   *
   * @return string
   */
  public function getTimeFinish(): string {
    return $this->time_finish;
  }
}
