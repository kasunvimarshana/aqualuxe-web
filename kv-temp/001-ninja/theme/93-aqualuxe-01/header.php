<?php
/**
 * Header template for AquaLuxe theme
 *
 * Displays the site header with navigation, logo, and accessibility features.
 * Implements semantic HTML5, ARIA landmarks, and responsive design.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Preconnect to external domains for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- DNS prefetch for common external resources -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    
    <?php wp_head(); ?>
    
    <!-- Critical CSS for above-the-fold content -->
    <style>
        .header-loading { opacity: 0; }
        .header-loaded { opacity: 1; transition: opacity 0.3s ease; }
    </style>
</head>

<body <?php body_class('antialiased'); ?> itemscope itemtype="https://schema.org/WebPage">

<?php wp_body_open(); ?>

<!-- Skip to content link for accessibility -->
<a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded" href="#main">
    <?php esc_html_e('Skip to main content', 'aqualuxe'); ?>
</a>

<!-- Page wrapper -->
<div id="page" class="site min-h-screen flex flex-col bg-white dark:bg-gray-900 transition-colors duration-300">
    
    <!-- Header -->
    <header id="masthead" class="site-header relative z-40 bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700" role="banner" itemscope itemtype="https://schema.org/WPHeader">
        
        <!-- Top bar (optional - for announcements, language switcher, etc.) -->
        <?php if (has_nav_menu('top-bar') || is_active_sidebar('header-top')) : ?>
            <div class="header-top bg-blue-600 text-white py-2">
                <div class="container mx-auto px-4">
                    <div class="flex justify-between items-center text-sm">
                        
                        <?php if (has_nav_menu('top-bar')) : ?>
                            <nav class="top-bar-navigation" aria-label="<?php esc_attr_e('Top bar menu', 'aqualuxe'); ?>">
                                <?php
                                wp_nav_menu([
                                    'theme_location' => 'top-bar',
                                    'menu_class' => 'flex space-x-4',
                                    'container' => false,
                                    'depth' => 1,
                                    'fallback_cb' => false
                                ]);
                                ?>
                            </nav>
                        <?php endif; ?>
                        
                        <div class="header-top-widgets">
                            <?php if (is_active_sidebar('header-top')) : ?>
                                <?php dynamic_sidebar('header-top'); ?>
                            <?php endif; ?>
                            
                            <!-- Language switcher placeholder -->
                            <div class="language-switcher">
                                <!-- Will be populated by multilingual module -->
                            </div>
                            
                            <!-- Dark mode toggle -->
                            <button 
                                class="dark-mode-toggle ml-4 p-1 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>"
                                data-toggle="dark-mode"
                            >
                                <svg class="w-4 h-4 dark-mode-icon-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <svg class="w-4 h-4 dark-mode-icon-dark hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Main header -->
        <div class="header-main py-4">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between">
                    
                    <!-- Site branding -->
                    <div class="site-branding flex items-center">
                        <?php if (has_custom_logo()) : ?>
                            <div class="site-logo mr-4">
                                <?php 
                                $custom_logo_id = get_theme_mod('custom_logo');
                                $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                                if ($logo) :
                                ?>
                                    <a href="<?php echo esc_url(home_url('/')); ?>" class="block" rel="home" itemprop="url">
                                        <img 
                                            src="<?php echo esc_url($logo[0]); ?>" 
                                            alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                                            class="h-12 w-auto"
                                            width="<?php echo esc_attr($logo[1]); ?>"
                                            height="<?php echo esc_attr($logo[2]); ?>"
                                            itemprop="logo"
                                        >
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="site-title-desc">
                            <?php if (is_front_page() && is_home()) : ?>
                                <h1 class="site-title text-2xl font-bold text-gray-900 dark:text-white" itemprop="name">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </h1>
                            <?php else : ?>
                                <p class="site-title text-2xl font-bold text-gray-900 dark:text-white" itemprop="name">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                            
                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) :
                            ?>
                                <p class="site-description text-sm text-gray-600 dark:text-gray-400 mt-1" itemprop="description">
                                    <?php echo $description; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Primary navigation -->
                    <nav id="site-navigation" class="main-navigation hidden lg:block" role="navigation" aria-label="<?php esc_attr_e('Primary menu', 'aqualuxe'); ?>" itemscope itemtype="https://schema.org/SiteNavigationElement">
                        <?php if (has_nav_menu('primary')) : ?>
                            <?php
                            wp_nav_menu([
                                'theme_location' => 'primary',
                                'menu_id' => 'primary-menu',
                                'menu_class' => 'primary-menu flex space-x-8',
                                'container' => false,
                                'depth' => 3,
                                'walker' => new AquaLuxe_Walker_Nav_Menu()
                            ]);
                            ?>
                        <?php else : ?>
                            <div class="no-menu text-gray-600 dark:text-gray-400">
                                <?php if (current_user_can('manage_options')) : ?>
                                    <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" class="text-blue-600 hover:text-blue-700">
                                        <?php esc_html_e('Create a menu', 'aqualuxe'); ?>
                                    </a>
                                <?php else : ?>
                                    <span><?php esc_html_e('Menu not configured', 'aqualuxe'); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </nav>
                    
                    <!-- Header utilities -->
                    <div class="header-utilities flex items-center space-x-4">
                        
                        <!-- Search toggle -->
                        <button 
                            class="search-toggle p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            aria-label="<?php esc_attr_e('Toggle search', 'aqualuxe'); ?>"
                            aria-expanded="false"
                            aria-controls="header-search"
                            data-toggle="search"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        
                        <!-- WooCommerce cart (if active) -->
                        <?php if (aqualuxe_is_woocommerce_active()) : ?>
                            <div class="cart-toggle">
                                <a 
                                    href="<?php echo esc_url(wc_get_cart_url()); ?>" 
                                    class="cart-link relative p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    aria-label="<?php esc_attr_e('View shopping cart', 'aqualuxe'); ?>"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 11-4 0v-6m4 0V9a2 2 0 10-4 0v4.01"></path>
                                    </svg>
                                    <span class="cart-count absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Account menu -->
                        <?php if (aqualuxe_is_woocommerce_active() && get_option('woocommerce_myaccount_page_id')) : ?>
                            <div class="account-menu">
                                <a 
                                    href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" 
                                    class="account-link p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    aria-label="<?php esc_attr_e('My account', 'aqualuxe'); ?>"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Mobile menu toggle -->
                        <button 
                            class="mobile-menu-toggle lg:hidden p-2 rounded-md text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>"
                            aria-expanded="false"
                            aria-controls="mobile-menu"
                            data-toggle="mobile-menu"
                        >
                            <svg class="w-6 h-6 hamburger-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg class="w-6 h-6 close-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        
                    </div>
                    
                </div>
            </div>
        </div>
        
        <!-- Search overlay -->
        <div id="header-search" class="search-overlay hidden absolute top-full left-0 right-0 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 shadow-lg z-30">
            <div class="container mx-auto px-4 py-6">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <label class="sr-only" for="header-search-input"><?php esc_html_e('Search for:', 'aqualuxe'); ?></label>
                    <div class="relative">
                        <input 
                            type="search" 
                            id="header-search-input"
                            class="search-field w-full px-4 py-3 pr-12 text-lg border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            placeholder="<?php esc_attr_e('Search...', 'aqualuxe'); ?>" 
                            value="<?php echo get_search_query(); ?>" 
                            name="s"
                            autocomplete="off"
                        >
                        <button 
                            type="submit" 
                            class="search-submit absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                            aria-label="<?php esc_attr_e('Submit search', 'aqualuxe'); ?>"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="mobile-menu lg:hidden hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
            <nav class="mobile-navigation py-4" aria-label="<?php esc_attr_e('Mobile menu', 'aqualuxe'); ?>">
                <div class="container mx-auto px-4">
                    <?php if (has_nav_menu('mobile') || has_nav_menu('primary')) : ?>
                        <?php
                        wp_nav_menu([
                            'theme_location' => has_nav_menu('mobile') ? 'mobile' : 'primary',
                            'menu_class' => 'mobile-menu-list space-y-2',
                            'container' => false,
                            'depth' => 2,
                            'walker' => new AquaLuxe_Walker_Mobile_Nav_Menu()
                        ]);
                        ?>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
        
    </header><!-- #masthead -->
    
    <?php
    /**
     * Hook for adding content after header
     * Used by modules like page builder, breadcrumbs, etc.
     */
    do_action('aqualuxe_after_header');
    ?>
