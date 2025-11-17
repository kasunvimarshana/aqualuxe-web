<?php
/**
 * Theme hooks
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme hooks class
 */
class AquaLuxe_Hooks {
    /**
     * Constructor
     */
    public function __construct() {
        // Header hooks
        add_action('aqualuxe_before_header', array($this, 'before_header'), 10);
        add_action('aqualuxe_header', array($this, 'header'), 10);
        add_action('aqualuxe_after_header', array($this, 'after_header'), 10);
        
        // Footer hooks
        add_action('aqualuxe_before_footer', array($this, 'before_footer'), 10);
        add_action('aqualuxe_footer', array($this, 'footer'), 10);
        add_action('aqualuxe_after_footer', array($this, 'after_footer'), 10);
        
        // Content hooks
        add_action('aqualuxe_before_content', array($this, 'before_content'), 10);
        add_action('aqualuxe_after_content', array($this, 'after_content'), 10);
        
        // Sidebar hooks
        add_action('aqualuxe_sidebar', array($this, 'sidebar'), 10);
        
        // Post hooks
        add_action('aqualuxe_before_post', array($this, 'before_post'), 10);
        add_action('aqualuxe_after_post', array($this, 'after_post'), 10);
        add_action('aqualuxe_post_meta', array($this, 'post_meta'), 10);
        add_action('aqualuxe_post_content', array($this, 'post_content'), 10);
        
        // Page hooks
        add_action('aqualuxe_before_page', array($this, 'before_page'), 10);
        add_action('aqualuxe_after_page', array($this, 'after_page'), 10);
        
        // WooCommerce hooks
        if (class_exists('WooCommerce')) {
            $this->setup_woocommerce_hooks();
        }
    }
    
    /**
     * Before header
     */
    public function before_header() {
        if (get_theme_mod('aqualuxe_enable_top_bar', true)) {
            get_template_part('templates/parts/top-bar');
        }
    }
    
    /**
     * Header
     */
    public function header() {
        $header_layout = get_theme_mod('aqualuxe_header_layout', 'default');
        get_template_part('templates/parts/header', $header_layout);
    }
    
    /**
     * After header
     */
    public function after_header() {
        if (get_theme_mod('aqualuxe_enable_breadcrumbs', true) && !is_front_page()) {
            get_template_part('templates/parts/breadcrumbs');
        }
    }
    
    /**
     * Before footer
     */
    public function before_footer() {
        if (get_theme_mod('aqualuxe_footer_newsletter', true)) {
            get_template_part('templates/parts/newsletter');
        }
    }
    
    /**
     * Footer
     */
    public function footer() {
        get_template_part('templates/parts/footer');
    }
    
    /**
     * After footer
     */
    public function after_footer() {
        if (get_theme_mod('aqualuxe_enable_back_to_top', true)) {
            echo '<a href="#" id="back-to-top" class="back-to-top" aria-label="' . esc_attr__('Back to top', 'aqualuxe') . '">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up"><polyline points="18 15 12 9 6 15"></polyline></svg>';
            echo '</a>';
        }
    }
    
    /**
     * Before content
     */
    public function before_content() {
        echo '<main id="main" class="site-main">';
    }
    
    /**
     * After content
     */
    public function after_content() {
        echo '</main><!-- #main -->';
    }
    
    /**
     * Sidebar
     */
    public function sidebar() {
        get_sidebar();
    }
    
    /**
     * Before post
     */
    public function before_post() {
        echo '<article id="post-' . get_the_ID() . '" ' . get_post_class('post-item') . '>';
    }
    
