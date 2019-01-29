<?php

namespace App;

class Pagination {

  /**
   * Number placeholder
   *
   * @var string
   */
  const NUM_PLACEHOLDER = '(:num)';

  /**
   * Total items
   *
   * @var int
   */
  protected $total_items;

  /**
   * Number of pages
   *
   * @var
   */
  protected $num_pages;

  /**
   * Items per page
   *
   * @var int
   */
  protected $items_per_page;

  /**
   * Current page
   *
   * @var int
   */
  protected $current_page;

  /**
   * Url pattern
   *
   * @var string
   */
  protected $url_pattern;

  /**
   * Max pages to show
   *
   * @var int
   */
  protected $max_pages_to_show = 5;

  /**
   * Pagination constructor
   *
   * @param int $total_items The total number of items.
   * @param int $items_per_page The number of items per page.
   * @param int $current_page The current page number.
   * @param string $url_pattern A URL for each page, with (:num) as a placeholder for the page number. Ex. '/foo/page/(:num)'
   */
  public function __construct( int $total_items, int $items_per_page, int $current_page, string $url_pattern = '' ) {
    $this->total_items    = $total_items;
    $this->items_per_page = $items_per_page;
    $this->current_page   = $current_page;
    $this->url_pattern    = $url_pattern;

    $this->updateNumPages();
  }

  /**
   * Updates number of pages
   *
   * @return void
   */
  protected function updateNumPages() {
    $this->num_pages = ( $this->items_per_page == 0 ? 0 : (int) ceil( $this->total_items / $this->items_per_page ) );
  }

  /**
   * Sets max pages to show
   *
   * @param int $max_pages_to_show
   *
   * @return void
   * @throws \InvalidArgumentException if $max_pages_to_show is less than 3.
   */
  public function setMaxPagesToShow( int $max_pages_to_show ) {
    if ( $max_pages_to_show < 3 ) {
      throw new \InvalidArgumentException( 'maxPagesToShow cannot be less than 3.' );
    }

    $this->max_pages_to_show = $max_pages_to_show;
  }

  /**
   * Gets max pages to show
   *
   * @return int
   */
  public function getMaxPagesToShow(): int {
    return $this->max_pages_to_show;
  }

  /**
   * Sets the current page
   *
   * @param int $current_page
   *
   * @return void
   */
  public function setCurrentPage( int $current_page ) {
    $this->current_page = $current_page;
  }

  /**
   * Gets the current page
   *
   * @return int
   */
  public function getCurrentPage(): int {
    return $this->current_page;
  }

  /**
   * Sets items per page
   *
   * @param int $items_per_page
   *
   * @return void
   */
  public function setItemsPerPage( int $items_per_page ) {
    $this->items_per_page = $items_per_page;
    $this->updateNumPages();
  }

  /**
   * Gets items per page
   *
   * @return int
   */
  public function getItemsPerPage(): int {
    return $this->items_per_page;
  }

  /**
   * Sets total items
   *
   * @param int $total_items
   *
   * @return void
   */
  public function setTotalItems( int $total_items ) {
    $this->total_items = $total_items;
    $this->updateNumPages();
  }

  /**
   * Gets total items
   *
   * @return int
   */
  public function getTotalItems(): int {
    return $this->total_items;
  }

  /**
   * Gets number of pages
   *
   * @return int
   */
  public function getNumPages(): int {
    return $this->num_pages;
  }

  /**
   * Sets the url pattern
   *
   * @param string $url_pattern
   *
   * @return void
   */
  public function setUrlPattern( string $url_pattern ) {
    $this->url_pattern = $url_pattern;
  }

  /**
   * Gets the url pattern
   *
   * @return string
   */
  public function getUrlPattern(): string {
    return $this->url_pattern;
  }

  /**
   * Gets the page url
   *
   * @param int $pageNum
   *
   * @return string
   */
  public function getPageUrl( int $pageNum ): string {
    return str_replace( self::NUM_PLACEHOLDER, $pageNum, $this->url_pattern );
  }

  /**
   * Gets the next page
   *
   * @return int|null
   */
  public function getNextPage() {
    if ( $this->current_page < $this->num_pages ) {
      return $this->current_page + 1;
    }

    return null;
  }

  /**
   * Gets the previous page
   *
   * @return int|null
   */
  public function getPrevPage() {
    if ( $this->current_page > 1 ) {
      return $this->current_page - 1;
    }

    return null;
  }

  /**
   * Gets the next page
   *
   * @return string|null
   */
  public function getNextUrl() {
    if ( ! $this->getNextPage() ) {
      return null;
    }

    return $this->getPageUrl( $this->getNextPage() );
  }

