<?php
/**
 * Homepage Testimonials Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'testimonials_title' => __( 'What Our Customers Say', 'aqualuxe' ),
    'testimonials_subtitle' => __( 'Hear from our satisfied customers', 'aqualuxe' ),
    'testimonials_items' => array(
        array(
            'name' => 'John Doe',
            'position' => 'Aquarium Enthusiast',
            'content' => 'The fish I received from AquaLuxe were healthy and vibrant. The packaging was excellent and the fish arrived in perfect condition.',
            'image' => '',
        ),
        array(
            'name' => 'Jane Smith',
            'position' => 'Professional Breeder',
            'content' => 'I\'ve been buying from AquaLuxe for years. Their fish are always top quality and their customer service is outstanding.',
            'image' => '',
        ),
        array(
            'name' => 'Mike Johnson',
            'position' => 'Aquarium Shop Owner',
            'content' => 'As a shop owner, I need reliable suppliers. AquaLuxe has never disappointed me with their quality and consistency.',
            'image' => '',
        ),
    ),
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['testimonials_title'];
$subtitle = $args['testimonials_subtitle'];
$testimonials = $args['testimonials_items'];

// Ensure we have testimonials
if ( empty( $testimonials ) ) {
    $testimonials = $defaults['testimonials_items'];
}
?>

<section class="aqualuxe-testimonials">
    <div class="aqualuxe-container">
        <div class="aqualuxe-section-header">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="aqualuxe-section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $subtitle ) ) : ?>
                <p class="aqualuxe-section-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-testimonials-slider">
            <?php foreach ( $testimonials as $testimonial ) : ?>
                <div class="aqualuxe-testimonial">
                    <div class="aqualuxe-testimonial-content">
                        <blockquote>
                            <?php echo esc_html( $testimonial['content'] ); ?>
                        </blockquote>
                    </div>
                    <div class="aqualuxe-testimonial-author">
                        <?php if ( ! empty( $testimonial['image'] ) ) : ?>
                            <div class="aqualuxe-testimonial-image">
                                <img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>" />
                            </div>
                        <?php endif; ?>
                        <div class="aqualuxe-testimonial-info">
                            <h4 class="aqualuxe-testimonial-name"><?php echo esc_html( $testimonial['name'] ); ?></h4>
                            <?php if ( ! empty( $testimonial['position'] ) ) : ?>
                                <p class="aqualuxe-testimonial-position"><?php echo esc_html( $testimonial['position'] ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="aqualuxe-testimonials-nav">
            <button class="aqualuxe-testimonials-prev" aria-label="<?php esc_attr_e( 'Previous testimonial', 'aqualuxe' ); ?>">
                <span class="dashicons dashicons-arrow-left-alt2"></span>
            </button>
            <button class="aqualuxe-testimonials-next" aria-label="<?php esc_attr_e( 'Next testimonial', 'aqualuxe' ); ?>">
                <span class="dashicons dashicons-arrow-right-alt2"></span>
            </button>
        </div>
    </div>
</section>