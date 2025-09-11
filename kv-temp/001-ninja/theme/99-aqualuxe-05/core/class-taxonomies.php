<?php
/**
 * Custom Taxonomies Class
 *
 * Registers custom taxonomies for the theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Taxonomies Class
 */
class AquaLuxe_Taxonomies {

    /**
     * Constructor
     */
    public function __construct() {
        // Class is auto-initialized when loaded
    }

    /**
     * Register custom taxonomies
     */
    public function register() {
        // This is handled by the post types class
        // Additional taxonomy customizations can be added here
        add_action( 'init', array( $this, 'add_taxonomy_capabilities' ) );
        add_action( 'created_term', array( $this, 'created_term_handler' ), 10, 3 );
    }

    /**
     * Add taxonomy capabilities
     */
    public function add_taxonomy_capabilities() {
        // Additional capabilities for taxonomies if needed
    }

    /**
     * Handle created terms
     *
     * @param int    $term_id
     * @param int    $tt_id
     * @param string $taxonomy
     */
    public function created_term_handler( $term_id, $tt_id, $taxonomy ) {
        // Add default term meta or perform actions when terms are created
        $default_term_meta = array(
            'service_category' => array(
                'icon'        => '',
                'color'       => '#14b8a6',
                'description' => '',
            ),
            'fish_water_type' => array(
                'temperature_range' => '',
                'ph_range'         => '',
                'difficulty'       => 'beginner',
            ),
            'fish_care_level' => array(
                'difficulty_score' => 1,
                'recommended_for'  => 'beginners',
            ),
            'event_type' => array(
                'default_duration' => '2',
                'capacity'         => '50',
                'booking_required' => true,
            ),
        );

        if ( isset( $default_term_meta[ $taxonomy ] ) ) {
            foreach ( $default_term_meta[ $taxonomy ] as $meta_key => $meta_value ) {
                add_term_meta( $term_id, $meta_key, $meta_value, true );
            }
        }
    }
}