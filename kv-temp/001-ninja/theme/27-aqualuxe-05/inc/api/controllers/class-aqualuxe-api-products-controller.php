<?php
/**
 * AquaLuxe API Products Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Products_Controller Class
 *
 * Handles API requests for products
 */
class AquaLuxe_API_Products_Controller extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'products';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Get all products
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get single product
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_item'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the product.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get products by category
        register_rest_route($this->namespace, '/' . $this->rest_base . '/category/(?P<category_id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_products_by_category'),
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

        // Get featured products
        register_rest_route($this->namespace, '/' . $this->rest_base . '/featured', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_featured_products'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get on sale products
        register_rest_route($this->namespace, '/' . $this->rest_base . '/on-sale', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_on_sale_products'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get product categories
        register_rest_route($this->namespace, '/' . $this->rest_base . '/categories', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_product_categories'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'parent' => array(
                        'description' => __('Limit result set to categories that are direct children of a parent category.'),
                        'type' => 'integer',
                        'sanitize_callback' => 'absint',
                    ),
                    'hide_empty' => array(
                        'description' => __('Whether to hide categories that don\'t have any products.'),
                        'type' => 'boolean',
                        'default' => false,
                    ),
                ),
            ),
        ));

        // Search products
        register_rest_route($this->namespace, '/' . $this->rest_base . '/search', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'search_products'),
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

        // Get related products
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/related', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_related_products'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array_merge(
                    array(
                        'id' => array(
                            'description' => __('Unique identifier for the product.'),
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

        // Get product reviews
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/reviews', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_product_reviews'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array_merge(
                    array(
                        'id' => array(
                            'description' => __('Unique identifier for the product.'),
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

        // Add product review
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/reviews', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_product_review'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the product.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'rating' => array(
                        'description' => __('Review rating (1-5).'),
                        'type' => 'integer',
                        'required' => true,
                        'minimum' => 1,
                        'maximum' => 5,
                        'sanitize_callback' => 'absint',
                    ),
                    'review' => array(
                        'description' => __('Review content.'),
                        'type' => 'string',
                        'required' => true,
                        'sanitize_callback' => 'sanitize_textarea_field',
                    ),
                    'title' => array(
                        'description' => __('Review title.'),
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                    ),
                ),
            ),
        ));
    }

    /**
     * Get all products.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_items($request) {
        $start_time = microtime(true);

        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'price', 'popularity', 'rating'));
        
        // Get search param
        $search = $this->get_search_param($request);
        
        // Get filter params
        $filters = $this->get_filter_params($request, array('category', 'tag', 'attribute', 'min_price', 'max_price'));
        
        // Query args
        $args = array(
            'post_type' => 'product',
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
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $filters['category'],
            );
        }
        
        // Add tag filter
        if (isset($filters['tag'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'field' => 'term_id',
                'terms' => $filters['tag'],
            );
        }
        
        // Add price filter
        if (isset($filters['min_price']) || isset($filters['max_price'])) {
            $args['meta_query'] = array('relation' => 'AND');
            
            if (isset($filters['min_price'])) {
                $args['meta_query'][] = array(
                    'key' => '_price',
                    'value' => $filters['min_price'],
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                );
            }
            
            if (isset($filters['max_price'])) {
                $args['meta_query'][] = array(
                    'key' => '_price',
                    'value' => $filters['max_price'],
                    'compare' => '<=',
                    'type' => 'NUMERIC',
                );
            }
        }
        
        // Get products
        $query = new WP_Query($args);
        $products = array();
        
        foreach ($query->posts as $post) {
            $product = wc_get_product($post);
            $products[] = $this->prepare_product_data($product);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'products' => $products,
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
     * Get a single product.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request) {
        $start_time = microtime(true);
        
        $product_id = $request->get_param('id');
        $product = wc_get_product($product_id);
        
        if (!$product || $product->get_status() !== 'publish') {
            return $this->format_error('product_not_found', __('Product not found.'), 404);
        }
        
        $data = $this->prepare_product_data($product, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get products by category.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_products_by_category($request) {
        $start_time = microtime(true);
        
        $category_id = $request->get_param('category_id');
        
        // Check if category exists
        $category = get_term($category_id, 'product_cat');
        
        if (!$category || is_wp_error($category)) {
            return $this->format_error('category_not_found', __('Category not found.'), 404);
        }
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'price', 'popularity', 'rating'));
        
        // Query args
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category_id,
                ),
            ),
        );
        
        // Get products
        $query = new WP_Query($args);
        $products = array();
        
        foreach ($query->posts as $post) {
            $product = wc_get_product($post);
            $products[] = $this->prepare_product_data($product);
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
            'products' => $products,
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
     * Get featured products.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_featured_products($request) {
        $start_time = microtime(true);
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'price', 'popularity', 'rating'));
        
        // Query args
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                ),
            ),
        );
        
        // Get products
        $query = new WP_Query($args);
        $products = array();
        
        foreach ($query->posts as $post) {
            $product = wc_get_product($post);
            $products[] = $this->prepare_product_data($product);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'products' => $products,
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
     * Get on sale products.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_on_sale_products($request) {
        $start_time = microtime(true);
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'price', 'popularity', 'rating'));
        
        // Get on sale product IDs
        $product_ids = wc_get_product_ids_on_sale();
        
        if (empty($product_ids)) {
            return $this->format_response(array(
                'products' => array(),
                'total' => 0,
                'pages' => 0,
            ));
        }
        
        // Query args
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'post__in' => $product_ids,
        );
        
        // Get products
        $query = new WP_Query($args);
        $products = array();
        
        foreach ($query->posts as $post) {
            $product = wc_get_product($post);
            $products[] = $this->prepare_product_data($product);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'products' => $products,
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
     * Get product categories.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_product_categories($request) {
        $start_time = microtime(true);
        
        $parent = $request->get_param('parent');
        $hide_empty = $request->get_param('hide_empty');
        
        // Get categories
        $args = array(
            'taxonomy' => 'product_cat',
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
     * Search products.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function search_products($request) {
        $start_time = microtime(true);
        
        $query = $request->get_param('query');
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Query args
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            's' => $query,
        );
        
        // Get products
        $query_obj = new WP_Query($args);
        $products = array();
        
        foreach ($query_obj->posts as $post) {
            $product = wc_get_product($post);
            $products[] = $this->prepare_product_data($product);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'query' => $query,
            'products' => $products,
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
     * Get related products.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_related_products($request) {
        $start_time = microtime(true);
        
        $product_id = $request->get_param('id');
        $product = wc_get_product($product_id);
        
        if (!$product || $product->get_status() !== 'publish') {
            return $this->format_error('product_not_found', __('Product not found.'), 404);
        }
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get related products
        $related_ids = wc_get_related_products($product_id, $pagination['per_page']);
        
        if (empty($related_ids)) {
            return $this->format_response(array(
                'products' => array(),
                'total' => 0,
                'pages' => 0,
            ));
        }
        
        $products = array();
        
        foreach ($related_ids as $related_id) {
            $related_product = wc_get_product($related_id);
            
            if ($related_product && $related_product->get_status() === 'publish') {
                $products[] = $this->prepare_product_data($related_product);
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'products' => $products,
            'total' => count($products),
            'pages' => 1,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get product reviews.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_product_reviews($request) {
        $start_time = microtime(true);
        
        $product_id = $request->get_param('id');
        $product = wc_get_product($product_id);
        
        if (!$product || $product->get_status() !== 'publish') {
            return $this->format_error('product_not_found', __('Product not found.'), 404);
        }
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get reviews
        $args = array(
            'post_id' => $product_id,
            'status' => 'approve',
            'type' => 'review',
            'number' => $pagination['per_page'],
            'offset' => $pagination['offset'],
        );
        
        $comments_query = new WP_Comment_Query();
        $comments = $comments_query->query($args);
        
        $reviews = array();
        
        foreach ($comments as $comment) {
            $reviews[] = array(
                'id' => $comment->comment_ID,
                'date' => $comment->comment_date,
                'rating' => get_comment_meta($comment->comment_ID, 'rating', true),
                'name' => $comment->comment_author,
                'email' => $comment->comment_author_email,
                'content' => $comment->comment_content,
                'verified' => wc_review_is_from_verified_owner($comment->comment_ID),
            );
        }
        
        // Get total reviews count
        $args['count'] = true;
        $args['offset'] = 0;
        $args['number'] = 0;
        $total = $comments_query->query($args);
        
        // Prepare response
        $response = $this->format_response(array(
            'reviews' => $reviews,
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
     * Create product review.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_product_review($request) {
        $start_time = microtime(true);
        
        $product_id = $request->get_param('id');
        $product = wc_get_product($product_id);
        
        if (!$product || $product->get_status() !== 'publish') {
            return $this->format_error('product_not_found', __('Product not found.'), 404);
        }
        
        $user_id = $this->get_current_user_id();
        $user = get_user_by('id', $user_id);
        
        if (!$user) {
            return $this->format_error('user_not_found', __('User not found.'), 404);
        }
        
        $rating = $request->get_param('rating');
        $review = $request->get_param('review');
        $title = $request->get_param('title');
        
        // Check if user has already reviewed this product
        $args = array(
            'post_id' => $product_id,
            'user_id' => $user_id,
            'count' => true,
        );
        
        $comments_query = new WP_Comment_Query();
        $count = $comments_query->query($args);
        
        if ($count > 0) {
            return $this->format_error('review_exists', __('You have already reviewed this product.'), 400);
        }
        
        // Create review
        $comment_data = array(
            'comment_post_ID' => $product_id,
            'comment_author' => $user->display_name,
            'comment_author_email' => $user->user_email,
            'comment_author_url' => '',
            'comment_content' => $review,
            'comment_type' => 'review',
            'comment_parent' => 0,
            'user_id' => $user_id,
            'comment_approved' => 1,
        );
        
        if ($title) {
            $comment_data['comment_title'] = $title;
        }
        
        $comment_id = wp_insert_comment($comment_data);
        
        if (!$comment_id) {
            return $this->format_error('review_failed', __('Failed to create review.'), 500);
        }
        
        // Add rating
        add_comment_meta($comment_id, 'rating', $rating);
        
        // Maybe set as verified
        if (wc_customer_bought_product($user->user_email, $user_id, $product_id)) {
            add_comment_meta($comment_id, 'verified', 1);
        }
        
        // Update product rating
        WC_Comments::clear_transients($product_id);
        
        // Get the new review
        $comment = get_comment($comment_id);
        
        $review_data = array(
            'id' => $comment->comment_ID,
            'date' => $comment->comment_date,
            'rating' => get_comment_meta($comment->comment_ID, 'rating', true),
            'name' => $comment->comment_author,
            'email' => $comment->comment_author_email,
            'content' => $comment->comment_content,
            'verified' => wc_review_is_from_verified_owner($comment->comment_ID),
        );
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'review' => $review_data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Prepare product data for API response.
     *
     * @param WC_Product $product Product object.
     * @param bool $single Whether this is a single product request.
     * @return array
     */
    protected function prepare_product_data($product, $single = false) {
        $data = array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'slug' => $product->get_slug(),
            'permalink' => get_permalink($product->get_id()),
            'date_created' => wc_rest_prepare_date_response($product->get_date_created()),
            'date_modified' => wc_rest_prepare_date_response($product->get_date_modified()),
            'type' => $product->get_type(),
            'status' => $product->get_status(),
            'featured' => $product->is_featured(),
            'catalog_visibility' => $product->get_catalog_visibility(),
            'description' => $single ? $product->get_description() : $product->get_short_description(),
            'short_description' => $product->get_short_description(),
            'sku' => $product->get_sku(),
            'price' => $product->get_price(),
            'regular_price' => $product->get_regular_price(),
            'sale_price' => $product->get_sale_price(),
            'date_on_sale_from' => wc_rest_prepare_date_response($product->get_date_on_sale_from()),
            'date_on_sale_to' => wc_rest_prepare_date_response($product->get_date_on_sale_to()),
            'on_sale' => $product->is_on_sale(),
            'purchasable' => $product->is_purchasable(),
            'total_sales' => $product->get_total_sales(),
            'virtual' => $product->is_virtual(),
            'downloadable' => $product->is_downloadable(),
            'downloads' => $this->get_downloads($product),
            'download_limit' => $product->get_download_limit(),
            'download_expiry' => $product->get_download_expiry(),
            'tax_status' => $product->get_tax_status(),
            'tax_class' => $product->get_tax_class(),
            'manage_stock' => $product->managing_stock(),
            'stock_quantity' => $product->get_stock_quantity(),
            'stock_status' => $product->get_stock_status(),
            'backorders' => $product->get_backorders(),
            'backorders_allowed' => $product->backorders_allowed(),
            'backordered' => $product->is_on_backorder(),
            'sold_individually' => $product->is_sold_individually(),
            'weight' => $product->get_weight(),
            'dimensions' => array(
                'length' => $product->get_length(),
                'width' => $product->get_width(),
                'height' => $product->get_height(),
            ),
            'shipping_required' => $product->needs_shipping(),
            'shipping_taxable' => $product->is_shipping_taxable(),
            'shipping_class' => $product->get_shipping_class(),
            'shipping_class_id' => $product->get_shipping_class_id(),
            'reviews_allowed' => $product->get_reviews_allowed(),
            'average_rating' => $product->get_average_rating(),
            'rating_count' => $product->get_rating_count(),
            'related_ids' => $single ? $product->get_related() : array(),
            'upsell_ids' => $single ? $product->get_upsell_ids() : array(),
            'cross_sell_ids' => $single ? $product->get_cross_sell_ids() : array(),
            'parent_id' => $product->get_parent_id(),
            'purchase_note' => $single ? $product->get_purchase_note() : '',
            'categories' => $this->get_taxonomy_terms($product, 'product_cat'),
            'tags' => $this->get_taxonomy_terms($product, 'product_tag'),
            'images' => $this->get_images($product),
            'attributes' => $this->get_attributes($product),
            'default_attributes' => $this->get_default_attributes($product),
            'variations' => $single && $product->is_type('variable') ? $this->get_variations($product) : array(),
            'grouped_products' => $single && $product->is_type('grouped') ? $product->get_children() : array(),
            'menu_order' => $product->get_menu_order(),
        );
        
        return $data;
    }

    /**
     * Get product downloads.
     *
     * @param WC_Product $product Product object.
     * @return array
     */
    protected function get_downloads($product) {
        $downloads = array();
        
        if ($product->is_downloadable()) {
            foreach ($product->get_downloads() as $file_id => $file) {
                $downloads[] = array(
                    'id' => $file_id,
                    'name' => $file['name'],
                    'file' => $file['file'],
                );
            }
        }
        
        return $downloads;
    }

    /**
     * Get product taxonomy terms.
     *
     * @param WC_Product $product Product object.
     * @param string $taxonomy Taxonomy name.
     * @return array
     */
    protected function get_taxonomy_terms($product, $taxonomy) {
        $terms = array();
        
        foreach (wc_get_object_terms($product->get_id(), $taxonomy) as $term) {
            $terms[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            );
        }
        
        return $terms;
    }

    /**
     * Get product images.
     *
     * @param WC_Product $product Product object.
     * @return array
     */
    protected function get_images($product) {
        $images = array();
        $attachment_ids = array();
        
        // Add featured image
        if ($product->get_image_id()) {
            $attachment_ids[] = $product->get_image_id();
        }
        
        // Add gallery images
        $attachment_ids = array_merge($attachment_ids, $product->get_gallery_image_ids());
        
        // Build image data
        foreach ($attachment_ids as $attachment_id) {
            $attachment = wp_get_attachment_image_src($attachment_id, 'full');
            
            if (!is_array($attachment)) {
                continue;
            }
            
            $images[] = array(
                'id' => (int) $attachment_id,
                'src' => current($attachment),
                'name' => get_the_title($attachment_id),
                'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true),
            );
        }
        
        return $images;
    }

    /**
     * Get product attributes.
     *
     * @param WC_Product $product Product object.
     * @return array
     */
    protected function get_attributes($product) {
        $attributes = array();
        
        foreach ($product->get_attributes() as $attribute) {
            $attribute_data = array(
                'id' => $attribute['is_taxonomy'] ? wc_attribute_taxonomy_id_by_name($attribute['name']) : 0,
                'name' => $attribute['name'],
                'position' => $attribute['position'],
                'visible' => (bool) $attribute['is_visible'],
                'variation' => (bool) $attribute['is_variation'],
                'options' => array(),
            );
            
            if ($attribute['is_taxonomy']) {
                $terms = wc_get_object_terms($product->get_id(), $attribute['name']);
                foreach ($terms as $term) {
                    $attribute_data['options'][] = array(
                        'id' => $term->term_id,
                        'name' => $term->name,
                        'slug' => $term->slug,
                    );
                }
            } else {
                $attribute_data['options'] = $attribute['value'];
            }
            
            $attributes[] = $attribute_data;
        }
        
        return $attributes;
    }

    /**
     * Get product default attributes.
     *
     * @param WC_Product $product Product object.
     * @return array
     */
    protected function get_default_attributes($product) {
        $default_attributes = array();
        
        if ($product->is_type('variable')) {
            foreach ($product->get_default_attributes() as $attribute_name => $attribute_value) {
                $default_attributes[] = array(
                    'name' => $attribute_name,
                    'option' => $attribute_value,
                );
            }
        }
        
        return $default_attributes;
    }

    /**
     * Get product variations.
     *
     * @param WC_Product_Variable $product Product object.
     * @return array
     */
    protected function get_variations($product) {
        $variations = array();
        
        foreach ($product->get_children() as $child_id) {
            $variation = wc_get_product($child_id);
            
            if (!$variation || !$variation->exists()) {
                continue;
            }
            
            $variation_data = array(
                'id' => $variation->get_id(),
                'sku' => $variation->get_sku(),
                'price' => $variation->get_price(),
                'regular_price' => $variation->get_regular_price(),
                'sale_price' => $variation->get_sale_price(),
                'on_sale' => $variation->is_on_sale(),
                'purchasable' => $variation->is_purchasable(),
                'visible' => $variation->is_visible(),
                'virtual' => $variation->is_virtual(),
                'downloadable' => $variation->is_downloadable(),
                'downloads' => $this->get_downloads($variation),
                'download_limit' => $variation->get_download_limit(),
                'download_expiry' => $variation->get_download_expiry(),
                'tax_status' => $variation->get_tax_status(),
                'tax_class' => $variation->get_tax_class(),
                'manage_stock' => $variation->managing_stock(),
                'stock_quantity' => $variation->get_stock_quantity(),
                'stock_status' => $variation->get_stock_status(),
                'backorders' => $variation->get_backorders(),
                'backorders_allowed' => $variation->backorders_allowed(),
                'backordered' => $variation->is_on_backorder(),
                'weight' => $variation->get_weight(),
                'dimensions' => array(
                    'length' => $variation->get_length(),
                    'width' => $variation->get_width(),
                    'height' => $variation->get_height(),
                ),
                'shipping_class' => $variation->get_shipping_class(),
                'shipping_class_id' => $variation->get_shipping_class_id(),
                'image' => $this->get_images($variation),
                'attributes' => array(),
            );
            
            // Get variation attributes
            foreach ($variation->get_variation_attributes() as $attribute_name => $attribute_value) {
                $variation_data['attributes'][] = array(
                    'name' => str_replace('attribute_', '', $attribute_name),
                    'option' => $attribute_value,
                );
            }
            
            $variations[] = $variation_data;
        }
        
        return $variations;
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