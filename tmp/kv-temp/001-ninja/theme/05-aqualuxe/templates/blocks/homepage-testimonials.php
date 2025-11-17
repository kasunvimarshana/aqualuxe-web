<?php
/**
 * Homepage Testimonials Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get testimonials settings from customizer or use defaults
$testimonials_title = get_theme_mod( 'aqualuxe_testimonials_title', 'What Our Customers Say' );
$testimonials_subtitle = get_theme_mod( 'aqualuxe_testimonials_subtitle', 'Hear from our satisfied customers around the world' );

// Demo testimonials data
$testimonials = array(
    array(
        'name' => 'John Anderson',
        'role' => 'Aquarium Enthusiast',
        'content' => 'I\'ve been collecting exotic fish for over 15 years, and AquaLuxe provides the healthiest, most vibrant specimens I\'ve ever purchased. Their attention to detail in shipping and handling is unmatched.',
        'image' => get_template_directory_uri() . '/demo-content/images/testimonial-1.jpg',
        'rating' => 5,
    ),
    array(
        'name' => 'Sarah Johnson',
        'role' => 'Professional Aquascaper',
        'content' => 'As a professional aquascaper, I need fish that not only look beautiful but are also healthy and well-acclimated. AquaLuxe consistently delivers on all fronts. Their customer service is also exceptional.',
        'image' => get_template_directory_uri() . '/demo-content/images/testimonial-2.jpg',
        'rating' => 5,
    ),
    array(
        'name' => 'Michael Chen',
        'role' => 'Aquarium Store Owner',
        'content' => 'We\'ve been sourcing our rare species from AquaLuxe for our store, and our customers are always impressed with the quality. The fish arrive in perfect condition, and the packaging is environmentally responsible.',
        'image' => get_template_directory_uri() . '/demo-content/images/testimonial-3.jpg',
        'rating' => 5,
    ),
);

// Filter testimonials through a hook to allow customization
$testimonials = apply_filters( 'aqualuxe_homepage_testimonials', $testimonials );

// Return if no testimonials
if ( empty( $testimonials ) ) {
    return;
}
?>

<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $testimonials_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $testimonials_subtitle ); ?></div>
        </div>
        
        <div class="testimonials-slider">
            <?php foreach ( $testimonials as $testimonial ) : ?>
                <div class="testimonial-item">
                    <div class="testimonial-inner">
                        <div class="testimonial-content">
                            <?php if ( ! empty( $testimonial['rating'] ) ) : ?>
                                <div class="testimonial-rating">
                                    <?php
                                    for ( $i = 1; $i <= 5; $i++ ) {
                                        if ( $i <= $testimonial['rating'] ) {
                                            echo '<span class="star star-filled"></span>';
                                        } else {
                                            echo '<span class="star star-empty"></span>';
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-text">
                                <?php echo wpautop( esc_html( $testimonial['content'] ) ); ?>
                            </div>
                        </div>
                        
                        <div class="testimonial-author">
                            <?php if ( ! empty( $testimonial['image'] ) ) : ?>
                                <div class="testimonial-author-image">
                                    <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-author-info">
                                <div class="testimonial-author-name"><?php echo esc_html( $testimonial['name'] ); ?></div>
                                <?php if ( ! empty( $testimonial['role'] ) ) : ?>
                                    <div class="testimonial-author-role"><?php echo esc_html( $testimonial['role'] ); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>