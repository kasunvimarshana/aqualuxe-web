<?php
/**
 * Booking product type
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register booking product type
 */
function aqualuxe_bookings_register_product_type() {
    // Include booking product class
    require_once dirname(__FILE__) . '/class-wc-product-booking.php';
    
    // Register product type
    add_filter('product_type_selector', 'aqualuxe_bookings_add_product_type');
    add_filter('woocommerce_product_class', 'aqualuxe_bookings_product_class', 10, 4);
    
    // Add product type tabs
    add_filter('woocommerce_product_data_tabs', 'aqualuxe_bookings_product_tabs');
    add_action('woocommerce_product_data_panels', 'aqualuxe_bookings_product_panels');
    
    // Save product data
    add_action('woocommerce_process_product_meta_booking', 'aqualuxe_bookings_save_product_data');
    
    // Product columns
    add_filter('manage_edit-product_columns', 'aqualuxe_bookings_product_columns');
    add_action('manage_product_posts_custom_column', 'aqualuxe_bookings_product_column_content', 10, 2);
    
    // Product filters
    add_action('restrict_manage_posts', 'aqualuxe_bookings_product_filters');
    add_filter('parse_query', 'aqualuxe_bookings_product_filter_query');
}

/**
 * Add booking product type to WooCommerce
 *
 * @param array $types Product types
 * @return array
 */
function aqualuxe_bookings_add_product_type($types) {
    $types['booking'] = __('Booking', 'aqualuxe');
    
    return $types;
}

/**
 * Set product class for booking products
 *
 * @param string $classname Product class name
 * @param string $product_type Product type
 * @param string $post_type Post type
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_bookings_product_class($classname, $product_type, $post_type, $product_id) {
    if ($product_type === 'booking') {
        $classname = 'WC_Product_Booking';
    }
    
    return $classname;
}

/**
 * Add booking product tabs
 *
 * @param array $tabs Product tabs
 * @return array
 */
function aqualuxe_bookings_product_tabs($tabs) {
    $tabs['booking'] = array(
        'label' => __('Booking', 'aqualuxe'),
        'target' => 'booking_product_data',
        'class' => array('show_if_booking'),
        'priority' => 21,
    );
    
    return $tabs;
}

/**
 * Add booking product panels
 */
