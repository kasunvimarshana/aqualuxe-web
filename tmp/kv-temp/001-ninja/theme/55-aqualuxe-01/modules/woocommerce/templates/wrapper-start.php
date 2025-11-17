<?php
/**
 * WooCommerce Content Wrapper Start
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <?php
        /**
         * Hook: aqualuxe_wc_before_main_content
         *
         * @hooked aqualuxe_wc_breadcrumb - 10
         */
        do_action('aqualuxe_wc_before_main_content');
        ?>