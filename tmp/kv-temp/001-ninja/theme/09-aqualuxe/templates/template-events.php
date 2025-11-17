<?php
/**
 * Template Name: Events
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <div class="container py-12">
        <header class="page-header text-center mb-12">
            <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <div class="page-description text-lg text-gray-600 max-w-3xl mx-auto">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="page-content">
            <?php
            // Display the page content first
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            
            // Get current date
            $current_date = date('Y-m-d');
            
            // Get upcoming events
            $upcoming_events = new WP_Query(array(
                'post_type' => 'event',
                'posts_per_page' => -1,
                'meta_key' => '_event_start_date',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => '_event_start_date',
                        'value' => $current_date,
                        'compare' => '>=',
                        'type' => 'DATE',
                    ),
                ),
            ));
            
            // Get past events
            $past_events = new WP_Query(array(
                'post_type' => 'event',
                'posts_per_page' => 5,
                'meta_key' => '_event_start_date',
                'orderby' => 'meta_value',
                'order' => 'DESC',
                'meta_query' => array(
                    array(
                        'key' => '_event_start_date',
                        'value' => $current_date,
                        'compare' => '<',
                        'type' => 'DATE',
                    ),
                ),
            ));
            ?>
            
            <!-- Upcoming Events Section -->
            <section class="upcoming-events-section mb-16">
                <h2 class="section-title text-3xl font-bold mb-8 pb-2 border-b border-gray-200"><?php esc_html_e('Upcoming Events', 'aqualuxe'); ?></h2>
                
                <?php if ($upcoming_events->have_posts()) : ?>
                    <div class="events-list space-y-8">
                        <?php
                        while ($upcoming_events->have_posts()) :
                            $upcoming_events->the_post();
                            
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
                                    <div class="event-date text-sm text-primary font-medium mb-2">
                                        <?php echo esc_html($date_display); ?>
                                        <?php if ($time) : ?>
                                            <span class="event-time ml-2"><?php echo esc_html($time); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h3 class="event-title text-xl font-bold mb-2">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="event-meta grid grid-cols-1 md:grid-cols-2 gap-2 mb-4 text-sm text-gray-600">
                                        <?php if ($location) : ?>
                                            <div class="event-location">
                                                <i class="fa fa-map-marker-alt mr-2"></i> <?php echo esc_html($location); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($cost) : ?>
                                            <div class="event-cost">
                                                <i class="fa fa-ticket-alt mr-2"></i> <?php echo esc_html($cost); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (has_excerpt()) : ?>
                                        <div class="event-excerpt text-gray-600 mb-4">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="event-actions flex flex-wrap gap-3">
                                        <a href="<?php the_permalink(); ?>" class="inline-block text-primary hover:text-secondary">
                                            <?php esc_html_e('Learn More', 'aqualuxe'); ?> <i class="fa fa-arrow-right ml-1"></i>
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
                <?php else : ?>
                    <div class="no-events bg-gray-50 p-8 rounded-lg text-center">
                        <p class="text-lg text-gray-600"><?php esc_html_e('No upcoming events at this time. Please check back later.', 'aqualuxe'); ?></p>
                    </div>
                <?php endif; ?>
            </section>
            
            <!-- Past Events Section -->
            <?php if ($past_events->have_posts()) : ?>
                <section class="past-events-section">
                    <h2 class="section-title text-3xl font-bold mb-8 pb-2 border-b border-gray-200"><?php esc_html_e('Past Events', 'aqualuxe'); ?></h2>
                    
                    <div class="events-list space-y-6">
                        <?php
                        while ($past_events->have_posts()) :
                            $past_events->the_post();
                            
                            // Get event meta
                            $start_date = get_post_meta(get_the_ID(), '_event_start_date', true);
                            $end_date = get_post_meta(get_the_ID(), '_event_end_date', true);
                            $location = get_post_meta(get_the_ID(), '_event_location', true);
                            
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
                            
                            <div class="past-event-item bg-gray-50 p-6 rounded-lg">
                                <div class="flex flex-col md:flex-row md:items-center justify-between">
                                    <div class="event-info mb-4 md:mb-0">
                                        <h3 class="event-title text-lg font-bold mb-1">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        
                                        <div class="event-meta text-sm text-gray-600">
                                            <span class="event-date"><?php echo esc_html($date_display); ?></span>
                                            
                                            <?php if ($location) : ?>
                                                <span class="mx-2">•</span>
                                                <span class="event-location"><?php echo esc_html($location); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="inline-block text-primary hover:text-secondary">
                                        <?php esc_html_e('View Details', 'aqualuxe'); ?>
                                    </a>
                                </div>
                            </div>
                            
                        <?php endwhile; ?>
                    </div>
                </section>
            <?php endif; ?>
            
            <?php
            wp_reset_postdata();
            ?>
        </div>
    </div>

</main><!-- #main -->

<?php
get_footer();