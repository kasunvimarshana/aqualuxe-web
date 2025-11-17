<?php
/**
 * Shipping & Returns Page Call to Action Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get shipping CTA settings from customizer or use defaults
$shipping_cta_title = get_theme_mod( 'aqualuxe_shipping_cta_title', 'Ready to Experience AquaLuxe?' );
$shipping_cta_text = get_theme_mod( 'aqualuxe_shipping_cta_text', 'Browse our collection of premium fish and aquatic products, backed by our industry-leading guarantees and shipping expertise.' );
$shipping_cta_button_text = get_theme_mod( 'aqualuxe_shipping_cta_button_text', 'Shop Now' );
$shipping_cta_button_url = get_theme_mod( 'aqualuxe_shipping_cta_button_url', wc_get_page_permalink( 'shop' ) );
$shipping_cta_background = get_theme_mod( 'aqualuxe_shipping_cta_background', get_template_directory_uri() . '/demo-content/images/shipping-cta-background.jpg' );
?>

<section class="shipping-cta-section" style="background-image: url('<?php echo esc_url( $shipping_cta_background ); ?>');">
    <div class="cta-overlay"></div>
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title"><?php echo esc_html( $shipping_cta_title ); ?></h2>
            <div class="cta-text"><?php echo wp_kses_post( $shipping_cta_text ); ?></div>
            <div class="cta-buttons">
                <a href="<?php echo esc_url( $shipping_cta_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $shipping_cta_button_text ); ?></a>
                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
            </div>
        </div>
    </div>
</section>