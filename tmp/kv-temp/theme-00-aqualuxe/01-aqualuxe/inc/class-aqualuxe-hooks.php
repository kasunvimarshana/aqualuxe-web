
<?php
/**
 * AquaLuxe Hooks Class
 *
 * Handles all theme hooks and filters
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Hooks Class
 */
class AquaLuxe_Hooks {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Hooks
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Hooks
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Header hooks
        add_action('aqualuxe_header', array($this, 'header_top_bar'), 10);
        add_action('aqualuxe_header', array($this, 'header_main'), 20);
        add_action('aqualuxe_header', array($this, 'header_navigation'), 30);
        
        // Footer hooks
        add_action('aqualuxe_footer', array($this, 'footer_widgets'), 10);
        add_action('aqualuxe_footer', array($this, 'footer_bottom'), 20);
        
        // Content hooks
        add_action('aqualuxe_before_content', array($this, 'page_header'), 10);
        add_action('aqualuxe_before_content', array($this, 'breadcrumbs'), 20);
        add_action('aqualuxe_after_content', array($this, 'page_footer'), 10);
        
        // Single post hooks
        add_action('aqualuxe_single_post_before', array($this, 'single_post_header'), 10);
        add_action('aqualuxe_single_post_after', array($this, 'single_post_footer'), 10);
        add_action('aqualuxe_single_post_after', array($this, 'single_post_navigation'), 20);
        add_action('aqualuxe_single_post_after', array($this, 'single_post_related'), 30);
        
        // Archive hooks
        add_action('aqualuxe_archive_before', array($this, 'archive_header'), 10);
        add_action('aqualuxe_archive_after', array($this, 'archive_footer'), 10);
        
        // Comment hooks
        add_filter('comment_form_defaults', array($this, 'custom_comment_form'));
        add_filter('comment_form_fields', array($this, 'custom_comment_fields'));
        add_filter('comment_form_submit_button', array($this, 'custom_comment_submit_button'), 10, 2);
        
        // Excerpt hooks
        add_filter('excerpt_length', array($this, 'custom_excerpt_length'));
        add_filter('excerpt_more', array($this, 'custom_excerpt_more'));
        
        // Body class hooks
        add_filter('body_class', array($this, 'custom_body_classes'));
        
        // Post class hooks
        add_filter('post_class', array($this, 'custom_post_classes'));
        
        // Menu hooks
        add_filter('nav_menu_css_class', array($this, 'custom_menu_classes'), 10, 3);
        add_filter('nav_menu_link_attributes', array($this, 'custom_menu_link_attributes'), 10, 3);
        
        // Widget hooks
        add_filter('widget_title', array($this, 'custom_widget_title'));
        add_filter('dynamic_sidebar_params', array($this, 'custom_widget_params'));
        
        // Search form hooks
        add_filter('get_search_form', array($this, 'custom_search_form'));
        
        // Password form hooks
        add_filter('the_password_form', array($this, 'custom_password_form'));
        
        // Gallery hooks
        add_filter('post_gallery', array($this, 'custom_gallery'), 10, 2);
        
        // Image hooks
        add_filter('wp_get_attachment_image_attributes', array($this, 'custom_image_attributes'), 10, 3);
        
        // Pagination hooks
        add_filter('next_posts_link_attributes', array($this, 'custom_next_posts_link_attributes'));
        add_filter('previous_posts_link_attributes', array($this, 'custom_previous_posts_link_attributes'));
        
        // Admin hooks
        add_filter('admin_footer_text', array($this, 'custom_admin_footer_text'));
        add_filter('update_footer', array($this, 'custom_admin_version_footer'), 11);
        
        // Login hooks
        add_filter('login_headerurl', array($this, 'custom_login_header_url'));
        add_filter('login_headertext', array($this, 'custom_login_header_text'));
        add_action('login_enqueue_scripts', array($this, 'custom_login_styles'));
        
        // AJAX hooks
        add_action('wp_ajax_aqualuxe_load_more_posts', array($this, 'load_more_posts'));
        add_action('wp_ajax_nopriv_aqualuxe_load_more_posts', array($this, 'load_more_posts'));
        
        // Shortcode hooks
        add_shortcode('aqualuxe_button', array($this, 'button_shortcode'));
        add_shortcode('aqualuxe_icon', array($this, 'icon_shortcode'));
        add_shortcode('aqualuxe_products', array($this, 'products_shortcode'));
        add_shortcode('aqualuxe_featured_products', array($this, 'featured_products_shortcode'));
        add_shortcode('aqualuxe_sale_products', array($this, 'sale_products_shortcode'));
        add_shortcode('aqualuxe_best_selling_products', array($this, 'best_selling_products_shortcode'));
        add_shortcode('aqualuxe_top_rated_products', array($this, 'top_rated_products_shortcode'));
        add_shortcode('aqualuxe_product_categories', array($this, 'product_categories_shortcode'));
        add_shortcode('aqualuxe_product_brands', array($this, 'product_brands_shortcode'));
        add_shortcode('aqualuxe_product_tags', array($this, 'product_tags_shortcode'));
        add_shortcode('aqualuxe_product_attribute', array($this, 'product_attribute_shortcode'));
        add_shortcode('aqualuxe_recent_products', array($this, 'recent_products_shortcode'));
        add_shortcode('aqualuxe_products_by_ids', array($this, 'products_by_ids_shortcode'));
        add_shortcode('aqualuxe_products_by_sku', array($this, 'products_by_sku_shortcode'));
        
        // Security hooks
        add_filter('the_generator', array($this, 'remove_version_info'));
        add_filter('style_loader_src', array($this, 'remove_version_from_scripts_styles'), 9999);
        add_filter('script_loader_src', array($this, 'remove_version_from_scripts_styles'), 9999);
        
        // Performance hooks
        add_action('wp_enqueue_scripts', array($this, 'dequeue_unnecessary_styles'), 9999);
        add_action('wp_enqueue_scripts', array($this, 'dequeue_unnecessary_scripts'), 9999);
        add_filter('script_loader_tag', array($this, 'add_async_defer_attributes'), 10, 2);
        
        // SEO hooks
        add_filter('wp_title', array($this, 'custom_wp_title'), 10, 2);
        add_action('wp_head', array($this, 'add_meta_tags'), 1);
        add_action('wp_head', array($this, 'add_open_graph_tags'), 2);
        add_action('wp_head', array($this, 'add_twitter_card_tags'), 3);
        add_action('wp_head', array($this, 'add_schema_markup'), 4);
        
        // Accessibility hooks
        add_filter('nav_menu_link_attributes', array($this, 'add_aria_attributes'), 10, 3);
        add_filter('the_content', array($this, 'add_skip_links'));
        
        // Mobile hooks
        add_action('wp_head', array($this, 'add_mobile_meta_tags'));
        
