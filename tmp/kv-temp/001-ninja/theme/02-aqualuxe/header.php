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
<html <?php language_attributes(); ?>>
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

    <header id="masthead" class="site-header">
        <div class="header-top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="header-contact">
                            <?php if ( get_theme_mod( 'aqualuxe_phone_number' ) ) : ?>
                                <span class="header-phone">
                                    <i class="fas fa-phone"></i>
                                    <?php echo esc_html( get_theme_mod( 'aqualuxe_phone_number' ) ); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ( get_theme_mod( 'aqualuxe_email_address' ) ) : ?>
                                <span class="header-email">
                                    <i class="fas fa-envelope"></i>
                                    <?php echo esc_html( get_theme_mod( 'aqualuxe_email_address' ) ); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="header-actions">
                            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                                <div class="header-account">
                                    <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
                                        <i class="fas fa-user"></i>
                                        <span><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></span>
                                    </a>
                                </div>
                                
                                <?php if ( function_exists( 'aqualuxe_wishlist_link' ) ) : ?>
                                    <?php aqualuxe_wishlist_link(); ?>
                                <?php endif; ?>
                                
                                <div class="header-cart">
                                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
                                    </a>
                                    <div class="mini-cart-dropdown">
                                        <?php woocommerce_mini_cart(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="header-main">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="site-branding">
                            <?php
                            the_custom_logo();
                            if ( is_front_page() && is_home() ) :
                                ?>
                                <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                                <?php
                            else :
                                ?>
                                <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                                <?php
                            endif;
                            $aqualuxe_description = get_bloginfo( 'description', 'display' );
                            if ( $aqualuxe_description || is_customize_preview() ) :
                                ?>
                                <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                            <?php endif; ?>
                        </div><!-- .site-branding -->
                    </div>
                    <div class="col-md-9">
                        <nav id="site-navigation" class="main-navigation">
                            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                                <span class="menu-toggle-icon"></span>
                                <?php esc_html_e( 'Menu', 'aqualuxe' ); ?>
                            </button>
                            <?php
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'primary',
                                    'menu_id'        => 'primary-menu',
                                    'menu_class'     => 'primary-menu',
                                    'container'      => 'div',
                                    'container_class' => 'primary-menu-container',
                                    'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                                )
                            );
                            ?>
                        </nav><!-- #site-navigation -->
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) : ?>
            <div class="header-shop">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if ( function_exists( 'aqualuxe_breadcrumb' ) ) : ?>
                                <?php aqualuxe_breadcrumb(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </header><!-- #masthead -->

    <div id="content" class="site-content">