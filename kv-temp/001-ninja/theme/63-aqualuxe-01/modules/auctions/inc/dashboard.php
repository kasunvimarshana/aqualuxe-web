<?php
/**
 * Auction dashboard functionality
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Initialize auction dashboard
 */
function aqualuxe_auctions_initialize_dashboard() {
    // Add auction endpoint to My Account
    add_action('init', 'aqualuxe_auctions_add_endpoints');
    add_filter('query_vars', 'aqualuxe_auctions_add_query_vars');
    add_filter('woocommerce_account_menu_items', 'aqualuxe_auctions_add_menu_items');
    add_action('woocommerce_account_auctions_endpoint', 'aqualuxe_auctions_endpoint_content');
    
    // Add auction dashboard widgets
    add_action('wp_dashboard_setup', 'aqualuxe_auctions_add_dashboard_widgets');
    
    // Add auction admin menu
    add_action('admin_menu', 'aqualuxe_auctions_add_admin_menu');
    
    // Add auction admin ajax handlers
    add_action('wp_ajax_aqualuxe_admin_end_auction', 'aqualuxe_auctions_admin_end_auction');
    add_action('wp_ajax_aqualuxe_admin_delete_bid', 'aqualuxe_auctions_admin_delete_bid');
    add_action('wp_ajax_aqualuxe_admin_set_winner', 'aqualuxe_auctions_admin_set_winner');
}

/**
 * Add auction endpoints to My Account
 */
function aqualuxe_auctions_add_endpoints() {
    add_rewrite_endpoint('auctions', EP_ROOT | EP_PAGES);
}

/**
 * Add auction query vars
 *
 * @param array $vars Query vars
 * @return array
 */
function aqualuxe_auctions_add_query_vars($vars) {
    $vars[] = 'auctions';
    return $vars;
}

/**
 * Add auction menu items to My Account
 *
 * @param array $items Menu items
 * @return array
 */
function aqualuxe_auctions_add_menu_items($items) {
    // Add auctions item after orders
    $new_items = array();
    
    foreach ($items as $key => $item) {
        $new_items[$key] = $item;
        
        if ($key === 'orders') {
            $new_items['auctions'] = __('My Auctions', 'aqualuxe');
        }
    }
    
    return $new_items;
}

/**
 * Auction endpoint content
 */