  /**
   * Gets the previous url
   *
   * @return string|null
   */
  public function getPrevUrl() {
    if ( ! $this->getPrevPage() ) {
      return null;
    }

    return $this->getPageUrl( $this->getPrevPage() );
  }

  /**
   * Gets an array of paginated page data
   *
   * @return array
   */
  public function getPages(): array {
    $pages = array();

    if ( $this->num_pages <= 1 ) {
      return array();
    }

    if ( $this->num_pages <= $this->max_pages_to_show ) {
      for ( $i = 1; $i <= $this->num_pages; $i ++ ) {
        $pages[] = $this->createPage( $i, $i == $this->current_page );
      }
    } else {

      $num_adjacents = (int) floor( ( $this->max_pages_to_show - 3 ) / 2 );

      if ( $this->current_page + $num_adjacents > $this->num_pages ) {
        $slidingStart = $this->num_pages - $this->max_pages_to_show + 2;
      } else {
        $slidingStart = $this->current_page - $num_adjacents;
      }

      if ( $slidingStart < 2 ) {
        $slidingStart = 2;
      }

      $slidingEnd = $slidingStart + $this->max_pages_to_show - 3;

      if ( $slidingEnd >= $this->num_pages ) {
        $slidingEnd = $this->num_pages - 1;
      }

      $pages[] = $this->createPage( 1, $this->current_page == 1 );

      if ( $slidingStart > 2 ) {
        $pages[] = $this->createPageEllipsis();
      }

      for ( $i = $slidingStart; $i <= $slidingEnd; $i ++ ) {
        $pages[] = $this->createPage( $i, $i == $this->current_page );
      }

      if ( $slidingEnd < $this->num_pages - 1 ) {
        $pages[] = $this->createPageEllipsis();
      }

      $pages[] = $this->createPage( $this->num_pages, $this->current_page == $this->num_pages );
    }

    return $pages;
  }

  /**
   * Creates a page data structure
   *
   * @param int $pageNum
   * @param bool $isCurrent
   *
   * @return array
   */
  protected function createPage( int $pageNum, bool $isCurrent = false ): array {
    return array(
      'num'       => $pageNum,
      'url'       => $this->getPageUrl( $pageNum ),
      'isCurrent' => $isCurrent,
    );
  }

  /**
   * Creates the page ellipsis
   *
   * @return array
   */
  protected function createPageEllipsis(): array {
    return array(
      'num'       => '...',
      'url'       => null,
      'isCurrent' => false,
    );
  }

  /**
   * Renders an HTML pagination control
   *
   * @return string
   */
  public function toHtml(): string {
    if ( $this->num_pages <= 1 ) {
      return '';
    }

    $html = '<ul class="pagination">';

    if ( $this->getPrevUrl() ) {
      $html .= '<li class="page-item"><a href="' . htmlspecialchars( $this->getPrevUrl() ) . '" class="page-link"><i class="fas fa-chevron-left" aria-hidden="true"></i></a></li>';
    }

    foreach ( $this->getPages() as $page ) {
      if ( $page['url'] ) {
        $html .= '<li class="page-item' . ( $page['isCurrent'] ? ' active' : '' ) . '"><a href="' . htmlspecialchars( $page['url'] ) . '" class="page-link">' . htmlspecialchars( $page['num'] ) . '</a></li>';
      } else {
        $html .= '<li class="page-item disabled"><a href="#" class="page-link">' . htmlspecialchars( $page['num'] ) . '</a></li>';
      }
    }

    if ( $this->getNextUrl() ) {
      $html .= '<li class="page-item"><a href="' . htmlspecialchars( $this->getNextUrl() ) . '" class="page-link"><i class="fas fa-chevron-right" aria-hidden="true"></i></a></li>';
    }

    $html .= '</ul>';

    return $html;
  }

  /**
   * To string
   *
   * @return string
   */
  public function __toString(): string {
    return $this->toHtml();
  }

  /**
   * Gets the current page first item
   *
   * @return mixed
   */
  public function getCurrentPageFirstItem() {
    $first = ( $this->current_page - 1 ) * $this->items_per_page + 1;

    if ( $first > $this->total_items ) {
      return null;
    }

    return $first;
  }

  /**
   * Gets the current page last item
   *
   * @return mixed
   */
  public function getCurrentPageLastItem() {
    $first = $this->getCurrentPageFirstItem();

    if ( $first === null ) {
      return null;
    }

    $last = $first + $this->items_per_page - 1;

    if ( $last > $this->total_items ) {
      return $this->total_items;
    }

    return $last;
  }
}
