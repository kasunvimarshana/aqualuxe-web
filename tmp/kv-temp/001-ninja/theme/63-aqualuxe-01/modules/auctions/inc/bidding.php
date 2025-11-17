<?php
/**
 * Auction bidding functionality
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Initialize bidding system
 */
function aqualuxe_auctions_initialize_bidding() {
    // Create database tables if they don't exist
    aqualuxe_auctions_create_tables();
    
    // Register AJAX actions
    add_action('wp_ajax_aqualuxe_place_bid', 'aqualuxe_auctions_ajax_place_bid');
    add_action('wp_ajax_nopriv_aqualuxe_place_bid', 'aqualuxe_auctions_ajax_place_bid');
    
    // Add bid form to single product page
    add_action('woocommerce_single_product_summary', 'aqualuxe_auctions_bid_form', 25);
    
    // Add auction information to single product page
    add_action('woocommerce_single_product_summary', 'aqualuxe_auctions_product_information', 15);
    
    // Add auction history to single product page
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_auctions_bid_history', 15);
    
    // Handle auction end
    add_action('aqualuxe_check_ended_auctions', 'aqualuxe_auctions_check_ended_auctions');
    
    // Handle auction winner
    add_action('aqualuxe_auction_winner_set', 'aqualuxe_auctions_handle_winner', 10, 2);
    
    // Add auction to cart
    add_filter('woocommerce_add_to_cart_validation', 'aqualuxe_auctions_add_to_cart_validation', 10, 3);
    add_filter('woocommerce_add_cart_item_data', 'aqualuxe_auctions_add_cart_item_data', 10, 3);
    add_filter('woocommerce_get_cart_item_from_session', 'aqualuxe_auctions_get_cart_item_from_session', 10, 2);
    
    // Prevent direct checkout for auction products
    add_filter('woocommerce_add_to_cart_redirect', 'aqualuxe_auctions_add_to_cart_redirect', 10, 2);
}

/**
 * Display auction bid form
 */
function aqualuxe_auctions_bid_form() {
    global $product;
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return;
    }
    
    $status = aqualuxe_auctions_get_status($product);
    
    // Only show bid form for active auctions
    if ($status !== 'active') {
        return;
    }
    
    $current_price = aqualuxe_auctions_get_current_price($product);
    $minimum_bid = aqualuxe_auctions_get_minimum_bid($product);
    $bid_increment = aqualuxe_auctions_get_bid_increment($product);
    
    // Check if user is logged in
    $user_id = get_current_user_id();
    $can_bid = $user_id > 0;
    
    // Get highest bidder
    $highest_bid = aqualuxe_auctions_get_highest_bid($product);
    $is_highest_bidder = $highest_bid && $highest_bid['user_id'] == $user_id;
    
    ?>
    <div class="auction-bid-form">
        <h3><?php esc_html_e('Place Bid', 'aqualuxe'); ?></h3>
        
        <?php if ($can_bid) : ?>
            <?php if ($is_highest_bidder) : ?>
                <p class="auction-highest-bidder">
                    <?php esc_html_e('You are currently the highest bidder!', 'aqualuxe'); ?>
                </p>
            <?php endif; ?>
            
            <form class="auction-bid-form" method="post">
                <div class="auction-bid-info">
                    <p class="auction-current-bid">
                        <span class="label"><?php esc_html_e('Current bid:', 'aqualuxe'); ?></span>
                        <span class="value"><?php echo wc_price($current_price); ?></span>
                    </p>
                    
                    <p class="auction-minimum-bid">
                        <span class="label"><?php esc_html_e('Minimum bid:', 'aqualuxe'); ?></span>
                        <span class="value"><?php echo wc_price($minimum_bid); ?></span>
                    </p>
                    
                    <p class="auction-bid-increment">
                        <span class="label"><?php esc_html_e('Bid increment:', 'aqualuxe'); ?></span>
                        <span class="value"><?php echo wc_price($bid_increment); ?></span>
                    </p>
                </div>
                
                <div class="auction-bid-amount">
                    <label for="bid_amount"><?php esc_html_e('Your bid:', 'aqualuxe'); ?></label>
                    <input type="number" name="bid_amount" id="bid_amount" class="input-text" step="any" min="<?php echo esc_attr($minimum_bid); ?>" value="<?php echo esc_attr($minimum_bid); ?>" required />
                    <?php echo get_woocommerce_currency_symbol(); ?>
                </div>
                
                <div class="auction-bid-button">
                    <button type="submit" class="button alt"><?php esc_html_e('Place Bid', 'aqualuxe'); ?></button>
                    <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>" />
                    <input type="hidden" name="auction_nonce" value="<?php echo wp_create_nonce('aqualuxe_place_bid'); ?>" />
                </div>
                
                <div class="auction-bid-response"></div>
            </form>
        <?php else : ?>
            <p class="auction-login-to-bid">
                <?php
                echo wp_kses_post(
                    sprintf(
                        __('You must be <a href="%s">logged in</a> to place a bid.', 'aqualuxe'),
                        esc_url(wc_get_page_permalink('myaccount'))
                    )
                );
                ?>
            </p>
        <?php endif; ?>
        
        <?php if ($product->get_auction_allow_buy_now()) : ?>
            <div class="auction-buy-now">
                <h3><?php esc_html_e('Buy Now', 'aqualuxe'); ?></h3>
                <p class="auction-buy-now-price">
                    <span class="label"><?php esc_html_e('Buy now price:', 'aqualuxe'); ?></span>
                    <span class="value"><?php echo wc_price($product->get_auction_buy_now_price()); ?></span>
                </p>
                
                <?php woocommerce_template_loop_add_to_cart(); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display auction product information
 */
