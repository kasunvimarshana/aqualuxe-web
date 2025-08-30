<?php
/**
 * Template part for displaying the primary navigation menu
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'aqualuxe' ); ?>">
    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
        <span class="sr-only"><?php esc_html_e( 'Primary Menu', 'aqualuxe' ); ?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    
    <?php
    wp_nav_menu(
        array(
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
            'menu_class'     => 'primary-menu flex flex-wrap lg:flex-nowrap items-center',
            'container'      => false,
            'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
            'walker'         => new AquaLuxe_Walker_Nav_Menu(),
        )
    );
    ?>
</nav><!-- #site-navigation -->