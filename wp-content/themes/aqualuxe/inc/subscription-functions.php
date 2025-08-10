<?php
/**
 * Subscription Management Functions
 *
 * Functions for the subscription management system
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue subscription scripts and styles
 */
function aqualuxe_enqueue_subscription_assets() {
    // Check if we're on a subscription-related page
    if (is_singular('subscription') || 
        is_post_type_archive('subscription') || 
        is_page('subscription') || 
        is_page('my-subscriptions') ||
        (is_account_page() && isset($_GET['subscription']))) {
        
        // Enqueue CSS
        wp_enqueue_style(
            'aqualuxe-subscription-styles',
            get_template_directory_uri() . '/assets/css/subscription.css',
            array(),
            AQUALUXE_VERSION
        );
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'aqualuxe-subscription-script',
            get_template_directory_uri() . '/assets/js/subscription.js',
            array('jquery', 'jquery-ui-datepicker'),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-subscription-script',
            'aqualuxe_subscription',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_subscription_nonce'),
                'currency_symbol' => get_woocommerce_currency_symbol(),
            )
        );
        
        // Enqueue jQuery UI styles for datepicker
        wp_enqueue_style(
            'jquery-ui-style',
            '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
            array(),
            '1.12.1'
        );
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_subscription_assets');

/**
 * Register subscription shortcodes
 */
function aqualuxe_register_subscription_shortcodes() {
    add_shortcode('subscription_plans', 'aqualuxe_subscription_plans_shortcode');
    add_shortcode('subscription_form', 'aqualuxe_subscription_form_shortcode');
    add_shortcode('my_subscriptions', 'aqualuxe_my_subscriptions_shortcode');
}
add_action('init', 'aqualuxe_register_subscription_shortcodes');

/**
 * Subscription Plans shortcode
 */
function aqualuxe_subscription_plans_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts(
        array(
            'count' => -1,
            'type' => '',
            'frequency' => '',
            'columns' => 3,
            'featured' => '',
        ),
        $atts,
        'subscription_plans'
    );
    
    // Set up query args
    $args = array(
        'post_type' => 'subscription',
        'posts_per_page' => $atts['count'],
        'post_status' => 'publish',
    );
    
    // Add taxonomy queries if specified
    $tax_query = array();
    
    if (!empty($atts['type'])) {
        $tax_query[] = array(
            'taxonomy' => 'subscription_type',
            'field' => 'slug',
            'terms' => explode(',', $atts['type']),
        );
    }
    
    if (!empty($atts['frequency'])) {
        $tax_query[] = array(
            'taxonomy' => 'subscription_frequency',
            'field' => 'slug',
            'terms' => explode(',', $atts['frequency']),
        );
    }
    
    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }
    
    // Add meta query for featured plans if specified
    if (!empty($atts['featured'])) {
        $args['meta_query'] = array(
            array(
                'key' => '_featured',
                'value' => '1',
                'compare' => '=',
            ),
        );
    }
    
    // Get subscription plans
    $subscription_plans = get_posts($args);
    
    // Start output buffer
    ob_start();
    
    if (!empty($subscription_plans)) {
        ?>
        <div class="subscription-plans-container columns-<?php echo esc_attr($atts['columns']); ?>">
            <?php foreach ($subscription_plans as $plan) : ?>
                <?php
                // Get plan details
                $price = get_post_meta($plan->ID, '_subscription_price', true);
                $features = get_post_meta($plan->ID, '_features', true);
                $featured = get_post_meta($plan->ID, '_featured', true);
                $popular = get_post_meta($plan->ID, '_popular', true);
                
                // Get frequency terms
                $frequency_terms = get_the_terms($plan->ID, 'subscription_frequency');
                $frequency = !empty($frequency_terms) ? $frequency_terms[0]->name : 'month';
                ?>
                <div class="subscription-plan-item<?php echo $featured ? ' featured' : ''; ?>" data-plan-id="<?php echo esc_attr($plan->ID); ?>">
                    <input type="radio" name="subscription_plan" value="<?php echo esc_attr($plan->ID); ?>" class="subscription-plan-radio">
                    
                    <?php if ($featured) : ?>
                        <span class="plan-badge featured"><?php _e('Featured', 'aqualuxe'); ?></span>
                    <?php elseif ($popular) : ?>
                        <span class="plan-badge popular"><?php _e('Popular', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                    
                    <div class="subscription-plan-header">
                        <h3 class="plan-name"><?php echo esc_html($plan->post_title); ?></h3>
                        <div class="plan-price-container">
                            <span class="plan-price" data-price="<?php echo esc_attr($price); ?>">$<?php echo esc_html(number_format((float)$price, 2)); ?></span>
                            <span class="plan-frequency">/<?php echo esc_html(strtolower($frequency)); ?></span>
                        </div>
                        <div class="plan-description"><?php echo esc_html(get_the_excerpt($plan)); ?></div>
                    </div>
                    
                    <?php if (!empty($features)) : ?>
                        <ul class="plan-features">
                            <?php foreach (explode("\n", $features) as $feature) : ?>
                                <?php if (!empty(trim($feature))) : ?>
                                    <li class="plan-feature-item">
                                        <span class="feature-icon">✓</span>
                                        <?php echo esc_html(trim($feature)); ?>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    
                    <div class="plan-cta">
                        <span class="button button-primary"><?php _e('Select Plan', 'aqualuxe'); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    } else {
        ?>
        <p><?php _e('No subscription plans found.', 'aqualuxe'); ?></p>
        <?php
    }
    
    // Return the output buffer contents
    return ob_get_clean();
}

/**
 * Subscription Form shortcode
 */
