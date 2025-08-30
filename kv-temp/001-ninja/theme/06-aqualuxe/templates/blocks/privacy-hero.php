<?php
/**
 * Privacy Policy Page Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get privacy hero settings from customizer or use defaults
$privacy_hero_title = get_theme_mod( 'aqualuxe_privacy_hero_title', 'Privacy Policy' );
$privacy_hero_subtitle = get_theme_mod( 'aqualuxe_privacy_hero_subtitle', 'How we collect, use, and protect your information' );
$privacy_hero_image = get_theme_mod( 'aqualuxe_privacy_hero_image', get_template_directory_uri() . '/demo-content/images/privacy-hero.jpg' );
?>

<section class="page-hero privacy-hero" style="background-image: url('<?php echo esc_url( $privacy_hero_image ); ?>');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html( $privacy_hero_title ); ?></h1>
            <div class="hero-subtitle"><?php echo wp_kses_post( $privacy_hero_subtitle ); ?></div>
            <div class="hero-meta">
                <div class="meta-item">
                    <span class="meta-label"><?php esc_html_e( 'Last Updated:', 'aqualuxe' ); ?></span>
                    <span class="meta-value"><?php echo esc_html( get_theme_mod( 'aqualuxe_privacy_last_updated', 'August 8, 2025' ) ); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>