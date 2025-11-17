<?php
/**
 * Event Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Events
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Events;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Event Class
 * 
 * This class represents a single event.
 */
class Event {
    /**
     * Event ID
     *
     * @var int
     */
    private $id;

    /**
     * Event title
     *
     * @var string
     */
    private $title;

    /**
     * Event description
     *
     * @var string
     */
    private $description;

    /**
     * Event start date and time
     *
     * @var string
     */
    private $start_date;

    /**
     * Event end date and time
     *
     * @var string
     */
    private $end_date;

    /**
     * Event venue ID
     *
     * @var int
     */
    private $venue_id;

    /**
     * Event organizer
     *
     * @var string
     */
    private $organizer;

    /**
     * Event cost
     *
     * @var float
     */
    private $cost;

    /**
     * Event URL
     *
     * @var string
     */
    private $url;

    /**
     * Event featured image ID
     *
     * @var int
     */
    private $featured_image_id;

    /**
     * Event capacity
     *
     * @var int
     */
    private $capacity;

    /**
     * Event registration status
     *
     * @var string
     */
    private $registration_status;

    /**
     * Constructor
     *
     * @param int $event_id
     */
    public function __construct( $event_id = 0 ) {
        if ( $event_id > 0 ) {
            $this->id = $event_id;
            $this->load();
        }
    }

    /**
     * Load event data
     *
     * @return boolean
     */
    public function load() {
        if ( ! $this->id ) {
            return false;
        }

        $event = get_post( $this->id );

        if ( ! $event || 'aqualuxe_event' !== $event->post_type ) {
            return false;
        }

        $this->title = $event->post_title;
        $this->description = $event->post_content;
        $this->start_date = get_post_meta( $this->id, '_aqualuxe_event_start_date', true );
        $this->end_date = get_post_meta( $this->id, '_aqualuxe_event_end_date', true );
        $this->venue_id = get_post_meta( $this->id, '_aqualuxe_event_venue_id', true );
        $this->organizer = get_post_meta( $this->id, '_aqualuxe_event_organizer', true );
        $this->cost = get_post_meta( $this->id, '_aqualuxe_event_cost', true );
        $this->url = get_post_meta( $this->id, '_aqualuxe_event_url', true );
        $this->featured_image_id = get_post_thumbnail_id( $this->id );
        $this->capacity = get_post_meta( $this->id, '_aqualuxe_event_capacity', true );
        $this->registration_status = get_post_meta( $this->id, '_aqualuxe_event_registration_status', true );

        if ( ! $this->registration_status ) {
            $this->registration_status = 'open';
        }

        return true;
    }

    /**
     * Save event data
     *
     * @return boolean
     */
    public function save() {
        $post_data = [
            'post_title'   => $this->title,
            'post_content' => $this->description,
            'post_status'  => 'publish',
            'post_type'    => 'aqualuxe_event',
        ];

        if ( $this->id ) {
            $post_data['ID'] = $this->id;
            $result = wp_update_post( $post_data );
        } else {
            $result = wp_insert_post( $post_data );
            if ( $result && ! is_wp_error( $result ) ) {
                $this->id = $result;
            }
        }

        if ( ! $result || is_wp_error( $result ) ) {
            return false;
        }

        // Save meta data
        update_post_meta( $this->id, '_aqualuxe_event_start_date', $this->start_date );
        update_post_meta( $this->id, '_aqualuxe_event_end_date', $this->end_date );
        update_post_meta( $this->id, '_aqualuxe_event_venue_id', $this->venue_id );
        update_post_meta( $this->id, '_aqualuxe_event_organizer', $this->organizer );
        update_post_meta( $this->id, '_aqualuxe_event_cost', $this->cost );
        update_post_meta( $this->id, '_aqualuxe_event_url', $this->url );
        update_post_meta( $this->id, '_aqualuxe_event_capacity', $this->capacity );
        update_post_meta( $this->id, '_aqualuxe_event_registration_status', $this->registration_status );

        // Set featured image
        if ( $this->featured_image_id ) {
            set_post_thumbnail( $this->id, $this->featured_image_id );
        }

        // Trigger action
        do_action( 'aqualuxe_event_saved', $this->id, $this );

        return true;
    }

