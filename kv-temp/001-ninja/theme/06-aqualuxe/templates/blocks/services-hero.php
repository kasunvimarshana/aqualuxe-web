<?php
/**
 * Services Page Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get services hero settings from customizer or use defaults
$services_hero_title = get_theme_mod( 'aqualuxe_services_hero_title', 'Our Services' );
$services_hero_subtitle = get_theme_mod( 'aqualuxe_services_hero_subtitle', 'Expert solutions for all your aquatic needs' );
$services_hero_image = get_theme_mod( 'aqualuxe_services_hero_image', get_template_directory_uri() . '/demo-content/images/services-hero.jpg' );
?>

<section class="page-hero services-hero" style="background-image: url('<?php echo esc_url( $services_hero_image ); ?>');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html( $services_hero_title ); ?></h1>
            <div class="hero-subtitle"><?php echo wp_kses_post( $services_hero_subtitle ); ?></div>
        </div>
    </div>
</section>