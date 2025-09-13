<?php
/**
 * Franchise/Licensing Module
 *
 * Handles franchise inquiries and partner portal functionality
 *
 * @package AquaLuxe\Modules\Franchise
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Franchise_Module
 *
 * Manages franchise and licensing operations
 */
class AquaLuxe_Franchise_Module {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Franchise_Module
     */
    private static $instance = null;

    /**
     * Module configuration
     *
     * @var array
     */
    private $config = array(
        'name'        => 'Franchise/Licensing',
        'version'     => '1.0.0',
        'description' => 'Franchise inquiries and partner portal functionality',
        'enabled'     => true,
        'dependencies' => array(),
    );

    /**
     * Get instance
     *
     * @return AquaLuxe_Franchise_Module
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
        if ($this->config['enabled']) {
            $this->init_hooks();
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_aqualuxe_franchise_request', array($this, 'handle_franchise_request'));
        add_action('wp_ajax_nopriv_aqualuxe_franchise_request', array($this, 'handle_franchise_request'));
        
        // User role management
        add_action('init', array($this, 'create_franchise_user_roles'));
        
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_franchise_meta'));
        
        // Partner portal
        add_action('wp', array($this, 'partner_portal_access_control'));
        add_filter('template_include', array($this, 'partner_portal_templates'));
        
        // Shortcodes
        add_shortcode('franchise_application_form', array($this, 'render_franchise_form_shortcode'));
        add_shortcode('partner_portal', array($this, 'render_partner_portal_shortcode'));
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Franchise Inquiries
        register_post_type('aqualuxe_franchise', array(
            'label'               => __('Franchise Inquiry', 'aqualuxe'),
            'description'         => __('Franchise and licensing inquiries', 'aqualuxe'),
            'labels'              => aqualuxe_get_post_type_labels('Franchise Inquiry', 'Franchise Inquiries'),
            'supports'            => array('title', 'editor', 'custom-fields', 'author'),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-networking',
            'menu_position'       => 26,
        ));

        // Partner Resources
        register_post_type('aqualuxe_resource', array(
            'label'               => __('Partner Resource', 'aqualuxe'),
            'description'         => __('Resources for franchise partners', 'aqualuxe'),
            'labels'              => aqualuxe_get_post_type_labels('Partner Resource', 'Partner Resources'),
            'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields', 'author'),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => 'edit.php?post_type=aqualuxe_franchise',
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'show_in_rest'        => false,
            'menu_icon'           => 'dashicons-portfolio',
        ));

        // Partner Performance
        register_post_type('aqualuxe_performance', array(
            'label'               => __('Partner Performance', 'aqualuxe'),
            'description'         => __('Partner performance tracking', 'aqualuxe'),
            'labels'              => aqualuxe_get_post_type_labels('Performance Report', 'Performance Reports'),
            'supports'            => array('title', 'custom-fields', 'author'),
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => 'edit.php?post_type=aqualuxe_franchise',
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'show_in_rest'        => false,
            'menu_icon'           => 'dashicons-chart-line',
        ));
    }

    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Franchise Types
        register_taxonomy('franchise_type', array('aqualuxe_franchise'), array(
            'labels' => array(
                'name'          => __('Franchise Types', 'aqualuxe'),
                'singular_name' => __('Franchise Type', 'aqualuxe'),
                'menu_name'     => __('Types', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
        ));

        // Geographic Regions
        register_taxonomy('franchise_region', array('aqualuxe_franchise', 'aqualuxe_performance'), array(
            'labels' => array(
                'name'          => __('Regions', 'aqualuxe'),
                'singular_name' => __('Region', 'aqualuxe'),
                'menu_name'     => __('Regions', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
        ));

        // Resource Categories
        register_taxonomy('resource_category', array('aqualuxe_resource'), array(
            'labels' => array(
                'name'          => __('Resource Categories', 'aqualuxe'),
                'singular_name' => __('Resource Category', 'aqualuxe'),
                'menu_name'     => __('Categories', 'aqualuxe'),
            ),
            'hierarchical'      => true,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'show_in_rest'      => false,
        ));
    }

    /**
     * Create franchise user roles
     */
    public function create_franchise_user_roles() {
        // Franchise Partner role
        if (!get_role('franchise_partner')) {
            add_role('franchise_partner', __('Franchise Partner', 'aqualuxe'), array(
                'read'                     => true,
                'access_partner_portal'    => true,
                'view_partner_resources'   => true,
                'download_partner_materials' => true,
                'submit_performance_reports' => true,
            ));
        }

        // Franchise Manager role
        if (!get_role('franchise_manager')) {
            add_role('franchise_manager', __('Franchise Manager', 'aqualuxe'), array(
                'read'                    => true,
                'edit_posts'              => true,
                'edit_others_posts'       => true,
                'publish_posts'           => true,
                'read_private_posts'      => true,
                'manage_franchise'        => true,
                'approve_franchise_requests' => true,
                'view_franchise_analytics' => true,
                'manage_partner_resources' => true,
            ));
        }
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=aqualuxe_franchise',
            __('Franchise Settings', 'aqualuxe'),
            __('Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-franchise-settings',
            array($this, 'render_settings_page')
        );

        add_submenu_page(
            'edit.php?post_type=aqualuxe_franchise',
            __('Franchise Analytics', 'aqualuxe'),
            __('Analytics', 'aqualuxe'),
            'view_franchise_analytics',
            'aqualuxe-franchise-analytics',
            array($this, 'render_analytics_page')
        );

        add_submenu_page(
            'edit.php?post_type=aqualuxe_franchise',
            __('Partner Management', 'aqualuxe'),
            __('Partners', 'aqualuxe'),
            'manage_franchise',
            'aqualuxe-partner-management',
            array($this, 'render_partner_management_page')
        );
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_franchise_details',
            __('Franchise Inquiry Details', 'aqualuxe'),
            array($this, 'render_franchise_meta_box'),
            'aqualuxe_franchise',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe_resource_access',
            __('Resource Access Control', 'aqualuxe'),
            array($this, 'render_resource_access_meta_box'),
            'aqualuxe_resource',
            'side',
            'default'
        );
    }

    /**
     * Handle AJAX franchise requests
     */
    public function handle_franchise_request() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_franchise_nonce')) {
            wp_die(__('Security check failed', 'aqualuxe'), 403);
        }

        $action = sanitize_text_field($_POST['franchise_action'] ?? '');

        switch ($action) {
            case 'submit_inquiry':
                $this->process_franchise_inquiry();
                break;
                
            case 'update_inquiry':
                $this->update_franchise_inquiry();
                break;
                
            case 'submit_performance_report':
                $this->process_performance_report();
                break;
                
            default:
                wp_send_json_error(__('Invalid action', 'aqualuxe'));
        }
    }

    /**
     * Process franchise inquiry
     */
    private function process_franchise_inquiry() {
        $contact_name = sanitize_text_field($_POST['contact_name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $company = sanitize_text_field($_POST['company'] ?? '');
        $location = sanitize_text_field($_POST['location'] ?? '');
        $investment_range = sanitize_text_field($_POST['investment_range'] ?? '');
        $experience = wp_kses_post($_POST['experience'] ?? '');
        $franchise_type = sanitize_text_field($_POST['franchise_type'] ?? '');
        $message = wp_kses_post($_POST['message'] ?? '');

        if (empty($contact_name) || empty($email) || empty($location)) {
            wp_send_json_error(__('Please fill in all required fields', 'aqualuxe'));
        }

        if (!is_email($email)) {
            wp_send_json_error(__('Please enter a valid email address', 'aqualuxe'));
        }

        // Create franchise inquiry
        $inquiry_id = wp_insert_post(array(
            'post_title'   => sprintf(__('Franchise Inquiry - %s (%s)', 'aqualuxe'), $contact_name, $location),
            'post_content' => $message,
            'post_status'  => 'pending',
            'post_type'    => 'aqualuxe_franchise',
            'meta_input'   => array(
                '_franchise_contact_name'    => $contact_name,
                '_franchise_email'           => $email,
                '_franchise_phone'           => $phone,
                '_franchise_company'         => $company,
                '_franchise_location'        => $location,
                '_franchise_investment_range' => $investment_range,
                '_franchise_experience'      => $experience,
                '_franchise_inquiry_date'    => current_time('mysql'),
                '_franchise_status'          => 'pending',
                '_franchise_ip_address'      => $this->get_client_ip(),
                '_franchise_user_agent'      => sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? ''),
            ),
        ));

        if ($inquiry_id && !is_wp_error($inquiry_id)) {
            // Set franchise type taxonomy if provided
            if (!empty($franchise_type)) {
                wp_set_object_terms($inquiry_id, $franchise_type, 'franchise_type');
            }
            
            // Send notifications
            $this->send_franchise_inquiry_notifications($inquiry_id);
            
            wp_send_json_success(array(
                'message' => __('Thank you for your franchise inquiry! We will review your application and contact you within 5-7 business days.', 'aqualuxe'),
                'inquiry_id' => $inquiry_id,
            ));
        } else {
            wp_send_json_error(__('Failed to submit inquiry. Please try again.', 'aqualuxe'));
        }
    }

    /**
     * Process performance report
     */
    private function process_performance_report() {
        if (!is_user_logged_in() || !current_user_can('submit_performance_reports')) {
            wp_send_json_error(__('Insufficient permissions', 'aqualuxe'));
        }

        $user_id = get_current_user_id();
        $report_period = sanitize_text_field($_POST['report_period'] ?? '');
        $revenue = sanitize_text_field($_POST['revenue'] ?? '');
        $customers = intval($_POST['customers'] ?? 0);
        $notes = wp_kses_post($_POST['notes'] ?? '');

        if (empty($report_period) || empty($revenue)) {
            wp_send_json_error(__('Please fill in all required fields', 'aqualuxe'));
        }

        // Create performance report
        $report_id = wp_insert_post(array(
            'post_title'   => sprintf(__('Performance Report - %s', 'aqualuxe'), $report_period),
            'post_content' => $notes,
            'post_status'  => 'pending',
            'post_type'    => 'aqualuxe_performance',
            'post_author'  => $user_id,
            'meta_input'   => array(
                '_performance_period'       => $report_period,
                '_performance_revenue'      => $revenue,
                '_performance_customers'    => $customers,
                '_performance_submit_date'  => current_time('mysql'),
                '_performance_status'       => 'pending',
            ),
        ));

        if ($report_id && !is_wp_error($report_id)) {
            wp_send_json_success(array(
                'message' => __('Performance report submitted successfully!', 'aqualuxe'),
                'report_id' => $report_id,
            ));
        } else {
            wp_send_json_error(__('Failed to submit report. Please try again.', 'aqualuxe'));
        }
    }

    /**
     * Partner portal access control
     */
    public function partner_portal_access_control() {
        if (is_page('partner-portal') || is_page('franchise-partner-portal')) {
            if (!is_user_logged_in() || !current_user_can('access_partner_portal')) {
                wp_redirect(wp_login_url(get_permalink()));
                exit;
            }
        }
    }

    /**
     * Partner portal templates
     */
    public function partner_portal_templates($template) {
        if (is_page('partner-portal') || is_page('franchise-partner-portal')) {
            $custom_template = get_template_directory() . '/template-parts/franchise/partner-portal.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        return $template;
    }

    /**
     * Render franchise form shortcode
     */
    public function render_franchise_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'style' => 'default',
            'title' => __('Franchise Inquiry', 'aqualuxe'),
        ), $atts);

        ob_start();
        ?>
        <div class="aqualuxe-franchise-form-wrapper">
            <form id="franchise-inquiry-form" class="aqualuxe-form franchise-form" data-style="<?php echo esc_attr($atts['style']); ?>">
                <h3><?php echo esc_html($atts['title']); ?></h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="contact_name"><?php esc_html_e('Full Name *', 'aqualuxe'); ?></label>
                        <input type="text" id="contact_name" name="contact_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email"><?php esc_html_e('Email Address *', 'aqualuxe'); ?></label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone"><?php esc_html_e('Phone Number', 'aqualuxe'); ?></label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="company"><?php esc_html_e('Current Company', 'aqualuxe'); ?></label>
                        <input type="text" id="company" name="company">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="location"><?php esc_html_e('Preferred Location *', 'aqualuxe'); ?></label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="investment_range"><?php esc_html_e('Investment Range', 'aqualuxe'); ?></label>
                        <select id="investment_range" name="investment_range">
                            <option value=""><?php esc_html_e('Select Range', 'aqualuxe'); ?></option>
                            <option value="50k-100k">$50,000 - $100,000</option>
                            <option value="100k-250k">$100,000 - $250,000</option>
                            <option value="250k-500k">$250,000 - $500,000</option>
                            <option value="500k+"><?php esc_html_e('$500,000+', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="franchise_type"><?php esc_html_e('Franchise Type', 'aqualuxe'); ?></label>
                    <select id="franchise_type" name="franchise_type">
                        <option value=""><?php esc_html_e('Select Type', 'aqualuxe'); ?></option>
                        <option value="retail"><?php esc_html_e('Retail Store', 'aqualuxe'); ?></option>
                        <option value="service"><?php esc_html_e('Service Center', 'aqualuxe'); ?></option>
                        <option value="hybrid"><?php esc_html_e('Retail + Service', 'aqualuxe'); ?></option>
                        <option value="online"><?php esc_html_e('Online Only', 'aqualuxe'); ?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="experience"><?php esc_html_e('Relevant Experience', 'aqualuxe'); ?></label>
                    <textarea id="experience" name="experience" rows="3" placeholder="<?php esc_attr_e('Please describe your experience in business, aquatics, or related fields...', 'aqualuxe'); ?>"></textarea>
                </div>

                <div class="form-group">
                    <label for="message"><?php esc_html_e('Additional Information', 'aqualuxe'); ?></label>
                    <textarea id="message" name="message" rows="4" placeholder="<?php esc_attr_e('Tell us about your goals, timeline, and any questions you have...', 'aqualuxe'); ?>"></textarea>
                </div>

                <div class="form-group form-submit">
                    <button type="submit" class="btn btn-primary"><?php esc_html_e('Submit Inquiry', 'aqualuxe'); ?></button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render partner portal shortcode
     */
    public function render_partner_portal_shortcode($atts) {
        if (!is_user_logged_in() || !current_user_can('access_partner_portal')) {
            return '<p>' . __('Please log in to access the partner portal.', 'aqualuxe') . '</p>';
        }

        $atts = shortcode_atts(array(
            'section' => 'dashboard',
        ), $atts);

        ob_start();
        ?>
        <div class="aqualuxe-partner-portal">
            <div class="portal-navigation">
                <ul class="portal-nav-menu">
                    <li><a href="#dashboard" class="portal-nav-link active" data-section="dashboard"><?php esc_html_e('Dashboard', 'aqualuxe'); ?></a></li>
                    <li><a href="#resources" class="portal-nav-link" data-section="resources"><?php esc_html_e('Resources', 'aqualuxe'); ?></a></li>
                    <li><a href="#performance" class="portal-nav-link" data-section="performance"><?php esc_html_e('Performance', 'aqualuxe'); ?></a></li>
                    <li><a href="#support" class="portal-nav-link" data-section="support"><?php esc_html_e('Support', 'aqualuxe'); ?></a></li>
                </ul>
            </div>

            <div class="portal-content">
                <div id="dashboard-section" class="portal-section active">
                    <?php $this->render_partner_dashboard(); ?>
                </div>
                <div id="resources-section" class="portal-section">
                    <?php $this->render_partner_resources(); ?>
                </div>
                <div id="performance-section" class="portal-section">
                    <?php $this->render_partner_performance(); ?>
                </div>
                <div id="support-section" class="portal-section">
                    <?php $this->render_partner_support(); ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render partner dashboard
     */
    private function render_partner_dashboard() {
        $user = wp_get_current_user();
        ?>
        <div class="partner-dashboard">
            <h2><?php printf(esc_html__('Welcome, %s', 'aqualuxe'), $user->display_name); ?></h2>
            
            <div class="dashboard-widgets">
                <div class="dashboard-widget">
                    <h3><?php esc_html_e('Quick Stats', 'aqualuxe'); ?></h3>
                    <div class="stat-grid">
                        <div class="stat-item">
                            <span class="stat-label"><?php esc_html_e('This Month Revenue', 'aqualuxe'); ?></span>
                            <span class="stat-value">$0</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label"><?php esc_html_e('Total Customers', 'aqualuxe'); ?></span>
                            <span class="stat-value">0</span>
                        </div>
                    </div>
                </div>

                <div class="dashboard-widget">
                    <h3><?php esc_html_e('Recent Activity', 'aqualuxe'); ?></h3>
                    <div class="activity-list">
                        <p><?php esc_html_e('No recent activity', 'aqualuxe'); ?></p>
                    </div>
                </div>

                <div class="dashboard-widget">
                    <h3><?php esc_html_e('Important Announcements', 'aqualuxe'); ?></h3>
                    <div class="announcements-list">
                        <p><?php esc_html_e('No announcements at this time', 'aqualuxe'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render partner resources
     */
    private function render_partner_resources() {
        $resources = get_posts(array(
            'post_type' => 'aqualuxe_resource',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => '_resource_access_level',
                    'value' => 'all_partners',
                    'compare' => '='
                ),
                array(
                    'key' => '_resource_access_users',
                    'value' => get_current_user_id(),
                    'compare' => 'LIKE'
                )
            )
        ));
        ?>
        <div class="partner-resources">
            <h2><?php esc_html_e('Partner Resources', 'aqualuxe'); ?></h2>
            
            <?php if ($resources): ?>
                <div class="resources-grid">
                    <?php foreach ($resources as $resource): ?>
                        <div class="resource-item">
                            <h3><?php echo esc_html($resource->post_title); ?></h3>
                            <div class="resource-excerpt">
                                <?php echo wp_kses_post($resource->post_excerpt ?: wp_trim_words($resource->post_content, 20)); ?>
                            </div>
                            <div class="resource-actions">
                                <a href="<?php echo esc_url(get_permalink($resource->ID)); ?>" class="btn btn-primary"><?php esc_html_e('View Resource', 'aqualuxe'); ?></a>
                                
                                <?php 
                                $download_file = get_post_meta($resource->ID, '_resource_download_file', true);
                                if ($download_file): ?>
                                    <a href="<?php echo esc_url($download_file); ?>" class="btn btn-secondary" download><?php esc_html_e('Download', 'aqualuxe'); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p><?php esc_html_e('No resources available at this time.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render partner performance
     */
    private function render_partner_performance() {
        ?>
        <div class="partner-performance">
            <h2><?php esc_html_e('Performance Reports', 'aqualuxe'); ?></h2>
            
            <div class="performance-form-wrapper">
                <h3><?php esc_html_e('Submit Performance Report', 'aqualuxe'); ?></h3>
                <form id="performance-report-form" class="aqualuxe-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="report_period"><?php esc_html_e('Reporting Period *', 'aqualuxe'); ?></label>
                            <select id="report_period" name="report_period" required>
                                <option value=""><?php esc_html_e('Select Period', 'aqualuxe'); ?></option>
                                <?php
                                $current_month = date('Y-m');
                                for ($i = 0; $i < 12; $i++) {
                                    $month = date('Y-m', strtotime("-$i months"));
                                    $month_name = date('F Y', strtotime("-$i months"));
                                    echo '<option value="' . esc_attr($month) . '">' . esc_html($month_name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="revenue"><?php esc_html_e('Revenue *', 'aqualuxe'); ?></label>
                            <input type="number" id="revenue" name="revenue" step="0.01" min="0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customers"><?php esc_html_e('Number of Customers', 'aqualuxe'); ?></label>
                        <input type="number" id="customers" name="customers" min="0">
                    </div>

                    <div class="form-group">
                        <label for="notes"><?php esc_html_e('Additional Notes', 'aqualuxe'); ?></label>
                        <textarea id="notes" name="notes" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><?php esc_html_e('Submit Report', 'aqualuxe'); ?></button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render partner support
     */
    private function render_partner_support() {
        ?>
        <div class="partner-support">
            <h2><?php esc_html_e('Partner Support', 'aqualuxe'); ?></h2>
            
            <div class="support-options">
                <div class="support-option">
                    <h3><?php esc_html_e('Contact Support', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Email: partners@aqualuxe.com', 'aqualuxe'); ?></p>
                    <p><?php esc_html_e('Phone: 1-800-AQUA-LUX', 'aqualuxe'); ?></p>
                </div>

                <div class="support-option">
                    <h3><?php esc_html_e('Training Materials', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Access training videos, manuals, and certification programs.', 'aqualuxe'); ?></p>
                    <a href="#" class="btn btn-primary"><?php esc_html_e('View Training', 'aqualuxe'); ?></a>
                </div>

                <div class="support-option">
                    <h3><?php esc_html_e('Marketing Support', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Download marketing materials, logos, and promotional content.', 'aqualuxe'); ?></p>
                    <a href="#" class="btn btn-primary"><?php esc_html_e('Get Materials', 'aqualuxe'); ?></a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Send franchise inquiry notifications
     */
    private function send_franchise_inquiry_notifications($inquiry_id) {
        $contact_name = get_post_meta($inquiry_id, '_franchise_contact_name', true);
        $email = get_post_meta($inquiry_id, '_franchise_email', true);
        $location = get_post_meta($inquiry_id, '_franchise_location', true);
        
        // Email to admin
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(__('New Franchise Inquiry - %s', 'aqualuxe'), $contact_name);
        $admin_message = sprintf(
            __('A new franchise inquiry has been submitted.\n\nContact: %s\nEmail: %s\nLocation: %s\nInquiry ID: %d\n\nReview the inquiry in the admin panel.', 'aqualuxe'),
            $contact_name,
            $email,
            $location,
            $inquiry_id
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
        
        // Email to inquirer
        $user_subject = __('Franchise Inquiry Received - AquaLuxe', 'aqualuxe');
        $user_message = sprintf(
            __('Thank you for your interest in an AquaLuxe franchise.\n\nWe have received your inquiry for the %s location and will review it within 5-7 business days.\n\nReference ID: %d\n\nBest regards,\nAquaLuxe Franchise Team', 'aqualuxe'),
            $location,
            $inquiry_id
        );
        
        wp_mail($email, $user_subject, $user_message);
    }

    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    /**
     * Enqueue module assets
     */
    public function enqueue_assets() {
        if ($this->should_enqueue_assets()) {
            wp_enqueue_script(
                'aqualuxe-franchise',
                AQUALUXE_ASSETS_URI . '/js/modules/franchise.js',
                array('jquery', 'aqualuxe-app'),
                AQUALUXE_VERSION,
                true
            );

            wp_localize_script('aqualuxe-franchise', 'aqualuxeFranchise', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('aqualuxe_franchise_nonce'),
                'strings' => array(
                    'submit_inquiry' => __('Submit Inquiry', 'aqualuxe'),
                    'submit_report'  => __('Submit Report', 'aqualuxe'),
                    'processing'     => __('Processing...', 'aqualuxe'),
                    'error'         => __('An error occurred. Please try again.', 'aqualuxe'),
                ),
            ));
        }
    }

    /**
     * Check if assets should be enqueued
     */
    private function should_enqueue_assets() {
        return is_page('franchise') || 
               is_page('partner-portal') || 
               is_page('franchise-opportunities') ||
               has_shortcode(get_post()->post_content ?? '', 'franchise_application_form') ||
               has_shortcode(get_post()->post_content ?? '', 'partner_portal');
    }

    /**
     * Render settings page placeholder
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Franchise Settings', 'aqualuxe'); ?></h1>
            <p><?php esc_html_e('Franchise module settings will be available here.', 'aqualuxe'); ?></p>
        </div>
        <?php
    }

    /**
     * Render analytics page placeholder
     */
    public function render_analytics_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Franchise Analytics', 'aqualuxe'); ?></h1>
            <p><?php esc_html_e('Franchise analytics and reporting will be available here.', 'aqualuxe'); ?></p>
        </div>
        <?php
    }

    /**
     * Render partner management page placeholder
     */
    public function render_partner_management_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Partner Management', 'aqualuxe'); ?></h1>
            <p><?php esc_html_e('Partner management tools will be available here.', 'aqualuxe'); ?></p>
        </div>
        <?php
    }

    /**
     * Render franchise meta box
     */
    public function render_franchise_meta_box($post) {
        wp_nonce_field('aqualuxe_franchise_meta', 'aqualuxe_franchise_nonce');
        
        $contact_name = get_post_meta($post->ID, '_franchise_contact_name', true);
        $email = get_post_meta($post->ID, '_franchise_email', true);
        $phone = get_post_meta($post->ID, '_franchise_phone', true);
        $location = get_post_meta($post->ID, '_franchise_location', true);
        $investment_range = get_post_meta($post->ID, '_franchise_investment_range', true);
        $status = get_post_meta($post->ID, '_franchise_status', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label><?php esc_html_e('Contact Name', 'aqualuxe'); ?></label></th>
                <td><?php echo esc_html($contact_name); ?></td>
            </tr>
            <tr>
                <th><label><?php esc_html_e('Email', 'aqualuxe'); ?></label></th>
                <td><?php echo esc_html($email); ?></td>
            </tr>
            <tr>
                <th><label><?php esc_html_e('Phone', 'aqualuxe'); ?></label></th>
                <td><?php echo esc_html($phone); ?></td>
            </tr>
            <tr>
                <th><label><?php esc_html_e('Location', 'aqualuxe'); ?></label></th>
                <td><?php echo esc_html($location); ?></td>
            </tr>
            <tr>
                <th><label><?php esc_html_e('Investment Range', 'aqualuxe'); ?></label></th>
                <td><?php echo esc_html($investment_range); ?></td>
            </tr>
            <tr>
                <th><label for="franchise_status"><?php esc_html_e('Status', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="franchise_status" name="franchise_status">
                        <option value="pending" <?php selected($status, 'pending'); ?>><?php esc_html_e('Pending', 'aqualuxe'); ?></option>
                        <option value="reviewing" <?php selected($status, 'reviewing'); ?>><?php esc_html_e('Under Review', 'aqualuxe'); ?></option>
                        <option value="approved" <?php selected($status, 'approved'); ?>><?php esc_html_e('Approved', 'aqualuxe'); ?></option>
                        <option value="rejected" <?php selected($status, 'rejected'); ?>><?php esc_html_e('Rejected', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render resource access meta box
     */
    public function render_resource_access_meta_box($post) {
        wp_nonce_field('aqualuxe_resource_meta', 'aqualuxe_resource_nonce');
        
        $access_level = get_post_meta($post->ID, '_resource_access_level', true);
        $download_file = get_post_meta($post->ID, '_resource_download_file', true);
        
        ?>
        <p>
            <label for="resource_access_level"><?php esc_html_e('Access Level', 'aqualuxe'); ?></label>
            <select id="resource_access_level" name="resource_access_level" style="width: 100%;">
                <option value="all_partners" <?php selected($access_level, 'all_partners'); ?>><?php esc_html_e('All Partners', 'aqualuxe'); ?></option>
                <option value="specific_partners" <?php selected($access_level, 'specific_partners'); ?>><?php esc_html_e('Specific Partners', 'aqualuxe'); ?></option>
                <option value="premium_partners" <?php selected($access_level, 'premium_partners'); ?>><?php esc_html_e('Premium Partners', 'aqualuxe'); ?></option>
            </select>
        </p>
        
        <p>
            <label for="resource_download_file"><?php esc_html_e('Download File URL', 'aqualuxe'); ?></label>
            <input type="url" id="resource_download_file" name="resource_download_file" value="<?php echo esc_attr($download_file); ?>" style="width: 100%;">
        </p>
        <?php
    }

    /**
     * Save franchise meta
     */
    public function save_franchise_meta($post_id) {
        // Check if our nonce is set and verify it
        if (!isset($_POST['aqualuxe_franchise_nonce']) || !wp_verify_nonce($_POST['aqualuxe_franchise_nonce'], 'aqualuxe_franchise_meta')) {
            return;
        }

        // Check if user has permission to edit
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Don't save on autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (get_post_type($post_id) === 'aqualuxe_franchise') {
            if (isset($_POST['franchise_status'])) {
                update_post_meta($post_id, '_franchise_status', sanitize_text_field($_POST['franchise_status']));
            }
        }

        if (get_post_type($post_id) === 'aqualuxe_resource') {
            if (isset($_POST['resource_access_level'])) {
                update_post_meta($post_id, '_resource_access_level', sanitize_text_field($_POST['resource_access_level']));
            }
            if (isset($_POST['resource_download_file'])) {
                update_post_meta($post_id, '_resource_download_file', esc_url_raw($_POST['resource_download_file']));
            }
        }
    }
}

// Initialize the module
AquaLuxe_Franchise_Module::get_instance();