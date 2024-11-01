<?php

namespace ELKLab\ELKAnalytics\Database;

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Seeder class
 * 
 * @since 1.0.0
 */
class Seeder {
  public static function seedEventTypes() {
    require_once plugin_dir_path(ELK_ANALYTICS_PLUGIN_FILE) . '/src/Database/CapsuleManager.php';
    $eventTypes = apply_filters('elk_analytics_event_types', ['contact_form', 'reservation_form', 'page_view']);
    foreach ($eventTypes as $type) {
      $exists = Capsule::table('elk_analytics_event_types')->where('name', $type)->exists();
      if (!$exists) {
        Capsule::table('elk_analytics_event_types')->insert(['name' => $type]);
      }
    }
  }
}