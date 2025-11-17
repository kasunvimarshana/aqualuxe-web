<?php
/**
 * My Account Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/myaccount.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <?php
        /**
         * My Account navigation.
         *
         * @since 2.4.0
         */
        do_action('woocommerce_account_navigation'); ?>

        <div class="woocommerce-MyAccount-content">
            <?php
            /**
             * My Account content.
             *
             * @since 2.6.0
             */
            do_action('woocommerce_account_content');
            ?>
        </div>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();