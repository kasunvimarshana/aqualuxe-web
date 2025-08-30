<?php
/**
 * List Calendar Template
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get calendar data
$calendar_data = $calendar->get_list_calendar_data();
?>

<div class="aqualuxe-events-calendar aqualuxe-events-calendar-list">
    <?php if (empty($calendar_data)) : ?>
        <div class="aqualuxe-events-calendar-no-events">
            <p><?php echo esc_html__('No events found for the selected period.', 'aqualuxe'); ?></p>
        </div>
    <?php else : ?>
        <?php foreach ($calendar_data as $date => $day_data) : ?>
            <div class="aqualuxe-events-calendar-list-day" data-date="<?php echo esc_attr($date); ?>">
                <div class="aqualuxe-events-calendar-list-day-header">
                    <div class="aqualuxe-events-calendar-list-day-name"><?php echo esc_html($day_data['day_of_week']); ?></div>
                    <div class="aqualuxe-events-calendar-list-day-date"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($date))); ?></div>
                </div>
                
                <div class="aqualuxe-events-calendar-list-events">
                    <?php foreach ($day_data['events'] as $event) : ?>
                        <?php
                        $event_status = aqualuxe_get_event_status($event);
                        $event_status_class = aqualuxe_get_event_status_class($event_status);
                        ?>
                        
                        <div class="aqualuxe-events-calendar-list-event <?php echo esc_attr($event_status_class); ?>">
                            <?php if ($event->get_featured_image_url('thumbnail')) : ?>
                                <div class="aqualuxe-events-calendar-list-event-image">
                                    <a href="<?php echo esc_url($event->get_permalink()); ?>">
                                        <img src="<?php echo esc_url($event->get_featured_image_url('thumbnail')); ?>" alt="<?php echo esc_attr($event->get_title()); ?>" />
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="aqualuxe-events-calendar-list-event-content">
                                <h3 class="aqualuxe-events-calendar-list-event-title">
                                    <a href="<?php echo esc_url($event->get_permalink()); ?>">
                                        <?php echo esc_html($event->get_title()); ?>
                                    </a>
                                </h3>
                                
                                <div class="aqualuxe-events-calendar-list-event-meta">
                                    <div class="aqualuxe-events-calendar-list-event-time">
                                        <i class="aqualuxe-icon aqualuxe-icon-clock"></i>
                                        <?php if ($event->is_all_day()) : ?>
                                            <?php echo esc_html__('All Day', 'aqualuxe'); ?>
                                        <?php else : ?>
                                            <?php echo esc_html($event->get_start_time() . ' - ' . $event->get_end_time()); ?>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($event->get_venue()) : ?>
                                        <div class="aqualuxe-events-calendar-list-event-venue">
                                            <i class="aqualuxe-icon aqualuxe-icon-location"></i>
                                            <?php echo esc_html($event->get_venue()); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($event->get_data('tickets_available')) : ?>
                                        <div class="aqualuxe-events-calendar-list-event-tickets">
                                            <i class="aqualuxe-icon aqualuxe-icon-ticket"></i>
                                            <?php echo esc_html($event->get_cost()); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="aqualuxe-events-calendar-list-event-description">
                                    <?php echo wp_trim_words($event->get_short_description(), 30); ?>
                                </div>
                                
                                <div class="aqualuxe-events-calendar-list-event-actions">
                                    <a href="<?php echo esc_url($event->get_permalink()); ?>" class="aqualuxe-button">
                                        <?php echo esc_html__('View Details', 'aqualuxe'); ?>
                                    </a>
                                    
                                    <?php if ($event->has_tickets_available()) : ?>
                                        <a href="<?php echo esc_url($event->get_permalink() . '#tickets'); ?>" class="aqualuxe-button aqualuxe-button-primary">
                                            <?php echo esc_html__('Get Tickets', 'aqualuxe'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>