<?php
/**
 * Services Shortcodes
 *
 * Shortcodes for the services module.
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Note: The actual shortcode functions are defined in template-functions.php
// This file is included for organizational purposes and potential future expansion.

/**
 * Register shortcodes
 */
function aqualuxe_register_services_shortcodes() {
    // Register services shortcode
    add_shortcode('services', 'aqualuxe_services_shortcode');
    
    // Register service shortcode
    add_shortcode('service', 'aqualuxe_service_shortcode');
    
    // Register service categories shortcode
    add_shortcode('service_categories', 'aqualuxe_service_categories_shortcode');
    
    // Register service booking form shortcode
    add_shortcode('service_booking', 'aqualuxe_service_booking_shortcode');
}
add_action('init', 'aqualuxe_register_services_shortcodes');

/**
 * Add shortcodes to Tiny MCE
 *
 * @param array $buttons
 * @return array
 */
function aqualuxe_register_services_shortcode_buttons($buttons) {
    array_push($buttons, 'services_shortcodes');
    return $buttons;
}
add_filter('mce_buttons', 'aqualuxe_register_services_shortcode_buttons');

/**
 * Register shortcode button script
 *
 * @param array $plugin_array
 * @return array
 */
function aqualuxe_add_services_shortcode_buttons($plugin_array) {
    global $aqualuxe_theme;
    $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

    if ($module) {
        $plugin_array['services_shortcodes'] = $module->get_url() . '/assets/js/shortcodes.js';
    }

    return $plugin_array;
}
add_filter('mce_external_plugins', 'aqualuxe_add_services_shortcode_buttons');

/**
 * Add shortcode generator button to TinyMCE
 */
function aqualuxe_services_shortcode_button() {
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }

    if (get_user_option('rich_editing') == 'true') {
        add_filter('mce_buttons', 'aqualuxe_register_services_shortcode_buttons');
        add_filter('mce_external_plugins', 'aqualuxe_add_services_shortcode_buttons');
    }
}
add_action('admin_init', 'aqualuxe_services_shortcode_button');

/**
 * Add shortcode data to editor
 */
