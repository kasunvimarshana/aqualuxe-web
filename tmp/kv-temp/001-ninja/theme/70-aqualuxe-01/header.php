<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link sr-only" href="#main"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

<div id="page" class="site">
    
    <?php if (get_theme_mod('aqualuxe_top_bar', false)) : ?>
    <div class="top-bar bg-primary-600 text-white py-2">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="top-bar-content">
                    <?php echo wp_kses_post(get_theme_mod('aqualuxe_top_bar_content', 'Free shipping on orders over $100')); ?>
                </div>
                <div class="top-bar-actions">
                    <?php if (get_theme_mod('aqualuxe_enable_dark_mode', true)) : ?>
                    <button class="dark-mode-toggle text-white hover:text-accent-400 transition-colors" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>
                    <?php endif; ?>
                    
                    <?php if (function_exists('icl_get_languages')) : ?>
                    <div class="language-switcher ml-4">
                        <?php do_action('aqualuxe_language_switcher'); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <header id="masthead" class="site-header header-<?php echo esc_attr(get_theme_mod('aqualuxe_header_layout', 'standard')); ?> <?php echo get_theme_mod('aqualuxe_sticky_header', true) ? 'sticky' : ''; ?>">
        <div class="container mx-auto px-4">
            <div class="header-main flex justify-between items-center py-4">
                
                <!-- Logo -->
                <div class="site-branding">
                    <?php
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $retina_logo = get_theme_mod('aqualuxe_retina_logo');
                    
                    if ($custom_logo_id) :
                        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                        $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                            <img src="<?php echo esc_url($logo_url); ?>" 
                                 alt="<?php echo esc_attr($logo_alt ?: get_bloginfo('name')); ?>"
                                 class="custom-logo h-12 w-auto"
                                 <?php if ($retina_logo) : ?>
                                 srcset="<?php echo esc_url($logo_url); ?> 1x, <?php echo esc_url($retina_logo); ?> 2x"
                                 <?php endif; ?>>
                        </a>
                        <?php
                    else :
                        ?>
                        <div class="site-title-group">
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="text-2xl font-bold text-primary-600 hover:text-primary-700 transition-colors">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) :
                                ?>
                                <p class="site-description text-sm text-gray-600"><?php echo $description; ?></p>
                            <?php endif; ?>
                        </div>
                        <?php
                    endif;
                    ?>
                </div>

                <!-- Primary Navigation -->
                <nav id="site-navigation" class="main-navigation hidden lg:block" aria-label="<?php esc_attr_e('Primary menu', 'aqualuxe'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'primary-menu flex space-x-8',
                        'container'      => false,
                        'fallback_cb'    => 'aqualuxe_default_menu',
                        'walker'         => new AquaLuxe_Walker_Nav_Menu(),
                    ));
                    ?>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions flex items-center space-x-4">
                    
                    <!-- Search Toggle -->
                    <button class="search-toggle text-gray-600 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e('Open search', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <?php if (class_exists('WooCommerce')) : ?>
                    <!-- WooCommerce Account -->
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="account-link text-gray-600 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e('My account', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </a>

                    <!-- WooCommerce Wishlist -->
                    <?php if (get_theme_mod('aqualuxe_wishlist', true)) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('wishlist')); ?>" class="wishlist-link relative text-gray-600 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e('Wishlist', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="wishlist-count absolute -top-2 -right-2 bg-accent-500 text-black text-xs rounded-full h-4 w-4 flex items-center justify-center">0</span>
                    </a>
                    <?php endif; ?>

                    <!-- WooCommerce Cart -->
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link relative text-gray-600 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e('Shopping cart', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                        </svg>
                        <span class="cart-count absolute -top-2 -right-2 bg-accent-500 text-black text-xs rounded-full h-4 w-4 flex items-center justify-center"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-toggle lg:hidden text-gray-600 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e('Open mobile menu', 'aqualuxe'); ?>">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div class="search-form fixed inset-0 z-50 bg-black bg-opacity-50 hidden">
            <div class="search-container bg-white p-8 max-w-2xl mx-auto mt-20 rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold"><?php esc_html_e('Search', 'aqualuxe'); ?></h3>
                    <button class="search-close text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <?php get_search_form(); ?>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu fixed inset-0 z-50 lg:hidden bg-black bg-opacity-50 hidden">
            <div class="mobile-menu-container bg-white w-80 h-full p-6 overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold"><?php esc_html_e('Menu', 'aqualuxe'); ?></h2>
                    <button class="mobile-menu-close text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <nav class="mobile-navigation" aria-label="<?php esc_attr_e('Mobile menu', 'aqualuxe'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'mobile',
                        'menu_id'        => 'mobile-menu',
                        'menu_class'     => 'mobile-menu-list space-y-2',
                        'container'      => false,
                        'fallback_cb'    => 'aqualuxe_default_mobile_menu',
                        'walker'         => new AquaLuxe_Walker_Mobile_Nav_Menu(),
                    ));
                    ?>
                </nav>

                <?php if (class_exists('WooCommerce')) : ?>
                <div class="mobile-menu-actions mt-8 pt-8 border-t border-gray-200">
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="block py-2 text-gray-700 hover:text-primary-600">
                        <?php esc_html_e('My Account', 'aqualuxe'); ?>
                    </a>
                    <?php if (get_theme_mod('aqualuxe_wishlist', true)) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('wishlist')); ?>" class="block py-2 text-gray-700 hover:text-primary-600">
                        <?php esc_html_e('Wishlist', 'aqualuxe'); ?>
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="block py-2 text-gray-700 hover:text-primary-600">
                        <?php esc_html_e('Cart', 'aqualuxe'); ?> (<span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>)
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main id="main" class="site-main"><?php
        // Note: Closing tag is in footer.php