function aqualuxe_auctions_endpoint_content() {
    // Get user auctions
    $user_id = get_current_user_id();
    $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';
    $auctions = aqualuxe_auctions_get_user_auctions($user_id, $status);
    
    // Display tabs
    ?>
    <div class="aqualuxe-my-auctions">
        <ul class="aqualuxe-tabs">
            <li class="<?php echo $status === 'all' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('auctions')); ?>">
                    <?php esc_html_e('All Auctions', 'aqualuxe'); ?>
                </a>
            </li>
            <li class="<?php echo $status === 'active' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(add_query_arg('status', 'active', wc_get_account_endpoint_url('auctions'))); ?>">
                    <?php esc_html_e('Active Auctions', 'aqualuxe'); ?>
                </a>
            </li>
            <li class="<?php echo $status === 'won' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(add_query_arg('status', 'won', wc_get_account_endpoint_url('auctions'))); ?>">
                    <?php esc_html_e('Won Auctions', 'aqualuxe'); ?>
                </a>
            </li>
            <li class="<?php echo $status === 'ended' ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(add_query_arg('status', 'ended', wc_get_account_endpoint_url('auctions'))); ?>">
                    <?php esc_html_e('Ended Auctions', 'aqualuxe'); ?>
                </a>
            </li>
        </ul>
        
        <div class="aqualuxe-tab-content">
            <?php if (!empty($auctions)) : ?>
                <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
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
                                <td data-title="<?php esc_html_e('Auction', 'aqualuxe'); ?>">
                                    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>">
                                        <?php echo esc_html($product->get_title()); ?>
                                    </a>
                                </td>
                                <td data-title="<?php esc_html_e('Current Bid', 'aqualuxe'); ?>">
                                    <?php echo wc_price($current_price); ?>
                                </td>
                                <td data-title="<?php esc_html_e('Your Bid', 'aqualuxe'); ?>">
                                    <?php echo $user_bid ? wc_price($user_bid) : '&mdash;'; ?>
                                </td>
                                <td data-title="<?php esc_html_e('Status', 'aqualuxe'); ?>">
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
                                <td data-title="<?php esc_html_e('End Time', 'aqualuxe'); ?>">
                                    <?php echo esc_html(aqualuxe_auctions_format_time($end_time)); ?>
                                </td>
                                <td data-title="<?php esc_html_e('Actions', 'aqualuxe'); ?>">
                                    <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="woocommerce-button button view">
                                        <?php esc_html_e('View', 'aqualuxe'); ?>
                                    </a>
                                    
                                    <?php if ($is_winner) : ?>
                                        <a href="<?php echo esc_url(add_query_arg('add-to-cart', $product->get_id())); ?>" class="woocommerce-button button">
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
                <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
                    <?php esc_html_e('No auctions found.', 'aqualuxe'); ?>
                    <a class="woocommerce-Button button" href="<?php echo esc_url(aqualuxe_auctions_get_archive_url()); ?>">
                        <?php esc_html_e('Browse Auctions', 'aqualuxe'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Add auction dashboard widgets
 */
function aqualuxe_auctions_add_dashboard_widgets() {
    // Only for admin users
    if (!current_user_can('manage_options')) {
        return;
    }
    
    wp_add_dashboard_widget(
        'aqualuxe_auctions_dashboard_widget',
        __('Auction Statistics', 'aqualuxe'),
        'aqualuxe_auctions_dashboard_widget_content'
    );
}

/**
 * Auction dashboard widget content
 */
function aqualuxe_auctions_dashboard_widget_content() {
    global $wpdb;
    
    // Get auction statistics
    $total_auctions = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->posts} p
        JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        AND t.slug = 'auction'"
    );
    
    $active_auctions = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->posts} p
        JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id
        JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        AND t.slug = 'auction'
        AND pm1.meta_key = '_auction_start_time'
        AND pm1.meta_value <= '" . current_time('mysql') . "'
        AND pm2.meta_key = '_auction_end_time'
        AND pm2.meta_value > '" . current_time('mysql') . "'"
    );
    
    $ended_auctions = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->posts} p
        JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        AND t.slug = 'auction'
        AND pm.meta_key = '_auction_end_time'
        AND pm.meta_value <= '" . current_time('mysql') . "'"
    );
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    $total_bids = $wpdb->get_var("SELECT COUNT(*) FROM {$bids_table}");
    
    $total_users = $wpdb->get_var("SELECT COUNT(DISTINCT user_id) FROM {$bids_table}");
    
    ?>
    <div class="aqualuxe-auction-stats">
        <div class="aqualuxe-auction-stat">
            <span class="stat-value"><?php echo esc_html($total_auctions); ?></span>
            <span class="stat-label"><?php esc_html_e('Total Auctions', 'aqualuxe'); ?></span>
        </div>
        
        <div class="aqualuxe-auction-stat">
            <span class="stat-value"><?php echo esc_html($active_auctions); ?></span>
            <span class="stat-label"><?php esc_html_e('Active Auctions', 'aqualuxe'); ?></span>
        </div>
        
        <div class="aqualuxe-auction-stat">
            <span class="stat-value"><?php echo esc_html($ended_auctions); ?></span>
            <span class="stat-label"><?php esc_html_e('Ended Auctions', 'aqualuxe'); ?></span>
        </div>
        
        <div class="aqualuxe-auction-stat">
            <span class="stat-value"><?php echo esc_html($total_bids); ?></span>
            <span class="stat-label"><?php esc_html_e('Total Bids', 'aqualuxe'); ?></span>
        </div>
        
        <div class="aqualuxe-auction-stat">
            <span class="stat-value"><?php echo esc_html($total_users); ?></span>
            <span class="stat-label"><?php esc_html_e('Bidding Users', 'aqualuxe'); ?></span>
        </div>
    </div>
    
    <p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-auctions')); ?>" class="button">
            <?php esc_html_e('View Auction Dashboard', 'aqualuxe'); ?>
        </a>
    </p>
    <?php
}

/**
 * Add auction admin menu
 */
function aqualuxe_auctions_add_admin_menu() {
    add_menu_page(
        __('Auctions', 'aqualuxe'),
        __('Auctions', 'aqualuxe'),
        'manage_woocommerce',
        'aqualuxe-auctions',
        'aqualuxe_auctions_admin_page',
        'dashicons-hammer',
        56
    );
    
    add_submenu_page(
        'aqualuxe-auctions',
        __('Auction Dashboard', 'aqualuxe'),
        __('Dashboard', 'aqualuxe'),
        'manage_woocommerce',
        'aqualuxe-auctions',
        'aqualuxe_auctions_admin_page'
    );
    
    add_submenu_page(
        'aqualuxe-auctions',
        __('Auction Bids', 'aqualuxe'),
        __('Bids', 'aqualuxe'),
        'manage_woocommerce',
        'aqualuxe-auction-bids',
        'aqualuxe_auctions_admin_bids_page'
    );
    
    add_submenu_page(
        'aqualuxe-auctions',
        __('Auction Settings', 'aqualuxe'),
        __('Settings', 'aqualuxe'),
        'manage_woocommerce',
        'admin.php?page=aqualuxe-module-auctions',
        null
    );
}

