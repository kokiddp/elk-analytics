<?php

namespace ELKLab\ELKAnalytics\Helpers;

use Carbon\Carbon;

use ELKLab\ELKAnalytics\Model\EventType;
use ELKLab\ELKAnalytics\Model\Event;

/**
 * Filter Helper
 * 
 * @since 1.0.0
 */
class FilterHelper {
  /**
   * Page
   * 
   * @var string
   * @since 1.0.0
   */
  public $page;

  /**
   * Minimum date
   * 
   * @var Carbon
   * @since 1.0.0
   */
  public $min_date;

  /**
   * Maximum date
   * 
   * @var Carbon
   * @since 1.0.0
   */
  public $max_date;

  /**
   * Filter minimum date
   * 
   * @var Carbon
   * @since 1.0.0
   */
  public $filter_min_date;

  /**
   * Filter maximum date
   * 
   * @var Carbon
   * @since 1.0.0
   */
  public $filter_max_date;

  /**
   * Event type ID
   * 
   * @var int
   * @since 1.0.0
   */
  public $event_type_id;

  /**
   * Constructor
   * 
   * @param string $page 
   * @param string|Carbon|null $min_date 
   * @param string|Carbon|null $max_date 
   * @return FilterHelper
   * @since 1.0.0
   */
  public function __construct($page, $min_date = null, $max_date = null) {
    $this->page = $page;

    $this->event_type_id = EventType::where('name', 'page_view')->value('id');

    $first_event_date = self::getFirstEventDate();
    $this->filter_min_date = $first_event_date ? $first_event_date->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
    $this->filter_max_date = Carbon::now()->endOfDay();

    $this->min_date = self::parseDate($min_date, $this->filter_min_date)->startOfDay();
    $this->max_date = self::parseDate($max_date, $this->filter_max_date)->endOfDay();

    $this->min_date = $this->min_date->lt($this->filter_min_date) ? $this->filter_min_date : $this->min_date;
    $this->max_date = $this->max_date->gt($this->filter_max_date) ? $this->filter_max_date : $this->max_date;
  }

  /**
   * Get the first event date
   *
   * @return Carbon|null
   * @since 1.0.0
   */
  private function getFirstEventDate() {
    $first_event = Event::where('event_type_id', $this->event_type_id)->orderBy('created_at', 'asc')->first();
    return $first_event ? $first_event->created_at : null;
  }
  
  /**
   * Parse date input or return default if invalid
   *
   * @param string|Carbon $date
   * @param Carbon $default
   * @return Carbon
   * @since 1.0.0
   */
  private static function parseDate($date, $default) {
    if ($date instanceof Carbon) {
      return $date;
    } elseif (is_string($date)) {
      $output = Carbon::createFromFormat('Y-m-d', $date);
      return $output instanceof Carbon ? $output : $default;
    }
    return $default;
  }
}
