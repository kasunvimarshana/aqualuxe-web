<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}

// Get the layout
$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );

// Return if no sidebar layout
if ( 'no-sidebar' === $layout ) {
    return;
}
?>

<aside id="secondary" class="widget-area">
    <?php
    /**
     * Functions hooked into aqualuxe_sidebar action
     *
     * @hooked aqualuxe_get_sidebar - 10
     */
    do_action( 'aqualuxe_sidebar' );
    ?>
</aside><!-- #secondary -->