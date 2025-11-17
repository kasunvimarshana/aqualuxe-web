<?php
/**
 * Template part for displaying the centered header layout
 *
 * @package AquaLuxe
 */

$sticky_header = get_theme_mod( 'aqualuxe_sticky_header', true );
$header_class = $sticky_header ? 'sticky top-0 z-50' : '';
?>

<header id="masthead" class="site-header bg-white dark:bg-gray-900 shadow-sm <?php echo esc_attr( $header_class ); ?>">
    <div class="top-bar bg-gray-100 dark:bg-gray-800 py-2 text-sm">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-between items-center">
                <div class="top-contact flex items-center space-x-4">
                    <?php if ( $phone = get_theme_mod( 'aqualuxe_phone', '+1-234-567-8901' ) ) : ?>
                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <?php echo esc_html( $phone ); ?>
                        </a>
                    <?php endif; ?>

                    <?php if ( $email = get_theme_mod( 'aqualuxe_email', 'info@aqualuxe.com' ) ) : ?>
                        <a href="mailto:<?php echo esc_attr( $email ); ?>" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <?php echo esc_html( $email ); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="top-actions flex items-center space-x-4">
                    <?php if ( function_exists( 'aqualuxe_language_switcher' ) ) : ?>
                        <div class="language-switcher">
                            <?php aqualuxe_language_switcher(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( function_exists( 'aqualuxe_get_theme_mode_toggle' ) ) : ?>
                        <div class="theme-mode-toggle">
                            <?php echo aqualuxe_get_theme_mode_toggle(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <div class="account-link">
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <?php echo is_user_logged_in() ? esc_html__( 'My Account', 'aqualuxe' ) : esc_html__( 'Login', 'aqualuxe' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="main-header py-4">
        <div class="container mx-auto px-4">
            <div class="flex flex-col items-center">
                <div class="site-branding text-center mb-4">
                    <?php
                    if ( has_custom_logo() ) :
                        the_custom_logo();
                    else :
                        ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-2xl font-bold text-gray-900 dark:text-white">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                        <?php
                        $aqualuxe_description = get_bloginfo( 'description', 'display' );
                        if ( $aqualuxe_description || is_customize_preview() ) :
                            ?>
                            <p class="site-description text-sm text-gray-600 dark:text-gray-400 mt-1">
                                <?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </p>
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
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </nav><!-- #site-navigation -->
            </div>

            <div class="header-actions flex justify-center mt-4 lg:absolute lg:top-4 lg:right-4">
                <div class="flex items-center space-x-4">
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <div class="search-toggle">
                            <button id="header-search-toggle" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                            </button>
                        </div>

                        <?php if ( get_theme_mod( 'aqualuxe_enable_wishlist', true ) ) : ?>
                            <div class="wishlist-link">
                                <a href="<?php echo esc_url( wc_get_endpoint_url( 'wishlist', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>" class="relative flex items-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span class="sr-only"><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></span>
                                    <span class="wishlist-count absolute -top-2 -right-2 bg-primary text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="cart-link">
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="relative flex items-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span class="sr-only"><?php esc_html_e( 'Cart', 'aqualuxe' ); ?></span>
                                <span class="cart-count absolute -top-2 -right-2 bg-primary text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                    <?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
                                </span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="mobile-menu-toggle lg:hidden">
                        <button id="mobile-menu-toggle" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <span class="sr-only"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
        <div id="header-search" class="header-search bg-white dark:bg-gray-900 py-4 border-t border-gray-200 dark:border-gray-700 hidden">
            <div class="container mx-auto px-4">
                <form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="flex">
                        <input type="search" id="woocommerce-product-search-field" class="search-field flex-grow px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-l-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-gray-100" placeholder="<?php esc_attr_e( 'Search products...', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        <button type="submit" class="search-submit bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-r-md transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                        </button>
                    </div>
                    <input type="hidden" name="post_type" value="product" />
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div id="mobile-menu" class="mobile-menu bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 hidden lg:hidden">
        <div class="container mx-auto px-4 py-4">
            <nav class="mobile-navigation">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'mobile-menu',
                        'menu_class'     => 'mobile-menu-items',
                        'container'      => false,
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </nav>
        </div>
    </div>
</header><!-- #masthead -->