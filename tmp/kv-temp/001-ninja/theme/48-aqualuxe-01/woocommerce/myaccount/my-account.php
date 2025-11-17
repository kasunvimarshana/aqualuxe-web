<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
?>

<div class="woocommerce-account-wrapper grid grid-cols-1 lg:grid-cols-12 gap-8">
    <div class="woocommerce-account-nav col-span-1 lg:col-span-3">
        <?php do_action( 'woocommerce_account_navigation' ); ?>
    </div>

    <div class="woocommerce-account-content col-span-1 lg:col-span-9">
        <div class="woocommerce-MyAccount-content bg-white dark:bg-dark-light rounded-lg shadow-soft p-6">
            <?php
                /**
                 * My Account content.
                 *
                 * @since 2.6.0
                 */
                do_action( 'woocommerce_account_content' );
            ?>
        </div>
    </div>
</div>