function aqualuxe_auctions_product_information() {
    global $product;
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return;
    }
    
    $status = aqualuxe_auctions_get_status($product);
    $start_time = aqualuxe_auctions_get_start_time($product);
    $end_time = aqualuxe_auctions_get_end_time($product);
    $current_price = aqualuxe_auctions_get_current_price($product);
    $bid_count = aqualuxe_auctions_get_bid_count($product);
    $has_reserve = aqualuxe_auctions_has_reserve_price($product);
    $reserve_met = aqualuxe_auctions_is_reserve_met($product);
    
    ?>
    <div class="auction-information">
        <h3><?php esc_html_e('Auction Information', 'aqualuxe'); ?></h3>
        
        <div class="auction-status">
            <span class="label"><?php esc_html_e('Status:', 'aqualuxe'); ?></span>
            <span class="value auction-status-<?php echo esc_attr($status); ?>">
                <?php
                switch ($status) {
                    case 'scheduled':
                        esc_html_e('Scheduled', 'aqualuxe');
                        break;
                    case 'active':
                        esc_html_e('Active', 'aqualuxe');
                        break;
                    case 'ended':
                        esc_html_e('Ended', 'aqualuxe');
                        break;
                    default:
                        esc_html_e('Unknown', 'aqualuxe');
                        break;
                }
                ?>
            </span>
        </div>
        
        <?php if ($start_time) : ?>
            <div class="auction-start-time">
                <span class="label"><?php esc_html_e('Start time:', 'aqualuxe'); ?></span>
                <span class="value"><?php echo esc_html(aqualuxe_auctions_format_time($start_time)); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($end_time) : ?>
            <div class="auction-end-time">
                <span class="label"><?php esc_html_e('End time:', 'aqualuxe'); ?></span>
                <span class="value"><?php echo esc_html(aqualuxe_auctions_format_time($end_time)); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($status === 'active') : ?>
            <div class="auction-time-remaining" data-seconds="<?php echo esc_attr(aqualuxe_auctions_get_time_remaining($product, false)); ?>">
                <span class="label"><?php esc_html_e('Time remaining:', 'aqualuxe'); ?></span>
                <span class="value"><?php echo esc_html(aqualuxe_auctions_get_time_remaining($product)); ?></span>
            </div>
        <?php endif; ?>
        
        <div class="auction-current-bid">
            <span class="label"><?php esc_html_e('Current bid:', 'aqualuxe'); ?></span>
            <span class="value"><?php echo wc_price($current_price); ?></span>
        </div>
        
        <div class="auction-bid-count">
            <span class="label"><?php esc_html_e('Bids:', 'aqualuxe'); ?></span>
            <span class="value"><?php echo esc_html($bid_count); ?></span>
        </div>
        
        <?php if ($has_reserve) : ?>
            <div class="auction-reserve-price">
                <span class="label"><?php esc_html_e('Reserve price:', 'aqualuxe'); ?></span>
                <span class="value">
                    <?php
                    if ($reserve_met) {
                        esc_html_e('Met', 'aqualuxe');
                    } else {
                        esc_html_e('Not met', 'aqualuxe');
                    }
                    ?>
                </span>
            </div>
        <?php endif; ?>
        
        <?php if ($status === 'ended') : ?>
            <?php
            $winner_id = aqualuxe_auctions_get_winner($product);
            
            if ($winner_id) {
                $winner = get_user_by('id', $winner_id);
                $winner_name = $winner ? $winner->display_name : __('Unknown', 'aqualuxe');
                
                if ($winner_id === get_current_user_id()) {
                    ?>
                    <div class="auction-winner auction-winner-you">
                        <span class="label"><?php esc_html_e('Winner:', 'aqualuxe'); ?></span>
                        <span class="value"><?php esc_html_e('You won this auction!', 'aqualuxe'); ?></span>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="auction-winner">
                        <span class="label"><?php esc_html_e('Winner:', 'aqualuxe'); ?></span>
                        <span class="value"><?php echo esc_html($winner_name); ?></span>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="auction-no-winner">
                    <span class="label"><?php esc_html_e('Winner:', 'aqualuxe'); ?></span>
                    <span class="value"><?php esc_html_e('No winner', 'aqualuxe'); ?></span>
                </div>
                <?php
            }
            ?>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display auction bid history
 */
function aqualuxe_auctions_bid_history() {
    global $product;
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return;
    }
    
    $bids = aqualuxe_auctions_get_bids($product, 10);
    $bid_count = aqualuxe_auctions_get_bid_count($product);
    
    ?>
    <div class="auction-bid-history">
        <h2><?php esc_html_e('Bid History', 'aqualuxe'); ?></h2>
        
        <?php if (!empty($bids)) : ?>
            <table class="auction-bids">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Bidder', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Amount', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($bids as $bid) {
                        $user = get_user_by('id', $bid['user_id']);
                        $username = $user ? $user->display_name : __('Unknown', 'aqualuxe');
                        
                        // Mask username except for the user's own bids
                        if ($bid['user_id'] !== get_current_user_id()) {
                            $username = substr($username, 0, 1) . str_repeat('*', strlen($username) - 2) . substr($username, -1);
                        }
                        
                        echo '<tr>';
                        echo '<td>' . esc_html($username) . '</td>';
                        echo '<td>' . wc_price($bid['amount']) . '</td>';
                        echo '<td>' . esc_html(aqualuxe_auctions_format_time($bid['date_created'])) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            
            <?php if ($bid_count > 10) : ?>
                <p class="auction-more-bids">
                    <?php
                    echo wp_kses_post(
                        sprintf(
                            __('Showing %1$d of %2$d bids. <a href="%3$s">View all bids</a>.', 'aqualuxe'),
                            count($bids),
                            $bid_count,
                            esc_url(add_query_arg('show_bids', '1', get_permalink($product->get_id())))
                        )
                    );
                    ?>
                </p>
            <?php endif; ?>
        <?php else : ?>
            <p class="auction-no-bids"><?php esc_html_e('No bids yet. Be the first to bid!', 'aqualuxe'); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * AJAX handler for placing bids
 */
function aqualuxe_auctions_ajax_place_bid() {
    // Check nonce
    if (!isset($_POST['auction_nonce']) || !wp_verify_nonce($_POST['auction_nonce'], 'aqualuxe_place_bid')) {
        wp_send_json_error(array(
            'message' => __('Security check failed. Please refresh the page and try again.', 'aqualuxe'),
        ));
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => __('You must be logged in to place a bid.', 'aqualuxe'),
        ));
    }
    
    // Check if product ID and bid amount are set
    if (!isset($_POST['product_id']) || !isset($_POST['bid_amount'])) {
        wp_send_json_error(array(
            'message' => __('Invalid bid data. Please refresh the page and try again.', 'aqualuxe'),
        ));
    }
    
    $product_id = absint($_POST['product_id']);
    $bid_amount = (float) $_POST['bid_amount'];
    $user_id = get_current_user_id();
    
    // Check if product exists and is an auction
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        wp_send_json_error(array(
            'message' => __('Invalid product. Please refresh the page and try again.', 'aqualuxe'),
        ));
    }
    
    // Check if auction is active
    $status = aqualuxe_auctions_get_status($product);
    
    if ($status !== 'active') {
        wp_send_json_error(array(
            'message' => __('This auction is not active.', 'aqualuxe'),
        ));
    }
    
    // Check if bid amount is valid
    $minimum_bid = aqualuxe_auctions_get_minimum_bid($product);
    
    if ($bid_amount < $minimum_bid) {
        wp_send_json_error(array(
            'message' => sprintf(
                __('Your bid is too low. The minimum bid is %s.', 'aqualuxe'),
                wc_price($minimum_bid)
            ),
        ));
    }
    
    // Check if user is already the highest bidder
    $highest_bid = aqualuxe_auctions_get_highest_bid($product);
    
    if ($highest_bid && $highest_bid['user_id'] == $user_id && $bid_amount <= $highest_bid['amount']) {
        wp_send_json_error(array(
            'message' => __('You are already the highest bidder. Please place a higher bid.', 'aqualuxe'),
        ));
    }
    
    // Place bid
    $bid_id = aqualuxe_auctions_place_bid($product_id, $user_id, $bid_amount);
    
    if (!$bid_id) {
        wp_send_json_error(array(
            'message' => __('There was an error placing your bid. Please try again.', 'aqualuxe'),
        ));
    }
    
    // Get updated product data
    $current_price = aqualuxe_auctions_get_current_price($product);
    $bid_count = aqualuxe_auctions_get_bid_count($product);
    $minimum_bid = aqualuxe_auctions_get_minimum_bid($product);
    
    wp_send_json_success(array(
        'message' => __('Your bid has been placed successfully!', 'aqualuxe'),
        'current_price' => wc_price($current_price),
        'minimum_bid' => $minimum_bid,
        'bid_count' => $bid_count,
    ));
}

