<?php
/**
 * The template for displaying single event posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

// Get event details
$event_date = get_post_meta(get_the_ID(), 'event_date', true);
$event_time = get_post_meta(get_the_ID(), 'event_time', true);
$event_end_time = get_post_meta(get_the_ID(), 'event_end_time', true);
$event_location = get_post_meta(get_the_ID(), 'event_location', true);
$event_address = get_post_meta(get_the_ID(), 'event_address', true);
$event_map = get_post_meta(get_the_ID(), 'event_map', true);
$event_cost = get_post_meta(get_the_ID(), 'event_cost', true);
$event_registration_url = get_post_meta(get_the_ID(), 'event_registration_url', true);
$event_speakers = get_post_meta(get_the_ID(), 'event_speakers', true);
$event_sponsors = get_post_meta(get_the_ID(), 'event_sponsors', true);
$event_gallery = get_post_meta(get_the_ID(), 'event_gallery', true);
?>

<main id="primary" class="site-main">
    <?php
    // Single Event Header
    get_template_part('template-parts/components/single-header', 'event');
    ?>

    <div class="container">
        <div class="event-single">
            <div class="row">
                <div class="col-lg-8">
                    <?php
                    while (have_posts()) :
                        the_post();
                        ?>
                        
                        <div class="event-content">
                            <?php
                            // Display featured image
                            if (has_post_thumbnail()) :
                            ?>
                            <div class="event-featured-image">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="event-description">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php
                            // Display gallery if available
                            if (!empty($event_gallery)) :
                            ?>
                            <div class="event-gallery">
                                <h3><?php echo esc_html__('Event Gallery', 'aqualuxe'); ?></h3>
                                <div class="row">
                                    <?php
                                    foreach ($event_gallery as $image_id) :
                                        $image_url = wp_get_attachment_image_url($image_id, 'medium');
                                        $image_full_url = wp_get_attachment_image_url($image_id, 'full');
                                        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                        ?>
                                        <div class="col-md-4 col-sm-6">
                                            <div class="gallery-item">
                                                <a href="<?php echo esc_url($image_full_url); ?>" class="gallery-lightbox">
                                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="img-fluid">
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php
                            // Display speakers if available
                            if (!empty($event_speakers)) :
                            ?>
                            <div class="event-speakers">
                                <h3><?php echo esc_html__('Event Speakers', 'aqualuxe'); ?></h3>
                                <div class="row">
                                    <?php
                                    foreach ($event_speakers as $speaker) :
                                        if (!empty($speaker['name'])) :
                                        ?>
                                        <div class="col-md-6">
                                            <div class="speaker-card">
                                                <?php if (!empty($speaker['image'])) : ?>
                                                    <div class="speaker-image">
                                                        <img src="<?php echo esc_url($speaker['image']); ?>" alt="<?php echo esc_attr($speaker['name']); ?>" class="img-fluid rounded-circle">
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="speaker-info">
                                                    <h4 class="speaker-name"><?php echo esc_html($speaker['name']); ?></h4>
                                                    
                                                    <?php if (!empty($speaker['position'])) : ?>
                                                        <p class="speaker-position"><?php echo esc_html($speaker['position']); ?></p>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($speaker['bio'])) : ?>
                                                        <div class="speaker-bio">
                                                            <?php echo wp_kses_post($speaker['bio']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($speaker['social'])) : ?>
                                                        <div class="speaker-social">
                                                            <?php foreach ($speaker['social'] as $social) : ?>
                                                                <a href="<?php echo esc_url($social['url']); ?>" target="_blank" rel="noopener noreferrer">
                                                                    <i class="<?php echo esc_attr($social['icon']); ?>"></i>
                                                                </a>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php
                            // Display sponsors if available
                            if (!empty($event_sponsors)) :
                            ?>
                            <div class="event-sponsors">
                                <h3><?php echo esc_html__('Event Sponsors', 'aqualuxe'); ?></h3>
                                <div class="sponsors-grid">
                                    <?php
                                    foreach ($event_sponsors as $sponsor) :
                                        if (!empty($sponsor['name'])) :
                                        ?>
                                        <div class="sponsor-item">
                                            <?php if (!empty($sponsor['logo']) && !empty($sponsor['url'])) : ?>
                                                <a href="<?php echo esc_url($sponsor['url']); ?>" target="_blank" rel="noopener noreferrer">
                                                    <img src="<?php echo esc_url($sponsor['logo']); ?>" alt="<?php echo esc_attr($sponsor['name']); ?>" class="img-fluid">
                                                </a>
                                            <?php elseif (!empty($sponsor['logo'])) : ?>
                                                <img src="<?php echo esc_url($sponsor['logo']); ?>" alt="<?php echo esc_attr($sponsor['name']); ?>" class="img-fluid">
                                            <?php else : ?>
                                                <span class="sponsor-name">
                                                    <?php echo esc_html($sponsor['name']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php
                            // Display location map if available
                            if (!empty($event_map)) :
                            ?>
                            <div class="event-map">
                                <h3><?php echo esc_html__('Event Location', 'aqualuxe'); ?></h3>
                                <div class="map-container">
                                    <?php echo wp_kses_post($event_map); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php
                    endwhile; // End of the loop.
                    ?>
                </div>
                
                <div class="col-lg-4">
                    <div class="event-sidebar">
                        <div class="event-info-card">
                            <h3><?php echo esc_html__('Event Details', 'aqualuxe'); ?></h3>
                            
                            <div class="event-meta">
                                <?php if (!empty($event_date)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="far fa-calendar-alt"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Date', 'aqualuxe'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($event_date); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($event_time)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="far fa-clock"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Time', 'aqualuxe'); ?></span>
                                            <span class="meta-value">
                                                <?php 
                                                echo esc_html($event_time);
                                                if (!empty($event_end_time)) {
                                                    echo ' - ' . esc_html($event_end_time);
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($event_location)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Location', 'aqualuxe'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($event_location); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($event_address)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-map"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Address', 'aqualuxe'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($event_address); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($event_cost)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Cost', 'aqualuxe'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($event_cost); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php
                                // Display event categories
                                $event_categories = get_the_terms(get_the_ID(), 'event_category');
                                if (!empty($event_categories) && !is_wp_error($event_categories)) :
                                ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Category', 'aqualuxe'); ?></span>
                                            <span class="meta-value">
                                                <?php
                                                $category_names = array();
                                                foreach ($event_categories as $category) {
                                                    $category_names[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                                                }
                                                echo implode(', ', $category_names);
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($event_registration_url)) : ?>
                                <div class="event-registration">
                                    <a href="<?php echo esc_url($event_registration_url); ?>" class="btn btn-primary btn-block" target="_blank">
                                        <?php echo esc_html__('Register Now', 'aqualuxe'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="event-share">
                                <h4><?php echo esc_html__('Share This Event', 'aqualuxe'); ?></h4>
                                <?php aqualuxe_social_sharing(); ?>
                            </div>
                        </div>
                        
                        <?php
                        // Display upcoming events
                        get_template_part('template-parts/components/upcoming-events');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();