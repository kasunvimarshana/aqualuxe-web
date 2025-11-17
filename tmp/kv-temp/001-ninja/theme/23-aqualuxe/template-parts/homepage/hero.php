<?php
/**
 * Template part for displaying the hero section on the homepage
 *
 * @package AquaLuxe
 */

// Get customizer options
$hero_title = get_theme_mod( 'aqualuxe_hero_title', __( 'Premium WordPress Theme for Modern Websites', 'aqualuxe' ) );
$hero_subtitle = get_theme_mod( 'aqualuxe_hero_subtitle', __( 'A versatile and elegant theme for businesses, portfolios, and online stores.', 'aqualuxe' ) );
$hero_button_text = get_theme_mod( 'aqualuxe_hero_button_text', __( 'Get Started', 'aqualuxe' ) );
$hero_button_url = get_theme_mod( 'aqualuxe_hero_button_url', '#' );
$hero_secondary_button_text = get_theme_mod( 'aqualuxe_hero_secondary_button_text', __( 'Learn More', 'aqualuxe' ) );
$hero_secondary_button_url = get_theme_mod( 'aqualuxe_hero_secondary_button_url', '#' );
$hero_image = get_theme_mod( 'aqualuxe_hero_image', get_template_directory_uri() . '/assets/images/hero-default.jpg' );
$hero_style = get_theme_mod( 'aqualuxe_hero_style', 'standard' );
$hero_overlay = get_theme_mod( 'aqualuxe_hero_overlay', true );
$hero_height = get_theme_mod( 'aqualuxe_hero_height', 'medium' );

// Set height class based on setting
$height_class = 'min-h-[60vh]';
if ( $hero_height === 'small' ) {
    $height_class = 'min-h-[40vh]';
} elseif ( $hero_height === 'large' ) {
    $height_class = 'min-h-[80vh]';
} elseif ( $hero_height === 'full' ) {
    $height_class = 'min-h-screen';
}

// Set overlay class
$overlay_class = $hero_overlay ? 'bg-black bg-opacity-50' : '';

// Set content alignment class
$content_class = 'text-center';
if ( $hero_style === 'left' ) {
    $content_class = 'text-left';
} elseif ( $hero_style === 'right' ) {
    $content_class = 'text-right';
}

?>

<section id="hero" class="hero-section relative <?php echo esc_attr( $height_class ); ?> flex items-center">
    <?php if ( $hero_image ) : ?>
        <div class="absolute inset-0 z-0">
            <img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( $hero_title ); ?>" class="w-full h-full object-cover" />
            <?php if ( $hero_overlay ) : ?>
                <div class="absolute inset-0 <?php echo esc_attr( $overlay_class ); ?>"></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl mx-auto <?php echo esc_attr( $content_class ); ?>">
            <?php if ( $hero_title ) : ?>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight"><?php echo esc_html( $hero_title ); ?></h1>
            <?php endif; ?>
            
            <?php if ( $hero_subtitle ) : ?>
                <p class="text-xl md:text-2xl text-white opacity-90 mb-8"><?php echo esc_html( $hero_subtitle ); ?></p>
            <?php endif; ?>
            
            <div class="flex flex-wrap gap-4 justify-center">
                <?php if ( $hero_button_text && $hero_button_url ) : ?>
                    <a href="<?php echo esc_url( $hero_button_url ); ?>" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                        <?php echo esc_html( $hero_button_text ); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ( $hero_secondary_button_text && $hero_secondary_button_url ) : ?>
                    <a href="<?php echo esc_url( $hero_secondary_button_url ); ?>" class="px-6 py-3 bg-transparent hover:bg-white/10 text-white border border-white font-medium rounded-md transition-colors">
                        <?php echo esc_html( $hero_secondary_button_text ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>