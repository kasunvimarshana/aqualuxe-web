<?php
/**
 * Custom Post Types Module
 * 
 * Manages custom post types, taxonomies, and meta fields for the AquaLuxe theme.
 * Provides aquarium-specific content types and organizational structures.
 * 
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Modules;

use AquaLuxe\Core\Base_Module;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Custom Post Types Module Class
 *
 * Responsible for:
 * - Registering aquarium-specific post types
 * - Creating taxonomies for categorization
 * - Managing custom meta fields
 * - Setting up admin interfaces
 * - Providing template integration
 *
 * @since 1.0.0
 */
class Custom_Post_Types extends Base_Module {

    /**
     * Post types configuration
     *
     * @var array
     */
    private $post_types = array();

    /**
     * Taxonomies configuration
     *
     * @var array
     */
    private $taxonomies = array();

    /**
     * Get the module name.
     *
     * @return string The module name.
     */
    public function get_name(): string {
        return 'Custom Post Types';
    }

    /**
     * Get the module description.
     *
     * @return string The module description.
     */
    public function get_description(): string {
        return 'Manages custom post types, taxonomies, and meta fields for aquarium-specific content organization.';
    }

    /**
     * Get the module version.
     *
     * @return string The module version.
     */
    public function get_version(): string {
        return '1.0.0';
    }

    /**
     * Get the module dependencies.
     *
     * @return array Array of required dependencies.
     */
    public function get_dependencies(): array {
        return array(); // No dependencies
    }

    /**
     * Module-specific setup.
     *
     * @return void
     */
    protected function setup(): void {
        $this->define_post_types();
        $this->define_taxonomies();
        $this->setup_hooks();

        $this->log( 'Custom Post Types module setup complete' );
    }

