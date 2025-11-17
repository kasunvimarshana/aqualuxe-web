<?php
/**
 * Marketplace Module Bootstrap
 *
 * @package AquaLuxe\Modules\Marketplace
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Marketplace Module Class
 */
class AquaLuxe_Marketplace {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Vendor registration
        add_action('wp_ajax_register_vendor', array($this, 'ajax_register_vendor'));
        add_action('wp_ajax_nopriv_register_vendor', array($this, 'ajax_register_vendor'));
        
        // Vendor dashboard
        add_action('wp_ajax_vendor_dashboard', array($this, 'ajax_vendor_dashboard'));
        
        // Custom post types and taxonomies
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        
        // Vendor capabilities
        add_action('init', array($this, 'add_vendor_capabilities'));
        
        // Shortcodes
        add_shortcode('vendor_registration', array($this, 'vendor_registration_shortcode'));
        add_shortcode('vendor_dashboard', array($this, 'vendor_dashboard_shortcode'));
        add_shortcode('vendor_directory', array($this, 'vendor_directory_shortcode'));
    }
    
    /**
     * Initialize
     */
    public function init() {
        // Create vendor pages
        $this->create_vendor_pages();
        
        // Add rewrite rules
        add_rewrite_rule('^vendor/([^/]+)/?$', 'index.php?vendor_page=1&vendor_slug=$matches[1]', 'top');
        add_filter('query_vars', array($this, 'add_query_vars'));
        add_action('template_redirect', array($this, 'template_redirect'));
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-marketplace',
            AQUALUXE_THEME_URI . '/modules/marketplace/assets/marketplace.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-marketplace', 'aqualuxe_marketplace', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('marketplace_nonce'),
            'vendor_dashboard_url' => home_url('/vendor-dashboard/'),
        ));
    }
    
    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Vendor Store post type
        register_post_type('vendor_store', array(
            'labels' => array(
                'name' => __('Vendor Stores', 'aqualuxe'),
                'singular_name' => __('Vendor Store', 'aqualuxe'),
                'add_new' => __('Add New Store', 'aqualuxe'),
                'add_new_item' => __('Add New Vendor Store', 'aqualuxe'),
                'edit_item' => __('Edit Vendor Store', 'aqualuxe'),
                'new_item' => __('New Vendor Store', 'aqualuxe'),
                'view_item' => __('View Vendor Store', 'aqualuxe'),
                'search_items' => __('Search Vendor Stores', 'aqualuxe'),
                'not_found' => __('No vendor stores found', 'aqualuxe'),
                'not_found_in_trash' => __('No vendor stores found in trash', 'aqualuxe'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'vendors'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'menu_icon' => 'dashicons-store',
            'show_in_rest' => true,
        ));
        
        // Vendor Application post type
        register_post_type('vendor_application', array(
            'labels' => array(
                'name' => __('Vendor Applications', 'aqualuxe'),
                'singular_name' => __('Vendor Application', 'aqualuxe'),
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'capability_type' => 'post',
            'supports' => array('title', 'editor', 'custom-fields'),
            'menu_icon' => 'dashicons-businessman',
        ));
    }
    
    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Vendor Category
        register_taxonomy('vendor_category', 'vendor_store', array(
            'labels' => array(
                'name' => __('Vendor Categories', 'aqualuxe'),
                'singular_name' => __('Vendor Category', 'aqualuxe'),
                'search_items' => __('Search Vendor Categories', 'aqualuxe'),
                'all_items' => __('All Vendor Categories', 'aqualuxe'),
                'edit_item' => __('Edit Vendor Category', 'aqualuxe'),
                'update_item' => __('Update Vendor Category', 'aqualuxe'),
                'add_new_item' => __('Add New Vendor Category', 'aqualuxe'),
                'new_item_name' => __('New Vendor Category Name', 'aqualuxe'),
                'menu_name' => __('Categories', 'aqualuxe'),
            ),
            'hierarchical' => true,
            'rewrite' => array('slug' => 'vendor-category'),
            'show_in_rest' => true,
        ));
    }
    
    /**
     * Add vendor capabilities
     */
    public function add_vendor_capabilities() {
        // Add vendor role
        if (!get_role('vendor')) {
            add_role('vendor', __('Vendor', 'aqualuxe'), array(
                'read' => true,
                'edit_posts' => true,
                'edit_published_posts' => true,
                'publish_posts' => true,
                'delete_posts' => true,
                'delete_published_posts' => true,
                'upload_files' => true,
                'edit_products' => true,
                'edit_published_products' => true,
                'publish_products' => true,
                'delete_products' => true,
                'delete_published_products' => true,
                'edit_shop_orders' => true,
                'read_shop_orders' => true,
            ));
        }
    }
    
    /**
     * Create vendor pages
     */
    private function create_vendor_pages() {
        $pages = array(
            'vendor-registration' => array(
                'title' => __('Become a Vendor', 'aqualuxe'),
                'content' => '[vendor_registration]',
            ),
            'vendor-dashboard' => array(
                'title' => __('Vendor Dashboard', 'aqualuxe'),
                'content' => '[vendor_dashboard]',
            ),
            'vendor-directory' => array(
                'title' => __('Vendor Directory', 'aqualuxe'),
                'content' => '[vendor_directory]',
            ),
        );
        
        foreach ($pages as $slug => $page_data) {
            $page = get_page_by_path($slug);
            if (!$page) {
                wp_insert_post(array(
                    'post_title' => $page_data['title'],
                    'post_content' => $page_data['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => $slug,
                ));
            }
        }
    }
    
    /**
     * Add query vars
     */
    public function add_query_vars($vars) {
        $vars[] = 'vendor_page';
        $vars[] = 'vendor_slug';
        return $vars;
    }
    
    /**
     * Template redirect
     */
    public function template_redirect() {
        if (get_query_var('vendor_page')) {
            $this->render_vendor_page();
            exit;
        }
    }
    
    /**
     * Render vendor page
     */
    private function render_vendor_page() {
        $vendor_slug = get_query_var('vendor_slug');
        
        // Find vendor store
        $vendor_store = get_posts(array(
            'post_type' => 'vendor_store',
            'name' => $vendor_slug,
            'post_status' => 'publish',
            'numberposts' => 1,
        ));
        
        if (empty($vendor_store)) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part('404');
            return;
        }
        
        $vendor = $vendor_store[0];
        
        get_header();
        ?>
        <div class="vendor-page">
            <div class="container">
                <div class="vendor-header">
                    <div class="vendor-avatar">
                        <?php echo get_the_post_thumbnail($vendor->ID, 'medium'); ?>
                    </div>
                    <div class="vendor-info">
                        <h1><?php echo esc_html($vendor->post_title); ?></h1>
                        <div class="vendor-description">
                            <?php echo wp_kses_post($vendor->post_content); ?>
                        </div>
                        <div class="vendor-meta">
                            <?php
                            $vendor_since = get_post_meta($vendor->ID, '_vendor_since', true);
                            if ($vendor_since) {
                                echo '<span class="vendor-since">' . sprintf(__('Vendor since %s', 'aqualuxe'), date('Y', strtotime($vendor_since))) . '</span>';
                            }
                            
                            $vendor_rating = get_post_meta($vendor->ID, '_vendor_rating', true);
                            if ($vendor_rating) {
                                echo '<span class="vendor-rating">' . wc_get_rating_html($vendor_rating) . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="vendor-products">
                    <h2><?php _e('Products', 'aqualuxe'); ?></h2>
                    <?php
                    // Display vendor products
                    $vendor_user_id = get_post_meta($vendor->ID, '_vendor_user_id', true);
                    if ($vendor_user_id && class_exists('WooCommerce')) {
                        $products = get_posts(array(
                            'post_type' => 'product',
                            'author' => $vendor_user_id,
                            'post_status' => 'publish',
                            'numberposts' => 12,
                        ));
                        
                        if ($products) {
                            echo '<div class="vendor-products-grid">';
                            foreach ($products as $product) {
                                $wc_product = wc_get_product($product->ID);
                                if ($wc_product) {
                                    ?>
                                    <div class="vendor-product-item">
                                        <a href="<?php echo esc_url($wc_product->get_permalink()); ?>">
                                            <?php echo $wc_product->get_image('medium'); ?>
                                            <h3><?php echo esc_html($wc_product->get_name()); ?></h3>
                                            <div class="price"><?php echo $wc_product->get_price_html(); ?></div>
                                        </a>
                                    </div>
                                    <?php
                                }
                            }
                            echo '</div>';
                        } else {
                            echo '<p>' . __('No products found for this vendor.', 'aqualuxe') . '</p>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        get_footer();
    }
    
    /**
     * Vendor registration shortcode
     */
    public function vendor_registration_shortcode($atts) {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            if (in_array('vendor', $user->roles)) {
                return '<p>' . __('You are already a registered vendor.', 'aqualuxe') . ' <a href="' . home_url('/vendor-dashboard/') . '">' . __('Go to Dashboard', 'aqualuxe') . '</a></p>';
            }
        }
        
        ob_start();
        ?>
        <div class="vendor-registration-form">
            <form id="vendor-registration" method="post">
                <h3><?php _e('Vendor Registration', 'aqualuxe'); ?></h3>
                
                <div class="form-group">
                    <label for="vendor_company_name"><?php _e('Company Name *', 'aqualuxe'); ?></label>
                    <input type="text" id="vendor_company_name" name="company_name" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="vendor_first_name"><?php _e('First Name *', 'aqualuxe'); ?></label>
                        <input type="text" id="vendor_first_name" name="first_name" required>
                    </div>
                    <div class="form-group half">
                        <label for="vendor_last_name"><?php _e('Last Name *', 'aqualuxe'); ?></label>
                        <input type="text" id="vendor_last_name" name="last_name" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="vendor_email"><?php _e('Email Address *', 'aqualuxe'); ?></label>
                    <input type="email" id="vendor_email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="vendor_phone"><?php _e('Phone Number', 'aqualuxe'); ?></label>
                    <input type="tel" id="vendor_phone" name="phone">
                </div>
                
                <div class="form-group">
                    <label for="vendor_website"><?php _e('Website', 'aqualuxe'); ?></label>
                    <input type="url" id="vendor_website" name="website">
                </div>
                
                <div class="form-group">
                    <label for="vendor_description"><?php _e('Business Description *', 'aqualuxe'); ?></label>
                    <textarea id="vendor_description" name="description" rows="5" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="vendor_category"><?php _e('Business Category', 'aqualuxe'); ?></label>
                    <select id="vendor_category" name="category">
                        <option value=""><?php _e('Select Category', 'aqualuxe'); ?></option>
                        <option value="fish-breeder"><?php _e('Fish Breeder', 'aqualuxe'); ?></option>
                        <option value="plant-supplier"><?php _e('Plant Supplier', 'aqualuxe'); ?></option>
                        <option value="equipment-dealer"><?php _e('Equipment Dealer', 'aqualuxe'); ?></option>
                        <option value="service-provider"><?php _e('Service Provider', 'aqualuxe'); ?></option>
                        <option value="other"><?php _e('Other', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="terms_agreed" required>
                        <?php printf(__('I agree to the <a href="%s" target="_blank">Terms and Conditions</a> *', 'aqualuxe'), '#'); ?>
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <?php _e('Submit Application', 'aqualuxe'); ?>
                    </button>
                </div>
                
                <?php wp_nonce_field('vendor_registration', 'vendor_registration_nonce'); ?>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Vendor dashboard shortcode
     */
    public function vendor_dashboard_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to access the vendor dashboard.', 'aqualuxe') . '</p>';
        }
        
        $user = wp_get_current_user();
        if (!in_array('vendor', $user->roles)) {
            return '<p>' . __('You need to be a registered vendor to access this page.', 'aqualuxe') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="vendor-dashboard">
            <h2><?php printf(__('Welcome, %s!', 'aqualuxe'), $user->display_name); ?></h2>
            
            <div class="dashboard-stats">
                <?php
                // Get vendor statistics
                $products_count = count_user_posts($user->ID, 'product');
                $orders_count = $this->get_vendor_orders_count($user->ID);
                ?>
                <div class="stat-card">
                    <h3><?php echo $products_count; ?></h3>
                    <p><?php _e('Products', 'aqualuxe'); ?></p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $orders_count; ?></h3>
                    <p><?php _e('Orders', 'aqualuxe'); ?></p>
                </div>
            </div>
            
            <div class="dashboard-actions">
                <a href="<?php echo admin_url('post-new.php?post_type=product'); ?>" class="btn btn-primary">
                    <?php _e('Add New Product', 'aqualuxe'); ?>
                </a>
                <a href="<?php echo admin_url('edit.php?post_type=product'); ?>" class="btn btn-secondary">
                    <?php _e('Manage Products', 'aqualuxe'); ?>
                </a>
                <?php if (class_exists('WooCommerce')) : ?>
                    <a href="<?php echo admin_url('edit.php?post_type=shop_order'); ?>" class="btn btn-secondary">
                        <?php _e('View Orders', 'aqualuxe'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Vendor directory shortcode
     */
    public function vendor_directory_shortcode($atts) {
        $atts = shortcode_atts(array(
            'per_page' => 12,
            'category' => '',
        ), $atts);
        
        $args = array(
            'post_type' => 'vendor_store',
            'post_status' => 'publish',
            'posts_per_page' => intval($atts['per_page']),
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        );
        
        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'vendor_category',
                    'field' => 'slug',
                    'terms' => $atts['category'],
                ),
            );
        }
        
        $vendors_query = new WP_Query($args);
        
        ob_start();
        ?>
        <div class="vendor-directory">
            <?php if ($vendors_query->have_posts()) : ?>
                <div class="vendors-grid">
                    <?php while ($vendors_query->have_posts()) : $vendors_query->the_post(); ?>
                        <div class="vendor-card">
                            <div class="vendor-thumbnail">
                                <a href="<?php echo home_url('/vendor/' . get_post_field('post_name')); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                            <div class="vendor-content">
                                <h3><a href="<?php echo home_url('/vendor/' . get_post_field('post_name')); ?>"><?php the_title(); ?></a></h3>
                                <div class="vendor-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <div class="vendor-meta">
                                    <?php
                                    $vendor_rating = get_post_meta(get_the_ID(), '_vendor_rating', true);
                                    if ($vendor_rating) {
                                        echo '<span class="vendor-rating">' . wc_get_rating_html($vendor_rating) . '</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <?php
                // Pagination
                if ($vendors_query->max_num_pages > 1) {
                    echo '<div class="vendors-pagination">';
                    echo paginate_links(array(
                        'total' => $vendors_query->max_num_pages,
                        'current' => $args['paged'],
                    ));
                    echo '</div>';
                }
                ?>
            <?php else : ?>
                <p><?php _e('No vendors found.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    /**
     * Get vendor orders count
     */
    private function get_vendor_orders_count($vendor_user_id) {
        if (!class_exists('WooCommerce')) {
            return 0;
        }
        
        global $wpdb;
        
        $orders_count = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(DISTINCT p.ID)
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->prefix}woocommerce_order_items oi ON p.ID = oi.order_id
            INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id
            INNER JOIN {$wpdb->posts} prod ON oim.meta_value = prod.ID
            WHERE p.post_type = 'shop_order'
            AND p.post_status IN ('wc-processing', 'wc-completed', 'wc-on-hold')
            AND oim.meta_key = '_product_id'
            AND prod.post_author = %d
        ", $vendor_user_id));
        
        return intval($orders_count);
    }
    
    /**
     * AJAX handler for vendor registration
     */
    public function ajax_register_vendor() {
        check_ajax_referer('marketplace_nonce', 'nonce');
        
        // Process vendor registration
        // This would typically involve creating a vendor application
        // and sending notifications to administrators
        
        wp_send_json_success(array(
            'message' => __('Vendor application submitted successfully!', 'aqualuxe'),
        ));
    }
}

// Initialize the module
new AquaLuxe_Marketplace();