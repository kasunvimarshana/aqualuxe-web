<?php
/**
 * Template part for displaying the hero section on the front page
 *
 * @package AquaLuxe
 */

// Get hero settings from customizer or default values
$hero_title = get_theme_mod( 'aqualuxe_hero_title', __( 'Bringing Elegance to Aquatic Life – Globally', 'aqualuxe' ) );
$hero_description = get_theme_mod( 'aqualuxe_hero_description', __( 'Premium ornamental fish, aquatic plants, and custom aquarium solutions for collectors and enthusiasts worldwide.', 'aqualuxe' ) );
$hero_button_text = get_theme_mod( 'aqualuxe_hero_button_text', __( 'Shop Now', 'aqualuxe' ) );
$hero_button_url = get_theme_mod( 'aqualuxe_hero_button_url', aqualuxe_is_woocommerce_active() ? wc_get_page_permalink( 'shop' ) : '#' );
$hero_secondary_button_text = get_theme_mod( 'aqualuxe_hero_secondary_button_text', __( 'Learn More', 'aqualuxe' ) );
$hero_secondary_button_url = get_theme_mod( 'aqualuxe_hero_secondary_button_url', home_url( '/about' ) );

// Get hero background image
$hero_bg_id = get_theme_mod( 'aqualuxe_hero_background_image', '' );
$hero_bg_url = $hero_bg_id ? wp_get_attachment_image_url( $hero_bg_id, 'full' ) : get_template_directory_uri() . '/assets/dist/images/hero-default.jpg';

// Hero style
$hero_style = get_theme_mod( 'aqualuxe_hero_style', 'default' );
$hero_height = get_theme_mod( 'aqualuxe_hero_height', 'medium' );
$hero_text_align = get_theme_mod( 'aqualuxe_hero_text_align', 'left' );
$hero_overlay_opacity = get_theme_mod( 'aqualuxe_hero_overlay_opacity', '50' );

// Hero classes
$hero_classes = array(
    'hero',
    'hero-' . $hero_style,
    'hero-height-' . $hero_height,
    'hero-text-' . $hero_text_align,
);

// Hero content classes
$hero_content_classes = array(
    'hero-content',
    'text-' . $hero_text_align,
);

// Check if we should use a slider instead of a static hero
$use_slider = get_theme_mod( 'aqualuxe_hero_use_slider', false );
?>

<section class="<?php echo esc_attr( implode( ' ', $hero_classes ) ); ?>" style="background-image: url('<?php echo esc_url( $hero_bg_url ); ?>');">
    <div class="hero-overlay" style="opacity: <?php echo esc_attr( $hero_overlay_opacity / 100 ); ?>;"></div>
    
    <?php if ( $use_slider ) : ?>
        <?php
        // Get slider settings
        $slider_shortcode = get_theme_mod( 'aqualuxe_hero_slider_shortcode', '' );
        
        if ( ! empty( $slider_shortcode ) ) {
            echo do_shortcode( $slider_shortcode );
        } else {
            // Fallback to default hero content if no slider shortcode is provided
            ?>
            <div class="container">
                <div class="<?php echo esc_attr( implode( ' ', $hero_content_classes ) ); ?>">
                    <h1 class="hero-title"><?php echo wp_kses_post( $hero_title ); ?></h1>
                    <div class="hero-description"><?php echo wp_kses_post( $hero_description ); ?></div>
                    <div class="hero-buttons">
                        <?php if ( $hero_button_text ) : ?>
                            <a href="<?php echo esc_url( $hero_button_url ); ?>" class="button button-lg"><?php echo esc_html( $hero_button_text ); ?></a>
                        <?php endif; ?>
                        
                        <?php if ( $hero_secondary_button_text ) : ?>
                            <a href="<?php echo esc_url( $hero_secondary_button_url ); ?>" class="button button-lg button-outline"><?php echo esc_html( $hero_secondary_button_text ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    <?php else : ?>
        <div class="container">
            <div class="<?php echo esc_attr( implode( ' ', $hero_content_classes ) ); ?>">
                <h1 class="hero-title"><?php echo wp_kses_post( $hero_title ); ?></h1>
                <div class="hero-description"><?php echo wp_kses_post( $hero_description ); ?></div>
                <div class="hero-buttons">
                    <?php if ( $hero_button_text ) : ?>
                        <a href="<?php echo esc_url( $hero_button_url ); ?>" class="button button-lg"><?php echo esc_html( $hero_button_text ); ?></a>
                    <?php endif; ?>
                    
                    <?php if ( $hero_secondary_button_text ) : ?>
                        <a href="<?php echo esc_url( $hero_secondary_button_url ); ?>" class="button button-lg button-outline"><?php echo esc_html( $hero_secondary_button_text ); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ( get_theme_mod( 'aqualuxe_hero_show_scroll_down', true ) ) : ?>
        <a href="#content" class="hero-scroll-down">
            <span class="screen-reader-text"><?php esc_html_e( 'Scroll Down', 'aqualuxe' ); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"/></svg>
        </a>
    <?php endif; ?>
</section>