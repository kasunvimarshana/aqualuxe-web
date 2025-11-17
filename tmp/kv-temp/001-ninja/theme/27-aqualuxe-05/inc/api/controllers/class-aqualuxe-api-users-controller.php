<?php
/**
 * AquaLuxe API Users Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Users_Controller Class
 *
 * Handles API requests for users
 */
class AquaLuxe_API_Users_Controller extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'users';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Get current user
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_current_user'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
            ),
        ));

        // Update current user
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_current_user'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'first_name' => array(
                        'description' => __('User first name.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'last_name' => array(
                        'description' => __('User last name.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'display_name' => array(
                        'description' => __('User display name.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'email' => array(
                        'description' => __('User email address.'),
                        'type' => 'string',
                        'format' => 'email',
                        'sanitize_callback' => 'sanitize_email',
                    ),
                    'password' => array(
                        'description' => __('User password.'),
                        'type' => 'string',
                        'minLength' => 8,
                    ),
                    'billing' => array(
                        'description' => __('User billing address.'),
                        'type' => 'object',
                    ),
                    'shipping' => array(
                        'description' => __('User shipping address.'),
                        'type' => 'object',
                    ),
                ),
            ),
        ));

        // Change password
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/password', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'change_password'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'current_password' => array(
                        'description' => __('Current password.'),
                        'type' => 'string',
                        'required' => true,
                    ),
                    'new_password' => array(
                        'description' => __('New password.'),
                        'type' => 'string',
                        'required' => true,
                        'minLength' => 8,
                    ),
                ),
            ),
        ));

        // Get user addresses
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/addresses', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_addresses'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
            ),
        ));

        // Update user addresses
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/addresses', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_addresses'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'billing' => array(
                        'description' => __('User billing address.'),
                        'type' => 'object',
                    ),
                    'shipping' => array(
                        'description' => __('User shipping address.'),
                        'type' => 'object',
                    ),
                ),
            ),
        ));

        // Get user orders
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/orders', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_orders'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get user downloads
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/downloads', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_downloads'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
            ),
        ));

        // Get user payment methods
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/payment-methods', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_payment_methods'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
            ),
        ));

        // Add user payment method
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/payment-methods', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'add_payment_method'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'payment_method' => array(
                        'description' => __('Payment method ID.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'payment_token' => array(
                        'description' => __('Payment token.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'is_default' => array(
                        'description' => __('Set as default payment method.'),
                        'type' => 'boolean',
                        'default' => false,
                    ),
                ),
            ),
        ));

        // Delete user payment method
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/payment-methods/(?P<token_id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_payment_method'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'token_id' => array(
                        'description' => __('Payment token ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Set default payment method
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/payment-methods/(?P<token_id>[\d]+)/default', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'set_default_payment_method'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'token_id' => array(
                        'description' => __('Payment token ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get user wishlist
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/wishlist', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_wishlist'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
            ),
        ));

        // Add product to wishlist
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/wishlist', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'add_to_wishlist'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'product_id' => array(
                        'description' => __('Product ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Remove product from wishlist
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/wishlist/(?P<product_id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'remove_from_wishlist'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'product_id' => array(
                        'description' => __('Product ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get user recently viewed products
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/recently-viewed', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_recently_viewed'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
            ),
        ));

        // Add product to recently viewed
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/recently-viewed', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'add_to_recently_viewed'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'product_id' => array(
                        'description' => __('Product ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Clear recently viewed products
        register_rest_route($this->namespace, '/' . $this->rest_base . '/me/recently-viewed/clear', array(
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'clear_recently_viewed'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
            ),
        ));
    }

    /**
     * Get current user.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_current_user($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $user = get_user_by('id', $user_id);
        
        if (!$user) {
            return $this->format_error('user_not_found', __('User not found.'), 404);
        }
        
        $data = $this->prepare_user_data($user);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Update current user.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_current_user($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $user = get_user_by('id', $user_id);
        
        if (!$user) {
            return $this->format_error('user_not_found', __('User not found.'), 404);
        }
        
        $first_name = $request->get_param('first_name');
        $last_name = $request->get_param('last_name');
        $display_name = $request->get_param('display_name');
        $email = $request->get_param('email');
        $password = $request->get_param('password');
        $billing = $request->get_param('billing');
        $shipping = $request->get_param('shipping');
        
        $userdata = array(
            'ID' => $user_id,
        );
        
        // Update first name
        if (!is_null($first_name)) {
            $userdata['first_name'] = $first_name;
        }
        
        // Update last name
        if (!is_null($last_name)) {
            $userdata['last_name'] = $last_name;
        }
        
        // Update display name
        if (!is_null($display_name)) {
            $userdata['display_name'] = $display_name;
        }
        
        // Update email
        if (!is_null($email)) {
            // Check if email is already in use
            if (email_exists($email) && email_exists($email) !== $user_id) {
                return $this->format_error('email_exists', __('Email address is already in use.'), 400);
            }
            
            $userdata['user_email'] = $email;
        }
        
        // Update password
        if (!is_null($password)) {
            $userdata['user_pass'] = $password;
        }
        
        // Update user
        $user_id = wp_update_user($userdata);
        
        if (is_wp_error($user_id)) {
            return $this->format_error('update_failed', $user_id->get_error_message(), 500);
        }
        
        // Update billing address
        if (!is_null($billing)) {
            foreach ($billing as $key => $value) {
                update_user_meta($user_id, 'billing_' . $key, $value);
            }
        }
        
        // Update shipping address
        if (!is_null($shipping)) {
            foreach ($shipping as $key => $value) {
                update_user_meta($user_id, 'shipping_' . $key, $value);
            }
        }
        
        // Get updated user
        $user = get_user_by('id', $user_id);
        $data = $this->prepare_user_data($user);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Change user password.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function change_password($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $user = get_user_by('id', $user_id);
        
        if (!$user) {
            return $this->format_error('user_not_found', __('User not found.'), 404);
        }
        
        $current_password = $request->get_param('current_password');
        $new_password = $request->get_param('new_password');
        
        // Check current password
        if (!wp_check_password($current_password, $user->user_pass, $user_id)) {
            return $this->format_error('invalid_password', __('Current password is incorrect.'), 400);
        }
        
        // Update password
        wp_set_password($new_password, $user_id);
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'message' => __('Password updated successfully.'),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get user addresses.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_addresses($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $user = get_user_by('id', $user_id);
        
        if (!$user) {
            return $this->format_error('user_not_found', __('User not found.'), 404);
        }
        
        // Get billing address
        $billing = array(
            'first_name' => get_user_meta($user_id, 'billing_first_name', true),
            'last_name' => get_user_meta($user_id, 'billing_last_name', true),
            'company' => get_user_meta($user_id, 'billing_company', true),
            'address_1' => get_user_meta($user_id, 'billing_address_1', true),
            'address_2' => get_user_meta($user_id, 'billing_address_2', true),
            'city' => get_user_meta($user_id, 'billing_city', true),
            'state' => get_user_meta($user_id, 'billing_state', true),
            'postcode' => get_user_meta($user_id, 'billing_postcode', true),
            'country' => get_user_meta($user_id, 'billing_country', true),
            'email' => get_user_meta($user_id, 'billing_email', true),
            'phone' => get_user_meta($user_id, 'billing_phone', true),
        );
        
        // Get shipping address
        $shipping = array(
            'first_name' => get_user_meta($user_id, 'shipping_first_name', true),
            'last_name' => get_user_meta($user_id, 'shipping_last_name', true),
            'company' => get_user_meta($user_id, 'shipping_company', true),
            'address_1' => get_user_meta($user_id, 'shipping_address_1', true),
            'address_2' => get_user_meta($user_id, 'shipping_address_2', true),
            'city' => get_user_meta($user_id, 'shipping_city', true),
            'state' => get_user_meta($user_id, 'shipping_state', true),
            'postcode' => get_user_meta($user_id, 'shipping_postcode', true),
            'country' => get_user_meta($user_id, 'shipping_country', true),
        );
        
        // Prepare response
        $response = $this->format_response(array(
            'billing' => $billing,
            'shipping' => $shipping,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Update user addresses.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_addresses($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $user = get_user_by('id', $user_id);
        
        if (!$user) {
            return $this->format_error('user_not_found', __('User not found.'), 404);
        }
        
        $billing = $request->get_param('billing');
        $shipping = $request->get_param('shipping');
        
        // Update billing address
        if (!is_null($billing)) {
            foreach ($billing as $key => $value) {
                update_user_meta($user_id, 'billing_' . $key, $value);
            }
        }
        
        // Update shipping address
        if (!is_null($shipping)) {
            foreach ($shipping as $key => $value) {
                update_user_meta($user_id, 'shipping_' . $key, $value);
            }
        }
        
        // Get updated addresses
        $updated_billing = array(
            'first_name' => get_user_meta($user_id, 'billing_first_name', true),
            'last_name' => get_user_meta($user_id, 'billing_last_name', true),
            'company' => get_user_meta($user_id, 'billing_company', true),
            'address_1' => get_user_meta($user_id, 'billing_address_1', true),
            'address_2' => get_user_meta($user_id, 'billing_address_2', true),
            'city' => get_user_meta($user_id, 'billing_city', true),
            'state' => get_user_meta($user_id, 'billing_state', true),
            'postcode' => get_user_meta($user_id, 'billing_postcode', true),
            'country' => get_user_meta($user_id, 'billing_country', true),
            'email' => get_user_meta($user_id, 'billing_email', true),
            'phone' => get_user_meta($user_id, 'billing_phone', true),
        );
        
        $updated_shipping = array(
            'first_name' => get_user_meta($user_id, 'shipping_first_name', true),
            'last_name' => get_user_meta($user_id, 'shipping_last_name', true),
            'company' => get_user_meta($user_id, 'shipping_company', true),
            'address_1' => get_user_meta($user_id, 'shipping_address_1', true),
            'address_2' => get_user_meta($user_id, 'shipping_address_2', true),
            'city' => get_user_meta($user_id, 'shipping_city', true),
            'state' => get_user_meta($user_id, 'shipping_state', true),
            'postcode' => get_user_meta($user_id, 'shipping_postcode', true),
            'country' => get_user_meta($user_id, 'shipping_country', true),
        );
        
        // Prepare response
        $response = $this->format_response(array(
            'billing' => $updated_billing,
            'shipping' => $updated_shipping,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get user orders.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_orders($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'id', 'total'));
        
        // Get filter params
        $status = $request->get_param('status');
        
        // Get date range
        $date_range = $this->get_date_range_params($request);
        
        // Query args
        $args = array(
            'customer_id' => $user_id,
            'limit' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'paginate' => true,
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
        
        // Get orders
        $query = wc_get_orders($args);
        $orders = $query->orders;
        $total = $query->total;
        $max_pages = $query->max_num_pages;
        
        $data = array();
        
        foreach ($orders as $order) {
            $data[] = array(
                'id' => $order->get_id(),
                'number' => $order->get_order_number(),
                'status' => $order->get_status(),
                'status_name' => wc_get_order_status_name($order->get_status()),
                'date_created' => wc_rest_prepare_date_response($order->get_date_created()),
                'total' => $order->get_total(),
                'currency' => $order->get_currency(),
                'item_count' => $order->get_item_count(),
                'payment_method' => $order->get_payment_method(),
                'payment_method_title' => $order->get_payment_method_title(),
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'orders' => $data,
            'total' => $total,
            'pages' => $max_pages,
        ));
        
        // Add pagination headers
        $response = $this->add_pagination_headers($response, $total, $pagination['per_page']);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get user downloads.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_downloads($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Get downloads
        $downloads = wc_get_customer_available_downloads($user_id);
        $data = array();
        
        foreach ($downloads as $download) {
            $data[] = array(
                'download_id' => $download['download_id'],
                'download_url' => $download['download_url'],
                'product_id' => $download['product_id'],
                'product_name' => $download['product_name'],
                'download_name' => $download['download_name'],
                'order_id' => $download['order_id'],
                'order_key' => $download['order_key'],
                'downloads_remaining' => $download['downloads_remaining'],
                'access_expires' => $download['access_expires'],
                'file' => array(
                    'name' => $download['file']['name'],
                    'file' => $download['file']['file'],
                ),
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'downloads' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get user payment methods.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_payment_methods($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Check if WooCommerce Payment Tokens is available
        if (!function_exists('WC_Payment_Tokens')) {
            return $this->format_error('payment_tokens_not_available', __('Payment tokens functionality is not available.'), 501);
        }
        
        // Get payment tokens
        $tokens = WC_Payment_Tokens::get_customer_tokens($user_id);
        $data = array();
        
        foreach ($tokens as $token) {
            $data[] = array(
                'id' => $token->get_id(),
                'type' => $token->get_type(),
                'last4' => $token->get_last4(),
                'expiry_month' => $token->get_expiry_month(),
                'expiry_year' => $token->get_expiry_year(),
                'card_type' => $token->get_card_type(),
                'default' => $token->is_default(),
                'gateway_id' => $token->get_gateway_id(),
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'payment_methods' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Add user payment method.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function add_payment_method($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Check if WooCommerce Payment Tokens is available
        if (!function_exists('WC_Payment_Tokens')) {
            return $this->format_error('payment_tokens_not_available', __('Payment tokens functionality is not available.'), 501);
        }
        
        $payment_method = $request->get_param('payment_method');
        $payment_token = $request->get_param('payment_token');
        $is_default = $request->get_param('is_default');
        
        // Create token
        $token = new WC_Payment_Token_CC();
        $token->set_token($payment_token);
        $token->set_gateway_id($payment_method);
        $token->set_user_id($user_id);
        
        // Set as default if requested
        if ($is_default) {
            $token->set_default(true);
        }
        
        // Save token
        $token->save();
        
        if (!$token->get_id()) {
            return $this->format_error('token_creation_failed', __('Failed to create payment token.'), 500);
        }
        
        // Get token data
        $data = array(
            'id' => $token->get_id(),
            'type' => $token->get_type(),
            'last4' => $token->get_last4(),
            'expiry_month' => $token->get_expiry_month(),
            'expiry_year' => $token->get_expiry_year(),
            'card_type' => $token->get_card_type(),
            'default' => $token->is_default(),
            'gateway_id' => $token->get_gateway_id(),
        );
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Delete user payment method.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_payment_method($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $token_id = $request->get_param('token_id');
        
        // Check if WooCommerce Payment Tokens is available
        if (!function_exists('WC_Payment_Tokens')) {
            return $this->format_error('payment_tokens_not_available', __('Payment tokens functionality is not available.'), 501);
        }
        
        // Get token
        $token = WC_Payment_Tokens::get($token_id);
        
        if (!$token) {
            return $this->format_error('token_not_found', __('Payment token not found.'), 404);
        }
        
        // Check if token belongs to user
        if ($token->get_user_id() !== $user_id) {
            return $this->format_error('permission_denied', __('You do not have permission to delete this payment method.'), 403);
        }
        
        // Delete token
        $deleted = WC_Payment_Tokens::delete($token_id);
        
        if (!$deleted) {
            return $this->format_error('delete_failed', __('Failed to delete payment method.'), 500);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'message' => __('Payment method deleted successfully.'),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Set default payment method.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function set_default_payment_method($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $token_id = $request->get_param('token_id');
        
        // Check if WooCommerce Payment Tokens is available
        if (!function_exists('WC_Payment_Tokens')) {
            return $this->format_error('payment_tokens_not_available', __('Payment tokens functionality is not available.'), 501);
        }
        
        // Get token
        $token = WC_Payment_Tokens::get($token_id);
        
        if (!$token) {
            return $this->format_error('token_not_found', __('Payment token not found.'), 404);
        }
        
        // Check if token belongs to user
        if ($token->get_user_id() !== $user_id) {
            return $this->format_error('permission_denied', __('You do not have permission to update this payment method.'), 403);
        }
        
        // Set as default
        WC_Payment_Tokens::set_users_default($user_id, $token_id);
        
        // Get updated tokens
        $tokens = WC_Payment_Tokens::get_customer_tokens($user_id);
        $data = array();
        
        foreach ($tokens as $token) {
            $data[] = array(
                'id' => $token->get_id(),
                'type' => $token->get_type(),
                'last4' => $token->get_last4(),
                'expiry_month' => $token->get_expiry_month(),
                'expiry_year' => $token->get_expiry_year(),
                'card_type' => $token->get_card_type(),
                'default' => $token->is_default(),
                'gateway_id' => $token->get_gateway_id(),
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'message' => __('Default payment method updated successfully.'),
            'payment_methods' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get user wishlist.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_wishlist($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Get wishlist
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!$wishlist) {
            $wishlist = array();
        }
        
        $data = array();
        
        foreach ($wishlist as $product_id) {
            $product = wc_get_product($product_id);
            
            if ($product && $product->is_visible()) {
                $data[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'price' => $product->get_price(),
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_sale_price(),
                    'on_sale' => $product->is_on_sale(),
                    'permalink' => get_permalink($product->get_id()),
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                    'average_rating' => $product->get_average_rating(),
                    'review_count' => $product->get_review_count(),
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'wishlist' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Add product to wishlist.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function add_to_wishlist($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $product_id = $request->get_param('product_id');
        
        // Check if product exists
        $product = wc_get_product($product_id);
        
        if (!$product) {
            return $this->format_error('product_not_found', __('Product not found.'), 404);
        }
        
        // Get wishlist
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!$wishlist) {
            $wishlist = array();
        }
        
        // Check if product is already in wishlist
        if (in_array($product_id, $wishlist)) {
            return $this->format_error('product_in_wishlist', __('Product is already in wishlist.'), 400);
        }
        
        // Add product to wishlist
        $wishlist[] = $product_id;
        
        // Save wishlist
        update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
        
        // Get updated wishlist
        $data = array();
        
        foreach ($wishlist as $id) {
            $product = wc_get_product($id);
            
            if ($product && $product->is_visible()) {
                $data[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'price' => $product->get_price(),
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_sale_price(),
                    'on_sale' => $product->is_on_sale(),
                    'permalink' => get_permalink($product->get_id()),
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                    'average_rating' => $product->get_average_rating(),
                    'review_count' => $product->get_review_count(),
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'message' => __('Product added to wishlist.'),
            'wishlist' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Remove product from wishlist.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function remove_from_wishlist($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $product_id = $request->get_param('product_id');
        
        // Get wishlist
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!$wishlist) {
            $wishlist = array();
        }
        
        // Check if product is in wishlist
        if (!in_array($product_id, $wishlist)) {
            return $this->format_error('product_not_in_wishlist', __('Product is not in wishlist.'), 400);
        }
        
        // Remove product from wishlist
        $wishlist = array_diff($wishlist, array($product_id));
        
        // Save wishlist
        update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
        
        // Get updated wishlist
        $data = array();
        
        foreach ($wishlist as $id) {
            $product = wc_get_product($id);
            
            if ($product && $product->is_visible()) {
                $data[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'price' => $product->get_price(),
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_sale_price(),
                    'on_sale' => $product->is_on_sale(),
                    'permalink' => get_permalink($product->get_id()),
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                    'average_rating' => $product->get_average_rating(),
                    'review_count' => $product->get_review_count(),
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'message' => __('Product removed from wishlist.'),
            'wishlist' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get recently viewed products.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_recently_viewed($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Get recently viewed
        $recently_viewed = get_user_meta($user_id, 'aqualuxe_recently_viewed', true);
        
        if (!$recently_viewed) {
            $recently_viewed = array();
        }
        
        $data = array();
        
        foreach ($recently_viewed as $product_id) {
            $product = wc_get_product($product_id);
            
            if ($product && $product->is_visible()) {
                $data[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'price' => $product->get_price(),
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_sale_price(),
                    'on_sale' => $product->is_on_sale(),
                    'permalink' => get_permalink($product->get_id()),
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                    'average_rating' => $product->get_average_rating(),
                    'review_count' => $product->get_review_count(),
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'recently_viewed' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Add product to recently viewed.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function add_to_recently_viewed($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $product_id = $request->get_param('product_id');
        
        // Check if product exists
        $product = wc_get_product($product_id);
        
        if (!$product) {
            return $this->format_error('product_not_found', __('Product not found.'), 404);
        }
        
        // Get recently viewed
        $recently_viewed = get_user_meta($user_id, 'aqualuxe_recently_viewed', true);
        
        if (!$recently_viewed) {
            $recently_viewed = array();
        }
        
        // Remove product if already in list
        $recently_viewed = array_diff($recently_viewed, array($product_id));
        
        // Add product to beginning of list
        array_unshift($recently_viewed, $product_id);
        
        // Limit to 20 products
        $recently_viewed = array_slice($recently_viewed, 0, 20);
        
        // Save recently viewed
        update_user_meta($user_id, 'aqualuxe_recently_viewed', $recently_viewed);
        
        // Get updated recently viewed
        $data = array();
        
        foreach ($recently_viewed as $id) {
            $product = wc_get_product($id);
            
            if ($product && $product->is_visible()) {
                $data[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'price' => $product->get_price(),
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_sale_price(),
                    'on_sale' => $product->is_on_sale(),
                    'permalink' => get_permalink($product->get_id()),
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                    'average_rating' => $product->get_average_rating(),
                    'review_count' => $product->get_review_count(),
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'message' => __('Product added to recently viewed.'),
            'recently_viewed' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Clear recently viewed products.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function clear_recently_viewed($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Clear recently viewed
        delete_user_meta($user_id, 'aqualuxe_recently_viewed');
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'message' => __('Recently viewed products cleared.'),
            'recently_viewed' => array(),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Prepare user data for API response.
     *
     * @param WP_User $user User object.
     * @return array
     */
    protected function prepare_user_data($user) {
        $data = array(
            'id' => $user->ID,
            'username' => $user->user_login,
            'email' => $user->user_email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'display_name' => $user->display_name,
            'avatar' => get_avatar_url($user->ID),
            'roles' => $user->roles,
            'billing' => array(
                'first_name' => get_user_meta($user->ID, 'billing_first_name', true),
                'last_name' => get_user_meta($user->ID, 'billing_last_name', true),
                'company' => get_user_meta($user->ID, 'billing_company', true),
                'address_1' => get_user_meta($user->ID, 'billing_address_1', true),
                'address_2' => get_user_meta($user->ID, 'billing_address_2', true),
                'city' => get_user_meta($user->ID, 'billing_city', true),
                'state' => get_user_meta($user->ID, 'billing_state', true),
                'postcode' => get_user_meta($user->ID, 'billing_postcode', true),
                'country' => get_user_meta($user->ID, 'billing_country', true),
                'email' => get_user_meta($user->ID, 'billing_email', true),
                'phone' => get_user_meta($user->ID, 'billing_phone', true),
            ),
            'shipping' => array(
                'first_name' => get_user_meta($user->ID, 'shipping_first_name', true),
                'last_name' => get_user_meta($user->ID, 'shipping_last_name', true),
                'company' => get_user_meta($user->ID, 'shipping_company', true),
                'address_1' => get_user_meta($user->ID, 'shipping_address_1', true),
                'address_2' => get_user_meta($user->ID, 'shipping_address_2', true),
                'city' => get_user_meta($user->ID, 'shipping_city', true),
                'state' => get_user_meta($user->ID, 'shipping_state', true),
                'postcode' => get_user_meta($user->ID, 'shipping_postcode', true),
                'country' => get_user_meta($user->ID, 'shipping_country', true),
            ),
        );
        
        return $data;
    }
}