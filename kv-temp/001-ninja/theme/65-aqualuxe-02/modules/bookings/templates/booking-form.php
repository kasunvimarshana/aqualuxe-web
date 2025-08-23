<?php
/**
 * Booking Form Template
 *
 * This template can be overridden by copying it to yourtheme/aqualuxe/bookings/booking-form.php.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get service data
$service_id = isset($service_id) ? $service_id : 0;
$show_title = isset($show_title) ? $show_title : true;
$show_description = isset($show_description) ? $show_description : true;
$show_price = isset($show_price) ? $show_price : true;
$show_image = isset($show_image) ? $show_image : true;
$redirect_url = isset($redirect_url) ? $redirect_url : '';

// If no service ID is provided, show service selection
if (empty($service_id)) {
    // Get all services
    $services = get_posts(array(
        'post_type' => 'bookable_service',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ));
    
    if (empty($services)) {
        echo '<p>' . __('No services found.', 'aqualuxe') . '</p>';
        return;
    }
    
    // Show service selection form
    ?>
    <div class="aqualuxe-bookings-service-selection">
        <h2><?php _e('Select a Service', 'aqualuxe'); ?></h2>
        
        <form class="aqualuxe-bookings-form aqualuxe-bookings-service-form">
            <div class="form-row">
                <label for="service_id"><?php _e('Service', 'aqualuxe'); ?></label>
                <select name="service_id" id="service_id" required>
                    <option value=""><?php _e('Select a service', 'aqualuxe'); ?></option>
                    <?php foreach ($services as $service) : ?>
                        <option value="<?php echo esc_attr($service->ID); ?>"><?php echo esc_html($service->post_title); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="button"><?php _e('Continue', 'aqualuxe'); ?></button>
            </div>
        </form>
    </div>
    <?php
    
    // Add JavaScript to handle form submission
    wc_enqueue_js("
        jQuery('.aqualuxe-bookings-service-form').on('submit', function(e) {
            e.preventDefault();
            
            var service_id = jQuery('#service_id').val();
            
            if (!service_id) {
                alert('" . esc_js(__('Please select a service', 'aqualuxe')) . "');
                return;
            }
            
            // Redirect to booking form with selected service
            window.location.href = '" . esc_js(add_query_arg(array(), get_permalink())) . "&service_id=' + service_id;
        });
    ");
    
    return;
}

// Get service data
$service = get_post($service_id);

if (!$service || $service->post_type !== 'bookable_service') {
    echo '<p>' . __('Invalid service.', 'aqualuxe') . '</p>';
    return;
}

// Get service meta
$price = get_post_meta($service_id, '_service_price', true);
$duration = get_post_meta($service_id, '_service_duration', true);
$capacity = get_post_meta($service_id, '_service_capacity', true);
$buffer_time = get_post_meta($service_id, '_service_buffer_time', true);
$min_duration = get_post_meta($service_id, '_service_min_duration', true);
$max_duration = get_post_meta($service_id, '_service_max_duration', true);
$allow_multiple = get_post_meta($service_id, '_service_allow_multiple', true);
$max_bookings = get_post_meta($service_id, '_service_max_bookings', true);
$description = get_post_meta($service_id, '_service_description', true);

// Set default values
$price = !empty($price) ? floatval($price) : 0;
$duration = !empty($duration) ? intval($duration) : 60;
$capacity = !empty($capacity) ? intval($capacity) : 1;
$buffer_time = !empty($buffer_time) ? intval($buffer_time) : get_option('aqualuxe_bookings_buffer_time', 30);
$min_duration = !empty($min_duration) ? intval($min_duration) : get_option('aqualuxe_bookings_min_booking_time', 60);
$max_duration = !empty($max_duration) ? intval($max_duration) : get_option('aqualuxe_bookings_max_booking_time', 480);
$allow_multiple = !empty($allow_multiple) ? $allow_multiple : 'no';
$max_bookings = !empty($max_bookings) ? intval($max_bookings) : 0;

// Format price
$formatted_price = function_exists('wc_price') ? wc_price($price) : '$' . number_format($price, 2);

// Get pre-filled values from query string
$selected_date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : '';
$selected_time = isset($_GET['time']) ? sanitize_text_field($_GET['time']) : '';
$selected_duration = isset($_GET['duration']) ? intval($_GET['duration']) : $duration;
$selected_quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

// Get user data if logged in
$user_name = '';
$user_email = '';
$user_phone = '';

if (is_user_logged_in()) {
    $user = wp_get_current_user();
    $user_name = $user->display_name;
    $user_email = $user->user_email;
    $user_phone = get_user_meta($user->ID, 'billing_phone', true);
}

// Get terms page
$terms_page_id = get_option('aqualuxe_bookings_terms_page_id');
$terms_page_url = $terms_page_id ? get_permalink($terms_page_id) : '';

// Get confirmation page
$confirmation_page_id = get_option('aqualuxe_bookings_confirmation_page_id');
$confirmation_page_url = $confirmation_page_id ? get_permalink($confirmation_page_id) : '';

// Get booking method
$booking_confirmation = get_option('aqualuxe_bookings_booking_confirmation', 'payment');

// Determine if we should show the booking form or redirect to WooCommerce
$use_woocommerce = 'payment' === $booking_confirmation && class_exists('WooCommerce');

// Get product ID if using WooCommerce
$product_id = 0;

if ($use_woocommerce) {
    $product_id = get_post_meta($service_id, '_product_id', true);
    
    if (empty($product_id)) {
        // Get default booking product
        $product_id = get_option('aqualuxe_bookings_product_id');
    }
}
?>

<div class="aqualuxe-bookings-form-container" data-service-id="<?php echo esc_attr($service_id); ?>">
    <?php if ($show_title) : ?>
        <h2 class="aqualuxe-bookings-form-title"><?php echo esc_html($service->post_title); ?></h2>
    <?php endif; ?>
    
    <?php if ($show_image && has_post_thumbnail($service_id)) : ?>
        <div class="aqualuxe-bookings-form-image">
            <?php echo get_the_post_thumbnail($service_id, 'large'); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($show_description) : ?>
        <div class="aqualuxe-bookings-form-description">
            <?php 
            if (!empty($description)) {
                echo wpautop(wp_kses_post($description));
            } else {
                echo wpautop(wp_kses_post($service->post_content));
            }
            ?>
        </div>
    <?php endif; ?>
    
    <?php if ($show_price && $price > 0) : ?>
        <div class="aqualuxe-bookings-form-price">
            <p><?php printf(__('Price: %s', 'aqualuxe'), $formatted_price); ?></p>
        </div>
    <?php endif; ?>
    
    <form class="aqualuxe-bookings-form" method="post" action="<?php echo esc_url(get_permalink()); ?>">
        <?php wp_nonce_field('aqualuxe_booking_form', 'aqualuxe_booking_nonce'); ?>
        <input type="hidden" name="aqualuxe_booking_form" value="1">
        <input type="hidden" name="service_id" value="<?php echo esc_attr($service_id); ?>">
        <?php if (!empty($redirect_url)) : ?>
            <input type="hidden" name="redirect_url" value="<?php echo esc_url($redirect_url); ?>">
        <?php endif; ?>
        
        <div class="aqualuxe-bookings-form-fields">
            <div class="form-section">
                <h3><?php _e('Select Date & Time', 'aqualuxe'); ?></h3>
                
                <div class="form-row">
                    <label for="booking_date"><?php _e('Date', 'aqualuxe'); ?></label>
                    <input type="text" name="booking_date" id="booking_date" class="aqualuxe-bookings-datepicker" value="<?php echo esc_attr($selected_date); ?>" required readonly>
                </div>
                
                <div class="form-row">
                    <label for="booking_time"><?php _e('Time', 'aqualuxe'); ?></label>
                    <select name="booking_time" id="booking_time" required disabled>
                        <option value=""><?php _e('Select a date first', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <?php if ('yes' === $allow_multiple) : ?>
                    <div class="form-row">
                        <label for="booking_duration"><?php _e('Duration', 'aqualuxe'); ?></label>
                        <div class="duration-slider-container">
                            <div id="booking_duration_slider" class="aqualuxe-bookings-slider"></div>
                            <input type="hidden" name="booking_duration" id="booking_duration" value="<?php echo esc_attr($selected_duration); ?>">
                            <div class="duration-display">
                                <span id="booking_duration_display"><?php echo esc_html($this->format_duration($selected_duration)); ?></span>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <input type="hidden" name="booking_duration" value="<?php echo esc_attr($duration); ?>">
                <?php endif; ?>
                
                <?php if ($capacity > 1) : ?>
                    <div class="form-row">
                        <label for="booking_quantity"><?php _e('Number of People', 'aqualuxe'); ?></label>
                        <select name="booking_quantity" id="booking_quantity">
                            <?php for ($i = 1; $i <= $capacity; $i++) : ?>
                                <option value="<?php echo esc_attr($i); ?>" <?php selected($selected_quantity, $i); ?>><?php echo esc_html($i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                <?php else : ?>
                    <input type="hidden" name="booking_quantity" value="1">
                <?php endif; ?>
            </div>
            
            <div class="form-section">
                <h3><?php _e('Your Information', 'aqualuxe'); ?></h3>
                
                <div class="form-row">
                    <label for="customer_name"><?php _e('Name', 'aqualuxe'); ?></label>
                    <input type="text" name="customer_name" id="customer_name" value="<?php echo esc_attr($user_name); ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="customer_email"><?php _e('Email', 'aqualuxe'); ?></label>
                    <input type="email" name="customer_email" id="customer_email" value="<?php echo esc_attr($user_email); ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="customer_phone"><?php _e('Phone', 'aqualuxe'); ?></label>
                    <input type="tel" name="customer_phone" id="customer_phone" value="<?php echo esc_attr($user_phone); ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="customer_notes"><?php _e('Notes', 'aqualuxe'); ?></label>
                    <textarea name="customer_notes" id="customer_notes" rows="4"></textarea>
                </div>
            </div>
            
            <?php if ($price > 0) : ?>
                <div class="form-section">
                    <h3><?php _e('Summary', 'aqualuxe'); ?></h3>
                    
                    <div class="booking-summary">
                        <div class="summary-row">
                            <span class="summary-label"><?php _e('Service', 'aqualuxe'); ?></span>
                            <span class="summary-value"><?php echo esc_html($service->post_title); ?></span>
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label"><?php _e('Date', 'aqualuxe'); ?></span>
                            <span class="summary-value" id="summary_date"><?php echo esc_html($selected_date); ?></span>
                        </div>
                        
                        <div class="summary-row">
                            <span class="summary-label"><?php _e('Time', 'aqualuxe'); ?></span>
                            <span class="summary-value" id="summary_time"><?php echo esc_html($selected_time); ?></span>
                        </div>
                        
                        <?php if ('yes' === $allow_multiple) : ?>
                            <div class="summary-row">
                                <span class="summary-label"><?php _e('Duration', 'aqualuxe'); ?></span>
                                <span class="summary-value" id="summary_duration"><?php echo esc_html($this->format_duration($selected_duration)); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($capacity > 1) : ?>
                            <div class="summary-row">
                                <span class="summary-label"><?php _e('People', 'aqualuxe'); ?></span>
                                <span class="summary-value" id="summary_quantity"><?php echo esc_html($selected_quantity); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="summary-row summary-total">
                            <span class="summary-label"><?php _e('Total', 'aqualuxe'); ?></span>
                            <span class="summary-value" id="summary_total"><?php echo esc_html($formatted_price); ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($terms_page_id) : ?>
                <div class="form-row form-row-terms">
                    <label class="checkbox">
                        <input type="checkbox" name="terms" id="terms" required>
                        <?php printf(__('I have read and agree to the <a href="%s" target="_blank">terms and conditions</a>', 'aqualuxe'), esc_url($terms_page_url)); ?>
                    </label>
                </div>
            <?php endif; ?>
            
            <div class="form-actions">
                <button type="submit" class="button button-primary"><?php _e('Book Now', 'aqualuxe'); ?></button>
            </div>
        </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Initialize datepicker
    $('.aqualuxe-bookings-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 0,
        maxDate: '+3m',
        beforeShowDay: function(date) {
            // This will be populated via AJAX
            return [true, ''];
        },
        onSelect: function(dateText) {
            // Load available times for selected date
            loadAvailableTimes(dateText);
            
            // Update summary
            $('#summary_date').text($.datepicker.formatDate('<?php echo esc_js(get_option('date_format')); ?>', new Date(dateText)));
        }
    });
    
    <?php if ('yes' === $allow_multiple) : ?>
    // Initialize duration slider
    $('#booking_duration_slider').slider({
        min: <?php echo esc_js($min_duration); ?>,
        max: <?php echo esc_js($max_duration); ?>,
        step: 15,
        value: <?php echo esc_js($selected_duration); ?>,
        slide: function(event, ui) {
            $('#booking_duration').val(ui.value);
            $('#booking_duration_display').text(formatDuration(ui.value));
            $('#summary_duration').text(formatDuration(ui.value));
            updateTotal();
        }
    });
    <?php endif; ?>
    
    // Handle time selection
    $('#booking_time').on('change', function() {
        $('#summary_time').text($(this).val());
        updateTotal();
    });
    
    <?php if ($capacity > 1) : ?>
    // Handle quantity selection
    $('#booking_quantity').on('change', function() {
        $('#summary_quantity').text($(this).val());
        updateTotal();
    });
    <?php endif; ?>
    
    // Load available times for selected date
    function loadAvailableTimes(date) {
        $.ajax({
            url: aqualuxe_bookings_params.ajax_url,
            type: 'POST',
            data: {
                action: 'get_available_times',
                service_id: <?php echo esc_js($service_id); ?>,
                date: date,
                nonce: aqualuxe_bookings_params.nonce
            },
            beforeSend: function() {
                $('#booking_time').prop('disabled', true).html('<option value=""><?php echo esc_js(__('Loading...', 'aqualuxe')); ?></option>');
            },
            success: function(response) {
                if (response.success) {
                    var times = response.data.times;
                    var options = '';
                    
                    if (times.length > 0) {
                        options += '<option value=""><?php echo esc_js(__('Select a time', 'aqualuxe')); ?></option>';
                        
                        for (var i = 0; i < times.length; i++) {
                            options += '<option value="' + times[i].time + '">' + times[i].time + '</option>';
                        }
                        
                        $('#booking_time').prop('disabled', false).html(options);
                    } else {
                        options = '<option value=""><?php echo esc_js(__('No available times', 'aqualuxe')); ?></option>';
                        $('#booking_time').prop('disabled', true).html(options);
                    }
                } else {
                    $('#booking_time').prop('disabled', true).html('<option value=""><?php echo esc_js(__('Error loading times', 'aqualuxe')); ?></option>');
                }
            },
            error: function() {
                $('#booking_time').prop('disabled', true).html('<option value=""><?php echo esc_js(__('Error loading times', 'aqualuxe')); ?></option>');
            }
        });
    }
    
    // Update total price
    function updateTotal() {
        var quantity = $('#booking_quantity').val() || 1;
        var duration = $('#booking_duration').val() || <?php echo esc_js($duration); ?>;
        var basePrice = <?php echo esc_js($price); ?>;
        var total = basePrice * quantity;
        
        <?php if ('yes' === $allow_multiple) : ?>
        // Adjust price based on duration
        var durationFactor = duration / <?php echo esc_js($duration); ?>;
        total = total * durationFactor;
        <?php endif; ?>
        
        // Format total
        var formattedTotal = formatPrice(total);
        
        // Update summary
        $('#summary_total').text(formattedTotal);
    }
    
    // Format duration
    function formatDuration(minutes) {
        if (minutes < 60) {
            return minutes + ' <?php echo esc_js(_n('minute', 'minutes', 2, 'aqualuxe')); ?>';
        } else {
            var hours = Math.floor(minutes / 60);
            var mins = minutes % 60;
            
            if (mins === 0) {
                return hours + ' <?php echo esc_js(_n('hour', 'hours', 2, 'aqualuxe')); ?>';
            } else {
                return hours + ' <?php echo esc_js(_n('hour', 'hours', 2, 'aqualuxe')); ?> ' + mins + ' <?php echo esc_js(_n('minute', 'minutes', 2, 'aqualuxe')); ?>';
            }
        }
    }
    
    // Format price
    function formatPrice(price) {
        var currencySymbol = '<?php echo esc_js(function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : '$'); ?>';
        var currencyPosition = '<?php echo esc_js(function_exists('get_option') ? get_option('woocommerce_currency_pos', 'left') : 'left'); ?>';
        var decimalSeparator = '<?php echo esc_js(function_exists('get_option') ? get_option('woocommerce_price_decimal_sep', '.') : '.'); ?>';
        var thousandSeparator = '<?php echo esc_js(function_exists('get_option') ? get_option('woocommerce_price_thousand_sep', ',') : ','); ?>';
        var decimals = <?php echo esc_js(function_exists('get_option') ? get_option('woocommerce_price_num_decimals', 2) : 2); ?>;
        
        // Format number
        var formattedNumber = price.toFixed(decimals);
        var parts = formattedNumber.split('.');
        var integerPart = parts[0];
        var decimalPart = parts.length > 1 ? parts[1] : '';
        
        // Add thousand separators
        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
        
        // Combine parts
        var formattedPrice = integerPart;
        
        if (decimalPart) {
            formattedPrice += decimalSeparator + decimalPart;
        }
        
        // Add currency symbol
        if (currencyPosition === 'left') {
            formattedPrice = currencySymbol + formattedPrice;
        } else if (currencyPosition === 'right') {
            formattedPrice = formattedPrice + currencySymbol;
        } else if (currencyPosition === 'left_space') {
            formattedPrice = currencySymbol + ' ' + formattedPrice;
        } else if (currencyPosition === 'right_space') {
            formattedPrice = formattedPrice + ' ' + currencySymbol;
        }
        
        return formattedPrice;
    }
    
    // Form validation
    $('.aqualuxe-bookings-form').on('submit', function(e) {
        var date = $('#booking_date').val();
        var time = $('#booking_time').val();
        
        if (!date) {
            alert(aqualuxe_bookings_params.i18n.select_date);
            e.preventDefault();
            return false;
        }
        
        if (!time) {
            alert(aqualuxe_bookings_params.i18n.select_time);
            e.preventDefault();
            return false;
        }
        
        // Validate booking
        $.ajax({
            url: aqualuxe_bookings_params.ajax_url,
            type: 'POST',
            data: {
                action: 'validate_booking_form',
                service_id: <?php echo esc_js($service_id); ?>,
                date: date,
                time: time,
                duration: $('#booking_duration').val() || <?php echo esc_js($duration); ?>,
                nonce: aqualuxe_bookings_params.nonce
            },
            async: false,
            success: function(response) {
                if (!response.success) {
                    alert(response.data.message);
                    e.preventDefault();
                    return false;
                }
            },
            error: function() {
                alert('<?php echo esc_js(__('An error occurred. Please try again.', 'aqualuxe')); ?>');
                e.preventDefault();
                return false;
            }
        });
    });
    
    // Load available dates
    $.ajax({
        url: aqualuxe_bookings_params.ajax_url,
        type: 'POST',
        data: {
            action: 'get_available_dates',
            service_id: <?php echo esc_js($service_id); ?>,
            month: new Date().getMonth() + 1,
            year: new Date().getFullYear(),
            nonce: aqualuxe_bookings_params.nonce
        },
        success: function(response) {
            if (response.success) {
                var dates = response.data.dates;
                
                // Update datepicker to only allow available dates
                $('.aqualuxe-bookings-datepicker').datepicker('option', 'beforeShowDay', function(date) {
                    var dateString = $.datepicker.formatDate('yy-mm-dd', date);
                    return [$.inArray(dateString, dates) !== -1, ''];
                });
                
                // If date is pre-selected, load available times
                <?php if (!empty($selected_date)) : ?>
                loadAvailableTimes('<?php echo esc_js($selected_date); ?>');
                <?php endif; ?>
            }
        }
    });
});
</script>

<style>
.aqualuxe-bookings-form-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.aqualuxe-bookings-form-title {
    margin-top: 0;
    margin-bottom: 20px;
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.aqualuxe-bookings-form-image {
    margin-bottom: 20px;
}

.aqualuxe-bookings-form-image img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
}

.aqualuxe-bookings-form-description {
    margin-bottom: 20px;
}

.aqualuxe-bookings-form-price {
    margin-bottom: 20px;
    font-size: 1.2em;
    font-weight: bold;
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.aqualuxe-bookings-form {
    margin-top: 20px;
}

.aqualuxe-bookings-form-fields {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
}

@media (min-width: 768px) {
    .aqualuxe-bookings-form-fields {
        grid-template-columns: 1fr 1fr;
    }
}

.form-section {
    margin-bottom: 20px;
}

.form-section h3 {
    margin-top: 0;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.form-row {
    margin-bottom: 15px;
}

.form-row label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}

.form-row input[type="text"],
.form-row input[type="email"],
.form-row input[type="tel"],
.form-row select,
.form-row textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-row input[type="text"]:focus,
.form-row input[type="email"]:focus,
.form-row input[type="tel"]:focus,
.form-row select:focus,
.form-row textarea:focus {
    border-color: var(--aqualuxe-bookings-primary-color, #0073aa);
    outline: none;
    box-shadow: 0 0 0 1px var(--aqualuxe-bookings-primary-color, #0073aa);
}

.duration-slider-container {
    padding: 10px 0;
}

.aqualuxe-bookings-slider {
    margin: 10px 0;
}

.duration-display {
    margin-top: 10px;
    font-weight: 600;
    text-align: center;
}

.booking-summary {
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 4px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.summary-label {
    font-weight: 600;
}

.summary-total {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #ddd;
    font-size: 1.2em;
    font-weight: bold;
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.form-row-terms {
    margin-top: 20px;
}

.form-row-terms label {
    display: flex;
    align-items: flex-start;
    font-weight: normal;
}

.form-row-terms input[type="checkbox"] {
    margin-top: 3px;
    margin-right: 8px;
}

.form-actions {
    margin-top: 30px;
    text-align: center;
    grid-column: 1 / -1;
}

.form-actions button {
    padding: 12px 24px;
    background-color: var(--aqualuxe-bookings-primary-color, #0073aa);
    color: var(--aqualuxe-bookings-text-on-primary, #fff);
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
}

.form-actions button:hover {
    background-color: var(--aqualuxe-bookings-primary-color-dark, #005d87);
}

/* jQuery UI Overrides */
.ui-datepicker {
    padding: 10px;
    border: none;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.ui-datepicker .ui-datepicker-header {
    background: var(--aqualuxe-bookings-primary-color, #0073aa);
    color: var(--aqualuxe-bookings-text-on-primary, #fff);
    border: none;
    border-radius: 4px 4px 0 0;
}

.ui-datepicker .ui-datepicker-title {
    font-weight: 600;
}

.ui-datepicker .ui-datepicker-prev,
.ui-datepicker .ui-datepicker-next {
    top: 5px;
}

.ui-datepicker .ui-datepicker-prev span,
.ui-datepicker .ui-datepicker-next span {
    filter: brightness(0) invert(1);
}

.ui-datepicker th {
    font-weight: 600;
    color: #333;
}

.ui-datepicker td {
    padding: 2px;
}

.ui-datepicker td a {
    text-align: center;
    border-radius: 4px;
}

.ui-datepicker td a.ui-state-default {
    background: #f9f9f9;
    border: 1px solid #f0f0f0;
    color: #333;
}

.ui-datepicker td a.ui-state-highlight {
    background: var(--aqualuxe-bookings-primary-color-light, #e1f5fe);
    border: 1px solid var(--aqualuxe-bookings-primary-color, #0073aa);
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.ui-datepicker td a.ui-state-active {
    background: var(--aqualuxe-bookings-primary-color, #0073aa);
    border: 1px solid var(--aqualuxe-bookings-primary-color, #0073aa);
    color: var(--aqualuxe-bookings-text-on-primary, #fff);
}

.ui-datepicker td.ui-state-disabled {
    opacity: 0.35;
}

.ui-slider {
    background: #f0f0f0;
    border: none;
    border-radius: 4px;
    height: 6px;
}

.ui-slider .ui-slider-handle {
    background: var(--aqualuxe-bookings-primary-color, #0073aa);
    border: none;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    top: -5px;
    cursor: pointer;
}

.ui-slider .ui-slider-range {
    background: var(--aqualuxe-bookings-primary-color-light, #e1f5fe);
    border: none;
}
</style>