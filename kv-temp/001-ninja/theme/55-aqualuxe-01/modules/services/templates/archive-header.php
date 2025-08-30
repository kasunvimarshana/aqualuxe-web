<?php
/**
 * Services archive header template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="aqualuxe-services-archive-header">
    <h1 class="aqualuxe-services-archive-title">
        <?php
        if (is_tax('service_category')) {
            /* translators: %s: category name */
            printf(esc_html__('Service Category: %s', 'aqualuxe'), single_term_title('', false));
        } elseif (is_tax('service_tag')) {
            /* translators: %s: tag name */
            printf(esc_html__('Service Tag: %s', 'aqualuxe'), single_term_title('', false));
        } else {
            esc_html_e('Services', 'aqualuxe');
        }
        ?>
    </h1>
</div>