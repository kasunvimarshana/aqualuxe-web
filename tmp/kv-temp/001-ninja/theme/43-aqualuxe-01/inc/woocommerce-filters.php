<?php
/**
 * WooCommerce Advanced Filtering
 *
 * @package AquaLuxe
 */

/**
 * Register product filter widgets.
 */
function aqualuxe_register_filter_widgets() {
    // Register price filter widget
    register_widget('Aqualuxe_WC_Widget_Price_Filter');
    
    // Register attribute filter widget
    register_widget('Aqualuxe_WC_Widget_Attribute_Filter');
    
    // Register stock status filter widget
    register_widget('Aqualuxe_WC_Widget_Stock_Filter');
    
    // Register rating filter widget
    register_widget('Aqualuxe_WC_Widget_Rating_Filter');
}
add_action('widgets_init', 'aqualuxe_register_filter_widgets');

/**
 * Price filter widget.
 */
class Aqualuxe_WC_Widget_Price_Filter extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_price_filter',
            __('AquaLuxe Price Filter', 'aqualuxe'),
            array(
                'description' => __('Filter products by price range.', 'aqualuxe'),
            )
        );
    }

    /**
     * Widget output.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget($args, $instance) {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Price', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        // Get min and max prices in the current result set
        global $wpdb;
        $min_price = floor($wpdb->get_var(
            $wpdb->prepare(
                "SELECT MIN(meta_value + 0) FROM {$wpdb->postmeta} WHERE meta_key = %s",
                '_price'
            )
        ));
        $max_price = ceil($wpdb->get_var(
            $wpdb->prepare(
                "SELECT MAX(meta_value + 0) FROM {$wpdb->postmeta} WHERE meta_key = %s",
                '_price'
            )
        ));

        // Get current values from URL
        $current_min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $min_price;
        $current_max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $max_price;

        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $args['before_title'] . esc_html($title) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        ?>
        <div class="price-filter-widget">
            <form method="get" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                <div class="price-range-slider" data-min="<?php echo esc_attr($min_price); ?>" data-max="<?php echo esc_attr($max_price); ?>" data-current-min="<?php echo esc_attr($current_min_price); ?>" data-current-max="<?php echo esc_attr($current_max_price); ?>"></div>
                
                <div class="price-inputs flex justify-between mt-4">
                    <div class="min-price">
                        <label for="min_price"><?php echo esc_html__('Min', 'aqualuxe'); ?></label>
                        <input type="number" id="min_price" name="min_price" value="<?php echo esc_attr($current_min_price); ?>" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($current_max_price); ?>" step="1" />
                    </div>
                    <div class="max-price">
                        <label for="max_price"><?php echo esc_html__('Max', 'aqualuxe'); ?></label>
                        <input type="number" id="max_price" name="max_price" value="<?php echo esc_attr($current_max_price); ?>" min="<?php echo esc_attr($current_min_price); ?>" max="<?php echo esc_attr($max_price); ?>" step="1" />
                    </div>
                </div>
                
                <div class="price-filter-submit mt-4">
                    <button type="submit" class="button"><?php echo esc_html__('Filter', 'aqualuxe'); ?></button>
                </div>
                
                <?php
                // Keep query string parameters
                foreach ($_GET as $key => $value) {
                    if (!in_array($key, array('min_price', 'max_price', 'paged'), true)) {
                        echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
                    }
                }
                ?>
            </form>
        </div>
        <?php

        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form.
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Price', 'aqualuxe');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    /**
     * Widget update.
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

/**
 * Attribute filter widget.
 */
