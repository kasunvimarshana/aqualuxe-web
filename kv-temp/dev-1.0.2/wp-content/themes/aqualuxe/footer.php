<?php

/**
 * Footer template
 *
 * @package aqualuxe
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
</div><!-- #content -->
<?php do_action('aqualuxe_before_footer'); ?>
<?php do_action('aqualuxe_footer'); ?>
<?php do_action('aqualuxe_after_footer'); ?>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>

</html>