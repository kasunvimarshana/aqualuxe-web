<?php
/**
 * Single service location template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get service location
$location = isset($args['location']) ? $args['location'] : '';

// Check if we have a location
if ($location) :
?>
<div class="aqualuxe-single-service-meta-item aqualuxe-single-service-meta-location">
    <div class="aqualuxe-single-service-meta-label"><?php esc_html_e('Location', 'aqualuxe'); ?></div>
    <div class="aqualuxe-single-service-meta-value">
        <?php echo esc_html($location); ?>
    </div>
</div>
<?php endif; ?>