class Aqualuxe_WC_Widget_Attribute_Filter extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_attribute_filter',
            __('AquaLuxe Attribute Filter', 'aqualuxe'),
            array(
                'description' => __('Filter products by attribute.', 'aqualuxe'),
            )
        );
    }

    /**
     * Widget output.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget($args, $instance) {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Attribute', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        $attribute = !empty($instance['attribute']) ? $instance['attribute'] : '';
        $display_type = !empty($instance['display_type']) ? $instance['display_type'] : 'list';

        if (empty($attribute)) {
            return;
        }

        $taxonomy = wc_attribute_taxonomy_name($attribute);
        
        if (!taxonomy_exists($taxonomy)) {
            return;
        }

        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ));

        if (empty($terms) || is_wp_error($terms)) {
            return;
        }

        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $args['before_title'] . esc_html($title) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        // Get current filter values
        $current_values = isset($_GET['filter_' . sanitize_title($attribute)]) ? explode(',', wc_clean(wp_unslash($_GET['filter_' . sanitize_title($attribute)]))) : array();

        if ('list' === $display_type) {
            // List display
            echo '<ul class="attribute-filter-list">';
            
            foreach ($terms as $term) {
                $current_filter = $current_values;
                $option_value = $term->slug;
                
                // Add or remove from filter
                if (in_array($option_value, $current_filter, true)) {
                    $key = array_search($option_value, $current_filter, true);
                    unset($current_filter[$key]);
                } else {
                    $current_filter[] = $option_value;
                }
                
                $link = remove_query_arg('paged');
                
                if (!empty($current_filter)) {
                    $link = add_query_arg('filter_' . sanitize_title($attribute), implode(',', $current_filter), $link);
                } else {
                    $link = remove_query_arg('filter_' . sanitize_title($attribute), $link);
                }
                
                $class = in_array($option_value, $current_values, true) ? 'chosen' : '';
                $count = get_term_meta($term->term_id, 'product_count_' . $taxonomy, true);
                
                if (!$count) {
                    $count = $term->count;
                }
                
                echo '<li class="' . esc_attr($class) . '">';
                echo '<a href="' . esc_url($link) . '">';
                echo esc_html($term->name);
                echo '<span class="count">(' . absint($count) . ')</span>';
                echo '</a>';
                echo '</li>';
            }
            
            echo '</ul>';
        } else {
            // Dropdown display
            $multiple = 'multiple' === $display_type;
            
            echo '<form method="get" action="' . esc_url(wc_get_page_permalink('shop')) . '">';
            echo '<select name="filter_' . esc_attr(sanitize_title($attribute)) . ($multiple ? '[]' : '') . '" class="attribute-filter-select" ' . ($multiple ? 'multiple="multiple"' : '') . '>';
            echo '<option value="">' . esc_html__('Any', 'aqualuxe') . '</option>';
            
            foreach ($terms as $term) {
                $option_value = $term->slug;
                $selected = in_array($option_value, $current_values, true) ? 'selected="selected"' : '';
                $count = get_term_meta($term->term_id, 'product_count_' . $taxonomy, true);
                
                if (!$count) {
                    $count = $term->count;
                }
                
                echo '<option value="' . esc_attr($option_value) . '" ' . $selected . '>';
                echo esc_html($term->name);
                echo ' (' . absint($count) . ')';
                echo '</option>';
            }
            
            echo '</select>';
            
            // Keep query string parameters
            foreach ($_GET as $key => $value) {
                if ($key !== 'filter_' . sanitize_title($attribute) && 'paged' !== $key) {
                    if (is_array($value)) {
                        foreach ($value as $val) {
                            echo '<input type="hidden" name="' . esc_attr($key) . '[]" value="' . esc_attr($val) . '" />';
                        }
                    } else {
                        echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
                    }
                }
            }
            
            echo '<button type="submit" class="button mt-4">' . esc_html__('Filter', 'aqualuxe') . '</button>';
            echo '</form>';
        }

        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form.
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Attribute', 'aqualuxe');
        $attribute = !empty($instance['attribute']) ? $instance['attribute'] : '';
        $display_type = !empty($instance['display_type']) ? $instance['display_type'] : 'list';
        
        // Get attribute taxonomies
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('attribute')); ?>"><?php esc_html_e('Attribute:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('attribute')); ?>" name="<?php echo esc_attr($this->get_field_name('attribute')); ?>">
                <option value=""><?php esc_html_e('Select an attribute', 'aqualuxe'); ?></option>
                <?php
                if (!empty($attribute_taxonomies)) {
                    foreach ($attribute_taxonomies as $tax) {
                        echo '<option value="' . esc_attr($tax->attribute_name) . '" ' . selected($attribute, $tax->attribute_name, false) . '>' . esc_html($tax->attribute_label) . '</option>';
                    }
                }
                ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('display_type')); ?>"><?php esc_html_e('Display Type:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('display_type')); ?>" name="<?php echo esc_attr($this->get_field_name('display_type')); ?>">
                <option value="list" <?php selected($display_type, 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                <option value="dropdown" <?php selected($display_type, 'dropdown'); ?>><?php esc_html_e('Dropdown', 'aqualuxe'); ?></option>
                <option value="multiple" <?php selected($display_type, 'multiple'); ?>><?php esc_html_e('Multiple Select', 'aqualuxe'); ?></option>
            </select>
        </p>
        <?php
    }

    /**
     * Widget update.
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['attribute'] = !empty($new_instance['attribute']) ? sanitize_text_field($new_instance['attribute']) : '';
        $instance['display_type'] = !empty($new_instance['display_type']) ? sanitize_text_field($new_instance['display_type']) : 'list';
        return $instance;
    }
}

/**
 * Stock status filter widget.
 */
