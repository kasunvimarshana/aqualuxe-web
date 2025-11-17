<?php
/**
 * Single Event Template
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

<article id="event-<?php echo esc_attr($event->id); ?>" class="aqualuxe-event aqualuxe-single-event <?php echo esc_attr($event_status_class); ?>">
    <div class="aqualuxe-event-header">
        <?php if ($event->get_featured_image_url()) : ?>
            <div class="aqualuxe-event-featured-image">
                <img src="<?php echo esc_url($event->get_featured_image_url('large')); ?>" alt="<?php echo esc_attr($event->get_title()); ?>" />
                
                <div class="aqualuxe-event-status-badge">
                    <span class="aqualuxe-event-status"><?php echo esc_html($event_status_label); ?></span>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="aqualuxe-event-header-content">
            <h1 class="aqualuxe-event-title"><?php echo esc_html($event->get_title()); ?></h1>
            
            <div class="aqualuxe-event-meta">
                <div class="aqualuxe-event-date-time">
                    <i class="aqualuxe-icon aqualuxe-icon-calendar"></i>
                    <?php echo esc_html(aqualuxe_format_event_date_range($event)); ?>
                </div>
                
                <?php if ($event->get_venue()) : ?>
                    <div class="aqualuxe-event-location">
                        <i class="aqualuxe-icon aqualuxe-icon-location"></i>
                        <?php echo esc_html($event->get_venue()); ?>
                        
                        <?php if ($event->get_full_address()) : ?>
                            <address class="aqualuxe-event-address">
                                <?php echo esc_html($event->get_full_address()); ?>
                            </address>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($event->get_data('tickets_available')) : ?>
                    <div class="aqualuxe-event-ticket-info">
                        <i class="aqualuxe-icon aqualuxe-icon-ticket"></i>
                        <?php echo esc_html($event->get_cost()); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($event->get_organizer()) : ?>
                    <div class="aqualuxe-event-organizer">
                        <i class="aqualuxe-icon aqualuxe-icon-user"></i>
                        <?php echo esc_html__('Organized by', 'aqualuxe'); ?> <?php echo esc_html($event->get_organizer()); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="aqualuxe-event-actions">
                <?php if ($event->has_tickets_available()) : ?>
                    <a href="#tickets" class="aqualuxe-button aqualuxe-button-primary">
                        <?php echo esc_html__('Get Tickets', 'aqualuxe'); ?>
                    </a>
                <?php endif; ?>
                
                <div class="aqualuxe-event-share">
                    <button class="aqualuxe-button aqualuxe-button-outline aqualuxe-event-share-toggle">
                        <i class="aqualuxe-icon aqualuxe-icon-share"></i>
                        <?php echo esc_html__('Share', 'aqualuxe'); ?>
                    </button>
                    
                    <div class="aqualuxe-event-share-dropdown">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($event->get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-event-share-link aqualuxe-event-share-facebook">
                            <i class="aqualuxe-icon aqualuxe-icon-facebook"></i>
                            <?php echo esc_html__('Facebook', 'aqualuxe'); ?>
                        </a>
                        
                        <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url($event->get_permalink()); ?>&text=<?php echo esc_attr($event->get_title()); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-event-share-link aqualuxe-event-share-twitter">
                            <i class="aqualuxe-icon aqualuxe-icon-twitter"></i>
                            <?php echo esc_html__('Twitter', 'aqualuxe'); ?>
                        </a>
                        
                        <a href="mailto:?subject=<?php echo esc_attr($event->get_title()); ?>&body=<?php echo esc_attr($event->get_permalink()); ?>" class="aqualuxe-event-share-link aqualuxe-event-share-email">
                            <i class="aqualuxe-icon aqualuxe-icon-email"></i>
                            <?php echo esc_html__('Email', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
                
                <div class="aqualuxe-event-calendar">
                    <button class="aqualuxe-button aqualuxe-button-outline aqualuxe-event-calendar-toggle">
                        <i class="aqualuxe-icon aqualuxe-icon-calendar-plus"></i>
                        <?php echo esc_html__('Add to Calendar', 'aqualuxe'); ?>
                    </button>
                    
                    <div class="aqualuxe-event-calendar-dropdown">
                        <a href="<?php echo esc_url(aqualuxe_get_add_to_calendar_link($event, 'google')); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-event-calendar-link aqualuxe-event-calendar-google">
                            <i class="aqualuxe-icon aqualuxe-icon-google"></i>
                            <?php echo esc_html__('Google Calendar', 'aqualuxe'); ?>
                        </a>
                        
                        <a href="<?php echo esc_url(aqualuxe_get_add_to_calendar_link($event, 'ical')); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-event-calendar-link aqualuxe-event-calendar-ical">
                            <i class="aqualuxe-icon aqualuxe-icon-apple"></i>
                            <?php echo esc_html__('Apple Calendar', 'aqualuxe'); ?>
                        </a>
                        
                        <a href="<?php echo esc_url(aqualuxe_get_add_to_calendar_link($event, 'outlook')); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-event-calendar-link aqualuxe-event-calendar-outlook">
                            <i class="aqualuxe-icon aqualuxe-icon-outlook"></i>
                            <?php echo esc_html__('Outlook', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="aqualuxe-event-content">
        <div class="aqualuxe-event-description">
            <?php echo wp_kses_post($event->get_description()); ?>
        </div>
        
        <?php if ($event->get_data('latitude') && $event->get_data('longitude')) : ?>
            <div class="aqualuxe-event-map">
                <h3><?php echo esc_html__('Location', 'aqualuxe'); ?></h3>
                
                <?php
                // Get Google Maps API key from settings
                $options = get_option('aqualuxe_events_settings', array());
                $api_key = isset($options['google_maps_api_key']) ? $options['google_maps_api_key'] : '';
                
                if (!empty($api_key)) :
                    $map_url = 'https://www.google.com/maps/embed/v1/place';
                    $map_url .= '?key=' . urlencode($api_key);
                    $map_url .= '&q=' . urlencode($event->get_full_address());
                    $map_url .= '&zoom=15';
                ?>
                    <div class="aqualuxe-event-map-container">
                        <iframe
                            width="100%"
                            height="400"
                            frameborder="0"
                            style="border:0"
                            src="<?php echo esc_url($map_url); ?>"
                            allowfullscreen
                        ></iframe>
                    </div>
                <?php else : ?>
                    <div class="aqualuxe-event-map-container">
                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($event->get_full_address()); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-button">
                            <?php echo esc_html__('View on Google Maps', 'aqualuxe'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($event->get_organizer()) : ?>
            <div class="aqualuxe-event-organizer-details">
                <h3><?php echo esc_html__('Organizer', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-event-organizer-info">
                    <h4><?php echo esc_html($event->get_organizer()); ?></h4>
                    
                    <?php
                    $organizer_info = $event->get_organizer_contact_info();
                    ?>
                    
                    <?php if (!empty($organizer_info['phone'])) : ?>
                        <div class="aqualuxe-event-organizer-phone">
                            <i class="aqualuxe-icon aqualuxe-icon-phone"></i>
                            <a href="tel:<?php echo esc_attr($organizer_info['phone']); ?>"><?php echo esc_html($organizer_info['phone']); ?></a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($organizer_info['email'])) : ?>
                        <div class="aqualuxe-event-organizer-email">
                            <i class="aqualuxe-icon aqualuxe-icon-email"></i>
                            <a href="mailto:<?php echo esc_attr($organizer_info['email']); ?>"><?php echo esc_html($organizer_info['email']); ?></a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($organizer_info['website'])) : ?>
                        <div class="aqualuxe-event-organizer-website">
                            <i class="aqualuxe-icon aqualuxe-icon-globe"></i>
                            <a href="<?php echo esc_url($organizer_info['website']); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($organizer_info['website']); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php
    // Get related events
    $related_events = $event->get_related_events(3);
    
    if (!empty($related_events)) :
    ?>
        <div class="aqualuxe-event-related">
            <h3><?php echo esc_html__('Related Events', 'aqualuxe'); ?></h3>
            
            <div class="aqualuxe-event-related-grid">
                <?php foreach ($related_events as $related_event) : ?>
                    <?php
                    $related_event_status = aqualuxe_get_event_status($related_event);
                    $related_event_status_class = aqualuxe_get_event_status_class($related_event_status);
                    ?>
                    
                    <div class="aqualuxe-event-related-item <?php echo esc_attr($related_event_status_class); ?>">
                        <?php if ($related_event->get_featured_image_url('medium')) : ?>
                            <div class="aqualuxe-event-related-image">
                                <a href="<?php echo esc_url($related_event->get_permalink()); ?>">
                                    <img src="<?php echo esc_url($related_event->get_featured_image_url('medium')); ?>" alt="<?php echo esc_attr($related_event->get_title()); ?>" />
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="aqualuxe-event-related-content">
                            <h4 class="aqualuxe-event-related-title">
                                <a href="<?php echo esc_url($related_event->get_permalink()); ?>">
                                    <?php echo esc_html($related_event->get_title()); ?>
                                </a>
                            </h4>
                            
                            <div class="aqualuxe-event-related-date">
                                <i class="aqualuxe-icon aqualuxe-icon-calendar"></i>
                                <?php echo esc_html(aqualuxe_format_event_date_range($related_event)); ?>
                            </div>
                            
                            <?php if ($related_event->get_venue()) : ?>
                                <div class="aqualuxe-event-related-venue">
                                    <i class="aqualuxe-icon aqualuxe-icon-location"></i>
                                    <?php echo esc_html($related_event->get_venue()); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</article>

<script>
    jQuery(document).ready(function($) {
        // Share dropdown
        $('.aqualuxe-event-share-toggle').on('click', function() {
            $(this).next('.aqualuxe-event-share-dropdown').toggleClass('active');
        });
        
        // Calendar dropdown
        $('.aqualuxe-event-calendar-toggle').on('click', function() {
            $(this).next('.aqualuxe-event-calendar-dropdown').toggleClass('active');
        });
        
        // Close dropdowns when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.aqualuxe-event-share, .aqualuxe-event-calendar').length) {
                $('.aqualuxe-event-share-dropdown, .aqualuxe-event-calendar-dropdown').removeClass('active');
            }
        });
    });
</script>