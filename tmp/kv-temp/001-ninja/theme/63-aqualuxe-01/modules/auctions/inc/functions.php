<?php
/**
 * Auctions module functions
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Check if current page is an auction page
 *
 * @return bool True if current page is an auction page
 */
function aqualuxe_auctions_is_auction_page() {
    // Get auction pages
    $auction_pages = aqualuxe_auctions_get_pages();
    
    // Check if current page is an auction page
    if (is_page() && !empty($auction_pages)) {
        $current_page_id = get_the_ID();
        
        foreach ($auction_pages as $page_id) {
            if ($current_page_id == $page_id) {
                return true;
            }
        }
    }
    
    // Check for auction query var
    if (get_query_var('auction', false)) {
        return true;
    }
    
    return false;
}

/**
 * Check if current product is an auction product
 *
 * @param int|WC_Product $product Product ID or product object
 * @return bool True if product is an auction product
 */
function aqualuxe_auctions_is_auction_product($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return false;
    }
    
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product) {
        return false;
    }
    
    return $product->get_type() === 'auction';
}

/**
 * Get auction pages
 *
 * @return array Auction page IDs
 */
function aqualuxe_auctions_get_pages() {
    $pages = array();
    
    // Auctions archive page
    $archive_page_id = aqualuxe_get_module_option('auctions', 'archive_page', 0);
    if ($archive_page_id) {
        $pages[] = $archive_page_id;
    }
    
    // My auctions page
    $my_auctions_page_id = aqualuxe_get_module_option('auctions', 'my_auctions_page', 0);
    if ($my_auctions_page_id) {
        $pages[] = $my_auctions_page_id;
    }
    
    // Dashboard page
    $dashboard_page_id = aqualuxe_get_module_option('auctions', 'dashboard_page', 0);
    if ($dashboard_page_id) {
        $pages[] = $dashboard_page_id;
    }
    
    return apply_filters('aqualuxe_auction_pages', $pages);
}

/**
 * Get auction status
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Auction status
 */
function aqualuxe_auctions_get_status($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return '';
    }
    
    $start_time = get_post_meta($product->get_id(), '_auction_start_time', true);
    $end_time = get_post_meta($product->get_id(), '_auction_end_time', true);
    $current_time = current_time('timestamp');
    
    if ($start_time && $current_time < strtotime($start_time)) {
        return 'scheduled';
    } elseif ($end_time && $current_time > strtotime($end_time)) {
        return 'ended';
    } else {
        return 'active';
    }
}

/**
 * Get auction start time
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Auction start time
 */
function aqualuxe_auctions_get_start_time($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return '';
    }
    
    return get_post_meta($product->get_id(), '_auction_start_time', true);
}

/**
 * Get auction end time
 *
 * @param int|WC_Product $product Product ID or product object
 * @return string Auction end time
 */
function aqualuxe_auctions_get_end_time($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return '';
    }
    
    return get_post_meta($product->get_id(), '_auction_end_time', true);
}

/**
 * Get auction starting price
 *
 * @param int|WC_Product $product Product ID or product object
 * @return float Auction starting price
 */
function aqualuxe_auctions_get_starting_price($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return 0;
    }
    
    return (float) get_post_meta($product->get_id(), '_auction_start_price', true);
}

/**
 * Get auction reserve price
 *
 * @param int|WC_Product $product Product ID or product object
 * @return float Auction reserve price
 */
function aqualuxe_auctions_get_reserve_price($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return 0;
    }
    
    return (float) get_post_meta($product->get_id(), '_auction_reserve_price', true);
}

/**
 * Get auction current price
 *
 * @param int|WC_Product $product Product ID or product object
 * @return float Auction current price
 */
function aqualuxe_auctions_get_current_price($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return 0;
    }
    
    $current_bid = aqualuxe_auctions_get_highest_bid($product);
    
    if ($current_bid) {
        return (float) $current_bid['amount'];
    }
    
    return aqualuxe_auctions_get_starting_price($product);
}

/**
 * Get auction bid increment
 *
 * @param int|WC_Product $product Product ID or product object
 * @return float Auction bid increment
 */
function aqualuxe_auctions_get_bid_increment($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return 0;
    }
    
    $increment = get_post_meta($product->get_id(), '_auction_bid_increment', true);
    
    if ($increment === '' || $increment === false) {
        // Use default increment from settings
        $increment = aqualuxe_get_module_option('auctions', 'default_bid_increment', 1);
    }
    
    return (float) $increment;
}

/**
 * Get auction minimum bid
 *
 * @param int|WC_Product $product Product ID or product object
 * @return float Auction minimum bid
 */
function aqualuxe_auctions_get_minimum_bid($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return 0;
    }
    
    $current_price = aqualuxe_auctions_get_current_price($product);
    $increment = aqualuxe_auctions_get_bid_increment($product);
    
    return $current_price + $increment;
}

