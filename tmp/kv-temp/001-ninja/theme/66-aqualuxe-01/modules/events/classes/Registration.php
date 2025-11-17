<?php
/**
 * Registration Class
 *
 * @package AquaLuxe\Modules\Events
 */

namespace AquaLuxe\Modules\Events;

/**
 * Registration Class
 */
class Registration {
    /**
     * Registration ID
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
     * Ticket ID
     *
     * @var int
     */
    private $ticket_id = 0;

    /**
     * Status
     *
     * @var string
     */
    private $status = 'pending';

    /**
     * Quantity
     *
     * @var int
     */
    private $quantity = 1;

    /**
     * Price
     *
     * @var float
     */
    private $price = 0;

    /**
     * Registration date
     *
     * @var string
     */
    private $date = '';

    /**
     * Attendee data
     *
     * @var array
     */
    private $attendee_data = [];

    /**
     * Payment data
     *
     * @var array
     */
    private $payment_data = [];

    /**
     * Constructor
     *
     * @param int $registration_id
     */
    public function __construct($registration_id = 0) {
        if ($registration_id > 0) {
            $this->id = $registration_id;
            $this->load();
        } else {
            $this->date = current_time('mysql');
        }
    }

    /**
     * Load registration data
     */
    private function load() {
        // Check if post exists
        $post = get_post($this->id);
        
        if (!$post || $post->post_type !== 'aqualuxe_registration') {
            return;
        }
        
        // Load registration data
        $this->event_id = get_post_meta($this->id, '_aqualuxe_registration_event_id', true);
        $this->ticket_id = get_post_meta($this->id, '_aqualuxe_registration_ticket_id', true);
        $this->status = get_post_meta($this->id, '_aqualuxe_registration_status', true);
        $this->quantity = get_post_meta($this->id, '_aqualuxe_registration_quantity', true);
        $this->price = get_post_meta($this->id, '_aqualuxe_registration_price', true);
        $this->date = get_post_meta($this->id, '_aqualuxe_registration_date', true);
        
        // Load attendee data
        $attendee_name = get_post_meta($this->id, '_aqualuxe_registration_attendee_name', true);
        
        if ($attendee_name) {
            $this->attendee_data = [
                'name' => $attendee_name,
                'email' => get_post_meta($this->id, '_aqualuxe_registration_attendee_email', true),
                'phone' => get_post_meta($this->id, '_aqualuxe_registration_attendee_phone', true),
                'address' => get_post_meta($this->id, '_aqualuxe_registration_attendee_address', true),
                'notes' => get_post_meta($this->id, '_aqualuxe_registration_attendee_notes', true),
            ];
        }
        
        // Load payment data
        $payment_method = get_post_meta($this->id, '_aqualuxe_registration_payment_method', true);
        
        if ($payment_method) {
            $this->payment_data = [
                'method' => $payment_method,
                'status' => get_post_meta($this->id, '_aqualuxe_registration_payment_status', true),
                'transaction_id' => get_post_meta($this->id, '_aqualuxe_registration_payment_transaction_id', true),
                'amount' => get_post_meta($this->id, '_aqualuxe_registration_payment_amount', true),
                'currency' => get_post_meta($this->id, '_aqualuxe_registration_payment_currency', true),
                'date' => get_post_meta($this->id, '_aqualuxe_registration_payment_date', true),
            ];
        }
    }

