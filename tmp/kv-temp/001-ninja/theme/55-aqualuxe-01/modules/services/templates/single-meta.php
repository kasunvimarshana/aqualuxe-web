<?php
/**
 * Single service meta template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="aqualuxe-single-service-meta">
    <?php
    /**
     * Hook: aqualuxe_service_meta
     *
     * @hooked aqualuxe_service_price - 10
     * @hooked aqualuxe_service_duration - 20
     * @hooked aqualuxe_service_location - 30
     * @hooked aqualuxe_service_availability - 40
     * @hooked aqualuxe_service_categories - 50
     * @hooked aqualuxe_service_tags - 60
     */
    do_action('aqualuxe_service_meta');
    ?>
</div>