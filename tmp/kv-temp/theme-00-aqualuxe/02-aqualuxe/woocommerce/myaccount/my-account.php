<?php
/**
 * My Account page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

do_action( 'woocommerce_account_navigation' ); ?>

<div class="woocommerce-MyAccount-content">
    <?php
    /**
     * My Account content.
     *
     * @since 2.6.0
     */
    do_action( 'woocommerce_account_content' );
    ?>
</div>