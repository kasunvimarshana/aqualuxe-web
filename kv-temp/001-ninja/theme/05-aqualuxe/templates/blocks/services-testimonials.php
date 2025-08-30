<?php
/**
 * Services Page Testimonials Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get testimonials settings from customizer or use defaults
$testimonials_title = get_theme_mod( 'aqualuxe_services_testimonials_title', 'Client Testimonials' );
$testimonials_subtitle = get_theme_mod( 'aqualuxe_services_testimonials_subtitle', 'What our clients say about our services' );

// Demo testimonials data
$testimonials = array(
    array(
        'name' => 'Robert Chen',
        'role' => 'Restaurant Owner',
        'content' => 'AquaLuxe designed and maintains the 300-gallon reef aquarium in our restaurant. The tank is always immaculate, and our customers are constantly amazed by the vibrant colors and healthy fish. Their maintenance team is professional, punctual, and thorough.',
        'image' => get_template_directory_uri() . '/demo-content/images/testimonial-service-1.jpg',
        'rating' => 5,
        'service' => 'Commercial Aquarium Services',
    ),
    array(
        'name' => 'Jennifer Williams',
        'role' => 'Home Aquarium Enthusiast',
        'content' => 'I\'ve been using AquaLuxe\'s maintenance service for my 150-gallon planted aquarium for over a year now. Their attention to detail is impressive, and they\'ve helped me solve algae issues that I struggled with for months. My tank has never looked better!',
        'image' => get_template_directory_uri() . '/demo-content/images/testimonial-service-2.jpg',
        'rating' => 5,
        'service' => 'Premium Maintenance',
    ),
    array(
        'name' => 'Dr. Michael Thompson',
        'role' => 'Veterinary Clinic Owner',
        'content' => 'AquaLuxe designed a custom aquarium for our waiting room that has become a centerpiece of our clinic. Their design team listened to our needs and created something truly special. The ongoing maintenance service ensures it always looks perfect for our clients.',
        'image' => get_template_directory_uri() . '/demo-content/images/testimonial-service-3.jpg',
        'rating' => 5,
        'service' => 'Custom Aquarium Design',
    ),
    array(
        'name' => 'Lisa Rodriguez',
        'role' => 'Discus Breeder',
        'content' => 'The breeding program setup service from AquaLuxe transformed my hobby into a successful small business. Their expert advice on water parameters, feeding regimens, and breeding techniques has significantly increased my success rate with discus breeding.',
        'image' => get_template_directory_uri() . '/demo-content/images/testimonial-service-4.jpg',
        'rating' => 5,
        'service' => 'Breeding Program Setup',
    ),
);

// Filter testimonials through a hook to allow customization
$testimonials = apply_filters( 'aqualuxe_services_testimonials', $testimonials );

// Return if no testimonials
if ( empty( $testimonials ) ) {
    return;
}
?>

<section class="services-testimonials-section">
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
                            
                            <?php if ( ! empty( $testimonial['service'] ) ) : ?>
                                <div class="testimonial-service">
                                    <span class="service-label"><?php esc_html_e( 'Service:', 'aqualuxe' ); ?></span>
                                    <span class="service-name"><?php echo esc_html( $testimonial['service'] ); ?></span>
                                </div>
                            <?php endif; ?>
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
        
        <div class="testimonials-cta">
            <p><?php esc_html_e( 'Join our satisfied customers and experience the AquaLuxe difference.', 'aqualuxe' ); ?></p>
            <a href="#services-pricing" class="btn btn-primary"><?php esc_html_e( 'View Service Plans', 'aqualuxe' ); ?></a>
        </div>
    </div>
</section>