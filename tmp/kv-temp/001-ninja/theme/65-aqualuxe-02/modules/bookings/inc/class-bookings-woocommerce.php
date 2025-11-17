<?php
/**
 * Bookings WooCommerce Integration
 *
 * Handles WooCommerce integration for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings WooCommerce Class
 */
class AquaLuxe_Bookings_WooCommerce {
    /**
     * Constructor
     */
    public function __construct() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }

        // Initialize hooks
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Cart and checkout
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_cart_item_data'), 10, 3);
        add_filter('woocommerce_get_item_data', array($this, 'get_item_data'), 10, 2);
        add_action('woocommerce_checkout_create_order_line_item', array($this, 'checkout_create_order_line_item'), 10, 4);
        add_action('woocommerce_checkout_order_processed', array($this, 'checkout_order_processed'), 10, 3);
        
        // Order management
        add_action('woocommerce_order_status_changed', array($this, 'order_status_changed'), 10, 4);
        add_action('woocommerce_order_item_meta_end', array($this, 'order_item_meta'), 10, 3);
        
        // Admin order
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'admin_order_data'), 10, 1);
        
        // My account
        add_filter('woocommerce_my_account_my_orders_actions', array($this, 'my_account_orders_actions'), 10, 2);
        add_filter('woocommerce_account_menu_items', array($this, 'account_menu_items'), 10, 1);
        add_action('init', array($this, 'add_bookings_endpoint'));
        add_action('woocommerce_account_bookings_endpoint', array($this, 'bookings_endpoint_content'));
        
        // Product integration
        add_action('woocommerce_product_options_general_product_data', array($this, 'product_options'));
        add_action('woocommerce_process_product_meta', array($this, 'save_product_options'), 10, 1);
        add_filter('woocommerce_product_tabs', array($this, 'product_tabs'), 10, 1);
        add_action('woocommerce_single_product_summary', array($this, 'single_product_summary'), 25);
        
        // Emails
        add_filter('woocommerce_email_classes', array($this, 'email_classes'));
        add_action('woocommerce_email_after_order_table', array($this, 'email_after_order_table'), 10, 4);
    }

    /**
     * Add cart item data
     *
     * @param array $cart_item_data Cart item data
     * @param int $product_id Product ID
     * @param int $variation_id Variation ID
     * @return array Modified cart item data
     */
    public function add_cart_item_data($cart_item_data, $product_id, $variation_id) {
        // Check if booking data is in session
        if (!isset(WC()->session) || !WC()->session->get('aqualuxe_booking_data')) {
            return $cart_item_data;
        }
        
        // Get booking data from session
        $booking_data = WC()->session->get('aqualuxe_booking_data');
        
        // Check if this is the product we want to add booking data to
        $product_id_to_check = $variation_id ? $variation_id : $product_id;
        
        if ($product_id_to_check != $booking_data['product_id']) {
            return $cart_item_data;
        }
        
        // Add booking data to cart item
        $cart_item_data['aqualuxe_booking'] = array(
            'service_id' => $booking_data['service_id'],
            'start_date' => $booking_data['start_date'],
            'end_date' => $booking_data['end_date'],
            'customer_name' => $booking_data['customer_name'],
            'customer_email' => $booking_data['customer_email'],
            'customer_phone' => $booking_data['customer_phone'],
            'customer_notes' => isset($booking_data['customer_notes']) ? $booking_data['customer_notes'] : '',
            'display_date' => date_i18n(get_option('date_format'), strtotime($booking_data['start_date'])),
            'display_time' => date_i18n(get_option('time_format'), strtotime($booking_data['start_date'])) . ' - ' . date_i18n(get_option('time_format'), strtotime($booking_data['end_date'])),
        );
        
        // Generate a unique key for this booking
        $cart_item_data['aqualuxe_booking_key'] = md5(json_encode($cart_item_data['aqualuxe_booking']));
        
        // Clear session data
        WC()->session->set('aqualuxe_booking_data', null);
        
        return $cart_item_data;
    }

    /**
     * Get item data
     *
     * @param array $item_data Item data
     * @param array $cart_item Cart item
     * @return array Modified item data
     */
    public function get_item_data($item_data, $cart_item) {
        if (isset($cart_item['aqualuxe_booking'])) {
            $booking = $cart_item['aqualuxe_booking'];
            
            // Get service name
            $service_name = get_the_title($booking['service_id']);
            
            $item_data[] = array(
                'key' => __('Service', 'aqualuxe'),
                'value' => $service_name,
                'display' => $service_name,
            );
            
            $item_data[] = array(
                'key' => __('Date', 'aqualuxe'),
                'value' => $booking['display_date'],
                'display' => $booking['display_date'],
            );
            
            $item_data[] = array(
                'key' => __('Time', 'aqualuxe'),
                'value' => $booking['display_time'],
                'display' => $booking['display_time'],
            );
        }
        
        return $item_data;
    }

    /**
     * Checkout create order line item
     *
     * @param WC_Order_Item_Product $item Order item
     * @param string $cart_item_key Cart item key
     * @param array $values Cart item values
     * @param WC_Order $order Order object
     */
    public function checkout_create_order_line_item($item, $cart_item_key, $values, $order) {
        if (isset($values['aqualuxe_booking'])) {
            $booking = $values['aqualuxe_booking'];
            
            // Get service name
            $service_name = get_the_title($booking['service_id']);
            
            // Add booking data as meta
            $item->add_meta_data(__('Service', 'aqualuxe'), $service_name);
            $item->add_meta_data(__('Date', 'aqualuxe'), $booking['display_date']);
            $item->add_meta_data(__('Time', 'aqualuxe'), $booking['display_time']);
            $item->add_meta_data('_aqualuxe_booking_service_id', $booking['service_id']);
            $item->add_meta_data('_aqualuxe_booking_start_date', $booking['start_date']);
            $item->add_meta_data('_aqualuxe_booking_end_date', $booking['end_date']);
            $item->add_meta_data('_aqualuxe_booking_customer_name', $booking['customer_name']);
            $item->add_meta_data('_aqualuxe_booking_customer_email', $booking['customer_email']);
            $item->add_meta_data('_aqualuxe_booking_customer_phone', $booking['customer_phone']);
            
            if (!empty($booking['customer_notes'])) {
                $item->add_meta_data('_aqualuxe_booking_customer_notes', $booking['customer_notes']);
            }
        }
    }

    /**
     * Checkout order processed
     *
     * @param int $order_id Order ID
     * @param array $posted_data Posted data
     * @param WC_Order $order Order object
     */
    public function checkout_order_processed($order_id, $posted_data, $order) {
        // Check if order contains booking items
        $has_booking = false;
        
        foreach ($order->get_items() as $item) {
            if ($item->meta_exists('_aqualuxe_booking_service_id')) {
                $has_booking = true;
                break;
            }
        }
        
        if (!$has_booking) {
            return;
        }
        
        // Process each booking item
        foreach ($order->get_items() as $item) {
            if (!$item->meta_exists('_aqualuxe_booking_service_id')) {
                continue;
            }
            
            // Get booking data from item meta
            $service_id = $item->get_meta('_aqualuxe_booking_service_id');
            $start_date = $item->get_meta('_aqualuxe_booking_start_date');
            $end_date = $item->get_meta('_aqualuxe_booking_end_date');
            $customer_name = $item->get_meta('_aqualuxe_booking_customer_name');
            $customer_email = $item->get_meta('_aqualuxe_booking_customer_email');
            $customer_phone = $item->get_meta('_aqualuxe_booking_customer_phone');
            $customer_notes = $item->get_meta('_aqualuxe_booking_customer_notes');
            
            // Get customer ID
            $customer_id = $order->get_customer_id();
            
            // Determine booking status based on payment method and order status
            $booking_status = 'aqualuxe-pending';
            
            if ($order->is_paid()) {
                $booking_status = 'aqualuxe-confirmed';
            }
            
            // Create booking
            $booking_data = array(
                'service_id' => $service_id,
                'customer_id' => $customer_id,
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'all_day' => false,
                'quantity' => $item->get_quantity(),
                'total' => $item->get_total(),
                'status' => $booking_status,
                'order_id' => $order_id,
            );
            
            $bookings_data = new AquaLuxe_Bookings_Data();
            $booking_id = $bookings_data->create_booking($booking_data);
            
            if (!is_wp_error($booking_id)) {
                // Store customer notes if provided
                if (!empty($customer_notes)) {
                    update_post_meta($booking_id, '_customer_notes', $customer_notes);
                }
                
                // Store booking ID in order item meta
                $item->add_meta_data('_aqualuxe_booking_id', $booking_id);
                $item->save();
                
                // Add note to order
                $order->add_order_note(
                    sprintf(
                        __('Booking #%1$s created for service "%2$s" on %3$s at %4$s.', 'aqualuxe'),
                        get_post_meta($booking_id, '_booking_id', true),
                        get_the_title($service_id),
                        date_i18n(get_option('date_format'), strtotime($start_date)),
                        date_i18n(get_option('time_format'), strtotime($start_date))
                    )
                );
            } else {
                // Add error note to order
                $order->add_order_note(
                    sprintf(
                        __('Error creating booking: %s', 'aqualuxe'),
                        $booking_id->get_error_message()
                    )
                );
            }
        }
    }

    /**
     * Order status changed
     *
     * @param int $order_id Order ID
     * @param string $old_status Old status
     * @param string $new_status New status
     * @param WC_Order $order Order object
     */
    public function order_status_changed($order_id, $old_status, $new_status, $order) {
        // Check if order contains booking items
        $has_booking = false;
        
        foreach ($order->get_items() as $item) {
            if ($item->meta_exists('_aqualuxe_booking_id')) {
                $has_booking = true;
                break;
            }
        }
        
        if (!$has_booking) {
            return;
        }
        
        // Process each booking item
        foreach ($order->get_items() as $item) {
            if (!$item->meta_exists('_aqualuxe_booking_id')) {
                continue;
            }
            
            // Get booking ID
            $booking_id = $item->get_meta('_aqualuxe_booking_id');
            
            // Update booking status based on order status
            $booking_status = 'aqualuxe-pending';
            
            if (in_array($new_status, array('processing', 'completed'))) {
                $booking_status = 'aqualuxe-confirmed';
            } elseif (in_array($new_status, array('cancelled', 'refunded', 'failed'))) {
                $booking_status = 'aqualuxe-cancelled';
            }
            
            // Update booking
            $bookings_data = new AquaLuxe_Bookings_Data();
            $result = $bookings_data->update_booking($booking_id, array('status' => $booking_status));
            
            if (!is_wp_error($result)) {
                // Add note to order
                $order->add_order_note(
                    sprintf(
                        __('Booking #%1$s status updated to %2$s.', 'aqualuxe'),
                        get_post_meta($booking_id, '_booking_id', true),
                        $booking_status
                    )
                );
            } else {
                // Add error note to order
                $order->add_order_note(
                    sprintf(
                        __('Error updating booking #%1$s: %2$s', 'aqualuxe'),
                        get_post_meta($booking_id, '_booking_id', true),
                        $result->get_error_message()
                    )
                );
            }
        }
    }

    /**
     * Order item meta
     *
     * @param int $item_id Item ID
     * @param WC_Order_Item $item Order item
     * @param WC_Order $order Order object
     */
    public function order_item_meta($item_id, $item, $order) {
        if (!$item->meta_exists('_aqualuxe_booking_id')) {
            return;
        }
        
        // Get booking ID
        $booking_id = $item->get_meta('_aqualuxe_booking_id');
        
        // Get booking data
        $bookings_data = new AquaLuxe_Bookings_Data();
        $booking = $bookings_data->get_booking($booking_id);
        
        if (!$booking) {
            return;
        }
        
        // Format date and time
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        
        $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
        $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));
        
        // Display booking details
        echo '<div class="aqualuxe-booking-details">';
        echo '<h4>' . __('Booking Details', 'aqualuxe') . '</h4>';
        echo '<p><strong>' . __('Booking ID:', 'aqualuxe') . '</strong> ' . $booking['booking_id'] . '</p>';
        echo '<p><strong>' . __('Service:', 'aqualuxe') . '</strong> ' . $booking['service_name'] . '</p>';
        echo '<p><strong>' . __('Date:', 'aqualuxe') . '</strong> ' . $booking_date . '</p>';
        echo '<p><strong>' . __('Time:', 'aqualuxe') . '</strong> ' . $booking_time . '</p>';
        echo '<p><strong>' . __('Status:', 'aqualuxe') . '</strong> ' . $this->get_booking_status_label($booking['status']) . '</p>';
        echo '</div>';
    }

    /**
     * Admin order data
     *
     * @param WC_Order $order Order object
     */
    public function admin_order_data($order) {
        // Check if order contains booking items
        $has_booking = false;
        
        foreach ($order->get_items() as $item) {
            if ($item->meta_exists('_aqualuxe_booking_id')) {
                $has_booking = true;
                break;
            }
        }
        
        if (!$has_booking) {
            return;
        }
        
        echo '<div class="aqualuxe-bookings-admin-order-data">';
        echo '<h3>' . __('Bookings', 'aqualuxe') . '</h3>';
        
        echo '<table class="widefat fixed">';
        echo '<thead><tr>';
        echo '<th>' . __('Booking ID', 'aqualuxe') . '</th>';
        echo '<th>' . __('Service', 'aqualuxe') . '</th>';
        echo '<th>' . __('Date', 'aqualuxe') . '</th>';
        echo '<th>' . __('Time', 'aqualuxe') . '</th>';
        echo '<th>' . __('Status', 'aqualuxe') . '</th>';
        echo '<th>' . __('Actions', 'aqualuxe') . '</th>';
        echo '</tr></thead>';
        
        echo '<tbody>';
        
        foreach ($order->get_items() as $item) {
            if (!$item->meta_exists('_aqualuxe_booking_id')) {
                continue;
            }
            
            // Get booking ID
            $booking_id = $item->get_meta('_aqualuxe_booking_id');
            
            // Get booking data
            $bookings_data = new AquaLuxe_Bookings_Data();
            $booking = $bookings_data->get_booking($booking_id);
            
            if (!$booking) {
                continue;
            }
            
            // Format date and time
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            
            $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
            $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));
            
            echo '<tr>';
            echo '<td>' . $booking['booking_id'] . '</td>';
            echo '<td>' . $booking['service_name'] . '</td>';
            echo '<td>' . $booking_date . '</td>';
            echo '<td>' . $booking_time . '</td>';
            echo '<td>' . $this->get_booking_status_label($booking['status']) . '</td>';
            echo '<td>';
            echo '<a href="' . admin_url('post.php?post=' . $booking['id'] . '&action=edit') . '" class="button">' . __('View', 'aqualuxe') . '</a>';
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
        echo '</div>';
    }

    /**
     * My account orders actions
     *
     * @param array $actions Actions
     * @param WC_Order $order Order object
     * @return array Modified actions
     */
    public function my_account_orders_actions($actions, $order) {
        // Check if order contains booking items
        $has_booking = false;
        
        foreach ($order->get_items() as $item) {
            if ($item->meta_exists('_aqualuxe_booking_id')) {
                $has_booking = true;
                break;
            }
        }
        
        if ($has_booking) {
            $actions['view_bookings'] = array(
                'url' => wc_get_endpoint_url('bookings', $order->get_id(), wc_get_page_permalink('myaccount')),
                'name' => __('View Bookings', 'aqualuxe'),
            );
        }
        
        return $actions;
    }

    /**
     * Account menu items
     *
     * @param array $items Menu items
     * @return array Modified menu items
     */
    public function account_menu_items($items) {
        // Add bookings menu item
        $items['bookings'] = __('My Bookings', 'aqualuxe');
        
        return $items;
    }

    /**
     * Add bookings endpoint
     */
    public function add_bookings_endpoint() {
        add_rewrite_endpoint('bookings', EP_ROOT | EP_PAGES);
    }

    /**
     * Bookings endpoint content
     *
     * @param int $order_id Order ID
     */
    public function bookings_endpoint_content($order_id = 0) {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            return;
        }
        
        // Get current user ID
        $user_id = get_current_user_id();
        
        // If order ID is provided, show bookings for that order
        if ($order_id > 0) {
            $order = wc_get_order($order_id);
            
            if (!$order || $order->get_customer_id() !== $user_id) {
                echo '<p>' . __('Invalid order.', 'aqualuxe') . '</p>';
                return;
            }
            
            $this->show_order_bookings($order);
            return;
        }
        
        // Otherwise, show all bookings for the user
        $bookings_data = new AquaLuxe_Bookings_Data();
        $bookings = $bookings_data->get_bookings(array(
            'customer_id' => $user_id,
            'limit' => -1,
        ));
        
        if (empty($bookings)) {
            echo '<p>' . __('You have no bookings yet.', 'aqualuxe') . '</p>';
            return;
        }
        
        // Group bookings by order
        $bookings_by_order = array();
        
        foreach ($bookings as $booking) {
            $order_id = $booking['order_id'];
            
            if (!isset($bookings_by_order[$order_id])) {
                $bookings_by_order[$order_id] = array();
            }
            
            $bookings_by_order[$order_id][] = $booking;
        }
        
        // Display bookings by order
        foreach ($bookings_by_order as $order_id => $order_bookings) {
            if ($order_id > 0) {
                $order = wc_get_order($order_id);
                
                if ($order) {
                    echo '<h3>' . sprintf(__('Order #%s', 'aqualuxe'), $order->get_order_number()) . '</h3>';
                    echo '<p>' . sprintf(__('Order Date: %s', 'aqualuxe'), $order->get_date_created()->date_i18n(get_option('date_format'))) . '</p>';
                    echo '<p>' . sprintf(__('Order Status: %s', 'aqualuxe'), wc_get_order_status_name($order->get_status())) . '</p>';
                }
            } else {
                echo '<h3>' . __('Bookings', 'aqualuxe') . '</h3>';
            }
            
            $this->show_bookings_table($order_bookings);
        }
    }

    /**
     * Show order bookings
     *
     * @param WC_Order $order Order object
     */
    private function show_order_bookings($order) {
        echo '<h2>' . sprintf(__('Bookings for Order #%s', 'aqualuxe'), $order->get_order_number()) . '</h2>';
        
        // Get bookings for this order
        $bookings = array();
        
        foreach ($order->get_items() as $item) {
            if (!$item->meta_exists('_aqualuxe_booking_id')) {
                continue;
            }
            
            // Get booking ID
            $booking_id = $item->get_meta('_aqualuxe_booking_id');
            
            // Get booking data
            $bookings_data = new AquaLuxe_Bookings_Data();
            $booking = $bookings_data->get_booking($booking_id);
            
            if ($booking) {
                $bookings[] = $booking;
            }
        }
        
        if (empty($bookings)) {
            echo '<p>' . __('No bookings found for this order.', 'aqualuxe') . '</p>';
            return;
        }
        
        // Show bookings table
        $this->show_bookings_table($bookings);
        
        // Add back to orders link
        echo '<p><a href="' . wc_get_account_endpoint_url('orders') . '" class="button">' . __('Back to Orders', 'aqualuxe') . '</a></p>';
    }

    /**
     * Show bookings table
     *
     * @param array $bookings Bookings
     */
    private function show_bookings_table($bookings) {
        echo '<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">';
        echo '<thead><tr>';
        echo '<th>' . __('Booking ID', 'aqualuxe') . '</th>';
        echo '<th>' . __('Service', 'aqualuxe') . '</th>';
        echo '<th>' . __('Date', 'aqualuxe') . '</th>';
        echo '<th>' . __('Time', 'aqualuxe') . '</th>';
        echo '<th>' . __('Status', 'aqualuxe') . '</th>';
        echo '<th>' . __('Actions', 'aqualuxe') . '</th>';
        echo '</tr></thead>';
        
        echo '<tbody>';
        
        foreach ($bookings as $booking) {
            // Format date and time
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            
            $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
            $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));
            
            echo '<tr>';
            echo '<td>' . $booking['booking_id'] . '</td>';
            echo '<td>' . $booking['service_name'] . '</td>';
            echo '<td>' . $booking_date . '</td>';
            echo '<td>' . $booking_time . '</td>';
            echo '<td>' . $this->get_booking_status_label($booking['status']) . '</td>';
            echo '<td>';
            
            // Add actions based on booking status
            if ($booking['status'] === 'aqualuxe-pending' || $booking['status'] === 'aqualuxe-confirmed') {
                echo '<a href="#" class="button cancel-booking" data-booking-id="' . $booking['id'] . '" data-nonce="' . wp_create_nonce('aqualuxe-bookings') . '">' . __('Cancel', 'aqualuxe') . '</a>';
            }
            
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
        
        // Add JavaScript for cancel booking action
        wc_enqueue_js("
            jQuery('.cancel-booking').on('click', function(e) {
                e.preventDefault();
                
                if (!confirm('" . esc_js(__('Are you sure you want to cancel this booking?', 'aqualuxe')) . "')) {
                    return;
                }
                
                var button = jQuery(this);
                var booking_id = button.data('booking-id');
                var nonce = button.data('nonce');
                
                jQuery.ajax({
                    url: '" . admin_url('admin-ajax.php') . "',
                    type: 'POST',
                    data: {
                        action: 'cancel_booking',
                        booking_id: booking_id,
                        nonce: nonce
                    },
                    beforeSend: function() {
                        button.prop('disabled', true).text('" . esc_js(__('Cancelling...', 'aqualuxe')) . "');
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.data.message);
                            button.prop('disabled', false).text('" . esc_js(__('Cancel', 'aqualuxe')) . "');
                        }
                    },
                    error: function() {
                        alert('" . esc_js(__('An error occurred. Please try again.', 'aqualuxe')) . "');
                        button.prop('disabled', false).text('" . esc_js(__('Cancel', 'aqualuxe')) . "');
                    }
                });
            });
        ");
    }

    /**
     * Product options
     */
    public function product_options() {
        echo '<div class="options_group show_if_simple show_if_variable">';
        
        woocommerce_wp_checkbox(array(
            'id' => '_is_bookable',
            'label' => __('Bookable', 'aqualuxe'),
            'description' => __('Check this box if this product is a bookable service.', 'aqualuxe'),
        ));
        
        woocommerce_wp_select(array(
            'id' => '_bookable_service_id',
            'label' => __('Bookable Service', 'aqualuxe'),
            'description' => __('Select the bookable service for this product.', 'aqualuxe'),
            'options' => $this->get_bookable_services_options(),
            'desc_tip' => true,
            'wrapper_class' => 'show_if_bookable',
        ));
        
        echo '</div>';
        
        // Add JavaScript to show/hide bookable service field
        wc_enqueue_js("
            jQuery('#_is_bookable').on('change', function() {
                if (jQuery(this).is(':checked')) {
                    jQuery('.show_if_bookable').show();
                } else {
                    jQuery('.show_if_bookable').hide();
                }
            }).trigger('change');
        ");
    }

    /**
     * Save product options
     *
     * @param int $post_id Post ID
     */
    public function save_product_options($post_id) {
        // Save is bookable
        $is_bookable = isset($_POST['_is_bookable']) ? 'yes' : 'no';
        update_post_meta($post_id, '_is_bookable', $is_bookable);
        
        // Save bookable service ID
        if (isset($_POST['_bookable_service_id'])) {
            update_post_meta($post_id, '_bookable_service_id', absint($_POST['_bookable_service_id']));
            
            // Also update the service with this product ID
            $service_id = absint($_POST['_bookable_service_id']);
            
            if ($service_id > 0) {
                update_post_meta($service_id, '_product_id', $post_id);
            }
        }
    }

    /**
     * Product tabs
     *
     * @param array $tabs Product tabs
     * @return array Modified product tabs
     */
    public function product_tabs($tabs) {
        global $product;
        
        if (!$product) {
            return $tabs;
        }
        
        // Check if product is bookable
        $is_bookable = get_post_meta($product->get_id(), '_is_bookable', true);
        
        if ($is_bookable !== 'yes') {
            return $tabs;
        }
        
        // Add booking tab
        $tabs['booking'] = array(
            'title' => __('Booking', 'aqualuxe'),
            'priority' => 20,
            'callback' => array($this, 'booking_tab_content'),
        );
        
        return $tabs;
    }

    /**
     * Booking tab content
     */
    public function booking_tab_content() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        // Get bookable service ID
        $service_id = get_post_meta($product->get_id(), '_bookable_service_id', true);
        
        if (empty($service_id)) {
            echo '<p>' . __('No bookable service found.', 'aqualuxe') . '</p>';
            return;
        }
        
        // Show booking form
        echo do_shortcode('[aqualuxe_booking_form service_id="' . $service_id . '"]');
    }

    /**
     * Single product summary
     */
    public function single_product_summary() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        // Check if product is bookable
        $is_bookable = get_post_meta($product->get_id(), '_is_bookable', true);
        
        if ($is_bookable !== 'yes') {
            return;
        }
        
        // Get bookable service ID
        $service_id = get_post_meta($product->get_id(), '_bookable_service_id', true);
        
        if (empty($service_id)) {
            return;
        }
        
        // Add booking button
        echo '<div class="aqualuxe-booking-button">';
        echo '<a href="#booking" class="button alt">' . __('Book Now', 'aqualuxe') . '</a>';
        echo '</div>';
        
        // Add JavaScript to scroll to booking tab
        wc_enqueue_js("
            jQuery('.aqualuxe-booking-button a').on('click', function(e) {
                e.preventDefault();
                
                // Open booking tab
                jQuery('a[href=&quot;#tab-booking&quot;]').trigger('click');
                
                // Scroll to booking tab
                jQuery('html, body').animate({
                    scrollTop: jQuery('#tab-booking').offset().top - 100
                }, 500);
            });
        ");
    }

    /**
     * Email classes
     *
     * @param array $email_classes Email classes
     * @return array Modified email classes
     */
    public function email_classes($email_classes) {
        // Include email classes
        include_once AQUALUXE_BOOKINGS_PATH . 'inc/emails/class-bookings-email-booking-confirmed.php';
        include_once AQUALUXE_BOOKINGS_PATH . 'inc/emails/class-bookings-email-booking-cancelled.php';
        include_once AQUALUXE_BOOKINGS_PATH . 'inc/emails/class-bookings-email-booking-reminder.php';
        
        // Add email classes
        $email_classes['AquaLuxe_Bookings_Email_Booking_Confirmed'] = new AquaLuxe_Bookings_Email_Booking_Confirmed();
        $email_classes['AquaLuxe_Bookings_Email_Booking_Cancelled'] = new AquaLuxe_Bookings_Email_Booking_Cancelled();
        $email_classes['AquaLuxe_Bookings_Email_Booking_Reminder'] = new AquaLuxe_Bookings_Email_Booking_Reminder();
        
        return $email_classes;
    }

    /**
     * Email after order table
     *
     * @param WC_Order $order Order object
     * @param bool $sent_to_admin Sent to admin
     * @param bool $plain_text Plain text
     * @param WC_Email $email Email object
     */
    public function email_after_order_table($order, $sent_to_admin, $plain_text, $email) {
        // Check if order contains booking items
        $has_booking = false;
        
        foreach ($order->get_items() as $item) {
            if ($item->meta_exists('_aqualuxe_booking_id')) {
                $has_booking = true;
                break;
            }
        }
        
        if (!$has_booking) {
            return;
        }
        
        // Display bookings
        if ($plain_text) {
            echo "\n\n" . __('Bookings', 'aqualuxe') . "\n\n";
            
            foreach ($order->get_items() as $item) {
                if (!$item->meta_exists('_aqualuxe_booking_id')) {
                    continue;
                }
                
                // Get booking ID
                $booking_id = $item->get_meta('_aqualuxe_booking_id');
                
                // Get booking data
                $bookings_data = new AquaLuxe_Bookings_Data();
                $booking = $bookings_data->get_booking($booking_id);
                
                if (!$booking) {
                    continue;
                }
                
                // Format date and time
                $date_format = get_option('date_format');
                $time_format = get_option('time_format');
                
                $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
                $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));
                
                echo __('Booking ID:', 'aqualuxe') . ' ' . $booking['booking_id'] . "\n";
                echo __('Service:', 'aqualuxe') . ' ' . $booking['service_name'] . "\n";
                echo __('Date:', 'aqualuxe') . ' ' . $booking_date . "\n";
                echo __('Time:', 'aqualuxe') . ' ' . $booking_time . "\n";
                echo __('Status:', 'aqualuxe') . ' ' . $this->get_booking_status_name($booking['status']) . "\n\n";
            }
        } else {
            echo '<h2>' . __('Bookings', 'aqualuxe') . '</h2>';
            echo '<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; margin: 0 0 16px;" border="1">';
            echo '<thead><tr>';
            echo '<th class="td" scope="col" style="text-align: left;">' . __('Booking ID', 'aqualuxe') . '</th>';
            echo '<th class="td" scope="col" style="text-align: left;">' . __('Service', 'aqualuxe') . '</th>';
            echo '<th class="td" scope="col" style="text-align: left;">' . __('Date', 'aqualuxe') . '</th>';
            echo '<th class="td" scope="col" style="text-align: left;">' . __('Time', 'aqualuxe') . '</th>';
            echo '<th class="td" scope="col" style="text-align: left;">' . __('Status', 'aqualuxe') . '</th>';
            echo '</tr></thead>';
            
            echo '<tbody>';
            
            foreach ($order->get_items() as $item) {
                if (!$item->meta_exists('_aqualuxe_booking_id')) {
                    continue;
                }
                
                // Get booking ID
                $booking_id = $item->get_meta('_aqualuxe_booking_id');
                
                // Get booking data
                $bookings_data = new AquaLuxe_Bookings_Data();
                $booking = $bookings_data->get_booking($booking_id);
                
                if (!$booking) {
                    continue;
                }
                
                // Format date and time
                $date_format = get_option('date_format');
                $time_format = get_option('time_format');
                
                $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
                $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));
                
                echo '<tr>';
                echo '<td class="td" style="text-align: left; vertical-align: middle;">' . $booking['booking_id'] . '</td>';
                echo '<td class="td" style="text-align: left; vertical-align: middle;">' . $booking['service_name'] . '</td>';
                echo '<td class="td" style="text-align: left; vertical-align: middle;">' . $booking_date . '</td>';
                echo '<td class="td" style="text-align: left; vertical-align: middle;">' . $booking_time . '</td>';
                echo '<td class="td" style="text-align: left; vertical-align: middle;">' . $this->get_booking_status_name($booking['status']) . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody></table>';
        }
    }

    /**
     * Get bookable services options
     *
     * @return array Bookable services options
     */
    private function get_bookable_services_options() {
        $options = array(
            '' => __('Select a service', 'aqualuxe'),
        );
        
        $services = get_posts(array(
            'post_type' => 'bookable_service',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));
        
        foreach ($services as $service) {
            $options[$service->ID] = $service->post_title;
        }
        
        return $options;
    }

    /**
     * Get booking status label
     *
     * @param string $status Status
     * @return string Status label
     */
    private function get_booking_status_label($status) {
        switch ($status) {
            case 'aqualuxe-pending':
                return '<span class="booking-status booking-status-pending">' . __('Pending', 'aqualuxe') . '</span>';
            case 'aqualuxe-confirmed':
                return '<span class="booking-status booking-status-confirmed">' . __('Confirmed', 'aqualuxe') . '</span>';
            case 'aqualuxe-completed':
                return '<span class="booking-status booking-status-completed">' . __('Completed', 'aqualuxe') . '</span>';
            case 'aqualuxe-cancelled':
                return '<span class="booking-status booking-status-cancelled">' . __('Cancelled', 'aqualuxe') . '</span>';
            default:
                return '<span class="booking-status">' . ucfirst($status) . '</span>';
        }
    }

    /**
     * Get booking status name
     *
     * @param string $status Status
     * @return string Status name
     */
    private function get_booking_status_name($status) {
        switch ($status) {
            case 'aqualuxe-pending':
                return __('Pending', 'aqualuxe');
            case 'aqualuxe-confirmed':
                return __('Confirmed', 'aqualuxe');
            case 'aqualuxe-completed':
                return __('Completed', 'aqualuxe');
            case 'aqualuxe-cancelled':
                return __('Cancelled', 'aqualuxe');
            default:
                return ucfirst($status);
        }
    }
}