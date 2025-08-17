<?php
/**
 * Template part for displaying the top bar
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check if top bar is enabled
if ( ! get_theme_mod( 'aqualuxe_top_bar_enable', true ) ) {
    return;
}
?>

<div class="top-bar bg-gray-800 text-white py-2">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="top-bar-left mb-2 md:mb-0 text-center md:text-left">
                <?php if ( get_theme_mod( 'aqualuxe_top_bar_text', '' ) ) : ?>
                    <span class="top-bar-text text-sm"><?php echo wp_kses_post( get_theme_mod( 'aqualuxe_top_bar_text' ) ); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="top-bar-right flex items-center justify-center md:justify-end space-x-4">
                <?php if ( get_theme_mod( 'aqualuxe_top_bar_phone', '' ) ) : ?>
                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', get_theme_mod( 'aqualuxe_top_bar_phone' ) ) ); ?>" class="top-bar-phone text-sm hover:text-primary-400 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <?php echo esc_html( get_theme_mod( 'aqualuxe_top_bar_phone' ) ); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ( get_theme_mod( 'aqualuxe_top_bar_email', '' ) ) : ?>
                    <a href="mailto:<?php echo esc_attr( get_theme_mod( 'aqualuxe_top_bar_email' ) ); ?>" class="top-bar-email text-sm hover:text-primary-400 transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <?php echo esc_html( get_theme_mod( 'aqualuxe_top_bar_email' ) ); ?>
                    </a>
                <?php endif; ?>
                
                <?php get_template_part( 'template-parts/navigation/secondary-menu' ); ?>
                
                <?php if ( function_exists( 'aqualuxe_language_switcher' ) ) : ?>
                    <div class="top-bar-language-switcher">
                        <?php aqualuxe_language_switcher(); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( function_exists( 'aqualuxe_currency_switcher' ) && class_exists( 'WooCommerce' ) ) : ?>
                    <div class="top-bar-currency-switcher">
                        <?php aqualuxe_currency_switcher(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>