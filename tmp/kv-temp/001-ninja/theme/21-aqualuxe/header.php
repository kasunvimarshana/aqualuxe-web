<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

    <header id="masthead" class="site-header bg-gradient-to-r from-blue-900 to-teal-700 text-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center justify-between py-4">
                <div class="site-branding flex items-center">
                    <?php
                    if (has_custom_logo()) :
                        the_custom_logo();
                    else :
                    ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="text-white text-2xl font-bold no-underline hover:text-teal-200 transition-colors">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                    <?php endif; ?>
                </div><!-- .site-branding -->

                <button id="mobile-menu-toggle" class="md:hidden flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white" aria-controls="primary-menu" aria-expanded="false">
                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <title><?php esc_html_e('Menu', 'aqualuxe'); ?></title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
                    </svg>
                </button>

                <nav id="site-navigation" class="main-navigation hidden md:block">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'flex flex-wrap',
                        'fallback_cb'    => false,
                        'walker'         => new AquaLuxe_Walker_Nav_Menu(),
                    ));
                    ?>
                </nav><!-- #site-navigation -->

                <div class="header-actions hidden md:flex items-center space-x-4">
                    <?php if (class_exists('WooCommerce')) : ?>
                        <div class="search-toggle">
                            <button id="search-toggle-btn" class="text-white hover:text-teal-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                            </button>
                        </div>

                        <div class="account-link">
                            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="text-white hover:text-teal-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="screen-reader-text"><?php esc_html_e('My Account', 'aqualuxe'); ?></span>
                            </a>
                        </div>

                        <div class="wishlist-link">
                            <a href="<?php echo esc_url(get_permalink(get_option('aqualuxe_wishlist_page'))); ?>" class="text-white hover:text-teal-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span class="screen-reader-text"><?php esc_html_e('Wishlist', 'aqualuxe'); ?></span>
                            </a>
                        </div>

                        <div class="cart-link relative">
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="text-white hover:text-teal-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="screen-reader-text"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
                                <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
                                    <span class="cart-count absolute -top-2 -right-2 bg-teal-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="theme-mode-toggle">
                        <button id="theme-toggle" class="text-white hover:text-teal-200 transition-colors" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 theme-light-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 theme-dark-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                    </div>

                    <?php if (function_exists('pll_the_languages')) : ?>
                    <div class="language-switcher">
                        <div class="relative inline-block text-left">
                            <button type="button" class="language-dropdown-toggle inline-flex justify-center w-full text-white hover:text-teal-200 transition-colors" id="language-menu-button" aria-expanded="false" aria-haspopup="true">
                                <?php echo esc_html(pll_current_language('name')); ?>
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="language-dropdown-menu hidden origin-top-right absolute right-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1">
                                <div class="py-1" role="none">
                                    <?php
                                    $languages = pll_the_languages(array('raw' => 1));
                                    if ($languages) :
                                        foreach ($languages as $language) :
                                    ?>
                                        <a href="<?php echo esc_url($language['url']); ?>" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1"><?php echo esc_html($language['name']); ?></a>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-blue-800 py-4">
            <div class="container mx-auto px-4">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu-items',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu-items',
                    'fallback_cb'    => false,
                ));
                ?>
                
                <?php if (class_exists('WooCommerce')) : ?>
                <div class="mobile-actions flex justify-around mt-4 pt-4 border-t border-blue-700">
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="text-white flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-xs mt-1"><?php esc_html_e('Account', 'aqualuxe'); ?></span>
                    </a>
                    
                    <a href="<?php echo esc_url(get_permalink(get_option('aqualuxe_wishlist_page'))); ?>" class="text-white flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="text-xs mt-1"><?php esc_html_e('Wishlist', 'aqualuxe'); ?></span>
                    </a>
                    
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="text-white flex flex-col items-center relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="text-xs mt-1"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
                        <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
                            <span class="cart-count absolute -top-2 right-0 bg-teal-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    
                    <button id="mobile-theme-toggle" class="text-white flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mobile-theme-light-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mobile-theme-dark-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <span class="text-xs mt-1"><?php esc_html_e('Theme', 'aqualuxe'); ?></span>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Search Overlay -->
        <div id="search-overlay" class="fixed inset-0 bg-blue-900 bg-opacity-95 z-50 hidden flex items-center justify-center">
            <div class="container mx-auto px-4">
                <button id="search-close" class="absolute top-6 right-6 text-white hover:text-teal-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="search-form-container max-w-3xl mx-auto">
                    <h2 class="text-white text-2xl mb-6 text-center"><?php esc_html_e('Search AquaLuxe', 'aqualuxe'); ?></h2>
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
    </header><!-- #masthead -->

    <?php if (is_front_page() && !is_home()) : ?>
        <?php get_template_part('templates/parts/hero'); ?>
    <?php elseif (!is_front_page()) : ?>
        <?php get_template_part('templates/parts/page-header'); ?>
    <?php endif; ?>

    <div id="content" class="site-content">