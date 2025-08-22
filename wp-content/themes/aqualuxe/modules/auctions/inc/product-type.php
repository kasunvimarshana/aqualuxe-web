<?php
/**
 * Auction product type
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register auction product type
 */
function aqualuxe_auctions_register_product_type() {
    // Include auction product class
    require_once dirname(__FILE__) . '/class-wc-product-auction.php';
    
    // Register product type
    add_filter('product_type_selector', 'aqualuxe_auctions_add_product_type');
    add_filter('woocommerce_product_class', 'aqualuxe_auctions_product_class', 10, 4);
    
    // Add product type tabs
    add_filter('woocommerce_product_data_tabs', 'aqualuxe_auctions_product_tabs');
    add_action('woocommerce_product_data_panels', 'aqualuxe_auctions_product_panels');
    
    // Save product data
    add_action('woocommerce_process_product_meta_auction', 'aqualuxe_auctions_save_product_data');
    
    // Product columns
    add_filter('manage_edit-product_columns', 'aqualuxe_auctions_product_columns');
    add_action('manage_product_posts_custom_column', 'aqualuxe_auctions_product_column_content', 10, 2);
    
    // Product filters
    add_action('restrict_manage_posts', 'aqualuxe_auctions_product_filters');
    add_filter('parse_query', 'aqualuxe_auctions_product_filter_query');
}

/**
 * Add auction product type to WooCommerce
 *
 * @param array $types Product types
 * @return array
 */
function aqualuxe_auctions_add_product_type($types) {
    $types['auction'] = __('Auction', 'aqualuxe');
    
    return $types;
}

/**
 * Set product class for auction products
 *
 * @param string $classname Product class name
 * @param string $product_type Product type
 * @param string $post_type Post type
 * @param int $product_id Product ID
 * @return string
 */
function aqualuxe_auctions_product_class($classname, $product_type, $post_type, $product_id) {
    if ($product_type === 'auction') {
        $classname = 'WC_Product_Auction';
    }
    
    return $classname;
}

/**
 * Add auction product tabs
 *
 * @param array $tabs Product tabs
 * @return array
 */
function aqualuxe_auctions_product_tabs($tabs) {
    $tabs['auction'] = array(
        'label' => __('Auction', 'aqualuxe'),
        'target' => 'auction_product_data',
        'class' => array('show_if_auction'),
        'priority' => 21,
    );
    
    return $tabs;
}

/**
 * Add auction product panels
 */
