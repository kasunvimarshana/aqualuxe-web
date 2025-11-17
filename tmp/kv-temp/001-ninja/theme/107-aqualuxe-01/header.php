<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main"><?php _e('Skip to content', 'aqualuxe'); ?></a>

    <header id="masthead" class="site-header bg-white shadow-md fixed w-full top-0 z-50">
        <div class="header-container">
            <!-- Top Bar -->
            <div class="top-bar bg-primary-900 text-white py-2 hidden lg:block">
                <div class="container mx-auto px-4">
                    <div class="flex justify-between items-center text-sm">
                        <div class="top-bar-left">
                            <span><?php _e('Bringing elegance to aquatic life – globally', 'aqualuxe'); ?></span>
                        </div>
                        <div class="top-bar-right flex items-center space-x-4">
                            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                <a href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>" class="hover:text-primary-200">
                                    <?php _e('My Account', 'aqualuxe'); ?>
                                </a>
                                <a href="<?php echo wc_get_cart_url(); ?>" class="hover:text-primary-200">
                                    <?php _e('Cart', 'aqualuxe'); ?>
                                    <span class="cart-count bg-accent-500 text-white rounded-full px-2 py-1 text-xs ml-1">
                                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                                    </span>
                                </a>
                            <?php endif; ?>
                            
                            <!-- Language Switcher Placeholder -->
                            <div class="language-switcher">
                                <!-- Will be populated by multilingual module -->
                            </div>
                            
                            <!-- Dark Mode Toggle -->
                            <button id="dark-mode-toggle" class="dark-mode-toggle hover:text-primary-200" aria-label="<?php _e('Toggle Dark Mode', 'aqualuxe'); ?>">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path class="dark-mode-sun" d="M10 2L13.09 8.26L20 9L14 14.74L15.18 21.02L10 17.77L4.82 21.02L6 14.74L0 9L6.91 8.26L10 2Z"/>
                                    <path class="dark-mode-moon hidden" d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Header -->
            <div class="main-header py-4">
                <div class="container mx-auto px-4">
                    <div class="flex justify-between items-center">
                        
                        <!-- Site Branding -->
                        <div class="site-branding">
                            <?php if (has_custom_logo()) : ?>
                                <div class="site-logo">
                                    <?php the_custom_logo(); ?>
                                </div>
                            <?php else : ?>
                                <div class="site-title-description">
                                    <h1 class="site-title text-2xl font-bold text-primary-900">
                                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                            <?php bloginfo('name'); ?>
                                        </a>
                                    </h1>
                                    <?php if (get_bloginfo('description')) : ?>
                                        <p class="site-description text-gray-600">
                                            <?php bloginfo('description'); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Main Navigation -->
                        <nav id="site-navigation" class="main-navigation hidden lg:block">
                            <?php
                            wp_nav_menu([
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'menu_class'     => 'nav-menu flex space-x-8',
                                'fallback_cb'    => false,
                            ]);
                            ?>
                        </nav>

                        <!-- Mobile Menu Toggle & Search -->
                        <div class="header-actions flex items-center space-x-4">
                            
                            <!-- Search Toggle -->
                            <button id="search-toggle" class="search-toggle p-2 text-gray-700 hover:text-primary-600" aria-label="<?php _e('Open Search', 'aqualuxe'); ?>">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                <!-- Cart Icon (Mobile) -->
                                <a href="<?php echo wc_get_cart_url(); ?>" class="cart-icon lg:hidden relative p-2 text-gray-700 hover:text-primary-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                    </svg>
                                    <span class="cart-count absolute -top-1 -right-1 bg-accent-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
                                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                                    </span>
                                </a>
                            <?php endif; ?>

                            <!-- Mobile Menu Toggle -->
                            <button id="mobile-menu-toggle" class="mobile-menu-toggle lg:hidden p-2 text-gray-700 hover:text-primary-600" aria-label="<?php _e('Open Menu', 'aqualuxe'); ?>">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Overlay -->
        <div id="search-overlay" class="search-overlay fixed inset-0 bg-black bg-opacity-50 z-60 hidden">
            <div class="search-container bg-white p-8 rounded-lg mx-4 mt-20 max-w-2xl mx-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold"><?php _e('Search', 'aqualuxe'); ?></h3>
                    <button id="search-close" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                <?php get_search_form(); ?>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu lg:hidden fixed inset-0 bg-white z-50 transform translate-x-full transition-transform duration-300">
            <div class="p-4">
                <div class="flex justify-between items-center mb-8">
                    <div class="site-logo">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <span class="font-bold text-xl text-primary-900"><?php bloginfo('name'); ?></span>
                        <?php endif; ?>
                    </div>
                    <button id="mobile-menu-close" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                
                <nav class="mobile-navigation">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'mobile',
                        'menu_id'        => 'mobile-menu-items',
                        'menu_class'     => 'mobile-nav-menu space-y-4',
                        'fallback_cb'    => false,
                    ]);
                    ?>
                </nav>
            </div>
        </div>
    </header>

    <div id="content" class="site-content pt-20 lg:pt-32"><?php // Offset for fixed header ?>