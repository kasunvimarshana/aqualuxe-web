<?php
/**
 * Booking dashboard functionality
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Initialize booking dashboard
 */
function aqualuxe_bookings_initialize_dashboard() {
    // Register AJAX actions
    add_action('wp_ajax_aqualuxe_cancel_booking', 'aqualuxe_bookings_ajax_cancel_booking');
    add_action('wp_ajax_aqualuxe_admin_update_booking', 'aqualuxe_bookings_ajax_admin_update_booking');
    
    // Add booking endpoint to My Account
    add_action('init', 'aqualuxe_bookings_add_endpoints');
    add_filter('query_vars', 'aqualuxe_bookings_add_query_vars');
    add_filter('woocommerce_account_menu_items', 'aqualuxe_bookings_add_menu_items');
    add_action('woocommerce_account_bookings_endpoint', 'aqualuxe_bookings_endpoint_content');
    
    // Add booking dashboard widgets
    add_action('wp_dashboard_setup', 'aqualuxe_bookings_add_dashboard_widgets');
    
    // Add booking admin menu
    add_action('admin_menu', 'aqualuxe_bookings_add_admin_menu');
}

/**
 * AJAX handler for cancelling bookings
 */
function aqualuxe_bookings_ajax_cancel_booking() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_bookings_nonce')) {
        wp_send_json_error(array(
            'message' => __('Security check failed.', 'aqualuxe'),
        ));
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => __('You must be logged in to cancel a booking.', 'aqualuxe'),
        ));
    }
    
    // Check booking ID
    if (!isset($_POST['booking_id'])) {
        wp_send_json_error(array(
            'message' => __('Invalid booking ID.', 'aqualuxe'),
        ));
    }
    
    $booking_id = absint($_POST['booking_id']);
    $booking = aqualuxe_bookings_get_booking($booking_id);
    
    if (!$booking) {
        wp_send_json_error(array(
            'message' => __('Booking not found.', 'aqualuxe'),
        ));
    }
    
    // Check if user owns the booking
    $user_id = get_current_user_id();
    
    if ($booking['user_id'] != $user_id && !current_user_can('manage_woocommerce')) {
        wp_send_json_error(array(
            'message' => __('You do not have permission to cancel this booking.', 'aqualuxe'),
        ));
    }
    
    // Check if booking can be cancelled
    $product_id = $booking['product_id'];
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        wp_send_json_error(array(
            'message' => __('Invalid booking product.', 'aqualuxe'),
        ));
    }
    
    $can_be_cancelled = get_post_meta($product_id, '_booking_can_be_cancelled', true) === 'yes';
    
    if (!$can_be_cancelled && !current_user_can('manage_woocommerce')) {
        wp_send_json_error(array(
            'message' => __('This booking cannot be cancelled.', 'aqualuxe'),
        ));
    }
    
    // Check cancellation limit
    if (!current_user_can('manage_woocommerce')) {
        $cancel_limit = (int) get_post_meta($product_id, '_booking_cancel_limit', true);
        $cancel_limit_unit = get_post_meta($product_id, '_booking_cancel_limit_unit', true);
        
        if ($cancel_limit > 0) {
            $start_timestamp = strtotime($booking['start_date']);
            $current_timestamp = current_time('timestamp');
            $limit_seconds = 0;
            
            if ($cancel_limit_unit === 'hour') {
                $limit_seconds = $cancel_limit * HOUR_IN_SECONDS;
            } elseif ($cancel_limit_unit === 'day') {
                $limit_seconds = $cancel_limit * DAY_IN_SECONDS;
            }
            
            if ($start_timestamp - $current_timestamp < $limit_seconds) {
                wp_send_json_error(array(
                    'message' => sprintf(
                        __('This booking cannot be cancelled less than %d %s before the start time.', 'aqualuxe'),
                        $cancel_limit,
                        $cancel_limit_unit === 'hour' ? __('hours', 'aqualuxe') : __('days', 'aqualuxe')
                    ),
                ));
            }
        }
    }
    
    // Cancel booking
    $result = aqualuxe_bookings_update_status($booking_id, 'cancelled');
    
    if (!$result) {
        wp_send_json_error(array(
            'message' => __('Failed to cancel booking.', 'aqualuxe'),
        ));
    }
    
    wp_send_json_success(array(
        'message' => __('Booking cancelled successfully.', 'aqualuxe'),
    ));
}

/**
 * AJAX handler for admin updating bookings
 */