function aqualuxe_subscription_form_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts(
        array(
            'plan_id' => '',
            'redirect' => '',
        ),
        $atts,
        'subscription_form'
    );
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return '<p>' . __('Please <a href="' . wp_login_url(get_permalink()) . '">log in</a> to subscribe.', 'aqualuxe') . '</p>';
    }
    
    // Start output buffer
    ob_start();
    
    ?>
    <div class="subscription-form-container">
        <form id="subscription-form" class="subscription-form">
            <input type="hidden" name="subscription_plan_id" id="subscription_plan_id" value="<?php echo esc_attr($atts['plan_id']); ?>">
            
            <div class="form-section">
                <h3 class="form-section-title"><?php _e('Subscription Details', 'aqualuxe'); ?></h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="subscription_frequency"><?php _e('Billing Frequency', 'aqualuxe'); ?></label>
                        <select id="subscription_frequency" name="subscription_frequency" required>
                            <option value=""><?php _e('Select Frequency', 'aqualuxe'); ?></option>
                            <?php
                            // Get frequency terms
                            $frequency_terms = get_terms(array(
                                'taxonomy' => 'subscription_frequency',
                                'hide_empty' => false,
                            ));
                            
                            if (!empty($frequency_terms) && !is_wp_error($frequency_terms)) {
                                foreach ($frequency_terms as $term) {
                                    echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="subscription_start_date"><?php _e('Start Date', 'aqualuxe'); ?></label>
                        <input type="text" id="subscription_start_date" name="subscription_start_date" required>
                        <p class="description"><?php _e('When should your subscription start?', 'aqualuxe'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title"><?php _e('Payment Information', 'aqualuxe'); ?></h3>
                
                <div class="form-group">
                    <label for="payment_method"><?php _e('Payment Method', 'aqualuxe'); ?></label>
                    <select id="payment_method" name="payment_method" required>
                        <option value=""><?php _e('Select Payment Method', 'aqualuxe'); ?></option>
                        <option value="credit_card"><?php _e('Credit Card', 'aqualuxe'); ?></option>
                        <option value="paypal"><?php _e('PayPal', 'aqualuxe'); ?></option>
                        <option value="bank_transfer"><?php _e('Bank Transfer', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <div id="credit_card_fields" style="display: none;">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="card_number"><?php _e('Card Number', 'aqualuxe'); ?></label>
                            <input type="text" id="card_number" name="card_number" placeholder="•••• •••• •••• ••••">
                        </div>
                        
                        <div class="form-group">
                            <label for="card_expiry"><?php _e('Expiration Date', 'aqualuxe'); ?></label>
                            <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY">
                        </div>
                        
                        <div class="form-group">
                            <label for="card_cvc"><?php _e('CVC', 'aqualuxe'); ?></label>
                            <input type="text" id="card_cvc" name="card_cvc" placeholder="•••">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title"><?php _e('Terms & Conditions', 'aqualuxe'); ?></h3>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms_accepted" required>
                        <?php _e('I agree to the <a href="/terms-and-conditions" target="_blank">Terms & Conditions</a> and <a href="/privacy-policy" target="_blank">Privacy Policy</a>.', 'aqualuxe'); ?>
                    </label>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="auto_renew" checked>
                        <?php _e('I understand that my subscription will automatically renew until I cancel it.', 'aqualuxe'); ?>
                    </label>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" id="submit-subscription" class="button button-primary"><?php _e('Subscribe', 'aqualuxe'); ?></button>
                <a href="<?php echo esc_url(get_permalink()); ?>" class="button button-secondary"><?php _e('Cancel', 'aqualuxe'); ?></a>
            </div>
        </form>
    </div>
    
    <script>
        jQuery(document).ready(function($) {
            // Show/hide credit card fields based on payment method
            $('#payment_method').on('change', function() {
                if ($(this).val() === 'credit_card') {
                    $('#credit_card_fields').slideDown();
                } else {
                    $('#credit_card_fields').slideUp();
                }
            });
        });
    </script>
    <?php
    
    // Return the output buffer contents
    return ob_get_clean();
}

/**
 * My Subscriptions shortcode
 */
function aqualuxe_my_subscriptions_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts(
        array(
            'show_history' => 'yes',
        ),
        $atts,
        'my_subscriptions'
    );
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return '<p>' . __('Please <a href="' . wp_login_url(get_permalink()) . '">log in</a> to view your subscriptions.', 'aqualuxe') . '</p>';
    }
    
    // Get current user
    $user_id = get_current_user_id();
    
    // Get user's subscriptions
    $subscriptions = get_posts(array(
        'post_type' => 'sub_customer',
        'posts_per_page' => -1,
        'meta_key' => '_user_id',
        'meta_value' => $user_id,
    ));
    
    // Start output buffer
    ob_start();
    
    ?>
    <div class="subscription-dashboard-container">
        <div class="dashboard-header">
            <h2 class="dashboard-title"><?php _e('My Subscriptions', 'aqualuxe'); ?></h2>
            <p class="dashboard-subtitle"><?php _e('Manage your subscriptions and view payment history.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="dashboard-section">
            <h3 class="dashboard-section-title"><?php _e('Active Subscriptions', 'aqualuxe'); ?></h3>
            
            <div class="active-subscriptions">
                <?php if (!empty($subscriptions)) : ?>
                    <?php foreach ($subscriptions as $subscription) : ?>
                        <?php
                        // Get subscription details
                        $subscription_id = get_post_meta($subscription->ID, '_subscription_id', true);
                        $subscription_plan = get_post($subscription_id);
                        
                        if (!$subscription_plan) {
                            continue;
                        }
                        
                        $start_date = get_post_meta($subscription->ID, '_start_date', true);
                        $next_payment_date = get_post_meta($subscription->ID, '_next_payment_date', true);
                        $billing_amount = get_post_meta($subscription->ID, '_billing_amount', true);
                        $payment_method = get_post_meta($subscription->ID, '_payment_method', true);
                        
                        // Get status
                        $status_terms = get_the_terms($subscription->ID, 'subscription_status');
                        $status = !empty($status_terms) ? $status_terms[0]->slug : 'active';
                        $status_name = !empty($status_terms) ? $status_terms[0]->name : __('Active', 'aqualuxe');
                        ?>
                        <div class="subscription-card">
                            <div class="subscription-card-header">
                                <h4 class="subscription-name"><?php echo esc_html($subscription_plan->post_title); ?></h4>
                                <span class="subscription-status status-<?php echo esc_attr($status); ?>"><?php echo esc_html($status_name); ?></span>
                            </div>
                            
                            <div class="subscription-card-body">
                                <div class="subscription-detail">
                                    <div class="detail-label"><?php _e('Start Date', 'aqualuxe'); ?></div>
                                    <div class="detail-value"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($start_date))); ?></div>
                                </div>
                                
                                <div class="subscription-detail">
                                    <div class="detail-label"><?php _e('Next Payment', 'aqualuxe'); ?></div>
                                    <div class="detail-value"><?php echo !empty($next_payment_date) ? esc_html(date_i18n(get_option('date_format'), strtotime($next_payment_date))) : '—'; ?></div>
                                </div>
                                
                                <div class="subscription-detail">
                                    <div class="detail-label"><?php _e('Amount', 'aqualuxe'); ?></div>
                                    <div class="detail-value"><?php echo !empty($billing_amount) ? '$' . number_format((float)$billing_amount, 2) : '—'; ?></div>
                                </div>
                                
                                <div class="subscription-detail">
                                    <div class="detail-label"><?php _e('Payment Method', 'aqualuxe'); ?></div>
                                    <div class="detail-value"><?php echo esc_html($payment_method); ?></div>
                                </div>
                            </div>
                            
                            <div class="subscription-card-footer">
                                <?php if ($status === 'active') : ?>
                                    <button class="button button-secondary pause-subscription-button" data-subscription-id="<?php echo esc_attr($subscription->ID); ?>"><?php _e('Pause', 'aqualuxe'); ?></button>
                                    <button class="button button-danger cancel-subscription-button" data-subscription-id="<?php echo esc_attr($subscription->ID); ?>"><?php _e('Cancel', 'aqualuxe'); ?></button>
                                <?php elseif ($status === 'paused') : ?>
                                    <button class="button button-success resume-subscription-button" data-subscription-id="<?php echo esc_attr($subscription->ID); ?>"><?php _e('Resume', 'aqualuxe'); ?></button>
                                    <button class="button button-danger cancel-subscription-button" data-subscription-id="<?php echo esc_attr($subscription->ID); ?>"><?php _e('Cancel', 'aqualuxe'); ?></button>
                                <?php endif; ?>
                                <button class="button button-primary update-subscription-button" data-subscription-id="<?php echo esc_attr($subscription->ID); ?>"><?php _e('Update', 'aqualuxe'); ?></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p><?php _e('You don\'t have any active subscriptions.', 'aqualuxe'); ?></p>
                    <p><a href="<?php echo esc_url(get_permalink(get_page_by_path('subscriptions'))); ?>" class="button button-primary"><?php _e('Browse Subscription Plans', 'aqualuxe'); ?></a></p>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($atts['show_history'] === 'yes') : ?>
            <div class="dashboard-section">
                <h3 class="dashboard-section-title"><?php _e('Payment History', 'aqualuxe'); ?></h3>
                
                <div class="payment-history">
                    <?php
                    // Get payment history (this would typically come from your payment gateway)
                    // This is a placeholder for demonstration purposes
                    $payment_history = array(
                        array(
                            'date' => date('Y-m-d', strtotime('-1 month')),
                            'amount' => '29.99',
                            'status' => 'completed',
                            'subscription' => 'Premium Plan',
                            'invoice' => '1001',
                        ),
                        array(
                            'date' => date('Y-m-d', strtotime('-2 months')),
                            'amount' => '29.99',
                            'status' => 'completed',
                            'subscription' => 'Premium Plan',
                            'invoice' => '1002',
                        ),
                    );
                    ?>
                    
                    <?php if (!empty($payment_history)) : ?>
                        <table class="payment-history-table">
                            <thead>
                                <tr>
                                    <th><?php _e('Date', 'aqualuxe'); ?></th>
                                    <th><?php _e('Subscription', 'aqualuxe'); ?></th>
                                    <th><?php _e('Amount', 'aqualuxe'); ?></th>
                                    <th><?php _e('Status', 'aqualuxe'); ?></th>
                                    <th><?php _e('Invoice', 'aqualuxe'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payment_history as $payment) : ?>
                                    <tr>
                                        <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($payment['date']))); ?></td>
                                        <td><?php echo esc_html($payment['subscription']); ?></td>
                                        <td>$<?php echo esc_html(number_format((float)$payment['amount'], 2)); ?></td>
                                        <td><span class="payment-status <?php echo esc_attr($payment['status']); ?>"><?php echo esc_html(ucfirst($payment['status'])); ?></span></td>
                                        <td><a href="#" class="payment-action"><?php echo esc_html($payment['invoice']); ?></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p><?php _e('No payment history found.', 'aqualuxe'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Modal for subscription actions -->
    <div class="subscription-modal-container">
        <div class="subscription-modal-content">
            <span class="subscription-modal-close">&times;</span>
            <h3 class="subscription-modal-title"></h3>
            <div class="subscription-modal-message"></div>
            <div class="subscription-modal-actions">
                <button class="button button-secondary cancel-action-button"><?php _e('Cancel', 'aqualuxe'); ?></button>
                <button class="button confirm-action-button"></button>
            </div>
        </div>
    </div>
    <?php
    
    // Return the output buffer contents
    return ob_get_clean();
}

