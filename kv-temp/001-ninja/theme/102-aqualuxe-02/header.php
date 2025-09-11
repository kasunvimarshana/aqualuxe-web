<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

<div id="page" class="site">
    <header id="masthead" class="site-header bg-white dark:bg-gray-900 shadow-md fixed top-0 left-0 right-0 z-50 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16 md:h-20">
                
                <!-- Logo / Site Title -->
                <div class="site-branding flex items-center">
                    <?php the_custom_logo(); ?>
                    
                    <?php if ( is_front_page() && is_home() ) : ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-2xl font-bold text-gray-900 dark:text-white no-underline hover:text-primary-600 transition-colors">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                    <?php else : ?>
                        <p class="site-title mb-0">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-2xl font-bold text-gray-900 dark:text-white no-underline hover:text-primary-600 transition-colors">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    
                    <?php
                    $aqualuxe_description = get_bloginfo( 'description', 'display' );
                    if ( $aqualuxe_description || is_customize_preview() ) :
                    ?>
                        <p class="site-description text-sm text-gray-600 dark:text-gray-400 ml-4 hidden md:block">
                            <?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </p>
                    <?php endif; ?>
                </div><!-- .site-branding -->

                <!-- Desktop Navigation -->
                <nav id="site-navigation" class="main-navigation hidden lg:flex items-center space-x-8" role="navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'aqualuxe' ); ?>">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'nav-menu flex items-center space-x-6',
                            'container'      => false,
                            'depth'          => 3,
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </nav><!-- #site-navigation -->

                <!-- Header Actions -->
                <div class="header-actions flex items-center space-x-4">
                    
                    <!-- Search Toggle -->
                    <button type="button" class="search-toggle p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="<?php esc_attr_e( 'Search', 'aqualuxe' ); ?>">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <!-- Dark Mode Toggle -->
                    <button type="button" class="dark-mode-toggle p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>

                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <!-- WooCommerce Cart -->
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-link p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                            </svg>
                            <?php if ( WC()->cart && WC()->cart->get_cart_contents_count() > 0 ) : ?>
                                <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                                    <?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
                                </span>
                            <?php endif; ?>
                        </a>

                        <!-- Account Link -->
                        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="account-link p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button type="button" class="nav-toggle lg:hidden p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'aqualuxe' ); ?>">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div><!-- .header-actions -->
            </div><!-- .flex -->

            <!-- Mobile Navigation -->
            <nav class="nav-menu lg:hidden hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 py-4" role="navigation" aria-label="<?php esc_attr_e( 'Mobile navigation', 'aqualuxe' ); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'mobile',
                        'menu_id'        => 'mobile-menu',
                        'menu_class'     => 'mobile-nav-menu space-y-2',
                        'container'      => false,
                        'depth'          => 2,
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </nav><!-- .nav-menu -->
        </div><!-- .container -->
    </header><!-- #masthead -->

    <!-- Search Modal -->
    <div id="search-modal" class="search-modal fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="search-container max-w-2xl mx-auto mt-20 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6">
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="search-form">
                    <label for="search-field" class="sr-only"><?php esc_html_e( 'Search for:', 'aqualuxe' ); ?></label>
                    <div class="relative">
                        <input type="search" id="search-field" class="search-field w-full px-4 py-3 pr-12 text-lg border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
                        <button type="submit" class="search-submit absolute right-3 top-1/2 transform -translate-y-1/2 p-2 text-gray-500 hover:text-primary-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                        </button>
                    </div>
                </form>
                <button type="button" class="search-close mt-4 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <?php esc_html_e( 'Close', 'aqualuxe' ); ?>
                </button>
            </div>
        </div>
    </div><!-- #search-modal -->

    <div id="content" class="site-content pt-16 md:pt-20">