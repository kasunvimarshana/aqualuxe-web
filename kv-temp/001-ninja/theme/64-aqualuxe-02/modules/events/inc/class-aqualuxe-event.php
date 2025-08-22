<?php
/**
 * Event Class
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe_Event class.
 */
class AquaLuxe_Event {

    /**
     * Event ID.
     *
     * @var int
     */
    public $id = 0;

    /**
     * Event post object.
     *
     * @var WP_Post
     */
    public $post = null;

    /**
     * Event data.
     *
     * @var array
     */
    protected $data = array(
        'title'             => '',
        'slug'              => '',
        'status'            => 'publish',
        'description'       => '',
        'short_description' => '',
        'start_date'        => '',
        'end_date'          => '',
        'start_time'        => '',
        'end_time'          => '',
        'all_day'           => false,
        'recurring'         => false,
        'recurrence_pattern' => '',
        'venue'             => '',
        'address'           => '',
        'city'              => '',
        'state'             => '',
        'zip'               => '',
        'country'           => '',
        'latitude'          => '',
        'longitude'         => '',
        'cost'              => '',
        'tickets_available' => true,
        'max_tickets'       => 0,
        'tickets_sold'      => 0,
        'featured_image'    => 0,
        'organizer'         => '',
        'organizer_phone'   => '',
        'organizer_email'   => '',
        'organizer_website' => '',
        'categories'        => array(),
        'tags'              => array(),
    );

    /**
     * Constructor.
     *
     * @param int|AquaLuxe_Event|WP_Post $event Event ID, post object, or event object.
     */
    public function __construct($event = 0) {
        if (is_numeric($event) && $event > 0) {
            $this->id = $event;
        } elseif ($event instanceof AquaLuxe_Event) {
            $this->id = $event->id;
        } elseif ($event instanceof WP_Post || (is_object($event) && isset($event->ID))) {
            $this->id = $event->ID;
            $this->post = $event;
        }

        if ($this->id > 0) {
            $this->load_event_data();
        }
    }

