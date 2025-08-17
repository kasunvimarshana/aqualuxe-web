<?php
/**
 * AquaLuxe API Auctions Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Auctions_Controller Class
 *
 * Handles API requests for auctions
 */
class AquaLuxe_API_Auctions_Controller extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'auctions';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Get all auctions
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get single auction
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_item'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the auction.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get auctions by category
        register_rest_route($this->namespace, '/' . $this->rest_base . '/category/(?P<category_id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_auctions_by_category'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array_merge(
                    array(
                        'category_id' => array(
                            'description' => __('Unique identifier for the category.'),
                            'type' => 'integer',
                            'required' => true,
                            'sanitize_callback' => 'absint',
                            'validate_callback' => 'rest_validate_request_arg',
                        ),
                    ),
                    $this->get_collection_params()
                ),
            ),
        ));

        // Get featured auctions
        register_rest_route($this->namespace, '/' . $this->rest_base . '/featured', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_featured_auctions'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get auction categories
        register_rest_route($this->namespace, '/' . $this->rest_base . '/categories', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_auction_categories'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'parent' => array(
                        'description' => __('Limit result set to categories that are direct children of a parent category.'),
                        'type' => 'integer',
                        'sanitize_callback' => 'absint',
                    ),
                    'hide_empty' => array(
                        'description' => __('Whether to hide categories that don\'t have any auctions.'),
                        'type' => 'boolean',
                        'default' => false,
                    ),
                ),
            ),
        ));

        // Search auctions
        register_rest_route($this->namespace, '/' . $this->rest_base . '/search', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'search_auctions'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array_merge(
                    array(
                        'query' => array(
                            'description' => __('Search query.'),
                            'type' => 'string',
                            'required' => true,
                            'sanitize_callback' => 'sanitize_text_field',
                        ),
                    ),
                    $this->get_collection_params()
                ),
            ),
        ));

        // Get auction bids
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/bids', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_auction_bids'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array_merge(
                    array(
                        'id' => array(
                            'description' => __('Unique identifier for the auction.'),
                            'type' => 'integer',
                            'required' => true,
                            'sanitize_callback' => 'absint',
                            'validate_callback' => 'rest_validate_request_arg',
                        ),
                    ),
                    $this->get_collection_params()
                ),
            ),
        ));

        // Place bid on auction
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/bid', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'place_bid'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the auction.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'amount' => array(
                        'description' => __('Bid amount.'),
                        'type' => 'number',
                        'required' => true,
                        'sanitize_callback' => 'floatval',
                    ),
                ),
            ),
        ));

        // Get auction status
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/status', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_auction_status'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the auction.'),
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
     * Get all auctions.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_items($request) {
        $start_time = microtime(true);

        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'end_date', 'current_bid'));
        
        // Get search param
        $search = $this->get_search_param($request);
        
        // Get filter params
        $filters = $this->get_filter_params($request, array('category', 'status', 'min_price', 'max_price'));
        
        // Query args
        $args = array(
            'post_type' => 'auction',
            'post_status' => 'publish',
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
                'taxonomy' => 'auction_category',
                'field' => 'term_id',
                'terms' => $filters['category'],
            );
        }
        
        // Add status filter
        if (isset($filters['status'])) {
            $args['meta_query'][] = array(
                'key' => '_auction_status',
                'value' => $filters['status'],
                'compare' => '=',
            );
        }
        
        // Add price filter
        if (isset($filters['min_price']) || isset($filters['max_price'])) {
            $args['meta_query'] = array('relation' => 'AND');
            
            if (isset($filters['min_price'])) {
                $args['meta_query'][] = array(
                    'key' => '_auction_current_bid',
                    'value' => $filters['min_price'],
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                );
            }
            
            if (isset($filters['max_price'])) {
                $args['meta_query'][] = array(
                    'key' => '_auction_current_bid',
                    'value' => $filters['max_price'],
                    'compare' => '<=',
                    'type' => 'NUMERIC',
                );
            }
        }
        
        // Get auctions
        $query = new WP_Query($args);
        $auctions = array();
        
        foreach ($query->posts as $post) {
            $auctions[] = $this->prepare_auction_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'auctions' => $auctions,
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
     * Get a single auction.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request) {
        $start_time = microtime(true);
        
        $auction_id = $request->get_param('id');
        $auction = get_post($auction_id);
        
        if (!$auction || $auction->post_type !== 'auction' || $auction->post_status !== 'publish') {
            return $this->format_error('auction_not_found', __('Auction not found.'), 404);
        }
        
        $data = $this->prepare_auction_data($auction, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get auctions by category.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_auctions_by_category($request) {
        $start_time = microtime(true);
        
        $category_id = $request->get_param('category_id');
        
        // Check if category exists
        $category = get_term($category_id, 'auction_category');
        
        if (!$category || is_wp_error($category)) {
            return $this->format_error('category_not_found', __('Category not found.'), 404);
        }
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'end_date', 'current_bid'));
        
        // Query args
        $args = array(
            'post_type' => 'auction',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'tax_query' => array(
                array(
                    'taxonomy' => 'auction_category',
                    'field' => 'term_id',
                    'terms' => $category_id,
                ),
            ),
        );
        
        // Get auctions
        $query = new WP_Query($args);
        $auctions = array();
        
        foreach ($query->posts as $post) {
            $auctions[] = $this->prepare_auction_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'category' => array(
                'id' => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $this->get_category_image($category_id),
            ),
            'auctions' => $auctions,
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
     * Get featured auctions.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_featured_auctions($request) {
        $start_time = microtime(true);
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'end_date', 'current_bid'));
        
        // Query args
        $args = array(
            'post_type' => 'auction',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'meta_query' => array(
                array(
                    'key' => '_auction_featured',
                    'value' => '1',
                    'compare' => '=',
                ),
            ),
        );
        
        // Get auctions
        $query = new WP_Query($args);
        $auctions = array();
        
        foreach ($query->posts as $post) {
            $auctions[] = $this->prepare_auction_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'auctions' => $auctions,
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
     * Get auction categories.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_auction_categories($request) {
        $start_time = microtime(true);
        
        $parent = $request->get_param('parent');
        $hide_empty = $request->get_param('hide_empty');
        
        // Get categories
        $args = array(
            'taxonomy' => 'auction_category',
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
     * Search auctions.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function search_auctions($request) {
        $start_time = microtime(true);
        
        $query = $request->get_param('query');
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Query args
        $args = array(
            'post_type' => 'auction',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            's' => $query,
        );
        
        // Get auctions
        $query_obj = new WP_Query($args);
        $auctions = array();
        
        foreach ($query_obj->posts as $post) {
            $auctions[] = $this->prepare_auction_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'query' => $query,
            'auctions' => $auctions,
            'total' => $query_obj->found_posts,
            'pages' => ceil($query_obj->found_posts / $pagination['per_page']),
        ));
        
        // Add pagination headers
        $response = $this->add_pagination_headers($response, $query_obj->found_posts, $pagination['per_page']);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get auction bids.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_auction_bids($request) {
        $start_time = microtime(true);
        
        $auction_id = $request->get_param('id');
        $auction = get_post($auction_id);
        
        if (!$auction || $auction->post_type !== 'auction' || $auction->post_status !== 'publish') {
            return $this->format_error('auction_not_found', __('Auction not found.'), 404);
        }
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get bids
        $bids = $this->get_auction_bid_history($auction_id, $pagination['per_page'], $pagination['offset']);
        $total_bids = $this->get_auction_bid_count($auction_id);
        
        // Prepare response
        $response = $this->format_response(array(
            'auction_id' => $auction_id,
            'bids' => $bids,
            'total' => $total_bids,
            'pages' => ceil($total_bids / $pagination['per_page']),
        ));
        
        // Add pagination headers
        $response = $this->add_pagination_headers($response, $total_bids, $pagination['per_page']);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Place bid on auction.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function place_bid($request) {
        $start_time = microtime(true);
        
        $auction_id = $request->get_param('id');
        $auction = get_post($auction_id);
        
        if (!$auction || $auction->post_type !== 'auction' || $auction->post_status !== 'publish') {
            return $this->format_error('auction_not_found', __('Auction not found.'), 404);
        }
        
        // Check if auction is active
        $status = get_post_meta($auction_id, '_auction_status', true);
        
        if ($status !== 'active') {
            return $this->format_error('auction_not_active', __('Auction is not active.'), 400);
        }
        
        // Check if auction has ended
        $end_date = get_post_meta($auction_id, '_auction_end_date', true);
        
        if (strtotime($end_date) < time()) {
            return $this->format_error('auction_ended', __('Auction has ended.'), 400);
        }
        
        // Get bid amount
        $amount = $request->get_param('amount');
        
        // Get current bid
        $current_bid = get_post_meta($auction_id, '_auction_current_bid', true);
        $current_bid = $current_bid ? floatval($current_bid) : 0;
        
        // Get minimum bid increment
        $min_increment = get_post_meta($auction_id, '_auction_bid_increment', true);
        $min_increment = $min_increment ? floatval($min_increment) : 1;
        
        // Check if bid is high enough
        $min_bid = $current_bid + $min_increment;
        
        if ($amount < $min_bid) {
            return $this->format_error('bid_too_low', sprintf(__('Bid must be at least %s.'), wc_price($min_bid)), 400);
        }
        
        // Get user ID
        $user_id = $this->get_current_user_id();
        
        // Place bid
        $bid_id = $this->add_auction_bid($auction_id, $user_id, $amount);
        
        if (!$bid_id) {
            return $this->format_error('bid_failed', __('Failed to place bid.'), 500);
        }
        
        // Update auction current bid
        update_post_meta($auction_id, '_auction_current_bid', $amount);
        update_post_meta($auction_id, '_auction_current_bidder', $user_id);
        
        // Get updated auction data
        $auction_data = $this->prepare_auction_data($auction);
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'bid_id' => $bid_id,
            'amount' => $amount,
            'auction' => $auction_data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get auction status.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_auction_status($request) {
        $start_time = microtime(true);
        
        $auction_id = $request->get_param('id');
        $auction = get_post($auction_id);
        
        if (!$auction || $auction->post_type !== 'auction' || $auction->post_status !== 'publish') {
            return $this->format_error('auction_not_found', __('Auction not found.'), 404);
        }
        
        // Get auction status
        $status = get_post_meta($auction_id, '_auction_status', true);
        $start_date = get_post_meta($auction_id, '_auction_start_date', true);
        $end_date = get_post_meta($auction_id, '_auction_end_date', true);
        $current_bid = get_post_meta($auction_id, '_auction_current_bid', true);
        $current_bidder = get_post_meta($auction_id, '_auction_current_bidder', true);
        $reserve_price = get_post_meta($auction_id, '_auction_reserve_price', true);
        $reserve_met = $current_bid >= $reserve_price;
        
        // Get time remaining
        $time_remaining = 0;
        
        if ($status === 'active' && $end_date) {
            $time_remaining = strtotime($end_date) - time();
            
            if ($time_remaining < 0) {
                $time_remaining = 0;
            }
        }
        
        // Get bidder data
        $bidder_data = null;
        
        if ($current_bidder) {
            $user = get_user_by('id', $current_bidder);
            
            if ($user) {
                $bidder_data = array(
                    'id' => $user->ID,
                    'name' => $user->display_name,
                    'avatar' => get_avatar_url($user->ID),
                );
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'auction_id' => $auction_id,
            'status' => $status,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'current_bid' => $current_bid ? floatval($current_bid) : 0,
            'current_bidder' => $bidder_data,
            'reserve_price' => $reserve_price ? floatval($reserve_price) : 0,
            'reserve_met' => $reserve_met,
            'time_remaining' => $time_remaining,
            'bid_count' => $this->get_auction_bid_count($auction_id),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Prepare auction data for API response.
     *
     * @param WP_Post $auction Auction post object.
     * @param bool $single Whether this is a single auction request.
     * @return array
     */
    protected function prepare_auction_data($auction, $single = false) {
        // Get auction meta
        $start_date = get_post_meta($auction->ID, '_auction_start_date', true);
        $end_date = get_post_meta($auction->ID, '_auction_end_date', true);
        $status = get_post_meta($auction->ID, '_auction_status', true);
        $starting_bid = get_post_meta($auction->ID, '_auction_starting_bid', true);
        $current_bid = get_post_meta($auction->ID, '_auction_current_bid', true);
        $reserve_price = get_post_meta($auction->ID, '_auction_reserve_price', true);
        $bid_increment = get_post_meta($auction->ID, '_auction_bid_increment', true);
        $featured = get_post_meta($auction->ID, '_auction_featured', true);
        $current_bidder = get_post_meta($auction->ID, '_auction_current_bidder', true);
        
        // Get time remaining
        $time_remaining = 0;
        
        if ($status === 'active' && $end_date) {
            $time_remaining = strtotime($end_date) - time();
            
            if ($time_remaining < 0) {
                $time_remaining = 0;
            }
        }
        
        // Get bidder data
        $bidder_data = null;
        
        if ($current_bidder) {
            $user = get_user_by('id', $current_bidder);
            
            if ($user) {
                $bidder_data = array(
                    'id' => $user->ID,
                    'name' => $user->display_name,
                    'avatar' => get_avatar_url($user->ID),
                );
            }
        }
        
        // Get images
        $images = array();
        
        // Featured image
        $thumbnail_id = get_post_thumbnail_id($auction->ID);
        
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
        $gallery_ids = get_post_meta($auction->ID, '_auction_gallery', true);
        
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
        $terms = wp_get_post_terms($auction->ID, 'auction_category');
        
        foreach ($terms as $term) {
            $categories[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }
        
        // Prepare data
        $data = array(
            'id' => $auction->ID,
            'title' => $auction->post_title,
            'slug' => $auction->post_name,
            'permalink' => get_permalink($auction->ID),
            'date_created' => $auction->post_date,
            'date_modified' => $auction->post_modified,
            'status' => $status,
            'description' => $single ? $auction->post_content : $auction->post_excerpt,
            'short_description' => $auction->post_excerpt,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'time_remaining' => $time_remaining,
            'starting_bid' => $starting_bid ? floatval($starting_bid) : 0,
            'current_bid' => $current_bid ? floatval($current_bid) : 0,
            'reserve_price' => $reserve_price ? floatval($reserve_price) : 0,
            'reserve_met' => $current_bid >= $reserve_price,
            'bid_increment' => $bid_increment ? floatval($bid_increment) : 0,
            'featured' => $featured === '1',
            'current_bidder' => $bidder_data,
            'bid_count' => $this->get_auction_bid_count($auction->ID),
            'images' => $images,
            'categories' => $categories,
        );
        
        // Add bids for single auction request
        if ($single) {
            $data['bids'] = $this->get_auction_bid_history($auction->ID, 10, 0);
        }
        
        return $data;
    }

    /**
     * Get auction bid history.
     *
     * @param int $auction_id Auction ID.
     * @param int $limit Number of bids to get.
     * @param int $offset Offset.
     * @return array
     */
    protected function get_auction_bid_history($auction_id, $limit = 10, $offset = 0) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'auction_bids';
        
        $bids = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE auction_id = %d ORDER BY date_created DESC LIMIT %d OFFSET %d",
                $auction_id,
                $limit,
                $offset
            )
        );
        
        $data = array();
        
        foreach ($bids as $bid) {
            $user = get_user_by('id', $bid->user_id);
            
            $data[] = array(
                'id' => $bid->id,
                'user' => array(
                    'id' => $user->ID,
                    'name' => $user->display_name,
                    'avatar' => get_avatar_url($user->ID),
                ),
                'amount' => floatval($bid->amount),
                'date' => $bid->date_created,
            );
        }
        
        return $data;
    }

    /**
     * Get auction bid count.
     *
     * @param int $auction_id Auction ID.
     * @return int
     */
    protected function get_auction_bid_count($auction_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'auction_bids';
        
        $count = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE auction_id = %d",
                $auction_id
            )
        );
        
        return intval($count);
    }

    /**
     * Add auction bid.
     *
     * @param int $auction_id Auction ID.
     * @param int $user_id User ID.
     * @param float $amount Bid amount.
     * @return int|false
     */
    protected function add_auction_bid($auction_id, $user_id, $amount) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'auction_bids';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'auction_id' => $auction_id,
                'user_id' => $user_id,
                'amount' => $amount,
                'date_created' => current_time('mysql'),
            ),
            array(
                '%d',
                '%d',
                '%f',
                '%s',
            )
        );
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        return false;
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