/**
 * Get auction highest bid
 *
 * @param int|WC_Product $product Product ID or product object
 * @return array|false Highest bid data or false if no bids
 */
function aqualuxe_auctions_get_highest_bid($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return false;
    }
    
    global $wpdb;
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    $query = $wpdb->prepare(
        "SELECT * FROM {$bids_table} WHERE product_id = %d ORDER BY amount DESC, date_created ASC LIMIT 1",
        $product->get_id()
    );
    
    $bid = $wpdb->get_row($query, ARRAY_A);
    
    if (!$bid) {
        return false;
    }
    
    return $bid;
}

/**
 * Get auction bids
 *
 * @param int|WC_Product $product Product ID or product object
 * @param int $limit Number of bids to return
 * @param int $offset Offset for pagination
 * @return array Auction bids
 */
function aqualuxe_auctions_get_bids($product, $limit = 10, $offset = 0) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return array();
    }
    
    global $wpdb;
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    $query = $wpdb->prepare(
        "SELECT * FROM {$bids_table} WHERE product_id = %d ORDER BY date_created DESC LIMIT %d OFFSET %d",
        $product->get_id(),
        $limit,
        $offset
    );
    
    $bids = $wpdb->get_results($query, ARRAY_A);
    
    if (!$bids) {
        return array();
    }
    
    return $bids;
}

/**
 * Get auction bid count
 *
 * @param int|WC_Product $product Product ID or product object
 * @return int Number of bids
 */
function aqualuxe_auctions_get_bid_count($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return 0;
    }
    
    global $wpdb;
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    $query = $wpdb->prepare(
        "SELECT COUNT(*) FROM {$bids_table} WHERE product_id = %d",
        $product->get_id()
    );
    
    return (int) $wpdb->get_var($query);
}

/**
 * Get auction winner
 *
 * @param int|WC_Product $product Product ID or product object
 * @return int|false User ID of winner or false if no winner
 */
function aqualuxe_auctions_get_winner($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return false;
    }
    
    $winner_id = get_post_meta($product->get_id(), '_auction_winner', true);
    
    if (!$winner_id) {
        // Check if auction has ended
        if (aqualuxe_auctions_get_status($product) === 'ended') {
            // Get highest bid
            $highest_bid = aqualuxe_auctions_get_highest_bid($product);
            
            if ($highest_bid) {
                $reserve_price = aqualuxe_auctions_get_reserve_price($product);
                
                // Check if reserve price is met
                if ($reserve_price === 0 || $highest_bid['amount'] >= $reserve_price) {
                    $winner_id = $highest_bid['user_id'];
                    update_post_meta($product->get_id(), '_auction_winner', $winner_id);
                    
                    // Trigger winner notification
                    do_action('aqualuxe_auction_winner_set', $product->get_id(), $winner_id);
                }
            }
        }
    }
    
    return $winner_id ? (int) $winner_id : false;
}

/**
 * Check if user is auction winner
 *
 * @param int|WC_Product $product Product ID or product object
 * @param int $user_id User ID
 * @return bool True if user is auction winner
 */
function aqualuxe_auctions_is_user_winner($product, $user_id = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return false;
    }
    
    $winner_id = aqualuxe_auctions_get_winner($product);
    
    return $winner_id === $user_id;
}

/**
 * Get user auctions
 *
 * @param int $user_id User ID
 * @param string $status Auction status (all, active, ended, won)
 * @param int $limit Number of auctions to return
 * @param int $offset Offset for pagination
 * @return array User auctions
 */
function aqualuxe_auctions_get_user_auctions($user_id = 0, $status = 'all', $limit = 10, $offset = 0) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return array();
    }
    
    global $wpdb;
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    // Get product IDs where user has placed bids
    $query = $wpdb->prepare(
        "SELECT DISTINCT product_id FROM {$bids_table} WHERE user_id = %d",
        $user_id
    );
    
    $product_ids = $wpdb->get_col($query);
    
    if (empty($product_ids)) {
        return array();
    }
    
    // Get products
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'offset' => $offset,
        'post__in' => $product_ids,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_type',
                'field' => 'slug',
                'terms' => 'auction',
            ),
        ),
    );
    
    // Filter by status
    if ($status === 'active') {
        $args['meta_query'] = array(
            array(
                'key' => '_auction_end_time',
                'value' => current_time('mysql'),
                'compare' => '>',
                'type' => 'DATETIME',
            ),
        );
    } elseif ($status === 'ended') {
        $args['meta_query'] = array(
            array(
                'key' => '_auction_end_time',
                'value' => current_time('mysql'),
                'compare' => '<=',
                'type' => 'DATETIME',
            ),
        );
    } elseif ($status === 'won') {
        $args['meta_query'] = array(
            array(
                'key' => '_auction_winner',
                'value' => $user_id,
                'compare' => '=',
            ),
        );
    }
    
    $query = new WP_Query($args);
    
    return $query->posts;
}