class Aqualuxe_WC_Widget_Stock_Filter extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_stock_filter',
            __('AquaLuxe Stock Filter', 'aqualuxe'),
            array(
                'description' => __('Filter products by stock status.', 'aqualuxe'),
            )
        );
    }

    /**
     * Widget output.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget($args, $instance) {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Stock', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $args['before_title'] . esc_html($title) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        // Get current stock filter
        $current_stock = isset($_GET['stock_status']) ? wc_clean(wp_unslash($_GET['stock_status'])) : '';

        // Stock status options
        $stock_statuses = array(
            'instock' => __('In Stock', 'aqualuxe'),
            'outofstock' => __('Out of Stock', 'aqualuxe'),
            'onbackorder' => __('On Backorder', 'aqualuxe'),
        );

        echo '<ul class="stock-filter-list">';
        
        // All option
        $all_link = remove_query_arg('stock_status');
        $all_class = '' === $current_stock ? 'chosen' : '';
        
        echo '<li class="' . esc_attr($all_class) . '">';
        echo '<a href="' . esc_url($all_link) . '">';
        echo esc_html__('All', 'aqualuxe');
        echo '</a>';
        echo '</li>';
        
        // Stock status options
        foreach ($stock_statuses as $status => $label) {
            $link = add_query_arg('stock_status', $status, remove_query_arg('paged'));
            $class = $current_stock === $status ? 'chosen' : '';
            
            echo '<li class="' . esc_attr($class) . '">';
            echo '<a href="' . esc_url($link) . '">';
            echo esc_html($label);
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';

        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form.
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Stock', 'aqualuxe');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    /**
     * Widget update.
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

/**
 * Rating filter widget.
 */
