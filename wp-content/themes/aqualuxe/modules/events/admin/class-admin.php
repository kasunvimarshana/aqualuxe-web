<?php
/**
 * Events Admin Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Events\Admin
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Events\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Events Admin Class
 * 
 * This class handles the admin functionality for the events module.
 */
class Admin {
    /**
     * Instance of this class
     *
     * @var Admin
     */
    private static $instance = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Get the singleton instance
     *
     * @return Admin
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Add meta boxes
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );

        // Save meta box data
        add_action( 'save_post', [ $this, 'save_meta_box_data' ] );

    // Add admin columns
    add_filter( 'manage_aqlx_event_posts_columns', [ $this, 'add_event_columns' ] );
    add_action( 'manage_aqlx_event_posts_custom_column', [ $this, 'render_event_columns' ], 10, 2 );
    add_filter( 'manage_edit-aqlx_event_sortable_columns', [ $this, 'sortable_event_columns' ] );

    add_filter( 'manage_aqlx_venue_posts_columns', [ $this, 'add_venue_columns' ] );
    add_action( 'manage_aqlx_venue_posts_custom_column', [ $this, 'render_venue_columns' ], 10, 2 );
    add_filter( 'manage_edit-aqlx_venue_sortable_columns', [ $this, 'sortable_venue_columns' ] );

        // Add admin filters
        add_action( 'restrict_manage_posts', [ $this, 'add_admin_filters' ] );
        add_filter( 'parse_query', [ $this, 'filter_events_by_date' ] );

        // Add category color field
        add_action( 'event_category_add_form_fields', [ $this, 'add_category_color_field' ] );
        add_action( 'event_category_edit_form_fields', [ $this, 'edit_category_color_field' ] );
        add_action( 'created_event_category', [ $this, 'save_category_color' ] );
        add_action( 'edited_event_category', [ $this, 'save_category_color' ] );

        // Add admin scripts and styles
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

        // Add dashboard widget
        add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widget' ] );

        // Add settings page
        add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    /**
     * Add meta boxes
     *
     * @return void
     */
    public function add_meta_boxes() {
        // Add meta boxes for events
        add_meta_box(
            'aqualuxe-event-details',
            __( 'Event Details', 'aqualuxe' ),
            [ $this, 'render_event_details_meta_box' ],
            'aqlx_event',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-event-venue',
            __( 'Event Venue', 'aqualuxe' ),
            [ $this, 'render_event_venue_meta_box' ],
            'aqlx_event',
            'side',
            'default'
        );

        add_meta_box(
            'aqualuxe-event-organizer',
            __( 'Event Organizer', 'aqualuxe' ),
            [ $this, 'render_event_organizer_meta_box' ],
            'aqlx_event',
            'side',
            'default'
        );

        // Add meta boxes for venues
        add_meta_box(
            'aqualuxe-venue-details',
            __( 'Venue Details', 'aqualuxe' ),
            [ $this, 'render_venue_details_meta_box' ],
            'aqlx_venue',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-venue-location',
            __( 'Venue Location', 'aqualuxe' ),
            [ $this, 'render_venue_location_meta_box' ],
            'aqlx_venue',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-venue-contact',
            __( 'Venue Contact', 'aqualuxe' ),
            [ $this, 'render_venue_contact_meta_box' ],
            'aqlx_venue',
            'side',
            'default'
        );
    }

    /**
     * Render event details meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_event_details_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_event_details', 'aqualuxe_event_details_nonce' );

        $start_date = get_post_meta( $post->ID, '_aqualuxe_event_start_date', true );
        $end_date = get_post_meta( $post->ID, '_aqualuxe_event_end_date', true );
        $cost = get_post_meta( $post->ID, '_aqualuxe_event_cost', true );
        $url = get_post_meta( $post->ID, '_aqualuxe_event_url', true );
        $capacity = get_post_meta( $post->ID, '_aqualuxe_event_capacity', true );
        $registration_status = get_post_meta( $post->ID, '_aqualuxe_event_registration_status', true );

        if ( ! $registration_status ) {
            $registration_status = 'open';
        }

        // Format dates for display
        if ( $start_date ) {
            $start_date_display = date( 'Y-m-d\TH:i', strtotime( $start_date ) );
        } else {
            $start_date_display = '';
        }

        if ( $end_date ) {
            $end_date_display = date( 'Y-m-d\TH:i', strtotime( $end_date ) );
        } else {
            $end_date_display = '';
        }
        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-event-start-date"><?php esc_html_e( 'Start Date and Time', 'aqualuxe' ); ?></label>
            <input type="datetime-local" id="aqualuxe-event-start-date" name="aqualuxe_event_start_date" value="<?php echo esc_attr( $start_date_display ); ?>" required>
            <p class="description"><?php esc_html_e( 'The start date and time of the event.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-event-end-date"><?php esc_html_e( 'End Date and Time', 'aqualuxe' ); ?></label>
            <input type="datetime-local" id="aqualuxe-event-end-date" name="aqualuxe_event_end_date" value="<?php echo esc_attr( $end_date_display ); ?>">
            <p class="description"><?php esc_html_e( 'The end date and time of the event. Leave blank for same-day events.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-event-cost"><?php esc_html_e( 'Cost', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-event-cost" name="aqualuxe_event_cost" value="<?php echo esc_attr( $cost ); ?>" step="0.01" min="0">
            <p class="description"><?php esc_html_e( 'The cost of the event. Leave blank for free events.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-event-url"><?php esc_html_e( 'Event URL', 'aqualuxe' ); ?></label>
            <input type="url" id="aqualuxe-event-url" name="aqualuxe_event_url" value="<?php echo esc_url( $url ); ?>">
            <p class="description"><?php esc_html_e( 'The URL for the event. Leave blank to use the event page.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-event-capacity"><?php esc_html_e( 'Capacity', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-event-capacity" name="aqualuxe_event_capacity" value="<?php echo esc_attr( $capacity ); ?>" step="1" min="0">
            <p class="description"><?php esc_html_e( 'The maximum number of attendees. Leave blank for unlimited.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-event-registration-status"><?php esc_html_e( 'Registration Status', 'aqualuxe' ); ?></label>
            <select id="aqualuxe-event-registration-status" name="aqualuxe_event_registration_status">
                <option value="open" <?php selected( $registration_status, 'open' ); ?>><?php esc_html_e( 'Open', 'aqualuxe' ); ?></option>
                <option value="closed" <?php selected( $registration_status, 'closed' ); ?>><?php esc_html_e( 'Closed', 'aqualuxe' ); ?></option>
                <option value="soldout" <?php selected( $registration_status, 'soldout' ); ?>><?php esc_html_e( 'Sold Out', 'aqualuxe' ); ?></option>
                <option value="cancelled" <?php selected( $registration_status, 'cancelled' ); ?>><?php esc_html_e( 'Cancelled', 'aqualuxe' ); ?></option>
            </select>
            <p class="description"><?php esc_html_e( 'The registration status of the event.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    /**
     * Render event venue meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_event_venue_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_event_venue', 'aqualuxe_event_venue_nonce' );

        $venue_id = get_post_meta( $post->ID, '_aqualuxe_event_venue_id', true );

        // Get all venues
        $venues = get_posts( [
            'post_type'      => 'aqualuxe_venue',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );
        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-event-venue-id"><?php esc_html_e( 'Venue', 'aqualuxe' ); ?></label>
            <select id="aqualuxe-event-venue-id" name="aqualuxe_event_venue_id">
                <option value=""><?php esc_html_e( 'Select a venue', 'aqualuxe' ); ?></option>
                <?php foreach ( $venues as $venue ) : ?>
                    <option value="<?php echo esc_attr( $venue->ID ); ?>" <?php selected( $venue_id, $venue->ID ); ?>><?php echo esc_html( $venue->post_title ); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php esc_html_e( 'Select the venue for this event.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=aqualuxe_venue' ) ); ?>" class="button" target="_blank"><?php esc_html_e( 'Add New Venue', 'aqualuxe' ); ?></a>
        </div>
        <?php
    }

    /**
     * Render event organizer meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_event_organizer_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_event_organizer', 'aqualuxe_event_organizer_nonce' );

        $organizer = get_post_meta( $post->ID, '_aqualuxe_event_organizer', true );
        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-event-organizer"><?php esc_html_e( 'Organizer', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-event-organizer" name="aqualuxe_event_organizer" value="<?php echo esc_attr( $organizer ); ?>">
            <p class="description"><?php esc_html_e( 'The organizer of the event.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    /**
     * Render venue details meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_venue_details_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_venue_details', 'aqualuxe_venue_details_nonce' );

        $capacity = get_post_meta( $post->ID, '_aqualuxe_venue_capacity', true );
        $website = get_post_meta( $post->ID, '_aqualuxe_venue_website', true );
        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-capacity"><?php esc_html_e( 'Capacity', 'aqualuxe' ); ?></label>
            <input type="number" id="aqualuxe-venue-capacity" name="aqualuxe_venue_capacity" value="<?php echo esc_attr( $capacity ); ?>" step="1" min="0">
            <p class="description"><?php esc_html_e( 'The maximum capacity of the venue.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-website"><?php esc_html_e( 'Website', 'aqualuxe' ); ?></label>
            <input type="url" id="aqualuxe-venue-website" name="aqualuxe_venue_website" value="<?php echo esc_url( $website ); ?>">
            <p class="description"><?php esc_html_e( 'The website URL of the venue.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    /**
     * Render venue location meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_venue_location_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_venue_location', 'aqualuxe_venue_location_nonce' );

        $address = get_post_meta( $post->ID, '_aqualuxe_venue_address', true );
        $city = get_post_meta( $post->ID, '_aqualuxe_venue_city', true );
        $state = get_post_meta( $post->ID, '_aqualuxe_venue_state', true );
        $postal_code = get_post_meta( $post->ID, '_aqualuxe_venue_postal_code', true );
        $country = get_post_meta( $post->ID, '_aqualuxe_venue_country', true );
        $latitude = get_post_meta( $post->ID, '_aqualuxe_venue_latitude', true );
        $longitude = get_post_meta( $post->ID, '_aqualuxe_venue_longitude', true );
        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-address"><?php esc_html_e( 'Address', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-venue-address" name="aqualuxe_venue_address" value="<?php echo esc_attr( $address ); ?>">
            <p class="description"><?php esc_html_e( 'The street address of the venue.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-city"><?php esc_html_e( 'City', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-venue-city" name="aqualuxe_venue_city" value="<?php echo esc_attr( $city ); ?>">
            <p class="description"><?php esc_html_e( 'The city of the venue.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-state"><?php esc_html_e( 'State/Province', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-venue-state" name="aqualuxe_venue_state" value="<?php echo esc_attr( $state ); ?>">
            <p class="description"><?php esc_html_e( 'The state or province of the venue.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-postal-code"><?php esc_html_e( 'Postal Code', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-venue-postal-code" name="aqualuxe_venue_postal_code" value="<?php echo esc_attr( $postal_code ); ?>">
            <p class="description"><?php esc_html_e( 'The postal code of the venue.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-country"><?php esc_html_e( 'Country', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-venue-country" name="aqualuxe_venue_country" value="<?php echo esc_attr( $country ); ?>">
            <p class="description"><?php esc_html_e( 'The country of the venue.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-latitude"><?php esc_html_e( 'Latitude', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-venue-latitude" name="aqualuxe_venue_latitude" value="<?php echo esc_attr( $latitude ); ?>">
            <p class="description"><?php esc_html_e( 'The latitude of the venue for map display.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-longitude"><?php esc_html_e( 'Longitude', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe-venue-longitude" name="aqualuxe_venue_longitude" value="<?php echo esc_attr( $longitude ); ?>">
            <p class="description"><?php esc_html_e( 'The longitude of the venue for map display.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    /**
     * Render venue contact meta box
     *
     * @param \WP_Post $post
     * @return void
     */
    public function render_venue_contact_meta_box( $post ) {
        wp_nonce_field( 'aqualuxe_venue_contact', 'aqualuxe_venue_contact_nonce' );

        $phone = get_post_meta( $post->ID, '_aqualuxe_venue_phone', true );
        $email = get_post_meta( $post->ID, '_aqualuxe_venue_email', true );
        ?>
        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
            <input type="tel" id="aqualuxe-venue-phone" name="aqualuxe_venue_phone" value="<?php echo esc_attr( $phone ); ?>">
            <p class="description"><?php esc_html_e( 'The phone number of the venue.', 'aqualuxe' ); ?></p>
        </div>

        <div class="aqualuxe-meta-box-row">
            <label for="aqualuxe-venue-email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
            <input type="email" id="aqualuxe-venue-email" name="aqualuxe_venue_email" value="<?php echo esc_attr( $email ); ?>">
            <p class="description"><?php esc_html_e( 'The email address of the venue.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    /**
     * Save meta box data
     *
     * @param int $post_id
     * @return void
     */
    public function save_meta_box_data( $post_id ) {
        // Check if our nonce is set
        if ( ! isset( $_POST['aqualuxe_event_details_nonce'] ) && ! isset( $_POST['aqualuxe_venue_details_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid
        if ( isset( $_POST['aqualuxe_event_details_nonce'] ) && ! wp_verify_nonce( $_POST['aqualuxe_event_details_nonce'], 'aqualuxe_event_details' ) ) {
            return;
        }

        if ( isset( $_POST['aqualuxe_venue_details_nonce'] ) && ! wp_verify_nonce( $_POST['aqualuxe_venue_details_nonce'], 'aqualuxe_venue_details' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions
        if ( isset( $_POST['post_type'] ) && 'aqualuxe_event' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        } elseif ( isset( $_POST['post_type'] ) && 'aqualuxe_venue' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        } else {
            return;
        }

        // Save event details
        if ( isset( $_POST['post_type'] ) && 'aqualuxe_event' === $_POST['post_type'] ) {
            // Save event details
            if ( isset( $_POST['aqualuxe_event_start_date'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_event_start_date', sanitize_text_field( $_POST['aqualuxe_event_start_date'] ) );
            }

            if ( isset( $_POST['aqualuxe_event_end_date'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_event_end_date', sanitize_text_field( $_POST['aqualuxe_event_end_date'] ) );
            }

            if ( isset( $_POST['aqualuxe_event_cost'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_event_cost', sanitize_text_field( $_POST['aqualuxe_event_cost'] ) );
            }

            if ( isset( $_POST['aqualuxe_event_url'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_event_url', esc_url_raw( $_POST['aqualuxe_event_url'] ) );
            }

            if ( isset( $_POST['aqualuxe_event_capacity'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_event_capacity', absint( $_POST['aqualuxe_event_capacity'] ) );
            }

            if ( isset( $_POST['aqualuxe_event_registration_status'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_event_registration_status', sanitize_text_field( $_POST['aqualuxe_event_registration_status'] ) );
            }

            // Save event venue
            if ( isset( $_POST['aqualuxe_event_venue_id'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_event_venue_id', absint( $_POST['aqualuxe_event_venue_id'] ) );
            }

            // Save event organizer
            if ( isset( $_POST['aqualuxe_event_organizer'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_event_organizer', sanitize_text_field( $_POST['aqualuxe_event_organizer'] ) );
            }
        }

        // Save venue details
        if ( isset( $_POST['post_type'] ) && 'aqualuxe_venue' === $_POST['post_type'] ) {
            // Save venue details
            if ( isset( $_POST['aqualuxe_venue_capacity'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_capacity', absint( $_POST['aqualuxe_venue_capacity'] ) );
            }

            if ( isset( $_POST['aqualuxe_venue_website'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_website', esc_url_raw( $_POST['aqualuxe_venue_website'] ) );
            }

            // Save venue location
            if ( isset( $_POST['aqualuxe_venue_address'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_address', sanitize_text_field( $_POST['aqualuxe_venue_address'] ) );
            }

            if ( isset( $_POST['aqualuxe_venue_city'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_city', sanitize_text_field( $_POST['aqualuxe_venue_city'] ) );
            }

            if ( isset( $_POST['aqualuxe_venue_state'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_state', sanitize_text_field( $_POST['aqualuxe_venue_state'] ) );
            }

            if ( isset( $_POST['aqualuxe_venue_postal_code'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_postal_code', sanitize_text_field( $_POST['aqualuxe_venue_postal_code'] ) );
            }

            if ( isset( $_POST['aqualuxe_venue_country'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_country', sanitize_text_field( $_POST['aqualuxe_venue_country'] ) );
            }

            if ( isset( $_POST['aqualuxe_venue_latitude'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_latitude', sanitize_text_field( $_POST['aqualuxe_venue_latitude'] ) );
            }

            if ( isset( $_POST['aqualuxe_venue_longitude'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_longitude', sanitize_text_field( $_POST['aqualuxe_venue_longitude'] ) );
            }

            // Save venue contact
            if ( isset( $_POST['aqualuxe_venue_phone'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_phone', sanitize_text_field( $_POST['aqualuxe_venue_phone'] ) );
            }

            if ( isset( $_POST['aqualuxe_venue_email'] ) ) {
                update_post_meta( $post_id, '_aqualuxe_venue_email', sanitize_email( $_POST['aqualuxe_venue_email'] ) );
            }
        }
    }

    /**
     * Add event columns
     *
     * @param array $columns
     * @return array
     */
    public function add_event_columns( $columns ) {
        $new_columns = [];

        foreach ( $columns as $key => $value ) {
            if ( 'title' === $key ) {
                $new_columns[ $key ] = $value;
                $new_columns['event_date'] = __( 'Date', 'aqualuxe' );
                $new_columns['event_time'] = __( 'Time', 'aqualuxe' );
                $new_columns['event_venue'] = __( 'Venue', 'aqualuxe' );
                $new_columns['event_organizer'] = __( 'Organizer', 'aqualuxe' );
                $new_columns['event_cost'] = __( 'Cost', 'aqualuxe' );
                $new_columns['event_status'] = __( 'Status', 'aqualuxe' );
            } else {
                $new_columns[ $key ] = $value;
            }
        }

        return $new_columns;
    }

    /**
     * Render event columns
     *
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function render_event_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'event_date':
                $start_date = get_post_meta( $post_id, '_aqualuxe_event_start_date', true );
                if ( $start_date ) {
                    echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $start_date ) ) );
                } else {
                    echo '—';
                }
                break;

            case 'event_time':
                $start_date = get_post_meta( $post_id, '_aqualuxe_event_start_date', true );
                if ( $start_date ) {
                    echo esc_html( date_i18n( get_option( 'time_format' ), strtotime( $start_date ) ) );
                } else {
                    echo '—';
                }
                break;

            case 'event_venue':
                $venue_id = get_post_meta( $post_id, '_aqualuxe_event_venue_id', true );
                if ( $venue_id ) {
                    $venue = get_post( $venue_id );
                    if ( $venue ) {
                        echo '<a href="' . esc_url( get_edit_post_link( $venue_id ) ) . '">' . esc_html( $venue->post_title ) . '</a>';
                    } else {
                        echo '—';
                    }
                } else {
                    echo '—';
                }
                break;

            case 'event_organizer':
                $organizer = get_post_meta( $post_id, '_aqualuxe_event_organizer', true );
                if ( $organizer ) {
                    echo esc_html( $organizer );
                } else {
                    echo '—';
                }
                break;

            case 'event_cost':
                $cost = get_post_meta( $post_id, '_aqualuxe_event_cost', true );
                if ( '' !== $cost ) {
                    if ( function_exists( 'wc_price' ) ) {
                        echo wc_price( $cost );
                    } else {
                        echo esc_html( number_format_i18n( $cost, 2 ) );
                    }
                } else {
                    echo esc_html__( 'Free', 'aqualuxe' );
                }
                break;

            case 'event_status':
                $status = get_post_meta( $post_id, '_aqualuxe_event_registration_status', true );
                if ( ! $status ) {
                    $status = 'open';
                }
                $status_labels = [
                    'open'      => __( 'Open', 'aqualuxe' ),
                    'closed'    => __( 'Closed', 'aqualuxe' ),
                    'soldout'   => __( 'Sold Out', 'aqualuxe' ),
                    'cancelled' => __( 'Cancelled', 'aqualuxe' ),
                ];
                $status_classes = [
                    'open'      => 'status-open',
                    'closed'    => 'status-closed',
                    'soldout'   => 'status-soldout',
                    'cancelled' => 'status-cancelled',
                ];
                echo '<span class="event-status ' . esc_attr( $status_classes[ $status ] ) . '">' . esc_html( $status_labels[ $status ] ) . '</span>';
                break;
        }
    }

    /**
     * Sortable event columns
     *
     * @param array $columns
     * @return array
     */
    public function sortable_event_columns( $columns ) {
        $columns['event_date'] = 'event_date';
        $columns['event_venue'] = 'event_venue';
        $columns['event_cost'] = 'event_cost';
        $columns['event_status'] = 'event_status';
        return $columns;
    }

    /**
     * Add venue columns
     *
     * @param array $columns
     * @return array
     */
    public function add_venue_columns( $columns ) {
        $new_columns = [];

        foreach ( $columns as $key => $value ) {
            if ( 'title' === $key ) {
                $new_columns[ $key ] = $value;
                $new_columns['venue_address'] = __( 'Address', 'aqualuxe' );
                $new_columns['venue_city'] = __( 'City', 'aqualuxe' );
                $new_columns['venue_capacity'] = __( 'Capacity', 'aqualuxe' );
                $new_columns['venue_phone'] = __( 'Phone', 'aqualuxe' );
                $new_columns['venue_email'] = __( 'Email', 'aqualuxe' );
            } else {
                $new_columns[ $key ] = $value;
            }
        }

        return $new_columns;
    }

    /**
     * Render venue columns
     *
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function render_venue_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'venue_address':
                $address = get_post_meta( $post_id, '_aqualuxe_venue_address', true );
                if ( $address ) {
                    echo esc_html( $address );
                } else {
                    echo '—';
                }
                break;

            case 'venue_city':
                $city = get_post_meta( $post_id, '_aqualuxe_venue_city', true );
                $state = get_post_meta( $post_id, '_aqualuxe_venue_state', true );
                if ( $city && $state ) {
                    echo esc_html( $city . ', ' . $state );
                } elseif ( $city ) {
                    echo esc_html( $city );
                } elseif ( $state ) {
                    echo esc_html( $state );
                } else {
                    echo '—';
                }
                break;

            case 'venue_capacity':
                $capacity = get_post_meta( $post_id, '_aqualuxe_venue_capacity', true );
                if ( $capacity ) {
                    echo esc_html( $capacity );
                } else {
                    echo '—';
                }
                break;

            case 'venue_phone':
                $phone = get_post_meta( $post_id, '_aqualuxe_venue_phone', true );
                if ( $phone ) {
                    echo esc_html( $phone );
                } else {
                    echo '—';
                }
                break;

            case 'venue_email':
                $email = get_post_meta( $post_id, '_aqualuxe_venue_email', true );
                if ( $email ) {
                    echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
                } else {
                    echo '—';
                }
                break;
        }
    }

    /**
     * Sortable venue columns
     *
     * @param array $columns
     * @return array
     */
    public function sortable_venue_columns( $columns ) {
        $columns['venue_city'] = 'venue_city';
        $columns['venue_capacity'] = 'venue_capacity';
        return $columns;
    }

    /**
     * Add admin filters
     *
     * @param string $post_type
     * @return void
     */
    public function add_admin_filters( $post_type ) {
        if ( 'aqualuxe_event' !== $post_type ) {
            return;
        }

        // Filter by date
        $current_date = isset( $_GET['event_date'] ) ? sanitize_text_field( $_GET['event_date'] ) : '';
        ?>
        <input type="date" name="event_date" value="<?php echo esc_attr( $current_date ); ?>" placeholder="<?php esc_attr_e( 'Filter by date', 'aqualuxe' ); ?>">
        <?php

        // Filter by venue
        $current_venue = isset( $_GET['event_venue'] ) ? absint( $_GET['event_venue'] ) : 0;
        $venues = get_posts( [
            'post_type'      => 'aqualuxe_venue',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );
        ?>
        <select name="event_venue">
            <option value=""><?php esc_html_e( 'All Venues', 'aqualuxe' ); ?></option>
            <?php foreach ( $venues as $venue ) : ?>
                <option value="<?php echo esc_attr( $venue->ID ); ?>" <?php selected( $current_venue, $venue->ID ); ?>><?php echo esc_html( $venue->post_title ); ?></option>
            <?php endforeach; ?>
        </select>
        <?php

        // Filter by status
        $current_status = isset( $_GET['event_status'] ) ? sanitize_text_field( $_GET['event_status'] ) : '';
        $statuses = [
            'open'      => __( 'Open', 'aqualuxe' ),
            'closed'    => __( 'Closed', 'aqualuxe' ),
            'soldout'   => __( 'Sold Out', 'aqualuxe' ),
            'cancelled' => __( 'Cancelled', 'aqualuxe' ),
        ];
        ?>
        <select name="event_status">
            <option value=""><?php esc_html_e( 'All Statuses', 'aqualuxe' ); ?></option>
            <?php foreach ( $statuses as $status => $label ) : ?>
                <option value="<?php echo esc_attr( $status ); ?>" <?php selected( $current_status, $status ); ?>><?php echo esc_html( $label ); ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    /**
     * Filter events by date
     *
     * @param \WP_Query $query
     * @return void
     */
    public function filter_events_by_date( $query ) {
        global $pagenow;

        if ( ! is_admin() || 'edit.php' !== $pagenow || ! $query->is_main_query() || 'aqualuxe_event' !== $query->get( 'post_type' ) ) {
            return;
        }

        $meta_query = [];

        // Filter by date
        if ( isset( $_GET['event_date'] ) && ! empty( $_GET['event_date'] ) ) {
            $date = sanitize_text_field( $_GET['event_date'] );
            $meta_query[] = [
                'key'     => '_aqualuxe_event_start_date',
                'value'   => $date,
                'compare' => 'LIKE',
            ];
        }

        // Filter by venue
        if ( isset( $_GET['event_venue'] ) && ! empty( $_GET['event_venue'] ) ) {
            $venue_id = absint( $_GET['event_venue'] );
            $meta_query[] = [
                'key'   => '_aqualuxe_event_venue_id',
                'value' => $venue_id,
            ];
        }

        // Filter by status
        if ( isset( $_GET['event_status'] ) && ! empty( $_GET['event_status'] ) ) {
            $status = sanitize_text_field( $_GET['event_status'] );
            $meta_query[] = [
                'key'   => '_aqualuxe_event_registration_status',
                'value' => $status,
            ];
        }

        if ( ! empty( $meta_query ) ) {
            $query->set( 'meta_query', $meta_query );
        }
    }

    /**
     * Add category color field
     *
     * @param string $taxonomy
     * @return void
     */
    public function add_category_color_field( $taxonomy ) {
        ?>
        <div class="form-field term-color-wrap">
            <label for="term-color"><?php esc_html_e( 'Color', 'aqualuxe' ); ?></label>
            <input type="color" id="term-color" name="term_color" value="#3788d8">
            <p class="description"><?php esc_html_e( 'Choose a color for this category.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    /**
     * Edit category color field
     *
     * @param \WP_Term $term
     * @return void
     */
    public function edit_category_color_field( $term ) {
        $color = get_term_meta( $term->term_id, '_aqualuxe_category_color', true );
        if ( ! $color ) {
            $color = '#3788d8';
        }
        ?>
        <tr class="form-field term-color-wrap">
            <th scope="row"><label for="term-color"><?php esc_html_e( 'Color', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="color" id="term-color" name="term_color" value="<?php echo esc_attr( $color ); ?>">
                <p class="description"><?php esc_html_e( 'Choose a color for this category.', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Save category color
     *
     * @param int $term_id
     * @return void
     */
    public function save_category_color( $term_id ) {
        if ( isset( $_POST['term_color'] ) ) {
            update_term_meta( $term_id, '_aqualuxe_category_color', sanitize_hex_color( $_POST['term_color'] ) );
        }
    }

    /**
     * Enqueue admin scripts
     *
     * @param string $hook
     * @return void
     */
    public function enqueue_admin_scripts( $hook ) {
        $screen = get_current_screen();

        if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
            if ( 'aqualuxe_event' === $screen->post_type || 'aqualuxe_venue' === $screen->post_type ) {
                wp_enqueue_style( 'aqualuxe-events-admin', AQUALUXE_MODULES_DIR . 'events/assets/css/admin.css', [], AQUALUXE_VERSION );
                wp_enqueue_script( 'aqualuxe-events-admin', AQUALUXE_MODULES_DIR . 'events/assets/js/admin.js', [ 'jquery' ], AQUALUXE_VERSION, true );
            }
        }

        if ( 'edit.php' === $hook && 'aqualuxe_event' === $screen->post_type ) {
            wp_enqueue_style( 'aqualuxe-events-admin', AQUALUXE_MODULES_DIR . 'events/assets/css/admin.css', [], AQUALUXE_VERSION );
        }
    }

    /**
     * Add dashboard widget
     *
     * @return void
     */
    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'aqualuxe_upcoming_events',
            __( 'Upcoming Events', 'aqualuxe' ),
            [ $this, 'render_dashboard_widget' ]
        );
    }

    /**
     * Render dashboard widget
     *
     * @return void
     */
    public function render_dashboard_widget() {
        $events = \AquaLuxe\Modules\Events\Event::get_upcoming_events( 5 );

        if ( empty( $events ) ) {
            echo '<p>' . esc_html__( 'No upcoming events found.', 'aqualuxe' ) . '</p>';
            echo '<p><a href="' . esc_url( admin_url( 'post-new.php?post_type=aqualuxe_event' ) ) . '" class="button">' . esc_html__( 'Add New Event', 'aqualuxe' ) . '</a></p>';
            return;
        }

        echo '<ul class="aqualuxe-dashboard-events">';
        foreach ( $events as $event ) {
            $event_data = $event->get_data();
            echo '<li>';
            echo '<strong><a href="' . esc_url( get_edit_post_link( $event_data['id'] ) ) . '">' . esc_html( $event_data['title'] ) . '</a></strong><br>';
            echo '<span class="event-date">' . esc_html( $event->get_start_date( get_option( 'date_format' ) ) ) . ' ' . esc_html( $event->get_start_date( get_option( 'time_format' ) ) ) . '</span><br>';
            if ( $event_data['venue_name'] ) {
                echo '<span class="event-venue">' . esc_html( $event_data['venue_name'] ) . '</span>';
            }
            echo '</li>';
        }
        echo '</ul>';

        echo '<p><a href="' . esc_url( admin_url( 'edit.php?post_type=aqualuxe_event' ) ) . '">' . esc_html__( 'View All Events', 'aqualuxe' ) . '</a> | <a href="' . esc_url( admin_url( 'post-new.php?post_type=aqualuxe_event' ) ) . '">' . esc_html__( 'Add New Event', 'aqualuxe' ) . '</a></p>';
    }

    /**
     * Add settings page
     *
     * @return void
     */
    public function add_settings_page() {
        add_submenu_page(
            'edit.php?post_type=aqualuxe_event',
            __( 'Events Settings', 'aqualuxe' ),
            __( 'Settings', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-events-settings',
            [ $this, 'render_settings_page' ]
        );
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register_settings() {
        register_setting( 'aqualuxe_events_settings', 'aqualuxe_events_settings' );

        add_settings_section(
            'aqualuxe_events_general',
            __( 'General Settings', 'aqualuxe' ),
            [ $this, 'render_general_settings_section' ],
            'aqualuxe_events_settings'
        );

        add_settings_field(
            'events_page',
            __( 'Events Page', 'aqualuxe' ),
            [ $this, 'render_events_page_field' ],
            'aqualuxe_events_settings',
            'aqualuxe_events_general'
        );

        add_settings_field(
            'google_maps_api_key',
            __( 'Google Maps API Key', 'aqualuxe' ),
            [ $this, 'render_google_maps_api_key_field' ],
            'aqualuxe_events_settings',
            'aqualuxe_events_general'
        );

        add_settings_field(
            'default_view',
            __( 'Default Calendar View', 'aqualuxe' ),
            [ $this, 'render_default_view_field' ],
            'aqualuxe_events_settings',
            'aqualuxe_events_general'
        );

        add_settings_field(
            'events_per_page',
            __( 'Events Per Page', 'aqualuxe' ),
            [ $this, 'render_events_per_page_field' ],
            'aqualuxe_events_settings',
            'aqualuxe_events_general'
        );
    }

    /**
     * Render settings page
     *
     * @return void
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Events Settings', 'aqualuxe' ); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'aqualuxe_events_settings' );
                do_settings_sections( 'aqualuxe_events_settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render general settings section
     *
     * @return void
     */
    public function render_general_settings_section() {
        echo '<p>' . esc_html__( 'Configure general settings for the events module.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render events page field
     *
     * @return void
     */
    public function render_events_page_field() {
        $settings = get_option( 'aqualuxe_events_settings', [] );
        $events_page = isset( $settings['events_page'] ) ? $settings['events_page'] : '';

        wp_dropdown_pages( [
            'name'              => 'aqualuxe_events_settings[events_page]',
            'selected'          => $events_page,
            'show_option_none'  => __( 'Select a page', 'aqualuxe' ),
        ] );
        echo '<p class="description">' . esc_html__( 'Select the page where the events calendar will be displayed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render Google Maps API key field
     *
     * @return void
     */
    public function render_google_maps_api_key_field() {
        $settings = get_option( 'aqualuxe_events_settings', [] );
        $google_maps_api_key = isset( $settings['google_maps_api_key'] ) ? $settings['google_maps_api_key'] : '';

        echo '<input type="text" name="aqualuxe_events_settings[google_maps_api_key]" value="' . esc_attr( $google_maps_api_key ) . '" class="regular-text">';
        echo '<p class="description">' . esc_html__( 'Enter your Google Maps API key to enable maps for venues.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render default view field
     *
     * @return void
     */
    public function render_default_view_field() {
        $settings = get_option( 'aqualuxe_events_settings', [] );
        $default_view = isset( $settings['default_view'] ) ? $settings['default_view'] : 'month';

        $views = [
            'month'     => __( 'Month', 'aqualuxe' ),
            'week'      => __( 'Week', 'aqualuxe' ),
            'day'       => __( 'Day', 'aqualuxe' ),
            'listMonth' => __( 'List', 'aqualuxe' ),
        ];

        echo '<select name="aqualuxe_events_settings[default_view]">';
        foreach ( $views as $value => $label ) {
            echo '<option value="' . esc_attr( $value ) . '" ' . selected( $default_view, $value, false ) . '>' . esc_html( $label ) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Select the default calendar view.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render events per page field
     *
     * @return void
     */
    public function render_events_per_page_field() {
        $settings = get_option( 'aqualuxe_events_settings', [] );
        $events_per_page = isset( $settings['events_per_page'] ) ? $settings['events_per_page'] : 10;

        echo '<input type="number" name="aqualuxe_events_settings[events_per_page]" value="' . esc_attr( $events_per_page ) . '" class="small-text" min="1" step="1">';
        echo '<p class="description">' . esc_html__( 'Number of events to display per page.', 'aqualuxe' ) . '</p>';
    }
}

// Initialize the admin class
Admin::get_instance();