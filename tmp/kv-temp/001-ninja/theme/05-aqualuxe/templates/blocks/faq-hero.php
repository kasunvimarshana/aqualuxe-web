<?php
/**
 * FAQ Page Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get FAQ hero settings from customizer or use defaults
$faq_hero_title = get_theme_mod( 'aqualuxe_faq_hero_title', 'Frequently Asked Questions' );
$faq_hero_subtitle = get_theme_mod( 'aqualuxe_faq_hero_subtitle', 'Find answers to common questions about our products and services' );
$faq_hero_image = get_theme_mod( 'aqualuxe_faq_hero_image', get_template_directory_uri() . '/demo-content/images/faq-hero.jpg' );
?>

<section class="page-hero faq-hero" style="background-image: url('<?php echo esc_url( $faq_hero_image ); ?>');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo esc_html( $faq_hero_title ); ?></h1>
            <div class="hero-subtitle"><?php echo wp_kses_post( $faq_hero_subtitle ); ?></div>
            
            <div class="faq-search">
                <form role="search" method="get" class="faq-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="hidden" name="post_type" value="page">
                    <input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search the FAQ...', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
                    <button type="submit" class="search-submit"><span class="icon-search"></span></button>
                </form>
            </div>
        </div>
    </div>
</section>