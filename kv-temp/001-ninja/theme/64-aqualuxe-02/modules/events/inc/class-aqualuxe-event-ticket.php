<?php
/**
 * Event Ticket Class
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe_Event_Ticket class.
 */
class AquaLuxe_Event_Ticket {

    /**
     * Ticket ID.
     *
     * @var int
     */
    public $id = 0;

    /**
     * Ticket post object.
     *
     * @var WP_Post
     */
    public $post = null;

    /**
     * Ticket data.
     *
     * @var array
     */
    protected $data = array(
        'title'             => '',
        'description'       => '',
        'event_id'          => 0,
        'price'             => 0,
        'sale_price'        => 0,
        'capacity'          => 0,
        'sold'              => 0,
        'min_purchase'      => 1,
        'max_purchase'      => 0,
        'start_sale_date'   => '',
        'end_sale_date'     => '',
        'status'            => 'publish',
        'features'          => array(),
    );

    /**
     * Constructor.
     *
     * @param int|AquaLuxe_Event_Ticket|WP_Post $ticket Ticket ID, post object, or ticket object.
     */
    public function __construct($ticket = 0) {
        if (is_numeric($ticket) && $ticket > 0) {
            $this->id = $ticket;
        } elseif ($ticket instanceof AquaLuxe_Event_Ticket) {
            $this->id = $ticket->id;
        } elseif ($ticket instanceof WP_Post || (is_object($ticket) && isset($ticket->ID))) {
            $this->id = $ticket->ID;
            $this->post = $ticket;
        }

        if ($this->id > 0) {
            $this->load_ticket_data();
        }
    }

    /**
     * Load ticket data.
     */
    protected function load_ticket_data() {
        if (!$this->post) {
            $this->post = get_post($this->id);
        }

        if (!$this->post || $this->post->post_type !== 'aqualuxe_ticket') {
            return;
        }

        // Set basic data
        $this->data['title'] = $this->post->post_title;
        $this->data['description'] = $this->post->post_content;
        $this->data['status'] = $this->post->post_status;

        // Load meta data
        $meta_data = get_post_meta($this->id);
        
        // Event ID
        $this->data['event_id'] = isset($meta_data['_ticket_event_id'][0]) ? intval($meta_data['_ticket_event_id'][0]) : 0;
        
        // Pricing
        $this->data['price'] = isset($meta_data['_ticket_price'][0]) ? floatval($meta_data['_ticket_price'][0]) : 0;
        $this->data['sale_price'] = isset($meta_data['_ticket_sale_price'][0]) ? floatval($meta_data['_ticket_sale_price'][0]) : 0;
        
        // Capacity
        $this->data['capacity'] = isset($meta_data['_ticket_capacity'][0]) ? intval($meta_data['_ticket_capacity'][0]) : 0;
        $this->data['sold'] = isset($meta_data['_ticket_sold'][0]) ? intval($meta_data['_ticket_sold'][0]) : 0;
        
        // Purchase limits
        $this->data['min_purchase'] = isset($meta_data['_ticket_min_purchase'][0]) ? intval($meta_data['_ticket_min_purchase'][0]) : 1;
        $this->data['max_purchase'] = isset($meta_data['_ticket_max_purchase'][0]) ? intval($meta_data['_ticket_max_purchase'][0]) : 0;
        
        // Sale dates
        $this->data['start_sale_date'] = isset($meta_data['_ticket_start_sale_date'][0]) ? $meta_data['_ticket_start_sale_date'][0] : '';
        $this->data['end_sale_date'] = isset($meta_data['_ticket_end_sale_date'][0]) ? $meta_data['_ticket_end_sale_date'][0] : '';
        
        // Features
        $features = isset($meta_data['_ticket_features'][0]) ? maybe_unserialize($meta_data['_ticket_features'][0]) : array();
        $this->data['features'] = is_array($features) ? $features : array();
    }

