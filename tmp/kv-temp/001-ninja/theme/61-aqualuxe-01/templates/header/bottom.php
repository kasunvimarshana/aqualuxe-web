<?php
/**
 * Template part for displaying the header bottom
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get header width
$header_width = get_theme_mod( 'aqualuxe_header_width', 'container' );

// Only display if we have a secondary menu
if ( ! has_nav_menu( 'secondary' ) ) {
    return;
}
?>

<div class="header-bottom">
    <div class="<?php echo esc_attr( $header_width ); ?>">
        <div class="header-bottom-inner">
            <nav class="secondary-navigation">
                <?php
                wp_nav_menu(
                    [
                        'theme_location' => 'secondary',
                        'menu_id'        => 'secondary-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ]
                );
                ?>
            </nav><!-- .secondary-navigation -->
        </div><!-- .header-bottom-inner -->
    </div><!-- .container -->
</div><!-- .header-bottom -->