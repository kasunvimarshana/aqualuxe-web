<?php
/**
 * Affiliates Module
 * 
 * Handles affiliate and referral program functionality
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
 * Affiliates Module Class
 */
class AquaLuxe_Affiliates_Module {
    
    /**
     * Module configuration
     */
    private $config = [
        'enabled' => true,
        'commission_rate' => 10.0,
        'cookie_duration' => 30,
        'min_payout' => 50.00,
        'payment_schedule' => 'monthly',
        'approval_required' => true
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
    }
    
    /**
     * Check if module is enabled
     */
    private function is_enabled() {
        return $this->config['enabled'] && apply_filters('aqualuxe_affiliates_enabled', true);
    }
    
    /**
     * Setup hooks
     */
    private function setup_hooks() {
        add_action('init', [$this, 'register_post_types']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_apply_affiliate', [$this, 'ajax_apply_affiliate']);
        add_action('wp_ajax_nopriv_apply_affiliate', [$this, 'ajax_apply_affiliate']);
        add_action('init', [$this, 'track_referral']);
        add_action('woocommerce_checkout_order_processed', [$this, 'process_affiliate_commission'], 10, 3);
        add_filter('aqualuxe_dashboard_modules', [$this, 'add_dashboard_widget']);
        add_action('init', [$this, 'add_affiliate_role']);
        add_shortcode('affiliate_registration', [$this, 'render_affiliate_registration']);
        add_shortcode('affiliate_dashboard', [$this, 'render_affiliate_dashboard']);
        add_shortcode('referral_link_generator', [$this, 'render_referral_link_generator']);
    }
    
    /**
     * Register affiliate-related post types
     */
    public function register_post_types() {
        // Affiliate Application post type
        register_post_type('affiliate_application', [
            'labels' => [
                'name' => esc_html__('Affiliate Applications', 'aqualuxe'),
                'singular_name' => esc_html__('Affiliate Application', 'aqualuxe'),
                'add_new' => esc_html__('Add New Application', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Affiliate Application', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Affiliate Application', 'aqualuxe'),
                'new_item' => esc_html__('New Affiliate Application', 'aqualuxe'),
                'view_item' => esc_html__('View Affiliate Application', 'aqualuxe'),
                'search_items' => esc_html__('Search Affiliate Applications', 'aqualuxe'),
                'not_found' => esc_html__('No affiliate applications found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No affiliate applications found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 30,
            'menu_icon' => 'dashicons-groups',
            'supports' => ['title', 'editor', 'custom-fields'],
            'show_in_rest' => false,
        ]);
        
        // Commission post type
        register_post_type('affiliate_commission', [
            'labels' => [
                'name' => esc_html__('Commissions', 'aqualuxe'),
                'singular_name' => esc_html__('Commission', 'aqualuxe'),
                'add_new' => esc_html__('Add New Commission', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Commission', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Commission', 'aqualuxe'),
                'new_item' => esc_html__('New Commission', 'aqualuxe'),
                'view_item' => esc_html__('View Commission', 'aqualuxe'),
                'search_items' => esc_html__('Search Commissions', 'aqualuxe'),
                'not_found' => esc_html__('No commissions found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No commissions found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=affiliate_application',
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => ['title', 'custom-fields'],
            'show_in_rest' => false,
        ]);
    }
    
    /**
     * Add affiliate user role
     */
    public function add_affiliate_role() {
        add_role('affiliate', esc_html__('Affiliate', 'aqualuxe'), [
            'read' => true,
            'view_affiliate_dashboard' => true,
            'generate_referral_links' => true,
            'view_commission_reports' => true,
        ]);
    }
    
    /**
     * Enqueue module assets
     */
    public function enqueue_assets() {
        if (is_page_template('page-affiliates.php') || 
            is_singular(['affiliate_application', 'affiliate_commission']) ||
            current_user_can('view_affiliate_dashboard')) {
            
            wp_enqueue_script(
                'aqualuxe-affiliates',
                aqualuxe_asset('js/modules/affiliates.js'),
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-affiliates', 'aqualuxeAffiliates', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_affiliates'),
                'messages' => [
                    'applicationSubmitted' => esc_html__('Affiliate application submitted successfully!', 'aqualuxe'),
                    'applicationError' => esc_html__('Error submitting application. Please try again.', 'aqualuxe'),
                    'linkGenerated' => esc_html__('Referral link generated successfully!', 'aqualuxe'),
                    'linkError' => esc_html__('Error generating referral link. Please try again.', 'aqualuxe'),
                ],
                'config' => [
                    'commissionRate' => $this->config['commission_rate'],
                    'cookieDuration' => $this->config['cookie_duration'],
                ],
            ]);
        }
    }
    
    /**
     * AJAX handler for affiliate applications
     */
    public function ajax_apply_affiliate() {
        check_ajax_referer('aqualuxe_affiliates', 'nonce');
        
        $data = wp_unslash($_POST);
        
        // Sanitize input
        $application_data = [
            'name' => sanitize_text_field($data['name']),
            'email' => sanitize_email($data['email']),
            'website' => esc_url_raw($data['website']),
            'phone' => sanitize_text_field($data['phone']),
            'marketing_channels' => sanitize_textarea_field($data['marketing_channels']),
            'audience_size' => intval($data['audience_size']),
            'experience' => sanitize_textarea_field($data['experience']),
            'motivation' => sanitize_textarea_field($data['motivation']),
            'social_media' => array_map('esc_url_raw', $data['social_media'] ?? []),
        ];
        
        // Create affiliate application
        $application_id = wp_insert_post([
            'post_title' => sprintf('%s - %s', $application_data['name'], $application_data['email']),
            'post_content' => sprintf(
                "Marketing Channels:\n%s\n\nExperience:\n%s\n\nMotivation:\n%s",
                $application_data['marketing_channels'],
                $application_data['experience'],
                $application_data['motivation']
            ),
            'post_status' => 'pending',
            'post_type' => 'affiliate_application',
            'meta_input' => array_merge($application_data, [
                'application_date' => current_time('mysql'),
                'status' => 'pending_review',
                'score' => $this->calculate_application_score($application_data),
            ]),
        ]);
        
        if ($application_id) {
            // Send notification emails
            $this->send_affiliate_application_notifications($application_id);
            
            wp_send_json_success([
                'message' => esc_html__('Affiliate application submitted successfully! We will review your application and contact you within 3-5 business days.', 'aqualuxe'),
                'application_id' => $application_id,
            ]);
        } else {
            wp_send_json_error(['message' => esc_html__('Error submitting affiliate application. Please try again.', 'aqualuxe')]);
        }
    }
    
    /**
     * Track referrals
     */
    public function track_referral() {
        if (isset($_GET['ref']) && !empty($_GET['ref'])) {
            $affiliate_code = sanitize_text_field($_GET['ref']);
            
            // Validate affiliate code
            $affiliate = get_users([
                'meta_key' => 'affiliate_code',
                'meta_value' => $affiliate_code,
                'number' => 1,
            ]);
            
            if (!empty($affiliate)) {
                // Set cookie for tracking
                $cookie_duration = $this->config['cookie_duration'] * 24 * 60 * 60; // Convert days to seconds
                setcookie('aqualuxe_referral', $affiliate_code, time() + $cookie_duration, '/');
                
                // Log the visit
                $this->log_referral_visit($affiliate[0]->ID);
            }
        }
    }
    
    /**
     * Process affiliate commission on order completion
     */
    public function process_affiliate_commission($order_id, $posted_data, $order) {
        if (!function_exists('wc_get_order')) {
            return;
        }
        
        // Check if there's a referral cookie
        if (!isset($_COOKIE['aqualuxe_referral'])) {
            return;
        }
        
        $affiliate_code = sanitize_text_field($_COOKIE['aqualuxe_referral']);
        
        // Get affiliate user
        $affiliate = get_users([
            'meta_key' => 'affiliate_code',
            'meta_value' => $affiliate_code,
            'number' => 1,
        ]);
        
        if (empty($affiliate)) {
            return;
        }
        
        $affiliate_user = $affiliate[0];
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return;
        }
        
        // Calculate commission
        $order_total = $order->get_total();
        $commission_amount = ($order_total * $this->config['commission_rate']) / 100;
        
        // Create commission record
        $commission_id = wp_insert_post([
            'post_title' => sprintf('Commission for Order #%s', $order_id),
            'post_type' => 'affiliate_commission',
            'post_status' => 'publish',
            'meta_input' => [
                'affiliate_id' => $affiliate_user->ID,
                'order_id' => $order_id,
                'order_total' => $order_total,
                'commission_amount' => $commission_amount,
                'commission_rate' => $this->config['commission_rate'],
                'commission_date' => current_time('mysql'),
                'status' => 'pending',
            ],
        ]);
        
        if ($commission_id) {
            // Update affiliate stats
            $this->update_affiliate_stats($affiliate_user->ID, $commission_amount);
            
            // Send notification to affiliate
            $this->send_commission_notification($affiliate_user, $commission_amount, $order_id);
            
            // Clear referral cookie
            setcookie('aqualuxe_referral', '', time() - 3600, '/');
        }
    }
    
    /**
     * Calculate application score
     */
    private function calculate_application_score($data) {
        $score = 0;
        
        // Website scoring
        if (!empty($data['website']) && filter_var($data['website'], FILTER_VALIDATE_URL)) {
            $score += 25;
        }
        
        // Audience size scoring
        if ($data['audience_size'] >= 10000) {
            $score += 30;
        } elseif ($data['audience_size'] >= 5000) {
            $score += 20;
        } elseif ($data['audience_size'] >= 1000) {
            $score += 10;
        }
        
        // Experience scoring
        $experience_length = strlen($data['experience']);
        if ($experience_length > 300) {
            $score += 25;
        } elseif ($experience_length > 150) {
            $score += 15;
        } else {
            $score += 5;
        }
        
        // Social media presence
        $social_count = count($data['social_media']);
        $score += min($social_count * 5, 20);
        
        return $score;
    }
    
    /**
     * Send affiliate application notifications
     */
    private function send_affiliate_application_notifications($application_id) {
        $application = get_post($application_id);
        $name = get_post_meta($application_id, 'name', true);
        $email = get_post_meta($application_id, 'email', true);
        $score = get_post_meta($application_id, 'score', true);
        
        // Send applicant confirmation
        $subject = esc_html__('Affiliate Application Received - AquaLuxe', 'aqualuxe');
        $message = sprintf(
            esc_html__('Dear %s,

Thank you for your interest in becoming an AquaLuxe affiliate partner.

We have received your application and our team will review it within 3-5 business days. You will receive an email with the approval status and next steps.

Commission Rate: %s%%
Cookie Duration: %d days

Best regards,
The AquaLuxe Affiliate Team', 'aqualuxe'),
            $name,
            $this->config['commission_rate'],
            $this->config['cookie_duration']
        );
        
        wp_mail($email, $subject, $message);
        
        // Send admin notification
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(esc_html__('New Affiliate Application: %s', 'aqualuxe'), $name);
        
        $admin_message = sprintf(
            esc_html__('A new affiliate application has been submitted:

Name: %s
Email: %s
Website: %s
Score: %d/100

Review the full details in the admin dashboard.', 'aqualuxe'),
            $name,
            $email,
            get_post_meta($application_id, 'website', true),
            $score
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
    }
    
    /**
     * Log referral visit
     */
    private function log_referral_visit($affiliate_id) {
        $visits = get_user_meta($affiliate_id, 'referral_visits', true) ?: [];
        $visits[] = [
            'date' => current_time('mysql'),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'url' => $_SERVER['REQUEST_URI'],
        ];
        
        // Keep only last 100 visits
        if (count($visits) > 100) {
            $visits = array_slice($visits, -100);
        }
        
        update_user_meta($affiliate_id, 'referral_visits', $visits);
        
        // Update visit count
        $visit_count = get_user_meta($affiliate_id, 'total_visits', true) ?: 0;
        update_user_meta($affiliate_id, 'total_visits', $visit_count + 1);
    }
    
    /**
     * Update affiliate stats
     */
    private function update_affiliate_stats($affiliate_id, $commission_amount) {
        // Update total earnings
        $total_earnings = get_user_meta($affiliate_id, 'total_earnings', true) ?: 0;
        update_user_meta($affiliate_id, 'total_earnings', $total_earnings + $commission_amount);
        
        // Update conversion count
        $conversion_count = get_user_meta($affiliate_id, 'conversion_count', true) ?: 0;
        update_user_meta($affiliate_id, 'conversion_count', $conversion_count + 1);
        
        // Update this month's earnings
        $current_month = date('Y-m');
        $monthly_earnings = get_user_meta($affiliate_id, 'monthly_earnings_' . $current_month, true) ?: 0;
        update_user_meta($affiliate_id, 'monthly_earnings_' . $current_month, $monthly_earnings + $commission_amount);
    }
    
    /**
     * Send commission notification
     */
    private function send_commission_notification($affiliate_user, $commission_amount, $order_id) {
        $subject = esc_html__('New Commission Earned - AquaLuxe Affiliates', 'aqualuxe');
        $message = sprintf(
            esc_html__('Congratulations! You have earned a new commission.

Commission Amount: $%.2f
Order ID: #%s
Commission Rate: %.1f%%

You can view your earnings and stats in your affiliate dashboard.

Best regards,
The AquaLuxe Team', 'aqualuxe'),
            $commission_amount,
            $order_id,
            $this->config['commission_rate']
        );
        
        wp_mail($affiliate_user->user_email, $subject, $message);
    }
    
    /**
     * Render affiliate registration shortcode
     */
    public function render_affiliate_registration($atts) {
        $atts = shortcode_atts([
            'title' => esc_html__('Join Our Affiliate Program', 'aqualuxe'),
        ], $atts);
        
        ob_start();
        ?>
        <div class="affiliate-registration">
            <h2><?php echo esc_html($atts['title']); ?></h2>
            
            <div class="affiliate-benefits">
                <h3><?php esc_html_e('Why Join Our Affiliate Program?', 'aqualuxe'); ?></h3>
                <ul>
                    <li><?php echo sprintf(esc_html__('%s%% commission on all sales', 'aqualuxe'), $this->config['commission_rate']); ?></li>
                    <li><?php echo sprintf(esc_html__('%d-day cookie duration', 'aqualuxe'), $this->config['cookie_duration']); ?></li>
                    <li><?php esc_html_e('Real-time tracking and reporting', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Marketing materials and support', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Monthly payouts', 'aqualuxe'); ?></li>
                </ul>
            </div>
            
            <form id="affiliate-application-form" class="affiliate-form">
                <div class="form-row">
                    <div class="form-field">
                        <label for="name"><?php esc_html_e('Full Name', 'aqualuxe'); ?> *</label>
                        <input type="text" name="name" id="name" required />
                    </div>
                    <div class="form-field">
                        <label for="email"><?php esc_html_e('Email Address', 'aqualuxe'); ?> *</label>
                        <input type="email" name="email" id="email" required />
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-field">
                        <label for="website"><?php esc_html_e('Website/Blog URL', 'aqualuxe'); ?></label>
                        <input type="url" name="website" id="website" />
                    </div>
                    <div class="form-field">
                        <label for="phone"><?php esc_html_e('Phone Number', 'aqualuxe'); ?></label>
                        <input type="tel" name="phone" id="phone" />
                    </div>
                </div>
                
                <div class="form-field">
                    <label for="audience_size"><?php esc_html_e('Audience Size', 'aqualuxe'); ?></label>
                    <select name="audience_size" id="audience_size">
                        <option value="0"><?php esc_html_e('Less than 1,000', 'aqualuxe'); ?></option>
                        <option value="1000"><?php esc_html_e('1,000 - 5,000', 'aqualuxe'); ?></option>
                        <option value="5000"><?php esc_html_e('5,000 - 10,000', 'aqualuxe'); ?></option>
                        <option value="10000"><?php esc_html_e('10,000 - 50,000', 'aqualuxe'); ?></option>
                        <option value="50000"><?php esc_html_e('50,000+', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <div class="form-field">
                    <label for="marketing_channels"><?php esc_html_e('Marketing Channels', 'aqualuxe'); ?> *</label>
                    <textarea name="marketing_channels" id="marketing_channels" rows="3" 
                              placeholder="<?php esc_attr_e('Describe your marketing channels (blog, social media, email list, etc.)', 'aqualuxe'); ?>" required></textarea>
                </div>
                
                <div class="form-field">
                    <label for="experience"><?php esc_html_e('Marketing Experience', 'aqualuxe'); ?></label>
                    <textarea name="experience" id="experience" rows="3" 
                              placeholder="<?php esc_attr_e('Tell us about your marketing and affiliate experience', 'aqualuxe'); ?>"></textarea>
                </div>
                
                <div class="form-field">
                    <label for="motivation"><?php esc_html_e('Why AquaLuxe?', 'aqualuxe'); ?></label>
                    <textarea name="motivation" id="motivation" rows="3" 
                              placeholder="<?php esc_attr_e('Why do you want to promote AquaLuxe products?', 'aqualuxe'); ?>"></textarea>
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
     * Render affiliate dashboard shortcode
     */
    public function render_affiliate_dashboard($atts) {
        if (!current_user_can('view_affiliate_dashboard')) {
            return '<p>' . esc_html__('Access denied. This dashboard is for approved affiliates only.', 'aqualuxe') . '</p>';
        }
        
        $user_id = get_current_user_id();
        $total_earnings = get_user_meta($user_id, 'total_earnings', true) ?: 0;
        $conversion_count = get_user_meta($user_id, 'conversion_count', true) ?: 0;
        $total_visits = get_user_meta($user_id, 'total_visits', true) ?: 0;
        $conversion_rate = $total_visits > 0 ? ($conversion_count / $total_visits) * 100 : 0;
        
        ob_start();
        ?>
        <div class="affiliate-dashboard">
            <h2><?php esc_html_e('Affiliate Dashboard', 'aqualuxe'); ?></h2>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <span class="stat-label"><?php esc_html_e('Total Earnings', 'aqualuxe'); ?></span>
                    <span class="stat-value">$<?php echo number_format($total_earnings, 2); ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label"><?php esc_html_e('Conversions', 'aqualuxe'); ?></span>
                    <span class="stat-value"><?php echo intval($conversion_count); ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label"><?php esc_html_e('Total Visits', 'aqualuxe'); ?></span>
                    <span class="stat-value"><?php echo intval($total_visits); ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label"><?php esc_html_e('Conversion Rate', 'aqualuxe'); ?></span>
                    <span class="stat-value"><?php echo number_format($conversion_rate, 2); ?>%</span>
                </div>
            </div>
            
            <div class="dashboard-sections">
                <div class="section">
                    <h3><?php esc_html_e('Your Referral Link', 'aqualuxe'); ?></h3>
                    <?php
                    $affiliate_code = get_user_meta($user_id, 'affiliate_code', true);
                    $referral_link = home_url('/?ref=' . $affiliate_code);
                    ?>
                    <div class="referral-link-display">
                        <input type="text" value="<?php echo esc_url($referral_link); ?>" readonly onclick="this.select()" />
                        <button class="copy-link-btn" data-link="<?php echo esc_url($referral_link); ?>">
                            <?php esc_html_e('Copy', 'aqualuxe'); ?>
                        </button>
                    </div>
                </div>
                
                <div class="section">
                    <h3><?php esc_html_e('Recent Commissions', 'aqualuxe'); ?></h3>
                    <?php $this->render_recent_commissions($user_id); ?>
                </div>
                
                <div class="section">
                    <h3><?php esc_html_e('Marketing Materials', 'aqualuxe'); ?></h3>
                    <div class="marketing-materials">
                        <a href="#" class="material-link"><?php esc_html_e('Banner Images', 'aqualuxe'); ?></a>
                        <a href="#" class="material-link"><?php esc_html_e('Product Images', 'aqualuxe'); ?></a>
                        <a href="#" class="material-link"><?php esc_html_e('Email Templates', 'aqualuxe'); ?></a>
                        <a href="#" class="material-link"><?php esc_html_e('Social Media Kit', 'aqualuxe'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render recent commissions
     */
    private function render_recent_commissions($affiliate_id) {
        $commissions = get_posts([
            'post_type' => 'affiliate_commission',
            'posts_per_page' => 10,
            'meta_query' => [
                [
                    'key' => 'affiliate_id',
                    'value' => $affiliate_id,
                    'compare' => '=',
                ],
            ],
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        
        if ($commissions) {
            echo '<table class="commissions-table">';
            echo '<thead><tr><th>' . esc_html__('Date', 'aqualuxe') . '</th><th>' . esc_html__('Order', 'aqualuxe') . '</th><th>' . esc_html__('Commission', 'aqualuxe') . '</th><th>' . esc_html__('Status', 'aqualuxe') . '</th></tr></thead>';
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
            echo '<p>' . esc_html__('No commissions yet. Start promoting to earn commissions!', 'aqualuxe') . '</p>';
        }
    }
    
    /**
     * Render referral link generator shortcode
     */
    public function render_referral_link_generator($atts) {
        if (!current_user_can('generate_referral_links')) {
            return '<p>' . esc_html__('Access denied.', 'aqualuxe') . '</p>';
        }
        
        $user_id = get_current_user_id();
        $affiliate_code = get_user_meta($user_id, 'affiliate_code', true);
        
        ob_start();
        ?>
        <div class="referral-link-generator">
            <h3><?php esc_html_e('Generate Referral Links', 'aqualuxe'); ?></h3>
            
            <div class="link-generator-form">
                <div class="form-field">
                    <label for="target_url"><?php esc_html_e('Target URL', 'aqualuxe'); ?></label>
                    <input type="url" id="target_url" placeholder="<?php echo esc_attr(home_url()); ?>" />
                </div>
                
                <div class="form-field">
                    <label for="campaign_name"><?php esc_html_e('Campaign Name', 'aqualuxe'); ?></label>
                    <input type="text" id="campaign_name" placeholder="<?php esc_attr_e('e.g., summer-sale', 'aqualuxe'); ?>" />
                </div>
                
                <button id="generate-link-btn" class="btn btn-primary">
                    <?php esc_html_e('Generate Link', 'aqualuxe'); ?>
                </button>
            </div>
            
            <div class="generated-link-display" style="display: none;">
                <label><?php esc_html_e('Your Referral Link:', 'aqualuxe'); ?></label>
                <input type="text" id="generated-link" readonly />
                <button class="copy-link-btn"><?php esc_html_e('Copy', 'aqualuxe'); ?></button>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget($widgets) {
        $widgets['affiliates'] = [
            'title' => esc_html__('Affiliate Program', 'aqualuxe'),
            'callback' => [$this, 'render_dashboard_widget'],
            'priority' => 60,
        ];
        
        return $widgets;
    }
    
    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        $pending_applications = get_posts([
            'post_type' => 'affiliate_application',
            'post_status' => 'pending',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);
        
        $active_affiliates = get_users([
            'role' => 'affiliate',
            'fields' => 'ID',
        ]);
        
        $total_commissions = get_posts([
            'post_type' => 'affiliate_commission',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]);
        
        echo '<div class="aqualuxe-dashboard-widget">';
        echo '<div class="stats-row">';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . count($pending_applications) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Pending Applications', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . count($active_affiliates) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Active Affiliates', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . count($total_commissions) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Total Commissions', 'aqualuxe') . '</span>';
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
        update_option('aqualuxe_affiliates_config', $this->config);
    }
}