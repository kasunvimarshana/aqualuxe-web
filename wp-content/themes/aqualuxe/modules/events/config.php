<?php
/**
 * Events Calendar Module Configuration
 *
 * @package AquaLuxe
 * @subpackage Modules\Events
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

return [
    'name'        => 'events',
    'title'       => __( 'Events Calendar', 'aqualuxe' ),
    'description' => __( 'Adds event calendar functionality for aquatic businesses.', 'aqualuxe' ),
    'version'     => '1.0.0',
    'author'      => 'NinjaTech AI',
    'enabled'     => true,
    'requires'    => [],
    'settings'    => [
        'page' => [
            'title'    => __( 'Events Settings', 'aqualuxe' ),
            'menu'     => __( 'Settings', 'aqualuxe' ),
            'parent'   => 'edit.php?post_type=aqualuxe_event',
            'callback' => 'render_settings_page',
        ],
    ],
];