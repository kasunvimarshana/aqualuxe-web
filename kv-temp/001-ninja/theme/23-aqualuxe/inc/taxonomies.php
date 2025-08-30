<?php
/**
 * Custom Taxonomies for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register custom taxonomies
 */
function aqualuxe_register_taxonomies() {
    // Service Category Taxonomy
    register_taxonomy(
        'service_category',
        'service',
        array(
            'labels'            => array(
                'name'              => _x( 'Service Categories', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Service Category', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Service Categories', 'aqualuxe' ),
                'all_items'         => __( 'All Service Categories', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Service Category', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Service Category:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Service Category', 'aqualuxe' ),
                'update_item'       => __( 'Update Service Category', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Service Category', 'aqualuxe' ),
                'new_item_name'     => __( 'New Service Category Name', 'aqualuxe' ),
                'menu_name'         => __( 'Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'service-category' ),
            'show_in_rest'      => true,
        )
    );

    // Event Category Taxonomy
    register_taxonomy(
        'event_category',
        'event',
        array(
            'labels'            => array(
                'name'              => _x( 'Event Categories', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Event Categories', 'aqualuxe' ),
                'all_items'         => __( 'All Event Categories', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Event Category', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Event Category:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Event Category', 'aqualuxe' ),
                'update_item'       => __( 'Update Event Category', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Event Category', 'aqualuxe' ),
                'new_item_name'     => __( 'New Event Category Name', 'aqualuxe' ),
                'menu_name'         => __( 'Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'event-category' ),
            'show_in_rest'      => true,
        )
    );

    // Event Tag Taxonomy
    register_taxonomy(
        'event_tag',
        'event',
        array(
            'labels'            => array(
                'name'              => _x( 'Event Tags', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Event Tag', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Event Tags', 'aqualuxe' ),
                'all_items'         => __( 'All Event Tags', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Event Tag', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Event Tag:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Event Tag', 'aqualuxe' ),
                'update_item'       => __( 'Update Event Tag', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Event Tag', 'aqualuxe' ),
                'new_item_name'     => __( 'New Event Tag Name', 'aqualuxe' ),
                'menu_name'         => __( 'Tags', 'aqualuxe' ),
            ),
            'hierarchical'      => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'event-tag' ),
            'show_in_rest'      => true,
        )
    );

    // Department Taxonomy (for Team Members)
    register_taxonomy(
        'department',
        'team_member',
        array(
            'labels'            => array(
                'name'              => _x( 'Departments', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Department', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Departments', 'aqualuxe' ),
                'all_items'         => __( 'All Departments', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Department', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Department:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Department', 'aqualuxe' ),
                'update_item'       => __( 'Update Department', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Department', 'aqualuxe' ),
                'new_item_name'     => __( 'New Department Name', 'aqualuxe' ),
                'menu_name'         => __( 'Departments', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'department' ),
            'show_in_rest'      => true,
        )
    );

    // Testimonial Category Taxonomy
    register_taxonomy(
        'testimonial_category',
        'testimonial',
        array(
            'labels'            => array(
                'name'              => _x( 'Testimonial Categories', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Testimonial Category', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Testimonial Categories', 'aqualuxe' ),
                'all_items'         => __( 'All Testimonial Categories', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Testimonial Category', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Testimonial Category:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Testimonial Category', 'aqualuxe' ),
                'update_item'       => __( 'Update Testimonial Category', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Testimonial Category', 'aqualuxe' ),
                'new_item_name'     => __( 'New Testimonial Category Name', 'aqualuxe' ),
                'menu_name'         => __( 'Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'testimonial-category' ),
            'show_in_rest'      => true,
        )
    );

    // Project Category Taxonomy
    register_taxonomy(
        'project_category',
        'project',
        array(
            'labels'            => array(
                'name'              => _x( 'Project Categories', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Project Category', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Project Categories', 'aqualuxe' ),
                'all_items'         => __( 'All Project Categories', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Project Category', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Project Category:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Project Category', 'aqualuxe' ),
                'update_item'       => __( 'Update Project Category', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Project Category', 'aqualuxe' ),
                'new_item_name'     => __( 'New Project Category Name', 'aqualuxe' ),
                'menu_name'         => __( 'Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'project-category' ),
            'show_in_rest'      => true,
        )
    );

    // Project Tag Taxonomy
    register_taxonomy(
        'project_tag',
        'project',
        array(
            'labels'            => array(
                'name'              => _x( 'Project Tags', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Project Tag', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Project Tags', 'aqualuxe' ),
                'all_items'         => __( 'All Project Tags', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Project Tag', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Project Tag:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Project Tag', 'aqualuxe' ),
                'update_item'       => __( 'Update Project Tag', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Project Tag', 'aqualuxe' ),
                'new_item_name'     => __( 'New Project Tag Name', 'aqualuxe' ),
                'menu_name'         => __( 'Tags', 'aqualuxe' ),
            ),
            'hierarchical'      => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'project-tag' ),
            'show_in_rest'      => true,
        )
    );

    // FAQ Category Taxonomy
    register_taxonomy(
        'faq_category',
        'faq',
        array(
            'labels'            => array(
                'name'              => _x( 'FAQ Categories', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'FAQ Category', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search FAQ Categories', 'aqualuxe' ),
                'all_items'         => __( 'All FAQ Categories', 'aqualuxe' ),
                'parent_item'       => __( 'Parent FAQ Category', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent FAQ Category:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit FAQ Category', 'aqualuxe' ),
                'update_item'       => __( 'Update FAQ Category', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New FAQ Category', 'aqualuxe' ),
                'new_item_name'     => __( 'New FAQ Category Name', 'aqualuxe' ),
                'menu_name'         => __( 'Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'faq-category' ),
            'show_in_rest'      => true,
        )
    );

    // Fish Species Taxonomy (for WooCommerce products)
    register_taxonomy(
        'fish_species',
        'product',
        array(
            'labels'            => array(
                'name'              => _x( 'Fish Species', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Fish Species', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Fish Species', 'aqualuxe' ),
                'all_items'         => __( 'All Fish Species', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Fish Species', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Fish Species:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Fish Species', 'aqualuxe' ),
                'update_item'       => __( 'Update Fish Species', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Fish Species', 'aqualuxe' ),
                'new_item_name'     => __( 'New Fish Species Name', 'aqualuxe' ),
                'menu_name'         => __( 'Fish Species', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'fish-species' ),
            'show_in_rest'      => true,
        )
    );

    // Water Type Taxonomy (for WooCommerce products)
    register_taxonomy(
        'water_type',
        'product',
        array(
            'labels'            => array(
                'name'              => _x( 'Water Types', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Water Type', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Water Types', 'aqualuxe' ),
                'all_items'         => __( 'All Water Types', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Water Type', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Water Type:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Water Type', 'aqualuxe' ),
                'update_item'       => __( 'Update Water Type', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Water Type', 'aqualuxe' ),
                'new_item_name'     => __( 'New Water Type Name', 'aqualuxe' ),
                'menu_name'         => __( 'Water Types', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'water-type' ),
            'show_in_rest'      => true,
        )
    );

    // Care Level Taxonomy (for WooCommerce products)
    register_taxonomy(
        'care_level',
        'product',
        array(
            'labels'            => array(
                'name'              => _x( 'Care Levels', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Care Level', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Care Levels', 'aqualuxe' ),
                'all_items'         => __( 'All Care Levels', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Care Level', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Care Level:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Care Level', 'aqualuxe' ),
                'update_item'       => __( 'Update Care Level', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Care Level', 'aqualuxe' ),
                'new_item_name'     => __( 'New Care Level Name', 'aqualuxe' ),
                'menu_name'         => __( 'Care Levels', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'care-level' ),
            'show_in_rest'      => true,
        )
    );

    // Plant Type Taxonomy (for WooCommerce products)
    register_taxonomy(
        'plant_type',
        'product',
        array(
            'labels'            => array(
                'name'              => _x( 'Plant Types', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Plant Type', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Plant Types', 'aqualuxe' ),
                'all_items'         => __( 'All Plant Types', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Plant Type', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Plant Type:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Plant Type', 'aqualuxe' ),
                'update_item'       => __( 'Update Plant Type', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Plant Type', 'aqualuxe' ),
                'new_item_name'     => __( 'New Plant Type Name', 'aqualuxe' ),
                'menu_name'         => __( 'Plant Types', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'plant-type' ),
            'show_in_rest'      => true,
        )
    );

    // Tank Size Taxonomy (for WooCommerce products)
    register_taxonomy(
        'tank_size',
        'product',
        array(
            'labels'            => array(
                'name'              => _x( 'Tank Sizes', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Tank Size', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Tank Sizes', 'aqualuxe' ),
                'all_items'         => __( 'All Tank Sizes', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Tank Size', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Tank Size:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Tank Size', 'aqualuxe' ),
                'update_item'       => __( 'Update Tank Size', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Tank Size', 'aqualuxe' ),
                'new_item_name'     => __( 'New Tank Size Name', 'aqualuxe' ),
                'menu_name'         => __( 'Tank Sizes', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'tank-size' ),
            'show_in_rest'      => true,
        )
    );
}
add_action( 'init', 'aqualuxe_register_taxonomies' );

/**
 * Add custom taxonomy meta fields
 */
function aqualuxe_add_taxonomy_fields( $taxonomy ) {
    // Service Category Icon
    if ( 'service_category' === $taxonomy ) {
        ?>
        <div class="form-field">
            <label for="category_icon"><?php esc_html_e( 'Category Icon', 'aqualuxe' ); ?></label>
            <input type="text" name="category_icon" id="category_icon" value="" />
            <p class="description"><?php esc_html_e( 'Enter a Font Awesome icon class (e.g., "fa-fish") or a URL to a custom icon image.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    // Fish Species Meta
    if ( 'fish_species' === $taxonomy ) {
        ?>
        <div class="form-field">
            <label for="species_scientific_name"><?php esc_html_e( 'Scientific Name', 'aqualuxe' ); ?></label>
            <input type="text" name="species_scientific_name" id="species_scientific_name" value="" />
        </div>
        <div class="form-field">
            <label for="species_origin"><?php esc_html_e( 'Origin', 'aqualuxe' ); ?></label>
            <input type="text" name="species_origin" id="species_origin" value="" />
        </div>
        <div class="form-field">
            <label for="species_temperature"><?php esc_html_e( 'Temperature Range', 'aqualuxe' ); ?></label>
            <input type="text" name="species_temperature" id="species_temperature" value="" />
            <p class="description"><?php esc_html_e( 'e.g., "72-78°F"', 'aqualuxe' ); ?></p>
        </div>
        <div class="form-field">
            <label for="species_ph"><?php esc_html_e( 'pH Range', 'aqualuxe' ); ?></label>
            <input type="text" name="species_ph" id="species_ph" value="" />
            <p class="description"><?php esc_html_e( 'e.g., "6.5-7.5"', 'aqualuxe' ); ?></p>
        </div>
        <div class="form-field">
            <label for="species_adult_size"><?php esc_html_e( 'Adult Size', 'aqualuxe' ); ?></label>
            <input type="text" name="species_adult_size" id="species_adult_size" value="" />
            <p class="description"><?php esc_html_e( 'e.g., "3 inches"', 'aqualuxe' ); ?></p>
        </div>
        <div class="form-field">
            <label for="species_lifespan"><?php esc_html_e( 'Lifespan', 'aqualuxe' ); ?></label>
            <input type="text" name="species_lifespan" id="species_lifespan" value="" />
            <p class="description"><?php esc_html_e( 'e.g., "3-5 years"', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    // Plant Type Meta
    if ( 'plant_type' === $taxonomy ) {
        ?>
        <div class="form-field">
            <label for="plant_scientific_name"><?php esc_html_e( 'Scientific Name', 'aqualuxe' ); ?></label>
            <input type="text" name="plant_scientific_name" id="plant_scientific_name" value="" />
        </div>
        <div class="form-field">
            <label for="plant_origin"><?php esc_html_e( 'Origin', 'aqualuxe' ); ?></label>
            <input type="text" name="plant_origin" id="plant_origin" value="" />
        </div>
        <div class="form-field">
            <label for="plant_growth_rate"><?php esc_html_e( 'Growth Rate', 'aqualuxe' ); ?></label>
            <select name="plant_growth_rate" id="plant_growth_rate">
                <option value=""><?php esc_html_e( 'Select Growth Rate', 'aqualuxe' ); ?></option>
                <option value="slow"><?php esc_html_e( 'Slow', 'aqualuxe' ); ?></option>
                <option value="medium"><?php esc_html_e( 'Medium', 'aqualuxe' ); ?></option>
                <option value="fast"><?php esc_html_e( 'Fast', 'aqualuxe' ); ?></option>
            </select>
        </div>
        <div class="form-field">
            <label for="plant_light"><?php esc_html_e( 'Light Requirements', 'aqualuxe' ); ?></label>
            <select name="plant_light" id="plant_light">
                <option value=""><?php esc_html_e( 'Select Light Requirements', 'aqualuxe' ); ?></option>
                <option value="low"><?php esc_html_e( 'Low', 'aqualuxe' ); ?></option>
                <option value="medium"><?php esc_html_e( 'Medium', 'aqualuxe' ); ?></option>
                <option value="high"><?php esc_html_e( 'High', 'aqualuxe' ); ?></option>
            </select>
        </div>
        <div class="form-field">
            <label for="plant_co2"><?php esc_html_e( 'CO2 Requirements', 'aqualuxe' ); ?></label>
            <select name="plant_co2" id="plant_co2">
                <option value=""><?php esc_html_e( 'Select CO2 Requirements', 'aqualuxe' ); ?></option>
                <option value="low"><?php esc_html_e( 'Low', 'aqualuxe' ); ?></option>
                <option value="medium"><?php esc_html_e( 'Medium', 'aqualuxe' ); ?></option>
                <option value="high"><?php esc_html_e( 'High', 'aqualuxe' ); ?></option>
            </select>
        </div>
        <div class="form-field">
            <label for="plant_placement"><?php esc_html_e( 'Placement', 'aqualuxe' ); ?></label>
            <select name="plant_placement" id="plant_placement">
                <option value=""><?php esc_html_e( 'Select Placement', 'aqualuxe' ); ?></option>
                <option value="foreground"><?php esc_html_e( 'Foreground', 'aqualuxe' ); ?></option>
                <option value="midground"><?php esc_html_e( 'Midground', 'aqualuxe' ); ?></option>
                <option value="background"><?php esc_html_e( 'Background', 'aqualuxe' ); ?></option>
            </select>
        </div>
        <?php
    }

    // Water Type Meta
    if ( 'water_type' === $taxonomy ) {
        ?>
        <div class="form-field">
            <label for="water_ph"><?php esc_html_e( 'Typical pH Range', 'aqualuxe' ); ?></label>
            <input type="text" name="water_ph" id="water_ph" value="" />
            <p class="description"><?php esc_html_e( 'e.g., "6.5-7.5"', 'aqualuxe' ); ?></p>
        </div>
        <div class="form-field">
            <label for="water_hardness"><?php esc_html_e( 'Typical Hardness Range', 'aqualuxe' ); ?></label>
            <input type="text" name="water_hardness" id="water_hardness" value="" />
            <p class="description"><?php esc_html_e( 'e.g., "5-12 dGH"', 'aqualuxe' ); ?></p>
        </div>
        <div class="form-field">
            <label for="water_temperature"><?php esc_html_e( 'Typical Temperature Range', 'aqualuxe' ); ?></label>
            <input type="text" name="water_temperature" id="water_temperature" value="" />
            <p class="description"><?php esc_html_e( 'e.g., "72-78°F"', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }

    // Care Level Meta
    if ( 'care_level' === $taxonomy ) {
        ?>
        <div class="form-field">
            <label for="care_level_description"><?php esc_html_e( 'Care Level Description', 'aqualuxe' ); ?></label>
            <textarea name="care_level_description" id="care_level_description" rows="5"></textarea>
            <p class="description"><?php esc_html_e( 'Provide a brief description of what this care level means.', 'aqualuxe' ); ?></p>
        </div>
        <div class="form-field">
            <label for="care_level_icon"><?php esc_html_e( 'Care Level Icon', 'aqualuxe' ); ?></label>
            <input type="text" name="care_level_icon" id="care_level_icon" value="" />
            <p class="description"><?php esc_html_e( 'Enter a Font Awesome icon class (e.g., "fa-leaf") or a URL to a custom icon image.', 'aqualuxe' ); ?></p>
        </div>
        <div class="form-field">
            <label for="care_level_order"><?php esc_html_e( 'Display Order', 'aqualuxe' ); ?></label>
            <input type="number" name="care_level_order" id="care_level_order" value="0" />
            <p class="description"><?php esc_html_e( 'Enter a number to determine the display order (lower numbers display first).', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }
}
add_action( 'service_category_add_form_fields', 'aqualuxe_add_taxonomy_fields' );
add_action( 'fish_species_add_form_fields', 'aqualuxe_add_taxonomy_fields' );
add_action( 'plant_type_add_form_fields', 'aqualuxe_add_taxonomy_fields' );
add_action( 'water_type_add_form_fields', 'aqualuxe_add_taxonomy_fields' );
add_action( 'care_level_add_form_fields', 'aqualuxe_add_taxonomy_fields' );

/**
 * Edit custom taxonomy meta fields
 */
function aqualuxe_edit_taxonomy_fields( $term, $taxonomy ) {
    // Service Category Icon
    if ( 'service_category' === $taxonomy ) {
        $category_icon = get_term_meta( $term->term_id, 'category_icon', true );
        ?>
        <tr class="form-field">
            <th scope="row"><label for="category_icon"><?php esc_html_e( 'Category Icon', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="category_icon" id="category_icon" value="<?php echo esc_attr( $category_icon ); ?>" />
                <p class="description"><?php esc_html_e( 'Enter a Font Awesome icon class (e.g., "fa-fish") or a URL to a custom icon image.', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <?php
    }

    // Fish Species Meta
    if ( 'fish_species' === $taxonomy ) {
        $scientific_name = get_term_meta( $term->term_id, 'species_scientific_name', true );
        $origin = get_term_meta( $term->term_id, 'species_origin', true );
        $temperature = get_term_meta( $term->term_id, 'species_temperature', true );
        $ph = get_term_meta( $term->term_id, 'species_ph', true );
        $adult_size = get_term_meta( $term->term_id, 'species_adult_size', true );
        $lifespan = get_term_meta( $term->term_id, 'species_lifespan', true );
        ?>
        <tr class="form-field">
            <th scope="row"><label for="species_scientific_name"><?php esc_html_e( 'Scientific Name', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="species_scientific_name" id="species_scientific_name" value="<?php echo esc_attr( $scientific_name ); ?>" />
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="species_origin"><?php esc_html_e( 'Origin', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="species_origin" id="species_origin" value="<?php echo esc_attr( $origin ); ?>" />
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="species_temperature"><?php esc_html_e( 'Temperature Range', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="species_temperature" id="species_temperature" value="<?php echo esc_attr( $temperature ); ?>" />
                <p class="description"><?php esc_html_e( 'e.g., "72-78°F"', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="species_ph"><?php esc_html_e( 'pH Range', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="species_ph" id="species_ph" value="<?php echo esc_attr( $ph ); ?>" />
                <p class="description"><?php esc_html_e( 'e.g., "6.5-7.5"', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="species_adult_size"><?php esc_html_e( 'Adult Size', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="species_adult_size" id="species_adult_size" value="<?php echo esc_attr( $adult_size ); ?>" />
                <p class="description"><?php esc_html_e( 'e.g., "3 inches"', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="species_lifespan"><?php esc_html_e( 'Lifespan', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="species_lifespan" id="species_lifespan" value="<?php echo esc_attr( $lifespan ); ?>" />
                <p class="description"><?php esc_html_e( 'e.g., "3-5 years"', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <?php
    }

    // Plant Type Meta
    if ( 'plant_type' === $taxonomy ) {
        $scientific_name = get_term_meta( $term->term_id, 'plant_scientific_name', true );
        $origin = get_term_meta( $term->term_id, 'plant_origin', true );
        $growth_rate = get_term_meta( $term->term_id, 'plant_growth_rate', true );
        $light = get_term_meta( $term->term_id, 'plant_light', true );
        $co2 = get_term_meta( $term->term_id, 'plant_co2', true );
        $placement = get_term_meta( $term->term_id, 'plant_placement', true );
        ?>
        <tr class="form-field">
            <th scope="row"><label for="plant_scientific_name"><?php esc_html_e( 'Scientific Name', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="plant_scientific_name" id="plant_scientific_name" value="<?php echo esc_attr( $scientific_name ); ?>" />
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="plant_origin"><?php esc_html_e( 'Origin', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="plant_origin" id="plant_origin" value="<?php echo esc_attr( $origin ); ?>" />
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="plant_growth_rate"><?php esc_html_e( 'Growth Rate', 'aqualuxe' ); ?></label></th>
            <td>
                <select name="plant_growth_rate" id="plant_growth_rate">
                    <option value=""><?php esc_html_e( 'Select Growth Rate', 'aqualuxe' ); ?></option>
                    <option value="slow" <?php selected( $growth_rate, 'slow' ); ?>><?php esc_html_e( 'Slow', 'aqualuxe' ); ?></option>
                    <option value="medium" <?php selected( $growth_rate, 'medium' ); ?>><?php esc_html_e( 'Medium', 'aqualuxe' ); ?></option>
                    <option value="fast" <?php selected( $growth_rate, 'fast' ); ?>><?php esc_html_e( 'Fast', 'aqualuxe' ); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="plant_light"><?php esc_html_e( 'Light Requirements', 'aqualuxe' ); ?></label></th>
            <td>
                <select name="plant_light" id="plant_light">
                    <option value=""><?php esc_html_e( 'Select Light Requirements', 'aqualuxe' ); ?></option>
                    <option value="low" <?php selected( $light, 'low' ); ?>><?php esc_html_e( 'Low', 'aqualuxe' ); ?></option>
                    <option value="medium" <?php selected( $light, 'medium' ); ?>><?php esc_html_e( 'Medium', 'aqualuxe' ); ?></option>
                    <option value="high" <?php selected( $light, 'high' ); ?>><?php esc_html_e( 'High', 'aqualuxe' ); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="plant_co2"><?php esc_html_e( 'CO2 Requirements', 'aqualuxe' ); ?></label></th>
            <td>
                <select name="plant_co2" id="plant_co2">
                    <option value=""><?php esc_html_e( 'Select CO2 Requirements', 'aqualuxe' ); ?></option>
                    <option value="low" <?php selected( $co2, 'low' ); ?>><?php esc_html_e( 'Low', 'aqualuxe' ); ?></option>
                    <option value="medium" <?php selected( $co2, 'medium' ); ?>><?php esc_html_e( 'Medium', 'aqualuxe' ); ?></option>
                    <option value="high" <?php selected( $co2, 'high' ); ?>><?php esc_html_e( 'High', 'aqualuxe' ); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="plant_placement"><?php esc_html_e( 'Placement', 'aqualuxe' ); ?></label></th>
            <td>
                <select name="plant_placement" id="plant_placement">
                    <option value=""><?php esc_html_e( 'Select Placement', 'aqualuxe' ); ?></option>
                    <option value="foreground" <?php selected( $placement, 'foreground' ); ?>><?php esc_html_e( 'Foreground', 'aqualuxe' ); ?></option>
                    <option value="midground" <?php selected( $placement, 'midground' ); ?>><?php esc_html_e( 'Midground', 'aqualuxe' ); ?></option>
                    <option value="background" <?php selected( $placement, 'background' ); ?>><?php esc_html_e( 'Background', 'aqualuxe' ); ?></option>
                </select>
            </td>
        </tr>
        <?php
    }

    // Water Type Meta
    if ( 'water_type' === $taxonomy ) {
        $ph = get_term_meta( $term->term_id, 'water_ph', true );
        $hardness = get_term_meta( $term->term_id, 'water_hardness', true );
        $temperature = get_term_meta( $term->term_id, 'water_temperature', true );
        ?>
        <tr class="form-field">
            <th scope="row"><label for="water_ph"><?php esc_html_e( 'Typical pH Range', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="water_ph" id="water_ph" value="<?php echo esc_attr( $ph ); ?>" />
                <p class="description"><?php esc_html_e( 'e.g., "6.5-7.5"', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="water_hardness"><?php esc_html_e( 'Typical Hardness Range', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="water_hardness" id="water_hardness" value="<?php echo esc_attr( $hardness ); ?>" />
                <p class="description"><?php esc_html_e( 'e.g., "5-12 dGH"', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="water_temperature"><?php esc_html_e( 'Typical Temperature Range', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="water_temperature" id="water_temperature" value="<?php echo esc_attr( $temperature ); ?>" />
                <p class="description"><?php esc_html_e( 'e.g., "72-78°F"', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <?php
    }

    // Care Level Meta
    if ( 'care_level' === $taxonomy ) {
        $description = get_term_meta( $term->term_id, 'care_level_description', true );
        $icon = get_term_meta( $term->term_id, 'care_level_icon', true );
        $order = get_term_meta( $term->term_id, 'care_level_order', true );
        ?>
        <tr class="form-field">
            <th scope="row"><label for="care_level_description"><?php esc_html_e( 'Care Level Description', 'aqualuxe' ); ?></label></th>
            <td>
                <textarea name="care_level_description" id="care_level_description" rows="5"><?php echo esc_textarea( $description ); ?></textarea>
                <p class="description"><?php esc_html_e( 'Provide a brief description of what this care level means.', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="care_level_icon"><?php esc_html_e( 'Care Level Icon', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="text" name="care_level_icon" id="care_level_icon" value="<?php echo esc_attr( $icon ); ?>" />
                <p class="description"><?php esc_html_e( 'Enter a Font Awesome icon class (e.g., "fa-leaf") or a URL to a custom icon image.', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row"><label for="care_level_order"><?php esc_html_e( 'Display Order', 'aqualuxe' ); ?></label></th>
            <td>
                <input type="number" name="care_level_order" id="care_level_order" value="<?php echo esc_attr( $order ); ?>" />
                <p class="description"><?php esc_html_e( 'Enter a number to determine the display order (lower numbers display first).', 'aqualuxe' ); ?></p>
            </td>
        </tr>
        <?php
    }
}
add_action( 'service_category_edit_form_fields', 'aqualuxe_edit_taxonomy_fields', 10, 2 );
add_action( 'fish_species_edit_form_fields', 'aqualuxe_edit_taxonomy_fields', 10, 2 );
add_action( 'plant_type_edit_form_fields', 'aqualuxe_edit_taxonomy_fields', 10, 2 );
add_action( 'water_type_edit_form_fields', 'aqualuxe_edit_taxonomy_fields', 10, 2 );
add_action( 'care_level_edit_form_fields', 'aqualuxe_edit_taxonomy_fields', 10, 2 );

/**
 * Save custom taxonomy meta fields
 */
function aqualuxe_save_taxonomy_fields( $term_id ) {
    // Service Category Icon
    if ( isset( $_POST['category_icon'] ) ) {
        update_term_meta( $term_id, 'category_icon', sanitize_text_field( $_POST['category_icon'] ) );
    }

    // Fish Species Meta
    if ( isset( $_POST['species_scientific_name'] ) ) {
        update_term_meta( $term_id, 'species_scientific_name', sanitize_text_field( $_POST['species_scientific_name'] ) );
    }
    if ( isset( $_POST['species_origin'] ) ) {
        update_term_meta( $term_id, 'species_origin', sanitize_text_field( $_POST['species_origin'] ) );
    }
    if ( isset( $_POST['species_temperature'] ) ) {
        update_term_meta( $term_id, 'species_temperature', sanitize_text_field( $_POST['species_temperature'] ) );
    }
    if ( isset( $_POST['species_ph'] ) ) {
        update_term_meta( $term_id, 'species_ph', sanitize_text_field( $_POST['species_ph'] ) );
    }
    if ( isset( $_POST['species_adult_size'] ) ) {
        update_term_meta( $term_id, 'species_adult_size', sanitize_text_field( $_POST['species_adult_size'] ) );
    }
    if ( isset( $_POST['species_lifespan'] ) ) {
        update_term_meta( $term_id, 'species_lifespan', sanitize_text_field( $_POST['species_lifespan'] ) );
    }

    // Plant Type Meta
    if ( isset( $_POST['plant_scientific_name'] ) ) {
        update_term_meta( $term_id, 'plant_scientific_name', sanitize_text_field( $_POST['plant_scientific_name'] ) );
    }
    if ( isset( $_POST['plant_origin'] ) ) {
        update_term_meta( $term_id, 'plant_origin', sanitize_text_field( $_POST['plant_origin'] ) );
    }
    if ( isset( $_POST['plant_growth_rate'] ) ) {
        update_term_meta( $term_id, 'plant_growth_rate', sanitize_text_field( $_POST['plant_growth_rate'] ) );
    }
    if ( isset( $_POST['plant_light'] ) ) {
        update_term_meta( $term_id, 'plant_light', sanitize_text_field( $_POST['plant_light'] ) );
    }
    if ( isset( $_POST['plant_co2'] ) ) {
        update_term_meta( $term_id, 'plant_co2', sanitize_text_field( $_POST['plant_co2'] ) );
    }
    if ( isset( $_POST['plant_placement'] ) ) {
        update_term_meta( $term_id, 'plant_placement', sanitize_text_field( $_POST['plant_placement'] ) );
    }

    // Water Type Meta
    if ( isset( $_POST['water_ph'] ) ) {
        update_term_meta( $term_id, 'water_ph', sanitize_text_field( $_POST['water_ph'] ) );
    }
    if ( isset( $_POST['water_hardness'] ) ) {
        update_term_meta( $term_id, 'water_hardness', sanitize_text_field( $_POST['water_hardness'] ) );
    }
    if ( isset( $_POST['water_temperature'] ) ) {
        update_term_meta( $term_id, 'water_temperature', sanitize_text_field( $_POST['water_temperature'] ) );
    }

    // Care Level Meta
    if ( isset( $_POST['care_level_description'] ) ) {
        update_term_meta( $term_id, 'care_level_description', sanitize_textarea_field( $_POST['care_level_description'] ) );
    }
    if ( isset( $_POST['care_level_icon'] ) ) {
        update_term_meta( $term_id, 'care_level_icon', sanitize_text_field( $_POST['care_level_icon'] ) );
    }
    if ( isset( $_POST['care_level_order'] ) ) {
        update_term_meta( $term_id, 'care_level_order', absint( $_POST['care_level_order'] ) );
    }
}
add_action( 'created_service_category', 'aqualuxe_save_taxonomy_fields' );
add_action( 'edited_service_category', 'aqualuxe_save_taxonomy_fields' );
add_action( 'created_fish_species', 'aqualuxe_save_taxonomy_fields' );
add_action( 'edited_fish_species', 'aqualuxe_save_taxonomy_fields' );
add_action( 'created_plant_type', 'aqualuxe_save_taxonomy_fields' );
add_action( 'edited_plant_type', 'aqualuxe_save_taxonomy_fields' );
add_action( 'created_water_type', 'aqualuxe_save_taxonomy_fields' );
add_action( 'edited_water_type', 'aqualuxe_save_taxonomy_fields' );
add_action( 'created_care_level', 'aqualuxe_save_taxonomy_fields' );
add_action( 'edited_care_level', 'aqualuxe_save_taxonomy_fields' );

/**
 * Add custom columns to taxonomy admin screens
 */
function aqualuxe_add_taxonomy_columns( $columns ) {
    $new_columns = array();

    // Add columns before the 'posts' column
    foreach ( $columns as $key => $value ) {
        if ( $key === 'posts' ) {
            // Fish Species columns
            if ( isset( $_GET['taxonomy'] ) && 'fish_species' === $_GET['taxonomy'] ) {
                $new_columns['scientific_name'] = __( 'Scientific Name', 'aqualuxe' );
                $new_columns['origin'] = __( 'Origin', 'aqualuxe' );
                $new_columns['temperature'] = __( 'Temperature', 'aqualuxe' );
                $new_columns['ph'] = __( 'pH', 'aqualuxe' );
            }

            // Plant Type columns
            if ( isset( $_GET['taxonomy'] ) && 'plant_type' === $_GET['taxonomy'] ) {
                $new_columns['scientific_name'] = __( 'Scientific Name', 'aqualuxe' );
                $new_columns['growth_rate'] = __( 'Growth Rate', 'aqualuxe' );
                $new_columns['light'] = __( 'Light', 'aqualuxe' );
                $new_columns['placement'] = __( 'Placement', 'aqualuxe' );
            }

            // Water Type columns
            if ( isset( $_GET['taxonomy'] ) && 'water_type' === $_GET['taxonomy'] ) {
                $new_columns['ph'] = __( 'pH', 'aqualuxe' );
                $new_columns['hardness'] = __( 'Hardness', 'aqualuxe' );
                $new_columns['temperature'] = __( 'Temperature', 'aqualuxe' );
            }

            // Care Level columns
            if ( isset( $_GET['taxonomy'] ) && 'care_level' === $_GET['taxonomy'] ) {
                $new_columns['icon'] = __( 'Icon', 'aqualuxe' );
                $new_columns['order'] = __( 'Order', 'aqualuxe' );
            }

            // Service Category columns
            if ( isset( $_GET['taxonomy'] ) && 'service_category' === $_GET['taxonomy'] ) {
                $new_columns['icon'] = __( 'Icon', 'aqualuxe' );
            }
        }

        $new_columns[ $key ] = $value;
    }

    return $new_columns;
}
add_filter( 'manage_edit-fish_species_columns', 'aqualuxe_add_taxonomy_columns' );
add_filter( 'manage_edit-plant_type_columns', 'aqualuxe_add_taxonomy_columns' );
add_filter( 'manage_edit-water_type_columns', 'aqualuxe_add_taxonomy_columns' );
add_filter( 'manage_edit-care_level_columns', 'aqualuxe_add_taxonomy_columns' );
add_filter( 'manage_edit-service_category_columns', 'aqualuxe_add_taxonomy_columns' );

/**
 * Display custom column content for taxonomies
 */
function aqualuxe_taxonomy_column_content( $content, $column_name, $term_id ) {
    // Fish Species columns
    if ( 'scientific_name' === $column_name ) {
        $scientific_name = get_term_meta( $term_id, 'species_scientific_name', true );
        if ( $scientific_name ) {
            $content = '<em>' . esc_html( $scientific_name ) . '</em>';
        }
    } elseif ( 'origin' === $column_name ) {
        $origin = get_term_meta( $term_id, 'species_origin', true );
        if ( $origin ) {
            $content = esc_html( $origin );
        }
    } elseif ( 'temperature' === $column_name ) {
        $temperature = get_term_meta( $term_id, 'species_temperature', true );
        if ( $temperature ) {
            $content = esc_html( $temperature );
        }
    } elseif ( 'ph' === $column_name ) {
        $ph = get_term_meta( $term_id, 'species_ph', true );
        if ( $ph ) {
            $content = esc_html( $ph );
        }
    }

    // Plant Type columns
    if ( 'scientific_name' === $column_name ) {
        $scientific_name = get_term_meta( $term_id, 'plant_scientific_name', true );
        if ( $scientific_name ) {
            $content = '<em>' . esc_html( $scientific_name ) . '</em>';
        }
    } elseif ( 'growth_rate' === $column_name ) {
        $growth_rate = get_term_meta( $term_id, 'plant_growth_rate', true );
        if ( $growth_rate ) {
            $content = esc_html( ucfirst( $growth_rate ) );
        }
    } elseif ( 'light' === $column_name ) {
        $light = get_term_meta( $term_id, 'plant_light', true );
        if ( $light ) {
            $content = esc_html( ucfirst( $light ) );
        }
    } elseif ( 'placement' === $column_name ) {
        $placement = get_term_meta( $term_id, 'plant_placement', true );
        if ( $placement ) {
            $content = esc_html( ucfirst( $placement ) );
        }
    }

    // Water Type columns
    if ( 'ph' === $column_name ) {
        $ph = get_term_meta( $term_id, 'water_ph', true );
        if ( $ph ) {
            $content = esc_html( $ph );
        }
    } elseif ( 'hardness' === $column_name ) {
        $hardness = get_term_meta( $term_id, 'water_hardness', true );
        if ( $hardness ) {
            $content = esc_html( $hardness );
        }
    } elseif ( 'temperature' === $column_name ) {
        $temperature = get_term_meta( $term_id, 'water_temperature', true );
        if ( $temperature ) {
            $content = esc_html( $temperature );
        }
    }

    // Care Level columns
    if ( 'icon' === $column_name ) {
        $icon = get_term_meta( $term_id, 'care_level_icon', true );
        if ( $icon ) {
            if ( filter_var( $icon, FILTER_VALIDATE_URL ) ) {
                $content = '<img src="' . esc_url( $icon ) . '" alt="" style="max-width: 24px; max-height: 24px;" />';
            } else {
                $content = '<i class="' . esc_attr( $icon ) . '"></i>';
            }
        }
    } elseif ( 'order' === $column_name ) {
        $order = get_term_meta( $term_id, 'care_level_order', true );
        if ( $order !== '' ) {
            $content = esc_html( $order );
        }
    }

    // Service Category columns
    if ( 'icon' === $column_name ) {
        $icon = get_term_meta( $term_id, 'category_icon', true );
        if ( $icon ) {
            if ( filter_var( $icon, FILTER_VALIDATE_URL ) ) {
                $content = '<img src="' . esc_url( $icon ) . '" alt="" style="max-width: 24px; max-height: 24px;" />';
            } else {
                $content = '<i class="' . esc_attr( $icon ) . '"></i>';
            }
        }
    }

    return $content;
}
add_filter( 'manage_fish_species_custom_column', 'aqualuxe_taxonomy_column_content', 10, 3 );
add_filter( 'manage_plant_type_custom_column', 'aqualuxe_taxonomy_column_content', 10, 3 );
add_filter( 'manage_water_type_custom_column', 'aqualuxe_taxonomy_column_content', 10, 3 );
add_filter( 'manage_care_level_custom_column', 'aqualuxe_taxonomy_column_content', 10, 3 );
add_filter( 'manage_service_category_custom_column', 'aqualuxe_taxonomy_column_content', 10, 3 );

/**
 * Make custom taxonomy columns sortable
 */
function aqualuxe_sortable_taxonomy_columns( $columns ) {
    $columns['scientific_name'] = 'scientific_name';
    $columns['origin'] = 'origin';
    $columns['growth_rate'] = 'growth_rate';
    $columns['light'] = 'light';
    $columns['placement'] = 'placement';
    $columns['order'] = 'order';
    
    return $columns;
}
add_filter( 'manage_edit-fish_species_sortable_columns', 'aqualuxe_sortable_taxonomy_columns' );
add_filter( 'manage_edit-plant_type_sortable_columns', 'aqualuxe_sortable_taxonomy_columns' );
add_filter( 'manage_edit-care_level_sortable_columns', 'aqualuxe_sortable_taxonomy_columns' );

/**
 * Add taxonomy filter to product admin
 */
function aqualuxe_product_filters() {
    global $typenow;
    
    if ( 'product' === $typenow ) {
        // Fish Species filter
        $taxonomy = 'fish_species';
        $selected = isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '';
        $info_taxonomy = get_taxonomy( $taxonomy );
        
        wp_dropdown_categories( array(
            'show_option_all' => sprintf( __( 'All %s', 'aqualuxe' ), $info_taxonomy->labels->name ),
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => $selected,
            'show_count'      => true,
            'hide_empty'      => true,
        ) );
        
        // Water Type filter
        $taxonomy = 'water_type';
        $selected = isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '';
        $info_taxonomy = get_taxonomy( $taxonomy );
        
        wp_dropdown_categories( array(
            'show_option_all' => sprintf( __( 'All %s', 'aqualuxe' ), $info_taxonomy->labels->name ),
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => $selected,
            'show_count'      => true,
            'hide_empty'      => true,
        ) );
        
        // Care Level filter
        $taxonomy = 'care_level';
        $selected = isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '';
        $info_taxonomy = get_taxonomy( $taxonomy );
        
        wp_dropdown_categories( array(
            'show_option_all' => sprintf( __( 'All %s', 'aqualuxe' ), $info_taxonomy->labels->name ),
            'taxonomy'        => $taxonomy,
            'name'            => $taxonomy,
            'orderby'         => 'name',
            'selected'        => $selected,
            'show_count'      => true,
            'hide_empty'      => true,
        ) );
    }
}
add_action( 'restrict_manage_posts', 'aqualuxe_product_filters' );

/**
 * Filter products by custom taxonomies
 */
function aqualuxe_products_filter( $query ) {
    global $pagenow;
    
    $taxonomies = array( 'fish_species', 'water_type', 'care_level', 'plant_type', 'tank_size' );
    
    if ( 'edit.php' === $pagenow && isset( $query->query_vars['post_type'] ) && 'product' === $query->query_vars['post_type'] ) {
        $tax_query = array();
        
        foreach ( $taxonomies as $taxonomy ) {
            if ( isset( $_GET[ $taxonomy ] ) && $_GET[ $taxonomy ] > 0 ) {
                $tax_query[] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'id',
                    'terms'    => $_GET[ $taxonomy ],
                );
            }
        }
        
        if ( ! empty( $tax_query ) ) {
            $query->set( 'tax_query', $tax_query );
        }
    }
}
add_action( 'pre_get_posts', 'aqualuxe_products_filter' );

/**
 * Add taxonomy terms to product data tabs
 */
function aqualuxe_product_data_tabs( $tabs ) {
    $tabs['aqualuxe_taxonomies'] = array(
        'label'    => __( 'AquaLuxe Taxonomies', 'aqualuxe' ),
        'target'   => 'aqualuxe_taxonomies_product_data',
        'class'    => array(),
        'priority' => 60,
    );
    
    return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'aqualuxe_product_data_tabs' );

/**
 * Add taxonomy terms panel to product data
 */
function aqualuxe_product_data_panels() {
    ?>
    <div id="aqualuxe_taxonomies_product_data" class="panel woocommerce_options_panel">
        <div class="options_group">
            <p class="form-field">
                <label><?php esc_html_e( 'Fish Species', 'aqualuxe' ); ?></label>
                <?php
                $taxonomy = 'fish_species';
                $terms = get_terms( array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                ) );
                
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    echo '<select multiple="multiple" class="wc-enhanced-select" name="tax_input[' . esc_attr( $taxonomy ) . '][]" style="width: 100%;">';
                    
                    foreach ( $terms as $term ) {
                        $scientific_name = get_term_meta( $term->term_id, 'species_scientific_name', true );
                        $display_name = $term->name;
                        
                        if ( $scientific_name ) {
                            $display_name .= ' (' . $scientific_name . ')';
                        }
                        
                        echo '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $display_name ) . '</option>';
                    }
                    
                    echo '</select>';
                }
                ?>
            </p>
            
            <p class="form-field">
                <label><?php esc_html_e( 'Water Type', 'aqualuxe' ); ?></label>
                <?php
                $taxonomy = 'water_type';
                $terms = get_terms( array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                ) );
                
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    echo '<select multiple="multiple" class="wc-enhanced-select" name="tax_input[' . esc_attr( $taxonomy ) . '][]" style="width: 100%;">';
                    
                    foreach ( $terms as $term ) {
                        echo '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ) . '</option>';
                    }
                    
                    echo '</select>';
                }
                ?>
            </p>
            
            <p class="form-field">
                <label><?php esc_html_e( 'Care Level', 'aqualuxe' ); ?></label>
                <?php
                $taxonomy = 'care_level';
                $terms = get_terms( array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                    'meta_key'   => 'care_level_order',
                    'orderby'    => 'meta_value_num',
                    'order'      => 'ASC',
                ) );
                
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    echo '<select class="wc-enhanced-select" name="tax_input[' . esc_attr( $taxonomy ) . '][]" style="width: 100%;">';
                    echo '<option value="">' . esc_html__( 'Select Care Level', 'aqualuxe' ) . '</option>';
                    
                    foreach ( $terms as $term ) {
                        echo '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ) . '</option>';
                    }
                    
                    echo '</select>';
                }
                ?>
            </p>
            
            <p class="form-field">
                <label><?php esc_html_e( 'Plant Type', 'aqualuxe' ); ?></label>
                <?php
                $taxonomy = 'plant_type';
                $terms = get_terms( array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                ) );
                
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    echo '<select multiple="multiple" class="wc-enhanced-select" name="tax_input[' . esc_attr( $taxonomy ) . '][]" style="width: 100%;">';
                    
                    foreach ( $terms as $term ) {
                        $scientific_name = get_term_meta( $term->term_id, 'plant_scientific_name', true );
                        $display_name = $term->name;
                        
                        if ( $scientific_name ) {
                            $display_name .= ' (' . $scientific_name . ')';
                        }
                        
                        echo '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $display_name ) . '</option>';
                    }
                    
                    echo '</select>';
                }
                ?>
            </p>
            
            <p class="form-field">
                <label><?php esc_html_e( 'Tank Size', 'aqualuxe' ); ?></label>
                <?php
                $taxonomy = 'tank_size';
                $terms = get_terms( array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                ) );
                
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    echo '<select multiple="multiple" class="wc-enhanced-select" name="tax_input[' . esc_attr( $taxonomy ) . '][]" style="width: 100%;">';
                    
                    foreach ( $terms as $term ) {
                        echo '<option value="' . esc_attr( $term->term_id ) . '">' . esc_html( $term->name ) . '</option>';
                    }
                    
                    echo '</select>';
                }
                ?>
            </p>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_product_data_panels', 'aqualuxe_product_data_panels' );

/**
 * Display taxonomy terms on product page
 */
function aqualuxe_display_product_taxonomies() {
    global $product;
    
    $taxonomies = array(
        'fish_species' => __( 'Fish Species', 'aqualuxe' ),
        'water_type'   => __( 'Water Type', 'aqualuxe' ),
        'care_level'   => __( 'Care Level', 'aqualuxe' ),
        'plant_type'   => __( 'Plant Type', 'aqualuxe' ),
        'tank_size'    => __( 'Tank Size', 'aqualuxe' ),
    );
    
    $has_terms = false;
    
    echo '<div class="product-taxonomies">';
    
    foreach ( $taxonomies as $taxonomy => $label ) {
        $terms = get_the_terms( $product->get_id(), $taxonomy );
        
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $has_terms = true;
            
            echo '<div class="product-taxonomy">';
            echo '<span class="taxonomy-label">' . esc_html( $label ) . ':</span> ';
            
            $term_links = array();
            
            foreach ( $terms as $term ) {
                $term_link = get_term_link( $term );
                
                if ( ! is_wp_error( $term_link ) ) {
                    $term_links[] = '<a href="' . esc_url( $term_link ) . '">' . esc_html( $term->name ) . '</a>';
                } else {
                    $term_links[] = esc_html( $term->name );
                }
            }
            
            echo implode( ', ', $term_links );
            echo '</div>';
        }
    }
    
    if ( ! $has_terms ) {
        echo '<div class="product-taxonomy-none">' . esc_html__( 'No taxonomies assigned', 'aqualuxe' ) . '</div>';
    }
    
    echo '</div>';
}
add_action( 'woocommerce_product_meta_end', 'aqualuxe_display_product_taxonomies' );

/**
 * Add taxonomy filter widgets
 */
function aqualuxe_register_taxonomy_widgets() {
    // Fish Species Widget
    register_widget( 'Aqualuxe_Fish_Species_Widget' );
    
    // Water Type Widget
    register_widget( 'Aqualuxe_Water_Type_Widget' );
    
    // Care Level Widget
    register_widget( 'Aqualuxe_Care_Level_Widget' );
    
    // Plant Type Widget
    register_widget( 'Aqualuxe_Plant_Type_Widget' );
    
    // Tank Size Widget
    register_widget( 'Aqualuxe_Tank_Size_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_register_taxonomy_widgets' );

/**
 * Fish Species Widget
 */
class Aqualuxe_Fish_Species_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'aqualuxe_fish_species_widget',
            __( 'AquaLuxe Fish Species', 'aqualuxe' ),
            array( 'description' => __( 'Filter products by fish species', 'aqualuxe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        
        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : __( 'Fish Species', 'aqualuxe' );
        $count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
        $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : true;
        
        $terms = get_terms( array(
            'taxonomy'   => 'fish_species',
            'hide_empty' => true,
        ) );
        
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="product-filter fish-species-filter">';
        
        foreach ( $terms as $term ) {
            $term_link = get_term_link( $term );
            $count_html = $count ? ' <span class="count">(' . $term->count . ')</span>' : '';
            
            echo '<li><a href="' . esc_url( $term_link ) . '">' . esc_html( $term->name ) . $count_html . '</a></li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : __( 'Fish Species', 'aqualuxe' );
        $count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
        $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" <?php checked( $count ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Show product counts', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hierarchical' ) ); ?>" <?php checked( $hierarchical ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"><?php esc_html_e( 'Show hierarchy', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count'] = ! empty( $new_instance['count'] ) ? 1 : 0;
        $instance['hierarchical'] = ! empty( $new_instance['hierarchical'] ) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Water Type Widget
 */
class Aqualuxe_Water_Type_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'aqualuxe_water_type_widget',
            __( 'AquaLuxe Water Type', 'aqualuxe' ),
            array( 'description' => __( 'Filter products by water type', 'aqualuxe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        
        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : __( 'Water Type', 'aqualuxe' );
        $count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
        
        $terms = get_terms( array(
            'taxonomy'   => 'water_type',
            'hide_empty' => true,
        ) );
        
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="product-filter water-type-filter">';
        
        foreach ( $terms as $term ) {
            $term_link = get_term_link( $term );
            $count_html = $count ? ' <span class="count">(' . $term->count . ')</span>' : '';
            
            echo '<li><a href="' . esc_url( $term_link ) . '">' . esc_html( $term->name ) . $count_html . '</a></li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : __( 'Water Type', 'aqualuxe' );
        $count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" <?php checked( $count ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Show product counts', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count'] = ! empty( $new_instance['count'] ) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Care Level Widget
 */
class Aqualuxe_Care_Level_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'aqualuxe_care_level_widget',
            __( 'AquaLuxe Care Level', 'aqualuxe' ),
            array( 'description' => __( 'Filter products by care level', 'aqualuxe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        
        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : __( 'Care Level', 'aqualuxe' );
        $count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
        
        $terms = get_terms( array(
            'taxonomy'   => 'care_level',
            'hide_empty' => true,
            'meta_key'   => 'care_level_order',
            'orderby'    => 'meta_value_num',
            'order'      => 'ASC',
        ) );
        
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="product-filter care-level-filter">';
        
        foreach ( $terms as $term ) {
            $term_link = get_term_link( $term );
            $count_html = $count ? ' <span class="count">(' . $term->count . ')</span>' : '';
            $icon = get_term_meta( $term->term_id, 'care_level_icon', true );
            $icon_html = '';
            
            if ( $icon ) {
                if ( filter_var( $icon, FILTER_VALIDATE_URL ) ) {
                    $icon_html = '<img src="' . esc_url( $icon ) . '" alt="" class="care-level-icon" /> ';
                } else {
                    $icon_html = '<i class="' . esc_attr( $icon ) . ' care-level-icon"></i> ';
                }
            }
            
            echo '<li><a href="' . esc_url( $term_link ) . '">' . $icon_html . esc_html( $term->name ) . $count_html . '</a></li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : __( 'Care Level', 'aqualuxe' );
        $count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" <?php checked( $count ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Show product counts', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count'] = ! empty( $new_instance['count'] ) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Plant Type Widget
 */
class Aqualuxe_Plant_Type_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'aqualuxe_plant_type_widget',
            __( 'AquaLuxe Plant Type', 'aqualuxe' ),
            array( 'description' => __( 'Filter products by plant type', 'aqualuxe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        
        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : __( 'Plant Type', 'aqualuxe' );
        $count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
        $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : true;
        
        $terms = get_terms( array(
            'taxonomy'   => 'plant_type',
            'hide_empty' => true,
        ) );
        
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="product-filter plant-type-filter">';
        
        foreach ( $terms as $term ) {
            $term_link = get_term_link( $term );
            $count_html = $count ? ' <span class="count">(' . $term->count . ')</span>' : '';
            
            echo '<li><a href="' . esc_url( $term_link ) . '">' . esc_html( $term->name ) . $count_html . '</a></li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : __( 'Plant Type', 'aqualuxe' );
        $count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
        $hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" <?php checked( $count ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Show product counts', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hierarchical' ) ); ?>" <?php checked( $hierarchical ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>"><?php esc_html_e( 'Show hierarchy', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count'] = ! empty( $new_instance['count'] ) ? 1 : 0;
        $instance['hierarchical'] = ! empty( $new_instance['hierarchical'] ) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Tank Size Widget
 */
class Aqualuxe_Tank_Size_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'aqualuxe_tank_size_widget',
            __( 'AquaLuxe Tank Size', 'aqualuxe' ),
            array( 'description' => __( 'Filter products by tank size', 'aqualuxe' ) )
        );
    }
    
    public function widget( $args, $instance ) {
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        
        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : __( 'Tank Size', 'aqualuxe' );
        $count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
        
        $terms = get_terms( array(
            'taxonomy'   => 'tank_size',
            'hide_empty' => true,
        ) );
        
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<ul class="product-filter tank-size-filter">';
        
        foreach ( $terms as $term ) {
            $term_link = get_term_link( $term );
            $count_html = $count ? ' <span class="count">(' . $term->count . ')</span>' : '';
            
            echo '<li><a href="' . esc_url( $term_link ) . '">' . esc_html( $term->name ) . $count_html . '</a></li>';
        }
        
        echo '</ul>';
        
        echo $args['after_widget'];
    }
    
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : __( 'Tank Size', 'aqualuxe' );
        $count = isset( $instance['count'] ) ? (bool) $instance['count'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" <?php checked( $count ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Show product counts', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }
    
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['count'] = ! empty( $new_instance['count'] ) ? 1 : 0;
        
        return $instance;
    }
}

/**
 * Add taxonomy terms to product filters
 */
function aqualuxe_product_filter_taxonomies( $args ) {
    $args['fish_species'] = array(
        'name'            => __( 'Fish Species', 'aqualuxe' ),
        'query_type'      => 'taxonomy',
        'taxonomy'        => 'fish_species',
        'hierarchical'    => true,
        'show_count'      => true,
        'enable_multiple' => true,
    );
    
    $args['water_type'] = array(
        'name'            => __( 'Water Type', 'aqualuxe' ),
        'query_type'      => 'taxonomy',
        'taxonomy'        => 'water_type',
        'hierarchical'    => true,
        'show_count'      => true,
        'enable_multiple' => false,
    );
    
    $args['care_level'] = array(
        'name'            => __( 'Care Level', 'aqualuxe' ),
        'query_type'      => 'taxonomy',
        'taxonomy'        => 'care_level',
        'hierarchical'    => true,
        'show_count'      => true,
        'enable_multiple' => false,
    );
    
    $args['plant_type'] = array(
        'name'            => __( 'Plant Type', 'aqualuxe' ),
        'query_type'      => 'taxonomy',
        'taxonomy'        => 'plant_type',
        'hierarchical'    => true,
        'show_count'      => true,
        'enable_multiple' => true,
    );
    
    $args['tank_size'] = array(
        'name'            => __( 'Tank Size', 'aqualuxe' ),
        'query_type'      => 'taxonomy',
        'taxonomy'        => 'tank_size',
        'hierarchical'    => true,
        'show_count'      => true,
        'enable_multiple' => true,
    );
    
    return $args;
}
add_filter( 'woocommerce_product_filter_taxonomies', 'aqualuxe_product_filter_taxonomies' );

/**
 * Flush rewrite rules on theme activation
 */
function aqualuxe_rewrite_flush() {
    aqualuxe_register_taxonomies();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'aqualuxe_rewrite_flush' );