/**
 * AJAX handler for processing subscription actions
 */
function aqualuxe_process_subscription_action() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'aqualuxe_subscription_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        die();
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in to perform this action.', 'aqualuxe')));
        die();
    }
    
    // Get subscription ID and action
    $subscription_id = isset($_POST['subscription_id']) ? intval($_POST['subscription_id']) : 0;
    $action = isset($_POST['subscription_action']) ? sanitize_text_field($_POST['subscription_action']) : '';
    
    if (empty($subscription_id) || empty($action)) {
        wp_send_json_error(array('message' => __('Invalid request.', 'aqualuxe')));
        die();
    }
    
    // Get subscription
    $subscription = get_post($subscription_id);
    
    if (!$subscription || $subscription->post_type !== 'sub_customer') {
        wp_send_json_error(array('message' => __('Subscription not found.', 'aqualuxe')));
        die();
    }
    
    // Check if user owns this subscription
    $user_id = get_current_user_id();
    $subscription_user_id = get_post_meta($subscription_id, '_user_id', true);
    
    if ($user_id != $subscription_user_id && !current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission to modify this subscription.', 'aqualuxe')));
        die();
    }
    
    // Process action
    switch ($action) {
        case 'cancel':
            // Update subscription status to cancelled
            wp_set_object_terms($subscription_id, 'cancelled', 'subscription_status');
            
            // Update end date to today
            update_post_meta($subscription_id, '_end_date', date('Y-m-d'));
            
            // Clear next payment date
            update_post_meta($subscription_id, '_next_payment_date', '');
            
            wp_send_json_success(array(
                'message' => __('Your subscription has been cancelled.', 'aqualuxe'),
            ));
            break;
            
        case 'pause':
            // Update subscription status to paused
            wp_set_object_terms($subscription_id, 'paused', 'subscription_status');
            
            wp_send_json_success(array(
                'message' => __('Your subscription has been paused.', 'aqualuxe'),
            ));
            break;
            
        case 'resume':
            // Update subscription status to active
            wp_set_object_terms($subscription_id, 'active', 'subscription_status');
            
            // Calculate next payment date
            $next_payment_date = aqualuxe_calculate_next_payment_date($subscription_id);
            
            // Update next payment date
            update_post_meta($subscription_id, '_next_payment_date', $next_payment_date);
            
            wp_send_json_success(array(
                'message' => __('Your subscription has been resumed.', 'aqualuxe'),
            ));
            break;
            
        default:
            wp_send_json_error(array('message' => __('Invalid action.', 'aqualuxe')));
            break;
    }
    
    die();
}
add_action('wp_ajax_aqualuxe_process_subscription_action', 'aqualuxe_process_subscription_action');

