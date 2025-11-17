<?php
/**
 * The header for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Theme color for mobile browsers -->
    <meta name="theme-color" content="#14b8a6">
    
    <!-- Apple touch icon -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    
    <!-- Microsoft tile -->
    <meta name="msapplication-TileColor" content="#14b8a6">
    
    <!-- Preconnect to external resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- DNS prefetch for performance -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    
    <?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php endif; ?>
    
    <!-- Schema.org markup for website -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "<?php bloginfo( 'name' ); ?>",
        "url": "<?php echo esc_url( home_url( '/' ) ); ?>",
        "description": "<?php bloginfo( 'description' ); ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php echo esc_url( wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ) ); ?>"
        }
    }
    </script>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
    
    <!-- Skip to content link for accessibility -->
    <a class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 bg-primary-600 text-white px-4 py-2 z-50 transition-all duration-200" href="#main-content">
        <?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?>
    </a>

    <header id="masthead" class="site-header bg-white dark:bg-gray-900 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                
                <!-- Site branding -->
                <div class="site-branding flex items-center">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="custom-logo-container mr-4">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="site-title-container">
                        <?php if ( is_front_page() && is_home() ) : ?>
                            <h1 class="site-title text-2xl font-bold text-gray-900 dark:text-white">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                    <?php bloginfo( 'name' ); ?>
                                </a>
                            </h1>
                        <?php else : ?>
                            <p class="site-title text-2xl font-bold text-gray-900 dark:text-white">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                    <?php bloginfo( 'name' ); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        
                        <?php
                        $description = get_bloginfo( 'description', 'display' );
                        if ( $description || is_customize_preview() ) :
                        ?>
                            <p class="site-description text-sm text-gray-600 dark:text-gray-400 mt-1">
                                <?php echo $description; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav id="site-navigation" class="main-navigation hidden lg:flex items-center space-x-8" aria-label="<?php esc_attr_e( 'Primary Navigation', 'aqualuxe' ); ?>">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'nav-menu flex items-center space-x-8',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'walker'         => new AquaLuxe_Walker_Nav_Menu(),
                    ) );
                    ?>
                </nav>

                <!-- Header actions -->
                <div class="header-actions flex items-center space-x-4">
                    
                    <!-- Search toggle -->
                    <button class="search-toggle p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Search', 'aqualuxe' ); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <!-- Account link -->
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="account-link p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'My Account', 'aqualuxe' ); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>

                        <!-- Wishlist toggle -->
                        <button class="wishlist-toggle p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 relative" aria-label="<?php esc_attr_e( 'Wishlist', 'aqualuxe' ); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="wishlist-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center hidden">0</span>
                        </button>

                        <!-- Cart toggle -->
                        <button class="cart-toggle p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 relative" aria-label="<?php esc_attr_e( 'Shopping Cart', 'aqualuxe' ); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5H21"></path>
                            </svg>
                            <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                <?php echo WC()->cart->get_cart_contents_count(); ?>
                            </span>
                        </button>
                    <?php endif; ?>

                    <!-- Mobile menu toggle -->
                    <button class="mobile-menu-toggle lg:hidden p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Menu', 'aqualuxe' ); ?>" aria-expanded="false">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu fixed inset-0 z-50 bg-white dark:bg-gray-900 transform translate-x-full transition-transform duration-300 lg:hidden">
        <div class="flex flex-col h-full">
            
            <!-- Mobile menu header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="site-branding flex items-center">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="custom-logo-container mr-3">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php endif; ?>
                    <span class="site-title text-lg font-bold text-gray-900 dark:text-white">
                        <?php bloginfo( 'name' ); ?>
                    </span>
                </div>
                <button class="mobile-menu-close p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" aria-label="<?php esc_attr_e( 'Close Menu', 'aqualuxe' ); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile navigation -->
            <nav class="mobile-navigation flex-1 overflow-y-auto p-4" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'aqualuxe' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu',
                    'menu_class'     => 'mobile-nav-menu space-y-2',
                    'container'      => false,
                    'fallback_cb'    => 'aqualuxe_mobile_menu_fallback',
                    'walker'         => new AquaLuxe_Walker_Mobile_Menu(),
                ) );
                ?>
            </nav>

            <!-- Mobile menu footer -->
            <div class="mobile-menu-footer p-4 border-t border-gray-200 dark:border-gray-700">
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <div class="flex justify-center space-x-4">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="btn-outline btn-sm">
                            <?php esc_html_e( 'My Account', 'aqualuxe' ); ?>
                        </a>
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn-primary btn-sm">
                            <?php esc_html_e( 'Cart', 'aqualuxe' ); ?>
                            <span class="ml-1">(<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mobile menu overlay -->
    <div class="mobile-menu-overlay fixed inset-0 z-40 bg-black bg-opacity-50 opacity-0 transition-opacity duration-300 pointer-events-none lg:hidden"></div>

    <!-- Search modal -->
    <div class="search-form-modal fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="search-form-container bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    <?php esc_html_e( 'Search', 'aqualuxe' ); ?>
                </h3>
                <button class="search-close text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <?php get_search_form(); ?>
        </div>
        <div class="search-overlay absolute inset-0"></div>
    </div>

    <div id="content" class="site-content flex-1">