function aqualuxe_bookings_ajax_admin_update_booking() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_bookings_admin_nonce')) {
        wp_send_json_error(array(
            'message' => __('Security check failed.', 'aqualuxe'),
        ));
    }
    
    // Check if user has permission
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error(array(
            'message' => __('You do not have permission to update bookings.', 'aqualuxe'),
        ));
    }
    
    // Check booking ID
    if (!isset($_POST['booking_id'])) {
        wp_send_json_error(array(
            'message' => __('Invalid booking ID.', 'aqualuxe'),
        ));
    }
    
    $booking_id = absint($_POST['booking_id']);
    $booking = aqualuxe_bookings_get_booking($booking_id);
    
    if (!$booking) {
        wp_send_json_error(array(
            'message' => __('Booking not found.', 'aqualuxe'),
        ));
    }
    
    // Prepare booking data
    $booking_data = array();
    
    if (isset($_POST['status'])) {
        $booking_data['status'] = sanitize_text_field($_POST['status']);
    }
    
    if (isset($_POST['start_date'])) {
        $booking_data['start_date'] = sanitize_text_field($_POST['start_date']);
    }
    
    if (isset($_POST['end_date'])) {
        $booking_data['end_date'] = sanitize_text_field($_POST['end_date']);
    }
    
    if (isset($_POST['quantity'])) {
        $booking_data['quantity'] = absint($_POST['quantity']);
    }
    
    if (isset($_POST['cost'])) {
        $booking_data['cost'] = (float) $_POST['cost'];
    }
    
    // Update booking
    $result = aqualuxe_bookings_update_booking($booking_id, $booking_data);
    
    if (!$result) {
        wp_send_json_error(array(
            'message' => __('Failed to update booking.', 'aqualuxe'),
        ));
    }
    
    wp_send_json_success(array(
        'message' => __('Booking updated successfully.', 'aqualuxe'),
    ));
}

/**
 * Add booking endpoints to My Account
 */
function aqualuxe_bookings_add_endpoints() {
    add_rewrite_endpoint('bookings', EP_ROOT | EP_PAGES);
}

/**
 * Add booking query vars
 *
 * @param array $vars Query vars
 * @return array
 */
function aqualuxe_bookings_add_query_vars($vars) {
    $vars[] = 'bookings';
    return $vars;
}

/**
 * Add booking menu items to My Account
 *
 * @param array $items Menu items
 * @return array
 */
function aqualuxe_bookings_add_menu_items($items) {
    // Add bookings item after orders
    $new_items = array();
    
    foreach ($items as $key => $item) {
        $new_items[$key] = $item;
        
        if ($key === 'orders') {
            $new_items['bookings'] = __('My Bookings', 'aqualuxe');
        }
    }
    
    return $new_items;
}

/**
 * Booking endpoint content
 */