function aqualuxe_auctions_product_panels() {
    global $post;
    
    // Get auction data
    $start_time = get_post_meta($post->ID, '_auction_start_time', true);
    $end_time = get_post_meta($post->ID, '_auction_end_time', true);
    $start_price = get_post_meta($post->ID, '_auction_start_price', true);
    $reserve_price = get_post_meta($post->ID, '_auction_reserve_price', true);
    $bid_increment = get_post_meta($post->ID, '_auction_bid_increment', true);
    $buy_now_price = get_post_meta($post->ID, '_auction_buy_now_price', true);
    $allow_buy_now = get_post_meta($post->ID, '_auction_allow_buy_now', true);
    
    // Format dates
    $start_time = $start_time ? date('Y-m-d H:i', strtotime($start_time)) : '';
    $end_time = $end_time ? date('Y-m-d H:i', strtotime($end_time)) : '';
    
    ?>
    <div id="auction_product_data" class="panel woocommerce_options_panel">
        <div class="options_group">
            <p class="form-field">
                <label for="_auction_start_time"><?php esc_html_e('Start Time', 'aqualuxe'); ?></label>
                <input type="text" class="short datetimepicker" name="_auction_start_time" id="_auction_start_time" value="<?php echo esc_attr($start_time); ?>" placeholder="<?php esc_html_e('YYYY-MM-DD HH:MM', 'aqualuxe'); ?>" />
                <span class="description"><?php esc_html_e('The time when the auction starts.', 'aqualuxe'); ?></span>
            </p>
            
            <p class="form-field">
                <label for="_auction_end_time"><?php esc_html_e('End Time', 'aqualuxe'); ?></label>
                <input type="text" class="short datetimepicker" name="_auction_end_time" id="_auction_end_time" value="<?php echo esc_attr($end_time); ?>" placeholder="<?php esc_html_e('YYYY-MM-DD HH:MM', 'aqualuxe'); ?>" />
                <span class="description"><?php esc_html_e('The time when the auction ends.', 'aqualuxe'); ?></span>
            </p>
        </div>
        
        <div class="options_group">
            <p class="form-field">
                <label for="_auction_start_price"><?php esc_html_e('Starting Price', 'aqualuxe'); ?></label>
                <input type="text" class="short wc_input_price" name="_auction_start_price" id="_auction_start_price" value="<?php echo esc_attr($start_price); ?>" placeholder="0.00" />
                <span class="description"><?php esc_html_e('The starting price for the auction.', 'aqualuxe'); ?></span>
            </p>
            
            <p class="form-field">
                <label for="_auction_reserve_price"><?php esc_html_e('Reserve Price', 'aqualuxe'); ?></label>
                <input type="text" class="short wc_input_price" name="_auction_reserve_price" id="_auction_reserve_price" value="<?php echo esc_attr($reserve_price); ?>" placeholder="0.00" />
                <span class="description"><?php esc_html_e('The minimum price at which you are willing to sell the item. Leave blank for no reserve price.', 'aqualuxe'); ?></span>
            </p>
            
            <p class="form-field">
                <label for="_auction_bid_increment"><?php esc_html_e('Bid Increment', 'aqualuxe'); ?></label>
                <input type="text" class="short wc_input_price" name="_auction_bid_increment" id="_auction_bid_increment" value="<?php echo esc_attr($bid_increment); ?>" placeholder="0.00" />
                <span class="description"><?php esc_html_e('The minimum amount by which a bid must exceed the current highest bid. Leave blank to use the default value from settings.', 'aqualuxe'); ?></span>
            </p>
        </div>
        
        <div class="options_group">
            <p class="form-field">
                <label for="_auction_allow_buy_now"><?php esc_html_e('Allow Buy Now', 'aqualuxe'); ?></label>
                <input type="checkbox" class="checkbox" name="_auction_allow_buy_now" id="_auction_allow_buy_now" value="yes" <?php checked($allow_buy_now, 'yes'); ?> />
                <span class="description"><?php esc_html_e('Allow users to buy the item immediately at a fixed price.', 'aqualuxe'); ?></span>
            </p>
            
            <p class="form-field show_if_allow_buy_now">
                <label for="_auction_buy_now_price"><?php esc_html_e('Buy Now Price', 'aqualuxe'); ?></label>
                <input type="text" class="short wc_input_price" name="_auction_buy_now_price" id="_auction_buy_now_price" value="<?php echo esc_attr($buy_now_price); ?>" placeholder="0.00" />
                <span class="description"><?php esc_html_e('The price at which a user can buy the item immediately.', 'aqualuxe'); ?></span>
            </p>
        </div>
        
        <?php if (aqualuxe_auctions_get_bid_count($post->ID) > 0) : ?>
            <div class="options_group">
                <h3><?php esc_html_e('Auction Bids', 'aqualuxe'); ?></h3>
                
                <table class="widefat">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Bidder', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Amount', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $bids = aqualuxe_auctions_get_bids($post->ID, 10);
                        
                        foreach ($bids as $bid) {
                            $user = get_user_by('id', $bid['user_id']);
                            $username = $user ? $user->display_name : __('Unknown', 'aqualuxe');
                            
                            echo '<tr>';
                            echo '<td>' . esc_html($username) . '</td>';
                            echo '<td>' . wc_price($bid['amount']) . '</td>';
                            echo '<td>' . esc_html(aqualuxe_auctions_format_time($bid['date_created'])) . '</td>';
                            echo '</tr>';
                        }
                        
                        if (empty($bids)) {
                            echo '<tr><td colspan="3">' . esc_html__('No bids yet.', 'aqualuxe') . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                
                <?php if (aqualuxe_auctions_get_bid_count($post->ID) > 10) : ?>
                    <p>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-auctions&tab=bids&product_id=' . $post->ID)); ?>" class="button">
                            <?php esc_html_e('View All Bids', 'aqualuxe'); ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script type="text/javascript">
        jQuery(function($) {
            // Show/hide buy now price field
            function toggleBuyNowPrice() {
                if ($('#_auction_allow_buy_now').is(':checked')) {
                    $('.show_if_allow_buy_now').show();
                } else {
                    $('.show_if_allow_buy_now').hide();
                }
            }
            
            $('#_auction_allow_buy_now').change(toggleBuyNowPrice);
            toggleBuyNowPrice();
            
            // Initialize datetime picker
            $('.datetimepicker').datetimepicker({
                dateFormat: 'yy-mm-dd',
                timeFormat: 'HH:mm',
                showSecond: false,
                showMillisec: false,
                showMicrosec: false,
                showTimezone: false,
                controlType: 'select',
                oneLine: true
            });
        });
    </script>
    <?php
}

/**
 * Save auction product data
 *
 * @param int $post_id Product ID
 */
function aqualuxe_auctions_save_product_data($post_id) {
    // Save auction data
    $fields = array(
        '_auction_start_time',
        '_auction_end_time',
        '_auction_start_price',
        '_auction_reserve_price',
        '_auction_bid_increment',
        '_auction_buy_now_price',
    );
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Save checkbox fields
    $checkbox_fields = array(
        '_auction_allow_buy_now',
    );
    
    foreach ($checkbox_fields as $field) {
        $value = isset($_POST[$field]) ? 'yes' : 'no';
        update_post_meta($post_id, $field, $value);
    }
    
    // Set product price to starting price
    $start_price = get_post_meta($post_id, '_auction_start_price', true);
    
    if ($start_price) {
        update_post_meta($post_id, '_price', $start_price);
        update_post_meta($post_id, '_regular_price', $start_price);
    }
}

/**
 * Add auction columns to product list
 *
 * @param array $columns Product columns
 * @return array
 */
function aqualuxe_auctions_product_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $column) {
        $new_columns[$key] = $column;
        
        if ($key === 'price') {
            $new_columns['auction_status'] = __('Auction Status', 'aqualuxe');
            $new_columns['auction_ends'] = __('Auction Ends', 'aqualuxe');
        }
    }
    
    return $new_columns;
}

