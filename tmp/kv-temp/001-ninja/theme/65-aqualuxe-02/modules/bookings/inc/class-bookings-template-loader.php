<?php
/**
 * Bookings Template Loader
 *
 * Handles loading template files for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Template Loader Class
 */
class AquaLuxe_Bookings_Template_Loader {
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks
        add_filter('template_include', array($this, 'template_loader'));
        add_filter('body_class', array($this, 'body_class'));
        add_filter('post_class', array($this, 'post_class'), 20, 3);
    }

    /**
     * Load a template
     *
     * @param string $template Template file to load
     * @return string Template file path
     */
    public function template_loader($template) {
        if (is_embed()) {
            return $template;
        }

        $default_file = '';

        if (is_singular('bookable_service')) {
            $default_file = 'single-bookable-service.php';
        } elseif (is_tax('service_category')) {
            $default_file = 'taxonomy-service-category.php';
        } elseif (is_tax('service_tag')) {
            $default_file = 'taxonomy-service-tag.php';
        } elseif (is_post_type_archive('bookable_service')) {
            $default_file = 'archive-bookable-service.php';
        }

        if ($default_file) {
            $template = $this->locate_template($default_file, $template);
        }

        return $template;
    }

    /**
     * Locate a template and return the path for inclusion
     *
     * @param string $template_name Template name
     * @param string $default_path Default path
     * @return string Template path
     */
    public function locate_template($template_name, $default_path = '') {
        // Look within passed path within the theme - this is priority
        $template = locate_template(
            array(
                'aqualuxe/bookings/' . $template_name,
                $template_name,
            )
        );

        // Get default template
        if (!$template && $default_path) {
            $template = $default_path;
        }

        // Use default template if we still haven't found one
        if (!$template) {
            $template = AQUALUXE_BOOKINGS_TEMPLATE_PATH . $template_name;
        }

        // Return what we found
        return apply_filters('aqualuxe_bookings_locate_template', $template, $template_name, $default_path);
    }

    /**
     * Add body classes
     *
     * @param array $classes Body classes
     * @return array Modified body classes
     */
    public function body_class($classes) {
        if (is_singular('bookable_service')) {
            $classes[] = 'aqualuxe-bookings';
            $classes[] = 'aqualuxe-bookable-service';
        } elseif (is_tax('service_category') || is_tax('service_tag')) {
            $classes[] = 'aqualuxe-bookings';
            $classes[] = 'aqualuxe-bookable-service-taxonomy';
        } elseif (is_post_type_archive('bookable_service')) {
            $classes[] = 'aqualuxe-bookings';
            $classes[] = 'aqualuxe-bookable-service-archive';
        }

        return $classes;
    }

    /**
     * Add post classes
     *
     * @param array $classes Post classes
     * @param string $class Additional class
     * @param int $post_id Post ID
     * @return array Modified post classes
     */
    public function post_class($classes, $class, $post_id) {
        if ('bookable_service' === get_post_type($post_id)) {
            $classes[] = 'aqualuxe-bookable-service';
            
            // Add class for service price
            $price = get_post_meta($post_id, '_service_price', true);
            
            if (!empty($price)) {
                $classes[] = 'has-price';
            } else {
                $classes[] = 'no-price';
            }
            
            // Add class for service duration
            $duration = get_post_meta($post_id, '_service_duration', true);
            
            if (!empty($duration)) {
                $classes[] = 'has-duration';
                
                if ($duration <= 30) {
                    $classes[] = 'short-duration';
                } elseif ($duration <= 60) {
                    $classes[] = 'medium-duration';
                } else {
                    $classes[] = 'long-duration';
                }
            } else {
                $classes[] = 'no-duration';
            }
            
            // Add class for service capacity
            $capacity = get_post_meta($post_id, '_service_capacity', true);
            
            if (!empty($capacity)) {
                $classes[] = 'has-capacity';
                
                if ($capacity == 1) {
                    $classes[] = 'single-capacity';
                } else {
                    $classes[] = 'multiple-capacity';
                }
            } else {
                $classes[] = 'no-capacity';
            }
        }

        return $classes;
    }
}

/**
 * Get a template
 *
 * @param string $template_name Template name
 * @param array $args Arguments
 * @param string $template_path Template path
 * @param string $default_path Default path
 */
function aqualuxe_bookings_get_template($template_name, $args = array(), $template_path = '', $default_path = '') {
    if (!empty($args) && is_array($args)) {
        extract($args);
    }

    $located = aqualuxe_bookings_locate_template($template_name, $template_path, $default_path);

    if (!file_exists($located)) {
        /* translators: %s template */
        _doing_it_wrong(__FUNCTION__, sprintf(__('%s does not exist.', 'aqualuxe'), '<code>' . $located . '</code>'), '1.0.0');
        return;
    }

    // Allow 3rd party plugin filter template file from their plugin.
    $located = apply_filters('aqualuxe_bookings_get_template', $located, $template_name, $args, $template_path, $default_path);

    do_action('aqualuxe_bookings_before_template_part', $template_name, $template_path, $located, $args);

    include $located;

    do_action('aqualuxe_bookings_after_template_part', $template_name, $template_path, $located, $args);
}

/**
 * Locate a template and return the path for inclusion
 *
 * @param string $template_name Template name
 * @param string $template_path Template path
 * @param string $default_path Default path
 * @return string Template path
 */
function aqualuxe_bookings_locate_template($template_name, $template_path = '', $default_path = '') {
    if (!$template_path) {
        $template_path = 'aqualuxe/bookings/';
    }

    if (!$default_path) {
        $default_path = AQUALUXE_BOOKINGS_TEMPLATE_PATH;
    }

    // Look within passed path within the theme - this is priority.
    $template = locate_template(
        array(
            trailingslashit($template_path) . $template_name,
            $template_name,
        )
    );

    // Get default template.
    if (!$template) {
        $template = trailingslashit($default_path) . $template_name;
    }

    // Return what we found.
    return apply_filters('aqualuxe_bookings_locate_template', $template, $template_name, $template_path);
}