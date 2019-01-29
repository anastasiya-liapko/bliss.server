<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class Mail
 */
class Mail {

  /**
   * Send a message
   *
   * @param mixed $to Recipient
   * @param string $subject Subject
   * @param string $text Text-only content of the message
   * @param string $html HTML content of the message
   *
   * @return bool
   * @throws \PHPMailer\PHPMailer\Exception
   */
  public static function send( $to, string $subject, string $text, string $html ): bool {
    $mail = new PHPMailer();

    $mail->CharSet = 'UTF-8';
    $mail->setLanguage( 'ru' );
    $mail->setFrom( SiteInfo::INFO_EMAIL, SiteInfo::NAME );

    if ( is_array( $to ) ) {
      foreach ( $to as $item ) {
        $mail->addAddress( $item );
      }
    } else {
      $mail->addAddress( $to );
    }

    $mail->isHTML( true );
    $mail->Subject = $subject;

    $mail->AltBody = $text;

    $mail->Body = $html;

    return $mail->send();
  }
}