/**
 * AJAX handler for getting subscription details
 */
function aqualuxe_get_subscription_details() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'aqualuxe_subscription_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        die();
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in to perform this action.', 'aqualuxe')));
        die();
    }
    
    // Get subscription ID
    $subscription_id = isset($_POST['subscription_id']) ? intval($_POST['subscription_id']) : 0;
    
    if (empty($subscription_id)) {
        wp_send_json_error(array('message' => __('Invalid request.', 'aqualuxe')));
        die();
    }
    
    // Get subscription
    $subscription = get_post($subscription_id);
    
    if (!$subscription || $subscription->post_type !== 'sub_customer') {
        wp_send_json_error(array('message' => __('Subscription not found.', 'aqualuxe')));
        die();
    }
    
    // Check if user owns this subscription
    $user_id = get_current_user_id();
    $subscription_user_id = get_post_meta($subscription_id, '_user_id', true);
    
    if ($user_id != $subscription_user_id && !current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission to view this subscription.', 'aqualuxe')));
        die();
    }
    
    // Get subscription details
    $subscription_plan_id = get_post_meta($subscription_id, '_subscription_id', true);
    $next_payment_date = get_post_meta($subscription_id, '_next_payment_date', true);
    $payment_method = get_post_meta($subscription_id, '_payment_method', true);
    
    // Get frequency terms
    $frequency_terms = get_terms(array(
        'taxonomy' => 'subscription_frequency',
        'hide_empty' => false,
    ));
    
    $frequencies = array();
    $current_frequency = '';
    
    if (!empty($frequency_terms) && !is_wp_error($frequency_terms)) {
        foreach ($frequency_terms as $term) {
            $frequencies[$term->slug] = $term->name;
        }
        
        // Get current frequency
        $subscription_frequency_terms = get_the_terms($subscription_plan_id, 'subscription_frequency');
        if (!empty($subscription_frequency_terms) && !is_wp_error($subscription_frequency_terms)) {
            $current_frequency = $subscription_frequency_terms[0]->slug;
        }
    }
    
    // Get payment methods
    $payment_methods = array(
        'credit_card' => __('Credit Card', 'aqualuxe'),
        'paypal' => __('PayPal', 'aqualuxe'),
        'bank_transfer' => __('Bank Transfer', 'aqualuxe'),
    );
    
    wp_send_json_success(array(
        'frequencies' => $frequencies,
        'current_frequency' => $current_frequency,
        'payment_methods' => $payment_methods,
        'current_payment_method' => $payment_method,
        'next_billing_date' => $next_payment_date,
    ));
    
    die();
}
add_action('wp_ajax_aqualuxe_get_subscription_details', 'aqualuxe_get_subscription_details');

/**
 * AJAX handler for updating subscription
 */
function aqualuxe_update_subscription() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'aqualuxe_subscription_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        die();
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in to perform this action.', 'aqualuxe')));
        die();
    }
    
    // Get subscription ID
    $subscription_id = isset($_POST['subscription_id']) ? intval($_POST['subscription_id']) : 0;
    
    if (empty($subscription_id)) {
        wp_send_json_error(array('message' => __('Invalid request.', 'aqualuxe')));
        die();
    }
    
    // Get subscription
    $subscription = get_post($subscription_id);
    
    if (!$subscription || $subscription->post_type !== 'sub_customer') {
        wp_send_json_error(array('message' => __('Subscription not found.', 'aqualuxe')));
        die();
    }
    
    // Check if user owns this subscription
    $user_id = get_current_user_id();
    $subscription_user_id = get_post_meta($subscription_id, '_user_id', true);
    
    if ($user_id != $subscription_user_id && !current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission to modify this subscription.', 'aqualuxe')));
        die();
    }
    
    // Get form data
    $frequency = isset($_POST['frequency']) ? sanitize_text_field($_POST['frequency']) : '';
    $payment_method = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';
    $next_billing_date = isset($_POST['next_billing_date']) ? sanitize_text_field($_POST['next_billing_date']) : '';
    
    // Update subscription
    if (!empty($frequency)) {
        // Get subscription plan ID
        $subscription_plan_id = get_post_meta($subscription_id, '_subscription_id', true);
        
        // Update subscription plan frequency
        wp_set_object_terms($subscription_plan_id, $frequency, 'subscription_frequency');
    }
    
    if (!empty($payment_method)) {
        update_post_meta($subscription_id, '_payment_method', $payment_method);
    }
    
    if (!empty($next_billing_date)) {
        update_post_meta($subscription_id, '_next_payment_date', $next_billing_date);
    }
    
    wp_send_json_success(array(
        'message' => __('Your subscription has been updated.', 'aqualuxe'),
    ));
    
    die();
}
add_action('wp_ajax_aqualuxe_update_subscription', 'aqualuxe_update_subscription');

/**
 * AJAX handler for processing subscription
 */
