<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Page Loader -->
<div class="page-loader">
    <div class="loader-content">
        <div class="loader-wave">
            <svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <circle cx="30" cy="30" r="20" fill="none" stroke="currentColor" stroke-width="2" class="wave-circle" />
                <circle cx="30" cy="30" r="15" fill="none" stroke="currentColor" stroke-width="2" class="wave-circle" />
                <circle cx="30" cy="30" r="10" fill="none" stroke="currentColor" stroke-width="2" class="wave-circle" />
            </svg>
        </div>
        <p class="loader-text">Loading AquaLuxe...</p>
    </div>
</div>

<!-- Skip to Content Link -->
<a class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-500 focus:text-white focus:rounded" href="#main">
    <?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?>
</a>

<!-- Header -->
<header class="site-header" role="banner">
    <!-- Top Bar -->
    <?php if ( aqualuxe_has_top_bar() ) : ?>
    <div class="top-bar bg-gray-900 text-gray-300 py-2 hidden lg:block">
        <div class="container">
            <div class="flex justify-between items-center text-sm">
                <div class="top-bar-left flex items-center space-x-6">
                    <?php if ( $phone = get_theme_mod( 'aqualuxe_contact_phone' ) ) : ?>
                    <a href="tel:<?php echo esc_attr( $phone ); ?>" class="flex items-center space-x-2 hover:text-white transition-colors">
                        <i class="fas fa-phone text-xs" aria-hidden="true"></i>
                        <span><?php echo esc_html( $phone ); ?></span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ( $email = get_theme_mod( 'aqualuxe_contact_email' ) ) : ?>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>" class="flex items-center space-x-2 hover:text-white transition-colors">
                        <i class="fas fa-envelope text-xs" aria-hidden="true"></i>
                        <span><?php echo esc_html( $email ); ?></span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ( $hours = get_theme_mod( 'aqualuxe_business_hours' ) ) : ?>
                    <span class="flex items-center space-x-2">
                        <i class="fas fa-clock text-xs" aria-hidden="true"></i>
                        <span><?php echo esc_html( $hours ); ?></span>
                    </span>
                    <?php endif; ?>
                </div>
                
                <div class="top-bar-right flex items-center space-x-6">
                    <!-- Language Switcher -->
                    <?php aqualuxe_language_switcher(); ?>
                    
                    <!-- Currency Switcher -->
                    <?php aqualuxe_currency_switcher(); ?>
                    
                    <!-- Social Links -->
                    <?php aqualuxe_social_links( 'top-bar' ); ?>
                    
                    <!-- Dark Mode Toggle -->
                    <label class="dark-mode-toggle-wrapper flex items-center space-x-2 cursor-pointer">
                        <span class="sr-only"><?php esc_html_e( 'Toggle dark mode', 'aqualuxe' ); ?></span>
                        <i class="fas fa-sun text-xs" aria-hidden="true"></i>
                        <input type="checkbox" class="dark-mode-toggle sr-only">
                        <div class="toggle-switch w-8 h-4 bg-gray-600 rounded-full relative transition-colors">
                            <div class="toggle-handle w-3 h-3 bg-white rounded-full absolute top-0.5 left-0.5 transition-transform"></div>
                        </div>
                        <i class="fas fa-moon text-xs" aria-hidden="true"></i>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Main Header -->
    <div class="main-header bg-white dark:bg-gray-900 shadow-sm">
        <div class="container">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="site-branding">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="site-logo">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else : ?>
                        <div class="site-identity">
                            <?php if ( is_front_page() && is_home() ) : ?>
                                <h1 class="site-title">
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                        <?php bloginfo( 'name' ); ?>
                                    </a>
                                </h1>
                            <?php else : ?>
                                <p class="site-title">
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                        <?php bloginfo( 'name' ); ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                            
                            <?php $description = get_bloginfo( 'description', 'display' ); ?>
                            <?php if ( $description || is_customize_preview() ) : ?>
                                <p class="site-description"><?php echo $description; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="main-navigation hidden lg:block" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'aqualuxe' ); ?>">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'nav-menu flex items-center space-x-8',
                        'container'      => false,
                        'fallback_cb'    => 'aqualuxe_default_menu',
                        'walker'         => new AquaLuxe_Nav_Walker(),
                    ) );
                    ?>
                </nav>
                
                <!-- Header Actions -->
                <div class="header-actions flex items-center space-x-4">
                    <!-- Search Toggle -->
                    <button class="search-toggle btn-ghost p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="<?php esc_attr_e( 'Open search', 'aqualuxe' ); ?>">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                    
                    <!-- WooCommerce Cart -->
                    <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
                    <div class="cart-toggle relative">
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn-ghost p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative">
                            <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                            <span class="cart-count absolute -top-1 -right-1 bg-primary-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center <?php echo WC()->cart->get_cart_contents_count() > 0 ? '' : 'hidden'; ?>">
                                <?php echo WC()->cart->get_cart_contents_count(); ?>
                            </span>
                            <span class="sr-only"><?php esc_html_e( 'View cart', 'aqualuxe' ); ?></span>
                        </a>
                        
                        <!-- Mini Cart Dropdown -->
                        <div class="mini-cart-dropdown absolute right-0 top-full mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible transform translate-y-2 transition-all duration-200 z-50">
                            <div class="p-4">
                                <?php woocommerce_mini_cart(); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Account/Login -->
                    <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
                    <div class="account-toggle relative">
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn-ghost p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            <span class="sr-only"><?php esc_html_e( 'My account', 'aqualuxe' ); ?></span>
                        </a>
                        
                        <?php if ( ! is_user_logged_in() ) : ?>
                        <!-- Login/Register Dropdown -->
                        <div class="account-dropdown absolute right-0 top-full mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible transform translate-y-2 transition-all duration-200 z-50">
                            <div class="p-4">
                                <div class="space-y-2">
                                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="block w-full btn btn-primary text-center">
                                        <?php esc_html_e( 'Login', 'aqualuxe' ); ?>
                                    </a>
                                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="block w-full btn btn-outline text-center">
                                        <?php esc_html_e( 'Register', 'aqualuxe' ); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-toggle lg:hidden btn-ghost p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="<?php esc_attr_e( 'Open mobile menu', 'aqualuxe' ); ?>" aria-expanded="false">
                        <div class="hamburger-icon w-5 h-5 flex flex-col justify-center space-y-1">
                            <span class="block w-full h-0.5 bg-current transition-transform"></span>
                            <span class="block w-full h-0.5 bg-current transition-transform"></span>
                            <span class="block w-full h-0.5 bg-current transition-transform"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu lg:hidden fixed inset-0 z-50 opacity-0 invisible transition-all duration-300">
        <div class="mobile-menu-overlay absolute inset-0 bg-black/50" aria-hidden="true"></div>
        <div class="mobile-menu-panel absolute right-0 top-0 h-full w-80 max-w-full bg-white dark:bg-gray-900 transform translate-x-full transition-transform duration-300">
            <div class="mobile-menu-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-gray-100"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></h2>
                <button class="mobile-menu-close btn-ghost p-2 rounded-full" aria-label="<?php esc_attr_e( 'Close mobile menu', 'aqualuxe' ); ?>">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
            
            <div class="mobile-menu-content p-4 overflow-y-auto">
                <!-- Mobile Navigation -->
                <nav class="mobile-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'aqualuxe' ); ?>">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'mobile-nav-menu space-y-2',
                        'container'      => false,
                        'fallback_cb'    => 'aqualuxe_default_mobile_menu',
                        'walker'         => new AquaLuxe_Mobile_Nav_Walker(),
                    ) );
                    ?>
                </nav>
                
                <!-- Mobile Actions -->
                <div class="mobile-actions mt-8 pt-8 border-t border-gray-200 dark:border-gray-700 space-y-4">
                    <!-- Search -->
                    <div class="search-form-wrapper">
                        <?php get_search_form(); ?>
                    </div>
                    
                    <!-- Language/Currency Switchers -->
                    <div class="switchers grid grid-cols-2 gap-4">
                        <?php aqualuxe_language_switcher( 'mobile' ); ?>
                        <?php aqualuxe_currency_switcher( 'mobile' ); ?>
                    </div>
                    
                    <!-- Social Links -->
                    <div class="social-links-wrapper">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3"><?php esc_html_e( 'Follow Us', 'aqualuxe' ); ?></h3>
                        <?php aqualuxe_social_links( 'mobile' ); ?>
                    </div>
                    
                    <!-- Contact Info -->
                    <?php if ( aqualuxe_has_contact_info() ) : ?>
                    <div class="contact-info-wrapper">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></h3>
                        <div class="space-y-2 text-sm">
                            <?php if ( $phone = get_theme_mod( 'aqualuxe_contact_phone' ) ) : ?>
                            <a href="tel:<?php echo esc_attr( $phone ); ?>" class="flex items-center space-x-2 text-gray-600 dark:text-gray-300 hover:text-primary-500">
                                <i class="fas fa-phone text-xs" aria-hidden="true"></i>
                                <span><?php echo esc_html( $phone ); ?></span>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ( $email = get_theme_mod( 'aqualuxe_contact_email' ) ) : ?>
                            <a href="mailto:<?php echo esc_attr( $email ); ?>" class="flex items-center space-x-2 text-gray-600 dark:text-gray-300 hover:text-primary-500">
                                <i class="fas fa-envelope text-xs" aria-hidden="true"></i>
                                <span><?php echo esc_html( $email ); ?></span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search Overlay -->
    <div class="search-overlay fixed inset-0 z-50 bg-black/80 opacity-0 invisible transition-all duration-300">
        <div class="search-overlay-content flex items-center justify-center min-h-screen p-4">
            <div class="search-form-container w-full max-w-2xl">
                <form role="search" method="get" class="search-form relative" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="relative">
                        <input type="search" 
                               class="search-field w-full px-6 py-4 pr-16 text-2xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-lg border-0 focus:ring-2 focus:ring-primary-500" 
                               placeholder="<?php esc_attr_e( 'Search...', 'aqualuxe' ); ?>"
                               value="<?php echo get_search_query(); ?>" 
                               name="s" 
                               autocomplete="off">
                        <button type="submit" class="search-submit absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-500 transition-colors">
                            <i class="fas fa-search text-xl" aria-hidden="true"></i>
                            <span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                        </button>
                    </div>
                </form>
                
                <button class="search-overlay-close absolute top-8 right-8 text-white hover:text-gray-300 transition-colors">
                    <i class="fas fa-times text-2xl" aria-hidden="true"></i>
                    <span class="sr-only"><?php esc_html_e( 'Close search', 'aqualuxe' ); ?></span>
                </button>
                
                <!-- Search Suggestions -->
                <div class="search-suggestions mt-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hidden">
                    <div class="suggestions-list"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Progress Bar -->
    <div class="scroll-progress fixed top-0 left-0 h-1 bg-primary-500 z-50 transition-all duration-100" style="width: 0%;"></div>
</header>

<!-- Main Content -->
<main id="main" class="site-main" role="main">
    <?php
    /**
     * Hook: aqualuxe_before_content
     * 
     * @hooked aqualuxe_page_header - 10
     * @hooked aqualuxe_breadcrumbs - 20
     */
    do_action( 'aqualuxe_before_content' );
    ?>
