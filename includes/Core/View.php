<?php

namespace Core;

/**
 * View
 */
class View {

  /**
   * Renders a view file
   *
   * @param string $view The view file.
   * @param array $args Associative array of data to display in the view (optional).
   *
   * @return void
   * @throws \Exception
   */
  public static function render( $view, $args = [] ) {
    extract( $args, EXTR_SKIP );

    $file = dirname( __DIR__ ) . "/App/Views/$view";  // relative to Core directory

    if ( is_readable( $file ) ) {
      require $file;
    } else {
      throw new \Exception( "$file not found" );
    }
  }

  /**
   * Gets the content of a view template using Twig
   *
   * @param string $template The template file.
   * @param array $args Associative array of data to display in the view (optional).
   *
   * @return string
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public static function getTemplate( $template, $args = [] ) {
    static $twig = null;

    if ( $twig === null ) {
      $loader = new \Twig_Loader_Filesystem( dirname( __DIR__ ) . '/App/Views' );
      $twig   = new \Twig_Environment( $loader );
      $twig->addGlobal( 'site_url', \App\SiteInfo::getSiteURL() );
      $twig->addGlobal( 'site_name', \App\SiteInfo::NAME );
      $twig->addGlobal( 'flash_messages', \App\Flash::getMessages() );
    }

    return $twig->render( $template, $args );
  }

  /**
   * Renders a view template using Twig
   *
   * @param string $template The template file.
   * @param array $args Associative array of data to display in the view (optional).
   *
   * @return void
   * @throws \Twig_Error_Loader
   * @throws \Twig_Error_Runtime
   * @throws \Twig_Error_Syntax
   */
  public static function renderTemplate( $template, $args = [] ) {
    echo static::getTemplate( $template, $args );
  }
}
