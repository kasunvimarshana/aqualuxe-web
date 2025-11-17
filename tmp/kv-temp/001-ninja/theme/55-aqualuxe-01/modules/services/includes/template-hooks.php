<?php
/**
 * Services Template Hooks
 *
 * Hooks for the services templates.
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Archive Service Hooks
 */
// Before archive services
add_action('aqualuxe_before_archive_services', 'aqualuxe_services_archive_header', 10);
add_action('aqualuxe_before_archive_services', 'aqualuxe_services_archive_description', 20);
add_action('aqualuxe_before_archive_services', 'aqualuxe_services_archive_filters', 30);

// After archive services
add_action('aqualuxe_after_archive_services', 'aqualuxe_services_archive_pagination', 10);

/**
 * Single Service Hooks
 */
// Before single service
add_action('aqualuxe_before_single_service', 'aqualuxe_service_header', 10);

// Service content
add_action('aqualuxe_service_content', 'aqualuxe_service_featured_image', 10);
add_action('aqualuxe_service_content', 'aqualuxe_service_meta', 20);
add_action('aqualuxe_service_content', 'aqualuxe_service_content', 30);
add_action('aqualuxe_service_content', 'aqualuxe_service_booking_form', 40);

// After single service
add_action('aqualuxe_after_single_service', 'aqualuxe_service_related_services', 10);
add_action('aqualuxe_after_single_service', 'aqualuxe_service_navigation', 20);

/**
 * Service Meta Hooks
 */
add_action('aqualuxe_service_meta', 'aqualuxe_service_price', 10);
add_action('aqualuxe_service_meta', 'aqualuxe_service_duration', 20);
add_action('aqualuxe_service_meta', 'aqualuxe_service_location', 30);
add_action('aqualuxe_service_meta', 'aqualuxe_service_availability', 40);
add_action('aqualuxe_service_meta', 'aqualuxe_service_categories', 50);
add_action('aqualuxe_service_meta', 'aqualuxe_service_tags', 60);

/**
 * Services Archive Header
 */
function aqualuxe_services_archive_header() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    if ($module) {
        $module->get_template_part('archive-header');
    }
}

/**
 * Services Archive Description
 */
function aqualuxe_services_archive_description() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    if ($module) {
        $module->get_template_part('archive-description');
    }
}

/**
 * Services Archive Filters
 */
function aqualuxe_services_archive_filters() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    if ($module) {
        $module->get_template_part('archive-filters');
    }
}

/**
 * Services Archive Pagination
 */
function aqualuxe_services_archive_pagination() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    if ($module) {
        $module->get_template_part('archive-pagination');
    }
}

/**
 * Service Header
 */
function aqualuxe_service_header() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    if ($module) {
        $module->get_template_part('single-header');
    }
}

/**
 * Service Featured Image
 */
function aqualuxe_service_featured_image() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    // Check if featured image is enabled
    if ($module && $module->get_option('featured_image', true) && has_post_thumbnail()) {
        $module->get_template_part('single-featured-image');
    }
}

/**
 * Service Meta
 */
function aqualuxe_service_meta() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    if ($module) {
        $module->get_template_part('single-meta');
    }
}

/**
 * Service Content
 */
function aqualuxe_service_content() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    if ($module) {
        $module->get_template_part('single-content');
    }
}

/**
 * Service Booking Form
 */
function aqualuxe_service_booking_form() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    // Check if booking form is enabled
    if ($module && $module->get_option('booking_form', true)) {
        // Get service booking
        $booking_enabled = get_post_meta(get_the_ID(), '_service_booking_enabled', true);

        if ($booking_enabled === 'yes') {
            $module->get_template_part('single-booking-form');
        }
    }
}

/**
 * Service Related Services
 */
function aqualuxe_service_related_services() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    // Check if related services is enabled
    if ($module && $module->get_option('related_services', true)) {
        $module->get_template_part('single-related-services');
    }
}

/**
 * Service Navigation
 */
function aqualuxe_service_navigation() {
    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    if ($module) {
        $module->get_template_part('single-navigation');
    }
}

/**
 * Service Price
 */
function aqualuxe_service_price() {
    // Get service price
    $price = get_post_meta(get_the_ID(), '_service_price', true);
    $sale_price = get_post_meta(get_the_ID(), '_service_sale_price', true);
    $price_type = get_post_meta(get_the_ID(), '_service_price_type', true);

    // Check if we have a price
    if ($price) {
        // Get module instance
        global $aqualuxe_theme;
        $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

        if ($module) {
            $module->get_template('single-price.php', [
                'price' => $price,
                'sale_price' => $sale_price,
                'price_type' => $price_type,
            ]);
        }
    }
}

/**
 * Service Duration
 */
function aqualuxe_service_duration() {
    // Get service duration
    $duration = get_post_meta(get_the_ID(), '_service_duration', true);

    // Check if we have a duration
    if ($duration) {
        // Get module instance
        global $aqualuxe_theme;
        $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

        if ($module) {
            $module->get_template('single-duration.php', [
                'duration' => $duration,
            ]);
        }
    }
}

/**
 * Service Location
 */
function aqualuxe_service_location() {
    // Get service location
    $location = get_post_meta(get_the_ID(), '_service_location', true);

    // Check if we have a location
    if ($location) {
        // Get module instance
        global $aqualuxe_theme;
        $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

        if ($module) {
            $module->get_template('single-location.php', [
                'location' => $location,
            ]);
        }
    }
}

/**
 * Service Availability
 */
function aqualuxe_service_availability() {
    // Get service availability
    $availability = get_post_meta(get_the_ID(), '_service_availability', true);

    // Check if we have availability
    if ($availability) {
        // Get module instance
        global $aqualuxe_theme;
        $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

        if ($module) {
            $module->get_template('single-availability.php', [
                'availability' => $availability,
            ]);
        }
    }
}

/**
 * Service Categories
 */
function aqualuxe_service_categories() {
    // Get service categories
    $categories = get_the_terms(get_the_ID(), 'service_category');

    // Check if we have categories
    if ($categories && !is_wp_error($categories)) {
        // Get module instance
        global $aqualuxe_theme;
        $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

        if ($module) {
            $module->get_template('single-categories.php', [
                'categories' => $categories,
            ]);
        }
    }
}

/**
 * Service Tags
 */
function aqualuxe_service_tags() {
    // Get service tags
    $tags = get_the_terms(get_the_ID(), 'service_tag');

    // Check if we have tags
    if ($tags && !is_wp_error($tags)) {
        // Get module instance
        global $aqualuxe_theme;
        $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

        if ($module) {
            $module->get_template('single-tags.php', [
                'tags' => $tags,
            ]);
        }
    }
}