<?php
/**
 * Shipping & Returns Page Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get shipping hero settings from customizer or use defaults
$shipping_hero_title = get_theme_mod( 'aqualuxe_shipping_hero_title', 'Shipping & Returns' );
$shipping_hero_subtitle = get_theme_mod( 'aqualuxe_shipping_hero_subtitle', 'Our policies for safe delivery and customer satisfaction' );
$shipping_hero_image = get_theme_mod( 'aqualuxe_shipping_hero_image', get_template_directory_uri() . '/demo-content/images/shipping-hero.jpg' );
?>

<section class="page-hero shipping-hero" style="background-image: url('<?php echo esc_url( $shipping_hero_image ); ?>');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html( $shipping_hero_title ); ?></h1>
            <div class="hero-subtitle"><?php echo wp_kses_post( $shipping_hero_subtitle ); ?></div>
        </div>
    </div>
</section>