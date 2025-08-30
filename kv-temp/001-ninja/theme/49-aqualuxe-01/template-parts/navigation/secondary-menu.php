<?php
/**
 * Template part for displaying the secondary navigation menu
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<nav id="secondary-navigation" class="secondary-navigation" aria-label="<?php esc_attr_e( 'Secondary Navigation', 'aqualuxe' ); ?>">
    <?php
    wp_nav_menu(
        array(
            'theme_location' => 'secondary',
            'menu_id'        => 'secondary-menu',
            'menu_class'     => 'secondary-menu flex flex-wrap items-center',
            'container'      => false,
            'depth'          => 1,
            'fallback_cb'    => false,
        )
    );
    ?>
</nav><!-- #secondary-navigation -->