class Aqualuxe_WC_Widget_Rating_Filter extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_rating_filter',
            __('AquaLuxe Rating Filter', 'aqualuxe'),
            array(
                'description' => __('Filter products by rating.', 'aqualuxe'),
            )
        );
    }

    /**
     * Widget output.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget($args, $instance) {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Rating', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $args['before_title'] . esc_html($title) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        // Get current rating filter
        $current_rating = isset($_GET['rating_filter']) ? absint($_GET['rating_filter']) : 0;

        echo '<ul class="rating-filter-list">';
        
        // All option
        $all_link = remove_query_arg('rating_filter');
        $all_class = 0 === $current_rating ? 'chosen' : '';
        
        echo '<li class="' . esc_attr($all_class) . '">';
        echo '<a href="' . esc_url($all_link) . '">';
        echo esc_html__('All', 'aqualuxe');
        echo '</a>';
        echo '</li>';
        
        // Rating options
        for ($rating = 5; $rating >= 1; $rating--) {
            $link = add_query_arg('rating_filter', $rating, remove_query_arg('paged'));
            $class = $current_rating === $rating ? 'chosen' : '';
            
            echo '<li class="' . esc_attr($class) . '">';
            echo '<a href="' . esc_url($link) . '">';
            
            // Display stars
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    echo '<span class="star star-filled">★</span>';
                } else {
                    echo '<span class="star">☆</span>';
                }
            }
            
            echo ' ' . esc_html(sprintf(__('and up', 'aqualuxe')));
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';

        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Widget form.
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Rating', 'aqualuxe');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    /**
     * Widget update.
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

/**
 * Apply product filters to query.
 *
 * @param array $query_args Query arguments.
 * @return array
 */
function aqualuxe_product_filter_query($query_args) {
    // Price filter
    if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
        $min_price = floatval($_GET['min_price']);
        $max_price = floatval($_GET['max_price']);
        
        $query_args['meta_query'][] = array(
            'key' => '_price',
            'value' => array($min_price, $max_price),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }
    
    // Stock status filter
    if (isset($_GET['stock_status'])) {
        $stock_status = wc_clean(wp_unslash($_GET['stock_status']));
        
        if (in_array($stock_status, array('instock', 'outofstock', 'onbackorder'), true)) {
            $query_args['meta_query'][] = array(
                'key' => '_stock_status',
                'value' => $stock_status,
                'compare' => '=',
            );
        }
    }
    
    // Rating filter
    if (isset($_GET['rating_filter'])) {
        $rating = absint($_GET['rating_filter']);
        
        if ($rating > 0) {
            $query_args['meta_query'][] = array(
                'key' => '_wc_average_rating',
                'value' => $rating,
                'compare' => '>=',
                'type' => 'DECIMAL',
            );
        }
    }
    
    // Attribute filters
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    
    if (!empty($attribute_taxonomies)) {
        foreach ($attribute_taxonomies as $tax) {
            $attribute = $tax->attribute_name;
            $filter_name = 'filter_' . sanitize_title($attribute);
            
            if (isset($_GET[$filter_name])) {
                $filter_value = wc_clean(wp_unslash($_GET[$filter_name]));
                $taxonomy = wc_attribute_taxonomy_name($attribute);
                
                if (!empty($filter_value)) {
                    $terms = explode(',', $filter_value);
                    
                    $query_args['tax_query'][] = array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $terms,
                        'operator' => 'IN',
                    );
                }
            }
        }
    }
    
    return $query_args;
}
add_filter('woocommerce_product_query_args', 'aqualuxe_product_filter_query');

/**
 * AJAX product filtering.
 */
