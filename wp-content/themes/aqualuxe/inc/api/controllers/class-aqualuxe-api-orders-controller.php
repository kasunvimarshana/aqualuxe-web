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
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'payment_method_title' => array(
                        'description' => __('Payment method title.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'set_paid' => array(
                        'description' => __('Set the order as paid.'),
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'billing' => array(
                        'description' => __('Billing address.'),
                        'type' => 'object',
                        'required' => true,
                    ),
                    'shipping' => array(
                        'description' => __('Shipping address.'),
                        'type' => 'object',
                        'required' => true,
                    ),
                    'line_items' => array(
                        'description' => __('Line items data.'),
                        'type' => 'array',
                        'required' => true,
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
                    'coupon_lines' => array(
                        'description' => __('Coupon lines data.'),
                        'type' => 'array',
                        'items' => array(
                            'type' => 'object',
                        ),
                    ),
                    'customer_note' => array(
                        'description' => __('Note added by customer during checkout.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_textarea_field',
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
                        'enum' => array_keys(wc_get_order_statuses()),
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

        // Add order note
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

        // Get order shipments
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/shipments', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_order_shipments'),
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
     * Get all orders.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_items($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Check if user is admin
        $is_admin = $this->is_current_user_admin();
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'id', 'total'));
        
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
        
        // Add customer filter
        if (!empty($customer_id) && $is_admin) {
            $args['customer_id'] = $customer_id;
        } else {
            // Non-admin users can only see their own orders
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
        
        // Get orders
        $query = wc_get_orders($args);
        $orders = $query->orders;
        $total = $query->total;
        $max_pages = $query->max_num_pages;
        
        $data = array();
        
        foreach ($orders as $order) {
            $data[] = $this->prepare_order_data($order);
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
     * Get a single order.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request) {
        $start_time = microtime(true);
        
        $order_id = $request->get_param('id');
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return $this->format_error('order_not_found', __('Order not found.'), 404);
        }
        
        // Check if user has permission to view this order
        if (!$this->is_current_user_admin() && $order->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this order.'), 403);
        }
        
        $data = $this->prepare_order_data($order, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Create an order.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        // Create order
        $order = wc_create_order(array(
            'customer_id' => $user_id,
            'created_via' => 'aqualuxe_api',
        ));
        
        if (is_wp_error($order)) {
            return $this->format_error('order_creation_failed', $order->get_error_message(), 500);
        }
        
        // Set addresses
        $billing = $request->get_param('billing');
        $shipping = $request->get_param('shipping');
        
        $order->set_address($billing, 'billing');
        $order->set_address($shipping, 'shipping');
        
        // Set payment method
        $payment_method = $request->get_param('payment_method');
        $payment_method_title = $request->get_param('payment_method_title');
        
        $order->set_payment_method($payment_method);
        $order->set_payment_method_title($payment_method_title);
        
        // Add line items
        $line_items = $request->get_param('line_items');
        
        foreach ($line_items as $line_item) {
            $product_id = isset($line_item['product_id']) ? $line_item['product_id'] : 0;
            $variation_id = isset($line_item['variation_id']) ? $line_item['variation_id'] : 0;
            $quantity = isset($line_item['quantity']) ? $line_item['quantity'] : 1;
            
            $product = wc_get_product($variation_id ? $variation_id : $product_id);
            
            if (!$product) {
                continue;
            }
            
            $item_id = $order->add_product($product, $quantity);
            
            // Add item meta
            if (isset($line_item['meta_data']) && is_array($line_item['meta_data'])) {
                $item = $order->get_item($item_id);
                
                foreach ($line_item['meta_data'] as $meta) {
                    $item->add_meta_data($meta['key'], $meta['value'], true);
                }
                
                $item->save();
            }
        }
        
        // Add shipping lines
        $shipping_lines = $request->get_param('shipping_lines');
        
        if ($shipping_lines) {
            foreach ($shipping_lines as $shipping_line) {
                $method_id = isset($shipping_line['method_id']) ? $shipping_line['method_id'] : '';
                $method_title = isset($shipping_line['method_title']) ? $shipping_line['method_title'] : '';
                $total = isset($shipping_line['total']) ? $shipping_line['total'] : 0;
                
                $item = new WC_Order_Item_Shipping();
                $item->set_method_id($method_id);
                $item->set_method_title($method_title);
                $item->set_total($total);
                
                // Add item meta
                if (isset($shipping_line['meta_data']) && is_array($shipping_line['meta_data'])) {
                    foreach ($shipping_line['meta_data'] as $meta) {
                        $item->add_meta_data($meta['key'], $meta['value'], true);
                    }
                }
                
                $order->add_item($item);
            }
        }
        
        // Add coupon lines
        $coupon_lines = $request->get_param('coupon_lines');
        
        if ($coupon_lines) {
            foreach ($coupon_lines as $coupon_line) {
                $code = isset($coupon_line['code']) ? $coupon_line['code'] : '';
                
                if (!empty($code)) {
                    $order->apply_coupon($code);
                }
            }
        }
        
        // Set customer note
        $customer_note = $request->get_param('customer_note');
        
        if ($customer_note) {
            $order->set_customer_note($customer_note);
        }
        
        // Calculate totals
        $order->calculate_totals();
        
        // Set as paid if requested
        $set_paid = $request->get_param('set_paid');
        
        if ($set_paid) {
            $order->payment_complete();
        }
        
        // Save order
        $order->save();
        
        // Add order note
        $order->add_order_note(__('Order created via AquaLuxe API.'), false);
        
        // Get order data
        $data = $this->prepare_order_data($order, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Update an order.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_item($request) {
        $start_time = microtime(true);
        
        $order_id = $request->get_param('id');
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return $this->format_error('order_not_found', __('Order not found.'), 404);
        }
        
        // Check if user has permission to update this order
        if (!$this->is_current_user_admin() && $order->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to update this order.'), 403);
        }
        
        // Update status
        $status = $request->get_param('status');
        
        if ($status) {
            // Non-admin users can only cancel orders
            if (!$this->is_current_user_admin() && $status !== 'cancelled') {
                return $this->format_error('permission_denied', __('You do not have permission to change the order status.'), 403);
            }
            
            $order->update_status($status, __('Status updated via AquaLuxe API.'), true);
        }
        
        // Update customer note
        $customer_note = $request->get_param('customer_note');
        
        if ($customer_note) {
            $order->set_customer_note($customer_note);
        }
        
        // Save order
        $order->save();
        
        // Get order data
        $data = $this->prepare_order_data($order, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Cancel an order.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function cancel_order($request) {
        $start_time = microtime(true);
        
        $order_id = $request->get_param('id');
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return $this->format_error('order_not_found', __('Order not found.'), 404);
        }
        
        // Check if user has permission to cancel this order
        if (!$this->is_current_user_admin() && $order->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to cancel this order.'), 403);
        }
        
        // Check if order can be cancelled
        if (!$order->has_status(array('pending', 'processing', 'on-hold'))) {
            return $this->format_error('invalid_order_status', __('This order cannot be cancelled.'), 400);
        }
        
        // Cancel order
        $order->update_status('cancelled', __('Order cancelled via AquaLuxe API.'), true);
        
        // Get order data
        $data = $this->prepare_order_data($order, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get order notes.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_order_notes($request) {
        $start_time = microtime(true);
        
        $order_id = $request->get_param('id');
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return $this->format_error('order_not_found', __('Order not found.'), 404);
        }
        
        // Check if user has permission to view this order
        if (!$this->is_current_user_admin() && $order->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this order.'), 403);
        }
        
        // Get notes
        $args = array(
            'post_id' => $order_id,
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
     * Create order note.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_order_note($request) {
        $start_time = microtime(true);
        
        $order_id = $request->get_param('id');
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return $this->format_error('order_not_found', __('Order not found.'), 404);
        }
        
        // Check if user has permission to update this order
        if (!$this->is_current_user_admin() && $order->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to update this order.'), 403);
        }
        
        $note = $request->get_param('note');
        $customer_note = $request->get_param('customer_note');
        
        // Add note
        $note_id = $order->add_order_note($note, $customer_note, true);
        
        if (!$note_id) {
            return $this->format_error('note_creation_failed', __('Failed to create order note.'), 500);
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
     * Get order shipments.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_order_shipments($request) {
        $start_time = microtime(true);
        
        $order_id = $request->get_param('id');
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return $this->format_error('order_not_found', __('Order not found.'), 404);
        }
        
        // Check if user has permission to view this order
        if (!$this->is_current_user_admin() && $order->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this order.'), 403);
        }
        
        // Get shipments
        $shipments = array();
        
        // Check if WooCommerce Shipment Tracking is active
        if (class_exists('WC_Shipment_Tracking')) {
            $tracking_items = wc_st_get_tracking_items($order_id);
            
            foreach ($tracking_items as $tracking_item) {
                $shipments[] = array(
                    'tracking_id' => $tracking_item['tracking_id'],
                    'tracking_provider' => $tracking_item['tracking_provider'],
                    'tracking_number' => $tracking_item['tracking_number'],
                    'date_shipped' => $tracking_item['date_shipped'],
                    'tracking_url' => $tracking_item['formatted_tracking_link'],
                );
            }
        } else {
            // Try to get tracking info from order meta
            $tracking_number = $order->get_meta('_tracking_number');
            $tracking_provider = $order->get_meta('_tracking_provider');
            $date_shipped = $order->get_meta('_date_shipped');
            
            if ($tracking_number) {
                $shipments[] = array(
                    'tracking_id' => '',
                    'tracking_provider' => $tracking_provider,
                    'tracking_number' => $tracking_number,
                    'date_shipped' => $date_shipped,
                    'tracking_url' => '',
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'shipments' => $shipments,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get order refunds.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_order_refunds($request) {
        $start_time = microtime(true);
        
        $order_id = $request->get_param('id');
        $order = wc_get_order($order_id);
        
        if (!$order) {
            return $this->format_error('order_not_found', __('Order not found.'), 404);
        }
        
        // Check if user has permission to view this order
        if (!$this->is_current_user_admin() && $order->get_customer_id() !== $this->get_current_user_id()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this order.'), 403);
        }
        
        // Get refunds
        $refunds = $order->get_refunds();
        $data = array();
        
        foreach ($refunds as $refund) {
            $data[] = array(
                'id' => $refund->get_id(),
                'reason' => $refund->get_reason(),
                'total' => $refund->get_amount(),
                'date_created' => wc_rest_prepare_date_response($refund->get_date_created()),
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'refunds' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Prepare order data for API response.
     *
     * @param WC_Order $order Order object.
     * @param bool $single Whether this is a single order request.
     * @return array
     */
    protected function prepare_order_data($order, $single = false) {
        $data = array(
            'id' => $order->get_id(),
            'parent_id' => $order->get_parent_id(),
            'number' => $order->get_order_number(),
            'order_key' => $order->get_order_key(),
            'created_via' => $order->get_created_via(),
            'version' => $order->get_version(),
            'status' => $order->get_status(),
            'status_name' => wc_get_order_status_name($order->get_status()),
            'currency' => $order->get_currency(),
            'date_created' => wc_rest_prepare_date_response($order->get_date_created()),
            'date_modified' => wc_rest_prepare_date_response($order->get_date_modified()),
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
            'date_paid' => wc_rest_prepare_date_response($order->get_date_paid()),
            'date_completed' => wc_rest_prepare_date_response($order->get_date_completed()),
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
        
        foreach ($order->get_shipping_methods() as $item_id => $item) {
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
        
        foreach ($order->get_fees() as $item_id => $item) {
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
        
        foreach ($order->get_coupon_codes() as $coupon_code) {
            $coupon_data = array(
                'code' => $coupon_code,
                'discount' => wc_get_order_coupon_discount_amount($order, $coupon_code),
                'discount_tax' => wc_get_order_coupon_discount_tax_amount($order, $coupon_code),
            );
            
            $data['coupon_lines'][] = $coupon_data;
        }
        
        // Add refunds
        $data['refunds'] = array();
        
        foreach ($order->get_refunds() as $refund) {
            $data['refunds'][] = array(
                'id' => $refund->get_id(),
                'reason' => $refund->get_reason(),
                'total' => $refund->get_amount(),
                'date_created' => wc_rest_prepare_date_response($refund->get_date_created()),
            );
        }
        
        // Add notes for single order
        if ($single) {
            $args = array(
                'post_id' => $order->get_id(),
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