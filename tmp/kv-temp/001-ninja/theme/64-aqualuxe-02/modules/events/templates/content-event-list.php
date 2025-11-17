<?php
/**
 * Event List Item Template
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

<div class="aqualuxe-event-list-item <?php echo esc_attr($event_status_class); ?>">
    <?php if ($event->get_featured_image_url('medium')) : ?>
        <div class="aqualuxe-event-list-image">
            <a href="<?php echo esc_url($event->get_permalink()); ?>">
                <img src="<?php echo esc_url($event->get_featured_image_url('medium')); ?>" alt="<?php echo esc_attr($event->get_title()); ?>" />
                
                <div class="aqualuxe-event-status-badge">
                    <span class="aqualuxe-event-status"><?php echo esc_html($event_status_label); ?></span>
                </div>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="aqualuxe-event-list-content">
        <h3 class="aqualuxe-event-list-title">
            <a href="<?php echo esc_url($event->get_permalink()); ?>">
                <?php echo esc_html($event->get_title()); ?>
            </a>
        </h3>
        
        <div class="aqualuxe-event-list-meta">
            <div class="aqualuxe-event-list-date">
                <i class="aqualuxe-icon aqualuxe-icon-calendar"></i>
                <?php echo esc_html(aqualuxe_format_event_date_range($event)); ?>
            </div>
            
            <?php if ($event->get_venue()) : ?>
                <div class="aqualuxe-event-list-venue">
                    <i class="aqualuxe-icon aqualuxe-icon-location"></i>
                    <?php echo esc_html($event->get_venue()); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($event->get_data('tickets_available')) : ?>
                <div class="aqualuxe-event-list-price">
                    <i class="aqualuxe-icon aqualuxe-icon-ticket"></i>
                    <?php echo esc_html($event->get_cost()); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-event-list-description">
            <?php echo wp_trim_words($event->get_short_description(), 30); ?>
        </div>
        
        <div class="aqualuxe-event-list-actions">
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