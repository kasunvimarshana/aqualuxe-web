<?php if (! defined('ABSPATH')) { exit; } ?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0ea5e9">
    <?php wp_head(); ?>
</head>
<body <?php body_class('antialiased'); ?>>
<?php wp_body_open(); ?>

<a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 bg-primary text-white p-4 z-50" href="#content">
    <?php esc_html_e('Skip to content', 'aqualuxe'); ?>
</a>

<header class="site-header" role="banner">
    <div class="header-container">
        <!-- Site Branding -->
        <div class="site-branding">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
            <?php endif; ?>
            
            <?php 
            $description = get_bloginfo('description', 'display');
            if ($description || is_customize_preview()) : ?>
                <p class="site-description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>

        <!-- Primary Navigation -->
        <nav class="primary-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'aqualuxe'); ?>">
            <?php 
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'nav-menu',
                'container'      => false,
                'fallback_cb'    => false,
            ]); 
            ?>
        </nav>

        <!-- Header Actions -->
        <div class="header-actions">
            <!-- Language and Currency Switchers will be added by JavaScript -->
            
            <!-- Account Menu -->
            <?php if (is_user_logged_in()) : ?>
                <div class="account-menu">
                    <button class="account-toggle" type="button" aria-expanded="false">
                        <?php echo get_avatar(get_current_user_id(), 24, '', '', ['class' => 'account-avatar']); ?>
                        <span><?php esc_html_e('Account', 'aqualuxe'); ?></span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
            <?php else : ?>
                <a href="<?php echo esc_url(wp_login_url()); ?>" class="account-toggle">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span><?php esc_html_e('Login', 'aqualuxe'); ?></span>
                </a>
            <?php endif; ?>

            <!-- Cart (WooCommerce) -->
            <?php if (class_exists('WooCommerce')) : ?>
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-toggle" aria-label="<?php esc_attr_e('Shopping Cart', 'aqualuxe'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m1.6 8L5 5m2 8v6a2 2 0 002 2h6a2 2 0 002-2v-6m-6 6h4"></path>
                    </svg>
                    <?php 
                    $cart_count = WC()->cart->get_cart_contents_count();
                    if ($cart_count > 0) : ?>
                        <span class="cart-count"><?php echo esc_html($cart_count); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>">
                <span class="hamburger"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobile-menu" aria-hidden="true">
        <div class="mobile-menu-content">
            <?php 
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'mobile-nav-menu',
                'container'      => false,
                'fallback_cb'    => false,
            ]); 
            ?>
            
            <!-- Mobile Account/Login -->
            <div class="mobile-account">
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('myaccount'))); ?>">
                        <?php esc_html_e('My Account', 'aqualuxe'); ?>
                    </a>
                    <a href="<?php echo esc_url(wp_logout_url()); ?>">
                        <?php esc_html_e('Logout', 'aqualuxe'); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(wp_login_url()); ?>">
                        <?php esc_html_e('Login', 'aqualuxe'); ?>
                    </a>
                    <a href="<?php echo esc_url(wp_registration_url()); ?>">
                        <?php esc_html_e('Register', 'aqualuxe'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main id="content" class="site-main" tabindex="-1" role="main">