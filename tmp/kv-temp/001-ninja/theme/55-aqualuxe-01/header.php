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

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
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
<div id="page" class="site" x-data="{ mobileMenuOpen: false, searchOpen: false, cartOpen: false }">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

    <header id="masthead" class="site-header">
        <div class="header-container">
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

            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" @click="mobileMenuOpen = !mobileMenuOpen">
                    <span class="menu-toggle-icon"></span>
                    <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
                </button>
                
                <div class="primary-menu-container" :class="{ 'is-active': mobileMenuOpen }">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'menu_class'     => 'primary-menu',
                            'fallback_cb'    => false,
                            'walker'         => new AquaLuxe_Walker_Nav_Menu(),
                        )
                    );
                    ?>
                </div>
            </nav><!-- #site-navigation -->

            <div class="header-actions">
                <button class="search-toggle" aria-expanded="false" @click="searchOpen = !searchOpen">
                    <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>
                </button>

                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                <button class="cart-toggle" aria-expanded="false" @click="cartOpen = !cartOpen">
                    <span class="screen-reader-text"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16V4H2V2h3a1 1 0 0 1 1 1v12h12.438l2-8H8V5h13.72a1 1 0 0 1 .97 1.243l-2.5 10a1 1 0 0 1-.97.757H5a1 1 0 0 1-1-1zm2 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm12 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
                    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </button>
                <?php endif; ?>

                <?php if (has_nav_menu('social')) : ?>
                <div class="social-navigation">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'social',
                            'menu_id'        => 'social-menu',
                            'container'      => false,
                            'menu_class'     => 'social-menu',
                            'link_before'    => '<span class="screen-reader-text">',
                            'link_after'     => '</span>',
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </div>
                <?php endif; ?>
            </div><!-- .header-actions -->
        </div><!-- .header-container -->

        <div class="search-modal" :class="{ 'is-active': searchOpen }" @click.away="searchOpen = false">
            <div class="search-modal-container">
                <button class="search-modal-close" @click="searchOpen = false">
                    <span class="screen-reader-text"><?php esc_html_e('Close search', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
                </button>
                <div class="search-form-container">
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div><!-- .search-modal -->

        <?php if (aqualuxe_is_woocommerce_active()) : ?>
        <div class="cart-modal" :class="{ 'is-active': cartOpen }" @click.away="cartOpen = false">
            <div class="cart-modal-container">
                <button class="cart-modal-close" @click="cartOpen = false">
                    <span class="screen-reader-text"><?php esc_html_e('Close cart', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
                </button>
                <div class="cart-modal-content">
                    <div class="widget_shopping_cart_content">
                        <?php woocommerce_mini_cart(); ?>
                    </div>
                </div>
            </div>
        </div><!-- .cart-modal -->
        <?php endif; ?>
    </header><!-- #masthead -->

    <div id="content" class="site-content">