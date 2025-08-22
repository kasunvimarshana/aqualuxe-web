<?php
/**
 * Services Blocks
 *
 * Gutenberg blocks for the services module.
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Note: The actual block render functions are defined in template-functions.php
// This file is included for organizational purposes and block registration.

/**
 * Register services blocks
 */
function aqualuxe_register_services_blocks() {
    // Check if Gutenberg is active
    if (!function_exists('register_block_type')) {
        return;
    }

    // Get module instance
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    if (!$module) {
        return;
    }

    // Register block scripts
    wp_register_script(
        'aqualuxe-services-block',
        $module->get_url() . '/assets/js/blocks.js',
        ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-data'],
        $module->get_version(),
        true
    );

    // Register block styles
    wp_register_style(
        'aqualuxe-services-block-editor',
        $module->get_url() . '/assets/css/blocks-editor.css',
        ['wp-edit-blocks'],
        $module->get_version()
    );

    // Register services block
    register_block_type('aqualuxe/services', [
        'editor_script' => 'aqualuxe-services-block',
        'editor_style' => 'aqualuxe-services-block-editor',
        'render_callback' => 'aqualuxe_services_block_render',
        'attributes' => [
            'layout' => [
                'type' => 'string',
                'default' => 'grid',
            ],
            'columns' => [
                'type' => 'number',
                'default' => 3,
            ],
            'categories' => [
                'type' => 'array',
                'default' => [],
            ],
            'tags' => [
                'type' => 'array',
                'default' => [],
            ],
            'limit' => [
                'type' => 'number',
                'default' => 6,
            ],
            'orderby' => [
                'type' => 'string',
                'default' => 'date',
            ],
            'order' => [
                'type' => 'string',
                'default' => 'DESC',
            ],
            'showImage' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'showExcerpt' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'showButton' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'buttonText' => [
                'type' => 'string',
                'default' => __('View Details', 'aqualuxe'),
            ],
            'className' => [
                'type' => 'string',
            ],
        ],
    ]);

    // Register service block
    register_block_type('aqualuxe/service', [
        'editor_script' => 'aqualuxe-services-block',
        'editor_style' => 'aqualuxe-services-block-editor',
        'render_callback' => 'aqualuxe_service_block_render',
        'attributes' => [
            'id' => [
                'type' => 'number',
            ],
            'showImage' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'showExcerpt' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'showButton' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'buttonText' => [
                'type' => 'string',
                'default' => __('View Details', 'aqualuxe'),
            ],
            'className' => [
                'type' => 'string',
            ],
        ],
    ]);

    // Register service categories block
    register_block_type('aqualuxe/service-categories', [
        'editor_script' => 'aqualuxe-services-block',
        'editor_style' => 'aqualuxe-services-block-editor',
        'render_callback' => 'aqualuxe_service_categories_block_render',
        'attributes' => [
            'layout' => [
                'type' => 'string',
                'default' => 'grid',
            ],
            'columns' => [
                'type' => 'number',
                'default' => 3,
            ],
            'showCount' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'showImage' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'showDescription' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'className' => [
                'type' => 'string',
            ],
        ],
    ]);

    // Register service booking form block
    register_block_type('aqualuxe/service-booking', [
        'editor_script' => 'aqualuxe-services-block',
        'editor_style' => 'aqualuxe-services-block-editor',
        'render_callback' => 'aqualuxe_service_booking_block_render',
        'attributes' => [
            'serviceId' => [
                'type' => 'number',
            ],
            'title' => [
                'type' => 'string',
                'default' => __('Book This Service', 'aqualuxe'),
            ],
            'buttonText' => [
                'type' => 'string',
                'default' => __('Book Now', 'aqualuxe'),
            ],
            'className' => [
                'type' => 'string',
            ],
        ],
    ]);

    // Add block data
    wp_localize_script('aqualuxe-services-block', 'aqualuxeServicesBlockData', [
        'categories' => aqualuxe_get_service_categories_for_blocks(),
        'tags' => aqualuxe_get_service_tags_for_blocks(),
        'services' => aqualuxe_get_services_for_blocks(),
    ]);
}
add_action('init', 'aqualuxe_register_services_blocks');

/**
 * Add services block category
 *
 * @param array $categories
 * @param WP_Post $post
 * @return array
 */
function aqualuxe_services_block_category($categories, $post) {
    return array_merge(
        $categories,
        [
            [
                'slug' => 'aqualuxe',
                'title' => __('AquaLuxe', 'aqualuxe'),
            ],
        ]
    );
}
add_filter('block_categories_all', 'aqualuxe_services_block_category', 10, 2);

/**
 * Get service categories for blocks
 *
 * @return array
 */
function aqualuxe_get_service_categories_for_blocks() {
    $categories = get_terms([
        'taxonomy' => 'service_category',
        'hide_empty' => false,
    ]);

    $options = [];
    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            $options[] = [
                'label' => $category->name,
                'value' => $category->term_id,
            ];
        }
    }

    return $options;
}

/**
 * Get service tags for blocks
 *
 * @return array
 */
function aqualuxe_get_service_tags_for_blocks() {
    $tags = get_terms([
        'taxonomy' => 'service_tag',
        'hide_empty' => false,
    ]);

    $options = [];
    if (!empty($tags) && !is_wp_error($tags)) {
        foreach ($tags as $tag) {
            $options[] = [
                'label' => $tag->name,
                'value' => $tag->term_id,
            ];
        }
    }

    return $options;
}

/**
 * Get services for blocks
 *
 * @return array
 */
function aqualuxe_get_services_for_blocks() {
    $services = get_posts([
        'post_type' => 'service',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ]);

    $options = [];
    if (!empty($services)) {
        foreach ($services as $service) {
            $options[] = [
                'label' => $service->post_title,
                'value' => $service->ID,
            ];
        }
    }

    return $options;
}