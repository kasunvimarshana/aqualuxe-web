<?php
/**
 * Fish Species Custom Post Type
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Fish Species Custom Post Type Class
 */
class AquaLuxe_Fish_Species {
    /**
     * Constructor
     */
    public function __construct() {
        // Register custom post type
        add_action( 'init', array( $this, 'register_post_type' ) );
        
        // Register taxonomies
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        
        // Add meta boxes
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        
        // Save meta box data
        add_action( 'save_post', array( $this, 'save_meta_box_data' ) );
        
        // Add admin columns
        add_filter( 'manage_fish_species_posts_columns', array( $this, 'set_custom_columns' ) );
        add_action( 'manage_fish_species_posts_custom_column', array( $this, 'custom_column_content' ), 10, 2 );
        
        // Add admin filters
        add_action( 'restrict_manage_posts', array( $this, 'add_admin_filters' ) );
        add_filter( 'parse_query', array( $this, 'filter_query' ) );
        
        // Add shortcode
        add_shortcode( 'fish_species', array( $this, 'fish_species_shortcode' ) );
        
        // Add template for single fish species
        add_filter( 'single_template', array( $this, 'single_template' ) );
        
        // Add template for archive fish species
        add_filter( 'archive_template', array( $this, 'archive_template' ) );
        
        // Add fish species to related products
        add_filter( 'woocommerce_related_products_args', array( $this, 'related_products_args' ), 20 );
        
        // Add fish species data to product meta
        add_action( 'woocommerce_product_options_general_product_data', array( $this, 'product_options' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_options' ) );
        
        // Add fish species tab to single product
        add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ) );
    }

