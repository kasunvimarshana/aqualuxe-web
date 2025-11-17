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

// Include WooCommerce helper functions if they exist
if (file_exists(get_template_directory() . '/inc/helpers/woocommerce-helpers.php')) {
    require_once get_template_directory() . '/inc/helpers/woocommerce-helpers.php';
}

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
        <div class="top-bar bg-blue-900 text-white py-2">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <div class="contact-info flex items-center space-x-4">
                    <a href="tel:+1234567890" class="flex items-center">
                        <span class="mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </span>
                        <?php echo esc_html( get_theme_mod( 'aqualuxe_phone_number', '+1 (234) 567-890' ) ); ?>
                    </a>
                    <a href="mailto:info@aqualuxe.com" class="flex items-center">
                        <span class="mr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <?php echo esc_html( get_theme_mod( 'aqualuxe_email', 'info@aqualuxe.com' ) ); ?>
                    </a>
                </div>
                <div class="top-right-menu flex items-center space-x-4">
                    <div class="language-switcher">
                        <?php 
                        // Language switcher placeholder - will be implemented with multilingual support
                        if ( function_exists( 'pll_the_languages' ) ) {
                            pll_the_languages( array( 'dropdown' => 1 ) );
                        } elseif ( function_exists( 'icl_object_id' ) ) {
                            do_action( 'wpml_add_language_selector' );
                        }
                        ?>
                    </div>
                    <div class="dark-mode-toggle">
                        <button id="dark-mode-toggle" class="text-white focus:outline-none" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 light-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                    </div>
                    
                    <?php 
                    // Only show WooCommerce elements if WooCommerce is active
                    if (function_exists('aqualuxe_is_woocommerce_active') && aqualuxe_is_woocommerce_active()) : 
                        // Account icon
                        if (function_exists('aqualuxe_account_icon')) {
                            aqualuxe_account_icon();
                        }
                        
                        // Cart icon
                        if (function_exists('aqualuxe_cart_icon')) {
                            aqualuxe_cart_icon();
                        }
                    endif; 
                    ?>
                </div>
            </div>
        </div>

        <div class="main-header bg-white py-4 shadow-md">
            <div class="container mx-auto px-4 flex justify-between items-center">
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

                <nav id="site-navigation" class="main-navigation">
                    <button class="menu-toggle md:hidden" aria-controls="primary-menu" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
                    </button>
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container_class' => 'primary-menu-container hidden md:block',
                            'menu_class'     => 'primary-menu flex space-x-6',
                        )
                    );
                    ?>
                </nav><!-- #site-navigation -->
            </div>
        </div>
    </header><!-- #masthead -->

    <div id="content" class="site-content">