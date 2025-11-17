<?php
/**
 * Auction ended email
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

<p><?php printf(__('The auction for %s has ended.', 'aqualuxe'), $product->get_title()); ?></p>

<?php if ($is_winner) : ?>
    <p><strong><?php esc_html_e('Congratulations! You are the winner of this auction.', 'aqualuxe'); ?></strong></p>
    
    <p><?php esc_html_e('To complete your purchase, please click the button below.', 'aqualuxe'); ?></p>
    
    <p>
        <a href="<?php echo esc_url(add_query_arg('add-to-cart', $product->get_id(), wc_get_cart_url())); ?>" class="button"><?php esc_html_e('Complete Purchase', 'aqualuxe'); ?></a>
    </p>
<?php else : ?>
    <?php if ($winner) : ?>
        <p><?php esc_html_e('Unfortunately, you did not win this auction.', 'aqualuxe'); ?></p>
    <?php else : ?>
        <p><?php esc_html_e('This auction ended without a winner.', 'aqualuxe'); ?></p>
    <?php endif; ?>
    
    <p><?php esc_html_e('Thank you for your participation.', 'aqualuxe'); ?></p>
    
    <p>
        <a href="<?php echo esc_url(aqualuxe_auctions_get_archive_url()); ?>" class="button"><?php esc_html_e('Browse More Auctions', 'aqualuxe'); ?></a>
    </p>
<?php endif; ?>

<?php
/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);