    /**
     * Register custom post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Fish Species', 'Post type general name', 'aqualuxe' ),
            'singular_name'         => _x( 'Fish Species', 'Post type singular name', 'aqualuxe' ),
            'menu_name'             => _x( 'Fish Species', 'Admin Menu text', 'aqualuxe' ),
            'name_admin_bar'        => _x( 'Fish Species', 'Add New on Toolbar', 'aqualuxe' ),
            'add_new'               => __( 'Add New', 'aqualuxe' ),
            'add_new_item'          => __( 'Add New Fish Species', 'aqualuxe' ),
            'new_item'              => __( 'New Fish Species', 'aqualuxe' ),
            'edit_item'             => __( 'Edit Fish Species', 'aqualuxe' ),
            'view_item'             => __( 'View Fish Species', 'aqualuxe' ),
            'all_items'             => __( 'All Fish Species', 'aqualuxe' ),
            'search_items'          => __( 'Search Fish Species', 'aqualuxe' ),
            'parent_item_colon'     => __( 'Parent Fish Species:', 'aqualuxe' ),
            'not_found'             => __( 'No fish species found.', 'aqualuxe' ),
            'not_found_in_trash'    => __( 'No fish species found in Trash.', 'aqualuxe' ),
            'featured_image'        => _x( 'Fish Species Image', 'Overrides the "Featured Image" phrase', 'aqualuxe' ),
            'set_featured_image'    => _x( 'Set fish species image', 'Overrides the "Set featured image" phrase', 'aqualuxe' ),
            'remove_featured_image' => _x( 'Remove fish species image', 'Overrides the "Remove featured image" phrase', 'aqualuxe' ),
            'use_featured_image'    => _x( 'Use as fish species image', 'Overrides the "Use as featured image" phrase', 'aqualuxe' ),
            'archives'              => _x( 'Fish Species Archives', 'The post type archive label used in nav menus', 'aqualuxe' ),
            'insert_into_item'      => _x( 'Insert into fish species', 'Overrides the "Insert into post" phrase', 'aqualuxe' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this fish species', 'Overrides the "Uploaded to this post" phrase', 'aqualuxe' ),
            'filter_items_list'     => _x( 'Filter fish species list', 'Screen reader text for the filter links', 'aqualuxe' ),
            'items_list_navigation' => _x( 'Fish Species list navigation', 'Screen reader text for the pagination', 'aqualuxe' ),
            'items_list'            => _x( 'Fish Species list', 'Screen reader text for the items list', 'aqualuxe' ),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'fish-species' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-fish',
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
            'show_in_rest'       => true,
        );
        
        register_post_type( 'fish_species', $args );
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Register Fish Category taxonomy
        $category_labels = array(
            'name'                       => _x( 'Fish Categories', 'Taxonomy general name', 'aqualuxe' ),
            'singular_name'              => _x( 'Fish Category', 'Taxonomy singular name', 'aqualuxe' ),
            'search_items'               => __( 'Search Fish Categories', 'aqualuxe' ),
            'popular_items'              => __( 'Popular Fish Categories', 'aqualuxe' ),
            'all_items'                  => __( 'All Fish Categories', 'aqualuxe' ),
            'parent_item'                => __( 'Parent Fish Category', 'aqualuxe' ),
            'parent_item_colon'          => __( 'Parent Fish Category:', 'aqualuxe' ),
            'edit_item'                  => __( 'Edit Fish Category', 'aqualuxe' ),
            'view_item'                  => __( 'View Fish Category', 'aqualuxe' ),
            'update_item'                => __( 'Update Fish Category', 'aqualuxe' ),
            'add_new_item'               => __( 'Add New Fish Category', 'aqualuxe' ),
            'new_item_name'              => __( 'New Fish Category Name', 'aqualuxe' ),
            'separate_items_with_commas' => __( 'Separate fish categories with commas', 'aqualuxe' ),
            'add_or_remove_items'        => __( 'Add or remove fish categories', 'aqualuxe' ),
            'choose_from_most_used'      => __( 'Choose from the most used fish categories', 'aqualuxe' ),
            'not_found'                  => __( 'No fish categories found.', 'aqualuxe' ),
            'no_terms'                   => __( 'No fish categories', 'aqualuxe' ),
            'items_list_navigation'      => __( 'Fish Categories list navigation', 'aqualuxe' ),
            'items_list'                 => __( 'Fish Categories list', 'aqualuxe' ),
            'back_to_items'              => __( '&larr; Back to Fish Categories', 'aqualuxe' ),
        );
        
        $category_args = array(
            'labels'            => $category_labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'fish-category' ),
            'show_in_rest'      => true,
        );
        
        register_taxonomy( 'fish_category', array( 'fish_species' ), $category_args );
        
        // Register Fish Habitat taxonomy
        $habitat_labels = array(
            'name'                       => _x( 'Fish Habitats', 'Taxonomy general name', 'aqualuxe' ),
            'singular_name'              => _x( 'Fish Habitat', 'Taxonomy singular name', 'aqualuxe' ),
            'search_items'               => __( 'Search Fish Habitats', 'aqualuxe' ),
            'popular_items'              => __( 'Popular Fish Habitats', 'aqualuxe' ),
            'all_items'                  => __( 'All Fish Habitats', 'aqualuxe' ),
            'parent_item'                => __( 'Parent Fish Habitat', 'aqualuxe' ),
            'parent_item_colon'          => __( 'Parent Fish Habitat:', 'aqualuxe' ),
            'edit_item'                  => __( 'Edit Fish Habitat', 'aqualuxe' ),
            'view_item'                  => __( 'View Fish Habitat', 'aqualuxe' ),
            'update_item'                => __( 'Update Fish Habitat', 'aqualuxe' ),
            'add_new_item'               => __( 'Add New Fish Habitat', 'aqualuxe' ),
            'new_item_name'              => __( 'New Fish Habitat Name', 'aqualuxe' ),
            'separate_items_with_commas' => __( 'Separate fish habitats with commas', 'aqualuxe' ),
            'add_or_remove_items'        => __( 'Add or remove fish habitats', 'aqualuxe' ),
            'choose_from_most_used'      => __( 'Choose from the most used fish habitats', 'aqualuxe' ),
            'not_found'                  => __( 'No fish habitats found.', 'aqualuxe' ),
            'no_terms'                   => __( 'No fish habitats', 'aqualuxe' ),
            'items_list_navigation'      => __( 'Fish Habitats list navigation', 'aqualuxe' ),
            'items_list'                 => __( 'Fish Habitats list', 'aqualuxe' ),
            'back_to_items'              => __( '&larr; Back to Fish Habitats', 'aqualuxe' ),
        );
        
        $habitat_args = array(
            'labels'            => $habitat_labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'fish-habitat' ),
            'show_in_rest'      => true,
        );
        
        register_taxonomy( 'fish_habitat', array( 'fish_species' ), $habitat_args );
        
        // Register Fish Care Level taxonomy
        $care_level_labels = array(
            'name'                       => _x( 'Care Levels', 'Taxonomy general name', 'aqualuxe' ),
            'singular_name'              => _x( 'Care Level', 'Taxonomy singular name', 'aqualuxe' ),
            'search_items'               => __( 'Search Care Levels', 'aqualuxe' ),
            'popular_items'              => __( 'Popular Care Levels', 'aqualuxe' ),
            'all_items'                  => __( 'All Care Levels', 'aqualuxe' ),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __( 'Edit Care Level', 'aqualuxe' ),
            'view_item'                  => __( 'View Care Level', 'aqualuxe' ),
            'update_item'                => __( 'Update Care Level', 'aqualuxe' ),
            'add_new_item'               => __( 'Add New Care Level', 'aqualuxe' ),
            'new_item_name'              => __( 'New Care Level Name', 'aqualuxe' ),
            'separate_items_with_commas' => __( 'Separate care levels with commas', 'aqualuxe' ),
            'add_or_remove_items'        => __( 'Add or remove care levels', 'aqualuxe' ),
            'choose_from_most_used'      => __( 'Choose from the most used care levels', 'aqualuxe' ),
            'not_found'                  => __( 'No care levels found.', 'aqualuxe' ),
            'no_terms'                   => __( 'No care levels', 'aqualuxe' ),
            'items_list_navigation'      => __( 'Care Levels list navigation', 'aqualuxe' ),
            'items_list'                 => __( 'Care Levels list', 'aqualuxe' ),
            'back_to_items'              => __( '&larr; Back to Care Levels', 'aqualuxe' ),
        );
        
        $care_level_args = array(
            'labels'            => $care_level_labels,
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'care-level' ),
            'show_in_rest'      => true,
        );
        
        register_taxonomy( 'fish_care_level', array( 'fish_species' ), $care_level_args );
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'fish_species_details',
            __( 'Fish Species Details', 'aqualuxe' ),
            array( $this, 'render_details_meta_box' ),
            'fish_species',
            'normal',
            'high'
        );
        
        add_meta_box(
            'fish_species_care',
            __( 'Fish Care Information', 'aqualuxe' ),
            array( $this, 'render_care_meta_box' ),
            'fish_species',
            'normal',
            'high'
        );
        
        add_meta_box(
            'fish_species_products',
            __( 'Related Products', 'aqualuxe' ),
            array( $this, 'render_products_meta_box' ),
            'fish_species',
            'side',
            'default'
        );
    }

    /**
     * Render details meta box
     *
     * @param WP_Post $post Post object.
     */
    public function render_details_meta_box( $post ) {
        // Add nonce for security
        wp_nonce_field( 'fish_species_details_nonce', 'fish_species_details_nonce' );
        
        // Get saved values
        $scientific_name = get_post_meta( $post->ID, '_fish_scientific_name', true );
        $common_names = get_post_meta( $post->ID, '_fish_common_names', true );
        $origin = get_post_meta( $post->ID, '_fish_origin', true );
        $adult_size = get_post_meta( $post->ID, '_fish_adult_size', true );
        $lifespan = get_post_meta( $post->ID, '_fish_lifespan', true );
        $temperament = get_post_meta( $post->ID, '_fish_temperament', true );
        $diet = get_post_meta( $post->ID, '_fish_diet', true );
        $breeding = get_post_meta( $post->ID, '_fish_breeding', true );
        
        // Output fields
        ?>
        <div class="fish-species-meta-box">
            <p>
                <label for="fish_scientific_name"><?php esc_html_e( 'Scientific Name:', 'aqualuxe' ); ?></label>
                <input type="text" id="fish_scientific_name" name="fish_scientific_name" value="<?php echo esc_attr( $scientific_name ); ?>" class="widefat" />
                <span class="description"><?php esc_html_e( 'Enter the scientific name of the fish species.', 'aqualuxe' ); ?></span>
            </p>
            
            <p>
                <label for="fish_common_names"><?php esc_html_e( 'Common Names:', 'aqualuxe' ); ?></label>
                <input type="text" id="fish_common_names" name="fish_common_names" value="<?php echo esc_attr( $common_names ); ?>" class="widefat" />
                <span class="description"><?php esc_html_e( 'Enter common names separated by commas.', 'aqualuxe' ); ?></span>
            </p>
            
            <p>
                <label for="fish_origin"><?php esc_html_e( 'Origin:', 'aqualuxe' ); ?></label>
                <input type="text" id="fish_origin" name="fish_origin" value="<?php echo esc_attr( $origin ); ?>" class="widefat" />
                <span class="description"><?php esc_html_e( 'Enter the geographical origin of the fish species.', 'aqualuxe' ); ?></span>
            </p>
            
            <div class="fish-species-meta-row">
                <p class="fish-species-meta-col">
                    <label for="fish_adult_size"><?php esc_html_e( 'Adult Size:', 'aqualuxe' ); ?></label>
                    <input type="text" id="fish_adult_size" name="fish_adult_size" value="<?php echo esc_attr( $adult_size ); ?>" class="widefat" />
                    <span class="description"><?php esc_html_e( 'Enter the average adult size (e.g., "3-4 inches").', 'aqualuxe' ); ?></span>
                </p>
                
                <p class="fish-species-meta-col">
                    <label for="fish_lifespan"><?php esc_html_e( 'Lifespan:', 'aqualuxe' ); ?></label>
                    <input type="text" id="fish_lifespan" name="fish_lifespan" value="<?php echo esc_attr( $lifespan ); ?>" class="widefat" />
                    <span class="description"><?php esc_html_e( 'Enter the average lifespan (e.g., "3-5 years").', 'aqualuxe' ); ?></span>
                </p>
            </div>
            
            <p>
                <label for="fish_temperament"><?php esc_html_e( 'Temperament:', 'aqualuxe' ); ?></label>
                <input type="text" id="fish_temperament" name="fish_temperament" value="<?php echo esc_attr( $temperament ); ?>" class="widefat" />
                <span class="description"><?php esc_html_e( 'Describe the temperament (e.g., "Peaceful", "Semi-aggressive").', 'aqualuxe' ); ?></span>
            </p>
            
            <p>
                <label for="fish_diet"><?php esc_html_e( 'Diet:', 'aqualuxe' ); ?></label>
                <input type="text" id="fish_diet" name="fish_diet" value="<?php echo esc_attr( $diet ); ?>" class="widefat" />
                <span class="description"><?php esc_html_e( 'Describe the diet (e.g., "Omnivore", "Carnivore").', 'aqualuxe' ); ?></span>
            </p>
            
            <p>
                <label for="fish_breeding"><?php esc_html_e( 'Breeding:', 'aqualuxe' ); ?></label>
                <textarea id="fish_breeding" name="fish_breeding" class="widefat" rows="3"><?php echo esc_textarea( $breeding ); ?></textarea>
                <span class="description"><?php esc_html_e( 'Enter breeding information.', 'aqualuxe' ); ?></span>
            </p>
        </div>
        <style>
            .fish-species-meta-box .description {
                display: block;
                color: #666;
                font-style: italic;
                margin: 2px 0 15px;
            }
            .fish-species-meta-row {
                display: flex;
                margin: 0 -10px;
            }
            .fish-species-meta-col {
                flex: 1;
                padding: 0 10px;
            }
        </style>
        <?php
    }

