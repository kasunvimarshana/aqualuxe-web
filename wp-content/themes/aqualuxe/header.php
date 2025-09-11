<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo aqualuxe_is_dark_mode() ? 'dark' : ''; ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
    <?php wp_body_open(); ?>
    
    <!-- Skip link for accessibility -->
    <a class="skip-link visually-hidden" href="#main">
        <?php esc_html_e('Skip to main content', 'aqualuxe'); ?>
    </a>
    
    <div id="page" class="site">
        
        <header id="masthead" class="site-header" role="banner">
            
            <!-- Top bar -->
            <?php if (get_theme_mod('aqualuxe_show_topbar', true)) : ?>
                <div class="top-bar bg-ocean-800 text-white py-2">
                    <div class="container mx-auto px-4">
                        <div class="flex justify-between items-center text-sm">
                            
                            <div class="top-bar-left">
                                <?php
                                $contact_info = get_theme_mod('aqualuxe_contact_info', '');
                                if ($contact_info) {
                                    echo wp_kses_post($contact_info);
                                }
                                ?>
                            </div>
                            
                            <div class="top-bar-right flex items-center space-x-4">
                                
                                <!-- Language switcher -->
                                <?php if (function_exists('pll_the_languages')) : ?>
                                    <div class="language-switcher">
                                        <?php
                                        pll_the_languages(array(
                                            'show_flags' => 1,
                                            'show_names' => 0,
                                            'display_names_as' => 'slug'
                                        ));
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Dark mode toggle -->
                                <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </button>
                                
                                <!-- Search toggle -->
                                <button id="search-toggle" class="search-toggle" aria-label="<?php esc_attr_e('Toggle search', 'aqualuxe'); ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Main header -->
            <div class="main-header bg-white dark:bg-ocean-900 shadow-md">
                <div class="container mx-auto px-4">
                    <div class="flex justify-between items-center py-4">
                        
                        <!-- Logo -->
                        <div class="site-branding">
                            <?php
                            $custom_logo_id = get_theme_mod('custom_logo');
                            if ($custom_logo_id) {
                                the_custom_logo();
                            } else {
                                if (is_front_page() && is_home()) : ?>
                                    <h1 class="site-title text-2xl font-heading font-bold">
                                        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-aqua-600 hover:text-aqua-800">
                                            <?php bloginfo('name'); ?>
                                        </a>
                                    </h1>
                                <?php else : ?>
                                    <p class="site-title text-2xl font-heading font-bold">
                                        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-aqua-600 hover:text-aqua-800">
                                            <?php bloginfo('name'); ?>
                                        </a>
                                    </p>
                                <?php endif;
                                
                                $description = get_bloginfo('description', 'display');
                                if ($description || is_customize_preview()) : ?>
                                    <p class="site-description text-sm text-gray-600 mt-1"><?php echo $description; ?></p>
                                <?php endif;
                            }
                            ?>
                        </div>
                        
                        <!-- Primary Navigation -->
                        <nav id="site-navigation" class="main-navigation hidden lg:block" role="navigation" aria-label="<?php esc_attr_e('Primary menu', 'aqualuxe'); ?>">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'menu_class'     => 'primary-menu flex space-x-8',
                                'container'      => false,
                                'depth'          => 3,
                                'walker'         => new AquaLuxe_Nav_Walker(),
                                'fallback_cb'    => 'aqualuxe_default_menu',
                            ));
                            ?>
                        </nav>
                        
                        <!-- Header actions -->
                        <div class="header-actions flex items-center space-x-4">
                            
                            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                                <!-- WooCommerce account -->
                                <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="account-link" aria-label="<?php esc_attr_e('My account', 'aqualuxe'); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </a>
                                
                                <!-- WooCommerce cart -->
                                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link relative" aria-label="<?php esc_attr_e('Shopping cart', 'aqualuxe'); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                    </svg>
                                    <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                                        <span class="cart-count absolute -top-2 -right-2 bg-coral-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                            <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                            
                            <!-- Mobile menu toggle -->
                            <button id="mobile-menu-toggle" class="mobile-menu-toggle lg:hidden" aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>" aria-expanded="false">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-navigation" class="mobile-navigation lg:hidden hidden bg-white dark:bg-ocean-900 border-t border-gray-200 dark:border-gray-700">
                <div class="container mx-auto px-4 py-4">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'mobile',
                        'menu_id'        => 'mobile-menu',
                        'menu_class'     => 'mobile-menu space-y-2',
                        'container'      => false,
                        'depth'          => 2,
                        'fallback_cb'    => 'aqualuxe_default_menu',
                    ));
                    ?>
                </div>
            </div>
            
            <!-- Search overlay -->
            <div id="search-overlay" class="search-overlay hidden fixed inset-0 bg-black bg-opacity-50 z-50">
                <div class="search-container flex items-center justify-center min-h-screen p-4">
                    <div class="search-form-wrapper bg-white dark:bg-ocean-900 rounded-lg p-8 w-full max-w-md">
                        <button id="search-close" class="search-close float-right mb-4" aria-label="<?php esc_attr_e('Close search', 'aqualuxe'); ?>">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <div class="clear-both">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </header>
        
        <?php
        // Add hero section on front page
        if (is_front_page()) {
            get_template_part('templates/components/hero');
        }
        
        // Add breadcrumbs on inner pages
        if (!is_front_page() && !is_home()) {
            get_template_part('templates/components/breadcrumbs');
        }
        ?>