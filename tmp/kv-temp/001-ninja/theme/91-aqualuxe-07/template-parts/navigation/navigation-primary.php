<?php
/**
 * Displays the site navigation.
 *
 * @package AquaLuxe
 */

?>

<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'aqualuxe' ); ?>">
	<div class="main-navigation-inside">
		<div class="toggle-container">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
				<span class="icon-menu" aria-hidden="true"></span>
			</button>
		</div>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'menu_id'        => 'primary-menu',
				'container'      => 'ul',
				'fallback_cb'    => false,
			)
		);
		?>
	</div>
</nav>
