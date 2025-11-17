<?php
/**
 * WooCommerce Widgets
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register WooCommerce widgets
 */
function aqualuxe_wc_register_widgets() {
    register_widget('AquaLuxe_WC_Widget_Products_Filter');
    register_widget('AquaLuxe_WC_Widget_Featured_Products');
    register_widget('AquaLuxe_WC_Widget_Recent_Products');
    register_widget('AquaLuxe_WC_Widget_Best_Selling_Products');
    register_widget('AquaLuxe_WC_Widget_Top_Rated_Products');
    register_widget('AquaLuxe_WC_Widget_Sale_Products');
}
add_action('widgets_init', 'aqualuxe_wc_register_widgets');

/**
 * Products Filter Widget
 */
class AquaLuxe_WC_Widget_Products_Filter extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_wc_products_filter',
            __('AquaLuxe Products Filter', 'aqualuxe'),
            array(
                'description' => __('Advanced product filter widget with price range, categories, attributes, and tags.', 'aqualuxe'),
                'classname' => 'aqualuxe-wc-products-filter',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        // Only show on shop pages
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $title = apply_filters('widget_title', $instance['title'] ?? '');
        $show_price_filter = isset($instance['show_price_filter']) ? (bool) $instance['show_price_filter'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : true;
        $show_attributes = isset($instance['show_attributes']) ? (bool) $instance['show_attributes'] : true;
        $show_tags = isset($instance['show_tags']) ? (bool) $instance['show_tags'] : true;
        $show_active_filters = isset($instance['show_active_filters']) ? (bool) $instance['show_active_filters'] : true;

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo '<div class="aqualuxe-products-filter">';

        // Active filters
        if ($show_active_filters) {
            echo '<div class="filter-section active-filters">';
            echo '<h4>' . esc_html__('Active Filters', 'aqualuxe') . '</h4>';
            the_widget('WC_Widget_Layered_Nav_Filters');
            echo '</div>';
        }

        // Price filter
        if ($show_price_filter) {
            echo '<div class="filter-section price-filter">';
            echo '<h4>' . esc_html__('Filter by Price', 'aqualuxe') . '</h4>';
            the_widget('WC_Widget_Price_Filter');
            echo '</div>';
        }

        // Categories
        if ($show_categories) {
            echo '<div class="filter-section categories-filter">';
            echo '<h4>' . esc_html__('Product Categories', 'aqualuxe') . '</h4>';
            the_widget('WC_Widget_Product_Categories', array(
                'title' => '',
                'hierarchical' => 1,
                'show_children_only' => 0,
                'hide_empty' => 1,
                'dropdown' => 0,
            ));
            echo '</div>';
        }

        // Attributes
        if ($show_attributes) {
            echo '<div class="filter-section attributes-filter">';
            echo '<h4>' . esc_html__('Product Attributes', 'aqualuxe') . '</h4>';
            
            // Get attribute taxonomies
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            
            if (!empty($attribute_taxonomies)) {
                foreach ($attribute_taxonomies as $attribute) {
                    $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);
                    
                    // Check if taxonomy exists and has terms
                    if (taxonomy_exists($taxonomy)) {
                        $terms = get_terms(array(
                            'taxonomy' => $taxonomy,
                            'hide_empty' => true,
                        ));
                        
                        if (!empty($terms)) {
                            echo '<div class="attribute-filter">';
                            echo '<h5>' . esc_html(wc_attribute_label($taxonomy)) . '</h5>';
                            the_widget('WC_Widget_Layered_Nav', array(
                                'title' => '',
                                'attribute' => $attribute->attribute_name,
                                'display_type' => 'list',
                                'query_type' => 'and',
                            ));
                            echo '</div>';
                        }
                    }
                }
            } else {
                echo '<p>' . esc_html__('No product attributes found.', 'aqualuxe') . '</p>';
            }
            
            echo '</div>';
        }

        // Tags
        if ($show_tags) {
            echo '<div class="filter-section tags-filter">';
            echo '<h4>' . esc_html__('Product Tags', 'aqualuxe') . '</h4>';
            the_widget('WC_Widget_Product_Tag_Cloud', array(
                'title' => '',
            ));
            echo '</div>';
        }

        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return string|void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Filter Products', 'aqualuxe');
        $show_price_filter = isset($instance['show_price_filter']) ? (bool) $instance['show_price_filter'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : true;
        $show_attributes = isset($instance['show_attributes']) ? (bool) $instance['show_attributes'] : true;
        $show_tags = isset($instance['show_tags']) ? (bool) $instance['show_tags'] : true;
        $show_active_filters = isset($instance['show_active_filters']) ? (bool) $instance['show_active_filters'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_active_filters); ?> id="<?php echo esc_attr($this->get_field_id('show_active_filters')); ?>" name="<?php echo esc_attr($this->get_field_name('show_active_filters')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_active_filters')); ?>"><?php esc_html_e('Show active filters', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_price_filter); ?> id="<?php echo esc_attr($this->get_field_id('show_price_filter')); ?>" name="<?php echo esc_attr($this->get_field_name('show_price_filter')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_price_filter')); ?>"><?php esc_html_e('Show price filter', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_categories); ?> id="<?php echo esc_attr($this->get_field_id('show_categories')); ?>" name="<?php echo esc_attr($this->get_field_name('show_categories')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_categories')); ?>"><?php esc_html_e('Show categories', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_attributes); ?> id="<?php echo esc_attr($this->get_field_id('show_attributes')); ?>" name="<?php echo esc_attr($this->get_field_name('show_attributes')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_attributes')); ?>"><?php esc_html_e('Show attributes', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_tags); ?> id="<?php echo esc_attr($this->get_field_id('show_tags')); ?>" name="<?php echo esc_attr($this->get_field_name('show_tags')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_tags')); ?>"><?php esc_html_e('Show tags', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['show_price_filter'] = isset($new_instance['show_price_filter']) ? (bool) $new_instance['show_price_filter'] : false;
        $instance['show_categories'] = isset($new_instance['show_categories']) ? (bool) $new_instance['show_categories'] : false;
        $instance['show_attributes'] = isset($new_instance['show_attributes']) ? (bool) $new_instance['show_attributes'] : false;
        $instance['show_tags'] = isset($new_instance['show_tags']) ? (bool) $new_instance['show_tags'] : false;
        $instance['show_active_filters'] = isset($new_instance['show_active_filters']) ? (bool) $new_instance['show_active_filters'] : false;
        return $instance;
    }
}

/**
 * Featured Products Widget
 */
class AquaLuxe_WC_Widget_Featured_Products extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_wc_featured_products',
            __('AquaLuxe Featured Products', 'aqualuxe'),
            array(
                'description' => __('Display featured products with advanced options.', 'aqualuxe'),
                'classname' => 'aqualuxe-wc-featured-products',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title'] ?? '');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $columns = isset($instance['columns']) ? absint($instance['columns']) : 2;
        $orderby = isset($instance['orderby']) ? sanitize_text_field($instance['orderby']) : 'date';
        $order = isset($instance['order']) ? sanitize_text_field($instance['order']) : 'desc';
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_add_to_cart = isset($instance['show_add_to_cart']) ? (bool) $instance['show_add_to_cart'] : true;
        $layout_style = isset($instance['layout_style']) ? sanitize_text_field($instance['layout_style']) : 'grid';

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get featured products
        $query_args = array(
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'post_type' => 'product',
            'featured' => true,
            'orderby' => $orderby,
            'order' => $order,
        );

        $products = new WP_Query(apply_filters('aqualuxe_wc_featured_products_widget_query_args', $query_args));

        if ($products->have_posts()) {
            echo '<div class="aqualuxe-featured-products layout-' . esc_attr($layout_style) . ' columns-' . esc_attr($columns) . '">';
            
            while ($products->have_posts()) {
                $products->the_post();
                global $product;
                
                if (!$product || !$product->is_visible()) {
                    continue;
                }
                
                echo '<div class="featured-product">';
                
                // Product image
                echo '<a href="' . esc_url(get_permalink()) . '" class="product-image">';
                echo woocommerce_get_product_thumbnail('woocommerce_thumbnail');
                echo '</a>';
                
                echo '<div class="product-details">';
                
                // Product title
                echo '<h3 class="product-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h3>';
                
                // Product categories
                if ($show_categories) {
                    echo aqualuxe_wc_get_categories_html($product);
                }
                
                // Product rating
                if ($show_rating) {
                    echo aqualuxe_wc_get_rating_html($product);
                }
                
                // Product price
                if ($show_price) {
                    echo '<div class="product-price">' . $product->get_price_html() . '</div>';
                }
                
                // Add to cart button
                if ($show_add_to_cart) {
                    echo '<div class="product-add-to-cart">';
                    woocommerce_template_loop_add_to_cart();
                    echo '</div>';
                }
                
                echo '</div>'; // .product-details
                echo '</div>'; // .featured-product
            }
            
            echo '</div>'; // .aqualuxe-featured-products
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No featured products found.', 'aqualuxe') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return string|void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Featured Products', 'aqualuxe');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $columns = isset($instance['columns']) ? absint($instance['columns']) : 2;
        $orderby = isset($instance['orderby']) ? $instance['orderby'] : 'date';
        $order = isset($instance['order']) ? $instance['order'] : 'desc';
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_add_to_cart = isset($instance['show_add_to_cart']) ? (bool) $instance['show_add_to_cart'] : true;
        $layout_style = isset($instance['layout_style']) ? $instance['layout_style'] : 'grid';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of products to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" max="20" value="<?php echo esc_attr($number); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('columns')); ?>"><?php esc_html_e('Columns:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('columns')); ?>" name="<?php echo esc_attr($this->get_field_name('columns')); ?>">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <option value="<?php echo esc_attr($i); ?>" <?php selected($columns, $i); ?>><?php echo esc_html($i); ?></option>
                <?php endfor; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order by:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
                <option value="date" <?php selected($orderby, 'date'); ?>><?php esc_html_e('Date', 'aqualuxe'); ?></option>
                <option value="price" <?php selected($orderby, 'price'); ?>><?php esc_html_e('Price', 'aqualuxe'); ?></option>
                <option value="rand" <?php selected($orderby, 'rand'); ?>><?php esc_html_e('Random', 'aqualuxe'); ?></option>
                <option value="sales" <?php selected($orderby, 'sales'); ?>><?php esc_html_e('Sales', 'aqualuxe'); ?></option>
                <option value="title" <?php selected($orderby, 'title'); ?>><?php esc_html_e('Title', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php esc_html_e('Order:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                <option value="asc" <?php selected($order, 'asc'); ?>><?php esc_html_e('ASC', 'aqualuxe'); ?></option>
                <option value="desc" <?php selected($order, 'desc'); ?>><?php esc_html_e('DESC', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout_style')); ?>"><?php esc_html_e('Layout Style:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout_style')); ?>" name="<?php echo esc_attr($this->get_field_name('layout_style')); ?>">
                <option value="grid" <?php selected($layout_style, 'grid'); ?>><?php esc_html_e('Grid', 'aqualuxe'); ?></option>
                <option value="list" <?php selected($layout_style, 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                <option value="compact" <?php selected($layout_style, 'compact'); ?>><?php esc_html_e('Compact', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> id="<?php echo esc_attr($this->get_field_id('show_rating')); ?>" name="<?php echo esc_attr($this->get_field_name('show_rating')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_rating')); ?>"><?php esc_html_e('Show rating', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_price); ?> id="<?php echo esc_attr($this->get_field_id('show_price')); ?>" name="<?php echo esc_attr($this->get_field_name('show_price')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_price')); ?>"><?php esc_html_e('Show price', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_categories); ?> id="<?php echo esc_attr($this->get_field_id('show_categories')); ?>" name="<?php echo esc_attr($this->get_field_name('show_categories')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_categories')); ?>"><?php esc_html_e('Show categories', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_add_to_cart); ?> id="<?php echo esc_attr($this->get_field_id('show_add_to_cart')); ?>" name="<?php echo esc_attr($this->get_field_name('show_add_to_cart')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_add_to_cart')); ?>"><?php esc_html_e('Show add to cart button', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['number'] = absint($new_instance['number'] ?? 4);
        $instance['columns'] = absint($new_instance['columns'] ?? 2);
        $instance['orderby'] = sanitize_text_field($new_instance['orderby'] ?? 'date');
        $instance['order'] = sanitize_text_field($new_instance['order'] ?? 'desc');
        $instance['show_rating'] = isset($new_instance['show_rating']) ? (bool) $new_instance['show_rating'] : false;
        $instance['show_price'] = isset($new_instance['show_price']) ? (bool) $new_instance['show_price'] : false;
        $instance['show_categories'] = isset($new_instance['show_categories']) ? (bool) $new_instance['show_categories'] : false;
        $instance['show_add_to_cart'] = isset($new_instance['show_add_to_cart']) ? (bool) $new_instance['show_add_to_cart'] : false;
        $instance['layout_style'] = sanitize_text_field($new_instance['layout_style'] ?? 'grid');
        return $instance;
    }
}

/**
 * Recent Products Widget
 */
class AquaLuxe_WC_Widget_Recent_Products extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_wc_recent_products',
            __('AquaLuxe Recent Products', 'aqualuxe'),
            array(
                'description' => __('Display recent products with advanced options.', 'aqualuxe'),
                'classname' => 'aqualuxe-wc-recent-products',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title'] ?? '');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $columns = isset($instance['columns']) ? absint($instance['columns']) : 2;
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_add_to_cart = isset($instance['show_add_to_cart']) ? (bool) $instance['show_add_to_cart'] : true;
        $layout_style = isset($instance['layout_style']) ? sanitize_text_field($instance['layout_style']) : 'grid';

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get recent products
        $query_args = array(
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'post_type' => 'product',
            'orderby' => 'date',
            'order' => 'desc',
        );

        $products = new WP_Query(apply_filters('aqualuxe_wc_recent_products_widget_query_args', $query_args));

        if ($products->have_posts()) {
            echo '<div class="aqualuxe-recent-products layout-' . esc_attr($layout_style) . ' columns-' . esc_attr($columns) . '">';
            
            while ($products->have_posts()) {
                $products->the_post();
                global $product;
                
                if (!$product || !$product->is_visible()) {
                    continue;
                }
                
                echo '<div class="recent-product">';
                
                // Product image
                echo '<a href="' . esc_url(get_permalink()) . '" class="product-image">';
                echo woocommerce_get_product_thumbnail('woocommerce_thumbnail');
                echo '</a>';
                
                echo '<div class="product-details">';
                
                // Product title
                echo '<h3 class="product-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h3>';
                
                // Product categories
                if ($show_categories) {
                    echo aqualuxe_wc_get_categories_html($product);
                }
                
                // Product rating
                if ($show_rating) {
                    echo aqualuxe_wc_get_rating_html($product);
                }
                
                // Product price
                if ($show_price) {
                    echo '<div class="product-price">' . $product->get_price_html() . '</div>';
                }
                
                // Add to cart button
                if ($show_add_to_cart) {
                    echo '<div class="product-add-to-cart">';
                    woocommerce_template_loop_add_to_cart();
                    echo '</div>';
                }
                
                echo '</div>'; // .product-details
                echo '</div>'; // .recent-product
            }
            
            echo '</div>'; // .aqualuxe-recent-products
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No recent products found.', 'aqualuxe') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return string|void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Recent Products', 'aqualuxe');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $columns = isset($instance['columns']) ? absint($instance['columns']) : 2;
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_add_to_cart = isset($instance['show_add_to_cart']) ? (bool) $instance['show_add_to_cart'] : true;
        $layout_style = isset($instance['layout_style']) ? $instance['layout_style'] : 'grid';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of products to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" max="20" value="<?php echo esc_attr($number); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('columns')); ?>"><?php esc_html_e('Columns:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('columns')); ?>" name="<?php echo esc_attr($this->get_field_name('columns')); ?>">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <option value="<?php echo esc_attr($i); ?>" <?php selected($columns, $i); ?>><?php echo esc_html($i); ?></option>
                <?php endfor; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout_style')); ?>"><?php esc_html_e('Layout Style:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout_style')); ?>" name="<?php echo esc_attr($this->get_field_name('layout_style')); ?>">
                <option value="grid" <?php selected($layout_style, 'grid'); ?>><?php esc_html_e('Grid', 'aqualuxe'); ?></option>
                <option value="list" <?php selected($layout_style, 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                <option value="compact" <?php selected($layout_style, 'compact'); ?>><?php esc_html_e('Compact', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> id="<?php echo esc_attr($this->get_field_id('show_rating')); ?>" name="<?php echo esc_attr($this->get_field_name('show_rating')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_rating')); ?>"><?php esc_html_e('Show rating', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_price); ?> id="<?php echo esc_attr($this->get_field_id('show_price')); ?>" name="<?php echo esc_attr($this->get_field_name('show_price')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_price')); ?>"><?php esc_html_e('Show price', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_categories); ?> id="<?php echo esc_attr($this->get_field_id('show_categories')); ?>" name="<?php echo esc_attr($this->get_field_name('show_categories')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_categories')); ?>"><?php esc_html_e('Show categories', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_add_to_cart); ?> id="<?php echo esc_attr($this->get_field_id('show_add_to_cart')); ?>" name="<?php echo esc_attr($this->get_field_name('show_add_to_cart')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_add_to_cart')); ?>"><?php esc_html_e('Show add to cart button', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['number'] = absint($new_instance['number'] ?? 4);
        $instance['columns'] = absint($new_instance['columns'] ?? 2);
        $instance['show_rating'] = isset($new_instance['show_rating']) ? (bool) $new_instance['show_rating'] : false;
        $instance['show_price'] = isset($new_instance['show_price']) ? (bool) $new_instance['show_price'] : false;
        $instance['show_categories'] = isset($new_instance['show_categories']) ? (bool) $new_instance['show_categories'] : false;
        $instance['show_add_to_cart'] = isset($new_instance['show_add_to_cart']) ? (bool) $new_instance['show_add_to_cart'] : false;
        $instance['layout_style'] = sanitize_text_field($new_instance['layout_style'] ?? 'grid');
        return $instance;
    }
}

/**
 * Best Selling Products Widget
 */
class AquaLuxe_WC_Widget_Best_Selling_Products extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_wc_best_selling_products',
            __('AquaLuxe Best Selling Products', 'aqualuxe'),
            array(
                'description' => __('Display best selling products with advanced options.', 'aqualuxe'),
                'classname' => 'aqualuxe-wc-best-selling-products',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title'] ?? '');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $columns = isset($instance['columns']) ? absint($instance['columns']) : 2;
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_add_to_cart = isset($instance['show_add_to_cart']) ? (bool) $instance['show_add_to_cart'] : true;
        $layout_style = isset($instance['layout_style']) ? sanitize_text_field($instance['layout_style']) : 'grid';

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get best selling products
        $query_args = array(
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'post_type' => 'product',
            'meta_key' => 'total_sales',
            'orderby' => 'meta_value_num',
            'order' => 'desc',
        );

        $products = new WP_Query(apply_filters('aqualuxe_wc_best_selling_products_widget_query_args', $query_args));

        if ($products->have_posts()) {
            echo '<div class="aqualuxe-best-selling-products layout-' . esc_attr($layout_style) . ' columns-' . esc_attr($columns) . '">';
            
            while ($products->have_posts()) {
                $products->the_post();
                global $product;
                
                if (!$product || !$product->is_visible()) {
                    continue;
                }
                
                echo '<div class="best-selling-product">';
                
                // Product image
                echo '<a href="' . esc_url(get_permalink()) . '" class="product-image">';
                echo woocommerce_get_product_thumbnail('woocommerce_thumbnail');
                echo '</a>';
                
                echo '<div class="product-details">';
                
                // Product title
                echo '<h3 class="product-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h3>';
                
                // Product categories
                if ($show_categories) {
                    echo aqualuxe_wc_get_categories_html($product);
                }
                
                // Product rating
                if ($show_rating) {
                    echo aqualuxe_wc_get_rating_html($product);
                }
                
                // Product price
                if ($show_price) {
                    echo '<div class="product-price">' . $product->get_price_html() . '</div>';
                }
                
                // Add to cart button
                if ($show_add_to_cart) {
                    echo '<div class="product-add-to-cart">';
                    woocommerce_template_loop_add_to_cart();
                    echo '</div>';
                }
                
                echo '</div>'; // .product-details
                echo '</div>'; // .best-selling-product
            }
            
            echo '</div>'; // .aqualuxe-best-selling-products
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No best selling products found.', 'aqualuxe') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return string|void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Best Selling Products', 'aqualuxe');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $columns = isset($instance['columns']) ? absint($instance['columns']) : 2;
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_add_to_cart = isset($instance['show_add_to_cart']) ? (bool) $instance['show_add_to_cart'] : true;
        $layout_style = isset($instance['layout_style']) ? $instance['layout_style'] : 'grid';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of products to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" max="20" value="<?php echo esc_attr($number); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('columns')); ?>"><?php esc_html_e('Columns:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('columns')); ?>" name="<?php echo esc_attr($this->get_field_name('columns')); ?>">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <option value="<?php echo esc_attr($i); ?>" <?php selected($columns, $i); ?>><?php echo esc_html($i); ?></option>
                <?php endfor; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout_style')); ?>"><?php esc_html_e('Layout Style:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout_style')); ?>" name="<?php echo esc_attr($this->get_field_name('layout_style')); ?>">
                <option value="grid" <?php selected($layout_style, 'grid'); ?>><?php esc_html_e('Grid', 'aqualuxe'); ?></option>
                <option value="list" <?php selected($layout_style, 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                <option value="compact" <?php selected($layout_style, 'compact'); ?>><?php esc_html_e('Compact', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> id="<?php echo esc_attr($this->get_field_id('show_rating')); ?>" name="<?php echo esc_attr($this->get_field_name('show_rating')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_rating')); ?>"><?php esc_html_e('Show rating', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_price); ?> id="<?php echo esc_attr($this->get_field_id('show_price')); ?>" name="<?php echo esc_attr($this->get_field_name('show_price')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_price')); ?>"><?php esc_html_e('Show price', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_categories); ?> id="<?php echo esc_attr($this->get_field_id('show_categories')); ?>" name="<?php echo esc_attr($this->get_field_name('show_categories')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_categories')); ?>"><?php esc_html_e('Show categories', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_add_to_cart); ?> id="<?php echo esc_attr($this->get_field_id('show_add_to_cart')); ?>" name="<?php echo esc_attr($this->get_field_name('show_add_to_cart')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_add_to_cart')); ?>"><?php esc_html_e('Show add to cart button', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['number'] = absint($new_instance['number'] ?? 4);
        $instance['columns'] = absint($new_instance['columns'] ?? 2);
        $instance['show_rating'] = isset($new_instance['show_rating']) ? (bool) $new_instance['show_rating'] : false;
        $instance['show_price'] = isset($new_instance['show_price']) ? (bool) $new_instance['show_price'] : false;
        $instance['show_categories'] = isset($new_instance['show_categories']) ? (bool) $new_instance['show_categories'] : false;
        $instance['show_add_to_cart'] = isset($new_instance['show_add_to_cart']) ? (bool) $new_instance['show_add_to_cart'] : false;
        $instance['layout_style'] = sanitize_text_field($new_instance['layout_style'] ?? 'grid');
        return $instance;
    }
}

/**
 * Top Rated Products Widget
 */
class AquaLuxe_WC_Widget_Top_Rated_Products extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_wc_top_rated_products',
            __('AquaLuxe Top Rated Products', 'aqualuxe'),
            array(
                'description' => __('Display top rated products with advanced options.', 'aqualuxe'),
                'classname' => 'aqualuxe-wc-top-rated-products',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title'] ?? '');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $columns = isset($instance['columns']) ? absint($instance['columns']) : 2;
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_add_to_cart = isset($instance['show_add_to_cart']) ? (bool) $instance['show_add_to_cart'] : true;
        $layout_style = isset($instance['layout_style']) ? sanitize_text_field($instance['layout_style']) : 'grid';

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get top rated products
        $query_args = array(
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'post_type' => 'product',
            'meta_key' => '_wc_average_rating',
            'orderby' => 'meta_value_num',
            'order' => 'desc',
        );

        $products = new WP_Query(apply_filters('aqualuxe_wc_top_rated_products_widget_query_args', $query_args));

        if ($products->have_posts()) {
            echo '<div class="aqualuxe-top-rated-products layout-' . esc_attr($layout_style) . ' columns-' . esc_attr($columns) . '">';
            
            while ($products->have_posts()) {
                $products->the_post();
                global $product;
                
                if (!$product || !$product->is_visible()) {
                    continue;
                }
                
                echo '<div class="top-rated-product">';
                
                // Product image
                echo '<a href="' . esc_url(get_permalink()) . '" class="product-image">';
                echo woocommerce_get_product_thumbnail('woocommerce_thumbnail');
                echo '</a>';
                
                echo '<div class="product-details">';
                
                // Product title
                echo '<h3 class="product-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h3>';
                
                // Product categories
                if ($show_categories) {
                    echo aqualuxe_wc_get_categories_html($product);
                }
                
                // Product rating
                if ($show_rating) {
                    echo aqualuxe_wc_get_rating_html($product);
                }
                
                // Product price
                if ($show_price) {
                    echo '<div class="product-price">' . $product->get_price_html() . '</div>';
                }
                
                // Add to cart button
                if ($show_add_to_cart) {
                    echo '<div class="product-add-to-cart">';
                    woocommerce_template_loop_add_to_cart();
                    echo '</div>';
                }
                
                echo '</div>'; // .product-details
                echo '</div>'; // .top-rated-product
            }
            
            echo '</div>'; // .aqualuxe-top-rated-products
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No top rated products found.', 'aqualuxe') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return string|void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Top Rated Products', 'aqualuxe');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $columns = isset($instance['columns']) ? absint($instance['columns']) : 2;
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_add_to_cart = isset($instance['show_add_to_cart']) ? (bool) $instance['show_add_to_cart'] : true;
        $layout_style = isset($instance['layout_style']) ? $instance['layout_style'] : 'grid';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of products to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" max="20" value="<?php echo esc_attr($number); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('columns')); ?>"><?php esc_html_e('Columns:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('columns')); ?>" name="<?php echo esc_attr($this->get_field_name('columns')); ?>">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <option value="<?php echo esc_attr($i); ?>" <?php selected($columns, $i); ?>><?php echo esc_html($i); ?></option>
                <?php endfor; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout_style')); ?>"><?php esc_html_e('Layout Style:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout_style')); ?>" name="<?php echo esc_attr($this->get_field_name('layout_style')); ?>">
                <option value="grid" <?php selected($layout_style, 'grid'); ?>><?php esc_html_e('Grid', 'aqualuxe'); ?></option>
                <option value="list" <?php selected($layout_style, 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                <option value="compact" <?php selected($layout_style, 'compact'); ?>><?php esc_html_e('Compact', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> id="<?php echo esc_attr($this->get_field_id('show_rating')); ?>" name="<?php echo esc_attr($this->get_field_name('show_rating')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_rating')); ?>"><?php esc_html_e('Show rating', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_price); ?> id="<?php echo esc_attr($this->get_field_id('show_price')); ?>" name="<?php echo esc_attr($this->get_field_name('show_price')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_price')); ?>"><?php esc_html_e('Show price', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_categories); ?> id="<?php echo esc_attr($this->get_field_id('show_categories')); ?>" name="<?php echo esc_attr($this->get_field_name('show_categories')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_categories')); ?>"><?php esc_html_e('Show categories', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_add_to_cart); ?> id="<?php echo esc_attr($this->get_field_id('show_add_to_cart')); ?>" name="<?php echo esc_attr($this->get_field_name('show_add_to_cart')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_add_to_cart')); ?>"><?php esc_html_e('Show add to cart button', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['number'] = absint($new_instance['number'] ?? 4);
        $instance['columns'] = absint($new_instance['columns'] ?? 2);
        $instance['show_rating'] = isset($new_instance['show_rating']) ? (bool) $new_instance['show_rating'] : false;
        $instance['show_price'] = isset($new_instance['show_price']) ? (bool) $new_instance['show_price'] : false;
        $instance['show_categories'] = isset($new_instance['show_categories']) ? (bool) $new_instance['show_categories'] : false;
        $instance['show_add_to_cart'] = isset($new_instance['show_add_to_cart']) ? (bool) $new_instance['show_add_to_cart'] : false;
        $instance['layout_style'] = sanitize_text_field($new_instance['layout_style'] ?? 'grid');
        return $instance;
    }
}

/**
 * Sale Products Widget
 */
class AquaLuxe_WC_Widget_Sale_Products extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_wc_sale_products',
            __('AquaLuxe Sale Products', 'aqualuxe'),
            array(
                'description' => __('Display sale products with advanced options.', 'aqualuxe'),
                'classname' => 'aqualuxe-wc-sale-products',
            )
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title'] ?? '');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $columns = isset($instance['columns']) ? absint($instance['columns']) : 2;
        $orderby = isset($instance['orderby']) ? sanitize_text_field($instance['orderby']) : 'date';
        $order = isset($instance['order']) ? sanitize_text_field($instance['order']) : 'desc';
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
        $show_discount = isset($instance['show_discount']) ? (bool) $instance['show_discount'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_add_to_cart = isset($instance['show_add_to_cart']) ? (bool) $instance['show_add_to_cart'] : true;
        $layout_style = isset($instance['layout_style']) ? sanitize_text_field($instance['layout_style']) : 'grid';

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get sale products
        $query_args = array(
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'post_type' => 'product',
            'meta_query' => WC()->query->get_meta_query(),
            'post__in' => array_merge(array(0), wc_get_product_ids_on_sale()),
            'orderby' => $orderby,
            'order' => $order,
        );

        $products = new WP_Query(apply_filters('aqualuxe_wc_sale_products_widget_query_args', $query_args));

        if ($products->have_posts()) {
            echo '<div class="aqualuxe-sale-products layout-' . esc_attr($layout_style) . ' columns-' . esc_attr($columns) . '">';
            
            while ($products->have_posts()) {
                $products->the_post();
                global $product;
                
                if (!$product || !$product->is_visible()) {
                    continue;
                }
                
                echo '<div class="sale-product">';
                
                // Product image
                echo '<a href="' . esc_url(get_permalink()) . '" class="product-image">';
                echo woocommerce_get_product_thumbnail('woocommerce_thumbnail');
                
                // Sale badge
                echo '<span class="onsale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
                
                echo '</a>';
                
                echo '<div class="product-details">';
                
                // Product title
                echo '<h3 class="product-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h3>';
                
                // Product categories
                if ($show_categories) {
                    echo aqualuxe_wc_get_categories_html($product);
                }
                
                // Product rating
                if ($show_rating) {
                    echo aqualuxe_wc_get_rating_html($product);
                }
                
                // Product price
                if ($show_price) {
                    echo '<div class="product-price">';
                    echo $product->get_price_html();
                    
                    // Show discount percentage
                    if ($show_discount && $product->is_on_sale() && $product->get_regular_price()) {
                        $regular_price = (float) $product->get_regular_price();
                        $sale_price = (float) $product->get_sale_price();
                        
                        if ($regular_price > 0) {
                            $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                            echo '<span class="discount-percentage">-' . $percentage . '%</span>';
                        }
                    }
                    
                    echo '</div>';
                }
                
                // Add to cart button
                if ($show_add_to_cart) {
                    echo '<div class="product-add-to-cart">';
                    woocommerce_template_loop_add_to_cart();
                    echo '</div>';
                }
                
                echo '</div>'; // .product-details
                echo '</div>'; // .sale-product
            }
            
            echo '</div>'; // .aqualuxe-sale-products
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('No sale products found.', 'aqualuxe') . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return string|void
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Sale Products', 'aqualuxe');
        $number = isset($instance['number']) ? absint($instance['number']) : 4;
        $columns = isset($instance['columns']) ? absint($instance['columns']) : 2;
        $orderby = isset($instance['orderby']) ? $instance['orderby'] : 'date';
        $order = isset($instance['order']) ? $instance['order'] : 'desc';
        $show_rating = isset($instance['show_rating']) ? (bool) $instance['show_rating'] : true;
        $show_price = isset($instance['show_price']) ? (bool) $instance['show_price'] : true;
        $show_discount = isset($instance['show_discount']) ? (bool) $instance['show_discount'] : true;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_add_to_cart = isset($instance['show_add_to_cart']) ? (bool) $instance['show_add_to_cart'] : true;
        $layout_style = isset($instance['layout_style']) ? $instance['layout_style'] : 'grid';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of products to show:', 'aqualuxe'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" max="20" value="<?php echo esc_attr($number); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('columns')); ?>"><?php esc_html_e('Columns:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('columns')); ?>" name="<?php echo esc_attr($this->get_field_name('columns')); ?>">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <option value="<?php echo esc_attr($i); ?>" <?php selected($columns, $i); ?>><?php echo esc_html($i); ?></option>
                <?php endfor; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Order by:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
                <option value="date" <?php selected($orderby, 'date'); ?>><?php esc_html_e('Date', 'aqualuxe'); ?></option>
                <option value="price" <?php selected($orderby, 'price'); ?>><?php esc_html_e('Price', 'aqualuxe'); ?></option>
                <option value="rand" <?php selected($orderby, 'rand'); ?>><?php esc_html_e('Random', 'aqualuxe'); ?></option>
                <option value="sales" <?php selected($orderby, 'sales'); ?>><?php esc_html_e('Sales', 'aqualuxe'); ?></option>
                <option value="title" <?php selected($orderby, 'title'); ?>><?php esc_html_e('Title', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php esc_html_e('Order:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                <option value="asc" <?php selected($order, 'asc'); ?>><?php esc_html_e('ASC', 'aqualuxe'); ?></option>
                <option value="desc" <?php selected($order, 'desc'); ?>><?php esc_html_e('DESC', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout_style')); ?>"><?php esc_html_e('Layout Style:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout_style')); ?>" name="<?php echo esc_attr($this->get_field_name('layout_style')); ?>">
                <option value="grid" <?php selected($layout_style, 'grid'); ?>><?php esc_html_e('Grid', 'aqualuxe'); ?></option>
                <option value="list" <?php selected($layout_style, 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                <option value="compact" <?php selected($layout_style, 'compact'); ?>><?php esc_html_e('Compact', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_rating); ?> id="<?php echo esc_attr($this->get_field_id('show_rating')); ?>" name="<?php echo esc_attr($this->get_field_name('show_rating')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_rating')); ?>"><?php esc_html_e('Show rating', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_price); ?> id="<?php echo esc_attr($this->get_field_id('show_price')); ?>" name="<?php echo esc_attr($this->get_field_name('show_price')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_price')); ?>"><?php esc_html_e('Show price', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_discount); ?> id="<?php echo esc_attr($this->get_field_id('show_discount')); ?>" name="<?php echo esc_attr($this->get_field_name('show_discount')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_discount')); ?>"><?php esc_html_e('Show discount percentage', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_categories); ?> id="<?php echo esc_attr($this->get_field_id('show_categories')); ?>" name="<?php echo esc_attr($this->get_field_name('show_categories')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_categories')); ?>"><?php esc_html_e('Show categories', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_add_to_cart); ?> id="<?php echo esc_attr($this->get_field_id('show_add_to_cart')); ?>" name="<?php echo esc_attr($this->get_field_name('show_add_to_cart')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_add_to_cart')); ?>"><?php esc_html_e('Show add to cart button', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['number'] = absint($new_instance['number'] ?? 4);
        $instance['columns'] = absint($new_instance['columns'] ?? 2);
        $instance['orderby'] = sanitize_text_field($new_instance['orderby'] ?? 'date');
        $instance['order'] = sanitize_text_field($new_instance['order'] ?? 'desc');
        $instance['show_rating'] = isset($new_instance['show_rating']) ? (bool) $new_instance['show_rating'] : false;
        $instance['show_price'] = isset($new_instance['show_price']) ? (bool) $new_instance['show_price'] : false;
        $instance['show_discount'] = isset($new_instance['show_discount']) ? (bool) $new_instance['show_discount'] : false;
        $instance['show_categories'] = isset($new_instance['show_categories']) ? (bool) $new_instance['show_categories'] : false;
        $instance['show_add_to_cart'] = isset($new_instance['show_add_to_cart']) ? (bool) $new_instance['show_add_to_cart'] : false;
        $instance['layout_style'] = sanitize_text_field($new_instance['layout_style'] ?? 'grid');
        return $instance;
    }
}