function aqualuxe_process_subscription() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'aqualuxe_subscription_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        die();
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in to subscribe.', 'aqualuxe')));
        die();
    }
    
    // Get form data
    $subscription_plan_id = isset($_POST['subscription_plan_id']) ? intval($_POST['subscription_plan_id']) : 0;
    $subscription_frequency = isset($_POST['subscription_frequency']) ? sanitize_text_field($_POST['subscription_frequency']) : '';
    $subscription_start_date = isset($_POST['subscription_start_date']) ? sanitize_text_field($_POST['subscription_start_date']) : '';
    $payment_method = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';
    $terms_accepted = isset($_POST['terms_accepted']) ? true : false;
    $auto_renew = isset($_POST['auto_renew']) ? true : false;
    
    // Validate required fields
    if (empty($subscription_plan_id) || empty($subscription_frequency) || empty($subscription_start_date) || empty($payment_method) || !$terms_accepted) {
        wp_send_json_error(array('message' => __('Please fill in all required fields.', 'aqualuxe')));
        die();
    }
    
    // Get subscription plan
    $subscription_plan = get_post($subscription_plan_id);
    
    if (!$subscription_plan || $subscription_plan->post_type !== 'subscription') {
        wp_send_json_error(array('message' => __('Invalid subscription plan.', 'aqualuxe')));
        die();
    }
    
    // Get user ID
    $user_id = get_current_user_id();
    
    // Get subscription price
    $price = get_post_meta($subscription_plan_id, '_subscription_price', true);
    
    // Calculate next payment date based on frequency and start date
    $next_payment_date = aqualuxe_calculate_next_payment_date_from_start($subscription_start_date, $subscription_frequency);
    
    // Create subscription customer post
    $subscription_customer_id = wp_insert_post(array(
        'post_title' => sprintf(
            '%s - %s',
            get_userdata($user_id)->display_name,
            $subscription_plan->post_title
        ),
        'post_type' => 'sub_customer',
        'post_status' => 'publish',
    ));
    
    if (is_wp_error($subscription_customer_id)) {
        wp_send_json_error(array('message' => __('Failed to create subscription.', 'aqualuxe')));
        die();
    }
    
    // Add subscription meta
    update_post_meta($subscription_customer_id, '_user_id', $user_id);
    update_post_meta($subscription_customer_id, '_subscription_id', $subscription_plan_id);
    update_post_meta($subscription_customer_id, '_start_date', $subscription_start_date);
    update_post_meta($subscription_customer_id, '_next_payment_date', $next_payment_date);
    update_post_meta($subscription_customer_id, '_payment_method', $payment_method);
    update_post_meta($subscription_customer_id, '_billing_amount', $price);
    update_post_meta($subscription_customer_id, '_total_payments', 0);
    update_post_meta($subscription_customer_id, '_auto_renew', $auto_renew ? '1' : '0');
    
    // Set subscription status to active
    wp_set_object_terms($subscription_customer_id, 'active', 'subscription_status');
    
    // Send confirmation email
    aqualuxe_send_subscription_confirmation_email($subscription_customer_id);
    
    // Return success
    wp_send_json_success(array(
        'message' => __('Subscription created successfully!', 'aqualuxe'),
        'redirect_url' => get_permalink(get_page_by_path('my-subscriptions')),
    ));
    
    die();
}
add_action('wp_ajax_aqualuxe_process_subscription', 'aqualuxe_process_subscription');

/**
 * AJAX handler for processing bulk action
 */
function aqualuxe_process_bulk_action() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'aqualuxe_subscription_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        die();
    }
    
    // Check if user has permission
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'aqualuxe')));
        die();
    }
    
    // Get action and subscription IDs
    $action = isset($_POST['bulk_action']) ? sanitize_text_field($_POST['bulk_action']) : '';
    $subscription_ids = isset($_POST['subscription_ids']) ? array_map('intval', $_POST['subscription_ids']) : array();
    
    if (empty($action) || empty($subscription_ids)) {
        wp_send_json_error(array('message' => __('Invalid request.', 'aqualuxe')));
        die();
    }
    
    // Process action for each subscription
    $processed = 0;
    
    foreach ($subscription_ids as $subscription_id) {
        $subscription = get_post($subscription_id);
        
        if (!$subscription || $subscription->post_type !== 'sub_customer') {
            continue;
        }
        
        switch ($action) {
            case 'activate':
                wp_set_object_terms($subscription_id, 'active', 'subscription_status');
                $processed++;
                break;
                
            case 'pause':
                wp_set_object_terms($subscription_id, 'paused', 'subscription_status');
                $processed++;
                break;
                
            case 'cancel':
                wp_set_object_terms($subscription_id, 'cancelled', 'subscription_status');
                update_post_meta($subscription_id, '_end_date', date('Y-m-d'));
                update_post_meta($subscription_id, '_next_payment_date', '');
                $processed++;
                break;
                
            case 'delete':
                wp_delete_post($subscription_id, true);
                $processed++;
                break;
        }
    }
    
    wp_send_json_success(array(
        'message' => sprintf(__('%d subscriptions processed.', 'aqualuxe'), $processed),
    ));
    
    die();
}
add_action('wp_ajax_aqualuxe_process_bulk_action', 'aqualuxe_process_bulk_action');

/**
 * AJAX handler for exporting subscriptions
 */
