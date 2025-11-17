<?php
/**
 * Footer template.
 *
 * @package Aqualuxe
 */
if (!defined('ABSPATH')) { exit; }
?>
</div><!-- #content -->
<footer class="site-footer" role="contentinfo">
	<div class="container">
		<p>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
