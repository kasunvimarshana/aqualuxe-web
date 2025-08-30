<?php
/**
 * AquaLuxe API Orders Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Orders_Controller Class
 *
 * Handles API requests for orders
 */
class AquaLuxe_API_Orders_Controller extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'orders';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Check if WooCommerce is active before registering routes
        if (!function_exists('aqualuxe_is_woocommerce_active') || !aqualuxe_is_woocommerce_active()) {
            return;
        }
        
        // Get all orders
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get single order
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_item'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the order.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Create order
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_item'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => array(
                    'payment_method' => array(
                        'description' => __('Payment method ID.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'payment_method_title' => array(
                        'description' => __('Payment method title.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'set_paid' => array(
                        'description' => __('Set the order as paid.'),
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'billing' => array(
                        'description' => __('Billing address.'),
                        'type' => 'object',
                    ),
                    'shipping' => array(
                        'description' => __('Shipping address.'),
                        'type' => 'object',
                    ),
                    'line_items' => array(
                        'description' => __('Line items data.'),
                        'type' => 'array',
                        'items' => array(
                            'type' => 'object',
                        ),
                    ),
                    'shipping_lines' => array(
                        'description' => __('Shipping lines data.'),
                        'type' => 'array',
                        'items' => array(
                            'type' => 'object',
                        ),
                    ),
                    'fee_lines' => array(
                        'description' => __('Fee lines data.'),
                        'type' => 'array',
                        'items' => array(
                            'type' => 'object',
                        ),
                    ),
                    'coupon_lines' => array(
                        'description' => __('Coupon lines data.'),
                        'type' => 'array',
                        'items' => array(
                            'type' => 'object',
                        ),
                    ),
                    'customer_id' => array(
                        'description' => __('Customer ID.'),
                        'type' => 'integer',
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'customer_note' => array(
                        'description' => __('Note added by customer during checkout.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_textarea_field',
                    ),
                    'status' => array(
                        'description' => __('Order status.'),
                        'type' => 'string',
                        'enum' => array_keys(function_exists('aqualuxe_get_order_statuses') ? aqualuxe_get_order_statuses() : array()),
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'customer_note' => array(
                        'description' => __('Note added by customer during checkout.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_textarea_field',
                    ),
                ),
            ),
        ));

        // Cancel order
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/cancel', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'cancel_order'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the order.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Update order
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_item'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the order.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'status' => array(
                        'description' => __('Order status.'),
                        'type' => 'string',
                        'enum' => array_keys(function_exists('aqualuxe_get_order_statuses') ? aqualuxe_get_order_statuses() : array()),
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'customer_note' => array(
                        'description' => __('Note added by customer during checkout.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_textarea_field',
                    ),
                ),
            ),
        ));

        // Delete order
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_item'),
                'permission_callback' => array($this, 'delete_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the order.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'force' => array(
                        'description' => __('Whether to bypass trash and force deletion.'),
                        'type' => 'boolean',
                        'default' => false,
                    ),
                ),
            ),
        ));

        // Get order notes
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/notes', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_order_notes'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the order.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Create order note
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/notes', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_order_note'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the order.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'note' => array(
                        'description' => __('Order note content.'),
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

        // Delete order note
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/notes/(?P<note_id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_order_note'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the order.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'note_id' => array(
                        'description' => __('Unique identifier for the note.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get order shipment tracking
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/shipment-tracking', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_order_shipment_tracking'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the order.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get order refunds
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/refunds', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_order_refunds'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the order.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));
    }

    /**
     * Check if a given request has access to read items.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_items_permissions_check($request) {
        if (!current_user_can('manage_woocommerce')) {
            return new WP_Error('rest_forbidden', __('You do not have permission to view orders.'), array('status' => rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * Check if a given request has access to read an item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function get_item_permissions_check($request) {
        if (!current_user_can('manage_woocommerce')) {
            return new WP_Error('rest_forbidden', __('You do not have permission to view this order.'), array('status' => rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * Check if a given request has access to create an item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function create_item_permissions_check($request) {
        if (!current_user_can('manage_woocommerce')) {
            return new WP_Error('rest_forbidden', __('You do not have permission to create orders.'), array('status' => rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * Check if a given request has access to update an item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function update_item_permissions_check($request) {
        if (!current_user_can('manage_woocommerce')) {
            return new WP_Error('rest_forbidden', __('You do not have permission to edit orders.'), array('status' => rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * Check if a given request has access to delete an item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|boolean
     */
    public function delete_item_permissions_check($request) {
        if (!current_user_can('manage_woocommerce')) {
            return new WP_Error('rest_forbidden', __('You do not have permission to delete orders.'), array('status' => rest_authorization_required_code()));
        }
        return true;
    }

    /**
     * Get a collection of orders.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request) {
        $args = array(
            'limit' => $request['per_page'],
            'page' => $request['page'],
            'order' => $request['order'],
            'orderby' => $request['orderby'],
        );

        if (!empty($request['status'])) {
            $args['status'] = $request['status'];
        }

        if (!empty($request['customer_id'])) {
            $args['customer_id'] = $request['customer_id'];
        }

        if (!empty($request['search'])) {
            $args['search'] = $request['search'];
        }

        if (!empty($request['after'])) {
            $args['date_created'] = '>' . $request['after'];
        }

        if (!empty($request['before'])) {
            if (!isset($args['date_created'])) {
                $args['date_created'] = '<' . $request['before'];
            } else {
                $args['date_created'] .= ';<' . $request['before'];
            }
        }

        $query = function_exists('aqualuxe_get_orders') ? aqualuxe_get_orders($args) : array();
        
        if (!$query) {
            return new WP_Error('no_orders', __('No orders found.'), array('status' => 404));
        }

        $orders = array();
        foreach ($query->orders as $order) {
            $data = $this->prepare_item_for_response($order, $request);
            $orders[] = $this->prepare_response_for_collection($data);
        }

        $response = rest_ensure_response($orders);

        // Store pagination values for headers
        $total_orders = $query->total;
        $max_pages = ceil($total_orders / $args['limit']);

        // Add pagination headers
        $response->header('X-WP-Total', $total_orders);
        $response->header('X-WP-TotalPages', $max_pages);

        return $response;
    }

    /**
     * Get a single order.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_item($request) {
        $order_id = $request['id'];
        $order = function_exists('aqualuxe_get_order') ? aqualuxe_get_order($order_id) : false;

        if (!$order || $order->get_id() !== $order_id) {
            return new WP_Error('rest_order_invalid_id', __('Invalid order ID.'), array('status' => 404));
        }

        $data = $this->prepare_item_for_response($order, $request);
        $response = rest_ensure_response($data);

        return $response;
    }

    /**
     * Create a single order.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item($request) {
        if (!function_exists('aqualuxe_create_order')) {
            return new WP_Error('woocommerce_not_active', __('WooCommerce is not active.'), array('status' => 500));
        }
        
        $order = aqualuxe_create_order(array(
            'status' => $request['status'] ? $request['status'] : 'pending',
            'customer_id' => $request['customer_id'] ? $request['customer_id'] : 0,
            'customer_note' => $request['customer_note'] ? $request['customer_note'] : '',
            'created_via' => 'api',
        ));

        if (is_wp_error($order)) {
            return $order;
        }

        // Set addresses
        if (!empty($request['billing'])) {
            $order->set_address($request['billing'], 'billing');
        }

        if (!empty($request['shipping'])) {
            $order->set_address($request['shipping'], 'shipping');
        }

        // Add line items
        if (!empty($request['line_items'])) {
            foreach ($request['line_items'] as $item) {
                $product_id = isset($item['product_id']) ? $item['product_id'] : 0;
                $variation_id = isset($item['variation_id']) ? $item['variation_id'] : 0;
                $quantity = isset($item['quantity']) ? $item['quantity'] : 1;

                $product = function_exists('aqualuxe_get_product') ? aqualuxe_get_product($variation_id ? $variation_id : $product_id) : false;
                
                if (!$product) {
                    continue;
                }

                $item_id = $order->add_product($product, $quantity);

                // Add item meta
                if (!empty($item['meta_data'])) {
                    foreach ($item['meta_data'] as $meta) {
                        if (isset($meta['key'], $meta['value'])) {
                            wc_add_order_item_meta($item_id, $meta['key'], $meta['value']);
                        }
                    }
                }
            }
        }

        // Add shipping lines
        if (!empty($request['shipping_lines'])) {
            foreach ($request['shipping_lines'] as $shipping) {
                $method_id = isset($shipping['method_id']) ? $shipping['method_id'] : '';
                $method_title = isset($shipping['method_title']) ? $shipping['method_title'] : '';
                $method_total = isset($shipping['total']) ? $shipping['total'] : 0;

                $item = new WC_Order_Item_Shipping();
                $item->set_method_title($method_title);
                $item->set_method_id($method_id);
                $item->set_total($method_total);

                // Add shipping meta
                if (!empty($shipping['meta_data'])) {
                    foreach ($shipping['meta_data'] as $meta) {
                        if (isset($meta['key'], $meta['value'])) {
                            $item->add_meta_data($meta['key'], $meta['value'], true);
                        }
                    }
                }

                $order->add_item($item);
            }
        }

        // Add fee lines
        if (!empty($request['fee_lines'])) {
            foreach ($request['fee_lines'] as $fee) {
                $fee_name = isset($fee['name']) ? $fee['name'] : '';
                $fee_total = isset($fee['total']) ? $fee['total'] : 0;

                $item = new WC_Order_Item_Fee();
                $item->set_name($fee_name);
                $item->set_total($fee_total);

                // Add fee meta
                if (!empty($fee['meta_data'])) {
                    foreach ($fee['meta_data'] as $meta) {
                        if (isset($meta['key'], $meta['value'])) {
                            $item->add_meta_data($meta['key'], $meta['value'], true);
                        }
                    }
                }

                $order->add_item($item);
            }
        }

        // Add coupon lines
        if (!empty($request['coupon_lines'])) {
            foreach ($request['coupon_lines'] as $coupon) {
                $coupon_code = isset($coupon['code']) ? $coupon['code'] : '';
                $coupon_discount = isset($coupon['discount']) ? $coupon['discount'] : 0;

                $item = new WC_Order_Item_Coupon();
                $item->set_code($coupon_code);
                $item->set_discount($coupon_discount);

                // Add coupon meta
                if (!empty($coupon['meta_data'])) {
                    foreach ($coupon['meta_data'] as $meta) {
                        if (isset($meta['key'], $meta['value'])) {
                            $item->add_meta_data($meta['key'], $meta['value'], true);
                        }
                    }
                }

                $order->add_item($item);
            }
        }

        // Set payment details
        if (!empty($request['payment_method'])) {
            $order->set_payment_method($request['payment_method']);
        }

        if (!empty($request['payment_method_title'])) {
            $order->set_payment_method_title($request['payment_method_title']);
        }

        // Calculate totals
        $order->calculate_totals();

        // Set order as paid if requested
        if (!empty($request['set_paid']) && $request['set_paid']) {
            $order->payment_complete();
        }

        // Save the order
        $order->save();

        $data = $this->prepare_item_for_response($order, $request);
        $response = rest_ensure_response($data);
        $response->set_status(201);
        $response->header('Location', rest_url(sprintf('%s/%s/%d', $this->namespace, $this->rest_base, $order->get_id())));

        return $response;
    }

    /**
     * Update a single order.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item($request) {
        $order_id = $request['id'];
        $order = function_exists('aqualuxe_get_order') ? aqualuxe_get_order($order_id) : false;

        if (!$order || $order->get_id() !== $order_id) {
            return new WP_Error('rest_order_invalid_id', __('Invalid order ID.'), array('status' => 404));
        }

        // Update status
        if (!empty($request['status'])) {
            $order->update_status($request['status']);
        }

        // Update customer note
        if (!empty($request['customer_note'])) {
            $order->set_customer_note($request['customer_note']);
        }

        // Save the order
        $order->save();

        $data = $this->prepare_item_for_response($order, $request);
        $response = rest_ensure_response($data);

        return $response;
    }

    /**
     * Delete a single order.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item($request) {
        $order_id = $request['id'];
        $force = $request['force'];
        $order = function_exists('aqualuxe_get_order') ? aqualuxe_get_order($order_id) : false;

        if (!$order || $order->get_id() !== $order_id) {
            return new WP_Error('rest_order_invalid_id', __('Invalid order ID.'), array('status' => 404));
        }

        $previous = $this->prepare_item_for_response($order, $request);
        $result = $order->delete($force);

        if (!$result) {
            return new WP_Error('rest_cannot_delete', __('The order cannot be deleted.'), array('status' => 500));
        }

        $response = new WP_REST_Response();
        $response->set_data(array('deleted' => true, 'previous' => $previous));

        return $response;
    }

    /**
     * Cancel an order.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function cancel_order($request) {
        $order_id = $request['id'];
        $order = function_exists('aqualuxe_get_order') ? aqualuxe_get_order($order_id) : false;

        if (!$order || $order->get_id() !== $order_id) {
            return new WP_Error('rest_order_invalid_id', __('Invalid order ID.'), array('status' => 404));
        }

        $result = $order->update_status('cancelled', __('Order cancelled via API.', 'aqualuxe'));

        if (!$result) {
            return new WP_Error('rest_cannot_cancel', __('The order cannot be cancelled.'), array('status' => 500));
        }

        $data = $this->prepare_item_for_response($order, $request);
        $response = rest_ensure_response($data);

        return $response;
    }

    /**
     * Get order notes.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_order_notes($request) {
        $order_id = $request['id'];
        $order = function_exists('aqualuxe_get_order') ? aqualuxe_get_order($order_id) : false;

        if (!$order || $order->get_id() !== $order_id) {
            return new WP_Error('rest_order_invalid_id', __('Invalid order ID.'), array('status' => 404));
        }

        $args = array(
            'order_id' => $order_id,
        );

        $notes = function_exists('aqualuxe_get_order_notes') ? aqualuxe_get_order_notes($args) : array();
        
        $data = array();
        foreach ($notes as $note) {
            $note_data = array(
                'id' => $note->id,
                'author' => $note->added_by,
                'date_created' => $note->date_created,
                'note' => $note->content,
                'customer_note' => (bool) $note->customer_note,
            );
            $data[] = $note_data;
        }

        $response = rest_ensure_response($data);
        return $response;
    }

    /**
     * Create order note.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_order_note($request) {
        $order_id = $request['id'];
        $order = function_exists('aqualuxe_get_order') ? aqualuxe_get_order($order_id) : false;

        if (!$order || $order->get_id() !== $order_id) {
            return new WP_Error('rest_order_invalid_id', __('Invalid order ID.'), array('status' => 404));
        }

        $note_id = $order->add_order_note($request['note'], $request['customer_note'], true);

        if (!$note_id) {
            return new WP_Error('rest_cannot_create_note', __('Cannot create order note.'), array('status' => 500));
        }

        $note = function_exists('aqualuxe_get_order_note') ? aqualuxe_get_order_note($note_id) : false;
        
        if (!$note) {
            return new WP_Error('rest_note_not_found', __('Order note not found.'), array('status' => 404));
        }

        $data = array(
            'id' => $note->id,
            'author' => $note->added_by,
            'date_created' => $note->date_created,
            'note' => $note->content,
            'customer_note' => (bool) $note->customer_note,
        );

        $response = rest_ensure_response($data);
        $response->set_status(201);

        return $response;
    }

    /**
     * Delete order note.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_order_note($request) {
        $order_id = $request['id'];
        $note_id = $request['note_id'];
        $order = function_exists('aqualuxe_get_order') ? aqualuxe_get_order($order_id) : false;

        if (!$order || $order->get_id() !== $order_id) {
            return new WP_Error('rest_order_invalid_id', __('Invalid order ID.'), array('status' => 404));
        }

        $result = wc_delete_order_note($note_id);

        if (!$result) {
            return new WP_Error('rest_cannot_delete_note', __('Cannot delete order note.'), array('status' => 500));
        }

        $response = new WP_REST_Response();
        $response->set_data(array('deleted' => true));

        return $response;
    }

    /**
     * Get order shipment tracking.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_order_shipment_tracking($request) {
        $order_id = $request['id'];
        $order = function_exists('aqualuxe_get_order') ? aqualuxe_get_order($order_id) : false;

        if (!$order || $order->get_id() !== $order_id) {
            return new WP_Error('rest_order_invalid_id', __('Invalid order ID.'), array('status' => 404));
        }

        // Check if WooCommerce Shipment Tracking is active
        if (function_exists('wc_st_get_tracking_items')) {
            $tracking_items = wc_st_get_tracking_items($order_id);
            $response = rest_ensure_response($tracking_items);
            return $response;
        }

        return new WP_Error('rest_shipment_tracking_not_available', __('Shipment tracking is not available.'), array('status' => 404));
    }

    /**
     * Get order refunds.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_order_refunds($request) {
        $order_id = $request['id'];
        $order = function_exists('aqualuxe_get_order') ? aqualuxe_get_order($order_id) : false;

        if (!$order || $order->get_id() !== $order_id) {
            return new WP_Error('rest_order_invalid_id', __('Invalid order ID.'), array('status' => 404));
        }

        $refunds = $order->get_refunds();
        $data = array();

        foreach ($refunds as $refund) {
            $refund_data = array(
                'id' => $refund->get_id(),
                'reason' => $refund->get_reason(),
                'total' => $refund->get_amount(),
                'date_created' => function_exists('aqualuxe_rest_prepare_date_response') ? aqualuxe_rest_prepare_date_response($refund->get_date_created()) : null,
            );
            $data[] = $refund_data;
        }

        $response = rest_ensure_response($data);
        return $response;
    }

    /**
     * Prepare a single order for response.
     *
     * @param WC_Order $order Order object.
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response Response data.
     */
    public function prepare_item_for_response($order, $request) {
        $data = array(
            'id' => $order->get_id(),
            'parent_id' => $order->get_parent_id(),
            'number' => $order->get_order_number(),
            'order_key' => $order->get_order_key(),
            'created_via' => $order->get_created_via(),
            'version' => $order->get_version(),
            'status' => $order->get_status(),
            'status_name' => function_exists('aqualuxe_get_order_status_name') ? aqualuxe_get_order_status_name($order->get_status()) : $order->get_status(),
            'currency' => $order->get_currency(),
            'date_created' => function_exists('aqualuxe_rest_prepare_date_response') ? aqualuxe_rest_prepare_date_response($order->get_date_created()) : null,
            'date_modified' => function_exists('aqualuxe_rest_prepare_date_response') ? aqualuxe_rest_prepare_date_response($order->get_date_modified()) : null,
            'discount_total' => $order->get_discount_total(),
            'discount_tax' => $order->get_discount_tax(),
            'shipping_total' => $order->get_shipping_total(),
            'shipping_tax' => $order->get_shipping_tax(),
            'cart_tax' => $order->get_cart_tax(),
            'total' => $order->get_total(),
            'total_tax' => $order->get_total_tax(),
            'prices_include_tax' => $order->get_prices_include_tax(),
            'customer_id' => $order->get_customer_id(),
            'customer_ip_address' => $order->get_customer_ip_address(),
            'customer_user_agent' => $order->get_customer_user_agent(),
            'customer_note' => $order->get_customer_note(),
            'billing' => array(
                'first_name' => $order->get_billing_first_name(),
                'last_name' => $order->get_billing_last_name(),
                'company' => $order->get_billing_company(),
                'address_1' => $order->get_billing_address_1(),
                'address_2' => $order->get_billing_address_2(),
                'city' => $order->get_billing_city(),
                'state' => $order->get_billing_state(),
                'postcode' => $order->get_billing_postcode(),
                'country' => $order->get_billing_country(),
                'email' => $order->get_billing_email(),
                'phone' => $order->get_billing_phone(),
            ),
            'shipping' => array(
                'first_name' => $order->get_shipping_first_name(),
                'last_name' => $order->get_shipping_last_name(),
                'company' => $order->get_shipping_company(),
                'address_1' => $order->get_shipping_address_1(),
                'address_2' => $order->get_shipping_address_2(),
                'city' => $order->get_shipping_city(),
                'state' => $order->get_shipping_state(),
                'postcode' => $order->get_shipping_postcode(),
                'country' => $order->get_shipping_country(),
            ),
            'payment_method' => $order->get_payment_method(),
            'payment_method_title' => $order->get_payment_method_title(),
            'transaction_id' => $order->get_transaction_id(),
            'date_paid' => function_exists('aqualuxe_rest_prepare_date_response') ? aqualuxe_rest_prepare_date_response($order->get_date_paid()) : null,
            'date_completed' => function_exists('aqualuxe_rest_prepare_date_response') ? aqualuxe_rest_prepare_date_response($order->get_date_completed()) : null,
            'cart_hash' => $order->get_cart_hash(),
        );

        // Add line items
        $data['line_items'] = array();
        foreach ($order->get_items() as $item_id => $item) {
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
                'sku' => $product ? $product->get_sku() : '',
                'price' => $order->get_item_total($item, false, false),
            );
            
            // Add item meta
            $item_data['meta_data'] = array();
            foreach ($item->get_meta_data() as $meta) {
                $item_data['meta_data'][] = array(
                    'id' => $meta->id,
                    'key' => $meta->key,
                    'value' => $meta->value,
                );
            }
            
            $data['line_items'][] = $item_data;
        }

        // Add shipping lines
        $data['shipping_lines'] = array();
        foreach ($order->get_shipping_methods() as $shipping_item_id => $shipping_item) {
            $shipping_data = array(
                'id' => $shipping_item_id,
                'method_title' => $shipping_item->get_method_title(),
                'method_id' => $shipping_item->get_method_id(),
                'instance_id' => $shipping_item->get_instance_id(),
                'total' => $shipping_item->get_total(),
                'total_tax' => $shipping_item->get_total_tax(),
                'taxes' => $shipping_item->get_taxes(),
            );
            
            // Add shipping meta
            $shipping_data['meta_data'] = array();
            foreach ($shipping_item->get_meta_data() as $meta) {
                $shipping_data['meta_data'][] = array(
                    'id' => $meta->id,
                    'key' => $meta->key,
                    'value' => $meta->value,
                );
            }
            
            $data['shipping_lines'][] = $shipping_data;
        }

        // Add tax lines
        $data['tax_lines'] = array();
        foreach ($order->get_tax_totals() as $tax_code => $tax) {
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
        foreach ($order->get_fees() as $fee_item_id => $fee_item) {
            $fee_data = array(
                'id' => $fee_item_id,
                'name' => $fee_item->get_name(),
                'tax_class' => $fee_item->get_tax_class(),
                'tax_status' => $fee_item->get_tax_status(),
                'amount' => $fee_item->get_amount(),
                'total' => $fee_item->get_total(),
                'total_tax' => $fee_item->get_total_tax(),
            );
            
            // Add fee meta
            $fee_data['meta_data'] = array();
            foreach ($fee_item->get_meta_data() as $meta) {
                $fee_data['meta_data'][] = array(
                    'id' => $meta->id,
                    'key' => $meta->key,
                    'value' => $meta->value,
                );
            }
            
            $data['fee_lines'][] = $fee_data;
        }

        // Add coupon lines
        $data['coupon_lines'] = array();
        foreach ($order->get_coupon_codes() as $coupon_code) {
            $coupon_data = array(
                'code' => $coupon_code,
                'discount' => function_exists('aqualuxe_get_order_coupon_discount_amount') ? aqualuxe_get_order_coupon_discount_amount($order, $coupon_code) : 0,
                'discount_tax' => function_exists('aqualuxe_get_order_coupon_discount_tax_amount') ? aqualuxe_get_order_coupon_discount_tax_amount($order, $coupon_code) : 0,
            );
            
            $data['coupon_lines'][] = $coupon_data;
        }

        // Add refunds
        $data['refunds'] = array();
        foreach ($order->get_refunds() as $refund) {
            $data['refunds'][] = array(
                'id' => $refund->get_id(),
                'reason' => $refund->get_reason() ? $refund->get_reason() : '',
                'total' => '-' . $refund->get_amount(),
            );
        }

        return rest_ensure_response($data);
    }
}