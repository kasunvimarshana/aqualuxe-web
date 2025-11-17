<?php
/**
 * Admin Calendar Template
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get services
$services = get_posts(array(
    'post_type' => 'bookable_service',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
));

// Get selected service
$selected_service_id = isset($_GET['service_id']) ? absint($_GET['service_id']) : 0;

// Get selected booking
$selected_booking_id = isset($_GET['booking_id']) ? absint($_GET['booking_id']) : 0;
$selected_booking = null;

if ($selected_booking_id) {
    $bookings_data = new AquaLuxe_Bookings_Data();
    $selected_booking = $bookings_data->get_booking($selected_booking_id);
    
    if ($selected_booking) {
        $selected_service_id = $selected_booking['service_id'];
    }
}

// Get view
$view = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'month';
$allowed_views = array('month', 'week', 'day', 'list');

if (!in_array($view, $allowed_views)) {
    $view = 'month';
}

// Get date
$date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : '';

if (!$date) {
    $date = date('Y-m-d');
}
?>

<div class="wrap aqualuxe-bookings-calendar-page">
    <h1 class="wp-heading-inline"><?php _e('Bookings Calendar', 'aqualuxe'); ?></h1>
    
    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=booking')); ?>" class="page-title-action"><?php _e('Add New Booking', 'aqualuxe'); ?></a>
    
    <hr class="wp-header-end">
    
    <div class="aqualuxe-bookings-calendar-filters">
        <form method="get" action="<?php echo esc_url(admin_url('admin.php')); ?>">
            <input type="hidden" name="page" value="aqualuxe-bookings-calendar">
            
            <div class="filter-group">
                <label for="service_id"><?php _e('Service:', 'aqualuxe'); ?></label>
                <select name="service_id" id="service_id">
                    <option value=""><?php _e('All Services', 'aqualuxe'); ?></option>
                    <?php foreach ($services as $service) : ?>
                        <option value="<?php echo esc_attr($service->ID); ?>" <?php selected($selected_service_id, $service->ID); ?>><?php echo esc_html($service->post_title); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="view"><?php _e('View:', 'aqualuxe'); ?></label>
                <select name="view" id="view">
                    <option value="month" <?php selected($view, 'month'); ?>><?php _e('Month', 'aqualuxe'); ?></option>
                    <option value="week" <?php selected($view, 'week'); ?>><?php _e('Week', 'aqualuxe'); ?></option>
                    <option value="day" <?php selected($view, 'day'); ?>><?php _e('Day', 'aqualuxe'); ?></option>
                    <option value="list" <?php selected($view, 'list'); ?>><?php _e('List', 'aqualuxe'); ?></option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="date"><?php _e('Date:', 'aqualuxe'); ?></label>
                <input type="text" name="date" id="date" class="aqualuxe-bookings-datepicker" value="<?php echo esc_attr($date); ?>" placeholder="<?php echo esc_attr(date('Y-m-d')); ?>">
            </div>
            
            <div class="filter-group">
                <button type="submit" class="button"><?php _e('Filter', 'aqualuxe'); ?></button>
                <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-bookings-calendar')); ?>" class="button"><?php _e('Reset', 'aqualuxe'); ?></a>
            </div>
        </form>
    </div>
    
    <div class="aqualuxe-bookings-calendar-container">
        <div id="aqualuxe-bookings-admin-calendar"></div>
    </div>
    
    <div id="aqualuxe-bookings-event-dialog" title="<?php esc_attr_e('Booking Details', 'aqualuxe'); ?>" style="display:none;">
        <div class="event-dialog-content">
            <div class="event-dialog-loading">
                <span class="spinner is-active"></span>
                <p><?php _e('Loading...', 'aqualuxe'); ?></p>
            </div>
            
            <div class="event-dialog-details" style="display:none;">
                <h3 class="event-title"></h3>
                
                <div class="event-meta">
                    <div class="event-meta-item">
                        <span class="meta-label"><?php _e('Booking ID:', 'aqualuxe'); ?></span>
                        <span class="meta-value booking-id"></span>
                    </div>
                    
                    <div class="event-meta-item">
                        <span class="meta-label"><?php _e('Service:', 'aqualuxe'); ?></span>
                        <span class="meta-value service-name"></span>
                    </div>
                    
                    <div class="event-meta-item">
                        <span class="meta-label"><?php _e('Date:', 'aqualuxe'); ?></span>
                        <span class="meta-value booking-date"></span>
                    </div>
                    
                    <div class="event-meta-item">
                        <span class="meta-label"><?php _e('Time:', 'aqualuxe'); ?></span>
                        <span class="meta-value booking-time"></span>
                    </div>
                    
                    <div class="event-meta-item">
                        <span class="meta-label"><?php _e('Status:', 'aqualuxe'); ?></span>
                        <span class="meta-value booking-status"></span>
                    </div>
                    
                    <div class="event-meta-item">
                        <span class="meta-label"><?php _e('Quantity:', 'aqualuxe'); ?></span>
                        <span class="meta-value booking-quantity"></span>
                    </div>
                    
                    <div class="event-meta-item">
                        <span class="meta-label"><?php _e('Total:', 'aqualuxe'); ?></span>
                        <span class="meta-value booking-total"></span>
                    </div>
                </div>
                
                <div class="event-customer">
                    <h4><?php _e('Customer Details', 'aqualuxe'); ?></h4>
                    
                    <div class="event-meta-item">
                        <span class="meta-label"><?php _e('Name:', 'aqualuxe'); ?></span>
                        <span class="meta-value customer-name"></span>
                    </div>
                    
                    <div class="event-meta-item">
                        <span class="meta-label"><?php _e('Email:', 'aqualuxe'); ?></span>
                        <span class="meta-value customer-email"></span>
                    </div>
                    
                    <div class="event-meta-item">
                        <span class="meta-label"><?php _e('Phone:', 'aqualuxe'); ?></span>
                        <span class="meta-value customer-phone"></span>
                    </div>
                </div>
                
                <div class="event-notes" style="display:none;">
                    <h4><?php _e('Notes', 'aqualuxe'); ?></h4>
                    <p class="customer-notes"></p>
                </div>
                
                <div class="event-actions">
                    <a href="#" class="button view-booking"><?php _e('View Booking', 'aqualuxe'); ?></a>
                    <a href="#" class="button edit-booking"><?php _e('Edit Booking', 'aqualuxe'); ?></a>
                    
                    <div class="status-actions">
                        <label for="booking-status"><?php _e('Change Status:', 'aqualuxe'); ?></label>
                        <select id="booking-status">
                            <option value="aqualuxe-pending"><?php _e('Pending', 'aqualuxe'); ?></option>
                            <option value="aqualuxe-confirmed"><?php _e('Confirmed', 'aqualuxe'); ?></option>
                            <option value="aqualuxe-completed"><?php _e('Completed', 'aqualuxe'); ?></option>
                            <option value="aqualuxe-cancelled"><?php _e('Cancelled', 'aqualuxe'); ?></option>
                        </select>
                        <button type="button" class="button update-status"><?php _e('Update', 'aqualuxe'); ?></button>
                    </div>
                    
                    <a href="#" class="button button-link-delete delete-booking"><?php _e('Delete Booking', 'aqualuxe'); ?></a>
                </div>
            </div>
        </div>
    </div>
    
    <div id="aqualuxe-bookings-availability-dialog" title="<?php esc_attr_e('Manage Availability', 'aqualuxe'); ?>" style="display:none;">
        <div class="availability-dialog-content">
            <form id="availability-form">
                <input type="hidden" id="rule_id" value="0">
                
                <div class="form-field">
                    <label for="availability_service_id"><?php _e('Service:', 'aqualuxe'); ?></label>
                    <select id="availability_service_id" required>
                        <?php foreach ($services as $service) : ?>
                            <option value="<?php echo esc_attr($service->ID); ?>" <?php selected($selected_service_id, $service->ID); ?>><?php echo esc_html($service->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="availability_from_date"><?php _e('From Date:', 'aqualuxe'); ?></label>
                    <input type="text" id="availability_from_date" class="aqualuxe-bookings-datepicker" required>
                </div>
                
                <div class="form-field">
                    <label for="availability_to_date"><?php _e('To Date:', 'aqualuxe'); ?></label>
                    <input type="text" id="availability_to_date" class="aqualuxe-bookings-datepicker" required>
                </div>
                
                <div class="form-field">
                    <label for="availability_bookable"><?php _e('Bookable:', 'aqualuxe'); ?></label>
                    <select id="availability_bookable">
                        <option value="1"><?php _e('Yes', 'aqualuxe'); ?></option>
                        <option value="0"><?php _e('No', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="button button-primary"><?php _e('Save', 'aqualuxe'); ?></button>
                    <button type="button" class="button cancel-rule"><?php _e('Cancel', 'aqualuxe'); ?></button>
                    <button type="button" class="button button-link-delete delete-rule" style="display:none;"><?php _e('Delete', 'aqualuxe'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Initialize datepicker
    $('.aqualuxe-bookings-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    
    // Initialize calendar
    var calendarEl = document.getElementById('aqualuxe-bookings-admin-calendar');
    
    if (!calendarEl) {
        return;
    }
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: '<?php echo esc_js($view . 'Grid'); ?>',
        initialDate: '<?php echo esc_js($date); ?>',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        firstDay: parseInt(aqualuxe_bookings_calendar_params.settings.calendar_first_day) || 0,
        weekends: true,
        allDaySlot: true,
        slotDuration: '00:30:00',
        slotLabelInterval: '01:00:00',
        snapDuration: '00:15:00',
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        },
        dayMaxEvents: 0,
        eventDisplay: 'block',
        eventColor: aqualuxe_bookings_calendar_params.settings.color_scheme,
        nowIndicator: true,
        locale: document.documentElement.lang || 'en',
        buttonText: {
            today: aqualuxe_bookings_calendar_params.i18n.today,
            month: aqualuxe_bookings_calendar_params.i18n.month,
            week: aqualuxe_bookings_calendar_params.i18n.week,
            day: aqualuxe_bookings_calendar_params.i18n.day,
            list: aqualuxe_bookings_calendar_params.i18n.list
        },
        selectable: true,
        select: function(info) {
            // Open availability dialog
            openAvailabilityDialog({
                service_id: <?php echo esc_js($selected_service_id); ?>,
                from_date: info.startStr,
                to_date: info.endStr
            });
        },
        eventClick: function(info) {
            // Handle event click
            if (info.event.extendedProps.type === 'booking') {
                openBookingDialog(info.event.id);
            } else if (info.event.extendedProps.type === 'availability_rule') {
                openAvailabilityDialog({
                    rule_id: info.event.extendedProps.rule_id,
                    service_id: info.event.extendedProps.service_id,
                    from_date: info.event.start,
                    to_date: info.event.end,
                    bookable: info.event.extendedProps.bookable
                });
            }
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            // Fetch events via AJAX
            $.ajax({
                url: aqualuxe_bookings_calendar_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_calendar_events',
                    service_id: <?php echo esc_js($selected_service_id); ?>,
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr,
                    nonce: aqualuxe_bookings_calendar_params.nonce
                },
                success: function(response) {
                    if (response.success) {
                        successCallback(response.data.events);
                    } else {
                        failureCallback(new Error(response.data.message));
                    }
                },
                error: function(xhr, status, error) {
                    failureCallback(new Error(error));
                }
            });
        }
    });
    
    calendar.render();
    
    // Initialize dialogs
    $('#aqualuxe-bookings-event-dialog').dialog({
        autoOpen: false,
        modal: true,
        width: 500,
        height: 'auto',
        resizable: false,
        draggable: false,
        closeOnEscape: true,
        close: function() {
            $('.event-dialog-details').hide();
            $('.event-dialog-loading').show();
        }
    });
    
    $('#aqualuxe-bookings-availability-dialog').dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        height: 'auto',
        resizable: false,
        draggable: false,
        closeOnEscape: true,
        close: function() {
            $('#availability-form')[0].reset();
            $('#rule_id').val(0);
            $('.delete-rule').hide();
        }
    });
    
    // Open booking dialog
    function openBookingDialog(bookingId) {
        $('#aqualuxe-bookings-event-dialog').dialog('open');
        
        // Show loading
        $('.event-dialog-details').hide();
        $('.event-dialog-loading').show();
        
        // Get booking details
        $.ajax({
            url: aqualuxe_bookings_calendar_params.ajax_url,
            type: 'POST',
            data: {
                action: 'get_booking_details',
                booking_id: bookingId,
                nonce: aqualuxe_bookings_calendar_params.nonce
            },
            success: function(response) {
                if (response.success) {
                    var booking = response.data.booking;
                    
                    // Set dialog title
                    $('#aqualuxe-bookings-event-dialog').dialog('option', 'title', aqualuxe_bookings_calendar_params.i18n.booking_details);
                    
                    // Fill booking details
                    $('.event-title').text(booking.customer_name + ' - ' + booking.service_name);
                    $('.booking-id').text(booking.booking_id);
                    $('.service-name').text(booking.service_name);
                    $('.booking-date').text(booking.formatted_date);
                    $('.booking-time').text(booking.formatted_time);
                    $('.booking-status').text(getStatusLabel(booking.status));
                    $('.booking-quantity').text(booking.quantity);
                    $('.booking-total').text(booking.formatted_total);
                    $('.customer-name').text(booking.customer_name);
                    $('.customer-email').text(booking.customer_email);
                    $('.customer-phone').text(booking.customer_phone);
                    
                    // Set status dropdown
                    $('#booking-status').val(booking.status);
                    
                    // Set action URLs
                    $('.view-booking').attr('href', '<?php echo esc_js(admin_url('post.php?action=edit&post=')); ?>' + booking.id);
                    $('.edit-booking').attr('href', '<?php echo esc_js(admin_url('post.php?action=edit&post=')); ?>' + booking.id);
                    
                    // Set data attributes for actions
                    $('.update-status').data('booking-id', booking.id);
                    $('.delete-booking').data('booking-id', booking.id);
                    
                    // Show customer notes if available
                    if (booking.customer_notes) {
                        $('.customer-notes').text(booking.customer_notes);
                        $('.event-notes').show();
                    } else {
                        $('.event-notes').hide();
                    }
                    
                    // Hide loading and show details
                    $('.event-dialog-loading').hide();
                    $('.event-dialog-details').show();
                } else {
                    alert(response.data.message);
                    $('#aqualuxe-bookings-event-dialog').dialog('close');
                }
            },
            error: function() {
                alert('<?php echo esc_js(__('An error occurred. Please try again.', 'aqualuxe')); ?>');
                $('#aqualuxe-bookings-event-dialog').dialog('close');
            }
        });
    }
    
    // Open availability dialog
    function openAvailabilityDialog(data) {
        // Set dialog title
        if (data.rule_id) {
            $('#aqualuxe-bookings-availability-dialog').dialog('option', 'title', aqualuxe_bookings_calendar_params.i18n.edit_availability_rule);
            $('.delete-rule').show();
        } else {
            $('#aqualuxe-bookings-availability-dialog').dialog('option', 'title', aqualuxe_bookings_calendar_params.i18n.add_availability_rule);
            $('.delete-rule').hide();
        }
        
        // Fill form fields
        $('#rule_id').val(data.rule_id || 0);
        $('#availability_service_id').val(data.service_id || <?php echo esc_js($selected_service_id); ?>);
        
        if (data.from_date) {
            var fromDate = data.from_date instanceof Date ? data.from_date : new Date(data.from_date);
            $('#availability_from_date').datepicker('setDate', fromDate);
        }
        
        if (data.to_date) {
            var toDate = data.to_date instanceof Date ? data.to_date : new Date(data.to_date);
            $('#availability_to_date').datepicker('setDate', toDate);
        }
        
        $('#availability_bookable').val(data.bookable !== undefined ? (data.bookable ? '1' : '0') : '1');
        
        // Open dialog
        $('#aqualuxe-bookings-availability-dialog').dialog('open');
    }
    
    // Handle update status
    $('.update-status').on('click', function() {
        var bookingId = $(this).data('booking-id');
        var status = $('#booking-status').val();
        
        $.ajax({
            url: aqualuxe_bookings_calendar_params.ajax_url,
            type: 'POST',
            data: {
                action: 'update_booking_status',
                booking_id: bookingId,
                status: status,
                nonce: aqualuxe_bookings_calendar_params.nonce
            },
            beforeSend: function() {
                $('.update-status').prop('disabled', true).text('<?php echo esc_js(__('Updating...', 'aqualuxe')); ?>');
            },
            success: function(response) {
                if (response.success) {
                    $('#aqualuxe-bookings-event-dialog').dialog('close');
                    calendar.refetchEvents();
                } else {
                    alert(response.data.message);
                }
                $('.update-status').prop('disabled', false).text('<?php echo esc_js(__('Update', 'aqualuxe')); ?>');
            },
            error: function() {
                alert('<?php echo esc_js(__('An error occurred. Please try again.', 'aqualuxe')); ?>');
                $('.update-status').prop('disabled', false).text('<?php echo esc_js(__('Update', 'aqualuxe')); ?>');
            }
        });
    });
    
    // Handle delete booking
    $('.delete-booking').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm(aqualuxe_bookings_calendar_params.i18n.confirm_delete)) {
            return;
        }
        
        var bookingId = $(this).data('booking-id');
        
        $.ajax({
            url: aqualuxe_bookings_calendar_params.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_booking',
                booking_id: bookingId,
                nonce: aqualuxe_bookings_calendar_params.nonce
            },
            beforeSend: function() {
                $('.delete-booking').prop('disabled', true).text('<?php echo esc_js(__('Deleting...', 'aqualuxe')); ?>');
            },
            success: function(response) {
                if (response.success) {
                    $('#aqualuxe-bookings-event-dialog').dialog('close');
                    calendar.refetchEvents();
                } else {
                    alert(response.data.message);
                    $('.delete-booking').prop('disabled', false).text('<?php echo esc_js(__('Delete Booking', 'aqualuxe')); ?>');
                }
            },
            error: function() {
                alert('<?php echo esc_js(__('An error occurred. Please try again.', 'aqualuxe')); ?>');
                $('.delete-booking').prop('disabled', false).text('<?php echo esc_js(__('Delete Booking', 'aqualuxe')); ?>');
            }
        });
    });
    
    // Handle save availability rule
    $('#availability-form').on('submit', function(e) {
        e.preventDefault();
        
        var ruleId = $('#rule_id').val();
        var serviceId = $('#availability_service_id').val();
        var fromDate = $('#availability_from_date').val();
        var toDate = $('#availability_to_date').val();
        var bookable = $('#availability_bookable').val();
        
        $.ajax({
            url: aqualuxe_bookings_calendar_params.ajax_url,
            type: 'POST',
            data: {
                action: 'save_availability_rule',
                rule_id: ruleId,
                service_id: serviceId,
                date_from: fromDate,
                date_to: toDate,
                bookable: bookable,
                nonce: aqualuxe_bookings_calendar_params.nonce
            },
            beforeSend: function() {
                $('#availability-form button[type="submit"]').prop('disabled', true).text('<?php echo esc_js(__('Saving...', 'aqualuxe')); ?>');
            },
            success: function(response) {
                if (response.success) {
                    $('#aqualuxe-bookings-availability-dialog').dialog('close');
                    calendar.refetchEvents();
                } else {
                    alert(response.data.message);
                }
                $('#availability-form button[type="submit"]').prop('disabled', false).text('<?php echo esc_js(__('Save', 'aqualuxe')); ?>');
            },
            error: function() {
                alert('<?php echo esc_js(__('An error occurred. Please try again.', 'aqualuxe')); ?>');
                $('#availability-form button[type="submit"]').prop('disabled', false).text('<?php echo esc_js(__('Save', 'aqualuxe')); ?>');
            }
        });
    });
    
    // Handle delete availability rule
    $('.delete-rule').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm(aqualuxe_bookings_calendar_params.i18n.confirm_delete_rule)) {
            return;
        }
        
        var ruleId = $('#rule_id').val();
        
        if (!ruleId) {
            $('#aqualuxe-bookings-availability-dialog').dialog('close');
            return;
        }
        
        $.ajax({
            url: aqualuxe_bookings_calendar_params.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_availability_rule',
                rule_id: ruleId,
                nonce: aqualuxe_bookings_calendar_params.nonce
            },
            beforeSend: function() {
                $('.delete-rule').prop('disabled', true).text('<?php echo esc_js(__('Deleting...', 'aqualuxe')); ?>');
            },
            success: function(response) {
                if (response.success) {
                    $('#aqualuxe-bookings-availability-dialog').dialog('close');
                    calendar.refetchEvents();
                } else {
                    alert(response.data.message);
                    $('.delete-rule').prop('disabled', false).text('<?php echo esc_js(__('Delete', 'aqualuxe')); ?>');
                }
            },
            error: function() {
                alert('<?php echo esc_js(__('An error occurred. Please try again.', 'aqualuxe')); ?>');
                $('.delete-rule').prop('disabled', false).text('<?php echo esc_js(__('Delete', 'aqualuxe')); ?>');
            }
        });
    });
    
    // Handle cancel rule
    $('.cancel-rule').on('click', function() {
        $('#aqualuxe-bookings-availability-dialog').dialog('close');
    });
    
    // Get status label
    function getStatusLabel(status) {
        switch (status) {
            case 'aqualuxe-pending':
                return '<?php echo esc_js(__('Pending', 'aqualuxe')); ?>';
            case 'aqualuxe-confirmed':
                return '<?php echo esc_js(__('Confirmed', 'aqualuxe')); ?>';
            case 'aqualuxe-completed':
                return '<?php echo esc_js(__('Completed', 'aqualuxe')); ?>';
            case 'aqualuxe-cancelled':
                return '<?php echo esc_js(__('Cancelled', 'aqualuxe')); ?>';
            default:
                return status.replace('aqualuxe-', '');
        }
    }
    
    <?php if ($selected_booking_id && $selected_booking) : ?>
    // Open selected booking
    openBookingDialog(<?php echo esc_js($selected_booking_id); ?>);
    <?php endif; ?>
});
</script>

<style>
.aqualuxe-bookings-calendar-page {
    max-width: 1200px;
}

.aqualuxe-bookings-calendar-filters {
    margin-bottom: 20px;
    padding: 15px;
    background-color: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
}

.aqualuxe-bookings-calendar-filters form {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 15px;
}

.filter-group {
    margin-bottom: 10px;
}

.filter-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}

.filter-group select,
.filter-group input[type="text"] {
    min-width: 200px;
}

.aqualuxe-bookings-calendar-container {
    background-color: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
}

#aqualuxe-bookings-admin-calendar {
    height: 700px;
}

/* Event Dialog */
.event-dialog-loading {
    text-align: center;
    padding: 20px;
}

