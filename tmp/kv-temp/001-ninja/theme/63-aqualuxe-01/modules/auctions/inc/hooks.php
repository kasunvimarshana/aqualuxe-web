<?php
/**
 * Auction module hooks
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add auction product type to WooCommerce
 */
add_filter('product_type_selector', function($types) {
    $types['auction'] = __('Auction', 'aqualuxe');
    return $types;
});

/**
 * Add auction tab to WooCommerce product data tabs
 */
add_filter('woocommerce_product_data_tabs', function($tabs) {
    $tabs['auction'] = array(
        'label' => __('Auction', 'aqualuxe'),
        'target' => 'auction_product_data',
        'class' => array('show_if_auction'),
    );
    return $tabs;
});

/**
 * Hide irrelevant product fields for auction products
 */
add_action('admin_footer', function() {
    ?>
    <script type="text/javascript">
        jQuery(function($) {
            // Hide irrelevant product fields for auction products
            $('body').on('woocommerce-product-type-change', function(event, select_val) {
                if (select_val === 'auction') {
                    // Hide these fields
                    $('.general_options, .inventory_options, .shipping_options').hide();
                    $('._regular_price_field, .sale_price_field, ._sale_price_dates_field').hide();
                    $('._manage_stock_field, ._stock_field, ._backorders_field, ._stock_status_field').hide();
                    $('._sold_individually_field, .product_shipping_class_field').hide();
                    
                    // Show auction fields
                    $('.show_if_auction').show();
                } else {
                    $('.show_if_auction').hide();
                }
            });
            
            // Trigger change on page load
            $('select#product-type').trigger('change');
        });
    </script>
    <?php
});

/**
 * Add auction product class
 */
add_filter('woocommerce_product_class', function($classname, $product_type) {
    if ($product_type === 'auction') {
        $classname = 'WC_Product_Auction';
    }
    return $classname;
}, 10, 2);

/**
 * Add auction status to product list
 */
add_filter('manage_edit-product_columns', function($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $column) {
        $new_columns[$key] = $column;
        
        if ($key === 'price') {
            $new_columns['auction_status'] = __('Auction Status', 'aqualuxe');
        }
    }
    
    return $new_columns;
});

/**
 * Display auction status in product list
 */
add_action('manage_product_posts_custom_column', function($column, $post_id) {
    if ($column === 'auction_status') {
        $product = wc_get_product($post_id);
        
        if ($product && $product->get_type() === 'auction') {
            $status = aqualuxe_auctions_get_status($product);
            
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
        }
    }
}, 10, 2);

/**
 * Add auction filter to product list
 */
add_action('restrict_manage_posts', function() {
    global $typenow;
    
    if ($typenow === 'product') {
        $current_status = isset($_GET['auction_status']) ? sanitize_text_field($_GET['auction_status']) : '';
        
        ?>
        <select name="auction_status" id="dropdown_auction_status">
            <option value=""><?php esc_html_e('All auction statuses', 'aqualuxe'); ?></option>
            <option value="scheduled" <?php selected($current_status, 'scheduled'); ?>><?php esc_html_e('Scheduled', 'aqualuxe'); ?></option>
            <option value="active" <?php selected($current_status, 'active'); ?>><?php esc_html_e('Active', 'aqualuxe'); ?></option>
            <option value="ended" <?php selected($current_status, 'ended'); ?>><?php esc_html_e('Ended', 'aqualuxe'); ?></option>
        </select>
        <?php
    }
});

/**
 * Filter products by auction status
 */
