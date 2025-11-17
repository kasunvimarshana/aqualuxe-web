<?php
/**
 * Template part for displaying the call-to-action section on the homepage
 *
 * @package AquaLuxe
 */

// Get section settings from theme options or use defaults
$section_title = aqualuxe_get_option( 'cta_title', 'Ready to Transform Your Aquatic Experience?' );
$section_subtitle = aqualuxe_get_option( 'cta_subtitle', 'Join thousands of satisfied customers worldwide' );
$section_content = aqualuxe_get_option( 'cta_content', '' );
$primary_button_text = aqualuxe_get_option( 'cta_primary_button_text', 'Shop Now' );
$primary_button_url = aqualuxe_get_option( 'cta_primary_button_url', '#' );
$secondary_button_text = aqualuxe_get_option( 'cta_secondary_button_text', 'Contact Us' );
$secondary_button_url = aqualuxe_get_option( 'cta_secondary_button_url', '#' );
$cta_style = aqualuxe_get_option( 'cta_style', 'standard' );
$cta_bg_type = aqualuxe_get_option( 'cta_bg_type', 'color' );
$cta_bg_image = aqualuxe_get_option( 'cta_bg_image', '' );

// If no content is set, use default
if ( empty( $section_content ) ) {
    $section_content = 'Discover our premium selection of ornamental fish, aquarium supplies, and professional services. Whether you\'re a beginner or an experienced aquarist, we have everything you need to create and maintain a stunning aquatic environment.';
}

// Set up section classes based on style and background type
$section_class = '';
$container_class = '';
$content_class = '';
$overlay_class = '';
$text_class = '';

// Style-specific classes
switch ( $cta_style ) {
    case 'boxed':
        $container_class = 'max-w-4xl mx-auto bg-white dark:bg-dark-400 rounded-lg shadow-lg p-8 md:p-12';
        $content_class = 'text-center';
        break;
    case 'split':
        $container_class = 'grid grid-cols-1 md:grid-cols-2 gap-8 items-center';
        $content_class = '';
        break;
    case 'minimal':
        $container_class = 'max-w-3xl mx-auto';
        $content_class = 'text-center';
        break;
    case 'standard':
    default:
        $container_class = '';
        $content_class = 'text-center max-w-3xl mx-auto';
}

// Background-specific classes
switch ( $cta_bg_type ) {
    case 'image':
        if ( ! empty( $cta_bg_image ) ) {
            $section_class = 'bg-cover bg-center relative';
            $overlay_class = 'absolute inset-0 bg-dark-500 bg-opacity-70';
            $text_class = 'text-white relative z-10';
        } else {
            $section_class = 'bg-primary-500';
            $text_class = 'text-white';
        }
        break;
    case 'primary':
        $section_class = 'bg-primary-500';
        $text_class = 'text-white';
        break;
    case 'secondary':
        $section_class = 'bg-secondary-500';
        $text_class = 'text-white';
        break;
    case 'gradient':
        $section_class = 'bg-gradient-to-r from-primary-500 to-secondary-500';
        $text_class = 'text-white';
        break;
    case 'color':
    default:
        $section_class = 'bg-gray-50 dark:bg-dark-400';
        $text_class = '';
}

// Process the content
$section_content = wpautop( $section_content );
?>

<section id="cta" class="cta py-16 <?php echo esc_attr( $section_class ); ?>" <?php if ( $cta_bg_type === 'image' && ! empty( $cta_bg_image ) ) : ?>style="background-image: url('<?php echo esc_url( $cta_bg_image ); ?>');"<?php endif; ?>>
    <?php if ( $cta_bg_type === 'image' && ! empty( $cta_bg_image ) ) : ?>
        <div class="<?php echo esc_attr( $overlay_class ); ?>"></div>
    <?php endif; ?>
    
    <div class="container-fluid max-w-screen-xl mx-auto px-4">
        <div class="<?php echo esc_attr( $container_class ); ?>">
            <?php if ( $cta_style === 'split' ) : ?>
                <div class="cta-image">
                    <?php if ( ! empty( $cta_bg_image ) && $cta_bg_type !== 'image' ) : ?>
                        <img src="<?php echo esc_url( $cta_bg_image ); ?>" alt="<?php echo esc_attr( $section_title ); ?>" class="rounded-lg shadow-lg w-full h-auto">
                    <?php else : ?>
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/cta-default.jpg' ); ?>" alt="<?php echo esc_attr( $section_title ); ?>" class="rounded-lg shadow-lg w-full h-auto">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="cta-content <?php echo esc_attr( $content_class . ' ' . $text_class ); ?>">
                <?php if ( $section_title ) : ?>
                    <h2 class="section-title text-3xl md:text-4xl font-serif font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
                <?php endif; ?>
                
                <?php if ( $section_subtitle ) : ?>
                    <p class="section-subtitle text-lg <?php echo esc_attr( $text_class ? '' : 'text-gray-600 dark:text-gray-300' ); ?> mb-6"><?php echo esc_html( $section_subtitle ); ?></p>
                <?php endif; ?>
                
                <?php if ( $section_content ) : ?>
                    <div class="cta-description prose <?php echo esc_attr( $text_class ? 'prose-invert' : 'dark:prose-invert' ); ?> max-w-none mb-8">
                        <?php echo wp_kses_post( $section_content ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="cta-buttons flex flex-wrap justify-center gap-4">
                    <?php if ( $primary_button_text && $primary_button_url ) : ?>
                        <a href="<?php echo esc_url( $primary_button_url ); ?>" class="btn-primary">
                            <?php echo esc_html( $primary_button_text ); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ( $secondary_button_text && $secondary_button_url ) : ?>
                        <a href="<?php echo esc_url( $secondary_button_url ); ?>" class="<?php echo esc_attr( $text_class ? 'btn-outline bg-opacity-20 bg-white border-white hover:bg-white hover:text-dark-500' : 'btn-outline' ); ?>">
                            <?php echo esc_html( $secondary_button_text ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>