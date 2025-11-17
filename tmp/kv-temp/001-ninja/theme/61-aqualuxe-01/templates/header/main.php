<?php
/**
 * Template part for displaying the main header
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get header width
$header_width = get_theme_mod( 'aqualuxe_header_width', 'container' );
?>

<div class="header-main">
    <div class="<?php echo esc_attr( $header_width ); ?>">
        <div class="header-main-inner">
            <div class="site-branding">
                <?php echo aqualuxe_get_logo(); ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="menu-toggle-icon"></span>
                    <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
                </button>
                <?php
                wp_nav_menu(
                    [
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ]
                );
                ?>
            </nav><!-- #site-navigation -->

            <div class="header-actions">
                <?php if ( apply_filters( 'aqualuxe_dark_mode_enabled', true ) ) : ?>
                    <div class="dark-mode-toggle">
                        <button class="dark-mode-button" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
                            <i class="fas fa-moon dark-icon" aria-hidden="true"></i>
                            <i class="fas fa-sun light-icon" aria-hidden="true"></i>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="header-search">
                    <button class="search-toggle" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle Search', 'aqualuxe' ); ?>">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                    <div class="search-dropdown">
                        <?php get_search_form(); ?>
                    </div>
                </div>

                <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
                    <div class="header-cart">
                        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                            <span class="cart-count"><?php echo esc_html( aqualuxe_get_cart_count() ); ?></span>
                        </a>
                        <div class="cart-dropdown">
                            <?php echo aqualuxe_get_mini_cart(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div><!-- .header-actions -->
        </div><!-- .header-main-inner -->
    </div><!-- .container -->
</div><!-- .header-main -->