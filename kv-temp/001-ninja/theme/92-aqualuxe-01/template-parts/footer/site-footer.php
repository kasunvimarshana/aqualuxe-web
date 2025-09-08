<footer id="colophon" class="site-footer bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 py-12">
	<div class="container mx-auto px-4">
		<div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
			
			<!-- About Widget Area -->
			<div class="footer-widget">
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<?php dynamic_sidebar( 'footer-1' ); ?>
				<?php else : ?>
					<h4 class="font-bold text-lg mb-4 text-gray-900 dark:text-white"><?php bloginfo( 'name' ); ?></h4>
					<p><?php bloginfo( 'description' ); ?></p>
				<?php endif; ?>
			</div>

			<!-- Links Widget Area -->
			<div class="footer-widget">
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<?php dynamic_sidebar( 'footer-2' ); ?>
				<?php endif; ?>
			</div>

			<!-- Social & Contact Widget Area -->
			<div class="footer-widget">
				<h4 class="font-bold text-lg mb-4 text-gray-900 dark:text-white"><?php esc_html_e( 'Connect With Us', 'aqualuxe' ); ?></h4>
				<?php
				if ( function_exists( 'aqualuxe_the_social_icons' ) ) {
					aqualuxe_the_social_icons();
				}
				?>
			</div>

		</div>

		<div class="site-info mt-12 pt-8 border-t border-gray-200 dark:border-gray-700 text-center text-sm">
			&copy; <?php echo date_i18n( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All Rights Reserved.', 'aqualuxe' ); ?>
		</div><!-- .site-info -->
	</div>
</footer><!-- #colophon -->
