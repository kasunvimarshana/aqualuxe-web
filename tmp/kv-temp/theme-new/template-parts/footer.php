<?php

/**
 * Footer template
 *
 * @package AquaLuxe
 */

defined('ABSPATH') || exit;
?>
<footer id="site-footer" class="site-footer">
    <div class="container">
        <div class="footer-widgets">
            <div class="footer-widget">
                <?php dynamic_sidebar('footer-1'); ?>
            </div>

            <div class="footer-widget">
                <?php dynamic_sidebar('footer-2'); ?>
            </div>

            <div class="footer-widget">
                <?php dynamic_sidebar('footer-3'); ?>
            </div>
        </div>

        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>