    /**
     * Define post types configuration.
     *
     * @return void
     */
    private function define_post_types(): void {
        $this->post_types = array(
            'aquarium_service' => array(
                'labels' => array(
                    'name'               => esc_html__( 'Aquarium Services', 'aqualuxe' ),
                    'singular_name'      => esc_html__( 'Service', 'aqualuxe' ),
                    'add_new'            => esc_html__( 'Add New Service', 'aqualuxe' ),
                    'add_new_item'       => esc_html__( 'Add New Service', 'aqualuxe' ),
                    'edit_item'          => esc_html__( 'Edit Service', 'aqualuxe' ),
                    'new_item'           => esc_html__( 'New Service', 'aqualuxe' ),
                    'view_item'          => esc_html__( 'View Service', 'aqualuxe' ),
                    'search_items'       => esc_html__( 'Search Services', 'aqualuxe' ),
                    'not_found'          => esc_html__( 'No services found', 'aqualuxe' ),
                    'not_found_in_trash' => esc_html__( 'No services found in trash', 'aqualuxe' ),
                ),
                'public'        => true,
                'has_archive'   => true,
                'show_ui'       => true,
                'show_in_menu'  => true,
                'menu_icon'     => 'dashicons-admin-tools',
                'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
                'rewrite'       => array( 'slug' => 'services' ),
                'show_in_rest'  => true,
            ),
            'aquarium_project' => array(
                'labels' => array(
                    'name'               => esc_html__( 'Aquarium Projects', 'aqualuxe' ),
                    'singular_name'      => esc_html__( 'Project', 'aqualuxe' ),
                    'add_new'            => esc_html__( 'Add New Project', 'aqualuxe' ),
                    'add_new_item'       => esc_html__( 'Add New Project', 'aqualuxe' ),
                    'edit_item'          => esc_html__( 'Edit Project', 'aqualuxe' ),
                    'new_item'           => esc_html__( 'New Project', 'aqualuxe' ),
                    'view_item'          => esc_html__( 'View Project', 'aqualuxe' ),
                    'search_items'       => esc_html__( 'Search Projects', 'aqualuxe' ),
                    'not_found'          => esc_html__( 'No projects found', 'aqualuxe' ),
                    'not_found_in_trash' => esc_html__( 'No projects found in trash', 'aqualuxe' ),
                ),
                'public'        => true,
                'has_archive'   => true,
                'show_ui'       => true,
                'show_in_menu'  => true,
                'menu_icon'     => 'dashicons-format-gallery',
                'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
                'rewrite'       => array( 'slug' => 'projects' ),
                'show_in_rest'  => true,
            ),
            'fish_species' => array(
                'labels' => array(
                    'name'               => esc_html__( 'Fish Species', 'aqualuxe' ),
                    'singular_name'      => esc_html__( 'Fish Species', 'aqualuxe' ),
                    'add_new'            => esc_html__( 'Add New Species', 'aqualuxe' ),
                    'add_new_item'       => esc_html__( 'Add New Fish Species', 'aqualuxe' ),
                    'edit_item'          => esc_html__( 'Edit Fish Species', 'aqualuxe' ),
                    'new_item'           => esc_html__( 'New Fish Species', 'aqualuxe' ),
                    'view_item'          => esc_html__( 'View Fish Species', 'aqualuxe' ),
                    'search_items'       => esc_html__( 'Search Fish Species', 'aqualuxe' ),
                    'not_found'          => esc_html__( 'No fish species found', 'aqualuxe' ),
                    'not_found_in_trash' => esc_html__( 'No fish species found in trash', 'aqualuxe' ),
                ),
                'public'        => true,
                'has_archive'   => true,
                'show_ui'       => true,
                'show_in_menu'  => true,
                'menu_icon'     => 'dashicons-pets',
                'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
                'rewrite'       => array( 'slug' => 'fish-species' ),
                'show_in_rest'  => true,
            ),
            'aquarium_guide' => array(
                'labels' => array(
                    'name'               => esc_html__( 'Aquarium Guides', 'aqualuxe' ),
                    'singular_name'      => esc_html__( 'Guide', 'aqualuxe' ),
                    'add_new'            => esc_html__( 'Add New Guide', 'aqualuxe' ),
                    'add_new_item'       => esc_html__( 'Add New Guide', 'aqualuxe' ),
                    'edit_item'          => esc_html__( 'Edit Guide', 'aqualuxe' ),
                    'new_item'           => esc_html__( 'New Guide', 'aqualuxe' ),
                    'view_item'          => esc_html__( 'View Guide', 'aqualuxe' ),
                    'search_items'       => esc_html__( 'Search Guides', 'aqualuxe' ),
                    'not_found'          => esc_html__( 'No guides found', 'aqualuxe' ),
                    'not_found_in_trash' => esc_html__( 'No guides found in trash', 'aqualuxe' ),
                ),
                'public'        => true,
                'has_archive'   => true,
                'show_ui'       => true,
                'show_in_menu'  => true,
                'menu_icon'     => 'dashicons-book',
                'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ),
                'rewrite'       => array( 'slug' => 'guides' ),
                'show_in_rest'  => true,
            ),
            'testimonial' => array(
                'labels' => array(
                    'name'               => esc_html__( 'Testimonials', 'aqualuxe' ),
                    'singular_name'      => esc_html__( 'Testimonial', 'aqualuxe' ),
                    'add_new'            => esc_html__( 'Add New Testimonial', 'aqualuxe' ),
                    'add_new_item'       => esc_html__( 'Add New Testimonial', 'aqualuxe' ),
                    'edit_item'          => esc_html__( 'Edit Testimonial', 'aqualuxe' ),
                    'new_item'           => esc_html__( 'New Testimonial', 'aqualuxe' ),
                    'view_item'          => esc_html__( 'View Testimonial', 'aqualuxe' ),
                    'search_items'       => esc_html__( 'Search Testimonials', 'aqualuxe' ),
                    'not_found'          => esc_html__( 'No testimonials found', 'aqualuxe' ),
                    'not_found_in_trash' => esc_html__( 'No testimonials found in trash', 'aqualuxe' ),
                ),
                'public'        => true,
                'has_archive'   => true,
                'show_ui'       => true,
                'show_in_menu'  => true,
                'menu_icon'     => 'dashicons-format-quote',
                'supports'      => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
                'rewrite'       => array( 'slug' => 'testimonials' ),
                'show_in_rest'  => true,
            ),
        );

        $this->post_types = apply_filters( 'aqualuxe_custom_post_types', $this->post_types );
    }

