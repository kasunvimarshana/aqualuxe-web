<?php
/**
 * WooCommerce setup functions
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * WooCommerce setup function.
 *
 * @return void
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 800,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 6,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ),
    ));

    // Add support for WooCommerce features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Register WooCommerce sidebars
    register_sidebar(array(
        'name'          => __('Shop Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-shop',
        'description'   => __('Widgets in this area will be shown on shop pages.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => __('Product Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-product',
        'description'   => __('Widgets in this area will be shown on single product pages.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
    // Enqueue WooCommerce styles
    wp_enqueue_style('aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/dist/css/woocommerce.css', array(), AQUALUXE_VERSION);

    // Enqueue WooCommerce scripts
    wp_enqueue_script('aqualuxe-woocommerce-script', get_template_directory_uri() . '/assets/dist/js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);

    // Add variables to script
    $woocommerce_vars = array(
        'ajax_url'                => admin_url('admin-ajax.php'),
        'woocommerce_ajax_url'    => WC_AJAX::get_endpoint('%%endpoint%%'),
        'cart_url'                => wc_get_cart_url(),
        'checkout_url'            => wc_get_checkout_url(),
        'is_cart'                 => is_cart(),
        'is_checkout'             => is_checkout(),
        'cart_redirect_after_add' => get_option('woocommerce_cart_redirect_after_add'),
        'i18n_view_cart'          => esc_html__('View cart', 'aqualuxe'),
        'i18n_add_to_cart'        => esc_html__('Add to cart', 'aqualuxe'),
        'i18n_added_to_cart'      => esc_html__('Added to cart', 'aqualuxe'),
        'i18n_remove_from_cart'   => esc_html__('Remove from cart', 'aqualuxe'),
    );

    wp_localize_script('aqualuxe-woocommerce-script', 'aqualuxe_woocommerce', $woocommerce_vars);
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet.
 *
 * @return void
 */