    /**
     * Load event data.
     */
    protected function load_event_data() {
        if (!$this->post) {
            $this->post = get_post($this->id);
        }

        if (!$this->post || $this->post->post_type !== 'aqualuxe_event') {
            return;
        }

        // Set basic data
        $this->data['title'] = $this->post->post_title;
        $this->data['slug'] = $this->post->post_name;
        $this->data['status'] = $this->post->post_status;
        $this->data['description'] = $this->post->post_content;
        $this->data['short_description'] = $this->post->post_excerpt;
        $this->data['featured_image'] = get_post_thumbnail_id($this->id);

        // Load meta data
        $meta_data = get_post_meta($this->id);
        
        // Event dates and times
        $this->data['start_date'] = isset($meta_data['_event_start_date'][0]) ? $meta_data['_event_start_date'][0] : '';
        $this->data['end_date'] = isset($meta_data['_event_end_date'][0]) ? $meta_data['_event_end_date'][0] : '';
        $this->data['start_time'] = isset($meta_data['_event_start_time'][0]) ? $meta_data['_event_start_time'][0] : '';
        $this->data['end_time'] = isset($meta_data['_event_end_time'][0]) ? $meta_data['_event_end_time'][0] : '';
        $this->data['all_day'] = isset($meta_data['_event_all_day'][0]) ? (bool) $meta_data['_event_all_day'][0] : false;
        
        // Recurrence
        $this->data['recurring'] = isset($meta_data['_event_recurring'][0]) ? (bool) $meta_data['_event_recurring'][0] : false;
        $this->data['recurrence_pattern'] = isset($meta_data['_event_recurrence_pattern'][0]) ? $meta_data['_event_recurrence_pattern'][0] : '';
        
        // Location
        $this->data['venue'] = isset($meta_data['_event_venue'][0]) ? $meta_data['_event_venue'][0] : '';
        $this->data['address'] = isset($meta_data['_event_address'][0]) ? $meta_data['_event_address'][0] : '';
        $this->data['city'] = isset($meta_data['_event_city'][0]) ? $meta_data['_event_city'][0] : '';
        $this->data['state'] = isset($meta_data['_event_state'][0]) ? $meta_data['_event_state'][0] : '';
        $this->data['zip'] = isset($meta_data['_event_zip'][0]) ? $meta_data['_event_zip'][0] : '';
        $this->data['country'] = isset($meta_data['_event_country'][0]) ? $meta_data['_event_country'][0] : '';
        $this->data['latitude'] = isset($meta_data['_event_latitude'][0]) ? $meta_data['_event_latitude'][0] : '';
        $this->data['longitude'] = isset($meta_data['_event_longitude'][0]) ? $meta_data['_event_longitude'][0] : '';
        
        // Tickets
        $this->data['cost'] = isset($meta_data['_event_cost'][0]) ? $meta_data['_event_cost'][0] : '';
        $this->data['tickets_available'] = isset($meta_data['_event_tickets_available'][0]) ? (bool) $meta_data['_event_tickets_available'][0] : true;
        $this->data['max_tickets'] = isset($meta_data['_event_max_tickets'][0]) ? intval($meta_data['_event_max_tickets'][0]) : 0;
        $this->data['tickets_sold'] = isset($meta_data['_event_tickets_sold'][0]) ? intval($meta_data['_event_tickets_sold'][0]) : 0;
        
        // Organizer
        $this->data['organizer'] = isset($meta_data['_event_organizer'][0]) ? $meta_data['_event_organizer'][0] : '';
        $this->data['organizer_phone'] = isset($meta_data['_event_organizer_phone'][0]) ? $meta_data['_event_organizer_phone'][0] : '';
        $this->data['organizer_email'] = isset($meta_data['_event_organizer_email'][0]) ? $meta_data['_event_organizer_email'][0] : '';
        $this->data['organizer_website'] = isset($meta_data['_event_organizer_website'][0]) ? $meta_data['_event_organizer_website'][0] : '';
        
        // Categories and tags
        $categories = wp_get_post_terms($this->id, 'aqualuxe_event_category', array('fields' => 'ids'));
        $this->data['categories'] = is_wp_error($categories) ? array() : $categories;
        
        $tags = wp_get_post_terms($this->id, 'aqualuxe_event_tag', array('fields' => 'ids'));
        $this->data['tags'] = is_wp_error($tags) ? array() : $tags;
    }

