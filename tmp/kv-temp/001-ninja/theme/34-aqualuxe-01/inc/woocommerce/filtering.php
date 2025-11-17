<?php
/**
 * AquaLuxe WooCommerce Product Filtering
 *
 * Handles the advanced product filtering functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include the product filter class with accessibility enhancements
require_once AQUALUXE_DIR . 'inc/woocommerce/filtering/class-aqualuxe-product-filter-a11y.php';

/**
 * Enqueue product filter scripts and styles.
 */
function aqualuxe_enqueue_product_filter_assets() {
    // Only enqueue on product archive pages
    if (!is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }

    // Check if filter is enabled in customizer
    if (!get_theme_mod('aqualuxe_enable_product_filter', true)) {
        return;
    }

    // Enqueue product filter styles
    wp_enqueue_style('aqualuxe-product-filter', AQUALUXE_ASSETS_URI . 'css/product-filter.css', array(), AQUALUXE_VERSION);

    // Enqueue product filter scripts
    wp_enqueue_script('aqualuxe-product-filter', AQUALUXE_ASSETS_URI . 'js/product-filter.js', array('jquery'), AQUALUXE_VERSION, true);

    // Localize script
    wp_localize_script('aqualuxe-product-filter', 'aqualuxeFilter', array(
        'ajaxUrl'      => admin_url('admin-ajax.php'),
        'nonce'        => wp_create_nonce('aqualuxe-filter-nonce'),
        'shopUrl'      => get_permalink(wc_get_page_id('shop')),
        'isShop'       => is_shop(),
        'isMobile'     => wp_is_mobile(),
        'enableAjax'   => get_theme_mod('aqualuxe_enable_ajax_filter', true),
        'i18n'         => array(
            'loading'     => __('Loading products...', 'aqualuxe'),
            'noProducts'  => __('No products found.', 'aqualuxe'),
            'clearAll'    => __('Clear All', 'aqualuxe'),
            'apply'       => __('Apply Filters', 'aqualuxe'),
            'filter'      => __('Filter', 'aqualuxe'),
            'price'       => __('Price', 'aqualuxe'),
            'min'         => __('Min', 'aqualuxe'),
            'max'         => __('Max', 'aqualuxe'),
        ),
    ));
    
    // Add accessibility-specific styles based on customizer settings
    $custom_css = '';
    
    // High contrast mode
    if (get_theme_mod('aqualuxe_filter_high_contrast', false)) {
        $custom_css .= '
            .filter-widget .filter-checkbox .checkmark {
                border-color: #000 !important;
            }
            .filter-widget .filter-checkbox input[type="checkbox"]:checked ~ .checkmark:after {
                background-color: #000 !important;
            }
            .filter-tag {
                border-color: #000 !important;
            }
            .noUi-connect {
                background-color: #000 !important;
            }
            .noUi-handle {
                border-color: #000 !important;
            }
            .filter-widget-title, .active-filters-title {
                color: #000 !important;
            }
            .dark-mode .filter-widget .filter-checkbox .checkmark {
                border-color: #fff !important;
            }
            .dark-mode .filter-widget .filter-checkbox input[type="checkbox"]:checked ~ .checkmark:after {
                background-color: #fff !important;
            }
            .dark-mode .filter-tag {
                border-color: #fff !important;
            }
            .dark-mode .noUi-connect {
                background-color: #fff !important;
            }
            .dark-mode .noUi-handle {
                border-color: #fff !important;
            }
            .dark-mode .filter-widget-title, .dark-mode .active-filters-title {
                color: #fff !important;
            }
        ';
    }
    
    // Larger text
    if (get_theme_mod('aqualuxe_filter_larger_text', false)) {
        $custom_css .= '
            .filter-widget-title, .active-filters-title {
                font-size: 1.25rem !important;
            }
            .filter-checkbox, .filter-tag, .clear-all-filters {
                font-size: 1.1rem !important;
            }
            .filter-toggle-button {
                font-size: 1.1rem !important;
                padding: 0.75rem 1rem !important;
            }
        ';
    }
    
    // Focus indicator style
    $focus_style = get_theme_mod('aqualuxe_filter_focus_style', 'default');
    if ($focus_style === 'bold') {
        $custom_css .= '
            button:focus-visible,
            a:focus-visible,
            input:focus-visible,
            select:focus-visible,
            textarea:focus-visible,
            .filter-checkbox input[type="checkbox"]:focus + .checkmark,
            .filter-color input[type="checkbox"]:focus + .color-swatch,
            .filter-image input[type="checkbox"]:focus + img,
            .noUi-handle:focus {
                outline: 3px solid #000 !important;
                outline-offset: 3px !important;
            }
            .dark-mode button:focus-visible,
            .dark-mode a:focus-visible,
            .dark-mode input:focus-visible,
            .dark-mode select:focus-visible,
            .dark-mode textarea:focus-visible,
            .dark-mode .filter-checkbox input[type="checkbox"]:focus + .checkmark,
            .dark-mode .filter-color input[type="checkbox"]:focus + .color-swatch,
            .dark-mode .filter-image input[type="checkbox"]:focus + img,
            .dark-mode .noUi-handle:focus {
                outline: 3px solid #fff !important;
                outline-offset: 3px !important;
            }
        ';
    } elseif ($focus_style === 'outline') {
        $custom_css .= '
            button:focus-visible,
            a:focus-visible,
            input:focus-visible,
            select:focus-visible,
            textarea:focus-visible,
            .filter-checkbox input[type="checkbox"]:focus + .checkmark,
            .filter-color input[type="checkbox"]:focus + .color-swatch,
            .filter-image input[type="checkbox"]:focus + img,
            .noUi-handle:focus {
                outline: 2px dotted #000 !important;
                outline-offset: 2px !important;
            }
            .dark-mode button:focus-visible,
            .dark-mode a:focus-visible,
            .dark-mode input:focus-visible,
            .dark-mode select:focus-visible,
            .dark-mode textarea:focus-visible,
            .dark-mode .filter-checkbox input[type="checkbox"]:focus + .checkmark,
            .dark-mode .filter-color input[type="checkbox"]:focus + .color-swatch,
            .dark-mode .filter-image input[type="checkbox"]:focus + img,
            .dark-mode .noUi-handle:focus {
                outline: 2px dotted #fff !important;
                outline-offset: 2px !important;
            }
        ';
    }
    
    // Add custom CSS if needed
    if (!empty($custom_css)) {
        wp_add_inline_style('aqualuxe-product-filter', $custom_css);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_product_filter_assets');

/**
 * Register product filter widgets.
 */
function aqualuxe_register_product_filter_widgets() {
    // Price Filter Widget
    register_widget('AquaLuxe_Price_Filter_Widget');
    
    // Attribute Filter Widget
    register_widget('AquaLuxe_Attribute_Filter_Widget');
    
    // Category Filter Widget
    register_widget('AquaLuxe_Category_Filter_Widget');
    
    // Rating Filter Widget
    register_widget('AquaLuxe_Rating_Filter_Widget');
    
    // Active Filters Widget
    register_widget('AquaLuxe_Active_Filters_Widget');
}
add_action('widgets_init', 'aqualuxe_register_product_filter_widgets');

/**
 * Price Filter Widget
 */
class AquaLuxe_Price_Filter_Widget extends WP_Widget {
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
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        // Only show on product archive pages
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Price', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        global $wpdb;

        // Get min and max prices from products
        $min_max_prices = $wpdb->get_row("
            SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_price, MAX(CAST(meta_value AS DECIMAL(10,2))) as max_price
            FROM {$wpdb->postmeta}
            WHERE meta_key = '_price'
            AND meta_value > 0
        ");

        if (!$min_max_prices) {
            echo $args['after_widget'];
            return;
        }

        $min_price = floor($min_max_prices->min_price);
        $max_price = ceil($min_max_prices->max_price);

        // Get current filter values
        $current_min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $min_price;
        $current_max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $max_price;

        $min_price_id = 'min-price-' . $this->id;
        $max_price_id = 'max-price-' . $this->id;
        $slider_id = 'price-slider-' . $this->id;
        ?>
        <div class="filter-widget filter-price">
            <div class="price-slider-container" role="group" aria-labelledby="<?php echo esc_attr($this->id); ?>-title">
                <div id="<?php echo esc_attr($slider_id); ?>" class="price-slider" 
                     data-min="<?php echo esc_attr($min_price); ?>" 
                     data-max="<?php echo esc_attr($max_price); ?>" 
                     data-current-min="<?php echo esc_attr($current_min_price); ?>" 
                     data-current-max="<?php echo esc_attr($current_max_price); ?>"
                     role="group"
                     aria-label="<?php esc_attr_e('Price range slider', 'aqualuxe'); ?>"
                     aria-describedby="<?php echo esc_attr($slider_id); ?>-description"
                >
                </div>
                <div id="<?php echo esc_attr($slider_id); ?>-description" class="screen-reader-text">
                    <?php esc_html_e('Use arrow keys to adjust the price range. Left/Right arrows for small changes, Page Up/Down for larger changes, Home/End to set minimum or maximum values.', 'aqualuxe'); ?>
                </div>
                <div class="price-slider-values">
                    <div class="price-slider-min">
                        <label for="<?php echo esc_attr($min_price_id); ?>" class="screen-reader-text"><?php esc_html_e('Minimum price', 'aqualuxe'); ?></label>
                        <?php echo get_woocommerce_currency_symbol(); ?>
                        <span><?php echo esc_html($current_min_price); ?></span>
                        <input type="hidden" id="<?php echo esc_attr($min_price_id); ?>" name="min_price" value="<?php echo esc_attr($current_min_price); ?>">
                    </div>
                    <div class="price-slider-max">
                        <label for="<?php echo esc_attr($max_price_id); ?>" class="screen-reader-text"><?php esc_html_e('Maximum price', 'aqualuxe'); ?></label>
                        <?php echo get_woocommerce_currency_symbol(); ?>
                        <span><?php echo esc_html($current_max_price); ?></span>
                        <input type="hidden" id="<?php echo esc_attr($max_price_id); ?>" name="max_price" value="<?php echo esc_attr($current_max_price); ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Widget form.
     *
     * @param array $instance Previously saved values from database.
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
     * @param array $new_instance New values from database.
     * @param array $old_instance Old values from database.
     * @return array Updated values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

/**
 * Attribute Filter Widget
 */
class AquaLuxe_Attribute_Filter_Widget extends WP_Widget {
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
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        // Only show on product archive pages
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $attribute = !empty($instance['attribute']) ? $instance['attribute'] : '';
        if (empty($attribute)) {
            return;
        }

        $title = !empty($instance['title']) ? $instance['title'] : wc_attribute_label($attribute);
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $query_type = !empty($instance['query_type']) ? $instance['query_type'] : 'and';
        $display_type = !empty($instance['display_type']) ? $instance['display_type'] : 'list';

        // Get terms
        $taxonomy = wc_attribute_taxonomy_name($attribute);
        $terms = get_terms(array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => true,
        ));

        if (empty($terms) || is_wp_error($terms)) {
            return;
        }

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $param_name = 'filter_' . $attribute;
        $current_values = isset($_GET[$param_name]) ? (array) $_GET[$param_name] : array();
        $filter_id = 'filter-' . $attribute . '-' . $this->id;

        ?>
        <div class="filter-widget filter-attribute filter-<?php echo esc_attr($attribute); ?>">
            <?php if ($display_type === 'color') : ?>
                <div class="filter-color-list" role="group" aria-labelledby="<?php echo esc_attr($this->id); ?>-title">
                    <?php foreach ($terms as $term) : 
                        $color = get_term_meta($term->term_id, 'product_attribute_color', true);
                        if (!$color) {
                            $color = '#eeeeee';
                        }
                        $is_selected = in_array($term->slug, $current_values);
                        $checkbox_id = 'color-' . $term->slug . '-' . $this->id;
                    ?>
                        <div class="filter-color-item">
                            <label class="filter-color" for="<?php echo esc_attr($checkbox_id); ?>" title="<?php echo esc_attr($term->name); ?>">
                                <input type="checkbox" 
                                    id="<?php echo esc_attr($checkbox_id); ?>" 
                                    name="<?php echo esc_attr($param_name); ?>[]" 
                                    value="<?php echo esc_attr($term->slug); ?>" 
                                    <?php checked($is_selected); ?>
                                    aria-label="<?php echo esc_attr($term->name); ?>"
                                >
                                <span class="color-swatch" style="background-color: <?php echo esc_attr($color); ?>" aria-hidden="true"></span>
                                <span class="color-name"><?php echo esc_html($term->name); ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($display_type === 'image') : ?>
                <div class="filter-image-list" role="group" aria-labelledby="<?php echo esc_attr($this->id); ?>-title">
                    <?php foreach ($terms as $term) : 
                        $image_id = get_term_meta($term->term_id, 'product_attribute_image', true);
                        $image_url = $image_id ? wp_get_attachment_thumb_url($image_id) : wc_placeholder_img_src('thumbnail');
                        $is_selected = in_array($term->slug, $current_values);
                        $checkbox_id = 'image-' . $term->slug . '-' . $this->id;
                    ?>
                        <div class="filter-image-item">
                            <label class="filter-image" for="<?php echo esc_attr($checkbox_id); ?>" title="<?php echo esc_attr($term->name); ?>">
                                <input type="checkbox" 
                                    id="<?php echo esc_attr($checkbox_id); ?>" 
                                    name="<?php echo esc_attr($param_name); ?>[]" 
                                    value="<?php echo esc_attr($term->slug); ?>" 
                                    <?php checked($is_selected); ?>
                                    aria-label="<?php echo esc_attr($term->name); ?>"
                                >
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($term->name); ?>" aria-hidden="true">
                                <span class="image-name"><?php echo esc_html($term->name); ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <ul class="filter-list" role="group" aria-labelledby="<?php echo esc_attr($this->id); ?>-title">
                    <?php foreach ($terms as $term) : 
                        $is_selected = in_array($term->slug, $current_values);
                        $checkbox_id = 'term-' . $term->slug . '-' . $this->id;
                    ?>
                        <li>
                            <label class="filter-checkbox" for="<?php echo esc_attr($checkbox_id); ?>">
                                <input type="checkbox" 
                                    id="<?php echo esc_attr($checkbox_id); ?>" 
                                    name="<?php echo esc_attr($param_name); ?>[]" 
                                    value="<?php echo esc_attr($term->slug); ?>" 
                                    <?php checked($is_selected); ?>
                                    aria-describedby="<?php echo esc_attr($checkbox_id); ?>-count"
                                >
                                <span class="checkmark" aria-hidden="true"></span>
                                <?php echo esc_html($term->name); ?>
                                <span class="count" id="<?php echo esc_attr($checkbox_id); ?>-count">(<?php echo esc_html($term->count); ?>)</span>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <input type="hidden" name="query_type_<?php echo esc_attr($attribute); ?>" value="<?php echo esc_attr($query_type); ?>">
        </div>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $attribute = !empty($instance['attribute']) ? $instance['attribute'] : '';
        $query_type = !empty($instance['query_type']) ? $instance['query_type'] : 'and';
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
                <?php foreach ($attribute_taxonomies as $tax) : ?>
                    <option value="<?php echo esc_attr($tax->attribute_name); ?>" <?php selected($attribute, $tax->attribute_name); ?>>
                        <?php echo esc_html($tax->attribute_label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('query_type')); ?>"><?php esc_html_e('Query Type:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('query_type')); ?>" name="<?php echo esc_attr($this->get_field_name('query_type')); ?>">
                <option value="and" <?php selected($query_type, 'and'); ?>><?php esc_html_e('AND - Products must have all selected attributes', 'aqualuxe'); ?></option>
                <option value="or" <?php selected($query_type, 'or'); ?>><?php esc_html_e('OR - Products must have any selected attribute', 'aqualuxe'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('display_type')); ?>"><?php esc_html_e('Display Type:', 'aqualuxe'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('display_type')); ?>" name="<?php echo esc_attr($this->get_field_name('display_type')); ?>">
                <option value="list" <?php selected($display_type, 'list'); ?>><?php esc_html_e('List', 'aqualuxe'); ?></option>
                <option value="color" <?php selected($display_type, 'color'); ?>><?php esc_html_e('Color Swatches', 'aqualuxe'); ?></option>
                <option value="image" <?php selected($display_type, 'image'); ?>><?php esc_html_e('Images', 'aqualuxe'); ?></option>
            </select>
        </p>
        <?php
    }

    /**
     * Widget update.
     *
     * @param array $new_instance New values from database.
     * @param array $old_instance Old values from database.
     * @return array Updated values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['attribute'] = !empty($new_instance['attribute']) ? sanitize_text_field($new_instance['attribute']) : '';
        $instance['query_type'] = !empty($new_instance['query_type']) ? sanitize_text_field($new_instance['query_type']) : 'and';
        $instance['display_type'] = !empty($new_instance['display_type']) ? sanitize_text_field($new_instance['display_type']) : 'list';
        return $instance;
    }
}

/**
 * Category Filter Widget
 */
class AquaLuxe_Category_Filter_Widget extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_category_filter',
            __('AquaLuxe Category Filter', 'aqualuxe'),
            array(
                'description' => __('Filter products by category.', 'aqualuxe'),
            )
        );
    }

    /**
     * Widget output.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        // Only show on product archive pages
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Category', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $show_hierarchy = !empty($instance['show_hierarchy']) ? (bool) $instance['show_hierarchy'] : true;
        $show_count = !empty($instance['show_count']) ? (bool) $instance['show_count'] : true;

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $product_categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => 0,
        ));

        if (empty($product_categories)) {
            echo $args['after_widget'];
            return;
        }

        ?>
        <div class="filter-widget filter-categories">
            <ul class="filter-list" role="group" aria-labelledby="<?php echo esc_attr($this->id); ?>-title">
                <?php foreach ($product_categories as $category) : 
                    $checkbox_id = 'category-' . $category->slug . '-' . $this->id;
                    $is_checked = isset($_GET['product_cat']) && in_array($category->slug, (array) $_GET['product_cat']);
                ?>
                    <li>
                        <label class="filter-checkbox" for="<?php echo esc_attr($checkbox_id); ?>">
                            <input type="checkbox" 
                                id="<?php echo esc_attr($checkbox_id); ?>" 
                                name="product_cat[]" 
                                value="<?php echo esc_attr($category->slug); ?>" 
                                <?php checked($is_checked); ?>
                                aria-describedby="<?php echo esc_attr($checkbox_id); ?>-count"
                            >
                            <span class="checkmark" aria-hidden="true"></span>
                            <?php echo esc_html($category->name); ?>
                            <?php if ($show_count) : ?>
                                <span class="count" id="<?php echo esc_attr($checkbox_id); ?>-count">(<?php echo esc_html($category->count); ?>)</span>
                            <?php endif; ?>
                        </label>
                        <?php
                        // Get child categories if hierarchy is enabled
                        if ($show_hierarchy) {
                            $child_categories = get_terms(array(
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => true,
                                'parent'     => $category->term_id,
                            ));

                            if (!empty($child_categories)) {
                                $child_group_id = 'child-categories-' . $category->slug . '-' . $this->id;
                                echo '<ul class="filter-children" role="group" aria-labelledby="' . esc_attr($child_group_id) . '">';
                                echo '<span id="' . esc_attr($child_group_id) . '" class="screen-reader-text">' . sprintf(__('Subcategories of %s', 'aqualuxe'), $category->name) . '</span>';
                                
                                foreach ($child_categories as $child) {
                                    $child_checkbox_id = 'category-' . $child->slug . '-' . $this->id;
                                    $is_child_checked = isset($_GET['product_cat']) && in_array($child->slug, (array) $_GET['product_cat']);
                                    ?>
                                    <li>
                                        <label class="filter-checkbox" for="<?php echo esc_attr($child_checkbox_id); ?>">
                                            <input type="checkbox" 
                                                id="<?php echo esc_attr($child_checkbox_id); ?>" 
                                                name="product_cat[]" 
                                                value="<?php echo esc_attr($child->slug); ?>" 
                                                <?php checked($is_child_checked); ?>
                                                aria-describedby="<?php echo esc_attr($child_checkbox_id); ?>-count"
                                            >
                                            <span class="checkmark" aria-hidden="true"></span>
                                            <?php echo esc_html($child->name); ?>
                                            <?php if ($show_count) : ?>
                                                <span class="count" id="<?php echo esc_attr($child_checkbox_id); ?>-count">(<?php echo esc_html($child->count); ?>)</span>
                                            <?php endif; ?>
                                        </label>
                                    </li>
                                    <?php
                                }
                                echo '</ul>';
                            }
                        }
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Category', 'aqualuxe');
        $show_hierarchy = isset($instance['show_hierarchy']) ? (bool) $instance['show_hierarchy'] : true;
        $show_count = isset($instance['show_count']) ? (bool) $instance['show_count'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('show_hierarchy')); ?>" name="<?php echo esc_attr($this->get_field_name('show_hierarchy')); ?>" <?php checked($show_hierarchy); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_hierarchy')); ?>"><?php esc_html_e('Show hierarchy', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('show_count')); ?>" name="<?php echo esc_attr($this->get_field_name('show_count')); ?>" <?php checked($show_count); ?>>
            <label for="<?php echo esc_attr($this->get_field_id('show_count')); ?>"><?php esc_html_e('Show product counts', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Widget update.
     *
     * @param array $new_instance New values from database.
     * @param array $old_instance Old values from database.
     * @return array Updated values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_hierarchy'] = !empty($new_instance['show_hierarchy']) ? 1 : 0;
        $instance['show_count'] = !empty($new_instance['show_count']) ? 1 : 0;
        return $instance;
    }
}

/**
 * Rating Filter Widget
 */
class AquaLuxe_Rating_Filter_Widget extends WP_Widget {
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
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        // Only show on product archive pages
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $title = !empty($instance['title']) ? $instance['title'] : __('Filter by Rating', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $current_ratings = isset($_GET['rating_filter']) ? (array) $_GET['rating_filter'] : array();
        ?>
        <div class="filter-widget filter-rating">
            <ul class="filter-list" role="group" aria-labelledby="<?php echo esc_attr($this->id); ?>-title">
                <?php for ($rating = 5; $rating >= 1; $rating--) : 
                    $is_selected = in_array($rating, $current_ratings);
                    $checkbox_id = 'rating-' . $rating . '-' . $this->id;
                    
                    // Prepare rating text for screen readers
                    $rating_text = ($rating === 1) 
                        ? __('1 star & up', 'aqualuxe')
                        : sprintf(__('%d stars & up', 'aqualuxe'), $rating);
                ?>
                    <li>
                        <label class="filter-checkbox" for="<?php echo esc_attr($checkbox_id); ?>">
                            <input type="checkbox" 
                                id="<?php echo esc_attr($checkbox_id); ?>" 
                                name="rating_filter[]" 
                                value="<?php echo esc_attr($rating); ?>" 
                                <?php checked($is_selected); ?>
                            >
                            <span class="checkmark" aria-hidden="true"></span>
                            <span class="star-rating" aria-hidden="true">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <span class="star <?php echo ($i <= $rating) ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </span>
                            <span class="rating-text">
                                <?php echo esc_html($rating_text); ?>
                            </span>
                        </label>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Widget form.
     *
     * @param array $instance Previously saved values from database.
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
     * @param array $new_instance New values from database.
     * @param array $old_instance Old values from database.
     * @return array Updated values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

/**
 * Active Filters Widget
 */
class AquaLuxe_Active_Filters_Widget extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_active_filters',
            __('AquaLuxe Active Filters', 'aqualuxe'),
            array(
                'description' => __('Display active product filters.', 'aqualuxe'),
            )
        );
    }

    /**
     * Widget output.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        // Only show on product archive pages
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        // Check if we have active filters
        if (!$this->has_active_filters()) {
            return;
        }

        $title = !empty($instance['title']) ? $instance['title'] : __('Active Filters', 'aqualuxe');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $active_filters_id = 'active-filters-' . $this->id;
        ?>
        <div class="active-filters" role="region" aria-labelledby="<?php echo esc_attr($active_filters_id); ?>">
            <div class="active-filter-tags">
                <?php
                // Category filters
                if (isset($_GET['product_cat']) && !empty($_GET['product_cat'])) {
                    $categories = (array) $_GET['product_cat'];
                    foreach ($categories as $category_slug) {
                        $term = get_term_by('slug', $category_slug, 'product_cat');
                        if ($term) {
                            $this->render_filter_tag($term->name, 'product_cat[]', $category_slug);
                        }
                    }
                }

                // Price filter
                if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
                    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
                    $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : PHP_INT_MAX;
                    
                    $price_label = sprintf(
                        '%s%s - %s%s',
                        get_woocommerce_currency_symbol(),
                        $min_price,
                        get_woocommerce_currency_symbol(),
                        $max_price
                    );
                    
                    $this->render_filter_tag($price_label, 'price_range', '', true);
                }

                // Attribute filters
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if (!empty($attribute_taxonomies)) {
                    foreach ($attribute_taxonomies as $attribute) {
                        $param_name = 'filter_' . $attribute->attribute_name;
                        if (isset($_GET[$param_name]) && !empty($_GET[$param_name])) {
                            $values = (array) $_GET[$param_name];
                            $taxonomy = 'pa_' . $attribute->attribute_name;
                            
                            foreach ($values as $value) {
                                $term = get_term_by('slug', $value, $taxonomy);
                                if ($term) {
                                    $this->render_filter_tag($term->name, $param_name . '[]', $value);
                                }
                            }
                        }
                    }
                }

                // Rating filter
                if (isset($_GET['rating_filter']) && !empty($_GET['rating_filter'])) {
                    $ratings = (array) $_GET['rating_filter'];
                    foreach ($ratings as $rating) {
                        $label = ($rating == 1) 
                            ? esc_html__('1 star & up', 'aqualuxe') 
                            : sprintf(esc_html__('%d stars & up', 'aqualuxe'), $rating);
                        
                        $this->render_filter_tag($label, 'rating_filter[]', $rating);
                    }
                }
                ?>
                <a href="<?php echo esc_url(remove_query_arg(array('product_cat', 'min_price', 'max_price', 'rating_filter', 'filter_', 'query_type_'))); ?>" class="clear-all-filters" role="button">
                    <?php esc_html_e('Clear All', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Render a single filter tag.
     *
     * @param string $label      The filter label.
     * @param string $param_name The parameter name.
     * @param string $value      The parameter value.
     * @param bool   $is_price   Whether this is a price filter.
     */
    private function render_filter_tag($label, $param_name, $value, $is_price = false) {
        $current_url = remove_query_arg('paged');
        
        if ($is_price) {
            $remove_url = remove_query_arg(array('min_price', 'max_price'), $current_url);
            $remove_label = sprintf(__('Remove price filter: %s', 'aqualuxe'), $label);
        } else {
            $remove_url = remove_query_arg($param_name . '=' . $value, $current_url);
            $remove_label = sprintf(__('Remove filter: %s', 'aqualuxe'), $label);
        }
        ?>
        <span class="filter-tag">
            <?php echo esc_html($label); ?>
            <a href="<?php echo esc_url($remove_url); ?>" class="remove-filter" role="button" aria-label="<?php echo esc_attr($remove_label); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
            </a>
        </span>
        <?php
    }

    /**
     * Check if there are active filters.
     *
     * @return bool True if there are active filters.
     */
    private function has_active_filters() {
        $has_filters = false;

        // Check category filter
        if (isset($_GET['product_cat']) && !empty($_GET['product_cat'])) {
            $has_filters = true;
        }

        // Check price filter
        if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
            $has_filters = true;
        }

        // Check attribute filters
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if (!empty($attribute_taxonomies)) {
            foreach ($attribute_taxonomies as $attribute) {
                $param_name = 'filter_' . $attribute->attribute_name;
                if (isset($_GET[$param_name]) && !empty($_GET[$param_name])) {
                    $has_filters = true;
                    break;
                }
            }
        }

        // Check rating filter
        if (isset($_GET['rating_filter']) && !empty($_GET['rating_filter'])) {
            $has_filters = true;
        }

        return $has_filters;
    }

    /**
     * Widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Active Filters', 'aqualuxe');
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
     * @param array $new_instance New values from database.
     * @param array $old_instance Old values from database.
     * @return array Updated values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}