/**
 * Get auction archive URL
 *
 * @return string Auction archive URL
 */
function aqualuxe_auctions_get_archive_url() {
    $archive_page_id = aqualuxe_get_module_option('auctions', 'archive_page', 0);
    
    if ($archive_page_id) {
        return get_permalink($archive_page_id);
    }
    
    return get_post_type_archive_link('product') . '?product_type=auction';
}

/**
 * Get my auctions URL
 *
 * @return string My auctions URL
 */
function aqualuxe_auctions_get_my_auctions_url() {
    $my_auctions_page_id = aqualuxe_get_module_option('auctions', 'my_auctions_page', 0);
    
    if ($my_auctions_page_id) {
        return get_permalink($my_auctions_page_id);
    }
    
    return wc_get_account_endpoint_url('auctions');
}

/**
 * Get auction dashboard URL
 *
 * @return string Auction dashboard URL
 */
function aqualuxe_auctions_get_dashboard_url() {
    $dashboard_page_id = aqualuxe_get_module_option('auctions', 'dashboard_page', 0);
    
    if ($dashboard_page_id) {
        return get_permalink($dashboard_page_id);
    }
    
    return home_url('/my-account/');
}

/**
 * Format auction time
 *
 * @param string $time Time string
 * @param string $format Date format
 * @return string Formatted time
 */
function aqualuxe_auctions_format_time($time, $format = '') {
    if (!$time) {
        return '';
    }
    
    if (!$format) {
        $format = get_option('date_format') . ' ' . get_option('time_format');
    }
    
    return date_i18n($format, strtotime($time));
}

/**
 * Get time remaining for auction
 *
 * @param int|WC_Product $product Product ID or product object
 * @param bool $formatted Whether to return formatted time
 * @return string|int Formatted time remaining or seconds remaining
 */
function aqualuxe_auctions_get_time_remaining($product, $formatted = true) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        return $formatted ? '' : 0;
    }
    
    $end_time = aqualuxe_auctions_get_end_time($product);
    
    if (!$end_time) {
        return $formatted ? '' : 0;
    }
    
    $current_time = current_time('timestamp');
    $end_timestamp = strtotime($end_time);
    
    if ($current_time >= $end_timestamp) {
        return $formatted ? __('Auction ended', 'aqualuxe') : 0;
    }
    
    $remaining = $end_timestamp - $current_time;
    
    if (!$formatted) {
        return $remaining;
    }
    
    $days = floor($remaining / 86400);
    $hours = floor(($remaining % 86400) / 3600);
    $minutes = floor(($remaining % 3600) / 60);
    $seconds = $remaining % 60;
    
    $output = '';
    
    if ($days > 0) {
        $output .= sprintf(_n('%d day', '%d days', $days, 'aqualuxe'), $days) . ' ';
    }
    
    if ($hours > 0 || $days > 0) {
        $output .= sprintf(_n('%d hour', '%d hours', $hours, 'aqualuxe'), $hours) . ' ';
    }
    
    if ($minutes > 0 || $hours > 0 || $days > 0) {
        $output .= sprintf(_n('%d minute', '%d minutes', $minutes, 'aqualuxe'), $minutes) . ' ';
    }
    
    $output .= sprintf(_n('%d second', '%d seconds', $seconds, 'aqualuxe'), $seconds);
    
    return $output;
}

/**
 * Check if auction has reserve price
 *
 * @param int|WC_Product $product Product ID or product object
 * @return bool True if auction has reserve price
 */
function aqualuxe_auctions_has_reserve_price($product) {
    $reserve_price = aqualuxe_auctions_get_reserve_price($product);
    
    return $reserve_price > 0;
}

/**
 * Check if auction reserve price is met
 *
 * @param int|WC_Product $product Product ID or product object
 * @return bool True if reserve price is met
 */
function aqualuxe_auctions_is_reserve_met($product) {
    $reserve_price = aqualuxe_auctions_get_reserve_price($product);
    
    if ($reserve_price <= 0) {
        return true;
    }
    
    $current_price = aqualuxe_auctions_get_current_price($product);
    
    return $current_price >= $reserve_price;
}

/**
 * Create auction tables
 */
function aqualuxe_auctions_create_tables() {
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    
    // Create bids table
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    $sql = "CREATE TABLE {$bids_table} (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        product_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        amount decimal(19,4) NOT NULL,
        date_created datetime NOT NULL,
        PRIMARY KEY  (id),
        KEY product_id (product_id),
        KEY user_id (user_id)
    ) {$charset_collate};";
    
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}