    /**
     * Render care meta box
     *
     * @param WP_Post $post Post object.
     */
    public function render_care_meta_box( $post ) {
        // Add nonce for security
        wp_nonce_field( 'fish_species_care_nonce', 'fish_species_care_nonce' );
        
        // Get saved values
        $tank_size = get_post_meta( $post->ID, '_fish_tank_size', true );
        $water_temp = get_post_meta( $post->ID, '_fish_water_temp', true );
        $water_ph = get_post_meta( $post->ID, '_fish_water_ph', true );
        $water_hardness = get_post_meta( $post->ID, '_fish_water_hardness', true );
        $substrate = get_post_meta( $post->ID, '_fish_substrate', true );
        $plants = get_post_meta( $post->ID, '_fish_plants', true );
        $lighting = get_post_meta( $post->ID, '_fish_lighting', true );
        $tank_mates = get_post_meta( $post->ID, '_fish_tank_mates', true );
        $special_requirements = get_post_meta( $post->ID, '_fish_special_requirements', true );
        
        // Output fields
        ?>
        <div class="fish-species-meta-box">
            <div class="fish-species-meta-row">
                <p class="fish-species-meta-col">
                    <label for="fish_tank_size"><?php esc_html_e( 'Minimum Tank Size:', 'aqualuxe' ); ?></label>
                    <input type="text" id="fish_tank_size" name="fish_tank_size" value="<?php echo esc_attr( $tank_size ); ?>" class="widefat" />
                    <span class="description"><?php esc_html_e( 'Enter the minimum tank size (e.g., "20 gallons").', 'aqualuxe' ); ?></span>
                </p>
                
                <p class="fish-species-meta-col">
                    <label for="fish_water_temp"><?php esc_html_e( 'Water Temperature:', 'aqualuxe' ); ?></label>
                    <input type="text" id="fish_water_temp" name="fish_water_temp" value="<?php echo esc_attr( $water_temp ); ?>" class="widefat" />
                    <span class="description"><?php esc_html_e( 'Enter the ideal water temperature range (e.g., "72-78°F").', 'aqualuxe' ); ?></span>
                </p>
            </div>
            
            <div class="fish-species-meta-row">
                <p class="fish-species-meta-col">
                    <label for="fish_water_ph"><?php esc_html_e( 'Water pH:', 'aqualuxe' ); ?></label>
                    <input type="text" id="fish_water_ph" name="fish_water_ph" value="<?php echo esc_attr( $water_ph ); ?>" class="widefat" />
                    <span class="description"><?php esc_html_e( 'Enter the ideal pH range (e.g., "6.5-7.5").', 'aqualuxe' ); ?></span>
                </p>
                
                <p class="fish-species-meta-col">
                    <label for="fish_water_hardness"><?php esc_html_e( 'Water Hardness:', 'aqualuxe' ); ?></label>
                    <input type="text" id="fish_water_hardness" name="fish_water_hardness" value="<?php echo esc_attr( $water_hardness ); ?>" class="widefat" />
                    <span class="description"><?php esc_html_e( 'Enter the ideal water hardness (e.g., "5-15 dGH").', 'aqualuxe' ); ?></span>
                </p>
            </div>
            
            <p>
                <label for="fish_substrate"><?php esc_html_e( 'Substrate:', 'aqualuxe' ); ?></label>
                <input type="text" id="fish_substrate" name="fish_substrate" value="<?php echo esc_attr( $substrate ); ?>" class="widefat" />
                <span class="description"><?php esc_html_e( 'Enter the recommended substrate type.', 'aqualuxe' ); ?></span>
            </p>
            
            <p>
                <label for="fish_plants"><?php esc_html_e( 'Plants:', 'aqualuxe' ); ?></label>
                <input type="text" id="fish_plants" name="fish_plants" value="<?php echo esc_attr( $plants ); ?>" class="widefat" />
                <span class="description"><?php esc_html_e( 'Enter recommended plants or plant density.', 'aqualuxe' ); ?></span>
            </p>
            
            <p>
                <label for="fish_lighting"><?php esc_html_e( 'Lighting:', 'aqualuxe' ); ?></label>
                <input type="text" id="fish_lighting" name="fish_lighting" value="<?php echo esc_attr( $lighting ); ?>" class="widefat" />
                <span class="description"><?php esc_html_e( 'Enter lighting requirements (e.g., "Low", "Moderate", "High").', 'aqualuxe' ); ?></span>
            </p>
            
            <p>
                <label for="fish_tank_mates"><?php esc_html_e( 'Compatible Tank Mates:', 'aqualuxe' ); ?></label>
                <textarea id="fish_tank_mates" name="fish_tank_mates" class="widefat" rows="3"><?php echo esc_textarea( $tank_mates ); ?></textarea>
                <span class="description"><?php esc_html_e( 'List compatible tank mates.', 'aqualuxe' ); ?></span>
            </p>
            
            <p>
                <label for="fish_special_requirements"><?php esc_html_e( 'Special Requirements:', 'aqualuxe' ); ?></label>
                <textarea id="fish_special_requirements" name="fish_special_requirements" class="widefat" rows="3"><?php echo esc_textarea( $special_requirements ); ?></textarea>
                <span class="description"><?php esc_html_e( 'Enter any special care requirements.', 'aqualuxe' ); ?></span>
            </p>
        </div>
        <?php
    }