add_filter('parse_query', function($query) {
    global $typenow, $pagenow;
    
    if ($pagenow === 'edit.php' && $typenow === 'product' && isset($_GET['auction_status']) && !empty($_GET['auction_status'])) {
        $status = sanitize_text_field($_GET['auction_status']);
        $current_time = current_time('mysql');
        
        // Add tax query for auction products
        $tax_query = $query->get('tax_query');
        
        if (!is_array($tax_query)) {
            $tax_query = array();
        }
        
        $tax_query[] = array(
            'taxonomy' => 'product_type',
            'field' => 'slug',
            'terms' => 'auction',
        );
        
        $query->set('tax_query', $tax_query);
        
        // Add meta query for auction status
        $meta_query = $query->get('meta_query');
        
        if (!is_array($meta_query)) {
            $meta_query = array();
        }
        
        switch ($status) {
            case 'scheduled':
                $meta_query[] = array(
                    'key' => '_auction_start_time',
                    'value' => $current_time,
                    'compare' => '>',
                    'type' => 'DATETIME',
                );
                break;
            case 'active':
                $meta_query[] = array(
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
                $meta_query[] = array(
                    'key' => '_auction_end_time',
                    'value' => $current_time,
                    'compare' => '<=',
                    'type' => 'DATETIME',
                );
                break;
        }
        
        $query->set('meta_query', $meta_query);
    }
});

/**
 * Add auction endpoint to My Account
 */
add_action('init', function() {
    add_rewrite_endpoint('auctions', EP_ROOT | EP_PAGES);
});

/**
 * Add auction menu item to My Account
 */
add_filter('woocommerce_account_menu_items', function($items) {
    $items['auctions'] = __('My Auctions', 'aqualuxe');
    return $items;
});

/**
 * Add auction content to My Account
 */
add_action('woocommerce_account_auctions_endpoint', function() {
    // Load template
    aqualuxe_get_module_template_part('auctions', 'my-auctions');
});

/**
 * Add auction product type to product query
 */
add_filter('woocommerce_product_query_tax_query', function($tax_query, $query) {
    if (isset($_GET['product_type']) && $_GET['product_type'] === 'auction') {
        $tax_query[] = array(
            'taxonomy' => 'product_type',
            'field' => 'slug',
            'terms' => 'auction',
        );
    }
    
    return $tax_query;
}, 10, 2);

/**
 * Add auction product type to product query vars
 */
add_filter('woocommerce_product_query_vars', function($vars) {
    $vars[] = 'product_type';
    return $vars;
});

/**
 * Add auction product type to query vars
 */
add_filter('query_vars', function($vars) {
    $vars[] = 'product_type';
    return $vars;
});

/**
 * Add auction product type to breadcrumb
 */
add_filter('woocommerce_get_breadcrumb', function($breadcrumb) {
    if (isset($_GET['product_type']) && $_GET['product_type'] === 'auction') {
        $breadcrumb[] = array(__('Auctions', 'aqualuxe'), get_permalink(wc_get_page_id('shop')) . '?product_type=auction');
    }
    
    return $breadcrumb;
});

/**
 * Add auction product type to page title
 */
add_filter('woocommerce_page_title', function($title) {
    if (isset($_GET['product_type']) && $_GET['product_type'] === 'auction') {
        $title = __('Auctions', 'aqualuxe');
    }
    
    return $title;
});

/**
 * Add auction product type to body class
 */
add_filter('body_class', function($classes) {
    if (isset($_GET['product_type']) && $_GET['product_type'] === 'auction') {
        $classes[] = 'woocommerce-auctions';
    }
    
    return $classes;
});

/**
 * Add auction product type to product class
 */
add_filter('post_class', function($classes, $class, $post_id) {
    $product = wc_get_product($post_id);
    
    if ($product && $product->get_type() === 'auction') {
        $classes[] = 'product-type-auction';
        
        $status = aqualuxe_auctions_get_status($product);
        $classes[] = 'auction-status-' . $status;
        
        if (aqualuxe_auctions_has_reserve_price($product)) {
            $classes[] = 'auction-has-reserve';
            
            if (aqualuxe_auctions_is_reserve_met($product)) {
                $classes[] = 'auction-reserve-met';
            } else {
                $classes[] = 'auction-reserve-not-met';
            }
        }
    }
    
    return $classes;
}, 10, 3);

/**
 * Add auction product type to product tabs
 */
add_filter('woocommerce_product_tabs', function($tabs) {
    global $product;
    
    if ($product && $product->get_type() === 'auction') {
        // Add bid history tab
        $tabs['bid_history'] = array(
            'title' => __('Bid History', 'aqualuxe'),
            'priority' => 30,
            'callback' => 'aqualuxe_auctions_bid_history_tab',
        );
        
        // Remove reviews tab
        unset($tabs['reviews']);
        
        // Remove additional information tab
        unset($tabs['additional_information']);
    }
    
    return $tabs;
});

/**
 * Bid history tab content
 */
function aqualuxe_auctions_bid_history_tab() {
    global $product;
    
    if ($product && $product->get_type() === 'auction') {
        aqualuxe_auctions_bid_history();
    }
}

/**
 * Add auction product type to related products
 */
add_filter('woocommerce_related_products_args', function($args) {
    global $product;
    
    if ($product && $product->get_type() === 'auction') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_type',
                'field' => 'slug',
                'terms' => 'auction',
            ),
        );
    }
    
    return $args;
});

