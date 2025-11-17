<?php
/**
 * WordPress Importer
 *
 * @package AquaLuxe
 * @subpackage Importer
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WordPress Importer class for managing the import process of a WXR file
 *
 * @package WordPress
 * @subpackage Importer
 */
if (!class_exists('WP_Import')) {
    class WP_Import {
        /**
         * Constructor
         */
        public function __construct() {
            // Nothing to do here
        }

        /**
         * Import WXR file
         *
         * @param string $file Path to the WXR file to import.
         */
        public function import($file) {
            // Check if WordPress Importer plugin is active
            if (!class_exists('WP_Importer')) {
                $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                if (file_exists($class_wp_importer)) {
                    require $class_wp_importer;
                }
            }

            // Check if WordPress Importer plugin is active
            if (!class_exists('WP_Import')) {
                $wp_import = ABSPATH . 'wp-content/plugins/wordpress-importer/wordpress-importer.php';
                if (file_exists($wp_import)) {
                    require $wp_import;
                } else {
                    // If plugin is not available, use the bundled version
                    require_once dirname(__FILE__) . '/class-wp-import.php';
                }
            }

            // Create a new instance of WP_Import
            $wp_import = new WP_Import();
            $wp_import->fetch_attachments = true;

            // Import the WXR file
            $wp_import->import($file);
        }

        /**
         * Get all the categories for a post
         *
         * @param int $post_id Post ID.
         * @return array
         */
        public function get_categories_for_post($post_id) {
            $categories = array();
            $terms = wp_get_post_terms($post_id, 'category');
            foreach ($terms as $term) {
                $categories[] = $term->slug;
            }
            return $categories;
        }

        /**
         * Get all the tags for a post
         *
         * @param int $post_id Post ID.
         * @return array
         */
        public function get_tags_for_post($post_id) {
            $tags = array();
            $terms = wp_get_post_terms($post_id, 'post_tag');
            foreach ($terms as $term) {
                $tags[] = $term->slug;
            }
            return $tags;
        }
    }
}