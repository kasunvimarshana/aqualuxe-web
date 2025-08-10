<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>
<!doctype html>
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

    <header id="masthead" class="site-header">
        <div class="top-bar bg-primary text-white py-2">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center">
                    <div class="top-contact hidden md:flex space-x-4">
                        <?php if (get_theme_mod('aqualuxe_phone_number')) : ?>
                            <a href="tel:<?php echo esc_attr(get_theme_mod('aqualuxe_phone_number')); ?>" class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <?php echo esc_html(get_theme_mod('aqualuxe_phone_number')); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (get_theme_mod('aqualuxe_email')) : ?>
                            <a href="mailto:<?php echo esc_attr(get_theme_mod('aqualuxe_email')); ?>" class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <?php echo esc_html(get_theme_mod('aqualuxe_email')); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="top-right flex items-center space-x-4">
                        <?php if (has_nav_menu('social')) : ?>
                            <div class="social-links">
                                <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'social',
                                        'menu_class'     => 'flex space-x-2',
                                        'container'      => false,
                                        'depth'          => 1,
                                        'link_before'    => '<span class="screen-reader-text">',
                                        'link_after'     => '</span>',
                                    )
                                );
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (function_exists('aqualuxe_dark_mode_toggle')) : ?>
                            <?php aqualuxe_dark_mode_toggle(); ?>
                        <?php endif; ?>
                        
                        <?php if (function_exists('aqualuxe_language_switcher') && get_theme_mod('aqualuxe_enable_multilingual', false)) : ?>
                            <?php aqualuxe_language_switcher(); ?>
                        <?php endif; ?>
                        
                        <?php if (class_exists('WooCommerce')) : ?>
                            <div class="woocommerce-header-actions flex items-center space-x-3">
                                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="header-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </a>
                                <?php if (function_exists('aqualuxe_header_wishlist')) : ?>
                                    <?php aqualuxe_header_wishlist(); ?>
                                <?php endif; ?>
                                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-cart-icon relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span class="cart-count absolute -top-2 -right-2 bg-accent text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
                                        <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-header py-4">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center">
                    <div class="site-branding">
                        <?php
                        if (has_custom_logo()) :
                            the_custom_logo();
                        else :
                            ?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                            <?php
                            $aqualuxe_description = get_bloginfo('description', 'display');
                            if ($aqualuxe_description || is_customize_preview()) :
                                ?>
                                <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div><!-- .site-branding -->

                    <nav id="site-navigation" class="main-navigation hidden lg:block">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'menu_class'     => 'flex space-x-6',
                                'container'      => false,
                            )
                        );
                        ?>
                    </nav><!-- #site-navigation -->

                    <button id="mobile-menu-toggle" class="lg:hidden" aria-controls="mobile-menu" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
                    </button>
                </div>
            </div>
        </div>

        <?php get_template_part('template-parts/navigation/mobile-menu'); ?>
        
        <?php if (function_exists('aqualuxe_breadcrumbs') && !is_front_page()) : ?>
            <div class="breadcrumbs-container bg-gray-100 py-3">
                <div class="container mx-auto px-4">
                    <?php aqualuxe_breadcrumbs(); ?>
                </div>
            </div>
        <?php endif; ?>
    </header><!-- #masthead -->

    <div id="content" class="site-content py-8">