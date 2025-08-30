<?php
/**
 * Booking Calendar Template
 *
 * This template can be overridden by copying it to yourtheme/aqualuxe/bookings/booking-calendar.php.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get calendar data
$service_id = isset($service_id) ? $service_id : 0;
$show_title = isset($show_title) ? $show_title : true;
$show_legend = isset($show_legend) ? $show_legend : true;
$view = isset($view) ? $view : 'month';
$height = isset($height) ? $height : 'auto';
$events_limit = isset($events_limit) ? $events_limit : 10;
$show_weekends = isset($show_weekends) ? $show_weekends : true;
$show_all_day = isset($show_all_day) ? $show_all_day : true;
$show_time = isset($show_time) ? $show_time : true;
$show_booking_button = isset($show_booking_button) ? $show_booking_button : true;
$booking_button_text = isset($booking_button_text) ? $booking_button_text : __('Book Now', 'aqualuxe');

// Get service data
$service_name = '';

if ($service_id) {
    $service = get_post($service_id);
    if ($service && $service->post_type === 'bookable_service') {
        $service_name = $service->post_title;
    }
}

// Get booking page URL
$booking_page_id = get_option('aqualuxe_bookings_page_id');
$booking_url = $booking_page_id ? add_query_arg(array('service_id' => $service_id), get_permalink($booking_page_id)) : '';

// Enqueue required scripts and styles
wp_enqueue_style('fullcalendar');
wp_enqueue_style('aqualuxe-bookings-calendar');
wp_enqueue_script('fullcalendar');
wp_enqueue_script('aqualuxe-bookings-calendar');
?>

<div class="aqualuxe-bookings-calendar-container" data-service-id="<?php echo esc_attr($service_id); ?>">
    <?php if ($show_title && $service_name) : ?>
        <h2 class="aqualuxe-bookings-calendar-title"><?php echo esc_html(sprintf(__('Availability Calendar: %s', 'aqualuxe'), $service_name)); ?></h2>
    <?php elseif ($show_title) : ?>
        <h2 class="aqualuxe-bookings-calendar-title"><?php echo esc_html(__('Availability Calendar', 'aqualuxe')); ?></h2>
    <?php endif; ?>
    
    <?php if ($show_booking_button && $booking_url) : ?>
        <div class="aqualuxe-bookings-calendar-booking-button">
            <a href="<?php echo esc_url($booking_url); ?>" class="button"><?php echo esc_html($booking_button_text); ?></a>
        </div>
    <?php endif; ?>
    
    <div id="aqualuxe-bookings-calendar" class="aqualuxe-bookings-calendar"
        data-service-id="<?php echo esc_attr($service_id); ?>"
        data-view="<?php echo esc_attr($view); ?>"
        data-height="<?php echo esc_attr($height); ?>"
        data-events-limit="<?php echo esc_attr($events_limit); ?>"
        data-show-weekends="<?php echo esc_attr($show_weekends ? 'true' : 'false'); ?>"
        data-show-all-day="<?php echo esc_attr($show_all_day ? 'true' : 'false'); ?>"
        data-show-time="<?php echo esc_attr($show_time ? 'true' : 'false'); ?>">
    </div>
    
    <?php if ($show_legend) : ?>
        <div class="aqualuxe-bookings-calendar-legend">
            <ul>
                <li><span class="legend-color available"></span><?php _e('Available', 'aqualuxe'); ?></li>
                <li><span class="legend-color partially-booked"></span><?php _e('Partially Booked', 'aqualuxe'); ?></li>
                <li><span class="legend-color fully-booked"></span><?php _e('Fully Booked', 'aqualuxe'); ?></li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    // Initialize calendar
    var calendarEl = document.getElementById('aqualuxe-bookings-calendar');
    
    if (!calendarEl) {
        return;
    }
    
    var $calendar = $(calendarEl);
    var serviceId = $calendar.data('service-id') || 0;
    var initialView = $calendar.data('view') || 'dayGridMonth';
    var calendarHeight = $calendar.data('height') || 'auto';
    var eventsLimit = $calendar.data('events-limit') || 10;
    var showWeekends = $calendar.data('show-weekends') !== 'false';
    var showAllDay = $calendar.data('show-all-day') !== 'false';
    var showTime = $calendar.data('show-time') !== 'false';
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: initialView,
        height: calendarHeight,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        firstDay: parseInt(aqualuxe_bookings_calendar_params.settings.calendar_first_day) || 0,
        weekends: showWeekends,
        allDaySlot: showAllDay,
        slotDuration: '00:30:00',
        slotLabelInterval: '01:00:00',
        snapDuration: '00:15:00',
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        },
        dayMaxEvents: eventsLimit,
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
        eventDidMount: function(info) {
            // Add tooltip
            if (info.event.extendedProps.type === 'availability') {
                var slots = info.event.extendedProps.available_slots || 0;
                var tooltipText = slots > 0 
                    ? slots + ' ' + (slots === 1 ? 'slot' : 'slots') + ' available' 
                    : 'Fully booked';
                
                $(info.el).attr('title', tooltipText);
            }
        },
        eventClick: function(info) {
            // Handle event click
            if (info.event.extendedProps.type === 'availability' && info.event.extendedProps.available_slots > 0) {
                // Redirect to booking page with date pre-selected
                var date = info.event.start;
                var formattedDate = date.toISOString().split('T')[0]; // YYYY-MM-DD
                
                window.location.href = '<?php echo esc_js($booking_url); ?>&date=' + formattedDate;
            }
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            // Fetch events via AJAX
            $.ajax({
                url: aqualuxe_bookings_calendar_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_calendar_events',
                    service_id: serviceId,
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
});
</script>

<style>
.aqualuxe-bookings-calendar-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.aqualuxe-bookings-calendar-title {
    margin-top: 0;
    margin-bottom: 20px;
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.aqualuxe-bookings-calendar-booking-button {
    margin-bottom: 20px;
    text-align: right;
}

.aqualuxe-bookings-calendar {
    margin-bottom: 20px;
}

.aqualuxe-bookings-calendar-legend {
    margin-top: 20px;
}

.aqualuxe-bookings-calendar-legend ul {
    display: flex;
    flex-wrap: wrap;
    list-style: none;
    padding: 0;
    margin: 0;
}

.aqualuxe-bookings-calendar-legend li {
    display: flex;
    align-items: center;
    margin-right: 20px;
    margin-bottom: 10px;
}

.legend-color {
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-right: 8px;
    border-radius: 3px;
}

.legend-color.available {
    background-color: #c6e1c6;
}

.legend-color.partially-booked {
    background-color: #f8dda7;
}

.legend-color.fully-booked {
    background-color: #eba3a3;
}

/* FullCalendar Overrides */
.fc .fc-toolbar-title {
    font-size: 1.5em;
    color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.fc .fc-button-primary {
    background-color: var(--aqualuxe-bookings-primary-color, #0073aa);
    border-color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.fc .fc-button-primary:hover {
    background-color: var(--aqualuxe-bookings-primary-color-dark, #005d87);
    border-color: var(--aqualuxe-bookings-primary-color-dark, #005d87);
}

.fc .fc-button-primary:not(:disabled).fc-button-active,
.fc .fc-button-primary:not(:disabled):active {
    background-color: var(--aqualuxe-bookings-primary-color-dark, #005d87);
    border-color: var(--aqualuxe-bookings-primary-color-dark, #005d87);
}

.fc .fc-daygrid-day.fc-day-today {
    background-color: var(--aqualuxe-bookings-primary-color-light, #e1f5fe);
}

.fc .fc-event {
    cursor: pointer;
    border-radius: 4px;
}

.fc .fc-event.fc-event-start {
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
}

.fc .fc-event.fc-event-end {
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
}

/* Calendar Style Variations */
.aqualuxe-bookings-calendar-style-minimal .fc .fc-toolbar {
    margin-bottom: 1em;
}

.aqualuxe-bookings-calendar-style-minimal .fc .fc-toolbar-title {
    font-size: 1.2em;
}

.aqualuxe-bookings-calendar-style-minimal .fc .fc-button {
    padding: 0.2em 0.65em;
    font-size: 0.9em;
}

.aqualuxe-bookings-calendar-style-minimal .fc .fc-daygrid-day-top {
    justify-content: center;
}

.aqualuxe-bookings-calendar-style-minimal .fc .fc-daygrid-day-number {
    padding: 4px;
}

.aqualuxe-bookings-calendar-style-modern .fc {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.aqualuxe-bookings-calendar-style-modern .fc .fc-toolbar {
    padding: 16px;
    background-color: var(--aqualuxe-bookings-primary-color, #0073aa);
}

.aqualuxe-bookings-calendar-style-modern .fc .fc-toolbar-title {
    color: #fff;
}

.aqualuxe-bookings-calendar-style-modern .fc .fc-button-primary {
    background-color: rgba(255, 255, 255, 0.2);
    border-color: transparent;
}

.aqualuxe-bookings-calendar-style-modern .fc .fc-button-primary:hover {
    background-color: rgba(255, 255, 255, 0.3);
    border-color: transparent;
}

.aqualuxe-bookings-calendar-style-modern .fc .fc-button-primary:not(:disabled).fc-button-active,
.aqualuxe-bookings-calendar-style-modern .fc .fc-button-primary:not(:disabled):active {
    background-color: rgba(255, 255, 255, 0.4);
    border-color: transparent;
}

.aqualuxe-bookings-calendar-style-modern .fc .fc-col-header-cell {
    padding: 8px 0;
    background-color: #f9f9f9;
}

.aqualuxe-bookings-calendar-style-modern .fc .fc-daygrid-day {
    transition: background-color 0.2s;
}

.aqualuxe-bookings-calendar-style-modern .fc .fc-daygrid-day:hover {
    background-color: #f9f9f9;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .fc .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }
    
    .fc .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
    }
    
    .fc .fc-toolbar-title {
        font-size: 1.2em;
    }
    
    .fc .fc-button {
        padding: 0.3em 0.6em;
        font-size: 0.9em;
    }
}
</style>