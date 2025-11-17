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

use AquaLuxe\Core\DarkMode;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="<?php echo DarkMode::get_instance()->is_dark_mode_active() ? 'dark-mode' : 'light-mode'; ?>">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

    <?php do_action( 'aqualuxe_before_header' ); ?>

    <header id="masthead" class="site-header">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <div class="site-branding">
                    <?php
                    if ( has_custom_logo() ) :
                        the_custom_logo();
                    else :
                        ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                        <?php
                        $aqualuxe_description = get_bloginfo( 'description', 'display' );
                        if ( $aqualuxe_description || is_customize_preview() ) :
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
                            'container'      => false,
                            'menu_class'     => 'flex',
                        )
                    );
                    ?>
                </nav><!-- #site-navigation -->

                <div class="flex items-center">
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <div class="header-cart relative mr-4">
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents">
                                <span class="cart-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16V4H2V2h3a1 1 0 0 1 1 1v12h12.438l2-8H8V5h13.72a1 1 0 0 1 .97 1.243l-2.5 10a1 1 0 0 1-.97.757H5a1 1 0 0 1-1-1zm2 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm12 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
                                </span>
                                <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
                            </a>
                            <div class="header-cart-dropdown hidden absolute right-0 top-full z-50 w-80 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4">
                                <div class="widget_shopping_cart_content">
                                    <?php woocommerce_mini_cart(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <button id="mobile-menu-toggle" class="mobile-menu-toggle lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="mobile-menu hidden lg:hidden">
            <div class="container mx-auto px-4 py-4">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'mobile',
                        'menu_id'        => 'mobile-menu',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'primary',
                                    'menu_id'        => 'mobile-menu',
                                    'container'      => false,
                                )
                            );
                        },
                    )
                );
                ?>
            </div>
        </div>
    </header><!-- #masthead -->

    <?php do_action( 'aqualuxe_after_header' ); ?>

    <div id="content" class="site-content">