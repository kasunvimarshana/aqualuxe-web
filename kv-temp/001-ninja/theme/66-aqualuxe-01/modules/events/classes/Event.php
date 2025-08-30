<?php
/**
 * Event Class
 *
 * @package AquaLuxe\Modules\Events
 */

namespace AquaLuxe\Modules\Events;

/**
 * Event Class
 */
class Event {
    /**
     * Event ID
     *
     * @var int
     */
    private $id = 0;

    /**
     * Start date
     *
     * @var string
     */
    private $start_date = '';

    /**
     * End date
     *
     * @var string
     */
    private $end_date = '';

    /**
     * Start time
     *
     * @var string
     */
    private $start_time = '';

    /**
     * End time
     *
     * @var string
     */
    private $end_time = '';

    /**
     * Status
     *
     * @var string
     */
    private $status = 'published';

    /**
     * Capacity
     *
     * @var int
     */
    private $capacity = 0;

    /**
     * Registration status
     *
     * @var string
     */
    private $registration_status = 'open';

    /**
     * Registration start date
     *
     * @var string
     */
    private $registration_start_date = '';

    /**
     * Registration end date
     *
     * @var string
     */
    private $registration_end_date = '';

    /**
     * Featured
     *
     * @var bool
     */
    private $featured = false;

    /**
     * Cost
     *
     * @var float
     */
    private $cost = 0;

    /**
     * Cost description
     *
     * @var string
     */
    private $cost_description = '';

    /**
     * Currency
     *
     * @var string
     */
    private $currency = '';

    /**
     * Website
     *
     * @var string
     */
    private $website = '';

    /**
     * Phone
     *
     * @var string
     */
    private $phone = '';

    /**
     * Email
     *
     * @var string
     */
    private $email = '';

    /**
     * Venue data
     *
     * @var array
     */
    private $venue_data = [];

    /**
     * Organizer data
     *
     * @var array
     */
    private $organizer_data = [];

    /**
     * Constructor
     *
     * @param int $event_id
     */
    public function __construct($event_id = 0) {
        if ($event_id > 0) {
            $this->id = $event_id;
            $this->load();
        }
    }

    /**
     * Load event data
     */
    private function load() {
        // Check if post exists
        $post = get_post($this->id);
        
        if (!$post || $post->post_type !== 'aqualuxe_event') {
            return;
        }
        
        // Load event data
        $this->start_date = get_post_meta($this->id, '_aqualuxe_event_start_date', true);
        $this->end_date = get_post_meta($this->id, '_aqualuxe_event_end_date', true);
        $this->start_time = get_post_meta($this->id, '_aqualuxe_event_start_time', true);
        $this->end_time = get_post_meta($this->id, '_aqualuxe_event_end_time', true);
        $this->status = get_post_meta($this->id, '_aqualuxe_event_status', true);
        $this->capacity = get_post_meta($this->id, '_aqualuxe_event_capacity', true);
        $this->registration_status = get_post_meta($this->id, '_aqualuxe_event_registration_status', true);
        $this->registration_start_date = get_post_meta($this->id, '_aqualuxe_event_registration_start_date', true);
        $this->registration_end_date = get_post_meta($this->id, '_aqualuxe_event_registration_end_date', true);
        $this->featured = get_post_meta($this->id, '_aqualuxe_event_featured', true);
        $this->cost = get_post_meta($this->id, '_aqualuxe_event_cost', true);
        $this->cost_description = get_post_meta($this->id, '_aqualuxe_event_cost_description', true);
        $this->currency = get_post_meta($this->id, '_aqualuxe_event_currency', true);
        $this->website = get_post_meta($this->id, '_aqualuxe_event_website', true);
        $this->phone = get_post_meta($this->id, '_aqualuxe_event_phone', true);
        $this->email = get_post_meta($this->id, '_aqualuxe_event_email', true);
        
        // Load venue data
        $venue_name = get_post_meta($this->id, '_aqualuxe_event_venue_name', true);
        
        if ($venue_name) {
            $this->venue_data = [
                'name' => $venue_name,
                'address' => get_post_meta($this->id, '_aqualuxe_event_venue_address', true),
                'city' => get_post_meta($this->id, '_aqualuxe_event_venue_city', true),
                'state' => get_post_meta($this->id, '_aqualuxe_event_venue_state', true),
                'zip' => get_post_meta($this->id, '_aqualuxe_event_venue_zip', true),
                'country' => get_post_meta($this->id, '_aqualuxe_event_venue_country', true),
                'latitude' => get_post_meta($this->id, '_aqualuxe_event_venue_latitude', true),
                'longitude' => get_post_meta($this->id, '_aqualuxe_event_venue_longitude', true),
                'website' => get_post_meta($this->id, '_aqualuxe_event_venue_website', true),
                'phone' => get_post_meta($this->id, '_aqualuxe_event_venue_phone', true),
            ];
        }
        
        // Load organizer data
        $organizer_name = get_post_meta($this->id, '_aqualuxe_event_organizer_name', true);
        
        if ($organizer_name) {
            $this->organizer_data = [
                'name' => $organizer_name,
                'description' => get_post_meta($this->id, '_aqualuxe_event_organizer_description', true),
                'website' => get_post_meta($this->id, '_aqualuxe_event_organizer_website', true),
                'phone' => get_post_meta($this->id, '_aqualuxe_event_organizer_phone', true),
                'email' => get_post_meta($this->id, '_aqualuxe_event_organizer_email', true),
            ];
        }
    }

