<?php
/**
 * Template part for displaying the header content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<header id="masthead" class="site-header">
	<div class="container mx-auto px-4 py-4 flex justify-between items-center">
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$aqualuxe_description = get_bloginfo( 'description', 'display' );
			if ( $aqualuxe_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'aqualuxe' ); ?></span>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/></svg>
			</button>
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

		<?php if ( function_exists( 'aqualuxe_dark_mode_toggle' ) ) : ?>
			<div class="dark-mode-toggle">
				<?php aqualuxe_dark_mode_toggle(); ?>
			</div>
		<?php endif; ?>

		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<div class="site-header-cart">
				<?php aqualuxe_woocommerce_cart_link(); ?>
				<div class="site-header-cart-dropdown">
					<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</header><!-- #masthead -->