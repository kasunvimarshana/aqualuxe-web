<?php
/**
 * Template part for displaying the hero section on the homepage
 *
 * @package AquaLuxe
 */

// Get hero settings from theme options or use defaults
$hero_title = aqualuxe_get_option( 'hero_title', 'Premium Ornamental Aquatic Solutions' );
$hero_subtitle = aqualuxe_get_option( 'hero_subtitle', 'Discover the beauty of aquatic life with our premium selection of ornamental fish and aquarium supplies.' );
$hero_button_text = aqualuxe_get_option( 'hero_button_text', 'Explore Our Collection' );
$hero_button_url = aqualuxe_get_option( 'hero_button_url', '#fish-catalog' );
$hero_secondary_button_text = aqualuxe_get_option( 'hero_secondary_button_text', 'Learn More' );
$hero_secondary_button_url = aqualuxe_get_option( 'hero_secondary_button_url', '#about' );

// Get hero background image
$hero_bg_image = aqualuxe_get_option( 'hero_bg_image', '' );
if ( empty( $hero_bg_image ) ) {
    $hero_bg_image = get_template_directory_uri() . '/assets/images/hero-bg.jpg';
}

// Hero style (full, contained, split)
$hero_style = aqualuxe_get_option( 'hero_style', 'full' );

// Hero height
$hero_height = aqualuxe_get_option( 'hero_height', 'large' );
$height_class = '';

switch ( $hero_height ) {
    case 'small':
        $height_class = 'min-h-[400px] md:min-h-[500px]';
        break;
    case 'medium':
        $height_class = 'min-h-[500px] md:min-h-[600px]';
        break;
    case 'large':
        $height_class = 'min-h-[600px] md:min-h-[700px]';
        break;
    case 'full':
        $height_class = 'min-h-screen';
        break;
    default:
        $height_class = 'min-h-[600px] md:min-h-[700px]';
}

// Text alignment
$text_alignment = aqualuxe_get_option( 'hero_text_alignment', 'center' );
$text_class = '';

switch ( $text_alignment ) {
    case 'left':
        $text_class = 'text-left';
        break;
    case 'center':
        $text_class = 'text-center';
        break;
    case 'right':
        $text_class = 'text-right';
        break;
    default:
        $text_class = 'text-center';
}

// Text color
$text_color = aqualuxe_get_option( 'hero_text_color', 'light' );
$text_color_class = $text_color === 'light' ? 'text-white' : 'text-dark-500';

// Overlay opacity
$overlay_opacity = aqualuxe_get_option( 'hero_overlay_opacity', '50' );
$overlay_class = 'bg-opacity-' . $overlay_opacity;

// Hero classes based on style
$container_class = '';
$content_class = '';

switch ( $hero_style ) {
    case 'contained':
        $container_class = 'container-fluid max-w-screen-xl mx-auto px-4';
        $content_class = 'max-w-2xl mx-auto';
        break;
    case 'split':
        $container_class = 'container-fluid max-w-screen-xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-8 items-center';
        $content_class = '';
        break;
    case 'full':
    default:
        $container_class = 'container-fluid max-w-screen-xl mx-auto px-4';
        $content_class = 'max-w-2xl mx-auto';
}
?>

<section id="hero" class="hero relative <?php echo esc_attr( $height_class ); ?> flex items-center overflow-hidden">
    <?php if ( $hero_style !== 'split' ) : ?>
        <div class="absolute inset-0 z-0">
            <img src="<?php echo esc_url( $hero_bg_image ); ?>" alt="<?php echo esc_attr( $hero_title ); ?>" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-dark-500 <?php echo esc_attr( $overlay_class ); ?>"></div>
        </div>
    <?php endif; ?>
    
    <div class="<?php echo esc_attr( $container_class ); ?> relative z-10">
        <?php if ( $hero_style === 'split' ) : ?>
            <div class="hero-image-container hidden md:block">
                <div class="rounded-lg overflow-hidden">
                    <img src="<?php echo esc_url( $hero_bg_image ); ?>" alt="<?php echo esc_attr( $hero_title ); ?>" class="w-full h-auto">
                </div>
            </div>
            
            <div class="hero-content <?php echo esc_attr( $text_class ); ?>">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold mb-4"><?php echo esc_html( $hero_title ); ?></h1>
                <p class="text-lg md:text-xl mb-8"><?php echo esc_html( $hero_subtitle ); ?></p>
                <div class="flex flex-wrap gap-4 <?php echo $text_alignment === 'center' ? 'justify-center' : ($text_alignment === 'right' ? 'justify-end' : ''); ?>">
                    <a href="<?php echo esc_url( $hero_button_url ); ?>" class="btn-primary"><?php echo esc_html( $hero_button_text ); ?></a>
                    <a href="<?php echo esc_url( $hero_secondary_button_url ); ?>" class="btn-outline"><?php echo esc_html( $hero_secondary_button_text ); ?></a>
                </div>
            </div>
        <?php else : ?>
            <div class="hero-content <?php echo esc_attr( $content_class . ' ' . $text_class . ' ' . $text_color_class ); ?>">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold mb-4"><?php echo esc_html( $hero_title ); ?></h1>
                <p class="text-lg md:text-xl mb-8"><?php echo esc_html( $hero_subtitle ); ?></p>
                <div class="flex flex-wrap gap-4 <?php echo $text_alignment === 'center' ? 'justify-center' : ($text_alignment === 'right' ? 'justify-end' : ''); ?>">
                    <a href="<?php echo esc_url( $hero_button_url ); ?>" class="btn-primary"><?php echo esc_html( $hero_button_text ); ?></a>
                    <a href="<?php echo esc_url( $hero_secondary_button_url ); ?>" class="btn-outline bg-opacity-20 bg-white border-white hover:bg-white hover:text-dark-500"><?php echo esc_html( $hero_secondary_button_text ); ?></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if ( aqualuxe_get_option( 'hero_show_scroll_down', true ) ) : ?>
        <a href="#content" class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center text-white animate-bounce">
            <span class="sr-only"><?php esc_html_e( 'Scroll Down', 'aqualuxe' ); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </a>
    <?php endif; ?>
</section>