    /**
     * Save event
     *
     * @return int
     */
    public function save() {
        // Prepare post data
        $post_data = [
            'post_title' => $this->id ? get_the_title($this->id) : '',
            'post_status' => 'publish',
            'post_type' => 'aqualuxe_event',
        ];
        
        // Update existing event
        if ($this->id > 0) {
            $post_data['ID'] = $this->id;
            $event_id = wp_update_post($post_data);
        }
        // Create new event
        else {
            $event_id = wp_insert_post($post_data);
        }
        
        // Check for errors
        if (is_wp_error($event_id)) {
            return 0;
        }
        
        // Set ID
        $this->id = $event_id;
        
        // Save event data
        update_post_meta($this->id, '_aqualuxe_event_start_date', $this->start_date);
        update_post_meta($this->id, '_aqualuxe_event_end_date', $this->end_date);
        update_post_meta($this->id, '_aqualuxe_event_start_time', $this->start_time);
        update_post_meta($this->id, '_aqualuxe_event_end_time', $this->end_time);
        update_post_meta($this->id, '_aqualuxe_event_status', $this->status);
        update_post_meta($this->id, '_aqualuxe_event_capacity', $this->capacity);
        update_post_meta($this->id, '_aqualuxe_event_registration_status', $this->registration_status);
        update_post_meta($this->id, '_aqualuxe_event_registration_start_date', $this->registration_start_date);
        update_post_meta($this->id, '_aqualuxe_event_registration_end_date', $this->registration_end_date);
        update_post_meta($this->id, '_aqualuxe_event_featured', $this->featured);
        update_post_meta($this->id, '_aqualuxe_event_cost', $this->cost);
        update_post_meta($this->id, '_aqualuxe_event_cost_description', $this->cost_description);
        update_post_meta($this->id, '_aqualuxe_event_currency', $this->currency);
        update_post_meta($this->id, '_aqualuxe_event_website', $this->website);
        update_post_meta($this->id, '_aqualuxe_event_phone', $this->phone);
        update_post_meta($this->id, '_aqualuxe_event_email', $this->email);
        
        // Save venue data
        if (!empty($this->venue_data)) {
            update_post_meta($this->id, '_aqualuxe_event_venue_name', $this->venue_data['name'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_venue_address', $this->venue_data['address'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_venue_city', $this->venue_data['city'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_venue_state', $this->venue_data['state'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_venue_zip', $this->venue_data['zip'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_venue_country', $this->venue_data['country'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_venue_latitude', $this->venue_data['latitude'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_venue_longitude', $this->venue_data['longitude'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_venue_website', $this->venue_data['website'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_venue_phone', $this->venue_data['phone'] ?? '');
        }
        
        // Save organizer data
        if (!empty($this->organizer_data)) {
            update_post_meta($this->id, '_aqualuxe_event_organizer_name', $this->organizer_data['name'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_organizer_description', $this->organizer_data['description'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_organizer_website', $this->organizer_data['website'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_organizer_phone', $this->organizer_data['phone'] ?? '');
            update_post_meta($this->id, '_aqualuxe_event_organizer_email', $this->organizer_data['email'] ?? '');
        }
        
        return $this->id;
    }

    /**
     * Set dates
     *
     * @param string $start_date
     * @param string $end_date
     */
    public function set_dates($start_date, $end_date = '') {
        $this->start_date = $start_date;
        $this->end_date = $end_date ? $end_date : $start_date;
    }

    /**
     * Set times
     *
     * @param string $start_time
     * @param string $end_time
     */
    public function set_times($start_time, $end_time = '') {
        $this->start_time = $start_time;
        $this->end_time = $end_time ? $end_time : $start_time;
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function set_status($status) {
        $this->status = $status;
    }

    /**
     * Set capacity
     *
     * @param int $capacity
     */
    public function set_capacity($capacity) {
        $this->capacity = max(0, absint($capacity));
    }

    /**
     * Set registration status
     *
     * @param string $status
     */
    public function set_registration_status($status) {
        $this->registration_status = $status;
    }

    /**
     * Set registration start date
     *
     * @param string $date
     */
    public function set_registration_start_date($date) {
        $this->registration_start_date = $date;
    }

    /**
     * Set registration end date
     *
     * @param string $date
     */
    public function set_registration_end_date($date) {
        $this->registration_end_date = $date;
    }

    /**
     * Set featured
     *
     * @param bool $featured
     */
    public function set_featured($featured) {
        $this->featured = (bool) $featured;
    }

    /**
     * Set cost
     *
     * @param float $cost
     */
    public function set_cost($cost) {
        $this->cost = floatval($cost);
    }

    /**
     * Set cost description
     *
     * @param string $description
     */
    public function set_cost_description($description) {
        $this->cost_description = $description;
    }

    /**
     * Set currency
     *
     * @param string $currency
     */
    public function set_currency($currency) {
        $this->currency = $currency;
    }

    /**
     * Set website
     *
     * @param string $website
     */
    public function set_website($website) {
        $this->website = $website;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function set_phone($phone) {
        $this->phone = $phone;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function set_email($email) {
        $this->email = $email;
    }

    /**
     * Set venue data
     *
     * @param array $data
     */
    public function set_venue_data($data) {
        $this->venue_data = array_merge($this->venue_data, $data);
    }

    /**
     * Set organizer data
     *
     * @param array $data
     */
    public function set_organizer_data($data) {
        $this->organizer_data = array_merge($this->organizer_data, $data);
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function get_title() {
        return get_the_title($this->id);
    }

    /**
     * Get description
     *
     * @return string
     */
    public function get_description() {
        $post = get_post($this->id);
        return $post ? $post->post_content : '';
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function get_excerpt() {
        $post = get_post($this->id);
        
        if (!$post) {
            return '';
        }
        
        if (!empty($post->post_excerpt)) {
            return $post->post_excerpt;
        }
        
        return wp_trim_words($post->post_content, 55);
    }

    /**
     * Get start date
     *
     * @return string
     */
    public function get_start_date() {
        return $this->start_date;
    }

    /**
     * Get end date
     *
     * @return string
     */
    public function get_end_date() {
        return $this->end_date;
    }

    /**
     * Get start time
     *
     * @return string
     */
    public function get_start_time() {
        return $this->start_time;
    }

    /**
     * Get end time
     *
     * @return string
     */
    public function get_end_time() {
        return $this->end_time;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function get_status() {
        return $this->status;
    }

    /**
     * Get capacity
     *
     * @return int
     */
    public function get_capacity() {
        return $this->capacity;
    }

    /**
     * Get registration status
     *
     * @return string
     */
    public function get_registration_status() {
        return $this->registration_status;
    }

    /**
     * Get registration start date
     *
     * @return string
     */
    public function get_registration_start_date() {
        return $this->registration_start_date;
    }

    /**
     * Get registration end date
     *
     * @return string
     */
    public function get_registration_end_date() {
        return $this->registration_end_date;
    }

    /**
     * Get featured
     *
     * @return bool
     */
    public function get_featured() {
        return $this->featured;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function get_cost() {
        return $this->cost;
    }

    /**
     * Get cost description
     *
     * @return string
     */
    public function get_cost_description() {
        return $this->cost_description;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function get_currency() {
        if (empty($this->currency)) {
            return get_woocommerce_currency();
        }
        
        return $this->currency;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function get_website() {
        return $this->website;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function get_phone() {
        return $this->phone;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function get_email() {
        return $this->email;
    }

    /**
     * Get venue data
     *
     * @return array
     */
    public function get_venue_data() {
        return $this->venue_data;
    }

    /**
     * Get organizer data
     *
     * @return array
     */
    public function get_organizer_data() {
        return $this->organizer_data;
    }

    /**
     * Get formatted start date
     *
     * @param string $format
     * @return string
     */
    public function get_formatted_start_date($format = '') {
        if (!$format) {
            // Get module instance
            $theme = \AquaLuxe\Theme::get_instance();
            $module = $theme->get_active_modules()['events'] ?? null;
            
            if ($module) {
                $format = $module->get_setting('date_format', 'F j, Y');
            } else {
                $format = get_option('date_format');
            }
        }
        
        return date_i18n($format, strtotime($this->start_date));
    }

    /**
     * Get formatted end date
     *
     * @param string $format
     * @return string
     */
    public function get_formatted_end_date($format = '') {
        if (!$format) {
            // Get module instance
            $theme = \AquaLuxe\Theme::get_instance();
            $module = $theme->get_active_modules()['events'] ?? null;
            
            if ($module) {
                $format = $module->get_setting('date_format', 'F j, Y');
            } else {
                $format = get_option('date_format');
            }
        }
        
        return date_i18n($format, strtotime($this->end_date));
    }

    /**
     * Get formatted start time
     *
     * @param string $format
     * @return string
     */
    public function get_formatted_start_time($format = '') {
        if (!$format) {
            // Get module instance
            $theme = \AquaLuxe\Theme::get_instance();
            $module = $theme->get_active_modules()['events'] ?? null;
            
            if ($module) {
                $format = $module->get_setting('time_format', 'g:i a');
            } else {
                $format = get_option('time_format');
            }
        }
        
        return date_i18n($format, strtotime($this->start_date . ' ' . $this->start_time));
    }

    /**
     * Get formatted end time
     *
     * @param string $format
     * @return string
     */
    public function get_formatted_end_time($format = '') {
        if (!$format) {
            // Get module instance
            $theme = \AquaLuxe\Theme::get_instance();
            $module = $theme->get_active_modules()['events'] ?? null;
            
            if ($module) {
                $format = $module->get_setting('time_format', 'g:i a');
            } else {
                $format = get_option('time_format');
            }
        }
        
        return date_i18n($format, strtotime($this->end_date . ' ' . $this->end_time));
    }

    /**
     * Get formatted cost
     *
     * @return string
     */
    public function get_formatted_cost() {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['events'] ?? null;
        
        if ($module) {
            return $module->format_price($this->cost);
        }
        
        return sprintf(
            get_woocommerce_price_format(),
            get_woocommerce_currency_symbol($this->get_currency()),
            number_format($this->cost, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator())
        );
    }

    /**
     * Get permalink
     *
     * @return string
     */
    public function get_permalink() {
        return get_permalink($this->id);
    }

    /**
     * Get image URL
     *
     * @param string $size
     * @return string
     */
    public function get_image_url($size = 'full') {
        if (has_post_thumbnail($this->id)) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($this->id), $size);
            return $image[0];
        }
        
        return '';
    }

    /**
     * Get tickets
     *
     * @return array
     */
    public function get_tickets() {
        $args = [
            'post_type' => 'aqualuxe_ticket',
            'posts_per_page' => -1,
            'meta_key' => '_aqualuxe_ticket_price',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => '_aqualuxe_ticket_event_id',
                    'value' => $this->id,
                    'compare' => '=',
                ],
            ],
        ];
        
        $query = new \WP_Query($args);
        $tickets = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $tickets[] = new Ticket(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $tickets;
    }

    /**
     * Get registrations
     *
     * @return array
     */
    public function get_registrations() {
        $args = [
            'post_type' => 'aqualuxe_registration',
            'posts_per_page' => -1,
            'meta_key' => '_aqualuxe_registration_date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_query' => [
                [
                    'key' => '_aqualuxe_registration_event_id',
                    'value' => $this->id,
                    'compare' => '=',
                ],
            ],
        ];
        
        $query = new \WP_Query($args);
        $registrations = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $registrations[] = new Registration(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $registrations;
    }

    /**
     * Get registered count
     *
     * @return int
     */
    public function get_registered_count() {
        global $wpdb;
        
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(meta_value) 
            FROM $wpdb->postmeta pm1 
            JOIN $wpdb->postmeta pm2 ON pm1.post_id = pm2.post_id 
            JOIN $wpdb->posts p ON pm1.post_id = p.ID 
            WHERE pm1.meta_key = '_aqualuxe_registration_quantity' 
            AND pm2.meta_key = '_aqualuxe_registration_event_id' 
            AND pm2.meta_value = %d 
            AND p.post_type = 'aqualuxe_registration' 
            AND p.post_status = 'publish'",
            $this->id
        ));
        
        return $count ? absint($count) : 0;
    }

    /**
     * Get available capacity
     *
     * @return int
     */
    public function get_available_capacity() {
        if ($this->capacity <= 0) {
            return -1; // Unlimited
        }
        
        $registered = $this->get_registered_count();
        
        return max(0, $this->capacity - $registered);
    }

    /**
     * Is registration open
     *
     * @return bool
     */
    public function is_registration_open() {
        // Check registration status
        if ($this->registration_status !== 'open') {
            return false;
        }
        
        // Check capacity
        $available_capacity = $this->get_available_capacity();
        
        if ($available_capacity === 0) {
            return false;
        }
        
        // Check registration dates
        $now = current_time('Y-m-d');
        
        // Check registration start date
        if ($this->registration_start_date && $now < $this->registration_start_date) {
            return false;
        }
        
        // Check registration end date
        if ($this->registration_end_date && $now > $this->registration_end_date) {
            return false;
        }
        
        // Check event date
        if ($now > $this->end_date) {
            return false;
        }
        
        return true;
    }

    /**
     * Is past event
     *
     * @return bool
     */
    public function is_past_event() {
        $now = current_time('Y-m-d');
        return $now > $this->end_date;
    }

    /**
     * Is upcoming event
     *
     * @return bool
     */
    public function is_upcoming_event() {
        $now = current_time('Y-m-d');
        return $now <= $this->start_date;
    }

    /**
     * Is ongoing event
     *
     * @return bool
     */
    public function is_ongoing_event() {
        $now = current_time('Y-m-d');
        return $now >= $this->start_date && $now <= $this->end_date;
    }

    /**
     * Get related events
     *
     * @param int $limit
     * @return array
     */
    public function get_related_events($limit = 3) {
        // Get event categories
        $categories = wp_get_post_terms($this->id, 'aqualuxe_event_category', ['fields' => 'ids']);
        
        // Get related events
        $args = [
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => $limit,
            'post__not_in' => [$this->id],
            'meta_key' => '_aqualuxe_event_start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => '_aqualuxe_event_start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ],
            ],
        ];
        
        // Add category filter if available
        if (!empty($categories)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'aqualuxe_event_category',
                    'field' => 'term_id',
                    'terms' => $categories,
                ],
            ];
        }
        
        $query = new \WP_Query($args);
        $events = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $events[] = new Event(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $events;
    }

    /**
     * Check if event exists
     *
     * @return bool
     */
    public function exists() {
        return $this->id > 0 && get_post_type($this->id) === 'aqualuxe_event';
    }
}