function aqualuxe_bookings_product_panels() {
    global $post;
    
    // Get booking data
    $booking_type = get_post_meta($post->ID, '_booking_type', true);
    $booking_duration = get_post_meta($post->ID, '_booking_duration', true);
    $booking_duration_unit = get_post_meta($post->ID, '_booking_duration_unit', true);
    $booking_min_duration = get_post_meta($post->ID, '_booking_min_duration', true);
    $booking_max_duration = get_post_meta($post->ID, '_booking_max_duration', true);
    $booking_max_bookings = get_post_meta($post->ID, '_booking_max_bookings', true);
    $booking_requires_confirmation = get_post_meta($post->ID, '_booking_requires_confirmation', true);
    $booking_can_be_cancelled = get_post_meta($post->ID, '_booking_can_be_cancelled', true);
    $booking_cancel_limit = get_post_meta($post->ID, '_booking_cancel_limit', true);
    $booking_cancel_limit_unit = get_post_meta($post->ID, '_booking_cancel_limit_unit', true);
    $booking_range_start = get_post_meta($post->ID, '_booking_range_start', true);
    $booking_range_end = get_post_meta($post->ID, '_booking_range_end', true);
    $booking_min_date = get_post_meta($post->ID, '_booking_min_date', true);
    $booking_min_date_unit = get_post_meta($post->ID, '_booking_min_date_unit', true);
    $booking_max_date = get_post_meta($post->ID, '_booking_max_date', true);
    $booking_max_date_unit = get_post_meta($post->ID, '_booking_max_date_unit', true);
    $booking_time_slots = get_post_meta($post->ID, '_booking_time_slots', true);
    $booking_available_days = get_post_meta($post->ID, '_booking_available_days', true);
    $booking_blocked_dates = get_post_meta($post->ID, '_booking_blocked_dates', true);
    
    // Format dates
    $booking_range_start = $booking_range_start ? date('Y-m-d', strtotime($booking_range_start)) : '';
    $booking_range_end = $booking_range_end ? date('Y-m-d', strtotime($booking_range_end)) : '';
    
    // Get booking types
    $booking_types = aqualuxe_bookings_get_types();
    
    // Get booking durations
    $booking_durations = aqualuxe_bookings_get_durations();
    
    // Get time slots
    if (!is_array($booking_time_slots)) {
        $booking_time_slots = array();
    }
    
    // Get available days
    if (!is_array($booking_available_days)) {
        $booking_available_days = array(0, 1, 2, 3, 4, 5, 6); // Default to all days
    }
    
    ?>
    <div id="booking_product_data" class="panel woocommerce_options_panel">
        <div class="options_group">
            <p class="form-field">
                <label for="_booking_type"><?php esc_html_e('Booking Type', 'aqualuxe'); ?></label>
                <select id="_booking_type" name="_booking_type" class="select short">
                    <?php foreach ($booking_types as $type => $label) : ?>
                        <option value="<?php echo esc_attr($type); ?>" <?php selected($booking_type, $type); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="description"><?php esc_html_e('The type of booking.', 'aqualuxe'); ?></span>
            </p>
            
            <div class="booking-type-options booking-type-duration" style="display: <?php echo $booking_type === 'duration' ? 'block' : 'none'; ?>">
                <p class="form-field">
                    <label for="_booking_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label>
                    <input type="number" class="short" name="_booking_duration" id="_booking_duration" value="<?php echo esc_attr($booking_duration); ?>" step="1" min="1" />
                    <select name="_booking_duration_unit" id="_booking_duration_unit" class="select short">
                        <?php foreach ($booking_durations as $unit => $label) : ?>
                            <option value="<?php echo esc_attr($unit); ?>" <?php selected($booking_duration_unit, $unit); ?>><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="description"><?php esc_html_e('The default booking duration.', 'aqualuxe'); ?></span>
                </p>
                
                <p class="form-field">
                    <label for="_booking_min_duration"><?php esc_html_e('Minimum Duration', 'aqualuxe'); ?></label>
                    <input type="number" class="short" name="_booking_min_duration" id="_booking_min_duration" value="<?php echo esc_attr($booking_min_duration); ?>" step="1" min="1" />
                    <span class="description"><?php esc_html_e('The minimum booking duration.', 'aqualuxe'); ?></span>
                </p>
                
                <p class="form-field">
                    <label for="_booking_max_duration"><?php esc_html_e('Maximum Duration', 'aqualuxe'); ?></label>
                    <input type="number" class="short" name="_booking_max_duration" id="_booking_max_duration" value="<?php echo esc_attr($booking_max_duration); ?>" step="1" min="1" />
                    <span class="description"><?php esc_html_e('The maximum booking duration.', 'aqualuxe'); ?></span>
                </p>
            </div>
            
            <div class="booking-type-options booking-type-fixed-time" style="display: <?php echo $booking_type === 'fixed_time' ? 'block' : 'none'; ?>">
                <p class="form-field">
                    <label><?php esc_html_e('Time Slots', 'aqualuxe'); ?></label>
                    <div class="booking-time-slots">
                        <div class="booking-time-slots-header">
                            <span class="booking-time-slot-days"><?php esc_html_e('Days', 'aqualuxe'); ?></span>
                            <span class="booking-time-slot-start"><?php esc_html_e('Start Time', 'aqualuxe'); ?></span>
                            <span class="booking-time-slot-end"><?php esc_html_e('End Time', 'aqualuxe'); ?></span>
                            <span class="booking-time-slot-actions"><?php esc_html_e('Actions', 'aqualuxe'); ?></span>
                        </div>
                        
                        <div class="booking-time-slots-container">
                            <?php if (!empty($booking_time_slots)) : ?>
                                <?php foreach ($booking_time_slots as $index => $slot) : ?>
                                    <div class="booking-time-slot">
                                        <div class="booking-time-slot-days">
                                            <?php for ($i = 0; $i < 7; $i++) : ?>
                                                <label>
                                                    <input type="checkbox" name="_booking_time_slots[<?php echo esc_attr($index); ?>][days][]" value="<?php echo esc_attr($i); ?>" <?php checked(isset($slot['days']) && is_array($slot['days']) && in_array($i, $slot['days'])); ?> />
                                                    <?php echo esc_html(date_i18n('D', strtotime("Sunday +{$i} days"))); ?>
                                                </label>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="booking-time-slot-start">
                                            <input type="time" name="_booking_time_slots[<?php echo esc_attr($index); ?>][start_time]" value="<?php echo esc_attr($slot['start_time']); ?>" />
                                        </div>
                                        <div class="booking-time-slot-end">
                                            <input type="time" name="_booking_time_slots[<?php echo esc_attr($index); ?>][end_time]" value="<?php echo esc_attr($slot['end_time']); ?>" />
                                        </div>
                                        <div class="booking-time-slot-actions">
                                            <button type="button" class="button remove-time-slot"><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <button type="button" class="button add-time-slot"><?php esc_html_e('Add Time Slot', 'aqualuxe'); ?></button>
                    </div>
                </p>
            </div>
        </div>
        
        <div class="options_group">
            <p class="form-field">
                <label for="_booking_max_bookings"><?php esc_html_e('Maximum Bookings', 'aqualuxe'); ?></label>
                <input type="number" class="short" name="_booking_max_bookings" id="_booking_max_bookings" value="<?php echo esc_attr($booking_max_bookings); ?>" step="1" min="1" />
                <span class="description"><?php esc_html_e('The maximum number of bookings allowed for the same time slot.', 'aqualuxe'); ?></span>
            </p>
            
            <p class="form-field">
                <label for="_booking_requires_confirmation"><?php esc_html_e('Requires Confirmation', 'aqualuxe'); ?></label>
                <input type="checkbox" class="checkbox" name="_booking_requires_confirmation" id="_booking_requires_confirmation" value="yes" <?php checked($booking_requires_confirmation, 'yes'); ?> />
                <span class="description"><?php esc_html_e('Check this box if bookings require admin confirmation/approval.', 'aqualuxe'); ?></span>
            </p>
            
            <p class="form-field">
                <label for="_booking_can_be_cancelled"><?php esc_html_e('Can Be Cancelled', 'aqualuxe'); ?></label>
                <input type="checkbox" class="checkbox" name="_booking_can_be_cancelled" id="_booking_can_be_cancelled" value="yes" <?php checked($booking_can_be_cancelled, 'yes'); ?> />
                <span class="description"><?php esc_html_e('Check this box if bookings can be cancelled by the customer.', 'aqualuxe'); ?></span>
            </p>
            
            <div class="booking-cancel-options" style="display: <?php echo $booking_can_be_cancelled === 'yes' ? 'block' : 'none'; ?>">
                <p class="form-field">
                    <label for="_booking_cancel_limit"><?php esc_html_e('Cancellation Limit', 'aqualuxe'); ?></label>
                    <input type="number" class="short" name="_booking_cancel_limit" id="_booking_cancel_limit" value="<?php echo esc_attr($booking_cancel_limit); ?>" step="1" min="1" />
                    <select name="_booking_cancel_limit_unit" id="_booking_cancel_limit_unit" class="select short">
                        <option value="hour" <?php selected($booking_cancel_limit_unit, 'hour'); ?>><?php esc_html_e('Hour(s)', 'aqualuxe'); ?></option>
                        <option value="day" <?php selected($booking_cancel_limit_unit, 'day'); ?>><?php esc_html_e('Day(s)', 'aqualuxe'); ?></option>
                    </select>
                    <span class="description"><?php esc_html_e('The minimum time before a booking that it can be cancelled.', 'aqualuxe'); ?></span>
                </p>
            </div>
        </div>
        
        <div class="options_group">
            <p class="form-field">
                <label for="_booking_range_start"><?php esc_html_e('Booking Range Start', 'aqualuxe'); ?></label>
                <input type="date" class="short" name="_booking_range_start" id="_booking_range_start" value="<?php echo esc_attr($booking_range_start); ?>" />
                <span class="description"><?php esc_html_e('The start date of the booking range. Leave blank for no restriction.', 'aqualuxe'); ?></span>
            </p>
            
            <p class="form-field">
                <label for="_booking_range_end"><?php esc_html_e('Booking Range End', 'aqualuxe'); ?></label>
                <input type="date" class="short" name="_booking_range_end" id="_booking_range_end" value="<?php echo esc_attr($booking_range_end); ?>" />
                <span class="description"><?php esc_html_e('The end date of the booking range. Leave blank for no restriction.', 'aqualuxe'); ?></span>
            </p>
            
            <p class="form-field">
                <label for="_booking_min_date"><?php esc_html_e('Minimum Booking Notice', 'aqualuxe'); ?></label>
                <input type="number" class="short" name="_booking_min_date" id="_booking_min_date" value="<?php echo esc_attr($booking_min_date); ?>" step="1" min="0" />
                <select name="_booking_min_date_unit" id="_booking_min_date_unit" class="select short">
                    <option value="hour" <?php selected($booking_min_date_unit, 'hour'); ?>><?php esc_html_e('Hour(s)', 'aqualuxe'); ?></option>
                    <option value="day" <?php selected($booking_min_date_unit, 'day'); ?>><?php esc_html_e('Day(s)', 'aqualuxe'); ?></option>
                </select>
                <span class="description"><?php esc_html_e('The minimum time in advance that a booking can be made.', 'aqualuxe'); ?></span>
            </p>
            
            <p class="form-field">
                <label for="_booking_max_date"><?php esc_html_e('Maximum Booking Notice', 'aqualuxe'); ?></label>
                <input type="number" class="short" name="_booking_max_date" id="_booking_max_date" value="<?php echo esc_attr($booking_max_date); ?>" step="1" min="0" />
                <select name="_booking_max_date_unit" id="_booking_max_date_unit" class="select short">
                    <option value="day" <?php selected($booking_max_date_unit, 'day'); ?>><?php esc_html_e('Day(s)', 'aqualuxe'); ?></option>
                    <option value="week" <?php selected($booking_max_date_unit, 'week'); ?>><?php esc_html_e('Week(s)', 'aqualuxe'); ?></option>
                    <option value="month" <?php selected($booking_max_date_unit, 'month'); ?>><?php esc_html_e('Month(s)', 'aqualuxe'); ?></option>
                </select>
                <span class="description"><?php esc_html_e('The maximum time in advance that a booking can be made.', 'aqualuxe'); ?></span>
            </p>
        </div>
        
        <div class="options_group">
            <p class="form-field">
                <label><?php esc_html_e('Available Days', 'aqualuxe'); ?></label>
                <span class="booking-available-days">
                    <?php for ($i = 0; $i < 7; $i++) : ?>
                        <label>
                            <input type="checkbox" name="_booking_available_days[]" value="<?php echo esc_attr($i); ?>" <?php checked(in_array($i, $booking_available_days)); ?> />
                            <?php echo esc_html(date_i18n('l', strtotime("Sunday +{$i} days"))); ?>
                        </label>
                    <?php endfor; ?>
                </span>
                <span class="description"><?php esc_html_e('The days of the week that are available for booking.', 'aqualuxe'); ?></span>
            </p>
            
            <p class="form-field">
                <label for="_booking_blocked_dates"><?php esc_html_e('Blocked Dates', 'aqualuxe'); ?></label>
                <input type="text" class="short" name="_booking_blocked_dates" id="_booking_blocked_dates" value="<?php echo esc_attr($booking_blocked_dates); ?>" />
                <span class="description"><?php esc_html_e('Comma-separated list of dates that are not available for booking (YYYY-MM-DD).', 'aqualuxe'); ?></span>
            </p>
        </div>
        
        <?php if (aqualuxe_bookings_get_product_bookings($post->ID)) : ?>
            <div class="options_group">
                <h3><?php esc_html_e('Bookings', 'aqualuxe'); ?></h3>
                
                <table class="widefat">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('ID', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Customer', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Start Date', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('End Date', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $bookings = aqualuxe_bookings_get_product_bookings($post->ID, '', array('limit' => 10));
                        
                        foreach ($bookings as $booking) {
                            $user = get_user_by('id', $booking['user_id']);
                            $username = $user ? $user->display_name : __('Unknown', 'aqualuxe');
                            
                            echo '<tr>';
                            echo '<td>' . esc_html($booking['id']) . '</td>';
                            echo '<td>' . esc_html($username) . '</td>';
                            echo '<td>' . esc_html(aqualuxe_bookings_format_datetime($booking['start_date'])) . '</td>';
                            echo '<td>' . esc_html(aqualuxe_bookings_format_datetime($booking['end_date'])) . '</td>';
                            echo '<td>' . esc_html(aqualuxe_bookings_get_status_label($booking['status'])) . '</td>';
                            echo '<td>';
                            echo '<a href="' . esc_url(admin_url('admin.php?page=aqualuxe-bookings&view=booking&id=' . $booking['id'])) . '" class="button">' . esc_html__('View', 'aqualuxe') . '</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        
                        if (empty($bookings)) {
                            echo '<tr><td colspan="6">' . esc_html__('No bookings yet.', 'aqualuxe') . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                
                <?php if (count(aqualuxe_bookings_get_product_bookings($post->ID)) > 10) : ?>
                    <p>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-bookings&product_id=' . $post->ID)); ?>" class="button">
                            <?php esc_html_e('View All Bookings', 'aqualuxe'); ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script type="text/javascript">
        jQuery(function($) {
            // Show/hide booking type options
            function toggleBookingTypeOptions() {
                var bookingType = $('#_booking_type').val();
                
                $('.booking-type-options').hide();
                $('.booking-type-' + bookingType).show();
            }
            
            $('#_booking_type').change(toggleBookingTypeOptions);
            toggleBookingTypeOptions();
            
            // Show/hide cancellation options
            function toggleCancellationOptions() {
                if ($('#_booking_can_be_cancelled').is(':checked')) {
                    $('.booking-cancel-options').show();
                } else {
                    $('.booking-cancel-options').hide();
                }
            }
            
            $('#_booking_can_be_cancelled').change(toggleCancellationOptions);
            toggleCancellationOptions();
            
            // Add time slot
            $('.add-time-slot').on('click', function() {
                var index = $('.booking-time-slot').length;
                var template = '<div class="booking-time-slot">' +
                    '<div class="booking-time-slot-days">';
                
                for (var i = 0; i < 7; i++) {
                    var day = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][i];
                    template += '<label>' +
                        '<input type="checkbox" name="_booking_time_slots[' + index + '][days][]" value="' + i + '" />' +
                        day +
                        '</label>';
                }
                
                template += '</div>' +
                    '<div class="booking-time-slot-start">' +
                    '<input type="time" name="_booking_time_slots[' + index + '][start_time]" value="09:00" />' +
                    '</div>' +
                    '<div class="booking-time-slot-end">' +
                    '<input type="time" name="_booking_time_slots[' + index + '][end_time]" value="17:00" />' +
                    '</div>' +
                    '<div class="booking-time-slot-actions">' +
                    '<button type="button" class="button remove-time-slot">Remove</button>' +
                    '</div>' +
                    '</div>';
                
                $('.booking-time-slots-container').append(template);
            });
            
            // Remove time slot
            $('.booking-time-slots').on('click', '.remove-time-slot', function() {
                $(this).closest('.booking-time-slot').remove();
            });
            
            // Initialize datepicker for blocked dates
            if ($.fn.datepicker) {
                $('#_booking_blocked_dates').datepicker({
                    dateFormat: 'yy-mm-dd',
                    multiSelect: true,
                    firstDay: 1,
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    showButtonPanel: true,
                    changeMonth: true,
                    changeYear: true,
                });
            }
        });
    </script>
    <?php
}

/**
 * Save booking product data
 *
 * @param int $post_id Product ID
 */
function aqualuxe_bookings_save_product_data($post_id) {
    // Save booking data
    $fields = array(
        '_booking_type',
        '_booking_duration',
        '_booking_duration_unit',
        '_booking_min_duration',
        '_booking_max_duration',
        '_booking_max_bookings',
        '_booking_range_start',
        '_booking_range_end',
        '_booking_min_date',
        '_booking_min_date_unit',
        '_booking_max_date',
        '_booking_max_date_unit',
        '_booking_blocked_dates',
    );
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Save checkbox fields
    $checkbox_fields = array(
        '_booking_requires_confirmation',
        '_booking_can_be_cancelled',
    );
    
    foreach ($checkbox_fields as $field) {
        $value = isset($_POST[$field]) ? 'yes' : 'no';
        update_post_meta($post_id, $field, $value);
    }
    
    // Save cancellation limit
    if (isset($_POST['_booking_cancel_limit'])) {
        update_post_meta($post_id, '_booking_cancel_limit', sanitize_text_field($_POST['_booking_cancel_limit']));
    }
    
    if (isset($_POST['_booking_cancel_limit_unit'])) {
        update_post_meta($post_id, '_booking_cancel_limit_unit', sanitize_text_field($_POST['_booking_cancel_limit_unit']));
    }
    
    // Save available days
    $available_days = isset($_POST['_booking_available_days']) ? array_map('absint', $_POST['_booking_available_days']) : array();
    update_post_meta($post_id, '_booking_available_days', $available_days);
    
    // Save time slots
    $time_slots = array();
    
    if (isset($_POST['_booking_time_slots']) && is_array($_POST['_booking_time_slots'])) {
        foreach ($_POST['_booking_time_slots'] as $slot) {
            if (empty($slot['days']) || empty($slot['start_time']) || empty($slot['end_time'])) {
                continue;
            }
            
            $time_slots[] = array(
                'days' => array_map('absint', $slot['days']),
                'start_time' => sanitize_text_field($slot['start_time']),
                'end_time' => sanitize_text_field($slot['end_time']),
            );
        }
    }
    
    update_post_meta($post_id, '_booking_time_slots', $time_slots);
}

/**
 * Add booking columns to product list
 *
 * @param array $columns Product columns
 * @return array
 */
function aqualuxe_bookings_product_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $column) {
        $new_columns[$key] = $column;
        
        if ($key === 'price') {
            $new_columns['booking_type'] = __('Booking Type', 'aqualuxe');
        }
    }
    
    return $new_columns;
}

/**
 * Add booking column content
 *
 * @param string $column Column name
 * @param int $post_id Product ID
 */
function aqualuxe_bookings_product_column_content($column, $post_id) {
    $product = wc_get_product($post_id);
    
    if (!$product || $product->get_type() !== 'booking') {
        return;
    }
    
    if ($column === 'booking_type') {
        $booking_type = get_post_meta($post_id, '_booking_type', true);
        $booking_types = aqualuxe_bookings_get_types();
        
        echo isset($booking_types[$booking_type]) ? esc_html($booking_types[$booking_type]) : esc_html($booking_type);
    }
}

/**
 * Add booking filter to product list
 */
function aqualuxe_bookings_product_filters() {
    global $typenow;
    
    if ($typenow !== 'product') {
        return;
    }
    
    // Add booking type filter
    $current_type = isset($_GET['booking_type']) ? sanitize_text_field($_GET['booking_type']) : '';
    $booking_types = aqualuxe_bookings_get_types();
    
    ?>
    <select name="booking_type" id="dropdown_booking_type">
        <option value=""><?php esc_html_e('All booking types', 'aqualuxe'); ?></option>
        <?php foreach ($booking_types as $type => $label) : ?>
            <option value="<?php echo esc_attr($type); ?>" <?php selected($current_type, $type); ?>><?php echo esc_html($label); ?></option>
        <?php endforeach; ?>
    </select>
    <?php
}

/**
 * Filter products by booking type
 *
 * @param WP_Query $query Query object
 * @return WP_Query
 */
function aqualuxe_bookings_product_filter_query($query) {
    global $typenow, $pagenow;
    
    if ($pagenow !== 'edit.php' || $typenow !== 'product' || !isset($_GET['booking_type']) || empty($_GET['booking_type'])) {
        return $query;
    }
    
    $booking_type = sanitize_text_field($_GET['booking_type']);
    
    // Add tax query for booking products
    $tax_query = $query->get('tax_query');
    
    if (!is_array($tax_query)) {
        $tax_query = array();
    }
    
    $tax_query[] = array(
        'taxonomy' => 'product_type',
        'field' => 'slug',
        'terms' => 'booking',
    );
    
    $query->set('tax_query', $tax_query);
    
    // Add meta query for booking type
    $meta_query = $query->get('meta_query');
    
    if (!is_array($meta_query)) {
        $meta_query = array();
    }
    
    $meta_query[] = array(
        'key' => '_booking_type',
        'value' => $booking_type,
        'compare' => '=',
    );
    
    $query->set('meta_query', $meta_query);
    
    return $query;
}

/**
 * Create booking product class
 */
if (!class_exists('WC_Product_Booking')) {
    /**
     * Booking product class
     */
    class WC_Product_Booking extends WC_Product {
        /**
         * Get internal type
         *
         * @return string
         */
        public function get_type() {
            return 'booking';
        }
        
        /**
         * Get booking type
         *
         * @return string
         */
        public function get_booking_type() {
            return $this->get_meta('_booking_type');
        }
        
        /**
         * Get booking duration
         *
         * @return int
         */
        public function get_booking_duration() {
            return (int) $this->get_meta('_booking_duration');
        }
        
        /**
         * Get booking duration unit
         *
         * @return string
         */
        public function get_booking_duration_unit() {
            return $this->get_meta('_booking_duration_unit');
        }
        
        /**
         * Get booking minimum duration
         *
         * @return int
         */
        public function get_booking_min_duration() {
            return (int) $this->get_meta('_booking_min_duration');
        }
        
        /**
         * Get booking maximum duration
         *
         * @return int
         */
        public function get_booking_max_duration() {
            return (int) $this->get_meta('_booking_max_duration');
        }
        
        /**
         * Get booking maximum bookings
         *
         * @return int
         */
        public function get_booking_max_bookings() {
            $max_bookings = $this->get_meta('_booking_max_bookings');
            
            if ($max_bookings === '' || $max_bookings === false) {
                $max_bookings = 1;
            }
            
            return (int) $max_bookings;
        }
        
        /**
         * Check if booking requires confirmation
         *
         * @return bool
         */
        public function get_booking_requires_confirmation() {
            return $this->get_meta('_booking_requires_confirmation') === 'yes';
        }
        
        /**
         * Check if booking can be cancelled
         *
         * @return bool
         */
        public function get_booking_can_be_cancelled() {
            return $this->get_meta('_booking_can_be_cancelled') === 'yes';
        }
        
        /**
         * Get booking cancellation limit
         *
         * @return int
         */
        public function get_booking_cancel_limit() {
            return (int) $this->get_meta('_booking_cancel_limit');
        }
        
        /**
         * Get booking cancellation limit unit
         *
         * @return string
         */
        public function get_booking_cancel_limit_unit() {
            return $this->get_meta('_booking_cancel_limit_unit');
        }
        
        /**
         * Get booking range start
         *
         * @return string
         */
        public function get_booking_range_start() {
            return $this->get_meta('_booking_range_start');
        }
        
        /**
         * Get booking range end
         *
         * @return string
         */
        public function get_booking_range_end() {
            return $this->get_meta('_booking_range_end');
        }
        
        /**
         * Get booking minimum date
         *
         * @return int
         */
        public function get_booking_min_date() {
            return (int) $this->get_meta('_booking_min_date');
        }
        
        /**
         * Get booking minimum date unit
         *
         * @return string
         */
        public function get_booking_min_date_unit() {
            return $this->get_meta('_booking_min_date_unit');
        }
        
        /**
         * Get booking maximum date
         *
         * @return int
         */
        public function get_booking_max_date() {
            return (int) $this->get_meta('_booking_max_date');
        }
        
        /**
         * Get booking maximum date unit
         *
         * @return string
         */
        public function get_booking_max_date_unit() {
            return $this->get_meta('_booking_max_date_unit');
        }
        
        /**
         * Get booking time slots
         *
         * @return array
         */
        public function get_booking_time_slots() {
            $time_slots = $this->get_meta('_booking_time_slots');
            
            if (!is_array($time_slots)) {
                $time_slots = array();
            }
            
            return $time_slots;
        }
        
        /**
         * Get booking available days
         *
         * @return array
         */
        public function get_booking_available_days() {
            $available_days = $this->get_meta('_booking_available_days');
            
            if (!is_array($available_days)) {
                $available_days = array(0, 1, 2, 3, 4, 5, 6); // Default to all days
            }
            
            return $available_days;
        }
        
        /**
         * Get booking blocked dates
         *
         * @return array
         */
        public function get_booking_blocked_dates() {
            $blocked_dates = $this->get_meta('_booking_blocked_dates');
            
            if (!$blocked_dates) {
                return array();
            }
            
            return explode(',', $blocked_dates);
        }
        
        /**
         * Is purchasable
         *
         * @return bool
         */
        public function is_purchasable() {
            $purchasable = true;
            
            // Check if product is published
            if ($this->get_status() !== 'publish') {
                $purchasable = false;
            }
            
            return apply_filters('woocommerce_is_purchasable', $purchasable, $this);
        }
        
        /**
         * Get price HTML
         *
         * @param string $price Price to display
         * @return string
         */
        public function get_price_html($price = '') {
            $price_html = wc_price($this->get_price());
            
            $booking_type = $this->get_booking_type();
            
            if ($booking_type === 'duration') {
                $duration_unit = $this->get_booking_duration_unit();
                $duration_label = '';
                
                switch ($duration_unit) {
                    case 'hour':
                        $duration_label = __('hour', 'aqualuxe');
                        break;
                    case 'day':
                        $duration_label = __('day', 'aqualuxe');
                        break;
                    case 'week':
                        $duration_label = __('week', 'aqualuxe');
                        break;
                    case 'month':
                        $duration_label = __('month', 'aqualuxe');
                        break;
                }
                
                $price_html .= ' <span class="booking-price-unit">/ ' . esc_html($duration_label) . '</span>';
            }
            
            return apply_filters('woocommerce_get_price_html', $price_html, $this);
        }
        
        /**
         * Get add to cart URL
         *
         * @return string
         */
        public function add_to_cart_url() {
            $url = $this->is_purchasable() ? remove_query_arg('added-to-cart', add_query_arg('add-to-cart', $this->get_id())) : get_permalink($this->get_id());
            
            return apply_filters('woocommerce_product_add_to_cart_url', $url, $this);
        }
        
        /**
         * Get add to cart text
         *
         * @return string
         */
        public function add_to_cart_text() {
            $text = $this->is_purchasable() ? __('Book now', 'aqualuxe') : __('Read more', 'aqualuxe');
            
            return apply_filters('woocommerce_product_add_to_cart_text', $text, $this);
        }
    }
}