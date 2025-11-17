<?php
/**
 * Homepage Events Section
 *
 * @package AquaLuxe
 */

// Get section content from theme options or use default
$section_title = get_theme_mod('aqualuxe_events_title', __('Upcoming Events', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_events_description', __('Join us for workshops, exhibitions, and aquascaping competitions.', 'aqualuxe'));
$events_count = get_theme_mod('aqualuxe_events_count', 3);
$show_button = get_theme_mod('aqualuxe_events_show_button', true);
$button_text = get_theme_mod('aqualuxe_events_button_text', __('View All Events', 'aqualuxe'));
$button_url = get_theme_mod('aqualuxe_events_button_url', get_post_type_archive_link('event'));

// Get upcoming events
$args = array(
    'post_type' => 'event',
    'posts_per_page' => $events_count,
    'post_status' => 'publish',
    'meta_key' => '_event_start_date',
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => '_event_start_date',
            'value' => date('Y-m-d'),
            'compare' => '>=',
            'type' => 'DATE',
        ),
    ),
);

$events = new WP_Query($args);

// Only show section if events exist
if ($events->have_posts()) :
?>

<section class="events-section py-16 bg-gray-50">
    <div class="container">
        <?php if ($section_title || $section_description) : ?>
            <div class="section-header text-center mb-12">
                <?php if ($section_title) : ?>
                    <h2 class="section-title text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($section_description) : ?>
                    <p class="section-description text-lg text-gray-600 max-w-3xl mx-auto"><?php echo esc_html($section_description); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="events-list space-y-6">
            <?php
            while ($events->have_posts()) :
                $events->the_post();
                
                // Get event meta
                $start_date = get_post_meta(get_the_ID(), '_event_start_date', true);
                $end_date = get_post_meta(get_the_ID(), '_event_end_date', true);
                $time = get_post_meta(get_the_ID(), '_event_time', true);
                $location = get_post_meta(get_the_ID(), '_event_location', true);
                $address = get_post_meta(get_the_ID(), '_event_address', true);
                $cost = get_post_meta(get_the_ID(), '_event_cost', true);
                $registration_url = get_post_meta(get_the_ID(), '_event_registration_url', true);
                
                // Format date
                $date_format = get_option('date_format');
                $start_date_formatted = date_i18n($date_format, strtotime($start_date));
                $end_date_formatted = $end_date ? date_i18n($date_format, strtotime($end_date)) : '';
                
                // Display date range if end date exists
                $date_display = $start_date_formatted;
                if ($end_date) {
                    $date_display = $start_date_formatted . ' - ' . $end_date_formatted;
                }
                ?>
                
                <div class="event-item flex flex-col md:flex-row bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="event-image md:w-1/3">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="event-content p-6 md:w-2/3">
                        <?php if ($start_date) : ?>
                            <div class="event-date text-sm text-primary font-medium mb-2">
                                <?php echo esc_html($date_display); ?>
                                <?php if ($time) : ?>
                                    <span class="event-time ml-2"><?php echo esc_html($time); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <h3 class="event-title text-xl font-bold mb-2">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        
                        <?php if ($location) : ?>
                            <div class="event-location text-gray-600 mb-3">
                                <i class="fa fa-map-marker-alt mr-2"></i> <?php echo esc_html($location); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (has_excerpt()) : ?>
                            <div class="event-excerpt text-gray-600 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="event-actions flex flex-wrap gap-3">
                            <a href="<?php the_permalink(); ?>" class="inline-block text-primary hover:text-secondary">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                            </a>
                            
                            <?php if ($registration_url) : ?>
                                <a href="<?php echo esc_url($registration_url); ?>" class="inline-block bg-primary text-white px-4 py-2 rounded hover:bg-secondary">
                                    <?php esc_html_e('Register Now', 'aqualuxe'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
            <?php endwhile; ?>
        </div>
        
        <?php if ($show_button && $button_url) : ?>
            <div class="text-center mt-10">
                <a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary">
                    <?php echo esc_html($button_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
wp_reset_postdata();
endif;