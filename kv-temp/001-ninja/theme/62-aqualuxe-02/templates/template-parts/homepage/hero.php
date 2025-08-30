<?php
/**
 * Homepage Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get hero settings from customizer or ACF
$hero_title = get_theme_mod( 'aqualuxe_hero_title', __( 'Premium WordPress Theme for Modern E-commerce', 'aqualuxe' ) );
$hero_subtitle = get_theme_mod( 'aqualuxe_hero_subtitle', __( 'Elevate your online store with our feature-rich, high-performance theme designed for serious businesses.', 'aqualuxe' ) );
$hero_button_text = get_theme_mod( 'aqualuxe_hero_button_text', __( 'Shop Now', 'aqualuxe' ) );
$hero_button_url = get_theme_mod( 'aqualuxe_hero_button_url', '#' );
$hero_secondary_button_text = get_theme_mod( 'aqualuxe_hero_secondary_button_text', __( 'Learn More', 'aqualuxe' ) );
$hero_secondary_button_url = get_theme_mod( 'aqualuxe_hero_secondary_button_url', '#' );
$hero_background_image = get_theme_mod( 'aqualuxe_hero_background_image', get_template_directory_uri() . '/assets/images/hero-background.jpg' );
$hero_overlay = get_theme_mod( 'aqualuxe_hero_overlay', true );
$hero_overlay_opacity = get_theme_mod( 'aqualuxe_hero_overlay_opacity', 50 );
$hero_text_color = get_theme_mod( 'aqualuxe_hero_text_color', 'light' );

// Hero classes
$hero_classes = array( 'hero-section' );
if ( $hero_overlay ) {
    $hero_classes[] = 'has-overlay';
}
if ( $hero_text_color === 'light' ) {
    $hero_classes[] = 'text-light';
} else {
    $hero_classes[] = 'text-dark';
}

// Hero style
$hero_style = '';
if ( $hero_background_image ) {
    $hero_style .= 'background-image: url(' . esc_url( $hero_background_image ) . ');';
}
if ( $hero_overlay && $hero_overlay_opacity ) {
    $hero_style .= '--overlay-opacity: ' . ( intval( $hero_overlay_opacity ) / 100 ) . ';';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $hero_classes ) ); ?>" style="<?php echo esc_attr( $hero_style ); ?>">
    <div class="container">
        <div class="hero-content">
            <?php if ( $hero_title ) : ?>
                <h1 class="hero-title"><?php echo wp_kses_post( $hero_title ); ?></h1>
            <?php endif; ?>
            
            <?php if ( $hero_subtitle ) : ?>
                <div class="hero-subtitle">
                    <p><?php echo wp_kses_post( $hero_subtitle ); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="hero-buttons">
                <?php if ( $hero_button_text && $hero_button_url ) : ?>
                    <a href="<?php echo esc_url( $hero_button_url ); ?>" class="btn btn-primary btn-lg"><?php echo esc_html( $hero_button_text ); ?></a>
                <?php endif; ?>
                
                <?php if ( $hero_secondary_button_text && $hero_secondary_button_url ) : ?>
                    <a href="<?php echo esc_url( $hero_secondary_button_url ); ?>" class="btn btn-outline-light btn-lg"><?php echo esc_html( $hero_secondary_button_text ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>