<?php
/**
 * Template part for displaying homepage testimonials section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get testimonials settings
$title = get_theme_mod( 'aqualuxe_homepage_testimonials_title', __( 'What Our Customers Say', 'aqualuxe' ) );
$subtitle = get_theme_mod( 'aqualuxe_homepage_testimonials_subtitle', __( 'Customer Testimonials', 'aqualuxe' ) );
$background = get_theme_mod( 'aqualuxe_homepage_testimonials_background', '' );

// Get testimonials
$testimonials = array();

// Check if we have custom testimonials defined in theme mods
for ( $i = 1; $i <= 6; $i++ ) {
    $testimonial_name = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_name', '' );
    $testimonial_position = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_position', '' );
    $testimonial_content = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_content', '' );
    $testimonial_image = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_image', '' );
    $testimonial_rating = get_theme_mod( 'aqualuxe_testimonial_' . $i . '_rating', 5 );
    
    if ( ! empty( $testimonial_name ) && ! empty( $testimonial_content ) ) {
        $testimonials[] = array(
            'name'     => $testimonial_name,
            'position' => $testimonial_position,
            'content'  => $testimonial_content,
            'image'    => $testimonial_image,
            'rating'   => $testimonial_rating,
        );
    }
}

// If no custom testimonials, use default ones
if ( empty( $testimonials ) ) {
    $testimonials = array(
        array(
            'name'     => __( 'John Smith', 'aqualuxe' ),
            'position' => __( 'Aquarium Enthusiast', 'aqualuxe' ),
            'content'  => __( 'AquaLuxe has transformed my home with their stunning custom aquarium. Their attention to detail and knowledge of aquatic life is unmatched. The maintenance service is excellent, keeping my aquarium looking pristine at all times.', 'aqualuxe' ),
            'image'    => '',
            'rating'   => 5,
        ),
        array(
            'name'     => __( 'Sarah Johnson', 'aqualuxe' ),
            'position' => __( 'Restaurant Owner', 'aqualuxe' ),
            'content'  => __( 'We installed an AquaLuxe aquarium in our restaurant, and it has become a focal point for our customers. The team was professional from design to installation, and their ongoing support has been exceptional.', 'aqualuxe' ),
            'image'    => '',
            'rating'   => 5,
        ),
        array(
            'name'     => __( 'Michael Chen', 'aqualuxe' ),
            'position' => __( 'Marine Biologist', 'aqualuxe' ),
            'content'  => __( 'As someone who works with marine life professionally, I can attest to the quality and care AquaLuxe provides. Their rare fish collection is ethically sourced, and their aquascaping services are world-class.', 'aqualuxe' ),
            'image'    => '',
            'rating'   => 5,
        ),
    );
}

// Section classes
$section_classes = array( 'homepage-testimonials' );

if ( ! empty( $background ) ) {
    $section_classes[] = 'has-background-image';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>" <?php if ( ! empty( $background ) ) : ?>style="background-image: url('<?php echo esc_url( $background ); ?>');"<?php endif; ?>>
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="section-header">
            <?php if ( ! empty( $subtitle ) ) : ?>
                <div class="section-subtitle"><?php echo esc_html( $subtitle ); ?></div>
            <?php endif; ?>
            
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
        </div>
        
        <div class="testimonials-slider">
            <?php foreach ( $testimonials as $testimonial ) : ?>
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        <div class="testimonial-rating">
                            <?php
                            $rating = isset( $testimonial['rating'] ) ? intval( $testimonial['rating'] ) : 5;
                            $rating = max( 1, min( 5, $rating ) );
                            
                            for ( $i = 1; $i <= 5; $i++ ) {
                                if ( $i <= $rating ) {
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                } else {
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                }
                            }
                            ?>
                        </div>
                        
                        <div class="testimonial-text">
                            <svg class="quote-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            <?php echo wp_kses_post( $testimonial['content'] ); ?>
                        </div>
                        
                        <div class="testimonial-author">
                            <?php if ( ! empty( $testimonial['image'] ) ) : ?>
                                <div class="testimonial-author-image">
                                    <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-author-info">
                                <div class="testimonial-author-name"><?php echo esc_html( $testimonial['name'] ); ?></div>
                                
                                <?php if ( ! empty( $testimonial['position'] ) ) : ?>
                                    <div class="testimonial-author-position"><?php echo esc_html( $testimonial['position'] ); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>