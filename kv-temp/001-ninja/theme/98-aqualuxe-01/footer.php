<?php
/** Footer template */
?>
</main>
<footer class="mt-10 border-t border-slate-200/60 dark:border-slate-700/60">
	<div class="container mx-auto px-4 py-8 text-sm text-slate-600 dark:text-slate-300">
		<nav aria-label="Footer Navigation" class="mb-4">
			<?php wp_nav_menu( [ 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'flex flex-wrap gap-4', 'fallback_cb' => '__return_false' ] ); ?>
		</nav>
		<p>&copy; <?php echo esc_html( date('Y') ); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
