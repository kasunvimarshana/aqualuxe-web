<?php
?>
	</div><!-- #content -->
	<footer class="site-footer" role="contentinfo">
		<div class="container mx-auto px-4 py-8 text-center text-sm opacity-80">
			<p>&copy; <?php echo function_exists('esc_html') ? call_user_func('esc_html', date('Y') ) : date('Y'); ?> AquaLuxe. <?php echo function_exists('esc_html__') ? call_user_func('esc_html__', 'All rights reserved.', 'aqualuxe' ) : 'All rights reserved.'; ?></p>
		</div>
	</footer>
	<?php if ( function_exists('wp_footer') ) { call_user_func('wp_footer'); } ?>
</body>
</html>
