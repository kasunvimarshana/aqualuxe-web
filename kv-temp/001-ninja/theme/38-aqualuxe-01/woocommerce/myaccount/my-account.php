<?php
/**
 * My Account page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_navigation' ); ?>

<div class="woocommerce-MyAccount-content">
    <div class="account-content-wrapper">
        <?php
        /**
         * Hook: aqualuxe_before_account_content.
         */
        do_action( 'aqualuxe_before_account_content' );
        
        /**
         * My Account content.
         *
         * @since 2.6.0
         */
        do_action( 'woocommerce_account_content' );
        
        /**
         * Hook: aqualuxe_after_account_content.
         */
        do_action( 'aqualuxe_after_account_content' );
        ?>
    </div>
</div>