<?php
/**
 * Franchise Module
 * 
 * Handles franchise and licensing functionality including partner portals
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
 * Franchise Module Class
 */
class AquaLuxe_Franchise_Module {
    
    /**
     * Module configuration
     */
    private $config = [
        'enabled' => true,
        'franchise_fee' => 50000,
        'royalty_percentage' => 7.5,
        'min_territory_size' => 10000,
        'require_approval' => true,
        'training_duration_weeks' => 4,
        'support_levels' => ['basic', 'premium', 'enterprise']
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
        return $this->config['enabled'] && apply_filters('aqualuxe_franchise_enabled', true);
    }
    
    /**
     * Setup hooks
     */
    private function setup_hooks() {
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_submit_franchise_inquiry', [$this, 'ajax_submit_franchise_inquiry']);
        add_action('wp_ajax_nopriv_submit_franchise_inquiry', [$this, 'ajax_submit_franchise_inquiry']);
        add_action('wp_ajax_update_partner_profile', [$this, 'ajax_update_partner_profile']);
        add_action('init', [$this, 'add_partner_role']);
        add_filter('aqualuxe_dashboard_modules', [$this, 'add_dashboard_widget']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_shortcode('franchise_inquiry_form', [$this, 'render_franchise_inquiry_form']);
        add_shortcode('partner_portal', [$this, 'render_partner_portal']);
    }
    
    /**
     * Register franchise-related post types
     */
    public function register_post_types() {
        // Franchise Inquiry post type
        register_post_type('franchise_inquiry', [
            'labels' => [
                'name' => esc_html__('Franchise Inquiries', 'aqualuxe'),
                'singular_name' => esc_html__('Franchise Inquiry', 'aqualuxe'),
                'add_new' => esc_html__('Add New Inquiry', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Franchise Inquiry', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Franchise Inquiry', 'aqualuxe'),
                'new_item' => esc_html__('New Franchise Inquiry', 'aqualuxe'),
                'view_item' => esc_html__('View Franchise Inquiry', 'aqualuxe'),
                'search_items' => esc_html__('Search Franchise Inquiries', 'aqualuxe'),
                'not_found' => esc_html__('No franchise inquiries found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No franchise inquiries found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 27,
            'menu_icon' => 'dashicons-building',
            'supports' => ['title', 'editor', 'custom-fields'],
            'show_in_rest' => false,
        ]);
        
        // Franchise Location post type
        register_post_type('franchise_location', [
            'labels' => [
                'name' => esc_html__('Franchise Locations', 'aqualuxe'),
                'singular_name' => esc_html__('Franchise Location', 'aqualuxe'),
                'add_new' => esc_html__('Add New Location', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Franchise Location', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Franchise Location', 'aqualuxe'),
                'new_item' => esc_html__('New Franchise Location', 'aqualuxe'),
                'view_item' => esc_html__('View Franchise Location', 'aqualuxe'),
                'search_items' => esc_html__('Search Franchise Locations', 'aqualuxe'),
                'not_found' => esc_html__('No franchise locations found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No franchise locations found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'franchise-locations'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 28,
            'menu_icon' => 'dashicons-location',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true,
        ]);
        
        // Training Module post type
        register_post_type('training_module', [
            'labels' => [
                'name' => esc_html__('Training Modules', 'aqualuxe'),
                'singular_name' => esc_html__('Training Module', 'aqualuxe'),
                'add_new' => esc_html__('Add New Module', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Training Module', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Training Module', 'aqualuxe'),
                'new_item' => esc_html__('New Training Module', 'aqualuxe'),
                'view_item' => esc_html__('View Training Module', 'aqualuxe'),
                'search_items' => esc_html__('Search Training Modules', 'aqualuxe'),
                'not_found' => esc_html__('No training modules found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No training modules found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=franchise_location',
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields'],
            'show_in_rest' => false,
        ]);
    }
    
    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Franchise Regions
        register_taxonomy('franchise_region', ['franchise_location', 'franchise_inquiry'], [
            'labels' => [
                'name' => esc_html__('Franchise Regions', 'aqualuxe'),
                'singular_name' => esc_html__('Franchise Region', 'aqualuxe'),
                'search_items' => esc_html__('Search Franchise Regions', 'aqualuxe'),
                'all_items' => esc_html__('All Franchise Regions', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Franchise Region', 'aqualuxe'),
                'update_item' => esc_html__('Update Franchise Region', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Franchise Region', 'aqualuxe'),
                'new_item_name' => esc_html__('New Franchise Region Name', 'aqualuxe'),
                'menu_name' => esc_html__('Regions', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'franchise-region'],
            'show_in_rest' => true,
        ]);
        
        // Training Categories
        register_taxonomy('training_category', 'training_module', [
            'labels' => [
                'name' => esc_html__('Training Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Training Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Training Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Training Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Training Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Training Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Training Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Training Category Name', 'aqualuxe'),
                'menu_name' => esc_html__('Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => false,
            'show_in_rest' => false,
        ]);
    }
    
    /**
     * Add partner user role
     */
    public function add_partner_role() {
        add_role('franchise_partner', esc_html__('Franchise Partner', 'aqualuxe'), [
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
            'upload_files' => true,
            'manage_franchise_location' => true,
            'edit_franchise_location' => true,
            'read_private_training_modules' => true,
        ]);
    }
    
    /**
     * Enqueue module assets
     */
    public function enqueue_assets() {
        if (is_page_template('page-franchise.php') || 
            is_singular(['franchise_location', 'franchise_inquiry']) ||
            is_post_type_archive(['franchise_location'])) {
            
            wp_enqueue_script(
                'aqualuxe-franchise',
                aqualuxe_asset('js/modules/franchise.js'),
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-franchise', 'aqualuxeFranchise', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_franchise'),
                'messages' => [
                    'inquirySubmitted' => esc_html__('Franchise inquiry submitted successfully!', 'aqualuxe'),
                    'inquiryError' => esc_html__('Error submitting inquiry. Please try again.', 'aqualuxe'),
                    'profileUpdated' => esc_html__('Profile updated successfully!', 'aqualuxe'),
                    'profileError' => esc_html__('Error updating profile. Please try again.', 'aqualuxe'),
                ],
                'config' => [
                    'franchiseFee' => $this->config['franchise_fee'],
                    'royaltyPercentage' => $this->config['royalty_percentage'],
                ],
            ]);
        }
    }
    
    /**
     * AJAX handler for franchise inquiry submission
     */
    public function ajax_submit_franchise_inquiry() {
        check_ajax_referer('aqualuxe_franchise', 'nonce');
        
        $data = wp_unslash($_POST);
        
        // Sanitize input
        $inquiry_data = [
            'first_name' => sanitize_text_field($data['first_name']),
            'last_name' => sanitize_text_field($data['last_name']),
            'email' => sanitize_email($data['email']),
            'phone' => sanitize_text_field($data['phone']),
            'location' => sanitize_text_field($data['location']),
            'investment_budget' => floatval($data['investment_budget']),
            'business_experience' => sanitize_textarea_field($data['business_experience']),
            'motivation' => sanitize_textarea_field($data['motivation']),
            'timeline' => sanitize_text_field($data['timeline']),
            'territory_size' => intval($data['territory_size']),
        ];
        
        // Validate required fields
        $required_fields = ['first_name', 'last_name', 'email', 'phone', 'location'];
        foreach ($required_fields as $field) {
            if (empty($inquiry_data[$field])) {
                wp_send_json_error([
                    'message' => sprintf(esc_html__('%s is required.', 'aqualuxe'), ucfirst(str_replace('_', ' ', $field)))
                ]);
            }
        }
        
        // Create franchise inquiry post
        $inquiry_id = wp_insert_post([
            'post_title' => sprintf('%s %s - %s', $inquiry_data['first_name'], $inquiry_data['last_name'], $inquiry_data['location']),
            'post_content' => sprintf(
                "Business Experience:\n%s\n\nMotivation:\n%s",
                $inquiry_data['business_experience'],
                $inquiry_data['motivation']
            ),
            'post_status' => 'pending',
            'post_type' => 'franchise_inquiry',
            'meta_input' => array_merge($inquiry_data, [
                'submission_date' => current_time('mysql'),
                'status' => 'pending_review',
                'score' => $this->calculate_inquiry_score($inquiry_data),
            ]),
        ]);
        
        if ($inquiry_id) {
            // Send notification emails
            $this->send_franchise_inquiry_notifications($inquiry_id);
            
            wp_send_json_success([
                'message' => esc_html__('Franchise inquiry submitted successfully! We will contact you within 48 hours.', 'aqualuxe'),
                'inquiry_id' => $inquiry_id,
            ]);
        } else {
            wp_send_json_error(['message' => esc_html__('Error submitting franchise inquiry. Please try again.', 'aqualuxe')]);
        }
    }
    
    /**
     * AJAX handler for partner profile updates
     */
    public function ajax_update_partner_profile() {
        check_ajax_referer('aqualuxe_franchise', 'nonce');
        
        if (!current_user_can('manage_franchise_location')) {
            wp_send_json_error(['message' => esc_html__('You do not have permission to update profile.', 'aqualuxe')]);
        }
        
        $user_id = get_current_user_id();
        $data = wp_unslash($_POST);
        
        // Update user meta
        $profile_fields = [
            'franchise_location_id',
            'business_hours',
            'contact_phone',
            'manager_name',
            'territory_info',
            'performance_metrics',
        ];
        
        foreach ($profile_fields as $field) {
            if (isset($data[$field])) {
                update_user_meta($user_id, $field, sanitize_text_field($data[$field]));
            }
        }
        
        wp_send_json_success([
            'message' => esc_html__('Profile updated successfully!', 'aqualuxe'),
        ]);
    }
    
    /**
     * Calculate inquiry score for qualification
     */
    private function calculate_inquiry_score($data) {
        $score = 0;
        
        // Investment budget scoring
        if ($data['investment_budget'] >= $this->config['franchise_fee'] * 2) {
            $score += 30;
        } elseif ($data['investment_budget'] >= $this->config['franchise_fee']) {
            $score += 20;
        } else {
            $score += 5;
        }
        
        // Territory size scoring
        if ($data['territory_size'] >= $this->config['min_territory_size'] * 2) {
            $score += 25;
        } elseif ($data['territory_size'] >= $this->config['min_territory_size']) {
            $score += 15;
        } else {
            $score += 5;
        }
        
        // Business experience scoring
        $experience_length = strlen($data['business_experience']);
        if ($experience_length > 500) {
            $score += 25;
        } elseif ($experience_length > 200) {
            $score += 15;
        } else {
            $score += 5;
        }
        
        // Timeline scoring
        switch ($data['timeline']) {
            case '3_months':
                $score += 20;
                break;
            case '6_months':
                $score += 15;
                break;
            case '12_months':
                $score += 10;
                break;
            default:
                $score += 5;
        }
        
        return $score;
    }
    
    /**
     * Send franchise inquiry notifications
     */
    private function send_franchise_inquiry_notifications($inquiry_id) {
        $inquiry = get_post($inquiry_id);
        $first_name = get_post_meta($inquiry_id, 'first_name', true);
        $last_name = get_post_meta($inquiry_id, 'last_name', true);
        $email = get_post_meta($inquiry_id, 'email', true);
        $location = get_post_meta($inquiry_id, 'location', true);
        
        // Send applicant confirmation
        $subject = esc_html__('Franchise Inquiry Received - AquaLuxe', 'aqualuxe');
        $message = sprintf(
            esc_html__('Dear %s %s,

Thank you for your interest in the AquaLuxe franchise opportunity. We have received your inquiry for the %s location.

Our franchise development team will review your application and contact you within 48 hours to discuss the next steps.

Best regards,
The AquaLuxe Franchise Team', 'aqualuxe'),
            $first_name,
            $last_name,
            $location
        );
        
        wp_mail($email, $subject, $message);
        
        // Send admin notification
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(esc_html__('New Franchise Inquiry: %s %s - %s', 'aqualuxe'), $first_name, $last_name, $location);
        $score = get_post_meta($inquiry_id, 'score', true);
        
        $admin_message = sprintf(
            esc_html__('A new franchise inquiry has been submitted:

Name: %s %s
Email: %s
Location: %s
Score: %d/100

Review the full details in the admin dashboard.', 'aqualuxe'),
            $first_name,
            $last_name,
            $email,
            $location,
            $score
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
    }
    
    /**
     * Add admin menu items
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=franchise_location',
            esc_html__('Franchise Settings', 'aqualuxe'),
            esc_html__('Settings', 'aqualuxe'),
            'manage_options',
            'franchise-settings',
            [$this, 'render_admin_settings']
        );
    }
    
    /**
     * Render admin settings page
     */
    public function render_admin_settings() {
        if (isset($_POST['submit'])) {
            // Handle settings update
            $this->update_config([
                'franchise_fee' => floatval($_POST['franchise_fee']),
                'royalty_percentage' => floatval($_POST['royalty_percentage']),
                'min_territory_size' => intval($_POST['min_territory_size']),
                'training_duration_weeks' => intval($_POST['training_duration_weeks']),
                'require_approval' => isset($_POST['require_approval']),
            ]);
            
            echo '<div class="notice notice-success"><p>' . esc_html__('Settings updated successfully!', 'aqualuxe') . '</p></div>';
        }
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Franchise Settings', 'aqualuxe'); ?></h1>
            
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Franchise Fee', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="franchise_fee" value="<?php echo esc_attr($this->config['franchise_fee']); ?>" step="0.01" />
                            <p class="description"><?php esc_html_e('Initial franchise fee in USD', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Royalty Percentage', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="royalty_percentage" value="<?php echo esc_attr($this->config['royalty_percentage']); ?>" step="0.1" min="0" max="100" />%
                            <p class="description"><?php esc_html_e('Monthly royalty percentage of gross sales', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Minimum Territory Size', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="min_territory_size" value="<?php echo esc_attr($this->config['min_territory_size']); ?>" />
                            <p class="description"><?php esc_html_e('Minimum territory population required', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Training Duration', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="training_duration_weeks" value="<?php echo esc_attr($this->config['training_duration_weeks']); ?>" min="1" max="52" />
                            <p class="description"><?php esc_html_e('Required training duration in weeks', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Require Approval', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="require_approval" value="1" <?php checked($this->config['require_approval']); ?> />
                            <p class="description"><?php esc_html_e('Require admin approval for new franchise applications', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Render franchise inquiry form shortcode
     */
    public function render_franchise_inquiry_form($atts) {
        $atts = shortcode_atts([
            'title' => esc_html__('Franchise Inquiry', 'aqualuxe'),
            'class' => 'franchise-inquiry-form',
        ], $atts);
        
        ob_start();
        ?>
        <div class="<?php echo esc_attr($atts['class']); ?>">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            
            <form id="franchise-inquiry-form" method="post">
                <div class="form-row">
                    <div class="form-field">
                        <label for="first_name"><?php esc_html_e('First Name', 'aqualuxe'); ?> *</label>
                        <input type="text" name="first_name" id="first_name" required />
                    </div>
                    <div class="form-field">
                        <label for="last_name"><?php esc_html_e('Last Name', 'aqualuxe'); ?> *</label>
                        <input type="text" name="last_name" id="last_name" required />
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-field">
                        <label for="email"><?php esc_html_e('Email', 'aqualuxe'); ?> *</label>
                        <input type="email" name="email" id="email" required />
                    </div>
                    <div class="form-field">
                        <label for="phone"><?php esc_html_e('Phone', 'aqualuxe'); ?> *</label>
                        <input type="tel" name="phone" id="phone" required />
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-field">
                        <label for="location"><?php esc_html_e('Desired Location', 'aqualuxe'); ?> *</label>
                        <input type="text" name="location" id="location" required />
                    </div>
                    <div class="form-field">
                        <label for="investment_budget"><?php esc_html_e('Investment Budget (USD)', 'aqualuxe'); ?></label>
                        <input type="number" name="investment_budget" id="investment_budget" step="1000" min="0" />
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-field">
                        <label for="territory_size"><?php esc_html_e('Territory Population', 'aqualuxe'); ?></label>
                        <input type="number" name="territory_size" id="territory_size" min="1000" />
                    </div>
                    <div class="form-field">
                        <label for="timeline"><?php esc_html_e('Expected Timeline', 'aqualuxe'); ?></label>
                        <select name="timeline" id="timeline">
                            <option value="3_months"><?php esc_html_e('Within 3 months', 'aqualuxe'); ?></option>
                            <option value="6_months"><?php esc_html_e('Within 6 months', 'aqualuxe'); ?></option>
                            <option value="12_months"><?php esc_html_e('Within 12 months', 'aqualuxe'); ?></option>
                            <option value="no_rush"><?php esc_html_e('No specific timeline', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="form-field">
                    <label for="business_experience"><?php esc_html_e('Business Experience', 'aqualuxe'); ?></label>
                    <textarea name="business_experience" id="business_experience" rows="4" placeholder="<?php esc_attr_e('Tell us about your business and management experience...', 'aqualuxe'); ?>"></textarea>
                </div>
                
                <div class="form-field">
                    <label for="motivation"><?php esc_html_e('Why AquaLuxe?', 'aqualuxe'); ?></label>
                    <textarea name="motivation" id="motivation" rows="4" placeholder="<?php esc_attr_e('What motivates you to join the AquaLuxe franchise family?', 'aqualuxe'); ?>"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php esc_html_e('Submit Inquiry', 'aqualuxe'); ?>
                    </button>
                </div>
            </form>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render partner portal shortcode
     */
    public function render_partner_portal($atts) {
        if (!current_user_can('manage_franchise_location')) {
            return '<p>' . esc_html__('Access denied. This portal is for franchise partners only.', 'aqualuxe') . '</p>';
        }
        
        $user_id = get_current_user_id();
        $franchise_location_id = get_user_meta($user_id, 'franchise_location_id', true);
        
        ob_start();
        ?>
        <div class="partner-portal">
            <h2><?php esc_html_e('Franchise Partner Portal', 'aqualuxe'); ?></h2>
            
            <div class="portal-sections">
                <div class="section">
                    <h3><?php esc_html_e('Performance Dashboard', 'aqualuxe'); ?></h3>
                    <div class="metrics-grid">
                        <div class="metric">
                            <span class="metric-label"><?php esc_html_e('Monthly Sales', 'aqualuxe'); ?></span>
                            <span class="metric-value">$<?php echo number_format(get_user_meta($user_id, 'monthly_sales', true)); ?></span>
                        </div>
                        <div class="metric">
                            <span class="metric-label"><?php esc_html_e('Customer Count', 'aqualuxe'); ?></span>
                            <span class="metric-value"><?php echo intval(get_user_meta($user_id, 'customer_count', true)); ?></span>
                        </div>
                        <div class="metric">
                            <span class="metric-label"><?php esc_html_e('Royalty Due', 'aqualuxe'); ?></span>
                            <span class="metric-value">$<?php echo number_format(get_user_meta($user_id, 'royalty_due', true)); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <h3><?php esc_html_e('Training Modules', 'aqualuxe'); ?></h3>
                    <?php
                    $training_modules = get_posts([
                        'post_type' => 'training_module',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                    ]);
                    
                    if ($training_modules) {
                        echo '<ul class="training-modules">';
                        foreach ($training_modules as $module) {
                            $completed = get_user_meta($user_id, 'training_completed_' . $module->ID, true);
                            echo '<li class="' . ($completed ? 'completed' : 'pending') . '">';
                            echo '<span class="module-title">' . esc_html($module->post_title) . '</span>';
                            echo '<span class="module-status">' . ($completed ? esc_html__('Completed', 'aqualuxe') : esc_html__('Pending', 'aqualuxe')) . '</span>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p>' . esc_html__('No training modules available.', 'aqualuxe') . '</p>';
                    }
                    ?>
                </div>
                
                <div class="section">
                    <h3><?php esc_html_e('Support Resources', 'aqualuxe'); ?></h3>
                    <div class="resources-list">
                        <a href="#" class="resource-link"><?php esc_html_e('Marketing Materials', 'aqualuxe'); ?></a>
                        <a href="#" class="resource-link"><?php esc_html_e('Operations Manual', 'aqualuxe'); ?></a>
                        <a href="#" class="resource-link"><?php esc_html_e('Contact Support', 'aqualuxe'); ?></a>
                        <a href="#" class="resource-link"><?php esc_html_e('Submit Support Ticket', 'aqualuxe'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget($widgets) {
        $widgets['franchise'] = [
            'title' => esc_html__('Franchise Management', 'aqualuxe'),
            'callback' => [$this, 'render_dashboard_widget'],
            'priority' => 40,
        ];
        
        return $widgets;
    }
    
    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        $pending_inquiries = get_posts([
            'post_type' => 'franchise_inquiry',
            'post_status' => 'pending',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);
        
        $active_locations = wp_count_posts('franchise_location');
        
        echo '<div class="aqualuxe-dashboard-widget">';
        echo '<div class="stats-row">';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . count($pending_inquiries) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Pending Inquiries', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . intval($active_locations->publish) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Active Locations', 'aqualuxe') . '</span>';
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
        update_option('aqualuxe_franchise_config', $this->config);
    }
}