<?php
/**
 * Booking Class
 *
 * @package AquaLuxe\Modules\Bookings
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Booking Class
 */
class Booking {
    /**
     * Booking ID
     *
     * @var int
     */
    private $id = 0;

    /**
     * Bookable item ID
     *
     * @var int
     */
    private $bookable_id = 0;

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
     * Customer data
     *
     * @var array
     */
    private $customer_data = [];

    /**
     * Payment data
     *
     * @var array
     */
    private $payment_data = [];

    /**
     * Notes
     *
     * @var array
     */
    private $notes = [];

    /**
     * Constructor
     *
     * @param int $booking_id
     */
    public function __construct($booking_id = 0) {
        if ($booking_id > 0) {
            $this->id = $booking_id;
            $this->load();
        }
    }

    /**
     * Load booking data
     */
    private function load() {
        // Check if post exists
        $post = get_post($this->id);
        
        if (!$post || $post->post_type !== 'aqualuxe_booking') {
            return;
        }
        
        // Load booking data
        $this->bookable_id = get_post_meta($this->id, '_aqualuxe_booking_bookable_id', true);
        $this->start_date = get_post_meta($this->id, '_aqualuxe_booking_start_date', true);
        $this->end_date = get_post_meta($this->id, '_aqualuxe_booking_end_date', true);
        $this->status = get_post_meta($this->id, '_aqualuxe_booking_status', true);
        $this->quantity = get_post_meta($this->id, '_aqualuxe_booking_quantity', true);
        $this->price = get_post_meta($this->id, '_aqualuxe_booking_price', true);
        
        // Load customer data
        $this->customer_data = [
            'name' => get_post_meta($this->id, '_aqualuxe_booking_customer_name', true),
            'email' => get_post_meta($this->id, '_aqualuxe_booking_customer_email', true),
            'phone' => get_post_meta($this->id, '_aqualuxe_booking_customer_phone', true),
            'address' => get_post_meta($this->id, '_aqualuxe_booking_customer_address', true),
            'notes' => get_post_meta($this->id, '_aqualuxe_booking_customer_notes', true),
        ];
        
        // Load payment data
        $payment_method = get_post_meta($this->id, '_aqualuxe_booking_payment_method', true);
        
        if ($payment_method) {
            $this->payment_data = [
                'method' => $payment_method,
                'status' => get_post_meta($this->id, '_aqualuxe_booking_payment_status', true),
                'transaction_id' => get_post_meta($this->id, '_aqualuxe_booking_payment_transaction_id', true),
                'amount' => get_post_meta($this->id, '_aqualuxe_booking_payment_amount', true),
                'currency' => get_post_meta($this->id, '_aqualuxe_booking_payment_currency', true),
                'date' => get_post_meta($this->id, '_aqualuxe_booking_payment_date', true),
            ];
        }
        
        // Load notes
        $notes = get_post_meta($this->id, '_aqualuxe_booking_notes', true);
        
        if ($notes) {
            $this->notes = $notes;
        }
    }

