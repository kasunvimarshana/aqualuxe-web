<?php
/**
 * AquaLuxe Product Filter with Accessibility Enhancements
 *
 * Handles the advanced product filtering functionality with improved accessibility.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Product Filter Class with Accessibility Enhancements
 */
class AquaLuxe_Product_Filter {

    /**
     * The single instance of the class.
     *
     * @var AquaLuxe_Product_Filter
     */
    protected static $_instance = null;

    /**
     * Main AquaLuxe_Product_Filter Instance.
     *
     * Ensures only one instance of AquaLuxe_Product_Filter is loaded or can be loaded.
     *
     * @return AquaLuxe_Product_Filter - Main instance.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks.
     */
    public function init_hooks() {
        // Add filter widget area
        add_action('widgets_init', array($this, 'register_filter_sidebar'));
        
        // Add filter container
        add_action('woocommerce_before_shop_loop', array($this, 'render_filter_container'), 20);
        
        // Add AJAX handlers
        add_action('wp_ajax_aqualuxe_filter_products', array($this, 'ajax_filter_products'));
        add_action('wp_ajax_nopriv_aqualuxe_filter_products', array($this, 'ajax_filter_products'));
        
        // Add filter scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add filter options to customizer
        add_action('customize_register', array($this, 'add_customizer_options'));
        
        // Add filter attributes
        add_filter('woocommerce_product_query_tax_query', array($this, 'filter_products'), 10, 2);
        
        // Add price filter
        add_filter('woocommerce_product_query_meta_query', array($this, 'price_filter'), 10, 2);
        
        // Add active filters display
        add_action('woocommerce_before_shop_loop', array($this, 'display_active_filters'), 25);
        
        // Add skip link for accessibility
        add_action('wp_footer', array($this, 'add_skip_link'));
    }

    /**
     * Add skip link for keyboard navigation.
     */
    public function add_skip_link() {
        // Only add on shop pages
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        ?>
        <a href="#main-content" class="skip-to-content-link"><?php esc_html_e('Skip to products', 'aqualuxe'); ?></a>
        <?php
    }

    /**
     * Register filter sidebar.
     */
    public function register_filter_sidebar() {
        register_sidebar(array(
            'name'          => __('Product Filters', 'aqualuxe'),
            'id'            => 'product-filters',
            'description'   => __('Add widgets here to appear in the product filter sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="filter-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="filter-widget-title">',
            'after_title'   => '</h4>',
        ));
    }

    /**
     * Render filter container.
     */
    public function render_filter_container() {
        // Only show on product archive pages
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        // Check if filter is enabled in customizer
        if (!get_theme_mod('aqualuxe_enable_product_filter', true)) {
            return;
        }

        // Get filter layout
        $filter_layout = get_theme_mod('aqualuxe_filter_layout', 'sidebar');

        // Get filter position
        $filter_position = get_theme_mod('aqualuxe_filter_position', 'left');

        // Filter classes
        $filter_classes = array(
            'product-filters',
            'filter-layout-' . $filter_layout,
            'filter-position-' . $filter_position
        );

        // Check if filter is active
        if ($this->has_active_filters()) {
            $filter_classes[] = 'has-active-filters';
        }

        // Start filter container
        ?>
        <div class="<?php echo esc_attr(implode(' ', $filter_classes)); ?>">
            <?php if ($filter_layout === 'offcanvas') : ?>
                <button class="filter-toggle-button" aria-expanded="false" aria-controls="filter-container" aria-label="<?php esc_attr_e('Toggle product filters', 'aqualuxe'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/></svg>
                    <?php esc_html_e('Filter Products', 'aqualuxe'); ?>
                </button>
            <?php endif; ?>

            <div id="filter-container" class="filter-container" role="region" aria-label="<?php esc_attr_e('Product Filters', 'aqualuxe'); ?>">
                <?php if ($filter_layout === 'offcanvas') : ?>
                    <div class="filter-header">
                        <h3 id="filter-heading"><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h3>
                        <button class="filter-close" aria-label="<?php esc_attr_e('Close filters', 'aqualuxe'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
                        </button>
                    </div>
                <?php endif; ?>

                <form class="filter-widgets" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" method="get" role="search" aria-labelledby="<?php echo $filter_layout === 'offcanvas' ? 'filter-heading' : ''; ?>">
                    <?php 
                    if (is_active_sidebar('product-filters')) {
                        dynamic_sidebar('product-filters');
                    } else {
                        $this->render_default_filters();
                    }
                    
                    // Add hidden inputs for any query string parameters that should be preserved
                    if (isset($_GET['s'])) {
                        echo '<input type="hidden" name="s" value="' . esc_attr($_GET['s']) . '">';
                    }
                    
                    // Add current page ID if on a category or tag page
                    if (is_product_category() || is_product_tag()) {
                        $queried_object = get_queried_object();
                        if ($queried_object && isset($queried_object->term_id)) {
                            echo '<input type="hidden" name="' . esc_attr($queried_object->taxonomy) . '" value="' . esc_attr($queried_object->slug) . '">';
                        }
                    }
                    ?>
                </form>

                <?php if ($filter_layout === 'offcanvas') : ?>
                    <div class="filter-actions">
                        <button class="filter-clear-all" type="button"><?php esc_html_e('Clear All', 'aqualuxe'); ?></button>
                        <button class="filter-apply" type="button"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Screen reader announcer for dynamic content updates -->
            <div id="a11y-status-message" class="screen-reader-text" aria-live="polite" aria-atomic="true"></div>
        </div>
        <?php
    }

    /**
     * Render default filters when no widgets are added.
     */
    public function render_default_filters() {
        // Categories filter
        $this->render_category_filter();
        
        // Price filter
        $this->render_price_filter();
        
        // Attribute filters
        $this->render_attribute_filters();
        
        // Rating filter
        $this->render_rating_filter();
    }

    /**
     * Render category filter.
     */
    public function render_category_filter() {
        $product_categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => 0,
        ));

