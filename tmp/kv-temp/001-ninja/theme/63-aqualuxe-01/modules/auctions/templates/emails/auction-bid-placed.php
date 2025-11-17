<?php
/**
 * Auction bid placed email
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

<p><?php printf(__('Hi %s,', 'aqualuxe'), $user->display_name); ?></p>

<p><?php printf(__('Your bid of %1$s on %2$s has been placed successfully.', 'aqualuxe'), wc_price($amount), $product->get_title()); ?></p>

<p><?php esc_html_e('You will be notified if you are outbid.', 'aqualuxe'); ?></p>

<p>
    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="button"><?php esc_html_e('View Auction', 'aqualuxe'); ?></a>
</p>

<?php
/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);