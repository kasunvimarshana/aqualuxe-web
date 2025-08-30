<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package AquaLuxe
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    add_theme_support(
        'woocommerce',
        array(
            'thumbnail_image_width' => 350,
            'single_image_width'    => 800,
            'product_grid'          => array(
                'default_rows'    => 3,
                'min_rows'        => 1,
                'default_columns' => 4,
                'min_columns'     => 1,
                'max_columns'     => 6,
            ),
        )
    );
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    wp_enqueue_style('aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), AQUALUXE_VERSION);

    $font_path   = WC()->plugin_url() . '/assets/fonts/';
    $inline_font = '@font-face {
            font-family: "star";
            src: url("' . $font_path . 'star.eot");
            src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
                url("' . $font_path . 'star.woff") format("woff"),
                url("' . $font_path . 'star.ttf") format("truetype"),
                url("' . $font_path . 'star.svg#star") format("svg");
            font-weight: normal;
            font-style: normal;
        }';

    wp_add_inline_style('aqualuxe-woocommerce-style', $inline_font);
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function aqualuxe_woocommerce_active_body_class($classes) {
    $classes[] = 'woocommerce-active';

    return $classes;
}
add_filter('body_class', 'aqualuxe_woocommerce_active_body_class');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function aqualuxe_woocommerce_related_products_args($args) {
    $defaults = array(
        'posts_per_page' => 4,
        'columns'        => 4,
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

if (!function_exists('aqualuxe_woocommerce_wrapper_before')) {
    /**
     * Before Content.
     *
     * Wraps all WooCommerce content in wrappers which match the theme markup.
     *
     * @return void
     */
    function aqualuxe_woocommerce_wrapper_before() {
        ?>
        <main id="primary" class="site-main container mx-auto px-4 py-8">
        <?php
    }
}
add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before');

if (!function_exists('aqualuxe_woocommerce_wrapper_after')) {
    /**
     * After Content.
     *
     * Closes the wrapping divs.
     *
     * @return void
     */
    function aqualuxe_woocommerce_wrapper_after() {
        ?>
        </main><!-- #main -->
        <?php
    }
}
add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after');

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 * <?php
 * if ( function_exists( 'aqualuxe_woocommerce_header_cart' ) ) {
 * aqualuxe_woocommerce_header_cart();
 * }
 * ?>
 */

if (!function_exists('aqualuxe_woocommerce_cart_link_fragment')) {
    /**
     * Cart Fragments.
     *
     * Ensure cart contents update when products are added to the cart via AJAX.
     *
     * @param array $fragments Fragments to refresh via AJAX.
     * @return array Fragments to refresh via AJAX.
     */
    function aqualuxe_woocommerce_cart_link_fragment($fragments) {
        ob_start();
        aqualuxe_woocommerce_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment');

if (!function_exists('aqualuxe_woocommerce_cart_link')) {
    /**
     * Cart Link.
     *
     * Displayed a link to the cart including the number of items present and the cart total.
     *
     * @return void
     */
    function aqualuxe_woocommerce_cart_link() {
        ?>
        <a class="cart-contents relative inline-flex items-center text-dark dark:text-light hover:text-primary dark:hover:text-secondary-light transition-colors duration-200" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
            <?php echo aqualuxe_get_svg('cart'); ?>
            <span class="count absolute -top-1 -right-1 w-5 h-5 flex items-center justify-center bg-primary text-white text-xs rounded-full">
                <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
            </span>
        </a>
        <?php
    }
}

if (!function_exists('aqualuxe_woocommerce_header_cart')) {
    /**
     * Display Header Cart.
     *
     * @return void
     */
    function aqualuxe_woocommerce_header_cart() {
        if (is_cart()) {
            $class = 'current-menu-item';
        } else {
            $class = '';
        }
        ?>
        <div id="site-header-cart" class="site-header-cart relative ml-4">
            <div class="<?php echo esc_attr($class); ?>">
                <?php aqualuxe_woocommerce_cart_link(); ?>
            </div>
            <div class="cart-dropdown absolute right-0 top-full mt-2 w-80 bg-white dark:bg-dark-light rounded-lg shadow-elegant z-50 hidden group-hover:block">
                <?php
                $instance = array(
                    'title' => '',
                );

                the_widget('WC_Widget_Cart', $instance);
                ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Modify WooCommerce opening/closing product container tags
 */
function aqualuxe_woocommerce_open_product_container() {
    echo '<div class="product-card group relative overflow-hidden bg-white dark:bg-dark-light rounded-lg shadow-soft transition-shadow duration-300 hover:shadow-elegant">';
}
add_action('woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_open_product_container', 5);

function aqualuxe_woocommerce_close_product_container() {
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_close_product_container', 15);

/**
 * Modify WooCommerce product image wrapper
 */
function aqualuxe_woocommerce_open_image_wrapper() {
    echo '<div class="product-image-container relative overflow-hidden">';
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_open_image_wrapper', 5);

function aqualuxe_woocommerce_close_image_wrapper() {
    // Quick view button
    if (get_theme_mod('aqualuxe_enable_quick_view', true)) {
        echo '<div class="product-quick-actions absolute inset-0 bg-dark bg-opacity-40 flex items-center justify-center opacity-0 transition-opacity duration-300 group-hover:opacity-100">';
        echo '<button type="button" class="quick-view-btn btn-primary text-sm" data-product-id="' . get_the_ID() . '">' . esc_html__('Quick View', 'aqualuxe') . '</button>';
        echo '</div>';
    }
    echo '</div>';
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_close_image_wrapper', 15);

/**
 * Modify WooCommerce product title
 */
function aqualuxe_woocommerce_product_title() {
    echo '<h2 class="product-title text-lg font-medium text-dark dark:text-light mt-4 transition-colors duration-200 group-hover:text-primary dark:group-hover:text-secondary-light">' . get_the_title() . '</h2>';
}
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title');
add_action('woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_product_title');

/**
 * Modify WooCommerce product price
 */
function aqualuxe_woocommerce_product_price() {
    global $product;
    echo '<div class="product-price text-lg font-bold text-primary dark:text-secondary-light">' . $product->get_price_html() . '</div>';
}
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');
add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_product_price');

/**
 * Modify WooCommerce add to cart button
 */
function aqualuxe_woocommerce_loop_add_to_cart_link($html, $product) {
    return sprintf(
        '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
        esc_url($product->add_to_cart_url()),
        esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
        esc_attr(isset($args['class']) ? $args['class'] . ' btn btn-primary mt-4' : 'btn btn-primary mt-4'),
        isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
        esc_html($product->add_to_cart_text())
    );
}
add_filter('woocommerce_loop_add_to_cart_link', 'aqualuxe_woocommerce_loop_add_to_cart_link', 10, 2);

/**
 * Modify WooCommerce pagination
 */
function aqualuxe_woocommerce_pagination() {
    echo '<nav class="woocommerce-pagination mt-8">';
    echo paginate_links(
        array(
            'prev_text' => '<span aria-hidden="true">&laquo;</span><span class="sr-only">' . esc_html__('Previous', 'aqualuxe') . '</span>',
            'next_text' => '<span aria-hidden="true">&raquo;</span><span class="sr-only">' . esc_html__('Next', 'aqualuxe') . '</span>',
            'type'      => 'list',
            'end_size'  => 3,
            'mid_size'  => 3,
        )
    );
    echo '</nav>';
}
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination');
add_action('woocommerce_after_shop_loop', 'aqualuxe_woocommerce_pagination');

/**
 * Modify WooCommerce breadcrumb
 */
function aqualuxe_woocommerce_breadcrumb_defaults($args) {
    $args['delimiter'] = '<span class="mx-2">/</span>';
    $args['wrap_before'] = '<nav class="woocommerce-breadcrumb text-sm text-dark-light dark:text-light-dark mb-6" itemprop="breadcrumb">';
    $args['wrap_after'] = '</nav>';
    return $args;
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults');

/**
 * Modify WooCommerce result count
 */
function aqualuxe_woocommerce_result_count() {
    echo '<p class="woocommerce-result-count text-sm text-dark-light dark:text-light-dark">' . woocommerce_result_count() . '</p>';
}
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_result_count', 20);

/**
 * Modify WooCommerce ordering
 */
function aqualuxe_woocommerce_catalog_ordering() {
    echo '<div class="woocommerce-ordering">';
    woocommerce_catalog_ordering();
    echo '</div>';
}
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_catalog_ordering', 30);

/**
 * Add wishlist button
 */
function aqualuxe_add_wishlist_button() {
    if (get_theme_mod('aqualuxe_enable_wishlist', true)) {
        global $product;
        echo '<button type="button" class="wishlist-btn absolute top-2 right-2 w-8 h-8 flex items-center justify-center bg-white dark:bg-dark-light rounded-full shadow-soft z-10 text-dark-light dark:text-light-dark hover:text-primary dark:hover:text-secondary-light transition-colors duration-200" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo aqualuxe_get_svg('heart');
        echo '</button>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_add_wishlist_button', 10);

/**
 * Add quick view modal
 */
function aqualuxe_quick_view_modal() {
    if (get_theme_mod('aqualuxe_enable_quick_view', true)) {
        ?>
        <div id="quick-view-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden" x-data="quickView" x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="absolute inset-0 bg-dark bg-opacity-75" @click="close"></div>
            <div class="relative bg-white dark:bg-dark-light rounded-lg shadow-elegant max-w-4xl w-full max-h-[90vh] overflow-y-auto" @click.away="close">
                <button type="button" class="absolute top-4 right-4 text-dark-light dark:text-light-dark hover:text-primary dark:hover:text-secondary-light transition-colors duration-200" @click="close">
                    <?php echo aqualuxe_get_svg('close'); ?>
                </button>
                <div class="p-6">
                    <div x-show="loading" class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary dark:border-secondary"></div>
                    </div>
                    <div x-show="!loading && productData" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="product-image">
                            <img :src="productData.images[0].src" :alt="productData.name" class="w-full h-auto rounded-lg">
                        </div>
                        <div class="product-details">
                            <h2 class="text-2xl font-serif font-bold text-dark dark:text-light mb-2" x-text="productData.name"></h2>
                            <div class="price text-xl font-bold text-primary dark:text-secondary-light mb-4" x-html="productData.price_html"></div>
                            <div class="description prose dark:prose-invert mb-6" x-html="productData.short_description"></div>
                            <a :href="productData.permalink" class="btn btn-primary">
                                <?php echo esc_html__('View Product', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_quick_view_modal');

/**
 * AJAX handler for quick view
 */
function aqualuxe_ajax_quick_view() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-nonce')) {
        wp_send_json_error('Invalid request');
    }

    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error('Product not found');
    }

    $data = array(
        'id'               => $product->get_id(),
        'name'             => $product->get_name(),
        'price_html'       => $product->get_price_html(),
        'short_description' => $product->get_short_description(),
        'permalink'        => get_permalink($product_id),
        'images'           => array(),
    );

    $attachment_ids = $product->get_gallery_image_ids();
    array_unshift($attachment_ids, $product->get_image_id());

    foreach ($attachment_ids as $attachment_id) {
        $data['images'][] = array(
            'id'  => $attachment_id,
            'src' => wp_get_attachment_image_url($attachment_id, 'large'),
        );
    }

    wp_send_json_success($data);
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view');

/**
 * Add product category class to product loop items
 */
function aqualuxe_woocommerce_product_cats_class($classes, $product) {
    $terms = get_the_terms($product->get_id(), 'product_cat');
    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $classes[] = 'product-cat-' . $term->slug;
        }
    }
    return $classes;
}
add_filter('woocommerce_post_class', 'aqualuxe_woocommerce_product_cats_class', 10, 2);

/**
 * Modify number of products per row
 */
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod('aqualuxe_products_per_row', 3);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Add custom product tabs
 */
function aqualuxe_woocommerce_product_tabs($tabs) {
    // Add shipping tab
    $tabs['shipping'] = array(
        'title'    => __('Shipping & Returns', 'aqualuxe'),
        'priority' => 30,
        'callback' => 'aqualuxe_woocommerce_shipping_tab_content',
    );

    // Add care tab for fish products
    global $product;
    $terms = get_the_terms($product->get_id(), 'fish_species');
    if ($terms && !is_wp_error($terms)) {
        $tabs['care'] = array(
            'title'    => __('Care Guide', 'aqualuxe'),
            'priority' => 40,
            'callback' => 'aqualuxe_woocommerce_care_tab_content',
        );
    }

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs');

/**
 * Shipping tab content
 */
function aqualuxe_woocommerce_shipping_tab_content() {
    echo '<h3>' . esc_html__('Shipping Information', 'aqualuxe') . '</h3>';
    echo '<p>' . esc_html__('We ship our products worldwide with special care for live fish and aquatic plants. Shipping times and costs vary depending on your location and the items ordered.', 'aqualuxe') . '</p>';
    
    echo '<h3>' . esc_html__('Returns Policy', 'aqualuxe') . '</h3>';
    echo '<p>' . esc_html__('For non-living products, we offer a 30-day return policy. Living organisms (fish, plants, invertebrates) are covered by our DOA (Dead on Arrival) guarantee - please contact us within 2 hours of receiving your order with photos if there are any issues.', 'aqualuxe') . '</p>';
}

/**
 * Care guide tab content
 */
function aqualuxe_woocommerce_care_tab_content() {
    global $product;
    
    // Get product meta for care guide
    $water_temperature = get_post_meta($product->get_id(), '_water_temperature', true);
    $ph_level = get_post_meta($product->get_id(), '_ph_level', true);
    $tank_size = get_post_meta($product->get_id(), '_tank_size', true);
    $diet = get_post_meta($product->get_id(), '_diet', true);
    $care_level = get_post_meta($product->get_id(), '_care_level', true);
    
    echo '<h3>' . esc_html__('Care Requirements', 'aqualuxe') . '</h3>';
    
    echo '<div class="care-guide-grid grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">';
    
    if ($water_temperature) {
        echo '<div class="care-item">';
        echo '<h4 class="font-bold">' . esc_html__('Water Temperature', 'aqualuxe') . '</h4>';
        echo '<p>' . esc_html($water_temperature) . '</p>';
        echo '</div>';
    }
    
    if ($ph_level) {
        echo '<div class="care-item">';
        echo '<h4 class="font-bold">' . esc_html__('pH Level', 'aqualuxe') . '</h4>';
        echo '<p>' . esc_html($ph_level) . '</p>';
        echo '</div>';
    }
    
    if ($tank_size) {
        echo '<div class="care-item">';
        echo '<h4 class="font-bold">' . esc_html__('Minimum Tank Size', 'aqualuxe') . '</h4>';
        echo '<p>' . esc_html($tank_size) . '</p>';
        echo '</div>';
    }
    
    if ($diet) {
        echo '<div class="care-item">';
        echo '<h4 class="font-bold">' . esc_html__('Diet', 'aqualuxe') . '</h4>';
        echo '<p>' . esc_html($diet) . '</p>';
        echo '</div>';
    }
    
    if ($care_level) {
        echo '<div class="care-item">';
        echo '<h4 class="font-bold">' . esc_html__('Care Level', 'aqualuxe') . '</h4>';
        echo '<p>' . esc_html($care_level) . '</p>';
        echo '</div>';
    }
    
    echo '</div>';
    
    echo '<h3>' . esc_html__('General Care Tips', 'aqualuxe') . '</h3>';
    echo '<ul class="list-disc pl-5 space-y-2">';
    echo '<li>' . esc_html__('Perform regular water changes (20-30% weekly)', 'aqualuxe') . '</li>';
    echo '<li>' . esc_html__('Test water parameters regularly', 'aqualuxe') . '</li>';
    echo '<li>' . esc_html__('Ensure proper filtration and aeration', 'aqualuxe') . '</li>';
    echo '<li>' . esc_html__('Feed appropriate amounts (what can be consumed in 2-3 minutes)', 'aqualuxe') . '</li>';
    echo '<li>' . esc_html__('Maintain consistent water temperature', 'aqualuxe') . '</li>';
    echo '</ul>';
}

/**
 * Add custom product meta fields
 */
function aqualuxe_woocommerce_product_custom_fields() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    
    // Water Temperature
    woocommerce_wp_text_input(
        array(
            'id'          => '_water_temperature',
            'label'       => __('Water Temperature', 'aqualuxe'),
            'placeholder' => '72-78°F (22-26°C)',
            'desc_tip'    => 'true',
            'description' => __('Enter the recommended water temperature range.', 'aqualuxe')
        )
    );
    
    // pH Level
    woocommerce_wp_text_input(
        array(
            'id'          => '_ph_level',
            'label'       => __('pH Level', 'aqualuxe'),
            'placeholder' => '6.5-7.5',
            'desc_tip'    => 'true',
            'description' => __('Enter the recommended pH range.', 'aqualuxe')
        )
    );
    
    // Tank Size
    woocommerce_wp_text_input(
        array(
            'id'          => '_tank_size',
            'label'       => __('Minimum Tank Size', 'aqualuxe'),
            'placeholder' => '10 gallons',
            'desc_tip'    => 'true',
            'description' => __('Enter the minimum recommended tank size.', 'aqualuxe')
        )
    );
    
    // Diet
    woocommerce_wp_text_input(
        array(
            'id'          => '_diet',
            'label'       => __('Diet', 'aqualuxe'),
            'placeholder' => 'Omnivore',
            'desc_tip'    => 'true',
            'description' => __('Enter the diet type.', 'aqualuxe')
        )
    );
    
    // Care Level
    woocommerce_wp_select(
        array(
            'id'          => '_care_level',
            'label'       => __('Care Level', 'aqualuxe'),
            'options'     => array(
                ''        => __('Select care level', 'aqualuxe'),
                'Easy'    => __('Easy', 'aqualuxe'),
                'Moderate' => __('Moderate', 'aqualuxe'),
                'Advanced' => __('Advanced', 'aqualuxe'),
                'Expert'  => __('Expert', 'aqualuxe')
            ),
            'desc_tip'    => 'true',
            'description' => __('Select the care difficulty level.', 'aqualuxe')
        )
    );
    
    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'aqualuxe_woocommerce_product_custom_fields');

/**
 * Save custom product meta fields
 */
function aqualuxe_woocommerce_product_custom_fields_save($post_id) {
    // Water Temperature
    $water_temperature = isset($_POST['_water_temperature']) ? sanitize_text_field($_POST['_water_temperature']) : '';
    update_post_meta($post_id, '_water_temperature', $water_temperature);
    
    // pH Level
    $ph_level = isset($_POST['_ph_level']) ? sanitize_text_field($_POST['_ph_level']) : '';
    update_post_meta($post_id, '_ph_level', $ph_level);
    
    // Tank Size
    $tank_size = isset($_POST['_tank_size']) ? sanitize_text_field($_POST['_tank_size']) : '';
    update_post_meta($post_id, '_tank_size', $tank_size);
    
    // Diet
    $diet = isset($_POST['_diet']) ? sanitize_text_field($_POST['_diet']) : '';
    update_post_meta($post_id, '_diet', $diet);
    
    // Care Level
    $care_level = isset($_POST['_care_level']) ? sanitize_text_field($_POST['_care_level']) : '';
    update_post_meta($post_id, '_care_level', $care_level);
}
add_action('woocommerce_process_product_meta', 'aqualuxe_woocommerce_product_custom_fields_save');

/**
 * Add wholesale price field
 */
function aqualuxe_woocommerce_wholesale_price_field() {
    global $woocommerce, $post;
    
    echo '<div class="options_group pricing">';
    
    woocommerce_wp_text_input(
        array(
            'id'          => '_wholesale_price',
            'label'       => __('Wholesale Price', 'aqualuxe') . ' (' . get_woocommerce_currency_symbol() . ')',
            'desc_tip'    => 'true',
            'description' => __('Enter the wholesale price for this product.', 'aqualuxe'),
            'type'        => 'number',
            'custom_attributes' => array(
                'step' => 'any',
                'min'  => '0'
            )
        )
    );
    
    echo '</div>';
}
add_action('woocommerce_product_options_pricing', 'aqualuxe_woocommerce_wholesale_price_field');

/**
 * Save wholesale price field
 */
function aqualuxe_woocommerce_wholesale_price_save($post_id) {
    $wholesale_price = isset($_POST['_wholesale_price']) ? wc_format_decimal($_POST['_wholesale_price']) : '';
    update_post_meta($post_id, '_wholesale_price', $wholesale_price);
}
add_action('woocommerce_process_product_meta', 'aqualuxe_woocommerce_wholesale_price_save');

/**
 * Add export information fields
 */
function aqualuxe_woocommerce_export_fields() {
    global $woocommerce, $post;
    
    echo '<div class="panel woocommerce_options_panel">';
    echo '<div class="options_group">';
    
    woocommerce_wp_checkbox(
        array(
            'id'          => '_export_ready',
            'label'       => __('Export Ready', 'aqualuxe'),
            'description' => __('Check if this product is ready for export.', 'aqualuxe')
        )
    );
    
    woocommerce_wp_text_input(
        array(
            'id'          => '_export_code',
            'label'       => __('Export Code', 'aqualuxe'),
            'desc_tip'    => 'true',
            'description' => __('Enter the export classification code.', 'aqualuxe')
        )
    );
    
    woocommerce_wp_textarea_input(
        array(
            'id'          => '_export_restrictions',
            'label'       => __('Export Restrictions', 'aqualuxe'),
            'desc_tip'    => 'true',
            'description' => __('Enter any export restrictions or requirements.', 'aqualuxe')
        )
    );
    
    echo '</div>';
    echo '</div>';
}
add_action('woocommerce_product_data_panels', 'aqualuxe_woocommerce_export_fields');

/**
 * Add export tab to product data tabs
 */
function aqualuxe_woocommerce_export_tab($tabs) {
    $tabs['export'] = array(
        'label'    => __('Export', 'aqualuxe'),
        'target'   => 'export_product_data',
        'class'    => array(),
        'priority' => 80,
    );
    return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'aqualuxe_woocommerce_export_tab');

/**
 * Save export fields
 */
function aqualuxe_woocommerce_export_fields_save($post_id) {
    $export_ready = isset($_POST['_export_ready']) ? 'yes' : 'no';
    update_post_meta($post_id, '_export_ready', $export_ready);
    
    $export_code = isset($_POST['_export_code']) ? sanitize_text_field($_POST['_export_code']) : '';
    update_post_meta($post_id, '_export_code', $export_code);
    
    $export_restrictions = isset($_POST['_export_restrictions']) ? sanitize_textarea_field($_POST['_export_restrictions']) : '';
    update_post_meta($post_id, '_export_restrictions', $export_restrictions);
}
add_action('woocommerce_process_product_meta', 'aqualuxe_woocommerce_export_fields_save');

/**
 * Display export information on product page
 */
function aqualuxe_woocommerce_export_info() {
    global $product;
    
    $export_ready = get_post_meta($product->get_id(), '_export_ready', true);
    
    if ($export_ready === 'yes') {
        echo '<div class="export-info mt-4 p-4 bg-light-dark dark:bg-dark-lighter rounded-lg">';
        echo '<h3 class="text-lg font-bold mb-2">' . esc_html__('Export Information', 'aqualuxe') . '</h3>';
        
        echo '<div class="flex items-center mb-2">';
        echo '<span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>';
        echo '<span>' . esc_html__('This product is available for international shipping', 'aqualuxe') . '</span>';
        echo '</div>';
        
        $export_restrictions = get_post_meta($product->get_id(), '_export_restrictions', true);
        if ($export_restrictions) {
            echo '<div class="text-sm mt-2">';
            echo '<strong>' . esc_html__('Restrictions:', 'aqualuxe') . '</strong> ';
            echo esc_html($export_restrictions);
            echo '</div>';
        }
        
        echo '<a href="#" class="inline-block mt-2 text-primary dark:text-secondary-light hover:underline">' . esc_html__('View Export Documentation', 'aqualuxe') . '</a>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_export_info', 35);

/**
 * Add wholesale inquiry form
 */
function aqualuxe_woocommerce_wholesale_inquiry() {
    global $product;
    
    if (is_product() && $product->is_type('simple')) {
        echo '<div class="wholesale-inquiry mt-8 p-6 bg-light-dark dark:bg-dark-lighter rounded-lg">';
        echo '<h3 class="text-xl font-serif font-bold mb-4">' . esc_html__('Wholesale Inquiry', 'aqualuxe') . '</h3>';
        echo '<p class="mb-4">' . esc_html__('Interested in wholesale pricing? Fill out the form below for bulk order quotes.', 'aqualuxe') . '</p>';
        
        echo '<form class="wholesale-form" method="post">';
        echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">';
        
        echo '<div>';
        echo '<label for="wholesale-name" class="form-label">' . esc_html__('Business Name', 'aqualuxe') . ' <span class="required">*</span></label>';
        echo '<input type="text" id="wholesale-name" name="wholesale-name" class="form-input" required>';
        echo '</div>';
        
        echo '<div>';
        echo '<label for="wholesale-email" class="form-label">' . esc_html__('Email Address', 'aqualuxe') . ' <span class="required">*</span></label>';
        echo '<input type="email" id="wholesale-email" name="wholesale-email" class="form-input" required>';
        echo '</div>';
        
        echo '<div>';
        echo '<label for="wholesale-quantity" class="form-label">' . esc_html__('Quantity Needed', 'aqualuxe') . ' <span class="required">*</span></label>';
        echo '<input type="number" id="wholesale-quantity" name="wholesale-quantity" class="form-input" min="1" required>';
        echo '</div>';
        
        echo '<div>';
        echo '<label for="wholesale-location" class="form-label">' . esc_html__('Business Location', 'aqualuxe') . ' <span class="required">*</span></label>';
        echo '<input type="text" id="wholesale-location" name="wholesale-location" class="form-input" required>';
        echo '</div>';
        
        echo '</div>';
        
        echo '<div class="mb-4">';
        echo '<label for="wholesale-message" class="form-label">' . esc_html__('Additional Information', 'aqualuxe') . '</label>';
        echo '<textarea id="wholesale-message" name="wholesale-message" rows="4" class="form-input"></textarea>';
        echo '</div>';
        
        echo '<input type="hidden" name="product-id" value="' . esc_attr($product->get_id()) . '">';
        echo '<input type="hidden" name="product-name" value="' . esc_attr($product->get_name()) . '">';
        
        echo '<button type="submit" class="btn btn-primary">' . esc_html__('Submit Inquiry', 'aqualuxe') . '</button>';
        echo '</form>';
        
        echo '</div>';
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_wholesale_inquiry', 15);

/**
 * Add trade-in section
 */
function aqualuxe_woocommerce_trade_in_section() {
    global $product;
    
    if (is_product() && has_term('fish', 'product_cat')) {
        echo '<div class="trade-in-section mt-8 p-6 bg-light-dark dark:bg-dark-lighter rounded-lg">';
        echo '<h3 class="text-xl font-serif font-bold mb-4">' . esc_html__('Trade-In Program', 'aqualuxe') . '</h3>';
        echo '<p class="mb-4">' . esc_html__('Have fish or aquatic plants to trade? Our trade-in program allows you to exchange your healthy aquatic life for store credit or discounts on new purchases.', 'aqualuxe') . '</p>';
        
        echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">';
        
        echo '<div>';
        echo '<h4 class="font-bold mb-2">' . esc_html__('How It Works', 'aqualuxe') . '</h4>';
        echo '<ol class="list-decimal pl-5 space-y-1">';
        echo '<li>' . esc_html__('Contact us with details about what you have', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('We\'ll evaluate and make an offer', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Bring your fish/plants to our store', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Receive store credit or discount', 'aqualuxe') . '</li>';
        echo '</ol>';
        echo '</div>';
        
        echo '<div>';
        echo '<h4 class="font-bold mb-2">' . esc_html__('Requirements', 'aqualuxe') . '</h4>';
        echo '<ul class="list-disc pl-5 space-y-1">';
        echo '<li>' . esc_html__('Fish/plants must be healthy and disease-free', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Minimum size requirements may apply', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Proper transport containers required', 'aqualuxe') . '</li>';
        echo '<li>' . esc_html__('Exotic species must have proper documentation', 'aqualuxe') . '</li>';
        echo '</ul>';
        echo '</div>';
        
        echo '</div>';
        
        echo '<a href="/trade-in-program" class="btn btn-secondary">' . esc_html__('Learn More About Trade-Ins', 'aqualuxe') . '</a>';
        echo '</div>';
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_trade_in_section', 20);

/**
 * Add product specifications table
 */
function aqualuxe_woocommerce_product_specs() {
    global $product;
    
    $attributes = $product->get_attributes();
    
    if (!empty($attributes)) {
        echo '<div class="product-specifications mt-8">';
        echo '<h3 class="text-xl font-serif font-bold mb-4">' . esc_html__('Product Specifications', 'aqualuxe') . '</h3>';
        
        echo '<div class="overflow-x-auto">';
        echo '<table class="w-full border-collapse">';
        
        echo '<thead>';
        echo '<tr class="bg-light-dark dark:bg-dark-lighter">';
        echo '<th class="p-3 text-left">' . esc_html__('Specification', 'aqualuxe') . '</th>';
        echo '<th class="p-3 text-left">' . esc_html__('Value', 'aqualuxe') . '</th>';
        echo '</tr>';
        echo '</thead>';
        
        echo '<tbody>';
        
        foreach ($attributes as $attribute) {
            if ($attribute->get_visible()) {
                echo '<tr class="border-b border-light-darker dark:border-dark-lighter">';
                echo '<td class="p-3 font-medium">' . wc_attribute_label($attribute->get_name()) . '</td>';
                
                if ($attribute->is_taxonomy()) {
                    $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'));
                    echo '<td class="p-3">' . apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values) . '</td>';
                } else {
                    $values = $attribute->get_options();
                    echo '<td class="p-3">' . apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values) . '</td>';
                }
                
                echo '</tr>';
            }
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        
        echo '</div>';
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_specs', 25);

/**
 * Add related services section
 */
function aqualuxe_woocommerce_related_services() {
    global $product;
    
    // Only show for certain product categories
    $show_services = false;
    $categories = array('aquariums', 'fish', 'plants');
    
    foreach ($categories as $category) {
        if (has_term($category, 'product_cat')) {
            $show_services = true;
            break;
        }
    }
    
    if ($show_services) {
        // Get services
        $args = array(
            'post_type'      => 'aqualuxe_service',
            'posts_per_page' => 3,
            'orderby'        => 'rand',
        );
        
        $services = new WP_Query($args);
        
        if ($services->have_posts()) {
            echo '<div class="related-services mt-12">';
            echo '<h3 class="text-2xl font-serif font-bold mb-6">' . esc_html__('Related Services', 'aqualuxe') . '</h3>';
            
            echo '<div class="grid grid-cols-1 md:grid-cols-3 gap-6">';
            
            while ($services->have_posts()) {
                $services->the_post();
                
                echo '<div class="service-card bg-white dark:bg-dark-light rounded-lg overflow-hidden shadow-soft transition-shadow duration-300 hover:shadow-elegant">';
                
                if (has_post_thumbnail()) {
                    echo '<div class="service-image overflow-hidden">';
                    echo '<a href="' . esc_url(get_permalink()) . '">';
                    the_post_thumbnail('aqualuxe-card', array('class' => 'w-full h-48 object-cover transition-transform duration-300 hover:scale-105'));
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<div class="service-content p-6">';
                echo '<h4 class="text-xl font-bold mb-2"><a href="' . esc_url(get_permalink()) . '" class="text-dark dark:text-light hover:text-primary dark:hover:text-secondary-light transition-colors duration-200">' . get_the_title() . '</a></h4>';
                echo '<div class="text-dark-light dark:text-light-dark mb-4">' . get_the_excerpt() . '</div>';
                echo '<a href="' . esc_url(get_permalink()) . '" class="btn btn-outline">' . esc_html__('Learn More', 'aqualuxe') . '</a>';
                echo '</div>';
                
                echo '</div>';
            }
            
            wp_reset_postdata();
            
            echo '</div>';
            echo '</div>';
        }
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_related_services', 30);

/**
 * Add product video section
 */
function aqualuxe_woocommerce_product_video() {
    global $product;
    
    $video_url = get_post_meta($product->get_id(), '_product_video_url', true);
    
    if ($video_url) {
        echo '<div class="product-video mt-8">';
        echo '<h3 class="text-xl font-serif font-bold mb-4">' . esc_html__('Product Video', 'aqualuxe') . '</h3>';
        
        // Check if it's a YouTube URL
        if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
            // Extract YouTube ID
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches);
            
            if (isset($matches[1])) {
                $youtube_id = $matches[1];
                
                echo '<div class="aspect-w-16 aspect-h-9">';
                echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($youtube_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="rounded-lg"></iframe>';
                echo '</div>';
            }
        } else {
            echo '<video controls class="w-full rounded-lg">';
            echo '<source src="' . esc_url($video_url) . '" type="video/mp4">';
            echo esc_html__('Your browser does not support the video tag.', 'aqualuxe');
            echo '</video>';
        }
        
        echo '</div>';
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_video', 35);

/**
 * Add product video URL field
 */
function aqualuxe_woocommerce_product_video_field() {
    global $woocommerce, $post;
    
    echo '<div class="options_group">';
    
    woocommerce_wp_text_input(
        array(
            'id'          => '_product_video_url',
            'label'       => __('Product Video URL', 'aqualuxe'),
            'placeholder' => 'https://www.youtube.com/watch?v=...',
            'desc_tip'    => 'true',
            'description' => __('Enter a YouTube URL or direct video file URL.', 'aqualuxe')
        )
    );
    
    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'aqualuxe_woocommerce_product_video_field');

/**
 * Save product video URL field
 */
function aqualuxe_woocommerce_product_video_save($post_id) {
    $video_url = isset($_POST['_product_video_url']) ? esc_url_raw($_POST['_product_video_url']) : '';
    update_post_meta($post_id, '_product_video_url', $video_url);
}
add_action('woocommerce_process_product_meta', 'aqualuxe_woocommerce_product_video_save');

/**
 * Add product FAQ section
 */
function aqualuxe_woocommerce_product_faq() {
    global $product;
    
    $faqs = get_post_meta($product->get_id(), '_product_faqs', true);
    
    if (!empty($faqs) && is_array($faqs)) {
        echo '<div class="product-faq mt-8" x-data="{ activeTab: null }">';
        echo '<h3 class="text-xl font-serif font-bold mb-4">' . esc_html__('Frequently Asked Questions', 'aqualuxe') . '</h3>';
        
        echo '<div class="space-y-2">';
        
        foreach ($faqs as $index => $faq) {
            if (!empty($faq['question']) && !empty($faq['answer'])) {
                echo '<div class="faq-item border border-light-darker dark:border-dark-lighter rounded-lg overflow-hidden">';
                
                echo '<button @click="activeTab = activeTab === ' . $index . ' ? null : ' . $index . '" class="w-full flex items-center justify-between p-4 text-left font-medium focus:outline-none">';
                echo '<span>' . esc_html($faq['question']) . '</span>';
                echo '<svg :class="{ \'rotate-180\': activeTab === ' . $index . ' }" class="w-5 h-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
                echo '</button>';
                
                echo '<div x-show="activeTab === ' . $index . '" x-collapse>';
                echo '<div class="p-4 pt-0 prose dark:prose-invert">';
                echo wp_kses_post(wpautop($faq['answer']));
                echo '</div>';
                echo '</div>';
                
                echo '</div>';
            }
        }
        
        echo '</div>';
        echo '</div>';
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_faq', 40);

/**
 * Add product FAQ fields
 */
function aqualuxe_woocommerce_product_faq_fields() {
    global $woocommerce, $post;
    
    echo '<div class="panel woocommerce_options_panel" id="faq_product_data">';
    echo '<div class="options_group">';
    
    $faqs = get_post_meta($post->ID, '_product_faqs', true);
    
    if (empty($faqs) || !is_array($faqs)) {
        $faqs = array(
            array('question' => '', 'answer' => '')
        );
    }
    
    echo '<div id="product_faqs_container">';
    
    foreach ($faqs as $index => $faq) {
        echo '<div class="product_faq" style="padding: 10px; border-bottom: 1px solid #eee; margin-bottom: 10px;">';
        
        woocommerce_wp_text_input(
            array(
                'id'          => '_product_faq_question_' . $index,
                'label'       => __('Question', 'aqualuxe'),
                'value'       => $faq['question'],
                'desc_tip'    => 'true',
                'description' => __('Enter the FAQ question.', 'aqualuxe')
            )
        );
        
        woocommerce_wp_textarea_input(
            array(
                'id'          => '_product_faq_answer_' . $index,
                'label'       => __('Answer', 'aqualuxe'),
                'value'       => $faq['answer'],
                'desc_tip'    => 'true',
                'description' => __('Enter the FAQ answer.', 'aqualuxe')
            )
        );
        
        echo '<p><button type="button" class="button remove_faq">' . __('Remove', 'aqualuxe') . '</button></p>';
        echo '</div>';
    }
    
    echo '</div>';
    
    echo '<p><button type="button" class="button add_faq">' . __('Add FAQ', 'aqualuxe') . '</button></p>';
    
    echo '</div>';
    echo '</div>';
    
    // Add JavaScript for dynamic FAQ fields
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Add FAQ
            $('.add_faq').on('click', function() {
                var index = $('.product_faq').length;
                var html = '<div class="product_faq" style="padding: 10px; border-bottom: 1px solid #eee; margin-bottom: 10px;">';
                
                html += '<p class="form-field _product_faq_question_' + index + '_field">';
                html += '<label for="_product_faq_question_' + index + '">Question</label>';
                html += '<input type="text" class="short" name="_product_faq_question_' + index + '" id="_product_faq_question_' + index + '" value="" placeholder="">';
                html += '</p>';
                
                html += '<p class="form-field _product_faq_answer_' + index + '_field">';
                html += '<label for="_product_faq_answer_' + index + '">Answer</label>';
                html += '<textarea class="short" name="_product_faq_answer_' + index + '" id="_product_faq_answer_' + index + '" placeholder=""></textarea>';
                html += '</p>';
                
                html += '<p><button type="button" class="button remove_faq">Remove</button></p>';
                html += '</div>';
                
                $('#product_faqs_container').append(html);
            });
            
            // Remove FAQ
            $('#product_faqs_container').on('click', '.remove_faq', function() {
                $(this).closest('.product_faq').remove();
            });
        });
    </script>
    <?php
}
add_action('woocommerce_product_data_panels', 'aqualuxe_woocommerce_product_faq_fields');

/**
 * Add FAQ tab to product data tabs
 */
function aqualuxe_woocommerce_faq_tab($tabs) {
    $tabs['faq'] = array(
        'label'    => __('FAQs', 'aqualuxe'),
        'target'   => 'faq_product_data',
        'class'    => array(),
        'priority' => 70,
    );
    return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'aqualuxe_woocommerce_faq_tab');

/**
 * Save product FAQ fields
 */
function aqualuxe_woocommerce_product_faq_save($post_id) {
    $faqs = array();
    $index = 0;
    
    while (isset($_POST['_product_faq_question_' . $index])) {
        $question = sanitize_text_field($_POST['_product_faq_question_' . $index]);
        $answer = isset($_POST['_product_faq_answer_' . $index]) ? wp_kses_post($_POST['_product_faq_answer_' . $index]) : '';
        
        if (!empty($question) && !empty($answer)) {
            $faqs[] = array(
                'question' => $question,
                'answer'   => $answer
            );
        }
        
        $index++;
    }
    
    update_post_meta($post_id, '_product_faqs', $faqs);
}
add_action('woocommerce_process_product_meta', 'aqualuxe_woocommerce_product_faq_save');