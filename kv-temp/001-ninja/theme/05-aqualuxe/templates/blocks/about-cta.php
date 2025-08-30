<?php
/**
 * About Page Call to Action Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get about CTA settings from customizer or use defaults
$about_cta_title = get_theme_mod( 'aqualuxe_about_cta_title', 'Want to Visit Our Facilities?' );
$about_cta_text = get_theme_mod( 'aqualuxe_about_cta_text', 'We offer guided tours of our breeding facilities for hobbyists, schools, and aquarium clubs. Learn about our breeding programs and see our rare species up close.' );
$about_cta_button_text = get_theme_mod( 'aqualuxe_about_cta_button_text', 'Schedule a Tour' );
$about_cta_button_url = get_theme_mod( 'aqualuxe_about_cta_button_url', '#' );
$about_cta_background = get_theme_mod( 'aqualuxe_about_cta_background', get_template_directory_uri() . '/demo-content/images/about-cta-background.jpg' );
?>

<section class="about-cta-section" style="background-image: url('<?php echo esc_url( $about_cta_background ); ?>');">
    <div class="cta-overlay"></div>
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title"><?php echo esc_html( $about_cta_title ); ?></h2>
            <div class="cta-text"><?php echo wp_kses_post( $about_cta_text ); ?></div>
            <div class="cta-buttons">
                <a href="<?php echo esc_url( $about_cta_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $about_cta_button_text ); ?></a>
            </div>
        </div>
    </div>
</section>