    /**
     * Save booking
     *
     * @return int
     */
    public function save() {
        // Prepare post data
        $post_data = [
            'post_title' => sprintf(
                __('Booking #%s', 'aqualuxe'),
                $this->id ? $this->id : '{ID}'
            ),
            'post_status' => 'publish',
            'post_type' => 'aqualuxe_booking',
        ];
        
        // Update existing booking
        if ($this->id > 0) {
            $post_data['ID'] = $this->id;
            $booking_id = wp_update_post($post_data);
        }
        // Create new booking
        else {
            $booking_id = wp_insert_post($post_data);
            
            // Update title with ID
            if ($booking_id) {
                wp_update_post([
                    'ID' => $booking_id,
                    'post_title' => sprintf(__('Booking #%s', 'aqualuxe'), $booking_id),
                ]);
            }
        }
        
        // Check for errors
        if (is_wp_error($booking_id)) {
            return 0;
        }
        
        // Set ID
        $this->id = $booking_id;
        
        // Save booking data
        update_post_meta($this->id, '_aqualuxe_booking_bookable_id', $this->bookable_id);
        update_post_meta($this->id, '_aqualuxe_booking_start_date', $this->start_date);
        update_post_meta($this->id, '_aqualuxe_booking_end_date', $this->end_date);
        update_post_meta($this->id, '_aqualuxe_booking_status', $this->status);
        update_post_meta($this->id, '_aqualuxe_booking_quantity', $this->quantity);
        update_post_meta($this->id, '_aqualuxe_booking_price', $this->price);
        
        // Save customer data
        update_post_meta($this->id, '_aqualuxe_booking_customer_name', $this->customer_data['name'] ?? '');
        update_post_meta($this->id, '_aqualuxe_booking_customer_email', $this->customer_data['email'] ?? '');
        update_post_meta($this->id, '_aqualuxe_booking_customer_phone', $this->customer_data['phone'] ?? '');
        update_post_meta($this->id, '_aqualuxe_booking_customer_address', $this->customer_data['address'] ?? '');
        update_post_meta($this->id, '_aqualuxe_booking_customer_notes', $this->customer_data['notes'] ?? '');
        
        // Save payment data
        if (!empty($this->payment_data)) {
            update_post_meta($this->id, '_aqualuxe_booking_payment_method', $this->payment_data['method'] ?? '');
            update_post_meta($this->id, '_aqualuxe_booking_payment_status', $this->payment_data['status'] ?? '');
            update_post_meta($this->id, '_aqualuxe_booking_payment_transaction_id', $this->payment_data['transaction_id'] ?? '');
            update_post_meta($this->id, '_aqualuxe_booking_payment_amount', $this->payment_data['amount'] ?? 0);
            update_post_meta($this->id, '_aqualuxe_booking_payment_currency', $this->payment_data['currency'] ?? '');
            update_post_meta($this->id, '_aqualuxe_booking_payment_date', $this->payment_data['date'] ?? '');
        }
        
        // Save notes
        update_post_meta($this->id, '_aqualuxe_booking_notes', $this->notes);
        
        return $this->id;
    }