/**
 * Auction admin page
 */
function aqualuxe_auctions_admin_page() {
    // Check user capabilities
    if (!current_user_can('manage_woocommerce')) {
        return;
    }
    
    // Get current tab
    $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'dashboard';
    
    // Get tabs
    $tabs = array(
        'dashboard' => __('Dashboard', 'aqualuxe'),
        'active' => __('Active Auctions', 'aqualuxe'),
        'scheduled' => __('Scheduled Auctions', 'aqualuxe'),
        'ended' => __('Ended Auctions', 'aqualuxe'),
    );
    
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Auction Dashboard', 'aqualuxe'); ?></h1>
        
        <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
            <?php
            foreach ($tabs as $tab_id => $tab_name) {
                $active = $current_tab === $tab_id ? ' nav-tab-active' : '';
                echo '<a href="' . esc_url(admin_url('admin.php?page=aqualuxe-auctions&tab=' . $tab_id)) . '" class="nav-tab' . esc_attr($active) . '">' . esc_html($tab_name) . '</a>';
            }
            ?>
        </nav>
        
        <div class="tab-content">
            <?php
            switch ($current_tab) {
                case 'dashboard':
                    aqualuxe_auctions_admin_dashboard_tab();
                    break;
                case 'active':
                    aqualuxe_auctions_admin_active_tab();
                    break;
                case 'scheduled':
                    aqualuxe_auctions_admin_scheduled_tab();
                    break;
                case 'ended':
                    aqualuxe_auctions_admin_ended_tab();
                    break;
                default:
                    aqualuxe_auctions_admin_dashboard_tab();
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Auction admin dashboard tab
 */
function aqualuxe_auctions_admin_dashboard_tab() {
    global $wpdb;
    
    // Get auction statistics
    $total_auctions = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->posts} p
        JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        AND t.slug = 'auction'"
    );
    
    $active_auctions = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->posts} p
        JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id
        JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        AND t.slug = 'auction'
        AND pm1.meta_key = '_auction_start_time'
        AND pm1.meta_value <= '" . current_time('mysql') . "'
        AND pm2.meta_key = '_auction_end_time'
        AND pm2.meta_value > '" . current_time('mysql') . "'"
    );
    
    $scheduled_auctions = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->posts} p
        JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        AND t.slug = 'auction'
        AND pm.meta_key = '_auction_start_time'
        AND pm.meta_value > '" . current_time('mysql') . "'"
    );
    
    $ended_auctions = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->posts} p
        JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        AND t.slug = 'auction'
        AND pm.meta_key = '_auction_end_time'
        AND pm.meta_value <= '" . current_time('mysql') . "'"
    );
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    $total_bids = $wpdb->get_var("SELECT COUNT(*) FROM {$bids_table}");
    
    $total_users = $wpdb->get_var("SELECT COUNT(DISTINCT user_id) FROM {$bids_table}");
    
    // Get recent auctions
    $recent_auctions = $wpdb->get_results(
        "SELECT p.ID, p.post_title, pm1.meta_value as start_time, pm2.meta_value as end_time
        FROM {$wpdb->posts} p
        JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_auction_start_time'
        LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_auction_end_time'
        WHERE p.post_type = 'product'
        AND p.post_status = 'publish'
        AND t.slug = 'auction'
        ORDER BY p.post_date DESC
        LIMIT 5"
    );
    
    // Get recent bids
    $recent_bids = $wpdb->get_results(
        "SELECT b.*, p.post_title, u.display_name
        FROM {$bids_table} b
        JOIN {$wpdb->posts} p ON b.product_id = p.ID
        JOIN {$wpdb->users} u ON b.user_id = u.ID
        ORDER BY b.date_created DESC
        LIMIT 5"
    );
    
    ?>
    <div class="aqualuxe-auction-admin-dashboard">
        <div class="aqualuxe-auction-stats">
            <div class="aqualuxe-auction-stat">
                <span class="stat-value"><?php echo esc_html($total_auctions); ?></span>
                <span class="stat-label"><?php esc_html_e('Total Auctions', 'aqualuxe'); ?></span>
            </div>
            
            <div class="aqualuxe-auction-stat">
                <span class="stat-value"><?php echo esc_html($active_auctions); ?></span>
                <span class="stat-label"><?php esc_html_e('Active Auctions', 'aqualuxe'); ?></span>
            </div>
            
            <div class="aqualuxe-auction-stat">
                <span class="stat-value"><?php echo esc_html($scheduled_auctions); ?></span>
                <span class="stat-label"><?php esc_html_e('Scheduled Auctions', 'aqualuxe'); ?></span>
            </div>
            
            <div class="aqualuxe-auction-stat">
                <span class="stat-value"><?php echo esc_html($ended_auctions); ?></span>
                <span class="stat-label"><?php esc_html_e('Ended Auctions', 'aqualuxe'); ?></span>
            </div>
            
            <div class="aqualuxe-auction-stat">
                <span class="stat-value"><?php echo esc_html($total_bids); ?></span>
                <span class="stat-label"><?php esc_html_e('Total Bids', 'aqualuxe'); ?></span>
            </div>
            
            <div class="aqualuxe-auction-stat">
                <span class="stat-value"><?php echo esc_html($total_users); ?></span>
                <span class="stat-label"><?php esc_html_e('Bidding Users', 'aqualuxe'); ?></span>
            </div>
        </div>
        
        <div class="aqualuxe-auction-sections">
            <div class="aqualuxe-auction-section">
                <h3><?php esc_html_e('Recent Auctions', 'aqualuxe'); ?></h3>
                
                <?php if (!empty($recent_auctions)) : ?>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Auction', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Start Time', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('End Time', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($recent_auctions as $auction) {
                                $product = wc_get_product($auction->ID);
                                
                                if (!$product) {
                                    continue;
                                }
                                
                                $status = aqualuxe_auctions_get_status($product);
                                
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo esc_url(get_edit_post_link($auction->ID)); ?>">
                                            <?php echo esc_html($auction->post_title); ?>
                                        </a>
                                    </td>
                                    <td><?php echo esc_html(aqualuxe_auctions_format_time($auction->start_time)); ?></td>
                                    <td><?php echo esc_html(aqualuxe_auctions_format_time($auction->end_time)); ?></td>
                                    <td>
                                        <?php
                                        switch ($status) {
                                            case 'scheduled':
                                                echo '<span class="auction-status scheduled">' . esc_html__('Scheduled', 'aqualuxe') . '</span>';
                                                break;
                                            case 'active':
                                                echo '<span class="auction-status active">' . esc_html__('Active', 'aqualuxe') . '</span>';
                                                break;
                                            case 'ended':
                                                echo '<span class="auction-status ended">' . esc_html__('Ended', 'aqualuxe') . '</span>';
                                                break;
                                            default:
                                                echo '<span class="auction-status unknown">' . esc_html__('Unknown', 'aqualuxe') . '</span>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo esc_url(get_edit_post_link($auction->ID)); ?>" class="button">
                                            <?php esc_html_e('Edit', 'aqualuxe'); ?>
                                        </a>
                                        
                                        <a href="<?php echo esc_url(get_permalink($auction->ID)); ?>" class="button">
                                            <?php esc_html_e('View', 'aqualuxe'); ?>
                                        </a>
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
            
            <div class="aqualuxe-auction-section">
                <h3><?php esc_html_e('Recent Bids', 'aqualuxe'); ?></h3>
                
                <?php if (!empty($recent_bids)) : ?>
                    <table class="widefat">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Auction', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Bidder', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Amount', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                                <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($recent_bids as $bid) {
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo esc_url(get_edit_post_link($bid->product_id)); ?>">
                                            <?php echo esc_html($bid->post_title); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo esc_url(get_edit_user_link($bid->user_id)); ?>">
                                            <?php echo esc_html($bid->display_name); ?>
                                        </a>
                                    </td>
                                    <td><?php echo wc_price($bid->amount); ?></td>
                                    <td><?php echo esc_html(aqualuxe_auctions_format_time($bid->date_created)); ?></td>
                                    <td>
                                        <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-auction-bids&product_id=' . $bid->product_id)); ?>" class="button">
                                            <?php esc_html_e('View Bids', 'aqualuxe'); ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p><?php esc_html_e('No bids found.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="aqualuxe-auction-actions">
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=product&product_type=auction')); ?>" class="button button-primary">
                <?php esc_html_e('Add New Auction', 'aqualuxe'); ?>
            </a>
            
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=product&product_type=auction')); ?>" class="button">
                <?php esc_html_e('View All Auctions', 'aqualuxe'); ?>
            </a>
            
            <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-auction-bids')); ?>" class="button">
                <?php esc_html_e('View All Bids', 'aqualuxe'); ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Auction admin active tab
 */
function aqualuxe_auctions_admin_active_tab() {
    // Get active auctions
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
            'relation' => 'AND',
            array(
                'key' => '_auction_start_time',
                'value' => current_time('mysql'),
                'compare' => '<=',
                'type' => 'DATETIME',
            ),
            array(
                'key' => '_auction_end_time',
                'value' => current_time('mysql'),
                'compare' => '>',
                'type' => 'DATETIME',
            ),
        ),
    );
    
    $query = new WP_Query($args);
    
    ?>
    <div class="aqualuxe-auction-admin-active">
        <h3><?php esc_html_e('Active Auctions', 'aqualuxe'); ?></h3>
        
        <?php if ($query->have_posts()) : ?>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Auction', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Current Bid', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Bids', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Start Time', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('End Time', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Time Remaining', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($query->have_posts()) {
                        $query->the_post();
                        $product = wc_get_product(get_the_ID());
                        
                        if (!$product) {
                            continue;
                        }
                        
                        $current_price = aqualuxe_auctions_get_current_price($product);
                        $bid_count = aqualuxe_auctions_get_bid_count($product);
                        $start_time = aqualuxe_auctions_get_start_time($product);
                        $end_time = aqualuxe_auctions_get_end_time($product);
                        $time_remaining = aqualuxe_auctions_get_time_remaining($product);
                        
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>">
                                    <?php echo esc_html($product->get_title()); ?>
                                </a>
                            </td>
                            <td><?php echo wc_price($current_price); ?></td>
                            <td><?php echo esc_html($bid_count); ?></td>
                            <td><?php echo esc_html(aqualuxe_auctions_format_time($start_time)); ?></td>
                            <td><?php echo esc_html(aqualuxe_auctions_format_time($end_time)); ?></td>
                            <td><?php echo esc_html($time_remaining); ?></td>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>" class="button">
                                    <?php esc_html_e('Edit', 'aqualuxe'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="button">
                                    <?php esc_html_e('View', 'aqualuxe'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-auction-bids&product_id=' . $product->get_id())); ?>" class="button">
                                    <?php esc_html_e('Bids', 'aqualuxe'); ?>
                                </a>
                                
                                <button class="button end-auction" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                    <?php esc_html_e('End Auction', 'aqualuxe'); ?>
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            
            <script type="text/javascript">
                jQuery(function($) {
                    $('.end-auction').on('click', function() {
                        var productId = $(this).data('product-id');
                        
                        if (confirm('<?php esc_html_e('Are you sure you want to end this auction?', 'aqualuxe'); ?>')) {
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'aqualuxe_admin_end_auction',
                                    product_id: productId,
                                    nonce: '<?php echo wp_create_nonce('aqualuxe_admin_end_auction'); ?>'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        alert(response.data.message);
                                        location.reload();
                                    } else {
                                        alert(response.data.message);
                                    }
                                }
                            });
                        }
                    });
                });
            </script>
        <?php else : ?>
            <p><?php esc_html_e('No active auctions found.', 'aqualuxe'); ?></p>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
    <?php
}

/**
 * Auction admin scheduled tab
 */
function aqualuxe_auctions_admin_scheduled_tab() {
    // Get scheduled auctions
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
                'key' => '_auction_start_time',
                'value' => current_time('mysql'),
                'compare' => '>',
                'type' => 'DATETIME',
            ),
        ),
    );
    
    $query = new WP_Query($args);
    
    ?>
    <div class="aqualuxe-auction-admin-scheduled">
        <h3><?php esc_html_e('Scheduled Auctions', 'aqualuxe'); ?></h3>
        
        <?php if ($query->have_posts()) : ?>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Auction', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Starting Price', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Reserve Price', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Start Time', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('End Time', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($query->have_posts()) {
                        $query->the_post();
                        $product = wc_get_product(get_the_ID());
                        
                        if (!$product) {
                            continue;
                        }
                        
                        $start_price = aqualuxe_auctions_get_starting_price($product);
                        $reserve_price = aqualuxe_auctions_get_reserve_price($product);
                        $start_time = aqualuxe_auctions_get_start_time($product);
                        $end_time = aqualuxe_auctions_get_end_time($product);
                        
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>">
                                    <?php echo esc_html($product->get_title()); ?>
                                </a>
                            </td>
                            <td><?php echo wc_price($start_price); ?></td>
                            <td><?php echo $reserve_price ? wc_price($reserve_price) : '&mdash;'; ?></td>
                            <td><?php echo esc_html(aqualuxe_auctions_format_time($start_time)); ?></td>
                            <td><?php echo esc_html(aqualuxe_auctions_format_time($end_time)); ?></td>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>" class="button">
                                    <?php esc_html_e('Edit', 'aqualuxe'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="button">
                                    <?php esc_html_e('View', 'aqualuxe'); ?>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php esc_html_e('No scheduled auctions found.', 'aqualuxe'); ?></p>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
    <?php
}

/**
 * Auction admin ended tab
 */
function aqualuxe_auctions_admin_ended_tab() {
    // Get ended auctions
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
        ),
    );
    
    $query = new WP_Query($args);
    
    ?>
    <div class="aqualuxe-auction-admin-ended">
        <h3><?php esc_html_e('Ended Auctions', 'aqualuxe'); ?></h3>
        
        <?php if ($query->have_posts()) : ?>
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Auction', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Final Price', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Bids', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Winner', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('End Time', 'aqualuxe'); ?></th>
                        <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($query->have_posts()) {
                        $query->the_post();
                        $product = wc_get_product(get_the_ID());
                        
                        if (!$product) {
                            continue;
                        }
                        
                        $final_price = aqualuxe_auctions_get_current_price($product);
                        $bid_count = aqualuxe_auctions_get_bid_count($product);
                        $end_time = aqualuxe_auctions_get_end_time($product);
                        $winner_id = aqualuxe_auctions_get_winner($product);
                        
                        if ($winner_id) {
                            $winner = get_user_by('id', $winner_id);
                            $winner_name = $winner ? $winner->display_name : __('Unknown', 'aqualuxe');
                        } else {
                            $winner_name = '&mdash;';
                        }
                        
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>">
                                    <?php echo esc_html($product->get_title()); ?>
                                </a>
                            </td>
                            <td><?php echo wc_price($final_price); ?></td>
                            <td><?php echo esc_html($bid_count); ?></td>
                            <td>
                                <?php
                                if ($winner_id) {
                                    echo '<a href="' . esc_url(get_edit_user_link($winner_id)) . '">' . esc_html($winner_name) . '</a>';
                                } else {
                                    echo '&mdash;';
                                }
                                ?>
                            </td>
                            <td><?php echo esc_html(aqualuxe_auctions_format_time($end_time)); ?></td>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>" class="button">
                                    <?php esc_html_e('Edit', 'aqualuxe'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="button">
                                    <?php esc_html_e('View', 'aqualuxe'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-auction-bids&product_id=' . $product->get_id())); ?>" class="button">
                                    <?php esc_html_e('Bids', 'aqualuxe'); ?>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php esc_html_e('No ended auctions found.', 'aqualuxe'); ?></p>
        <?php endif; ?>
        
        <?php wp_reset_postdata(); ?>
    </div>
    <?php
}

/**
 * Auction admin bids page
 */
function aqualuxe_auctions_admin_bids_page() {
    // Check user capabilities
    if (!current_user_can('manage_woocommerce')) {
        return;
    }
    
    // Get product ID
    $product_id = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;
    
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Auction Bids', 'aqualuxe'); ?></h1>
        
        <?php if ($product_id) : ?>
            <?php
            $product = wc_get_product($product_id);
            
            if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
                echo '<div class="notice notice-error"><p>' . esc_html__('Invalid auction product.', 'aqualuxe') . '</p></div>';
                return;
            }
            
            $bids = aqualuxe_auctions_get_bids($product, 100);
            $current_price = aqualuxe_auctions_get_current_price($product);
            $status = aqualuxe_auctions_get_status($product);
            $winner_id = aqualuxe_auctions_get_winner($product);
            
            ?>
            <h2>
                <?php
                echo sprintf(
                    __('Bids for %s', 'aqualuxe'),
                    '<a href="' . esc_url(get_edit_post_link($product_id)) . '">' . esc_html($product->get_title()) . '</a>'
                );
                ?>
            </h2>
            
            <div class="aqualuxe-auction-info">
                <p>
                    <strong><?php esc_html_e('Current Price:', 'aqualuxe'); ?></strong>
                    <?php echo wc_price($current_price); ?>
                </p>
                
                <p>
                    <strong><?php esc_html_e('Status:', 'aqualuxe'); ?></strong>
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
                </p>
                
                <?php if ($status === 'ended') : ?>
                    <p>
                        <strong><?php esc_html_e('Winner:', 'aqualuxe'); ?></strong>
                        <?php
                        if ($winner_id) {
                            $winner = get_user_by('id', $winner_id);
                            echo $winner ? '<a href="' . esc_url(get_edit_user_link($winner_id)) . '">' . esc_html($winner->display_name) . '</a>' : esc_html__('Unknown', 'aqualuxe');
                        } else {
                            echo esc_html__('No winner', 'aqualuxe');
                        }
                        ?>
                    </p>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($bids)) : ?>
                <table class="widefat">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Bidder', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Amount', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($bids as $bid) {
                            $user = get_user_by('id', $bid['user_id']);
                            $username = $user ? $user->display_name : __('Unknown', 'aqualuxe');
                            
                            ?>
                            <tr>
                                <td>
                                    <?php
                                    if ($user) {
                                        echo '<a href="' . esc_url(get_edit_user_link($bid['user_id'])) . '">' . esc_html($username) . '</a>';
                                    } else {
                                        echo esc_html($username);
                                    }
                                    ?>
                                </td>
                                <td><?php echo wc_price($bid['amount']); ?></td>
                                <td><?php echo esc_html(aqualuxe_auctions_format_time($bid['date_created'])); ?></td>
                                <td>
                                    <?php if ($status === 'ended' && !$winner_id) : ?>
                                        <button class="button set-winner" data-product-id="<?php echo esc_attr($product_id); ?>" data-user-id="<?php echo esc_attr($bid['user_id']); ?>">
                                            <?php esc_html_e('Set as Winner', 'aqualuxe'); ?>
                                        </button>
                                    <?php endif; ?>
                                    
                                    <button class="button delete-bid" data-bid-id="<?php echo esc_attr($bid['id']); ?>">
                                        <?php esc_html_e('Delete Bid', 'aqualuxe'); ?>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                
                <script type="text/javascript">
                    jQuery(function($) {
                        $('.delete-bid').on('click', function() {
                            var bidId = $(this).data('bid-id');
                            
                            if (confirm('<?php esc_html_e('Are you sure you want to delete this bid?', 'aqualuxe'); ?>')) {
                                $.ajax({
                                    url: ajaxurl,
                                    type: 'POST',
                                    data: {
                                        action: 'aqualuxe_admin_delete_bid',
                                        bid_id: bidId,
                                        nonce: '<?php echo wp_create_nonce('aqualuxe_admin_delete_bid'); ?>'
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            alert(response.data.message);
                                            location.reload();
                                        } else {
                                            alert(response.data.message);
                                        }
                                    }
                                });
                            }
                        });
                        
                        $('.set-winner').on('click', function() {
                            var productId = $(this).data('product-id');
                            var userId = $(this).data('user-id');
                            
                            if (confirm('<?php esc_html_e('Are you sure you want to set this user as the winner?', 'aqualuxe'); ?>')) {
                                $.ajax({
                                    url: ajaxurl,
                                    type: 'POST',
                                    data: {
                                        action: 'aqualuxe_admin_set_winner',
                                        product_id: productId,
                                        user_id: userId,
                                        nonce: '<?php echo wp_create_nonce('aqualuxe_admin_set_winner'); ?>'
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            alert(response.data.message);
                                            location.reload();
                                        } else {
                                            alert(response.data.message);
                                        }
                                    }
                                });
                            }
                        });
                    });
                </script>
            <?php else : ?>
                <p><?php esc_html_e('No bids found for this auction.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        <?php else : ?>
            <?php
            global $wpdb;
            
            $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
            
            $bids = $wpdb->get_results(
                "SELECT b.*, p.post_title, u.display_name
                FROM {$bids_table} b
                JOIN {$wpdb->posts} p ON b.product_id = p.ID
                JOIN {$wpdb->users} u ON b.user_id = u.ID
                ORDER BY b.date_created DESC
                LIMIT 100"
            );
            ?>
            
            <h2><?php esc_html_e('Recent Bids', 'aqualuxe'); ?></h2>
            
            <?php if (!empty($bids)) : ?>
                <table class="widefat">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Auction', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Bidder', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Amount', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Actions', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($bids as $bid) {
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo esc_url(get_edit_post_link($bid->product_id)); ?>">
                                        <?php echo esc_html($bid->post_title); ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo esc_url(get_edit_user_link($bid->user_id)); ?>">
                                        <?php echo esc_html($bid->display_name); ?>
                                    </a>
                                </td>
                                <td><?php echo wc_price($bid->amount); ?></td>
                                <td><?php echo esc_html(aqualuxe_auctions_format_time($bid->date_created)); ?></td>
                                <td>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-auction-bids&product_id=' . $bid->product_id)); ?>" class="button">
                                        <?php esc_html_e('View Auction Bids', 'aqualuxe'); ?>
                                    </a>
                                    
                                    <button class="button delete-bid" data-bid-id="<?php echo esc_attr($bid->id); ?>">
                                        <?php esc_html_e('Delete Bid', 'aqualuxe'); ?>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                
                <script type="text/javascript">
                    jQuery(function($) {
                        $('.delete-bid').on('click', function() {
                            var bidId = $(this).data('bid-id');
                            
                            if (confirm('<?php esc_html_e('Are you sure you want to delete this bid?', 'aqualuxe'); ?>')) {
                                $.ajax({
                                    url: ajaxurl,
                                    type: 'POST',
                                    data: {
                                        action: 'aqualuxe_admin_delete_bid',
                                        bid_id: bidId,
                                        nonce: '<?php echo wp_create_nonce('aqualuxe_admin_delete_bid'); ?>'
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            alert(response.data.message);
                                            location.reload();
                                        } else {
                                            alert(response.data.message);
                                        }
                                    }
                                });
                            }
                        });
                    });
                </script>
            <?php else : ?>
                <p><?php esc_html_e('No bids found.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Admin AJAX handler for ending auctions
 */
function aqualuxe_auctions_admin_end_auction() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_admin_end_auction')) {
        wp_send_json_error(array(
            'message' => __('Security check failed.', 'aqualuxe'),
        ));
    }
    
    // Check user capabilities
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error(array(
            'message' => __('You do not have permission to do this.', 'aqualuxe'),
        ));
    }
    
    // Check product ID
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(array(
            'message' => __('Invalid product ID.', 'aqualuxe'),
        ));
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        wp_send_json_error(array(
            'message' => __('Invalid auction product.', 'aqualuxe'),
        ));
    }
    
    // End auction
    update_post_meta($product_id, '_auction_ended', 'yes');
    update_post_meta($product_id, '_auction_end_time', current_time('mysql'));
    
    // Set winner if there are bids
    $winner_id = aqualuxe_auctions_get_winner($product);
    
    // Trigger auction ended action
    do_action('aqualuxe_auction_ended', $product_id, $winner_id);
    
    wp_send_json_success(array(
        'message' => __('Auction ended successfully.', 'aqualuxe'),
    ));
}

