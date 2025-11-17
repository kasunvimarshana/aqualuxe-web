<?php
/**
 * Single service duration template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get service duration
$duration = isset($args['duration']) ? $args['duration'] : '';

// Check if we have a duration
if ($duration) :
?>
<div class="aqualuxe-single-service-meta-item aqualuxe-single-service-meta-duration">
    <div class="aqualuxe-single-service-meta-label"><?php esc_html_e('Duration', 'aqualuxe'); ?></div>
    <div class="aqualuxe-single-service-meta-value">
        <?php echo esc_html($duration); ?>
    </div>
</div>
<?php endif; ?>