    /**
     * Set bookable ID
     *
     * @param int $bookable_id
     */
    public function set_bookable_id($bookable_id) {
        $this->bookable_id = absint($bookable_id);
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
     * Set status
     *
     * @param string $status
     */
    public function set_status($status) {
        $old_status = $this->status;
        $this->status = $status;
        
        // Trigger status change action if ID exists
        if ($this->id && $old_status !== $status) {
            do_action('aqualuxe_booking_status_changed', $this->id, $old_status, $status);
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
     * Set customer data
     *
     * @param array $data
     */
    public function set_customer_data($data) {
        $this->customer_data = array_merge($this->customer_data, $data);
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
     * Add note
     *
     * @param string $note
     * @param string $type
     * @param int $author_id
     */
    public function add_note($note, $type = 'private', $author_id = 0) {
        $this->notes[] = [
            'note' => $note,
            'type' => $type,
            'author_id' => $author_id,
            'date' => current_time('mysql'),
        ];
    }

    /**
     * Calculate price
     *
     * @return float
     */
    public function calculate_price() {
        // Get base price
        $base_price = get_post_meta($this->bookable_id, '_aqualuxe_base_price', true);
        $base_price = floatval($base_price);
        
        // Get pricing type
        $pricing_type = get_post_meta($this->bookable_id, '_aqualuxe_pricing_type', true);
        $pricing_type = $pricing_type ? $pricing_type : 'fixed';
        
        // Calculate price based on pricing type
        $price = $base_price;
        
        switch ($pricing_type) {
            case 'hourly':
                // Calculate hours
                $start = strtotime($this->start_date);
                $end = strtotime($this->end_date);
                $hours = max(1, ceil(($end - $start) / HOUR_IN_SECONDS));
                
                $price = $base_price * $hours;
                break;
                
            case 'daily':
                // Calculate days
                $start = strtotime($this->start_date);
                $end = strtotime($this->end_date);
                $days = max(1, ceil(($end - $start) / DAY_IN_SECONDS));
                
                $price = $base_price * $days;
                break;
                
            case 'fixed':
            default:
                // Fixed price
                $price = $base_price;
                break;
        }
        
        // Apply pricing rules
        $pricing_rules = get_post_meta($this->bookable_id, '_aqualuxe_pricing_rules', true);
        
        if ($pricing_rules && is_array($pricing_rules)) {
            foreach ($pricing_rules as $rule) {
                // Check if rule applies
                $applies = false;
                
                switch ($rule['type']) {
                    case 'date_range':
                        $rule_start = strtotime($rule['from']);
                        $rule_end = strtotime($rule['to']);
                        $booking_start = strtotime($this->start_date);
                        $booking_end = strtotime($this->end_date);
                        
                        // Check if booking dates overlap with rule dates
                        $applies = ($booking_start <= $rule_end && $booking_end >= $rule_start);
                        break;
                        
                    case 'weekday':
                        $booking_day = date('w', strtotime($this->start_date));
                        $rule_days = explode(',', $rule['from']);
                        
                        $applies = in_array($booking_day, $rule_days);
                        break;
                        
                    case 'time':
                        $rule_start = strtotime($rule['from']);
                        $rule_end = strtotime($rule['to']);
                        $booking_time = strtotime($this->start_date);
                        
                        // Extract time components
                        $rule_start_time = date('H:i', $rule_start);
                        $rule_end_time = date('H:i', $rule_end);
                        $booking_time_str = date('H:i', $booking_time);
                        
                        // Compare times
                        $applies = ($booking_time_str >= $rule_start_time && $booking_time_str <= $rule_end_time);
                        break;
                }
                
                // Apply rule adjustment
                if ($applies) {
                    $adjustment = floatval($rule['adjustment']);
                    $adjustment_type = $rule['adjustment_type'] ?? 'fixed';
                    
                    if ($adjustment_type === 'percentage') {
                        $price += ($price * ($adjustment / 100));
                    } else {
                        $price += $adjustment;
                    }
                }
            }
        }
        
        // Multiply by quantity
        $price *= $this->quantity;
        
        // Set price
        $this->price = max(0, $price);
        
        return $this->price;
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
     * Get bookable ID
     *
     * @return int
     */
    public function get_bookable_id() {
        return $this->bookable_id;
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
     * Get customer data
     *
     * @return array
     */
    public function get_customer_data() {
        return $this->customer_data;
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
     * Get notes
     *
     * @return array
     */
    public function get_notes() {
        return $this->notes;
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
            $module = $theme->get_active_modules()['bookings'] ?? null;
            
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
            $module = $theme->get_active_modules()['bookings'] ?? null;
            
            if ($module) {
                $format = $module->get_setting('date_format', 'F j, Y');
            } else {
                $format = get_option('date_format');
            }
        }
        
        return date_i18n($format, strtotime($this->end_date));
    }

    /**
     * Get formatted price
     *
     * @return string
     */
    public function get_formatted_price() {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['bookings'] ?? null;
        
        if ($module) {
            return $module->format_price($this->price);
        }
        
        return sprintf(
            get_woocommerce_price_format(),
            get_woocommerce_currency_symbol(),
            number_format($this->price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator())
        );
    }

    /**
     * Get bookable title
     *
     * @return string
     */
    public function get_bookable_title() {
        return get_the_title($this->bookable_id);
    }

    /**
     * Get bookable permalink
     *
     * @return string
     */
    public function get_bookable_permalink() {
        return get_permalink($this->bookable_id);
    }

    /**
     * Get booking permalink
     *
     * @return string
     */
    public function get_permalink() {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['bookings'] ?? null;
        
        if ($module) {
            $booking_page_id = $module->get_setting('booking_page', 0);
            
            if ($booking_page_id) {
                return add_query_arg('booking_id', $this->id, get_permalink($booking_page_id));
            }
        }
        
        return get_permalink($this->id);
    }

    /**
     * Check if booking exists
     *
     * @return bool
     */
    public function exists() {
        return $this->id > 0 && get_post_type($this->id) === 'aqualuxe_booking';
    }
}