/**
 * Add auction column content
 *
 * @param string $column Column name
 * @param int $post_id Product ID
 */
function aqualuxe_auctions_product_column_content($column, $post_id) {
    $product = wc_get_product($post_id);
    
    if (!$product || $product->get_type() !== 'auction') {
        return;
    }
    
    if ($column === 'auction_status') {
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
    } elseif ($column === 'auction_ends') {
        $end_time = aqualuxe_auctions_get_end_time($product);
        
        if ($end_time) {
            echo esc_html(aqualuxe_auctions_format_time($end_time));
        } else {
            echo '&mdash;';
        }
    }
}

/**
 * Add auction filters to product list
 */
function aqualuxe_auctions_product_filters() {
    global $typenow;
    
    if ($typenow !== 'product') {
        return;
    }
    
    // Add auction status filter
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

/**
 * Filter products by auction status
 *
 * @param WP_Query $query Query object
 * @return WP_Query
 */
function aqualuxe_auctions_product_filter_query($query) {
    global $typenow, $pagenow;
    
    if ($pagenow !== 'edit.php' || $typenow !== 'product' || !isset($_GET['auction_status']) || empty($_GET['auction_status'])) {
        return $query;
    }
    
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
    
    return $query;
}

/**
 * Create auction product class
 */
if (!class_exists('WC_Product_Auction')) {
    /**
     * Auction product class
     */
    class WC_Product_Auction extends WC_Product {
        /**
         * Get internal type
         *
         * @return string
         */
        public function get_type() {
            return 'auction';
        }
        
        /**
         * Get auction start time
         *
         * @return string
         */
        public function get_auction_start_time() {
            return $this->get_meta('_auction_start_time');
        }
        
        /**
         * Get auction end time
         *
         * @return string
         */
        public function get_auction_end_time() {
            return $this->get_meta('_auction_end_time');
        }
        
        /**
         * Get auction start price
         *
         * @return float
         */
        public function get_auction_start_price() {
            return (float) $this->get_meta('_auction_start_price');
        }
        
        /**
         * Get auction reserve price
         *
         * @return float
         */
        public function get_auction_reserve_price() {
            return (float) $this->get_meta('_auction_reserve_price');
        }
        
        /**
         * Get auction bid increment
         *
         * @return float
         */
        public function get_auction_bid_increment() {
            $increment = $this->get_meta('_auction_bid_increment');
            
            if ($increment === '' || $increment === false) {
                $increment = aqualuxe_get_module_option('auctions', 'default_bid_increment', 1);
            }
            
            return (float) $increment;
        }
        
        /**
         * Get auction buy now price
         *
         * @return float
         */
        public function get_auction_buy_now_price() {
            return (float) $this->get_meta('_auction_buy_now_price');
        }
        
        /**
         * Check if auction allows buy now
         *
         * @return bool
         */
        public function get_auction_allow_buy_now() {
            return $this->get_meta('_auction_allow_buy_now') === 'yes';
        }
        
        /**
         * Get auction status
         *
         * @return string
         */
        public function get_auction_status() {
            return aqualuxe_auctions_get_status($this);
        }
        
        /**
         * Get auction current price
         *
         * @return float
         */
        public function get_auction_current_price() {
            return aqualuxe_auctions_get_current_price($this);
        }
        
        /**
         * Get auction minimum bid
         *
         * @return float
         */
        public function get_auction_minimum_bid() {
            return aqualuxe_auctions_get_minimum_bid($this);
        }
        
        /**
         * Get auction highest bid
         *
         * @return array|false
         */
        public function get_auction_highest_bid() {
            return aqualuxe_auctions_get_highest_bid($this);
        }
        
        /**
         * Get auction bid count
         *
         * @return int
         */
        public function get_auction_bid_count() {
            return aqualuxe_auctions_get_bid_count($this);
        }
        
        /**
         * Get auction winner
         *
         * @return int|false
         */
        public function get_auction_winner() {
            return aqualuxe_auctions_get_winner($this);
        }
        
        /**
         * Check if auction has reserve price
         *
         * @return bool
         */
        public function has_auction_reserve_price() {
            return aqualuxe_auctions_has_reserve_price($this);
        }
        
        /**
         * Check if auction reserve price is met
         *
         * @return bool
         */
        public function is_auction_reserve_met() {
            return aqualuxe_auctions_is_reserve_met($this);
        }
        
        /**
         * Get auction time remaining
         *
         * @param bool $formatted Whether to return formatted time
         * @return string|int
         */
        public function get_auction_time_remaining($formatted = true) {
            return aqualuxe_auctions_get_time_remaining($this, $formatted);
        }
        
        /**
         * Is purchasable
         *
         * @return bool
         */
        public function is_purchasable() {
            $purchasable = true;
            
            // Check if auction is active
            if ($this->get_auction_status() !== 'active') {
                $purchasable = false;
            }
            
            // Check if buy now is allowed
            if (!$this->get_auction_allow_buy_now()) {
                $purchasable = false;
            }
            
            return apply_filters('woocommerce_is_purchasable', $purchasable, $this);
        }
        
        /**
         * Get price
         *
         * @return string
         */
        public function get_price() {
            // If buy now is allowed, return buy now price
            if ($this->get_auction_allow_buy_now()) {
                return $this->get_auction_buy_now_price();
            }
            
            // Otherwise return current price
            return $this->get_auction_current_price();
        }
        
        /**
         * Get price HTML
         *
         * @param string $price Price to display
         * @return string
         */
        public function get_price_html($price = '') {
            $status = $this->get_auction_status();
            
            if ($status === 'scheduled') {
                $price_html = '<span class="auction-price">' . __('Auction starts at', 'aqualuxe') . ' ' . wc_price($this->get_auction_start_price()) . '</span>';
            } elseif ($status === 'active') {
                $current_price = $this->get_auction_current_price();
                $price_html = '<span class="auction-price">' . __('Current bid:', 'aqualuxe') . ' ' . wc_price($current_price) . '</span>';
                
                // Add buy now price if available
                if ($this->get_auction_allow_buy_now()) {
                    $price_html .= '<span class="auction-buy-now-price">' . __('Buy now:', 'aqualuxe') . ' ' . wc_price($this->get_auction_buy_now_price()) . '</span>';
                }
            } elseif ($status === 'ended') {
                $current_price = $this->get_auction_current_price();
                $price_html = '<span class="auction-price">' . __('Final price:', 'aqualuxe') . ' ' . wc_price($current_price) . '</span>';
            } else {
                $price_html = '<span class="auction-price">' . __('Auction', 'aqualuxe') . '</span>';
            }
            
            return apply_filters('woocommerce_get_price_html', $price_html, $this);
        }
        
        /**
         * Get add to cart URL
         *
         * @return string
         */
        public function add_to_cart_url() {
            $url = $this->is_purchasable() ? remove_query_arg('added-to-cart', add_query_arg('add-to-cart', $this->get_id())) : get_permalink($this->get_id());
            
            return apply_filters('woocommerce_product_add_to_cart_url', $url, $this);
        }
        
        /**
         * Get add to cart text
         *
         * @return string
         */
        public function add_to_cart_text() {
            $text = $this->is_purchasable() ? __('Buy now', 'aqualuxe') : __('Bid now', 'aqualuxe');
            
            return apply_filters('woocommerce_product_add_to_cart_text', $text, $this);
        }
    }
}