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
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 font-sans' ); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site min-h-screen flex flex-col">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

	<header id="masthead" class="site-header bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
		<div class="container mx-auto px-4">
			<div class="flex justify-between items-center py-4">
				<div class="site-branding">
					<?php
					if ( has_custom_logo() ) {
						the_custom_logo();
					} else {
						if ( is_front_page() && is_home() ) :
							?>
							<h1 class="site-title text-2xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"><?php bloginfo( 'name' ); ?></a></h1>
							<?php
						else :
							?>
							<p class="site-title text-2xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300"><?php bloginfo( 'name' ); ?></a></p>
							<?php
						endif;
					}
					?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation hidden md:block">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'flex items-center space-x-4',
							'container'      => false,
						)
					);
					?>
				</nav><!-- #site-navigation -->

				<div class="flex items-center space-x-2">
                    <?php
                        if ( class_exists( 'WooCommerce' ) ) {
                            echo '<div class="relative group">';
                            echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">';
                            echo '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>';
                            echo '<span class="absolute -top-1 -right-1 bg-blue-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">' . WC()->cart->get_cart_contents_count() . '</span>';
                            echo '</a>';
                            echo '<div class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg p-4 hidden group-hover:block z-50">';
                            the_widget( 'WC_Widget_Cart', 'title=' );
                            echo '</div>';
                            echo '</div>';
                        }
                    ?>
					<button id="dark-mode-toggle" class="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
						<svg id="moon-icon" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
						<svg id="sun-icon" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-15.66l-.707.707M5.05 18.95l-.707.707M21 12h-1M4 12H3m15.66 8.66l-.707-.707M6.464 6.464l-.707-.707M12 18a6 6 0 100-12 6 6 0 000 12z"></path></svg>
					</button>
					<button id="mobile-menu-button" class="md:hidden p-2 rounded-md text-gray-600 dark:text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="primary-menu" aria-expanded="false">
						<span class="sr-only"><?php esc_html_e( 'Open main menu', 'aqualuxe' ); ?></span>
						<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
					</button>
				</div>
			</div>
		</div>
		<div id="mobile-menu" class="md:hidden hidden">
			<div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'mobile-primary-menu',
						'container'      => false,
					)
				);
                if ( class_exists( 'WooCommerce' ) ) {
                    echo '<div class="border-t border-gray-700 pt-4 mt-4">';
                    the_widget( 'WC_Widget_Cart', 'title=' );
                    echo '</div>';
                }
				?>
			</div>
		</div>
	</header><!-- #masthead -->
	<div id="content" class="site-content flex-grow">
		<div class="container mx-auto px-4 py-8">
