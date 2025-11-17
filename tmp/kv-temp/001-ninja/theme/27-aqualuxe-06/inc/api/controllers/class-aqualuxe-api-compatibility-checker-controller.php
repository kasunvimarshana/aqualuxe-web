<?php
/**
 * AquaLuxe API Compatibility Checker Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Compatibility_Checker_Controller Class
 *
 * Handles API requests for fish compatibility checker
 */
class AquaLuxe_API_Compatibility_Checker_Controller extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'compatibility-checker';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Check fish compatibility
        register_rest_route($this->namespace, '/' . $this->rest_base . '/check', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'check_compatibility'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'fish' => array(
                        'description' => __('List of fish species IDs or slugs to check compatibility.'),
                        'type' => 'array',
                        'required' => true,
                        'items' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
            ),
        ));

        // Get compatibility matrix
        register_rest_route($this->namespace, '/' . $this->rest_base . '/matrix', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'get_compatibility_matrix'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'fish' => array(
                        'description' => __('List of fish species IDs or slugs to include in the matrix.'),
                        'type' => 'array',
                        'required' => true,
                        'items' => array(
                            'type' => 'string',
                        ),
                    ),
                ),
            ),
        ));

        // Get fish species
        register_rest_route($this->namespace, '/' . $this->rest_base . '/species', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_species'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'search' => array(
                        'description' => __('Search term for fish species.'),
                        'type' => 'string',
                    ),
                    'water_type' => array(
                        'description' => __('Filter by water type (freshwater, saltwater, brackish).'),
                        'type' => 'string',
                        'enum' => array('freshwater', 'saltwater', 'brackish'),
                    ),
                    'limit' => array(
                        'description' => __('Number of results to return.'),
                        'type' => 'integer',
                        'default' => 50,
                        'minimum' => 1,
                        'maximum' => 100,
                    ),
                ),
            ),
        ));

        // Get fish species by ID
        register_rest_route($this->namespace, '/' . $this->rest_base . '/species/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_species_by_id'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Fish species ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get compatibility details between two species
        register_rest_route($this->namespace, '/' . $this->rest_base . '/details', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'get_compatibility_details'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'species1' => array(
                        'description' => __('First fish species ID or slug.'),
                        'type' => 'string',
                        'required' => true,
                    ),
                    'species2' => array(
                        'description' => __('Second fish species ID or slug.'),
                        'type' => 'string',
                        'required' => true,
                    ),
                ),
            ),
        ));

        // Get user saved fish collections
        register_rest_route($this->namespace, '/' . $this->rest_base . '/collections', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_user_collections'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            ),
        ));

        // Create fish collection
        register_rest_route($this->namespace, '/' . $this->rest_base . '/collections', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_collection'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => array(
                    'name' => array(
                        'description' => __('Collection name.'),
                        'type' => 'string',
                        'required' => true,
                    ),
                    'fish' => array(
                        'description' => __('List of fish species IDs to include in the collection.'),
                        'type' => 'array',
                        'required' => true,
                        'items' => array(
                            'type' => 'integer',
                        ),
                    ),
                    'description' => array(
                        'description' => __('Collection description.'),
                        'type' => 'string',
                    ),
                ),
            ),
        ));

        // Update fish collection
        register_rest_route($this->namespace, '/' . $this->rest_base . '/collections/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_collection'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Collection ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'name' => array(
                        'description' => __('Collection name.'),
                        'type' => 'string',
                    ),
                    'fish' => array(
                        'description' => __('List of fish species IDs to include in the collection.'),
                        'type' => 'array',
                        'items' => array(
                            'type' => 'integer',
                        ),
                    ),
                    'description' => array(
                        'description' => __('Collection description.'),
                        'type' => 'string',
                    ),
                ),
            ),
        ));

        // Delete fish collection
        register_rest_route($this->namespace, '/' . $this->rest_base . '/collections/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_collection'),
                'permission_callback' => array($this, 'delete_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Collection ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Get recommended tank mates
        register_rest_route($this->namespace, '/' . $this->rest_base . '/recommended-tankmates', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'get_recommended_tankmates'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'fish' => array(
                        'description' => __('List of fish species IDs or slugs to find tank mates for.'),
                        'type' => 'array',
                        'required' => true,
                        'items' => array(
                            'type' => 'string',
                        ),
                    ),
                    'water_type' => array(
                        'description' => __('Filter by water type (freshwater, saltwater, brackish).'),
                        'type' => 'string',
                        'enum' => array('freshwater', 'saltwater', 'brackish'),
                    ),
                    'limit' => array(
                        'description' => __('Number of results to return.'),
                        'type' => 'integer',
                        'default' => 10,
                        'minimum' => 1,
                        'maximum' => 50,
                    ),
                ),
            ),
        ));
    }

    /**
     * Check fish compatibility.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function check_compatibility($request) {
        $start_time = microtime(true);
        
        $fish_list = $request->get_param('fish');
        
        // Get species terms
        $species_terms = array();
        
        foreach ($fish_list as $fish) {
            $term = is_numeric($fish) ? get_term($fish, 'fish_species') : get_term_by('slug', $fish, 'fish_species');
            
            if ($term && !is_wp_error($term)) {
                $species_terms[] = $term;
            }
        }
        
        if (empty($species_terms)) {
            return $this->format_error('no_valid_species', __('No valid fish species provided.'), 400);
        }
        
        // Check compatibility
        $compatibility_results = $this->check_species_compatibility($species_terms);
        
        // Prepare response
        $response = $this->format_response($compatibility_results);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get compatibility matrix.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_compatibility_matrix($request) {
        $start_time = microtime(true);
        
        $fish_list = $request->get_param('fish');
        
        // Get species terms
        $species_terms = array();
        
        foreach ($fish_list as $fish) {
            $term = is_numeric($fish) ? get_term($fish, 'fish_species') : get_term_by('slug', $fish, 'fish_species');
            
            if ($term && !is_wp_error($term)) {
                $species_terms[] = $term;
            }
        }
        
        if (empty($species_terms)) {
            return $this->format_error('no_valid_species', __('No valid fish species provided.'), 400);
        }
        
        // Generate compatibility matrix
        $matrix = $this->generate_compatibility_matrix($species_terms);
        
        // Prepare response
        $response = $this->format_response($matrix);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get fish species.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_species($request) {
        $start_time = microtime(true);
        
        $search = $request->get_param('search');
        $water_type = $request->get_param('water_type');
        $limit = $request->get_param('limit');
        
        // Query args
        $args = array(
            'taxonomy' => 'fish_species',
            'hide_empty' => false,
            'number' => $limit,
        );
        
        // Add search
        if ($search) {
            $args['search'] = $search;
        }
        
        // Add water type filter
        if ($water_type) {
            $args['meta_query'] = array(
                array(
                    'key' => '_species_water_type',
                    'value' => $water_type,
                    'compare' => '=',
                ),
            );
        }
        
        // Get species
        $species = get_terms($args);
        
        if (is_wp_error($species)) {
            return $this->format_error('species_error', $species->get_error_message(), 500);
        }
        
        $data = array();
        
        foreach ($species as $term) {
            $data[] = $this->prepare_species_data($term);
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
     * Get fish species by ID.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_species_by_id($request) {
        $start_time = microtime(true);
        
        $species_id = $request->get_param('id');
        
        // Get species term
        $term = get_term($species_id, 'fish_species');
        
        if (!$term || is_wp_error($term)) {
            return $this->format_error('species_not_found', __('Species not found.'), 404);
        }
        
        // Get species data
        $data = $this->prepare_species_data($term, true);
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get compatibility details between two species.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_compatibility_details($request) {
        $start_time = microtime(true);
        
        $species1 = $request->get_param('species1');
        $species2 = $request->get_param('species2');
        
        // Get species terms
        $term1 = is_numeric($species1) ? get_term($species1, 'fish_species') : get_term_by('slug', $species1, 'fish_species');
        $term2 = is_numeric($species2) ? get_term($species2, 'fish_species') : get_term_by('slug', $species2, 'fish_species');
        
        if (!$term1 || is_wp_error($term1)) {
            return $this->format_error('species1_not_found', __('First species not found.'), 404);
        }
        
        if (!$term2 || is_wp_error($term2)) {
            return $this->format_error('species2_not_found', __('Second species not found.'), 404);
        }
        
        // Get compatibility details
        $compatibility = $this->get_species_compatibility_details($term1, $term2);
        
        // Prepare response
        $response = $this->format_response($compatibility);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get user saved fish collections.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_user_collections($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_fish_collections';
        
        // Get collections
        $collections = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE user_id = %d ORDER BY name ASC",
                $user_id
            )
        );
        
        $data = array();
        
        foreach ($collections as $collection) {
            $fish_ids = maybe_unserialize($collection->fish_ids);
            $fish = array();
            
            foreach ($fish_ids as $fish_id) {
                $term = get_term($fish_id, 'fish_species');
                
                if ($term && !is_wp_error($term)) {
                    $fish[] = $this->prepare_species_data($term);
                }
            }
            
            $data[] = array(
                'id' => (int) $collection->id,
                'name' => $collection->name,
                'description' => $collection->description,
                'fish' => $fish,
                'created_at' => $collection->created_at,
                'updated_at' => $collection->updated_at,
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'collections' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Create fish collection.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_collection($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $name = $request->get_param('name');
        $fish_ids = $request->get_param('fish');
        $description = $request->get_param('description');
        
        // Validate fish IDs
        $valid_fish_ids = array();
        
        foreach ($fish_ids as $fish_id) {
            $term = get_term($fish_id, 'fish_species');
            
            if ($term && !is_wp_error($term)) {
                $valid_fish_ids[] = $fish_id;
            }
        }
        
        if (empty($valid_fish_ids)) {
            return $this->format_error('no_valid_species', __('No valid fish species provided.'), 400);
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_fish_collections';
        
        // Insert collection
        $inserted = $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'name' => $name,
                'description' => $description,
                'fish_ids' => maybe_serialize($valid_fish_ids),
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );
        
        if ($inserted === false) {
            return $this->format_error('insert_failed', __('Failed to create collection.'), 500);
        }
        
        $collection_id = $wpdb->insert_id;
        
        // Get collection
        $collection = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d",
                $collection_id
            )
        );
        
        // Get fish data
        $fish_ids = maybe_unserialize($collection->fish_ids);
        $fish = array();
        
        foreach ($fish_ids as $fish_id) {
            $term = get_term($fish_id, 'fish_species');
            
            if ($term && !is_wp_error($term)) {
                $fish[] = $this->prepare_species_data($term);
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'collection' => array(
                'id' => (int) $collection->id,
                'name' => $collection->name,
                'description' => $collection->description,
                'fish' => $fish,
                'created_at' => $collection->created_at,
                'updated_at' => $collection->updated_at,
            ),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Update fish collection.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_collection($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $collection_id = $request->get_param('id');
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_fish_collections';
        
        // Check if collection exists and belongs to user
        $collection = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d AND user_id = %d",
                $collection_id,
                $user_id
            )
        );
        
        if (!$collection) {
            return $this->format_error('collection_not_found', __('Collection not found.'), 404);
        }
        
        $name = $request->get_param('name');
        $fish_ids = $request->get_param('fish');
        $description = $request->get_param('description');
        
        // Prepare update data
        $data = array(
            'updated_at' => current_time('mysql'),
        );
        
        $formats = array('%s');
        
        if ($name !== null) {
            $data['name'] = $name;
            $formats[] = '%s';
        }
        
        if ($description !== null) {
            $data['description'] = $description;
            $formats[] = '%s';
        }
        
        if ($fish_ids !== null) {
            // Validate fish IDs
            $valid_fish_ids = array();
            
            foreach ($fish_ids as $fish_id) {
                $term = get_term($fish_id, 'fish_species');
                
                if ($term && !is_wp_error($term)) {
                    $valid_fish_ids[] = $fish_id;
                }
            }
            
            if (empty($valid_fish_ids)) {
                return $this->format_error('no_valid_species', __('No valid fish species provided.'), 400);
            }
            
            $data['fish_ids'] = maybe_serialize($valid_fish_ids);
            $formats[] = '%s';
        }
        
        // Update collection
        $updated = $wpdb->update(
            $table_name,
            $data,
            array('id' => $collection_id),
            $formats,
            array('%d')
        );
        
        if ($updated === false) {
            return $this->format_error('update_failed', __('Failed to update collection.'), 500);
        }
        
        // Get updated collection
        $collection = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d",
                $collection_id
            )
        );
        
        // Get fish data
        $fish_ids = maybe_unserialize($collection->fish_ids);
        $fish = array();
        
        foreach ($fish_ids as $fish_id) {
            $term = get_term($fish_id, 'fish_species');
            
            if ($term && !is_wp_error($term)) {
                $fish[] = $this->prepare_species_data($term);
            }
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'collection' => array(
                'id' => (int) $collection->id,
                'name' => $collection->name,
                'description' => $collection->description,
                'fish' => $fish,
                'created_at' => $collection->created_at,
                'updated_at' => $collection->updated_at,
            ),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Delete fish collection.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_collection($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $collection_id = $request->get_param('id');
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_fish_collections';
        
        // Check if collection exists and belongs to user
        $collection = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d AND user_id = %d",
                $collection_id,
                $user_id
            )
        );
        
        if (!$collection) {
            return $this->format_error('collection_not_found', __('Collection not found.'), 404);
        }
        
        // Delete collection
        $deleted = $wpdb->delete(
            $table_name,
            array('id' => $collection_id),
            array('%d')
        );
        
        if ($deleted === false) {
            return $this->format_error('delete_failed', __('Failed to delete collection.'), 500);
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'message' => __('Collection deleted successfully.'),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get recommended tank mates.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_recommended_tankmates($request) {
        $start_time = microtime(true);
        
        $fish_list = $request->get_param('fish');
        $water_type = $request->get_param('water_type');
        $limit = $request->get_param('limit');
        
        // Get species terms
        $species_terms = array();
        
        foreach ($fish_list as $fish) {
            $term = is_numeric($fish) ? get_term($fish, 'fish_species') : get_term_by('slug', $fish, 'fish_species');
            
            if ($term && !is_wp_error($term)) {
                $species_terms[] = $term;
            }
        }
        
        if (empty($species_terms)) {
            return $this->format_error('no_valid_species', __('No valid fish species provided.'), 400);
        }
        
        // Get recommended tank mates
        $tankmates = $this->find_recommended_tankmates($species_terms, $water_type, $limit);
        
        // Prepare response
        $response = $this->format_response($tankmates);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Check species compatibility.
     *
     * @param array $species_terms Array of species terms.
     * @return array
     */
    private function check_species_compatibility($species_terms) {
        $species = array();
        $compatibility_issues = array();
        $water_parameters = array();
        $overall_compatibility = 'high';
        
        // Get species data
        foreach ($species_terms as $term) {
            $species[] = $this->prepare_species_data($term);
        }
        
        // Check compatibility between each pair of species
        for ($i = 0; $i < count($species_terms); $i++) {
            for ($j = $i + 1; $j < count($species_terms); $j++) {
                $compatibility = $this->get_species_compatibility($species_terms[$i], $species_terms[$j]);
                
                if ($compatibility['level'] !== 'high') {
                    $compatibility_issues[] = array(
                        'species1' => $this->prepare_species_data($species_terms[$i]),
                        'species2' => $this->prepare_species_data($species_terms[$j]),
                        'compatibility' => $compatibility,
                    );
                    
                    // Update overall compatibility
                    if ($compatibility['level'] === 'low' && $overall_compatibility !== 'low') {
                        $overall_compatibility = 'low';
                    } elseif ($compatibility['level'] === 'medium' && $overall_compatibility === 'high') {
                        $overall_compatibility = 'medium';
                    }
                }
            }
        }
        
        // Check water parameter compatibility
        $water_types = array();
        $ph_ranges = array();
        $temp_ranges = array();
        $hardness_ranges = array();
        
        foreach ($species_terms as $term) {
            $water_type = get_term_meta($term->term_id, '_species_water_type', true);
            $ph_min = get_term_meta($term->term_id, '_species_ph_min', true);
            $ph_max = get_term_meta($term->term_id, '_species_ph_max', true);
            $temp_min = get_term_meta($term->term_id, '_species_temp_min', true);
            $temp_max = get_term_meta($term->term_id, '_species_temp_max', true);
            $gh_min = get_term_meta($term->term_id, '_species_gh_min', true);
            $gh_max = get_term_meta($term->term_id, '_species_gh_max', true);
            
            if ($water_type) {
                $water_types[] = $water_type;
            }
            
            if ($ph_min !== '' && $ph_max !== '') {
                $ph_ranges[] = array(
                    'species' => $term->name,
                    'min' => (float) $ph_min,
                    'max' => (float) $ph_max,
                );
            }
            
            if ($temp_min !== '' && $temp_max !== '') {
                $temp_ranges[] = array(
                    'species' => $term->name,
                    'min' => (float) $temp_min,
                    'max' => (float) $temp_max,
                );
            }
            
            if ($gh_min !== '' && $gh_max !== '') {
                $hardness_ranges[] = array(
                    'species' => $term->name,
                    'min' => (float) $gh_min,
                    'max' => (float) $gh_max,
                );
            }
        }
        
        // Check water type compatibility
        $water_types = array_unique($water_types);
        
        if (count($water_types) > 1) {
            $water_parameters['water_type'] = array(
                'compatible' => false,
                'message' => __('Fish require different water types.'),
                'types' => $water_types,
            );
            
            $overall_compatibility = 'low';
        } else {
            $water_parameters['water_type'] = array(
                'compatible' => true,
                'message' => __('All fish are compatible with the same water type.'),
                'types' => $water_types,
            );
        }
        
        // Check pH compatibility
        if (!empty($ph_ranges)) {
            $ph_min = max(array_column($ph_ranges, 'min'));
            $ph_max = min(array_column($ph_ranges, 'max'));
            
            if ($ph_min <= $ph_max) {
                $water_parameters['ph'] = array(
                    'compatible' => true,
                    'message' => __('All fish are compatible with the same pH range.'),
                    'recommended_range' => array(
                        'min' => $ph_min,
                        'max' => $ph_max,
                    ),
                    'species_ranges' => $ph_ranges,
                );
            } else {
                $water_parameters['ph'] = array(
                    'compatible' => false,
                    'message' => __('Fish require different pH ranges.'),
                    'species_ranges' => $ph_ranges,
                );
                
                if ($overall_compatibility !== 'low') {
                    $overall_compatibility = 'medium';
                }
            }
        }
        
        // Check temperature compatibility
        if (!empty($temp_ranges)) {
            $temp_min = max(array_column($temp_ranges, 'min'));
            $temp_max = min(array_column($temp_ranges, 'max'));
            
            if ($temp_min <= $temp_max) {
                $water_parameters['temperature'] = array(
                    'compatible' => true,
                    'message' => __('All fish are compatible with the same temperature range.'),
                    'recommended_range' => array(
                        'min' => $temp_min,
                        'max' => $temp_max,
                    ),
                    'species_ranges' => $temp_ranges,
                );
            } else {
                $water_parameters['temperature'] = array(
                    'compatible' => false,
                    'message' => __('Fish require different temperature ranges.'),
                    'species_ranges' => $temp_ranges,
                );
                
                if ($overall_compatibility !== 'low') {
                    $overall_compatibility = 'medium';
                }
            }
        }
        
        // Check hardness compatibility
        if (!empty($hardness_ranges)) {
            $hardness_min = max(array_column($hardness_ranges, 'min'));
            $hardness_max = min(array_column($hardness_ranges, 'max'));
            
            if ($hardness_min <= $hardness_max) {
                $water_parameters['hardness'] = array(
                    'compatible' => true,
                    'message' => __('All fish are compatible with the same water hardness range.'),
                    'recommended_range' => array(
                        'min' => $hardness_min,
                        'max' => $hardness_max,
                    ),
                    'species_ranges' => $hardness_ranges,
                );
            } else {
                $water_parameters['hardness'] = array(
                    'compatible' => false,
                    'message' => __('Fish require different water hardness ranges.'),
                    'species_ranges' => $hardness_ranges,
                );
                
                if ($overall_compatibility !== 'low') {
                    $overall_compatibility = 'medium';
                }
            }
        }
        
        // Generate compatibility summary
        $compatibility_summary = '';
        
        if ($overall_compatibility === 'high') {
            $compatibility_summary = __('These fish are highly compatible and should thrive together in the same aquarium.');
        } elseif ($overall_compatibility === 'medium') {
            $compatibility_summary = __('These fish have some compatibility issues. They may coexist with proper care and monitoring.');
        } else {
            $compatibility_summary = __('These fish have significant compatibility issues and are not recommended to be kept together.');
        }
        
        return array(
            'species' => $species,
            'overall_compatibility' => $overall_compatibility,
            'compatibility_summary' => $compatibility_summary,
            'compatibility_issues' => $compatibility_issues,
            'water_parameters' => $water_parameters,
        );
    }

    /**
     * Generate compatibility matrix.
     *
     * @param array $species_terms Array of species terms.
     * @return array
     */
    private function generate_compatibility_matrix($species_terms) {
        $species = array();
        $matrix = array();
        
        // Get species data
        foreach ($species_terms as $term) {
            $species[] = $this->prepare_species_data($term);
        }
        
        // Generate matrix
        for ($i = 0; $i < count($species_terms); $i++) {
            $row = array();
            
            for ($j = 0; $j < count($species_terms); $j++) {
                if ($i === $j) {
                    $row[] = array(
                        'level' => 'self',
                        'score' => 0,
                    );
                } else {
                    $row[] = $this->get_species_compatibility($species_terms[$i], $species_terms[$j]);
                }
            }
            
            $matrix[] = $row;
        }
        
        return array(
            'species' => $species,
            'matrix' => $matrix,
        );
    }

    /**
     * Get species compatibility.
     *
     * @param WP_Term $species1 First species term.
     * @param WP_Term $species2 Second species term.
     * @return array
     */
    private function get_species_compatibility($species1, $species2) {
        // Check compatibility table first
        $compatibility = $this->get_compatibility_from_table($species1->term_id, $species2->term_id);
        
        if ($compatibility) {
            return $compatibility;
        }
        
        // Calculate compatibility based on species attributes
        return $this->calculate_species_compatibility($species1, $species2);
    }

    /**
     * Get compatibility from compatibility table.
     *
     * @param int $species1_id First species ID.
     * @param int $species2_id Second species ID.
     * @return array|false
     */
    private function get_compatibility_from_table($species1_id, $species2_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_fish_compatibility';
        
        // Check direct compatibility
        $compatibility = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE (species1_id = %d AND species2_id = %d) OR (species1_id = %d AND species2_id = %d)",
                $species1_id,
                $species2_id,
                $species2_id,
                $species1_id
            )
        );
        
        if ($compatibility) {
            return array(
                'level' => $compatibility->compatibility_level,
                'score' => (int) $compatibility->compatibility_score,
                'notes' => $compatibility->notes,
            );
        }
        
        return false;
    }

    /**
     * Calculate species compatibility based on attributes.
     *
     * @param WP_Term $species1 First species term.
     * @param WP_Term $species2 Second species term.
     * @return array
     */
    private function calculate_species_compatibility($species1, $species2) {
        $score = 100;
        $issues = array();
        
        // Check water type compatibility
        $water_type1 = get_term_meta($species1->term_id, '_species_water_type', true);
        $water_type2 = get_term_meta($species2->term_id, '_species_water_type', true);
        
        if ($water_type1 && $water_type2 && $water_type1 !== $water_type2) {
            $score -= 100;
            $issues[] = __('Different water types');
        }
        
        // Check pH compatibility
        $ph_min1 = get_term_meta($species1->term_id, '_species_ph_min', true);
        $ph_max1 = get_term_meta($species1->term_id, '_species_ph_max', true);
        $ph_min2 = get_term_meta($species2->term_id, '_species_ph_min', true);
        $ph_max2 = get_term_meta($species2->term_id, '_species_ph_max', true);
        
        if ($ph_min1 !== '' && $ph_max1 !== '' && $ph_min2 !== '' && $ph_max2 !== '') {
            $ph_min1 = (float) $ph_min1;
            $ph_max1 = (float) $ph_max1;
            $ph_min2 = (float) $ph_min2;
            $ph_max2 = (float) $ph_max2;
            
            $ph_overlap = min($ph_max1, $ph_max2) - max($ph_min1, $ph_min2);
            
            if ($ph_overlap < 0) {
                $score -= 20;
                $issues[] = __('Incompatible pH ranges');
            } elseif ($ph_overlap < 0.5) {
                $score -= 10;
                $issues[] = __('Narrow pH overlap');
            }
        }
        
        // Check temperature compatibility
        $temp_min1 = get_term_meta($species1->term_id, '_species_temp_min', true);
        $temp_max1 = get_term_meta($species1->term_id, '_species_temp_max', true);
        $temp_min2 = get_term_meta($species2->term_id, '_species_temp_min', true);
        $temp_max2 = get_term_meta($species2->term_id, '_species_temp_max', true);
        
        if ($temp_min1 !== '' && $temp_max1 !== '' && $temp_min2 !== '' && $temp_max2 !== '') {
            $temp_min1 = (float) $temp_min1;
            $temp_max1 = (float) $temp_max1;
            $temp_min2 = (float) $temp_min2;
            $temp_max2 = (float) $temp_max2;
            
            $temp_overlap = min($temp_max1, $temp_max2) - max($temp_min1, $temp_min2);
            
            if ($temp_overlap < 0) {
                $score -= 20;
                $issues[] = __('Incompatible temperature ranges');
            } elseif ($temp_overlap < 2) {
                $score -= 10;
                $issues[] = __('Narrow temperature overlap');
            }
        }
        
        // Check hardness compatibility
        $gh_min1 = get_term_meta($species1->term_id, '_species_gh_min', true);
        $gh_max1 = get_term_meta($species1->term_id, '_species_gh_max', true);
        $gh_min2 = get_term_meta($species2->term_id, '_species_gh_min', true);
        $gh_max2 = get_term_meta($species2->term_id, '_species_gh_max', true);
        
        if ($gh_min1 !== '' && $gh_max1 !== '' && $gh_min2 !== '' && $gh_max2 !== '') {
            $gh_min1 = (float) $gh_min1;
            $gh_max1 = (float) $gh_max1;
            $gh_min2 = (float) $gh_min2;
            $gh_max2 = (float) $gh_max2;
            
            $gh_overlap = min($gh_max1, $gh_max2) - max($gh_min1, $gh_min2);
            
            if ($gh_overlap < 0) {
                $score -= 15;
                $issues[] = __('Incompatible hardness ranges');
            } elseif ($gh_overlap < 2) {
                $score -= 5;
                $issues[] = __('Narrow hardness overlap');
            }
        }
        
        // Check aggression compatibility
        $aggression1 = get_term_meta($species1->term_id, '_species_aggression', true);
        $aggression2 = get_term_meta($species2->term_id, '_species_aggression', true);
        
        if ($aggression1 && $aggression2) {
            if ($aggression1 === 'high' && $aggression2 === 'high') {
                $score -= 20;
                $issues[] = __('Both species are highly aggressive');
            } elseif (($aggression1 === 'high' && $aggression2 === 'low') || ($aggression1 === 'low' && $aggression2 === 'high')) {
                $score -= 15;
                $issues[] = __('Aggressive species with peaceful species');
            }
        }
        
        // Check size compatibility
        $adult_size1 = get_term_meta($species1->term_id, '_species_adult_size', true);
        $adult_size2 = get_term_meta($species2->term_id, '_species_adult_size', true);
        
        if ($adult_size1 !== '' && $adult_size2 !== '') {
            $adult_size1 = (float) $adult_size1;
            $adult_size2 = (float) $adult_size2;
            
            $size_ratio = max($adult_size1, $adult_size2) / min($adult_size1, $adult_size2);
            
            if ($size_ratio > 3) {
                $score -= 15;
                $issues[] = __('Significant size difference');
            } elseif ($size_ratio > 2) {
                $score -= 5;
                $issues[] = __('Moderate size difference');
            }
        }
        
        // Check feeding habits
        $feeding1 = get_term_meta($species1->term_id, '_species_feeding_habit', true);
        $feeding2 = get_term_meta($species2->term_id, '_species_feeding_habit', true);
        
        if ($feeding1 && $feeding2) {
            if (($feeding1 === 'predator' && $feeding2 === 'small') || ($feeding1 === 'small' && $feeding2 === 'predator')) {
                $score -= 20;
                $issues[] = __('Predator may eat smaller species');
            }
        }
        
        // Check swimming level
        $swimming1 = get_term_meta($species1->term_id, '_species_swimming_level', true);
        $swimming2 = get_term_meta($species2->term_id, '_species_swimming_level', true);
        
        if ($swimming1 && $swimming2 && $swimming1 === $swimming2) {
            $score -= 5;
            $issues[] = __('Both species prefer the same swimming level');
        }
        
        // Determine compatibility level
        $level = 'high';
        
        if ($score < 50) {
            $level = 'low';
        } elseif ($score < 80) {
            $level = 'medium';
        }
        
        return array(
            'level' => $level,
            'score' => $score,
            'issues' => $issues,
        );
    }

    /**
     * Get species compatibility details.
     *
     * @param WP_Term $species1 First species term.
     * @param WP_Term $species2 Second species term.
     * @return array
     */
    private function get_species_compatibility_details($species1, $species2) {
        // Get basic compatibility
        $compatibility = $this->get_species_compatibility($species1, $species2);
        
        // Get species data
        $species1_data = $this->prepare_species_data($species1, true);
        $species2_data = $this->prepare_species_data($species2, true);
        
        // Check water parameter compatibility
        $water_parameters = array();
        
        // Check water type compatibility
        $water_type1 = get_term_meta($species1->term_id, '_species_water_type', true);
        $water_type2 = get_term_meta($species2->term_id, '_species_water_type', true);
        
        if ($water_type1 && $water_type2) {
            $water_parameters['water_type'] = array(
                'compatible' => $water_type1 === $water_type2,
                'species1' => $water_type1,
                'species2' => $water_type2,
            );
        }
        
        // Check pH compatibility
        $ph_min1 = get_term_meta($species1->term_id, '_species_ph_min', true);
        $ph_max1 = get_term_meta($species1->term_id, '_species_ph_max', true);
        $ph_min2 = get_term_meta($species2->term_id, '_species_ph_min', true);
        $ph_max2 = get_term_meta($species2->term_id, '_species_ph_max', true);
        
        if ($ph_min1 !== '' && $ph_max1 !== '' && $ph_min2 !== '' && $ph_max2 !== '') {
            $ph_min1 = (float) $ph_min1;
            $ph_max1 = (float) $ph_max1;
            $ph_min2 = (float) $ph_min2;
            $ph_max2 = (float) $ph_max2;
            
            $ph_overlap = min($ph_max1, $ph_max2) - max($ph_min1, $ph_min2);
            
            $water_parameters['ph'] = array(
                'compatible' => $ph_overlap >= 0,
                'species1' => array(
                    'min' => $ph_min1,
                    'max' => $ph_max1,
                ),
                'species2' => array(
                    'min' => $ph_min2,
                    'max' => $ph_max2,
                ),
                'overlap' => $ph_overlap >= 0 ? array(
                    'min' => max($ph_min1, $ph_min2),
                    'max' => min($ph_max1, $ph_max2),
                ) : null,
            );
        }
        
        // Check temperature compatibility
        $temp_min1 = get_term_meta($species1->term_id, '_species_temp_min', true);
        $temp_max1 = get_term_meta($species1->term_id, '_species_temp_max', true);
        $temp_min2 = get_term_meta($species2->term_id, '_species_temp_min', true);
        $temp_max2 = get_term_meta($species2->term_id, '_species_temp_max', true);
        
        if ($temp_min1 !== '' && $temp_max1 !== '' && $temp_min2 !== '' && $temp_max2 !== '') {
            $temp_min1 = (float) $temp_min1;
            $temp_max1 = (float) $temp_max1;
            $temp_min2 = (float) $temp_min2;
            $temp_max2 = (float) $temp_max2;
            
            $temp_overlap = min($temp_max1, $temp_max2) - max($temp_min1, $temp_min2);
            
            $water_parameters['temperature'] = array(
                'compatible' => $temp_overlap >= 0,
                'species1' => array(
                    'min' => $temp_min1,
                    'max' => $temp_max1,
                ),
                'species2' => array(
                    'min' => $temp_min2,
                    'max' => $temp_max2,
                ),
                'overlap' => $temp_overlap >= 0 ? array(
                    'min' => max($temp_min1, $temp_min2),
                    'max' => min($temp_max1, $temp_max2),
                ) : null,
            );
        }
        
        // Check hardness compatibility
        $gh_min1 = get_term_meta($species1->term_id, '_species_gh_min', true);
        $gh_max1 = get_term_meta($species1->term_id, '_species_gh_max', true);
        $gh_min2 = get_term_meta($species2->term_id, '_species_gh_min', true);
        $gh_max2 = get_term_meta($species2->term_id, '_species_gh_max', true);
        
        if ($gh_min1 !== '' && $gh_max1 !== '' && $gh_min2 !== '' && $gh_max2 !== '') {
            $gh_min1 = (float) $gh_min1;
            $gh_max1 = (float) $gh_max1;
            $gh_min2 = (float) $gh_min2;
            $gh_max2 = (float) $gh_max2;
            
            $gh_overlap = min($gh_max1, $gh_max2) - max($gh_min1, $gh_min2);
            
            $water_parameters['hardness'] = array(
                'compatible' => $gh_overlap >= 0,
                'species1' => array(
                    'min' => $gh_min1,
                    'max' => $gh_max1,
                ),
                'species2' => array(
                    'min' => $gh_min2,
                    'max' => $gh_max2,
                ),
                'overlap' => $gh_overlap >= 0 ? array(
                    'min' => max($gh_min1, $gh_min2),
                    'max' => min($gh_max1, $gh_max2),
                ) : null,
            );
        }
        
        // Check behavioral compatibility
        $behavioral_compatibility = array();
        
        // Check aggression
        $aggression1 = get_term_meta($species1->term_id, '_species_aggression', true);
        $aggression2 = get_term_meta($species2->term_id, '_species_aggression', true);
        
        if ($aggression1 && $aggression2) {
            $aggression_compatible = true;
            $aggression_notes = '';
            
            if ($aggression1 === 'high' && $aggression2 === 'high') {
                $aggression_compatible = false;
                $aggression_notes = __('Both species are highly aggressive and may fight.');
            } elseif (($aggression1 === 'high' && $aggression2 === 'low') || ($aggression1 === 'low' && $aggression2 === 'high')) {
                $aggression_compatible = false;
                $aggression_notes = __('Aggressive species may harass peaceful species.');
            }
            
            $behavioral_compatibility['aggression'] = array(
                'compatible' => $aggression_compatible,
                'species1' => $aggression1,
                'species2' => $aggression2,
                'notes' => $aggression_notes,
            );
        }
        
        // Check size
        $adult_size1 = get_term_meta($species1->term_id, '_species_adult_size', true);
        $adult_size2 = get_term_meta($species2->term_id, '_species_adult_size', true);
        
        if ($adult_size1 !== '' && $adult_size2 !== '') {
            $adult_size1 = (float) $adult_size1;
            $adult_size2 = (float) $adult_size2;
            
            $size_ratio = max($adult_size1, $adult_size2) / min($adult_size1, $adult_size2);
            $size_compatible = true;
            $size_notes = '';
            
            if ($size_ratio > 3) {
                $size_compatible = false;
                $size_notes = __('Significant size difference may lead to predation or intimidation.');
            } elseif ($size_ratio > 2) {
                $size_compatible = true;
                $size_notes = __('Moderate size difference may cause issues in some cases.');
            }
            
            $behavioral_compatibility['size'] = array(
                'compatible' => $size_compatible,
                'species1' => $adult_size1,
                'species2' => $adult_size2,
                'ratio' => round($size_ratio, 1),
                'notes' => $size_notes,
            );
        }
        
        // Check feeding habits
        $feeding1 = get_term_meta($species1->term_id, '_species_feeding_habit', true);
        $feeding2 = get_term_meta($species2->term_id, '_species_feeding_habit', true);
        
        if ($feeding1 && $feeding2) {
            $feeding_compatible = true;
            $feeding_notes = '';
            
            if (($feeding1 === 'predator' && $feeding2 === 'small') || ($feeding1 === 'small' && $feeding2 === 'predator')) {
                $feeding_compatible = false;
                $feeding_notes = __('Predatory species may eat smaller species.');
            }
            
            $behavioral_compatibility['feeding'] = array(
                'compatible' => $feeding_compatible,
                'species1' => $feeding1,
                'species2' => $feeding2,
                'notes' => $feeding_notes,
            );
        }
        
        // Check swimming level
        $swimming1 = get_term_meta($species1->term_id, '_species_swimming_level', true);
        $swimming2 = get_term_meta($species2->term_id, '_species_swimming_level', true);
        
        if ($swimming1 && $swimming2) {
            $swimming_compatible = true;
            $swimming_notes = '';
            
            if ($swimming1 === $swimming2) {
                $swimming_compatible = true;
                $swimming_notes = __('Both species prefer the same swimming level, which may cause competition for space.');
            }
            
            $behavioral_compatibility['swimming_level'] = array(
                'compatible' => $swimming_compatible,
                'species1' => $swimming1,
                'species2' => $swimming2,
                'notes' => $swimming_notes,
            );
        }
        
        // Generate compatibility summary
        $compatibility_summary = '';
        
        if ($compatibility['level'] === 'high') {
            $compatibility_summary = __('These fish are highly compatible and should thrive together in the same aquarium.');
        } elseif ($compatibility['level'] === 'medium') {
            $compatibility_summary = __('These fish have some compatibility issues. They may coexist with proper care and monitoring.');
        } else {
            $compatibility_summary = __('These fish have significant compatibility issues and are not recommended to be kept together.');
        }
        
        return array(
            'species1' => $species1_data,
            'species2' => $species2_data,
            'compatibility' => $compatibility,
            'compatibility_summary' => $compatibility_summary,
            'water_parameters' => $water_parameters,
            'behavioral_compatibility' => $behavioral_compatibility,
        );
    }

    /**
     * Find recommended tank mates.
     *
     * @param array $species_terms Array of species terms.
     * @param string $water_type Water type filter.
     * @param int $limit Number of results to return.
     * @return array
     */
    private function find_recommended_tankmates($species_terms, $water_type, $limit) {
        // Get all species
        $args = array(
            'taxonomy' => 'fish_species',
            'hide_empty' => false,
            'number' => 100,
        );
        
        // Add water type filter
        if ($water_type) {
            $args['meta_query'] = array(
                array(
                    'key' => '_species_water_type',
                    'value' => $water_type,
                    'compare' => '=',
                ),
            );
        } else {
            // Use water type of first species
            $first_species_water_type = get_term_meta($species_terms[0]->term_id, '_species_water_type', true);
            
            if ($first_species_water_type) {
                $args['meta_query'] = array(
                    array(
                        'key' => '_species_water_type',
                        'value' => $first_species_water_type,
                        'compare' => '=',
                    ),
                );
            }
        }
        
        $all_species = get_terms($args);
        
        if (is_wp_error($all_species)) {
            return array(
                'species' => array_map(array($this, 'prepare_species_data'), $species_terms),
                'recommended_tankmates' => array(),
                'error' => $all_species->get_error_message(),
            );
        }
        
        // Filter out species already in the list
        $species_ids = array_map(function($term) {
            return $term->term_id;
        }, $species_terms);
        
        $potential_tankmates = array_filter($all_species, function($term) use ($species_ids) {
            return !in_array($term->term_id, $species_ids);
        });
        
        // Score potential tank mates
        $scored_tankmates = array();
        
        foreach ($potential_tankmates as $tankmate) {
            $score = 0;
            $compatibility_levels = array();
            
            // Check compatibility with each species in the list
            foreach ($species_terms as $species) {
                $compatibility = $this->get_species_compatibility($species, $tankmate);
                $compatibility_levels[] = $compatibility['level'];
                
                if ($compatibility['level'] === 'high') {
                    $score += 2;
                } elseif ($compatibility['level'] === 'medium') {
                    $score += 1;
                } else {
                    $score -= 2;
                }
            }
            
            // Only include if compatible with all species
            if (!in_array('low', $compatibility_levels)) {
                $scored_tankmates[] = array(
                    'species' => $tankmate,
                    'score' => $score,
                    'compatibility_levels' => $compatibility_levels,
                );
            }
        }
        
        // Sort by score
        usort($scored_tankmates, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        
        // Limit results
        $scored_tankmates = array_slice($scored_tankmates, 0, $limit);
        
        // Prepare response
        $recommended_tankmates = array();
        
        foreach ($scored_tankmates as $tankmate) {
            $recommended_tankmates[] = array(
                'species' => $this->prepare_species_data($tankmate['species']),
                'score' => $tankmate['score'],
                'compatibility_levels' => $tankmate['compatibility_levels'],
            );
        }
        
        return array(
            'species' => array_map(array($this, 'prepare_species_data'), $species_terms),
            'recommended_tankmates' => $recommended_tankmates,
        );
    }

    /**
     * Prepare species data for API response.
     *
     * @param WP_Term $term Species term.
     * @param bool $detailed Whether to include detailed information.
     * @return array
     */
    private function prepare_species_data($term, $detailed = false) {
        $data = array(
            'id' => $term->term_id,
            'name' => $term->name,
            'slug' => $term->slug,
            'scientific_name' => get_term_meta($term->term_id, '_species_scientific_name', true),
            'image' => $this->get_term_image($term->term_id, 'fish_species'),
        );
        
        if ($detailed) {
            $data['description'] = $term->description;
            $data['water_type'] = get_term_meta($term->term_id, '_species_water_type', true);
            $data['adult_size'] = get_term_meta($term->term_id, '_species_adult_size', true);
            $data['lifespan'] = get_term_meta($term->term_id, '_species_lifespan', true);
            $data['temperament'] = get_term_meta($term->term_id, '_species_temperament', true);
            $data['aggression'] = get_term_meta($term->term_id, '_species_aggression', true);
            $data['swimming_level'] = get_term_meta($term->term_id, '_species_swimming_level', true);
            $data['feeding_habit'] = get_term_meta($term->term_id, '_species_feeding_habit', true);
            $data['min_tank_size'] = get_term_meta($term->term_id, '_species_min_tank_size', true);
            $data['water_parameters'] = array(
                'ph' => array(
                    'min' => get_term_meta($term->term_id, '_species_ph_min', true),
                    'max' => get_term_meta($term->term_id, '_species_ph_max', true),
                ),
                'temperature' => array(
                    'min' => get_term_meta($term->term_id, '_species_temp_min', true),
                    'max' => get_term_meta($term->term_id, '_species_temp_max', true),
                ),
                'hardness' => array(
                    'min' => get_term_meta($term->term_id, '_species_gh_min', true),
                    'max' => get_term_meta($term->term_id, '_species_gh_max', true),
                ),
            );
            $data['care_level'] = get_term_meta($term->term_id, '_species_care_level', true);
            $data['breeding'] = get_term_meta($term->term_id, '_species_breeding', true);
            $data['diet'] = get_term_meta($term->term_id, '_species_diet', true);
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