function aqualuxe_bookings_endpoint_content() {
    // Get user bookings
    $user_id = get_current_user_id();
    $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
    $bookings = aqualuxe_bookings_get_user_bookings($user_id, $status);
    
    // Display tabs
    ?>
    <div class="aqualuxe-my-bookings">
        <ul class="aqualuxe-tabs">
            <li class="<?php echo $status === '' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('bookings')); ?>">
                    <?php esc_html_e('All Bookings', 'aqualuxe'); ?>
                </a>
            </li>
            <li class="<?php echo $status === 'confirmed' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(add_query_arg('status', 'confirmed', wc_get_account_endpoint_url('bookings'))); ?>">
                    <?php esc_html_e('Confirmed', 'aqualuxe'); ?>
                </a>
            </li>
            <li class="<?php echo $status === 'pending' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(add_query_arg('status', 'pending', wc_get_account_endpoint_url('bookings'))); ?>">
                    <?php esc_html_e('Pending', 'aqualuxe'); ?>
                </a>
            </li>
            <li class="<?php echo $status === 'completed' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(add_query_arg('status', 'completed', wc_get_account_endpoint_url('bookings'))); ?>">
                    <?php esc_html_e('Completed', 'aqualuxe'); ?>
                </a>
            </li>
            <li class="<?php echo $status === 'cancelled' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(add_query_arg('status', 'cancelled', wc_get_account_endpoint_url('bookings'))); ?>">
                    <?php esc_html_e('Cancelled', 'aqualuxe'); ?>
                </a>
            </li>
        </ul>
        
        <div class="aqualuxe-tab-content">
            <?php if (!empty($bookings)) : ?>
                <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Booking', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Total', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($bookings as $booking) {
                            $product = wc_get_product($booking['product_id']);
                            
                            if (!$product) {
                                continue;
                            }
                            
                            $order = $booking['order_id'] ? wc_get_order($booking['order_id']) : null;
                            $booking_meta = maybe_unserialize($booking['meta']);
                            $booking_type = isset($booking_meta['booking_type']) ? $booking_meta['booking_type'] : '';
                            
                            ?>
                            <tr>
                                <td data-title="<?php esc_html_e('Booking', 'aqualuxe'); ?>">
                                    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                                        <?php echo esc_html($product->get_name()); ?>
                                    </a>
                                </td>
                                <td data-title="<?php esc_html_e('Date', 'aqualuxe'); ?>">
                                    <?php
                                    if ($booking_type === 'date_time' || $booking_type === 'fixed_time') {
                                        echo esc_html(aqualuxe_bookings_format_date($booking['start_date'])) . '<br>';
                                        echo esc_html(aqualuxe_bookings_format_time($booking['start_date'])) . ' - ' . esc_html(aqualuxe_bookings_format_time($booking['end_date']));
                                    } else {
                                        echo esc_html(aqualuxe_bookings_format_date($booking['start_date']));
                                        
                                        if ($booking['start_date'] !== $booking['end_date']) {
                                            echo ' - ' . esc_html(aqualuxe_bookings_format_date($booking['end_date']));
                                        }
                                    }
                                    ?>
                                </td>
                                <td data-title="<?php esc_html_e('Status', 'aqualuxe'); ?>">
                                    <span class="booking-status booking-status-<?php echo esc_attr($booking['status']); ?>">
                                        <?php echo esc_html(aqualuxe_bookings_get_status_label($booking['status'])); ?>
                                    </span>
                                </td>
                                <td data-title="<?php esc_html_e('Total', 'aqualuxe'); ?>">
                                    <?php echo wc_price($booking['cost']); ?>
                                </td>
                                <td data-title="<?php esc_html_e('Actions', 'aqualuxe'); ?>">
                                    <?php
                                    // View order button
                                    if ($order) {
                                        echo '<a href="' . esc_url($order->get_view_order_url()) . '" class="woocommerce-button button view">' . esc_html__('View Order', 'aqualuxe') . '</a>';
                                    }
                                    
                                    // Cancel button
                                    $can_be_cancelled = get_post_meta($product->get_id(), '_booking_can_be_cancelled', true) === 'yes';
                                    
                                    if ($can_be_cancelled && ($booking['status'] === 'confirmed' || $booking['status'] === 'pending')) {
                                        $cancel_limit = (int) get_post_meta($product->get_id(), '_booking_cancel_limit', true);
                                        $cancel_limit_unit = get_post_meta($product->get_id(), '_booking_cancel_limit_unit', true);
                                        $can_cancel = true;
                                        
                                        if ($cancel_limit > 0) {
                                            $start_timestamp = strtotime($booking['start_date']);
                                            $current_timestamp = current_time('timestamp');
                                            $limit_seconds = 0;
                                            
                                            if ($cancel_limit_unit === 'hour') {
                                                $limit_seconds = $cancel_limit * HOUR_IN_SECONDS;
                                            } elseif ($cancel_limit_unit === 'day') {
                                                $limit_seconds = $cancel_limit * DAY_IN_SECONDS;
                                            }
                                            
                                            if ($start_timestamp - $current_timestamp < $limit_seconds) {
                                                $can_cancel = false;
                                            }
                                        }
                                        
                                        if ($can_cancel) {
                                            echo '<button type="button" class="woocommerce-button button cancel-booking" data-booking-id="' . esc_attr($booking['id']) . '">' . esc_html__('Cancel', 'aqualuxe') . '</button>';
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                
                <script type="text/javascript">
                    jQuery(function($) {
                        $('.cancel-booking').on('click', function() {
                            var bookingId = $(this).data('booking-id');
                            
                            if (confirm('<?php esc_html_e('Are you sure you want to cancel this booking?', 'aqualuxe'); ?>')) {
                                $.ajax({
                                    url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
                                    type: 'POST',
                                    data: {
                                        action: 'aqualuxe_cancel_booking',
                                        booking_id: bookingId,
                                        nonce: '<?php echo wp_create_nonce('aqualuxe_bookings_nonce'); ?>'
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            alert(response.data.message);
                                            location.reload();
                                        } else {
                                            alert(response.data.message);
                                        }
                                    },
                                    error: function() {
                                        alert('<?php esc_html_e('An error occurred. Please try again.', 'aqualuxe'); ?>');
                                    }
                                });
                            }
                        });
                    });
                </script>
            <?php else : ?>
                <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
                    <?php esc_html_e('No bookings found.', 'aqualuxe'); ?>
                    <a class="woocommerce-Button button" href="<?php echo esc_url(aqualuxe_bookings_get_archive_url()); ?>">
                        <?php esc_html_e('Browse Bookable Products', 'aqualuxe'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Add booking dashboard widgets
 */
function aqualuxe_bookings_add_dashboard_widgets() {
    // Only for admin users
    if (!current_user_can('manage_options')) {
        return;
    }
    
    wp_add_dashboard_widget(
        'aqualuxe_bookings_dashboard_widget',
        __('Booking Statistics', 'aqualuxe'),
        'aqualuxe_bookings_dashboard_widget_content'
    );
}

/**
 * Booking dashboard widget content
 */
function aqualuxe_bookings_dashboard_widget_content() {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    // Get booking statistics
    $total_bookings = $wpdb->get_var("SELECT COUNT(*) FROM {$bookings_table}");
    
    $pending_bookings = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$bookings_table} WHERE status = %s",
        'pending'
    ));
    
    $confirmed_bookings = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$bookings_table} WHERE status = %s",
        'confirmed'
    ));
    
    $completed_bookings = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$bookings_table} WHERE status = %s",
        'completed'
    ));
    
    $cancelled_bookings = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$bookings_table} WHERE status = %s",
        'cancelled'
    ));
    
    $upcoming_bookings = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$bookings_table} WHERE status = %s AND start_date >= %s",
        'confirmed',
        current_time('mysql')
    ));
    
    // Get recent bookings
    $recent_bookings = $wpdb->get_results(
        "SELECT b.*, p.post_title
         FROM {$bookings_table} b
         JOIN {$wpdb->posts} p ON b.product_id = p.ID
         ORDER BY b.date_created DESC
         LIMIT 5"
    );
    
    ?>
    <div class="aqualuxe-booking-stats">
        <div class="aqualuxe-booking-stat">
            <span class="stat-value"><?php echo esc_html($total_bookings); ?></span>
            <span class="stat-label"><?php esc_html_e('Total Bookings', 'aqualuxe'); ?></span>
        </div>
        
        <div class="aqualuxe-booking-stat">
            <span class="stat-value"><?php echo esc_html($pending_bookings); ?></span>
            <span class="stat-label"><?php esc_html_e('Pending', 'aqualuxe'); ?></span>
        </div>
        
        <div class="aqualuxe-booking-stat">
            <span class="stat-value"><?php echo esc_html($confirmed_bookings); ?></span>
            <span class="stat-label"><?php esc_html_e('Confirmed', 'aqualuxe'); ?></span>
        </div>
        
        <div class="aqualuxe-booking-stat">
            <span class="stat-value"><?php echo esc_html($completed_bookings); ?></span>
            <span class="stat-label"><?php esc_html_e('Completed', 'aqualuxe'); ?></span>
        </div>
        
        <div class="aqualuxe-booking-stat">
            <span class="stat-value"><?php echo esc_html($cancelled_bookings); ?></span>
            <span class="stat-label"><?php esc_html_e('Cancelled', 'aqualuxe'); ?></span>
        </div>
        
        <div class="aqualuxe-booking-stat">
            <span class="stat-value"><?php echo esc_html($upcoming_bookings); ?></span>
            <span class="stat-label"><?php esc_html_e('Upcoming', 'aqualuxe'); ?></span>
        </div>
    </div>
    
    <h3><?php esc_html_e('Recent Bookings', 'aqualuxe'); ?></h3>
    
    <?php if (!empty($recent_bookings)) : ?>
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php esc_html_e('ID', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                    <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($recent_bookings as $booking) {
                    echo '<tr>';
                    echo '<td>' . esc_html($booking->id) . '</td>';
                    echo '<td>' . esc_html($booking->post_title) . '</td>';
                    echo '<td>' . esc_html(aqualuxe_bookings_format_datetime($booking->start_date)) . '</td>';
                    echo '<td>' . esc_html(aqualuxe_bookings_get_status_label($booking->status)) . '</td>';
                    echo '<td><a href="' . esc_url(admin_url('admin.php?page=aqualuxe-bookings&view=booking&id=' . $booking->id)) . '" class="button">' . esc_html__('View', 'aqualuxe') . '</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    <?php else : ?>
        <p><?php esc_html_e('No bookings found.', 'aqualuxe'); ?></p>
    <?php endif; ?>
    
    <p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-bookings')); ?>" class="button">
            <?php esc_html_e('View All Bookings', 'aqualuxe'); ?>
        </a>
    </p>
    <?php
}

/**
 * Add booking admin menu
 */
function aqualuxe_bookings_add_admin_menu() {
    add_menu_page(
        __('Bookings', 'aqualuxe'),
        __('Bookings', 'aqualuxe'),
        'manage_woocommerce',
        'aqualuxe-bookings',
        'aqualuxe_bookings_admin_page',
        'dashicons-calendar-alt',
        56
    );
    
    add_submenu_page(
        'aqualuxe-bookings',
        __('Booking Dashboard', 'aqualuxe'),
        __('Dashboard', 'aqualuxe'),
        'manage_woocommerce',
        'aqualuxe-bookings',
        'aqualuxe_bookings_admin_page'
    );
    
    add_submenu_page(
        'aqualuxe-bookings',
        __('Add Booking', 'aqualuxe'),
        __('Add Booking', 'aqualuxe'),
        'manage_woocommerce',
        'aqualuxe-add-booking',
        'aqualuxe_bookings_admin_add_page'
    );
    
    add_submenu_page(
        'aqualuxe-bookings',
        __('Calendar', 'aqualuxe'),
        __('Calendar', 'aqualuxe'),
        'manage_woocommerce',
        'aqualuxe-bookings-calendar',
        'aqualuxe_bookings_admin_calendar_page'
    );
    
    add_submenu_page(
        'aqualuxe-bookings',
        __('Booking Settings', 'aqualuxe'),
        __('Settings', 'aqualuxe'),
        'manage_woocommerce',
        'admin.php?page=aqualuxe-module-bookings',
        null
    );
}

/**
 * Booking admin page
 */
function aqualuxe_bookings_admin_page() {
    // Check user capabilities
    if (!current_user_can('manage_woocommerce')) {
        return;
    }
    
    // Check if viewing a single booking
    if (isset($_GET['view']) && $_GET['view'] === 'booking' && isset($_GET['id'])) {
        aqualuxe_bookings_admin_view_booking();
        return;
    }
    
    // Get current tab
    $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'upcoming';
    
    // Get tabs
    $tabs = array(
        'upcoming' => __('Upcoming', 'aqualuxe'),
        'pending' => __('Pending', 'aqualuxe'),
        'confirmed' => __('Confirmed', 'aqualuxe'),
        'completed' => __('Completed', 'aqualuxe'),
        'cancelled' => __('Cancelled', 'aqualuxe'),
        'all' => __('All', 'aqualuxe'),
    );
    
    // Get bookings
    $status = $current_tab === 'all' ? '' : $current_tab;
    $product_id = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;
    $user_id = isset($_GET['user_id']) ? absint($_GET['user_id']) : 0;
    
    $args = array();
    
    if ($product_id) {
        $args['product_id'] = $product_id;
    }
    
    if ($user_id) {
        $args['user_id'] = $user_id;
    }
    
    // Get bookings
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    $query = "SELECT b.*, p.post_title, u.display_name
              FROM {$bookings_table} b
              JOIN {$wpdb->posts} p ON b.product_id = p.ID
              LEFT JOIN {$wpdb->users} u ON b.user_id = u.ID";
    
    $where = array();
    $params = array();
    
    if ($status) {
        $where[] = "b.status = %s";
        $params[] = $status;
    }
    
    if ($product_id) {
        $where[] = "b.product_id = %d";
        $params[] = $product_id;
    }
    
    if ($user_id) {
        $where[] = "b.user_id = %d";
        $params[] = $user_id;
    }
    
    if ($current_tab === 'upcoming') {
        $where[] = "b.status = %s AND b.start_date >= %s";
        $params[] = 'confirmed';
        $params[] = current_time('mysql');
    }
    
    if (!empty($where)) {
        $query .= " WHERE " . implode(" AND ", $where);
    }
    
    $query .= " ORDER BY b.start_date DESC";
    
    $bookings = $wpdb->get_results($wpdb->prepare($query, $params));
    
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Bookings', 'aqualuxe'); ?></h1>
        
        <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
            <?php
            foreach ($tabs as $tab_id => $tab_name) {
                $active = $current_tab === $tab_id ? ' nav-tab-active' : '';
                echo '<a href="' . esc_url(admin_url('admin.php?page=aqualuxe-bookings&tab=' . $tab_id)) . '" class="nav-tab' . esc_attr($active) . '">' . esc_html($tab_name) . '</a>';
            }
            ?>
        </nav>
        
        <div class="tab-content">
            <div class="tablenav top">
                <div class="alignleft actions">
                    <form method="get">
                        <input type="hidden" name="page" value="aqualuxe-bookings">
                        <input type="hidden" name="tab" value="<?php echo esc_attr($current_tab); ?>">
                        
                        <select name="product_id">
                            <option value=""><?php esc_html_e('All Products', 'aqualuxe'); ?></option>
                            <?php
                            $products = wc_get_products(array(
                                'type' => 'booking',
                                'limit' => -1,
                                'return' => 'ids',
                            ));
                            
                            foreach ($products as $id) {
                                $product = wc_get_product($id);
                                echo '<option value="' . esc_attr($id) . '" ' . selected($product_id, $id, false) . '>' . esc_html($product->get_name()) . '</option>';
                            }
                            ?>
                        </select>
                        
                        <input type="submit" class="button" value="<?php esc_attr_e('Filter', 'aqualuxe'); ?>">
                    </form>
                </div>
                
                <div class="alignright">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-add-booking')); ?>" class="button button-primary">
                        <?php esc_html_e('Add Booking', 'aqualuxe'); ?>
                    </a>
                </div>
                
                <br class="clear">
            </div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('ID', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Customer', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Start Date', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('End Date', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($bookings)) {
                        foreach ($bookings as $booking) {
                            echo '<tr>';
                            echo '<td>' . esc_html($booking->id) . '</td>';
                            echo '<td><a href="' . esc_url(get_edit_post_link($booking->product_id)) . '">' . esc_html($booking->post_title) . '</a></td>';
                            echo '<td><a href="' . esc_url(get_edit_user_link($booking->user_id)) . '">' . esc_html($booking->display_name) . '</a></td>';
                            echo '<td>' . esc_html(aqualuxe_bookings_format_datetime($booking->start_date)) . '</td>';
                            echo '<td>' . esc_html(aqualuxe_bookings_format_datetime($booking->end_date)) . '</td>';
                            echo '<td><span class="booking-status booking-status-' . esc_attr($booking->status) . '">' . esc_html(aqualuxe_bookings_get_status_label($booking->status)) . '</span></td>';
                            echo '<td>';
                            echo '<a href="' . esc_url(admin_url('admin.php?page=aqualuxe-bookings&view=booking&id=' . $booking->id)) . '" class="button">' . esc_html__('View', 'aqualuxe') . '</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7">' . esc_html__('No bookings found.', 'aqualuxe') . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

/**
 * View booking admin page
 */
function aqualuxe_bookings_admin_view_booking() {
    // Check user capabilities
    if (!current_user_can('manage_woocommerce')) {
        return;
    }
    
    // Get booking ID
    $booking_id = isset($_GET['id']) ? absint($_GET['id']) : 0;
    
    if (!$booking_id) {
        wp_die(__('Invalid booking ID.', 'aqualuxe'));
    }
    
    // Get booking
    $booking = aqualuxe_bookings_get_booking($booking_id);
    
    if (!$booking) {
        wp_die(__('Booking not found.', 'aqualuxe'));
    }
    
    // Get product
    $product = wc_get_product($booking['product_id']);
    
    if (!$product) {
        wp_die(__('Product not found.', 'aqualuxe'));
    }
    
    // Get user
    $user = get_user_by('id', $booking['user_id']);
    
    if (!$user) {
        wp_die(__('User not found.', 'aqualuxe'));
    }
    
    // Get order
    $order = $booking['order_id'] ? wc_get_order($booking['order_id']) : null;
    
    // Get booking meta
    $booking_meta = maybe_unserialize($booking['meta']);
    $booking_type = isset($booking_meta['booking_type']) ? $booking_meta['booking_type'] : '';
    
    ?>
    <div class="wrap">
        <h1><?php printf(__('Booking #%s', 'aqualuxe'), $booking_id); ?></h1>
        
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <div class="postbox">
                        <h2 class="hndle"><?php esc_html_e('Booking Details', 'aqualuxe'); ?></h2>
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                                    <td>
                                        <a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>">
                                            <?php echo esc_html($product->get_name()); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Customer', 'aqualuxe'); ?></th>
                                    <td>
                                        <a href="<?php echo esc_url(get_edit_user_link($user->ID)); ?>">
                                            <?php echo esc_html($user->display_name); ?> (<?php echo esc_html($user->user_email); ?>)
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Order', 'aqualuxe'); ?></th>
                                    <td>
                                        <?php
                                        if ($order) {
                                            echo '<a href="' . esc_url($order->get_edit_order_url()) . '">';
                                            echo esc_html(sprintf(__('Order #%s', 'aqualuxe'), $order->get_order_number()));
                                            echo '</a>';
                                        } else {
                                            echo '&mdash;';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Date Created', 'aqualuxe'); ?></th>
                                    <td><?php echo esc_html(aqualuxe_bookings_format_datetime($booking['date_created'])); ?></td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Start Date', 'aqualuxe'); ?></th>
                                    <td><?php echo esc_html(aqualuxe_bookings_format_datetime($booking['start_date'])); ?></td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('End Date', 'aqualuxe'); ?></th>
                                    <td><?php echo esc_html(aqualuxe_bookings_format_datetime($booking['end_date'])); ?></td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                                    <td>
                                        <select id="booking_status">
                                            <?php
                                            $statuses = aqualuxe_bookings_get_status_labels();
                                            
                                            foreach ($statuses as $status => $label) {
                                                echo '<option value="' . esc_attr($status) . '" ' . selected($booking['status'], $status, false) . '>' . esc_html($label) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <button type="button" id="update_status" class="button"><?php esc_html_e('Update', 'aqualuxe'); ?></button>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Quantity', 'aqualuxe'); ?></th>
                                    <td><?php echo esc_html($booking['quantity']); ?></td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Cost', 'aqualuxe'); ?></th>
                                    <td><?php echo wc_price($booking['cost']); ?></td>
                                </tr>
                                <?php if ($booking_type) : ?>
                                    <tr>
                                        <th><?php esc_html_e('Booking Type', 'aqualuxe'); ?></th>
                                        <td>
                                            <?php
                                            $booking_types = aqualuxe_bookings_get_types();
                                            echo isset($booking_types[$booking_type]) ? esc_html($booking_types[$booking_type]) : esc_html($booking_type);
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div id="postbox-container-1" class="postbox-container">
                    <div class="postbox">
                        <h2 class="hndle"><?php esc_html_e('Actions', 'aqualuxe'); ?></h2>
                        <div class="inside">
                            <div class="submitbox">
                                <div id="major-publishing-actions">
                                    <div id="publishing-action">
                                        <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-bookings')); ?>" class="button"><?php esc_html_e('Back to Bookings', 'aqualuxe'); ?></a>
                                    </div>
                                    <div id="delete-action">
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=aqualuxe_delete_booking&id=' . $booking_id), 'delete_booking_' . $booking_id)); ?>" class="submitdelete deletion" onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this booking?', 'aqualuxe'); ?>');"><?php esc_html_e('Delete Booking', 'aqualuxe'); ?></a>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
            jQuery(function($) {
                $('#update_status').on('click', function() {
                    var status = $('#booking_status').val();
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_admin_update_booking',
                            booking_id: <?php echo esc_js($booking_id); ?>,
                            status: status,
                            nonce: '<?php echo wp_create_nonce('aqualuxe_bookings_admin_nonce'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.data.message);
                                location.reload();
                            } else {
                                alert(response.data.message);
                            }
                        },
                        error: function() {
                            alert('<?php esc_html_e('An error occurred. Please try again.', 'aqualuxe'); ?>');
                        }
                    });
                });
            });
        </script>
    </div>
    <?php
}

/**
 * Add booking admin page
 */
function aqualuxe_bookings_admin_add_page() {
    // Check user capabilities
    if (!current_user_can('manage_woocommerce')) {
        return;
    }
    
    // Process form submission
    if (isset($_POST['create_booking']) && isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'create_booking')) {
        $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
        $user_id = isset($_POST['user_id']) ? absint($_POST['user_id']) : 0;
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'pending';
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
        $cost = isset($_POST['cost']) ? (float) $_POST['cost'] : 0;
        
        // Validate required fields
        $errors = array();
        
        if (!$product_id) {
            $errors[] = __('Please select a product.', 'aqualuxe');
        }
        
        if (!$user_id) {
            $errors[] = __('Please select a user.', 'aqualuxe');
        }
        
        if (!$start_date) {
            $errors[] = __('Please enter a start date.', 'aqualuxe');
        }
        
        if (!$end_date) {
            $errors[] = __('Please enter an end date.', 'aqualuxe');
        }
        
        if (empty($errors)) {
            // Create booking
            $booking_data = array(
                'product_id' => $product_id,
                'user_id' => $user_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => $status,
                'quantity' => $quantity,
                'cost' => $cost,
            );
            
            $booking_id = aqualuxe_bookings_create_booking($booking_data);
            
            if ($booking_id) {
                wp_redirect(admin_url('admin.php?page=aqualuxe-bookings&view=booking&id=' . $booking_id));
                exit;
            } else {
                $errors[] = __('Failed to create booking.', 'aqualuxe');
            }
        }
    }
    
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Add Booking', 'aqualuxe'); ?></h1>
        
        <?php if (!empty($errors)) : ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo esc_html($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <?php wp_nonce_field('create_booking'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="product_id"><?php esc_html_e('Product', 'aqualuxe'); ?></label></th>
                    <td>
                        <select name="product_id" id="product_id" class="regular-text" required>
                            <option value=""><?php esc_html_e('Select a product', 'aqualuxe'); ?></option>
                            <?php
                            $products = wc_get_products(array(
                                'type' => 'booking',
                                'limit' => -1,
                                'return' => 'ids',
                            ));
                            
                            foreach ($products as $id) {
                                $product = wc_get_product($id);
                                echo '<option value="' . esc_attr($id) . '">' . esc_html($product->get_name()) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="user_id"><?php esc_html_e('Customer', 'aqualuxe'); ?></label></th>
                    <td>
                        <select name="user_id" id="user_id" class="regular-text" required>
                            <option value=""><?php esc_html_e('Select a customer', 'aqualuxe'); ?></option>
                            <?php
                            $users = get_users(array(
                                'role__in' => array('customer', 'subscriber'),
                                'orderby' => 'display_name',
                            ));
                            
                            foreach ($users as $user) {
                                echo '<option value="' . esc_attr($user->ID) . '">' . esc_html($user->display_name) . ' (' . esc_html($user->user_email) . ')</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="start_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label></th>
                    <td>
                        <input type="datetime-local" name="start_date" id="start_date" class="regular-text" required>
                    </td>
                </tr>
                <tr>
                    <th><label for="end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label></th>
                    <td>
                        <input type="datetime-local" name="end_date" id="end_date" class="regular-text" required>
                    </td>
                </tr>
                <tr>
                    <th><label for="status"><?php esc_html_e('Status', 'aqualuxe'); ?></label></th>
                    <td>
                        <select name="status" id="status" class="regular-text">
                            <?php
                            $statuses = aqualuxe_bookings_get_status_labels();
                            
                            foreach ($statuses as $status => $label) {
                                echo '<option value="' . esc_attr($status) . '">' . esc_html($label) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="quantity"><?php esc_html_e('Quantity', 'aqualuxe'); ?></label></th>
                    <td>
                        <input type="number" name="quantity" id="quantity" class="regular-text" value="1" min="1" step="1">
                    </td>
                </tr>
                <tr>
                    <th><label for="cost"><?php esc_html_e('Cost', 'aqualuxe'); ?></label></th>
                    <td>
                        <input type="number" name="cost" id="cost" class="regular-text" value="0" min="0" step="0.01">
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="create_booking" class="button button-primary" value="<?php esc_attr_e('Create Booking', 'aqualuxe'); ?>">
            </p>
        </form>
    </div>
    <?php
}

/**
 * Booking calendar admin page
 */
function aqualuxe_bookings_admin_calendar_page() {
    // Check user capabilities
    if (!current_user_can('manage_woocommerce')) {
        return;
    }
    
    // Get current month and year
    $month = isset($_GET['month']) ? absint($_GET['month']) : date('n');
    $year = isset($_GET['year']) ? absint($_GET['year']) : date('Y');
    
    // Get product filter
    $product_id = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;
    
    // Get bookings for the month
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    $start_date = date('Y-m-01', strtotime($year . '-' . $month . '-01'));
    $end_date = date('Y-m-t', strtotime($year . '-' . $month . '-01'));
    
    $query = "SELECT b.*, p.post_title
              FROM {$bookings_table} b
              JOIN {$wpdb->posts} p ON b.product_id = p.ID
              WHERE b.status IN ('confirmed', 'pending')
              AND (
                  (b.start_date BETWEEN %s AND %s)
                  OR (b.end_date BETWEEN %s AND %s)
                  OR (b.start_date <= %s AND b.end_date >= %s)
              )";
    
    $params = array(
        $start_date,
        $end_date,
        $start_date,
        $end_date,
        $start_date,
        $end_date,
    );
    
    if ($product_id) {
        $query .= " AND b.product_id = %d";
        $params[] = $product_id;
    }
    
    $query .= " ORDER BY b.start_date ASC";
    
    $bookings = $wpdb->get_results($wpdb->prepare($query, $params));
    
    // Prepare calendar data
    $first_day = mktime(0, 0, 0, $month, 1, $year);
    $days_in_month = date('t', $first_day);
    $first_day_of_week = date('w', $first_day);
    
    $prev_month = $month - 1;
    $prev_year = $year;
    
    if ($prev_month < 1) {
        $prev_month = 12;
        $prev_year--;
    }
    
    $next_month = $month + 1;
    $next_year = $year;
    
    if ($next_month > 12) {
        $next_month = 1;
        $next_year++;
    }
    
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Booking Calendar', 'aqualuxe'); ?></h1>
        
        <div class="tablenav top">
            <div class="alignleft actions">
                <form method="get">
                    <input type="hidden" name="page" value="aqualuxe-bookings-calendar">
                    
                    <select name="month">
                        <?php
                        for ($i = 1; $i <= 12; $i++) {
                            echo '<option value="' . esc_attr($i) . '" ' . selected($month, $i, false) . '>' . esc_html(date_i18n('F', mktime(0, 0, 0, $i, 1, 2000))) . '</option>';
                        }
                        ?>
                    </select>
                    
                    <select name="year">
                        <?php
                        $current_year = date('Y');
                        
                        for ($i = $current_year - 2; $i <= $current_year + 5; $i++) {
                            echo '<option value="' . esc_attr($i) . '" ' . selected($year, $i, false) . '>' . esc_html($i) . '</option>';
                        }
                        ?>
                    </select>
                    
                    <select name="product_id">
                        <option value=""><?php esc_html_e('All Products', 'aqualuxe'); ?></option>
                        <?php
                        $products = wc_get_products(array(
                            'type' => 'booking',
                            'limit' => -1,
                            'return' => 'ids',
                        ));
                        
                        foreach ($products as $id) {
                            $product = wc_get_product($id);
                            echo '<option value="' . esc_attr($id) . '" ' . selected($product_id, $id, false) . '>' . esc_html($product->get_name()) . '</option>';
                        }
                        ?>
                    </select>
                    
                    <input type="submit" class="button" value="<?php esc_attr_e('Filter', 'aqualuxe'); ?>">
                </form>
            </div>
            
            <div class="alignright">
                <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-bookings-calendar&month=' . $prev_month . '&year=' . $prev_year . ($product_id ? '&product_id=' . $product_id : ''))); ?>" class="button">&laquo; <?php esc_html_e('Previous Month', 'aqualuxe'); ?></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-bookings-calendar&month=' . date('n') . '&year=' . date('Y') . ($product_id ? '&product_id=' . $product_id : ''))); ?>" class="button"><?php esc_html_e('Current Month', 'aqualuxe'); ?></a>
                <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-bookings-calendar&month=' . $next_month . '&year=' . $next_year . ($product_id ? '&product_id=' . $product_id : ''))); ?>" class="button"><?php esc_html_e('Next Month', 'aqualuxe'); ?> &raquo;</a>
            </div>
            
            <br class="clear">
        </div>
        
        <div class="booking-calendar">
            <h2><?php echo esc_html(date_i18n('F Y', mktime(0, 0, 0, $month, 1, $year))); ?></h2>
            
            <table class="widefat fixed">
                <thead>
                    <tr>
                        <?php
                        for ($i = 0; $i < 7; $i++) {
                            echo '<th>' . esc_html(date_i18n('D', strtotime("Sunday +{$i} days"))) . '</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        // Add empty cells for days before the first day of the month
                        for ($i = 0; $i < $first_day_of_week; $i++) {
                            echo '<td class="empty"></td>';
                        }
                        
                        // Add days of the month
                        $day_count = $first_day_of_week;
                        
                        for ($day = 1; $day <= $days_in_month; $day++) {
                            $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
                            $is_today = $date === date('Y-m-d');
                            
                            // Get bookings for this day
                            $day_bookings = array();
                            
                            foreach ($bookings as $booking) {
                                $booking_start = date('Y-m-d', strtotime($booking->start_date));
                                $booking_end = date('Y-m-d', strtotime($booking->end_date));
                                
                                if (
                                    ($booking_start <= $date && $booking_end >= $date) ||
                                    ($booking_start === $date) ||
                                    ($booking_end === $date)
                                ) {
                                    $day_bookings[] = $booking;
                                }
                            }
                            
                            echo '<td class="' . ($is_today ? 'today' : '') . '">';
                            echo '<div class="day-number">' . esc_html($day) . '</div>';
                            
                            if (!empty($day_bookings)) {
                                echo '<ul class="day-bookings">';
                                
                                foreach ($day_bookings as $booking) {
                                    $booking_url = admin_url('admin.php?page=aqualuxe-bookings&view=booking&id=' . $booking->id);
                                    $booking_class = 'booking-status-' . $booking->status;
                                    
                                    echo '<li class="' . esc_attr($booking_class) . '">';
                                    echo '<a href="' . esc_url($booking_url) . '">';
                                    echo esc_html($booking->post_title);
                                    echo ' (' . esc_html(aqualuxe_bookings_format_time($booking->start_date)) . ')';
                                    echo '</a>';
                                    echo '</li>';
                                }
                                
                                echo '</ul>';
                            }
                            
                            echo '</td>';
                            
                            $day_count++;
                            
                            if ($day_count % 7 === 0) {
                                echo '</tr><tr>';
                            }
                        }
                        
                        // Add empty cells for days after the last day of the month
                        $remaining_cells = 7 - ($day_count % 7);
                        
                        if ($remaining_cells < 7) {
                            for ($i = 0; $i < $remaining_cells; $i++) {
                                echo '<td class="empty"></td>';
                            }
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <style>
            .booking-calendar table {
                table-layout: fixed;
            }
            
            .booking-calendar th {
                text-align: center;
            }
            
            .booking-calendar td {
                height: 100px;
                vertical-align: top;
                padding: 5px;
            }
            
            .booking-calendar td.empty {
                background-color: #f9f9f9;
            }
            
            .booking-calendar td.today {
                background-color: #fffde7;
            }
            
            .booking-calendar .day-number {
                font-weight: bold;
                margin-bottom: 5px;
            }
            
            .booking-calendar .day-bookings {
                margin: 0;
                padding: 0;
                list-style: none;
            }
            
            .booking-calendar .day-bookings li {
                margin-bottom: 3px;
                padding: 2px 4px;
                font-size: 12px;
                background-color: #f0f0f0;
                border-radius: 3px;
            }
            
            .booking-calendar .day-bookings li a {
                text-decoration: none;
            }
            
            .booking-calendar .booking-status-pending {
                background-color: #fff3cd;
            }
            
            .booking-calendar .booking-status-confirmed {
                background-color: #d4edda;
            }
        </style>
    </div>
    <?php
}