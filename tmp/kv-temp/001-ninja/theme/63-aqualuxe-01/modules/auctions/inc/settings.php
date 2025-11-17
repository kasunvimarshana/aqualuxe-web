<?php
/**
 * Auction module settings
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register auction settings
 */
function aqualuxe_auctions_register_settings() {
    // Register settings
    aqualuxe_register_module_settings('auctions', array(
        'sections' => array(
            'general' => array(
                'title' => __('General Settings', 'aqualuxe'),
                'fields' => array(
                    'enable_auctions' => array(
                        'title' => __('Enable Auctions', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Enable auction functionality on the site.', 'aqualuxe'),
                    ),
                    'archive_page' => array(
                        'title' => __('Auctions Archive Page', 'aqualuxe'),
                        'type' => 'select',
                        'options' => aqualuxe_auctions_get_page_options(),
                        'description' => __('Select the page to display all auctions.', 'aqualuxe'),
                    ),
                    'my_auctions_page' => array(
                        'title' => __('My Auctions Page', 'aqualuxe'),
                        'type' => 'select',
                        'options' => aqualuxe_auctions_get_page_options(),
                        'description' => __('Select the page to display user auctions.', 'aqualuxe'),
                    ),
                    'dashboard_page' => array(
                        'title' => __('Auction Dashboard Page', 'aqualuxe'),
                        'type' => 'select',
                        'options' => aqualuxe_auctions_get_page_options(),
                        'description' => __('Select the page to display the auction dashboard.', 'aqualuxe'),
                    ),
                ),
            ),
            'bidding' => array(
                'title' => __('Bidding Settings', 'aqualuxe'),
                'fields' => array(
                    'default_bid_increment' => array(
                        'title' => __('Default Bid Increment', 'aqualuxe'),
                        'type' => 'number',
                        'default' => '1',
                        'description' => __('Default bid increment amount.', 'aqualuxe'),
                    ),
                    'minimum_bid_amount' => array(
                        'title' => __('Minimum Bid Amount', 'aqualuxe'),
                        'type' => 'number',
                        'default' => '0',
                        'description' => __('Minimum bid amount allowed.', 'aqualuxe'),
                    ),
                    'allow_proxy_bidding' => array(
                        'title' => __('Allow Proxy Bidding', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Allow users to set a maximum bid amount.', 'aqualuxe'),
                    ),
                    'allow_buy_now' => array(
                        'title' => __('Allow Buy Now', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Allow users to buy auction items immediately.', 'aqualuxe'),
                    ),
                    'allow_reserve_price' => array(
                        'title' => __('Allow Reserve Price', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Allow setting a reserve price for auctions.', 'aqualuxe'),
                    ),
                    'show_reserve_status' => array(
                        'title' => __('Show Reserve Status', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Show whether the reserve price has been met.', 'aqualuxe'),
                    ),
                ),
            ),
            'display' => array(
                'title' => __('Display Settings', 'aqualuxe'),
                'fields' => array(
                    'show_countdown' => array(
                        'title' => __('Show Countdown', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Show countdown timer for auctions.', 'aqualuxe'),
                    ),
                    'refresh_interval' => array(
                        'title' => __('Refresh Interval', 'aqualuxe'),
                        'type' => 'number',
                        'default' => '10',
                        'description' => __('Interval in seconds to refresh auction data.', 'aqualuxe'),
                    ),
                    'show_bid_history' => array(
                        'title' => __('Show Bid History', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Show bid history on auction pages.', 'aqualuxe'),
                    ),
                    'mask_bidder_names' => array(
                        'title' => __('Mask Bidder Names', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Mask bidder names in bid history.', 'aqualuxe'),
                    ),
                    'show_auctions_in_shop' => array(
                        'title' => __('Show Auctions in Shop', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Show auction products in the main shop page.', 'aqualuxe'),
                    ),
                ),
            ),
            'notifications' => array(
                'title' => __('Notification Settings', 'aqualuxe'),
                'fields' => array(
                    'enable_email_notifications' => array(
                        'title' => __('Enable Email Notifications', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Send email notifications for auction events.', 'aqualuxe'),
                    ),
                    'notify_on_outbid' => array(
                        'title' => __('Notify on Outbid', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Notify users when they are outbid.', 'aqualuxe'),
                    ),
                    'notify_on_win' => array(
                        'title' => __('Notify on Win', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Notify users when they win an auction.', 'aqualuxe'),
                    ),
                    'notify_on_end' => array(
                        'title' => __('Notify on End', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Notify users when an auction they bid on ends.', 'aqualuxe'),
                    ),
                    'admin_notifications' => array(
                        'title' => __('Admin Notifications', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Send notifications to admin for auction events.', 'aqualuxe'),
                    ),
                ),
            ),
            'advanced' => array(
                'title' => __('Advanced Settings', 'aqualuxe'),
                'fields' => array(
                    'auction_duration' => array(
                        'title' => __('Default Auction Duration', 'aqualuxe'),
                        'type' => 'number',
                        'default' => '7',
                        'description' => __('Default auction duration in days.', 'aqualuxe'),
                    ),
                    'extend_on_last_minute_bids' => array(
                        'title' => __('Extend on Last Minute Bids', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'yes',
                        'description' => __('Extend auction end time when bids are placed in the last minute.', 'aqualuxe'),
                    ),
                    'extension_time' => array(
                        'title' => __('Extension Time', 'aqualuxe'),
                        'type' => 'number',
                        'default' => '5',
                        'description' => __('Time in minutes to extend the auction.', 'aqualuxe'),
                    ),
                    'allow_user_auctions' => array(
                        'title' => __('Allow User Auctions', 'aqualuxe'),
                        'type' => 'checkbox',
                        'default' => 'no',
                        'description' => __('Allow users to create their own auctions.', 'aqualuxe'),
                    ),
                    'user_auction_fee' => array(
                        'title' => __('User Auction Fee', 'aqualuxe'),
                        'type' => 'number',
                        'default' => '5',
                        'description' => __('Fee percentage for user auctions.', 'aqualuxe'),
                    ),
                ),
            ),
        ),
    ));
}

/**
 * Get page options for settings
 *
 * @return array
 */
function aqualuxe_auctions_get_page_options() {
    $pages = get_pages();
    $options = array(
        '' => __('Select a page', 'aqualuxe'),
    );
    
    foreach ($pages as $page) {
        $options[$page->ID] = $page->post_title;
    }
    
    return $options;
}

/**
 * Create default auction pages
 */
function aqualuxe_auctions_create_default_pages() {
    // Check if pages already exist
    $archive_page_id = aqualuxe_get_module_option('auctions', 'archive_page', 0);
    $my_auctions_page_id = aqualuxe_get_module_option('auctions', 'my_auctions_page', 0);
    $dashboard_page_id = aqualuxe_get_module_option('auctions', 'dashboard_page', 0);
    
    // Create archive page if it doesn't exist
    if (!$archive_page_id) {
        $archive_page_id = wp_insert_post(array(
            'post_title' => __('Auctions', 'aqualuxe'),
            'post_content' => '[aqualuxe_auctions]',
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
        
        if ($archive_page_id) {
            aqualuxe_update_module_option('auctions', 'archive_page', $archive_page_id);
        }
    }
    
    // Create my auctions page if it doesn't exist
    if (!$my_auctions_page_id) {
        $my_auctions_page_id = wp_insert_post(array(
            'post_title' => __('My Auctions', 'aqualuxe'),
            'post_content' => '[aqualuxe_my_auctions]',
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
        
        if ($my_auctions_page_id) {
            aqualuxe_update_module_option('auctions', 'my_auctions_page', $my_auctions_page_id);
        }
    }
    
    // Create dashboard page if it doesn't exist
    if (!$dashboard_page_id) {
        $dashboard_page_id = wp_insert_post(array(
            'post_title' => __('Auction Dashboard', 'aqualuxe'),
            'post_content' => '[aqualuxe_auction_dashboard]',
            'post_status' => 'publish',
            'post_type' => 'page',
        ));
        
        if ($dashboard_page_id) {
            aqualuxe_update_module_option('auctions', 'dashboard_page', $dashboard_page_id);
        }
    }
}
add_action('aqualuxe_modules_loaded', 'aqualuxe_auctions_create_default_pages');

/**
 * Register auction shortcodes
 */
function aqualuxe_auctions_register_shortcodes() {
    add_shortcode('aqualuxe_auctions', 'aqualuxe_auctions_shortcode');
    add_shortcode('aqualuxe_my_auctions', 'aqualuxe_my_auctions_shortcode');
    add_shortcode('aqualuxe_auction_dashboard', 'aqualuxe_auction_dashboard_shortcode');
}
add_action('init', 'aqualuxe_auctions_register_shortcodes');

/**
 * Auctions shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_auctions_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 12,
        'columns' => 4,
        'status' => 'all',
        'orderby' => 'date',
        'order' => 'desc',
    ), $atts);
    
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $atts['limit'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'tax_query' => array(
            array(
                'taxonomy' => 'product_type',
                'field' => 'slug',
                'terms' => 'auction',
            ),
        ),
    );
    
    // Filter by status
    if ($atts['status'] !== 'all') {
        $current_time = current_time('mysql');
        
        switch ($atts['status']) {
            case 'scheduled':
                $args['meta_query'] = array(
                    array(
                        'key' => '_auction_start_time',
                        'value' => $current_time,
                        'compare' => '>',
                        'type' => 'DATETIME',
                    ),
                );
                break;
            case 'active':
                $args['meta_query'] = array(
                    'relation' => 'AND',
                    array(
                        'key' => '_auction_start_time',
                        'value' => $current_time,
                        'compare' => '<=',
                        'type' => 'DATETIME',
                    ),
                    array(
                        'key' => '_auction_end_time',
                        'value' => $current_time,
                        'compare' => '>',
                        'type' => 'DATETIME',
                    ),
                );
                break;
            case 'ended':
                $args['meta_query'] = array(
                    array(
                        'key' => '_auction_end_time',
                        'value' => $current_time,
                        'compare' => '<=',
                        'type' => 'DATETIME',
                    ),
                );
                break;
        }
    }
    
    ob_start();
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
        woocommerce_pagination();
    } else {
        echo '<p>' . esc_html__('No auctions found.', 'aqualuxe') . '</p>';
    }
    
    wp_reset_postdata();
    
    return ob_get_clean();
}

/**
 * My auctions shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_my_auctions_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 10,
        'status' => 'all',
    ), $atts);
    
    if (!is_user_logged_in()) {
        return '<p>' . esc_html__('You must be logged in to view your auctions.', 'aqualuxe') . '</p>';
    }
    
    $user_id = get_current_user_id();
    $auctions = aqualuxe_auctions_get_user_auctions($user_id, $atts['status'], $atts['limit']);
    
    ob_start();
    
    // Tabs for different auction statuses
    ?>
    <div class="aqualuxe-my-auctions">
        <ul class="aqualuxe-tabs">
            <li class="<?php echo $atts['status'] === 'all' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(add_query_arg('status', 'all')); ?>">
                    <?php esc_html_e('All Auctions', 'aqualuxe'); ?>
                </a>
            </li>
            <li class="<?php echo $atts['status'] === 'active' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(add_query_arg('status', 'active')); ?>">
                    <?php esc_html_e('Active Auctions', 'aqualuxe'); ?>
                </a>
            </li>
            <li class="<?php echo $atts['status'] === 'won' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(add_query_arg('status', 'won')); ?>">
                    <?php esc_html_e('Won Auctions', 'aqualuxe'); ?>
                </a>
            </li>
            <li class="<?php echo $atts['status'] === 'ended' ? 'active' : ''; ?>">
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
            <?php endif; ?>
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}

/**
 * Auction dashboard shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function aqualuxe_auction_dashboard_shortcode($atts) {
    $atts = shortcode_atts(array(), $atts);
    
    if (!is_user_logged_in()) {
        return '<p>' . esc_html__('You must be logged in to view the auction dashboard.', 'aqualuxe') . '</p>';
    }
    
    $user_id = get_current_user_id();
    
    // Get user auction statistics
    global $wpdb;
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    $bid_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$bids_table} WHERE user_id = %d",
        $user_id
    ));
    
    $auction_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(DISTINCT product_id) FROM {$bids_table} WHERE user_id = %d",
        $user_id
    ));
    
    $won_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_auction_winner' AND meta_value = %d",
        $user_id
    ));
    
    $active_auctions = aqualuxe_auctions_get_user_auctions($user_id, 'active', 5);
    $won_auctions = aqualuxe_auctions_get_user_auctions($user_id, 'won', 5);
    
    ob_start();
    
    ?>
    <div class="aqualuxe-auction-dashboard">
        <h2><?php esc_html_e('Auction Dashboard', 'aqualuxe'); ?></h2>
        
        <div class="aqualuxe-auction-stats">
            <div class="aqualuxe-auction-stat">
                <span class="stat-value"><?php echo esc_html($bid_count); ?></span>
                <span class="stat-label"><?php esc_html_e('Bids Placed', 'aqualuxe'); ?></span>
            </div>
            
            <div class="aqualuxe-auction-stat">
                <span class="stat-value"><?php echo esc_html($auction_count); ?></span>
                <span class="stat-label"><?php esc_html_e('Auctions Participated', 'aqualuxe'); ?></span>
            </div>
            
            <div class="aqualuxe-auction-stat">
                <span class="stat-value"><?php echo esc_html($won_count); ?></span>
                <span class="stat-label"><?php esc_html_e('Auctions Won', 'aqualuxe'); ?></span>
            </div>
        </div>
        
        <div class="aqualuxe-auction-sections">
            <div class="aqualuxe-auction-section">
                <h3><?php esc_html_e('Active Auctions', 'aqualuxe'); ?></h3>
                
                <?php if (!empty($active_auctions)) : ?>
                    <ul class="aqualuxe-auction-list">
                        <?php
                        foreach ($active_auctions as $auction_post) {
                            $product = wc_get_product($auction_post->ID);
                            
                            if (!$product) {
                                continue;
                            }
                            
                            $current_price = aqualuxe_auctions_get_current_price($product);
                            $end_time = aqualuxe_auctions_get_end_time($product);
                            
                            ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                                    <?php echo esc_html($product->get_title()); ?>
                                </a>
                                <span class="auction-price"><?php echo wc_price($current_price); ?></span>
                                <span class="auction-time"><?php echo esc_html(aqualuxe_auctions_get_time_remaining($product)); ?></span>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    
                    <p>
                        <a href="<?php echo esc_url(aqualuxe_auctions_get_my_auctions_url() . '?status=active'); ?>" class="button">
                            <?php esc_html_e('View All Active Auctions', 'aqualuxe'); ?>
                        </a>
                    </p>
                <?php else : ?>
                    <p><?php esc_html_e('You have no active auctions.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="aqualuxe-auction-section">
                <h3><?php esc_html_e('Won Auctions', 'aqualuxe'); ?></h3>
                
                <?php if (!empty($won_auctions)) : ?>
                    <ul class="aqualuxe-auction-list">
                        <?php
                        foreach ($won_auctions as $auction_post) {
                            $product = wc_get_product($auction_post->ID);
                            
                            if (!$product) {
                                continue;
                            }
                            
                            $current_price = aqualuxe_auctions_get_current_price($product);
                            
                            ?>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                                    <?php echo esc_html($product->get_title()); ?>
                                </a>
                                <span class="auction-price"><?php echo wc_price($current_price); ?></span>
                                <a href="<?php echo esc_url(add_query_arg('add-to-cart', $product->get_id())); ?>" class="button">
                                    <?php esc_html_e('Purchase', 'aqualuxe'); ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    
                    <p>
                        <a href="<?php echo esc_url(aqualuxe_auctions_get_my_auctions_url() . '?status=won'); ?>" class="button">
                            <?php esc_html_e('View All Won Auctions', 'aqualuxe'); ?>
                        </a>
                    </p>
                <?php else : ?>
                    <p><?php esc_html_e('You have not won any auctions yet.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="aqualuxe-auction-links">
            <a href="<?php echo esc_url(aqualuxe_auctions_get_archive_url()); ?>" class="button">
                <?php esc_html_e('Browse All Auctions', 'aqualuxe'); ?>
            </a>
            
            <a href="<?php echo esc_url(aqualuxe_auctions_get_my_auctions_url()); ?>" class="button">
                <?php esc_html_e('View My Auctions', 'aqualuxe'); ?>
            </a>
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}