function aqualuxe_export_subscriptions() {
    // Check nonce
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'aqualuxe_subscription_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        die();
    }
    
    // Check if user has permission
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'aqualuxe')));
        die();
    }
    
    // Get filter values
    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
    $date_start = isset($_POST['date_start']) ? sanitize_text_field($_POST['date_start']) : '';
    $date_end = isset($_POST['date_end']) ? sanitize_text_field($_POST['date_end']) : '';
    
    // Set up query args
    $args = array(
        'post_type' => 'sub_customer',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );
    
    // Add taxonomy query if status is specified
    if (!empty($status)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'subscription_status',
                'field' => 'slug',
                'terms' => $status,
            ),
        );
    }
    
    // Add date query if date range is specified
    if (!empty($date_start) || !empty($date_end)) {
        $args['meta_query'] = array(
            'relation' => 'AND',
        );
        
        if (!empty($date_start)) {
            $args['meta_query'][] = array(
                'key' => '_start_date',
                'value' => $date_start,
                'compare' => '>=',
                'type' => 'DATE',
            );
        }
        
        if (!empty($date_end)) {
            $args['meta_query'][] = array(
                'key' => '_start_date',
                'value' => $date_end,
                'compare' => '<=',
                'type' => 'DATE',
            );
        }
    }
    
    // Get subscriptions
    $subscriptions = get_posts($args);
    
    // Prepare CSV data
    $csv_data = array();
    
    // Add header row
    $csv_data[] = array(
        'ID',
        'Customer',
        'Email',
        'Subscription Plan',
        'Status',
        'Start Date',
        'Next Payment Date',
        'End Date',
        'Payment Method',
        'Billing Amount',
        'Total Payments',
    );
    
    // Add subscription rows
    foreach ($subscriptions as $subscription) {
        $user_id = get_post_meta($subscription->ID, '_user_id', true);
        $user = get_userdata($user_id);
        
        if (!$user) {
            continue;
        }
        
        $subscription_id = get_post_meta($subscription->ID, '_subscription_id', true);
        $subscription_plan = get_post($subscription_id);
        
        if (!$subscription_plan) {
            continue;
        }
        
        $status_terms = get_the_terms($subscription->ID, 'subscription_status');
        $status = !empty($status_terms) ? $status_terms[0]->name : '';
        
        $start_date = get_post_meta($subscription->ID, '_start_date', true);
        $next_payment_date = get_post_meta($subscription->ID, '_next_payment_date', true);
        $end_date = get_post_meta($subscription->ID, '_end_date', true);
        $payment_method = get_post_meta($subscription->ID, '_payment_method', true);
        $billing_amount = get_post_meta($subscription->ID, '_billing_amount', true);
        $total_payments = get_post_meta($subscription->ID, '_total_payments', true);
        
        $csv_data[] = array(
            $subscription->ID,
            $user->display_name,
            $user->user_email,
            $subscription_plan->post_title,
            $status,
            $start_date,
            $next_payment_date,
            $end_date,
            $payment_method,
            $billing_amount,
            $total_payments,
        );
    }
    
    // Generate CSV file
    $upload_dir = wp_upload_dir();
    $filename = 'subscriptions-export-' . date('Y-m-d') . '.csv';
    $file_path = $upload_dir['path'] . '/' . $filename;
    $file_url = $upload_dir['url'] . '/' . $filename;
    
    $file = fopen($file_path, 'w');
    
    foreach ($csv_data as $row) {
        fputcsv($file, $row);
    }
    
    fclose($file);
    
    wp_send_json_success(array(
        'file_url' => $file_url,
        'filename' => $filename,
    ));
    
    die();
}
add_action('wp_ajax_aqualuxe_export_subscriptions', 'aqualuxe_export_subscriptions');

/**
 * Calculate next payment date based on subscription ID
 */
function aqualuxe_calculate_next_payment_date($subscription_id) {
    // Get subscription plan ID
    $subscription_plan_id = get_post_meta($subscription_id, '_subscription_id', true);
    
    // Get frequency
    $frequency_terms = get_the_terms($subscription_plan_id, 'subscription_frequency');
    $frequency = !empty($frequency_terms) ? $frequency_terms[0]->slug : 'monthly';
    
    // Get start date
    $start_date = get_post_meta($subscription_id, '_start_date', true);
    
    // Calculate next payment date
    return aqualuxe_calculate_next_payment_date_from_start($start_date, $frequency);
}

/**
 * Calculate next payment date based on start date and frequency
 */
function aqualuxe_calculate_next_payment_date_from_start($start_date, $frequency) {
    $start_timestamp = strtotime($start_date);
    $current_timestamp = current_time('timestamp');
    
    // If start date is in the future, return start date
    if ($start_timestamp > $current_timestamp) {
        return $start_date;
    }
    
    // Calculate interval based on frequency
    switch ($frequency) {
        case 'weekly':
            $interval = '+1 week';
            break;
        case 'biweekly':
            $interval = '+2 weeks';
            break;
        case 'monthly':
            $interval = '+1 month';
            break;
        case 'quarterly':
            $interval = '+3 months';
            break;
        case 'biannually':
            $interval = '+6 months';
            break;
        case 'annually':
            $interval = '+1 year';
            break;
        default:
            $interval = '+1 month';
    }
    
    // Calculate next payment date
    $next_date = $start_timestamp;
    
    while ($next_date <= $current_timestamp) {
        $next_date = strtotime($interval, $next_date);
    }
    
    return date('Y-m-d', $next_date);
}

/**
 * Send subscription confirmation email
 */
function aqualuxe_send_subscription_confirmation_email($subscription_id) {
    // Get subscription details
    $subscription = get_post($subscription_id);
    
    if (!$subscription || $subscription->post_type !== 'sub_customer') {
        return;
    }
    
    // Get user ID
    $user_id = get_post_meta($subscription_id, '_user_id', true);
    $user = get_userdata($user_id);
    
    if (!$user) {
        return;
    }
    
    // Get subscription plan ID
    $subscription_plan_id = get_post_meta($subscription_id, '_subscription_id', true);
    $subscription_plan = get_post($subscription_plan_id);
    
    if (!$subscription_plan) {
        return;
    }
    
    // Get subscription details
    $start_date = get_post_meta($subscription_id, '_start_date', true);
    $next_payment_date = get_post_meta($subscription_id, '_next_payment_date', true);
    $billing_amount = get_post_meta($subscription_id, '_billing_amount', true);
    $payment_method = get_post_meta($subscription_id, '_payment_method', true);
    
    // Build email content
    $to = $user->user_email;
    $subject = sprintf(__('Your %s Subscription Confirmation', 'aqualuxe'), get_bloginfo('name'));
    
    $message = sprintf(__('Hello %s,', 'aqualuxe'), $user->display_name) . "\n\n";
    $message .= sprintf(__('Thank you for subscribing to %s!', 'aqualuxe'), $subscription_plan->post_title) . "\n\n";
    $message .= __('Here are your subscription details:', 'aqualuxe') . "\n\n";
    $message .= sprintf(__('Subscription Plan: %s', 'aqualuxe'), $subscription_plan->post_title) . "\n";
    $message .= sprintf(__('Start Date: %s', 'aqualuxe'), date_i18n(get_option('date_format'), strtotime($start_date))) . "\n";
    $message .= sprintf(__('Next Payment Date: %s', 'aqualuxe'), date_i18n(get_option('date_format'), strtotime($next_payment_date))) . "\n";
    $message .= sprintf(__('Billing Amount: $%s', 'aqualuxe'), number_format((float)$billing_amount, 2)) . "\n";
    $message .= sprintf(__('Payment Method: %s', 'aqualuxe'), $payment_method) . "\n\n";
    $message .= __('You can manage your subscription from your account dashboard:', 'aqualuxe') . "\n";
    $message .= get_permalink(get_page_by_path('my-subscriptions')) . "\n\n";
    $message .= __('Thank you for your business!', 'aqualuxe') . "\n\n";
    $message .= sprintf(__('The %s Team', 'aqualuxe'), get_bloginfo('name'));
    
    // Send email
    wp_mail($to, $subject, $message);
    
    // Send admin notification
    $admin_email = get_option('admin_email');
    $admin_subject = sprintf(__('New Subscription: %s', 'aqualuxe'), $subscription_plan->post_title);
    
    $admin_message = sprintf(__('A new subscription has been created:', 'aqualuxe')) . "\n\n";
    $admin_message .= sprintf(__('Customer: %s (%s)', 'aqualuxe'), $user->display_name, $user->user_email) . "\n";
    $admin_message .= sprintf(__('Subscription Plan: %s', 'aqualuxe'), $subscription_plan->post_title) . "\n";
    $admin_message .= sprintf(__('Start Date: %s', 'aqualuxe'), date_i18n(get_option('date_format'), strtotime($start_date))) . "\n";
    $admin_message .= sprintf(__('Next Payment Date: %s', 'aqualuxe'), date_i18n(get_option('date_format'), strtotime($next_payment_date))) . "\n";
    $admin_message .= sprintf(__('Billing Amount: $%s', 'aqualuxe'), number_format((float)$billing_amount, 2)) . "\n";
    $admin_message .= sprintf(__('Payment Method: %s', 'aqualuxe'), $payment_method) . "\n\n";
    $admin_message .= __('View subscription details:', 'aqualuxe') . "\n";
    $admin_message .= admin_url('post.php?post=' . $subscription_id . '&action=edit');
    
    wp_mail($admin_email, $admin_subject, $admin_message);
}

