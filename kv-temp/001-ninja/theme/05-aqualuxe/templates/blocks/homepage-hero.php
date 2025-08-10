<?php
/**
 * Homepage Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get hero settings from customizer or use defaults
$hero_title = get_theme_mod( 'aqualuxe_hero_title', 'Premium Ornamental Fish for Collectors & Enthusiasts' );
$hero_subtitle = get_theme_mod( 'aqualuxe_hero_subtitle', 'Discover our exclusive collection of rare and exotic fish species, bred with care and expertise.' );
$hero_button_text = get_theme_mod( 'aqualuxe_hero_button_text', 'Explore Collection' );
$hero_button_url = get_theme_mod( 'aqualuxe_hero_button_url', '#fish-species' );
$hero_image = get_theme_mod( 'aqualuxe_hero_image', get_template_directory_uri() . '/demo-content/images/hero-fish.jpg' );
?>

<section class="hero-section" style="background-image: url('<?php echo esc_url( $hero_image ); ?>');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
            <div class="hero-subtitle"><?php echo wp_kses_post( $hero_subtitle ); ?></div>
            <div class="hero-buttons">
                <a href="<?php echo esc_url( $hero_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $hero_button_text ); ?></a>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?></a>
            </div>
        </div>
    </div>
</section>