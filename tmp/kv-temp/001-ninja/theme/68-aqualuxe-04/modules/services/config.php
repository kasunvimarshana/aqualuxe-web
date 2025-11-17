<?php
/**
 * Services Module Configuration
 *
 * @package AquaLuxe
 * @subpackage Modules\Services
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

return [
    'name'        => 'services',
    'title'       => __( 'Services', 'aqualuxe' ),
    'description' => __( 'Adds service management functionality for aquatic businesses.', 'aqualuxe' ),
    'version'     => '1.0.0',
    'author'      => 'NinjaTech AI',
    'enabled'     => true,
    'requires'    => [],
    'settings'    => [
        'page' => [
            'title'    => __( 'Service Settings', 'aqualuxe' ),
            'menu'     => __( 'Settings', 'aqualuxe' ),
            'parent'   => 'edit.php?post_type=aqualuxe_service',
            'callback' => 'render_settings_page',
        ],
    ],
];