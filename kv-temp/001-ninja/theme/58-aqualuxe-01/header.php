<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get theme options
$options = get_option('aqualuxe_options', array());

// Header options
$enable_preloader = isset($options['enable_preloader']) ? $options['enable_preloader'] : true;
$preloader_style = isset($options['preloader_style']) ? $options['preloader_style'] : 'wave';
$header_layout = isset($options['header_layout']) ? $options['header_layout'] : 'standard';
$enable_sticky_header = isset($options['enable_sticky_header']) ? $options['enable_sticky_header'] : true;
$enable_top_bar = isset($options['enable_top_bar']) ? $options['enable_top_bar'] : true;
$enable_search = isset($options['enable_search']) ? $options['enable_search'] : true;
$enable_dark_mode = isset($options['enable_dark_mode']) ? $options['enable_dark_mode'] : true;
$dark_mode_default = isset($options['dark_mode_default']) ? $options['dark_mode_default'] : 'light';
$dark_mode_toggle_position = isset($options['dark_mode_toggle_position']) ? $options['dark_mode_toggle_position'] : 'header';
$dark_mode_toggle_style = isset($options['dark_mode_toggle_style']) ? $options['dark_mode_toggle_style'] : 'icon';
$enable_language_switcher = isset($options['enable_language_switcher']) ? $options['enable_language_switcher'] : true;
$language_switcher_style = isset($options['language_switcher_style']) ? $options['language_switcher_style'] : 'dropdown';
$social_show_header = isset($options['social_show_header']) ? $options['social_show_header'] : true;

// WooCommerce options
$mini_cart_style = isset($options['mini_cart_style']) ? $options['mini_cart_style'] : 'dropdown';

// Get the dark mode class
$body_class = '';
if ($enable_dark_mode && $dark_mode_default === 'dark') {
    $body_class = 'dark-mode';
} elseif ($enable_dark_mode && $dark_mode_default === 'auto') {
    $body_class = 'auto-dark-mode';
}

// Get header class
$header_class = 'site-header';
if ($enable_sticky_header) {
    $header_class .= ' sticky-header';
}
$header_class .= ' header-' . $header_layout;

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <?php if (isset($options['header_code']) && !empty($options['header_code'])) : ?>
        <?php echo wp_kses_post($options['header_code']); ?>
    <?php endif; ?>
</head>

<body <?php body_class($body_class); ?>>
<?php wp_body_open(); ?>

<?php if ($enable_preloader) : ?>
    <div id="preloader" class="preloader preloader-<?php echo esc_attr($preloader_style); ?>">
        <?php if ($preloader_style === 'logo' && has_custom_logo()) : ?>
            <div class="preloader-logo">
                <?php the_custom_logo(); ?>
            </div>
        <?php else : ?>
            <div class="preloader-animation"></div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

    <?php if ($enable_top_bar) : ?>
        <div class="top-bar">
            <div class="container">
                <div class="top-bar-content">
                    <div class="top-bar-left">
                        <?php if (has_nav_menu('top-bar')) : ?>
                            <nav class="top-bar-navigation">
                                <?php
                                wp_nav_menu(array(
                                    'theme_location' => 'top-bar',
                                    'menu_id'        => 'top-bar-menu',
                                    'container'      => false,
                                    'depth'          => 1,
                                ));
                                ?>
                            </nav>
                        <?php endif; ?>
                    </div>
                    <div class="top-bar-right">
                        <?php if ($enable_language_switcher && function_exists('aqualuxe_language_switcher')) : ?>
                            <div class="language-switcher language-switcher-<?php echo esc_attr($language_switcher_style); ?>">
                                <?php aqualuxe_language_switcher(); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($social_show_header && function_exists('aqualuxe_social_icons')) : ?>
                            <div class="social-icons">
                                <?php aqualuxe_social_icons('header'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <header id="masthead" class="<?php echo esc_attr($header_class); ?>">
        <div class="container">
            <div class="site-header-inner">
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()) :
                        the_custom_logo();
                    else :
                    ?>
                        <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                        <?php
                        $aqualuxe_description = get_bloginfo('description', 'display');
                        if ($aqualuxe_description || is_customize_preview()) :
                        ?>
                            <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div><!-- .site-branding -->

                <div class="header-right">
                    <nav id="site-navigation" class="main-navigation">
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <span class="menu-toggle-icon"></span>
                            <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
                        </button>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                        ));
                        ?>
                    </nav><!-- #site-navigation -->

                    <div class="header-actions">
                        <?php if ($enable_search) : ?>
                            <div class="header-search">
                                <button class="search-toggle" aria-expanded="false">
                                    <span class="icon-search"></span>
                                    <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                                </button>
                                <div class="search-dropdown">
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($enable_dark_mode && ($dark_mode_toggle_position === 'header' || $dark_mode_toggle_position === 'both')) : ?>
                            <div class="dark-mode-toggle dark-mode-toggle-<?php echo esc_attr($dark_mode_toggle_style); ?>">
                                <button class="dark-mode-toggle-button" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>">
                                    <span class="icon-light"></span>
                                    <span class="icon-dark"></span>
                                    <?php if ($dark_mode_toggle_style === 'text' || $dark_mode_toggle_style === 'button') : ?>
                                        <span class="toggle-text toggle-text-light"><?php esc_html_e('Light', 'aqualuxe'); ?></span>
                                        <span class="toggle-text toggle-text-dark"><?php esc_html_e('Dark', 'aqualuxe'); ?></span>
                                    <?php endif; ?>
                                </button>
                            </div>
                        <?php endif; ?>

                        <?php if (class_exists('WooCommerce')) : ?>
                            <div class="header-cart mini-cart-<?php echo esc_attr($mini_cart_style); ?>">
                                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-contents" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
                                    <span class="icon-cart"></span>
                                    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                                </a>
                                <?php if ($mini_cart_style === 'dropdown') : ?>
                                    <div class="mini-cart-dropdown">
                                        <div class="widget_shopping_cart_content">
                                            <?php woocommerce_mini_cart(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (class_exists('WooCommerce')) : ?>
                            <div class="header-account">
                                <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="account-link">
                                    <span class="icon-user"></span>
                                    <span class="screen-reader-text"><?php esc_html_e('My Account', 'aqualuxe'); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div><!-- .header-actions -->
                </div><!-- .header-right -->
            </div><!-- .site-header-inner -->
        </div><!-- .container -->
    </header><!-- #masthead -->

    <?php if ($mini_cart_style === 'offcanvas' && class_exists('WooCommerce')) : ?>
        <div class="mini-cart-offcanvas">
            <div class="mini-cart-header">
                <h3><?php esc_html_e('Your Cart', 'aqualuxe'); ?></h3>
                <button class="close-mini-cart">
                    <span class="icon-close"></span>
                    <span class="screen-reader-text"><?php esc_html_e('Close', 'aqualuxe'); ?></span>
                </button>
            </div>
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
        <div class="mini-cart-overlay"></div>
    <?php endif; ?>

    <?php if (function_exists('aqualuxe_breadcrumbs') && !is_front_page()) : ?>
        <div class="breadcrumbs-container">
            <div class="container">
                <?php aqualuxe_breadcrumbs(); ?>
            </div>
        </div>
    <?php endif; ?>

    <div id="content" class="site-content">