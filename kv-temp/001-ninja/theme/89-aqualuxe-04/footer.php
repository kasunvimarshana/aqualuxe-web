<?php
/** Footer template */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<?php do_action( 'aqualuxe_after_main' ); ?>
</main>
<?php do_action( 'aqualuxe_before_footer' ); ?>
<footer id="colophon" class="site-footer">
	<div class="alx-container py-8">
		<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
			<div>
				<h2 class="text-lg font-semibold"><?php esc_html_e( 'About AquaLuxe', 'aqualuxe' ); ?></h2>
				<p><?php esc_html_e( 'Bringing elegance to aquatic life – globally.', 'aqualuxe' ); ?></p>
			</div>
			<div>
				<h2 class="text-lg font-semibold"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h2>
				<?php wp_nav_menu( [ 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'space-y-2', 'fallback_cb' => '__return_empty_string' ] ); ?>
			</div>
			<div>
				<h2 class="text-lg font-semibold"><?php esc_html_e( 'Newsletter', 'aqualuxe' ); ?></h2>
				<form method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label for="alx-news-email" class="sr-only"><?php esc_html_e( 'Email address', 'aqualuxe' ); ?></label>
					<input id="alx-news-email" name="alx_news_email" type="email" required class="border p-2 w-full" placeholder="<?php esc_attr_e( 'you@example.com', 'aqualuxe' ); ?>" />
					<button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
					<?php wp_nonce_field( 'alx_newsletter', '_alx_news' ); ?>
				</form>
			</div>
		</div>
		<p class="mt-6 text-sm text-gray-500">&copy; <?php echo esc_html( date( 'Y' ) ); ?> AquaLuxe</p>
	</div>
</footer>
<?php do_action( 'aqualuxe_after_footer' ); ?>
<?php wp_footer(); ?>
</body>
</html>
