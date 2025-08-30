<?php
/**
 * Template part for displaying testimonials on the homepage
 *
 * @package AquaLuxe
 */

// Get testimonials options from theme customizer
$show_testimonials = get_theme_mod('aqualuxe_show_home_testimonials', true);
$testimonials_title = get_theme_mod('aqualuxe_home_testimonials_title', __('What Our Clients Say', 'aqualuxe'));
$testimonials_subtitle = get_theme_mod('aqualuxe_home_testimonials_subtitle', __('Testimonials from our satisfied customers', 'aqualuxe'));
$testimonials_count = get_theme_mod('aqualuxe_home_testimonials_count', 6);
$testimonials_style = get_theme_mod('aqualuxe_home_testimonials_style', 'carousel');
$testimonials_type = get_theme_mod('aqualuxe_home_testimonials_type', 0);
$testimonials_orderby = get_theme_mod('aqualuxe_home_testimonials_orderby', 'date');
$testimonials_order = get_theme_mod('aqualuxe_home_testimonials_order', 'DESC');
$testimonials_bg_color = get_theme_mod('aqualuxe_home_testimonials_bg_color', '#f8f9fa');
$testimonials_text_color = get_theme_mod('aqualuxe_home_testimonials_text_color', '#212529');
$testimonials_bg_image = get_theme_mod('aqualuxe_home_testimonials_bg_image', '');
$testimonials_overlay = get_theme_mod('aqualuxe_home_testimonials_overlay', 'rgba(0,0,0,0.5)');

// Check if testimonials should be displayed
if (!$show_testimonials) {
    return;
}

// Set up query arguments
$args = array(
    'post_type'      => 'testimonial',
    'posts_per_page' => $testimonials_count,
    'orderby'        => $testimonials_orderby,
    'order'          => $testimonials_order,
);

// Add type filter
if ($testimonials_type > 0) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'testimonial_type',
            'field'    => 'term_id',
            'terms'    => $testimonials_type,
        ),
    );
}

// Get testimonials
$testimonials_query = new WP_Query($args);

// Check if we have testimonials
if (!$testimonials_query->have_posts()) {
    return;
}

// Testimonials section classes
$testimonials_classes = array('testimonials-section', 'section-padding');
$testimonials_classes[] = 'testimonials-style-' . $testimonials_style;

if (!empty($testimonials_bg_image)) {
    $testimonials_classes[] = 'has-background';
}
?>

<div class="<?php echo esc_attr(implode(' ', $testimonials_classes)); ?>">
    <?php if (!empty($testimonials_bg_image)) : ?>
        <div class="testimonials-background" style="background-image: url('<?php echo esc_url($testimonials_bg_image); ?>');">
            <div class="testimonials-overlay" style="background-color: <?php echo esc_attr($testimonials_overlay); ?>;"></div>
        </div>
    <?php else : ?>
        <div class="testimonials-background" style="background-color: <?php echo esc_attr($testimonials_bg_color); ?>;"></div>
    <?php endif; ?>
    
    <div class="container">
        <div class="section-header text-center" style="color: <?php echo esc_attr($testimonials_text_color); ?>;">
            <?php if (!empty($testimonials_title)) : ?>
                <h2 class="section-title"><?php echo esc_html($testimonials_title); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($testimonials_subtitle)) : ?>
                <div class="section-subtitle"><?php echo esc_html($testimonials_subtitle); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="testimonials-wrapper">
            <?php if ($testimonials_style === 'carousel') : ?>
                <div class="testimonials-carousel">
                    <?php
                    // Loop through testimonials
                    while ($testimonials_query->have_posts()) :
                        $testimonials_query->the_post();
                        
                        // Get testimonial details
                        $client_name = get_post_meta(get_the_ID(), 'testimonial_client_name', true);
                        $client_company = get_post_meta(get_the_ID(), 'testimonial_client_company', true);
                        $client_position = get_post_meta(get_the_ID(), 'testimonial_client_position', true);
                        $client_rating = get_post_meta(get_the_ID(), 'testimonial_rating', true);
                        ?>
                        <div class="testimonial-item">
                            <div class="testimonial-inner" style="color: <?php echo esc_attr($testimonials_text_color); ?>;">
                                <?php if (!empty($client_rating)) : ?>
                                    <div class="testimonial-rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $client_rating) {
                                                echo '<i class="fas fa-star"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-content">
                                    <div class="testimonial-text">
                                        <i class="fas fa-quote-left testimonial-quote-icon"></i>
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                                
                                <div class="testimonial-client">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="client-image">
                                            <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid rounded-circle')); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="client-info">
                                        <?php if (!empty($client_name)) : ?>
                                            <h4 class="client-name"><?php echo esc_html($client_name); ?></h4>
                                        <?php else : ?>
                                            <h4 class="client-name"><?php the_title(); ?></h4>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($client_position) && !empty($client_company)) : ?>
                                            <p class="client-position"><?php echo esc_html($client_position); ?>, <?php echo esc_html($client_company); ?></p>
                                        <?php elseif (!empty($client_position)) : ?>
                                            <p class="client-position"><?php echo esc_html($client_position); ?></p>
                                        <?php elseif (!empty($client_company)) : ?>
                                            <p class="client-company"><?php echo esc_html($client_company); ?></p>
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
            <?php else : ?>
                <div class="row">
                    <?php
                    // Loop through testimonials
                    while ($testimonials_query->have_posts()) :
                        $testimonials_query->the_post();
                        
                        // Get testimonial details
                        $client_name = get_post_meta(get_the_ID(), 'testimonial_client_name', true);
                        $client_company = get_post_meta(get_the_ID(), 'testimonial_client_company', true);
                        $client_position = get_post_meta(get_the_ID(), 'testimonial_client_position', true);
                        $client_rating = get_post_meta(get_the_ID(), 'testimonial_rating', true);
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="testimonial-item">
                                <div class="testimonial-inner" style="color: <?php echo esc_attr($testimonials_text_color); ?>;">
                                    <?php if (!empty($client_rating)) : ?>
                                        <div class="testimonial-rating">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $client_rating) {
                                                    echo '<i class="fas fa-star"></i>';
                                                } else {
                                                    echo '<i class="far fa-star"></i>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-content">
                                        <div class="testimonial-text">
                                            <i class="fas fa-quote-left testimonial-quote-icon"></i>
                                            <?php the_content(); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="testimonial-client">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="client-image">
                                                <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid rounded-circle')); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="client-info">
                                            <?php if (!empty($client_name)) : ?>
                                                <h4 class="client-name"><?php echo esc_html($client_name); ?></h4>
                                            <?php else : ?>
                                                <h4 class="client-name"><?php the_title(); ?></h4>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($client_position) && !empty($client_company)) : ?>
                                                <p class="client-position"><?php echo esc_html($client_position); ?>, <?php echo esc_html($client_company); ?></p>
                                            <?php elseif (!empty($client_position)) : ?>
                                                <p class="client-position"><?php echo esc_html($client_position); ?></p>
                                            <?php elseif (!empty($client_company)) : ?>
                                                <p class="client-company"><?php echo esc_html($client_company); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>