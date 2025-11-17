<?php
/**
 * Template part for displaying the testimonials section on the front page
 *
 * @package AquaLuxe
 */

// Get section settings from customizer or default values
$section_title = get_theme_mod( 'aqualuxe_testimonials_title', __( 'What Our Customers Say', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_testimonials_description', __( 'Read testimonials from our satisfied customers around the world.', 'aqualuxe' ) );
$layout_style = get_theme_mod( 'aqualuxe_testimonials_layout', 'slider' );
$background_style = get_theme_mod( 'aqualuxe_testimonials_background', 'dark' );
$columns = get_theme_mod( 'aqualuxe_testimonials_columns', 3 );

// Section classes
$section_classes = array(
    'section',
    'testimonials-section',
    'testimonials-layout-' . $layout_style,
    'testimonials-background-' . $background_style,
);

// Check if we should show the section
$show_section = get_theme_mod( 'aqualuxe_show_testimonials', true );

if ( ! $show_section ) {
    return;
}

// Get testimonials
$testimonials = array();
for ( $i = 1; $i <= 6; $i++ ) {
    $testimonial_content = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_content', '' );
    $testimonial_name = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_name', '' );
    $testimonial_position = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_position', '' );
    $testimonial_image = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_image', '' );
    $testimonial_rating = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_rating', 5 );
    
    if ( $testimonial_content && $testimonial_name ) {
        $testimonials[] = array(
            'content' => $testimonial_content,
            'name' => $testimonial_name,
            'position' => $testimonial_position,
            'image' => $testimonial_image,
            'rating' => $testimonial_rating,
        );
    }
}

// Default testimonials if none are set
if ( empty( $testimonials ) ) {
    $testimonials = array(
        array(
            'content' => __( 'AquaLuxe has transformed my living space with their stunning custom aquarium. Their attention to detail and knowledge of aquatic ecosystems is unparalleled. The maintenance service is prompt and thorough.', 'aqualuxe' ),
            'name' => __( 'Michael Johnson', 'aqualuxe' ),
            'position' => __( 'Aquarium Enthusiast', 'aqualuxe' ),
            'image' => '',
            'rating' => 5,
        ),
        array(
            'content' => __( 'I\'ve been collecting rare fish species for years, and AquaLuxe is by far the best supplier I\'ve worked with. Their specimens are healthy, ethically sourced, and exactly as described. Shipping is always safe and secure.', 'aqualuxe' ),
            'name' => __( 'Sarah Williams', 'aqualuxe' ),
            'position' => __( 'Fish Collector', 'aqualuxe' ),
            'image' => '',
            'rating' => 5,
        ),
        array(
            'content' => __( 'The aquascaping service from AquaLuxe is truly an art form. They created a miniature underwater landscape that has become the centerpiece of my home. Their team is professional, creative, and passionate.', 'aqualuxe' ),
            'name' => __( 'David Chen', 'aqualuxe' ),
            'position' => __( 'Interior Designer', 'aqualuxe' ),
            'image' => '',
            'rating' => 5,
        ),
    );
}
?>

<section id="testimonials" class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="section-header">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_description ) : ?>
                <div class="section-description"><?php echo wp_kses_post( $section_description ); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="section-content">
            <?php if ( $layout_style === 'slider' ) : ?>
                <div class="testimonials-slider" data-slider-options='{"slidesToShow": <?php echo esc_attr( $columns ); ?>, "dots": true, "arrows": true, "autoplay": true}'>
                    <?php foreach ( $testimonials as $testimonial ) : ?>
                        <div class="testimonial-item">
                            <div class="testimonial-inner">
                                <div class="testimonial-content">
                                    <div class="testimonial-quote">
                                        <i class="icon-quote"></i>
                                    </div>
                                    
                                    <?php if ( ! empty( $testimonial['rating'] ) ) : ?>
                                        <div class="testimonial-rating">
                                            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                                <?php if ( $i <= $testimonial['rating'] ) : ?>
                                                    <i class="icon-star-filled"></i>
                                                <?php else : ?>
                                                    <i class="icon-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-text">
                                        <?php echo wp_kses_post( $testimonial['content'] ); ?>
                                    </div>
                                </div>
                                
                                <div class="testimonial-author">
                                    <?php if ( ! empty( $testimonial['image'] ) ) : ?>
                                        <div class="testimonial-author-image">
                                            <?php echo wp_get_attachment_image( $testimonial['image'], 'thumbnail', false, array( 'class' => 'testimonial-img' ) ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-author-info">
                                        <div class="testimonial-author-name">
                                            <?php echo esc_html( $testimonial['name'] ); ?>
                                        </div>
                                        
                                        <?php if ( ! empty( $testimonial['position'] ) ) : ?>
                                            <div class="testimonial-author-position">
                                                <?php echo esc_html( $testimonial['position'] ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="testimonials-grid grid-cols-<?php echo esc_attr( $columns ); ?>">
                    <?php foreach ( $testimonials as $testimonial ) : ?>
                        <div class="testimonial-item">
                            <div class="testimonial-inner">
                                <div class="testimonial-content">
                                    <div class="testimonial-quote">
                                        <i class="icon-quote"></i>
                                    </div>
                                    
                                    <?php if ( ! empty( $testimonial['rating'] ) ) : ?>
                                        <div class="testimonial-rating">
                                            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                                <?php if ( $i <= $testimonial['rating'] ) : ?>
                                                    <i class="icon-star-filled"></i>
                                                <?php else : ?>
                                                    <i class="icon-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-text">
                                        <?php echo wp_kses_post( $testimonial['content'] ); ?>
                                    </div>
                                </div>
                                
                                <div class="testimonial-author">
                                    <?php if ( ! empty( $testimonial['image'] ) ) : ?>
                                        <div class="testimonial-author-image">
                                            <?php echo wp_get_attachment_image( $testimonial['image'], 'thumbnail', false, array( 'class' => 'testimonial-img' ) ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-author-info">
                                        <div class="testimonial-author-name">
                                            <?php echo esc_html( $testimonial['name'] ); ?>
                                        </div>
                                        
                                        <?php if ( ! empty( $testimonial['position'] ) ) : ?>
                                            <div class="testimonial-author-position">
                                                <?php echo esc_html( $testimonial['position'] ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>