/**
 * Place a bid on an auction
 *
 * @param int $product_id Product ID
 * @param int $user_id User ID
 * @param float $amount Bid amount
 * @return int|false Bid ID or false on failure
 */
function aqualuxe_auctions_place_bid($product_id, $user_id, $amount) {
    global $wpdb;
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    // Insert bid
    $result = $wpdb->insert(
        $bids_table,
        array(
            'product_id' => $product_id,
            'user_id' => $user_id,
            'amount' => $amount,
            'date_created' => current_time('mysql'),
        ),
        array(
            '%d',
            '%d',
            '%f',
            '%s',
        )
    );
    
    if (!$result) {
        return false;
    }
    
    $bid_id = $wpdb->insert_id;
    
    // Update product price
    update_post_meta($product_id, '_price', $amount);
    
    // Trigger bid placed action
    do_action('aqualuxe_auction_bid_placed', $product_id, $user_id, $amount, $bid_id);
    
    return $bid_id;
}

/**
 * Check for ended auctions
 */
function aqualuxe_auctions_check_ended_auctions() {
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_type',
                'field' => 'slug',
                'terms' => 'auction',
            ),
        ),
        'meta_query' => array(
            array(
                'key' => '_auction_end_time',
                'value' => current_time('mysql'),
                'compare' => '<=',
                'type' => 'DATETIME',
            ),
            array(
                'key' => '_auction_ended',
                'compare' => 'NOT EXISTS',
            ),
        ),
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product_id = get_the_ID();
            
            // Mark auction as ended
            update_post_meta($product_id, '_auction_ended', 'yes');
            
            // Set winner if there are bids
            $product = wc_get_product($product_id);
            $winner_id = aqualuxe_auctions_get_winner($product);
            
            // Trigger auction ended action
            do_action('aqualuxe_auction_ended', $product_id, $winner_id);
        }
    }
    
    wp_reset_postdata();
}