    /**
     * Save registration
     *
     * @return int
     */
    public function save() {
        // Prepare post data
        $post_data = [
            'post_title' => sprintf(
                __('Registration #%s', 'aqualuxe'),
                $this->id ? $this->id : '{ID}'
            ),
            'post_status' => 'publish',
            'post_type' => 'aqualuxe_registration',
        ];
        
        // Update existing registration
        if ($this->id > 0) {
            $post_data['ID'] = $this->id;
            $registration_id = wp_update_post($post_data);
        }
        // Create new registration
        else {
            $registration_id = wp_insert_post($post_data);
            
            // Update title with ID
            if ($registration_id) {
                wp_update_post([
                    'ID' => $registration_id,
                    'post_title' => sprintf(__('Registration #%s', 'aqualuxe'), $registration_id),
                ]);
            }
        }
        
        // Check for errors
        if (is_wp_error($registration_id)) {
            return 0;
        }
        
        // Set ID
        $this->id = $registration_id;
        
        // Save registration data
        update_post_meta($this->id, '_aqualuxe_registration_event_id', $this->event_id);
        update_post_meta($this->id, '_aqualuxe_registration_ticket_id', $this->ticket_id);
        update_post_meta($this->id, '_aqualuxe_registration_status', $this->status);
        update_post_meta($this->id, '_aqualuxe_registration_quantity', $this->quantity);
        update_post_meta($this->id, '_aqualuxe_registration_price', $this->price);
        update_post_meta($this->id, '_aqualuxe_registration_date', $this->date);
        
        // Save attendee data
        update_post_meta($this->id, '_aqualuxe_registration_attendee_name', $this->attendee_data['name'] ?? '');
        update_post_meta($this->id, '_aqualuxe_registration_attendee_email', $this->attendee_data['email'] ?? '');
        update_post_meta($this->id, '_aqualuxe_registration_attendee_phone', $this->attendee_data['phone'] ?? '');
        update_post_meta($this->id, '_aqualuxe_registration_attendee_address', $this->attendee_data['address'] ?? '');
        update_post_meta($this->id, '_aqualuxe_registration_attendee_notes', $this->attendee_data['notes'] ?? '');
        
        // Save payment data
        if (!empty($this->payment_data)) {
            update_post_meta($this->id, '_aqualuxe_registration_payment_method', $this->payment_data['method'] ?? '');
            update_post_meta($this->id, '_aqualuxe_registration_payment_status', $this->payment_data['status'] ?? '');
            update_post_meta($this->id, '_aqualuxe_registration_payment_transaction_id', $this->payment_data['transaction_id'] ?? '');
            update_post_meta($this->id, '_aqualuxe_registration_payment_amount', $this->payment_data['amount'] ?? 0);
            update_post_meta($this->id, '_aqualuxe_registration_payment_currency', $this->payment_data['currency'] ?? '');
            update_post_meta($this->id, '_aqualuxe_registration_payment_date', $this->payment_data['date'] ?? '');
        }
        
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
     * Set ticket ID
     *
     * @param int $ticket_id
     */
    public function set_ticket_id($ticket_id) {
        $this->ticket_id = absint($ticket_id);
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function set_status($status) {
        $old_status = $this->status;
        $this->status = $status;
        
        // Trigger status change action if ID exists
        if ($this->id && $old_status !== $status) {
            do_action('aqualuxe_registration_status_changed', $this->id, $old_status, $status);
        }
    }

    /**
     * Set quantity
     *
     * @param int $quantity
     */
    public function set_quantity($quantity) {
        $this->quantity = max(1, absint($quantity));
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
     * Set date
     *
     * @param string $date
     */
    public function set_date($date) {
        $this->date = $date;
    }

    /**
     * Set attendee data
     *
     * @param array $data
     */
    public function set_attendee_data($data) {
        $this->attendee_data = array_merge($this->attendee_data, $data);
    }

    /**
     * Set payment data
     *
     * @param array $data
     */
    public function set_payment_data($data) {
        $this->payment_data = array_merge($this->payment_data, $data);
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
     * Get ticket ID
     *
     * @return int
     */
    public function get_ticket_id() {
        return $this->ticket_id;
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
     * Get quantity
     *
     * @return int
     */
    public function get_quantity() {
        return $this->quantity;
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
     * Get date
     *
     * @return string
     */
    public function get_date() {
        return $this->date;
    }

    /**
     * Get attendee data
     *
     * @return array
     */
    public function get_attendee_data() {
        return $this->attendee_data;
    }

    /**
     * Get payment data
     *
     * @return array
     */
    public function get_payment_data() {
        return $this->payment_data;
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
     * Get formatted date
     *
     * @param string $format
     * @return string
     */
    public function get_formatted_date($format = '') {
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
        
        return date_i18n($format, strtotime($this->date));
    }

    /**
     * Get event
     *
     * @return Event
     */
    public function get_event() {
        return new Event($this->event_id);
    }

    /**
     * Get ticket
     *
     * @return Ticket
     */
    public function get_ticket() {
        return new Ticket($this->ticket_id);
    }

    /**
     * Get permalink
     *
     * @return string
     */
    public function get_permalink() {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['events'] ?? null;
        
        if ($module) {
            $registration_page_id = $module->get_setting('registration_page', 0);
            
            if ($registration_page_id) {
                return add_query_arg('registration_id', $this->id, get_permalink($registration_page_id));
            }
        }
        
        return get_permalink($this->id);
    }

    /**
     * Is paid
     *
     * @return bool
     */
    public function is_paid() {
        return !empty($this->payment_data) && isset($this->payment_data['status']) && $this->payment_data['status'] === 'completed';
    }

    /**
     * Is pending payment
     *
     * @return bool
     */
    public function is_pending_payment() {
        return !empty($this->payment_data) && isset($this->payment_data['status']) && $this->payment_data['status'] === 'pending';
    }

    /**
     * Is confirmed
     *
     * @return bool
     */
    public function is_confirmed() {
        return $this->status === 'confirmed';
    }

    /**
     * Is cancelled
     *
     * @return bool
     */
    public function is_cancelled() {
        return $this->status === 'cancelled';
    }

    /**
     * Is completed
     *
     * @return bool
     */
    public function is_completed() {
        return $this->status === 'completed';
    }

    /**
     * Is refunded
     *
     * @return bool
     */
    public function is_refunded() {
        return $this->status === 'refunded';
    }

    /**
     * Check if registration exists
     *
     * @return bool
     */
    public function exists() {
        return $this->id > 0 && get_post_type($this->id) === 'aqualuxe_registration';
    }
}