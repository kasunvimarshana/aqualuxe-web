<?php
/**
 * Template part for displaying the footer navigation menu
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer Navigation', 'aqualuxe' ); ?>">
    <?php
    wp_nav_menu(
        array(
            'theme_location' => 'footer',
            'menu_id'        => 'footer-menu',
            'menu_class'     => 'footer-menu flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-2',
            'container'      => false,
            'depth'          => 1,
            'fallback_cb'    => false,
        )
    );
    ?>
</nav><!-- .footer-navigation -->