function aqualuxe_ajax_filter_products() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'aqualuxe-woocommerce-nonce')) {
        wp_send_json_error(array('message' => __('Invalid request.', 'aqualuxe')));
    }
    
    // Set up query arguments
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => apply_filters('loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page()),
        'orderby' => 'menu_order title',
        'order' => 'ASC',
        'meta_query' => array(),
        'tax_query' => array(
            'relation' => 'AND',
        ),
    );
    
    // Price filter
    if (isset($_POST['min_price']) && isset($_POST['max_price'])) {
        $min_price = floatval($_POST['min_price']);
        $max_price = floatval($_POST['max_price']);
        
        $args['meta_query'][] = array(
            'key' => '_price',
            'value' => array($min_price, $max_price),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }
    
    // Stock status filter
    if (isset($_POST['stock_status']) && !empty($_POST['stock_status'])) {
        $stock_status = sanitize_text_field(wp_unslash($_POST['stock_status']));
        
        if (in_array($stock_status, array('instock', 'outofstock', 'onbackorder'), true)) {
            $args['meta_query'][] = array(
                'key' => '_stock_status',
                'value' => $stock_status,
                'compare' => '=',
            );
        }
    }
    
    // Rating filter
    if (isset($_POST['rating_filter']) && !empty($_POST['rating_filter'])) {
        $rating = absint($_POST['rating_filter']);
        
        if ($rating > 0) {
            $args['meta_query'][] = array(
                'key' => '_wc_average_rating',
                'value' => $rating,
                'compare' => '>=',
                'type' => 'DECIMAL',
            );
        }
    }
    
    // Category filter
    if (isset($_POST['product_cat']) && !empty($_POST['product_cat'])) {
        $categories = sanitize_text_field(wp_unslash($_POST['product_cat']));
        $cat_array = explode(',', $categories);
        
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $cat_array,
            'operator' => 'IN',
        );
    }
    
    // Tag filter
    if (isset($_POST['product_tag']) && !empty($_POST['product_tag'])) {
        $tags = sanitize_text_field(wp_unslash($_POST['product_tag']));
        $tag_array = explode(',', $tags);
        
        $args['tax_query'][] = array(
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => $tag_array,
            'operator' => 'IN',
        );
    }
    
    // Brand filter
    if (isset($_POST['product_brand']) && !empty($_POST['product_brand'])) {
        $brands = sanitize_text_field(wp_unslash($_POST['product_brand']));
        $brand_array = explode(',', $brands);
        
        $args['tax_query'][] = array(
            'taxonomy' => 'product_brand',
            'field' => 'slug',
            'terms' => $brand_array,
            'operator' => 'IN',
        );
    }
    
    // Origin filter
    if (isset($_POST['product_origin']) && !empty($_POST['product_origin'])) {
        $origins = sanitize_text_field(wp_unslash($_POST['product_origin']));
        $origin_array = explode(',', $origins);
        
        $args['tax_query'][] = array(
            'taxonomy' => 'product_origin',
            'field' => 'slug',
            'terms' => $origin_array,
            'operator' => 'IN',
        );
    }
    
    // Difficulty filter
    if (isset($_POST['product_difficulty']) && !empty($_POST['product_difficulty'])) {
        $difficulties = sanitize_text_field(wp_unslash($_POST['product_difficulty']));
        $difficulty_array = explode(',', $difficulties);
        
        $args['tax_query'][] = array(
            'taxonomy' => 'product_difficulty',
            'field' => 'slug',
            'terms' => $difficulty_array,
            'operator' => 'IN',
        );
    }
    
    // Attribute filters
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    
    if (!empty($attribute_taxonomies)) {
        foreach ($attribute_taxonomies as $tax) {
            $attribute = $tax->attribute_name;
            $filter_name = 'filter_' . sanitize_title($attribute);
            
            if (isset($_POST[$filter_name]) && !empty($_POST[$filter_name])) {
                $filter_value = sanitize_text_field(wp_unslash($_POST[$filter_name]));
                $taxonomy = wc_attribute_taxonomy_name($attribute);
                
                if (!empty($filter_value)) {
                    $terms = explode(',', $filter_value);
                    
                    $args['tax_query'][] = array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $terms,
                        'operator' => 'IN',
                    );
                }
            }
        }
    }
    
    // Search filter
    if (isset($_POST['s']) && !empty($_POST['s'])) {
        $args['s'] = sanitize_text_field(wp_unslash($_POST['s']));
    }
    
    // Ordering
    if (isset($_POST['orderby']) && !empty($_POST['orderby'])) {
        $ordering = sanitize_text_field(wp_unslash($_POST['orderby']));
        
        switch ($ordering) {
            case 'price':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'ASC';
                break;
            case 'price-desc':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_price';
                $args['order'] = 'DESC';
                break;
            case 'popularity':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'total_sales';
                $args['order'] = 'DESC';
                break;
            case 'rating':
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = '_wc_average_rating';
                $args['order'] = 'DESC';
                break;
            case 'date':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
            default:
                $args['orderby'] = 'menu_order title';
                $args['order'] = 'ASC';
                break;
        }
    }
    
    // Pagination
    if (isset($_POST['paged']) && !empty($_POST['paged'])) {
        $args['paged'] = absint($_POST['paged']);
    }
    
    // Run the query
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
        woocommerce_pagination();
    } else {
        echo '<p class="woocommerce-info">' . esc_html__('No products found matching your criteria.', 'aqualuxe') . '</p>';
    }
    
    wp_reset_postdata();
    
    $products_html = ob_get_clean();
    
    wp_send_json_success(array(
        'products' => $products_html,
        'found_posts' => $query->found_posts,
        'max_num_pages' => $query->max_num_pages,
    ));
}
add_action('wp_ajax_aqualuxe_filter_products', 'aqualuxe_ajax_filter_products');
add_action('wp_ajax_nopriv_aqualuxe_filter_products', 'aqualuxe_ajax_filter_products');

