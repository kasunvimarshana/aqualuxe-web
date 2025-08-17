<?php
/**
 * AquaLuxe API Care Guides Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Care_Guides_Controller Class
 *
 * Handles API requests for care guides
 */
class AquaLuxe_API_Care_Guides_Controller extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'care-guides';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Get all care guides
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_items'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get single care guide
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_item'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Unique identifier for the care guide.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get care guide categories
        register_rest_route($this->namespace, '/' . $this->rest_base . '/categories', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_categories'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'parent' => array(
                        'description' => __('Limit result set to categories that are direct children of a parent category.'),
                        'type' => 'integer',
                        'sanitize_callback' => 'absint',
                    ),
                    'hide_empty' => array(
                        'description' => __('Whether to hide categories that don\'t have any care guides.'),
                        'type' => 'boolean',
                        'default' => false,
                    ),
                ),
            ),
        ));

        // Get care guides by category
        register_rest_route($this->namespace, '/' . $this->rest_base . '/category/(?P<category_id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_guides_by_category'),
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

        // Get care guide species
        register_rest_route($this->namespace, '/' . $this->rest_base . '/species', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_species'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'hide_empty' => array(
                        'description' => __('Whether to hide species that don\'t have any care guides.'),
                        'type' => 'boolean',
                        'default' => false,
                    ),
                ),
            ),
        ));

        // Get care guides by species
        register_rest_route($this->namespace, '/' . $this->rest_base . '/species/(?P<species_id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_guides_by_species'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array_merge(
                    array(
                        'species_id' => array(
                            'description' => __('Unique identifier for the species.'),
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

        // Search care guides
        register_rest_route($this->namespace, '/' . $this->rest_base . '/search', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'search_guides'),
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

        // Get featured care guides
        register_rest_route($this->namespace, '/' . $this->rest_base . '/featured', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_featured_guides'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => $this->get_collection_params(),
            ),
        ));

        // Get related care guides
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/related', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_related_guides'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array_merge(
                    array(
                        'id' => array(
                            'description' => __('Unique identifier for the care guide.'),
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
    }

    /**
     * Get all care guides.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_items($request) {
        $start_time = microtime(true);
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'menu_order'));
        
        // Get search param
        $search = $this->get_search_param($request);
        
        // Get filter params
        $category = $request->get_param('category');
        $species = $request->get_param('species');
        $difficulty = $request->get_param('difficulty');
        
        // Query args
        $args = array(
            'post_type' => 'care_guide',
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
        if ($category) {
            $args['tax_query'][] = array(
                'taxonomy' => 'care_guide_category',
                'field' => 'term_id',
                'terms' => $category,
            );
        }
        
        // Add species filter
        if ($species) {
            $args['tax_query'][] = array(
                'taxonomy' => 'fish_species',
                'field' => 'term_id',
                'terms' => $species,
            );
        }
        
        // Add difficulty filter
        if ($difficulty) {
            $args['meta_query'][] = array(
                'key' => '_care_guide_difficulty',
                'value' => $difficulty,
                'compare' => '=',
            );
        }
        
        // Get care guides
        $query = new WP_Query($args);
        $guides = array();
        
        foreach ($query->posts as $post) {
            $guides[] = $this->prepare_guide_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'guides' => $guides,
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
     * Get a single care guide.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_item($request) {
        $start_time = microtime(true);
        
        $guide_id = $request->get_param('id');
        $post = get_post($guide_id);
        
        if (!$post || $post->post_type !== 'care_guide' || $post->post_status !== 'publish') {
            return $this->format_error('guide_not_found', __('Care guide not found.'), 404);
        }
        
        $data = $this->prepare_guide_data($post, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get care guide categories.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_categories($request) {
        $start_time = microtime(true);
        
        $parent = $request->get_param('parent');
        $hide_empty = $request->get_param('hide_empty');
        
        // Get categories
        $args = array(
            'taxonomy' => 'care_guide_category',
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
                'image' => $this->get_term_image($category->term_id, 'care_guide_category'),
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
     * Get care guides by category.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_guides_by_category($request) {
        $start_time = microtime(true);
        
        $category_id = $request->get_param('category_id');
        
        // Check if category exists
        $category = get_term($category_id, 'care_guide_category');
        
        if (!$category || is_wp_error($category)) {
            return $this->format_error('category_not_found', __('Category not found.'), 404);
        }
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'menu_order'));
        
        // Query args
        $args = array(
            'post_type' => 'care_guide',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'tax_query' => array(
                array(
                    'taxonomy' => 'care_guide_category',
                    'field' => 'term_id',
                    'terms' => $category_id,
                ),
            ),
        );
        
        // Get care guides
        $query = new WP_Query($args);
        $guides = array();
        
        foreach ($query->posts as $post) {
            $guides[] = $this->prepare_guide_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'category' => array(
                'id' => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $this->get_term_image($category->term_id, 'care_guide_category'),
            ),
            'guides' => $guides,
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
     * Get care guide species.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_species($request) {
        $start_time = microtime(true);
        
        $hide_empty = $request->get_param('hide_empty');
        
        // Get species
        $args = array(
            'taxonomy' => 'fish_species',
            'hide_empty' => $hide_empty,
        );
        
        $species = get_terms($args);
        
        if (is_wp_error($species)) {
            return $this->format_error('species_error', $species->get_error_message(), 500);
        }
        
        $data = array();
        
        foreach ($species as $term) {
            $data[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
                'description' => $term->description,
                'count' => $term->count,
                'image' => $this->get_term_image($term->term_id, 'fish_species'),
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'species' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get care guides by species.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_guides_by_species($request) {
        $start_time = microtime(true);
        
        $species_id = $request->get_param('species_id');
        
        // Check if species exists
        $species = get_term($species_id, 'fish_species');
        
        if (!$species || is_wp_error($species)) {
            return $this->format_error('species_not_found', __('Species not found.'), 404);
        }
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'menu_order'));
        
        // Query args
        $args = array(
            'post_type' => 'care_guide',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'tax_query' => array(
                array(
                    'taxonomy' => 'fish_species',
                    'field' => 'term_id',
                    'terms' => $species_id,
                ),
            ),
        );
        
        // Get care guides
        $query = new WP_Query($args);
        $guides = array();
        
        foreach ($query->posts as $post) {
            $guides[] = $this->prepare_guide_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'species' => array(
                'id' => $species->term_id,
                'name' => $species->name,
                'slug' => $species->slug,
                'description' => $species->description,
                'image' => $this->get_term_image($species->term_id, 'fish_species'),
            ),
            'guides' => $guides,
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
     * Search care guides.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function search_guides($request) {
        $start_time = microtime(true);
        
        $query = $request->get_param('query');
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Query args
        $args = array(
            'post_type' => 'care_guide',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            's' => $query,
        );
        
        // Get care guides
        $query_obj = new WP_Query($args);
        $guides = array();
        
        foreach ($query_obj->posts as $post) {
            $guides[] = $this->prepare_guide_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'query' => $query,
            'guides' => $guides,
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
     * Get featured care guides.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_featured_guides($request) {
        $start_time = microtime(true);
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get sort params
        $sort = $this->get_sort_params($request, array('date', 'title', 'menu_order'));
        
        // Query args
        $args = array(
            'post_type' => 'care_guide',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'offset' => $pagination['offset'],
            'orderby' => $sort['orderby'],
            'order' => $sort['order'],
            'meta_query' => array(
                array(
                    'key' => '_care_guide_featured',
                    'value' => '1',
                    'compare' => '=',
                ),
            ),
        );
        
        // Get care guides
        $query = new WP_Query($args);
        $guides = array();
        
        foreach ($query->posts as $post) {
            $guides[] = $this->prepare_guide_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'guides' => $guides,
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
     * Get related care guides.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_related_guides($request) {
        $start_time = microtime(true);
        
        $guide_id = $request->get_param('id');
        $post = get_post($guide_id);
        
        if (!$post || $post->post_type !== 'care_guide' || $post->post_status !== 'publish') {
            return $this->format_error('guide_not_found', __('Care guide not found.'), 404);
        }
        
        // Get pagination params
        $pagination = $this->get_pagination_params($request);
        
        // Get categories
        $categories = wp_get_post_terms($guide_id, 'care_guide_category', array('fields' => 'ids'));
        
        // Get species
        $species = wp_get_post_terms($guide_id, 'fish_species', array('fields' => 'ids'));
        
        // Query args
        $args = array(
            'post_type' => 'care_guide',
            'post_status' => 'publish',
            'posts_per_page' => $pagination['per_page'],
            'post__not_in' => array($guide_id),
            'orderby' => 'rand',
        );
        
        // Add taxonomy query
        if (!empty($categories) || !empty($species)) {
            $args['tax_query'] = array('relation' => 'OR');
            
            if (!empty($categories)) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'care_guide_category',
                    'field' => 'term_id',
                    'terms' => $categories,
                );
            }
            
            if (!empty($species)) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'fish_species',
                    'field' => 'term_id',
                    'terms' => $species,
                );
            }
        }
        
        // Get care guides
        $query = new WP_Query($args);
        $guides = array();
        
        foreach ($query->posts as $post) {
            $guides[] = $this->prepare_guide_data($post);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'guides' => $guides,
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
     * Prepare care guide data for API response.
     *
     * @param WP_Post $post Post object.
     * @param bool $single Whether this is a single guide request.
     * @return array
     */
    protected function prepare_guide_data($post, $single = false) {
        $data = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'date' => get_the_date('c', $post),
            'modified' => get_the_modified_date('c', $post),
            'excerpt' => get_the_excerpt($post),
            'featured_image' => get_the_post_thumbnail_url($post, 'large'),
            'permalink' => get_permalink($post),
            'difficulty' => get_post_meta($post->ID, '_care_guide_difficulty', true),
            'featured' => (bool) get_post_meta($post->ID, '_care_guide_featured', true),
            'categories' => $this->get_guide_terms($post->ID, 'care_guide_category'),
            'species' => $this->get_guide_terms($post->ID, 'fish_species'),
        );
        
        // Add full content for single guide
        if ($single) {
            $data['content'] = apply_filters('the_content', $post->post_content);
            
            // Add sections
            $sections = get_post_meta($post->ID, '_care_guide_sections', true);
            
            if ($sections) {
                $data['sections'] = $sections;
            }
            
            // Add water parameters
            $water_params = get_post_meta($post->ID, '_care_guide_water_parameters', true);
            
            if ($water_params) {
                $data['water_parameters'] = $water_params;
            }
            
            // Add tank setup
            $tank_setup = get_post_meta($post->ID, '_care_guide_tank_setup', true);
            
            if ($tank_setup) {
                $data['tank_setup'] = $tank_setup;
            }
            
            // Add feeding info
            $feeding = get_post_meta($post->ID, '_care_guide_feeding', true);
            
            if ($feeding) {
                $data['feeding'] = $feeding;
            }
            
            // Add compatibility info
            $compatibility = get_post_meta($post->ID, '_care_guide_compatibility', true);
            
            if ($compatibility) {
                $data['compatibility'] = $compatibility;
            }
            
            // Add breeding info
            $breeding = get_post_meta($post->ID, '_care_guide_breeding', true);
            
            if ($breeding) {
                $data['breeding'] = $breeding;
            }
            
            // Add common diseases
            $diseases = get_post_meta($post->ID, '_care_guide_diseases', true);
            
            if ($diseases) {
                $data['diseases'] = $diseases;
            }
            
            // Add gallery
            $gallery = get_post_meta($post->ID, '_care_guide_gallery', true);
            
            if ($gallery) {
                $data['gallery'] = $gallery;
            }
            
            // Add video
            $video = get_post_meta($post->ID, '_care_guide_video', true);
            
            if ($video) {
                $data['video'] = $video;
            }
            
            // Add related products
            $related_products = get_post_meta($post->ID, '_care_guide_related_products', true);
            
            if ($related_products) {
                $products = array();
                
                foreach ($related_products as $product_id) {
                    $product = wc_get_product($product_id);
                    
                    if ($product && $product->is_visible()) {
                        $products[] = array(
                            'id' => $product->get_id(),
                            'name' => $product->get_name(),
                            'price' => $product->get_price(),
                            'regular_price' => $product->get_regular_price(),
                            'sale_price' => $product->get_sale_price(),
                            'on_sale' => $product->is_on_sale(),
                            'permalink' => get_permalink($product->get_id()),
                            'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                        );
                    }
                }
                
                $data['related_products'] = $products;
            }
        }
        
        return $data;
    }

    /**
     * Get care guide terms.
     *
     * @param int $post_id Post ID.
     * @param string $taxonomy Taxonomy name.
     * @return array
     */
    protected function get_guide_terms($post_id, $taxonomy) {
        $terms = wp_get_post_terms($post_id, $taxonomy);
        $data = array();
        
        foreach ($terms as $term) {
            $data[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
                'image' => $this->get_term_image($term->term_id, $taxonomy),
            );
        }
        
        return $data;
    }

    /**
     * Get term image.
     *
     * @param int $term_id Term ID.
     * @param string $taxonomy Taxonomy name.
     * @return string|null
     */
    protected function get_term_image($term_id, $taxonomy) {
        $image_id = get_term_meta($term_id, '_term_image', true);
        
        if ($image_id) {
            return wp_get_attachment_image_url($image_id, 'medium');
        }
        
        return null;
    }
}