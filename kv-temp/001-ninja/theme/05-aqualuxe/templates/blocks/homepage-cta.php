<?php
/**
 * Homepage Call to Action Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get CTA settings from customizer or use defaults
$cta_title = get_theme_mod( 'aqualuxe_cta_title', 'Ready to Transform Your Aquarium?' );
$cta_text = get_theme_mod( 'aqualuxe_cta_text', 'Join thousands of satisfied customers who have created stunning aquatic environments with our premium fish species and expert guidance.' );
$cta_button_text = get_theme_mod( 'aqualuxe_cta_button_text', 'Shop Our Collection' );
$cta_button_url = get_theme_mod( 'aqualuxe_cta_button_url', wc_get_page_permalink( 'shop' ) );
$cta_secondary_button_text = get_theme_mod( 'aqualuxe_cta_secondary_button_text', 'Contact Us' );
$cta_secondary_button_url = get_theme_mod( 'aqualuxe_cta_secondary_button_url', '#' );
$cta_background = get_theme_mod( 'aqualuxe_cta_background', get_template_directory_uri() . '/demo-content/images/cta-background.jpg' );
?>

<section class="cta-section" style="background-image: url('<?php echo esc_url( $cta_background ); ?>');">
    <div class="cta-overlay"></div>
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title"><?php echo esc_html( $cta_title ); ?></h2>
            <div class="cta-text"><?php echo wp_kses_post( $cta_text ); ?></div>
            <div class="cta-buttons">
                <a href="<?php echo esc_url( $cta_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $cta_button_text ); ?></a>
                <a href="<?php echo esc_url( $cta_secondary_button_url ); ?>" class="btn btn-outline"><?php echo esc_html( $cta_secondary_button_text ); ?></a>
            </div>
        </div>
    </div>
</section>