<?php

namespace ELKLab\ELKAnalytics\Admin;

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Options class
 * 
 * @since 1.0.0
 */
class Options {
  /**
   * Constructor
   * 
   * @return Options
   * @since 1.0.0
   */
  public function __construct() {
    add_action('after_setup_theme', function() { Carbon_Fields::boot(); });
    add_action('carbon_fields_register_fields', ['ELKLab\ELKAnalytics\Admin\Options', 'registerFields']);
    add_action('admin_menu', ['ELKLab\ELKAnalytics\Admin\Options', 'maybeHideFields'], 99);
  }

  /**
   * Register fields
   * 
   * @return void
   * @since 1.0.0
   */
  public static function registerFields() {
    Container::make('theme_options', __('Options', 'elk-analytics'))
      ->set_page_parent('elk-analytics')
      ->set_page_file('elk-analytics-options')
      ->add_fields([
        Field::make('text', 'elk_analytics_maxmind_api_key', __('MaxMind API Key', 'elk-analytics'))
          ->set_help_text(__('Enter your MaxMind API key. You can obtain it <a href="https://www.maxmind.com/" target="_blank" noreferrer>on maxmind.com</a>', 'elk-analytics'))
      ]);
  }

  /**
   * Maybe hide fields
   * 
   * @return void
   * @since 1.0.0
   */
  public static function maybeHideFields() {
    if (!current_user_can('write_elk_analytics')) {
      remove_submenu_page('elk-analytics', 'elk-analytics-options');
    }
  }
}