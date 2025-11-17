<?php
/**
 * Split Header Layout
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<header id="masthead" class="<?php echo esc_attr( aqualuxe_get_header_class() ); ?>">
    <div class="container">
        <div class="site-header-inner">
            <div class="header-left">
                <nav id="left-navigation" class="left-navigation">
                    <?php
                    // Get the primary menu items
                    $menu_name = 'primary';
                    $locations = get_nav_menu_locations();
                    $menu_items = array();
                    
                    if ( isset( $locations[ $menu_name ] ) ) {
                        $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
                        $menu_items = wp_get_nav_menu_items( $menu->term_id );
                    }
                    
                    // Calculate the middle point
                    $total_items = count( $menu_items );
                    $middle = ceil( $total_items / 2 );
                    
                    // Create left menu
                    $left_menu_args = array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'left-menu',
                        'container'      => false,
                        'menu_class'     => 'left-menu',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    );
                    
                    // If we have menu items, add a walker to split the menu
                    if ( $total_items > 0 ) {
                        $left_menu_args['walker'] = new AquaLuxe_Split_Menu_Walker( 0, $middle - 1 );
                    }
                    
                    wp_nav_menu( $left_menu_args );
                    ?>
                </nav><!-- #left-navigation -->
            </div><!-- .header-left -->

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

            <div class="header-right">
                <nav id="right-navigation" class="right-navigation">
                    <?php
                    // Create right menu
                    $right_menu_args = array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'right-menu',
                        'container'      => false,
                        'menu_class'     => 'right-menu',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    );
                    
                    // If we have menu items, add a walker to split the menu
                    if ( $total_items > 0 ) {
                        $right_menu_args['walker'] = new AquaLuxe_Split_Menu_Walker( $middle, $total_items - 1 );
                    }
                    
                    wp_nav_menu( $right_menu_args );
                    ?>
                </nav><!-- #right-navigation -->

                <div class="header-actions">
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
                </div><!-- .header-actions -->
            </div><!-- .header-right -->

            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="menu-toggle-icon"></span>
                <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
            </button>

            <nav id="mobile-navigation" class="mobile-navigation">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'mobile-menu',
                        'container'      => false,
                        'menu_class'     => 'mobile-menu',
                        'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                    )
                );
                ?>
            </nav><!-- #mobile-navigation -->
        </div><!-- .site-header-inner -->
    </div><!-- .container -->
</header><!-- #masthead -->