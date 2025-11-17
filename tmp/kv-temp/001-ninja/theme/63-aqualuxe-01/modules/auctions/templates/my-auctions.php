<?php
/**
 * My Auctions template
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get user auctions
$user_id = get_current_user_id();
$status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';
$auctions = aqualuxe_auctions_get_user_auctions($user_id, $status);

?>
<div class="aqualuxe-my-auctions">
    <ul class="aqualuxe-tabs">
        <li class="<?php echo $status === 'all' ? 'active' : ''; ?>">
            <a href="<?php echo esc_url(add_query_arg('status', 'all')); ?>">
                <?php esc_html_e('All Auctions', 'aqualuxe'); ?>
            </a>
        </li>
        <li class="<?php echo $status === 'active' ? 'active' : ''; ?>">
            <a href="<?php echo esc_url(add_query_arg('status', 'active')); ?>">
                <?php esc_html_e('Active Auctions', 'aqualuxe'); ?>
            </a>
        </li>
        <li class="<?php echo $status === 'won' ? 'active' : ''; ?>">
            <a href="<?php echo esc_url(add_query_arg('status', 'won')); ?>">
                <?php esc_html_e('Won Auctions', 'aqualuxe'); ?>
            </a>
        </li>
        <li class="<?php echo $status === 'ended' ? 'active' : ''; ?>">
            <a href="<?php echo esc_url(add_query_arg('status', 'ended')); ?>">
                <?php esc_html_e('Ended Auctions', 'aqualuxe'); ?>
            </a>
        </li>
    </ul>
    
    <div class="aqualuxe-tab-content">
        <?php if (!empty($auctions)) : ?>
            <table class="aqualuxe-auctions-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Auction', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Current Bid', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Your Bid', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('End Time', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($auctions as $auction_post) {
                        $product = wc_get_product($auction_post->ID);
                        
                        if (!$product) {
                            continue;
                        }
                        
                        $current_price = aqualuxe_auctions_get_current_price($product);
                        $status = aqualuxe_auctions_get_status($product);
                        $end_time = aqualuxe_auctions_get_end_time($product);
                        $is_winner = aqualuxe_auctions_is_user_winner($product, $user_id);
                        
                        // Get user's highest bid
                        global $wpdb;
                        $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
                        
                        $user_bid = $wpdb->get_var($wpdb->prepare(
                            "SELECT MAX(amount) FROM {$bids_table} WHERE product_id = %d AND user_id = %d",
                            $product->get_id(),
                            $user_id
                        ));
                        
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                                    <?php echo esc_html($product->get_title()); ?>
                                </a>
                            </td>
                            <td><?php echo wc_price($current_price); ?></td>
                            <td><?php echo $user_bid ? wc_price($user_bid) : '&mdash;'; ?></td>
                            <td>
                                <?php
                                switch ($status) {
                                    case 'scheduled':
                                        esc_html_e('Scheduled', 'aqualuxe');
                                        break;
                                    case 'active':
                                        esc_html_e('Active', 'aqualuxe');
                                        break;
                                    case 'ended':
                                        if ($is_winner) {
                                            echo '<span class="auction-won">' . esc_html__('Won', 'aqualuxe') . '</span>';
                                        } else {
                                            esc_html_e('Ended', 'aqualuxe');
                                        }
                                        break;
                                    default:
                                        esc_html_e('Unknown', 'aqualuxe');
                                        break;
                                }
                                ?>
                            </td>
                            <td><?php echo esc_html(aqualuxe_auctions_format_time($end_time)); ?></td>
                            <td>
                                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="button">
                                    <?php esc_html_e('View', 'aqualuxe'); ?>
                                </a>
                                
                                <?php if ($is_winner) : ?>
                                    <a href="<?php echo esc_url(add_query_arg('add-to-cart', $product->get_id())); ?>" class="button">
                                        <?php esc_html_e('Purchase', 'aqualuxe'); ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php esc_html_e('No auctions found.', 'aqualuxe'); ?></p>
            
            <p>
                <a href="<?php echo esc_url(aqualuxe_auctions_get_archive_url()); ?>" class="button">
                    <?php esc_html_e('Browse Auctions', 'aqualuxe'); ?>
                </a>
            </p>
        <?php endif; ?>
    </div>
</div>