/**
 * Admin AJAX handler for deleting bids
 */
function aqualuxe_auctions_admin_delete_bid() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_admin_delete_bid')) {
        wp_send_json_error(array(
            'message' => __('Security check failed.', 'aqualuxe'),
        ));
    }
    
    // Check user capabilities
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error(array(
            'message' => __('You do not have permission to do this.', 'aqualuxe'),
        ));
    }
    
    // Check bid ID
    if (!isset($_POST['bid_id'])) {
        wp_send_json_error(array(
            'message' => __('Invalid bid ID.', 'aqualuxe'),
        ));
    }
    
    $bid_id = absint($_POST['bid_id']);
    
    // Delete bid
    global $wpdb;
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    // Get bid data before deleting
    $bid = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$bids_table} WHERE id = %d",
        $bid_id
    ), ARRAY_A);
    
    if (!$bid) {
        wp_send_json_error(array(
            'message' => __('Bid not found.', 'aqualuxe'),
        ));
    }
    
    // Delete bid
    $result = $wpdb->delete(
        $bids_table,
        array('id' => $bid_id),
        array('%d')
    );
    
    if (!$result) {
        wp_send_json_error(array(
            'message' => __('Failed to delete bid.', 'aqualuxe'),
        ));
    }
    
    // Update product price if this was the highest bid
    $product_id = $bid['product_id'];
    $product = wc_get_product($product_id);
    
    if ($product && aqualuxe_auctions_is_auction_product($product)) {
        $highest_bid = aqualuxe_auctions_get_highest_bid($product);
        
        if ($highest_bid) {
            update_post_meta($product_id, '_price', $highest_bid['amount']);
        } else {
            $start_price = aqualuxe_auctions_get_starting_price($product);
            update_post_meta($product_id, '_price', $start_price);
        }
    }
    
    wp_send_json_success(array(
        'message' => __('Bid deleted successfully.', 'aqualuxe'),
    ));
}

