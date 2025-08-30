<?php
/**
 * The header for the theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

    <header id="masthead" class="site-header" role="banner">
        <div class="container">
            <div class="site-header-inner">
                
                <div class="site-branding">
                    <?php aqualuxe_custom_logo(); ?>
                </div>

                <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'aqualuxe'); ?>">
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                        <span class="screen-reader-text"><?php esc_html_e('Primary Menu', 'aqualuxe'); ?></span>
                        <span class="menu-icon"></span>
                    </button>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'walker'         => new AquaLuxe_Walker_Nav_Menu(),
                    ));
                    ?>
                </nav>

                <?php if (class_exists('WooCommerce')) : ?>
                    <div class="header-actions">
                        
                        <!-- Search -->
                        <div class="header-search">
                            <button class="search-toggle" aria-label="<?php esc_attr_e('Toggle search', 'aqualuxe'); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                            </button>
                            <div class="search-form-container">
                                <?php get_product_search_form(); ?>
                            </div>
                        </div>

                        <!-- User Account -->
                        <div class="header-account">
                            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" 
                               aria-label="<?php esc_attr_e('My Account', 'aqualuxe'); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </a>
                        </div>

                        <!-- Wishlist (if plugin active) -->
                        <?php if (function_exists('YITH_WCWL')) : ?>
                            <div class="header-wishlist">
                                <a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>" 
                                   aria-label="<?php esc_attr_e('Wishlist', 'aqualuxe'); ?>">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                    <span class="wishlist-count"><?php echo esc_html(YITH_WCWL()->count_products()); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Shopping Cart -->
                        <div class="header-cart">
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
                               class="cart-link" 
                               aria-label="<?php esc_attr_e('Shopping Cart', 'aqualuxe'); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 22C9.55228 22 10 21.5523 10 21C10 20.4477 9.55228 20 9 20C8.44772 20 8 20.4477 8 21C8 21.5523 8.44772 22 9 22Z"></path>
                                    <path d="M20 22C20.5523 22 21 21.5523 21 21C21 20.4477 20.5523 20 20 20C19.4477 20 19 20.4477 19 21C19 21.5523 19.4477 22 20 22Z"></path>
                                    <path d="M1 1H5L7.68 14.39C7.77144 14.8504 8.02191 15.264 8.38755 15.5583C8.75318 15.8526 9.2107 16.009 9.68 16H19.4C19.8693 16.009 20.3268 15.8526 20.6925 15.5583C21.0581 15.264 21.3086 14.8504 21.4 14.39L23 6H6"></path>
                                </svg>
                                <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                                <span class="cart-total"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                            </a>
                            
                            <!-- Mini Cart -->
                            <div class="mini-cart-container">
                                <?php woocommerce_mini_cart(); ?>
                            </div>
                        </div>

                    </div>
                <?php endif; ?>

            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div class="mobile-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'mobile',
                'menu_id'        => 'mobile-menu',
                'container'      => false,
                'fallback_cb'    => false,
            ));
            ?>
        </div>
        
    </header>

    <?php
    // Display breadcrumbs on all pages except front page
    if (!is_front_page()) {
        echo '<div class="breadcrumb-container">';
        echo '<div class="container">';
        aqualuxe_breadcrumbs();
        echo '</div>';
        echo '</div>';
    }
    ?>

    <div id="content" class="site-content">