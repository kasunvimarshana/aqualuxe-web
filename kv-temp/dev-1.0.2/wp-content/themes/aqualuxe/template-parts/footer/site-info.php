<?php

/**
 * Site info
 *
 * @package aqualuxe
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="site-info">
    <?php echo esc_html(apply_filters('aqualuxe_copyright_text', $content = '&copy; ' . get_bloginfo('name') . ' ' . date('Y'))); ?>
</div>