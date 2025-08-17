<?php
/**
 * Template part for displaying the default header
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

$sticky_header = get_theme_mod( 'aqualuxe_sticky_header', true );
$transparent_header = get_theme_mod( 'aqualuxe_transparent_header', false );
$header_classes = array( 'site-header', 'header-default' );

if ( $sticky_header ) {
	$header_classes[] = 'sticky-header';
}

if ( $transparent_header && ( is_front_page() || is_home() ) ) {
	$header_classes[] = 'transparent-header';
}
?>

<header id="masthead" class="<?php echo esc_attr( implode( ' ', $header_classes ) ); ?>">
	<div class="header-top">
		<div class="container">
			<div class="header-top-inner">
				<div class="header-top-left">
					<?php if ( is_active_sidebar( 'header-top-left' ) ) : ?>
						<?php dynamic_sidebar( 'header-top-left' ); ?>
					<?php else : ?>
						<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
							<div class="header-currency-switcher">
								<?php aqualuxe_currency_switcher(); ?>
							</div>
						<?php endif; ?>
						<div class="header-language-switcher">
							<?php aqualuxe_language_switcher(); ?>
						</div>
					<?php endif; ?>
				</div><!-- .header-top-left -->

				<div class="header-top-right">
					<?php if ( is_active_sidebar( 'header-top-right' ) ) : ?>
						<?php dynamic_sidebar( 'header-top-right' ); ?>
					<?php else : ?>
						<div class="header-contact-info">
							<a href="tel:+1234567890"><i class="icon-phone"></i> +1 (234) 567-890</a>
							<a href="mailto:info@aqualuxe.com"><i class="icon-envelope"></i> info@aqualuxe.com</a>
						</div>
						<div class="header-social-icons">
							<a href="#" target="_blank" rel="noopener noreferrer"><i class="icon-facebook"></i></a>
							<a href="#" target="_blank" rel="noopener noreferrer"><i class="icon-twitter"></i></a>
							<a href="#" target="_blank" rel="noopener noreferrer"><i class="icon-instagram"></i></a>
							<a href="#" target="_blank" rel="noopener noreferrer"><i class="icon-youtube"></i></a>
						</div>
					<?php endif; ?>
				</div><!-- .header-top-right -->
			</div><!-- .header-top-inner -->
		</div><!-- .container -->
	</div><!-- .header-top -->

	<div class="header-main">
		<div class="container">
			<div class="header-main-inner">
				<div class="site-branding">
					<?php aqualuxe_site_logo(); ?>
				</div><!-- .site-branding -->

				<div class="header-navigation">
					<nav id="site-navigation" class="main-navigation">
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
							<span class="menu-toggle-icon"></span>
							<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
						</button>
						<?php aqualuxe_primary_navigation(); ?>
					</nav><!-- #site-navigation -->
				</div><!-- .header-navigation -->

				<div class="header-actions">
					<?php if ( get_theme_mod( 'aqualuxe_header_search', true ) ) : ?>
						<div class="header-search">
							<button class="search-toggle" aria-expanded="false">
								<i class="icon-search"></i>
								<span class="screen-reader-text"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
							</button>
							<div class="search-dropdown">
								<?php get_search_form(); ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
						<?php if ( get_theme_mod( 'aqualuxe_header_account', true ) ) : ?>
							<div class="header-account">
								<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>">
									<i class="icon-user"></i>
									<span class="screen-reader-text"><?php esc_html_e( 'Account', 'aqualuxe' ); ?></span>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'aqualuxe_header_wishlist', true ) ) : ?>
							<div class="header-wishlist">
								<a href="<?php echo esc_url( get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) ) ); ?>">
									<i class="icon-heart"></i>
									<span class="screen-reader-text"><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></span>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'aqualuxe_header_cart', true ) ) : ?>
							<div class="header-cart">
								<?php aqualuxe_woocommerce_header_cart(); ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<?php aqualuxe_dark_mode_toggle(); ?>
				</div><!-- .header-actions -->
			</div><!-- .header-main-inner -->
		</div><!-- .container -->
	</div><!-- .header-main -->

	<?php if ( aqualuxe_is_woocommerce_active() && is_active_sidebar( 'header-categories' ) ) : ?>
		<div class="header-categories">
			<div class="container">
				<div class="header-categories-inner">
					<?php dynamic_sidebar( 'header-categories' ); ?>
				</div><!-- .header-categories-inner -->
			</div><!-- .container -->
		</div><!-- .header-categories -->
	<?php endif; ?>
</header><!-- #masthead -->

<?php if ( get_theme_mod( 'aqualuxe_header_search', true ) ) : ?>
	<div class="search-modal">
		<div class="search-modal-inner">
			<div class="container">
				<button class="search-modal-close">
					<i class="icon-close"></i>
					<span class="screen-reader-text"><?php esc_html_e( 'Close', 'aqualuxe' ); ?></span>
				</button>
				<div class="search-form-container">
					<?php get_search_form(); ?>
				</div>
			</div><!-- .container -->
		</div><!-- .search-modal-inner -->
	</div><!-- .search-modal -->
<?php endif; ?>