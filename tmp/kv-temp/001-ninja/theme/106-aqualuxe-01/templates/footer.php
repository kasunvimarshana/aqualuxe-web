<?php
/** Footer template */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</main>
<footer class="site-footer mt-12" role="contentinfo">
	<div class="wrap p-6 text-center text-sm text-slate-500">
		<p>&copy; <?php echo esc_html( date('Y') ); ?> <?php bloginfo('name'); ?>. <?php esc_html_e( 'All rights reserved.', 'aqualuxe' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
