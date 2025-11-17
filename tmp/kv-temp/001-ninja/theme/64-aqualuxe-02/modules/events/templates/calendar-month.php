<?php
/**
 * Month Calendar Template
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get calendar data
$calendar_data = $calendar->get_month_calendar_data();
?>

<div class="aqualuxe-events-calendar aqualuxe-events-calendar-month">
    <div class="aqualuxe-events-calendar-header">
        <div class="aqualuxe-events-calendar-weekdays">
            <div class="aqualuxe-events-calendar-weekday"><?php echo esc_html__('Sun', 'aqualuxe'); ?></div>
            <div class="aqualuxe-events-calendar-weekday"><?php echo esc_html__('Mon', 'aqualuxe'); ?></div>
            <div class="aqualuxe-events-calendar-weekday"><?php echo esc_html__('Tue', 'aqualuxe'); ?></div>
            <div class="aqualuxe-events-calendar-weekday"><?php echo esc_html__('Wed', 'aqualuxe'); ?></div>
            <div class="aqualuxe-events-calendar-weekday"><?php echo esc_html__('Thu', 'aqualuxe'); ?></div>
            <div class="aqualuxe-events-calendar-weekday"><?php echo esc_html__('Fri', 'aqualuxe'); ?></div>
            <div class="aqualuxe-events-calendar-weekday"><?php echo esc_html__('Sat', 'aqualuxe'); ?></div>
        </div>
    </div>
    
    <div class="aqualuxe-events-calendar-body">
        <div class="aqualuxe-events-calendar-grid">
            <?php foreach ($calendar_data as $day) : ?>
                <?php
                $day_classes = array('aqualuxe-events-calendar-day');
                
                if (!$day['is_current_month']) {
                    $day_classes[] = 'aqualuxe-events-calendar-day-other-month';
                }
                
                if ($day['date'] === date('Y-m-d')) {
                    $day_classes[] = 'aqualuxe-events-calendar-day-today';
                }
                
                if (!empty($day['events'])) {
                    $day_classes[] = 'aqualuxe-events-calendar-day-has-events';
                }
                ?>
                
                <div class="<?php echo esc_attr(implode(' ', $day_classes)); ?>" data-date="<?php echo esc_attr($day['date']); ?>">
                    <?php if ($day['day']) : ?>
                        <div class="aqualuxe-events-calendar-day-number"><?php echo esc_html($day['day']); ?></div>
                        
                        <?php if (!empty($day['events'])) : ?>
                            <div class="aqualuxe-events-calendar-day-events">
                                <?php foreach ($day['events'] as $event) : ?>
                                    <?php
                                    $event_status = aqualuxe_get_event_status($event);
                                    $event_status_class = aqualuxe_get_event_status_class($event_status);
                                    ?>
                                    
                                    <div class="aqualuxe-events-calendar-event <?php echo esc_attr($event_status_class); ?>">
                                        <a href="<?php echo esc_url($event->get_permalink()); ?>" class="aqualuxe-events-calendar-event-link">
                                            <?php if ($event->is_all_day()) : ?>
                                                <span class="aqualuxe-events-calendar-event-time"><?php echo esc_html__('All Day', 'aqualuxe'); ?></span>
                                            <?php else : ?>
                                                <span class="aqualuxe-events-calendar-event-time"><?php echo esc_html($event->get_start_time()); ?></span>
                                            <?php endif; ?>
                                            
                                            <span class="aqualuxe-events-calendar-event-title"><?php echo esc_html($event->get_title()); ?></span>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                                
                                <?php if (count($day['events']) > 3) : ?>
                                    <div class="aqualuxe-events-calendar-more-events">
                                        <a href="<?php echo esc_url(add_query_arg(array('view' => 'day', 'date' => $day['date']))); ?>" class="aqualuxe-events-calendar-more-events-link">
                                            <?php echo esc_html(sprintf(
                                                __('+ %d more', 'aqualuxe'),
                                                count($day['events']) - 3
                                            )); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>