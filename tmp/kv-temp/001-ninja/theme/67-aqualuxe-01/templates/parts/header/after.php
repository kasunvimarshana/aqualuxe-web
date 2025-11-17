<?php
/**
 * Template part for displaying header after content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="header-after">
    <?php
    // Display mobile navigation
    aqualuxe_mobile_navigation();
    
    // Display mini cart if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) && function_exists( 'aqualuxe_mini_cart' ) ) {
        aqualuxe_mini_cart();
    }
    ?>
</div>