/**
 * Add filter form to shop page.
 */
function aqualuxe_product_filter_form() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    // Get min and max prices in the current result set
    global $wpdb;
    $min_price = floor($wpdb->get_var(
        $wpdb->prepare(
            "SELECT MIN(meta_value + 0) FROM {$wpdb->postmeta} WHERE meta_key = %s",
            '_price'
        )
    ));
    $max_price = ceil($wpdb->get_var(
        $wpdb->prepare(
            "SELECT MAX(meta_value + 0) FROM {$wpdb->postmeta} WHERE meta_key = %s",
            '_price'
        )
    ));
    
    // Get current values from URL
    $current_min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $min_price;
    $current_max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $max_price;
    $current_stock = isset($_GET['stock_status']) ? wc_clean(wp_unslash($_GET['stock_status'])) : '';
    $current_rating = isset($_GET['rating_filter']) ? absint($_GET['rating_filter']) : 0;
    $current_orderby = isset($_GET['orderby']) ? wc_clean(wp_unslash($_GET['orderby'])) : 'menu_order';
    
    // Get product categories
    $product_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));
    
    // Get product brands
    $product_brands = get_terms(array(
        'taxonomy' => 'product_brand',
        'hide_empty' => true,
    ));
    
    // Get product origins
    $product_origins = get_terms(array(
        'taxonomy' => 'product_origin',
        'hide_empty' => true,
    ));
    
    // Get product difficulties
    $product_difficulties = get_terms(array(
        'taxonomy' => 'product_difficulty',
        'hide_empty' => true,
    ));
    
    ?>
    <div class="product-filters mb-8">
        <button type="button" class="filter-toggle button mb-4 md:hidden"><?php esc_html_e('Show Filters', 'aqualuxe'); ?></button>
        
        <div class="filter-container hidden md:block">
            <form id="product-filters-form" class="product-filters-form" method="get" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                <div class="filter-header flex justify-between items-center mb-4">
                    <h3 class="filter-title text-lg font-bold"><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
                    <button type="button" class="reset-filters text-sm text-primary hover:text-primary-600"><?php esc_html_e('Reset Filters', 'aqualuxe'); ?></button>
                </div>
                
                <div class="filter-sections grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Price Filter -->
                    <div class="filter-section">
                        <h4 class="filter-section-title font-bold mb-2"><?php esc_html_e('Price Range', 'aqualuxe'); ?></h4>
                        <div class="price-range" data-min="<?php echo esc_attr($min_price); ?>" data-max="<?php echo esc_attr($max_price); ?>" data-current-min="<?php echo esc_attr($current_min_price); ?>" data-current-max="<?php echo esc_attr($current_max_price); ?>"></div>
                        
                        <div class="price-inputs flex justify-between mt-4">
                            <div class="min-price">
                                <label for="min_price"><?php echo esc_html__('Min', 'aqualuxe'); ?></label>
                                <input type="number" id="min_price" name="min_price" value="<?php echo esc_attr($current_min_price); ?>" min="<?php echo esc_attr($min_price); ?>" max="<?php echo esc_attr($current_max_price); ?>" step="1" />
                            </div>
                            <div class="max-price">
                                <label for="max_price"><?php echo esc_html__('Max', 'aqualuxe'); ?></label>
                                <input type="number" id="max_price" name="max_price" value="<?php echo esc_attr($current_max_price); ?>" min="<?php echo esc_attr($current_min_price); ?>" max="<?php echo esc_attr($max_price); ?>" step="1" />
                            </div>
                        </div>
                        
                        <div class="price-display mt-2 text-center">
                            <?php echo wc_price($current_min_price) . ' - ' . wc_price($current_max_price); ?>
                        </div>
                    </div>
                    
                    <!-- Categories Filter -->
                    <?php if (!empty($product_categories) && !is_wp_error($product_categories)) : ?>
                    <div class="filter-section">
                        <h4 class="filter-section-title font-bold mb-2"><?php esc_html_e('Categories', 'aqualuxe'); ?></h4>
                        <div class="categories-filter">
                            <select name="product_cat" class="select2" data-placeholder="<?php esc_attr_e('Select categories', 'aqualuxe'); ?>">
                                <option value=""><?php esc_html_e('All Categories', 'aqualuxe'); ?></option>
                                <?php
                                foreach ($product_categories as $category) {
                                    $selected = isset($_GET['product_cat']) && $_GET['product_cat'] === $category->slug ? 'selected="selected"' : '';
                                    echo '<option value="' . esc_attr($category->slug) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Brands Filter -->
                    <?php if (!empty($product_brands) && !is_wp_error($product_brands)) : ?>
                    <div class="filter-section">
                        <h4 class="filter-section-title font-bold mb-2"><?php esc_html_e('Brands', 'aqualuxe'); ?></h4>
                        <div class="brands-filter">
                            <select name="product_brand" class="select2" data-placeholder="<?php esc_attr_e('Select brands', 'aqualuxe'); ?>">
                                <option value=""><?php esc_html_e('All Brands', 'aqualuxe'); ?></option>
                                <?php
                                foreach ($product_brands as $brand) {
                                    $selected = isset($_GET['product_brand']) && $_GET['product_brand'] === $brand->slug ? 'selected="selected"' : '';
                                    echo '<option value="' . esc_attr($brand->slug) . '" ' . $selected . '>' . esc_html($brand->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Origins Filter -->
                    <?php if (!empty($product_origins) && !is_wp_error($product_origins)) : ?>
                    <div class="filter-section">
                        <h4 class="filter-section-title font-bold mb-2"><?php esc_html_e('Origins', 'aqualuxe'); ?></h4>
                        <div class="origins-filter">
                            <select name="product_origin" class="select2" data-placeholder="<?php esc_attr_e('Select origins', 'aqualuxe'); ?>">
                                <option value=""><?php esc_html_e('All Origins', 'aqualuxe'); ?></option>
                                <?php
                                foreach ($product_origins as $origin) {
                                    $selected = isset($_GET['product_origin']) && $_GET['product_origin'] === $origin->slug ? 'selected="selected"' : '';
                                    echo '<option value="' . esc_attr($origin->slug) . '" ' . $selected . '>' . esc_html($origin->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Difficulties Filter -->
                    <?php if (!empty($product_difficulties) && !is_wp_error($product_difficulties)) : ?>
                    <div class="filter-section">
                        <h4 class="filter-section-title font-bold mb-2"><?php esc_html_e('Care Difficulty', 'aqualuxe'); ?></h4>
                        <div class="difficulties-filter">
                            <select name="product_difficulty" class="select2" data-placeholder="<?php esc_attr_e('Select difficulty', 'aqualuxe'); ?>">
                                <option value=""><?php esc_html_e('All Difficulties', 'aqualuxe'); ?></option>
                                <?php
                                foreach ($product_difficulties as $difficulty) {
                                    $selected = isset($_GET['product_difficulty']) && $_GET['product_difficulty'] === $difficulty->slug ? 'selected="selected"' : '';
                                    echo '<option value="' . esc_attr($difficulty->slug) . '" ' . $selected . '>' . esc_html($difficulty->name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Stock Status Filter -->
                    <div class="filter-section">
                        <h4 class="filter-section-title font-bold mb-2"><?php esc_html_e('Stock Status', 'aqualuxe'); ?></h4>
                        <div class="stock-filter">
                            <select name="stock_status">
                                <option value=""><?php esc_html_e('All', 'aqualuxe'); ?></option>
                                <option value="instock" <?php selected($current_stock, 'instock'); ?>><?php esc_html_e('In Stock', 'aqualuxe'); ?></option>
                                <option value="outofstock" <?php selected($current_stock, 'outofstock'); ?>><?php esc_html_e('Out of Stock', 'aqualuxe'); ?></option>
                                <option value="onbackorder" <?php selected($current_stock, 'onbackorder'); ?>><?php esc_html_e('On Backorder', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Rating Filter -->
                    <div class="filter-section">
                        <h4 class="filter-section-title font-bold mb-2"><?php esc_html_e('Rating', 'aqualuxe'); ?></h4>
                        <div class="rating-filter">
                            <select name="rating_filter">
                                <option value=""><?php esc_html_e('All', 'aqualuxe'); ?></option>
                                <?php for ($rating = 5; $rating >= 1; $rating--) : ?>
                                <option value="<?php echo esc_attr($rating); ?>" <?php selected($current_rating, $rating); ?>>
                                    <?php
                                    // Display stars
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '★';
                                        } else {
                                            echo '☆';
                                        }
                                    }
                                    echo ' ' . esc_html(sprintf(__('and up', 'aqualuxe')));
                                    ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Sorting -->
                    <div class="filter-section">
                        <h4 class="filter-section-title font-bold mb-2"><?php esc_html_e('Sort By', 'aqualuxe'); ?></h4>
                        <div class="sorting-filter">
                            <select name="orderby" class="orderby">
                                <option value="menu_order" <?php selected($current_orderby, 'menu_order'); ?>><?php esc_html_e('Default sorting', 'aqualuxe'); ?></option>
                                <option value="popularity" <?php selected($current_orderby, 'popularity'); ?>><?php esc_html_e('Sort by popularity', 'aqualuxe'); ?></option>
                                <option value="rating" <?php selected($current_orderby, 'rating'); ?>><?php esc_html_e('Sort by average rating', 'aqualuxe'); ?></option>
                                <option value="date" <?php selected($current_orderby, 'date'); ?>><?php esc_html_e('Sort by latest', 'aqualuxe'); ?></option>
                                <option value="price" <?php selected($current_orderby, 'price'); ?>><?php esc_html_e('Sort by price: low to high', 'aqualuxe'); ?></option>
                                <option value="price-desc" <?php selected($current_orderby, 'price-desc'); ?>><?php esc_html_e('Sort by price: high to low', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="filter-actions mt-6">
                    <button type="submit" class="button filter-submit"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
                    <button type="button" class="button alt reset-filters ml-4"><?php esc_html_e('Reset', 'aqualuxe'); ?></button>
                </div>
            </form>
        </div>
    </div>
    <?php
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_filter_form', 20);

/**
 * Add filter toggle script.
 */
function aqualuxe_filter_toggle_script() {
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterToggle = document.querySelector('.filter-toggle');
        const filterContainer = document.querySelector('.filter-container');
        
        if (filterToggle && filterContainer) {
            filterToggle.addEventListener('click', function() {
                filterContainer.classList.toggle('hidden');
                
                if (filterContainer.classList.contains('hidden')) {
                    filterToggle.textContent = '<?php echo esc_js(__('Show Filters', 'aqualuxe')); ?>';
                } else {
                    filterToggle.textContent = '<?php echo esc_js(__('Hide Filters', 'aqualuxe')); ?>';
                }
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'aqualuxe_filter_toggle_script');