function aqualuxe_services_shortcode_data() {
    // Get service categories
    $categories = get_terms([
        'taxonomy' => 'service_category',
        'hide_empty' => false,
    ]);

    $category_options = [];
    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            $category_options[] = [
                'text' => $category->name,
                'value' => $category->slug,
            ];
        }
    }

    // Get service tags
    $tags = get_terms([
        'taxonomy' => 'service_tag',
        'hide_empty' => false,
    ]);

    $tag_options = [];
    if (!empty($tags) && !is_wp_error($tags)) {
        foreach ($tags as $tag) {
            $tag_options[] = [
                'text' => $tag->name,
                'value' => $tag->slug,
            ];
        }
    }

    // Get services
    $services = get_posts([
        'post_type' => 'service',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ]);

    $service_options = [];
    if (!empty($services)) {
        foreach ($services as $service) {
            $service_options[] = [
                'text' => $service->post_title,
                'value' => $service->ID,
            ];
        }
    }

    // Prepare shortcode data
    $shortcode_data = [
        'services' => [
            'title' => __('Services', 'aqualuxe'),
            'description' => __('Display a list of services', 'aqualuxe'),
            'fields' => [
                'layout' => [
                    'type' => 'select',
                    'label' => __('Layout', 'aqualuxe'),
                    'options' => [
                        ['text' => __('Grid', 'aqualuxe'), 'value' => 'grid'],
                        ['text' => __('List', 'aqualuxe'), 'value' => 'list'],
                        ['text' => __('Masonry', 'aqualuxe'), 'value' => 'masonry'],
                    ],
                    'default' => 'grid',
                ],
                'columns' => [
                    'type' => 'select',
                    'label' => __('Columns', 'aqualuxe'),
                    'options' => [
                        ['text' => '2', 'value' => '2'],
                        ['text' => '3', 'value' => '3'],
                        ['text' => '4', 'value' => '4'],
                    ],
                    'default' => '3',
                ],
                'limit' => [
                    'type' => 'text',
                    'label' => __('Limit', 'aqualuxe'),
                    'default' => '-1',
                ],
                'category' => [
                    'type' => 'select',
                    'label' => __('Category', 'aqualuxe'),
                    'options' => $category_options,
                    'multiple' => true,
                ],
                'tag' => [
                    'type' => 'select',
                    'label' => __('Tag', 'aqualuxe'),
                    'options' => $tag_options,
                    'multiple' => true,
                ],
                'orderby' => [
                    'type' => 'select',
                    'label' => __('Order By', 'aqualuxe'),
                    'options' => [
                        ['text' => __('Date', 'aqualuxe'), 'value' => 'date'],
                        ['text' => __('Title', 'aqualuxe'), 'value' => 'title'],
                        ['text' => __('Menu Order', 'aqualuxe'), 'value' => 'menu_order'],
                        ['text' => __('Random', 'aqualuxe'), 'value' => 'rand'],
                    ],
                    'default' => 'date',
                ],
                'order' => [
                    'type' => 'select',
                    'label' => __('Order', 'aqualuxe'),
                    'options' => [
                        ['text' => __('Descending', 'aqualuxe'), 'value' => 'DESC'],
                        ['text' => __('Ascending', 'aqualuxe'), 'value' => 'ASC'],
                    ],
                    'default' => 'DESC',
                ],
                'show_image' => [
                    'type' => 'checkbox',
                    'label' => __('Show Image', 'aqualuxe'),
                    'default' => true,
                ],
                'show_excerpt' => [
                    'type' => 'checkbox',
                    'label' => __('Show Excerpt', 'aqualuxe'),
                    'default' => true,
                ],
                'show_button' => [
                    'type' => 'checkbox',
                    'label' => __('Show Button', 'aqualuxe'),
                    'default' => true,
                ],
                'button_text' => [
                    'type' => 'text',
                    'label' => __('Button Text', 'aqualuxe'),
                    'default' => __('View Details', 'aqualuxe'),
                ],
            ],
        ],
        'service' => [
            'title' => __('Service', 'aqualuxe'),
            'description' => __('Display a single service', 'aqualuxe'),
            'fields' => [
                'id' => [
                    'type' => 'select',
                    'label' => __('Service', 'aqualuxe'),
                    'options' => $service_options,
                    'required' => true,
                ],
                'show_image' => [
                    'type' => 'checkbox',
                    'label' => __('Show Image', 'aqualuxe'),
                    'default' => true,
                ],
                'show_excerpt' => [
                    'type' => 'checkbox',
                    'label' => __('Show Excerpt', 'aqualuxe'),
                    'default' => true,
                ],
                'show_button' => [
                    'type' => 'checkbox',
                    'label' => __('Show Button', 'aqualuxe'),
                    'default' => true,
                ],
                'button_text' => [
                    'type' => 'text',
                    'label' => __('Button Text', 'aqualuxe'),
                    'default' => __('View Details', 'aqualuxe'),
                ],
            ],
        ],
        'service_categories' => [
            'title' => __('Service Categories', 'aqualuxe'),
            'description' => __('Display service categories', 'aqualuxe'),
            'fields' => [
                'layout' => [
                    'type' => 'select',
                    'label' => __('Layout', 'aqualuxe'),
                    'options' => [
                        ['text' => __('Grid', 'aqualuxe'), 'value' => 'grid'],
                        ['text' => __('List', 'aqualuxe'), 'value' => 'list'],
                    ],
                    'default' => 'grid',
                ],
                'columns' => [
                    'type' => 'select',
                    'label' => __('Columns', 'aqualuxe'),
                    'options' => [
                        ['text' => '2', 'value' => '2'],
                        ['text' => '3', 'value' => '3'],
                        ['text' => '4', 'value' => '4'],
                    ],
                    'default' => '3',
                ],
                'show_count' => [
                    'type' => 'checkbox',
                    'label' => __('Show Count', 'aqualuxe'),
                    'default' => true,
                ],
                'show_image' => [
                    'type' => 'checkbox',
                    'label' => __('Show Image', 'aqualuxe'),
                    'default' => true,
                ],
                'show_description' => [
                    'type' => 'checkbox',
                    'label' => __('Show Description', 'aqualuxe'),
                    'default' => true,
                ],
                'parent' => [
                    'type' => 'select',
                    'label' => __('Parent Category', 'aqualuxe'),
                    'options' => array_merge(
                        [['text' => __('All Categories', 'aqualuxe'), 'value' => '0']],
                        $category_options
                    ),
                    'default' => '0',
                ],
                'orderby' => [
                    'type' => 'select',
                    'label' => __('Order By', 'aqualuxe'),
                    'options' => [
                        ['text' => __('Name', 'aqualuxe'), 'value' => 'name'],
                        ['text' => __('ID', 'aqualuxe'), 'value' => 'id'],
                        ['text' => __('Count', 'aqualuxe'), 'value' => 'count'],
                        ['text' => __('Slug', 'aqualuxe'), 'value' => 'slug'],
                    ],
                    'default' => 'name',
                ],
                'order' => [
                    'type' => 'select',
                    'label' => __('Order', 'aqualuxe'),
                    'options' => [
                        ['text' => __('Ascending', 'aqualuxe'), 'value' => 'ASC'],
                        ['text' => __('Descending', 'aqualuxe'), 'value' => 'DESC'],
                    ],
                    'default' => 'ASC',
                ],
            ],
        ],
        'service_booking' => [
            'title' => __('Service Booking', 'aqualuxe'),
            'description' => __('Display a service booking form', 'aqualuxe'),
            'fields' => [
                'id' => [
                    'type' => 'select',
                    'label' => __('Service', 'aqualuxe'),
                    'options' => $service_options,
                    'required' => true,
                ],
                'title' => [
                    'type' => 'text',
                    'label' => __('Title', 'aqualuxe'),
                    'default' => __('Book This Service', 'aqualuxe'),
                ],
                'button_text' => [
                    'type' => 'text',
                    'label' => __('Button Text', 'aqualuxe'),
                    'default' => __('Book Now', 'aqualuxe'),
                ],
            ],
        ],
    ];

    // Output shortcode data
    wp_localize_script('jquery', 'aqualuxeServicesShortcodes', $shortcode_data);
}
add_action('admin_enqueue_scripts', 'aqualuxe_services_shortcode_data');