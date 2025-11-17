<?php
/**
 * Template part for displaying the newsletter section on the homepage
 *
 * @package AquaLuxe
 */

// Get newsletter section settings from customizer or use defaults
$section_title = get_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_newsletter_description', __( 'Stay updated with the latest news, rare species arrivals, and exclusive offers.', 'aqualuxe' ) );
$button_text = get_theme_mod( 'aqualuxe_newsletter_button_text', __( 'Subscribe', 'aqualuxe' ) );
$show_section = get_theme_mod( 'aqualuxe_newsletter_show', true );
$background_image = get_theme_mod( 'aqualuxe_newsletter_background', get_template_directory_uri() . '/assets/dist/images/newsletter-bg.jpg' );
$overlay_opacity = get_theme_mod( 'aqualuxe_newsletter_overlay_opacity', 70 );

// If section is disabled, return
if ( ! $show_section ) {
    return;
}

// Convert overlay opacity to CSS value
$overlay_opacity = $overlay_opacity / 100;

// Check if Mailchimp for WordPress plugin is active
$has_mc4wp = function_exists( 'mc4wp_show_form' );
?>

<section class="newsletter-section py-16 bg-cover bg-center relative" style="background-image: url('<?php echo esc_url( $background_image ); ?>');">
    <div class="absolute inset-0 bg-primary opacity-<?php echo esc_attr( $overlay_opacity * 100 ); ?> dark:bg-gray-900"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="newsletter-content max-w-2xl mx-auto text-center text-white">
            <?php if ( $section_title ) : ?>
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_description ) : ?>
                <p class="text-xl mb-8"><?php echo esc_html( $section_description ); ?></p>
            <?php endif; ?>
            
            <div class="newsletter-form">
                <?php if ( $has_mc4wp ) : ?>
                    <?php mc4wp_show_form(); ?>
                <?php else : ?>
                    <form class="flex flex-col sm:flex-row gap-4">
                        <input type="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" class="flex-grow px-4 py-3 rounded-md focus:outline-none focus:ring-2 focus:ring-white" required>
                        <button type="submit" class="bg-white text-primary hover:bg-gray-100 px-6 py-3 rounded-md font-medium"><?php echo esc_html( $button_text ); ?></button>
                    </form>
                    <p class="text-sm mt-4 opacity-80"><?php esc_html_e( 'By subscribing, you agree to our Privacy Policy and consent to receive updates from our company.', 'aqualuxe' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>