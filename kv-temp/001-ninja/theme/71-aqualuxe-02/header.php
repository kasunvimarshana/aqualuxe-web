<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php echo aqualuxe_schema_markup(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php
    // Preload critical assets
    $asset_manager = Asset_Manager::get_instance();
    $asset_manager->preload_critical_assets();
    
    wp_head();
    ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

    <header id="masthead" class="site-header bg-white dark:bg-gray-900 shadow-sm transition-colors duration-300" role="banner">
        <div class="header-top bg-primary-50 dark:bg-gray-800 py-2 text-sm">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between">
                    <div class="header-contact flex items-center space-x-4 text-gray-600 dark:text-gray-300">
                        <?php if ($phone = get_theme_mod('aqualuxe_contact_phone')) : ?>
                            <a href="tel:<?php echo esc_attr($phone); ?>" class="flex items-center space-x-1 hover:text-primary-600 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                <span><?php echo esc_html($phone); ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($email = get_theme_mod('aqualuxe_contact_email')) : ?>
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="flex items-center space-x-1 hover:text-primary-600 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <span><?php echo esc_html($email); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="header-social flex items-center space-x-3">
                        <?php
                        $social_links = [
                            'facebook' => get_theme_mod('aqualuxe_social_facebook'),
                            'twitter' => get_theme_mod('aqualuxe_social_twitter'),
                            'instagram' => get_theme_mod('aqualuxe_social_instagram'),
                            'linkedin' => get_theme_mod('aqualuxe_social_linkedin'),
                            'youtube' => get_theme_mod('aqualuxe_social_youtube'),
                        ];
                        
                        foreach ($social_links as $platform => $url) :
                            if ($url) :
                        ?>
                            <a href="<?php echo esc_url($url); ?>" 
                               class="text-gray-500 hover:text-primary-600 transition-colors"
                               target="_blank" 
                               rel="noopener noreferrer"
                               aria-label="<?php printf(esc_attr__('Follow us on %s', 'aqualuxe'), ucfirst($platform)); ?>">
                                <?php echo aqualuxe_get_social_icon($platform); ?>
                            </a>
                        <?php
                            endif;
                        endforeach;
                        ?>
                        
                        <?php if (get_theme_mod('aqualuxe_dark_mode_enabled', true)) : ?>
                            <button id="dark-mode-toggle" 
                                    class="text-gray-500 hover:text-primary-600 transition-colors p-1"
                                    aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
                                <svg class="w-4 h-4 dark-mode-sun" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                                </svg>
                                <svg class="w-4 h-4 dark-mode-moon hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="header-main py-4 sticky top-0 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md z-50">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="site-branding">
                        <?php
                        $custom_logo_id = get_theme_mod('custom_logo');
                        if ($custom_logo_id) :
                            $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                        ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                                <img src="<?php echo esc_url($logo_url); ?>" 
                                     alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                                     class="custom-logo h-12 w-auto">
                            </a>
                        <?php else : ?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>" 
                                   class="text-2xl font-bold text-gray-900 dark:text-white hover:text-primary-600 transition-colors">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) :
                            ?>
                                <p class="site-description text-sm text-gray-600 dark:text-gray-300">
                                    <?php echo $description; ?>
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Main Navigation -->
                    <nav id="site-navigation" class="main-navigation hidden lg:block" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'aqualuxe'); ?>">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'flex items-center space-x-8',
                            'container'      => false,
                            'depth'          => 3,
                            'walker'         => new AquaLuxe_Walker_Nav_Menu(),
                            'fallback_cb'    => 'aqualuxe_fallback_menu',
                        ]);
                        ?>
                    </nav>
                    
                    <!-- Header Actions -->
                    <div class="header-actions flex items-center space-x-4">
                        <!-- Search Trigger -->
                        <?php 
                        $search_module = AquaLuxe_Module_Loader::get_instance()->get_module('search');
                        if ($search_module && $search_module->is_enabled()) :
                            echo $search_module->get_search_trigger(['show_shortcut' => false]);
                        endif;
                        ?>
                        
                        <!-- Dark Mode Toggle -->
                        <?php 
                        $dark_mode_module = AquaLuxe_Module_Loader::get_instance()->get_module('dark-mode');
                        if ($dark_mode_module && $dark_mode_module->is_enabled()) :
                            echo $dark_mode_module->get_toggle_button(['show_labels' => false]);
                        endif;
                        ?>
                        
                        <?php if (class_exists('WooCommerce')) : ?>
                            <!-- Wishlist -->
                            <?php 
                            $wishlist_module = AquaLuxe_Module_Loader::get_instance()->get_module('wishlist');
                            if ($wishlist_module && $wishlist_module->is_enabled()) :
                            ?>
                                <a href="<?php echo esc_url(home_url('/wishlist/')); ?>" 
                                   class="wishlist-link relative text-gray-600 dark:text-gray-300 hover:text-primary-600 transition-colors"
                                   title="<?php esc_attr_e('Wishlist', 'aqualuxe'); ?>">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span class="wishlist-count absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">
                                        0
                                    </span>
                                </a>
                            <?php endif; ?>
                            
                            <!-- WooCommerce Cart -->
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
                               class="cart-link relative text-gray-600 dark:text-gray-300 hover:text-primary-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m4.5-5a2.5 2.5 0 105 0 2.5 2.5 0 00-5 0z"></path>
                                </svg>
                                <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                                    <span class="cart-count absolute -top-2 -right-2 bg-primary-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                            
                            <!-- Account -->
                            <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" 
                               class="text-gray-600 dark:text-gray-300 hover:text-primary-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <!-- Mobile Menu Toggle -->
                        <button id="mobile-menu-toggle" 
                                class="lg:hidden text-gray-600 dark:text-gray-300 hover:text-primary-600 transition-colors"
                                aria-label="<?php esc_attr_e('Open menu', 'aqualuxe'); ?>"
                                aria-expanded="false">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu lg:hidden fixed inset-0 z-50 bg-white dark:bg-gray-900 transform -translate-x-full transition-transform duration-300 ease-in-out">
            <div class="mobile-menu-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="mobile-logo">
                    <?php if ($custom_logo_id) : ?>
                        <img src="<?php echo esc_url($logo_url); ?>" 
                             alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                             class="h-8 w-auto">
                    <?php else : ?>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                            <?php bloginfo('name'); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <button id="mobile-menu-close" 
                        class="text-gray-600 dark:text-gray-300 hover:text-primary-600 transition-colors"
                        aria-label="<?php esc_attr_e('Close menu', 'aqualuxe'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <nav class="mobile-navigation p-4" role="navigation" aria-label="<?php esc_attr_e('Mobile Menu', 'aqualuxe'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu-list',
                    'menu_class'     => 'mobile-menu-list space-y-2',
                    'container'      => false,
                    'depth'          => 2,
                    'walker'         => new AquaLuxe_Mobile_Walker_Nav_Menu(),
                    'fallback_cb'    => 'aqualuxe_mobile_fallback_menu',
                ]);
                ?>
            </nav>
        </div>
        
        <!-- Search Modal -->
        <div id="search-modal" class="search-modal fixed inset-0 z-60 bg-black/50 backdrop-blur-sm opacity-0 invisible transition-all duration-300">
            <div class="search-container absolute top-20 left-1/2 transform -translate-x-1/2 w-full max-w-2xl mx-auto px-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="relative">
                            <input type="search" 
                                   id="search-field" 
                                   class="search-field w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                                   placeholder="<?php echo esc_attr_x('Search products, services, or content...', 'placeholder', 'aqualuxe'); ?>" 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" 
                                   autocomplete="off">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="search-submit sr-only">
                            <?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?>
                        </button>
                    </form>
                    
                    <div id="search-results" class="search-results mt-4 hidden">
                        <!-- Search results will be populated via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </header>

    <?php do_action('aqualuxe_after_header'); ?>
