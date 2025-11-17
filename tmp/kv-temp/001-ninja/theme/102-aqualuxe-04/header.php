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
<html <?php language_attributes(); ?> class="<?php echo esc_attr( aqualuxe_get_html_classes() ); ?>">
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

    <header id="masthead" class="site-header bg-white dark:bg-gray-900 shadow-lg transition-colors duration-300">
        <div class="container mx-auto px-4">
            <!-- Top Bar -->
            <?php if ( aqualuxe_has_top_bar() ) : ?>
                <div class="top-bar border-b border-gray-200 dark:border-gray-700 py-2">
                    <div class="flex justify-between items-center text-sm">
                        <div class="top-bar-left">
                            <?php aqualuxe_top_bar_left(); ?>
                        </div>
                        <div class="top-bar-right flex items-center space-x-4">
                            <?php aqualuxe_top_bar_right(); ?>
                            
                            <!-- Language Switcher -->
                            <?php if ( function_exists( 'aqualuxe_language_switcher' ) ) : ?>
                                <?php aqualuxe_language_switcher(); ?>
                            <?php endif; ?>
                            
                            <!-- Dark Mode Toggle -->
                            <?php if ( function_exists( 'aqualuxe_dark_mode_toggle' ) ) : ?>
                                <?php aqualuxe_dark_mode_toggle(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Main Header -->
            <div class="main-header py-4">
                <div class="flex justify-between items-center">
                    <!-- Site Branding -->
                    <div class="site-branding">
                        <?php
                        the_custom_logo();
                        if ( is_front_page() && is_home() ) :
                            ?>
                            <h1 class="site-title text-2xl font-bold">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-aqua-600 hover:text-aqua-700 transition-colors">
                                    <?php bloginfo( 'name' ); ?>
                                </a>
                            </h1>
                            <?php
                        else :
                            ?>
                            <p class="site-title text-2xl font-bold">
                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-aqua-600 hover:text-aqua-700 transition-colors">
                                    <?php bloginfo( 'name' ); ?>
                                </a>
                            </p>
                            <?php
                        endif;
                        $aqualuxe_description = get_bloginfo( 'description', 'display' );
                        if ( $aqualuxe_description || is_customize_preview() ) :
                            ?>
                            <p class="site-description text-gray-600 dark:text-gray-400"><?php echo $aqualuxe_description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?></p>
                            <?php endif; ?>
                    </div><!-- .site-branding -->

                    <!-- Primary Navigation -->
                    <nav id="site-navigation" class="main-navigation hidden lg:block">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'container'      => false,
                                'menu_class'     => 'flex space-x-8',
                                'link_before'    => '<span class="nav-link-text">',
                                'link_after'     => '</span>',
                                'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                            )
                        );
                        ?>
                    </nav><!-- #site-navigation -->

                    <!-- Header Actions -->
                    <div class="header-actions flex items-center space-x-4">
                        <!-- Search Toggle -->
                        <button id="search-toggle" class="search-toggle p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="<?php esc_attr_e( 'Toggle Search', 'aqualuxe' ); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>

                        <!-- WooCommerce Cart -->
                        <?php if ( function_exists( 'aqualuxe_cart_icon' ) ) : ?>
                            <?php aqualuxe_cart_icon(); ?>
                        <?php endif; ?>

                        <!-- Mobile Menu Toggle -->
                        <button id="mobile-menu-toggle" class="mobile-menu-toggle lg:hidden p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="<?php esc_attr_e( 'Toggle Mobile Menu', 'aqualuxe' ); ?>">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="mobile-menu hidden lg:hidden border-t border-gray-200 dark:border-gray-700 py-4">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'mobile',
                        'menu_id'        => 'mobile-menu-list',
                        'container'      => false,
                        'menu_class'     => 'space-y-2',
                        'fallback_cb'    => 'aqualuxe_mobile_menu_fallback',
                    )
                );
                ?>
            </div>

            <!-- Search Overlay -->
            <div id="search-overlay" class="search-overlay hidden absolute top-full left-0 right-0 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 py-4 z-50">
                <div class="container mx-auto px-4">
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
    </header><!-- #masthead -->

    <div id="content" class="site-content">