        // WooCommerce specific hooks
        if (class_exists('WooCommerce')) {
            // Product hooks
            add_action('woocommerce_before_shop_loop_item', array($this, 'product_wrapper_open'), 5);
            add_action('woocommerce_after_shop_loop_item', array($this, 'product_wrapper_close'), 50);
            
            // Add sale flash
            add_filter('woocommerce_sale_flash', array($this, 'custom_sale_flash'), 10, 3);
            
            // Add featured badge
            add_action('woocommerce_before_shop_loop_item_title', array($this, 'add_featured_badge'), 5);
            
            // Add new badge
            add_action('woocommerce_before_shop_loop_item_title', array($this, 'add_new_badge'), 6);
            
            // Add out of stock badge
            add_action('woocommerce_before_shop_loop_item_title', array($this, 'add_out_of_stock_badge'), 7);
            
            // Add product countdown
            add_action('woocommerce_before_shop_loop_item_title', array($this, 'add_product_countdown'), 8);
            
            // Add product rating
            add_action('woocommerce_after_shop_loop_item_title', array($this, 'add_product_rating'), 5);
            
            // Add product excerpt
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_excerpt'), 5);
            
            // Add product categories
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_categories'), 6);
            
            // Add product tags
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_tags'), 7);
            
            // Add product brands
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_brands'), 8);
            
            // Add product attributes
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_attributes'), 9);
            
            // Add product stock
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_stock'), 10);
            
            // Add product sku
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_sku'), 11);
            
            // Add product dimensions
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_dimensions'), 12);
            
            // Add product weight
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_weight'), 13);
            
            // Add product shipping class
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_shipping_class'), 14);
            
            // Add product meta
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_meta'), 15);
            
            // Add product actions
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_actions'), 20);
            
            // Add product quick view
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_quick_view'), 25);
            
            // Add product compare
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_compare'), 30);
            
            // Add product wishlist
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_wishlist'), 35);
            
            // Add product share
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_share'), 40);
            
            // Add product video
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_video'), 45);
            
            // Add product 360 view
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_360_view'), 46);
            
            // Add product virtual tour
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_virtual_tour'), 47);
            
            // Add product augmented reality
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_augmented_reality'), 48);
            
            // Add product reviews
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_product_reviews'), 49);
        }
    }

    /**
     * Header top bar
     */
    public function header_top_bar() {
        // Get theme options
        $show_top_bar = get_theme_mod('aqualuxe_show_top_bar', true);
        
        if (!$show_top_bar) {
            return;
        }
        
        // Get contact information
        $phone = get_theme_mod('aqualuxe_phone', '+1 (123) 456-7890');
        $email = get_theme_mod('aqualuxe_email', 'info@aqualuxe.com');
        
        // Get social media links
        $facebook = get_theme_mod('aqualuxe_facebook_url', '');
        $twitter = get_theme_mod('aqualuxe_twitter_url', '');
        $instagram = get_theme_mod('aqualuxe_instagram_url', '');
        $youtube = get_theme_mod('aqualuxe_youtube_url', '');
        
        ?>
        <div class="aqualuxe-top-bar">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="aqualuxe-contact-info">
                            <?php if (!empty($phone)) : ?>
                                <span class="aqualuxe-phone">
                                    <i class="fas fa-phone"></i>
                                    <?php echo esc_html($phone); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($email)) : ?>
                                <span class="aqualuxe-email">
                                    <i class="fas fa-envelope"></i>
                                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="aqualuxe-top-right">
                            <div class="aqualuxe-social-icons">
                                <?php if (!empty($facebook)) : ?>
                                    <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($twitter)) : ?>
                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($instagram)) : ?>
                                    <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($youtube)) : ?>
                                    <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (class_exists('WooCommerce')) : ?>
                                <div class="aqualuxe-account-links">
                                    <?php if (is_user_logged_in()) : ?>
                                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                                            <i class="fas fa-user"></i>
                                            <?php esc_html_e('My Account', 'aqualuxe'); ?>
                                        </a>
                                        <a href="<?php echo esc_url(wc_logout_url(wc_get_page_permalink('myaccount'))); ?>">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <?php esc_html_e('Logout', 'aqualuxe'); ?>
                                        </a>
                                    <?php else : ?>
                                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <?php esc_html_e('Login / Register', 'aqualuxe'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Header main
     */
    public function header_main() {
        ?>
        <div class="aqualuxe-header-main">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="site-branding">
                            <?php
                            if (has_custom_logo()) {
                                the_custom_logo();
                            } else {
                                ?>
                                <h1 class="site-title">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </h1>
                                <?php
                                $description = get_bloginfo('description', 'display');
                                if ($description || is_customize_preview()) {
                                    ?>
                                    <p class="site-description"><?php echo esc_html($description); ?></p>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <?php if (class_exists('WooCommerce')) : ?>
                            <div class="aqualuxe-search-form">
                                <?php get_product_search_form(); ?>
                            </div>
                        <?php else : ?>
                            <div class="aqualuxe-search-form">
                                <?php get_search_form(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-3">
                        <?php if (class_exists('WooCommerce')) : ?>
                            <div class="aqualuxe-header-actions">
                                <div class="aqualuxe-wishlist">
                                    <a href="<?php echo esc_url(get_permalink(get_option('yith_wcwl_wishlist_page_id'))); ?>">
                                        <i class="fas fa-heart"></i>
                                        <span class="wishlist-count">0</span>
                                    </a>
                                </div>
                                
                                <div class="aqualuxe-cart">
                                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
                                    </a>
                                    <div class="aqualuxe-mini-cart">
                                        <div class="widget_shopping_cart_content">
                                            <?php woocommerce_mini_cart(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Header navigation
     */
    public function header_navigation() {
        ?>
        <div class="aqualuxe-header-navigation">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <nav id="site-navigation" class="main-navigation">
                            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                                <i class="fas fa-bars"></i>
                                <?php esc_html_e('Menu', 'aqualuxe'); ?>
                            </button>
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'container'      => false,
                                'fallback_cb'    => '__return_false',
                            ));
                            ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Footer widgets
     */
    public function footer_widgets() {
        // Get number of footer columns
        $footer_columns = intval(get_theme_mod('aqualuxe_footer_columns', 4));
        
        if ($footer_columns < 1) {
            return;
        }
        
        // Calculate column width
        $column_width = 12 / $footer_columns;
        
        // Check if any footer widget area has widgets
        $has_widgets = false;
        for ($i = 1; $i <= $footer_columns; $i++) {
            if (is_active_sidebar('footer-' . $i)) {
                $has_widgets = true;
                break;
            }
        }
        
        if (!$has_widgets) {
            return;
        }
        
        ?>
        <div class="aqualuxe-footer-widgets">
            <div class="container">
                <div class="row">
                    <?php
                    for ($i = 1; $i <= $footer_columns; $i++) {
                        if (is_active_sidebar('footer-' . $i)) {
                            ?>
                            <div class="col-md-<?php echo esc_attr($column_width); ?> footer-column">
                                <?php dynamic_sidebar('footer-' . $i); ?>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Footer bottom
     */
    public function footer_bottom() {
        // Get copyright text
        $copyright_text = get_theme_mod('aqualuxe_copyright_text', sprintf(esc_html__('\u00a9 %s AquaLuxe. All Rights Reserved.', 'aqualuxe'), date('Y')));
        
        ?>
        <div class="aqualuxe-footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="aqualuxe-copyright">
                            <?php echo wp_kses_post($copyright_text); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="aqualuxe-footer-menu">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'container'      => false,
                                'depth'          => 1,
                                'fallback_cb'    => '__return_false',
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Page header
     */
    public function page_header() {
        if (is_front_page()) {
            return;
        }
        
        ?>
        <div class="aqualuxe-page-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-title">
                            <?php
                            if (is_home()) {
                                echo esc_html(get_the_title(get_option('page_for_posts')));
                            } elseif (is_archive()) {
                                the_archive_title();
                            } elseif (is_search()) {
                                /* translators: %s: search query */
                                printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                            } elseif (is_404()) {
                                esc_html_e('Page Not Found', 'aqualuxe');
                            } elseif (is_singular()) {
                                the_title();
                            }
                            ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Breadcrumbs
     */
    public function breadcrumbs() {
        if (is_front_page()) {
            return;
        }
        
        // Use WooCommerce breadcrumbs if available
        if (class_exists('WooCommerce') && function_exists('woocommerce_breadcrumb') && (is_woocommerce() || is_cart() || is_checkout())) {
            woocommerce_breadcrumb();
            return;
        }
        
        // Custom breadcrumbs
        ?>
        <div class="aqualuxe-breadcrumbs">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <nav aria-label="<?php esc_attr_e('Breadcrumbs', 'aqualuxe'); ?>">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo esc_url(home_url('/')); ?>">
                                        <i class="fas fa-home"></i>
                                        <?php esc_html_e('Home', 'aqualuxe'); ?>
                                    </a>
                                </li>
                                
                                <?php
                                if (is_home()) {
                                    // Blog page
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</li>';
                                } elseif (is_category()) {
                                    // Category archive
                                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</a></li>';
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(single_cat_title('', false)) . '</li>';
                                } elseif (is_tag()) {
                                    // Tag archive
                                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</a></li>';
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(single_tag_title('', false)) . '</li>';
                                } elseif (is_author()) {
                                    // Author archive
                                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</a></li>';
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_author()) . '</li>';
                                } elseif (is_year()) {
                                    // Year archive
                                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</a></li>';
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_date('Y')) . '</li>';
                                } elseif (is_month()) {
                                    // Month archive
                                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</a></li>';
                                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_year_link(get_the_date('Y'))) . '">' . esc_html(get_the_date('Y')) . '</a></li>';
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_date('F')) . '</li>';
                                } elseif (is_day()) {
                                    // Day archive
                                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</a></li>';
                                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_year_link(get_the_date('Y'))) . '">' . esc_html(get_the_date('Y')) . '</a></li>';
                                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_month_link(get_the_date('Y'), get_the_date('m'))) . '">' . esc_html(get_the_date('F')) . '</a></li>';
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_date('j')) . '</li>';
                                } elseif (is_search()) {
                                    // Search results
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Search Results', 'aqualuxe') . '</li>';
                                } elseif (is_404()) {
                                    // 404 page
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Page Not Found', 'aqualuxe') . '</li>';
                                } elseif (is_page()) {
                                    // Page
                                    $ancestors = get_post_ancestors(get_the_ID());
                                    if ($ancestors) {
                                        $ancestors = array_reverse($ancestors);
                                        foreach ($ancestors as $ancestor) {
                                            echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a></li>';
                                        }
                                    }
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
                                } elseif (is_singular('post')) {
                                    // Single post
                                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</a></li>';
                                    $categories = get_the_category();
                                    if ($categories) {
                                        $category = $categories[0];
                                        echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                                    }
                                    echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
                                }
                                ?>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Page footer
     */
    public function page_footer() {
        // Implement page footer
    }

    /**
     * Single post header
     */
    public function single_post_header() {
        ?>
        <div class="aqualuxe-single-post-header">
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail('full'); ?>
                </div>
            <?php endif; ?>
            
            <div class="post-meta">
                <span class="post-date">
                    <i class="fas fa-calendar-alt"></i>
                    <?php echo esc_html(get_the_date()); ?>
                </span>
                
                <span class="post-author">
                    <i class="fas fa-user"></i>
                    <?php the_author_posts_link(); ?>
                </span>
                
                <?php if (has_category()) : ?>
                    <span class="post-categories">
                        <i class="fas fa-folder"></i>
                        <?php the_category(', '); ?>
                    </span>
                <?php endif; ?>
                
                <?php if (has_tag()) : ?>
                    <span class="post-tags">
                        <i class="fas fa-tags"></i>
                        <?php the_tags('', ', ', ''); ?>
                    </span>
                <?php endif; ?>
                
                <span class="post-comments">
                    <i class="fas fa-comments"></i>
                    <?php comments_popup_link(
                        esc_html__('No Comments', 'aqualuxe'),
                        esc_html__('1 Comment', 'aqualuxe'),
                        esc_html__('% Comments', 'aqualuxe'),
                        'comments-link',
                        esc_html__('Comments Closed', 'aqualuxe')
                    ); ?>
                </span>
            </div>
        </div>
        <?php
    }

    /**
     * Single post footer
     */
    public function single_post_footer() {
        // Author bio
        $author_bio = get_the_author_meta('description');
        if (!empty($author_bio)) {
            ?>
            <div class="aqualuxe-author-bio">
                <div class="author-avatar">
                    <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                </div>
                
                <div class="author-info">
                    <h3 class="author-name">
                        <?php the_author_posts_link(); ?>
                    </h3>
                    
                    <div class="author-description">
                        <?php echo wpautop(wp_kses_post($author_bio)); ?>
                    </div>
                    
                    <div class="author-links">
                        <?php
                        $author_website = get_the_author_meta('url');
                        if (!empty($author_website)) {
                            echo '<a href="' . esc_url($author_website) . '" target="_blank" rel="noopener noreferrer"><i class="fas fa-globe"></i></a>';
                        }
                        
                        $author_twitter = get_the_author_meta('twitter');
                        if (!empty($author_twitter)) {
                            echo '<a href="' . esc_url($author_twitter) . '" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>';
                        }
                        
                        $author_facebook = get_the_author_meta('facebook');
                        if (!empty($author_facebook)) {
                            echo '<a href="' . esc_url($author_facebook) . '" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>';
                        }
                        
                        $author_instagram = get_the_author_meta('instagram');
                        if (!empty($author_instagram)) {
                            echo '<a href="' . esc_url($author_instagram) . '" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>';
                        }
                        
                        $author_linkedin = get_the_author_meta('linkedin');
                        if (!empty($author_linkedin)) {
                            echo '<a href="' . esc_url($author_linkedin) . '" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
        
        // Social sharing
        ?>
        <div class="aqualuxe-social-sharing">
            <h3><?php esc_html_e('Share This Post', 'aqualuxe'); ?></h3>
            
            <div class="social-icons">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="facebook">
                    <i class="fab fa-facebook-f"></i>
                    <span><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                </a>
                
                <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url(get_permalink()); ?>&text=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="twitter">
                    <i class="fab fa-twitter"></i>
                    <span><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                </a>
                
                <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&media=<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>&description=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="pinterest">
                    <i class="fab fa-pinterest-p"></i>
                    <span><?php esc_html_e('Pinterest', 'aqualuxe'); ?></span>
                </a>
                
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url(get_permalink()); ?>&title=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="linkedin">
                    <i class="fab fa-linkedin-in"></i>
                    <span><?php esc_html_e('LinkedIn', 'aqualuxe'); ?></span>
                </a>
                
                <a href="mailto:?subject=<?php echo esc_attr(get_the_title()); ?>&body=<?php echo esc_url(get_permalink()); ?>" class="email">
                    <i class="fas fa-envelope"></i>
                    <span><?php esc_html_e('Email', 'aqualuxe'); ?></span>
                </a>
            </div>
        </div>
        <?php
    }

    /**
     * Single post navigation
     */
    public function single_post_navigation() {
        the_post_navigation(array(
            'prev_text' => '<span class="nav-subtitle"><i class="fas fa-arrow-left"></i> ' . esc_html__('Previous Post', 'aqualuxe') . '</span><span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next Post', 'aqualuxe') . ' <i class="fas fa-arrow-right"></i></span><span class="nav-title">%title</span>',
        ));
    }

    /**
     * Single post related
     */
    public function single_post_related() {
        $categories = get_the_category();
        
        if (empty($categories)) {
            return;
        }
        
        $category_ids = array();
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
        
        $args = array(
            'category__in'        => $category_ids,
            'post__not_in'        => array(get_the_ID()),
            'posts_per_page'      => 3,
            'ignore_sticky_posts' => 1,
        );
        
        $related_posts = new WP_Query($args);
        
        if ($related_posts->have_posts()) {
            ?>
            <div class="aqualuxe-related-posts">
                <h3><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
                
                <div class="row">
                    <?php
                    while ($related_posts->have_posts()) {
                        $related_posts->the_post();
                        ?>
                        <div class="col-md-4">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('related-post'); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="post-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <header class="entry-header">
                                    <?php the_title('<h4 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h4>'); ?>
                                    
                                    <div class="entry-meta">
                                        <span class="posted-on">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo esc_html(get_the_date()); ?>
                                        </span>
                                    </div>
                                </header>
                                
                                <div class="entry-summary">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <footer class="entry-footer">
                                    <a href="<?php the_permalink(); ?>" class="read-more">
                                        <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </footer>
                            </article>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        
        wp_reset_postdata();
    }

    /**
     * Archive header
     */
    public function archive_header() {
        if (is_archive()) {
            the_archive_description('<div class="archive-description">', '</div>');
        }
    }

    /**
     * Archive footer
     */
    public function archive_footer() {
        // Implement archive footer
    }

    /**
     * Custom comment form
     *
     * @param array $defaults Comment form defaults.
     * @return array Modified defaults.
     */
    public function custom_comment_form($defaults) {
        $defaults['title_reply'] = esc_html__('Leave a Comment', 'aqualuxe');
        $defaults['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title">';
        $defaults['title_reply_after'] = '</h3>';
        $defaults['comment_notes_before'] = '<p class="comment-notes">' . esc_html__('Your email address will not be published. Required fields are marked *', 'aqualuxe') . '</p>';
        $defaults['comment_notes_after'] = '';
        
        return $defaults;
    }

    /**
     * Custom comment fields
     *
     * @param array $fields Comment fields.
     * @return array Modified fields.
     */
    public function custom_comment_fields($fields) {
        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ($req ? ' aria-required="true"' : '');
        
        $fields['author'] = '<div class="comment-form-author form-group">' .
            '<label for="author">' . esc_html__('Name', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label>' .
            '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . $aria_req . ' class="form-control" />' .
            '</div>';
        
        $fields['email'] = '<div class="comment-form-email form-group">' .
            '<label for="email">' . esc_html__('Email', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label>' .
            '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' class="form-control" />' .
            '</div>';
        
        $fields['url'] = '<div class="comment-form-url form-group">' .
            '<label for="url">' . esc_html__('Website', 'aqualuxe') . '</label>' .
            '<input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" class="form-control" />' .
            '</div>';
        
        return $fields;
    }

    /**
     * Custom comment submit button
     *
     * @param string $submit_button Submit button HTML.
     * @param array  $args          Comment form args.
     * @return string Modified submit button HTML.
     */
    public function custom_comment_submit_button($submit_button, $args) {
        $submit_button = '<button name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s">%4$s</button>';
        $submit_button = sprintf(
            $submit_button,
            esc_attr($args['name_submit']),
            esc_attr($args['id_submit']),
            esc_attr($args['class_submit'] . ' btn btn-primary'),
            esc_attr($args['label_submit'])
        );
        
        return $submit_button;
    }

    /**
     * Custom excerpt length
     *
     * @param int $length Excerpt length.
     * @return int Modified length.
     */
    public function custom_excerpt_length($length) {
        return 20;
    }

    /**
     * Custom excerpt more
     *
     * @param string $more Excerpt more.
     * @return string Modified more.
     */
    public function custom_excerpt_more($more) {
        return '&hellip;';
    }

    /**
     * Custom body classes
     *
     * @param array $classes Body classes.
     * @return array Modified classes.
     */
    public function custom_body_classes($classes) {
        // Add theme name class
        $classes[] = 'aqualuxe-theme';
        
        // Add header style class
        $header_style = get_theme_mod('aqualuxe_header_style', 'default');
        $classes[] = 'header-style-' . $header_style;
        
        // Add sidebar position class
        $sidebar_position = get_theme_mod('aqualuxe_sidebar_position', 'right');
        $classes[] = 'sidebar-' . $sidebar_position;
        
        // Add shop layout class
        if (class_exists('WooCommerce') && (is_shop() || is_product_category() || is_product_tag())) {
            $shop_layout = get_theme_mod('aqualuxe_shop_layout', 'grid');
            $classes[] = 'shop-layout-' . $shop_layout;
        }
        
        return $classes;
    }

    /**
     * Custom post classes
     *
     * @param array $classes Post classes.
     * @return array Modified classes.
     */
    public function custom_post_classes($classes) {
        // Add theme name class
        $classes[] = 'aqualuxe-post';
        
        return $classes;
    }

    /**
     * Custom menu classes
     *
     * @param array  $classes Menu item classes.
     * @param object $item    Menu item.
     * @param array  $args    Menu args.
     * @return array Modified classes.
     */
    public function custom_menu_classes($classes, $item, $args) {
        // Add active class
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'active';
        }
        
        return $classes;
    }

    /**
     * Custom menu link attributes
     *
     * @param array  $atts Menu item attributes.
     * @param object $item Menu item.
     * @param array  $args Menu args.
     * @return array Modified attributes.
     */
    public function custom_menu_link_attributes($atts, $item, $args) {
        // Add class to menu links
        if (!isset($atts['class'])) {
            $atts['class'] = '';
        }
        
        $atts['class'] .= ' nav-link';
        
        return $atts;
    }

    /**
     * Custom widget title
     *
     * @param string $title Widget title.
     * @return string Modified title.
     */
    public function custom_widget_title($title) {
        if (empty($title)) {
            return $title;
        }
        
        return $title;
    }

    /**
     * Custom widget params
     *
     * @param array $params Widget params.
     * @return array Modified params.
     */
    public function custom_widget_params($params) {
        return $params;
    }

    /**
     * Custom search form
     *
     * @param string $form Search form HTML.
     * @return string Modified form HTML.
     */
    public function custom_search_form($form) {
        $form = '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">
            <div class="input-group">
                <input type="search" class="search-field form-control" placeholder="' . esc_attr__('Search&hellip;', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" />
                <div class="input-group-append">
                    <button type="submit" class="search-submit btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>';
        
        return $form;
    }

    /**
     * Custom password form
     *
     * @param string $form Password form HTML.
     * @return string Modified form HTML.
     */
    public function custom_password_form($form) {
        global $post;
        
        $label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
        
        $form = '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" class="post-password-form" method="post">
            <p>' . esc_html__('This content is password protected. To view it please enter your password below:', 'aqualuxe') . '</p>
            <div class="input-group">
                <input name="post_password" id="' . esc_attr($label) . '" type="password" class="form-control" placeholder="' . esc_attr__('Password', 'aqualuxe') . '" />
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">' . esc_html__('Submit', 'aqualuxe') . '</button>
                </div>
            </div>
        </form>';
        
        return $form;
    }

    /**
     * Custom gallery
     *
     * @param string $output Gallery output.
     * @param array  $attr   Gallery attributes.
     * @return string Modified output.
     */
    public function custom_gallery($output, $attr) {
        return $output;
    }

    /**
     * Custom image attributes
     *
     * @param array  $attr       Image attributes.
     * @param object $attachment Attachment object.
     * @param mixed  $size       Image size.
     * @return array Modified attributes.
     */
    public function custom_image_attributes($attr, $attachment, $size) {
        // Add lazy loading
        $attr['loading'] = 'lazy';
        
        return $attr;
    }

    /**
     * Custom next posts link attributes
     *
     * @param string $attributes Next posts link attributes.
     * @return string Modified attributes.
     */
    public function custom_next_posts_link_attributes($attributes) {
        return 'class="next-posts-link"';
    }

    /**
     * Custom previous posts link attributes
     *
     * @param string $attributes Previous posts link attributes.
     * @return string Modified attributes.
     */
    public function custom_previous_posts_link_attributes($attributes) {
        return 'class="prev-posts-link"';
    }

    /**
     * Custom admin footer text
     *
     * @param string $text Admin footer text.
     * @return string Modified text.
     */
    public function custom_admin_footer_text($text) {
        return '<span id="footer-thankyou">' . esc_html__('Thank you for creating with AquaLuxe Theme', 'aqualuxe') . '</span>';
    }

    /**
     * Custom admin version footer
     *
     * @param string $text Admin version footer.
     * @return string Modified text.
     */
    public function custom_admin_version_footer($text) {
        return esc_html__('AquaLuxe Theme', 'aqualuxe') . ' ' . AQUALUXE_VERSION;
    }

    /**
     * Custom login header URL
     *
     * @param string $url Login header URL.
     * @return string Modified URL.
     */
    public function custom_login_header_url($url) {
        return home_url('/');
    }

    /**
     * Custom login header text
     *
     * @param string $text Login header text.
     * @return string Modified text.
     */
    public function custom_login_header_text($text) {
        return get_bloginfo('name');
    }

    /**
     * Custom login styles
     */
    public function custom_login_styles() {
        wp_enqueue_style(
            'aqualuxe-login',
            AQUALUXE_ASSETS_URI . '/css/login.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Load more posts
     */
    public function load_more_posts() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-ajax-nonce')) {
            wp_send_json_error(array('message' => esc_html__('Security check failed.', 'aqualuxe')));
            exit;
        }
        
        // Get parameters
        $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
        $category = isset($_POST['category']) ? intval($_POST['category']) : 0;
        $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        
        // Build query args
        $args = array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => get_option('posts_per_page'),
            'paged'          => $paged,
        );
        
        // Add category
        if ($category > 0) {
            $args['cat'] = $category;
        }
        
        // Add tag
        if (!empty($tag)) {
            $args['tag'] = $tag;
        }
        
        // Add search
        if (!empty($search)) {
            $args['s'] = $search;
        }
        
        // Run query
        $query = new WP_Query($args);
        
        // Start output buffering
        ob_start();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                get_template_part('template-parts/content', get_post_format());
            }
        } else {
            echo '<p>' . esc_html__('No posts found.', 'aqualuxe') . '</p>';
        }
        
        $html = ob_get_clean();
        
        // Get pagination
        $pagination = array(
            'current' => $paged,
            'total'   => $query->max_num_pages,
        );
        
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'html'       => $html,
            'pagination' => $pagination,
        ));
        exit;
    }

    /**
     * Button shortcode
     *
     * @param array  $atts    Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string Shortcode output.
     */
    public function button_shortcode($atts, $content = null) {
        $atts = shortcode_atts(array(
            'url'    => '#',
            'target' => '_self',
            'style'  => 'primary',
            'size'   => 'medium',
            'icon'   => '',
        ), $atts);
        
        $classes = array('aqualuxe-button', 'btn');
        $classes[] = 'btn-' . $atts['style'];
        $classes[] = 'btn-' . $atts['size'];
        
        $icon_html = '';
        if (!empty($atts['icon'])) {
            $icon_html = '<i class="' . esc_attr($atts['icon']) . '"></i> ';
        }
        
        $output = '<a href="' . esc_url($atts['url']) . '" target="' . esc_attr($atts['target']) . '" class="' . esc_attr(implode(' ', $classes)) . '">';
        $output .= $icon_html . do_shortcode($content);
        $output .= '</a>';
        
        return $output;
    }

    /**
     * Icon shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function icon_shortcode($atts) {
        $atts = shortcode_atts(array(
            'name'  => 'fas fa-star',
            'color' => '',
            'size'  => '',
        ), $atts);
        
        $style = '';
        if (!empty($atts['color'])) {
            $style .= 'color:' . $atts['color'] . ';';
        }
        if (!empty($atts['size'])) {
            $style .= 'font-size:' . $atts['size'] . ';';
        }
        
        $output = '<i class="' . esc_attr($atts['name']) . '"';
        if (!empty($style)) {
            $output .= ' style="' . esc_attr($style) . '"';
        }
        $output .= '></i>';
        
        return $output;
    }

    /**
     * Products shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function products_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit'   => 4,
            'columns' => 4,
            'orderby' => 'date',
            'order'   => 'desc',
        ), $atts);
        
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        );
        
        return $this->render_products_shortcode($args, $atts);
    }

    /**
     * Featured products shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function featured_products_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit'   => 4,
            'columns' => 4,
            'orderby' => 'date',
            'order'   => 'desc',
        ), $atts);
        
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                ),
            ),
        );
        
        return $this->render_products_shortcode($args, $atts);
    }

    /**
     * Sale products shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function sale_products_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit'   => 4,
            'columns' => 4,
            'orderby' => 'date',
            'order'   => 'desc',
        ), $atts);
        
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
            'meta_query'     => array(
                'relation' => 'OR',
                array(
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
                array(
                    'key'     => '_min_variation_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
            ),
        );
        
        return $this->render_products_shortcode($args, $atts);
    }

    /**
     * Best selling products shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function best_selling_products_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit'   => 4,
            'columns' => 4,
        ), $atts);
        
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $atts['limit'],
            'meta_key'       => 'total_sales',
            'orderby'        => 'meta_value_num',
            'order'          => 'desc',
        );
        
        return $this->render_products_shortcode($args, $atts);
    }

    /**
     * Top rated products shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function top_rated_products_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit'   => 4,
            'columns' => 4,
        ), $atts);
        
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $atts['limit'],
            'meta_key'       => '_wc_average_rating',
            'orderby'        => 'meta_value_num',
            'order'          => 'desc',
        );
        
        return $this->render_products_shortcode($args, $atts);
    }

    /**
     * Product categories shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function product_categories_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit'   => 4,
            'columns' => 4,
            'orderby' => 'name',
            'order'   => 'asc',
            'parent'  => '',
        ), $atts);
        
        $args = array(
            'taxonomy'   => 'product_cat',
            'orderby'    => $atts['orderby'],
            'order'      => $atts['order'],
            'hide_empty' => 1,
            'number'     => $atts['limit'],
        );
        
        if ('' !== $atts['parent']) {
            $args['parent'] = $atts['parent'];
        }
        
        $categories = get_terms($args);
        
        if (is_wp_error($categories) || empty($categories)) {
            return '';
        }
        
        ob_start();
        
        echo '<div class="aqualuxe-product-categories columns-' . esc_attr($atts['columns']) . '">';
        
        foreach ($categories as $category) {
            echo '<div class="product-category">';
            
            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
            $image = wp_get_attachment_image_src($thumbnail_id, 'woocommerce_thumbnail');
            
            echo '<a href="' . esc_url(get_term_link($category)) . '">';
            
            if ($image) {
                echo '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr($category->name) . '" />';
            } else {
                echo wc_placeholder_img();
            }
            
            echo '<h3>' . esc_html($category->name) . '</h3>';
            
            if ($category->count > 0) {
                echo '<span class="count">' . esc_html($category->count) . ' ' . esc_html(_n('product', 'products', $category->count, 'aqualuxe')) . '</span>';
            }
            
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Product brands shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function product_brands_shortcode($atts) {
        if (!class_exists('WooCommerce') || !taxonomy_exists('product_brand')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit'   => 4,
            'columns' => 4,
            'orderby' => 'name',
            'order'   => 'asc',
        ), $atts);
        
        $args = array(
            'taxonomy'   => 'product_brand',
            'orderby'    => $atts['orderby'],
            'order'      => $atts['order'],
            'hide_empty' => 1,
            'number'     => $atts['limit'],
        );
        
        $brands = get_terms($args);
        
        if (is_wp_error($brands) || empty($brands)) {
            return '';
        }
        
        ob_start();
        
        echo '<div class="aqualuxe-product-brands columns-' . esc_attr($atts['columns']) . '">';
        
        foreach ($brands as $brand) {
            echo '<div class="product-brand">';
            
            $thumbnail_id = get_term_meta($brand->term_id, 'thumbnail_id', true);
            $image = wp_get_attachment_image_src($thumbnail_id, 'woocommerce_thumbnail');
            
            echo '<a href="' . esc_url(get_term_link($brand)) . '">';
            
            if ($image) {
                echo '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr($brand->name) . '" />';
            } else {
                echo wc_placeholder_img();
            }
            
            echo '<h3>' . esc_html($brand->name) . '</h3>';
            
            if ($brand->count > 0) {
                echo '<span class="count">' . esc_html($brand->count) . ' ' . esc_html(_n('product', 'products', $brand->count, 'aqualuxe')) . '</span>';
            }
            
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Product tags shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function product_tags_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit'   => 20,
            'orderby' => 'name',
            'order'   => 'asc',
        ), $atts);
        
        $args = array(
            'taxonomy'   => 'product_tag',
            'orderby'    => $atts['orderby'],
            'order'      => $atts['order'],
            'hide_empty' => 1,
            'number'     => $atts['limit'],
        );
        
        $tags = get_terms($args);
        
        if (is_wp_error($tags) || empty($tags)) {
            return '';
        }
        
        ob_start();
        
        echo '<div class="aqualuxe-product-tags">';
        
        foreach ($tags as $tag) {
            echo '<a href="' . esc_url(get_term_link($tag)) . '" class="product-tag">';
            echo esc_html($tag->name);
            echo '<span class="count">(' . esc_html($tag->count) . ')</span>';
            echo '</a>';
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Product attribute shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function product_attribute_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit'     => 4,
            'columns'   => 4,
            'orderby'   => 'date',
            'order'     => 'desc',
            'attribute' => '',
            'terms'     => '',
        ), $atts);
        
        if (empty($atts['attribute'])) {
            return '';
        }
        
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        );
        
        $tax_query = array();
        
        if (!empty($atts['terms'])) {
            $terms = explode(',', $atts['terms']);
            $tax_query[] = array(
                'taxonomy' => 'pa_' . $atts['attribute'],
                'field'    => 'slug',
                'terms'    => $terms,
            );
        } else {
            $tax_query[] = array(
                'taxonomy' => 'pa_' . $atts['attribute'],
                'field'    => 'slug',
                'operator' => 'EXISTS',
            );
        }
        
        $args['tax_query'] = $tax_query;
        
        return $this->render_products_shortcode($args, $atts);
    }

    /**
     * Recent products shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function recent_products_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'limit'   => 4,
            'columns' => 4,
            'orderby' => 'date',
            'order'   => 'desc',
        ), $atts);
        
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $atts['limit'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        );
        
        return $this->render_products_shortcode($args, $atts);
    }

    /**
     * Products by IDs shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function products_by_ids_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'ids'     => '',
            'columns' => 4,
        ), $atts);
        
        if (empty($atts['ids'])) {
            return '';
        }
        
        $ids = explode(',', $atts['ids']);
        $ids = array_map('trim', $ids);
        
        $args = array(
            'post_type'      => 'product',
            'post__in'       => $ids,
            'posts_per_page' => -1,
            'orderby'        => 'post__in',
        );
        
        return $this->render_products_shortcode($args, $atts);
    }

    /**
     * Products by SKU shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function products_by_sku_shortcode($atts) {
        if (!class_exists('WooCommerce')) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'skus'    => '',
            'columns' => 4,
        ), $atts);
        
        if (empty($atts['skus'])) {
            return '';
        }
        
        $skus = explode(',', $atts['skus']);
        $skus = array_map('trim', $skus);
        
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_sku',
                    'value'   => $skus,
                    'compare' => 'IN',
                ),
            ),
        );
        
        return $this->render_products_shortcode($args, $atts);
    }

    /**
     * Render products shortcode
     *
     * @param array $args Query args.
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    private function render_products_shortcode($args, $atts) {
        $products = new WP_Query($args);
        
        if (!$products->have_posts()) {
            return '';
        }
        
        ob_start();
        
        echo '<div class="aqualuxe-products columns-' . esc_attr($atts['columns']) . '">';
        
        woocommerce_product_loop_start();
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
        
        echo '</div>';
        
        wp_reset_postdata();
        
        return ob_get_clean();
    }

    /**
     * Remove version info
     *
     * @param string $generator Generator meta tag.
     * @return string Empty string.
     */
    public function remove_version_info($generator) {
        return '';
    }

    /**
     * Remove version from scripts and styles
     *
     * @param string $src Script or style source URL.
     * @return string Modified URL.
     */
    public function remove_version_from_scripts_styles($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        
        return $src;
    }

    /**
     * Dequeue unnecessary styles
     */
    public function dequeue_unnecessary_styles() {
        // Dequeue unnecessary styles
    }

    /**
     * Dequeue unnecessary scripts
     */
    public function dequeue_unnecessary_scripts() {
        // Dequeue unnecessary scripts
    }

    /**
     * Add async/defer attributes to scripts
     *
     * @param string $tag    Script tag.
     * @param string $handle Script handle.
     * @return string Modified script tag.
     */
    public function add_async_defer_attributes($tag, $handle) {
        // Add async/defer attributes to non-critical scripts
        return $tag;
    }

    /**
     * Custom wp title
     *
     * @param string $title   Page title.
     * @param string $sep     Title separator.
     * @return string Modified title.
     */
    public function custom_wp_title($title, $sep) {
        return $title;
    }

    /**
     * Add meta tags
     */
    public function add_meta_tags() {
        // Add meta description
        if (is_singular()) {
            $description = get_the_excerpt();
            if (empty($description)) {
                $description = wp_trim_words(get_the_content(), 30, '...');
            }
            
            if (!empty($description)) {
                echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\
";
            }
        } elseif (is_home() || is_front_page()) {
            $description = get_bloginfo('description');
            if (!empty($description)) {
                echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\
";
            }
        } elseif (is_category() || is_tag() || is_tax()) {
            $description = term_description();
            if (!empty($description)) {
                echo '<meta name="description" content="' . esc_attr(wp_strip_all_tags($description)) . '" />' . "\
";
            }
        }
        
        // Add meta keywords
        if (is_singular()) {
            $keywords = array();
            
            // Get categories
            $categories = get_the_category();
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $keywords[] = $category->name;
                }
            }
            
            // Get tags
            $tags = get_the_tags();
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $keywords[] = $tag->name;
                }
            }
            
            if (!empty($keywords)) {
                echo '<meta name="keywords" content="' . esc_attr(implode(', ', $keywords)) . '" />' . "\
";
            }
        }
        
        // Add canonical URL
        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '" />' . "\
";
        
        // Add mobile meta tags
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />' . "\
";
        echo '<meta name="mobile-web-app-capable" content="yes" />' . "\
";
        echo '<meta name="apple-mobile-web-app-capable" content="yes" />' . "\
";
        echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\
";
    }

    /**
     * Add Open Graph tags
     */
    public function add_open_graph_tags() {
        // Basic Open Graph tags
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '" />' . "\
";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\
";
        
        if (is_singular()) {
            echo '<meta property="og:type" content="article" />' . "\
";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\
";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\
";
            
            // Description
            $description = get_the_excerpt();
            if (empty($description)) {
                $description = wp_trim_words(get_the_content(), 30, '...');
            }
            
            if (!empty($description)) {
                echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\
";
            }
            
            // Image
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($image) {
                    echo '<meta property="og:image" content="' . esc_url($image[0]) . '" />' . "\
";
                    echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '" />' . "\
";
                    echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '" />' . "\
";
                }
            }
            
            // Article tags
            echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '" />' . "\
";
            echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '" />' . "\
";
            
            // Author
            echo '<meta property="article:author" content="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" />' . "\
";
        } elseif (is_home() || is_front_page()) {
            echo '<meta property="og:type" content="website" />' . "\
";
            echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\
";
            echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '" />' . "\
";
            
            $description = get_bloginfo('description');
            if (!empty($description)) {
                echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\
";
            }
            
            // Site logo
            $custom_logo_id = get_theme_mod('custom_logo');
            if ($custom_logo_id) {
                $image = wp_get_attachment_image_src($custom_logo_id, 'full');
                if ($image) {
                    echo '<meta property="og:image" content="' . esc_url($image[0]) . '" />' . "\
";
                    echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '" />' . "\
";
                    echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '" />' . "\
";
                }
            }
        } elseif (is_archive()) {
            echo '<meta property="og:type" content="website" />' . "\
";
            
            if (is_category() || is_tag() || is_tax()) {
                $term = get_queried_object();
                echo '<meta property="og:title" content="' . esc_attr($term->name) . '" />' . "\
";
                echo '<meta property="og:url" content="' . esc_url(get_term_link($term)) . '" />' . "\
";
                
                $description = term_description();
                if (!empty($description)) {
                    echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags($description)) . '" />' . "\
";
                }
            } else {
                echo '<meta property="og:title" content="' . esc_attr(get_the_archive_title()) . '" />' . "\
";
                echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\
";
            }
        }
    }

    /**
     * Add Twitter Card tags
     */
    public function add_twitter_card_tags() {
        // Basic Twitter Card tags
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\
";
        echo '<meta name="twitter:site" content="@' . esc_attr(str_replace('@', '', get_theme_mod('aqualuxe_twitter_username', ''))) . '" />' . "\
";
        
        if (is_singular()) {
            echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '" />' . "\
";
            
            // Description
            $description = get_the_excerpt();
            if (empty($description)) {
                $description = wp_trim_words(get_the_content(), 30, '...');
            }
            
            if (!empty($description)) {
                echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\
";
            }
            
            // Image
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                if ($image) {
                    echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '" />' . "\
";
                }
            }
            
            // Author
            $twitter_username = get_the_author_meta('twitter');
            if (!empty($twitter_username)) {
                echo '<meta name="twitter:creator" content="@' . esc_attr(str_replace('@', '', $twitter_username)) . '" />' . "\
";
            }
        } elseif (is_home() || is_front_page()) {
            echo '<meta name="twitter:title" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\
";
            
            $description = get_bloginfo('description');
            if (!empty($description)) {
                echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\
";
            }
            
            // Site logo
            $custom_logo_id = get_theme_mod('custom_logo');
            if ($custom_logo_id) {
                $image = wp_get_attachment_image_src($custom_logo_id, 'full');
                if ($image) {
                    echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '" />' . "\
";
                }
            }
        } elseif (is_archive()) {
            if (is_category() || is_tag() || is_tax()) {
                $term = get_queried_object();
                echo '<meta name="twitter:title" content="' . esc_attr($term->name) . '" />' . "\
";
                
                $description = term_description();
                if (!empty($description)) {
                    echo '<meta name="twitter:description" content="' . esc_attr(wp_strip_all_tags($description)) . '" />' . "\
";
                }
            } else {
                echo '<meta name="twitter:title" content="' . esc_attr(get_the_archive_title()) . '" />' . "\
";
            }
        }
    }

    /**
     * Add schema markup
     */
    public function add_schema_markup() {
        // Website schema
        echo '<script type="application/ld+json">' . "\
";
        echo '{' . "\
";
        echo '  "@context": "https://schema.org",' . "\
";
        echo '  "@type": "WebSite",' . "\
";
        echo '  "name": "' . esc_js(get_bloginfo('name')) . '",' . "\
";
        echo '  "url": "' . esc_url(home_url('/')) . '",' . "\
";
        echo '  "potentialAction": {' . "\
";
        echo '    "@type": "SearchAction",' . "\
";
        echo '    "target": "' . esc_url(home_url('/')) . '?s={search_term_string}",' . "\
";
        echo '    "query-input": "required name=search_term_string"' . "\
";
        echo '  }' . "\
";
        echo '}' . "\
";
        echo '</script>' . "\
";
        
        // Organization schema
        echo '<script type="application/ld+json">' . "\
";
        echo '{' . "\
";
        echo '  "@context": "https://schema.org",' . "\
";
        echo '  "@type": "Organization",' . "\
";
        echo '  "name": "' . esc_js(get_bloginfo('name')) . '",' . "\
";
        echo '  "url": "' . esc_url(home_url('/')) . '",' . "\
";
        
        // Logo
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $image = wp_get_attachment_image_src($custom_logo_id, 'full');
            if ($image) {
                echo '  "logo": "' . esc_url($image[0]) . '",' . "\
";
            }
        }
        
        // Social profiles
        echo '  "sameAs": [' . "\
";
        
        $social_profiles = array();
        
        $facebook = get_theme_mod('aqualuxe_facebook_url', '');
        if (!empty($facebook)) {
            $social_profiles[] = $facebook;
        }
        
        $twitter = get_theme_mod('aqualuxe_twitter_url', '');
        if (!empty($twitter)) {
            $social_profiles[] = $twitter;
        }
        
        $instagram = get_theme_mod('aqualuxe_instagram_url', '');
        if (!empty($instagram)) {
            $social_profiles[] = $instagram;
        }
        
        $youtube = get_theme_mod('aqualuxe_youtube_url', '');
        if (!empty($youtube)) {
            $social_profiles[] = $youtube;
        }
        
        echo '    "' . implode('",
    "', array_map('esc_url', $social_profiles)) . '"' . "\
";
        
        echo '  ]' . "\
";
        echo '}' . "\
";
        echo '</script>' . "\
";
        
        // Article schema for single posts
        if (is_singular('post')) {
            echo '<script type="application/ld+json">' . "\
";
            echo '{' . "\
";
            echo '  "@context": "https://schema.org",' . "\
";
            echo '  "@type": "Article",' . "\
";
            echo '  "mainEntityOfPage": {' . "\
";
            echo '    "@type": "WebPage",' . "\
";
            echo '    "@id": "' . esc_url(get_permalink()) . '"' . "\
";
            echo '  },' . "\
";
            echo '  "headline": "' . esc_js(get_the_title()) . '",' . "\
";
            
            // Featured image
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                if ($image) {
                    echo '  "image": "' . esc_url($image[0]) . '",' . "\
";
                }
            }
            
            echo '  "datePublished": "' . esc_attr(get_the_date('c')) . '",' . "\
";
            echo '  "dateModified": "' . esc_attr(get_the_modified_date('c')) . '",' . "\
";
            
            // Author
            echo '  "author": {' . "\
";
            echo '    "@type": "Person",' . "\
";
            echo '    "name": "' . esc_js(get_the_author()) . '"' . "\
";
            echo '  },' . "\
";
            
            // Publisher
            echo '  "publisher": {' . "\
";
            echo '    "@type": "Organization",' . "\
";
            echo '    "name": "' . esc_js(get_bloginfo('name')) . '",' . "\
";
            
            // Logo
            if ($custom_logo_id) {
                $image = wp_get_attachment_image_src($custom_logo_id, 'full');
                if ($image) {
                    echo '    "logo": {' . "\
";
                    echo '      "@type": "ImageObject",' . "\
";
                    echo '      "url": "' . esc_url($image[0]) . '",' . "\
";
                    echo '      "width": "' . esc_attr($image[1]) . '",' . "\
";
                    echo '      "height": "' . esc_attr($image[2]) . '"' . "\
";
                    echo '    }' . "\
";
                }
            }
            
            echo '  }' . "\
";
            echo '}' . "\
";
            echo '</script>' . "\
";
        }
        
        // Product schema for WooCommerce products
        if (class_exists('WooCommerce') && is_product()) {
            global $product;
            
            echo '<script type="application/ld+json">' . "\
";
            echo '{' . "\
";
            echo '  "@context": "https://schema.org",' . "\
";
            echo '  "@type": "Product",' . "\
";
            echo '  "name": "' . esc_js(get_the_title()) . '",' . "\
";
            
            // Description
            $description = $product->get_short_description();
            if (empty($description)) {
                $description = $product->get_description();
            }
            
            if (!empty($description)) {
                echo '  "description": "' . esc_js(wp_strip_all_tags($description)) . '",' . "\
";
            }
            
            // SKU
            $sku = $product->get_sku();
            if (!empty($sku)) {
                echo '  "sku": "' . esc_js($sku) . '",' . "\
";
            }
            
            // MPN
            $mpn = get_post_meta($product->get_id(), '_mpn', true);
            if (!empty($mpn)) {
                echo '  "mpn": "' . esc_js($mpn) . '",' . "\
";
            }
            
            // Brand
            $brands = get_the_terms($product->get_id(), 'product_brand');
            if ($brands && !is_wp_error($brands)) {
                $brand = reset($brands);
                echo '  "brand": {' . "\
";
                echo '    "@type": "Brand",' . "\
";
                echo '    "name": "' . esc_js($brand->name) . '"' . "\
";
                echo '  },' . "\
";
            }
            
            // Image
            if (has_post_thumbnail()) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                if ($image) {
                    echo '  "image": "' . esc_url($image[0]) . '",' . "\
";
                }
            }
            
            // Offers
            echo '  "offers": {' . "\
";
            echo '    "@type": "Offer",' . "\
";
            echo '    "url": "' . esc_url(get_permalink()) . '",' . "\
";
            echo '    "priceCurrency": "' . esc_js(get_woocommerce_currency()) . '",' . "\
";
            echo '    "price": "' . esc_js($product->get_price()) . '",' . "\
";
            echo '    "priceValidUntil": "' . esc_js(date('Y-m-d', strtotime('+1 year'))) . '",' . "\
";
            echo '    "availability": "' . esc_js($product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock') . '"' . "\
";
            echo '  },' . "\
";
            
            // Rating
            if ($product->get_rating_count() > 0) {
                echo '  "aggregateRating": {' . "\
";
                echo '    "@type": "AggregateRating",' . "\
";
                echo '    "ratingValue": "' . esc_js($product->get_average_rating()) . '",' . "\
";
                echo '    "reviewCount": "' . esc_js($product->get_review_count()) . '"' . "\
";
                echo '  }' . "\
";
            }
            
            echo '}' . "\
";
            echo '</script>' . "\
";
        }
    }

    /**
     * Add aria attributes
     *
     * @param array  $atts Menu item attributes.
     * @param object $item Menu item.
     * @param array  $args Menu args.
     * @return array Modified attributes.
     */
    public function add_aria_attributes($atts, $item, $args) {
        // Add aria-current attribute to current menu item
        if (in_array('current-menu-item', $item->classes)) {
            $atts['aria-current'] = 'page';
        }
        
        return $atts;
    }

    /**
     * Add skip links
     *
     * @param string $content Post content.
     * @return string Modified content.
     */
    public function add_skip_links($content) {
        // Add skip links to content
        return $content;
    }

    /**
     * Add mobile meta tags
     */
    public function add_mobile_meta_tags() {
        // Add mobile meta tags
    }

    /**
     * Product wrapper open
     */
    public function product_wrapper_open() {
        echo '<div class="aqualuxe-product-wrapper">';
    }

    /**
     * Product wrapper close
     */
    public function product_wrapper_close() {
        echo '</div>';
    }

    /**
     * Custom sale flash
     *
     * @param string $html    Sale flash HTML.
     * @param object $post    Post object.
     * @param object $product Product object.
     * @return string Modified HTML.
     */
    public function custom_sale_flash($html, $post, $product) {
        if ($product->is_on_sale()) {
            $percentage = 0;
            
            if ($product->is_type('variable')) {
                $prices = $product->get_variation_prices();
                
                if (!empty($prices['regular_price']) && !empty($prices['sale_price'])) {
                    $max_percentage = 0;
                    
                    foreach ($prices['regular_price'] as $key => $regular_price) {
                        $sale_price = $prices['sale_price'][$key];
                        
                        if ($regular_price > 0) {
                            $percentage = round(100 - ($sale_price / $regular_price * 100));
                            $max_percentage = max($max_percentage, $percentage);
                        }
                    }
                    
                    $percentage = $max_percentage;
                }
            } else {
                $regular_price = $product->get_regular_price();
                $sale_price = $product->get_sale_price();
                
                if ($regular_price > 0) {
                    $percentage = round(100 - ($sale_price / $regular_price * 100));
                }
            }
            
            if ($percentage > 0) {
                return '<span class="onsale">-' . $percentage . '%</span>';
            } else {
                return '<span class="onsale">' . esc_html__('Sale!', 'aqualuxe') . '</span>';
            }
        }
        
        return $html;
    }

    /**
     * Add featured badge
     */
    public function add_featured_badge() {
        global $product;
        
        if ($product->is_featured()) {
            echo '<span class="featured-badge">' . esc_html__('Featured', 'aqualuxe') . '</span>';
        }
    }

    /**
     * Add new badge
     */
    public function add_new_badge() {
        global $product;
        
        $newness_days = 30;
        $created = strtotime($product->get_date_created());
        
        if ((time() - (60 * 60 * 24 * $newness_days)) < $created) {
            echo '<span class="new-badge">' . esc_html__('New', 'aqualuxe') . '</span>';
        }
    }

    /**
     * Add out of stock badge
     */
    public function add_out_of_stock_badge() {
        global $product;
        
        if (!$product->is_in_stock()) {
            echo '<span class="out-of-stock-badge">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
        }
    }

    /**
     * Add product countdown
     */
    public function add_product_countdown() {
        global $product;
        
        if ($product->is_on_sale()) {
            $sale_price_dates_to = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
            
            if ($sale_price_dates_to) {
                $now = current_time('timestamp');
                
                if ($now < $sale_price_dates_to) {
                    echo '<div class="aqualuxe-countdown" data-date="' . esc_attr(date('Y/m/d H:i:s', $sale_price_dates_to)) . '">';
                    echo '<div class="countdown-title">' . esc_html__('Sale Ends In:', 'aqualuxe') . '</div>';
                    echo '<div class="countdown-timer">';
                    echo '<div class="countdown-item"><span class="days">00</span><span class="label">' . esc_html__('Days', 'aqualuxe') . '</span></div>';
                    echo '<div class="countdown-item"><span class="hours">00</span><span class="label">' . esc_html__('Hours', 'aqualuxe') . '</span></div>';
                    echo '<div class="countdown-item"><span class="minutes">00</span><span class="label">' . esc_html__('Minutes', 'aqualuxe') . '</span></div>';
                    echo '<div class="countdown-item"><span class="seconds">00</span><span class="label">' . esc_html__('Seconds', 'aqualuxe') . '</span></div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
        }
    }

    /**
     * Add product rating
     */
    public function add_product_rating() {
        global $product;
        
        if ($product->get_rating_count() > 0) {
            echo wc_get_rating_html($product->get_average_rating());
        } else {
            echo '<div class="star-rating"></div>';
        }
    }

    /**
     * Add product excerpt
     */
    public function add_product_excerpt() {
        global $product;
        
        if ($product->get_short_description()) {
            echo '<div class="aqualuxe-product-excerpt">';
            echo wp_kses_post($product->get_short_description());
            echo '</div>';
        }
    }

    /**
     * Add product categories
     */
    public function add_product_categories() {
        global $product;
        
        $categories = wc_get_product_category_list($product->get_id(), ', ');
        
        if ($categories) {
            echo '<div class="aqualuxe-product-categories">';
            echo '<span class="label">' . esc_html__('Categories:', 'aqualuxe') . '</span> ';
            echo wp_kses_post($categories);
            echo '</div>';
        }
    }

    /**
     * Add product tags
     */
    public function add_product_tags() {
        global $product;
        
        $tags = wc_get_product_tag_list($product->get_id(), ', ');
        
        if ($tags) {
            echo '<div class="aqualuxe-product-tags">';
            echo '<span class="label">' . esc_html__('Tags:', 'aqualuxe') . '</span> ';
            echo wp_kses_post($tags);
            echo '</div>';
        }
    }

    /**
     * Add product brands
     */
    public function add_product_brands() {
        global $product;
        
        if (taxonomy_exists('product_brand')) {
            $brands = get_the_terms($product->get_id(), 'product_brand');
            
            if ($brands && !is_wp_error($brands)) {
                echo '<div class="aqualuxe-product-brands">';
                echo '<span class="label">' . esc_html__('Brand:', 'aqualuxe') . '</span> ';
                
                $brand_links = array();
                foreach ($brands as $brand) {
                    $brand_links[] = '<a href="' . esc_url(get_term_link($brand)) . '">' . esc_html($brand->name) . '</a>';
                }
                
                echo implode(', ', $brand_links);
                echo '</div>';
            }
        }
    }

    /**
     * Add product attributes
     */
    public function add_product_attributes() {
        global $product;
        
        $attributes = $product->get_attributes();
        
        if (!empty($attributes)) {
            echo '<div class="aqualuxe-product-attributes">';
            
            foreach ($attributes as $attribute) {
                if ($attribute->get_visible()) {
                    echo '<div class="aqualuxe-product-attribute">';
                    echo '<span class="label">' . esc_html(wc_attribute_label($attribute->get_name())) . ':</span> ';
                    
                    if ($attribute->is_taxonomy()) {
                        $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'));
                        echo esc_html(implode(', ', $values));
                    } else {
                        $values = $attribute->get_options();
                        echo esc_html(implode(', ', $values));
                    }
                    
                    echo '</div>';
                }
            }
            
            echo '</div>';
        }
    }

    /**
     * Add product stock
     */
    public function add_product_stock() {
        global $product;
        
        echo '<div class="aqualuxe-product-stock">';
        echo wc_get_stock_html($product);
        echo '</div>';
    }

    /**
     * Add product sku
     */
    public function add_product_sku() {
        global $product;
        
        if ($product->get_sku()) {
            echo '<div class="aqualuxe-product-sku">';
            echo '<span class="label">' . esc_html__('SKU:', 'aqualuxe') . '</span> ';
            echo esc_html($product->get_sku());
            echo '</div>';
        }
    }

    /**
     * Add product dimensions
     */
    public function add_product_dimensions() {
        global $product;
        
        if ($product->has_dimensions()) {
            echo '<div class="aqualuxe-product-dimensions">';
            echo '<span class="label">' . esc_html__('Dimensions:', 'aqualuxe') . '</span> ';
            echo esc_html(wc_format_dimensions($product->get_dimensions(false)));
            echo '</div>';
        }
    }

    /**
     * Add product weight
     */
    public function add_product_weight() {
        global $product;
        
        if ($product->has_weight()) {
            echo '<div class="aqualuxe-product-weight">';
            echo '<span class="label">' . esc_html__('Weight:', 'aqualuxe') . '</span> ';
            echo esc_html($product->get_weight()) . ' ' . esc_html(get_option('woocommerce_weight_unit'));
            echo '</div>';
        }
    }

    /**
     * Add product shipping class
     */
    public function add_product_shipping_class() {
        global $product;
        
        $shipping_class_id = $product->get_shipping_class_id();
        
        if ($shipping_class_id) {
            $shipping_class = get_term_by('id', $shipping_class_id, 'product_shipping_class');
            
            if ($shipping_class && !is_wp_error($shipping_class)) {
                echo '<div class="aqualuxe-product-shipping-class">';
                echo '<span class="label">' . esc_html__('Shipping Class:', 'aqualuxe') . '</span> ';
                echo esc_html($shipping_class->name);
                echo '</div>';
            }
        }
    }

    /**
     * Add product meta
     */
    public function add_product_meta() {
        // Implement product meta
    }

    /**
     * Add product actions
     */
    public function add_product_actions() {
        // Implement product actions
    }

    /**
     * Add product quick view
     */
    public function add_product_quick_view() {
        global $product;
        
        echo '<div class="aqualuxe-product-quick-view">';
        echo '<a href="#" class="quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<i class="fas fa-eye"></i>';
        echo '<span>' . esc_html__('Quick View', 'aqualuxe') . '</span>';
        echo '</a>';
        echo '</div>';
    }

    /**
     * Add product compare
     */
    public function add_product_compare() {
        global $product;
        
        echo '<div class="aqualuxe-product-compare">';
        echo '<a href="#" class="compare-button" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<i class="fas fa-sync-alt"></i>';
        echo '<span>' . esc_html__('Compare', 'aqualuxe') . '</span>';
        echo '</a>';
        echo '</div>';
    }

    /**
     * Add product wishlist
     */
    public function add_product_wishlist() {
        global $product;
        
        echo '<div class="aqualuxe-product-wishlist">';
        echo '<a href="#" class="wishlist-button" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<i class="fas fa-heart"></i>';
        echo '<span>' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
        echo '</a>';
        echo '</div>';
    }

    /**
     * Add product share
     */
    public function add_product_share() {
        global $product;
        
        echo '<div class="aqualuxe-product-share">';
        echo '<a href="#" class="share-button" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<i class="fas fa-share-alt"></i>';
        echo '<span>' . esc_html__('Share', 'aqualuxe') . '</span>';
        echo '</a>';
        echo '</div>';
    }

    /**
     * Add product video
     */
    public function add_product_video() {
        // Implement product video
    }

    /**
     * Add product 360 view
     */
    public function add_product_360_view() {
        // Implement product 360 view
    }

    /**
     * Add product virtual tour
     */
    public function add_product_virtual_tour() {
        // Implement product virtual tour
    }

    /**
     * Add product augmented reality
     */
    public function add_product_augmented_reality() {
        // Implement product augmented reality
    }

    /**
     * Add product reviews
     */
    public function add_product_reviews() {
        // Implement product reviews
    }
}
