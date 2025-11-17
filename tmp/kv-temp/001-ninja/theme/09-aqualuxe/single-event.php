<?php
/**
 * The template for displaying single event posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

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
$start_date_formatted = $start_date ? date_i18n($date_format, strtotime($start_date)) : '';
$end_date_formatted = $end_date ? date_i18n($date_format, strtotime($end_date)) : '';

// Display date range if end date exists
$date_display = $start_date_formatted;
if ($end_date) {
    $date_display = $start_date_formatted . ' - ' . $end_date_formatted;
}

// Calculate if event is upcoming, ongoing, or past
$current_date = date('Y-m-d');
$event_status = 'upcoming'; // Default

if ($start_date <= $current_date) {
    if ($end_date && $end_date >= $current_date) {
        $event_status = 'ongoing';
    } else {
        $event_status = 'past';
    }
}

// Set status label and class
$status_label = '';
$status_class = '';

switch ($event_status) {
    case 'upcoming':
        $status_label = __('Upcoming', 'aqualuxe');
        $status_class = 'bg-green-100 text-green-800';
        break;
    case 'ongoing':
        $status_label = __('Happening Now', 'aqualuxe');
        $status_class = 'bg-blue-100 text-blue-800';
        break;
    case 'past':
        $status_label = __('Past Event', 'aqualuxe');
        $status_class = 'bg-gray-100 text-gray-800';
        break;
}
?>

<main id="primary" class="site-main">

    <div class="container py-12">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header mb-8">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                        <div>
                            <?php if ($status_label) : ?>
                                <div class="event-status inline-block px-3 py-1 rounded-full text-sm font-medium <?php echo esc_attr($status_class); ?> mb-4">
                                    <?php echo esc_html($status_label); ?>
                                </div>
                            <?php endif; ?>
                            
                            <h1 class="entry-title text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
                            
                            <div class="event-meta space-y-2 text-lg">
                                <?php if ($start_date) : ?>
                                    <div class="event-date flex items-center">
                                        <i class="fa fa-calendar-alt text-primary mr-3"></i>
                                        <span><?php echo esc_html($date_display); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($time) : ?>
                                    <div class="event-time flex items-center">
                                        <i class="fa fa-clock text-primary mr-3"></i>
                                        <span><?php echo esc_html($time); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($location) : ?>
                                    <div class="event-location flex items-center">
                                        <i class="fa fa-map-marker-alt text-primary mr-3"></i>
                                        <span><?php echo esc_html($location); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($cost) : ?>
                                    <div class="event-cost flex items-center">
                                        <i class="fa fa-ticket-alt text-primary mr-3"></i>
                                        <span><?php echo esc_html($cost); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($event_status !== 'past' && $registration_url) : ?>
                            <div class="event-registration mt-6 md:mt-0">
                                <a href="<?php echo esc_url($registration_url); ?>" class="btn btn-primary">
                                    <?php esc_html_e('Register Now', 'aqualuxe'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </header><!-- .entry-header -->

                <?php if (has_post_thumbnail()) : ?>
                    <div class="event-featured-image mb-8">
                        <?php the_post_thumbnail('full', array('class' => 'rounded-lg shadow-lg w-full h-auto')); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content prose max-w-none mb-8">
                    <?php the_content(); ?>
                </div><!-- .entry-content -->

                <?php if ($address) : ?>
                    <div class="event-address mb-8 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-3"><?php esc_html_e('Event Location', 'aqualuxe'); ?></h3>
                        <p class="mb-4"><?php echo nl2br(esc_html($address)); ?></p>
                        
                        <?php
                        // Google Maps embed (in a real theme, this would be a custom field)
                        $map_embed = get_post_meta(get_the_ID(), '_event_map_embed', true);
                        
                        if ($map_embed) {
                            echo $map_embed;
                        } else {
                            // Generate a Google Maps link based on the address
                            $map_url = 'https://www.google.com/maps/search/' . urlencode($address);
                            ?>
                            <a href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener noreferrer" class="inline-block bg-white hover:bg-gray-100 text-primary px-4 py-2 rounded border border-gray-300 transition duration-300">
                                <i class="fa fa-map-marked-alt mr-2"></i> <?php esc_html_e('View on Google Maps', 'aqualuxe'); ?>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <?php
                // Get event category terms
                $event_categories = get_the_terms(get_the_ID(), 'event_category');
                
                if ($event_categories && !is_wp_error($event_categories)) :
                ?>
                    <div class="event-categories mb-8 flex flex-wrap gap-2">
                        <?php foreach ($event_categories as $category) : ?>
                            <a href="<?php echo esc_url(get_term_link($category)); ?>" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm transition duration-300">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Event Schedule Section -->
                <?php
                $schedule_title = get_post_meta(get_the_ID(), '_event_schedule_title', true);
                $schedule_items = get_post_meta(get_the_ID(), '_event_schedule_items', true);
                
                if (empty($schedule_title)) {
                    $schedule_title = __('Event Schedule', 'aqualuxe');
                }
                
                // If no custom schedule items, use default ones
                if (empty($schedule_items)) {
                    $schedule_items = array(
                        array(
                            'time' => '09:00 AM',
                            'title' => __('Registration & Welcome Coffee', 'aqualuxe'),
                            'description' => __('Check-in and receive your event materials.', 'aqualuxe'),
                        ),
                        array(
                            'time' => '10:00 AM',
                            'title' => __('Opening Keynote', 'aqualuxe'),
                            'description' => __('Introduction and welcome address.', 'aqualuxe'),
                        ),
                        array(
                            'time' => '11:30 AM',
                            'title' => __('Workshop Session', 'aqualuxe'),
                            'description' => __('Hands-on activities and demonstrations.', 'aqualuxe'),
                        ),
                        array(
                            'time' => '01:00 PM',
                            'title' => __('Lunch Break', 'aqualuxe'),
                            'description' => __('Networking lunch with refreshments.', 'aqualuxe'),
                        ),
                        array(
                            'time' => '02:00 PM',
                            'title' => __('Panel Discussion', 'aqualuxe'),
                            'description' => __('Industry experts share insights and answer questions.', 'aqualuxe'),
                        ),
                        array(
                            'time' => '04:00 PM',
                            'title' => __('Closing Remarks', 'aqualuxe'),
                            'description' => __('Summary and next steps.', 'aqualuxe'),
                        ),
                    );
                }
                
                if (!empty($schedule_items)) :
                ?>
                    <section class="event-schedule mb-12">
                        <h2 class="text-2xl font-bold mb-6"><?php echo esc_html($schedule_title); ?></h2>
                        
                        <div class="schedule-items space-y-6">
                            <?php foreach ($schedule_items as $item) : ?>
                                <div class="schedule-item flex">
                                    <div class="schedule-time w-24 flex-shrink-0 font-bold text-primary">
                                        <?php echo esc_html($item['time']); ?>
                                    </div>
                                    <div class="schedule-content border-l-2 border-gray-200 pl-6 pb-6">
                                        <h3 class="text-xl font-bold mb-2"><?php echo esc_html($item['title']); ?></h3>
                                        <p class="text-gray-600"><?php echo esc_html($item['description']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Speakers Section -->
                <?php
                $speakers_title = get_post_meta(get_the_ID(), '_event_speakers_title', true);
                $speakers = get_post_meta(get_the_ID(), '_event_speakers', true);
                
                if (empty($speakers_title)) {
                    $speakers_title = __('Featured Speakers', 'aqualuxe');
                }
                
                // If no custom speakers, use default ones
                if (empty($speakers)) {
                    $speakers = array(
                        array(
                            'name' => __('Dr. Jane Smith', 'aqualuxe'),
                            'role' => __('Marine Biologist', 'aqualuxe'),
                            'bio' => __('Dr. Smith is a renowned expert in tropical fish breeding and conservation.', 'aqualuxe'),
                            'image' => get_template_directory_uri() . '/assets/images/speaker-1.jpg',
                        ),
                        array(
                            'name' => __('Michael Johnson', 'aqualuxe'),
                            'role' => __('Aquascaping Champion', 'aqualuxe'),
                            'bio' => __('Michael has won multiple international aquascaping competitions and is known for his nature-inspired designs.', 'aqualuxe'),
                            'image' => get_template_directory_uri() . '/assets/images/speaker-2.jpg',
                        ),
                        array(
                            'name' => __('Sarah Williams', 'aqualuxe'),
                            'role' => __('Aquarium Technology Specialist', 'aqualuxe'),
                            'bio' => __('Sarah specializes in cutting-edge aquarium technology and automation systems.', 'aqualuxe'),
                            'image' => get_template_directory_uri() . '/assets/images/speaker-3.jpg',
                        ),
                    );
                }
                
                if (!empty($speakers)) :
                ?>
                    <section class="event-speakers mb-12">
                        <h2 class="text-2xl font-bold mb-6"><?php echo esc_html($speakers_title); ?></h2>
                        
                        <div class="speakers-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <?php foreach ($speakers as $speaker) : ?>
                                <div class="speaker-item bg-white rounded-lg shadow-md overflow-hidden">
                                    <?php if (!empty($speaker['image'])) : ?>
                                        <div class="speaker-image">
                                            <img src="<?php echo esc_url($speaker['image']); ?>" alt="<?php echo esc_attr($speaker['name']); ?>" class="w-full h-64 object-cover">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="speaker-content p-6">
                                        <h3 class="speaker-name text-xl font-bold mb-1"><?php echo esc_html($speaker['name']); ?></h3>
                                        
                                        <?php if (!empty($speaker['role'])) : ?>
                                            <p class="speaker-role text-primary mb-3"><?php echo esc_html($speaker['role']); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($speaker['bio'])) : ?>
                                            <p class="speaker-bio text-gray-600"><?php echo esc_html($speaker['bio']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Related Events Section -->
                <?php
                // Get related events from the same category
                $related_args = array(
                    'post_type' => 'event',
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
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
                
                // Add category filter if this event has categories
                if ($event_categories && !is_wp_error($event_categories)) {
                    $category_ids = array();
                    foreach ($event_categories as $category) {
                        $category_ids[] = $category->term_id;
                    }
                    
                    $related_args['tax_query'] = array(
                        array(
                            'taxonomy' => 'event_category',
                            'field' => 'term_id',
                            'terms' => $category_ids,
                        ),
                    );
                }
                
                $related_events = new WP_Query($related_args);
                
                if ($related_events->have_posts()) :
                ?>
                    <section class="related-events mb-12">
                        <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Upcoming Events', 'aqualuxe'); ?></h2>
                        
                        <div class="events-list space-y-6">
                            <?php
                            while ($related_events->have_posts()) :
                                $related_events->the_post();
                                
                                // Get event meta
                                $rel_start_date = get_post_meta(get_the_ID(), '_event_start_date', true);
                                $rel_location = get_post_meta(get_the_ID(), '_event_location', true);
                                
                                // Format date
                                $rel_date_formatted = date_i18n($date_format, strtotime($rel_start_date));
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
                                            <?php echo esc_html($rel_date_formatted); ?>
                                        </div>
                                        
                                        <h3 class="event-title text-xl font-bold mb-2">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        
                                        <?php if ($rel_location) : ?>
                                            <div class="event-location text-gray-600 mb-3">
                                                <i class="fa fa-map-marker-alt mr-2"></i> <?php echo esc_html($rel_location); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (has_excerpt()) : ?>
                                            <div class="event-excerpt text-gray-600 mb-4">
                                                <?php the_excerpt(); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <a href="<?php the_permalink(); ?>" class="inline-block text-primary hover:text-secondary">
                                            <?php esc_html_e('Learn More', 'aqualuxe'); ?> <i class="fa fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                                
                            <?php endwhile; ?>
                        </div>
                        
                        <div class="text-center mt-8">
                            <a href="<?php echo esc_url(get_post_type_archive_link('event')); ?>" class="btn btn-primary">
                                <?php esc_html_e('View All Events', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </section>
                <?php
                endif;
                wp_reset_postdata();
                ?>

                <!-- Call to Action Section -->
                <?php if ($event_status !== 'past' && $registration_url) : ?>
                    <section class="event-cta bg-primary text-white p-8 rounded-lg text-center">
                        <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Ready to Join Us?', 'aqualuxe'); ?></h2>
                        <p class="text-lg mb-6"><?php esc_html_e('Secure your spot at this event by registering today.', 'aqualuxe'); ?></p>
                        <a href="<?php echo esc_url($registration_url); ?>" class="btn bg-white text-primary hover:bg-gray-100">
                            <?php esc_html_e('Register Now', 'aqualuxe'); ?>
                        </a>
                    </section>
                <?php endif; ?>
            </article><!-- #post-<?php the_ID(); ?> -->
            
        <?php endwhile; ?>
    </div>

</main><!-- #main -->

<?php
get_footer();