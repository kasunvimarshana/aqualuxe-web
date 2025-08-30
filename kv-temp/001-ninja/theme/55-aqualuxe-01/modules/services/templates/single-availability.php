<?php
/**
 * Single service availability template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get service availability
$availability = isset($args['availability']) ? $args['availability'] : '';

// Check if we have availability
if ($availability) :
?>
<div class="aqualuxe-single-service-meta-item aqualuxe-single-service-meta-availability">
    <div class="aqualuxe-single-service-meta-label"><?php esc_html_e('Availability', 'aqualuxe'); ?></div>
    <div class="aqualuxe-single-service-meta-value">
        <?php echo esc_html($availability); ?>
    </div>
</div>
<?php endif; ?>