<?php
/**
 * Displays the responsive navigation menu.
 *
 * @package AquaLuxe
 */

?>
<nav class="responsive-menu" aria-label="<?php esc_attr_e( 'Responsive menu', 'aqualuxe' ); ?>">
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'menu_id'        => 'responsive-menu',
			'container'      => 'ul',
			'fallback_cb'    => false,
		)
	);
	?>
</nav>
