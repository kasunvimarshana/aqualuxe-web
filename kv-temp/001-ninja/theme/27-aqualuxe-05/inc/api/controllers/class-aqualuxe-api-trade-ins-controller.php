<?php
/**
 * AquaLuxe API Trade-ins Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Trade_Ins_Controller Class
 *
 * Handles API requests for trade-ins
 */
class AquaLuxe_API_Trade_Ins_Controller extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'trade-ins';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Get all trade-in requests
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get single trade-in request
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_item'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the trade-in request.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Submit trade-in request
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_item'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => array(
                    'title' => array(
                        'description' => __('Title of the trade-in item.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'description' => array(
                        'description' => __('Description of the trade-in item.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_textarea_field',
                    ),
                    'category_id' => array(
                        'description' => __('Category ID for the trade-in item.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                    ),
                    'condition' => array(
                        'description' => __('Condition of the trade-in item.'),
                        'type' => 'string',
                        'required' => true,
                        'enum' => array('new', 'like_new', 'excellent', 'good', 'fair', 'poor'),
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'age' => array(
                        'description' => __('Age of the trade-in item in months.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                    ),
                    'brand' => array(
                        'description' => __('Brand of the trade-in item.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'model' => array(
                        'description' => __('Model of the trade-in item.'),
                        'type' => 'string',
                        'required' => false,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'serial_number' => array(
                        'description' => __('Serial number of the trade-in item.'),
                        'type' => 'string',
                        'required' => false,
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                    'asking_price' => array(
                        'description' => __('Asking price for the trade-in item.'),
                        'type' => 'number',
                        'required' => false,
                        'sanitize_callback' => 'floatval',
                    ),
                    'images' => array(
                        'description' => __('Array of image IDs for the trade-in item.'),
                        'type' => 'array',
                        'required' => false,
                        'sanitize_callback' => function($images) {
                            return array_map('absint', $images);
                        },
                    ),
                    'contact_preference' => array(
                        'description' => __('Preferred contact method.'),
                        'type' => 'string',
                        'required' => true,
                        'enum' => array('email', 'phone', 'either'),
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
            ),
        ));

        // Get trade-in status
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/status', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_trade_in_status'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the trade-in request.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get user trade-in history
        register_rest_route($this->namespace, '/' . $this->rest_base . '/history', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_user_trade_in_history'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get trade-in categories
        register_rest_route($this->namespace, '/' . $this->rest_base . '/categories', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_trade_in_categories'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'parent' => array(
                        'description' => __('Limit result set to categories that are direct children of a parent category.'),
                        'type' => 'integer',
                        'sanitize_callback' => 'absint',
                    ),
                    'hide_empty' => array(
                        'description' => __('Whether to hide categories that don\'t have any trade-ins.'),
                        'type' => 'boolean',
                        'default' => false,
                    ),
                ),
            ),
        ));

        // Get trade-in evaluation
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/evaluation', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_trade_in_evaluation'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the trade-in request.'),
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
     * Get all trade-in requests.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_items($request) {
        $start_time = microtime(true);

        // Check if user is admin
        if (!$this->is_current_user_admin()) {
            return $this->format_error('permission_denied', __('You do not have permission to view all trade-in requests.'), 403);
        }

        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'status'));
        
        // Get search param
        $search = $this->get_search_param($request);
        
        // Get filter params
        $filters = $this->get_filter_params($request, array('category', 'status', 'user_id'));
        
        // Query args
        $args = array(
            'post_type' => 'trade_in_request',
            'post_status' => 'any',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
        );
        
        // Add search
        if ($search) {
            $args['s'] = $search;
        }
        
        // Add category filter
        if (isset($filters['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'trade_in_category',
                'field' => 'term_id',
                'terms' => $filters['category'],
            );
        }
        
        // Add status filter
        if (isset($filters['status'])) {
            $args['meta_query'][] = array(
                'key' => '_trade_in_status',
                'value' => $filters['status'],
                'compare' => '=',
            );
        }
        
        // Add user filter
        if (isset($filters['user_id'])) {
            $args['author'] = $filters['user_id'];
        }
        
        // Get trade-in requests
        $query = new WP_Query($args);
        $trade_ins = array();
        
        foreach ($query->posts as $post) {
            $trade_ins[] = $this->prepare_trade_in_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'trade_ins' => $trade_ins,
            'total' => $query->found_posts,
            'pages' => ceil($query->found_posts / $pagination['per_page']),
        ));
        
        // Add pagination headers
        $response = $this->add_pagination_headers($response, $query->found_posts, $pagination['per_page']);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get a single trade-in request.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request) {
        $start_time = microtime(true);
        
        $trade_in_id = $request->get_param('id');
        $trade_in = get_post($trade_in_id);
        
        if (!$trade_in || $trade_in->post_type !== 'trade_in_request') {
            return $this->format_error('trade_in_not_found', __('Trade-in request not found.'), 404);
        }
        
        // Check if user has permission to view this trade-in request
        $current_user_id = $this->get_current_user_id();
        
        if ($trade_in->post_author != $current_user_id && !$this->is_current_user_admin()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this trade-in request.'), 403);
        }
        
        $data = $this->prepare_trade_in_data($trade_in, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Create a trade-in request.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_item($request) {
        $start_time = microtime(true);
        
        // Get user ID
        $user_id = $this->get_current_user_id();
        
        // Get request params
        $title = $request->get_param('title');
        $description = $request->get_param('description');
        $category_id = $request->get_param('category_id');
        $condition = $request->get_param('condition');
        $age = $request->get_param('age');
        $brand = $request->get_param('brand');
        $model = $request->get_param('model');
        $serial_number = $request->get_param('serial_number');
        $asking_price = $request->get_param('asking_price');
        $images = $request->get_param('images');
        $contact_preference = $request->get_param('contact_preference');
        
        // Check if category exists
        $category = get_term($category_id, 'trade_in_category');
        
        if (!$category || is_wp_error($category)) {
            return $this->format_error('category_not_found', __('Category not found.'), 404);
        }
        
        // Create trade-in request
        $trade_in_data = array(
            'post_title' => $title,
            'post_content' => $description,
            'post_status' => 'publish',
            'post_type' => 'trade_in_request',
            'post_author' => $user_id,
        );
        
        $trade_in_id = wp_insert_post($trade_in_data);
        
        if (is_wp_error($trade_in_id)) {
            return $this->format_error('trade_in_creation_failed', $trade_in_id->get_error_message(), 500);
        }
        
        // Set category
        wp_set_object_terms($trade_in_id, $category_id, 'trade_in_category');
        
        // Set meta data
        update_post_meta($trade_in_id, '_trade_in_status', 'pending');
        update_post_meta($trade_in_id, '_trade_in_condition', $condition);
        update_post_meta($trade_in_id, '_trade_in_age', $age);
        update_post_meta($trade_in_id, '_trade_in_brand', $brand);
        
        if ($model) {
            update_post_meta($trade_in_id, '_trade_in_model', $model);
        }
        
        if ($serial_number) {
            update_post_meta($trade_in_id, '_trade_in_serial_number', $serial_number);
        }
        
        if ($asking_price) {
            update_post_meta($trade_in_id, '_trade_in_asking_price', $asking_price);
        }
        
        update_post_meta($trade_in_id, '_trade_in_contact_preference', $contact_preference);
        update_post_meta($trade_in_id, '_trade_in_submission_date', current_time('mysql'));
        
        // Set images
        if ($images && is_array($images)) {
            // Set featured image
            if (!empty($images[0])) {
                set_post_thumbnail($trade_in_id, $images[0]);
            }
            
            // Set gallery images
            if (count($images) > 1) {
                update_post_meta($trade_in_id, '_trade_in_gallery', implode(',', $images));
            }
        }
        
        // Get the created trade-in request
        $trade_in = get_post($trade_in_id);
        $data = $this->prepare_trade_in_data($trade_in, true);
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'trade_in' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get trade-in status.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_trade_in_status($request) {
        $start_time = microtime(true);
        
        $trade_in_id = $request->get_param('id');
        $trade_in = get_post($trade_in_id);
        
        if (!$trade_in || $trade_in->post_type !== 'trade_in_request') {
            return $this->format_error('trade_in_not_found', __('Trade-in request not found.'), 404);
        }
        
        // Check if user has permission to view this trade-in request
        $current_user_id = $this->get_current_user_id();
        
        if ($trade_in->post_author != $current_user_id && !$this->is_current_user_admin()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this trade-in request.'), 403);
        }
        
        // Get trade-in status
        $status = get_post_meta($trade_in_id, '_trade_in_status', true);
        $submission_date = get_post_meta($trade_in_id, '_trade_in_submission_date', true);
        $evaluation_date = get_post_meta($trade_in_id, '_trade_in_evaluation_date', true);
        $evaluation_amount = get_post_meta($trade_in_id, '_trade_in_evaluation_amount', true);
        $evaluation_notes = get_post_meta($trade_in_id, '_trade_in_evaluation_notes', true);
        $expiration_date = get_post_meta($trade_in_id, '_trade_in_expiration_date', true);
        
        // Prepare response
        $response = $this->format_response(array(
            'trade_in_id' => $trade_in_id,
            'status' => $status,
            'submission_date' => $submission_date,
            'evaluation_date' => $evaluation_date,
            'evaluation_amount' => $evaluation_amount ? floatval($evaluation_amount) : null,
            'evaluation_notes' => $evaluation_notes,
            'expiration_date' => $expiration_date,
            'is_expired' => $expiration_date && strtotime($expiration_date) < time(),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get user trade-in history.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_user_trade_in_history($request) {
        $start_time = microtime(true);
        
        // Get user ID
        $user_id = $this->get_current_user_id();
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'status'));
        
        // Query args
        $args = array(
            'post_type' => 'trade_in_request',
            'post_status' => 'any',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'author' => $user_id,
        );
        
        // Get trade-in requests
        $query = new WP_Query($args);
        $trade_ins = array();
        
        foreach ($query->posts as $post) {
            $trade_ins[] = $this->prepare_trade_in_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'trade_ins' => $trade_ins,
            'total' => $query->found_posts,
            'pages' => ceil($query->found_posts / $pagination['per_page']),
        ));
        
        // Add pagination headers
        $response = $this->add_pagination_headers($response, $query->found_posts, $pagination['per_page']);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get trade-in categories.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_trade_in_categories($request) {
        $start_time = microtime(true);
        
        $parent = $request->get_param('parent');
        $hide_empty = $request->get_param('hide_empty');
        
        // Get categories
        $args = array(
            'taxonomy' => 'trade_in_category',
            'hide_empty' => $hide_empty,
        );
        
        if (!is_null($parent)) {
            $args['parent'] = $parent;
        }
        
        $categories = get_terms($args);
        
        if (is_wp_error($categories)) {
            return $this->format_error('categories_error', $categories->get_error_message(), 500);
        }
        
        $data = array();
        
        foreach ($categories as $category) {
            $data[] = array(
                'id' => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'parent' => $category->parent,
                'count' => $category->count,
                'image' => $this->get_category_image($category->term_id),
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'categories' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get trade-in evaluation.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_trade_in_evaluation($request) {
        $start_time = microtime(true);
        
        $trade_in_id = $request->get_param('id');
        $trade_in = get_post($trade_in_id);
        
        if (!$trade_in || $trade_in->post_type !== 'trade_in_request') {
            return $this->format_error('trade_in_not_found', __('Trade-in request not found.'), 404);
        }
        
        // Check if user has permission to view this trade-in request
        $current_user_id = $this->get_current_user_id();
        
        if ($trade_in->post_author != $current_user_id && !$this->is_current_user_admin()) {
            return $this->format_error('permission_denied', __('You do not have permission to view this trade-in request.'), 403);
        }
        
        // Get trade-in status
        $status = get_post_meta($trade_in_id, '_trade_in_status', true);
        
        // Check if trade-in has been evaluated
        if ($status !== 'evaluated' && $status !== 'accepted' && $status !== 'completed') {
            return $this->format_error('not_evaluated', __('Trade-in request has not been evaluated yet.'), 400);
        }
        
        // Get evaluation data
        $evaluation_date = get_post_meta($trade_in_id, '_trade_in_evaluation_date', true);
        $evaluation_amount = get_post_meta($trade_in_id, '_trade_in_evaluation_amount', true);
        $evaluation_notes = get_post_meta($trade_in_id, '_trade_in_evaluation_notes', true);
        $expiration_date = get_post_meta($trade_in_id, '_trade_in_expiration_date', true);
        $evaluator_id = get_post_meta($trade_in_id, '_trade_in_evaluator_id', true);
        
        // Get evaluator data
        $evaluator_data = null;
        
        if ($evaluator_id) {
            $user = get_user_by('id', $evaluator_id);
            
            if ($user) {
                $evaluator_data = array(
                    'id' => $user->ID,
                    'name' => $user->display_name,
                    'email' => $user->user_email,
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'trade_in_id' => $trade_in_id,
            'status' => $status,
            'evaluation_date' => $evaluation_date,
            'evaluation_amount' => $evaluation_amount ? floatval($evaluation_amount) : 0,
            'evaluation_notes' => $evaluation_notes,
            'expiration_date' => $expiration_date,
            'is_expired' => $expiration_date && strtotime($expiration_date) < time(),
            'evaluator' => $evaluator_data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Prepare trade-in data for API response.
     *
     * @param WP_Post $trade_in Trade-in post object.
     * @param bool $single Whether this is a single trade-in request.
     * @return array
     */
    protected function prepare_trade_in_data($trade_in, $single = false) {
        // Get trade-in meta
        $status = get_post_meta($trade_in->ID, '_trade_in_status', true);
        $condition = get_post_meta($trade_in->ID, '_trade_in_condition', true);
        $age = get_post_meta($trade_in->ID, '_trade_in_age', true);
        $brand = get_post_meta($trade_in->ID, '_trade_in_brand', true);
        $model = get_post_meta($trade_in->ID, '_trade_in_model', true);
        $serial_number = get_post_meta($trade_in->ID, '_trade_in_serial_number', true);
        $asking_price = get_post_meta($trade_in->ID, '_trade_in_asking_price', true);
        $contact_preference = get_post_meta($trade_in->ID, '_trade_in_contact_preference', true);
        $submission_date = get_post_meta($trade_in->ID, '_trade_in_submission_date', true);
        $evaluation_date = get_post_meta($trade_in->ID, '_trade_in_evaluation_date', true);
        $evaluation_amount = get_post_meta($trade_in->ID, '_trade_in_evaluation_amount', true);
        $evaluation_notes = get_post_meta($trade_in->ID, '_trade_in_evaluation_notes', true);
        $expiration_date = get_post_meta($trade_in->ID, '_trade_in_expiration_date', true);
        
        // Get images
        $images = array();
        
        // Featured image
        $thumbnail_id = get_post_thumbnail_id($trade_in->ID);
        
        if ($thumbnail_id) {
            $image = wp_get_attachment_image_src($thumbnail_id, 'full');
            
            if ($image) {
                $images[] = array(
                    'id' => $thumbnail_id,
                    'src' => $image[0],
                    'alt' => get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true),
                );
            }
        }
        
        // Gallery images
        $gallery_ids = get_post_meta($trade_in->ID, '_trade_in_gallery', true);
        
        if ($gallery_ids) {
            $gallery_ids = explode(',', $gallery_ids);
            
            foreach ($gallery_ids as $gallery_id) {
                $image = wp_get_attachment_image_src($gallery_id, 'full');
                
                if ($image) {
                    $images[] = array(
                        'id' => $gallery_id,
                        'src' => $image[0],
                        'alt' => get_post_meta($gallery_id, '_wp_attachment_image_alt', true),
                    );
                }
            }
        }
        
        // Get categories
        $categories = array();
        $terms = wp_get_post_terms($trade_in->ID, 'trade_in_category');
        
        foreach ($terms as $term) {
            $categories[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }
        
        // Get user data
        $user = get_user_by('id', $trade_in->post_author);
        $user_data = array(
            'id' => $user->ID,
            'name' => $user->display_name,
            'email' => $user->user_email,
        );
        
        // Prepare data
        $data = array(
            'id' => $trade_in->ID,
            'title' => $trade_in->post_title,
            'description' => $trade_in->post_content,
            'status' => $status,
            'condition' => $condition,
            'age' => intval($age),
            'brand' => $brand,
            'model' => $model,
            'serial_number' => $serial_number,
            'asking_price' => $asking_price ? floatval($asking_price) : null,
            'contact_preference' => $contact_preference,
            'submission_date' => $submission_date,
            'user' => $user_data,
            'images' => $images,
            'categories' => $categories,
        );
        
        // Add evaluation data if available
        if ($status === 'evaluated' || $status === 'accepted' || $status === 'completed') {
            $data['evaluation'] = array(
                'date' => $evaluation_date,
                'amount' => $evaluation_amount ? floatval($evaluation_amount) : 0,
                'notes' => $evaluation_notes,
                'expiration_date' => $expiration_date,
                'is_expired' => $expiration_date && strtotime($expiration_date) < time(),
            );
        }
        
        return $data;
    }

    /**
     * Get category image.
     *
     * @param int $category_id Category ID.
     * @return array
     */
    protected function get_category_image($category_id) {
        $thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);
        
        if ($thumbnail_id) {
            $image = wp_get_attachment_image_src($thumbnail_id, 'full');
            
            if ($image) {
                return array(
                    'id' => (int) $thumbnail_id,
                    'src' => $image[0],
                    'name' => get_the_title($thumbnail_id),
                    'alt' => get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true),
                );
            }
        }
        
        return array();
    }
}