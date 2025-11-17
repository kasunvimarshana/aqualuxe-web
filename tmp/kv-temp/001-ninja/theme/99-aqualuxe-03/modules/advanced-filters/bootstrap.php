<?php
/**
 * Advanced Filters Module Bootstrap
 *
 * @package AquaLuxe\Modules\AdvancedFilters
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Advanced Filters Module Class
 */
class AquaLuxe_Advanced_Filters {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Only initialize if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_filter_products', array($this, 'ajax_filter_products'));
        add_action('wp_ajax_nopriv_filter_products', array($this, 'ajax_filter_products'));
        add_action('woocommerce_before_shop_loop', array($this, 'add_filter_bar'), 15);
        add_shortcode('aqualuxe_product_filters', array($this, 'product_filters_shortcode'));
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        if (is_shop() || is_product_category() || is_product_tag()) {
            wp_enqueue_script(
                'aqualuxe-advanced-filters',
                AQUALUXE_THEME_URI . '/modules/advanced-filters/assets/advanced-filters.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-advanced-filters', 'aqualuxe_filters', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('filters_nonce'),
                'loading_text' => __('Loading...', 'aqualuxe'),
                'no_results_text' => __('No products found', 'aqualuxe'),
                'shop_url' => wc_get_page_permalink('shop'),
            ));
        }
    }
    
    /**
     * Add filter bar to shop page
     */
    public function add_filter_bar() {
        echo '<div class="aqualuxe-filters-wrapper">';
        $this->render_filters();
        echo '</div>';
    }
    
    /**
     * Render filters
     */
    public function render_filters() {
        ?>
        <div class="aqualuxe-product-filters">
            <div class="filters-header">
                <h3><?php _e('Filter Products', 'aqualuxe'); ?></h3>
                <button class="filters-toggle" aria-expanded="false">
                    <?php _e('Filters', 'aqualuxe'); ?>
                    <span class="toggle-icon">▼</span>
                </button>
            </div>
            
            <div class="filters-content">
                <form class="filters-form" data-action="filter">
                    
                    <!-- Price Range Filter -->
                    <div class="filter-group">
                        <h4><?php _e('Price Range', 'aqualuxe'); ?></h4>
                        <div class="price-filter">
                            <?php
                            $min_price = $this->get_min_price();
                            $max_price = $this->get_max_price();
                            $current_min = isset($_GET['min_price']) ? intval($_GET['min_price']) : $min_price;
                            $current_max = isset($_GET['max_price']) ? intval($_GET['max_price']) : $max_price;
                            ?>
                            <div class="price-inputs">
                                <input type="number" name="min_price" class="price-input" 
                                       placeholder="<?php echo esc_attr($min_price); ?>" 
                                       value="<?php echo esc_attr($current_min !== $min_price ? $current_min : ''); ?>"
                                       min="<?php echo esc_attr($min_price); ?>" 
                                       max="<?php echo esc_attr($max_price); ?>">
                                <span class="price-separator">-</span>
                                <input type="number" name="max_price" class="price-input" 
                                       placeholder="<?php echo esc_attr($max_price); ?>" 
                                       value="<?php echo esc_attr($current_max !== $max_price ? $current_max : ''); ?>"
                                       min="<?php echo esc_attr($min_price); ?>" 
                                       max="<?php echo esc_attr($max_price); ?>">
                            </div>
                            <div class="price-range-slider">
                                <input type="range" name="price_range_min" class="range-min" 
                                       min="<?php echo esc_attr($min_price); ?>" 
                                       max="<?php echo esc_attr($max_price); ?>" 
                                       value="<?php echo esc_attr($current_min); ?>">
                                <input type="range" name="price_range_max" class="range-max" 
                                       min="<?php echo esc_attr($min_price); ?>" 
                                       max="<?php echo esc_attr($max_price); ?>" 
                                       value="<?php echo esc_attr($current_max); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="filter-group">
                        <h4><?php _e('Categories', 'aqualuxe'); ?></h4>
                        <div class="category-filter">
                            <?php
                            $categories = get_terms(array(
                                'taxonomy' => 'product_cat',
                                'hide_empty' => true,
                                'parent' => 0,
                            ));
                            
                            $selected_categories = isset($_GET['product_cat']) ? explode(',', $_GET['product_cat']) : array();
                            
                            foreach ($categories as $category) {
                                $checked = in_array($category->slug, $selected_categories) ? 'checked' : '';
                                ?>
                                <label class="filter-checkbox">
                                    <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($category->slug); ?>" <?php echo $checked; ?>>
                                    <span class="checkmark"></span>
                                    <?php echo esc_html($category->name); ?>
                                    <span class="count">(<?php echo $category->count; ?>)</span>
                                </label>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    
                    <!-- Brand Filter (Product Attributes) -->
                    <?php
                    $brand_attribute = $this->get_brand_attribute();
                    if ($brand_attribute) {
                        ?>
                        <div class="filter-group">
                            <h4><?php echo esc_html($brand_attribute->attribute_label); ?></h4>
                            <div class="brand-filter">
                                <?php
                                $brands = get_terms(array(
                                    'taxonomy' => 'pa_' . $brand_attribute->attribute_name,
                                    'hide_empty' => true,
                                ));
                                
                                $selected_brands = isset($_GET['pa_' . $brand_attribute->attribute_name]) ? explode(',', $_GET['pa_' . $brand_attribute->attribute_name]) : array();
                                
                                foreach ($brands as $brand) {
                                    $checked = in_array($brand->slug, $selected_brands) ? 'checked' : '';
                                    ?>
                                    <label class="filter-checkbox">
                                        <input type="checkbox" name="pa_<?php echo esc_attr($brand_attribute->attribute_name); ?>[]" value="<?php echo esc_attr($brand->slug); ?>" <?php echo $checked; ?>>
                                        <span class="checkmark"></span>
                                        <?php echo esc_html($brand->name); ?>
                                        <span class="count">(<?php echo $brand->count; ?>)</span>
                                    </label>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    
                    <!-- Stock Status Filter -->
                    <div class="filter-group">
                        <h4><?php _e('Availability', 'aqualuxe'); ?></h4>
                        <div class="stock-filter">
                            <?php
                            $selected_stock = isset($_GET['stock_status']) ? $_GET['stock_status'] : '';
                            ?>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="stock_status" value="instock" <?php checked($selected_stock, 'instock'); ?>>
                                <span class="checkmark"></span>
                                <?php _e('In Stock', 'aqualuxe'); ?>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="stock_status" value="onbackorder" <?php checked($selected_stock, 'onbackorder'); ?>>
                                <span class="checkmark"></span>
                                <?php _e('On Backorder', 'aqualuxe'); ?>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Rating Filter -->
                    <div class="filter-group">
                        <h4><?php _e('Customer Rating', 'aqualuxe'); ?></h4>
                        <div class="rating-filter">
                            <?php
                            $selected_rating = isset($_GET['rating']) ? intval($_GET['rating']) : 0;
                            
                            for ($i = 5; $i >= 1; $i--) {
                                $checked = $selected_rating === $i ? 'checked' : '';
                                ?>
                                <label class="filter-radio">
                                    <input type="radio" name="rating" value="<?php echo $i; ?>" <?php echo $checked; ?>>
                                    <span class="rating-stars">
                                        <?php echo wc_get_rating_html($i); ?>
                                    </span>
                                    <?php _e('& Up', 'aqualuxe'); ?>
                                </label>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    
                    <!-- Filter Actions -->
                    <div class="filter-actions">
                        <button type="submit" class="apply-filters-btn">
                            <?php _e('Apply Filters', 'aqualuxe'); ?>
                        </button>
                        <button type="button" class="clear-filters-btn">
                            <?php _e('Clear All', 'aqualuxe'); ?>
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get minimum price
     */
    private function get_min_price() {
        global $wpdb;
        
        $min_price = $wpdb->get_var("
            SELECT MIN(CAST(meta_value AS DECIMAL(10,2)))
            FROM {$wpdb->postmeta}
            WHERE meta_key = '_price'
            AND meta_value != ''
        ");
        
        return $min_price ? floor($min_price) : 0;
    }
    
    /**
     * Get maximum price
     */
    private function get_max_price() {
        global $wpdb;
        
        $max_price = $wpdb->get_var("
            SELECT MAX(CAST(meta_value AS DECIMAL(10,2)))
            FROM {$wpdb->postmeta}
            WHERE meta_key = '_price'
            AND meta_value != ''
        ");
        
        return $max_price ? ceil($max_price) : 1000;
    }
    
    /**
     * Get brand attribute
     */
    private function get_brand_attribute() {
        global $wpdb;
        
        $brand_attribute = $wpdb->get_row(
            "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies 
             WHERE attribute_name = 'brand' OR attribute_name = 'manufacturer'
             LIMIT 1"
        );
        
        return $brand_attribute;
    }
    
    /**
     * AJAX handler for product filtering
     */
    public function ajax_filter_products() {
        check_ajax_referer('filters_nonce', 'nonce');
        
        $filters = $_POST['filters'];
        
        // Build query args
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => wc_get_default_products_per_row() * wc_get_default_product_rows_per_page(),
            'paged' => isset($_POST['paged']) ? intval($_POST['paged']) : 1,
            'meta_query' => array(),
            'tax_query' => array(),
        );
        
        // Price filter
        if (!empty($filters['min_price']) || !empty($filters['max_price'])) {
            $price_meta_query = array();
            
            if (!empty($filters['min_price'])) {
                $price_meta_query[] = array(
                    'key' => '_price',
                    'value' => floatval($filters['min_price']),
                    'compare' => '>=',
                    'type' => 'DECIMAL(10,2)',
                );
            }
            
            if (!empty($filters['max_price'])) {
                $price_meta_query[] = array(
                    'key' => '_price',
                    'value' => floatval($filters['max_price']),
                    'compare' => '<=',
                    'type' => 'DECIMAL(10,2)',
                );
            }
            
            if (count($price_meta_query) > 1) {
                $price_meta_query['relation'] = 'AND';
            }
            
            $args['meta_query'][] = $price_meta_query;
        }
        
        // Category filter
        if (!empty($filters['product_cat'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $filters['product_cat'],
            );
        }
        
        // Stock status filter
        if (!empty($filters['stock_status'])) {
            $args['meta_query'][] = array(
                'key' => '_stock_status',
                'value' => $filters['stock_status'],
                'compare' => '=',
            );
        }
        
        // Rating filter
        if (!empty($filters['rating'])) {
            $args['meta_query'][] = array(
                'key' => '_wc_average_rating',
                'value' => floatval($filters['rating']),
                'compare' => '>=',
                'type' => 'DECIMAL(3,2)',
            );
        }
        
        // Attribute filters
        foreach ($filters as $key => $value) {
            if (strpos($key, 'pa_') === 0 && !empty($value)) {
                $args['tax_query'][] = array(
                    'taxonomy' => $key,
                    'field' => 'slug',
                    'terms' => $value,
                );
            }
        }
        
        // Set tax query relation
        if (count($args['tax_query']) > 1) {
            $args['tax_query']['relation'] = 'AND';
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        
        if ($query->have_posts()) {
            woocommerce_product_loop_start();
            
            while ($query->have_posts()) {
                $query->the_post();
                wc_get_template_part('content', 'product');
            }
            
            woocommerce_product_loop_end();
            
            // Pagination
            if ($query->max_num_pages > 1) {
                echo '<div class="woocommerce-pagination">';
                echo paginate_links(array(
                    'total' => $query->max_num_pages,
                    'current' => $args['paged'],
                    'format' => '?paged=%#%',
                    'show_all' => false,
                    'end_size' => 1,
                    'mid_size' => 2,
                    'prev_next' => true,
                    'prev_text' => __('« Previous', 'aqualuxe'),
                    'next_text' => __('Next »', 'aqualuxe'),
                    'type' => 'plain',
                ));
                echo '</div>';
            }
        } else {
            echo '<div class="no-products-found">';
            echo '<p>' . __('No products found matching your criteria.', 'aqualuxe') . '</p>';
            echo '</div>';
        }
        
        $content = ob_get_clean();
        
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'content' => $content,
            'found_posts' => $query->found_posts,
            'max_pages' => $query->max_num_pages,
            'current_page' => $args['paged'],
        ));
    }
    
    /**
     * Product filters shortcode
     */
    public function product_filters_shortcode($atts) {
        $atts = shortcode_atts(array(
            'style' => 'default',
        ), $atts);
        
        ob_start();
        $this->render_filters();
        return ob_get_clean();
    }
}

// Initialize the module
new AquaLuxe_Advanced_Filters();