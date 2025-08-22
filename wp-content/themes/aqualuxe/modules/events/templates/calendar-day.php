<?php
/**
 * Day Calendar Template
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get calendar data
$calendar_data = $calendar->get_day_calendar_data();
$date = $calendar_data['date'];
$day_of_week = $calendar_data['day_of_week'];
$events = $calendar_data['events'];

// Separate all-day events from timed events
$all_day_events = array();
$timed_events = array();

foreach ($events as $event) {
    if ($event->is_all_day()) {
        $all_day_events[] = $event;
    } else {
        $timed_events[] = $event;
    }
}
?>

<div class="aqualuxe-events-calendar aqualuxe-events-calendar-day">
    <div class="aqualuxe-events-calendar-header">
        <div class="aqualuxe-events-calendar-day-header">
            <div class="aqualuxe-events-calendar-day-name"><?php echo esc_html($day_of_week); ?></div>
            <div class="aqualuxe-events-calendar-day-date"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($date))); ?></div>
        </div>
    </div>
    
    <div class="aqualuxe-events-calendar-body">
        <?php if (!empty($all_day_events)) : ?>
            <div class="aqualuxe-events-calendar-all-day-events">
                <h3 class="aqualuxe-events-calendar-section-title"><?php echo esc_html__('All Day Events', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-events-calendar-events-list">
                    <?php foreach ($all_day_events as $event) : ?>
                        <?php
                        $event_status = aqualuxe_get_event_status($event);
                        $event_status_class = aqualuxe_get_event_status_class($event_status);
                        ?>
                        
                        <div class="aqualuxe-events-calendar-event <?php echo esc_attr($event_status_class); ?>">
                            <div class="aqualuxe-events-calendar-event-content">
                                <h4 class="aqualuxe-events-calendar-event-title">
                                    <a href="<?php echo esc_url($event->get_permalink()); ?>">
                                        <?php echo esc_html($event->get_title()); ?>
                                    </a>
                                </h4>
                                
                                <?php if ($event->get_venue()) : ?>
                                    <div class="aqualuxe-events-calendar-event-venue">
                                        <i class="aqualuxe-icon aqualuxe-icon-location"></i>
                                        <?php echo esc_html($event->get_venue()); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="aqualuxe-events-calendar-event-description">
                                    <?php echo wp_trim_words($event->get_short_description(), 20); ?>
                                </div>
                                
                                <div class="aqualuxe-events-calendar-event-actions">
                                    <a href="<?php echo esc_url($event->get_permalink()); ?>" class="aqualuxe-button aqualuxe-button-small">
                                        <?php echo esc_html__('View Details', 'aqualuxe'); ?>
                                    </a>
                                    
                                    <?php if ($event->has_tickets_available()) : ?>
                                        <a href="<?php echo esc_url($event->get_permalink() . '#tickets'); ?>" class="aqualuxe-button aqualuxe-button-small aqualuxe-button-primary">
                                            <?php echo esc_html__('Get Tickets', 'aqualuxe'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($timed_events)) : ?>
            <div class="aqualuxe-events-calendar-timed-events">
                <h3 class="aqualuxe-events-calendar-section-title"><?php echo esc_html__('Scheduled Events', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-events-calendar-timeline">
                    <?php for ($hour = 0; $hour < 24; $hour++) : ?>
                        <div class="aqualuxe-events-calendar-hour">
                            <div class="aqualuxe-events-calendar-hour-label">
                                <?php echo esc_html(date_i18n(get_option('time_format'), strtotime("$hour:00"))); ?>
                            </div>
                            
                            <div class="aqualuxe-events-calendar-hour-events">
                                <?php foreach ($timed_events as $event) : ?>
                                    <?php
                                    $event_status = aqualuxe_get_event_status($event);
                                    $event_status_class = aqualuxe_get_event_status_class($event_status);
                                    
                                    // Check if event is in this hour
                                    $start_time = strtotime($event->get_data('start_time'));
                                    $end_time = strtotime($event->get_data('end_time'));
                                    $start_hour = date('G', $start_time);
                                    
                                    if ($start_hour === (string)$hour) :
                                        $start_minute = date('i', $start_time);
                                        $end_hour = date('G', $end_time);
                                        $end_minute = date('i', $end_time);
                                        
                                        $top = $start_minute / 60 * 100;
                                        $height = max(25, (($end_hour * 60 + $end_minute) - ($start_hour * 60 + $start_minute)) / 60 * 100);
                                        
                                        if ($height > 100) {
                                            $height = 100;
                                        }
                                    ?>
                                        <div class="aqualuxe-events-calendar-day-event <?php echo esc_attr($event_status_class); ?>" style="top: <?php echo esc_attr($top); ?>%; height: <?php echo esc_attr($height); ?>%;">
                                            <a href="<?php echo esc_url($event->get_permalink()); ?>" class="aqualuxe-events-calendar-day-event-link">
                                                <span class="aqualuxe-events-calendar-day-event-time">
                                                    <?php echo esc_html($event->get_start_time() . ' - ' . $event->get_end_time()); ?>
                                                </span>
                                                
                                                <span class="aqualuxe-events-calendar-day-event-title">
                                                    <?php echo esc_html($event->get_title()); ?>
                                                </span>
                                                
                                                <?php if ($event->get_venue()) : ?>
                                                    <span class="aqualuxe-events-calendar-day-event-venue">
                                                        <?php echo esc_html($event->get_venue()); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (empty($events)) : ?>
            <div class="aqualuxe-events-calendar-no-events">
                <p><?php echo esc_html__('No events scheduled for this day.', 'aqualuxe'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>