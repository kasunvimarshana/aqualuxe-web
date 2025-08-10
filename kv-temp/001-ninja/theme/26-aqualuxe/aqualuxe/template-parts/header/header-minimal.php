<?php
/**
 * Template part for displaying the minimal header layout
 *
 * @package AquaLuxe
 */

$sticky_header = get_theme_mod( 'aqualuxe_sticky_header', true );
$header_class = $sticky_header ? 'sticky top-0 z-50' : '';
?>

<header id="masthead" class="site-header bg-white dark:bg-gray-900 shadow-sm <?php echo esc_attr( $header_class ); ?>">
    <div class="container mx-auto px-4">
        <div class="header-main py-4">
            <div class="flex flex-wrap justify-between items-center">
                <div class="site-branding flex items-center">
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

                <div class="header-actions flex items-center space-x-4">
                    <?php if ( function_exists( 'aqualuxe_get_theme_mode_toggle' ) ) : ?>
                        <div class="theme-mode-toggle">
                            <?php echo aqualuxe_get_theme_mode_toggle(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <div class="search-toggle">
                            <button id="header-search-toggle" class="text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                            </button>
                        </div>

                        <div class="cart-link">
                            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="relative flex items-center text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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