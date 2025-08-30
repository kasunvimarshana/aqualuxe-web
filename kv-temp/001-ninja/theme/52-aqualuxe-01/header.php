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

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
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

    <?php
    // Top Bar
    if (get_theme_mod('aqualuxe_enable_topbar', true)) {
        get_template_part('templates/header/topbar');
    }
    ?>

    <header id="site-header" class="site-header">
        <div class="site-header__container">
            <div class="site-header__branding">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <?php
                    $aqualuxe_description = get_bloginfo('description', 'display');
                    if ($aqualuxe_description || is_customize_preview()) {
                        ?>
                        <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                        <?php
                    }
                }
                ?>
            </div><!-- .site-branding -->

            <div class="site-header__navigation">
                <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'aqualuxe'); ?>">
                    <button class="main-navigation__toggle" aria-controls="primary-menu" aria-expanded="false">
                        <span class="main-navigation__toggle-icon"></span>
                        <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
                    </button>

                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'main-navigation__menu',
                            'container'      => false,
                            'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                        )
                    );
                    ?>
                </nav><!-- #site-navigation -->
            </div>

            <div class="site-header__actions">
                <?php
                // Dark Mode Toggle
                if (get_theme_mod('aqualuxe_enable_dark_mode', true)) {
                    get_template_part('templates/header/dark-mode-toggle');
                }

                // Search Icon
                if (get_theme_mod('aqualuxe_enable_header_search', true)) {
                    get_template_part('templates/header/search-icon');
                }

                // Language Switcher
                if (get_theme_mod('aqualuxe_enable_language_switcher', false) && function_exists('aqualuxe_language_switcher')) {
                    aqualuxe_language_switcher();
                }

                // Currency Switcher
                if (get_theme_mod('aqualuxe_enable_currency_switcher', false) && function_exists('aqualuxe_currency_switcher')) {
                    aqualuxe_currency_switcher();
                }

                // WooCommerce Icons
                if (aqualuxe_is_woocommerce_active()) {
                    // My Account Icon
                    if (get_theme_mod('aqualuxe_enable_header_account', true)) {
                        get_template_part('templates/header/account-icon');
                    }

                    // Wishlist Icon
                    if (get_theme_mod('aqualuxe_enable_header_wishlist', true) && function_exists('aqualuxe_wishlist_icon')) {
                        aqualuxe_wishlist_icon();
                    }

                    // Cart Icon
                    if (get_theme_mod('aqualuxe_enable_header_cart', true)) {
                        get_template_part('templates/header/cart-icon');
                    }
                }
                ?>
            </div>
        </div>
    </header><!-- #site-header -->

    <?php
    // Mobile Navigation
    get_template_part('templates/header/mobile-navigation');

    // Search Modal
    if (get_theme_mod('aqualuxe_enable_header_search', true)) {
        get_template_part('templates/header/search-modal');
    }
    ?>

    <div id="content" class="site-content">
        <?php
        // Display breadcrumbs if enabled
        if (get_theme_mod('aqualuxe_enable_breadcrumbs', true) && !is_front_page()) {
            aqualuxe_breadcrumbs();
        }
        ?>