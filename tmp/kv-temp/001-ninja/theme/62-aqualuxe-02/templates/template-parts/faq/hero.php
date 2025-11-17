<?php
/**
 * FAQ Page Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get hero settings from customizer or ACF
$hero_title = get_theme_mod( 'aqualuxe_faq_hero_title', get_the_title() );
$hero_subtitle = get_theme_mod( 'aqualuxe_faq_hero_subtitle', __( 'Find answers to frequently asked questions about our products and services.', 'aqualuxe' ) );
$hero_background_image = get_theme_mod( 'aqualuxe_faq_hero_background_image', get_template_directory_uri() . '/assets/images/faq/faq-hero.jpg' );
$hero_overlay = get_theme_mod( 'aqualuxe_faq_hero_overlay', true );
$hero_overlay_opacity = get_theme_mod( 'aqualuxe_faq_hero_overlay_opacity', 50 );
$hero_text_color = get_theme_mod( 'aqualuxe_faq_hero_text_color', 'light' );
$show_search = get_theme_mod( 'aqualuxe_faq_hero_show_search', true );

// Hero classes
$hero_classes = array( 'page-hero', 'faq-hero' );
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
            
            <?php if ( $show_search ) : ?>
                <div class="faq-search">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="input-group">
                            <input type="search" class="search-field form-control" placeholder="<?php esc_attr_e( 'Search FAQs...', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                            <input type="hidden" name="post_type" value="faq" />
                            <button type="submit" class="search-submit btn btn-primary">
                                <i class="icon-search"></i>
                                <span class="screen-reader-text"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>