        if (empty($product_categories)) {
            return;
        }

        $filter_id = 'filter-categories-' . uniqid();
        ?>
        <div class="filter-widget filter-categories">
            <h4 class="filter-widget-title" id="<?php echo esc_attr($filter_id); ?>" data-collapsible="true" aria-expanded="true"><?php esc_html_e('Categories', 'aqualuxe'); ?></h4>
            <ul class="filter-list" role="group" aria-labelledby="<?php echo esc_attr($filter_id); ?>">
                <?php foreach ($product_categories as $category) : 
                    $checkbox_id = 'category-' . $category->slug;
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
                            <span class="count" id="<?php echo esc_attr($checkbox_id); ?>-count">(<?php echo esc_html($category->count); ?>)</span>
                        </label>
                        <?php
                        // Get child categories
                        $child_categories = get_terms(array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                            'parent'     => $category->term_id,
                        ));

                        if (!empty($child_categories)) {
                            $child_group_id = 'child-categories-' . $category->slug;
                            echo '<ul class="filter-children" role="group" aria-labelledby="' . esc_attr($child_group_id) . '">';
                            echo '<span id="' . esc_attr($child_group_id) . '" class="screen-reader-text">' . sprintf(__('Subcategories of %s', 'aqualuxe'), $category->name) . '</span>';
                            
                            foreach ($child_categories as $child) {
                                $child_checkbox_id = 'category-' . $child->slug;
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
                                        <span class="count" id="<?php echo esc_attr($child_checkbox_id); ?>-count">(<?php echo esc_html($child->count); ?>)</span>
                                    </label>
                                </li>
                                <?php
                            }
                            echo '</ul>';
                        }
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }

    /**
     * Render price filter.
     */
    public function render_price_filter() {
        global $wpdb;

        // Get min and max prices from products
        $min_max_prices = $wpdb->get_row("
            SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_price, MAX(CAST(meta_value AS DECIMAL(10,2))) as max_price
            FROM {$wpdb->postmeta}
            WHERE meta_key = '_price'
            AND meta_value > 0
        ");

        if (!$min_max_prices) {
            return;
        }

        $min_price = floor($min_max_prices->min_price);
        $max_price = ceil($min_max_prices->max_price);

        // Get current filter values
        $current_min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : $min_price;
        $current_max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : $max_price;

        $filter_id = 'filter-price-' . uniqid();
        $min_price_id = 'min-price-' . uniqid();
        $max_price_id = 'max-price-' . uniqid();
        ?>
        <div class="filter-widget filter-price">
            <h4 class="filter-widget-title" id="<?php echo esc_attr($filter_id); ?>" data-collapsible="true" aria-expanded="true"><?php esc_html_e('Price', 'aqualuxe'); ?></h4>
            <div class="price-slider-container" role="group" aria-labelledby="<?php echo esc_attr($filter_id); ?>">
                <div class="price-slider" 
                     data-min="<?php echo esc_attr($min_price); ?>" 
                     data-max="<?php echo esc_attr($max_price); ?>" 
                     data-current-min="<?php echo esc_attr($current_min_price); ?>" 
                     data-current-max="<?php echo esc_attr($current_max_price); ?>"
                     role="group"
                     aria-label="<?php esc_attr_e('Price range slider', 'aqualuxe'); ?>"
                     aria-describedby="price-slider-description"
                >
                </div>
                <div id="price-slider-description" class="screen-reader-text">
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
    }

    /**
     * Render attribute filters.
     */
    public function render_attribute_filters() {
        // Get filterable attributes
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        
        if (empty($attribute_taxonomies)) {
            return;
        }

        foreach ($attribute_taxonomies as $attribute) {
            $taxonomy = 'pa_' . $attribute->attribute_name;
            $terms = get_terms(array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => true,
            ));

            if (empty($terms)) {
                continue;
            }

            $filter_type = $attribute->attribute_type;
            $param_name = 'filter_' . $attribute->attribute_name;
            $current_values = isset($_GET[$param_name]) ? (array) $_GET[$param_name] : array();
            $filter_id = 'filter-' . $attribute->attribute_name . '-' . uniqid();

            ?>
            <div class="filter-widget filter-attribute filter-<?php echo esc_attr($attribute->attribute_name); ?>">
                <h4 class="filter-widget-title" id="<?php echo esc_attr($filter_id); ?>" data-collapsible="true" aria-expanded="true"><?php echo esc_html($attribute->attribute_label); ?></h4>
                
                <?php if ($filter_type === 'color') : ?>
                    <div class="filter-color-list" role="group" aria-labelledby="<?php echo esc_attr($filter_id); ?>">
                        <?php foreach ($terms as $term) : 
                            $color = get_term_meta($term->term_id, 'product_attribute_color', true);
                            if (!$color) {
                                $color = '#eeeeee';
                            }
                            $is_selected = in_array($term->slug, $current_values);
                            $checkbox_id = 'color-' . $term->slug . '-' . uniqid();
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
                <?php elseif ($filter_type === 'image') : ?>
                    <div class="filter-image-list" role="group" aria-labelledby="<?php echo esc_attr($filter_id); ?>">
                        <?php foreach ($terms as $term) : 
                            $image_id = get_term_meta($term->term_id, 'product_attribute_image', true);
                            $image_url = $image_id ? wp_get_attachment_thumb_url($image_id) : wc_placeholder_img_src('thumbnail');
                            $is_selected = in_array($term->slug, $current_values);
                            $checkbox_id = 'image-' . $term->slug . '-' . uniqid();
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
                    <ul class="filter-list" role="group" aria-labelledby="<?php echo esc_attr($filter_id); ?>">
                        <?php foreach ($terms as $term) : 
                            $is_selected = in_array($term->slug, $current_values);
                            $checkbox_id = 'term-' . $term->slug . '-' . uniqid();
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
                <input type="hidden" name="query_type_<?php echo esc_attr($attribute->attribute_name); ?>" value="and">
            </div>
            <?php
        }
    }

    /**
     * Render rating filter.
     */
    public function render_rating_filter() {
        $current_ratings = isset($_GET['rating_filter']) ? (array) $_GET['rating_filter'] : array();
        $filter_id = 'filter-rating-' . uniqid();
        ?>
        <div class="filter-widget filter-rating">
            <h4 class="filter-widget-title" id="<?php echo esc_attr($filter_id); ?>" data-collapsible="true" aria-expanded="true"><?php esc_html_e('Rating', 'aqualuxe'); ?></h4>
            <ul class="filter-list" role="group" aria-labelledby="<?php echo esc_attr($filter_id); ?>">
                <?php for ($rating = 5; $rating >= 1; $rating--) : 
                    $is_selected = in_array($rating, $current_ratings);
                    $checkbox_id = 'rating-' . $rating . '-' . uniqid();
                    
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
    }

    /**
     * Display active filters.
     */
    public function display_active_filters() {
        // Only show on product archive pages
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        // Check if filter is enabled in customizer
        if (!get_theme_mod('aqualuxe_enable_product_filter', true)) {
            return;
        }

        // Check if we have active filters
        if (!$this->has_active_filters()) {
            return;
        }

        $active_filters_id = 'active-filters-' . uniqid();
        ?>
        <div class="active-filters" role="region" aria-labelledby="<?php echo esc_attr($active_filters_id); ?>">
            <h4 class="active-filters-title" id="<?php echo esc_attr($active_filters_id); ?>"><?php esc_html_e('Active Filters', 'aqualuxe'); ?></h4>
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
     * Filter products by attributes.
     *
     * @param array    $tax_query  Tax query array.
     * @param WC_Query $wc_query   WC_Query object.
     * @return array Modified tax query.
     */
    public function filter_products($tax_query, $wc_query) {
        // Category filter
        if (isset($_GET['product_cat']) && !empty($_GET['product_cat'])) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => (array) $_GET['product_cat'],
                'operator' => 'IN',
            );
        }

        // Attribute filters
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if (!empty($attribute_taxonomies)) {
            foreach ($attribute_taxonomies as $attribute) {
                $param_name = 'filter_' . $attribute->attribute_name;
                $taxonomy = 'pa_' . $attribute->attribute_name;
                $query_type = isset($_GET['query_type_' . $attribute->attribute_name]) ? $_GET['query_type_' . $attribute->attribute_name] : 'and';
                
                if (isset($_GET[$param_name]) && !empty($_GET[$param_name])) {
                    $tax_query[] = array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'slug',
                        'terms'    => (array) $_GET[$param_name],
                        'operator' => $query_type === 'or' ? 'IN' : 'AND',
                    );
                }
            }
        }

        // Rating filter
        if (isset($_GET['rating_filter']) && !empty($_GET['rating_filter'])) {
            $rating_filter = array_filter(array_map('absint', (array) $_GET['rating_filter']));
            
            if (!empty($rating_filter)) {
                $tax_query[] = array(
                    'taxonomy'      => 'product_visibility',
                    'field'         => 'name',
                    'terms'         => array_map(array($this, 'get_product_visibility_term_name'), $rating_filter),
                    'operator'      => 'IN',
                );
            }
        }

        return $tax_query;
    }

    /**
     * Get product visibility term name for a rating.
     *
     * @param int $rating Rating.
     * @return string Term name.
     */
    private function get_product_visibility_term_name($rating) {
        return 'rated-' . $rating;
    }

    /**
     * Filter products by price.
     *
     * @param array    $meta_query Meta query array.
     * @param WC_Query $wc_query   WC_Query object.
     * @return array Modified meta query.
     */
    public function price_filter($meta_query, $wc_query) {
        if (isset($_GET['min_price']) || isset($_GET['max_price'])) {
            $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
            $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : PHP_INT_MAX;
            
            $meta_query[] = array(
                'key'     => '_price',
                'value'   => array($min_price, $max_price),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            );
        }

        return $meta_query;
    }

    /**
     * AJAX filter products.
     */
    public function ajax_filter_products() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-filter-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Get filter data
        $filter_data = isset($_POST['filter_data']) ? $_POST['filter_data'] : array();
        
        // Build query args
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => get_option('posts_per_page'),
            'paged'          => isset($filter_data['paged']) ? absint($filter_data['paged']) : 1,
        );

        // Add tax query
        $tax_query = array();
        
        // Category filter
        if (isset($filter_data['product_cat']) && !empty($filter_data['product_cat'])) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => (array) $filter_data['product_cat'],
                'operator' => 'IN',
            );
        }

        // Attribute filters
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if (!empty($attribute_taxonomies)) {
            foreach ($attribute_taxonomies as $attribute) {
                $param_name = 'filter_' . $attribute->attribute_name;
                $taxonomy = 'pa_' . $attribute->attribute_name;
                $query_type = isset($filter_data['query_type_' . $attribute->attribute_name]) ? $filter_data['query_type_' . $attribute->attribute_name] : 'and';
                
                if (isset($filter_data[$param_name]) && !empty($filter_data[$param_name])) {
                    $tax_query[] = array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'slug',
                        'terms'    => (array) $filter_data[$param_name],
                        'operator' => $query_type === 'or' ? 'IN' : 'AND',
                    );
                }
            }
        }

        // Rating filter
        if (isset($filter_data['rating_filter']) && !empty($filter_data['rating_filter'])) {
            $rating_filter = array_filter(array_map('absint', (array) $filter_data['rating_filter']));
            
            if (!empty($rating_filter)) {
                $tax_query[] = array(
                    'taxonomy'      => 'product_visibility',
                    'field'         => 'name',
                    'terms'         => array_map(array($this, 'get_product_visibility_term_name'), $rating_filter),
                    'operator'      => 'IN',
                );
            }
        }

        // Add meta query
        $meta_query = array();
        
        // Price filter
        if (isset($filter_data['min_price']) || isset($filter_data['max_price'])) {
            $min_price = isset($filter_data['min_price']) ? floatval($filter_data['min_price']) : 0;
            $max_price = isset($filter_data['max_price']) ? floatval($filter_data['max_price']) : PHP_INT_MAX;
            
            $meta_query[] = array(
                'key'     => '_price',
                'value'   => array($min_price, $max_price),
                'compare' => 'BETWEEN',
                'type'    => 'NUMERIC',
            );
        }

        // Add tax query to args
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        // Add meta query to args
        if (!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        // Add ordering
        if (isset($filter_data['orderby'])) {
            $ordering = WC()->query->get_catalog_ordering_args($filter_data['orderby']);
            $args['orderby'] = $ordering['orderby'];
            $args['order'] = $ordering['order'];
            
            if (isset($ordering['meta_key'])) {
                $args['meta_key'] = $ordering['meta_key'];
            }
        }

        // Run the query
        $query = new WP_Query($args);

        // Prepare the response
        $response = array(
            'products'      => '',
            'pagination'    => '',
            'count'         => $query->found_posts,
            'max_num_pages' => $query->max_num_pages,
        );

        // Buffer the output
        ob_start();

        if ($query->have_posts()) {
            woocommerce_product_loop_start();

            while ($query->have_posts()) {
                $query->the_post();
                wc_get_template_part('content', 'product');
            }

            woocommerce_product_loop_end();
            
            // Reset post data
            wp_reset_postdata();
        } else {
            echo '<p class="woocommerce-info">' . esc_html__('No products found.', 'aqualuxe') . '</p>';
        }

        $response['products'] = ob_get_clean();

        // Get pagination
        ob_start();
        woocommerce_pagination();
        $response['pagination'] = ob_get_clean();

        // Send response
        wp_send_json_success($response);
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue_scripts() {
        // Only enqueue on product archive pages
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        // Check if filter is enabled in customizer
        if (!get_theme_mod('aqualuxe_enable_product_filter', true)) {
            return;
        }

        // Enqueue noUiSlider for price range slider
        wp_enqueue_script('nouislider', AQUALUXE_ASSETS_URI . 'js/vendor/nouislider.min.js', array(), '14.6.3', true);
        wp_enqueue_style('nouislider', AQUALUXE_ASSETS_URI . 'css/vendor/nouislider.min.css', array(), '14.6.3');

        // Enqueue filter scripts and styles
        wp_enqueue_script('aqualuxe-product-filter', AQUALUXE_ASSETS_URI . 'js/product-filter.js', array('jquery', 'nouislider'), AQUALUXE_VERSION, true);
        wp_enqueue_style('aqualuxe-product-filter', AQUALUXE_ASSETS_URI . 'css/product-filter.css', array(), AQUALUXE_VERSION);

        // Localize script
        wp_localize_script('aqualuxe-product-filter', 'aqualuxeFilter', array(
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('aqualuxe-filter-nonce'),
            'shopUrl'   => get_permalink(wc_get_page_id('shop')),
            'isShop'    => is_shop(),
            'isMobile'  => wp_is_mobile(),
            'enableAjax' => get_theme_mod('aqualuxe_enable_ajax_filter', true),
            'i18n'      => array(
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
    }

    /**
     * Add customizer options.
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function add_customizer_options($wp_customize) {
        // Add section
        $wp_customize->add_section('aqualuxe_product_filter', array(
            'title'       => __('Product Filters', 'aqualuxe'),
            'description' => __('Customize the product filtering system.', 'aqualuxe'),
            'priority'    => 30,
            'panel'       => 'woocommerce',
        ));

        // Enable product filter
        $wp_customize->add_setting('aqualuxe_enable_product_filter', array(
            'default'           => true,
            'sanitize_callback' => 'wc_bool_to_string',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_enable_product_filter', array(
            'label'    => __('Enable Product Filter', 'aqualuxe'),
            'section'  => 'aqualuxe_product_filter',
            'type'     => 'checkbox',
            'priority' => 10,
        ));

        // Filter layout
        $wp_customize->add_setting('aqualuxe_filter_layout', array(
            'default'           => 'sidebar',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_filter_layout', array(
            'label'    => __('Filter Layout', 'aqualuxe'),
            'section'  => 'aqualuxe_product_filter',
            'type'     => 'select',
            'choices'  => array(
                'sidebar'   => __('Sidebar', 'aqualuxe'),
                'offcanvas' => __('Off-Canvas', 'aqualuxe'),
                'horizontal' => __('Horizontal', 'aqualuxe'),
            ),
            'priority' => 20,
        ));

        // Filter position
        $wp_customize->add_setting('aqualuxe_filter_position', array(
            'default'           => 'left',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_filter_position', array(
            'label'    => __('Filter Position', 'aqualuxe'),
            'section'  => 'aqualuxe_product_filter',
            'type'     => 'select',
            'choices'  => array(
                'left'  => __('Left', 'aqualuxe'),
                'right' => __('Right', 'aqualuxe'),
            ),
            'priority' => 30,
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_filter_layout', 'sidebar') === 'sidebar';
            },
        ));

        // Show filter button
        $wp_customize->add_setting('aqualuxe_show_filter_button', array(
            'default'           => true,
            'sanitize_callback' => 'wc_bool_to_string',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_show_filter_button', array(
            'label'    => __('Show Filter Button on Mobile', 'aqualuxe'),
            'section'  => 'aqualuxe_product_filter',
            'type'     => 'checkbox',
            'priority' => 40,
        ));

        // Enable AJAX filtering
        $wp_customize->add_setting('aqualuxe_enable_ajax_filter', array(
            'default'           => true,
            'sanitize_callback' => 'wc_bool_to_string',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_enable_ajax_filter', array(
            'label'    => __('Enable AJAX Filtering', 'aqualuxe'),
            'description' => __('Filter products without page reload.', 'aqualuxe'),
            'section'  => 'aqualuxe_product_filter',
            'type'     => 'checkbox',
            'priority' => 50,
        ));
        
        // Accessibility Options
        $wp_customize->add_setting('aqualuxe_filter_a11y_heading', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control(new WP_Customize_Heading_Control($wp_customize, 'aqualuxe_filter_a11y_heading', array(
            'label'    => __('Accessibility Options', 'aqualuxe'),
            'section'  => 'aqualuxe_product_filter',
            'priority' => 60,
        )));

        // High contrast mode
        $wp_customize->add_setting('aqualuxe_filter_high_contrast', array(
            'default'           => false,
            'sanitize_callback' => 'wc_bool_to_string',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_filter_high_contrast', array(
            'label'    => __('Enable High Contrast Mode', 'aqualuxe'),
            'description' => __('Increases contrast for better visibility.', 'aqualuxe'),
            'section'  => 'aqualuxe_product_filter',
            'type'     => 'checkbox',
            'priority' => 70,
        ));

        // Larger text
        $wp_customize->add_setting('aqualuxe_filter_larger_text', array(
            'default'           => false,
            'sanitize_callback' => 'wc_bool_to_string',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_filter_larger_text', array(
            'label'    => __('Enable Larger Text', 'aqualuxe'),
            'description' => __('Increases text size for better readability.', 'aqualuxe'),
            'section'  => 'aqualuxe_product_filter',
            'type'     => 'checkbox',
            'priority' => 80,
        ));

        // Focus indicator style
        $wp_customize->add_setting('aqualuxe_filter_focus_style', array(
            'default'           => 'default',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));

        $wp_customize->add_control('aqualuxe_filter_focus_style', array(
            'label'    => __('Focus Indicator Style', 'aqualuxe'),
            'section'  => 'aqualuxe_product_filter',
            'type'     => 'select',
            'choices'  => array(
                'default' => __('Default', 'aqualuxe'),
                'bold'    => __('Bold', 'aqualuxe'),
                'outline' => __('Outline', 'aqualuxe'),
            ),
            'priority' => 90,
        ));
    }
}

// Initialize the class
AquaLuxe_Product_Filter::instance();

// Add custom Customizer control for headings
if (!class_exists('WP_Customize_Heading_Control')) {
    /**
     * Custom Customizer control for headings.
     */
    class WP_Customize_Heading_Control extends WP_Customize_Control {
        /**
         * Control type.
         *
         * @var string
         */
        public $type = 'heading';

        /**
         * Render the control's content.
         */
        public function render_content() {
            if (!empty($this->label)) {
                echo '<h3 style="margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">' . esc_html($this->label) . '</h3>';
            }
            if (!empty($this->description)) {
                echo '<span class="description customize-control-description">' . esc_html($this->description) . '</span>';
            }
        }
    }
}