    /**
     * Delete event
     *
     * @return boolean
     */
    public function delete() {
        if ( ! $this->id ) {
            return false;
        }

        // Trigger action before deletion
        do_action( 'aqualuxe_event_before_delete', $this->id, $this );

        $result = wp_delete_post( $this->id, true );

        if ( $result ) {
            // Trigger action after deletion
            do_action( 'aqualuxe_event_deleted', $this->id );
            return true;
        }

        return false;
    }

    /**
     * Update event registration status
     *
     * @param string $status
     * @return boolean
     */
    public function update_registration_status( $status ) {
        $valid_statuses = [ 'open', 'closed', 'soldout', 'cancelled' ];
        if ( ! in_array( $status, $valid_statuses, true ) ) {
            return false;
        }

        $old_status = $this->registration_status;
        $this->registration_status = $status;

        $result = update_post_meta( $this->id, '_aqualuxe_event_registration_status', $status );

        if ( $result ) {
            // Trigger status change action
            do_action( 'aqualuxe_event_registration_status_changed', $this->id, $status, $old_status, $this );
            return true;
        }

        return false;
    }

    /**
     * Check if event is upcoming
     *
     * @return boolean
     */
    public function is_upcoming() {
        if ( ! $this->start_date ) {
            return false;
        }

        $now = current_time( 'timestamp' );
        $start_date = strtotime( $this->start_date );

        return $start_date > $now;
    }

    /**
     * Check if event is ongoing
     *
     * @return boolean
     */
    public function is_ongoing() {
        if ( ! $this->start_date || ! $this->end_date ) {
            return false;
        }

        $now = current_time( 'timestamp' );
        $start_date = strtotime( $this->start_date );
        $end_date = strtotime( $this->end_date );

        return $now >= $start_date && $now <= $end_date;
    }

    /**
     * Check if event is past
     *
     * @return boolean
     */
    public function is_past() {
        if ( ! $this->end_date ) {
            return false;
        }

        $now = current_time( 'timestamp' );
        $end_date = strtotime( $this->end_date );

        return $end_date < $now;
    }

    /**
     * Check if registration is open
     *
     * @return boolean
     */
    public function is_registration_open() {
        return 'open' === $this->registration_status && ! $this->is_past();
    }

    /**
     * Get event ID
     *
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get event title
     *
     * @return string
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Set event title
     *
     * @param string $title
     * @return void
     */
    public function set_title( $title ) {
        $this->title = $title;
    }

    /**
     * Get event description
     *
     * @return string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Set event description
     *
     * @param string $description
     * @return void
     */
    public function set_description( $description ) {
        $this->description = $description;
    }

    /**
     * Get event start date
     *
     * @param string $format
     * @return string
     */
    public function get_start_date( $format = '' ) {
        if ( ! $format ) {
            return $this->start_date;
        }
        return date_i18n( $format, strtotime( $this->start_date ) );
    }

    /**
     * Set event start date
     *
     * @param string $start_date
     * @return void
     */
    public function set_start_date( $start_date ) {
        $this->start_date = $start_date;
    }

    /**
     * Get event end date
     *
     * @param string $format
     * @return string
     */
    public function get_end_date( $format = '' ) {
        if ( ! $format ) {
            return $this->end_date;
        }
        return date_i18n( $format, strtotime( $this->end_date ) );
    }

    /**
     * Set event end date
     *
     * @param string $end_date
     * @return void
     */
    public function set_end_date( $end_date ) {
        $this->end_date = $end_date;
    }

