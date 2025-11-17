<?php
/**
 * Ticket Class
 *
 * @package AquaLuxe\Modules\Events
 */

namespace AquaLuxe\Modules\Events;

/**
 * Ticket Class
 */
class Ticket {
    /**
     * Ticket ID
     *
     * @var int
     */
    private $id = 0;

    /**
     * Event ID
     *
     * @var int
     */
    private $event_id = 0;

    /**
     * Name
     *
     * @var string
     */
    private $name = '';

    /**
     * Description
     *
     * @var string
     */
    private $description = '';

    /**
     * Price
     *
     * @var float
     */
    private $price = 0;

    /**
     * Capacity
     *
     * @var int
     */
    private $capacity = 0;

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
     * Status
     *
     * @var string
     */
    private $status = 'available';

    /**
     * Constructor
     *
     * @param int $ticket_id
     */
    public function __construct($ticket_id = 0) {
        if ($ticket_id > 0) {
            $this->id = $ticket_id;
            $this->load();
        }
    }

    /**
     * Load ticket data
     */
    private function load() {
        // Check if post exists
        $post = get_post($this->id);
        
        if (!$post || $post->post_type !== 'aqualuxe_ticket') {
            return;
        }
        
        // Load ticket data
        $this->event_id = get_post_meta($this->id, '_aqualuxe_ticket_event_id', true);
        $this->name = get_post_meta($this->id, '_aqualuxe_ticket_name', true);
        $this->description = get_post_meta($this->id, '_aqualuxe_ticket_description', true);
        $this->price = get_post_meta($this->id, '_aqualuxe_ticket_price', true);
        $this->capacity = get_post_meta($this->id, '_aqualuxe_ticket_capacity', true);
        $this->start_date = get_post_meta($this->id, '_aqualuxe_ticket_start_date', true);
        $this->end_date = get_post_meta($this->id, '_aqualuxe_ticket_end_date', true);
        $this->status = get_post_meta($this->id, '_aqualuxe_ticket_status', true);
    }

    /**
     * Save ticket
     *
     * @return int
     */
    public function save() {
        // Prepare post data
        $post_data = [
            'post_title' => $this->name,
            'post_status' => 'publish',
            'post_type' => 'aqualuxe_ticket',
        ];
        
        // Update existing ticket
        if ($this->id > 0) {
            $post_data['ID'] = $this->id;
            $ticket_id = wp_update_post($post_data);
        }
        // Create new ticket
        else {
            $ticket_id = wp_insert_post($post_data);
        }
        
        // Check for errors
        if (is_wp_error($ticket_id)) {
            return 0;
        }
        
        // Set ID
        $this->id = $ticket_id;
        
        // Save ticket data
        update_post_meta($this->id, '_aqualuxe_ticket_event_id', $this->event_id);
        update_post_meta($this->id, '_aqualuxe_ticket_name', $this->name);
        update_post_meta($this->id, '_aqualuxe_ticket_description', $this->description);
        update_post_meta($this->id, '_aqualuxe_ticket_price', $this->price);
        update_post_meta($this->id, '_aqualuxe_ticket_capacity', $this->capacity);
        update_post_meta($this->id, '_aqualuxe_ticket_start_date', $this->start_date);
        update_post_meta($this->id, '_aqualuxe_ticket_end_date', $this->end_date);
        update_post_meta($this->id, '_aqualuxe_ticket_status', $this->status);
        
        return $this->id;
    }

    /**
     * Set event ID
     *
     * @param int $event_id
     */
    public function set_event_id($event_id) {
        $this->event_id = absint($event_id);
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function set_name($name) {
        $this->name = $name;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function set_description($description) {
        $this->description = $description;
    }

    /**
     * Set price
     *
     * @param float $price
     */
    public function set_price($price) {
        $this->price = floatval($price);
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
     * Set start date
     *
     * @param string $date
     */
    public function set_start_date($date) {
        $this->start_date = $date;
    }

    /**
     * Set end date
     *
     * @param string $date
     */
    public function set_end_date($date) {
        $this->end_date = $date;
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
     * Get ID
     *
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get event ID
     *
     * @return int
     */
    public function get_event_id() {
        return $this->event_id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function get_price() {
        return $this->price;
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
     * Get status
     *
     * @return string
     */
    public function get_status() {
        return $this->status;
    }

    /**
     * Get formatted price
     *
     * @return string
     */
    public function get_formatted_price() {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['events'] ?? null;
        
        if ($module) {
            return $module->format_price($this->price);
        }
        
        // Get event currency
        $event = new Event($this->event_id);
        $currency = $event->get_currency();
        
        return sprintf(
            get_woocommerce_price_format(),
            get_woocommerce_currency_symbol($currency),
            number_format($this->price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator())
        );
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
     * Get sold count
     *
     * @return int
     */
    public function get_sold_count() {
        global $wpdb;
        
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(meta_value) 
            FROM $wpdb->postmeta pm1 
            JOIN $wpdb->postmeta pm2 ON pm1.post_id = pm2.post_id 
            JOIN $wpdb->posts p ON pm1.post_id = p.ID 
            WHERE pm1.meta_key = '_aqualuxe_registration_quantity' 
            AND pm2.meta_key = '_aqualuxe_registration_ticket_id' 
            AND pm2.meta_value = %d 
            AND p.post_type = 'aqualuxe_registration' 
            AND p.post_status = 'publish'",
            $this->id
        ));
        
        return $count ? absint($count) : 0;
    }

    /**
     * Get available count
     *
     * @return int
     */
    public function get_available_count() {
        if ($this->capacity <= 0) {
            return -1; // Unlimited
        }
        
        $sold = $this->get_sold_count();
        
        return max(0, $this->capacity - $sold);
    }

    /**
     * Is available
     *
     * @return bool
     */
    public function is_available() {
        // Check status
        if ($this->status !== 'available') {
            return false;
        }
        
        // Check capacity
        $available = $this->get_available_count();
        
        if ($available === 0) {
            return false;
        }
        
        // Check dates
        $now = current_time('Y-m-d');
        
        // Check start date
        if ($this->start_date && $now < $this->start_date) {
            return false;
        }
        
        // Check end date
        if ($this->end_date && $now > $this->end_date) {
            return false;
        }
        
        // Check event
        $event = new Event($this->event_id);
        
        if (!$event->exists()) {
            return false;
        }
        
        // Check if event registration is open
        if (!$event->is_registration_open()) {
            return false;
        }
        
        return true;
    }

    /**
     * Has capacity
     *
     * @param int $quantity
     * @return bool
     */
    public function has_capacity($quantity) {
        $available = $this->get_available_count();
        
        if ($available === -1) {
            return true; // Unlimited
        }
        
        return $quantity <= $available;
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
                    'key' => '_aqualuxe_registration_ticket_id',
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
     * Check if ticket exists
     *
     * @return bool
     */
    public function exists() {
        return $this->id > 0 && get_post_type($this->id) === 'aqualuxe_ticket';
    }
}