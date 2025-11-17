<?php
/**
 * Event Grid Item Template
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$event_status = aqualuxe_get_event_status($event);
$event_status_class = aqualuxe_get_event_status_class($event_status);
$event_status_label = aqualuxe_get_event_status_label($event_status);
?>

<div class="aqualuxe-event-grid-item <?php echo esc_attr($event_status_class); ?>">
    <div class="aqualuxe-event-grid-inner">
        <?php if ($event->get_featured_image_url('medium')) : ?>
            <div class="aqualuxe-event-grid-image">
                <a href="<?php echo esc_url($event->get_permalink()); ?>">
                    <img src="<?php echo esc_url($event->get_featured_image_url('medium')); ?>" alt="<?php echo esc_attr($event->get_title()); ?>" />
                    
                    <div class="aqualuxe-event-status-badge">
                        <span class="aqualuxe-event-status"><?php echo esc_html($event_status_label); ?></span>
                    </div>
                </a>
            </div>
        <?php endif; ?>
        
        <div class="aqualuxe-event-grid-content">
            <div class="aqualuxe-event-grid-date">
                <div class="aqualuxe-event-grid-date-day">
                    <?php echo esc_html(date_i18n('d', strtotime($event->get_data('start_date')))); ?>
                </div>
                <div class="aqualuxe-event-grid-date-month">
                    <?php echo esc_html(date_i18n('M', strtotime($event->get_data('start_date')))); ?>
                </div>
            </div>
            
            <h3 class="aqualuxe-event-grid-title">
                <a href="<?php echo esc_url($event->get_permalink()); ?>">
                    <?php echo esc_html($event->get_title()); ?>
                </a>
            </h3>
            
            <div class="aqualuxe-event-grid-meta">
                <div class="aqualuxe-event-grid-time">
                    <i class="aqualuxe-icon aqualuxe-icon-clock"></i>
                    <?php if ($event->is_all_day()) : ?>
                        <?php echo esc_html__('All Day', 'aqualuxe'); ?>
                    <?php else : ?>
                        <?php echo esc_html($event->get_start_time()); ?>
                    <?php endif; ?>
                </div>
                
                <?php if ($event->get_venue()) : ?>
                    <div class="aqualuxe-event-grid-venue">
                        <i class="aqualuxe-icon aqualuxe-icon-location"></i>
                        <?php echo esc_html($event->get_venue()); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($event->get_data('tickets_available')) : ?>
                <div class="aqualuxe-event-grid-price">
                    <?php echo esc_html($event->get_cost()); ?>
                </div>
            <?php endif; ?>
            
            <div class="aqualuxe-event-grid-actions">
                <a href="<?php echo esc_url($event->get_permalink()); ?>" class="aqualuxe-button aqualuxe-button-outline">
                    <?php echo esc_html__('Details', 'aqualuxe'); ?>
                </a>
                
                <?php if ($event->has_tickets_available()) : ?>
                    <a href="<?php echo esc_url($event->get_permalink() . '#tickets'); ?>" class="aqualuxe-button aqualuxe-button-primary">
                        <?php echo esc_html__('Tickets', 'aqualuxe'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>