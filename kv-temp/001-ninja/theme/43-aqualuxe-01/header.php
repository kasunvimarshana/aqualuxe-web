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

    <?php get_template_part( 'template-parts/header/announcement-bar' ); ?>

    <header id="masthead" class="site-header">
        <div class="header-wrapper">
            <div class="container mx-auto px-4">
                <div class="header-inner py-4">
                    <div class="flex items-center justify-between">
                        <div class="site-branding flex items-center">
                            <?php
                            if ( has_custom_logo() ) :
                                the_custom_logo();
                            else :
                                ?>
                                <div class="site-title-wrapper">
                                    <h1 class="site-title">
                                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                                    </h1>
                                    <?php
                                    $aqualuxe_description = get_bloginfo( 'description', 'display' );
                                    if ( $aqualuxe_description || is_customize_preview() ) :
                                        ?>
                                        <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div><!-- .site-branding -->

                        <div class="header-right flex items-center">
                            <nav id="site-navigation" class="main-navigation hidden lg:block">
                                <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'primary',
                                        'menu_id'        => 'primary-menu',
                                        'container'      => false,
                                        'menu_class'     => 'primary-menu flex',
                                        'fallback_cb'    => false,
                                    )
                                );
                                ?>
                            </nav><!-- #site-navigation -->

                            <div class="header-actions flex items-center ml-4">
                                <?php get_template_part( 'template-parts/header/search-toggle' ); ?>
                                
                                <?php get_template_part( 'template-parts/header/language-switcher' ); ?>
                                
                                <?php get_template_part( 'template-parts/header/dark-mode-toggle' ); ?>
                                
                                <?php 
                                // WooCommerce cart and account icons if WooCommerce is active
                                if ( class_exists( 'WooCommerce' ) ) {
                                    get_template_part( 'template-parts/header/cart' );
                                    get_template_part( 'template-parts/header/account' );
                                }
                                ?>
                                
                                <button id="mobile-menu-toggle" class="mobile-menu-toggle lg:hidden ml-4" aria-controls="mobile-menu" aria-expanded="false">
                                    <span class="sr-only"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php get_template_part( 'template-parts/header/mobile-menu' ); ?>
        <?php get_template_part( 'template-parts/header/search-modal' ); ?>
    </header><!-- #masthead -->

    <?php 
    // Display WooCommerce notice if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        woocommerce_output_all_notices();
    }
    ?>