/**
 * Handle auction winner
 *
 * @param int $product_id Product ID
 * @param int $winner_id Winner user ID
 */
function aqualuxe_auctions_handle_winner($product_id, $winner_id) {
    // Send winner notification
    $product = wc_get_product($product_id);
    $user = get_user_by('id', $winner_id);
    
    if (!$product || !$user) {
        return;
    }
    
    // Send winner email
    $to = $user->user_email;
    $subject = sprintf(__('You won the auction for %s', 'aqualuxe'), $product->get_title());
    
    $message = sprintf(
        __('Congratulations! You won the auction for %1$s with a bid of %2$s.', 'aqualuxe'),
        $product->get_title(),
        wc_price(aqualuxe_auctions_get_current_price($product))
    );
    
    $message .= "\n\n";
    
    $message .= sprintf(
        __('To complete your purchase, please visit the product page: %s', 'aqualuxe'),
        get_permalink($product_id)
    );
    
    wp_mail($to, $subject, $message);
    
    // Send admin notification
    $admin_email = get_option('admin_email');
    $admin_subject = sprintf(__('Auction ended: %s', 'aqualuxe'), $product->get_title());
    
    $admin_message = sprintf(
        __('The auction for %1$s has ended. The winner is %2$s with a bid of %3$s.', 'aqualuxe'),
        $product->get_title(),
        $user->display_name,
        wc_price(aqualuxe_auctions_get_current_price($product))
    );
    
    $admin_message .= "\n\n";
    
    $admin_message .= sprintf(
        __('View product: %s', 'aqualuxe'),
        get_permalink($product_id)
    );
    
    wp_mail($admin_email, $admin_subject, $admin_message);
}

