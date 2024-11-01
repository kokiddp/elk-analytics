<?php

namespace ELKLab\ELKAnalytics;

use ELKLab\ELKAnalytics\Helpers\PluginsHelper;
use ELKLab\ELKAnalytics\Helpers\PostTypesHelper;

use ELKLab\ELKAnalytics\Admin\Admin;
use ELKLab\ELKAnalytics\Admin\Options;

/**
 * ELKAnalytics main class
 * 
 * @since 1.0.0
 */
class ELKAnalytics {
  private $enable_posts;
  private $enable_contact_form;
  private $enable_reservations;

  public function __construct() {
    $this->enable_posts = apply_filters(
      'elk_analytics_enable_posts',
      true
    );

    $this->enable_contact_form = apply_filters(
      'elk_analytics_enable_contact_form',
      PluginsHelper::isPluginActive('contact-form-7/wp-contact-form-7.php')
    );

    $this->enable_reservations = apply_filters(
      'elk_analytics_enable_reservations',
      PostTypesHelper::postTypeExists('apartment')
    );

    $this->initAdmin();
    $this->initOptions();

    $this->initCrons();

    $this->postsActions();
    $this->contactFormActions();
    $this->reservationActions();
  }

  private function postsActions() {
    if ($this->enable_posts) {
      add_action('template_redirect', ['ELKLab\ELKAnalytics\Services\EventsManager', 'registerPostEvent']);
    }
  }

  private function contactFormActions() {
    if ($this->enable_contact_form) {
      add_action('wpcf7_mail_sent', ['ELKLab\ELKAnalytics\Services\EventsManager', 'registerFormEvent']);
    }
  }

  private function reservationActions() {
    if ($this->enable_reservations) {
      add_action('wp_ajax_register_form_submit', ['ELKLab\ELKAnalytics\Services\EventsManager', 'handleReservationSubmit']);
      add_action('wp_ajax_nopriv_register_form_submit', ['ELKLab\ELKAnalytics\Services\EventsManager', 'handleReservationSubmit']);
    }
  }

  private function initAdmin() {
    new Admin();
  }

  private function initOptions() {
    new Options();
  }

  private function initCrons() {
    add_action('elk_analytics_download_geolite_database', ['ELKLab\ELKAnalytics\Helpers\UserAgentHelper', 'downloadGeoLiteDatabase']);
    if (!wp_next_scheduled('elk_analytics_download_geolite_database')) {
      wp_schedule_event(time(), 'daily', 'elk_analytics_download_geolite_database');
    }
  }
}