/**
 * Add subscription management page to My Account menu
 */
function aqualuxe_add_subscription_endpoint() {
    add_rewrite_endpoint('subscriptions', EP_ROOT | EP_PAGES);
}
add_action('init', 'aqualuxe_add_subscription_endpoint');

/**
 * Add subscription management menu item
 */
function aqualuxe_add_subscription_menu_item($items) {
    $items['subscriptions'] = __('Subscriptions', 'aqualuxe');
    return $items;
}
add_filter('woocommerce_account_menu_items', 'aqualuxe_add_subscription_menu_item');

/**
 * Add subscription management content
 */
function aqualuxe_subscription_endpoint_content() {
    echo do_shortcode('[my_subscriptions]');
}
add_action('woocommerce_account_subscriptions_endpoint', 'aqualuxe_subscription_endpoint_content');

/**
 * Schedule daily subscription processing
 */
function aqualuxe_schedule_subscription_processing() {
    if (!wp_next_scheduled('aqualuxe_process_subscriptions')) {
        wp_schedule_event(time(), 'daily', 'aqualuxe_process_subscriptions');
    }
}
add_action('wp', 'aqualuxe_schedule_subscription_processing');

/**
 * Process subscriptions daily
 */
function aqualuxe_process_subscriptions() {
    // Get active subscriptions with next payment date today or earlier
    $args = array(
        'post_type' => 'sub_customer',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'subscription_status',
                'field' => 'slug',
                'terms' => 'active',
            ),
        ),
        'meta_query' => array(
            array(
                'key' => '_next_payment_date',
                'value' => date('Y-m-d'),
                'compare' => '<=',
                'type' => 'DATE',
            ),
        ),
    );
    
    $subscriptions = get_posts($args);
    
    foreach ($subscriptions as $subscription) {
        // Process payment
        $payment_processed = aqualuxe_process_subscription_payment($subscription->ID);
        
        if ($payment_processed) {
            // Update next payment date
            $next_payment_date = aqualuxe_calculate_next_payment_date($subscription->ID);
            update_post_meta($subscription->ID, '_next_payment_date', $next_payment_date);
            
            // Increment total payments
            $total_payments = get_post_meta($subscription->ID, '_total_payments', true);
            update_post_meta($subscription->ID, '_total_payments', intval($total_payments) + 1);
            
            // Send payment confirmation email
            aqualuxe_send_payment_confirmation_email($subscription->ID);
        } else {
            // Handle failed payment
            aqualuxe_handle_failed_payment($subscription->ID);
        }
    }
}
add_action('aqualuxe_process_subscriptions', 'aqualuxe_process_subscriptions');

/**
 * Process subscription payment
 */
function aqualuxe_process_subscription_payment($subscription_id) {
    // This is a placeholder function for demonstration purposes
    // In a real-world scenario, this would integrate with a payment gateway
    
    // Get subscription details
    $billing_amount = get_post_meta($subscription_id, '_billing_amount', true);
    $payment_method = get_post_meta($subscription_id, '_payment_method', true);
    
    // Simulate payment processing
    // In a real implementation, this would call the payment gateway API
    $payment_successful = true;
    
    // Log payment
    if ($payment_successful) {
        // Create payment record
        $payment_id = wp_insert_post(array(
            'post_title' => sprintf(__('Payment for Subscription #%d', 'aqualuxe'), $subscription_id),
            'post_type' => 'subscription_payment',
            'post_status' => 'publish',
        ));
        
        if (!is_wp_error($payment_id)) {
            // Add payment meta
            update_post_meta($payment_id, '_subscription_id', $subscription_id);
            update_post_meta($payment_id, '_amount', $billing_amount);
            update_post_meta($payment_id, '_payment_method', $payment_method);
            update_post_meta($payment_id, '_payment_date', date('Y-m-d'));
            update_post_meta($payment_id, '_status', 'completed');
        }
    }
    
    return $payment_successful;
}

/**
 * Handle failed payment
 */
function aqualuxe_handle_failed_payment($subscription_id) {
    // Update subscription status to on-hold
    wp_set_object_terms($subscription_id, 'on-hold', 'subscription_status');
    
    // Send failed payment notification
    aqualuxe_send_failed_payment_email($subscription_id);
}

/**
 * Send payment confirmation email
 */