.event-dialog-loading .spinner {
    float: none;
    margin: 0 auto 10px;
}

.event-title {
    margin-top: 0;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    color: #23282d;
}

.event-meta {
    margin-bottom: 20px;
}

.event-meta-item {
    display: flex;
    margin-bottom: 8px;
}

.meta-label {
    flex: 0 0 100px;
    font-weight: 600;
}

.meta-value {
    flex: 1;
}

.event-customer h4,
.event-notes h4 {
    margin-top: 20px;
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid #eee;
    color: #23282d;
}

.event-actions {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.event-actions .button {
    margin-right: 5px;
    margin-bottom: 10px;
}

.status-actions {
    margin-top: 15px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.status-actions label {
    margin-right: 5px;
    font-weight: 600;
}

.status-actions select {
    margin-right: 10px;
}

.delete-booking {
    display: block;
    margin-top: 15px;
    color: #a00;
}

/* Availability Dialog */
.availability-dialog-content {
    padding: 10px;
}

.form-field {
    margin-bottom: 15px;
}

.form-field label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}

.form-field input,
.form-field select {
    width: 100%;
}

.form-actions {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
}

.delete-rule {
    color: #a00;
}

/* FullCalendar Overrides */
.fc .fc-toolbar-title {
    font-size: 1.5em;
    color: #23282d;
}

.fc .fc-button-primary {
    background-color: #2271b1;
    border-color: #2271b1;
}

.fc .fc-button-primary:hover {
    background-color: #135e96;
    border-color: #135e96;
}

.fc .fc-button-primary:not(:disabled).fc-button-active,
.fc .fc-button-primary:not(:disabled):active {
    background-color: #135e96;
    border-color: #135e96;
}

.fc .fc-daygrid-day.fc-day-today {
    background-color: #f0f6fc;
}

.fc .fc-event {
    cursor: pointer;
    border-radius: 3px;
}

/* Responsive Styles */
@media screen and (max-width: 782px) {
    .aqualuxe-bookings-calendar-filters form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        margin-bottom: 15px;
    }
    
    .filter-group select,
    .filter-group input[type="text"] {
        width: 100%;
        max-width: none;
    }
    
    #aqualuxe-bookings-admin-calendar {
        height: 500px;
    }
    
    .status-actions {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .status-actions select {
        width: 100%;
        margin-right: 0;
        margin-bottom: 10px;
    }
}
</style>