/**
 * Add auction product type to upsells
 */
add_filter('woocommerce_upsell_display_args', function($args) {
    global $product;
    
    if ($product && $product->get_type() === 'auction') {
        $args['meta_query'] = array(
            array(
                'key' => '_auction_end_time',
                'value' => current_time('mysql'),
                'compare' => '>',
                'type' => 'DATETIME',
            ),
        );
    }
    
    return $args;
});

/**
 * Add auction product type to cross-sells
 */
add_filter('woocommerce_cross_sells_args', function($args) {
    global $product;
    
    if ($product && $product->get_type() === 'auction') {
        $args['meta_query'] = array(
            array(
                'key' => '_auction_end_time',
                'value' => current_time('mysql'),
                'compare' => '>',
                'type' => 'DATETIME',
            ),
        );
    }
    
    return $args;
});

/**
 * Add auction product type to cart item data
 */
add_filter('woocommerce_add_cart_item_data', function($cart_item_data, $product_id, $variation_id) {
    $product = wc_get_product($product_id);
    
    if ($product && $product->get_type() === 'auction') {
        $cart_item_data['auction_data'] = array(
            'is_auction' => true,
            'buy_now_price' => $product->get_auction_buy_now_price(),
        );
    }
    
    return $cart_item_data;
}, 10, 3);

/**
 * Add auction product type to cart item
 */
add_filter('woocommerce_get_cart_item_from_session', function($cart_item, $values) {
    if (isset($values['auction_data'])) {
        $cart_item['auction_data'] = $values['auction_data'];
    }
    
    return $cart_item;
}, 10, 2);

/**
 * Add auction product type to cart item price
 */
add_filter('woocommerce_cart_item_price', function($price, $cart_item, $cart_item_key) {
    if (isset($cart_item['auction_data']) && $cart_item['auction_data']['is_auction']) {
        $price = wc_price($cart_item['auction_data']['buy_now_price']);
    }
    
    return $price;
}, 10, 3);

/**
 * Add auction product type to order item meta
 */
add_action('woocommerce_checkout_create_order_line_item', function($item, $cart_item_key, $values, $order) {
    if (isset($values['auction_data']) && $values['auction_data']['is_auction']) {
        $item->add_meta_data('_auction_buy_now', true);
        $item->add_meta_data('_auction_buy_now_price', $values['auction_data']['buy_now_price']);
    }
}, 10, 4);

/**
 * Add auction product type to order item display
 */
add_filter('woocommerce_order_item_name', function($name, $item, $is_visible) {
    if ($item->get_meta('_auction_buy_now')) {
        $name .= ' <small>' . __('(Buy Now)', 'aqualuxe') . '</small>';
    }
    
    return $name;
}, 10, 3);

/**
 * Add auction product type to order item meta display
 */
add_filter('woocommerce_order_item_get_formatted_meta_data', function($formatted_meta, $item) {
    foreach ($formatted_meta as $key => $meta) {
        if ($meta->key === '_auction_buy_now' || $meta->key === '_auction_buy_now_price') {
            unset($formatted_meta[$key]);
        }
    }
    
    return $formatted_meta;
}, 10, 2);

/**
 * Add auction product type to order completed
 */
add_action('woocommerce_order_status_completed', function($order_id) {
    $order = wc_get_order($order_id);
    
    foreach ($order->get_items() as $item) {
        if ($item->get_meta('_auction_buy_now')) {
            $product_id = $item->get_product_id();
            $product = wc_get_product($product_id);
            
            if ($product && $product->get_type() === 'auction') {
                // End auction early
                update_post_meta($product_id, '_auction_ended', 'yes');
                update_post_meta($product_id, '_auction_end_time', current_time('mysql'));
                
                // Set buyer as winner
                update_post_meta($product_id, '_auction_winner', $order->get_customer_id());
                
                // Trigger auction ended action
                do_action('aqualuxe_auction_ended', $product_id, $order->get_customer_id());
            }
        }
    }
});

