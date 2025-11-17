<?php
/**
 * About Page Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get about hero settings from customizer or use defaults
$about_hero_title = get_theme_mod( 'aqualuxe_about_hero_title', 'About AquaLuxe' );
$about_hero_subtitle = get_theme_mod( 'aqualuxe_about_hero_subtitle', 'Passionate about aquatic excellence since 2005' );
$about_hero_image = get_theme_mod( 'aqualuxe_about_hero_image', get_template_directory_uri() . '/demo-content/images/about-hero.jpg' );
?>

<section class="page-hero about-hero" style="background-image: url('<?php echo esc_url( $about_hero_image ); ?>');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html( $about_hero_title ); ?></h1>
            <div class="hero-subtitle"><?php echo wp_kses_post( $about_hero_subtitle ); ?></div>
        </div>
    </div>
</section>