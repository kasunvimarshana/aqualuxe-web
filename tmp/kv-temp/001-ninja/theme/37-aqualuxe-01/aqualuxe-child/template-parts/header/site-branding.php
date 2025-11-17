<?php
/**
 * Displays header site branding
 *
 * This is an example of overriding a template part from the parent theme.
 *
 * @package AquaLuxe_Child
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="site-branding">
	<?php
	// Custom implementation of site branding with additional elements
	if ( has_custom_logo() ) :
		?>
		<div class="site-logo"><?php the_custom_logo(); ?></div>
		<?php
	endif;
	?>

	<div class="site-identity">
		<?php if ( is_front_page() && is_home() ) : ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php else : ?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php endif; ?>

		<?php
		$description = get_bloginfo( 'description', 'display' );
		if ( $description || is_customize_preview() ) :
			?>
			<p class="site-description">
				<?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</p>
		<?php endif; ?>
	</div>

	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<div class="site-branding-extras">
			<!-- Custom child theme addition: Featured promotion -->
			<div class="featured-promotion">
				<?php echo esc_html( apply_filters( 'aqualuxe_child_featured_promotion', __( 'Free shipping on orders over $100', 'aqualuxe-child' ) ) ); ?>
			</div>
		</div>
	<?php endif; ?>
</div><!-- .site-branding -->