    /**
     * After post
     */
    public function after_post() {
        echo '</article><!-- #post-' . get_the_ID() . ' -->';
        
        if (is_singular('post')) {
            // Author bio
            if (get_theme_mod('aqualuxe_show_author_bio', true)) {
                get_template_part('templates/parts/author-bio');
            }
            
            // Related posts
            if (get_theme_mod('aqualuxe_show_related_posts', true)) {
                get_template_part('templates/parts/related-posts');
            }
            
            // Post navigation
            the_post_navigation(array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            ));
            
            // Comments
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
        }
    }
    
    /**
     * Post meta
     */
    public function post_meta() {
        echo '<div class="entry-meta">';
        
        if (get_theme_mod('aqualuxe_show_post_date', true)) {
            echo '<span class="posted-on">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>';
            echo '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . get_the_date() . '</a>';
            echo '</span>';
        }
        
        if (get_theme_mod('aqualuxe_show_post_author', true)) {
            echo '<span class="byline">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
            echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>';
            echo '</span>';
        }
        
        if (get_theme_mod('aqualuxe_show_post_categories', true) && has_category()) {
            echo '<span class="cat-links">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>';
            echo get_the_category_list(', ');
            echo '</span>';
        }
        
        if (get_theme_mod('aqualuxe_show_post_comments', true) && comments_open()) {
            echo '<span class="comments-link">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>';
            comments_popup_link(
                esc_html__('Leave a comment', 'aqualuxe'),
                esc_html__('1 Comment', 'aqualuxe'),
                esc_html__('% Comments', 'aqualuxe')
            );
            echo '</span>';
        }
        
        echo '</div><!-- .entry-meta -->';
    }
    
    /**
     * Post content
     */
    public function post_content() {
        if (is_singular()) {
            the_content();
            
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                'after'  => '</div>',
            ));
            
            if (get_theme_mod('aqualuxe_show_post_tags', true) && has_tag()) {
                echo '<div class="entry-tags">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>';
                the_tags('', ', ', '');
                echo '</div>';
            }
            
            if (get_theme_mod('aqualuxe_show_social_sharing', true)) {
                get_template_part('templates/parts/social-sharing');
            }
        } else {
            if (get_theme_mod('aqualuxe_show_featured_image', true) && has_post_thumbnail()) {
                echo '<a href="' . esc_url(get_permalink()) . '" class="post-thumbnail">';
                the_post_thumbnail('aqualuxe-thumbnail', array('class' => 'rounded-lg shadow transition-transform duration-300 hover:scale-105'));
                echo '</a>';
            }
            
            echo '<div class="entry-content">';
            the_excerpt();
            echo '<a href="' . esc_url(get_permalink()) . '" class="read-more">' . esc_html__('Read More', 'aqualuxe') . ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>';
            echo '</div><!-- .entry-content -->';
        }
    }
    
    /**
     * Before page
     */
    public function before_page() {
        echo '<article id="page-' . get_the_ID() . '" ' . get_post_class('page-item') . '>';
    }
    
    /**
     * After page
     */
    public function after_page() {
        echo '</article><!-- #page-' . get_the_ID() . ' -->';
        
        // Comments
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
    }
    
    /**
     * Setup WooCommerce hooks
     */
    public function setup_woocommerce_hooks() {
        // Remove default WooCommerce wrappers
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        
        // Add custom wrappers
        add_action('woocommerce_before_main_content', array($this, 'woocommerce_wrapper_start'), 10);
        add_action('woocommerce_after_main_content', array($this, 'woocommerce_wrapper_end'), 10);
        
        // Customize product display
        add_filter('woocommerce_product_loop_start', array($this, 'product_loop_start'), 10);
        add_filter('woocommerce_product_loop_end', array($this, 'product_loop_end'), 10);
        
        // Add quick view
        if (get_theme_mod('aqualuxe_quick_view', true)) {
            add_action('woocommerce_after_shop_loop_item', array($this, 'quick_view_button'), 15);
            add_action('wp_footer', array($this, 'quick_view_modal'));
        }
        
        // Add wishlist
        if (get_theme_mod('aqualuxe_wishlist', true) && !function_exists('YITH_WCWL')) {
            add_action('woocommerce_after_shop_loop_item', array($this, 'wishlist_button'), 20);
        }
        
        // Customize sale badge
        add_filter('woocommerce_sale_flash', array($this, 'custom_sale_badge'), 10, 3);
        
        // Products per row
        add_filter('loop_shop_columns', array($this, 'loop_columns'), 999);
        
        // Products per page
        add_filter('loop_shop_per_page', array($this, 'products_per_page'), 20);
        
        // Related products
        add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
        
        // Upsell products
        add_filter('woocommerce_upsell_display_args', array($this, 'upsell_products_args'));
        
        // Cross-sell products
        add_filter('woocommerce_cross_sells_columns', array($this, 'cross_sell_columns'));
        
        // Disable features based on theme options
        if (!get_theme_mod('aqualuxe_product_zoom', true)) {
            remove_theme_support('wc-product-gallery-zoom');
        }
        
        if (!get_theme_mod('aqualuxe_product_lightbox', true)) {
            remove_theme_support('wc-product-gallery-lightbox');
        }
        
        if (!get_theme_mod('aqualuxe_product_slider', true)) {
            remove_theme_support('wc-product-gallery-slider');
        }
        
        if (!get_theme_mod('aqualuxe_related_products', true)) {
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
        }
        
        if (!get_theme_mod('aqualuxe_product_upsells', true)) {
            remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
        }
        
        if (!get_theme_mod('aqualuxe_cart_cross_sells', true)) {
            remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
        }
    }
    
    /**
     * WooCommerce wrapper start
     */
    public function woocommerce_wrapper_start() {
        $sidebar_position = get_theme_mod('aqualuxe_shop_sidebar', 'right');
        
        if (is_product()) {
            $sidebar_position = get_theme_mod('aqualuxe_product_sidebar', 'none');
        }
        
        echo '<div class="woocommerce-content-wrapper sidebar-' . esc_attr($sidebar_position) . '">';
        echo '<div class="woocommerce-main-content">';
    }
    
    /**
     * WooCommerce wrapper end
     */
    public function woocommerce_wrapper_end() {
        echo '</div><!-- .woocommerce-main-content -->';
        
        $sidebar_position = get_theme_mod('aqualuxe_shop_sidebar', 'right');
        
        if (is_product()) {
            $sidebar_position = get_theme_mod('aqualuxe_product_sidebar', 'none');
        }
        
        if ($sidebar_position !== 'none') {
            echo '<div class="woocommerce-sidebar">';
            dynamic_sidebar('shop-sidebar');
            echo '</div><!-- .woocommerce-sidebar -->';
        }
        
        echo '</div><!-- .woocommerce-content-wrapper -->';
    }
    
    /**
     * Product loop start
     *
     * @param string $html Loop start HTML.
     * @return string Modified loop start HTML.
     */
    public function product_loop_start($html) {
        $columns = get_theme_mod('aqualuxe_products_per_row', '3');
        $layout = get_theme_mod('aqualuxe_shop_layout', 'grid');
        
        return '<ul class="products columns-' . esc_attr($columns) . ' layout-' . esc_attr($layout) . '">';
    }
    
    /**
     * Product loop end
     *
     * @param string $html Loop end HTML.
     * @return string Modified loop end HTML.
     */
    public function product_loop_end($html) {
        return '</ul>';
    }
    
    /**
     * Quick view button
     */
    public function quick_view_button() {
        global $product;
        
        echo '<a href="#" class="quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        echo '<span>' . esc_html__('Quick View', 'aqualuxe') . '</span>';
        echo '</a>';
    }
    
    /**
     * Quick view modal
     */
    public function quick_view_modal() {
        ?>
        <div id="quick-view-modal" class="quick-view-modal" style="display: none;">
            <div class="quick-view-modal-content">
                <span class="quick-view-close">&times;</span>
                <div class="quick-view-content"></div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Wishlist button
     */
    public function wishlist_button() {
        global $product;
        
        echo '<a href="#" class="wishlist-button" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';
        echo '<span>' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
        echo '</a>';
    }
    
    /**
     * Custom sale badge
     *
     * @param string $html Sale badge HTML.
     * @param object $post Post object.
     * @param object $product Product object.
     * @return string Modified sale badge HTML.
     */
    public function custom_sale_badge($html, $post, $product) {
        $sale_text = get_theme_mod('aqualuxe_sale_badge_text', __('Sale!', 'aqualuxe'));
        
        return '<span class="onsale">' . esc_html($sale_text) . '</span>';
    }
    
    /**
     * Loop columns
     *
     * @return int Number of columns.
     */
    public function loop_columns() {
        return get_theme_mod('aqualuxe_products_per_row', 3);
    }
    
    /**
     * Products per page
     *
     * @return int Number of products per page.
     */
    public function products_per_page() {
        return get_theme_mod('aqualuxe_products_per_page', 12);
    }
    
    /**
     * Related products args
     *
     * @param array $args Related products args.
     * @return array Modified related products args.
     */
    public function related_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        
        return $args;
    }
    
    /**
     * Upsell products args
     *
     * @param array $args Upsell products args.
     * @return array Modified upsell products args.
     */
    public function upsell_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        
        return $args;
    }
    
    /**
     * Cross-sell columns
     *
     * @return int Number of columns.
     */
    public function cross_sell_columns() {
        return 2;
    }
}