    /**
     * Define taxonomies configuration.
     *
     * @return void
     */
    private function define_taxonomies(): void {
        $this->taxonomies = array(
            'service_category' => array(
                'post_types' => array( 'aquarium_service' ),
                'args' => array(
                    'labels' => array(
                        'name'          => esc_html__( 'Service Categories', 'aqualuxe' ),
                        'singular_name' => esc_html__( 'Service Category', 'aqualuxe' ),
                        'add_new_item'  => esc_html__( 'Add New Service Category', 'aqualuxe' ),
                        'edit_item'     => esc_html__( 'Edit Service Category', 'aqualuxe' ),
                        'search_items'  => esc_html__( 'Search Service Categories', 'aqualuxe' ),
                    ),
                    'hierarchical' => true,
                    'public'       => true,
                    'show_ui'      => true,
                    'show_in_rest' => true,
                    'rewrite'      => array( 'slug' => 'service-category' ),
                ),
            ),
            'project_type' => array(
                'post_types' => array( 'aquarium_project' ),
                'args' => array(
                    'labels' => array(
                        'name'          => esc_html__( 'Project Types', 'aqualuxe' ),
                        'singular_name' => esc_html__( 'Project Type', 'aqualuxe' ),
                        'add_new_item'  => esc_html__( 'Add New Project Type', 'aqualuxe' ),
                        'edit_item'     => esc_html__( 'Edit Project Type', 'aqualuxe' ),
                        'search_items'  => esc_html__( 'Search Project Types', 'aqualuxe' ),
                    ),
                    'hierarchical' => true,
                    'public'       => true,
                    'show_ui'      => true,
                    'show_in_rest' => true,
                    'rewrite'      => array( 'slug' => 'project-type' ),
                ),
            ),
            'fish_habitat' => array(
                'post_types' => array( 'fish_species' ),
                'args' => array(
                    'labels' => array(
                        'name'          => esc_html__( 'Fish Habitats', 'aqualuxe' ),
                        'singular_name' => esc_html__( 'Fish Habitat', 'aqualuxe' ),
                        'add_new_item'  => esc_html__( 'Add New Fish Habitat', 'aqualuxe' ),
                        'edit_item'     => esc_html__( 'Edit Fish Habitat', 'aqualuxe' ),
                        'search_items'  => esc_html__( 'Search Fish Habitats', 'aqualuxe' ),
                    ),
                    'hierarchical' => true,
                    'public'       => true,
                    'show_ui'      => true,
                    'show_in_rest' => true,
                    'rewrite'      => array( 'slug' => 'fish-habitat' ),
                ),
            ),
            'fish_care_level' => array(
                'post_types' => array( 'fish_species' ),
                'args' => array(
                    'labels' => array(
                        'name'          => esc_html__( 'Care Levels', 'aqualuxe' ),
                        'singular_name' => esc_html__( 'Care Level', 'aqualuxe' ),
                        'add_new_item'  => esc_html__( 'Add New Care Level', 'aqualuxe' ),
                        'edit_item'     => esc_html__( 'Edit Care Level', 'aqualuxe' ),
                        'search_items'  => esc_html__( 'Search Care Levels', 'aqualuxe' ),
                    ),
                    'hierarchical' => false,
                    'public'       => true,
                    'show_ui'      => true,
                    'show_in_rest' => true,
                    'rewrite'      => array( 'slug' => 'care-level' ),
                ),
            ),
            'guide_category' => array(
                'post_types' => array( 'aquarium_guide' ),
                'args' => array(
                    'labels' => array(
                        'name'          => esc_html__( 'Guide Categories', 'aqualuxe' ),
                        'singular_name' => esc_html__( 'Guide Category', 'aqualuxe' ),
                        'add_new_item'  => esc_html__( 'Add New Guide Category', 'aqualuxe' ),
                        'edit_item'     => esc_html__( 'Edit Guide Category', 'aqualuxe' ),
                        'search_items'  => esc_html__( 'Search Guide Categories', 'aqualuxe' ),
                    ),
                    'hierarchical' => true,
                    'public'       => true,
                    'show_ui'      => true,
                    'show_in_rest' => true,
                    'rewrite'      => array( 'slug' => 'guide-category' ),
                ),
            ),
        );

        $this->taxonomies = apply_filters( 'aqualuxe_custom_taxonomies', $this->taxonomies );
    }

