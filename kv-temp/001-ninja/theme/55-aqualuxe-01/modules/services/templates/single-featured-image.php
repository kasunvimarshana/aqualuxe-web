<?php
/**
 * Single service featured image template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if we have a featured image
if (has_post_thumbnail()) :
?>
<div class="aqualuxe-single-service-featured-image">
    <?php the_post_thumbnail('large'); ?>
</div>
<?php endif; ?>