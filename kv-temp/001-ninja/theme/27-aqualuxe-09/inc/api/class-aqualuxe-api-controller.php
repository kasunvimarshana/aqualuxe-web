<?php
/**
 * AquaLuxe API Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Controller Class
 *
 * Base class for all API controllers
 */
abstract class AquaLuxe_API_Controller {

    /**
     * The namespace for this API.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The base for this API controller.
     *
     * @var string
     */
    protected $rest_base;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        $api = AquaLuxe_API::get_instance();
        $this->namespace = $api->get_namespace();
        $this->rest_base = $this->get_rest_base();
    }

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    abstract protected function get_rest_base();

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    abstract public function register_routes();

    /**
     * Check if a given request has access to read items.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
     */
    public function get_items_permissions_check($request) {
        return is_user_logged_in();
    }

    /**
     * Check if a given request has access to read a specific item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
     */
    public function get_item_permissions_check($request) {
        return is_user_logged_in();
    }

    /**
     * Check if a given request has access to create items.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error True if the request has access to create items, WP_Error object otherwise.
     */
    public function create_item_permissions_check($request) {
        return is_user_logged_in();
    }

    /**
     * Check if a given request has access to update a specific item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error True if the request has access to update the item, WP_Error object otherwise.
     */
    public function update_item_permissions_check($request) {
        return is_user_logged_in();
    }

    /**
     * Check if a given request has access to delete a specific item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return true|WP_Error True if the request has access to delete the item, WP_Error object otherwise.
     */
    public function delete_item_permissions_check($request) {
        return is_user_logged_in() && current_user_can('manage_options');
    }

    /**
     * Prepare a response for insertion into a collection.
     *
     * @param WP_REST_Response $response Response object.
     * @return array Response data, ready for insertion into collection data.
     */
    public function prepare_response_for_collection($response) {
        if (!($response instanceof WP_REST_Response)) {
            return $response;
        }

        $data = (array) $response->get_data();
        $server = rest_get_server();

        if (method_exists($server, 'get_compact_response_links')) {
            $links = call_user_func(array($server, 'get_compact_response_links'), $response);
        } else {
            $links = call_user_func(array($server, 'get_response_links'), $response);
        }

        if (!empty($links)) {
            $data['_links'] = $links;
        }

        return $data;
    }

    /**
     * Format API response.
     *
     * @param mixed $data Response data.
     * @param int $status HTTP status code.
     * @param array $headers HTTP headers.
     * @return WP_REST_Response
     */
    protected function format_response($data, $status = 200, $headers = array()) {
        $response = new WP_REST_Response($data, $status);
        
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }
        
        return $response;
    }

    /**
     * Format error response.
     *
     * @param string $code Error code.
     * @param string $message Error message.
     * @param int $status HTTP status code.
     * @return WP_Error
     */
    protected function format_error($code, $message, $status = 400) {
        return new WP_Error($code, $message, array('status' => $status));
    }

    /**
     * Get pagination params.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return array
     */
    protected function get_pagination_params($request) {
        $page = (int) $request->get_param('page');
        $per_page = (int) $request->get_param('per_page');
        
        $page = $page <= 0 ? 1 : $page;
        $per_page = $per_page <= 0 ? 10 : ($per_page > 100 ? 100 : $per_page);
        
        return array(
            'page' => $page,
            'per_page' => $per_page,
            'offset' => ($page - 1) * $per_page,
        );
    }

    /**
     * Add pagination headers.
     *
     * @param WP_REST_Response $response Response object.
     * @param int $total_items Total number of items.
     * @param int $per_page Number of items per page.
     * @return WP_REST_Response
     */
    protected function add_pagination_headers($response, $total_items, $per_page) {
        $total_pages = ceil($total_items / $per_page);
        
        $response->header('X-WP-Total', $total_items);
        $response->header('X-WP-TotalPages', $total_pages);
        
        return $response;
    }

    /**
     * Get sort params.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @param array $allowed_fields Allowed fields for sorting.
     * @return array
     */
    protected function get_sort_params($request, $allowed_fields = array()) {
        $orderby = $request->get_param('orderby');
        $order = strtoupper($request->get_param('order'));
        
        if (!in_array($orderby, $allowed_fields)) {
            $orderby = 'date';
        }
        
        if (!in_array($order, array('ASC', 'DESC'))) {
            $order = 'DESC';
        }
        
        return array(
            'orderby' => $orderby,
            'order' => $order,
        );
    }

    /**
     * Get filter params.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @param array $allowed_filters Allowed filter fields.
     * @return array
     */
    protected function get_filter_params($request, $allowed_filters = array()) {
        $filters = array();
        
        foreach ($allowed_filters as $filter) {
            $value = $request->get_param($filter);
            
            if (!is_null($value)) {
                $filters[$filter] = $value;
            }
        }
        
        return $filters;
    }

    /**
     * Get search params.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return string|null
     */
    protected function get_search_param($request) {
        $search = $request->get_param('search');
        
        if (!is_null($search) && !empty($search)) {
            return sanitize_text_field($search);
        }
        
        return null;
    }

    /**
     * Get date range params.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return array
     */
    protected function get_date_range_params($request) {
        $start_date = $request->get_param('start_date');
        $end_date = $request->get_param('end_date');
        
        $date_range = array();
        
        if (!is_null($start_date)) {
            $date_range['start_date'] = sanitize_text_field($start_date);
        }
        
        if (!is_null($end_date)) {
            $date_range['end_date'] = sanitize_text_field($end_date);
        }
        
        return $date_range;
    }

    /**
     * Validate date format.
     *
     * @param string $date Date string.
     * @param string $format Date format.
     * @return bool
     */
    protected function validate_date($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Log API request.
     *
     * @param WP_REST_Request $request The request object.
     * @param mixed $response The response data.
     * @param float $execution_time The execution time in seconds.
     * @return void
     */
    protected function log_request($request, $response, $execution_time) {
        $api = AquaLuxe_API::get_instance();
        $api->log_request($request, $response, $execution_time);
    }

    /**
     * Get current user ID.
     *
     * @return int
     */
    protected function get_current_user_id() {
        return get_current_user_id();
    }

    /**
     * Check if current user has capability.
     *
     * @param string $capability Capability name.
     * @return bool
     */
    protected function current_user_can($capability) {
        return current_user_can($capability);
    }

    /**
     * Check if current user is admin.
     *
     * @return bool
     */
    protected function is_current_user_admin() {
        return current_user_can('manage_options');
    }

    /**
     * Get common arguments for REST API endpoints.
     *
     * @return array
     */
    protected function get_common_args() {
        return array(
            'page' => array(
                'description' => __('Current page of the collection.'),
                'type' => 'integer',
                'default' => 1,
                'minimum' => 1,
                'sanitize_callback' => 'absint',
            ),
            'per_page' => array(
                'description' => __('Maximum number of items to be returned in result set.'),
                'type' => 'integer',
                'default' => 10,
                'minimum' => 1,
                'maximum' => 100,
                'sanitize_callback' => 'absint',
            ),
            'search' => array(
                'description' => __('Limit results to those matching a string.'),
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'orderby' => array(
                'description' => __('Sort collection by object attribute.'),
                'type' => 'string',
                'default' => 'date',
                'sanitize_callback' => 'sanitize_key',
            ),
            'order' => array(
                'description' => __('Order sort attribute ascending or descending.'),
                'type' => 'string',
                'default' => 'desc',
                'enum' => array('asc', 'desc', 'ASC', 'DESC'),
                'sanitize_callback' => 'sanitize_key',
            ),
            'start_date' => array(
                'description' => __('Limit results to those after the specified date.'),
                'type' => 'string',
                'format' => 'date',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'end_date' => array(
                'description' => __('Limit results to those before the specified date.'),
                'type' => 'string',
                'format' => 'date',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        );
    }

    /**
     * Get collection params.
     *
     * @return array
     */
    public function get_collection_params() {
        return $this->get_common_args();
    }
}