<?php

namespace ELKLab\ELKAnalytics\Helpers;

/**
 * Post types helper
 * 
 * @since 1.0.0
 */
class PostTypesHelper {
  /**
   * Get all post types
   * 
   * @return array
   * @since 1.0.0
   */
  public static function getPostTypes() {
    return get_post_types();
  }

  /**
   * Get all public post types
   * 
   * @return array
   * @since 1.0.0
   */
  public static function getPublicPostTypes() {
    return get_post_types(['public' => true]);
  }

  /**
   * Get all private post types
   * 
   * @return array
   * @since 1.0.0
   */
  public static function getPrivatePostTypes() {
    return get_post_types(['public' => false]);
  }

  /**
   * Get a post type by name
   * 
   * @param string $postType
   * @return object
   * @since 1.0.0
   */
  public static function getPostType($postType) {
    return get_post_type_object($postType);
  }

  /**
   * Check if a post type exists
   * 
   * @param string $postType
   * @return bool
   * @since 1.0.0
   */
  public static function postTypeExists($postType) {
    return post_type_exists($postType);
  }

  /**
   * Check if a post type is public
   * 
   * @param string $postType
   * @return bool
   * @since 1.0.0
   */
  public static function isPostTypePublic($postType) {
    $postType = self::getPostType($postType);
    return $postType->public;
  }
}