    /**
     * Get event venue ID
     *
     * @return int
     */
    public function get_venue_id() {
        return $this->venue_id;
    }

    /**
     * Set event venue ID
     *
     * @param int $venue_id
     * @return void
     */
    public function set_venue_id( $venue_id ) {
        $this->venue_id = $venue_id;
    }

    /**
     * Get event venue
     *
     * @return WP_Post|null
     */
    public function get_venue() {
        if ( ! $this->venue_id ) {
            return null;
        }
        return get_post( $this->venue_id );
    }

    /**
     * Get event venue name
     *
     * @return string
     */
    public function get_venue_name() {
        $venue = $this->get_venue();
        return $venue ? $venue->post_title : '';
    }

    /**
     * Get event venue address
     *
     * @return string
     */
    public function get_venue_address() {
        if ( ! $this->venue_id ) {
            return '';
        }
        return get_post_meta( $this->venue_id, '_aqualuxe_venue_address', true );
    }

    /**
     * Get event venue city
     *
     * @return string
     */
    public function get_venue_city() {
        if ( ! $this->venue_id ) {
            return '';
        }
        return get_post_meta( $this->venue_id, '_aqualuxe_venue_city', true );
    }

    /**
     * Get event venue state
     *
     * @return string
     */
    public function get_venue_state() {
        if ( ! $this->venue_id ) {
            return '';
        }
        return get_post_meta( $this->venue_id, '_aqualuxe_venue_state', true );
    }

    /**
     * Get event venue postal code
     *
     * @return string
     */
    public function get_venue_postal_code() {
        if ( ! $this->venue_id ) {
            return '';
        }
        return get_post_meta( $this->venue_id, '_aqualuxe_venue_postal_code', true );
    }

    /**
     * Get event venue country
     *
     * @return string
     */
    public function get_venue_country() {
        if ( ! $this->venue_id ) {
            return '';
        }
        return get_post_meta( $this->venue_id, '_aqualuxe_venue_country', true );
    }

    /**
     * Get event venue full address
     *
     * @return string
     */
    public function get_venue_full_address() {
        if ( ! $this->venue_id ) {
            return '';
        }

        $address_parts = [
            $this->get_venue_address(),
            $this->get_venue_city(),
            $this->get_venue_state(),
            $this->get_venue_postal_code(),
            $this->get_venue_country(),
        ];

        $address_parts = array_filter( $address_parts );

        return implode( ', ', $address_parts );
    }

    /**
     * Get event organizer
     *
     * @return string
     */
    public function get_organizer() {
        return $this->organizer;
    }

    /**
     * Set event organizer
     *
     * @param string $organizer
     * @return void
     */
    public function set_organizer( $organizer ) {
        $this->organizer = $organizer;
    }

    /**
     * Get event cost
     *
     * @return float
     */
    public function get_cost() {
        return $this->cost;
    }

    /**
     * Set event cost
     *
     * @param float $cost
     * @return void
     */
    public function set_cost( $cost ) {
        $this->cost = $cost;
    }

    /**
     * Get formatted event cost
     *
     * @return string
     */
    public function get_formatted_cost() {
        if ( ! $this->cost ) {
            return __( 'Free', 'aqualuxe' );
        }

        if ( function_exists( 'wc_price' ) ) {
            return wc_price( $this->cost );
        }

        return number_format_i18n( $this->cost, 2 );
    }

    /**
     * Get event URL
     *
     * @return string
     */
    public function get_url() {
        return $this->url;
    }

    /**
     * Set event URL
     *
     * @param string $url
     * @return void
     */
    public function set_url( $url ) {
        $this->url = $url;
    }

    /**
     * Get event featured image ID
     *
     * @return int
     */
    public function get_featured_image_id() {
        return $this->featured_image_id;
    }

    /**
     * Set event featured image ID
     *
     * @param int $featured_image_id
     * @return void
     */
    public function set_featured_image_id( $featured_image_id ) {
        $this->featured_image_id = $featured_image_id;
    }