    /**
     * Setup WordPress hooks.
     *
     * @return void
     */
    private function setup_hooks(): void {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
        
        // Admin column customization
        add_filter( 'manage_aquarium_service_posts_columns', array( $this, 'service_admin_columns' ) );
        add_action( 'manage_aquarium_service_posts_custom_column', array( $this, 'service_admin_column_content' ), 10, 2 );
        
        add_filter( 'manage_fish_species_posts_columns', array( $this, 'fish_admin_columns' ) );
        add_action( 'manage_fish_species_posts_custom_column', array( $this, 'fish_admin_column_content' ), 10, 2 );

        // Template integration
        add_filter( 'template_include', array( $this, 'template_include' ) );
        add_action( 'pre_get_posts', array( $this, 'modify_archive_queries' ) );

        // Shortcodes
        add_shortcode( 'aqualuxe_services', array( $this, 'services_shortcode' ) );
        add_shortcode( 'aqualuxe_projects', array( $this, 'projects_shortcode' ) );
        add_shortcode( 'aqualuxe_testimonials', array( $this, 'testimonials_shortcode' ) );
        add_shortcode( 'aqualuxe_fish_species', array( $this, 'fish_species_shortcode' ) );
    }

    /**
     * Called on WordPress 'init' action.
     *
     * @return void
     */
    public function on_init(): void {
        // Register post types
        foreach ( $this->post_types as $post_type => $args ) {
            register_post_type( $post_type, $args );
        }

        // Register taxonomies
        foreach ( $this->taxonomies as $taxonomy => $config ) {
            register_taxonomy( $taxonomy, $config['post_types'], $config['args'] );
        }

        // Insert default terms for taxonomies
        $this->insert_default_terms();
    }

    /**
     * Insert default terms for taxonomies.
     *
     * @return void
     */
    private function insert_default_terms(): void {
        $default_terms = array(
            'service_category' => array(
                'Aquarium Design',
                'Maintenance',
                'Installation',
                'Consultation',
                'Repair',
            ),
            'project_type' => array(
                'Residential',
                'Commercial',
                'Public Aquarium',
                'Custom Build',
            ),
            'fish_habitat' => array(
                'Freshwater',
                'Saltwater',
                'Reef',
                'Brackish',
            ),
            'fish_care_level' => array(
                'Beginner',
                'Intermediate',
                'Advanced',
                'Expert',
            ),
            'guide_category' => array(
                'Setup & Installation',
                'Maintenance & Care',
                'Fish Care',
                'Plant Care',
                'Troubleshooting',
            ),
        );

        foreach ( $default_terms as $taxonomy => $terms ) {
            foreach ( $terms as $term ) {
                if ( ! term_exists( $term, $taxonomy ) ) {
                    wp_insert_term( $term, $taxonomy );
                }
            }
        }
    }

    /**
     * Add meta boxes for custom post types.
     *
     * @return void
     */
    public function add_meta_boxes(): void {
        // Service meta box
        add_meta_box(
            'service_details',
            esc_html__( 'Service Details', 'aqualuxe' ),
            array( $this, 'service_meta_box_callback' ),
            'aquarium_service',
            'normal',
            'high'
        );

        // Fish species meta box
        add_meta_box(
            'fish_details',
            esc_html__( 'Fish Details', 'aqualuxe' ),
            array( $this, 'fish_meta_box_callback' ),
            'fish_species',
            'normal',
            'high'
        );

        // Project meta box
        add_meta_box(
            'project_details',
            esc_html__( 'Project Details', 'aqualuxe' ),
            array( $this, 'project_meta_box_callback' ),
            'aquarium_project',
            'normal',
            'high'
        );

        // Testimonial meta box
        add_meta_box(
            'testimonial_details',
            esc_html__( 'Testimonial Details', 'aqualuxe' ),
            array( $this, 'testimonial_meta_box_callback' ),
            'testimonial',
            'normal',
            'high'
        );
    }

