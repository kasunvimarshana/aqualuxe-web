<?php
/**
 * Template part for displaying the hero section on the homepage
 *
 * @package AquaLuxe
 */

// Get hero settings from customizer or use defaults
$hero_title = get_theme_mod( 'aqualuxe_hero_title', __( 'Bringing Elegance to Aquatic Life – Globally', 'aqualuxe' ) );
$hero_description = get_theme_mod( 'aqualuxe_hero_description', __( 'Premium ornamental fish, aquatic plants, and custom aquarium solutions for enthusiasts and professionals.', 'aqualuxe' ) );
$hero_button_text = get_theme_mod( 'aqualuxe_hero_button_text', __( 'Shop Now', 'aqualuxe' ) );
$hero_button_url = get_theme_mod( 'aqualuxe_hero_button_url', class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : '#' );
$hero_secondary_button_text = get_theme_mod( 'aqualuxe_hero_secondary_button_text', __( 'Learn More', 'aqualuxe' ) );
$hero_secondary_button_url = get_theme_mod( 'aqualuxe_hero_secondary_button_url', '#' );
$hero_image = get_theme_mod( 'aqualuxe_hero_image', get_template_directory_uri() . '/assets/dist/images/hero-default.jpg' );
$hero_overlay_opacity = get_theme_mod( 'aqualuxe_hero_overlay_opacity', 30 );
$hero_text_color = get_theme_mod( 'aqualuxe_hero_text_color', 'text-white' );
$hero_height = get_theme_mod( 'aqualuxe_hero_height', 'h-[600px]' );
$hero_layout = get_theme_mod( 'aqualuxe_hero_layout', 'center' );

// Convert overlay opacity to CSS value
$overlay_opacity = $hero_overlay_opacity / 100;

// Set layout classes based on hero layout
switch ( $hero_layout ) {
    case 'left':
        $content_classes = 'text-left items-start';
        break;
    case 'right':
        $content_classes = 'text-right items-end';
        break;
    case 'center':
    default:
        $content_classes = 'text-center items-center';
        break;
}
?>

<section class="hero-section relative <?php echo esc_attr( $hero_height ); ?> bg-cover bg-center flex items-center" style="background-image: url('<?php echo esc_url( $hero_image ); ?>');">
    <div class="absolute inset-0 bg-black opacity-<?php echo esc_attr( $hero_overlay_opacity ); ?>"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col <?php echo esc_attr( $content_classes ); ?> max-w-3xl mx-auto">
            <?php if ( $hero_title ) : ?>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 <?php echo esc_attr( $hero_text_color ); ?>"><?php echo esc_html( $hero_title ); ?></h1>
            <?php endif; ?>
            
            <?php if ( $hero_description ) : ?>
                <p class="text-xl md:text-2xl mb-8 <?php echo esc_attr( $hero_text_color ); ?>"><?php echo esc_html( $hero_description ); ?></p>
            <?php endif; ?>
            
            <div class="flex flex-wrap gap-4 justify-center">
                <?php if ( $hero_button_text && $hero_button_url ) : ?>
                    <a href="<?php echo esc_url( $hero_button_url ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <?php echo esc_html( $hero_button_text ); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ( $hero_secondary_button_text && $hero_secondary_button_url ) : ?>
                    <a href="<?php echo esc_url( $hero_secondary_button_url ); ?>" class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md shadow-sm text-white hover:bg-white hover:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                        <?php echo esc_html( $hero_secondary_button_text ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>