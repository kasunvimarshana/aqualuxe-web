<?php
/**
 * Legal Pages Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get hero settings from customizer or ACF
$hero_title = get_theme_mod( 'aqualuxe_legal_hero_title', get_the_title() );
$hero_subtitle = get_theme_mod( 'aqualuxe_legal_hero_subtitle', __( 'Last updated: January 1, 2025', 'aqualuxe' ) );
$hero_background_color = get_theme_mod( 'aqualuxe_legal_hero_background', 'light' );
$hero_text_color = get_theme_mod( 'aqualuxe_legal_hero_text_color', 'dark' );

// Hero classes
$hero_classes = array( 'page-hero', 'legal-hero' );
if ( $hero_background_color === 'dark' ) {
    $hero_classes[] = 'bg-dark';
    $hero_text_color = 'light';
} else {
    $hero_classes[] = 'bg-light';
}

if ( $hero_text_color === 'light' ) {
    $hero_classes[] = 'text-light';
} else {
    $hero_classes[] = 'text-dark';
}

// Get last updated date from post modified date if not set in subtitle
if ( strpos( $hero_subtitle, 'Last updated' ) !== false ) {
    $last_updated = get_the_modified_date( 'F j, Y' );
    $hero_subtitle = sprintf( __( 'Last updated: %s', 'aqualuxe' ), $last_updated );
}
?>

<section class="<?php echo esc_attr( implode( ' ', $hero_classes ) ); ?>">
    <div class="container">
        <div class="hero-content">
            <?php if ( $hero_title ) : ?>
                <h1 class="page-title"><?php echo wp_kses_post( $hero_title ); ?></h1>
            <?php endif; ?>
            
            <?php if ( $hero_subtitle ) : ?>
                <div class="page-subtitle">
                    <p><?php echo wp_kses_post( $hero_subtitle ); ?></p>
                </div>
            <?php endif; ?>
            
            <?php
            // Breadcrumbs
            if ( function_exists( 'aqualuxe_breadcrumbs' ) ) {
                aqualuxe_breadcrumbs();
            }
            ?>
        </div>
    </div>
</section>