<?php
/**
 * Auction winner email
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email);
?>

<p><?php printf(__('Hi %s,', 'aqualuxe'), $winner->display_name); ?></p>

<p><strong><?php printf(__('Congratulations! You won the auction for %s.', 'aqualuxe'), $product->get_title()); ?></strong></p>

<p><?php printf(__('Your winning bid was %s.', 'aqualuxe'), wc_price(aqualuxe_auctions_get_current_price($product))); ?></p>

<p><?php esc_html_e('To complete your purchase, please click the button below.', 'aqualuxe'); ?></p>

<p>
    <a href="<?php echo esc_url(add_query_arg('add-to-cart', $product->get_id(), wc_get_cart_url())); ?>" class="button"><?php esc_html_e('Complete Purchase', 'aqualuxe'); ?></a>
</p>

<?php
/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);