/**
 * Add auction product type to user profile
 */
add_action('show_user_profile', 'aqualuxe_auctions_user_profile_fields');
add_action('edit_user_profile', 'aqualuxe_auctions_user_profile_fields');

/**
 * User profile fields for auctions
 *
 * @param WP_User $user User object
 */
function aqualuxe_auctions_user_profile_fields($user) {
    if (!current_user_can('manage_woocommerce')) {
        return;
    }
    
    ?>
    <h2><?php esc_html_e('Auction Information', 'aqualuxe'); ?></h2>
    
    <table class="form-table">
        <tr>
            <th><label for="auction_bid_count"><?php esc_html_e('Total Bids', 'aqualuxe'); ?></label></th>
            <td>
                <?php
                global $wpdb;
                
                $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
                
                $bid_count = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM {$bids_table} WHERE user_id = %d",
                    $user->ID
                ));
                
                echo esc_html($bid_count);
                ?>
            </td>
        </tr>
        <tr>
            <th><label for="auction_won_count"><?php esc_html_e('Auctions Won', 'aqualuxe'); ?></label></th>
            <td>
                <?php
                $won_count = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_auction_winner' AND meta_value = %d",
                    $user->ID
                ));
                
                echo esc_html($won_count);
                ?>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Add auction product type to user dashboard
 */
add_action('woocommerce_account_dashboard', function() {
    $user_id = get_current_user_id();
    
    global $wpdb;
    
    $bids_table = $wpdb->prefix . 'aqualuxe_auction_bids';
    
    $bid_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$bids_table} WHERE user_id = %d",
        $user_id
    ));
    
    $won_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_auction_winner' AND meta_value = %d",
        $user_id
    ));
    
    ?>
    <h2><?php esc_html_e('Auction Information', 'aqualuxe'); ?></h2>
    
    <ul class="woocommerce-auction-info">
        <li>
            <?php
            echo wp_kses_post(
                sprintf(
                    __('You have placed <strong>%d</strong> bids.', 'aqualuxe'),
                    $bid_count
                )
            );
            ?>
        </li>
        <li>
            <?php
            echo wp_kses_post(
                sprintf(
                    __('You have won <strong>%d</strong> auctions.', 'aqualuxe'),
                    $won_count
                )
            );
            ?>
        </li>
    </ul>
    
    <p>
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('auctions')); ?>" class="button">
            <?php esc_html_e('View My Auctions', 'aqualuxe'); ?>
        </a>
    </p>
    <?php
});

/**
 * Add auction product type to product search
 */
add_filter('woocommerce_product_search_fields', function($fields) {
    $fields[] = '_auction_start_time';
    $fields[] = '_auction_end_time';
    $fields[] = '_auction_start_price';
    $fields[] = '_auction_reserve_price';
    $fields[] = '_auction_bid_increment';
    $fields[] = '_auction_buy_now_price';
    $fields[] = '_auction_winner';
    
    return $fields;
});

/**
 * Add auction product type to product sorting
 */
add_filter('woocommerce_catalog_orderby', function($orderby) {
    $orderby['auction_end_time'] = __('Sort by auction end time', 'aqualuxe');
    
    return $orderby;
});

/**
 * Add auction product type to product sorting query
 */
add_filter('woocommerce_get_catalog_ordering_args', function($args) {
    if (isset($_GET['orderby']) && $_GET['orderby'] === 'auction_end_time') {
        $args['meta_key'] = '_auction_end_time';
        $args['orderby'] = 'meta_value';
        $args['order'] = 'ASC';
    }
    
    return $args;
});

/**
 * Add auction product type to product filtering
 */
