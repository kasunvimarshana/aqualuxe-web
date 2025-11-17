<?php
/**
 * Contact Page Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get contact hero settings from customizer or use defaults
$contact_hero_title = get_theme_mod( 'aqualuxe_contact_hero_title', 'Contact Us' );
$contact_hero_subtitle = get_theme_mod( 'aqualuxe_contact_hero_subtitle', 'We\'d love to hear from you' );
$contact_hero_image = get_theme_mod( 'aqualuxe_contact_hero_image', get_template_directory_uri() . '/demo-content/images/contact-hero.jpg' );
?>

<section class="page-hero contact-hero" style="background-image: url('<?php echo esc_url( $contact_hero_image ); ?>');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html( $contact_hero_title ); ?></h1>
            <div class="hero-subtitle"><?php echo wp_kses_post( $contact_hero_subtitle ); ?></div>
        </div>
    </div>
</section>