    /**
     * Get ticket data.
     *
     * @param string $key Data key.
     * @return mixed
     */
    public function get_data($key) {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Set ticket data.
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
     * Save ticket data.
     *
     * @return int Ticket ID.
     */
    public function save() {
        $post_data = array(
            'post_title'   => $this->data['title'],
            'post_content' => $this->data['description'],
            'post_status'  => $this->data['status'],
            'post_type'    => 'aqualuxe_ticket',
        );

        if ($this->id > 0) {
            $post_data['ID'] = $this->id;
            wp_update_post($post_data);
        } else {
            $this->id = wp_insert_post($post_data);
        }

        // Save meta data
        if ($this->id > 0) {
            // Event ID
            update_post_meta($this->id, '_ticket_event_id', $this->data['event_id']);
            
            // Pricing
            update_post_meta($this->id, '_ticket_price', $this->data['price']);
            update_post_meta($this->id, '_ticket_sale_price', $this->data['sale_price']);
            
            // Capacity
            update_post_meta($this->id, '_ticket_capacity', $this->data['capacity']);
            update_post_meta($this->id, '_ticket_sold', $this->data['sold']);
            
            // Purchase limits
            update_post_meta($this->id, '_ticket_min_purchase', $this->data['min_purchase']);
            update_post_meta($this->id, '_ticket_max_purchase', $this->data['max_purchase']);
            
            // Sale dates
            update_post_meta($this->id, '_ticket_start_sale_date', $this->data['start_sale_date']);
            update_post_meta($this->id, '_ticket_end_sale_date', $this->data['end_sale_date']);
            
            // Features
            update_post_meta($this->id, '_ticket_features', $this->data['features']);
        }

        return $this->id;
    }

    /**
     * Get ticket title.
     *
     * @return string
     */
    public function get_title() {
        return $this->data['title'];
    }

    /**
     * Get ticket description.
     *
     * @return string
     */
    public function get_description() {
        return $this->data['description'];
    }

    /**
     * Get event ID.
     *
     * @return int
     */
    public function get_event_id() {
        return $this->data['event_id'];
    }

    /**
     * Get event object.
     *
     * @return AquaLuxe_Event|false
     */
    public function get_event() {
        if ($this->data['event_id'] > 0) {
            return new AquaLuxe_Event($this->data['event_id']);
        }
        
        return false;
    }

    /**
     * Get ticket price.
     *
     * @param bool $formatted Whether to format the price.
     * @return string|float
     */
    public function get_price($formatted = true) {
        $price = $this->is_on_sale() ? $this->data['sale_price'] : $this->data['price'];
        
        if ($formatted) {
            return sprintf(
                get_woocommerce_price_format(),
                get_woocommerce_currency_symbol(),
                $price
            );
        }
        
        return $price;
    }

    /**
     * Get regular price.
     *
     * @param bool $formatted Whether to format the price.
     * @return string|float
     */
    public function get_regular_price($formatted = true) {
        if ($formatted) {
            return sprintf(
                get_woocommerce_price_format(),
                get_woocommerce_currency_symbol(),
                $this->data['price']
            );
        }
        
        return $this->data['price'];
    }

    /**
     * Get sale price.
     *
     * @param bool $formatted Whether to format the price.
     * @return string|float
     */
    public function get_sale_price($formatted = true) {
        if ($formatted) {
            return sprintf(
                get_woocommerce_price_format(),
                get_woocommerce_currency_symbol(),
                $this->data['sale_price']
            );
        }
        
        return $this->data['sale_price'];
    }

    /**
     * Check if ticket is on sale.
     *
     * @return bool
     */
    public function is_on_sale() {
        if (empty($this->data['sale_price'])) {
            return false;
        }
        
        // Check sale dates
        if (!empty($this->data['start_sale_date']) && !empty($this->data['end_sale_date'])) {
            $now = current_time('timestamp');
            $start_date = strtotime($this->data['start_sale_date']);
            $end_date = strtotime($this->data['end_sale_date']);
            
            if ($now < $start_date || $now > $end_date) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get ticket capacity.
     *
     * @return int
     */
    public function get_capacity() {
        return $this->data['capacity'];
    }

    /**
     * Get tickets sold.
     *
     * @return int
     */
    public function get_sold() {
        return $this->data['sold'];
    }

    /**
     * Get tickets remaining.
     *
     * @return int
     */
    public function get_remaining() {
        if ($this->data['capacity'] > 0) {
            return max(0, $this->data['capacity'] - $this->data['sold']);
        }
        
        return -1; // Unlimited
    }

    /**
     * Check if ticket is available.
     *
     * @return bool
     */
    public function is_available() {
        // Check status
        if ($this->data['status'] !== 'publish') {
            return false;
        }
        
        // Check capacity
        if ($this->data['capacity'] > 0 && $this->data['sold'] >= $this->data['capacity']) {
            return false;
        }
        
        // Check sale dates
        if (!empty($this->data['start_sale_date']) && !empty($this->data['end_sale_date'])) {
            $now = current_time('timestamp');
            $start_date = strtotime($this->data['start_sale_date']);
            $end_date = strtotime($this->data['end_sale_date']);
            
            if ($now < $start_date || $now > $end_date) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get minimum purchase quantity.
     *
     * @return int
     */
    public function get_min_purchase() {
        return $this->data['min_purchase'];
    }

    /**
     * Get maximum purchase quantity.
     *
     * @return int
     */
    public function get_max_purchase() {
        return $this->data['max_purchase'];
    }

    /**
     * Get sale start date.
     *
     * @param string $format Date format.
     * @return string
     */
    public function get_start_sale_date($format = '') {
        if (empty($format)) {
            $format = get_option('date_format');
        }
        
        return !empty($this->data['start_sale_date']) ? date_i18n($format, strtotime($this->data['start_sale_date'])) : '';
    }

    /**
     * Get sale end date.
     *
     * @param string $format Date format.
     * @return string
     */
    public function get_end_sale_date($format = '') {
        if (empty($format)) {
            $format = get_option('date_format');
        }
        
        return !empty($this->data['end_sale_date']) ? date_i18n($format, strtotime($this->data['end_sale_date'])) : '';
    }

    /**
     * Get ticket features.
     *
     * @return array
     */
    public function get_features() {
        return $this->data['features'];
    }

    /**
     * Add ticket feature.
     *
     * @param string $feature Feature to add.
     */
    public function add_feature($feature) {
        if (!in_array($feature, $this->data['features'])) {
            $this->data['features'][] = $feature;
        }
    }

    /**
     * Remove ticket feature.
     *
     * @param string $feature Feature to remove.
     */
    public function remove_feature($feature) {
        $key = array_search($feature, $this->data['features']);
        
        if ($key !== false) {
            unset($this->data['features'][$key]);
            $this->data['features'] = array_values($this->data['features']);
        }
    }

    /**
     * Record ticket sale.
     *
     * @param int $quantity Quantity sold.
     * @return bool
     */
    public function record_sale($quantity = 1) {
        $quantity = absint($quantity);
        
        if ($quantity <= 0) {
            return false;
        }
        
        // Check if we have enough tickets
        if ($this->data['capacity'] > 0 && $this->data['sold'] + $quantity > $this->data['capacity']) {
            return false;
        }
        
        $this->data['sold'] += $quantity;
        update_post_meta($this->id, '_ticket_sold', $this->data['sold']);
        
        // Update event ticket count
        if ($this->data['event_id'] > 0) {
            $event = new AquaLuxe_Event($this->data['event_id']);
            $tickets_sold = $event->get_data('tickets_sold') + $quantity;
            $event->set_data('tickets_sold', $tickets_sold);
            $event->save();
        }
        
        return true;
    }

    /**
     * Cancel ticket sale.
     *
     * @param int $quantity Quantity to cancel.
     * @return bool
     */
    public function cancel_sale($quantity = 1) {
        $quantity = absint($quantity);
        
        if ($quantity <= 0) {
            return false;
        }
        
        $this->data['sold'] = max(0, $this->data['sold'] - $quantity);
        update_post_meta($this->id, '_ticket_sold', $this->data['sold']);
        
        // Update event ticket count
        if ($this->data['event_id'] > 0) {
            $event = new AquaLuxe_Event($this->data['event_id']);
            $tickets_sold = max(0, $event->get_data('tickets_sold') - $quantity);
            $event->set_data('tickets_sold', $tickets_sold);
            $event->save();
        }
        
        return true;
    }
}