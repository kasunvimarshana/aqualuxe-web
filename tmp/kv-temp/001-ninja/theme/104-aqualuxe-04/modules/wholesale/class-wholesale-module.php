<?php
/**
 * Wholesale Module
 * 
 * Handles B2B wholesale functionality
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Wholesale_Module {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_aqualuxe_wholesale_inquiry', [$this, 'ajax_wholesale_inquiry']);
        add_action('wp_ajax_nopriv_aqualuxe_wholesale_inquiry', [$this, 'ajax_wholesale_inquiry']);
        add_filter('woocommerce_product_get_price', [$this, 'apply_wholesale_pricing'], 10, 2);
        add_shortcode('aqualuxe_wholesale_form', [$this, 'wholesale_form_shortcode']);
        add_shortcode('aqualuxe_wholesale_benefits', [$this, 'wholesale_benefits_shortcode']);
    }
    
    /**
     * Initialize module
     */
    public function init() {
        $this->register_user_roles();
        $this->register_post_types();
    }
    
    /**
     * Register user roles
     */
    private function register_user_roles() {
        // Wholesale customer role
        add_role('wholesale_customer', esc_html__('Wholesale Customer', 'aqualuxe'), [
            'read' => true,
            'view_wholesale_prices' => true,
            'place_wholesale_orders' => true,
            'access_wholesale_content' => true
        ]);
        
        // Distributor role
        add_role('distributor', esc_html__('Distributor', 'aqualuxe'), [
            'read' => true,
            'view_wholesale_prices' => true,
            'place_wholesale_orders' => true,
            'access_wholesale_content' => true,
            'distributor_pricing' => true,
            'bulk_ordering' => true
        ]);
    }
    
    /**
     * Register post types
     */
    private function register_post_types() {
        // Wholesale inquiries
        register_post_type('aqualuxe_wholesale', [
            'labels' => [
                'name' => esc_html__('Wholesale Inquiries', 'aqualuxe'),
                'singular_name' => esc_html__('Wholesale Inquiry', 'aqualuxe'),
                'view_item' => esc_html__('View Inquiry', 'aqualuxe'),
                'search_items' => esc_html__('Search Inquiries', 'aqualuxe'),
                'not_found' => esc_html__('No inquiries found', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-businessman',
            'supports' => ['title', 'custom-fields']
        ]);
    }
    
    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_page_template('templates/page-wholesale.php') || $this->is_wholesale_page()) {
            wp_enqueue_style(
                'aqualuxe-wholesale',
                AQUALUXE_ASSETS_URI . '/css/modules/wholesale.css',
                ['aqualuxe-style'],
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-wholesale',
                AQUALUXE_ASSETS_URI . '/js/modules/wholesale.js',
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-wholesale', 'aqualuxe_wholesale_vars', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_wholesale_nonce'),
                'strings' => [
                    'inquiry_success' => esc_html__('Your wholesale inquiry has been submitted successfully!', 'aqualuxe'),
                    'inquiry_error' => esc_html__('There was an error submitting your inquiry. Please try again.', 'aqualuxe'),
                    'required_fields' => esc_html__('Please fill in all required fields.', 'aqualuxe'),
                ]
            ]);
        }
    }
    
    /**
     * Wholesale form shortcode
     */
    public function wholesale_form_shortcode($atts) {
        $atts = shortcode_atts([
            'title' => 'Wholesale Application',
            'description' => 'Apply for wholesale pricing and exclusive B2B benefits.'
        ], $atts, 'aqualuxe_wholesale_form');
        
        ob_start();
        ?>
        <div class="wholesale-application-form">
            <?php if ($atts['title']) : ?>
                <h3 class="form-title text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php echo esc_html($atts['title']); ?>
                </h3>
            <?php endif; ?>
            
            <?php if ($atts['description']) : ?>
                <p class="form-description text-gray-600 dark:text-gray-400 mb-8">
                    <?php echo esc_html($atts['description']); ?>
                </p>
            <?php endif; ?>
            
            <form class="wholesale-form space-y-6" method="post">
                <?php wp_nonce_field('aqualuxe_wholesale_inquiry', 'wholesale_nonce'); ?>
                
                <!-- Business Information -->
                <div class="form-section">
                    <h4 class="section-title text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Business Information
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="business_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Business Name *
                            </label>
                            <input type="text" id="business_name" name="business_name" required 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        
                        <div class="form-group">
                            <label for="business_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Business Type *
                            </label>
                            <select id="business_type" name="business_type" required 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">Select business type...</option>
                                <option value="pet_store">Pet Store</option>
                                <option value="aquarium_shop">Aquarium Shop</option>
                                <option value="distributor">Distributor</option>
                                <option value="zoo_aquarium">Zoo/Aquarium</option>
                                <option value="hotel_restaurant">Hotel/Restaurant</option>
                                <option value="landscaping">Landscaping Company</option>
                                <option value="online_retailer">Online Retailer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="tax_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tax ID / Business Registration *
                            </label>
                            <input type="text" id="tax_id" name="tax_id" required 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        
                        <div class="form-group">
                            <label for="years_in_business" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Years in Business *
                            </label>
                            <select id="years_in_business" name="years_in_business" required 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">Select...</option>
                                <option value="0-1">0-1 years</option>
                                <option value="2-5">2-5 years</option>
                                <option value="6-10">6-10 years</option>
                                <option value="11-20">11-20 years</option>
                                <option value="20+">20+ years</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="form-section">
                    <h4 class="section-title text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Contact Information
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="contact_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Contact Name *
                            </label>
                            <input type="text" id="contact_name" name="contact_name" required 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Title/Position
                            </label>
                            <input type="text" id="contact_title" name="contact_title" 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address *
                            </label>
                            <input type="email" id="contact_email" name="contact_email" required 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number *
                            </label>
                            <input type="tel" id="contact_phone" name="contact_phone" required 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="business_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Business Address *
                        </label>
                        <textarea id="business_address" name="business_address" rows="3" required
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                    </div>
                </div>
                
                <!-- Business Details -->
                <div class="form-section">
                    <h4 class="section-title text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Business Details
                    </h4>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="monthly_volume" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Expected Monthly Purchase Volume *
                            </label>
                            <select id="monthly_volume" name="monthly_volume" required 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">Select volume...</option>
                                <option value="under_1k">Under $1,000</option>
                                <option value="1k_5k">$1,000 - $5,000</option>
                                <option value="5k_10k">$5,000 - $10,000</option>
                                <option value="10k_25k">$10,000 - $25,000</option>
                                <option value="25k_50k">$25,000 - $50,000</option>
                                <option value="50k_plus">$50,000+</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="product_interest" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Primary Product Interest *
                            </label>
                            <select id="product_interest" name="product_interest" multiple required 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="freshwater_fish">Freshwater Fish</option>
                                <option value="marine_fish">Marine Fish</option>
                                <option value="aquatic_plants">Aquatic Plants</option>
                                <option value="equipment">Equipment & Supplies</option>
                                <option value="foods_chemicals">Foods & Chemicals</option>
                                <option value="aquariums_tanks">Aquariums & Tanks</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="current_suppliers" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Current Suppliers
                        </label>
                        <textarea id="current_suppliers" name="current_suppliers" rows="3"
                                  placeholder="Please list your current aquatic suppliers (optional)"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="additional_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Additional Information
                        </label>
                        <textarea id="additional_info" name="additional_info" rows="4"
                                  placeholder="Tell us more about your business and wholesale needs..."
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                    </div>
                </div>
                
                <!-- Terms and Conditions -->
                <div class="form-section">
                    <div class="form-group">
                        <label class="flex items-start">
                            <input type="checkbox" name="terms_agreement" value="1" required 
                                   class="mt-1 mr-3 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                I agree to the 
                                <a href="#" class="text-primary-600 hover:text-primary-700 underline">wholesale terms and conditions</a>
                                and confirm that all information provided is accurate. *
                            </span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label class="flex items-start">
                            <input type="checkbox" name="marketing_consent" value="1" 
                                   class="mt-1 mr-3 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                I would like to receive wholesale product updates and promotional materials via email.
                            </span>
                        </label>
                    </div>
                </div>
                
                <button type="submit" name="submit_wholesale_inquiry" class="btn btn-primary w-full justify-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Wholesale Application
                </button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Wholesale benefits shortcode
     */
    public function wholesale_benefits_shortcode($atts) {
        $atts = shortcode_atts([
            'title' => 'Wholesale Benefits',
            'columns' => 3
        ], $atts, 'aqualuxe_wholesale_benefits');
        
        $benefits = [
            [
                'icon' => 'fas fa-percentage',
                'title' => 'Wholesale Pricing',
                'description' => 'Competitive wholesale prices with volume discounts up to 40% off retail prices.'
            ],
            [
                'icon' => 'fas fa-shipping-fast',
                'title' => 'Priority Shipping',
                'description' => 'Fast, reliable shipping with special handling for live aquatic animals.'
            ],
            [
                'icon' => 'fas fa-user-tie',
                'title' => 'Dedicated Support',
                'description' => 'Personal account manager and priority customer support for all your needs.'
            ],
            [
                'icon' => 'fas fa-fish',
                'title' => 'Exclusive Access',
                'description' => 'First access to new species, rare fish, and limited edition products.'
            ],
            [
                'icon' => 'fas fa-credit-card',
                'title' => 'Flexible Terms',
                'description' => 'Extended payment terms and credit facilities for qualified businesses.'
            ],
            [
                'icon' => 'fas fa-graduation-cap',
                'title' => 'Training & Resources',
                'description' => 'Access to training materials, care guides, and marketing support.'
            ]
        ];
        
        ob_start();
        ?>
        <div class="wholesale-benefits">
            <?php if ($atts['title']) : ?>
                <h3 class="benefits-title text-2xl font-bold text-gray-900 dark:text-white mb-8 text-center">
                    <?php echo esc_html($atts['title']); ?>
                </h3>
            <?php endif; ?>
            
            <div class="benefits-grid grid grid-cols-1 md:grid-cols-<?php echo esc_attr($atts['columns']); ?> gap-8">
                <?php foreach ($benefits as $benefit) : ?>
                    <div class="benefit-card text-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <div class="benefit-icon w-16 h-16 mx-auto mb-4 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                            <i class="<?php echo esc_attr($benefit['icon']); ?> text-2xl text-primary-600"></i>
                        </div>
                        <h4 class="benefit-title text-lg font-semibold text-gray-900 dark:text-white mb-3">
                            <?php echo esc_html($benefit['title']); ?>
                        </h4>
                        <p class="benefit-description text-gray-600 dark:text-gray-400">
                            <?php echo esc_html($benefit['description']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * AJAX wholesale inquiry handler
     */
    public function ajax_wholesale_inquiry() {
        check_ajax_referer('aqualuxe_wholesale_nonce', 'nonce');
        
        // Sanitize form data
        $business_name = sanitize_text_field($_POST['business_name']);
        $business_type = sanitize_text_field($_POST['business_type']);
        $contact_name = sanitize_text_field($_POST['contact_name']);
        $contact_email = sanitize_email($_POST['contact_email']);
        $contact_phone = sanitize_text_field($_POST['contact_phone']);
        $monthly_volume = sanitize_text_field($_POST['monthly_volume']);
        
        // Validate required fields
        if (!$business_name || !$contact_name || !$contact_email || !$monthly_volume) {
            wp_send_json_error('Required fields are missing');
        }
        
        // Create wholesale inquiry
        $inquiry_id = wp_insert_post([
            'post_type' => 'aqualuxe_wholesale',
            'post_title' => sprintf('Wholesale Inquiry - %s (%s)', $business_name, $contact_name),
            'post_status' => 'publish',
            'meta_input' => [
                'business_name' => $business_name,
                'business_type' => $business_type,
                'contact_name' => $contact_name,
                'contact_email' => $contact_email,
                'contact_phone' => $contact_phone,
                'monthly_volume' => $monthly_volume,
                'inquiry_date' => current_time('mysql'),
                'inquiry_status' => 'pending'
            ]
        ]);
        
        if ($inquiry_id && !is_wp_error($inquiry_id)) {
            // Send confirmation emails
            $this->send_inquiry_confirmation($inquiry_id);
            wp_send_json_success(['message' => 'Wholesale inquiry submitted successfully!']);
        } else {
            wp_send_json_error('Failed to submit inquiry');
        }
    }
    
    /**
     * Apply wholesale pricing
     */
    public function apply_wholesale_pricing($price, $product) {
        if (!$this->is_wholesale_customer()) {
            return $price;
        }
        
        $wholesale_price = get_post_meta($product->get_id(), '_wholesale_price', true);
        
        if ($wholesale_price) {
            return $wholesale_price;
        }
        
        // Apply percentage discount if no specific wholesale price
        $discount_percent = $this->get_wholesale_discount_percent();
        if ($discount_percent > 0) {
            return $price * (1 - ($discount_percent / 100));
        }
        
        return $price;
    }
    
    /**
     * Check if current user is wholesale customer
     */
    private function is_wholesale_customer() {
        if (!is_user_logged_in()) {
            return false;
        }
        
        $user = wp_get_current_user();
        return in_array('wholesale_customer', $user->roles) || in_array('distributor', $user->roles);
    }
    
    /**
     * Get wholesale discount percentage
     */
    private function get_wholesale_discount_percent() {
        if (!is_user_logged_in()) {
            return 0;
        }
        
        $user = wp_get_current_user();
        
        if (in_array('distributor', $user->roles)) {
            return 40; // 40% discount for distributors
        } elseif (in_array('wholesale_customer', $user->roles)) {
            return 25; // 25% discount for wholesale customers
        }
        
        return 0;
    }
    
    /**
     * Check if current page is wholesale-related
     */
    private function is_wholesale_page() {
        return is_page('wholesale') || 
               (is_page() && has_shortcode(get_post()->post_content, 'aqualuxe_wholesale_form'));
    }
    
    /**
     * Send inquiry confirmation emails
     */
    private function send_inquiry_confirmation($inquiry_id) {
        $contact_email = get_post_meta($inquiry_id, 'contact_email', true);
        $contact_name = get_post_meta($inquiry_id, 'contact_name', true);
        $business_name = get_post_meta($inquiry_id, 'business_name', true);
        
        // Customer confirmation
        $subject = 'Wholesale Inquiry Received - AquaLuxe';
        $message = "
            Dear {$contact_name},
            
            Thank you for your wholesale inquiry for {$business_name}.
            
            We have received your application and our wholesale team will review it within 2-3 business days.
            
            You will receive an email with next steps and pricing information once your application is approved.
            
            Inquiry Reference: #{$inquiry_id}
            
            Best regards,
            AquaLuxe Wholesale Team
        ";
        
        wp_mail($contact_email, $subject, $message);
        
        // Admin notification
        $admin_email = get_option('admin_email');
        $admin_subject = 'New Wholesale Inquiry - ' . $business_name;
        $admin_message = "
            New wholesale inquiry received:
            
            Business: {$business_name}
            Contact: {$contact_name}
            Email: {$contact_email}
            Inquiry ID: {$inquiry_id}
            
            Please review and respond to the inquiry in the admin panel.
        ";
        
        wp_mail($admin_email, $admin_subject, $admin_message);
    }
}