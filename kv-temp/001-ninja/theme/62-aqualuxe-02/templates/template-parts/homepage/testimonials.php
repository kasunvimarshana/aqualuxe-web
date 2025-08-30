<?php
/**
 * Homepage Testimonials Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get testimonials settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_testimonials_title', __( 'What Our Customers Say', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_testimonials_subtitle', __( 'Read testimonials from our satisfied customers', 'aqualuxe' ) );
$layout_style = get_theme_mod( 'aqualuxe_testimonials_layout', 'slider' );
$background_color = get_theme_mod( 'aqualuxe_testimonials_background', 'light' );

// Default testimonials if not set in customizer
$default_testimonials = array(
    array(
        'name'     => 'John Doe',
        'position' => 'CEO, Company Name',
        'content'  => 'AquaLuxe has transformed our online store. The performance improvements and beautiful design have significantly increased our conversion rates.',
        'rating'   => 5,
        'image'    => get_template_directory_uri() . '/assets/images/testimonials/testimonial-1.jpg',
    ),
    array(
        'name'     => 'Jane Smith',
        'position' => 'Marketing Director',
        'content'  => 'The customer support team is exceptional. They helped us customize the theme to match our brand perfectly. Highly recommended!',
        'rating'   => 5,
        'image'    => get_template_directory_uri() . '/assets/images/testimonials/testimonial-2.jpg',
    ),
    array(
        'name'     => 'Michael Johnson',
        'position' => 'E-commerce Manager',
        'content'  => 'The SEO features built into AquaLuxe have helped us rank higher in search results. Our organic traffic has increased by 40% since switching.',
        'rating'   => 4,
        'image'    => get_template_directory_uri() . '/assets/images/testimonials/testimonial-3.jpg',
    ),
);

// Get testimonials from customizer or use defaults
$testimonials = array();
for ( $i = 1; $i <= 3; $i++ ) {
    $testimonial_name = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_name', $default_testimonials[$i-1]['name'] );
    $testimonial_position = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_position', $default_testimonials[$i-1]['position'] );
    $testimonial_content = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_content', $default_testimonials[$i-1]['content'] );
    $testimonial_rating = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_rating', $default_testimonials[$i-1]['rating'] );
    $testimonial_image = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_image', $default_testimonials[$i-1]['image'] );
    
    if ( $testimonial_name && $testimonial_content ) {
        $testimonials[] = array(
            'name'     => $testimonial_name,
            'position' => $testimonial_position,
            'content'  => $testimonial_content,
            'rating'   => $testimonial_rating,
            'image'    => $testimonial_image,
        );
    }
}

// Skip if no testimonials
if ( empty( $testimonials ) ) {
    return;
}

// Section classes
$section_classes = array( 'testimonials-section', 'section' );
if ( $background_color === 'dark' ) {
    $section_classes[] = 'bg-dark text-light';
} else {
    $section_classes[] = 'bg-light';
}

// Testimonials container classes
$container_classes = array( 'testimonials-container' );
if ( $layout_style === 'slider' ) {
    $container_classes[] = 'testimonials-slider';
} else {
    $container_classes[] = 'testimonials-grid';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="section-header text-center">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <div class="section-subtitle">
                    <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>" data-slider-options='{"slidesToShow": 1, "slidesToScroll": 1, "dots": true, "arrows": true, "autoplay": true, "autoplaySpeed": 5000}'>
            <?php foreach ( $testimonials as $testimonial ) : ?>
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        <?php if ( $testimonial['rating'] ) : ?>
                            <div class="testimonial-rating">
                                <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                    <?php if ( $i <= $testimonial['rating'] ) : ?>
                                        <i class="icon-star-filled"></i>
                                    <?php else : ?>
                                        <i class="icon-star-empty"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="testimonial-text">
                            <p><?php echo wp_kses_post( $testimonial['content'] ); ?></p>
                        </div>
                    </div>
                    
                    <div class="testimonial-author">
                        <?php if ( $testimonial['image'] ) : ?>
                            <div class="testimonial-image">
                                <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>">
                            </div>
                        <?php endif; ?>
                        
                        <div class="testimonial-info">
                            <h4 class="testimonial-name"><?php echo esc_html( $testimonial['name'] ); ?></h4>
                            
                            <?php if ( $testimonial['position'] ) : ?>
                                <div class="testimonial-position"><?php echo esc_html( $testimonial['position'] ); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>