    /**
     * Service meta box callback.
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function service_meta_box_callback( \WP_Post $post ): void {
        wp_nonce_field( 'service_meta_box', 'service_meta_box_nonce' );

        $price = get_post_meta( $post->ID, '_service_price', true );
        $duration = get_post_meta( $post->ID, '_service_duration', true );
        $features = get_post_meta( $post->ID, '_service_features', true );

        ?>
        <table class="form-table">
            <tr>
                <th><label for="service_price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="service_price" name="service_price" value="<?php echo esc_attr( $price ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="service_duration"><?php esc_html_e( 'Duration', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr( $duration ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="service_features"><?php esc_html_e( 'Features', 'aqualuxe' ); ?></label></th>
                <td><textarea id="service_features" name="service_features" rows="5" cols="50"><?php echo esc_textarea( $features ); ?></textarea></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Fish meta box callback.
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function fish_meta_box_callback( \WP_Post $post ): void {
        wp_nonce_field( 'fish_meta_box', 'fish_meta_box_nonce' );

        $scientific_name = get_post_meta( $post->ID, '_fish_scientific_name', true );
        $size = get_post_meta( $post->ID, '_fish_size', true );
        $lifespan = get_post_meta( $post->ID, '_fish_lifespan', true );
        $temperature = get_post_meta( $post->ID, '_fish_temperature', true );
        $ph_range = get_post_meta( $post->ID, '_fish_ph_range', true );

        ?>
        <table class="form-table">
            <tr>
                <th><label for="fish_scientific_name"><?php esc_html_e( 'Scientific Name', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="fish_scientific_name" name="fish_scientific_name" value="<?php echo esc_attr( $scientific_name ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="fish_size"><?php esc_html_e( 'Size', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="fish_size" name="fish_size" value="<?php echo esc_attr( $size ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="fish_lifespan"><?php esc_html_e( 'Lifespan', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="fish_lifespan" name="fish_lifespan" value="<?php echo esc_attr( $lifespan ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="fish_temperature"><?php esc_html_e( 'Temperature Range', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="fish_temperature" name="fish_temperature" value="<?php echo esc_attr( $temperature ); ?>" /></td>
            </tr>
            <tr>
                <th><label for="fish_ph_range"><?php esc_html_e( 'pH Range', 'aqualuxe' ); ?></label></th>
                <td><input type="text" id="fish_ph_range" name="fish_ph_range" value="<?php echo esc_attr( $ph_range ); ?>" /></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save meta box data.
     *
     * @param int $post_id Post ID.
     * @return void
     */
    public function save_meta_boxes( int $post_id ): void {
        // Check if user has permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Save service meta
        if ( isset( $_POST['service_meta_box_nonce'] ) && wp_verify_nonce( $_POST['service_meta_box_nonce'], 'service_meta_box' ) ) {
            $fields = array( 'service_price', 'service_duration', 'service_features' );
            foreach ( $fields as $field ) {
                if ( isset( $_POST[ $field ] ) ) {
                    update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
                }
            }
        }

        // Save fish meta
        if ( isset( $_POST['fish_meta_box_nonce'] ) && wp_verify_nonce( $_POST['fish_meta_box_nonce'], 'fish_meta_box' ) ) {
            $fields = array( 'fish_scientific_name', 'fish_size', 'fish_lifespan', 'fish_temperature', 'fish_ph_range' );
            foreach ( $fields as $field ) {
                if ( isset( $_POST[ $field ] ) ) {
                    update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
                }
            }
        }
    }

    /**
     * Services shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function services_shortcode( $atts ): string {
        $atts = shortcode_atts( array(
            'limit'    => 6,
            'category' => '',
            'columns'  => 3,
        ), $atts, 'aqualuxe_services' );

        $args = array(
            'post_type'      => 'aquarium_service',
            'posts_per_page' => intval( $atts['limit'] ),
            'post_status'    => 'publish',
        );

        if ( ! empty( $atts['category'] ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'service_category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field( $atts['category'] ),
                ),
            );
        }

        $services = new \WP_Query( $args );
        
        if ( ! $services->have_posts() ) {
            return '<p>' . esc_html__( 'No services found.', 'aqualuxe' ) . '</p>';
        }

        ob_start();
        ?>
        <div class="aqualuxe-services grid grid-cols-1 md:grid-cols-<?php echo esc_attr( $atts['columns'] ); ?> gap-6">
            <?php while ( $services->have_posts() ) : $services->the_post(); ?>
                <div class="service-item bg-white dark:bg-neutral-800 rounded-lg shadow-md overflow-hidden">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="service-thumbnail">
                            <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="service-content p-6">
                        <h3 class="service-title text-xl font-semibold mb-2">
                            <a href="<?php the_permalink(); ?>" class="text-neutral-900 dark:text-neutral-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="service-excerpt text-neutral-600 dark:text-neutral-300 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <?php
                        $price = get_post_meta( get_the_ID(), '_service_price', true );
                        if ( $price ) :
                        ?>
                            <div class="service-price text-lg font-semibold text-primary-600 dark:text-primary-400 mb-4">
                                <?php echo esc_html( $price ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                            <?php esc_html_e( 'Learn More', 'aqualuxe' ); ?>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();
        
        return ob_get_clean();
    }

    /**
     * Get service name for dependency injection.
     *
     * @return string Service name.
     */
    public function get_service_name(): string {
        return 'custom_post_types';
    }
}