<?php
/**
 * Centered Header Layout
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<header id="masthead" class="<?php echo esc_attr( aqualuxe_get_header_class() ); ?>">
    <div class="container">
        <div class="site-header-inner">
            <div class="site-branding centered">
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

            <nav id="site-navigation" class="main-navigation centered">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="menu-toggle-icon"></span>
                    <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
                </button>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'primary-menu',
                        'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                    )
                );
                ?>
            </nav><!-- #site-navigation -->

            <div class="header-actions centered">
                <div class="header-actions-inner">
                    <?php if ( get_theme_mod( 'aqualuxe_show_search', true ) ) : ?>
                        <div class="header-search">
                            <button class="search-toggle" aria-expanded="false">
                                <span class="search-icon"></span>
                                <span class="screen-reader-text"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                            </button>
                            <div class="search-dropdown">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ( function_exists( 'aqualuxe_is_woocommerce_active' ) && aqualuxe_is_woocommerce_active() ) : ?>
                        <?php if ( function_exists( 'aqualuxe_woocommerce_header_cart' ) ) : ?>
                            <?php aqualuxe_woocommerce_header_cart(); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div><!-- .header-actions -->
        </div><!-- .site-header-inner -->
    </div><!-- .container -->
</header><!-- #masthead -->