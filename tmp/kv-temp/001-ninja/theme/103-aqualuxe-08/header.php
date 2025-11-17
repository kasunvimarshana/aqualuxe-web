<?php
/**
 * The header template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Skip to content link for accessibility -->
<a class="skip-link screen-reader-text" href="#main-content">
    <?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?>
</a>

<div id="page" class="site">
    
    <header id="masthead" class="site-header">
        <div class="container">
            <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'aqualuxe' ); ?>">
                <div class="nav-container">
                    
                    <!-- Site Logo -->
                    <div class="site-logo">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-text" rel="home">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( get_bloginfo( 'description', 'display' ) ) : ?>
                            <p class="site-description sr-only">
                                <?php echo esc_html( get_bloginfo( 'description', 'display' ) ); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Primary Navigation -->
                    <?php if ( has_nav_menu( 'primary' ) ) : ?>
                        <div class="nav-menu">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'primary',
                                'menu_class'     => 'nav-menu-list',
                                'container'      => false,
                                'fallback_cb'    => false,
                                'depth'          => 3,
                            ) );
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Header Actions -->
                    <div class="header-actions">
                        
                        <!-- Dark Mode Toggle -->
                        <?php 
                        $dark_mode_module = aqualuxe_module( 'dark-mode' );
                        if ( $dark_mode_module && method_exists( $dark_mode_module, 'is_dark_mode_enabled' ) && $dark_mode_module->is_dark_mode_enabled() ) {
                            echo $dark_mode_module->shortcode_toggle( array(
                                'class' => 'nav-dark-mode-toggle',
                                'show_text' => 'false'
                            ) );
                        }
                        ?>
                        
                        <!-- Search Toggle -->
                        <button type="button" class="search-toggle btn btn-ghost" data-search-toggle aria-label="<?php esc_attr_e( 'Open search', 'aqualuxe' ); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                        </button>
                        
                        <!-- WooCommerce Cart -->
                        <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-toggle btn btn-ghost" aria-label="<?php esc_attr_e( 'View cart', 'aqualuxe' ); ?>">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                                </svg>
                                <?php if ( WC()->cart && WC()->cart->get_cart_contents_count() > 0 ) : ?>
                                    <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
                                <?php endif; ?>
                                <span class="sr-only"><?php esc_html_e( 'Cart', 'aqualuxe' ); ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <!-- Mobile Menu Toggle -->
                        <button type="button" class="mobile-menu-toggle lg:hidden" data-mobile-menu-toggle aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'aqualuxe' ); ?>">
                            <div class="hamburger">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </button>
                        
                    </div>
                </div>
            </nav>
        </div>
        
        <!-- Mobile Navigation -->
        <div class="mobile-navigation" data-mobile-menu>
            <div class="mobile-nav-overlay" data-mobile-menu-overlay></div>
            <div class="mobile-nav-content">
                
                <div class="mobile-nav-header">
                    <div class="site-logo">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-text" rel="home">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" class="mobile-nav-close" data-mobile-menu-close aria-label="<?php esc_attr_e( 'Close menu', 'aqualuxe' ); ?>">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <?php if ( has_nav_menu( 'primary' ) ) : ?>
                    <div class="mobile-nav-menu">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'primary',
                            'menu_class'     => 'mobile-menu-list',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'depth'          => 3,
                        ) );
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="mobile-nav-footer">
                    <!-- Additional mobile menu content -->
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="btn btn-outline btn-full">
                            <?php esc_html_e( 'My Account', 'aqualuxe' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
        
    </header><!-- #masthead -->

    <main id="main-content" class="site-main" tabindex="-1">