<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class('dark-mode-transition'); ?>>
    <?php wp_body_open(); ?>
    
    <!-- Skip links for accessibility -->
    <a class="skip-link screen-reader-text" href="#main">
        <?php esc_html_e('Skip to content', 'aqualuxe'); ?>
    </a>
    
    <div id="page" class="site min-h-screen flex flex-col">
        
        <header id="masthead" class="site-header sticky top-0 z-50" role="banner">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-16 lg:h-20">
                    
                    <!-- Site branding -->
                    <div class="site-branding flex items-center">
                        <?php
                        $custom_logo_id = get_theme_mod('custom_logo');
                        if ($custom_logo_id) :
                            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                            if ($logo) :
                        ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
                                <img src="<?php echo esc_url($logo[0]); ?>" 
                                     alt="<?php bloginfo('name'); ?>"
                                     class="h-8 lg:h-10 w-auto">
                                <span class="screen-reader-text"><?php bloginfo('name'); ?></span>
                            </a>
                        <?php 
                            endif;
                        else : 
                        ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title text-xl lg:text-2xl font-bold text-primary-600" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php
                        $description = get_bloginfo('description', 'display');
                        if ($description || is_customize_preview()) :
                        ?>
                            <p class="site-description screen-reader-text"><?php echo esc_html($description); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Primary navigation -->
                    <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary menu', 'aqualuxe'); ?>">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'flex items-center space-x-8',
                            'container'      => false,
                            'depth'          => 2,
                            'fallback_cb'    => false,
                        ));
                        ?>
                    </nav>
                    
                    <!-- Header utilities -->
                    <div class="header-utilities flex items-center space-x-4">
                        
                        <!-- Dark mode toggle -->
                        <button type="button" 
                                class="dark-mode-toggle btn-ghost btn-sm" 
                                aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>"
                                data-toggle="dark-mode">
                            <span class="dark-mode-icon-light" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </span>
                            <span class="dark-mode-icon-dark hidden" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                            </span>
                        </button>
                        
                        <!-- Search toggle -->
                        <button type="button" 
                                class="search-toggle btn-ghost btn-sm lg:hidden" 
                                aria-label="<?php esc_attr_e('Toggle search', 'aqualuxe'); ?>"
                                data-toggle="#search-modal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        
                        <!-- WooCommerce cart -->
                        <?php if (class_exists('WooCommerce')) : ?>
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link btn-ghost btn-sm relative">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v5a2 2 0 01-2 2H9a2 2 0 01-2-2v-5m6-5V8a2 2 0 00-2-2H9a2 2 0 00-2 2v5"></path>
                                </svg>
                                <?php
                                $cart_count = WC()->cart->get_cart_contents_count();
                                if ($cart_count > 0) :
                                ?>
                                    <span class="cart-count absolute -top-2 -right-2 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        <?php echo esc_html($cart_count); ?>
                                    </span>
                                <?php endif; ?>
                                <span class="screen-reader-text"><?php esc_html_e('View cart', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <!-- Mobile menu toggle -->
                        <button type="button" 
                                class="mobile-menu-toggle lg:hidden" 
                                aria-controls="mobile-menu"
                                aria-expanded="false"
                                aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>">
                            <span class="mobile-menu-icon" aria-hidden="true">
                                <span class="block w-5 h-0.5 bg-current mb-1"></span>
                                <span class="block w-5 h-0.5 bg-current mb-1"></span>
                                <span class="block w-5 h-0.5 bg-current"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="mobile-menu lg:hidden" aria-hidden="true">
                <div class="mobile-menu-overlay fixed inset-0 bg-black bg-opacity-50 z-40"></div>
                <div class="mobile-menu-content fixed top-0 right-0 h-full w-64 bg-white dark:bg-secondary-900 shadow-xl z-50 transform translate-x-full transition-transform duration-300">
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold"><?php esc_html_e('Menu', 'aqualuxe'); ?></h2>
                            <button type="button" 
                                    class="mobile-menu-close btn-ghost btn-sm"
                                    aria-label="<?php esc_attr_e('Close menu', 'aqualuxe'); ?>">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'mobile',
                            'menu_id'        => 'mobile-menu-items',
                            'menu_class'     => 'space-y-2',
                            'container'      => false,
                            'depth'          => 2,
                            'fallback_cb'    => 'aqualuxe_mobile_menu_fallback',
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </header>
        
        <?php
        // Display page header for non-homepage
        if (!is_front_page()) :
            get_template_part('template-parts/header/page-header');
        endif;
        ?>