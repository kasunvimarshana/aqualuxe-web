<?php
/**
 * AquaLuxe API Water Calculator Controller
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Water_Calculator_Controller Class
 *
 * Handles API requests for water calculator
 */
class AquaLuxe_API_Water_Calculator_Controller extends AquaLuxe_API_Controller {

    /**
     * Get the base for this controller.
     *
     * @return string
     */
    protected function get_rest_base() {
        return 'water-calculator';
    }

    /**
     * Register routes for this controller.
     *
     * @return void
     */
    public function register_routes() {
        // Calculate water parameters
        register_rest_route($this->namespace, '/' . $this->rest_base . '/calculate', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'calculate_parameters'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'tank_length' => array(
                        'description' => __('Tank length in inches or centimeters.'),
                        'type' => 'number',
                        'required' => true,
                        'minimum' => 0,
                    ),
                    'tank_width' => array(
                        'description' => __('Tank width in inches or centimeters.'),
                        'type' => 'number',
                        'required' => true,
                        'minimum' => 0,
                    ),
                    'tank_height' => array(
                        'description' => __('Tank height in inches or centimeters.'),
                        'type' => 'number',
                        'required' => true,
                        'minimum' => 0,
                    ),
                    'unit_system' => array(
                        'description' => __('Unit system (imperial or metric).'),
                        'type' => 'string',
                        'enum' => array('imperial', 'metric'),
                        'default' => 'imperial',
                    ),
                    'substrate_depth' => array(
                        'description' => __('Substrate depth in inches or centimeters.'),
                        'type' => 'number',
                        'default' => 0,
                        'minimum' => 0,
                    ),
                    'decorations_volume' => array(
                        'description' => __('Volume of decorations in gallons or liters.'),
                        'type' => 'number',
                        'default' => 0,
                        'minimum' => 0,
                    ),
                ),
            ),
        ));

        // Calculate water change
        register_rest_route($this->namespace, '/' . $this->rest_base . '/water-change', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'calculate_water_change'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'tank_volume' => array(
                        'description' => __('Tank volume in gallons or liters.'),
                        'type' => 'number',
                        'required' => true,
                        'minimum' => 0,
                    ),
                    'change_percentage' => array(
                        'description' => __('Water change percentage.'),
                        'type' => 'number',
                        'required' => true,
                        'minimum' => 0,
                        'maximum' => 100,
                    ),
                    'unit_system' => array(
                        'description' => __('Unit system (imperial or metric).'),
                        'type' => 'string',
                        'enum' => array('imperial', 'metric'),
                        'default' => 'imperial',
                    ),
                ),
            ),
        ));

        // Calculate stocking level
        register_rest_route($this->namespace, '/' . $this->rest_base . '/stocking-level', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'calculate_stocking_level'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'tank_volume' => array(
                        'description' => __('Tank volume in gallons or liters.'),
                        'type' => 'number',
                        'required' => true,
                        'minimum' => 0,
                    ),
                    'fish' => array(
                        'description' => __('Fish list with quantities and sizes.'),
                        'type' => 'array',
                        'required' => true,
                        'items' => array(
                            'type' => 'object',
                            'properties' => array(
                                'name' => array(
                                    'type' => 'string',
                                    'required' => true,
                                ),
                                'quantity' => array(
                                    'type' => 'integer',
                                    'required' => true,
                                    'minimum' => 1,
                                ),
                                'adult_size' => array(
                                    'type' => 'number',
                                    'required' => true,
                                    'minimum' => 0,
                                ),
                                'bioload' => array(
                                    'type' => 'string',
                                    'enum' => array('low', 'medium', 'high'),
                                    'default' => 'medium',
                                ),
                            ),
                        ),
                    ),
                    'unit_system' => array(
                        'description' => __('Unit system (imperial or metric).'),
                        'type' => 'string',
                        'enum' => array('imperial', 'metric'),
                        'default' => 'imperial',
                    ),
                    'filtration_capacity' => array(
                        'description' => __('Filtration capacity in percentage of recommended.'),
                        'type' => 'number',
                        'default' => 100,
                        'minimum' => 0,
                    ),
                ),
            ),
        ));

        // Calculate medication dosage
        register_rest_route($this->namespace, '/' . $this->rest_base . '/medication-dosage', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'calculate_medication_dosage'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'tank_volume' => array(
                        'description' => __('Tank volume in gallons or liters.'),
                        'type' => 'number',
                        'required' => true,
                        'minimum' => 0,
                    ),
                    'dosage_rate' => array(
                        'description' => __('Dosage rate per gallon or liter.'),
                        'type' => 'number',
                        'required' => true,
                        'minimum' => 0,
                    ),
                    'dosage_unit' => array(
                        'description' => __('Dosage unit (ml, drops, g, etc.).'),
                        'type' => 'string',
                        'required' => true,
                    ),
                    'unit_system' => array(
                        'description' => __('Unit system (imperial or metric).'),
                        'type' => 'string',
                        'enum' => array('imperial', 'metric'),
                        'default' => 'imperial',
                    ),
                ),
            ),
        ));

        // Get water parameter recommendations
        register_rest_route($this->namespace, '/' . $this->rest_base . '/recommendations', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_parameter_recommendations'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'species' => array(
                        'description' => __('Fish species ID or slug.'),
                        'type' => 'string',
                    ),
                ),
            ),
        ));

        // Get water parameter recommendations by species
        register_rest_route($this->namespace, '/' . $this->rest_base . '/recommendations/(?P<species_id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_species_recommendations'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
                'args' => array(
                    'species_id' => array(
                        'description' => __('Fish species ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                ),
            ),
        ));

        // Save water test results
        register_rest_route($this->namespace, '/' . $this->rest_base . '/test-results', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'save_test_results'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => array(
                    'tank_id' => array(
                        'description' => __('Tank ID.'),
                        'type' => 'integer',
                        'required' => true,
                    ),
                    'date' => array(
                        'description' => __('Test date.'),
                        'type' => 'string',
                        'format' => 'date-time',
                        'default' => current_time('mysql'),
                    ),
                    'parameters' => array(
                        'description' => __('Water parameters.'),
                        'type' => 'object',
                        'required' => true,
                        'properties' => array(
                            'ph' => array('type' => 'number'),
                            'ammonia' => array('type' => 'number'),
                            'nitrite' => array('type' => 'number'),
                            'nitrate' => array('type' => 'number'),
                            'kh' => array('type' => 'number'),
                            'gh' => array('type' => 'number'),
                            'temperature' => array('type' => 'number'),
                            'tds' => array('type' => 'number'),
                        ),
                    ),
                    'notes' => array(
                        'description' => __('Test notes.'),
                        'type' => 'string',
                    ),
                ),
            ),
        ));

        // Get water test results
        register_rest_route($this->namespace, '/' . $this->rest_base . '/test-results/(?P<tank_id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_test_results'),
                'permission_callback' => array($this, 'get_item_permissions_check'),
                'args' => array(
                    'tank_id' => array(
                        'description' => __('Tank ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'limit' => array(
                        'description' => __('Number of results to return.'),
                        'type' => 'integer',
                        'default' => 10,
                        'minimum' => 1,
                        'maximum' => 100,
                    ),
                ),
            ),
        ));

        // Get user tanks
        register_rest_route($this->namespace, '/' . $this->rest_base . '/tanks', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_user_tanks'),
                'permission_callback' => array($this, 'get_items_permissions_check'),
            ),
        ));

        // Create tank
        register_rest_route($this->namespace, '/' . $this->rest_base . '/tanks', array(
            array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array($this, 'create_tank'),
                'permission_callback' => array($this, 'create_item_permissions_check'),
                'args' => array(
                    'name' => array(
                        'description' => __('Tank name.'),
                        'type' => 'string',
                        'required' => true,
                    ),
                    'volume' => array(
                        'description' => __('Tank volume in gallons or liters.'),
                        'type' => 'number',
                        'required' => true,
                        'minimum' => 0,
                    ),
                    'unit_system' => array(
                        'description' => __('Unit system (imperial or metric).'),
                        'type' => 'string',
                        'enum' => array('imperial', 'metric'),
                        'default' => 'imperial',
                    ),
                    'description' => array(
                        'description' => __('Tank description.'),
                        'type' => 'string',
                    ),
                    'setup_date' => array(
                        'description' => __('Tank setup date.'),
                        'type' => 'string',
                        'format' => 'date',
                    ),
                    'tank_type' => array(
                        'description' => __('Tank type.'),
                        'type' => 'string',
                        'enum' => array('freshwater', 'saltwater', 'brackish', 'planted'),
                    ),
                ),
            ),
        ));

        // Update tank
        register_rest_route($this->namespace, '/' . $this->rest_base . '/tanks/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, 'update_tank'),
                'permission_callback' => array($this, 'update_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Tank ID.'),
                        'type' => 'integer',
                        'required' => true,
                        'sanitize_callback' => 'absint',
                        'validate_callback' => 'rest_validate_request_arg',
                    ),
                    'name' => array(
                        'description' => __('Tank name.'),
                        'type' => 'string',
                    ),
                    'volume' => array(
                        'description' => __('Tank volume in gallons or liters.'),
                        'type' => 'number',
                        'minimum' => 0,
                    ),
                    'unit_system' => array(
                        'description' => __('Unit system (imperial or metric).'),
                        'type' => 'string',
                        'enum' => array('imperial', 'metric'),
                    ),
                    'description' => array(
                        'description' => __('Tank description.'),
                        'type' => 'string',
                    ),
                    'setup_date' => array(
                        'description' => __('Tank setup date.'),
                        'type' => 'string',
                        'format' => 'date',
                    ),
                    'tank_type' => array(
                        'description' => __('Tank type.'),
                        'type' => 'string',
                        'enum' => array('freshwater', 'saltwater', 'brackish', 'planted'),
                    ),
                ),
            ),
        ));

        // Delete tank
        register_rest_route($this->namespace, '/' . $this->rest_base . '/tanks/(?P<id>[\d]+)', array(
            array(
                'methods' => WP_REST_Server::DELETABLE,
                'callback' => array($this, 'delete_tank'),
                'permission_callback' => array($this, 'delete_item_permissions_check'),
                'args' => array(
                    'id' => array(
                        'description' => __('Tank ID.'),
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
     * Calculate water parameters.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function calculate_parameters($request) {
        $start_time = microtime(true);
        
        $tank_length = $request->get_param('tank_length');
        $tank_width = $request->get_param('tank_width');
        $tank_height = $request->get_param('tank_height');
        $unit_system = $request->get_param('unit_system');
        $substrate_depth = $request->get_param('substrate_depth');
        $decorations_volume = $request->get_param('decorations_volume');
        
        // Calculate tank volume
        if ($unit_system === 'imperial') {
            // Calculate in gallons (length, width, height in inches)
            $total_volume = ($tank_length * $tank_width * $tank_height) / 231;
            $substrate_volume = ($tank_length * $tank_width * $substrate_depth) / 231;
            $water_volume = $total_volume - $substrate_volume - $decorations_volume;
            
            $volume_unit = 'gallons';
            $dimension_unit = 'inches';
        } else {
            // Calculate in liters (length, width, height in centimeters)
            $total_volume = ($tank_length * $tank_width * $tank_height) / 1000;
            $substrate_volume = ($tank_length * $tank_width * $substrate_depth) / 1000;
            $water_volume = $total_volume - $substrate_volume - $decorations_volume;
            
            $volume_unit = 'liters';
            $dimension_unit = 'cm';
        }
        
        // Calculate surface area
        $surface_area = $tank_length * $tank_width;
        
        if ($unit_system === 'imperial') {
            $surface_area_unit = 'square inches';
        } else {
            $surface_area_unit = 'square cm';
        }
        
        // Calculate recommended fish load
        if ($unit_system === 'imperial') {
            // Rule of thumb: 1 inch of fish per gallon
            $recommended_fish_load = $water_volume;
            $fish_load_unit = 'inches';
        } else {
            // Rule of thumb: 1 cm of fish per 2 liters
            $recommended_fish_load = $water_volume / 2;
            $fish_load_unit = 'cm';
        }
        
        // Calculate recommended filter capacity
        $recommended_filter_capacity = $water_volume * 4;
        $filter_capacity_unit = $unit_system === 'imperial' ? 'gallons per hour' : 'liters per hour';
        
        // Calculate recommended heater wattage
        if ($unit_system === 'imperial') {
            // Rule of thumb: 5 watts per gallon
            $recommended_heater_wattage = $water_volume * 5;
        } else {
            // Rule of thumb: 1.3 watts per liter
            $recommended_heater_wattage = $water_volume * 1.3;
        }
        
        // Prepare response
        $data = array(
            'dimensions' => array(
                'length' => $tank_length,
                'width' => $tank_width,
                'height' => $tank_height,
                'unit' => $dimension_unit,
            ),
            'volume' => array(
                'total' => round($total_volume, 2),
                'substrate' => round($substrate_volume, 2),
                'decorations' => round($decorations_volume, 2),
                'water' => round($water_volume, 2),
                'unit' => $volume_unit,
            ),
            'surface_area' => array(
                'value' => round($surface_area, 2),
                'unit' => $surface_area_unit,
            ),
            'recommendations' => array(
                'fish_load' => array(
                    'value' => round($recommended_fish_load, 2),
                    'unit' => $fish_load_unit,
                ),
                'filter_capacity' => array(
                    'value' => round($recommended_filter_capacity, 2),
                    'unit' => $filter_capacity_unit,
                ),
                'heater_wattage' => array(
                    'value' => round($recommended_heater_wattage),
                    'unit' => 'watts',
                ),
            ),
        );
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Calculate water change.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function calculate_water_change($request) {
        $start_time = microtime(true);
        
        $tank_volume = $request->get_param('tank_volume');
        $change_percentage = $request->get_param('change_percentage');
        $unit_system = $request->get_param('unit_system');
        
        // Calculate water change volume
        $change_volume = ($tank_volume * $change_percentage) / 100;
        
        // Calculate water conditioner dosage
        if ($unit_system === 'imperial') {
            // Typical dosage: 1 ml per 10 gallons
            $conditioner_dosage = $change_volume / 10;
            $conditioner_unit = 'ml';
            $volume_unit = 'gallons';
        } else {
            // Typical dosage: 1 ml per 40 liters
            $conditioner_dosage = $change_volume / 40;
            $conditioner_unit = 'ml';
            $volume_unit = 'liters';
        }
        
        // Calculate impact on parameters
        $parameter_reduction = $change_percentage / 100;
        
        // Prepare response
        $data = array(
            'tank_volume' => array(
                'value' => $tank_volume,
                'unit' => $volume_unit,
            ),
            'change_percentage' => $change_percentage,
            'change_volume' => array(
                'value' => round($change_volume, 2),
                'unit' => $volume_unit,
            ),
            'conditioner_dosage' => array(
                'value' => round($conditioner_dosage, 2),
                'unit' => $conditioner_unit,
            ),
            'parameter_impact' => array(
                'reduction_percentage' => $change_percentage,
                'reduction_factor' => round($parameter_reduction, 2),
                'note' => 'This is the percentage by which water parameters like nitrate will be reduced.',
            ),
        );
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Calculate stocking level.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function calculate_stocking_level($request) {
        $start_time = microtime(true);
        
        $tank_volume = $request->get_param('tank_volume');
        $fish = $request->get_param('fish');
        $unit_system = $request->get_param('unit_system');
        $filtration_capacity = $request->get_param('filtration_capacity');
        
        // Calculate stocking level
        $total_bioload = 0;
        $total_length = 0;
        $fish_details = array();
        
        foreach ($fish as $fish_item) {
            $name = $fish_item['name'];
            $quantity = $fish_item['quantity'];
            $adult_size = $fish_item['adult_size'];
            $bioload = $fish_item['bioload'];
            
            // Calculate bioload factor
            $bioload_factor = 1;
            
            if ($bioload === 'low') {
                $bioload_factor = 0.7;
            } elseif ($bioload === 'high') {
                $bioload_factor = 1.5;
            }
            
            // Calculate fish bioload
            $fish_bioload = $quantity * $adult_size * $bioload_factor;
            $total_bioload += $fish_bioload;
            
            // Calculate total length
            $fish_length = $quantity * $adult_size;
            $total_length += $fish_length;
            
            // Add fish details
            $fish_details[] = array(
                'name' => $name,
                'quantity' => $quantity,
                'adult_size' => $adult_size,
                'bioload' => $bioload,
                'bioload_factor' => $bioload_factor,
                'fish_bioload' => round($fish_bioload, 2),
                'fish_length' => round($fish_length, 2),
            );
        }
        
        // Calculate stocking percentage
        if ($unit_system === 'imperial') {
            // Rule of thumb: 1 inch of fish per gallon
            $max_bioload = $tank_volume;
        } else {
            // Rule of thumb: 1 cm of fish per 2 liters
            $max_bioload = $tank_volume / 2;
        }
        
        // Adjust for filtration capacity
        $adjusted_max_bioload = $max_bioload * ($filtration_capacity / 100);
        
        // Calculate stocking percentage
        $stocking_percentage = ($total_bioload / $adjusted_max_bioload) * 100;
        
        // Determine stocking level
        $stocking_level = 'Optimal';
        $stocking_advice = 'Your tank is stocked at an optimal level.';
        
        if ($stocking_percentage > 100) {
            $stocking_level = 'Overstocked';
            $stocking_advice = 'Your tank is overstocked. Consider reducing the number of fish or upgrading to a larger tank.';
        } elseif ($stocking_percentage > 85) {
            $stocking_level = 'Heavily Stocked';
            $stocking_advice = 'Your tank is heavily stocked. Monitor water parameters closely and perform regular water changes.';
        } elseif ($stocking_percentage < 50) {
            $stocking_level = 'Understocked';
            $stocking_advice = 'Your tank is understocked. You have room for more fish if desired.';
        }
        
        // Prepare response
        $data = array(
            'tank_volume' => array(
                'value' => $tank_volume,
                'unit' => $unit_system === 'imperial' ? 'gallons' : 'liters',
            ),
            'filtration_capacity' => array(
                'percentage' => $filtration_capacity,
                'adjusted_max_bioload' => round($adjusted_max_bioload, 2),
                'unit' => $unit_system === 'imperial' ? 'inches' : 'cm',
            ),
            'fish_details' => $fish_details,
            'total_length' => array(
                'value' => round($total_length, 2),
                'unit' => $unit_system === 'imperial' ? 'inches' : 'cm',
            ),
            'total_bioload' => round($total_bioload, 2),
            'max_bioload' => round($max_bioload, 2),
            'stocking_percentage' => round($stocking_percentage, 2),
            'stocking_level' => $stocking_level,
            'stocking_advice' => $stocking_advice,
        );
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Calculate medication dosage.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function calculate_medication_dosage($request) {
        $start_time = microtime(true);
        
        $tank_volume = $request->get_param('tank_volume');
        $dosage_rate = $request->get_param('dosage_rate');
        $dosage_unit = $request->get_param('dosage_unit');
        $unit_system = $request->get_param('unit_system');
        
        // Calculate dosage
        $dosage = $tank_volume * $dosage_rate;
        
        // Prepare response
        $data = array(
            'tank_volume' => array(
                'value' => $tank_volume,
                'unit' => $unit_system === 'imperial' ? 'gallons' : 'liters',
            ),
            'dosage_rate' => array(
                'value' => $dosage_rate,
                'unit' => $dosage_unit . ' per ' . ($unit_system === 'imperial' ? 'gallon' : 'liter'),
            ),
            'total_dosage' => array(
                'value' => round($dosage, 2),
                'unit' => $dosage_unit,
            ),
            'warning' => 'Always follow the manufacturer\'s instructions and consult with a professional if unsure.',
        );
        
        // Prepare response
        $response = $this->format_response($data);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get water parameter recommendations.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_parameter_recommendations($request) {
        $start_time = microtime(true);
        
        $species = $request->get_param('species');
        
        // Get species term
        if ($species) {
            $term = is_numeric($species) ? get_term($species, 'fish_species') : get_term_by('slug', $species, 'fish_species');
            
            if (!$term || is_wp_error($term)) {
                return $this->format_error('species_not_found', __('Species not found.'), 404);
            }
            
            // Get species recommendations
            $recommendations = $this->get_species_parameter_recommendations($term->term_id);
        } else {
            // Get general recommendations
            $recommendations = $this->get_general_parameter_recommendations();
        }
        
        // Prepare response
        $response = $this->format_response($recommendations);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get water parameter recommendations by species.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_species_recommendations($request) {
        $start_time = microtime(true);
        
        $species_id = $request->get_param('species_id');
        
        // Get species term
        $term = get_term($species_id, 'fish_species');
        
        if (!$term || is_wp_error($term)) {
            return $this->format_error('species_not_found', __('Species not found.'), 404);
        }
        
        // Get species recommendations
        $recommendations = $this->get_species_parameter_recommendations($species_id);
        
        // Add species info
        $recommendations['species'] = array(
            'id' => $term->term_id,
            'name' => $term->name,
            'slug' => $term->slug,
            'description' => $term->description,
            'image' => $this->get_term_image($term->term_id, 'fish_species'),
        );
        
        // Prepare response
        $response = $this->format_response($recommendations);
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Save water test results.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function save_test_results($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $tank_id = $request->get_param('tank_id');
        $date = $request->get_param('date');
        $parameters = $request->get_param('parameters');
        $notes = $request->get_param('notes');
        
        // Check if tank exists and belongs to user
        $tank = $this->get_tank($tank_id);
        
        if (!$tank) {
            return $this->format_error('tank_not_found', __('Tank not found.'), 404);
        }
        
        if ($tank->user_id !== $user_id) {
            return $this->format_error('permission_denied', __('You do not have permission to update this tank.'), 403);
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_water_tests';
        
        // Insert test results
        $inserted = $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'tank_id' => $tank_id,
                'test_date' => $date,
                'ph' => isset($parameters['ph']) ? $parameters['ph'] : null,
                'ammonia' => isset($parameters['ammonia']) ? $parameters['ammonia'] : null,
                'nitrite' => isset($parameters['nitrite']) ? $parameters['nitrite'] : null,
                'nitrate' => isset($parameters['nitrate']) ? $parameters['nitrate'] : null,
                'kh' => isset($parameters['kh']) ? $parameters['kh'] : null,
                'gh' => isset($parameters['gh']) ? $parameters['gh'] : null,
                'temperature' => isset($parameters['temperature']) ? $parameters['temperature'] : null,
                'tds' => isset($parameters['tds']) ? $parameters['tds'] : null,
                'notes' => $notes,
                'created_at' => current_time('mysql'),
            ),
            array(
                '%d',
                '%d',
                '%s',
                '%f',
                '%f',
                '%f',
                '%f',
                '%f',
                '%f',
                '%f',
                '%f',
                '%s',
                '%s',
            )
        );
        
        if ($inserted === false) {
            return $this->format_error('insert_failed', __('Failed to save test results.'), 500);
        }
        
        $test_id = $wpdb->insert_id;
        
        // Get test results
        $test = $this->get_test($test_id);
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'test' => $test,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get water test results.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_test_results($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $tank_id = $request->get_param('tank_id');
        $limit = $request->get_param('limit');
        
        // Check if tank exists and belongs to user
        $tank = $this->get_tank($tank_id);
        
        if (!$tank) {
            return $this->format_error('tank_not_found', __('Tank not found.'), 404);
        }
        
        if ($tank->user_id !== $user_id) {
            return $this->format_error('permission_denied', __('You do not have permission to view this tank.'), 403);
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_water_tests';
        
        // Get test results
        $tests = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE user_id = %d AND tank_id = %d ORDER BY test_date DESC LIMIT %d",
                $user_id,
                $tank_id,
                $limit
            )
        );
        
        $data = array();
        
        foreach ($tests as $test) {
            $data[] = array(
                'id' => (int) $test->id,
                'tank_id' => (int) $test->tank_id,
                'test_date' => $test->test_date,
                'parameters' => array(
                    'ph' => $test->ph !== null ? (float) $test->ph : null,
                    'ammonia' => $test->ammonia !== null ? (float) $test->ammonia : null,
                    'nitrite' => $test->nitrite !== null ? (float) $test->nitrite : null,
                    'nitrate' => $test->nitrate !== null ? (float) $test->nitrate : null,
                    'kh' => $test->kh !== null ? (float) $test->kh : null,
                    'gh' => $test->gh !== null ? (float) $test->gh : null,
                    'temperature' => $test->temperature !== null ? (float) $test->temperature : null,
                    'tds' => $test->tds !== null ? (float) $test->tds : null,
                ),
                'notes' => $test->notes,
                'created_at' => $test->created_at,
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'tank' => array(
                'id' => $tank->id,
                'name' => $tank->name,
                'volume' => $tank->volume,
                'unit_system' => $tank->unit_system,
            ),
            'tests' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get user tanks.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function get_user_tanks($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_tanks';
        
        // Get tanks
        $tanks = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE user_id = %d ORDER BY name ASC",
                $user_id
            )
        );
        
        $data = array();
        
        foreach ($tanks as $tank) {
            $data[] = array(
                'id' => (int) $tank->id,
                'name' => $tank->name,
                'volume' => (float) $tank->volume,
                'unit_system' => $tank->unit_system,
                'description' => $tank->description,
                'setup_date' => $tank->setup_date,
                'tank_type' => $tank->tank_type,
                'created_at' => $tank->created_at,
                'updated_at' => $tank->updated_at,
            );
        }
        
        // Prepare response
        $response = $this->format_response(array(
            'tanks' => $data,
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Create tank.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function create_tank($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $name = $request->get_param('name');
        $volume = $request->get_param('volume');
        $unit_system = $request->get_param('unit_system');
        $description = $request->get_param('description');
        $setup_date = $request->get_param('setup_date');
        $tank_type = $request->get_param('tank_type');
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_tanks';
        
        // Insert tank
        $inserted = $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'name' => $name,
                'volume' => $volume,
                'unit_system' => $unit_system,
                'description' => $description,
                'setup_date' => $setup_date,
                'tank_type' => $tank_type,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
            ),
            array(
                '%d',
                '%s',
                '%f',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );
        
        if ($inserted === false) {
            return $this->format_error('insert_failed', __('Failed to create tank.'), 500);
        }
        
        $tank_id = $wpdb->insert_id;
        
        // Get tank
        $tank = $this->get_tank($tank_id);
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'tank' => array(
                'id' => (int) $tank->id,
                'name' => $tank->name,
                'volume' => (float) $tank->volume,
                'unit_system' => $tank->unit_system,
                'description' => $tank->description,
                'setup_date' => $tank->setup_date,
                'tank_type' => $tank->tank_type,
                'created_at' => $tank->created_at,
                'updated_at' => $tank->updated_at,
            ),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Update tank.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function update_tank($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $tank_id = $request->get_param('id');
        
        // Check if tank exists and belongs to user
        $tank = $this->get_tank($tank_id);
        
        if (!$tank) {
            return $this->format_error('tank_not_found', __('Tank not found.'), 404);
        }
        
        if ($tank->user_id !== $user_id) {
            return $this->format_error('permission_denied', __('You do not have permission to update this tank.'), 403);
        }
        
        $name = $request->get_param('name');
        $volume = $request->get_param('volume');
        $unit_system = $request->get_param('unit_system');
        $description = $request->get_param('description');
        $setup_date = $request->get_param('setup_date');
        $tank_type = $request->get_param('tank_type');
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_tanks';
        
        // Prepare update data
        $data = array(
            'updated_at' => current_time('mysql'),
        );
        
        $formats = array('%s');
        
        if ($name !== null) {
            $data['name'] = $name;
            $formats[] = '%s';
        }
        
        if ($volume !== null) {
            $data['volume'] = $volume;
            $formats[] = '%f';
        }
        
        if ($unit_system !== null) {
            $data['unit_system'] = $unit_system;
            $formats[] = '%s';
        }
        
        if ($description !== null) {
            $data['description'] = $description;
            $formats[] = '%s';
        }
        
        if ($setup_date !== null) {
            $data['setup_date'] = $setup_date;
            $formats[] = '%s';
        }
        
        if ($tank_type !== null) {
            $data['tank_type'] = $tank_type;
            $formats[] = '%s';
        }
        
        // Update tank
        $updated = $wpdb->update(
            $table_name,
            $data,
            array('id' => $tank_id),
            $formats,
            array('%d')
        );
        
        if ($updated === false) {
            return $this->format_error('update_failed', __('Failed to update tank.'), 500);
        }
        
        // Get updated tank
        $tank = $this->get_tank($tank_id);
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'tank' => array(
                'id' => (int) $tank->id,
                'name' => $tank->name,
                'volume' => (float) $tank->volume,
                'unit_system' => $tank->unit_system,
                'description' => $tank->description,
                'setup_date' => $tank->setup_date,
                'tank_type' => $tank->tank_type,
                'created_at' => $tank->created_at,
                'updated_at' => $tank->updated_at,
            ),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Delete tank.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error
     */
    public function delete_tank($request) {
        $start_time = microtime(true);
        
        $user_id = $this->get_current_user_id();
        $tank_id = $request->get_param('id');
        
        // Check if tank exists and belongs to user
        $tank = $this->get_tank($tank_id);
        
        if (!$tank) {
            return $this->format_error('tank_not_found', __('Tank not found.'), 404);
        }
        
        if ($tank->user_id !== $user_id) {
            return $this->format_error('permission_denied', __('You do not have permission to delete this tank.'), 403);
        }
        
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_tanks';
        
        // Delete tank
        $deleted = $wpdb->delete(
            $table_name,
            array('id' => $tank_id),
            array('%d')
        );
        
        if ($deleted === false) {
            return $this->format_error('delete_failed', __('Failed to delete tank.'), 500);
        }
        
        // Delete associated test results
        $table_name = $wpdb->prefix . 'aqualuxe_water_tests';
        
        $wpdb->delete(
            $table_name,
            array('tank_id' => $tank_id),
            array('%d')
        );
        
        // Prepare response
        $response = $this->format_response(array(
            'success' => true,
            'message' => __('Tank deleted successfully.'),
        ));
        
        // Log request
        $this->log_request($request, $response, microtime(true) - $start_time);
        
        return $response;
    }

    /**
     * Get tank by ID.
     *
     * @param int $tank_id Tank ID.
     * @return object|false
     */
    private function get_tank($tank_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_tanks';
        
        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d",
                $tank_id
            )
        );
    }

    /**
     * Get test by ID.
     *
     * @param int $test_id Test ID.
     * @return array|false
     */
    private function get_test($test_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_water_tests';
        
        $test = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE id = %d",
                $test_id
            )
        );
        
        if (!$test) {
            return false;
        }
        
        return array(
            'id' => (int) $test->id,
            'tank_id' => (int) $test->tank_id,
            'test_date' => $test->test_date,
            'parameters' => array(
                'ph' => $test->ph !== null ? (float) $test->ph : null,
                'ammonia' => $test->ammonia !== null ? (float) $test->ammonia : null,
                'nitrite' => $test->nitrite !== null ? (float) $test->nitrite : null,
                'nitrate' => $test->nitrate !== null ? (float) $test->nitrate : null,
                'kh' => $test->kh !== null ? (float) $test->kh : null,
                'gh' => $test->gh !== null ? (float) $test->gh : null,
                'temperature' => $test->temperature !== null ? (float) $test->temperature : null,
                'tds' => $test->tds !== null ? (float) $test->tds : null,
            ),
            'notes' => $test->notes,
            'created_at' => $test->created_at,
        );
    }

    /**
     * Get general parameter recommendations.
     *
     * @return array
     */
    private function get_general_parameter_recommendations() {
        return array(
            'freshwater' => array(
                'ph' => array(
                    'min' => 6.5,
                    'max' => 7.5,
                    'ideal' => 7.0,
                    'unit' => 'pH',
                ),
                'ammonia' => array(
                    'min' => 0,
                    'max' => 0,
                    'ideal' => 0,
                    'unit' => 'ppm',
                ),
                'nitrite' => array(
                    'min' => 0,
                    'max' => 0,
                    'ideal' => 0,
                    'unit' => 'ppm',
                ),
                'nitrate' => array(
                    'min' => 0,
                    'max' => 20,
                    'ideal' => 5,
                    'unit' => 'ppm',
                ),
                'kh' => array(
                    'min' => 4,
                    'max' => 8,
                    'ideal' => 6,
                    'unit' => 'dKH',
                ),
                'gh' => array(
                    'min' => 4,
                    'max' => 12,
                    'ideal' => 7,
                    'unit' => 'dGH',
                ),
                'temperature' => array(
                    'min' => 72,
                    'max' => 82,
                    'ideal' => 78,
                    'unit' => '°F',
                    'min_c' => 22,
                    'max_c' => 28,
                    'ideal_c' => 25,
                ),
            ),
            'saltwater' => array(
                'ph' => array(
                    'min' => 8.0,
                    'max' => 8.4,
                    'ideal' => 8.2,
                    'unit' => 'pH',
                ),
                'ammonia' => array(
                    'min' => 0,
                    'max' => 0,
                    'ideal' => 0,
                    'unit' => 'ppm',
                ),
                'nitrite' => array(
                    'min' => 0,
                    'max' => 0,
                    'ideal' => 0,
                    'unit' => 'ppm',
                ),
                'nitrate' => array(
                    'min' => 0,
                    'max' => 10,
                    'ideal' => 0,
                    'unit' => 'ppm',
                ),
                'calcium' => array(
                    'min' => 380,
                    'max' => 450,
                    'ideal' => 420,
                    'unit' => 'ppm',
                ),
                'alkalinity' => array(
                    'min' => 7,
                    'max' => 12,
                    'ideal' => 9,
                    'unit' => 'dKH',
                ),
                'salinity' => array(
                    'min' => 1.023,
                    'max' => 1.025,
                    'ideal' => 1.024,
                    'unit' => 'sg',
                ),
                'temperature' => array(
                    'min' => 75,
                    'max' => 82,
                    'ideal' => 78,
                    'unit' => '°F',
                    'min_c' => 24,
                    'max_c' => 28,
                    'ideal_c' => 26,
                ),
            ),
            'planted' => array(
                'ph' => array(
                    'min' => 6.0,
                    'max' => 7.0,
                    'ideal' => 6.5,
                    'unit' => 'pH',
                ),
                'ammonia' => array(
                    'min' => 0,
                    'max' => 0,
                    'ideal' => 0,
                    'unit' => 'ppm',
                ),
                'nitrite' => array(
                    'min' => 0,
                    'max' => 0,
                    'ideal' => 0,
                    'unit' => 'ppm',
                ),
                'nitrate' => array(
                    'min' => 5,
                    'max' => 30,
                    'ideal' => 15,
                    'unit' => 'ppm',
                ),
                'kh' => array(
                    'min' => 2,
                    'max' => 6,
                    'ideal' => 4,
                    'unit' => 'dKH',
                ),
                'gh' => array(
                    'min' => 3,
                    'max' => 8,
                    'ideal' => 5,
                    'unit' => 'dGH',
                ),
                'co2' => array(
                    'min' => 15,
                    'max' => 30,
                    'ideal' => 20,
                    'unit' => 'ppm',
                ),
                'temperature' => array(
                    'min' => 72,
                    'max' => 82,
                    'ideal' => 76,
                    'unit' => '°F',
                    'min_c' => 22,
                    'max_c' => 28,
                    'ideal_c' => 24,
                ),
            ),
            'brackish' => array(
                'ph' => array(
                    'min' => 7.5,
                    'max' => 8.2,
                    'ideal' => 7.8,
                    'unit' => 'pH',
                ),
                'ammonia' => array(
                    'min' => 0,
                    'max' => 0,
                    'ideal' => 0,
                    'unit' => 'ppm',
                ),
                'nitrite' => array(
                    'min' => 0,
                    'max' => 0,
                    'ideal' => 0,
                    'unit' => 'ppm',
                ),
                'nitrate' => array(
                    'min' => 0,
                    'max' => 20,
                    'ideal' => 10,
                    'unit' => 'ppm',
                ),
                'kh' => array(
                    'min' => 6,
                    'max' => 10,
                    'ideal' => 8,
                    'unit' => 'dKH',
                ),
                'gh' => array(
                    'min' => 8,
                    'max' => 14,
                    'ideal' => 10,
                    'unit' => 'dGH',
                ),
                'salinity' => array(
                    'min' => 1.005,
                    'max' => 1.015,
                    'ideal' => 1.010,
                    'unit' => 'sg',
                ),
                'temperature' => array(
                    'min' => 75,
                    'max' => 82,
                    'ideal' => 78,
                    'unit' => '°F',
                    'min_c' => 24,
                    'max_c' => 28,
                    'ideal_c' => 26,
                ),
            ),
        );
    }

    /**
     * Get species parameter recommendations.
     *
     * @param int $species_id Species ID.
     * @return array
     */
    private function get_species_parameter_recommendations($species_id) {
        // Get species meta
        $ph_min = get_term_meta($species_id, '_species_ph_min', true);
        $ph_max = get_term_meta($species_id, '_species_ph_max', true);
        $temp_min = get_term_meta($species_id, '_species_temp_min', true);
        $temp_max = get_term_meta($species_id, '_species_temp_max', true);
        $gh_min = get_term_meta($species_id, '_species_gh_min', true);
        $gh_max = get_term_meta($species_id, '_species_gh_max', true);
        $kh_min = get_term_meta($species_id, '_species_kh_min', true);
        $kh_max = get_term_meta($species_id, '_species_kh_max', true);
        $water_type = get_term_meta($species_id, '_species_water_type', true);
        
        // Get general recommendations
        $general = $this->get_general_parameter_recommendations();
        
        // Use general recommendations for water type if not specified
        if (!$water_type || !isset($general[$water_type])) {
            $water_type = 'freshwater';
        }
        
        $recommendations = $general[$water_type];
        
        // Override with species-specific values if available
        if ($ph_min !== '' && $ph_max !== '') {
            $recommendations['ph']['min'] = (float) $ph_min;
            $recommendations['ph']['max'] = (float) $ph_max;
            $recommendations['ph']['ideal'] = ($ph_min + $ph_max) / 2;
        }
        
        if ($temp_min !== '' && $temp_max !== '') {
            $temp_min_f = (float) $temp_min;
            $temp_max_f = (float) $temp_max;
            $temp_min_c = ($temp_min_f - 32) * 5 / 9;
            $temp_max_c = ($temp_max_f - 32) * 5 / 9;
            
            $recommendations['temperature']['min'] = $temp_min_f;
            $recommendations['temperature']['max'] = $temp_max_f;
            $recommendations['temperature']['ideal'] = ($temp_min_f + $temp_max_f) / 2;
            $recommendations['temperature']['min_c'] = round($temp_min_c, 1);
            $recommendations['temperature']['max_c'] = round($temp_max_c, 1);
            $recommendations['temperature']['ideal_c'] = round(($temp_min_c + $temp_max_c) / 2, 1);
        }
        
        if ($gh_min !== '' && $gh_max !== '') {
            $recommendations['gh']['min'] = (float) $gh_min;
            $recommendations['gh']['max'] = (float) $gh_max;
            $recommendations['gh']['ideal'] = ($gh_min + $gh_max) / 2;
        }
        
        if ($kh_min !== '' && $kh_max !== '') {
            $recommendations['kh']['min'] = (float) $kh_min;
            $recommendations['kh']['max'] = (float) $kh_max;
            $recommendations['kh']['ideal'] = ($kh_min + $kh_max) / 2;
        }
        
        return array(
            'water_type' => $water_type,
            'parameters' => $recommendations,
        );
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