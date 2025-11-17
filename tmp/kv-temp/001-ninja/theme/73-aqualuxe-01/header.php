<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="<?php echo esc_attr(get_theme_mod('aqualuxe_primary_color', '#14b8a6')); ?>">
    
    <?php if (get_theme_mod('aqualuxe_seo_enabled', true)): ?>
        <!-- SEO Meta Tags -->
        <meta name="description" content="<?php echo esc_attr(aqualuxe_get_meta_description()); ?>">
        <meta name="keywords" content="<?php echo esc_attr(aqualuxe_get_meta_keywords()); ?>">
        <meta name="author" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        
        <!-- Open Graph -->
        <meta property="og:title" content="<?php echo esc_attr(aqualuxe_get_og_title()); ?>">
        <meta property="og:description" content="<?php echo esc_attr(aqualuxe_get_meta_description()); ?>">
        <meta property="og:type" content="<?php echo esc_attr(aqualuxe_get_og_type()); ?>">
        <meta property="og:url" content="<?php echo esc_url(aqualuxe_get_canonical_url()); ?>">
        <meta property="og:image" content="<?php echo esc_url(aqualuxe_get_og_image()); ?>">
        <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <meta property="og:locale" content="<?php echo esc_attr(get_locale()); ?>">
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo esc_attr(aqualuxe_get_og_title()); ?>">
        <meta name="twitter:description" content="<?php echo esc_attr(aqualuxe_get_meta_description()); ?>">
        <meta name="twitter:image" content="<?php echo esc_url(aqualuxe_get_og_image()); ?>">
        
        <!-- Canonical URL -->
        <link rel="canonical" href="<?php echo esc_url(aqualuxe_get_canonical_url()); ?>">
    <?php endif; ?>
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    <?php echo wp_json_encode(aqualuxe_get_schema_markup()); ?>
    </script>
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- DNS prefetch for performance -->
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <link rel="dns-prefetch" href="//www.googletagmanager.com">
    
    <!-- Favicon and Apple Touch Icons -->
    <link rel="icon" type="image/x-icon" href="<?php echo esc_url(get_site_icon_url(32)); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_site_icon_url(180)); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(get_site_icon_url(32)); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url(get_site_icon_url(16)); ?>">
    
    <?php wp_head(); ?>
    
    <!-- Critical CSS inline for above-the-fold content -->
    <style>
        /* Critical CSS - Loaded inline for performance */
        .loading { opacity: 0; }
        .loaded { opacity: 1; transition: opacity 0.3s ease; }
        
        /* Header critical styles */
        .site-header {
            position: relative;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        /* Hero section critical styles */
        .hero-section {
            min-height: 60vh;
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            position: relative;
            overflow: hidden;
        }
        
        /* Dark mode critical styles */
        .dark .site-header {
            background: rgba(15, 23, 42, 0.95);
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(20, 184, 166, 0.3);
            border-radius: 50%;
            border-top-color: #14b8a6;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body <?php body_class('loading'); ?> itemscope itemtype="https://schema.org/WebPage">
    <!-- Skip link for accessibility -->
    <a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary-600 text-white px-4 py-2 rounded z-50" href="#main">
        <?php esc_html_e('Skip to content', 'aqualuxe'); ?>
    </a>
    
    <!-- Site wrapper -->
    <div id="page" class="site min-h-screen flex flex-col">
        
        <!-- Header -->
        <header id="masthead" class="site-header sticky top-0 z-40 transition-all duration-300" role="banner" itemscope itemtype="https://schema.org/WPHeader">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between py-4">
                    
                    <!-- Logo/Site Title -->
                    <div class="site-branding flex items-center space-x-3" itemscope itemtype="https://schema.org/Organization">
                        <?php if (has_custom_logo()): ?>
                            <div class="custom-logo">
                                <?php the_custom_logo(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-logo">
                                <h1 class="site-title text-2xl md:text-3xl font-display font-bold text-primary-600 dark:text-primary-400">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" itemprop="url">
                                        <span itemprop="name"><?php bloginfo('name'); ?></span>
                                    </a>
                                </h1>
                                <?php
                                $description = get_bloginfo('description', 'display');
                                if ($description || is_customize_preview()): ?>
                                    <p class="site-description text-sm text-secondary-600 dark:text-secondary-400 italic" itemprop="description">
                                        <?php echo esc_html($description); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Primary Navigation -->
                    <nav id="site-navigation" class="main-navigation hidden lg:block" role="navigation" aria-label="<?php esc_attr_e('Primary menu', 'aqualuxe'); ?>" itemscope itemtype="https://schema.org/SiteNavigationElement">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_id' => 'primary-menu',
                            'menu_class' => 'menu flex items-center space-x-8',
                            'container' => false,
                            'fallback_cb' => 'aqualuxe_default_menu',
                        ]);
                        ?>
                    </nav>
                    
                    <!-- Header Actions -->
                    <div class="header-actions flex items-center space-x-4">
                        
                        <!-- Search Toggle -->
                        <button type="button" 
                                class="search-toggle btn-icon p-2 rounded-full hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors" 
                                aria-label="<?php esc_attr_e('Open search', 'aqualuxe'); ?>"
                                data-search-toggle>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        
                        <!-- Dark Mode Toggle -->
                        <?php if (get_theme_mod('aqualuxe_dark_mode_enabled', true)): ?>
                            <button type="button" 
                                    class="dark-mode-toggle btn-icon p-2 rounded-full hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors" 
                                    aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>"
                                    data-dark-mode-toggle>
                                <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </button>
                        <?php endif; ?>
                        
                        <!-- Language Switcher -->
                        <?php if (function_exists('aqualuxe_language_switcher')): ?>
                            <div class="language-switcher">
                                <?php aqualuxe_language_switcher(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- WooCommerce Cart -->
                        <?php if (aqualuxe_is_woocommerce_active()): ?>
                            <div class="header-cart">
                                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
                                   class="cart-link flex items-center space-x-2 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors"
                                   aria-label="<?php esc_attr_e('View cart', 'aqualuxe'); ?>">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.707 2.707a1 1 0 00-.293.707V19a1 1 0 001 1h11a1 1 0 001-1v-2.586a1 1 0 00-.293-.707L15 13H7z"></path>
                                    </svg>
                                    <span class="cart-count bg-accent-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Account Menu -->
                        <div class="account-menu relative">
                            <?php if (is_user_logged_in()): ?>
                                <button type="button" 
                                        class="account-toggle flex items-center space-x-2 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors"
                                        aria-label="<?php esc_attr_e('Account menu', 'aqualuxe'); ?>"
                                        data-account-toggle>
                                    <?php echo get_avatar(get_current_user_id(), 24, '', '', ['class' => 'rounded-full']); ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="account-dropdown absolute right-0 mt-2 w-48 bg-white dark:bg-dark-800 rounded-lg shadow-lg border border-gray-200 dark:border-dark-700 hidden"
                                     data-account-dropdown>
                                    <div class="py-2">
                                        <?php if (aqualuxe_is_woocommerce_active()): ?>
                                            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-dark-700">
                                                <?php esc_html_e('My Account', 'aqualuxe'); ?>
                                            </a>
                                            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>orders/" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-dark-700">
                                                <?php esc_html_e('Orders', 'aqualuxe'); ?>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-dark-700">
                                            <?php esc_html_e('Logout', 'aqualuxe'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <a href="<?php echo esc_url(aqualuxe_is_woocommerce_active() ? wc_get_page_permalink('myaccount') : wp_login_url()); ?>" 
                                   class="login-link flex items-center space-x-2 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="hidden sm:inline"><?php esc_html_e('Login', 'aqualuxe'); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Mobile Menu Toggle -->
                        <button type="button" 
                                class="mobile-menu-toggle lg:hidden p-2 rounded-full hover:bg-gray-100 dark:hover:bg-dark-700 transition-colors" 
                                aria-label="<?php esc_attr_e('Open main menu', 'aqualuxe'); ?>"
                                aria-expanded="false"
                                data-mobile-menu-toggle>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="mobile-menu lg:hidden hidden" data-mobile-menu>
                <div class="container mx-auto px-4 py-4 border-t border-gray-200 dark:border-dark-700">
                    <nav class="mobile-navigation" role="navigation" aria-label="<?php esc_attr_e('Mobile menu', 'aqualuxe'); ?>">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'mobile',
                            'menu_id' => 'mobile-menu',
                            'menu_class' => 'menu flex flex-col space-y-2',
                            'container' => false,
                            'fallback_cb' => 'aqualuxe_default_mobile_menu',
                        ]);
                        ?>
                    </nav>
                </div>
            </div>
            
            <!-- Search Overlay -->
            <div class="search-overlay fixed inset-0 bg-black bg-opacity-50 z-50 hidden" data-search-overlay>
                <div class="search-modal bg-white dark:bg-dark-800 rounded-lg max-w-2xl mx-auto mt-20 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold"><?php esc_html_e('Search', 'aqualuxe'); ?></h3>
                        <button type="button" 
                                class="search-close p-1 rounded hover:bg-gray-100 dark:hover:bg-dark-700"
                                data-search-close>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="relative">
                            <input type="search" 
                                   class="search-field w-full p-3 pr-12 border border-gray-300 dark:border-dark-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                                   placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'aqualuxe'); ?>" 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" 
                                   autocomplete="off">
                            <button type="submit" 
                                    class="search-submit absolute right-3 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-primary-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </header>
        
        <!-- Main Content -->
        <main id="main" class="site-main flex-1" role="main" itemprop="mainContentOfPage">
