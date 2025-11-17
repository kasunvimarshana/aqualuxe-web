<?php
/**
 * Shortcodes Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Shortcodes Class
 * 
 * This class handles module shortcodes.
 */
class Shortcodes {
    /**
     * Instance of this class
     *
     * @var Shortcodes
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Shortcodes
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Register shortcodes
        add_shortcode( 'aqualuxe_booking_form', [ $this, 'booking_form_shortcode' ] );
        add_shortcode( 'aqualuxe_services', [ $this, 'services_shortcode' ] );
        add_shortcode( 'aqualuxe_calendar', [ $this, 'calendar_shortcode' ] );
        add_shortcode( 'aqualuxe_availability', [ $this, 'availability_shortcode' ] );
    }

    /**
     * Booking form shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function booking_form_shortcode( $atts ) {
        // Extract shortcode attributes
        $atts = shortcode_atts(
            [
                'service_id' => 0,
                'date'       => '',
                'time'       => '',
                'title'      => esc_html__( 'Book an Appointment', 'aqualuxe' ),
                'button'     => esc_html__( 'Book Now', 'aqualuxe' ),
                'class'      => '',
            ],
            $atts,
            'aqualuxe_booking_form'
        );

        // Enqueue scripts and styles
        wp_enqueue_style( 'aqualuxe-bookings' );
        wp_enqueue_script( 'aqualuxe-bookings' );

        // Get service ID from URL if not specified
        if ( ! $atts['service_id'] && isset( $_GET['service_id'] ) ) {
            $atts['service_id'] = absint( $_GET['service_id'] );
        }

        // Get date from URL if not specified
        if ( ! $atts['date'] && isset( $_GET['date'] ) ) {
            $atts['date'] = sanitize_text_field( $_GET['date'] );
        }

        // Get time from URL if not specified
        if ( ! $atts['time'] && isset( $_GET['time'] ) ) {
            $atts['time'] = sanitize_text_field( $_GET['time'] );
        }

        // Start output buffering
        ob_start();

        // Include booking form template
        include AQUALUXE_MODULES_DIR . 'bookings/templates/booking-form.php';

        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Services shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function services_shortcode( $atts ) {
        // Extract shortcode attributes
        $atts = shortcode_atts(
            [
                'category'   => '',
                'limit'      => -1,
                'orderby'    => 'title',
                'order'      => 'ASC',
                'columns'    => 3,
                'show_image' => true,
                'show_price' => true,
                'show_desc'  => true,
                'show_button' => true,
                'button_text' => esc_html__( 'Book Now', 'aqualuxe' ),
                'class'      => '',
            ],
            $atts,
            'aqualuxe_services'
        );

        // Convert string boolean values to actual booleans
        $atts['show_image'] = filter_var( $atts['show_image'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_price'] = filter_var( $atts['show_price'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_desc'] = filter_var( $atts['show_desc'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_button'] = filter_var( $atts['show_button'], FILTER_VALIDATE_BOOLEAN );

        // Enqueue scripts and styles
        wp_enqueue_style( 'aqualuxe-bookings' );
        wp_enqueue_script( 'aqualuxe-bookings' );

        // Query args
        $args = [
            'post_type'      => 'aqualuxe_service',
            'posts_per_page' => $atts['limit'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        ];

        // Add category if specified
        if ( ! empty( $atts['category'] ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'aqualuxe_service_category',
                    'field'    => 'slug',
                    'terms'    => explode( ',', $atts['category'] ),
                ],
            ];
        }

        // Get services
        $services_query = new \WP_Query( $args );
        $services = [];

        if ( $services_query->have_posts() ) {
            while ( $services_query->have_posts() ) {
                $services_query->the_post();
                $service_id = get_the_ID();
                $services[] = new Service( $service_id );
            }
        }

        wp_reset_postdata();

        // Start output buffering
        ob_start();

        // Include services template
        include AQUALUXE_MODULES_DIR . 'bookings/templates/services.php';

        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Calendar shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function calendar_shortcode( $atts ) {
        // Extract shortcode attributes
        $atts = shortcode_atts(
            [
                'service_id' => 0,
                'month'      => date( 'n' ),
                'year'       => date( 'Y' ),
                'class'      => '',
            ],
            $atts,
            'aqualuxe_calendar'
        );

        // Enqueue scripts and styles
        wp_enqueue_style( 'aqualuxe-bookings' );
        wp_enqueue_script( 'aqualuxe-bookings' );

        // Get service ID from URL if not specified
        if ( ! $atts['service_id'] && isset( $_GET['service_id'] ) ) {
            $atts['service_id'] = absint( $_GET['service_id'] );
        }

        // Get month from URL if not specified
        if ( isset( $_GET['month'] ) ) {
            $atts['month'] = absint( $_GET['month'] );
        }

        // Get year from URL if not specified
        if ( isset( $_GET['year'] ) ) {
            $atts['year'] = absint( $_GET['year'] );
        }

        // Create calendar
        $calendar = new Calendar( $atts['service_id'], $atts['month'], $atts['year'] );

        // Start output buffering
        ob_start();

        // Include calendar template
        echo $calendar->generate();

        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Availability shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function availability_shortcode( $atts ) {
        // Extract shortcode attributes
        $atts = shortcode_atts(
            [
                'service_id' => 0,
                'date'       => date( 'Y-m-d' ),
                'class'      => '',
            ],
            $atts,
            'aqualuxe_availability'
        );

        // Enqueue scripts and styles
        wp_enqueue_style( 'aqualuxe-bookings' );
        wp_enqueue_script( 'aqualuxe-bookings' );

        // Get service ID from URL if not specified
        if ( ! $atts['service_id'] && isset( $_GET['service_id'] ) ) {
            $atts['service_id'] = absint( $_GET['service_id'] );
        }

        // Get date from URL if not specified
        if ( isset( $_GET['date'] ) ) {
            $atts['date'] = sanitize_text_field( $_GET['date'] );
        }

        // Create availability
        $availability = new Availability( $atts['service_id'], $atts['date'] );

        // Start output buffering
        ob_start();

        // Include availability template
        echo $availability->generate();

        // Return buffered output
        return ob_get_clean();
    }
}