add_action('woocommerce_before_shop_loop', function() {
    if (isset($_GET['product_type']) && $_GET['product_type'] === 'auction') {
        $current_status = isset($_GET['auction_status']) ? sanitize_text_field($_GET['auction_status']) : '';
        
        ?>
        <div class="auction-filter">
            <form method="get" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                <input type="hidden" name="product_type" value="auction" />
                
                <select name="auction_status">
                    <option value=""><?php esc_html_e('All auction statuses', 'aqualuxe'); ?></option>
                    <option value="scheduled" <?php selected($current_status, 'scheduled'); ?>><?php esc_html_e('Scheduled', 'aqualuxe'); ?></option>
                    <option value="active" <?php selected($current_status, 'active'); ?>><?php esc_html_e('Active', 'aqualuxe'); ?></option>
                    <option value="ended" <?php selected($current_status, 'ended'); ?>><?php esc_html_e('Ended', 'aqualuxe'); ?></option>
                </select>
                
                <button type="submit" class="button"><?php esc_html_e('Filter', 'aqualuxe'); ?></button>
            </form>
        </div>
        <?php
    }
});

/**
 * Add auction product type to product filtering query
 */
add_filter('woocommerce_product_query_meta_query', function($meta_query, $query) {
    if (isset($_GET['auction_status']) && !empty($_GET['auction_status'])) {
        $status = sanitize_text_field($_GET['auction_status']);
        $current_time = current_time('mysql');
        
        switch ($status) {
            case 'scheduled':
                $meta_query[] = array(
                    'key' => '_auction_start_time',
                    'value' => $current_time,
                    'compare' => '>',
                    'type' => 'DATETIME',
                );
                break;
            case 'active':
                $meta_query[] = array(
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
                $meta_query[] = array(
                    'key' => '_auction_end_time',
                    'value' => $current_time,
                    'compare' => '<=',
                    'type' => 'DATETIME',
                );
                break;
        }
    }
    
    return $meta_query;
}, 10, 2);

/**
 * Add auction product type to product widgets
 */
add_filter('woocommerce_products_widget_query_args', function($args) {
    if (isset($args['auction_status'])) {
        $status = $args['auction_status'];
        $current_time = current_time('mysql');
        
        switch ($status) {
            case 'scheduled':
                $args['meta_query'][] = array(
                    'key' => '_auction_start_time',
                    'value' => $current_time,
                    'compare' => '>',
                    'type' => 'DATETIME',
                );
                break;
            case 'active':
                $args['meta_query'][] = array(
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
                $args['meta_query'][] = array(
                    'key' => '_auction_end_time',
                    'value' => $current_time,
                    'compare' => '<=',
                    'type' => 'DATETIME',
                );
                break;
        }
    }
    
    return $args;
});

/**
 * Add auction product type to product shortcodes
 */
add_filter('woocommerce_shortcode_products_query', function($query_args, $attributes, $type) {
    if (isset($attributes['auction_status'])) {
        $status = $attributes['auction_status'];
        $current_time = current_time('mysql');
        
        switch ($status) {
            case 'scheduled':
                $query_args['meta_query'][] = array(
                    'key' => '_auction_start_time',
                    'value' => $current_time,
                    'compare' => '>',
                    'type' => 'DATETIME',
                );
                break;
            case 'active':
                $query_args['meta_query'][] = array(
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
                $query_args['meta_query'][] = array(
                    'key' => '_auction_end_time',
                    'value' => $current_time,
                    'compare' => '<=',
                    'type' => 'DATETIME',
                );
                break;
        }
    }
    
    return $query_args;
}, 10, 3);

/**
 * Add auction product type to product shortcode attributes
 */
add_filter('shortcode_atts_products', function($out, $pairs, $atts) {
    if (isset($atts['auction_status'])) {
        $out['auction_status'] = $atts['auction_status'];
    }
    
    return $out;
}, 10, 3);

/**
 * Add auction product type to product shortcode tag
 */
add_filter('woocommerce_shortcode_products_query', function($query_args, $attributes, $type) {
    if (isset($attributes['product_type']) && $attributes['product_type'] === 'auction') {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'product_type',
            'field' => 'slug',
            'terms' => 'auction',
        );
    }
    
    return $query_args;
}, 10, 3);

/**
 * Add auction product type to product shortcode attributes
 */
add_filter('shortcode_atts_products', function($out, $pairs, $atts) {
    if (isset($atts['product_type'])) {
        $out['product_type'] = $atts['product_type'];
    }
    
    return $out;
}, 10, 3);