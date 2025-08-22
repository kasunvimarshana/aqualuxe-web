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

// Custom hook before account content
do_action( 'aqualuxe_before_account_content' );
?>

<div class="account-content-area">
    <?php
    /**
     * My Account navigation.
     *
     * @since 2.6.0
     */
    do_action( 'woocommerce_account_navigation' );
    ?>

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
</div>

<?php
// Custom hook after account content
do_action( 'aqualuxe_after_account_content' );