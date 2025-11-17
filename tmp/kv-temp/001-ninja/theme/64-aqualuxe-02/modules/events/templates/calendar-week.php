<?php
/**
 * Week Calendar Template
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get calendar data
$calendar_data = $calendar->get_week_calendar_data();
?>

<div class="aqualuxe-events-calendar aqualuxe-events-calendar-week">
    <div class="aqualuxe-events-calendar-header">
        <div class="aqualuxe-events-calendar-weekdays">
            <?php foreach ($calendar_data as $day) : ?>
                <?php
                $day_classes = array('aqualuxe-events-calendar-weekday');
                
                if ($day['date'] === date('Y-m-d')) {
                    $day_classes[] = 'aqualuxe-events-calendar-weekday-today';
                }
                ?>
                
                <div class="<?php echo esc_attr(implode(' ', $day_classes)); ?>" data-date="<?php echo esc_attr($day['date']); ?>">
                    <div class="aqualuxe-events-calendar-weekday-name"><?php echo esc_html($day['day_of_week']); ?></div>
                    <div class="aqualuxe-events-calendar-weekday-date"><?php echo esc_html(date_i18n('M j', strtotime($day['date']))); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="aqualuxe-events-calendar-body">
        <div class="aqualuxe-events-calendar-timeline">
            <?php for ($hour = 0; $hour < 24; $hour++) : ?>
                <div class="aqualuxe-events-calendar-hour">
                    <div class="aqualuxe-events-calendar-hour-label">
                        <?php echo esc_html(date_i18n(get_option('time_format'), strtotime("$hour:00"))); ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
        
        <div class="aqualuxe-events-calendar-week-grid">
            <?php foreach ($calendar_data as $day_index => $day) : ?>
                <?php
                $day_classes = array('aqualuxe-events-calendar-week-day');
                
                if ($day['date'] === date('Y-m-d')) {
                    $day_classes[] = 'aqualuxe-events-calendar-week-day-today';
                }
                ?>
                
                <div class="<?php echo esc_attr(implode(' ', $day_classes)); ?>" data-date="<?php echo esc_attr($day['date']); ?>">
                    <?php foreach ($day['events'] as $event) : ?>
                        <?php
                        $event_status = aqualuxe_get_event_status($event);
                        $event_status_class = aqualuxe_get_event_status_class($event_status);
                        
                        // Calculate event position and height
                        $start_time = strtotime($event->get_data('start_time'));
                        $end_time = strtotime($event->get_data('end_time'));
                        
                        if ($event->is_all_day()) {
                            $top = 0;
                            $height = 40;
                            $all_day = true;
                        } else {
                            $start_hour = date('G', $start_time);
                            $start_minute = date('i', $start_time);
                            $end_hour = date('G', $end_time);
                            $end_minute = date('i', $end_time);
                            
                            $top = ($start_hour * 60 + $start_minute) / 60 * 60;
                            $height = max(30, (($end_hour * 60 + $end_minute) - ($start_hour * 60 + $start_minute)) / 60 * 60);
                            $all_day = false;
                        }
                        ?>
                        
                        <div class="aqualuxe-events-calendar-week-event <?php echo esc_attr($event_status_class); ?> <?php echo $all_day ? 'aqualuxe-events-calendar-week-event-all-day' : ''; ?>" style="<?php echo !$all_day ? 'top: ' . esc_attr($top) . 'px; height: ' . esc_attr($height) . 'px;' : ''; ?>">
                            <a href="<?php echo esc_url($event->get_permalink()); ?>" class="aqualuxe-events-calendar-week-event-link">
                                <span class="aqualuxe-events-calendar-week-event-title"><?php echo esc_html($event->get_title()); ?></span>
                                
                                <?php if (!$all_day) : ?>
                                    <span class="aqualuxe-events-calendar-week-event-time">
                                        <?php echo esc_html($event->get_start_time() . ' - ' . $event->get_end_time()); ?>
                                    </span>
                                <?php else : ?>
                                    <span class="aqualuxe-events-calendar-week-event-time">
                                        <?php echo esc_html__('All Day', 'aqualuxe'); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($event->get_venue()) : ?>
                                    <span class="aqualuxe-events-calendar-week-event-venue">
                                        <?php echo esc_html($event->get_venue()); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>