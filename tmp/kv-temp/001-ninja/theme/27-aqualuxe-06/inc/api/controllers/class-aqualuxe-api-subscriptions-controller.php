<?php
/**
 * AquaLuxe API Subscriptions Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Subscriptions_Controller Class
 *
 * Handles API requests for subscriptions
 */
class AquaLuxe_API_Subscriptions_Controller extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'subscriptions';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Get all subscriptions
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get single subscription
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_item'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the subscription.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Update subscription
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_item'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the subscription.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'payment_method' => array(
                        'description' => __('Payment method ID.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'billing_interval' => array(
                        'description' => __('Billing interval.'),
                        'type' => 'integer',
                        'sanitize_callback' => 'absint',
                    ),
                    'billing_period' => array(
                        'description' => __('Billing period.'),
                        'type' => 'string',
                        'enum' => array('day', 'week', 'month', 'year'),
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'next_payment_date' => array(
                        'description' => __('Next payment date.'),
                        'type' => 'string',
                        'format' => 'date-time',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'billing_address' => array(
                        'description' => __('Billing address.'),
                        'type' => 'object',
                    ),
                    'shipping_address' => array(
                        'description' => __('Shipping address.'),
                        'type' => 'object',
                    ),
                ),
            ),
        ));

        // Cancel subscription
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/cancel', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'cancel_subscription'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the subscription.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Suspend subscription
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/suspend', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'suspend_subscription'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the subscription.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Reactivate subscription
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/reactivate', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'reactivate_subscription'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the subscription.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get subscription orders
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/orders', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_subscription_orders'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the subscription.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get subscription payment method
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/payment-method', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_subscription_payment_method'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the subscription.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Update subscription payment method
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/payment-method', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_subscription_payment_method'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the subscription.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'payment_method' => array(
                        'description' => __('Payment method ID.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'payment_token' => array(
                        'description' => __('Payment token ID.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
            ),
        ));

        // Get subscription notes
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/notes', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_subscription_notes'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the subscription.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Add subscription note
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/notes', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_subscription_note'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the subscription.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'note' => array(
                        'description' => __('Subscription note content.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_textarea_field',
                    ),
                    'customer_note' => array(
                        'description' => __('If true, the note will be shown to customers.'),
                        'type' => 'boolean',
                        'default' => false,
                    ),
                ),
            ),
        ));
    }

    /**
     * Get all subscriptions.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_items($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $user_id = $this->get_current_user_id();
        
        // Check if user is admin
        $is_admin = $this->is_current_user_admin();
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'id', 'status'));
        
        // Get filter params
        $status = $request->get_param('status');
        $customer_id = $request->get_param('customer_id');
        
        // Get date range
        $date_range = $this->get_date_range_params($request);
        
        // Query args
        $args = array(
            'limit' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'type' => 'shop_subscription',
            'return' => 'objects',
        );
        
        // Add status filter
        if (!empty($status)) {
            if (strpos($status, ',') !== false) {
                $statuses = explode(',', $status);
                $args['status'] = $statuses;
            } else {
                $args['status'] = $status;
            }
        }
        
        // Add customer filter
        if (!empty($customer_id) && $is_admin) {
            $args['customer_id'] = $customer_id;
        } else {
            // Non-admin users can only see their own subscriptions
            if (!$is_admin) {
                $args['customer_id'] = $user_id;
            }
        }
        
        // Add date range
        if (!empty($date_range)) {
            if (isset($date_range['start_date'])) {
                $args['date_created'] = '>=' . strtotime($date_range['start_date']);
            }
            
            if (isset($date_range['end_date'])) {
                if (isset($args['date_created'])) {
                    $args['date_created'] .= ';';
                }
                
                $args['date_created'] .= '<=' . strtotime($date_range['end_date'] . ' 23:59:59');
            }
        }
        
        // Get subscriptions
        $query = wcs_get_subscriptions($args);
        
        // Count total
        $count_args = $args;
        $count_args['limit'] = -1;
        $count_args['return'] = 'ids';
        $total = count(wcs_get_subscriptions($count_args));
        
        $data = array();
        
        foreach ($query as $subscription) {
            $data[] = $this->prepare_subscription_data($subscription);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'subscriptions' => $data,
            'total' => $total,
            'pages' => ceil($total / $pagination['per_page']),
        ));
        
        // Add pagination headers
        $response = $this->add_pagination_headers($response, $total, $pagination['per_page']);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get a single subscription.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $subscription_id = $request->get_param('id');
        $subscription = wcs_get_subscription($subscription_id);
        
        if (!$subscription) {
            return $this->format_error('subscription_not_found', __('Subscription not found.'), 404);
        }
        
        // Check if user has permission to view this subscription
        if (!$this->is_current_user_admin() && $subscription->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this subscription.'), 403);
        }
        
        $data = $this->prepare_subscription_data($subscription, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Update a subscription.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $subscription_id = $request->get_param('id');
        $subscription = wcs_get_subscription($subscription_id);
        
        if (!$subscription) {
            return $this->format_error('subscription_not_found', __('Subscription not found.'), 404);
        }
        
        // Check if user has permission to update this subscription
        if (!$this->is_current_user_admin() && $subscription->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to update this subscription.'), 403);
        }
        
        // Update payment method
        $payment_method = $request->get_param('payment_method');
        
        if ($payment_method) {
            $subscription->set_payment_method($payment_method);
        }
        
        // Update billing interval
        $billing_interval = $request->get_param('billing_interval');
        
        if ($billing_interval) {
            // Only admins can update billing interval
            if (!$this->is_current_user_admin()) {
                return $this->format_error('permission_denied', __('You do not have permission to update the billing interval.'), 403);
            }
            
            $subscription->set_billing_interval($billing_interval);
        }
        
        // Update billing period
        $billing_period = $request->get_param('billing_period');
        
        if ($billing_period) {
            // Only admins can update billing period
            if (!$this->is_current_user_admin()) {
                return $this->format_error('permission_denied', __('You do not have permission to update the billing period.'), 403);
            }
            
            $subscription->set_billing_period($billing_period);
        }
        
        // Update next payment date
        $next_payment_date = $request->get_param('next_payment_date');
        
        if ($next_payment_date) {
            // Only admins can update next payment date
            if (!$this->is_current_user_admin()) {
                return $this->format_error('permission_denied', __('You do not have permission to update the next payment date.'), 403);
            }
            
            $subscription->update_dates(array('next_payment' => $next_payment_date));
        }
        
        // Update billing address
        $billing_address = $request->get_param('billing_address');
        
        if ($billing_address) {
            $subscription->set_address($billing_address, 'billing');
        }
        
        // Update shipping address
        $shipping_address = $request->get_param('shipping_address');
        
        if ($shipping_address) {
            $subscription->set_address($shipping_address, 'shipping');
        }
        
        // Save subscription
        $subscription->save();
        
        // Add subscription note
        $subscription->add_order_note(__('Subscription updated via AquaLuxe API.'), false);
        
        // Get updated subscription
        $data = $this->prepare_subscription_data($subscription, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Cancel a subscription.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function cancel_subscription($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $subscription_id = $request->get_param('id');
        $subscription = wcs_get_subscription($subscription_id);
        
        if (!$subscription) {
            return $this->format_error('subscription_not_found', __('Subscription not found.'), 404);
        }
        
        // Check if user has permission to cancel this subscription
        if (!$this->is_current_user_admin() && $subscription->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to cancel this subscription.'), 403);
        }
        
        // Check if subscription can be cancelled
        if (!$subscription->can_be_updated_to('cancelled')) {
            return $this->format_error('cannot_cancel', __('This subscription cannot be cancelled.'), 400);
        }
        
        // Cancel subscription
        $subscription->update_status('cancelled', __('Subscription cancelled via AquaLuxe API.'));
        
        // Get updated subscription
        $data = $this->prepare_subscription_data($subscription, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Suspend a subscription.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function suspend_subscription($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $subscription_id = $request->get_param('id');
        $subscription = wcs_get_subscription($subscription_id);
        
        if (!$subscription) {
            return $this->format_error('subscription_not_found', __('Subscription not found.'), 404);
        }
        
        // Check if user has permission to suspend this subscription
        if (!$this->is_current_user_admin() && $subscription->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to suspend this subscription.'), 403);
        }
        
        // Check if subscription can be suspended
        if (!$subscription->can_be_updated_to('on-hold')) {
            return $this->format_error('cannot_suspend', __('This subscription cannot be suspended.'), 400);
        }
        
        // Suspend subscription
        $subscription->update_status('on-hold', __('Subscription suspended via AquaLuxe API.'));
        
        // Get updated subscription
        $data = $this->prepare_subscription_data($subscription, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Reactivate a subscription.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function reactivate_subscription($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $subscription_id = $request->get_param('id');
        $subscription = wcs_get_subscription($subscription_id);
        
        if (!$subscription) {
            return $this->format_error('subscription_not_found', __('Subscription not found.'), 404);
        }
        
        // Check if user has permission to reactivate this subscription
        if (!$this->is_current_user_admin() && $subscription->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to reactivate this subscription.'), 403);
        }
        
        // Check if subscription can be reactivated
        if (!$subscription->can_be_updated_to('active')) {
            return $this->format_error('cannot_reactivate', __('This subscription cannot be reactivated.'), 400);
        }
        
        // Reactivate subscription
        $subscription->update_status('active', __('Subscription reactivated via AquaLuxe API.'));
        
        // Get updated subscription
        $data = $this->prepare_subscription_data($subscription, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get subscription orders.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_subscription_orders($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $subscription_id = $request->get_param('id');
        $subscription = wcs_get_subscription($subscription_id);
        
        if (!$subscription) {
            return $this->format_error('subscription_not_found', __('Subscription not found.'), 404);
        }
        
        // Check if user has permission to view this subscription
        if (!$this->is_current_user_admin() && $subscription->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this subscription.'), 403);
        }
        
        // Get orders
        $orders = $subscription->get_related_orders();
        $data = array();
        
        foreach ($orders as $order_id) {
            $order = wc_get_order($order_id);
            
            if ($order) {
                $data[] = array(
                    'id' => $order->get_id(),
                    'number' => $order->get_order_number(),
                    'status' => $order->get_status(),
                    'status_name' => wc_get_order_status_name($order->get_status()),
                    'date_created' => wc_rest_prepare_date_response($order->get_date_created()),
                    'total' => $order->get_total(),
                    'currency' => $order->get_currency(),
                    'payment_method' => $order->get_payment_method(),
                    'payment_method_title' => $order->get_payment_method_title(),
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'orders' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get subscription payment method.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_subscription_payment_method($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $subscription_id = $request->get_param('id');
        $subscription = wcs_get_subscription($subscription_id);
        
        if (!$subscription) {
            return $this->format_error('subscription_not_found', __('Subscription not found.'), 404);
        }
        
        // Check if user has permission to view this subscription
        if (!$this->is_current_user_admin() && $subscription->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this subscription.'), 403);
        }
        
        // Get payment method
        $payment_method = array(
            'method_id' => $subscription->get_payment_method(),
            'method_title' => $subscription->get_payment_method_title(),
            'method_description' => '',
        );
        
        // Get payment token
        $token = $subscription->get_payment_token();
        
        if ($token) {
            $token_obj = WC_Payment_Tokens::get($token);
            
            if ($token_obj) {
                $payment_method['token'] = array(
                    'id' => $token_obj->get_id(),
                    'type' => $token_obj->get_type(),
                    'last4' => $token_obj->get_last4(),
                    'expiry_month' => $token_obj->get_expiry_month(),
                    'expiry_year' => $token_obj->get_expiry_year(),
                    'card_type' => $token_obj->get_card_type(),
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response($payment_method);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Update subscription payment method.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_subscription_payment_method($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $subscription_id = $request->get_param('id');
        $subscription = wcs_get_subscription($subscription_id);
        
        if (!$subscription) {
            return $this->format_error('subscription_not_found', __('Subscription not found.'), 404);
        }
        
        // Check if user has permission to update this subscription
        if (!$this->is_current_user_admin() && $subscription->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to update this subscription.'), 403);
        }
        
        $payment_method = $request->get_param('payment_method');
        $payment_token = $request->get_param('payment_token');
        
        // Update payment method
        $subscription->set_payment_method($payment_method);
        
        // Update payment token
        if ($payment_token) {
            $subscription->update_meta_data('_payment_tokens', array($payment_token));
        }
        
        // Save subscription
        $subscription->save();
        
        // Add subscription note
        $subscription->add_order_note(__('Payment method updated via AquaLuxe API.'), false);
        
        // Get updated payment method
        $updated_payment_method = array(
            'method_id' => $subscription->get_payment_method(),
            'method_title' => $subscription->get_payment_method_title(),
            'method_description' => '',
        );
        
        // Get payment token
        $token = $subscription->get_payment_token();
        
        if ($token) {
            $token_obj = WC_Payment_Tokens::get($token);
            
            if ($token_obj) {
                $updated_payment_method['token'] = array(
                    'id' => $token_obj->get_id(),
                    'type' => $token_obj->get_type(),
                    'last4' => $token_obj->get_last4(),
                    'expiry_month' => $token_obj->get_expiry_month(),
                    'expiry_year' => $token_obj->get_expiry_year(),
                    'card_type' => $token_obj->get_card_type(),
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response($updated_payment_method);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get subscription notes.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_subscription_notes($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $subscription_id = $request->get_param('id');
        $subscription = wcs_get_subscription($subscription_id);
        
        if (!$subscription) {
            return $this->format_error('subscription_not_found', __('Subscription not found.'), 404);
        }
        
        // Check if user has permission to view this subscription
        if (!$this->is_current_user_admin() && $subscription->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this subscription.'), 403);
        }
        
        // Get notes
        $args = array(
            'post_id' => $subscription_id,
            'approve' => 'approve',
            'type' => 'order_note',
        );
        
        // Non-admin users can only see customer notes
        if (!$this->is_current_user_admin()) {
            $args['meta_query'] = array(
                array(
                    'key' => 'is_customer_note',
                    'value' => 1,
                    'compare' => '=',
                ),
            );
        }
        
        $notes = wc_get_order_notes($args);
        $data = array();
        
        foreach ($notes as $note) {
            $data[] = array(
                'id' => $note->id,
                'author' => $note->added_by,
                'date' => $note->date_created->date('c'),
                'content' => $note->content,
                'is_customer_note' => (bool) $note->customer_note,
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'notes' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Create subscription note.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_subscription_note($request) {
        $start_time = microtime(true);
        
        // Check if WooCommerce Subscriptions is active
        if (!class_exists('WC_Subscriptions')) {
            return $this->format_error('subscriptions_not_available', __('WooCommerce Subscriptions is not available.'), 501);
        }
        
        $subscription_id = $request->get_param('id');
        $subscription = wcs_get_subscription($subscription_id);
        
        if (!$subscription) {
            return $this->format_error('subscription_not_found', __('Subscription not found.'), 404);
        }
        
        // Check if user has permission to update this subscription
        if (!$this->is_current_user_admin() && $subscription->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to update this subscription.'), 403);
        }
        
        $note = $request->get_param('note');
        $customer_note = $request->get_param('customer_note');
        
        // Add note
        $note_id = $subscription->add_order_note($note, $customer_note, true);
        
        if (!$note_id) {
            return $this->format_error('note_creation_failed', __('Failed to create subscription note.'), 500);
        }
        
        // Get note
        $note = wc_get_order_note($note_id);
        
        $data = array(
            'id' => $note->id,
            'author' => $note->added_by,
            'date' => $note->date_created->date('c'),
            'content' => $note->content,
            'is_customer_note' => (bool) $note->customer_note,
        );
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Prepare subscription data for API response.
     *
     * @param WC_Subscription $subscription Subscription object.
     * @param bool $single Whether this is a single subscription request.
     * @return array
     */
    protected function prepare_subscription_data($subscription, $single = false) {
        $data = array(
            'id' => $subscription->get_id(),
            'parent_id' => $subscription->get_parent_id(),
            'status' => $subscription->get_status(),
            'status_name' => wcs_get_subscription_status_name($subscription->get_status()),
            'currency' => $subscription->get_currency(),
            'date_created' => wc_rest_prepare_date_response($subscription->get_date_created()),
            'date_modified' => wc_rest_prepare_date_response($subscription->get_date_modified()),
            'discount_total' => $subscription->get_discount_total(),
            'discount_tax' => $subscription->get_discount_tax(),
            'shipping_total' => $subscription->get_shipping_total(),
            'shipping_tax' => $subscription->get_shipping_tax(),
            'cart_tax' => $subscription->get_cart_tax(),
            'total' => $subscription->get_total(),
            'total_tax' => $subscription->get_total_tax(),
            'customer_id' => $subscription->get_customer_id(),
            'billing' => array(
                'first_name' => $subscription->get_billing_first_name(),
                'last_name' => $subscription->get_billing_last_name(),
                'company' => $subscription->get_billing_company(),
                'address_1' => $subscription->get_billing_address_1(),
                'address_2' => $subscription->get_billing_address_2(),
                'city' => $subscription->get_billing_city(),
                'state' => $subscription->get_billing_state(),
                'postcode' => $subscription->get_billing_postcode(),
                'country' => $subscription->get_billing_country(),
                'email' => $subscription->get_billing_email(),
                'phone' => $subscription->get_billing_phone(),
            ),
            'shipping' => array(
                'first_name' => $subscription->get_shipping_first_name(),
                'last_name' => $subscription->get_shipping_last_name(),
                'company' => $subscription->get_shipping_company(),
                'address_1' => $subscription->get_shipping_address_1(),
                'address_2' => $subscription->get_shipping_address_2(),
                'city' => $subscription->get_shipping_city(),
                'state' => $subscription->get_shipping_state(),
                'postcode' => $subscription->get_shipping_postcode(),
                'country' => $subscription->get_shipping_country(),
            ),
            'payment_method' => $subscription->get_payment_method(),
            'payment_method_title' => $subscription->get_payment_method_title(),
            'start_date' => wc_rest_prepare_date_response($subscription->get_date('start')),
            'trial_end_date' => wc_rest_prepare_date_response($subscription->get_date('trial_end')),
            'next_payment_date' => wc_rest_prepare_date_response($subscription->get_date('next_payment')),
            'end_date' => wc_rest_prepare_date_response($subscription->get_date('end')),
            'billing_period' => $subscription->get_billing_period(),
            'billing_interval' => $subscription->get_billing_interval(),
        );
        
        // Add line items
        $data['line_items'] = array();
        
        foreach ($subscription->get_items() as $item_id => $item) {
            $product = $item->get_product();
            $product_id = $item->get_product_id();
            $variation_id = $item->get_variation_id();
            
            $item_data = array(
                'id' => $item_id,
                'name' => $item->get_name(),
                'product_id' => $product_id,
                'variation_id' => $variation_id,
                'quantity' => $item->get_quantity(),
                'tax_class' => $item->get_tax_class(),
                'subtotal' => $item->get_subtotal(),
                'subtotal_tax' => $item->get_subtotal_tax(),
                'total' => $item->get_total(),
                'total_tax' => $item->get_total_tax(),
                'taxes' => $item->get_taxes(),
                'meta_data' => array(),
            );
            
            // Add product data
            if ($product) {
                $item_data['sku'] = $product->get_sku();
                $item_data['price'] = $product->get_price();
                $item_data['image'] = array(
                    'id' => $product->get_image_id(),
                    'src' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                );
            }
            
            // Add meta data
            foreach ($item->get_meta_data() as $meta) {
                $item_data['meta_data'][] = array(
                    'key' => $meta->key,
                    'value' => $meta->value,
                );
            }
            
            $data['line_items'][] = $item_data;
        }
        
        // Add shipping lines
        $data['shipping_lines'] = array();
        
        foreach ($subscription->get_shipping_methods() as $item_id => $item) {
            $shipping_data = array(
                'id' => $item_id,
                'method_title' => $item->get_method_title(),
                'method_id' => $item->get_method_id(),
                'instance_id' => $item->get_instance_id(),
                'total' => $item->get_total(),
                'total_tax' => $item->get_total_tax(),
                'taxes' => $item->get_taxes(),
                'meta_data' => array(),
            );
            
            // Add meta data
            foreach ($item->get_meta_data() as $meta) {
                $shipping_data['meta_data'][] = array(
                    'key' => $meta->key,
                    'value' => $meta->value,
                );
            }
            
            $data['shipping_lines'][] = $shipping_data;
        }
        
        // Add tax lines
        $data['tax_lines'] = array();
        
        foreach ($subscription->get_tax_totals() as $tax_code => $tax) {
            $tax_data = array(
                'id' => $tax->id,
                'rate_code' => $tax_code,
                'rate_id' => $tax->rate_id,
                'label' => $tax->label,
                'compound' => (bool) $tax->is_compound,
                'tax_total' => $tax->amount,
                'shipping_tax_total' => $tax->shipping_tax_amount,
            );
            
            $data['tax_lines'][] = $tax_data;
        }
        
        // Add fee lines
        $data['fee_lines'] = array();
        
        foreach ($subscription->get_fees() as $item_id => $item) {
            $fee_data = array(
                'id' => $item_id,
                'name' => $item->get_name(),
                'tax_class' => $item->get_tax_class(),
                'tax_status' => $item->get_tax_status(),
                'amount' => $item->get_amount(),
                'total' => $item->get_total(),
                'total_tax' => $item->get_total_tax(),
                'taxes' => $item->get_taxes(),
                'meta_data' => array(),
            );
            
            // Add meta data
            foreach ($item->get_meta_data() as $meta) {
                $fee_data['meta_data'][] = array(
                    'key' => $meta->key,
                    'value' => $meta->value,
                );
            }
            
            $data['fee_lines'][] = $fee_data;
        }
        
        // Add coupon lines
        $data['coupon_lines'] = array();
        
        foreach ($subscription->get_coupon_codes() as $coupon_code) {
            $coupon_data = array(
                'code' => $coupon_code,
                'discount' => wc_get_order_coupon_discount_amount($subscription, $coupon_code),
                'discount_tax' => wc_get_order_coupon_discount_tax_amount($subscription, $coupon_code),
            );
            
            $data['coupon_lines'][] = $coupon_data;
        }
        
        // Add related orders
        if ($single) {
            $data['orders'] = array();
            
            foreach ($subscription->get_related_orders() as $order_id) {
                $order = wc_get_order($order_id);
                
                if ($order) {
                    $data['orders'][] = array(
                        'id' => $order->get_id(),
                        'number' => $order->get_order_number(),
                        'status' => $order->get_status(),
                        'status_name' => wc_get_order_status_name($order->get_status()),
                        'date_created' => wc_rest_prepare_date_response($order->get_date_created()),
                        'total' => $order->get_total(),
                        'currency' => $order->get_currency(),
                    );
                }
            }
            
            // Add notes
            $args = array(
                'post_id' => $subscription->get_id(),
                'approve' => 'approve',
                'type' => 'order_note',
            );
            
            // Non-admin users can only see customer notes
            if (!$this->is_current_user_admin()) {
                $args['meta_query'] = array(
                    array(
                        'key' => 'is_customer_note',
                        'value' => 1,
                        'compare' => '=',
                    ),
                );
            }
            
            $notes = wc_get_order_notes($args);
            $data['notes'] = array();
            
            foreach ($notes as $note) {
                $data['notes'][] = array(
                    'id' => $note->id,
                    'author' => $note->added_by,
                    'date' => $note->date_created->date('c'),
                    'content' => $note->content,
                    'is_customer_note' => (bool) $note->customer_note,
                );
            }
        }
        
        return $data;
    }
}