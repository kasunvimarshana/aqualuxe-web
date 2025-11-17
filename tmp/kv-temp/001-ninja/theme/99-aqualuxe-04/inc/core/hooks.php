<?php
/**
 * Theme Hooks and Filters
 *
 * @package AquaLuxe\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return apply_filters('aqualuxe_excerpt_length', 25);
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length', 999);

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return apply_filters('aqualuxe_excerpt_more', '&hellip;');
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom body classes
 */
function aqualuxe_body_classes($classes) {
    // Add page slug if it doesn't exist
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }
    
    // Add a class if it's a mobile device
    if (wp_is_mobile()) {
        $classes[] = 'mobile';
    }
    
    // Add dark mode class if enabled
    if (get_theme_mod('enable_dark_mode', false)) {
        $classes[] = 'dark-mode-available';
    }
    
    // Add WooCommerce classes
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
        
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            $classes[] = 'woocommerce-page';
        }
    }
    
    // Add custom post type classes
    if (aqualuxe_is_custom_post_type()) {
        $classes[] = 'aqualuxe-custom-post-type';
        $classes[] = 'post-type-' . get_post_type();
    }
    
    return apply_filters('aqualuxe_body_classes', $classes);
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add pingback URL to head for single posts
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="' . esc_url(get_bloginfo('pingback_url')) . '">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Enqueue comment reply script
 */
function aqualuxe_comment_reply_script() {
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_comment_reply_script');

/**
 * Add custom image sizes to media uploader
 */
function aqualuxe_custom_image_sizes($sizes) {
    $custom_sizes = array(
        'aqualuxe-hero' => __('Hero Image', 'aqualuxe'),
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-thumbnail' => __('Thumbnail', 'aqualuxe'),
        'aqualuxe-gallery' => __('Gallery Image', 'aqualuxe'),
    );
    
    return array_merge($sizes, $custom_sizes);
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Remove unnecessary dashboard widgets for non-admin users
 */
function aqualuxe_remove_dashboard_widgets() {
    if (!current_user_can('manage_options')) {
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    }
}
add_action('wp_dashboard_setup', 'aqualuxe_remove_dashboard_widgets');

/**
 * Custom login page styling
 */
function aqualuxe_login_styles() {
    wp_enqueue_style('aqualuxe-login', aqualuxe_asset('css/login.css'), array(), AQUALUXE_VERSION);
}
add_action('login_enqueue_scripts', 'aqualuxe_login_styles');

/**
 * Change login logo URL
 */
function aqualuxe_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'aqualuxe_login_logo_url');

/**
 * Change login logo title
 */
function aqualuxe_login_logo_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'aqualuxe_login_logo_title');

/**
 * Add theme support for WooCommerce 3.0+ features
 */
function aqualuxe_woocommerce_setup() {
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ));

    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

/**
 * Remove WooCommerce default styles
 */
function aqualuxe_dequeue_woocommerce_styles($enqueue_styles) {
    if (aqualuxe_get_option('disable_wc_default_styles', false)) {
        unset($enqueue_styles['woocommerce-general']);
        unset($enqueue_styles['woocommerce-layout']);
        unset($enqueue_styles['woocommerce-smallscreen']);
    }
    
    return $enqueue_styles;
}
add_filter('woocommerce_enqueue_styles', 'aqualuxe_dequeue_woocommerce_styles');

/**
 * Modify WooCommerce breadcrumbs
 */
function aqualuxe_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => ' <span class="breadcrumb-separator">/</span> ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
        'home'        => _x('Home', 'breadcrumb', 'aqualuxe'),
    );
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumbs');

/**
 * Change number of products per row
 */
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod('woocommerce_products_per_row', 4);
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

/**
 * Change number of products per page
 */
function aqualuxe_woocommerce_products_per_page() {
    return get_theme_mod('woocommerce_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');

/**
 * Add custom fields to product pages
 */
function aqualuxe_woocommerce_product_meta() {
    global $product;
    
    $care_level = get_post_meta($product->get_id(), '_care_level', true);
    $water_type = get_post_meta($product->get_id(), '_water_type', true);
    $origin = get_post_meta($product->get_id(), '_origin', true);
    
    if ($care_level || $water_type || $origin) {
        echo '<div class="product-aquatic-info">';
        echo '<h3>' . __('Aquatic Information', 'aqualuxe') . '</h3>';
        
        if ($care_level) {
            echo '<p><strong>' . __('Care Level:', 'aqualuxe') . '</strong> ' . esc_html($care_level) . '</p>';
        }
        
        if ($water_type) {
            echo '<p><strong>' . __('Water Type:', 'aqualuxe') . '</strong> ' . esc_html($water_type) . '</p>';
        }
        
        if ($origin) {
            echo '<p><strong>' . __('Origin:', 'aqualuxe') . '</strong> ' . esc_html($origin) . '</p>';
        }
        
        echo '</div>';
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_meta', 25);

/**
 * Add custom search form
 */
function aqualuxe_search_form($form) {
    $form = '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">
        <label>
            <span class="screen-reader-text">' . _x('Search for:', 'label', 'aqualuxe') . '</span>
            <input type="search" class="search-field" placeholder="' . esc_attr_x('Search &hellip;', 'placeholder', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" />
        </label>
        <button type="submit" class="search-submit">
            <span class="screen-reader-text">' . _x('Search', 'submit button', 'aqualuxe') . '</span>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 13A6 6 0 1 0 7 1a6 6 0 0 0 0 12zM15 15l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </form>';
    
    return $form;
}
add_filter('get_search_form', 'aqualuxe_search_form');

/**
 * Modify archive titles
 */
function aqualuxe_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_year()) {
        $title = get_the_date(_x('Y', 'yearly archives date format', 'aqualuxe'));
    } elseif (is_month()) {
        $title = get_the_date(_x('F Y', 'monthly archives date format', 'aqualuxe'));
    } elseif (is_day()) {
        $title = get_the_date(_x('F j, Y', 'daily archives date format', 'aqualuxe'));
    } elseif (is_tax('aqualuxe_service_category')) {
        $title = single_term_title('', false);
    } elseif (is_tax('aqualuxe_event_category')) {
        $title = single_term_title('', false);
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    }
    
    return $title;
}
add_filter('get_the_archive_title', 'aqualuxe_archive_title');

/**
 * Add structured data for posts
 */
function aqualuxe_structured_data() {
    if (is_single() && 'post' === get_post_type()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name')
            )
        );
        
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'full');
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'aqualuxe_structured_data');

/**
 * Flush rewrite rules after theme activation
 */
function aqualuxe_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'aqualuxe_flush_rewrite_rules');

/**
 * Clean up category transients
 */
function aqualuxe_category_transient_flusher() {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    delete_transient('aqualuxe_categories');
}
add_action('edit_category', 'aqualuxe_category_transient_flusher');
add_action('save_post', 'aqualuxe_category_transient_flusher');