/**
 * Admin AJAX handler for setting auction winner
 */
function aqualuxe_auctions_admin_set_winner() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_admin_set_winner')) {
        wp_send_json_error(array(
            'message' => __('Security check failed.', 'aqualuxe'),
        ));
    }
    
    // Check user capabilities
    if (!current_user_can('manage_woocommerce')) {
        wp_send_json_error(array(
            'message' => __('You do not have permission to do this.', 'aqualuxe'),
        ));
    }
    
    // Check product ID and user ID
    if (!isset($_POST['product_id']) || !isset($_POST['user_id'])) {
        wp_send_json_error(array(
            'message' => __('Invalid product ID or user ID.', 'aqualuxe'),
        ));
    }
    
    $product_id = absint($_POST['product_id']);
    $user_id = absint($_POST['user_id']);
    
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_auctions_is_auction_product($product)) {
        wp_send_json_error(array(
            'message' => __('Invalid auction product.', 'aqualuxe'),
        ));
    }
    
    $user = get_user_by('id', $user_id);
    
    if (!$user) {
        wp_send_json_error(array(
            'message' => __('Invalid user.', 'aqualuxe'),
        ));
    }
    
    // Set winner
    update_post_meta($product_id, '_auction_winner', $user_id);
    
    // Trigger winner notification
    do_action('aqualuxe_auction_winner_set', $product_id, $user_id);
    
    wp_send_json_success(array(
        'message' => __('Winner set successfully.', 'aqualuxe'),
    ));
}