// Initialize hooks
$aqualuxe_hooks = new AquaLuxe_Hooks();

/**
 * Custom template tags for this theme
 */

if (!function_exists('aqualuxe_header')) {
    /**
     * Display site header
     */
    function aqualuxe_header() {
        do_action('aqualuxe_before_header');
        do_action('aqualuxe_header');
        do_action('aqualuxe_after_header');
    }
}

if (!function_exists('aqualuxe_footer')) {
    /**
     * Display site footer
     */
    function aqualuxe_footer() {
        do_action('aqualuxe_before_footer');
        do_action('aqualuxe_footer');
        do_action('aqualuxe_after_footer');
    }
}

if (!function_exists('aqualuxe_content')) {
    /**
     * Display site content
     */
    function aqualuxe_content() {
        do_action('aqualuxe_before_content');
        
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                
                if (is_singular()) {
                    do_action('aqualuxe_before_post');
                    
                    echo '<header class="entry-header">';
                    the_title('<h1 class="entry-title">', '</h1>');
                    do_action('aqualuxe_post_meta');
                    echo '</header><!-- .entry-header -->';
                    
                    if (has_post_thumbnail() && get_theme_mod('aqualuxe_show_featured_image', true)) {
                        echo '<div class="post-thumbnail">';
                        the_post_thumbnail('aqualuxe-featured', array('class' => 'rounded-lg shadow-lg'));
                        echo '</div><!-- .post-thumbnail -->';
                    }
                    
                    echo '<div class="entry-content">';
                    do_action('aqualuxe_post_content');
                    echo '</div><!-- .entry-content -->';
                    
                    do_action('aqualuxe_after_post');
                } else {
                    get_template_part('templates/content', get_post_type());
                }
            }
            
            if (!is_singular()) {
                aqualuxe_pagination();
            }
        } else {
            get_template_part('templates/content', 'none');
        }
        
        do_action('aqualuxe_after_content');
    }
}

if (!function_exists('aqualuxe_sidebar')) {
    /**
     * Display site sidebar
     */
    function aqualuxe_sidebar() {
        $sidebar_position = get_theme_mod('aqualuxe_sidebar_position', 'right');
        
        if ($sidebar_position !== 'none') {
            do_action('aqualuxe_sidebar');
        }
    }
}

if (!function_exists('aqualuxe_pagination')) {
    /**
     * Display pagination
     */
    function aqualuxe_pagination() {
        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg><span class="screen-reader-text">' . esc_html__('Previous', 'aqualuxe') . '</span>',
            'next_text' => '<span class="screen-reader-text">' . esc_html__('Next', 'aqualuxe') . '</span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>',
        ));
    }
}