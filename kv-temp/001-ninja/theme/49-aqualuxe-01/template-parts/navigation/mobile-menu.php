<?php
/**
 * Template part for displaying the mobile navigation menu
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div id="mobile-menu" class="mobile-menu-container fixed inset-0 z-50 bg-white dark:bg-gray-900 transform translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
    <div class="mobile-menu-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="site-branding">
            <?php if ( has_custom_logo() ) : ?>
                <div class="site-logo"><?php the_custom_logo(); ?></div>
            <?php else : ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                </h1>
            <?php endif; ?>
        </div>
        
        <button id="close-mobile-menu" class="close-menu-button p-2" aria-label="<?php esc_attr_e( 'Close Menu', 'aqualuxe' ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <div class="mobile-menu-content p-4 overflow-y-auto h-full pb-32">
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'mobile',
                'menu_id'        => 'mobile-menu-items',
                'menu_class'     => 'mobile-menu-items space-y-2',
                'container'      => false,
                'fallback_cb'    => 'aqualuxe_mobile_menu_fallback',
                'walker'         => new AquaLuxe_Walker_Mobile_Menu(),
            )
        );
        ?>
        
        <?php if ( is_active_sidebar( 'mobile-menu-widgets' ) ) : ?>
            <div class="mobile-widgets mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <?php dynamic_sidebar( 'mobile-menu-widgets' ); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>