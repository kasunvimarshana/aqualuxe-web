<?php
/**
 * WooCommerce Content Wrapper End
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

        <?php
        /**
         * Hook: aqualuxe_wc_after_main_content
         */
        do_action('aqualuxe_wc_after_main_content');
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
/**
 * Hook: aqualuxe_wc_sidebar
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action('aqualuxe_wc_sidebar');