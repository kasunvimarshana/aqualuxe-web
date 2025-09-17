<?php
/**
 * Header template
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
    <a class="skip-link screen-reader-text sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary-600 text-white px-4 py-2 rounded" href="#main">
        <?php esc_html_e('Skip to content', 'aqualuxe'); ?>
    </a>

    <header id="masthead" class="site-header sticky top-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <!-- Logo -->
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        ?>
                        <h1 class="site-title text-2xl font-bold text-primary-800">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="hover:text-primary-600 transition-colors">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                        <?php
                        $description = get_bloginfo('description', 'display');
                        if ($description || is_customize_preview()) :
                            ?>
                            <p class="site-description text-sm text-gray-600"><?php echo $description; ?></p>
                        <?php endif;
                    }
                    ?>
                </div>

                <!-- Primary Navigation -->
                <nav id="site-navigation" class="main-navigation hidden lg:block" aria-label="<?php esc_attr_e('Primary Menu', 'aqualuxe'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'container' => false,
                        'menu_class' => 'flex space-x-8',
                        'fallback_cb' => false,
                    ]);
                    ?>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions flex items-center space-x-4">
                    <!-- Search Toggle -->
                    <button type="button" class="search-toggle p-2 text-gray-600 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e('Toggle search', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <?php if (class_exists('WooCommerce')) : ?>
                        <!-- Cart -->
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-icon relative p-2 text-gray-600 hover:text-primary-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m7.5-5v5a2 2 0 01-2 2H9a2 2 0 01-2-2v-5m7.5 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4h7.5z"></path>
                            </svg>
                            <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                                <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                                </span>
                            <?php endif; ?>
                        </a>

                        <!-- Account -->
                        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="account-icon p-2 text-gray-600 hover:text-primary-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </a>
                    <?php endif; ?>

                    <!-- Dark Mode Toggle -->
                    <button type="button" class="dark-mode-toggle p-2 text-gray-600 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </button>

                    <!-- Mobile Menu Toggle -->
                    <button type="button" class="mobile-menu-toggle lg:hidden p-2 text-gray-600 hover:text-primary-600 transition-colors" aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Search Form -->
            <div class="search-form-container hidden absolute top-full left-0 w-full bg-white shadow-lg border-t z-40">
                <div class="container mx-auto px-4 py-6">
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu hidden lg:hidden fixed inset-0 top-16 bg-white z-40 overflow-y-auto">
            <nav class="mobile-navigation p-4">
                <?php
                wp_nav_menu([
                    'theme_location' => 'mobile',
                    'menu_id' => 'mobile-menu',
                    'container' => false,
                    'menu_class' => 'space-y-4',
                    'fallback_cb' => false,
                ]);
                ?>
            </nav>
        </div>
    </header>