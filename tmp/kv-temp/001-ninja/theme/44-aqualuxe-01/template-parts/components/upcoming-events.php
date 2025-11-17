<?php
/**
 * Template part for displaying upcoming events
 *
 * @package AquaLuxe
 */

// Get upcoming events options from theme customizer
$show_upcoming_events = get_theme_mod('aqualuxe_show_upcoming_events', true);
$upcoming_events_title = get_theme_mod('aqualuxe_upcoming_events_title', __('Upcoming Events', 'aqualuxe'));
$upcoming_events_count = get_theme_mod('aqualuxe_upcoming_events_count', 3);
$upcoming_events_category = get_theme_mod('aqualuxe_upcoming_events_category', 0);

// Check if upcoming events should be displayed
if (!$show_upcoming_events) {
    return;
}

// Get current date
$current_date = current_time('Y-m-d');

// Set up query arguments
$args = array(
    'post_type'      => 'event',
    'posts_per_page' => $upcoming_events_count,
    'post_status'    => 'publish',
    'meta_key'       => 'event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => array(
        array(
            'key'     => 'event_date',
            'value'   => $current_date,
            'compare' => '>=',
            'type'    => 'DATE',
        ),
    ),
);

// Add category filter
if ($upcoming_events_category > 0) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'event_category',
            'field'    => 'term_id',
            'terms'    => $upcoming_events_category,
        ),
    );
}

// Get upcoming events
$upcoming_events_query = new WP_Query($args);

// Check if we have upcoming events
if (!$upcoming_events_query->have_posts()) {
    return;
}
?>

<div class="upcoming-events">
    <?php if (!empty($upcoming_events_title)) : ?>
        <h3 class="upcoming-events-title"><?php echo esc_html($upcoming_events_title); ?></h3>
    <?php endif; ?>
    
    <div class="upcoming-events-list">
        <?php
        // Loop through upcoming events
        while ($upcoming_events_query->have_posts()) :
            $upcoming_events_query->the_post();
            
            // Get event details
            $event_date = get_post_meta(get_the_ID(), 'event_date', true);
            $event_time = get_post_meta(get_the_ID(), 'event_time', true);
            $event_location = get_post_meta(get_the_ID(), 'event_location', true);
            
            // Format date
            $event_date_formatted = '';
            if (!empty($event_date)) {
                $event_timestamp = strtotime($event_date);
                $event_date_formatted = date_i18n(get_option('date_format'), $event_timestamp);
            }
            ?>
            <div class="upcoming-event-item">
                <div class="upcoming-event-inner">
                    <?php if (!empty($event_date)) : ?>
                        <div class="event-date">
                            <span class="event-day"><?php echo date_i18n('d', $event_timestamp); ?></span>
                            <span class="event-month"><?php echo date_i18n('M', $event_timestamp); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="event-content">
                        <h4 class="event-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        
                        <div class="event-meta">
                            <?php if (!empty($event_time)) : ?>
                                <div class="event-time">
                                    <i class="far fa-clock"></i>
                                    <span><?php echo esc_html($event_time); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($event_location)) : ?>
                                <div class="event-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo esc_html($event_location); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
    
    <div class="upcoming-events-more">
        <a href="<?php echo esc_url(get_post_type_archive_link('event')); ?>" class="btn btn-outline-primary btn-sm">
            <?php echo esc_html__('View All Events', 'aqualuxe'); ?>
        </a>
    </div>
</div>