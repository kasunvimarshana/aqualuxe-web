<?php
/**
 * Subscriptions Module
 * 
 * Handles subscription and membership functionality
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Subscriptions_Module {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_aqualuxe_subscribe', [$this, 'ajax_subscribe']);
        add_action('wp_ajax_nopriv_aqualuxe_subscribe', [$this, 'ajax_subscribe']);
        add_shortcode('aqualuxe_subscription_plans', [$this, 'subscription_plans_shortcode']);
        add_shortcode('aqualuxe_newsletter_signup', [$this, 'newsletter_signup_shortcode']);
    }
    
    /**
     * Initialize module
     */
    public function init() {
        $this->register_post_types();
        $this->register_user_roles();
    }
    
    /**
     * Register custom post types
     */
    private function register_post_types() {
        // Subscription plans
        register_post_type('aqualuxe_sub_plan', [
            'labels' => [
                'name' => esc_html__('Subscription Plans', 'aqualuxe'),
                'singular_name' => esc_html__('Subscription Plan', 'aqualuxe'),
                'add_new' => esc_html__('Add New Plan', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Subscription Plan', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Subscription Plan', 'aqualuxe'),
                'new_item' => esc_html__('New Subscription Plan', 'aqualuxe'),
                'view_item' => esc_html__('View Subscription Plan', 'aqualuxe'),
                'search_items' => esc_html__('Search Plans', 'aqualuxe'),
                'not_found' => esc_html__('No plans found', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'subscription-plans'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 25,
            'menu_icon' => 'dashicons-id-alt',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields']
        ]);
        
        // Subscriber management
        register_post_type('aqualuxe_subscriber', [
            'labels' => [
                'name' => esc_html__('Subscribers', 'aqualuxe'),
                'singular_name' => esc_html__('Subscriber', 'aqualuxe'),
                'view_item' => esc_html__('View Subscriber', 'aqualuxe'),
                'search_items' => esc_html__('Search Subscribers', 'aqualuxe'),
                'not_found' => esc_html__('No subscribers found', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_sub_plan',
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => ['title', 'custom-fields']
        ]);
    }
    
    /**
     * Register user roles
     */
    private function register_user_roles() {
        // Premium subscriber role
        add_role('aqualuxe_premium', esc_html__('Premium Subscriber', 'aqualuxe'), [
            'read' => true,
            'access_premium_content' => true,
            'priority_support' => true
        ]);
        
        // VIP subscriber role
        add_role('aqualuxe_vip', esc_html__('VIP Subscriber', 'aqualuxe'), [
            'read' => true,
            'access_premium_content' => true,
            'access_vip_content' => true,
            'priority_support' => true,
            'exclusive_access' => true
        ]);
    }
    
    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_singular('aqualuxe_sub_plan') || is_post_type_archive('aqualuxe_sub_plan')) {
            wp_enqueue_style(
                'aqualuxe-subscriptions',
                AQUALUXE_ASSETS_URI . '/css/modules/subscriptions.css',
                ['aqualuxe-style'],
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-subscriptions',
                AQUALUXE_ASSETS_URI . '/js/modules/subscriptions.js',
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-subscriptions', 'aqualuxe_subs_vars', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_subs_nonce'),
                'strings' => [
                    'subscription_success' => esc_html__('Subscription successful! Welcome to AquaLuxe Premium.', 'aqualuxe'),
                    'subscription_error' => esc_html__('There was an error processing your subscription. Please try again.', 'aqualuxe'),
                    'email_required' => esc_html__('Please enter a valid email address.', 'aqualuxe'),
                ]
            ]);
        }
    }
    
    /**
     * Subscription plans shortcode
     */
    public function subscription_plans_shortcode($atts) {
        $atts = shortcode_atts([
            'limit' => -1,
            'featured' => false,
            'columns' => 3,
            'show_features' => true,
            'show_trial' => true
        ], $atts, 'aqualuxe_subscription_plans');
        
        $args = [
            'post_type' => 'aqualuxe_sub_plan',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ];
        
        if ($atts['featured']) {
            $args['meta_query'][] = [
                'key' => 'plan_featured',
                'value' => '1',
                'compare' => '='
            ];
        }
        
        $plans = new WP_Query($args);
        
        if (!$plans->have_posts()) {
            return '<p>' . esc_html__('No subscription plans found.', 'aqualuxe') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="subscription-plans-grid grid grid-cols-1 md:grid-cols-<?php echo esc_attr($atts['columns']); ?> gap-8 max-w-6xl mx-auto">
            <?php while ($plans->have_posts()) : $plans->the_post(); ?>
                <?php $this->render_subscription_plan($atts); ?>
            <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    /**
     * Newsletter signup shortcode
     */
    public function newsletter_signup_shortcode($atts) {
        $atts = shortcode_atts([
            'title' => 'Stay Updated',
            'description' => 'Get aquatic care tips, product updates, and exclusive offers.',
            'button_text' => 'Subscribe Now',
            'style' => 'default'
        ], $atts, 'aqualuxe_newsletter_signup');
        
        ob_start();
        ?>
        <div class="newsletter-signup newsletter-<?php echo esc_attr($atts['style']); ?>">
            <?php if ($atts['title']) : ?>
                <h3 class="newsletter-title text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php echo esc_html($atts['title']); ?>
                </h3>
            <?php endif; ?>
            
            <?php if ($atts['description']) : ?>
                <p class="newsletter-description text-gray-600 dark:text-gray-400 mb-6">
                    <?php echo esc_html($atts['description']); ?>
                </p>
            <?php endif; ?>
            
            <form class="newsletter-form flex gap-3" method="post">
                <?php wp_nonce_field('aqualuxe_newsletter', 'newsletter_nonce'); ?>
                
                <div class="form-group flex-1">
                    <input type="email" 
                           name="newsletter_email" 
                           placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                
                <button type="submit" 
                        name="subscribe_newsletter" 
                        class="btn btn-primary whitespace-nowrap">
                    <?php echo esc_html($atts['button_text']); ?>
                </button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render subscription plan
     */
    private function render_subscription_plan($atts) {
        $plan_id = get_the_ID();
        $price = get_post_meta($plan_id, 'plan_price', true);
        $billing_cycle = get_post_meta($plan_id, 'plan_billing_cycle', true);
        $features = get_post_meta($plan_id, 'plan_features', true);
        $trial_days = get_post_meta($plan_id, 'plan_trial_days', true);
        $is_featured = get_post_meta($plan_id, 'plan_featured', true) === '1';
        $popular = get_post_meta($plan_id, 'plan_popular', true) === '1';
        
        $classes = ['subscription-plan', 'bg-white', 'dark:bg-gray-800', 'rounded-xl', 'shadow-lg', 'p-8', 'relative'];
        if ($is_featured) {
            $classes[] = 'featured-plan';
            $classes[] = 'ring-2';
            $classes[] = 'ring-primary-500';
        }
        ?>
        <div class="<?php echo implode(' ', $classes); ?>">
            
            <?php if ($popular) : ?>
                <div class="popular-badge absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-accent-500 text-white px-4 py-1 rounded-full text-sm font-semibold">
                        <?php esc_html_e('Most Popular', 'aqualuxe'); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <div class="plan-header text-center mb-8">
                <h3 class="plan-name text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    <?php the_title(); ?>
                </h3>
                
                <div class="plan-price mb-4">
                    <span class="price-amount text-4xl font-bold text-primary-600">
                        <?php echo esc_html($price ?: 'Free'); ?>
                    </span>
                    <?php if ($price && $billing_cycle) : ?>
                        <span class="price-cycle text-gray-600 dark:text-gray-400">
                            /<?php echo esc_html($billing_cycle); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <?php if ($atts['show_trial'] && $trial_days) : ?>
                    <div class="trial-info text-sm text-green-600 dark:text-green-400 font-medium">
                        <?php printf(esc_html__('%d-day free trial', 'aqualuxe'), $trial_days); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="plan-description text-gray-600 dark:text-gray-400 mb-8">
                <?php the_excerpt(); ?>
            </div>
            
            <?php if ($atts['show_features'] && $features) : ?>
                <div class="plan-features mb-8">
                    <h4 class="features-title text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Features Include:', 'aqualuxe'); ?>
                    </h4>
                    <ul class="features-list space-y-3">
                        <?php foreach (explode("\n", $features) as $feature) : ?>
                            <?php if (trim($feature)) : ?>
                                <li class="feature-item flex items-center">
                                    <i class="fas fa-check text-green-500 mr-3"></i>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php echo esc_html(trim($feature)); ?>
                                    </span>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="plan-actions">
                <button class="btn btn-primary w-full justify-center subscribe-plan-btn" 
                        data-plan-id="<?php echo $plan_id; ?>" 
                        data-plan-name="<?php echo esc_attr(get_the_title()); ?>"
                        data-plan-price="<?php echo esc_attr($price); ?>">
                    <?php esc_html_e('Choose Plan', 'aqualuxe'); ?>
                </button>
                
                <div class="plan-guarantee text-center mt-4">
                    <small class="text-gray-500 dark:text-gray-400">
                        <?php esc_html_e('Cancel anytime. No questions asked.', 'aqualuxe'); ?>
                    </small>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX subscribe handler
     */
    public function ajax_subscribe() {
        check_ajax_referer('aqualuxe_subs_nonce', 'nonce');
        
        $action_type = sanitize_text_field($_POST['action_type'] ?? 'newsletter');
        $email = sanitize_email($_POST['email']);
        
        if (!$email) {
            wp_send_json_error('Valid email is required');
        }
        
        if ($action_type === 'newsletter') {
            $this->handle_newsletter_subscription($email);
        } elseif ($action_type === 'plan') {
            $plan_id = intval($_POST['plan_id']);
            $this->handle_plan_subscription($email, $plan_id);
        }
    }
    
    /**
     * Handle newsletter subscription
     */
    private function handle_newsletter_subscription($email) {
        // Check if already subscribed
        $existing = get_posts([
            'post_type' => 'aqualuxe_subscriber',
            'meta_query' => [
                [
                    'key' => 'subscriber_email',
                    'value' => $email,
                    'compare' => '='
                ]
            ]
        ]);
        
        if (!empty($existing)) {
            wp_send_json_error('Email already subscribed');
        }
        
        // Create subscriber record
        $subscriber_id = wp_insert_post([
            'post_type' => 'aqualuxe_subscriber',
            'post_title' => 'Newsletter Subscriber - ' . $email,
            'post_status' => 'publish',
            'meta_input' => [
                'subscriber_email' => $email,
                'subscription_type' => 'newsletter',
                'subscription_date' => current_time('mysql'),
                'subscription_status' => 'active'
            ]
        ]);
        
        if ($subscriber_id && !is_wp_error($subscriber_id)) {
            // Send welcome email
            $this->send_welcome_email($email, 'newsletter');
            wp_send_json_success(['message' => 'Successfully subscribed to newsletter!']);
        } else {
            wp_send_json_error('Failed to create subscription');
        }
    }
    
    /**
     * Handle plan subscription
     */
    private function handle_plan_subscription($email, $plan_id) {
        $plan = get_post($plan_id);
        if (!$plan || $plan->post_type !== 'aqualuxe_sub_plan') {
            wp_send_json_error('Invalid subscription plan');
        }
        
        // In a real implementation, this would integrate with payment processing
        $subscriber_id = wp_insert_post([
            'post_type' => 'aqualuxe_subscriber',
            'post_title' => get_the_title($plan_id) . ' Subscriber - ' . $email,
            'post_status' => 'publish',
            'meta_input' => [
                'subscriber_email' => $email,
                'subscription_type' => 'plan',
                'subscription_plan_id' => $plan_id,
                'subscription_date' => current_time('mysql'),
                'subscription_status' => 'pending_payment'
            ]
        ]);
        
        if ($subscriber_id && !is_wp_error($subscriber_id)) {
            wp_send_json_success([
                'message' => 'Subscription request received! Payment processing required.',
                'subscriber_id' => $subscriber_id
            ]);
        } else {
            wp_send_json_error('Failed to create subscription');
        }
    }
    
    /**
     * Send welcome email
     */
    private function send_welcome_email($email, $type = 'newsletter') {
        $subject = 'Welcome to AquaLuxe!';
        
        if ($type === 'newsletter') {
            $message = "
                Thank you for subscribing to the AquaLuxe newsletter!
                
                You'll receive:
                - Aquatic care tips and guides
                - New product announcements
                - Exclusive offers and discounts
                - Expert advice from our team
                
                Welcome to the AquaLuxe community!
                
                Best regards,
                The AquaLuxe Team
            ";
        } else {
            $message = "
                Welcome to AquaLuxe Premium!
                
                Your subscription is being processed and you'll receive access details shortly.
                
                Thank you for choosing AquaLuxe!
                
                Best regards,
                The AquaLuxe Team
            ";
        }
        
        wp_mail($email, $subject, $message);
    }
    
    /**
     * Get subscriber statistics
     */
    public function get_subscriber_stats() {
        global $wpdb;
        
        $total = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM {$wpdb->posts} 
            WHERE post_type = %s AND post_status = 'publish'
        ", 'aqualuxe_subscriber'));
        
        $newsletter = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = %s AND p.post_status = 'publish'
            AND pm.meta_key = 'subscription_type' AND pm.meta_value = 'newsletter'
        ", 'aqualuxe_subscriber'));
        
        $premium = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            WHERE p.post_type = %s AND p.post_status = 'publish'
            AND pm.meta_key = 'subscription_type' AND pm.meta_value = 'plan'
        ", 'aqualuxe_subscriber'));
        
        return [
            'total' => (int) $total,
            'newsletter' => (int) $newsletter,
            'premium' => (int) $premium
        ];
    }
}