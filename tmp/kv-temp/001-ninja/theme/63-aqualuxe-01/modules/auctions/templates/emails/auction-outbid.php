<?php
/**
 * Auction outbid email
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

<p><?php printf(__('You have been outbid on %s.', 'aqualuxe'), $product->get_title()); ?></p>

<p><?php printf(__('The current highest bid is %s.', 'aqualuxe'), wc_price($amount)); ?></p>

<p><?php esc_html_e('Place a new bid to get back in the auction!', 'aqualuxe'); ?></p>

<p>
    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="button"><?php esc_html_e('Place New Bid', 'aqualuxe'); ?></a>
</p>

<?php
/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);