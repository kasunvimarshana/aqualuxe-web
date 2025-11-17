<?php
/**
 * My Bookings Template
 *
 * This template can be overridden by copying it to yourtheme/aqualuxe/bookings/my-bookings.php.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get bookings data
$bookings = isset($bookings) ? $bookings : array();
$show_title = isset($show_title) ? $show_title : true;
$show_status = isset($show_status) ? $show_status : true;
$show_actions = isset($show_actions) ? $show_actions : true;

// Get booking page URL
$booking_page_id = get_option('aqualuxe_bookings_page_id');
$booking_url = $booking_page_id ? get_permalink($booking_page_id) : '';

// Get confirmation page URL
$confirmation_page_id = get_option('aqualuxe_bookings_confirmation_page_id');
$confirmation_url = $confirmation_page_id ? get_permalink($confirmation_page_id) : '';
?>

<div class="aqualuxe-bookings-my-bookings">
    <?php if ($show_title) : ?>
        <h2><?php _e('My Bookings', 'aqualuxe'); ?></h2>
    <?php endif; ?>
    
    <?php if (empty($bookings)) : ?>
        <div class="aqualuxe-bookings-no-bookings">
            <p><?php _e('You have no bookings yet.', 'aqualuxe'); ?></p>
            
            <?php if ($booking_url) : ?>
                <p><a href="<?php echo esc_url($booking_url); ?>" class="button"><?php _e('Book a Service', 'aqualuxe'); ?></a></p>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <div class="aqualuxe-bookings-list">
            <div class="aqualuxe-bookings-filters">
                <div class="filter-group">
                    <label for="booking-filter-status"><?php _e('Filter by Status:', 'aqualuxe'); ?></label>
                    <select id="booking-filter-status">
                        <option value=""><?php _e('All Statuses', 'aqualuxe'); ?></option>
                        <option value="aqualuxe-pending"><?php _e('Pending', 'aqualuxe'); ?></option>
                        <option value="aqualuxe-confirmed"><?php _e('Confirmed', 'aqualuxe'); ?></option>
                        <option value="aqualuxe-completed"><?php _e('Completed', 'aqualuxe'); ?></option>
                        <option value="aqualuxe-cancelled"><?php _e('Cancelled', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="booking-sort"><?php _e('Sort by:', 'aqualuxe'); ?></label>
                    <select id="booking-sort">
                        <option value="date-desc"><?php _e('Date (Newest First)', 'aqualuxe'); ?></option>
                        <option value="date-asc"><?php _e('Date (Oldest First)', 'aqualuxe'); ?></option>
                        <option value="service"><?php _e('Service Name', 'aqualuxe'); ?></option>
                        <option value="status"><?php _e('Status', 'aqualuxe'); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="aqualuxe-bookings-table-container">
                <table class="aqualuxe-bookings-table">
                    <thead>
                        <tr>
                            <th><?php _e('Booking ID', 'aqualuxe'); ?></th>
                            <th><?php _e('Service', 'aqualuxe'); ?></th>
                            <th><?php _e('Date', 'aqualuxe'); ?></th>
                            <th><?php _e('Time', 'aqualuxe'); ?></th>
                            <?php if ($show_status) : ?>
                                <th><?php _e('Status', 'aqualuxe'); ?></th>
                            <?php endif; ?>
                            <th><?php _e('Total', 'aqualuxe'); ?></th>
                            <?php if ($show_actions) : ?>
                                <th><?php _e('Actions', 'aqualuxe'); ?></th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking) : 
                            // Format date and time
                            $date_format = get_option('date_format');
                            $time_format = get_option('time_format');
                            
                            $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
                            $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));
                            
                            // Format price
                            $formatted_total = function_exists('wc_price') ? wc_price($booking['total']) : '$' . number_format($booking['total'], 2);
                            
                            // Get status label
                            $status_labels = array(
                                'aqualuxe-pending' => __('Pending', 'aqualuxe'),
                                'aqualuxe-confirmed' => __('Confirmed', 'aqualuxe'),
                                'aqualuxe-completed' => __('Completed', 'aqualuxe'),
                                'aqualuxe-cancelled' => __('Cancelled', 'aqualuxe'),
                            );
                            
                            $status_label = isset($status_labels[$booking['status']]) ? $status_labels[$booking['status']] : ucfirst(str_replace('aqualuxe-', '', $booking['status']));
                            
                            // Get view URL
                            $view_url = $confirmation_url ? add_query_arg('booking_id', $booking['id'], $confirmation_url) : '';
                        ?>
                            <tr class="booking-item" data-status="<?php echo esc_attr($booking['status']); ?>" data-service="<?php echo esc_attr($booking['service_name']); ?>" data-date="<?php echo esc_attr($booking['start_date']); ?>">
                                <td class="booking-id"><?php echo esc_html($booking['booking_id']); ?></td>
                                <td class="booking-service"><?php echo esc_html($booking['service_name']); ?></td>
                                <td class="booking-date"><?php echo esc_html($booking_date); ?></td>
                                <td class="booking-time"><?php echo esc_html($booking_time); ?></td>
                                <?php if ($show_status) : ?>
                                    <td class="booking-status">
                                        <span class="status-badge status-<?php echo esc_attr(str_replace('aqualuxe-', '', $booking['status'])); ?>">
                                            <?php echo esc_html($status_label); ?>
                                        </span>
                                    </td>
                                <?php endif; ?>
                                <td class="booking-total"><?php echo $formatted_total; ?></td>
                                <?php if ($show_actions) : ?>
                                    <td class="booking-actions">
                                        <?php if ($view_url) : ?>
                                            <a href="<?php echo esc_url($view_url); ?>" class="button button-small view-booking"><?php _e('View', 'aqualuxe'); ?></a>
                                        <?php endif; ?>
                                        
                                        <?php if ($booking['status'] === 'aqualuxe-pending' || $booking['status'] === 'aqualuxe-confirmed') : ?>
                                            <a href="#" class="button button-small cancel-booking" data-booking-id="<?php echo esc_attr($booking['id']); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('aqualuxe-bookings')); ?>"><?php _e('Cancel', 'aqualuxe'); ?></a>
                                        <?php endif; ?>
                                        
                                        <?php if (class_exists('AquaLuxe_Bookings_Calendar') && ($booking['status'] === 'aqualuxe-pending' || $booking['status'] === 'aqualuxe-confirmed')) : ?>
                                            <div class="calendar-dropdown-container">
                                                <a href="#" class="button button-small add-to-calendar"><?php _e('Calendar', 'aqualuxe'); ?></a>
                                                <div class="calendar-dropdown">
                                                    <a href="<?php echo esc_url($this->get_google_calendar_url($booking)); ?>" target="_blank" rel="noopener noreferrer"><?php _e('Google', 'aqualuxe'); ?></a>
                                                    <a href="<?php echo esc_url($this->get_ical_url($booking)); ?>" target="_blank" rel="noopener noreferrer"><?php _e('Apple', 'aqualuxe'); ?></a>
                                                    <a href="<?php echo esc_url($this->get_outlook_url($booking)); ?>" target="_blank" rel="noopener noreferrer"><?php _e('Outlook', 'aqualuxe'); ?></a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($booking_url) : ?>
                <div class="aqualuxe-bookings-actions">
                    <a href="<?php echo esc_url($booking_url); ?>" class="button"><?php _e('Book Another Service', 'aqualuxe'); ?></a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    // Handle status filter
    $('#booking-filter-status').on('change', function() {
        var status = $(this).val();
        
        if (status) {
            $('.booking-item').hide();
            $('.booking-item[data-status="' + status + '"]').show();
        } else {
            $('.booking-item').show();
        }
    });
    
    // Handle sorting
    $('#booking-sort').on('change', function() {
        var sort = $(this).val();
        var $table = $('.aqualuxe-bookings-table');
        var $rows = $table.find('tbody tr').get();
        
        $rows.sort(function(a, b) {
            var keyA, keyB;
            
            if (sort === 'date-desc' || sort === 'date-asc') {
                keyA = $(a).data('date');
                keyB = $(b).data('date');
                
                if (sort === 'date-asc') {
                    return keyA.localeCompare(keyB);
                } else {
                    return keyB.localeCompare(keyA);
                }
            } else if (sort === 'service') {
                keyA = $(a).data('service').toLowerCase();
                keyB = $(b).data('service').toLowerCase();
                return keyA.localeCompare(keyB);
            } else if (sort === 'status') {
                keyA = $(a).data('status');
                keyB = $(b).data('status');
                return keyA.localeCompare(keyB);
            }
            
            return 0;
        });
        
        $.each($rows, function(index, row) {
            $table.children('tbody').append(row);
        });
    });
    
    // Handle cancel booking action
    $('.cancel-booking').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm('<?php echo esc_js(__('Are you sure you want to cancel this booking?', 'aqualuxe')); ?>')) {
            return;
        }
        
        var button = $(this);
        var booking_id = button.data('booking-id');
        var nonce = button.data('nonce');
        
        $.ajax({
            url: aqualuxe_bookings_params.ajax_url,
            type: 'POST',
            data: {
                action: 'cancel_booking',
                booking_id: booking_id,
                nonce: nonce
            },
            beforeSend: function() {
                button.prop('disabled', true).text('<?php echo esc_js(__('Cancelling...', 'aqualuxe')); ?>');
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.data.message);
                    button.prop('disabled', false).text('<?php echo esc_js(__('Cancel', 'aqualuxe')); ?>');
                }
            },
            error: function() {
                alert('<?php echo esc_js(__('An error occurred. Please try again.', 'aqualuxe')); ?>');
                button.prop('disabled', false).text('<?php echo esc_js(__('Cancel', 'aqualuxe')); ?>');
            }
        });
    });
    
    // Handle add to calendar dropdown
    $('.add-to-calendar').on('click', function(e) {
        e.preventDefault();
        $(this).next('.calendar-dropdown').toggleClass('active');
    });
    
    // Close calendar dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.calendar-dropdown-container').length) {
            $('.calendar-dropdown').removeClass('active');
        }
    });
});
</script>

<style>
.aqualuxe-bookings-my-bookings {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.aqualuxe-bookings-my-bookings h2 {
    margin-top: 0;
    margin-bottom: 20px;
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.aqualuxe-bookings-no-bookings {
    text-align: center;
    padding: 40px 0;
}

.aqualuxe-bookings-no-bookings p {
    margin-bottom: 20px;
    font-size: 1.1em;
}

.aqualuxe-bookings-filters {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.filter-group {
    margin-bottom: 10px;
}

.filter-group label {
    display: inline-block;
    margin-right: 10px;
    font-weight: 600;
}

.filter-group select {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #fff;
}

.aqualuxe-bookings-table-container {
    overflow-x: auto;
    margin-bottom: 20px;
}

.aqualuxe-bookings-table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
}

.aqualuxe-bookings-table th,
.aqualuxe-bookings-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.aqualuxe-bookings-table th {
    background-color: #f9f9f9;
    font-weight: 600;
    color: #333;
}

.aqualuxe-bookings-table tr:hover {
    background-color: #f9f9f9;
}

.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
    font-weight: 600;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background-color: #d4edda;
    color: #155724;
}

.status-completed {
    background-color: #cce5ff;
    color: #004085;
}

.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}

.booking-actions {
    display: flex;
    gap: 5px;
}

.booking-actions .button {
    padding: 5px 10px;
    font-size: 0.85em;
}

.calendar-dropdown-container {
    position: relative;
}

.calendar-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    z-index: 10;
    min-width: 120px;
}

.calendar-dropdown.active {
    display: block;
}

.calendar-dropdown a {
    display: block;
    padding: 8px 12px;
    text-decoration: none;
    color: #333;
    border-bottom: 1px solid #f5f5f5;
}

.calendar-dropdown a:last-child {
    border-bottom: none;
}

.calendar-dropdown a:hover {
    background-color: #f9f9f9;
}

.aqualuxe-bookings-actions {
    margin-top: 20px;
    text-align: right;
}

@media (max-width: 768px) {
    .aqualuxe-bookings-filters {
        flex-direction: column;
    }
    
    .filter-group {
        margin-bottom: 15px;
    }
    
    .aqualuxe-bookings-table th,
    .aqualuxe-bookings-table td {
        padding: 10px 8px;
    }
    
    .booking-actions {
        flex-direction: column;
    }
    
    .booking-actions .button {
        margin-bottom: 5px;
    }
}

@media (max-width: 576px) {
    .aqualuxe-bookings-table {
        display: block;
    }
    
    .aqualuxe-bookings-table thead {
        display: none;
    }
    
    .aqualuxe-bookings-table tbody {
        display: block;
    }
    
    .aqualuxe-bookings-table tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .aqualuxe-bookings-table td {
        display: flex;
        padding: 10px 15px;
        text-align: right;
        border-bottom: 1px solid #eee;
    }
    
    .aqualuxe-bookings-table td:last-child {
        border-bottom: none;
    }
    
    .aqualuxe-bookings-table td::before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        margin-right: 10px;
    }
    
    .booking-actions {
        justify-content: flex-end;
    }
}
</style>