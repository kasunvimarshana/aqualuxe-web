<?php
/**
 * Template part for displaying homepage hero section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get hero settings
$hero_title = get_theme_mod( 'aqualuxe_homepage_hero_title', __( 'Bringing Elegance to Aquatic Life – Globally', 'aqualuxe' ) );
$hero_subtitle = get_theme_mod( 'aqualuxe_homepage_hero_subtitle', __( 'Premium Ornamental Fish, Aquatic Plants & Custom Aquariums', 'aqualuxe' ) );
$hero_text = get_theme_mod( 'aqualuxe_homepage_hero_text', __( 'Discover our exclusive collection of rare and exotic aquatic species, premium equipment, and professional services.', 'aqualuxe' ) );
$hero_button_text = get_theme_mod( 'aqualuxe_homepage_hero_button_text', __( 'Shop Now', 'aqualuxe' ) );
$hero_button_url = get_theme_mod( 'aqualuxe_homepage_hero_button_url', '#' );
$hero_button2_text = get_theme_mod( 'aqualuxe_homepage_hero_button2_text', __( 'Our Services', 'aqualuxe' ) );
$hero_button2_url = get_theme_mod( 'aqualuxe_homepage_hero_button2_url', '#' );
$hero_image = get_theme_mod( 'aqualuxe_homepage_hero_image', '' );
$hero_style = get_theme_mod( 'aqualuxe_homepage_hero_style', 'default' );

// Set default image if none is set
if ( empty( $hero_image ) ) {
    $hero_image = AQUALUXE_ASSETS_URI . 'images/hero-default.jpg';
}

// Hero classes
$hero_classes = array(
    'homepage-hero',
    'hero-style-' . $hero_style,
);

if ( ! empty( $hero_image ) ) {
    $hero_classes[] = 'has-background-image';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $hero_classes ) ); ?>" <?php if ( ! empty( $hero_image ) ) : ?>style="background-image: url('<?php echo esc_url( $hero_image ); ?>');"<?php endif; ?>>
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="hero-content">
            <?php if ( ! empty( $hero_subtitle ) ) : ?>
                <div class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></div>
            <?php endif; ?>
            
            <?php if ( ! empty( $hero_title ) ) : ?>
                <h1 class="hero-title"><?php echo esc_html( $hero_title ); ?></h1>
            <?php endif; ?>
            
            <?php if ( ! empty( $hero_text ) ) : ?>
                <div class="hero-text"><?php echo wp_kses_post( $hero_text ); ?></div>
            <?php endif; ?>
            
            <div class="hero-buttons">
                <?php if ( ! empty( $hero_button_text ) && ! empty( $hero_button_url ) ) : ?>
                    <a href="<?php echo esc_url( $hero_button_url ); ?>" class="button hero-button"><?php echo esc_html( $hero_button_text ); ?></a>
                <?php endif; ?>
                
                <?php if ( ! empty( $hero_button2_text ) && ! empty( $hero_button2_url ) ) : ?>
                    <a href="<?php echo esc_url( $hero_button2_url ); ?>" class="button button-outline hero-button-secondary"><?php echo esc_html( $hero_button2_text ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>