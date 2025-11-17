<?php
/**
 * Template part for displaying testimonials on the homepage
 *
 * @package AquaLuxe
 */

// Get testimonials section settings from customizer
$testimonials_title = get_theme_mod('aqualuxe_testimonials_title', __('What Our Customers Say', 'aqualuxe'));
$testimonials_description = get_theme_mod('aqualuxe_testimonials_description', __('Read testimonials from our satisfied customers', 'aqualuxe'));
$testimonials_count = get_theme_mod('aqualuxe_testimonials_count', 3);
$testimonials_enable = get_theme_mod('aqualuxe_testimonials_enable', true);

// Exit if testimonials section is disabled
if (!$testimonials_enable) {
    return;
}

// Check if the Testimonials custom post type exists
if (!post_type_exists('testimonials')) {
    return;
}
?>

<section class="testimonials">
    <div class="container">
        <div class="section-header">
            <?php if ($testimonials_title) : ?>
                <h2 class="section-title"><?php echo esc_html($testimonials_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($testimonials_description) : ?>
                <p class="section-description"><?php echo esc_html($testimonials_description); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="testimonials-wrapper">
            <?php
            $args = array(
                'post_type'      => 'testimonials',
                'posts_per_page' => $testimonials_count,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            
            $testimonials_query = new WP_Query($args);
            
            if ($testimonials_query->have_posts()) :
                echo '<div class="testimonials-slider">';
                
                while ($testimonials_query->have_posts()) : $testimonials_query->the_post();
                    // Get testimonial meta data
                    $client_name = get_post_meta(get_the_ID(), 'aqualuxe_testimonial_client_name', true);
                    $client_position = get_post_meta(get_the_ID(), 'aqualuxe_testimonial_client_position', true);
                    $rating = get_post_meta(get_the_ID(), 'aqualuxe_testimonial_rating', true);
                    ?>
                    <div class="testimonial-item">
                        <div class="testimonial-content">
                            <?php if ($rating && $rating > 0) : ?>
                                <div class="testimonial-rating">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<span class="star filled">★</span>';
                                        } else {
                                            echo '<span class="star">☆</span>';
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-text">
                                <?php the_content(); ?>
                            </div>
                            
                            <div class="testimonial-author">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="testimonial-author-image">
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'author-avatar')); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-author-info">
                                    <?php if ($client_name) : ?>
                                        <h4 class="author-name"><?php echo esc_html($client_name); ?></h4>
                                    <?php endif; ?>
                                    
                                    <?php if ($client_position) : ?>
                                        <p class="author-position"><?php echo esc_html($client_position); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                
                echo '</div>';
                
                wp_reset_postdata();
            else :
                echo '<p class="no-testimonials">' . esc_html__('No testimonials found.', 'aqualuxe') . '</p>';
            endif;
            ?>
        </div>
    </div>
</section>