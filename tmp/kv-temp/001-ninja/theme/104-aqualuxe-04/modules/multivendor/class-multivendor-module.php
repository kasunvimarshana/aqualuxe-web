<?php
/**
 * Multivendor Module
 * 
 * Handles multivendor marketplace functionality
 * 
 * @package AquaLuxe
 * @subpackage Modules
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Multivendor Module Class
 */
class AquaLuxe_Multivendor_Module {
    
    /**
     * Module configuration
     */
    private $config = [
        'enabled' => true,
        'commission_rate' => 15.0,
        'approval_required' => true,
        'min_payout' => 100.00,
        'auto_approve_products' => false,
        'vendor_registration_enabled' => true
    ];
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize module
     */
    private function init() {
        if (!$this->is_enabled()) {
            return;
        }
        
        $this->setup_hooks();
        $this->register_post_types();
        $this->register_taxonomies();
    }
    
    /**
     * Check if module is enabled
     */
    private function is_enabled() {
        return $this->config['enabled'] && apply_filters('aqualuxe_multivendor_enabled', true);
    }
    
    /**
     * Setup hooks
     */
    private function setup_hooks() {
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('init', [$this, 'add_vendor_role']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_vendor_registration', [$this, 'ajax_vendor_registration']);
        add_action('wp_ajax_nopriv_vendor_registration', [$this, 'ajax_vendor_registration']);
        add_filter('aqualuxe_dashboard_modules', [$this, 'add_dashboard_widget']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_shortcode('vendor_registration', [$this, 'render_vendor_registration']);
        add_shortcode('vendor_dashboard', [$this, 'render_vendor_dashboard']);
        add_shortcode('vendor_directory', [$this, 'render_vendor_directory']);
        
        // WooCommerce integration
        if (aqualuxe_is_woocommerce_active()) {
            add_action('woocommerce_checkout_order_processed', [$this, 'process_vendor_commission'], 10, 3);
            add_filter('woocommerce_product_tabs', [$this, 'add_vendor_tab']);
        }
    }
    
    /**
     * Register multivendor-related post types
     */
    public function register_post_types() {
        // Vendor Application post type
        register_post_type('vendor_application', [
            'labels' => [
                'name' => esc_html__('Vendor Applications', 'aqualuxe'),
                'singular_name' => esc_html__('Vendor Application', 'aqualuxe'),
                'add_new' => esc_html__('Add New Application', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Vendor Application', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Vendor Application', 'aqualuxe'),
                'new_item' => esc_html__('New Vendor Application', 'aqualuxe'),
                'view_item' => esc_html__('View Vendor Application', 'aqualuxe'),
                'search_items' => esc_html__('Search Vendor Applications', 'aqualuxe'),
                'not_found' => esc_html__('No vendor applications found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No vendor applications found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 31,
            'menu_icon' => 'dashicons-store',
            'supports' => ['title', 'editor', 'custom-fields'],
            'show_in_rest' => false,
        ]);
        
        // Vendor Store post type
        register_post_type('vendor_store', [
            'labels' => [
                'name' => esc_html__('Vendor Stores', 'aqualuxe'),
                'singular_name' => esc_html__('Vendor Store', 'aqualuxe'),
                'add_new' => esc_html__('Add New Store', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Vendor Store', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Vendor Store', 'aqualuxe'),
                'new_item' => esc_html__('New Vendor Store', 'aqualuxe'),
                'view_item' => esc_html__('View Vendor Store', 'aqualuxe'),
                'search_items' => esc_html__('Search Vendor Stores', 'aqualuxe'),
                'not_found' => esc_html__('No vendor stores found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No vendor stores found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=vendor_application',
            'query_var' => true,
            'rewrite' => ['slug' => 'vendor-stores'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Vendor Categories
        register_taxonomy('vendor_category', 'vendor_store', [
            'labels' => [
                'name' => esc_html__('Vendor Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Vendor Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Vendor Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Vendor Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Vendor Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Vendor Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Vendor Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Vendor Category Name', 'aqualuxe'),
                'menu_name' => esc_html__('Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'vendor-category'],
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Add vendor user role
     */
    public function add_vendor_role() {
        add_role('vendor', esc_html__('Vendor', 'aqualuxe'), [
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
            'upload_files' => true,
            'manage_vendor_store' => true,
            'edit_vendor_store' => true,
            'publish_vendor_products' => true,
            'edit_vendor_products' => true,
            'delete_vendor_products' => true,
            'view_vendor_reports' => true,
        ]);
    }
    
    /**
     * Enqueue module assets
     */
    public function enqueue_assets() {
        if (is_page_template('page-vendors.php') || 
            is_singular(['vendor_store', 'vendor_application']) ||
            is_post_type_archive('vendor_store') ||
            current_user_can('manage_vendor_store')) {
            
            wp_enqueue_script(
                'aqualuxe-multivendor',
                aqualuxe_asset('js/modules/multivendor.js'),
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-multivendor', 'aqualuxeMultivendor', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_multivendor'),
                'messages' => [
                    'applicationSubmitted' => esc_html__('Vendor application submitted successfully!', 'aqualuxe'),
                    'applicationError' => esc_html__('Error submitting application. Please try again.', 'aqualuxe'),
                ],
                'config' => $this->config,
            ]);
        }
    }
    
    /**
     * AJAX handler for vendor registration
     */
    public function ajax_vendor_registration() {
        check_ajax_referer('aqualuxe_multivendor', 'nonce');
        
        $data = wp_unslash($_POST);
        
        // Sanitize input
        $vendor_data = [
            'business_name' => sanitize_text_field($data['business_name']),
            'contact_name' => sanitize_text_field($data['contact_name']),
            'email' => sanitize_email($data['email']),
            'phone' => sanitize_text_field($data['phone']),
            'website' => esc_url_raw($data['website']),
            'business_type' => sanitize_text_field($data['business_type']),
            'tax_id' => sanitize_text_field($data['tax_id']),
            'business_description' => sanitize_textarea_field($data['business_description']),
            'product_categories' => array_map('sanitize_text_field', $data['product_categories'] ?? []),
            'experience_years' => intval($data['experience_years']),
            'annual_revenue' => sanitize_text_field($data['annual_revenue']),
            'certifications' => sanitize_textarea_field($data['certifications']),
            'references' => sanitize_textarea_field($data['references']),
        ];
        
        // Create vendor application
        $application_id = wp_insert_post([
            'post_title' => sprintf('%s - %s', $vendor_data['business_name'], $vendor_data['contact_name']),
            'post_content' => $vendor_data['business_description'],
            'post_status' => 'pending',
            'post_type' => 'vendor_application',
            'meta_input' => array_merge($vendor_data, [
                'application_date' => current_time('mysql'),
                'status' => 'pending_review',
                'score' => $this->calculate_vendor_score($vendor_data),
            ]),
        ]);
        
        if ($application_id) {
            // Send notification emails
            $this->send_vendor_application_notifications($application_id);
            
            wp_send_json_success([
                'message' => esc_html__('Vendor application submitted successfully! We will review your application and contact you within 5-7 business days.', 'aqualuxe'),
                'application_id' => $application_id,
            ]);
        } else {
            wp_send_json_error(['message' => esc_html__('Error submitting vendor application. Please try again.', 'aqualuxe')]);
        }
    }
    
    /**
     * Process vendor commission on order completion
     */
    public function process_vendor_commission($order_id, $posted_data, $order) {
        if (!function_exists('wc_get_order')) {
            return;
        }
        
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $vendor_id = get_post_meta($product_id, '_vendor_id', true);
            
            if (!$vendor_id) {
                continue;
            }
            
            $line_total = $item->get_total();
            $commission_amount = ($line_total * (100 - $this->config['commission_rate'])) / 100;
            
            // Create commission record
            $this->create_vendor_commission($vendor_id, $order_id, $product_id, $line_total, $commission_amount);
        }
    }
    
    /**
     * Add vendor tab to product pages
     */
    public function add_vendor_tab($tabs) {
        if (!aqualuxe_is_woocommerce_active()) {
            return $tabs;
        }
        
        global $product;
        $vendor_id = get_post_meta($product->get_id(), '_vendor_id', true);
        
        if ($vendor_id) {
            $tabs['vendor'] = [
                'title' => esc_html__('Vendor', 'aqualuxe'),
                'priority' => 25,
                'callback' => [$this, 'render_vendor_tab_content'],
            ];
        }
        
        return $tabs;
    }
    
    /**
     * Render vendor tab content
     */
    public function render_vendor_tab_content() {
        global $product;
        $vendor_id = get_post_meta($product->get_id(), '_vendor_id', true);
        
        if (!$vendor_id) {
            return;
        }
        
        $vendor_store_id = get_user_meta($vendor_id, 'vendor_store_id', true);
        if (!$vendor_store_id) {
            return;
        }
        
        $vendor_store = get_post($vendor_store_id);
        $vendor_user = get_user_by('ID', $vendor_id);
        
        ?>
        <div class="vendor-tab-content">
            <h3><?php echo esc_html($vendor_store->post_title); ?></h3>
            
            <div class="vendor-info">
                <?php if (has_post_thumbnail($vendor_store_id)): ?>
                    <div class="vendor-logo">
                        <?php echo get_the_post_thumbnail($vendor_store_id, 'thumbnail'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="vendor-details">
                    <p><?php echo wp_kses_post(wp_trim_words($vendor_store->post_content, 50)); ?></p>
                    
                    <div class="vendor-meta">
                        <span class="vendor-rating">
                            <?php $this->render_vendor_rating($vendor_id); ?>
                        </span>
                        <span class="vendor-products-count">
                            <?php echo sprintf(esc_html__('%d Products', 'aqualuxe'), $this->get_vendor_products_count($vendor_id)); ?>
                        </span>
                    </div>
                    
                    <div class="vendor-actions">
                        <a href="<?php echo esc_url(get_permalink($vendor_store_id)); ?>" class="btn btn-primary">
                            <?php esc_html_e('Visit Store', 'aqualuxe'); ?>
                        </a>
                        <a href="<?php echo esc_url($this->get_vendor_contact_url($vendor_id)); ?>" class="btn btn-secondary">
                            <?php esc_html_e('Contact Vendor', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Calculate vendor application score
     */
    private function calculate_vendor_score($data) {
        $score = 0;
        
        // Business registration scoring
        if (!empty($data['tax_id'])) {
            $score += 20;
        }
        
        // Website scoring
        if (!empty($data['website']) && filter_var($data['website'], FILTER_VALIDATE_URL)) {
            $score += 15;
        }
        
        // Experience scoring
        if ($data['experience_years'] >= 10) {
            $score += 25;
        } elseif ($data['experience_years'] >= 5) {
            $score += 20;
        } elseif ($data['experience_years'] >= 2) {
            $score += 10;
        }
        
        // Revenue scoring
        switch ($data['annual_revenue']) {
            case '1m_plus':
                $score += 20;
                break;
            case '500k_1m':
                $score += 15;
                break;
            case '100k_500k':
                $score += 10;
                break;
            case '50k_100k':
                $score += 5;
                break;
        }
        
        // Certifications scoring
        if (!empty($data['certifications'])) {
            $score += 10;
        }
        
        // References scoring
        if (!empty($data['references'])) {
            $score += 10;
        }
        
        return $score;
    }
    
    /**
     * Send vendor application notifications
     */
    private function send_vendor_application_notifications($application_id) {
        $application = get_post($application_id);
        $business_name = get_post_meta($application_id, 'business_name', true);
        $contact_name = get_post_meta($application_id, 'contact_name', true);
        $email = get_post_meta($application_id, 'email', true);
        $score = get_post_meta($application_id, 'score', true);
        
        // Send applicant confirmation
        $subject = esc_html__('Vendor Application Received - AquaLuxe Marketplace', 'aqualuxe');
        $message = sprintf(
            esc_html__('Dear %s,

Thank you for your interest in becoming a vendor on the AquaLuxe marketplace.

Business: %s
Application Status: Under Review

Our vendor team will review your application and contact you within 5-7 business days with the approval status and next steps.

Commission Rate: %s%%
Marketplace Benefits:
- Access to our global customer base
- Marketing and promotional support
- Dedicated vendor dashboard
- Real-time sales reporting

Best regards,
The AquaLuxe Vendor Team', 'aqualuxe'),
            $contact_name,
            $business_name,
            $this->config['commission_rate']
        );
        
        wp_mail($email, $subject, $message);
        
        // Send admin notification
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(esc_html__('New Vendor Application: %s', 'aqualuxe'), $business_name);
        
        $admin_message = sprintf(
            esc_html__('A new vendor application has been submitted:

Business: %s
Contact: %s
Email: %s
Score: %d/100

Review the full details in the admin dashboard.', 'aqualuxe'),
            $business_name,
            $contact_name,
            $email,
            $score
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
    }
    
    /**
     * Create vendor commission record
     */
    private function create_vendor_commission($vendor_id, $order_id, $product_id, $line_total, $commission_amount) {
        $commission_id = wp_insert_post([
            'post_title' => sprintf('Commission - Order #%s', $order_id),
            'post_type' => 'vendor_commission',
            'post_status' => 'publish',
            'meta_input' => [
                'vendor_id' => $vendor_id,
                'order_id' => $order_id,
                'product_id' => $product_id,
                'line_total' => $line_total,
                'commission_amount' => $commission_amount,
                'commission_rate' => $this->config['commission_rate'],
                'commission_date' => current_time('mysql'),
                'status' => 'pending',
            ],
        ]);
        
        // Update vendor stats
        if ($commission_id) {
            $this->update_vendor_stats($vendor_id, $commission_amount);
        }
        
        return $commission_id;
    }
    
    /**
     * Update vendor stats
     */
    private function update_vendor_stats($vendor_id, $commission_amount) {
        // Update total earnings
        $total_earnings = get_user_meta($vendor_id, 'vendor_total_earnings', true) ?: 0;
        update_user_meta($vendor_id, 'vendor_total_earnings', $total_earnings + $commission_amount);
        
        // Update sales count
        $sales_count = get_user_meta($vendor_id, 'vendor_sales_count', true) ?: 0;
        update_user_meta($vendor_id, 'vendor_sales_count', $sales_count + 1);
    }
    
    /**
     * Render vendor registration shortcode
     */
    public function render_vendor_registration($atts) {
        $atts = shortcode_atts([
            'title' => esc_html__('Become a Vendor', 'aqualuxe'),
        ], $atts);
        
        ob_start();
        ?>
        <div class="vendor-registration">
            <h2><?php echo esc_html($atts['title']); ?></h2>
            
            <div class="vendor-benefits">
                <h3><?php esc_html_e('Why Sell on AquaLuxe?', 'aqualuxe'); ?></h3>
                <ul>
                    <li><?php echo sprintf(esc_html__('Keep %s%% of your sales', 'aqualuxe'), 100 - $this->config['commission_rate']); ?></li>
                    <li><?php esc_html_e('Access to global aquatic enthusiasts', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Professional marketing support', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Dedicated vendor dashboard', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Real-time sales reporting', 'aqualuxe'); ?></li>
                </ul>
            </div>
            
            <form id="vendor-application-form" class="vendor-form">
                <div class="form-section">
                    <h4><?php esc_html_e('Business Information', 'aqualuxe'); ?></h4>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="business_name"><?php esc_html_e('Business Name', 'aqualuxe'); ?> *</label>
                            <input type="text" name="business_name" id="business_name" required />
                        </div>
                        <div class="form-field">
                            <label for="contact_name"><?php esc_html_e('Contact Name', 'aqualuxe'); ?> *</label>
                            <input type="text" name="contact_name" id="contact_name" required />
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="email"><?php esc_html_e('Email Address', 'aqualuxe'); ?> *</label>
                            <input type="email" name="email" id="email" required />
                        </div>
                        <div class="form-field">
                            <label for="phone"><?php esc_html_e('Phone Number', 'aqualuxe'); ?> *</label>
                            <input type="tel" name="phone" id="phone" required />
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="website"><?php esc_html_e('Website URL', 'aqualuxe'); ?></label>
                            <input type="url" name="website" id="website" />
                        </div>
                        <div class="form-field">
                            <label for="tax_id"><?php esc_html_e('Tax ID / Business License', 'aqualuxe'); ?></label>
                            <input type="text" name="tax_id" id="tax_id" />
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label for="business_type"><?php esc_html_e('Business Type', 'aqualuxe'); ?></label>
                        <select name="business_type" id="business_type">
                            <option value="sole_proprietorship"><?php esc_html_e('Sole Proprietorship', 'aqualuxe'); ?></option>
                            <option value="partnership"><?php esc_html_e('Partnership', 'aqualuxe'); ?></option>
                            <option value="corporation"><?php esc_html_e('Corporation', 'aqualuxe'); ?></option>
                            <option value="llc"><?php esc_html_e('LLC', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="form-section">
                    <h4><?php esc_html_e('Business Details', 'aqualuxe'); ?></h4>
                    
                    <div class="form-field">
                        <label for="business_description"><?php esc_html_e('Business Description', 'aqualuxe'); ?> *</label>
                        <textarea name="business_description" id="business_description" rows="4" 
                                  placeholder="<?php esc_attr_e('Describe your business, products, and what makes you unique...', 'aqualuxe'); ?>" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="experience_years"><?php esc_html_e('Years in Business', 'aqualuxe'); ?></label>
                            <select name="experience_years" id="experience_years">
                                <option value="0"><?php esc_html_e('Less than 1 year', 'aqualuxe'); ?></option>
                                <option value="1"><?php esc_html_e('1-2 years', 'aqualuxe'); ?></option>
                                <option value="3"><?php esc_html_e('3-5 years', 'aqualuxe'); ?></option>
                                <option value="6"><?php esc_html_e('6-10 years', 'aqualuxe'); ?></option>
                                <option value="11"><?php esc_html_e('10+ years', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="annual_revenue"><?php esc_html_e('Annual Revenue', 'aqualuxe'); ?></label>
                            <select name="annual_revenue" id="annual_revenue">
                                <option value="under_50k"><?php esc_html_e('Under $50K', 'aqualuxe'); ?></option>
                                <option value="50k_100k"><?php esc_html_e('$50K - $100K', 'aqualuxe'); ?></option>
                                <option value="100k_500k"><?php esc_html_e('$100K - $500K', 'aqualuxe'); ?></option>
                                <option value="500k_1m"><?php esc_html_e('$500K - $1M', 'aqualuxe'); ?></option>
                                <option value="1m_plus"><?php esc_html_e('$1M+', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label for="certifications"><?php esc_html_e('Certifications & Licenses', 'aqualuxe'); ?></label>
                        <textarea name="certifications" id="certifications" rows="3" 
                                  placeholder="<?php esc_attr_e('List any relevant certifications, licenses, or industry memberships...', 'aqualuxe'); ?>"></textarea>
                    </div>
                    
                    <div class="form-field">
                        <label for="references"><?php esc_html_e('References', 'aqualuxe'); ?></label>
                        <textarea name="references" id="references" rows="3" 
                                  placeholder="<?php esc_attr_e('Provide business references or customer testimonials...', 'aqualuxe'); ?>"></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php esc_html_e('Submit Application', 'aqualuxe'); ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render vendor dashboard shortcode
     */
    public function render_vendor_dashboard($atts) {
        if (!current_user_can('manage_vendor_store')) {
            return '<p>' . esc_html__('Access denied. This dashboard is for approved vendors only.', 'aqualuxe') . '</p>';
        }
        
        $user_id = get_current_user_id();
        $total_earnings = get_user_meta($user_id, 'vendor_total_earnings', true) ?: 0;
        $sales_count = get_user_meta($user_id, 'vendor_sales_count', true) ?: 0;
        $products_count = $this->get_vendor_products_count($user_id);
        
        ob_start();
        ?>
        <div class="vendor-dashboard">
            <h2><?php esc_html_e('Vendor Dashboard', 'aqualuxe'); ?></h2>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <span class="stat-label"><?php esc_html_e('Total Earnings', 'aqualuxe'); ?></span>
                    <span class="stat-value">$<?php echo number_format($total_earnings, 2); ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label"><?php esc_html_e('Total Sales', 'aqualuxe'); ?></span>
                    <span class="stat-value"><?php echo intval($sales_count); ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label"><?php esc_html_e('Products Listed', 'aqualuxe'); ?></span>
                    <span class="stat-value"><?php echo intval($products_count); ?></span>
                </div>
            </div>
            
            <div class="dashboard-sections">
                <div class="section">
                    <h3><?php esc_html_e('Quick Actions', 'aqualuxe'); ?></h3>
                    <div class="action-buttons">
                        <a href="#" class="btn btn-primary"><?php esc_html_e('Add New Product', 'aqualuxe'); ?></a>
                        <a href="#" class="btn btn-secondary"><?php esc_html_e('Manage Inventory', 'aqualuxe'); ?></a>
                        <a href="#" class="btn btn-secondary"><?php esc_html_e('View Orders', 'aqualuxe'); ?></a>
                        <a href="#" class="btn btn-secondary"><?php esc_html_e('Generate Report', 'aqualuxe'); ?></a>
                    </div>
                </div>
                
                <div class="section">
                    <h3><?php esc_html_e('Recent Sales', 'aqualuxe'); ?></h3>
                    <?php $this->render_recent_vendor_sales($user_id); ?>
                </div>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render vendor directory shortcode
     */
    public function render_vendor_directory($atts) {
        $atts = shortcode_atts([
            'count' => 12,
            'category' => '',
        ], $atts);
        
        $args = [
            'post_type' => 'vendor_store',
            'posts_per_page' => intval($atts['count']),
            'post_status' => 'publish',
        ];
        
        if (!empty($atts['category'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'vendor_category',
                    'field' => 'slug',
                    'terms' => $atts['category'],
                ],
            ];
        }
        
        $vendors = get_posts($args);
        
        if (empty($vendors)) {
            return '<p>' . esc_html__('No vendors found.', 'aqualuxe') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="vendor-directory">
            <div class="vendors-grid">
                <?php foreach ($vendors as $vendor): ?>
                    <div class="vendor-card">
                        <?php if (has_post_thumbnail($vendor->ID)): ?>
                            <div class="vendor-logo">
                                <?php echo get_the_post_thumbnail($vendor->ID, 'medium'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="vendor-info">
                            <h3 class="vendor-name">
                                <a href="<?php echo esc_url(get_permalink($vendor->ID)); ?>">
                                    <?php echo esc_html($vendor->post_title); ?>
                                </a>
                            </h3>
                            
                            <div class="vendor-excerpt">
                                <?php echo wp_trim_words($vendor->post_excerpt ?: $vendor->post_content, 20); ?>
                            </div>
                            
                            <div class="vendor-meta">
                                <?php
                                $vendor_user_id = get_post_meta($vendor->ID, 'vendor_user_id', true);
                                if ($vendor_user_id) {
                                    $products_count = $this->get_vendor_products_count($vendor_user_id);
                                    echo '<span class="products-count">' . sprintf(esc_html__('%d Products', 'aqualuxe'), $products_count) . '</span>';
                                    
                                    $this->render_vendor_rating($vendor_user_id);
                                }
                                ?>
                            </div>
                            
                            <div class="vendor-actions">
                                <a href="<?php echo esc_url(get_permalink($vendor->ID)); ?>" class="btn btn-primary">
                                    <?php esc_html_e('Visit Store', 'aqualuxe'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Get vendor products count
     */
    private function get_vendor_products_count($vendor_id) {
        if (!aqualuxe_is_woocommerce_active()) {
            return 0;
        }
        
        $products = get_posts([
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_vendor_id',
                    'value' => $vendor_id,
                    'compare' => '=',
                ],
            ],
            'fields' => 'ids',
        ]);
        
        return count($products);
    }
    
    /**
     * Render vendor rating
     */
    private function render_vendor_rating($vendor_id) {
        $rating = get_user_meta($vendor_id, 'vendor_rating', true) ?: 5.0;
        $rating_count = get_user_meta($vendor_id, 'vendor_rating_count', true) ?: 1;
        
        echo '<div class="vendor-rating">';
        echo '<div class="stars">';
        
        for ($i = 1; $i <= 5; $i++) {
            $class = $i <= $rating ? 'star filled' : 'star';
            echo '<span class="' . esc_attr($class) . '">★</span>';
        }
        
        echo '</div>';
        echo '<span class="rating-text">(' . number_format($rating, 1) . ' - ' . $rating_count . ' reviews)</span>';
        echo '</div>';
    }
    
    /**
     * Get vendor contact URL
     */
    private function get_vendor_contact_url($vendor_id) {
        return '#'; // Implement contact form or messaging system
    }
    
    /**
     * Render recent vendor sales
     */
    private function render_recent_vendor_sales($vendor_id) {
        $commissions = get_posts([
            'post_type' => 'vendor_commission',
            'posts_per_page' => 5,
            'meta_query' => [
                [
                    'key' => 'vendor_id',
                    'value' => $vendor_id,
                    'compare' => '=',
                ],
            ],
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        
        if ($commissions) {
            echo '<table class="vendor-sales-table">';
            echo '<thead><tr><th>' . esc_html__('Date', 'aqualuxe') . '</th><th>' . esc_html__('Order', 'aqualuxe') . '</th><th>' . esc_html__('Amount', 'aqualuxe') . '</th><th>' . esc_html__('Status', 'aqualuxe') . '</th></tr></thead>';
            echo '<tbody>';
            
            foreach ($commissions as $commission) {
                $commission_amount = get_post_meta($commission->ID, 'commission_amount', true);
                $order_id = get_post_meta($commission->ID, 'order_id', true);
                $status = get_post_meta($commission->ID, 'status', true);
                
                echo '<tr>';
                echo '<td>' . esc_html(date('M j, Y', strtotime($commission->post_date))) . '</td>';
                echo '<td>#' . esc_html($order_id) . '</td>';
                echo '<td>$' . esc_html(number_format($commission_amount, 2)) . '</td>';
                echo '<td><span class="status-' . esc_attr($status) . '">' . esc_html(ucfirst($status)) . '</span></td>';
                echo '</tr>';
            }
            
            echo '</tbody></table>';
        } else {
            echo '<p>' . esc_html__('No sales yet. Start selling to see your recent transactions!', 'aqualuxe') . '</p>';
        }
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=vendor_application',
            esc_html__('Vendor Settings', 'aqualuxe'),
            esc_html__('Settings', 'aqualuxe'),
            'manage_options',
            'vendor-settings',
            [$this, 'render_admin_settings']
        );
    }
    
    /**
     * Render admin settings page
     */
    public function render_admin_settings() {
        if (isset($_POST['submit'])) {
            $this->update_config([
                'commission_rate' => floatval($_POST['commission_rate']),
                'approval_required' => isset($_POST['approval_required']),
                'min_payout' => floatval($_POST['min_payout']),
                'auto_approve_products' => isset($_POST['auto_approve_products']),
                'vendor_registration_enabled' => isset($_POST['vendor_registration_enabled']),
            ]);
            
            echo '<div class="notice notice-success"><p>' . esc_html__('Settings updated successfully!', 'aqualuxe') . '</p></div>';
        }
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Vendor Settings', 'aqualuxe'); ?></h1>
            
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Commission Rate (%)', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="commission_rate" value="<?php echo esc_attr($this->config['commission_rate']); ?>" step="0.1" min="0" max="100" />
                            <p class="description"><?php esc_html_e('Percentage commission taken from each sale', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Minimum Payout', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="min_payout" value="<?php echo esc_attr($this->config['min_payout']); ?>" step="0.01" min="0" />
                            <p class="description"><?php esc_html_e('Minimum amount before vendor can request payout', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Require Vendor Approval', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="approval_required" value="1" <?php checked($this->config['approval_required']); ?> />
                            <p class="description"><?php esc_html_e('Require admin approval for new vendor applications', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Auto-approve Products', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="auto_approve_products" value="1" <?php checked($this->config['auto_approve_products']); ?> />
                            <p class="description"><?php esc_html_e('Automatically approve vendor products without review', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Enable Vendor Registration', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="vendor_registration_enabled" value="1" <?php checked($this->config['vendor_registration_enabled']); ?> />
                            <p class="description"><?php esc_html_e('Allow new vendors to register', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget($widgets) {
        $widgets['multivendor'] = [
            'title' => esc_html__('Multivendor Marketplace', 'aqualuxe'),
            'callback' => [$this, 'render_dashboard_widget'],
            'priority' => 70,
        ];
        
        return $widgets;
    }
    
    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        $pending_applications = get_posts([
            'post_type' => 'vendor_application',
            'post_status' => 'pending',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);
        
        $active_vendors = get_users([
            'role' => 'vendor',
            'fields' => 'ID',
        ]);
        
        $active_stores = wp_count_posts('vendor_store');
        
        echo '<div class="aqualuxe-dashboard-widget">';
        echo '<div class="stats-row">';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . count($pending_applications) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Pending Applications', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . count($active_vendors) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Active Vendors', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . intval($active_stores->publish) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Vendor Stores', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Get module configuration
     */
    public function get_config() {
        return $this->config;
    }
    
    /**
     * Update module configuration
     */
    public function update_config($config) {
        $this->config = array_merge($this->config, $config);
        update_option('aqualuxe_multivendor_config', $this->config);
    }
}