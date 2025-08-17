<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> class="<?php echo is_customize_preview() ? 'is-customize-preview' : ''; ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<header id="site-header" class="site-header">
		<?php get_template_part( 'templates/header/top-bar' ); ?>
		
		<div class="header-inner container">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) :
					?>
					<div class="site-logo">
						<?php the_custom_logo(); ?>
					</div>
					<?php
				endif;
				
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
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
					)
				);
				?>
			</nav><!-- #site-navigation -->

			<div class="header-actions">
				<?php get_template_part( 'templates/header/search-toggle' ); ?>
				
				<?php if ( function_exists( 'aqualuxe_currency_switcher' ) ) : ?>
					<?php aqualuxe_currency_switcher(); ?>
				<?php endif; ?>
				
				<?php if ( function_exists( 'aqualuxe_language_switcher' ) ) : ?>
					<?php aqualuxe_language_switcher(); ?>
				<?php endif; ?>
				
				<button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>" aria-pressed="false">
					<svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z"></path></svg>
					<svg class="moon-icon hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd"></path></svg>
				</button>
				
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php get_template_part( 'templates/header/cart-icon' ); ?>
					
					<?php if ( function_exists( 'aqualuxe_wishlist_icon' ) ) : ?>
						<?php aqualuxe_wishlist_icon(); ?>
					<?php endif; ?>
					
					<?php if ( function_exists( 'aqualuxe_account_icon' ) ) : ?>
						<?php aqualuxe_account_icon(); ?>
					<?php endif; ?>
				<?php endif; ?>
			</div><!-- .header-actions -->
			
			<div class="mobile-navigation">
				<button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'aqualuxe' ); ?>" aria-expanded="false">
					<span></span>
				</button>
			</div><!-- .mobile-navigation -->
		</div><!-- .header-inner -->
		
		<?php get_template_part( 'templates/header/search-form' ); ?>
	</header><!-- #site-header -->
	
	<div id="mobile-menu" class="mobile-menu hidden">
		<div class="mobile-menu-header">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
					?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php
				endif;
				?>
			</div>
			
			<button id="mobile-menu-close" class="mobile-menu-close" aria-label="<?php esc_attr_e( 'Close mobile menu', 'aqualuxe' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd"></path></svg>
			</button>
		</div>
		
		<div class="mobile-menu-content">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'mobile',
					'menu_id'        => 'mobile-menu-nav',
					'container'      => false,
					'fallback_cb'    => 'aqualuxe_mobile_menu_fallback',
				)
			);
			?>
			
			<div class="mobile-menu-actions">
				<?php if ( function_exists( 'aqualuxe_currency_switcher' ) ) : ?>
					<?php aqualuxe_currency_switcher(); ?>
				<?php endif; ?>
				
				<?php if ( function_exists( 'aqualuxe_language_switcher' ) ) : ?>
					<?php aqualuxe_language_switcher(); ?>
				<?php endif; ?>
			</div>
		</div>
	</div><!-- #mobile-menu -->
	
	<div id="mobile-menu-overlay" class="mobile-menu-overlay hidden"></div>

	<div id="content" class="site-content">