function aqualuxe_send_payment_confirmation_email($subscription_id) {
    // Get subscription details
    $subscription = get_post($subscription_id);
    
    if (!$subscription || $subscription->post_type !== 'sub_customer') {
        return;
    }
    
    // Get user ID
    $user_id = get_post_meta($subscription_id, '_user_id', true);
    $user = get_userdata($user_id);
    
    if (!$user) {
        return;
    }
    
    // Get subscription plan ID
    $subscription_plan_id = get_post_meta($subscription_id, '_subscription_id', true);
    $subscription_plan = get_post($subscription_plan_id);
    
    if (!$subscription_plan) {
        return;
    }
    
    // Get subscription details
    $billing_amount = get_post_meta($subscription_id, '_billing_amount', true);
    $next_payment_date = get_post_meta($subscription_id, '_next_payment_date', true);
    
    // Build email content
    $to = $user->user_email;
    $subject = sprintf(__('Payment Confirmation for %s Subscription', 'aqualuxe'), get_bloginfo('name'));
    
    $message = sprintf(__('Hello %s,', 'aqualuxe'), $user->display_name) . "\n\n";
    $message .= sprintf(__('We\'ve processed your payment for the %s subscription.', 'aqualuxe'), $subscription_plan->post_title) . "\n\n";
    $message .= __('Payment details:', 'aqualuxe') . "\n";
    $message .= sprintf(__('Amount: $%s', 'aqualuxe'), number_format((float)$billing_amount, 2)) . "\n";
    $message .= sprintf(__('Date: %s', 'aqualuxe'), date_i18n(get_option('date_format'))) . "\n";
    $message .= sprintf(__('Next Payment Date: %s', 'aqualuxe'), date_i18n(get_option('date_format'), strtotime($next_payment_date))) . "\n\n";
    $message .= __('You can view your subscription details in your account:', 'aqualuxe') . "\n";
    $message .= get_permalink(get_page_by_path('my-subscriptions')) . "\n\n";
    $message .= __('Thank you for your continued support!', 'aqualuxe') . "\n\n";
    $message .= sprintf(__('The %s Team', 'aqualuxe'), get_bloginfo('name'));
    
    // Send email
    wp_mail($to, $subject, $message);
}

/**
 * Send failed payment email
 */
function aqualuxe_send_failed_payment_email($subscription_id) {
    // Get subscription details
    $subscription = get_post($subscription_id);
    
    if (!$subscription || $subscription->post_type !== 'sub_customer') {
        return;
    }
    
    // Get user ID
    $user_id = get_post_meta($subscription_id, '_user_id', true);
    $user = get_userdata($user_id);
    
    if (!$user) {
        return;
    }
    
    // Get subscription plan ID
    $subscription_plan_id = get_post_meta($subscription_id, '_subscription_id', true);
    $subscription_plan = get_post($subscription_plan_id);
    
    if (!$subscription_plan) {
        return;
    }
    
    // Get subscription details
    $billing_amount = get_post_meta($subscription_id, '_billing_amount', true);
    
    // Build email content
    $to = $user->user_email;
    $subject = sprintf(__('Payment Failed for %s Subscription', 'aqualuxe'), get_bloginfo('name'));
    
    $message = sprintf(__('Hello %s,', 'aqualuxe'), $user->display_name) . "\n\n";
    $message .= sprintf(__('We were unable to process your payment for the %s subscription.', 'aqualuxe'), $subscription_plan->post_title) . "\n\n";
    $message .= __('Payment details:', 'aqualuxe') . "\n";
    $message .= sprintf(__('Amount: $%s', 'aqualuxe'), number_format((float)$billing_amount, 2)) . "\n";
    $message .= sprintf(__('Date: %s', 'aqualuxe'), date_i18n(get_option('date_format'))) . "\n\n";
    $message .= __('Your subscription has been placed on hold. Please update your payment information to reactivate your subscription:', 'aqualuxe') . "\n";
    $message .= get_permalink(get_page_by_path('my-subscriptions')) . "\n\n";
    $message .= __('If you have any questions, please contact our support team.', 'aqualuxe') . "\n\n";
    $message .= sprintf(__('The %s Team', 'aqualuxe'), get_bloginfo('name'));
    
    // Send email
    wp_mail($to, $subject, $message);
    
    // Send admin notification
    $admin_email = get_option('admin_email');
    $admin_subject = sprintf(__('Failed Payment: %s', 'aqualuxe'), $subscription_plan->post_title);
    
    $admin_message = sprintf(__('A payment has failed for the following subscription:', 'aqualuxe')) . "\n\n";
    $admin_message .= sprintf(__('Customer: %s (%s)', 'aqualuxe'), $user->display_name, $user->user_email) . "\n";
    $admin_message .= sprintf(__('Subscription Plan: %s', 'aqualuxe'), $subscription_plan->post_title) . "\n";
    $admin_message .= sprintf(__('Billing Amount: $%s', 'aqualuxe'), number_format((float)$billing_amount, 2)) . "\n\n";
    $admin_message .= __('The subscription has been placed on hold.', 'aqualuxe') . "\n\n";
    $admin_message .= __('View subscription details:', 'aqualuxe') . "\n";
    $admin_message .= admin_url('post.php?post=' . $subscription_id . '&action=edit');
    
    wp_mail($admin_email, $admin_subject, $admin_message);
}

/**
 * Register subscription payment post type
 */
function aqualuxe_register_subscription_payment_post_type() {
    $labels = array(
        'name'               => _x('Subscription Payments', 'post type general name', 'aqualuxe'),
        'singular_name'      => _x('Subscription Payment', 'post type singular name', 'aqualuxe'),
        'menu_name'          => _x('Payments', 'admin menu', 'aqualuxe'),
        'name_admin_bar'     => _x('Subscription Payment', 'add new on admin bar', 'aqualuxe'),
        'add_new'            => _x('Add New', 'subscription payment', 'aqualuxe'),
        'add_new_item'       => __('Add New Subscription Payment', 'aqualuxe'),
        'new_item'           => __('New Subscription Payment', 'aqualuxe'),
        'edit_item'          => __('Edit Subscription Payment', 'aqualuxe'),
        'view_item'          => __('View Subscription Payment', 'aqualuxe'),
        'all_items'          => __('All Subscription Payments', 'aqualuxe'),
        'search_items'       => __('Search Subscription Payments', 'aqualuxe'),
        'parent_item_colon'  => __('Parent Subscription Payments:', 'aqualuxe'),
        'not_found'          => __('No subscription payments found.', 'aqualuxe'),
        'not_found_in_trash' => __('No subscription payments found in Trash.', 'aqualuxe')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Subscription payment records', 'aqualuxe'),
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => 'edit.php?post_type=subscription',
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title'),
    );

    register_post_type('subscription_payment', $args);
}
add_action('init', 'aqualuxe_register_subscription_payment_post_type');