    /**
     * Render products meta box
     *
     * @param WP_Post $post Post object.
     */
    public function render_products_meta_box( $post ) {
        // Add nonce for security
        wp_nonce_field( 'fish_species_products_nonce', 'fish_species_products_nonce' );
        
        // Get saved values
        $related_products = get_post_meta( $post->ID, '_fish_related_products', true );
        
        if ( ! is_array( $related_products ) ) {
            $related_products = array();
        }
        
        // Get all products
        $products = get_posts( array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );
        
        // Output fields
        ?>
        <div class="fish-species-meta-box">
            <p>
                <label for="fish_related_products"><?php esc_html_e( 'Select Related Products:', 'aqualuxe' ); ?></label>
                <select id="fish_related_products" name="fish_related_products[]" multiple="multiple" class="widefat" style="height: 150px;">
                    <?php foreach ( $products as $product ) : ?>
                        <option value="<?php echo esc_attr( $product->ID ); ?>" <?php selected( in_array( $product->ID, $related_products ) ); ?>>
                            <?php echo esc_html( $product->post_title ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="description"><?php esc_html_e( 'Hold Ctrl/Cmd to select multiple products.', 'aqualuxe' ); ?></span>
            </p>
        </div>
        <?php
    }

    /**
     * Save meta box data
     *
     * @param int $post_id Post ID.
     */
    public function save_meta_box_data( $post_id ) {
        // Check if this is an autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        
        // Check user permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        
        // Check if this is the correct post type
        if ( get_post_type( $post_id ) !== 'fish_species' ) {
            return;
        }
        
        // Save details meta box data
        if ( isset( $_POST['fish_species_details_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fish_species_details_nonce'] ) ), 'fish_species_details_nonce' ) ) {
            // Save scientific name
            if ( isset( $_POST['fish_scientific_name'] ) ) {
                update_post_meta( $post_id, '_fish_scientific_name', sanitize_text_field( wp_unslash( $_POST['fish_scientific_name'] ) ) );
            }
            
            // Save common names
            if ( isset( $_POST['fish_common_names'] ) ) {
                update_post_meta( $post_id, '_fish_common_names', sanitize_text_field( wp_unslash( $_POST['fish_common_names'] ) ) );
            }
            
            // Save origin
            if ( isset( $_POST['fish_origin'] ) ) {
                update_post_meta( $post_id, '_fish_origin', sanitize_text_field( wp_unslash( $_POST['fish_origin'] ) ) );
            }
            
            // Save adult size
            if ( isset( $_POST['fish_adult_size'] ) ) {
                update_post_meta( $post_id, '_fish_adult_size', sanitize_text_field( wp_unslash( $_POST['fish_adult_size'] ) ) );
            }
            
            // Save lifespan
            if ( isset( $_POST['fish_lifespan'] ) ) {
                update_post_meta( $post_id, '_fish_lifespan', sanitize_text_field( wp_unslash( $_POST['fish_lifespan'] ) ) );
            }
            
            // Save temperament
            if ( isset( $_POST['fish_temperament'] ) ) {
                update_post_meta( $post_id, '_fish_temperament', sanitize_text_field( wp_unslash( $_POST['fish_temperament'] ) ) );
            }
            
            // Save diet
            if ( isset( $_POST['fish_diet'] ) ) {
                update_post_meta( $post_id, '_fish_diet', sanitize_text_field( wp_unslash( $_POST['fish_diet'] ) ) );
            }
            
            // Save breeding
            if ( isset( $_POST['fish_breeding'] ) ) {
                update_post_meta( $post_id, '_fish_breeding', sanitize_textarea_field( wp_unslash( $_POST['fish_breeding'] ) ) );
            }
        }
        