    /**
     * Get event data.
     *
     * @param string $key Data key.
     * @return mixed
     */
    public function get_data($key) {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Set event data.
     *
     * @param string $key Data key.
     * @param mixed $value Data value.
     */
    public function set_data($key, $value) {
        if (array_key_exists($key, $this->data)) {
            $this->data[$key] = $value;
        }
    }

    /**
     * Save event data.
     *
     * @return int Event ID.
     */
    public function save() {
        $post_data = array(
            'post_title'   => $this->data['title'],
            'post_content' => $this->data['description'],
            'post_excerpt' => $this->data['short_description'],
            'post_status'  => $this->data['status'],
            'post_type'    => 'aqualuxe_event',
        );

        if ($this->id > 0) {
            $post_data['ID'] = $this->id;
            wp_update_post($post_data);
        } else {
            $this->id = wp_insert_post($post_data);
        }

        // Save meta data
        if ($this->id > 0) {
            // Event dates and times
            update_post_meta($this->id, '_event_start_date', $this->data['start_date']);
            update_post_meta($this->id, '_event_end_date', $this->data['end_date']);
            update_post_meta($this->id, '_event_start_time', $this->data['start_time']);
            update_post_meta($this->id, '_event_end_time', $this->data['end_time']);
            update_post_meta($this->id, '_event_all_day', $this->data['all_day']);
            
            // Recurrence
            update_post_meta($this->id, '_event_recurring', $this->data['recurring']);
            update_post_meta($this->id, '_event_recurrence_pattern', $this->data['recurrence_pattern']);
            
            // Location
            update_post_meta($this->id, '_event_venue', $this->data['venue']);
            update_post_meta($this->id, '_event_address', $this->data['address']);
            update_post_meta($this->id, '_event_city', $this->data['city']);
            update_post_meta($this->id, '_event_state', $this->data['state']);
            update_post_meta($this->id, '_event_zip', $this->data['zip']);
            update_post_meta($this->id, '_event_country', $this->data['country']);
            update_post_meta($this->id, '_event_latitude', $this->data['latitude']);
            update_post_meta($this->id, '_event_longitude', $this->data['longitude']);
            
            // Tickets
            update_post_meta($this->id, '_event_cost', $this->data['cost']);
            update_post_meta($this->id, '_event_tickets_available', $this->data['tickets_available']);
            update_post_meta($this->id, '_event_max_tickets', $this->data['max_tickets']);
            update_post_meta($this->id, '_event_tickets_sold', $this->data['tickets_sold']);
            
            // Organizer
            update_post_meta($this->id, '_event_organizer', $this->data['organizer']);
            update_post_meta($this->id, '_event_organizer_phone', $this->data['organizer_phone']);
            update_post_meta($this->id, '_event_organizer_email', $this->data['organizer_email']);
            update_post_meta($this->id, '_event_organizer_website', $this->data['organizer_website']);
            
            // Featured image
            if ($this->data['featured_image'] > 0) {
                set_post_thumbnail($this->id, $this->data['featured_image']);
            }
            
            // Categories and tags
            if (!empty($this->data['categories'])) {
                wp_set_object_terms($this->id, $this->data['categories'], 'aqualuxe_event_category');
            }
            
            if (!empty($this->data['tags'])) {
                wp_set_object_terms($this->id, $this->data['tags'], 'aqualuxe_event_tag');
            }
        }

        return $this->id;
    }

    /**
     * Get event permalink.
     *
     * @return string
     */
    public function get_permalink() {
        return get_permalink($this->id);
    }

    /**
     * Get event title.
     *
     * @return string
     */
    public function get_title() {
        return $this->data['title'];
    }

    /**
     * Get event description.
     *
     * @return string
     */
    public function get_description() {
        return $this->data['description'];
    }

    /**
     * Get event short description.
     *
     * @return string
     */
    public function get_short_description() {
        return $this->data['short_description'];
    }

    /**
     * Get event start date.
     *
     * @param string $format Date format.
     * @return string
     */
    public function get_start_date($format = '') {
        if (empty($format)) {
            $format = get_option('date_format');
        }
        
        return !empty($this->data['start_date']) ? date_i18n($format, strtotime($this->data['start_date'])) : '';
    }

    /**
     * Get event end date.
     *
     * @param string $format Date format.
     * @return string
     */
    public function get_end_date($format = '') {
        if (empty($format)) {
            $format = get_option('date_format');
        }
        
        return !empty($this->data['end_date']) ? date_i18n($format, strtotime($this->data['end_date'])) : '';
    }

    /**
     * Get event start time.
     *
     * @param string $format Time format.
     * @return string
     */
    public function get_start_time($format = '') {
        if (empty($format)) {
            $format = get_option('time_format');
        }
        
        return !empty($this->data['start_time']) ? date_i18n($format, strtotime($this->data['start_time'])) : '';
    }

    /**
     * Get event end time.
     *
     * @param string $format Time format.
     * @return string
     */
    public function get_end_time($format = '') {
        if (empty($format)) {
            $format = get_option('time_format');
        }
        
        return !empty($this->data['end_time']) ? date_i18n($format, strtotime($this->data['end_time'])) : '';
    }

    /**
     * Check if event is all day.
     *
     * @return bool
     */
    public function is_all_day() {
        return (bool) $this->data['all_day'];
    }

    /**
     * Check if event is recurring.
     *
     * @return bool
     */
    public function is_recurring() {
        return (bool) $this->data['recurring'];
    }

    /**
     * Get event recurrence pattern.
     *
     * @return string
     */
    public function get_recurrence_pattern() {
        return $this->data['recurrence_pattern'];
    }

    /**
     * Get event venue.
     *
     * @return string
     */
    public function get_venue() {
        return $this->data['venue'];
    }

    /**
     * Get event full address.
     *
     * @return string
     */
    public function get_full_address() {
        $address_parts = array(
            $this->data['address'],
            $this->data['city'],
            $this->data['state'],
            $this->data['zip'],
            $this->data['country'],
        );
        
        $address_parts = array_filter($address_parts);
        
        return implode(', ', $address_parts);
    }

    /**
     * Get event cost.
     *
     * @param bool $formatted Whether to format the cost.
     * @return string
     */
    public function get_cost($formatted = true) {
        if (empty($this->data['cost'])) {
            return __('Free', 'aqualuxe');
        }
        
        if ($formatted) {
            return sprintf(
                get_woocommerce_price_format(),
                get_woocommerce_currency_symbol(),
                $this->data['cost']
            );
        }
        
        return $this->data['cost'];
    }

    /**
     * Check if tickets are available.
     *
     * @return bool
     */
    public function has_tickets_available() {
        if (!$this->data['tickets_available']) {
            return false;
        }
        
        if ($this->data['max_tickets'] > 0 && $this->data['tickets_sold'] >= $this->data['max_tickets']) {
            return false;
        }
        
        return true;
    }

    /**
     * Get tickets remaining.
     *
     * @return int
     */
    public function get_tickets_remaining() {
        if (!$this->data['tickets_available']) {
            return 0;
        }
        
        if ($this->data['max_tickets'] > 0) {
            return max(0, $this->data['max_tickets'] - $this->data['tickets_sold']);
        }
        
        return -1; // Unlimited
    }

    /**
     * Get event organizer.
     *
     * @return string
     */
    public function get_organizer() {
        return $this->data['organizer'];
    }

    /**
     * Get event organizer contact info.
     *
     * @return array
     */
    public function get_organizer_contact_info() {
        return array(
            'name'    => $this->data['organizer'],
            'phone'   => $this->data['organizer_phone'],
            'email'   => $this->data['organizer_email'],
            'website' => $this->data['organizer_website'],
        );
    }

    /**
     * Get event categories.
     *
     * @return array
     */
    public function get_categories() {
        return $this->data['categories'];
    }

    /**
     * Get event tags.
     *
     * @return array
     */
    public function get_tags() {
        return $this->data['tags'];
    }

    /**
     * Get event featured image URL.
     *
     * @param string $size Image size.
     * @return string
     */
    public function get_featured_image_url($size = 'full') {
        if ($this->data['featured_image'] > 0) {
            $image = wp_get_attachment_image_src($this->data['featured_image'], $size);
            if ($image) {
                return $image[0];
            }
        }
        
        return '';
    }

    /**
     * Get event tickets.
     *
     * @return array
     */
    public function get_tickets() {
        $tickets = array();
        
        $ticket_posts = get_posts(array(
            'post_type'      => 'aqualuxe_ticket',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'   => '_ticket_event_id',
                    'value' => $this->id,
                ),
            ),
        ));
        
        foreach ($ticket_posts as $ticket_post) {
            $tickets[] = new AquaLuxe_Event_Ticket($ticket_post);
        }
        
        return $tickets;
    }

    /**
     * Get related events.
     *
     * @param int $limit Number of events to get.
     * @return array
     */
    public function get_related_events($limit = 3) {
        $related_events = array();
        
        // Get events in the same categories
        $args = array(
            'post_type'      => 'aqualuxe_event',
            'posts_per_page' => $limit,
            'post__not_in'   => array($this->id),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'aqualuxe_event_category',
                    'field'    => 'term_id',
                    'terms'    => $this->data['categories'],
                ),
            ),
        );
        
        $related_posts = get_posts($args);
        
        foreach ($related_posts as $related_post) {
            $related_events[] = new AquaLuxe_Event($related_post);
        }
        
        return $related_events;
    }
}