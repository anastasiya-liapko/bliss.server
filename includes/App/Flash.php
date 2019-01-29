<?php

namespace App;

/**
 * Flash notification messages
 *
 * Messages for one-time display using the session
 * for storage between requests.
 */
class Flash {

  /**
   * Success message type
   *
   * @var string
   */
  const SUCCESS = 'success';

  /**
   * Information message type
   *
   * @var string
   */
  const INFO = 'info';

  /**
   * Warning message type
   *
   * @var string
   */
  const WARNING = 'warning';

  /**
   * Add a message
   *
   * @param string $message The message content.
   * @param string $type The optional message type, defaults to SUCCESS.
   *
   * @return void
   */
  public static function addMessage( $message, $type = 'success' ) {
    if ( ! isset( $_SESSION['flash_notifications'] ) ) {
      $_SESSION['flash_notifications'] = [];
    }

    $_SESSION['flash_notifications'][] = [
      'body' => $message,
      'type' => $type
    ];
  }

  /**
   * Get all the messages
   *
   * @return mixed  An array with all the messages or false if none set.
   */
  public static function getMessages() {
    if ( isset( $_SESSION['flash_notifications'] ) ) {
      $messages = $_SESSION['flash_notifications'];
      unset( $_SESSION['flash_notifications'] );

      return $messages;
    }

    return false;
  }
}