    /**
     * Get event featured image URL
     *
     * @param string $size
     * @return string
     */
    public function get_featured_image_url( $size = 'full' ) {
        if ( ! $this->featured_image_id ) {
            return '';
        }
        $image = wp_get_attachment_image_src( $this->featured_image_id, $size );
        return $image ? $image[0] : '';
    }

    /**
     * Get event capacity
     *
     * @return int
     */
    public function get_capacity() {
        return $this->capacity;
    }

    /**
     * Set event capacity
     *
     * @param int $capacity
     * @return void
     */
    public function set_capacity( $capacity ) {
        $this->capacity = $capacity;
    }

    /**
     * Get event registration status
     *
     * @return string
     */
    public function get_registration_status() {
        return $this->registration_status;
    }

    /**
     * Get event registration status label
     *
     * @return string
     */
    public function get_registration_status_label() {
        $status_labels = [
            'open'      => __( 'Open', 'aqualuxe' ),
            'closed'    => __( 'Closed', 'aqualuxe' ),
            'soldout'   => __( 'Sold Out', 'aqualuxe' ),
            'cancelled' => __( 'Cancelled', 'aqualuxe' ),
        ];

        return isset( $status_labels[ $this->registration_status ] ) ? $status_labels[ $this->registration_status ] : $this->registration_status;
    }

    /**
     * Set event registration status
     *
     * @param string $registration_status
     * @return void
     */
    public function set_registration_status( $registration_status ) {
        $this->registration_status = $registration_status;
    }

    /**
     * Get event permalink
     *
     * @return string
     */
    public function get_permalink() {
        return get_permalink( $this->id );
    }

    /**
     * Get event categories
     *
     * @return array
     */
    public function get_categories() {
        return get_the_terms( $this->id, 'event_category' );
    }

    /**
     * Get event tags
     *
     * @return array
     */
    public function get_tags() {
        return get_the_terms( $this->id, 'event_tag' );
    }

    /**
     * Get event data
     *
     * @return array
     */
    public function get_data() {
        return [
            'id'                  => $this->id,
            'title'               => $this->title,
            'description'         => $this->description,
            'start_date'          => $this->start_date,
            'end_date'            => $this->end_date,
            'venue_id'            => $this->venue_id,
            'venue_name'          => $this->get_venue_name(),
            'venue_address'       => $this->get_venue_full_address(),
            'organizer'           => $this->organizer,
            'cost'                => $this->cost,
            'formatted_cost'      => $this->get_formatted_cost(),
            'url'                 => $this->url,
            'featured_image_id'   => $this->featured_image_id,
            'featured_image_url'  => $this->get_featured_image_url(),
            'capacity'            => $this->capacity,
            'registration_status' => $this->registration_status,
            'status_label'        => $this->get_registration_status_label(),
            'permalink'           => $this->get_permalink(),
            'is_upcoming'         => $this->is_upcoming(),
            'is_ongoing'          => $this->is_ongoing(),
            'is_past'             => $this->is_past(),
            'is_registration_open' => $this->is_registration_open(),
        ];
    }

    /**
     * Get Google Calendar URL
     *
     * @return string
     */
    public function get_google_calendar_url() {
        if ( ! $this->start_date ) {
            return '';
        }

        $start_date = date( 'Ymd\THis\Z', strtotime( $this->start_date ) );
        $end_date = $this->end_date ? date( 'Ymd\THis\Z', strtotime( $this->end_date ) ) : $start_date;

        $params = [
            'action'   => 'TEMPLATE',
            'text'     => $this->title,
            'dates'    => $start_date . '/' . $end_date,
            'details'  => wp_strip_all_tags( $this->description ),
            'location' => $this->get_venue_full_address(),
        ];

        return add_query_arg( $params, 'https://www.google.com/calendar/render' );
    }

