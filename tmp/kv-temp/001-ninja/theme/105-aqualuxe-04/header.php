<?php
/**
 * The header for our theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?> class="<?php echo aqualuxe_is_dark_mode() ? 'dark' : ''; ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
    <a class="skip-link screen-reader-text sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 bg-primary-600 text-white p-2 z-50 focus:z-[9999]" href="#main">
        <?php esc_html_e('Skip to content', 'aqualuxe'); ?>
    </a>

    <header id="masthead" class="site-header bg-white dark:bg-gray-900 shadow-sm sticky top-0 z-40 transition-colors duration-300" role="banner">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                
                <!-- Site Branding -->
                <div class="site-branding">
                    <?php aqualuxe_site_branding(); ?>
                </div>

                <!-- Primary Navigation -->
                <nav class="main-navigation hidden lg:block" aria-label="<?php esc_attr_e('Primary Navigation', 'aqualuxe'); ?>" role="navigation">
                    <?php
                    if (has_nav_menu('primary')) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'primary-menu flex items-center space-x-8',
                            'container'      => false,
                            'depth'          => 2,
                            'walker'         => new AquaLuxe_Walker_Nav_Menu(),
                        ));
                    }
                    ?>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions flex items-center space-x-4" role="toolbar" aria-label="<?php esc_attr_e('Header actions', 'aqualuxe'); ?>">
                    
                    <!-- Search Toggle -->
                    <button type="button" 
                            class="search-toggle p-2 text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-md"
                            aria-label="<?php esc_attr_e('Toggle search', 'aqualuxe'); ?>"
                            aria-expanded="false"
                            aria-controls="search-modal"
                            data-modal-trigger="search-modal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                    </button>

                    <!-- Dark Mode Toggle -->
                    <button type="button" 
                            class="dark-mode-toggle p-2 text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-md"
                            aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>"
                            aria-pressed="false"
                            data-dark-mode-toggle>
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <span class="sr-only dark:hidden"><?php esc_html_e('Enable dark mode', 'aqualuxe'); ?></span>
                        <span class="sr-only hidden dark:block"><?php esc_html_e('Disable dark mode', 'aqualuxe'); ?></span>
                    </button>

                    <!-- WooCommerce Cart -->
                    <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
                           class="cart-toggle relative p-2 text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-md"
                           aria-label="<?php esc_attr_e('View shopping cart', 'aqualuxe'); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                            </svg>
                            <?php
                            $cart_count = WC()->cart->get_cart_contents_count();
                            if ($cart_count > 0) :
                            ?>
                                <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" aria-hidden="true">
                                    <?php echo esc_html($cart_count); ?>
                                </span>
                                <span class="sr-only"><?php printf(esc_html__('%d items in cart', 'aqualuxe'), $cart_count); ?></span>
                            <?php else : ?>
                                <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden" aria-hidden="true">0</span>
                                <span class="sr-only"><?php esc_html_e('Cart is empty', 'aqualuxe'); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button type="button" 
                            class="mobile-menu-toggle lg:hidden p-2 text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-md"
                            aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>"
                            aria-expanded="false"
                            aria-controls="mobile-navigation"
                            data-mobile-menu-toggle>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path class="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path class="close-icon hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="sr-only menu-text"><?php esc_html_e('Open menu', 'aqualuxe'); ?></span>
                        <span class="sr-only close-text hidden"><?php esc_html_e('Close menu', 'aqualuxe'); ?></span>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <nav id="mobile-navigation" class="mobile-navigation lg:hidden hidden" aria-label="<?php esc_attr_e('Mobile Navigation', 'aqualuxe'); ?>" role="navigation">
                <div class="mobile-menu-wrapper bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 pb-4">
                    <?php
                    if (has_nav_menu('mobile')) {
                        wp_nav_menu(array(
                            'theme_location' => 'mobile',
                            'menu_id'        => 'mobile-menu',
                            'menu_class'     => 'mobile-menu space-y-2 pt-4',
                            'container'      => false,
                            'depth'          => 1,
                        ));
                    } elseif (has_nav_menu('primary')) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'mobile-menu-fallback',
                            'menu_class'     => 'mobile-menu space-y-2 pt-4',
                            'container'      => false,
                            'depth'          => 1,
                        ));
                    }
                    ?>
                </div>
            </nav>
        </div>
    </header>

    <!-- Search Modal -->
    <div id="search-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" role="dialog" aria-modal="true" aria-labelledby="search-modal-title">
        <div class="modal-content bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4" role="document">
            <div class="modal-header flex items-center justify-between mb-4">
                <h3 id="search-modal-title" class="text-lg font-semibold text-gray-900 dark:text-white">
                    <?php esc_html_e('Search', 'aqualuxe'); ?>
                </h3>
                <button type="button" class="modal-close text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-md" aria-label="<?php esc_attr_e('Close search', 'aqualuxe'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>

    <main id="main" class="site-main flex-1" role="main"><?php
    // Content will be added by individual templates