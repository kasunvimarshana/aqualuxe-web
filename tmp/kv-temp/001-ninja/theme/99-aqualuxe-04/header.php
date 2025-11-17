<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" data-theme="light">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Preload critical assets -->
    <?php do_action('aqualuxe_preload_assets'); ?>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('antialiased'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col bg-white dark:bg-gray-900 transition-colors duration-300">
    <a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-6 bg-primary-600 text-white px-4 py-2 rounded-md z-50 transition-all" href="#primary">
        <?php esc_html_e('Skip to content', 'aqualuxe'); ?>
    </a>

    <header id="masthead" class="site-header bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40 transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Site Branding -->
                <div class="site-branding flex items-center">
                    <?php if (has_custom_logo()) : ?>
                        <div class="custom-logo-wrapper">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else : ?>
                        <div class="site-identity">
                            <h1 class="site-title text-2xl lg:text-3xl font-bold font-serif m-0">
                                <a href="<?php echo esc_url(home_url('/')); ?>" 
                                   class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 no-underline transition-colors duration-200" 
                                   rel="home">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) :
                            ?>
                                <p class="site-description text-sm text-gray-600 dark:text-gray-400 mt-1 hidden md:block">
                                    <?php echo esc_html($description); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Desktop Navigation -->
                <nav id="site-navigation" class="main-navigation hidden lg:block" aria-label="<?php esc_attr_e('Main Menu', 'aqualuxe'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'container' => false,
                        'menu_class' => 'primary-menu flex items-center space-x-8 list-none m-0 p-0',
                        'link_before' => '<span class="relative inline-block">',
                        'link_after' => '</span>',
                        'fallback_cb' => false,
                    ));
                    ?>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button id="dark-mode-toggle" 
                            class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors"
                            aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>

                    <!-- Search Toggle -->
                    <button id="search-toggle" 
                            class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors lg:hidden"
                            aria-label="<?php esc_attr_e('Open search', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <?php if (aqualuxe_is_woocommerce_active()) : ?>
                        <!-- WooCommerce Header Elements -->
                        <div class="wc-header-elements flex items-center space-x-3">
                            <!-- Search Form (Desktop) -->
                            <div class="hidden lg:block">
                                <?php get_search_form(); ?>
                            </div>

                            <!-- Account Link -->
                            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" 
                               class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors"
                               aria-label="<?php esc_attr_e('My Account', 'aqualuxe'); ?>">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </a>

                            <!-- Cart Link -->
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
                               class="relative p-2 rounded-lg bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors"
                               aria-label="<?php esc_attr_e('View cart', 'aqualuxe'); ?>">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293a1 1 0 00.707 1.707L7 13M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6"></path>
                                </svg>
                                <?php 
                                $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
                                if ($cart_count > 0) :
                                ?>
                                    <span class="absolute -top-1 -right-1 bg-accent-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-medium">
                                        <?php echo esc_html($cart_count); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button class="menu-toggle lg:hidden p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors" 
                            aria-controls="mobile-menu" 
                            aria-expanded="false"
                            aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>">
                        <div class="hamburger-lines">
                            <span class="line line-1 block w-6 h-0.5 bg-current transition-all duration-300"></span>
                            <span class="line line-2 block w-6 h-0.5 bg-current mt-1.5 transition-all duration-300"></span>
                            <span class="line line-3 block w-6 h-0.5 bg-current mt-1.5 transition-all duration-300"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div id="mobile-search" class="mobile-search-bar hidden lg:hidden border-t border-gray-200 dark:border-gray-700 px-4 py-3">
            <?php get_search_form(); ?>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu-overlay" class="mobile-menu-overlay fixed inset-0 bg-black/50 z-50 opacity-0 invisible lg:hidden transition-all duration-300">
            <div class="mobile-menu-content fixed top-0 right-0 w-80 max-w-full h-full bg-white dark:bg-gray-900 shadow-xl transform translate-x-full transition-transform duration-300 overflow-y-auto">
                <div class="mobile-menu-header flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        <?php esc_html_e('Menu', 'aqualuxe'); ?>
                    </h2>
                    <button class="mobile-menu-close p-2 -mr-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                            aria-label="<?php esc_attr_e('Close menu', 'aqualuxe'); ?>">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <nav class="mobile-navigation p-6">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'mobile',
                        'fallback_cb' => function() {
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'container' => false,
                                'menu_class' => 'mobile-menu space-y-1',
                                'link_before' => '<span class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">',
                                'link_after' => '</span>',
                            ));
                        },
                        'container' => false,
                        'menu_class' => 'mobile-menu space-y-1',
                        'link_before' => '<span class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">',
                        'link_after' => '</span>',
                    ));
                    ?>
                </nav>

                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                    <div class="mobile-wc-actions p-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="space-y-3">
                            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" 
                               class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <?php esc_html_e('My Account', 'aqualuxe'); ?>
                            </a>
                            
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
                               class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293a1 1 0 00.707 1.707L7 13M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6"></path>
                                </svg>
                                <?php esc_html_e('Cart', 'aqualuxe'); ?>
                                <?php if ($cart_count > 0) : ?>
                                    <span class="ml-auto bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 px-2 py-1 rounded-full text-xs font-medium">
                                        <?php echo esc_html($cart_count); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main id="primary" class="site-main flex-1"><?php // Note: closing tag is in footer.php ?>