    /**
     * Get iCal URL
     *
     * @return string
     */
    public function get_ical_url() {
        return add_query_arg( [
            'action'   => 'aqualuxe_add_to_ical',
            'event_id' => $this->id,
            'nonce'    => wp_create_nonce( 'aqualuxe_add_to_ical_' . $this->id ),
        ], admin_url( 'admin-ajax.php' ) );
    }

    /**
     * Generate iCal file
     *
     * @return string
     */
    public function generate_ical() {
        $start_date = date( 'Ymd\THis\Z', strtotime( $this->start_date ) );
        $end_date = $this->end_date ? date( 'Ymd\THis\Z', strtotime( $this->end_date ) ) : $start_date;

        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//AquaLuxe//Events Calendar//EN\r\n";
        $ical .= "CALSCALE:GREGORIAN\r\n";
        $ical .= "METHOD:PUBLISH\r\n";
        $ical .= "BEGIN:VEVENT\r\n";
        $ical .= "UID:" . md5( $this->id . $this->start_date ) . "@" . $_SERVER['HTTP_HOST'] . "\r\n";
        $ical .= "DTSTAMP:" . date( 'Ymd\THis\Z' ) . "\r\n";
        $ical .= "DTSTART:" . $start_date . "\r\n";
        $ical .= "DTEND:" . $end_date . "\r\n";
        $ical .= "SUMMARY:" . $this->title . "\r\n";
        $ical .= "DESCRIPTION:" . wp_strip_all_tags( $this->description ) . "\r\n";
        $ical .= "URL:" . $this->get_permalink() . "\r\n";
        $ical .= "LOCATION:" . $this->get_venue_full_address() . "\r\n";
        $ical .= "END:VEVENT\r\n";
        $ical .= "END:VCALENDAR\r\n";

        return $ical;
    }

    /**
     * Get schema.org markup
     *
     * @return array
     */
    public function get_schema() {
        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Event',
            'name'        => $this->title,
            'description' => wp_strip_all_tags( $this->description ),
            'startDate'   => date( 'c', strtotime( $this->start_date ) ),
            'url'         => $this->get_permalink(),
        ];

        if ( $this->end_date ) {
            $schema['endDate'] = date( 'c', strtotime( $this->end_date ) );
        }

        if ( $this->venue_id ) {
            $schema['location'] = [
                '@type'   => 'Place',
                'name'    => $this->get_venue_name(),
                'address' => [
                    '@type'           => 'PostalAddress',
                    'streetAddress'   => $this->get_venue_address(),
                    'addressLocality' => $this->get_venue_city(),
                    'addressRegion'   => $this->get_venue_state(),
                    'postalCode'      => $this->get_venue_postal_code(),
                    'addressCountry'  => $this->get_venue_country(),
                ],
            ];
        }

        if ( $this->organizer ) {
            $schema['organizer'] = [
                '@type' => 'Organization',
                'name'  => $this->organizer,
            ];
        }

        if ( $this->cost ) {
            $schema['offers'] = [
                '@type'         => 'Offer',
                'price'         => $this->cost,
                'priceCurrency' => get_option( 'woocommerce_currency', 'USD' ),
                'availability'  => $this->is_registration_open() ? 'https://schema.org/InStock' : 'https://schema.org/SoldOut',
                'url'           => $this->get_permalink(),
                'validFrom'     => date( 'c', strtotime( '-1 month', strtotime( $this->start_date ) ) ),
            ];
        }

        if ( $this->featured_image_id ) {
            $schema['image'] = $this->get_featured_image_url();
        }