        // Save care meta box data
        if ( isset( $_POST['fish_species_care_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fish_species_care_nonce'] ) ), 'fish_species_care_nonce' ) ) {
            // Save tank size
            if ( isset( $_POST['fish_tank_size'] ) ) {
                update_post_meta( $post_id, '_fish_tank_size', sanitize_text_field( wp_unslash( $_POST['fish_tank_size'] ) ) );
            }
            
            // Save water temperature
            if ( isset( $_POST['fish_water_temp'] ) ) {
                update_post_meta( $post_id, '_fish_water_temp', sanitize_text_field( wp_unslash( $_POST['fish_water_temp'] ) ) );
            }
            
            // Save water pH
            if ( isset( $_POST['fish_water_ph'] ) ) {
                update_post_meta( $post_id, '_fish_water_ph', sanitize_text_field( wp_unslash( $_POST['fish_water_ph'] ) ) );
            }
            
            // Save water hardness
            if ( isset( $_POST['fish_water_hardness'] ) ) {
                update_post_meta( $post_id, '_fish_water_hardness', sanitize_text_field( wp_unslash( $_POST['fish_water_hardness'] ) ) );
            }
            
            // Save substrate
            if ( isset( $_POST['fish_substrate'] ) ) {
                update_post_meta( $post_id, '_fish_substrate', sanitize_text_field( wp_unslash( $_POST['fish_substrate'] ) ) );
            }
            
            // Save plants
            if ( isset( $_POST['fish_plants'] ) ) {
                update_post_meta( $post_id, '_fish_plants', sanitize_text_field( wp_unslash( $_POST['fish_plants'] ) ) );
            }
            
            // Save lighting
            if ( isset( $_POST['fish_lighting'] ) ) {
                update_post_meta( $post_id, '_fish_lighting', sanitize_text_field( wp_unslash( $_POST['fish_lighting'] ) ) );
            }
            
            // Save tank mates
            if ( isset( $_POST['fish_tank_mates'] ) ) {
                update_post_meta( $post_id, '_fish_tank_mates', sanitize_textarea_field( wp_unslash( $_POST['fish_tank_mates'] ) ) );
            }
            
            // Save special requirements
            if ( isset( $_POST['fish_special_requirements'] ) ) {
                update_post_meta( $post_id, '_fish_special_requirements', sanitize_textarea_field( wp_unslash( $_POST['fish_special_requirements'] ) ) );
            }
        }
        
        // Save products meta box data
        if ( isset( $_POST['fish_species_products_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fish_species_products_nonce'] ) ), 'fish_species_products_nonce' ) ) {
            // Save related products
            if ( isset( $_POST['fish_related_products'] ) && is_array( $_POST['fish_related_products'] ) ) {
                $related_products = array_map( 'intval', $_POST['fish_related_products'] );
                update_post_meta( $post_id, '_fish_related_products', $related_products );
            } else {
                update_post_meta( $post_id, '_fish_related_products', array() );
            }
        }
    }

    /**
     * Set custom columns
     *
     * @param array $columns Columns.
     * @return array Modified columns.
     */
    public function set_custom_columns( $columns ) {
        $new_columns = array();
        
        // Add checkbox and title first
        if ( isset( $columns['cb'] ) ) {
            $new_columns['cb'] = $columns['cb'];
        }
        
        if ( isset( $columns['title'] ) ) {
            $new_columns['title'] = $columns['title'];
        }
        
        // Add custom columns
        $new_columns['scientific_name'] = __( 'Scientific Name', 'aqualuxe' );
        $new_columns['care_level'] = __( 'Care Level', 'aqualuxe' );
        $new_columns['adult_size'] = __( 'Adult Size', 'aqualuxe' );
        $new_columns['tank_size'] = __( 'Tank Size', 'aqualuxe' );
        $new_columns['temperament'] = __( 'Temperament', 'aqualuxe' );
        
        // Add remaining columns
        foreach ( $columns as $key => $value ) {
            if ( ! isset( $new_columns[ $key ] ) && $key !== 'date' ) {
                $new_columns[ $key ] = $value;
            }
        }
        
        // Add date column at the end
        if ( isset( $columns['date'] ) ) {
            $new_columns['date'] = $columns['date'];
        }
        
        return $new_columns;
    }

    /**
     * Custom column content
     *
     * @param string $column Column name.
     * @param int    $post_id Post ID.
     */
    public function custom_column_content( $column, $post_id ) {
        switch ( $column ) {
            case 'scientific_name':
                $scientific_name = get_post_meta( $post_id, '_fish_scientific_name', true );
                echo esc_html( $scientific_name );
                break;
                
            case 'care_level':
                $care_levels = get_the_terms( $post_id, 'fish_care_level' );
                
                if ( ! empty( $care_levels ) && ! is_wp_error( $care_levels ) ) {
                    $care_level_names = array();
                    
                    foreach ( $care_levels as $care_level ) {
                        $care_level_names[] = $care_level->name;
                    }
                    
                    echo esc_html( implode( ', ', $care_level_names ) );
                } else {
                    echo '—';
                }
                break;
                
            case 'adult_size':
                $adult_size = get_post_meta( $post_id, '_fish_adult_size', true );
                echo esc_html( $adult_size );
                break;
                
            case 'tank_size':
                $tank_size = get_post_meta( $post_id, '_fish_tank_size', true );
                echo esc_html( $tank_size );
                break;
                
            case 'temperament':
                $temperament = get_post_meta( $post_id, '_fish_temperament', true );
                echo esc_html( $temperament );
                break;
        }
    }

    /**
     * Add admin filters
     *
     * @param string $post_type Post type.
     */
    public function add_admin_filters( $post_type ) {
        if ( 'fish_species' !== $post_type ) {
            return;
        }
        
        // Add fish category filter
        $fish_category_taxonomy = 'fish_category';
        $fish_category_terms = get_terms( array(
            'taxonomy'   => $fish_category_taxonomy,
            'hide_empty' => true,
        ) );
        
        if ( ! empty( $fish_category_terms ) && ! is_wp_error( $fish_category_terms ) ) {
            $selected_fish_category = isset( $_GET[ $fish_category_taxonomy ] ) ? sanitize_text_field( wp_unslash( $_GET[ $fish_category_taxonomy ] ) ) : '';
            
            echo '<select name="' . esc_attr( $fish_category_taxonomy ) . '">';
            echo '<option value="">' . esc_html__( 'All Fish Categories', 'aqualuxe' ) . '</option>';
            
            foreach ( $fish_category_terms as $term ) {
                echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $selected_fish_category, $term->slug, false ) . '>' . esc_html( $term->name ) . '</option>';
            }
            
            echo '</select>';
        }
        
        // Add fish habitat filter
        $fish_habitat_taxonomy = 'fish_habitat';
        $fish_habitat_terms = get_terms( array(
            'taxonomy'   => $fish_habitat_taxonomy,
            'hide_empty' => true,
        ) );
        
        if ( ! empty( $fish_habitat_terms ) && ! is_wp_error( $fish_habitat_terms ) ) {
            $selected_fish_habitat = isset( $_GET[ $fish_habitat_taxonomy ] ) ? sanitize_text_field( wp_unslash( $_GET[ $fish_habitat_taxonomy ] ) ) : '';
            
            echo '<select name="' . esc_attr( $fish_habitat_taxonomy ) . '">';
            echo '<option value="">' . esc_html__( 'All Fish Habitats', 'aqualuxe' ) . '</option>';
            
            foreach ( $fish_habitat_terms as $term ) {
                echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $selected_fish_habitat, $term->slug, false ) . '>' . esc_html( $term->name ) . '</option>';
            }
            
            echo '</select>';
        }
        
        // Add fish care level filter
        $fish_care_level_taxonomy = 'fish_care_level';
        $fish_care_level_terms = get_terms( array(
            'taxonomy'   => $fish_care_level_taxonomy,
            'hide_empty' => true,
        ) );
        
        if ( ! empty( $fish_care_level_terms ) && ! is_wp_error( $fish_care_level_terms ) ) {
            $selected_fish_care_level = isset( $_GET[ $fish_care_level_taxonomy ] ) ? sanitize_text_field( wp_unslash( $_GET[ $fish_care_level_taxonomy ] ) ) : '';
            
            echo '<select name="' . esc_attr( $fish_care_level_taxonomy ) . '">';
            echo '<option value="">' . esc_html__( 'All Care Levels', 'aqualuxe' ) . '</option>';
            
            foreach ( $fish_care_level_terms as $term ) {
                echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $selected_fish_care_level, $term->slug, false ) . '>' . esc_html( $term->name ) . '</option>';
            }
            
            echo '</select>';
        }
    }

    /**
     * Filter query
     *
     * @param WP_Query $query Query object.
     * @return WP_Query Modified query.
     */
    public function filter_query( $query ) {
        global $pagenow;
        
        // Check if we're in the admin area, on the fish species list page, and the query is for fish species
        if ( is_admin() && 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && 'fish_species' === $_GET['post_type'] && $query->is_main_query() ) {
            // Filter by fish category
            if ( isset( $_GET['fish_category'] ) && ! empty( $_GET['fish_category'] ) ) {
                $query->query_vars['tax_query'][] = array(
                    'taxonomy' => 'fish_category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( wp_unslash( $_GET['fish_category'] ) ),
                );
            }
            
            // Filter by fish habitat
            if ( isset( $_GET['fish_habitat'] ) && ! empty( $_GET['fish_habitat'] ) ) {
                $query->query_vars['tax_query'][] = array(
                    'taxonomy' => 'fish_habitat',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( wp_unslash( $_GET['fish_habitat'] ) ),
                );
            }
            
            // Filter by fish care level
            if ( isset( $_GET['fish_care_level'] ) && ! empty( $_GET['fish_care_level'] ) ) {
                $query->query_vars['tax_query'][] = array(
                    'taxonomy' => 'fish_care_level',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( wp_unslash( $_GET['fish_care_level'] ) ),
                );
            }
        }
        
        return $query;
    }

    /**
     * Fish species shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function fish_species_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'category'   => '',
            'habitat'    => '',
            'care_level' => '',
            'orderby'    => 'title',
            'order'      => 'ASC',
            'limit'      => 10,
        ), $atts, 'fish_species' );
        
        // Build query args
        $args = array(
            'post_type'      => 'fish_species',
            'posts_per_page' => intval( $atts['limit'] ),
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        );
        
        // Add taxonomy queries
        $tax_query = array();
        
        if ( ! empty( $atts['category'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'fish_category',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['category'] ),
            );
        }
        
        if ( ! empty( $atts['habitat'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'fish_habitat',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['habitat'] ),
            );
        }
        
        if ( ! empty( $atts['care_level'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'fish_care_level',
                'field'    => 'slug',
                'terms'    => explode( ',', $atts['care_level'] ),
            );
        }
        
        if ( ! empty( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }
        
        // Get fish species
        $fish_species = get_posts( $args );
        
        if ( empty( $fish_species ) ) {
            return '<p>' . esc_html__( 'No fish species found.', 'aqualuxe' ) . '</p>';
        }
        
        // Build output
        $output = '<div class="fish-species-grid">';
        
        foreach ( $fish_species as $fish ) {
            $permalink = get_permalink( $fish->ID );
            $title = get_the_title( $fish->ID );
            $scientific_name = get_post_meta( $fish->ID, '_fish_scientific_name', true );
            $care_levels = get_the_terms( $fish->ID, 'fish_care_level' );
            $care_level_text = '';
            
            if ( ! empty( $care_levels ) && ! is_wp_error( $care_levels ) ) {
                $care_level_names = array();
                
                foreach ( $care_levels as $care_level ) {
                    $care_level_names[] = $care_level->name;
                }
                
                $care_level_text = implode( ', ', $care_level_names );
            }
            
            $output .= '<div class="fish-species-item">';
            
            if ( has_post_thumbnail( $fish->ID ) ) {
                $output .= '<a href="' . esc_url( $permalink ) . '" class="fish-species-image">';
                $output .= get_the_post_thumbnail( $fish->ID, 'medium' );
                $output .= '</a>';
            }
            
            $output .= '<div class="fish-species-content">';
            $output .= '<h3 class="fish-species-title"><a href="' . esc_url( $permalink ) . '">' . esc_html( $title ) . '</a></h3>';
            
            if ( $scientific_name ) {
                $output .= '<p class="fish-species-scientific-name"><em>' . esc_html( $scientific_name ) . '</em></p>';
            }
            
            if ( $care_level_text ) {
                $output .= '<p class="fish-species-care-level">' . esc_html__( 'Care Level:', 'aqualuxe' ) . ' ' . esc_html( $care_level_text ) . '</p>';
            }
            
            $output .= '<a href="' . esc_url( $permalink ) . '" class="fish-species-link">' . esc_html__( 'Learn More', 'aqualuxe' ) . '</a>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }

    /**
     * Single template
     *
     * @param string $template Template path.
     * @return string Modified template path.
     */
    public function single_template( $template ) {
        if ( is_singular( 'fish_species' ) ) {
            $theme_template = locate_template( array( 'single-fish_species.php' ) );
            
            if ( $theme_template ) {
                return $theme_template;
            } else {
                return plugin_dir_path( __FILE__ ) . 'templates/single-fish_species.php';
            }
        }
        
        return $template;
    }

    /**
     * Archive template
     *
     * @param string $template Template path.
     * @return string Modified template path.
     */
    public function archive_template( $template ) {
        if ( is_post_type_archive( 'fish_species' ) || is_tax( array( 'fish_category', 'fish_habitat', 'fish_care_level' ) ) ) {
            $theme_template = locate_template( array( 'archive-fish_species.php' ) );
            
            if ( $theme_template ) {
                return $theme_template;
            } else {
                return plugin_dir_path( __FILE__ ) . 'templates/archive-fish_species.php';
            }
        }
        
        return $template;
    }

    /**
     * Related products args
     *
     * @param array $args Related products args.
     * @return array Modified related products args.
     */
    public function related_products_args( $args ) {
        global $product;
        
        if ( ! $product ) {
            return $args;
        }
        
        // Get fish species related to this product
        $fish_species = get_posts( array(
            'post_type'      => 'fish_species',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_fish_related_products',
                    'value'   => $product->get_id(),
                    'compare' => 'LIKE',
                ),
            ),
        ) );
        
        if ( ! empty( $fish_species ) ) {
            $related_products = array();
            
            foreach ( $fish_species as $fish ) {
                $fish_related_products = get_post_meta( $fish->ID, '_fish_related_products', true );
                
                if ( is_array( $fish_related_products ) ) {
                    $related_products = array_merge( $related_products, $fish_related_products );
                }
            }
            
            // Remove current product
            $related_products = array_diff( $related_products, array( $product->get_id() ) );
            
            if ( ! empty( $related_products ) ) {
                $args['post__in'] = $related_products;
                $args['orderby'] = 'post__in';
            }
        }
        
        return $args;
    }

    /**
     * Product options
     */
    public function product_options() {
        global $post;
        
        echo '<div class="options_group">';
        
        // Get all fish species
        $fish_species = get_posts( array(
            'post_type'      => 'fish_species',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );
        
        if ( ! empty( $fish_species ) ) {
            // Get selected fish species
            $selected_fish_species = get_post_meta( $post->ID, '_related_fish_species', true );
            
            if ( ! is_array( $selected_fish_species ) ) {
                $selected_fish_species = array();
            }
            
            // Output field
            woocommerce_wp_select( array(
                'id'          => '_related_fish_species',
                'label'       => __( 'Related Fish Species', 'aqualuxe' ),
                'description' => __( 'Select fish species related to this product.', 'aqualuxe' ),
                'desc_tip'    => true,
                'options'     => wp_list_pluck( $fish_species, 'post_title', 'ID' ),
                'value'       => $selected_fish_species,
                'custom_attributes' => array(
                    'multiple' => 'multiple',
                    'style'    => 'height: 150px;',
                ),
            ) );
        }
        
        echo '</div>';
    }

    /**
     * Save product options
     *
     * @param int $post_id Post ID.
     */
    public function save_product_options( $post_id ) {
        // Save related fish species
        if ( isset( $_POST['_related_fish_species'] ) && is_array( $_POST['_related_fish_species'] ) ) {
            $related_fish_species = array_map( 'intval', $_POST['_related_fish_species'] );
            update_post_meta( $post_id, '_related_fish_species', $related_fish_species );
        } else {
            update_post_meta( $post_id, '_related_fish_species', array() );
        }
    }

    /**
     * Product tabs
     *
     * @param array $tabs Product tabs.
     * @return array Modified product tabs.
     */
    public function product_tabs( $tabs ) {
        global $post;
        
        // Get related fish species
        $related_fish_species = get_post_meta( $post->ID, '_related_fish_species', true );
        
        if ( is_array( $related_fish_species ) && ! empty( $related_fish_species ) ) {
            $tabs['fish_species'] = array(
                'title'    => __( 'Fish Species', 'aqualuxe' ),
                'priority' => 25,
                'callback' => array( $this, 'fish_species_tab_content' ),
            );
        }
        
        return $tabs;
    }

    /**
     * Fish species tab content
     */
    public function fish_species_tab_content() {
        global $post;
        
        // Get related fish species
        $related_fish_species = get_post_meta( $post->ID, '_related_fish_species', true );
        
        if ( ! is_array( $related_fish_species ) || empty( $related_fish_species ) ) {
            return;
        }
        
        // Get fish species
        $fish_species = get_posts( array(
            'post_type'      => 'fish_species',
            'posts_per_page' => -1,
            'post__in'       => $related_fish_species,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );
        
        if ( empty( $fish_species ) ) {
            return;
        }
        
        // Output fish species
        echo '<div class="fish-species-tab-content">';
        
        foreach ( $fish_species as $fish ) {
            $permalink = get_permalink( $fish->ID );
            $title = get_the_title( $fish->ID );
            $scientific_name = get_post_meta( $fish->ID, '_fish_scientific_name', true );
            $care_levels = get_the_terms( $fish->ID, 'fish_care_level' );
            $care_level_text = '';
            
            if ( ! empty( $care_levels ) && ! is_wp_error( $care_levels ) ) {
                $care_level_names = array();
                
                foreach ( $care_levels as $care_level ) {
                    $care_level_names[] = $care_level->name;
                }
                
                $care_level_text = implode( ', ', $care_level_names );
            }
            
            echo '<div class="fish-species-item">';
            
            if ( has_post_thumbnail( $fish->ID ) ) {
                echo '<a href="' . esc_url( $permalink ) . '" class="fish-species-image">';
                echo get_the_post_thumbnail( $fish->ID, 'thumbnail' );
                echo '</a>';
            }
            
            echo '<div class="fish-species-content">';
            echo '<h3 class="fish-species-title"><a href="' . esc_url( $permalink ) . '">' . esc_html( $title ) . '</a></h3>';
            
            if ( $scientific_name ) {
                echo '<p class="fish-species-scientific-name"><em>' . esc_html( $scientific_name ) . '</em></p>';
            }
            
            if ( $care_level_text ) {
                echo '<p class="fish-species-care-level">' . esc_html__( 'Care Level:', 'aqualuxe' ) . ' ' . esc_html( $care_level_text ) . '</p>';
            }
            
            echo '<a href="' . esc_url( $permalink ) . '" class="fish-species-link">' . esc_html__( 'Learn More', 'aqualuxe' ) . '</a>';
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    }
}

// Initialize the class
new AquaLuxe_Fish_Species();