function aqualuxe_dequeue_woocommerce_styles() {
    // If we're not using the default WooCommerce stylesheet
    if (get_theme_mod('woocommerce_disable_default_styles', true)) {
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    }
}
add_action('init', 'aqualuxe_dequeue_woocommerce_styles');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function aqualuxe_related_products_args($args) {
    $defaults = array(
        'posts_per_page' => get_theme_mod('woocommerce_related_products_count', 4),
        'columns'        => get_theme_mod('woocommerce_related_products_columns', 4),
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_related_products_args');

/**
 * Product gallery thumbnail columns.
 *
 * @return integer number of columns.
 */
function aqualuxe_woocommerce_thumbnail_columns() {
    return get_theme_mod('woocommerce_gallery_thumbnail_columns', 4);
}
add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_thumbnail_columns');

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod('woocommerce_products_per_row', 4);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Products per page.
 *
 * @return integer products per page.
 */
function aqualuxe_woocommerce_products_per_page() {
    return get_theme_mod('woocommerce_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function aqualuxe_woocommerce_active_body_class($classes) {
    $classes[] = 'woocommerce-active';

    return $classes;
}
add_filter('body_class', 'aqualuxe_woocommerce_active_body_class');

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
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_link_fragment');

/**
 * Cart Link.
 *
 * Displayed a link to the cart including the number of items present and the cart total.
 *
 * @return void
 */
function aqualuxe_woocommerce_cart_link() {
    ?>
    <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'aqualuxe'); ?>">
        <span class="cart-contents__icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
            </svg>
        </span>
        <span class="cart-contents__count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span>
        <span class="cart-contents__total"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span>
    </a>
    <?php
}

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
    <div class="site-header-cart">
        <div class="<?php echo esc_attr($class); ?>">
            <?php aqualuxe_woocommerce_cart_link(); ?>
        </div>
        <div class="site-header-cart__dropdown">
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

/**
 * Add WooCommerce header cart to header actions.
 */
function aqualuxe_add_header_cart() {
    if (get_theme_mod('woocommerce_header_cart', true)) {
        aqualuxe_woocommerce_header_cart();
    }
}
add_action('aqualuxe_header_actions', 'aqualuxe_add_header_cart', 30);

/**
 * Customize WooCommerce breadcrumbs.
 *
 * @return array
 */
function aqualuxe_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => '<span class="breadcrumb-separator">' . esc_html(get_theme_mod('breadcrumbs_separator', '/')) . '</span>',
        'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">',
        'wrap_after'  => '</nav>',
        'before'      => '<span>',
        'after'       => '</span>',
        'home'        => _x('Home', 'breadcrumb', 'aqualuxe'),
    );
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumbs');

/**
 * Add quantity buttons to the product quantity input.
 *
 * @param string $html The quantity input HTML.
 * @param array  $args The quantity input arguments.
 * @return string
 */
function aqualuxe_quantity_input_field($html, $args) {
    if ($args['min_value'] < $args['max_value']) {
        $html = '<div class="quantity-wrapper">';
        $html .= '<button type="button" class="quantity-button quantity-down">-</button>';
        $html .= $html;
        $html .= '<button type="button" class="quantity-button quantity-up">+</button>';
        $html .= '</div>';
    }
    
    return $html;
}
add_filter('woocommerce_quantity_input_args', 'aqualuxe_quantity_input_field', 10, 2);

/**
 * Add 'View Product' button to product loops.
 */
function aqualuxe_view_product_button() {
    global $product;

    // Only show on variable products
    if ($product->is_type('variable')) {
        echo '<a href="' . esc_url($product->get_permalink()) . '" class="button view-product">' . esc_html__('View Product', 'aqualuxe') . '</a>';
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_view_product_button', 15);

/**
 * Customize sale flash.
 *
 * @param string $html The sale flash HTML.
 * @param object $post The post object.
 * @param object $product The product object.
 * @return string
 */
function aqualuxe_sale_flash($html, $post, $product) {
    $sale_style = get_theme_mod('woocommerce_sale_badge_style', 'circle');
    $sale_text = get_theme_mod('woocommerce_sale_badge_text', __('Sale!', 'aqualuxe'));

    if ($product->is_on_sale()) {
        if ($sale_style === 'percentage' && $product->get_type() !== 'variable') {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();

            if ($regular_price && $sale_price) {
                $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
                $sale_text = sprintf(__('%s%%', 'aqualuxe'), $percentage);
            }
        }

        $html = '<span class="onsale onsale--' . esc_attr($sale_style) . '">' . esc_html($sale_text) . '</span>';
    }

    return $html;
}
add_filter('woocommerce_sale_flash', 'aqualuxe_sale_flash', 10, 3);

/**
 * Add product categories to shop loop items.
 */
function aqualuxe_show_product_categories() {
    if (get_theme_mod('woocommerce_show_categories', true)) {
        global $product;
        $categories = wc_get_product_category_list($product->get_id(), ', ');

        if ($categories) {
            echo '<div class="product-categories">' . $categories . '</div>';
        }
    }
}
add_action('woocommerce_shop_loop_item_title', 'aqualuxe_show_product_categories', 5);

/**
 * Add product rating to shop loop items.
 */
function aqualuxe_show_product_rating() {
    if (get_theme_mod('woocommerce_show_rating', true)) {
        global $product;
        if ($product->get_rating_count() > 0) {
            echo wc_get_rating_html($product->get_average_rating());
        } else {
            echo '<div class="star-rating"></div>';
        }
    }
}
add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_show_product_rating', 5);

/**
 * Add product short description to shop loop items.
 */
function aqualuxe_show_product_excerpt() {
    if (get_theme_mod('woocommerce_show_excerpt', false)) {
        global $product;
        $short_description = $product->get_short_description();

        if ($short_description) {
            echo '<div class="product-excerpt">' . wp_kses_post($short_description) . '</div>';
        }
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_show_product_excerpt', 5);

/**
 * Add quick view button to shop loop items.
 */
function aqualuxe_quick_view_button() {
    if (get_theme_mod('woocommerce_quick_view', true)) {
        global $product;
        echo '<a href="#" class="button quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_quick_view_button', 20);

/**
 * Add wishlist button to shop loop items.
 */
function aqualuxe_wishlist_button() {
    if (get_theme_mod('woocommerce_wishlist', true) && function_exists('aqualuxe_add_to_wishlist_button')) {
        aqualuxe_add_to_wishlist_button();
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_wishlist_button', 25);

/**
 * Add compare button to shop loop items.
 */
function aqualuxe_compare_button() {
    if (get_theme_mod('woocommerce_compare', true) && function_exists('aqualuxe_add_to_compare_button')) {
        aqualuxe_add_to_compare_button();
    }
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_compare_button', 30);

/**
 * Add product tabs.
 *
 * @param array $tabs The product tabs.
 * @return array
 */
function aqualuxe_product_tabs($tabs) {
    // Add custom tabs
    if (get_theme_mod('woocommerce_custom_tab', false)) {
        $tabs['custom_tab'] = array(
            'title'    => get_theme_mod('woocommerce_custom_tab_title', __('Custom Tab', 'aqualuxe')),
            'priority' => 50,
            'callback' => 'aqualuxe_custom_tab_content',
        );
    }

    // Reorder tabs
    $tabs['description']['priority'] = 10;
    $tabs['additional_information']['priority'] = 20;
    $tabs['reviews']['priority'] = 30;

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_product_tabs');

/**
 * Custom tab content.
 */
function aqualuxe_custom_tab_content() {
    echo wp_kses_post(get_theme_mod('woocommerce_custom_tab_content', ''));
}

/**
 * Add product meta.
 */
function aqualuxe_product_meta() {
    global $product;

    echo '<div class="product-meta">';

    // SKU
    if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) {
        echo '<span class="product-meta__sku">' . esc_html__('SKU:', 'aqualuxe') . ' <span>' . esc_html($product->get_sku()) . '</span></span>';
    }

    // Categories
    echo '<span class="product-meta__categories">' . wc_get_product_category_list($product->get_id(), ', ', '<span>' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'aqualuxe') . ' ', '</span>') . '</span>';

    // Tags
    echo '<span class="product-meta__tags">' . wc_get_product_tag_list($product->get_id(), ', ', '<span>' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'aqualuxe') . ' ', '</span>') . '</span>';

    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'aqualuxe_product_meta', 40);

/**
 * Add product share buttons.
 */
function aqualuxe_product_share() {
    if (get_theme_mod('woocommerce_product_share', true)) {
        global $product;

        $share_url = get_permalink();
        $share_title = get_the_title();
        $share_image = wp_get_attachment_url($product->get_image_id());

        echo '<div class="product-share">';
        echo '<h4>' . esc_html__('Share:', 'aqualuxe') . '</h4>';
        echo '<ul class="product-share__list">';
        echo '<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url($share_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on Facebook', 'aqualuxe') . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a></li>';
        echo '<li><a href="https://twitter.com/intent/tweet?text=' . esc_attr($share_title) . '&url=' . esc_url($share_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on Twitter', 'aqualuxe') . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a></li>';
        echo '<li><a href="https://pinterest.com/pin/create/button/?url=' . esc_url($share_url) . '&media=' . esc_url($share_image) . '&description=' . esc_attr($share_title) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on Pinterest', 'aqualuxe') . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg></a></li>';
        echo '<li><a href="mailto:?subject=' . esc_attr($share_title) . '&body=' . esc_url($share_url) . '" aria-label="' . esc_attr__('Share via Email', 'aqualuxe') . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" /><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" /></svg></a></li>';
        echo '</ul>';
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_product_share', 50);

/**
 * Add product navigation.
 */
function aqualuxe_product_navigation() {
    if (get_theme_mod('woocommerce_product_navigation', true)) {
        $prev_post = get_previous_post();
        $next_post = get_next_post();

        echo '<div class="product-navigation">';
        
        if ($prev_post) {
            $prev_thumb = get_the_post_thumbnail($prev_post->ID, array(50, 50));
            echo '<div class="product-navigation__prev">';
            echo '<a href="' . esc_url(get_permalink($prev_post->ID)) . '" rel="prev">';
            echo '<span class="product-navigation__arrow">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 010-1.06l7.5-7.5a.75.75 0 111.06 1.06L9.31 12l6.97 6.97a.75.75 0 11-1.06 1.06l-7.5-7.5z" clip-rule="evenodd" /></svg>';
            echo '</span>';
            if ($prev_thumb) {
                echo '<span class="product-navigation__thumb">' . $prev_thumb . '</span>';
            }
            echo '<span class="product-navigation__title">' . esc_html(get_the_title($prev_post->ID)) . '</span>';
            echo '</a>';
            echo '</div>';
        }

        echo '<div class="product-navigation__catalog">';
        echo '<a href="' . esc_url(wc_get_page_permalink('shop')) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M3 6a3 3 0 013-3h2.25a3 3 0 013 3v2.25a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm9.75 0a3 3 0 013-3H18a3 3 0 013 3v2.25a3 3 0 01-3 3h-2.25a3 3 0 01-3-3V6zM3 15.75a3 3 0 013-3h2.25a3 3 0 013 3V18a3 3 0 01-3 3H6a3 3 0 01-3-3v-2.25zm9.75 0a3 3 0 013-3H18a3 3 0 013 3V18a3 3 0 01-3 3h-2.25a3 3 0 01-3-3v-2.25z" clip-rule="evenodd" /></svg>';
        echo '</a>';
        echo '</div>';

        if ($next_post) {
            $next_thumb = get_the_post_thumbnail($next_post->ID, array(50, 50));
            echo '<div class="product-navigation__next">';
            echo '<a href="' . esc_url(get_permalink($next_post->ID)) . '" rel="next">';
            echo '<span class="product-navigation__title">' . esc_html(get_the_title($next_post->ID)) . '</span>';
            if ($next_thumb) {
                echo '<span class="product-navigation__thumb">' . $next_thumb . '</span>';
            }
            echo '<span class="product-navigation__arrow">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 011.06-1.06l7.5 7.5z" clip-rule="evenodd" /></svg>';
            echo '</span>';
            echo '</a>';
            echo '</div>';
        }

        echo '</div>';
    }
}
add_action('woocommerce_before_single_product_summary', 'aqualuxe_product_navigation', 5);

/**
 * Add product countdown.
 */
function aqualuxe_product_countdown() {
    global $product;

    if (get_theme_mod('woocommerce_product_countdown', true) && $product->is_on_sale()) {
        $sale_price_dates_to = get_post_meta($product->get_id(), '_sale_price_dates_to', true);

        if ($sale_price_dates_to) {
            $now = current_time('timestamp');
            $sale_end = $sale_price_dates_to;

            if ($now < $sale_end) {
                echo '<div class="product-countdown" data-end-time="' . esc_attr($sale_end) . '">';
                echo '<div class="product-countdown__title">' . esc_html__('Sale Ends In:', 'aqualuxe') . '</div>';
                echo '<div class="product-countdown__timer">';
                echo '<div class="product-countdown__days"><span class="product-countdown__value">00</span><span class="product-countdown__label">' . esc_html__('Days', 'aqualuxe') . '</span></div>';
                echo '<div class="product-countdown__hours"><span class="product-countdown__value">00</span><span class="product-countdown__label">' . esc_html__('Hours', 'aqualuxe') . '</span></div>';
                echo '<div class="product-countdown__minutes"><span class="product-countdown__value">00</span><span class="product-countdown__label">' . esc_html__('Minutes', 'aqualuxe') . '</span></div>';
                echo '<div class="product-countdown__seconds"><span class="product-countdown__value">00</span><span class="product-countdown__label">' . esc_html__('Seconds', 'aqualuxe') . '</span></div>';
                echo '</div>';
                echo '</div>';
            }
        }
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_product_countdown', 15);

/**
 * Add product trust badges.
 */
function aqualuxe_product_trust_badges() {
    if (get_theme_mod('woocommerce_trust_badges', true)) {
        $badges = get_theme_mod('woocommerce_trust_badges_content', '');

        if ($badges) {
            echo '<div class="product-trust-badges">';
            echo wp_kses_post($badges);
            echo '</div>';
        }
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_product_trust_badges', 55);

/**
 * Add recently viewed products.
 */
function aqualuxe_recently_viewed_products() {
    if (get_theme_mod('woocommerce_recently_viewed', true) && !is_cart() && !is_checkout()) {
        $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
        $viewed_products = array_filter(array_map('absint', $viewed_products));

        if (empty($viewed_products)) {
            return;
        }

        $title = get_theme_mod('woocommerce_recently_viewed_title', __('Recently Viewed Products', 'aqualuxe'));
        $count = get_theme_mod('woocommerce_recently_viewed_count', 4);

        $args = array(
            'posts_per_page' => $count,
            'no_found_rows'  => 1,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'post__in'       => $viewed_products,
            'orderby'        => 'post__in',
        );

        $products = new WP_Query($args);

        if ($products->have_posts()) {
            echo '<section class="recently-viewed-products">';
            echo '<h2>' . esc_html($title) . '</h2>';
            echo '<ul class="products columns-' . esc_attr($count) . '">';

            while ($products->have_posts()) {
                $products->the_post();
                wc_get_template_part('content', 'product');
            }

            echo '</ul>';
            echo '</section>';

            wp_reset_postdata();
        }
    }
}
add_action('woocommerce_after_single_product', 'aqualuxe_recently_viewed_products', 20);

/**
 * Track product views.
 */
function aqualuxe_track_product_view() {
    if (!is_singular('product')) {
        return;
    }

    global $post;

    if (empty($_COOKIE['woocommerce_recently_viewed'])) {
        $viewed_products = array();
    } else {
        $viewed_products = (array) explode('|', $_COOKIE['woocommerce_recently_viewed']);
    }

    if (!in_array($post->ID, $viewed_products)) {
        $viewed_products[] = $post->ID;
    }

    if (sizeof($viewed_products) > 15) {
        array_shift($viewed_products);
    }

    // Store for session only
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}
add_action('template_redirect', 'aqualuxe_track_product_view', 20);

/**
 * Add size guide button.
 */
function aqualuxe_size_guide_button() {
    if (get_theme_mod('woocommerce_size_guide', true)) {
        global $product;

        // Check if product has size guide
        $size_guide = get_post_meta($product->get_id(), '_aqualuxe_size_guide', true);

        if ($size_guide) {
            echo '<a href="#" class="size-guide-button" data-open="size-guide-modal">' . esc_html__('Size Guide', 'aqualuxe') . '</a>';
            
            // Add size guide modal
            add_action('wp_footer', 'aqualuxe_size_guide_modal');
        }
    }
}
add_action('woocommerce_before_add_to_cart_form', 'aqualuxe_size_guide_button', 10);

/**
 * Add size guide modal.
 */
function aqualuxe_size_guide_modal() {
    global $product;

    // Check if product has size guide
    $size_guide = get_post_meta($product->get_id(), '_aqualuxe_size_guide', true);

    if ($size_guide) {
        echo '<div id="size-guide-modal" class="modal" aria-hidden="true">';
        echo '<div class="modal__overlay" tabindex="-1" data-close></div>';
        echo '<div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="size-guide-title">';
        echo '<div class="modal__header">';
        echo '<h2 id="size-guide-title" class="modal__title">' . esc_html__('Size Guide', 'aqualuxe') . '</h2>';
        echo '<button class="modal__close" aria-label="' . esc_attr__('Close', 'aqualuxe') . '" data-close>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>';
        echo '</button>';
        echo '</div>';
        echo '<div class="modal__content">';
        echo wp_kses_post($size_guide);
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Add delivery information.
 */
function aqualuxe_delivery_information() {
    if (get_theme_mod('woocommerce_delivery_info', true)) {
        $delivery_info = get_theme_mod('woocommerce_delivery_info_content', '');

        if ($delivery_info) {
            echo '<div class="delivery-information">';
            echo wp_kses_post($delivery_info);
            echo '</div>';
        }
    }
}
add_action('woocommerce_after_add_to_cart_form', 'aqualuxe_delivery_information', 10);

/**
 * Add stock progress bar.
 */
function aqualuxe_stock_progress_bar() {
    if (get_theme_mod('woocommerce_stock_progress', true)) {
        global $product;

        if ($product->managing_stock()) {
            $stock_quantity = $product->get_stock_quantity();
            $low_stock_amount = get_option('woocommerce_notify_low_stock_amount', 2);
            $progress = 0;

            if ($stock_quantity > 0) {
                // Calculate progress based on stock level
                $total_stock = max($stock_quantity, get_post_meta($product->get_id(), '_aqualuxe_total_stock', true));
                
                if ($total_stock > 0) {
                    $progress = min(100, max(0, ($stock_quantity / $total_stock) * 100));
                }

                echo '<div class="stock-progress">';
                echo '<div class="stock-progress__bar">';
                echo '<span class="stock-progress__fill" style="width: ' . esc_attr($progress) . '%"></span>';
                echo '</div>';
                
                if ($stock_quantity <= $low_stock_amount) {
                    echo '<div class="stock-progress__text stock-progress__text--low">' . esc_html__('Hurry! Only', 'aqualuxe') . ' ' . esc_html($stock_quantity) . ' ' . esc_html(_n('item', 'items', $stock_quantity, 'aqualuxe')) . ' ' . esc_html__('left in stock!', 'aqualuxe') . '</div>';
                } else {
                    echo '<div class="stock-progress__text">' . esc_html($stock_quantity) . ' ' . esc_html(_n('item', 'items', $stock_quantity, 'aqualuxe')) . ' ' . esc_html__('in stock', 'aqualuxe') . '</div>';
                }
                
                echo '</div>';
            }
        }
    }
}
add_action('woocommerce_after_add_to_cart_form', 'aqualuxe_stock_progress_bar', 5);

/**
 * Customize add to cart button text.
 *
 * @param string $text The button text.
 * @param object $product The product object.
 * @return string
 */
function aqualuxe_add_to_cart_text($text, $product) {
    if (get_theme_mod('woocommerce_custom_add_to_cart_text', false)) {
        $custom_text = get_theme_mod('woocommerce_add_to_cart_text', '');
        
        if ($custom_text) {
            return esc_html($custom_text);
        }
    }
    
    return $text;
}
add_filter('woocommerce_product_add_to_cart_text', 'aqualuxe_add_to_cart_text', 10, 2);
add_filter('woocommerce_product_single_add_to_cart_text', 'aqualuxe_add_to_cart_text', 10, 2);

/**
 * Add AJAX add to cart functionality.
 */
function aqualuxe_ajax_add_to_cart_script() {
    if (get_theme_mod('woocommerce_ajax_add_to_cart', true)) {
        wp_enqueue_script('aqualuxe-ajax-add-to-cart', get_template_directory_uri() . '/assets/dist/js/ajax-add-to-cart.js', array('jquery'), AQUALUXE_VERSION, true);
        
        wp_localize_script('aqualuxe-ajax-add-to-cart', 'aqualuxe_ajax_add_to_cart', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-ajax-add-to-cart'),
            'i18n_added_to_cart' => esc_html__('Added to cart', 'aqualuxe'),
            'i18n_error' => esc_html__('Error adding to cart', 'aqualuxe'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_ajax_add_to_cart_script');

/**
 * AJAX add to cart handler.
 */
function aqualuxe_ajax_add_to_cart() {
    check_ajax_referer('aqualuxe-ajax-add-to-cart', 'nonce');

    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? wc_stock_amount($_POST['quantity']) : 1;
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    $variations = isset($_POST['variations']) ? (array) $_POST['variations'] : array();

    $product_status = get_post_status($product_id);

    if (!$product_id || 'publish' !== $product_status) {
        wp_send_json_error();
        exit;
    }

    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations);

    if ($passed_validation) {
        if (WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations)) {
            do_action('woocommerce_ajax_added_to_cart', $product_id);

            if (get_option('woocommerce_cart_redirect_after_add') === 'yes') {
                wp_send_json_success(array(
                    'redirect' => wc_get_cart_url(),
                ));
            } else {
                $fragments = apply_filters('woocommerce_add_to_cart_fragments', array());
                $cart_count = WC()->cart->get_cart_contents_count();
                $cart_total = WC()->cart->get_cart_subtotal();

                wp_send_json_success(array(
                    'fragments' => $fragments,
                    'cart_count' => $cart_count,
                    'cart_total' => $cart_total,
                ));
            }
        } else {
            wp_send_json_error();
        }
    } else {
        wp_send_json_error();
    }

    exit;
}
add_action('wp_ajax_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart');
add_action('wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', 'aqualuxe_ajax_add_to_cart');

/**
 * Add mini cart.
 */
function aqualuxe_mini_cart() {
    if (get_theme_mod('woocommerce_mini_cart', true)) {
        ?>
        <div class="mini-cart">
            <div class="mini-cart__overlay"></div>
            <div class="mini-cart__content">
                <div class="mini-cart__header">
                    <h3 class="mini-cart__title"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></h3>
                    <button class="mini-cart__close" aria-label="<?php esc_attr_e('Close cart', 'aqualuxe'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
                <div class="mini-cart__body">
                    <div class="widget_shopping_cart_content"></div>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_mini_cart');

/**
 * Add quick view modal.
 */
function aqualuxe_quick_view_modal() {
    if (get_theme_mod('woocommerce_quick_view', true)) {
        ?>
        <div id="quick-view-modal" class="modal quick-view-modal" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1" data-close></div>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="quick-view-title">
                <div class="modal__header">
                    <h2 id="quick-view-title" class="modal__title"><?php esc_html_e('Quick View', 'aqualuxe'); ?></h2>
                    <button class="modal__close" aria-label="<?php esc_attr_e('Close', 'aqualuxe'); ?>" data-close>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
                <div class="modal__content">
                    <div class="quick-view-content"></div>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_quick_view_modal');

/**
 * AJAX quick view handler.
 */
function aqualuxe_ajax_quick_view() {
    check_ajax_referer('aqualuxe-quick-view', 'nonce');

    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

    if (!$product_id) {
        wp_send_json_error();
        exit;
    }

    $product = wc_get_product($product_id);

    if (!$product) {
        wp_send_json_error();
        exit;
    }

    ob_start();
    ?>
    <div class="quick-view-product">
        <div class="quick-view-product__images">
            <?php
            $attachment_ids = $product->get_gallery_image_ids();
            $featured_image = $product->get_image_id();

            if ($featured_image) {
                echo wp_get_attachment_image($featured_image, 'medium_large', false, array('class' => 'quick-view-product__image'));
            } else {
                echo wc_placeholder_img('medium_large');
            }

            if ($attachment_ids) {
                echo '<div class="quick-view-product__gallery">';
                foreach ($attachment_ids as $attachment_id) {
                    echo wp_get_attachment_image($attachment_id, 'thumbnail', false, array('class' => 'quick-view-product__gallery-image'));
                }
                echo '</div>';
            }
            ?>
        </div>
        <div class="quick-view-product__details">
            <h2 class="quick-view-product__title"><?php echo esc_html($product->get_name()); ?></h2>
            <div class="quick-view-product__price"><?php echo $product->get_price_html(); ?></div>
            <div class="quick-view-product__rating">
                <?php
                if ($product->get_rating_count() > 0) {
                    echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
                    echo '<span class="quick-view-product__rating-count">(' . esc_html($product->get_rating_count()) . ')</span>';
                }
                ?>
            </div>
            <div class="quick-view-product__description">
                <?php echo wp_kses_post($product->get_short_description()); ?>
            </div>
            <div class="quick-view-product__add-to-cart">
                <?php
                if ($product->is_type('simple')) {
                    echo '<form class="cart" action="' . esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())) . '" method="post" enctype="multipart/form-data">';
                    
                    if ($product->is_in_stock()) {
                        do_action('woocommerce_before_add_to_cart_button');
                        
                        echo woocommerce_quantity_input(array(
                            'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                            'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                            'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
                        ), $product, false);
                        
                        echo '<button type="submit" name="add-to-cart" value="' . esc_attr($product->get_id()) . '" class="single_add_to_cart_button button alt">' . esc_html($product->single_add_to_cart_text()) . '</button>';
                        
                        do_action('woocommerce_after_add_to_cart_button');
                    } else {
                        echo '<p class="stock out-of-stock">' . esc_html__('Out of stock', 'aqualuxe') . '</p>';
                    }
                    
                    echo '</form>';
                } else {
                    echo '<a href="' . esc_url($product->get_permalink()) . '" class="button">' . esc_html__('View Product', 'aqualuxe') . '</a>';
                }
                ?>
            </div>
            <div class="quick-view-product__meta">
                <?php
                if ($product->get_sku()) {
                    echo '<span class="quick-view-product__sku">' . esc_html__('SKU:', 'aqualuxe') . ' ' . esc_html($product->get_sku()) . '</span>';
                }
                
                echo '<span class="quick-view-product__categories">' . wc_get_product_category_list($product->get_id(), ', ', '<span>' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'aqualuxe') . ' ', '</span>') . '</span>';
                ?>
            </div>
            <div class="quick-view-product__actions">
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="button button-secondary"><?php esc_html_e('View Full Details', 'aqualuxe'); ?></a>
            </div>
        </div>
    </div>
    <?php
    $content = ob_get_clean();

    wp_send_json_success(array(
        'content' => $content,
    ));

    exit;
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_ajax_quick_view');

/**
 * Add quick view script.
 */
function aqualuxe_quick_view_script() {
    if (get_theme_mod('woocommerce_quick_view', true)) {
        wp_enqueue_script('aqualuxe-quick-view', get_template_directory_uri() . '/assets/dist/js/quick-view.js', array('jquery'), AQUALUXE_VERSION, true);
        
        wp_localize_script('aqualuxe-quick-view', 'aqualuxe_quick_view', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-quick-view'),
            'i18n_loading' => esc_html__('Loading...', 'aqualuxe'),
            'i18n_error' => esc_html__('Error loading product', 'aqualuxe'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_quick_view_script');

/**
 * Add wishlist functionality.
 */
function aqualuxe_wishlist_script() {
    if (get_theme_mod('woocommerce_wishlist', true)) {
        wp_enqueue_script('aqualuxe-wishlist', get_template_directory_uri() . '/assets/dist/js/wishlist.js', array('jquery'), AQUALUXE_VERSION, true);
        
        wp_localize_script('aqualuxe-wishlist', 'aqualuxe_wishlist', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-wishlist'),
            'i18n_add_to_wishlist' => esc_html__('Add to Wishlist', 'aqualuxe'),
            'i18n_added_to_wishlist' => esc_html__('Added to Wishlist', 'aqualuxe'),
            'i18n_remove_from_wishlist' => esc_html__('Remove from Wishlist', 'aqualuxe'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_wishlist_script');

/**
 * Add to wishlist button.
 */
function aqualuxe_add_to_wishlist_button() {
    global $product;
    
    $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? explode(',', $_COOKIE['aqualuxe_wishlist']) : array();
    $in_wishlist = in_array($product->get_id(), $wishlist);
    
    echo '<a href="#" class="button wishlist-button' . ($in_wishlist ? ' added' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" /></svg>';
    echo '<span>' . ($in_wishlist ? esc_html__('Remove from Wishlist', 'aqualuxe') : esc_html__('Add to Wishlist', 'aqualuxe')) . '</span>';
    echo '</a>';
}

/**
 * AJAX wishlist handler.
 */
function aqualuxe_ajax_wishlist() {
    check_ajax_referer('aqualuxe-wishlist', 'nonce');

    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

    if (!$product_id) {
        wp_send_json_error();
        exit;
    }

    $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? explode(',', $_COOKIE['aqualuxe_wishlist']) : array();
    
    if (in_array($product_id, $wishlist)) {
        // Remove from wishlist
        $wishlist = array_diff($wishlist, array($product_id));
        $action = 'removed';
    } else {
        // Add to wishlist
        $wishlist[] = $product_id;
        $action = 'added';
    }
    
    // Filter out empty values and make unique
    $wishlist = array_unique(array_filter($wishlist));
    
    // Set cookie
    setcookie('aqualuxe_wishlist', implode(',', $wishlist), time() + (86400 * 30), '/'); // 30 days
    
    wp_send_json_success(array(
        'action' => $action,
        'count' => count($wishlist),
    ));

    exit;
}
add_action('wp_ajax_aqualuxe_wishlist', 'aqualuxe_ajax_wishlist');
add_action('wp_ajax_nopriv_aqualuxe_wishlist', 'aqualuxe_ajax_wishlist');

/**
 * Add wishlist page.
 */
function aqualuxe_create_wishlist_page() {
    if (get_theme_mod('woocommerce_wishlist', true)) {
        $wishlist_page = get_page_by_path('wishlist');
        
        if (!$wishlist_page) {
            $page_id = wp_insert_post(array(
                'post_title'     => __('Wishlist', 'aqualuxe'),
                'post_content'   => '[aqualuxe_wishlist]',
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'comment_status' => 'closed',
            ));
            
            if ($page_id) {
                update_option('aqualuxe_wishlist_page_id', $page_id);
            }
        }
    }
}
add_action('after_switch_theme', 'aqualuxe_create_wishlist_page');

/**
 * Wishlist shortcode.
 */
function aqualuxe_wishlist_shortcode() {
    $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? explode(',', $_COOKIE['aqualuxe_wishlist']) : array();
    $wishlist = array_filter($wishlist);
    
    ob_start();
    
    if (!empty($wishlist)) {
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post__in'       => $wishlist,
            'orderby'        => 'post__in',
        );
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            ?>
            <div class="wishlist-products">
                <table class="wishlist-table">
                    <thead>
                        <tr>
                            <th class="product-remove">&nbsp;</th>
                            <th class="product-thumbnail">&nbsp;</th>
                            <th class="product-name"><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                            <th class="product-price"><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                            <th class="product-stock"><?php esc_html_e('Stock', 'aqualuxe'); ?></th>
                            <th class="product-actions">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($products->have_posts()) {
                            $products->the_post();
                            global $product;
                            ?>
                            <tr>
                                <td class="product-remove">
                                    <a href="#" class="remove-from-wishlist" data-product-id="<?php echo esc_attr($product->get_id()); ?>">×</a>
                                </td>
                                <td class="product-thumbnail">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                        <?php echo $product->get_image('thumbnail'); ?>
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a>
                                </td>
                                <td class="product-price">
                                    <?php echo $product->get_price_html(); ?>
                                </td>
                                <td class="product-stock">
                                    <?php
                                    if ($product->is_in_stock()) {
                                        echo '<span class="in-stock">' . esc_html__('In stock', 'aqualuxe') . '</span>';
                                    } else {
                                        echo '<span class="out-of-stock">' . esc_html__('Out of stock', 'aqualuxe') . '</span>';
                                    }
                                    ?>
                                </td>
                                <td class="product-actions">
                                    <?php
                                    if ($product->is_in_stock()) {
                                        echo '<a href="' . esc_url($product->add_to_cart_url()) . '" class="button add_to_cart_button">' . esc_html__('Add to cart', 'aqualuxe') . '</a>';
                                    } else {
                                        echo '<a href="' . esc_url($product->get_permalink()) . '" class="button">' . esc_html__('Read more', 'aqualuxe') . '</a>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
        
        wp_reset_postdata();
    } else {
        ?>
        <div class="wishlist-empty">
            <p><?php esc_html_e('Your wishlist is empty.', 'aqualuxe'); ?></p>
            <p><a class="button" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Return to shop', 'aqualuxe'); ?></a></p>
        </div>
        <?php
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_wishlist', 'aqualuxe_wishlist_shortcode');

/**
 * Add compare functionality.
 */
function aqualuxe_compare_script() {
    if (get_theme_mod('woocommerce_compare', true)) {
        wp_enqueue_script('aqualuxe-compare', get_template_directory_uri() . '/assets/dist/js/compare.js', array('jquery'), AQUALUXE_VERSION, true);
        
        wp_localize_script('aqualuxe-compare', 'aqualuxe_compare', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-compare'),
            'i18n_add_to_compare' => esc_html__('Add to Compare', 'aqualuxe'),
            'i18n_added_to_compare' => esc_html__('Added to Compare', 'aqualuxe'),
            'i18n_remove_from_compare' => esc_html__('Remove from Compare', 'aqualuxe'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_compare_script');

/**
 * Add to compare button.
 */
function aqualuxe_add_to_compare_button() {
    global $product;
    
    $compare = isset($_COOKIE['aqualuxe_compare']) ? explode(',', $_COOKIE['aqualuxe_compare']) : array();
    $in_compare = in_array($product->get_id(), $compare);
    
    echo '<a href="#" class="button compare-button' . ($in_compare ? ' added' : '') . '" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24"><path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" /></svg>';
    echo '<span>' . ($in_compare ? esc_html__('Remove from Compare', 'aqualuxe') : esc_html__('Add to Compare', 'aqualuxe')) . '</span>';
    echo '</a>';
}

/**
 * AJAX compare handler.
 */
function aqualuxe_ajax_compare() {
    check_ajax_referer('aqualuxe-compare', 'nonce');

    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

    if (!$product_id) {
        wp_send_json_error();
        exit;
    }

    $compare = isset($_COOKIE['aqualuxe_compare']) ? explode(',', $_COOKIE['aqualuxe_compare']) : array();
    
    if (in_array($product_id, $compare)) {
        // Remove from compare
        $compare = array_diff($compare, array($product_id));
        $action = 'removed';
    } else {
        // Add to compare
        $compare[] = $product_id;
        $action = 'added';
    }
    
    // Filter out empty values and make unique
    $compare = array_unique(array_filter($compare));
    
    // Limit to 4 products
    $compare = array_slice($compare, 0, 4);
    
    // Set cookie
    setcookie('aqualuxe_compare', implode(',', $compare), time() + (86400 * 30), '/'); // 30 days
    
    wp_send_json_success(array(
        'action' => $action,
        'count' => count($compare),
    ));

    exit;
}
add_action('wp_ajax_aqualuxe_compare', 'aqualuxe_ajax_compare');
add_action('wp_ajax_nopriv_aqualuxe_compare', 'aqualuxe_ajax_compare');

/**
 * Add compare page.
 */
function aqualuxe_create_compare_page() {
    if (get_theme_mod('woocommerce_compare', true)) {
        $compare_page = get_page_by_path('compare');
        
        if (!$compare_page) {
            $page_id = wp_insert_post(array(
                'post_title'     => __('Compare Products', 'aqualuxe'),
                'post_content'   => '[aqualuxe_compare]',
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'comment_status' => 'closed',
            ));
            
            if ($page_id) {
                update_option('aqualuxe_compare_page_id', $page_id);
            }
        }
    }
}
add_action('after_switch_theme', 'aqualuxe_create_compare_page');

/**
 * Compare shortcode.
 */
function aqualuxe_compare_shortcode() {
    $compare = isset($_COOKIE['aqualuxe_compare']) ? explode(',', $_COOKIE['aqualuxe_compare']) : array();
    $compare = array_filter($compare);
    
    ob_start();
    
    if (!empty($compare)) {
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post__in'       => $compare,
            'orderby'        => 'post__in',
        );
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            ?>
            <div class="compare-products">
                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <?php
                            while ($products->have_posts()) {
                                $products->the_post();
                                global $product;
                                ?>
                                <th class="product-name">
                                    <a href="#" class="remove-from-compare" data-product-id="<?php echo esc_attr($product->get_id()); ?>">×</a>
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                        <?php echo $product->get_image('thumbnail'); ?>
                                        <span><?php echo esc_html($product->get_name()); ?></span>
                                    </a>
                                </th>
                                <?php
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="price-row">
                            <th><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                            <?php
                            $products->rewind_posts();
                            while ($products->have_posts()) {
                                $products->the_post();
                                global $product;
                                ?>
                                <td class="product-price">
                                    <?php echo $product->get_price_html(); ?>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <tr class="description-row">
                            <th><?php esc_html_e('Description', 'aqualuxe'); ?></th>
                            <?php
                            $products->rewind_posts();
                            while ($products->have_posts()) {
                                $products->the_post();
                                global $product;
                                ?>
                                <td class="product-description">
                                    <?php echo wp_kses_post($product->get_short_description()); ?>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <tr class="sku-row">
                            <th><?php esc_html_e('SKU', 'aqualuxe'); ?></th>
                            <?php
                            $products->rewind_posts();
                            while ($products->have_posts()) {
                                $products->the_post();
                                global $product;
                                ?>
                                <td class="product-sku">
                                    <?php echo esc_html($product->get_sku()); ?>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <tr class="stock-row">
                            <th><?php esc_html_e('Availability', 'aqualuxe'); ?></th>
                            <?php
                            $products->rewind_posts();
                            while ($products->have_posts()) {
                                $products->the_post();
                                global $product;
                                ?>
                                <td class="product-stock">
                                    <?php
                                    if ($product->is_in_stock()) {
                                        echo '<span class="in-stock">' . esc_html__('In stock', 'aqualuxe') . '</span>';
                                    } else {
                                        echo '<span class="out-of-stock">' . esc_html__('Out of stock', 'aqualuxe') . '</span>';
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <tr class="add-to-cart-row">
                            <th>&nbsp;</th>
                            <?php
                            $products->rewind_posts();
                            while ($products->have_posts()) {
                                $products->the_post();
                                global $product;
                                ?>
                                <td class="product-add-to-cart">
                                    <?php
                                    if ($product->is_in_stock()) {
                                        echo '<a href="' . esc_url($product->add_to_cart_url()) . '" class="button add_to_cart_button">' . esc_html__('Add to cart', 'aqualuxe') . '</a>';
                                    } else {
                                        echo '<a href="' . esc_url($product->get_permalink()) . '" class="button">' . esc_html__('Read more', 'aqualuxe') . '</a>';
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php
        }
        
        wp_reset_postdata();
    } else {
        ?>
        <div class="compare-empty">
            <p><?php esc_html_e('No products to compare.', 'aqualuxe'); ?></p>
            <p><a class="button" href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Return to shop', 'aqualuxe'); ?></a></p>
        </div>
        <?php
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_compare', 'aqualuxe_compare_shortcode');

/**
 * Add multicurrency support.
 */
function aqualuxe_multicurrency_setup() {
    if (get_theme_mod('woocommerce_multicurrency', true)) {
        // Add currency switcher to header
        add_action('aqualuxe_header_actions', 'aqualuxe_currency_switcher', 40);
        
        // Add currency switcher to footer
        add_action('aqualuxe_footer_widgets', 'aqualuxe_footer_currency_switcher', 20);
    }
}
add_action('init', 'aqualuxe_multicurrency_setup');

/**
 * Currency switcher.
 */
function aqualuxe_currency_switcher() {
    // Check if WooCommerce Multilingual is active
    if (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
        global $woocommerce_wpml;
        
        if ($woocommerce_wpml->multi_currency) {
            $currencies = $woocommerce_wpml->multi_currency->get_currencies();
            $current_currency = $woocommerce_wpml->multi_currency->get_client_currency();
            
            if (!empty($currencies)) {
                ?>
                <div class="currency-switcher">
                    <div class="currency-switcher__dropdown">
                        <button class="currency-switcher__toggle" aria-expanded="false" aria-controls="currency-dropdown">
                            <span class="currency-switcher__current"><?php echo esc_html($current_currency); ?></span>
                            <span class="currency-switcher__arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                    <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 01-1.06 0l-7.5-7.5a.75.75 0 011.06-1.06L12 14.69l6.97-6.97a.75.75 0 111.06 1.06l-7.5 7.5z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                        
                        <div id="currency-dropdown" class="currency-switcher__list" hidden>
                            <?php foreach ($currencies as $code => $currency) : ?>
                                <?php if ($code !== $current_currency) : ?>
                                    <a href="<?php echo esc_url(add_query_arg('currency', $code)); ?>" class="currency-switcher__item">
                                        <?php echo esc_html($code); ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
}

/**
 * Footer currency switcher.
 */
function aqualuxe_footer_currency_switcher() {
    // Check if WooCommerce Multilingual is active
    if (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
        global $woocommerce_wpml;
        
        if ($woocommerce_wpml->multi_currency) {
            $currencies = $woocommerce_wpml->multi_currency->get_currencies();
            $current_currency = $woocommerce_wpml->multi_currency->get_client_currency();
            
            if (!empty($currencies)) {
                ?>
                <div class="footer-currency-switcher">
                    <h3 class="footer-currency-switcher__title"><?php esc_html_e('Currency', 'aqualuxe'); ?></h3>
                    
                    <ul class="footer-currency-switcher__list">
                        <?php foreach ($currencies as $code => $currency) : ?>
                            <li class="footer-currency-switcher__item <?php echo $code === $current_currency ? 'footer-currency-switcher__item--current' : ''; ?>">
                                <a href="<?php echo esc_url(add_query_arg('currency', $code)); ?>">
                                    <?php echo esc_html($code); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php
            }
        }
    }
}

/**
 * Add advanced filtering.
 */
function aqualuxe_advanced_filtering_setup() {
    if (get_theme_mod('woocommerce_advanced_filtering', true)) {
        // Add filter sidebar
        add_action('woocommerce_before_shop_loop', 'aqualuxe_filter_sidebar', 20);
        
        // Add filter button
        add_action('woocommerce_before_shop_loop', 'aqualuxe_filter_button', 15);
        
        // Add filter widgets
        add_action('widgets_init', 'aqualuxe_filter_widgets');
    }
}
add_action('init', 'aqualuxe_advanced_filtering_setup');

/**
 * Register filter widgets.
 */
function aqualuxe_filter_widgets() {
    register_sidebar(array(
        'name'          => __('Product Filters', 'aqualuxe'),
        'id'            => 'product-filters',
        'description'   => __('Widgets in this area will be shown in the filter sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}

/**
 * Filter button.
 */
function aqualuxe_filter_button() {
    if (is_active_sidebar('product-filters')) {
        ?>
        <button class="filter-button" aria-expanded="false" aria-controls="filter-sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                <path d="M18.75 12.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zM12 6a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 0112 6zM12 18a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 0112 18zM3.75 6.75h1.5a.75.75 0 100-1.5h-1.5a.75.75 0 000 1.5zM5.25 18.75h-1.5a.75.75 0 010-1.5h1.5a.75.75 0 010 1.5zM3 12a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 013 12zM9 3.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zM12.75 12a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0zM9 15.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z" />
            </svg>
            <?php esc_html_e('Filter', 'aqualuxe'); ?>
        </button>
        <?php
    }
}

/**
 * Filter sidebar.
 */
function aqualuxe_filter_sidebar() {
    if (is_active_sidebar('product-filters')) {
        ?>
        <div id="filter-sidebar" class="filter-sidebar" aria-hidden="true">
            <div class="filter-sidebar__header">
                <h2 class="filter-sidebar__title"><?php esc_html_e('Filter Products', 'aqualuxe'); ?></h2>
                <button class="filter-sidebar__close" aria-label="<?php esc_attr_e('Close filters', 'aqualuxe'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="filter-sidebar__content">
                <?php dynamic_sidebar('product-filters'); ?>
            </div>
            <div class="filter-sidebar__footer">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button button-secondary"><?php esc_html_e('Reset Filters', 'aqualuxe'); ?></a>
                <button class="button filter-sidebar__apply"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
            </div>
        </div>
        <div class="filter-sidebar__overlay"></div>
        <?php
    }
}

/**
 * Add filter script.
 */
function aqualuxe_filter_script() {
    if (get_theme_mod('woocommerce_advanced_filtering', true)) {
        wp_enqueue_script('aqualuxe-filter', get_template_directory_uri() . '/assets/dist/js/filter.js', array('jquery'), AQUALUXE_VERSION, true);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_filter_script');

/**
 * Add AJAX filter functionality.
 */
function aqualuxe_ajax_filter_script() {
    if (get_theme_mod('woocommerce_ajax_filter', true)) {
        wp_enqueue_script('aqualuxe-ajax-filter', get_template_directory_uri() . '/assets/dist/js/ajax-filter.js', array('jquery'), AQUALUXE_VERSION, true);
        
        wp_localize_script('aqualuxe-ajax-filter', 'aqualuxe_ajax_filter', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-ajax-filter'),
            'shop_url' => wc_get_page_permalink('shop'),
            'i18n_loading' => esc_html__('Loading...', 'aqualuxe'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_ajax_filter_script');

/**
 * AJAX filter handler.
 */
function aqualuxe_ajax_filter() {
    check_ajax_referer('aqualuxe-ajax-filter', 'nonce');

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => get_option('posts_per_page'),
        'paged'          => isset($_POST['page']) ? absint($_POST['page']) : 1,
    );

    // Get filter parameters
    $min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : '';
    $max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : '';
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : '';
    $categories = isset($_POST['categories']) ? array_map('absint', $_POST['categories']) : array();
    $attributes = isset($_POST['attributes']) ? $_POST['attributes'] : array();
    $rating = isset($_POST['rating']) ? array_map('absint', $_POST['rating']) : array();

    // Price filter
    if ($min_price !== '' && $max_price !== '') {
        $args['meta_query'][] = array(
            'key'     => '_price',
            'value'   => array($min_price, $max_price),
            'compare' => 'BETWEEN',
            'type'    => 'NUMERIC',
        );
    }

    // Category filter
    if (!empty($categories)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $categories,
        );
    }

    // Attribute filter
    if (!empty($attributes)) {
        foreach ($attributes as $taxonomy => $terms) {
            if (!empty($terms)) {
                $args['tax_query'][] = array(
                    'taxonomy' => sanitize_key($taxonomy),
                    'field'    => 'term_id',
                    'terms'    => array_map('absint', $terms),
                );
            }
        }
    }

    // Rating filter
    if (!empty($rating)) {
        $rating_filter = array('relation' => 'OR');
        
        foreach ($rating as $rate) {
            $rating_filter[] = array(
                'key'     => '_wc_average_rating',
                'value'   => array($rate - 0.5, $rate + 0.5),
                'compare' => 'BETWEEN',
                'type'    => 'DECIMAL',
            );
        }
        
        $args['meta_query'][] = $rating_filter;
    }

    // Order by
    if ($orderby) {
        switch ($orderby) {
            case 'price':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
            case 'price-desc':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'popularity':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'rating':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
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

    // Search
    if (isset($_POST['s']) && !empty($_POST['s'])) {
        $args['s'] = sanitize_text_field($_POST['s']);
    }

    // Set tax query relation
    if (isset($args['tax_query']) && count($args['tax_query']) > 1) {
        $args['tax_query']['relation'] = 'AND';
    }

    // Set meta query relation
    if (isset($args['meta_query']) && count($args['meta_query']) > 1) {
        $args['meta_query']['relation'] = 'AND';
    }

    $products = new WP_Query($args);

    ob_start();

    if ($products->have_posts()) {
        woocommerce_product_loop_start();

        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }

        woocommerce_product_loop_end();

        woocommerce_pagination();
    } else {
        echo '<p class="woocommerce-info">' . esc_html__('No products found.', 'aqualuxe') . '</p>';
    }

    wp_reset_postdata();

    $output = ob_get_clean();

    wp_send_json_success(array(
        'html' => $output,
        'found' => $products->found_posts,
    ));

    exit;
}
add_action('wp_ajax_aqualuxe_ajax_filter', 'aqualuxe_ajax_filter');
add_action('wp_ajax_nopriv_aqualuxe_ajax_filter', 'aqualuxe_ajax_filter');