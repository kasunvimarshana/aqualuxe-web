<?php
/**
 * Bookings Module Configuration
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

return [
    'name'        => 'bookings',
    'title'       => __( 'Bookings', 'aqualuxe' ),
    'description' => __( 'Adds booking functionality for aquatic services and products.', 'aqualuxe' ),
    'version'     => '1.0.0',
    'author'      => 'NinjaTech AI',
    'enabled'     => true,
    'requires'    => [],
    'settings'    => [
        'page' => [
            'title'    => __( 'Booking Settings', 'aqualuxe' ),
            'menu'     => __( 'Settings', 'aqualuxe' ),
            'parent'   => 'edit.php?post_type=aqualuxe_booking',
            'callback' => 'render_settings_page',
        ],
    ],
];