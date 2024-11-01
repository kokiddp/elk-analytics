<?php

namespace ELKLab\ELKAnalytics\Services;

use ELKLab\ELKAnalytics\Model\User;
use ELKLab\ELKAnalytics\Model\Event;
use ELKLab\ELKAnalytics\Model\EventType;
use ELKLab\ELKAnalytics\Helpers\UserAgentHelper;
use ELKLab\ELKAnalytics\Helpers\PostTypesHelper;
use ELKLab\ELKAnalytics\Helpers\PluginsHelper;

/**
 * Events manager
 * 
 * @since 1.0.0
 */
class EventsManager {
  /**
   * Register or get user
   * 
   * @return int
   */
  private static function registerOrGetUser() {
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $session = $_COOKIE['PHPSESSID'] ?? null;
    $now = date('Y-m-d H:i:s');
  
    $existing_user = User::where('ip', $ip)->where('user_agent', $user_agent)->first();  
    if ($existing_user) {
      return $existing_user->id;
    } else {      
      $new_user = User::create([
        'ip' => $ip,
        'user_agent' => $user_agent,
        'browser_type' => UserAgentHelper::getBrowserType(),
        'browser_version' => UserAgentHelper::getBrowserVersion(),
        'os_type' => UserAgentHelper::getOSType(),
        'os_version' => UserAgentHelper::getOSVersion(),
        'device' => UserAgentHelper::getDevice(),
        'country' => UserAgentHelper::getCountry(),
        'session_id' => $session,
        'created_at' => $now,
        'updated_at' => $now,
      ]);  
      return $new_user->id;
    }
  }

  /**
   * Register post event
   * 
   * @return int|null 
   */
  public static function registerPostEvent() {
    if (is_admin()) {
      return null;
    }
    if (defined('DOING_AJAX') && DOING_AJAX) {
      return null;
    }
    if (defined('REST_REQUEST') && REST_REQUEST) {
      return null;
    }

    $user_id = static::registerOrGetUser();
    $event_type = EventType::where('name', 'page_view')->first();
    $referrer = $_SERVER['HTTP_REFERER'] ?? null;
    $url = $_SERVER['REQUEST_URI'];
    $now = date('Y-m-d H:i:s');

    if (is_front_page()) {
      $post_id = get_the_ID();
      $event = Event::create([
        'user_id' => $user_id,
        'event_type_id' => $event_type->id,
        'url' => '/',
        'referrer' => $referrer,
        'post_id' => $post_id,
        'created_at' => $now,
        'updated_at' => $now,
      ]);
      return $event->id;
    }
    if (is_home()) {
      $post_id = get_option('page_for_posts');
      $url = $_SERVER['REQUEST_URI'];
      $event = Event::create([
        'user_id' => $user_id,
        'event_type_id' => $event_type->id,
        'url' => $url,
        'referrer' => $referrer,
        'post_id' => $post_id,
        'created_at' => $now,
        'updated_at' => $now,
      ]);
      return $event->id;
    }
    if (is_archive() || is_tax() || is_category() || is_tag()) {
      $url = $_SERVER['REQUEST_URI'];
      $event = Event::create([
        'user_id' => $user_id,
        'event_type_id' => $event_type->id,
        'url' => $url,
        'referrer' => $referrer,
        'created_at' => $now,
        'updated_at' => $now,
      ]);
      return $event->id;
    }
  
    $post_types = apply_filters('elk_analytics_post_types', PostTypesHelper::getPublicPostTypes());  
    if (!is_singular($post_types)) {
      return null;
    }
    $post_id = get_the_ID();
    
    $event = Event::create([
      'user_id' => $user_id,
      'event_type_id' => $event_type->id,
      'url' => $url,
      'referrer' => $referrer,
      'post_id' => $post_id,
      'created_at' => $now,
      'updated_at' => $now,
    ]);
    return $event->id;
  }
  
  /**
   * Register form event
   * 
   * @param string $contact_form
   * @return int
   */
  public static function registerFormEvent($contact_form) {
    $user_id = static::registerOrGetUser();
    $event_type = EventType::where('name', 'contact_form')->first();
    $referrer = $_SERVER['HTTP_REFERER'] ?? null;
    $url = $_SERVER['REQUEST_URI'];
    $post_id = get_the_ID();
    $now = date('Y-m-d H:i:s');
    
    $submission = \WPCF7_Submission::get_instance();
    $form_data = apply_filters('elk_analytics_form_data', $submission->get_posted_data(), $contact_form);
    $form_data = json_encode($form_data);
  
    $event = Event::create([
      'user_id' => $user_id,
      'event_type_id' => $event_type->id,
      'event_details' => $form_data,
      'url' => $url,
      'referrer' => $referrer,
      'post_id' => $post_id,
      'created_at' => $now,
      'updated_at' => $now,
    ]);
    return $event->id;
  }
  
  /**
   * Handle reservation submit
   * 
   * @return void
   */
  public static function handleReservationSubmit() {
    static::registerReservationEvent();
    wp_send_json_success();
  }
  
  /**
   * Register reservation event
   * 
   * @return int|null
   */
  public static function registerReservationEvent() {
    $user_id = static::registerOrGetUser();  
    $event_type = EventType::where('name', 'reservation_form')->first();
    $now = date('Y-m-d H:i:s');

    $event_details = apply_filters('elk_analytics_reservation_details', $_POST['event_details']);
    $event_details = json_encode($event_details);
  
    $event = Event::create([
      'user_id' => $user_id,
      'event_type_id' => $event_type->id,
      'event_details' => $event_details,
      'created_at' => $now,
      'updated_at' => $now,
    ]);
    return $event->id;
  }    
}