        return $schema;
    }

    /**
     * Create a new event
     *
     * @param array $data
     * @return Event|WP_Error
     */
    public static function create( $data ) {
        // Validate required fields
        $required_fields = [ 'title', 'start_date' ];
        foreach ( $required_fields as $field ) {
            if ( ! isset( $data[ $field ] ) || empty( $data[ $field ] ) ) {
                return new \WP_Error( 'missing_field', sprintf( __( 'Missing required field: %s', 'aqualuxe' ), $field ) );
            }
        }

        // Create the event
        $event = new self();
        $event->set_title( $data['title'] );
        $event->set_start_date( $data['start_date'] );

        // Set optional fields
        if ( isset( $data['description'] ) ) {
            $event->set_description( $data['description'] );
        }

        if ( isset( $data['end_date'] ) ) {
            $event->set_end_date( $data['end_date'] );
        }

        if ( isset( $data['venue_id'] ) ) {
            $event->set_venue_id( $data['venue_id'] );
        }

        if ( isset( $data['organizer'] ) ) {
            $event->set_organizer( $data['organizer'] );
        }

        if ( isset( $data['cost'] ) ) {
            $event->set_cost( $data['cost'] );
        }

        if ( isset( $data['url'] ) ) {
            $event->set_url( $data['url'] );
        }

        if ( isset( $data['featured_image_id'] ) ) {
            $event->set_featured_image_id( $data['featured_image_id'] );
        }

        if ( isset( $data['capacity'] ) ) {
            $event->set_capacity( $data['capacity'] );
        }

        if ( isset( $data['registration_status'] ) ) {
            $event->set_registration_status( $data['registration_status'] );
        }

        // Save the event
        if ( ! $event->save() ) {
            return new \WP_Error( 'save_failed', __( 'Failed to save the event.', 'aqualuxe' ) );
        }

        return $event;
    }

    /**
     * Get event by ID
     *
     * @param int $event_id
     * @return Event|false
     */
    public static function get( $event_id ) {
        $event = new self( $event_id );
        return $event->id ? $event : false;
    }

    /**
     * Get events
     *
     * @param array $args
     * @return array
     */
    public static function get_events( $args = [] ) {
        $defaults = [
            'status'      => 'any',
            'orderby'     => 'meta_value',
            'meta_key'    => '_aqualuxe_event_start_date',
            'order'       => 'ASC',
            'limit'       => -1,
            'offset'      => 0,
            'category'    => '',
            'tag'         => '',
            'venue_id'    => 0,
            'start_date'  => '',
            'end_date'    => '',
            'search'      => '',
        ];

        $args = wp_parse_args( $args, $defaults );

        $query_args = [
            'post_type'      => 'aqualuxe_event',
            'post_status'    => $args['status'],
            'posts_per_page' => $args['limit'],
            'offset'         => $args['offset'],
            'orderby'        => $args['orderby'],
            'order'          => $args['order'],
        ];

        if ( 'meta_value' === $args['orderby'] && $args['meta_key'] ) {
            $query_args['meta_key'] = $args['meta_key'];
        }

        $meta_query = [];

        if ( $args['venue_id'] ) {
            $meta_query[] = [
                'key'   => '_aqualuxe_event_venue_id',
                'value' => $args['venue_id'],
            ];
        }

        if ( $args['start_date'] && $args['end_date'] ) {
            $meta_query[] = [
                'relation' => 'OR',
                [
                    'key'     => '_aqualuxe_event_start_date',
                    'value'   => [ $args['start_date'], $args['end_date'] ],
                    'compare' => 'BETWEEN',
                    'type'    => 'DATETIME',
                ],
                [
                    'key'     => '_aqualuxe_event_end_date',
                    'value'   => [ $args['start_date'], $args['end_date'] ],
                    'compare' => 'BETWEEN',
                    'type'    => 'DATETIME',
                ],
                [
                    'relation' => 'AND',
                    [
                        'key'     => '_aqualuxe_event_start_date',
                        'value'   => $args['start_date'],
                        'compare' => '<=',
                        'type'    => 'DATETIME',
                    ],
                    [
                        'key'     => '_aqualuxe_event_end_date',
                        'value'   => $args['end_date'],
                        'compare' => '>=',
                        'type'    => 'DATETIME',
                    ],
                ],
            ];
        } elseif ( $args['start_date'] ) {
            $meta_query[] = [
                'key'     => '_aqualuxe_event_start_date',
                'value'   => $args['start_date'],
                'compare' => '>=',
                'type'    => 'DATETIME',
            ];
        } elseif ( $args['end_date'] ) {
            $meta_query[] = [
                'key'     => '_aqualuxe_event_end_date',
                'value'   => $args['end_date'],
                'compare' => '<=',
                'type'    => 'DATETIME',
            ];
        }

        if ( ! empty( $meta_query ) ) {
            $query_args['meta_query'] = $meta_query;
        }

        $tax_query = [];

        if ( $args['category'] ) {
            $tax_query[] = [
                'taxonomy' => 'event_category',
                'field'    => 'slug',
                'terms'    => $args['category'],
            ];
        }

        if ( $args['tag'] ) {
            $tax_query[] = [
                'taxonomy' => 'event_tag',
                'field'    => 'slug',
                'terms'    => $args['tag'],
            ];
        }

        if ( ! empty( $tax_query ) ) {
            $query_args['tax_query'] = $tax_query;
        }

        if ( $args['search'] ) {
            $query_args['s'] = $args['search'];
        }

        $posts = get_posts( $query_args );
        $events = [];

        foreach ( $posts as $post ) {
            $events[] = new self( $post->ID );
        }

        return $events;
    }

    /**
     * Get upcoming events
     *
     * @param int $limit
     * @return array
     */
    public static function get_upcoming_events( $limit = 10 ) {
        return self::get_events( [
            'start_date' => date( 'Y-m-d H:i:s' ),
            'orderby'    => 'meta_value',
            'meta_key'   => '_aqualuxe_event_start_date',
            'order'      => 'ASC',
            'limit'      => $limit,
        ] );
    }

    /**
     * Get past events
     *
     * @param int $limit
     * @return array
     */
    public static function get_past_events( $limit = 10 ) {
        return self::get_events( [
            'end_date' => date( 'Y-m-d H:i:s' ),
            'orderby'  => 'meta_value',
            'meta_key' => '_aqualuxe_event_start_date',
            'order'    => 'DESC',
            'limit'    => $limit,
        ] );
    }

    /**
     * Get events by venue
     *
     * @param int $venue_id
     * @param int $limit
     * @return array
     */
    public static function get_events_by_venue( $venue_id, $limit = 10 ) {
        return self::get_events( [
            'venue_id' => $venue_id,
            'orderby'  => 'meta_value',
            'meta_key' => '_aqualuxe_event_start_date',
            'order'    => 'ASC',
            'limit'    => $limit,
        ] );
    }

    /**
     * Get events by category
     *
     * @param string $category
     * @param int $limit
     * @return array
     */
    public static function get_events_by_category( $category, $limit = 10 ) {
        return self::get_events( [
            'category' => $category,
            'orderby'  => 'meta_value',
            'meta_key' => '_aqualuxe_event_start_date',
            'order'    => 'ASC',
            'limit'    => $limit,
        ] );
    }

    /**
     * Get events by tag
     *
     * @param string $tag
     * @param int $limit
     * @return array
     */
    public static function get_events_by_tag( $tag, $limit = 10 ) {
        return self::get_events( [
            'tag'      => $tag,
            'orderby'  => 'meta_value',
            'meta_key' => '_aqualuxe_event_start_date',
            'order'    => 'ASC',
            'limit'    => $limit,
        ] );
    }

    /**
     * Search events
     *
     * @param string $search
     * @param int $limit
     * @return array
     */
    public static function search_events( $search, $limit = 10 ) {
        return self::get_events( [
            'search'   => $search,
            'orderby'  => 'meta_value',
            'meta_key' => '_aqualuxe_event_start_date',
            'order'    => 'ASC',
            'limit'    => $limit,
        ] );
    }
}