/**
 * Validate adding auction product to cart
 *
 * @param bool $passed Whether validation passed
 * @param int $product_id Product ID
 * @param int $quantity Quantity
 * @return bool
 */
function aqualuxe_auctions_add_to_cart_validation($passed, $product_id, $quantity) {
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return $passed;
    }
    
    // Check if auction is active
    $status = aqualuxe_auctions_get_status($product);
    
    if ($status !== 'active') {
        wc_add_notice(__('This auction is not active.', 'aqualuxe'), 'error');
        return false;
    }
    
    // Check if buy now is allowed
    if (!$product->get_auction_allow_buy_now()) {
        wc_add_notice(__('This auction does not allow buy now.', 'aqualuxe'), 'error');
        return false;
    }
    
    return $passed;
}

/**
 * Add auction data to cart item
 *
 * @param array $cart_item_data Cart item data
 * @param int $product_id Product ID
 * @param int $variation_id Variation ID
 * @return array
 */
function aqualuxe_auctions_add_cart_item_data($cart_item_data, $product_id, $variation_id) {
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return $cart_item_data;
    }
    
    // Add auction data to cart item
    $cart_item_data['auction_data'] = array(
        'is_auction' => true,
        'buy_now_price' => $product->get_auction_buy_now_price(),
    );
    
    return $cart_item_data;
}

/**
 * Get auction cart item from session
 *
 * @param array $cart_item Cart item
 * @param array $values Cart item values
 * @return array
 */
function aqualuxe_auctions_get_cart_item_from_session($cart_item, $values) {
    if (isset($values['auction_data'])) {
        $cart_item['auction_data'] = $values['auction_data'];
    }
    
    return $cart_item;
}

/**
 * Redirect to cart after adding auction product
 *
 * @param string $url Redirect URL
 * @param WC_Product $product Product object
 * @return string
 */
function aqualuxe_auctions_add_to_cart_redirect($url, $product) {
    if (aqualuxe_auctions_is_auction_product($product)) {
        return wc_get_cart_url();
    }
    
    return $url;
}