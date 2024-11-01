<?php

namespace ELKLab\ELKAnalytics\Helpers;

/**
 * Plugins helper
 * 
 * @since 1.0.0
 */
class PluginsHelper {
  /**
   * Get all plugins details
   * 
   * @return array
   * @since 1.0.0
   */
  public static function getPluginsDetails() {
    if (!function_exists( 'get_plugins' )) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    return get_plugins();
  }

  /**
   * Get all plugins
   * 
   * @return array
   * @since 1.0.0
   */
  public static function getPlugins() {
    return array_keys(self::getPluginsDetails());
  }

  /**
   * Get all active plugins
   *
   * @return array
   * @since 1.0.0
   */
  public static function getActivePlugins() {
    return get_option('active_plugins');
  }

  /**
   * Get all inactive plugins
   *
   * @return array
   * @since 1.0.0
   */
  public static function getInactivePlugins() {
    $plugins = self::getPluginsDetails();
    $activePlugins = self::getActivePlugins();
    return array_keys(array_diff_key($plugins, array_flip($activePlugins)));
  }

  /**
   * Get a plugin by name
   *
   * @param string $pluginName
   * @return array
   * @since 1.0.0
   */
  public static function getPluginData($pluginName) {
    if (!function_exists('get_plugin_data')) {
      include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    return get_plugin_data(WP_PLUGIN_DIR . '/' . $pluginName);
  }

  /**
   * Check if a plugin is active
   *
   * @param string $pluginName
   * @return bool
   * @since 1.0.0
   */
  public static function isPluginActive($pluginName) {
    return in_array($pluginName, static::getActivePlugins());
  }
}