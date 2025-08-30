<?php
/**
 * AquaLuxe Fish Custom Post Types and Taxonomies
 *
 * Handles all fish-specific custom post types and taxonomies
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Fish CPT Class
 */
class AquaLuxe_Fish_CPT {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Fish_CPT
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Fish_CPT
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Register custom post types and taxonomies
        add_action('init', array($this, 'register_fish_post_type'));
        add_action('init', array($this, 'register_fish_taxonomies'));
        
        // Add meta boxes for fish specifications
        add_action('add_meta_boxes', array($this, 'add_fish_meta_boxes'));
        
        // Save fish meta data
        add_action('save_post_fish_species', array($this, 'save_fish_meta'));
        
        // Add fish species columns to admin
        add_filter('manage_fish_species_posts_columns', array($this, 'fish_species_columns'));
        add_action('manage_fish_species_posts_custom_column', array($this, 'fish_species_custom_column'), 10, 2);
        
        // Add fish species filters to admin
        add_action('restrict_manage_posts', array($this, 'fish_species_filters'));
        
        // Add fish species to REST API
        add_action('rest_api_init', array($this, 'register_fish_rest_fields'));
        
        // Add shortcodes
        add_shortcode('fish_species', array($this, 'fish_species_shortcode'));
        add_shortcode('fish_compatibility', array($this, 'fish_compatibility_shortcode'));
        
        // Add fish species to search
        add_filter('pre_get_posts', array($this, 'include_fish_in_search'));
    }

    /**
     * Register Fish Species custom post type
     */
    public function register_fish_post_type() {
        $labels = array(
            'name'               => _x('Fish Species', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Fish Species', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Fish Species', 'admin menu', 'aqualuxe'),
            'name_admin_bar'     => _x('Fish Species', 'add new on admin bar', 'aqualuxe'),
            'add_new'            => _x('Add New', 'fish species', 'aqualuxe'),
            'add_new_item'       => __('Add New Fish Species', 'aqualuxe'),
            'new_item'           => __('New Fish Species', 'aqualuxe'),
            'edit_item'          => __('Edit Fish Species', 'aqualuxe'),
            'view_item'          => __('View Fish Species', 'aqualuxe'),
            'all_items'          => __('All Fish Species', 'aqualuxe'),
            'search_items'       => __('Search Fish Species', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Fish Species:', 'aqualuxe'),
            'not_found'          => __('No fish species found.', 'aqualuxe'),
            'not_found_in_trash' => __('No fish species found in Trash.', 'aqualuxe')
        );

        $args = array(
            'labels'             => $labels,
            'description'        => __('Fish species for the aquarium store.', 'aqualuxe'),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'fish-species'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-fish',
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
            'show_in_rest'       => true,
        );

        register_post_type('fish_species', $args);
    }

    /**
     * Register Fish taxonomies
     */
    public function register_fish_taxonomies() {
        // Fish Category Taxonomy (Freshwater, Saltwater, Brackish)
        $category_labels = array(
            'name'              => _x('Fish Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Fish Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Fish Categories', 'aqualuxe'),
            'all_items'         => __('All Fish Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Fish Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Fish Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Fish Category', 'aqualuxe'),
            'update_item'       => __('Update Fish Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Fish Category', 'aqualuxe'),
            'new_item_name'     => __('New Fish Category Name', 'aqualuxe'),
            'menu_name'         => __('Fish Categories', 'aqualuxe'),
        );

        $category_args = array(
            'hierarchical'      => true,
            'labels'            => $category_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'fish-category'),
            'show_in_rest'      => true,
        );

        register_taxonomy('fish_category', array('fish_species', 'product'), $category_args);

        // Fish Family Taxonomy
        $family_labels = array(
            'name'              => _x('Fish Families', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Fish Family', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Fish Families', 'aqualuxe'),
            'all_items'         => __('All Fish Families', 'aqualuxe'),
            'parent_item'       => __('Parent Fish Family', 'aqualuxe'),
            'parent_item_colon' => __('Parent Fish Family:', 'aqualuxe'),
            'edit_item'         => __('Edit Fish Family', 'aqualuxe'),
            'update_item'       => __('Update Fish Family', 'aqualuxe'),
            'add_new_item'      => __('Add New Fish Family', 'aqualuxe'),
            'new_item_name'     => __('New Fish Family Name', 'aqualuxe'),
            'menu_name'         => __('Fish Families', 'aqualuxe'),
        );

        $family_args = array(
            'hierarchical'      => true,
            'labels'            => $family_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'fish-family'),
            'show_in_rest'      => true,
        );

        register_taxonomy('fish_family', array('fish_species', 'product'), $family_args);

        // Fish Origin Taxonomy
        $origin_labels = array(
            'name'              => _x('Fish Origins', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Fish Origin', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Fish Origins', 'aqualuxe'),
            'all_items'         => __('All Fish Origins', 'aqualuxe'),
            'parent_item'       => __('Parent Fish Origin', 'aqualuxe'),
            'parent_item_colon' => __('Parent Fish Origin:', 'aqualuxe'),
            'edit_item'         => __('Edit Fish Origin', 'aqualuxe'),
            'update_item'       => __('Update Fish Origin', 'aqualuxe'),
            'add_new_item'      => __('Add New Fish Origin', 'aqualuxe'),
            'new_item_name'     => __('New Fish Origin Name', 'aqualuxe'),
            'menu_name'         => __('Fish Origins', 'aqualuxe'),
        );

        $origin_args = array(
            'hierarchical'      => false,
            'labels'            => $origin_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'fish-origin'),
            'show_in_rest'      => true,
        );

        register_taxonomy('fish_origin', array('fish_species', 'product'), $origin_args);

        // Fish Care Level Taxonomy
        $care_labels = array(
            'name'              => _x('Care Levels', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Care Level', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Care Levels', 'aqualuxe'),
            'all_items'         => __('All Care Levels', 'aqualuxe'),
            'parent_item'       => __('Parent Care Level', 'aqualuxe'),
            'parent_item_colon' => __('Parent Care Level:', 'aqualuxe'),
            'edit_item'         => __('Edit Care Level', 'aqualuxe'),
            'update_item'       => __('Update Care Level', 'aqualuxe'),
            'add_new_item'      => __('Add New Care Level', 'aqualuxe'),
            'new_item_name'     => __('New Care Level Name', 'aqualuxe'),
            'menu_name'         => __('Care Levels', 'aqualuxe'),
        );

        $care_args = array(
            'hierarchical'      => true,
            'labels'            => $care_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'care-level'),
            'show_in_rest'      => true,
        );

        register_taxonomy('care_level', array('fish_species', 'product'), $care_args);
    }

    /**
     * Add meta boxes for fish specifications
     */
    public function add_fish_meta_boxes() {
        add_meta_box(
            'fish_specs',
            __('Fish Specifications', 'aqualuxe'),
            array($this, 'fish_specs_meta_box_callback'),
            'fish_species',
            'normal',
            'high'
        );

        add_meta_box(
            'fish_compatibility',
            __('Fish Compatibility', 'aqualuxe'),
            array($this, 'fish_compatibility_meta_box_callback'),
            'fish_species',
            'normal',
            'high'
        );

        add_meta_box(
            'fish_care',
            __('Fish Care Guide', 'aqualuxe'),
            array($this, 'fish_care_meta_box_callback'),
            'fish_species',
            'normal',
            'high'
        );
    }

    /**
     * Fish specifications meta box callback
     * 
     * @param WP_Post $post The post object.
     */
    public function fish_specs_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_fish_specs_nonce', 'fish_specs_nonce');

        // Get saved values
        $scientific_name = get_post_meta($post->ID, '_scientific_name', true);
        $adult_size = get_post_meta($post->ID, '_adult_size', true);
        $lifespan = get_post_meta($post->ID, '_lifespan', true);
        $min_tank_size = get_post_meta($post->ID, '_min_tank_size', true);
        $temperature_min = get_post_meta($post->ID, '_temperature_min', true);
        $temperature_max = get_post_meta($post->ID, '_temperature_max', true);
        $ph_min = get_post_meta($post->ID, '_ph_min', true);
        $ph_max = get_post_meta($post->ID, '_ph_max', true);
        $hardness_min = get_post_meta($post->ID, '_hardness_min', true);
        $hardness_max = get_post_meta($post->ID, '_hardness_max', true);
        $diet = get_post_meta($post->ID, '_diet', true);
        $temperament = get_post_meta($post->ID, '_temperament', true);
        $swimming_level = get_post_meta($post->ID, '_swimming_level', true);
        $breeding_difficulty = get_post_meta($post->ID, '_breeding_difficulty', true);
        ?>
        <div class="fish-specs-container">
            <p>
                <label for="scientific_name"><?php esc_html_e('Scientific Name:', 'aqualuxe'); ?></label>
                <input type="text" id="scientific_name" name="scientific_name" value="<?php echo esc_attr($scientific_name); ?>" class="widefat" />
            </p>
            
            <p>
                <label for="adult_size"><?php esc_html_e('Adult Size (inches):', 'aqualuxe'); ?></label>
                <input type="text" id="adult_size" name="adult_size" value="<?php echo esc_attr($adult_size); ?>" class="widefat" />
            </p>
            
            <p>
                <label for="lifespan"><?php esc_html_e('Lifespan (years):', 'aqualuxe'); ?></label>
                <input type="text" id="lifespan" name="lifespan" value="<?php echo esc_attr($lifespan); ?>" class="widefat" />
            </p>
            
            <p>
                <label for="min_tank_size"><?php esc_html_e('Minimum Tank Size (gallons):', 'aqualuxe'); ?></label>
                <input type="number" id="min_tank_size" name="min_tank_size" value="<?php echo esc_attr($min_tank_size); ?>" class="widefat" />
            </p>
            
            <div class="fish-specs-row">
                <p class="fish-specs-col">
                    <label for="temperature_min"><?php esc_html_e('Temperature Range (°F):', 'aqualuxe'); ?></label>
                    <input type="number" id="temperature_min" name="temperature_min" value="<?php echo esc_attr($temperature_min); ?>" class="small-text" step="0.1" /> - 
                    <input type="number" id="temperature_max" name="temperature_max" value="<?php echo esc_attr($temperature_max); ?>" class="small-text" step="0.1" />
                </p>
            </div>
            
            <div class="fish-specs-row">
                <p class="fish-specs-col">
                    <label for="ph_min"><?php esc_html_e('pH Range:', 'aqualuxe'); ?></label>
                    <input type="number" id="ph_min" name="ph_min" value="<?php echo esc_attr($ph_min); ?>" class="small-text" step="0.1" /> - 
                    <input type="number" id="ph_max" name="ph_max" value="<?php echo esc_attr($ph_max); ?>" class="small-text" step="0.1" />
                </p>
            </div>
            
            <div class="fish-specs-row">
                <p class="fish-specs-col">
                    <label for="hardness_min"><?php esc_html_e('Water Hardness (dGH):', 'aqualuxe'); ?></label>
                    <input type="number" id="hardness_min" name="hardness_min" value="<?php echo esc_attr($hardness_min); ?>" class="small-text" step="0.1" /> - 
                    <input type="number" id="hardness_max" name="hardness_max" value="<?php echo esc_attr($hardness_max); ?>" class="small-text" step="0.1" />
                </p>
            </div>
            
            <p>
                <label for="diet"><?php esc_html_e('Diet:', 'aqualuxe'); ?></label>
                <input type="text" id="diet" name="diet" value="<?php echo esc_attr($diet); ?>" class="widefat" />
                <span class="description"><?php esc_html_e('E.g., Omnivore, Carnivore, Herbivore, etc.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="temperament"><?php esc_html_e('Temperament:', 'aqualuxe'); ?></label>
                <select id="temperament" name="temperament" class="widefat">
                    <option value=""><?php esc_html_e('Select Temperament', 'aqualuxe'); ?></option>
                    <option value="peaceful" <?php selected($temperament, 'peaceful'); ?>><?php esc_html_e('Peaceful', 'aqualuxe'); ?></option>
                    <option value="semi-aggressive" <?php selected($temperament, 'semi-aggressive'); ?>><?php esc_html_e('Semi-Aggressive', 'aqualuxe'); ?></option>
                    <option value="aggressive" <?php selected($temperament, 'aggressive'); ?>><?php esc_html_e('Aggressive', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <label for="swimming_level"><?php esc_html_e('Swimming Level:', 'aqualuxe'); ?></label>
                <select id="swimming_level" name="swimming_level" class="widefat">
                    <option value=""><?php esc_html_e('Select Swimming Level', 'aqualuxe'); ?></option>
                    <option value="top" <?php selected($swimming_level, 'top'); ?>><?php esc_html_e('Top', 'aqualuxe'); ?></option>
                    <option value="middle" <?php selected($swimming_level, 'middle'); ?>><?php esc_html_e('Middle', 'aqualuxe'); ?></option>
                    <option value="bottom" <?php selected($swimming_level, 'bottom'); ?>><?php esc_html_e('Bottom', 'aqualuxe'); ?></option>
                    <option value="all" <?php selected($swimming_level, 'all'); ?>><?php esc_html_e('All Levels', 'aqualuxe'); ?></option>
                </select>
            </p>
            
            <p>
                <label for="breeding_difficulty"><?php esc_html_e('Breeding Difficulty:', 'aqualuxe'); ?></label>
                <select id="breeding_difficulty" name="breeding_difficulty" class="widefat">
                    <option value=""><?php esc_html_e('Select Difficulty', 'aqualuxe'); ?></option>
                    <option value="easy" <?php selected($breeding_difficulty, 'easy'); ?>><?php esc_html_e('Easy', 'aqualuxe'); ?></option>
                    <option value="moderate" <?php selected($breeding_difficulty, 'moderate'); ?>><?php esc_html_e('Moderate', 'aqualuxe'); ?></option>
                    <option value="difficult" <?php selected($breeding_difficulty, 'difficult'); ?>><?php esc_html_e('Difficult', 'aqualuxe'); ?></option>
                    <option value="very-difficult" <?php selected($breeding_difficulty, 'very-difficult'); ?>><?php esc_html_e('Very Difficult', 'aqualuxe'); ?></option>
                </select>
            </p>
        </div>
        <style>
            .fish-specs-row {
                display: flex;
                flex-wrap: wrap;
                margin: 0 -10px;
            }
            .fish-specs-col {
                flex: 1;
                padding: 0 10px;
                min-width: 200px;
            }
        </style>
        <?php
    }

    /**
     * Fish compatibility meta box callback
     * 
     * @param WP_Post $post The post object.
     */
    public function fish_compatibility_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_fish_compatibility_nonce', 'fish_compatibility_nonce');

        // Get saved values
        $compatible_with = get_post_meta($post->ID, '_compatible_with', true);
        $incompatible_with = get_post_meta($post->ID, '_incompatible_with', true);
        $compatibility_notes = get_post_meta($post->ID, '_compatibility_notes', true);
        ?>
        <div class="fish-compatibility-container">
            <p>
                <label for="compatible_with"><?php esc_html_e('Compatible With:', 'aqualuxe'); ?></label>
                <textarea id="compatible_with" name="compatible_with" class="widefat" rows="3"><?php echo esc_textarea($compatible_with); ?></textarea>
                <span class="description"><?php esc_html_e('Enter fish species that are compatible, separated by commas.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="incompatible_with"><?php esc_html_e('Incompatible With:', 'aqualuxe'); ?></label>
                <textarea id="incompatible_with" name="incompatible_with" class="widefat" rows="3"><?php echo esc_textarea($incompatible_with); ?></textarea>
                <span class="description"><?php esc_html_e('Enter fish species that are incompatible, separated by commas.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="compatibility_notes"><?php esc_html_e('Compatibility Notes:', 'aqualuxe'); ?></label>
                <textarea id="compatibility_notes" name="compatibility_notes" class="widefat" rows="5"><?php echo esc_textarea($compatibility_notes); ?></textarea>
                <span class="description"><?php esc_html_e('Additional notes about compatibility with other fish or invertebrates.', 'aqualuxe'); ?></span>
            </p>
        </div>
        <?php
    }

    /**
     * Fish care meta box callback
     * 
     * @param WP_Post $post The post object.
     */
    public function fish_care_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_fish_care_nonce', 'fish_care_nonce');

        // Get saved values
        $care_instructions = get_post_meta($post->ID, '_care_instructions', true);
        $feeding_guide = get_post_meta($post->ID, '_feeding_guide', true);
        $tank_setup = get_post_meta($post->ID, '_tank_setup', true);
        $common_diseases = get_post_meta($post->ID, '_common_diseases', true);
        $breeding_tips = get_post_meta($post->ID, '_breeding_tips', true);
        ?>
        <div class="fish-care-container">
            <p>
                <label for="care_instructions"><?php esc_html_e('Care Instructions:', 'aqualuxe'); ?></label>
                <?php
                $settings = array(
                    'textarea_name' => 'care_instructions',
                    'textarea_rows' => 5,
                    'media_buttons' => true,
                );
                wp_editor($care_instructions, 'care_instructions', $settings);
                ?>
                <span class="description"><?php esc_html_e('General care instructions for this fish species.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="feeding_guide"><?php esc_html_e('Feeding Guide:', 'aqualuxe'); ?></label>
                <textarea id="feeding_guide" name="feeding_guide" class="widefat" rows="4"><?php echo esc_textarea($feeding_guide); ?></textarea>
                <span class="description"><?php esc_html_e('Recommended feeding schedule and food types.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="tank_setup"><?php esc_html_e('Tank Setup:', 'aqualuxe'); ?></label>
                <textarea id="tank_setup" name="tank_setup" class="widefat" rows="4"><?php echo esc_textarea($tank_setup); ?></textarea>
                <span class="description"><?php esc_html_e('Recommended tank setup including substrate, plants, decorations, etc.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="common_diseases"><?php esc_html_e('Common Diseases & Prevention:', 'aqualuxe'); ?></label>
                <textarea id="common_diseases" name="common_diseases" class="widefat" rows="4"><?php echo esc_textarea($common_diseases); ?></textarea>
                <span class="description"><?php esc_html_e('Common diseases and prevention tips.', 'aqualuxe'); ?></span>
            </p>
            
            <p>
                <label for="breeding_tips"><?php esc_html_e('Breeding Tips:', 'aqualuxe'); ?></label>
                <textarea id="breeding_tips" name="breeding_tips" class="widefat" rows="4"><?php echo esc_textarea($breeding_tips); ?></textarea>
                <span class="description"><?php esc_html_e('Tips for breeding this fish species.', 'aqualuxe'); ?></span>
            </p>
        </div>
        <?php
    }

    /**
     * Save fish meta data
     * 
     * @param int $post_id The post ID.
     */
    public function save_fish_meta($post_id) {
        // Check if nonce is set
        if (!isset($_POST['fish_specs_nonce']) || !isset($_POST['fish_compatibility_nonce']) || !isset($_POST['fish_care_nonce'])) {
            return;
        }

        // Verify nonces
        if (!wp_verify_nonce($_POST['fish_specs_nonce'], 'aqualuxe_fish_specs_nonce') ||
            !wp_verify_nonce($_POST['fish_compatibility_nonce'], 'aqualuxe_fish_compatibility_nonce') ||
            !wp_verify_nonce($_POST['fish_care_nonce'], 'aqualuxe_fish_care_nonce')) {
            return;
        }

        // Check if autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save fish specifications
        if (isset($_POST['scientific_name'])) {
            update_post_meta($post_id, '_scientific_name', sanitize_text_field($_POST['scientific_name']));
        }
        
        if (isset($_POST['adult_size'])) {
            update_post_meta($post_id, '_adult_size', sanitize_text_field($_POST['adult_size']));
        }
        
        if (isset($_POST['lifespan'])) {
            update_post_meta($post_id, '_lifespan', sanitize_text_field($_POST['lifespan']));
        }
        
        if (isset($_POST['min_tank_size'])) {
            update_post_meta($post_id, '_min_tank_size', absint($_POST['min_tank_size']));
        }
        
        if (isset($_POST['temperature_min'])) {
            update_post_meta($post_id, '_temperature_min', floatval($_POST['temperature_min']));
        }
        
        if (isset($_POST['temperature_max'])) {
            update_post_meta($post_id, '_temperature_max', floatval($_POST['temperature_max']));
        }
        
        if (isset($_POST['ph_min'])) {
            update_post_meta($post_id, '_ph_min', floatval($_POST['ph_min']));
        }
        
        if (isset($_POST['ph_max'])) {
            update_post_meta($post_id, '_ph_max', floatval($_POST['ph_max']));
        }
        
        if (isset($_POST['hardness_min'])) {
            update_post_meta($post_id, '_hardness_min', floatval($_POST['hardness_min']));
        }
        
        if (isset($_POST['hardness_max'])) {
            update_post_meta($post_id, '_hardness_max', floatval($_POST['hardness_max']));
        }
        
        if (isset($_POST['diet'])) {
            update_post_meta($post_id, '_diet', sanitize_text_field($_POST['diet']));
        }
        
        if (isset($_POST['temperament'])) {
            update_post_meta($post_id, '_temperament', sanitize_text_field($_POST['temperament']));
        }
        
        if (isset($_POST['swimming_level'])) {
            update_post_meta($post_id, '_swimming_level', sanitize_text_field($_POST['swimming_level']));
        }
        
        if (isset($_POST['breeding_difficulty'])) {
            update_post_meta($post_id, '_breeding_difficulty', sanitize_text_field($_POST['breeding_difficulty']));
        }

        // Save fish compatibility
        if (isset($_POST['compatible_with'])) {
            update_post_meta($post_id, '_compatible_with', sanitize_textarea_field($_POST['compatible_with']));
        }
        
        if (isset($_POST['incompatible_with'])) {
            update_post_meta($post_id, '_incompatible_with', sanitize_textarea_field($_POST['incompatible_with']));
        }
        
        if (isset($_POST['compatibility_notes'])) {
            update_post_meta($post_id, '_compatibility_notes', sanitize_textarea_field($_POST['compatibility_notes']));
        }

        // Save fish care
        if (isset($_POST['care_instructions'])) {
            update_post_meta($post_id, '_care_instructions', wp_kses_post($_POST['care_instructions']));
        }
        
        if (isset($_POST['feeding_guide'])) {
            update_post_meta($post_id, '_feeding_guide', sanitize_textarea_field($_POST['feeding_guide']));
        }
        
        if (isset($_POST['tank_setup'])) {
            update_post_meta($post_id, '_tank_setup', sanitize_textarea_field($_POST['tank_setup']));
        }
        
        if (isset($_POST['common_diseases'])) {
            update_post_meta($post_id, '_common_diseases', sanitize_textarea_field($_POST['common_diseases']));
        }
        
        if (isset($_POST['breeding_tips'])) {
            update_post_meta($post_id, '_breeding_tips', sanitize_textarea_field($_POST['breeding_tips']));
        }
    }

    /**
     * Add custom columns to fish species admin
     * 
     * @param array $columns Admin columns.
     * @return array Modified columns.
     */
    public function fish_species_columns($columns) {
        $new_columns = array();
        
        // Insert thumbnail after checkbox
        $new_columns['cb'] = $columns['cb'];
        $new_columns['thumbnail'] = __('Image', 'aqualuxe');
        
        // Add remaining columns
        $new_columns['title'] = $columns['title'];
        $new_columns['scientific_name'] = __('Scientific Name', 'aqualuxe');
        $new_columns['category'] = __('Category', 'aqualuxe');
        $new_columns['care_level'] = __('Care Level', 'aqualuxe');
        $new_columns['temperament'] = __('Temperament', 'aqualuxe');
        $new_columns['tank_size'] = __('Min. Tank Size', 'aqualuxe');
        $new_columns['date'] = $columns['date'];
        
        return $new_columns;
    }

    /**
     * Display custom column content
     * 
     * @param string $column Column name.
     * @param int $post_id Post ID.
     */
    public function fish_species_custom_column($column, $post_id) {
        switch ($column) {
            case 'thumbnail':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, array(50, 50));
                } else {
                    echo '<img src="' . esc_url(AQUALUXE_ASSETS_URI . '/images/placeholder-fish.png') . '" width="50" height="50" alt="' . esc_attr__('No Image', 'aqualuxe') . '" />';
                }
                break;
                
            case 'scientific_name':
                echo esc_html(get_post_meta($post_id, '_scientific_name', true));
                break;
                
            case 'category':
                $terms = get_the_terms($post_id, 'fish_category');
                if (!empty($terms) && !is_wp_error($terms)) {
                    $category_links = array();
                    foreach ($terms as $term) {
                        $category_links[] = '<a href="' . esc_url(get_edit_term_link($term->term_id, 'fish_category')) . '">' . esc_html($term->name) . '</a>';
                    }
                    echo implode(', ', $category_links);
                } else {
                    echo '—';
                }
                break;
                
            case 'care_level':
                $terms = get_the_terms($post_id, 'care_level');
                if (!empty($terms) && !is_wp_error($terms)) {
                    $care_links = array();
                    foreach ($terms as $term) {
                        $care_links[] = '<a href="' . esc_url(get_edit_term_link($term->term_id, 'care_level')) . '">' . esc_html($term->name) . '</a>';
                    }
                    echo implode(', ', $care_links);
                } else {
                    echo '—';
                }
                break;
                
            case 'temperament':
                $temperament = get_post_meta($post_id, '_temperament', true);
                if (!empty($temperament)) {
                    $temperament_labels = array(
                        'peaceful' => __('Peaceful', 'aqualuxe'),
                        'semi-aggressive' => __('Semi-Aggressive', 'aqualuxe'),
                        'aggressive' => __('Aggressive', 'aqualuxe'),
                    );
                    echo isset($temperament_labels[$temperament]) ? esc_html($temperament_labels[$temperament]) : esc_html($temperament);
                } else {
                    echo '—';
                }
                break;
                
            case 'tank_size':
                $tank_size = get_post_meta($post_id, '_min_tank_size', true);
                if (!empty($tank_size)) {
                    echo esc_html($tank_size) . ' ' . esc_html__('gallons', 'aqualuxe');
                } else {
                    echo '—';
                }
                break;
        }
    }

    /**
     * Add fish species filters to admin
     * 
     * @param string $post_type Current post type.
     */
    public function fish_species_filters($post_type) {
        if ('fish_species' !== $post_type) {
            return;
        }
        
        // Add category filter
        $taxonomy_slug = 'fish_category';
        $taxonomy_obj = get_taxonomy($taxonomy_slug);
        $taxonomy_name = $taxonomy_obj->labels->name;
        $terms = get_terms($taxonomy_slug);
        
        if (count($terms) > 0) {
            echo '<select name="' . esc_attr($taxonomy_slug) . '" id="' . esc_attr($taxonomy_slug) . '" class="postform">';
            echo '<option value="">' . esc_html(sprintf(__('All %s', 'aqualuxe'), $taxonomy_name)) . '</option>';
            foreach ($terms as $term) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr($term->slug),
                    ((isset($_GET[$taxonomy_slug]) && ($_GET[$taxonomy_slug] === $term->slug)) ? ' selected="selected"' : ''),
                    esc_html($term->name),
                    esc_html($term->count)
                );
            }
            echo '</select>';
        }
        
        // Add care level filter
        $taxonomy_slug = 'care_level';
        $taxonomy_obj = get_taxonomy($taxonomy_slug);
        $taxonomy_name = $taxonomy_obj->labels->name;
        $terms = get_terms($taxonomy_slug);
        
        if (count($terms) > 0) {
            echo '<select name="' . esc_attr($taxonomy_slug) . '" id="' . esc_attr($taxonomy_slug) . '" class="postform">';
            echo '<option value="">' . esc_html(sprintf(__('All %s', 'aqualuxe'), $taxonomy_name)) . '</option>';
            foreach ($terms as $term) {
                printf(
                    '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                    esc_attr($term->slug),
                    ((isset($_GET[$taxonomy_slug]) && ($_GET[$taxonomy_slug] === $term->slug)) ? ' selected="selected"' : ''),
                    esc_html($term->name),
                    esc_html($term->count)
                );
            }
            echo '</select>';
        }
        
        // Add temperament filter
        $temperament_options = array(
            'peaceful' => __('Peaceful', 'aqualuxe'),
            'semi-aggressive' => __('Semi-Aggressive', 'aqualuxe'),
            'aggressive' => __('Aggressive', 'aqualuxe'),
        );
        
        echo '<select name="temperament" id="temperament" class="postform">';
        echo '<option value="">' . esc_html__('All Temperaments', 'aqualuxe') . '</option>';
        foreach ($temperament_options as $value => $label) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                esc_attr($value),
                ((isset($_GET['temperament']) && ($_GET['temperament'] === $value)) ? ' selected="selected"' : ''),
                esc_html($label)
            );
        }
        echo '</select>';
    }

    /**
     * Register fish species REST API fields
     */
    public function register_fish_rest_fields() {
        register_rest_field('fish_species', 'fish_specs', array(
            'get_callback' => array($this, 'get_fish_specs_rest'),
            'schema' => array(
                'description' => __('Fish specifications', 'aqualuxe'),
                'type' => 'object',
            ),
        ));
        
        register_rest_field('fish_species', 'fish_compatibility', array(
            'get_callback' => array($this, 'get_fish_compatibility_rest'),
            'schema' => array(
                'description' => __('Fish compatibility', 'aqualuxe'),
                'type' => 'object',
            ),
        ));
        
        register_rest_field('fish_species', 'fish_care', array(
            'get_callback' => array($this, 'get_fish_care_rest'),
            'schema' => array(
                'description' => __('Fish care', 'aqualuxe'),
                'type' => 'object',
            ),
        ));
    }

    /**
     * Get fish specifications for REST API
     * 
     * @param array $object Post object.
     * @return array Fish specifications.
     */
    public function get_fish_specs_rest($object) {
        $post_id = $object['id'];
        
        return array(
            'scientific_name' => get_post_meta($post_id, '_scientific_name', true),
            'adult_size' => get_post_meta($post_id, '_adult_size', true),
            'lifespan' => get_post_meta($post_id, '_lifespan', true),
            'min_tank_size' => get_post_meta($post_id, '_min_tank_size', true),
            'temperature_min' => get_post_meta($post_id, '_temperature_min', true),
            'temperature_max' => get_post_meta($post_id, '_temperature_max', true),
            'ph_min' => get_post_meta($post_id, '_ph_min', true),
            'ph_max' => get_post_meta($post_id, '_ph_max', true),
            'hardness_min' => get_post_meta($post_id, '_hardness_min', true),
            'hardness_max' => get_post_meta($post_id, '_hardness_max', true),
            'diet' => get_post_meta($post_id, '_diet', true),
            'temperament' => get_post_meta($post_id, '_temperament', true),
            'swimming_level' => get_post_meta($post_id, '_swimming_level', true),
            'breeding_difficulty' => get_post_meta($post_id, '_breeding_difficulty', true),
        );
    }

    /**
     * Get fish compatibility for REST API
     * 
     * @param array $object Post object.
     * @return array Fish compatibility.
     */
    public function get_fish_compatibility_rest($object) {
        $post_id = $object['id'];
        
        return array(
            'compatible_with' => get_post_meta($post_id, '_compatible_with', true),
            'incompatible_with' => get_post_meta($post_id, '_incompatible_with', true),
            'compatibility_notes' => get_post_meta($post_id, '_compatibility_notes', true),
        );
    }

    /**
     * Get fish care for REST API
     * 
     * @param array $object Post object.
     * @return array Fish care.
     */
    public function get_fish_care_rest($object) {
        $post_id = $object['id'];
        
        return array(
            'care_instructions' => get_post_meta($post_id, '_care_instructions', true),
            'feeding_guide' => get_post_meta($post_id, '_feeding_guide', true),
            'tank_setup' => get_post_meta($post_id, '_tank_setup', true),
            'common_diseases' => get_post_meta($post_id, '_common_diseases', true),
            'breeding_tips' => get_post_meta($post_id, '_breeding_tips', true),
        );
    }

    /**
     * Fish species shortcode
     * 
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function fish_species_shortcode($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'care_level' => '',
            'temperament' => '',
            'limit' => 10,
            'orderby' => 'title',
            'order' => 'ASC',
        ), $atts);
        
        $args = array(
            'post_type' => 'fish_species',
            'posts_per_page' => intval($atts['limit']),
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );
        
        // Add tax query
        $tax_query = array();
        
        if (!empty($atts['category'])) {
            $tax_query[] = array(
                'taxonomy' => 'fish_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            );
        }
        
        if (!empty($atts['care_level'])) {
            $tax_query[] = array(
                'taxonomy' => 'care_level',
                'field' => 'slug',
                'terms' => explode(',', $atts['care_level']),
            );
        }
        
        if (!empty($tax_query)) {
            $args['tax_query'] = array_merge(array('relation' => 'AND'), $tax_query);
        }
        
        // Add meta query for temperament
        if (!empty($atts['temperament'])) {
            $args['meta_query'] = array(
                array(
                    'key' => '_temperament',
                    'value' => explode(',', $atts['temperament']),
                    'compare' => 'IN',
                ),
            );
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        
        if ($query->have_posts()) {
            echo '<div class="aqualuxe-fish-species-grid">';
            
            while ($query->have_posts()) {
                $query->the_post();
                
                $scientific_name = get_post_meta(get_the_ID(), '_scientific_name', true);
                $temperament = get_post_meta(get_the_ID(), '_temperament', true);
                $care_level_terms = get_the_terms(get_the_ID(), 'care_level');
                $care_level = !empty($care_level_terms) ? $care_level_terms[0]->name : '';
                
                echo '<div class="fish-species-item">';
                
                echo '<div class="fish-species-image">';
                if (has_post_thumbnail()) {
                    the_post_thumbnail('medium');
                } else {
                    echo '<img src="' . esc_url(AQUALUXE_ASSETS_URI . '/images/placeholder-fish.png') . '" alt="' . esc_attr(get_the_title()) . '" />';
                }
                echo '</div>';
                
                echo '<div class="fish-species-content">';
                echo '<h3 class="fish-species-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
                
                if (!empty($scientific_name)) {
                    echo '<p class="fish-scientific-name"><em>' . esc_html($scientific_name) . '</em></p>';
                }
                
                echo '<div class="fish-species-meta">';
                
                if (!empty($care_level)) {
                    echo '<span class="fish-care-level">' . esc_html__('Care Level:', 'aqualuxe') . ' ' . esc_html($care_level) . '</span>';
                }
                
                if (!empty($temperament)) {
                    $temperament_labels = array(
                        'peaceful' => __('Peaceful', 'aqualuxe'),
                        'semi-aggressive' => __('Semi-Aggressive', 'aqualuxe'),
                        'aggressive' => __('Aggressive', 'aqualuxe'),
                    );
                    $temperament_label = isset($temperament_labels[$temperament]) ? $temperament_labels[$temperament] : $temperament;
                    echo '<span class="fish-temperament">' . esc_html__('Temperament:', 'aqualuxe') . ' ' . esc_html($temperament_label) . '</span>';
                }
                
                echo '</div>';
                
                echo '<div class="fish-species-excerpt">';
                the_excerpt();
                echo '</div>';
                
                echo '<a href="' . esc_url(get_permalink()) . '" class="button fish-species-more">' . esc_html__('Learn More', 'aqualuxe') . '</a>';
                
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>';
        } else {
            echo '<p class="no-fish-found">' . esc_html__('No fish species found.', 'aqualuxe') . '</p>';
        }
        
        wp_reset_postdata();
        
        return ob_get_clean();
    }

    /**
     * Fish compatibility shortcode
     * 
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function fish_compatibility_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
        ), $atts);
        
        $post_id = intval($atts['id']);
        
        if (empty($post_id)) {
            return '<p class="error">' . esc_html__('Please specify a fish species ID.', 'aqualuxe') . '</p>';
        }
        
        $post = get_post($post_id);
        
        if (empty($post) || 'fish_species' !== $post->post_type) {
            return '<p class="error">' . esc_html__('Invalid fish species ID.', 'aqualuxe') . '</p>';
        }
        
        $compatible_with = get_post_meta($post_id, '_compatible_with', true);
        $incompatible_with = get_post_meta($post_id, '_incompatible_with', true);
        $compatibility_notes = get_post_meta($post_id, '_compatibility_notes', true);
        
        ob_start();
        
        echo '<div class="aqualuxe-fish-compatibility">';
        
        echo '<h3>' . esc_html(sprintf(__('Compatibility Guide for %s', 'aqualuxe'), get_the_title($post_id))) . '</h3>';
        
        echo '<div class="compatibility-section compatible">';
        echo '<h4>' . esc_html__('Compatible With', 'aqualuxe') . '</h4>';
        if (!empty($compatible_with)) {
            echo '<p>' . esc_html($compatible_with) . '</p>';
        } else {
            echo '<p>' . esc_html__('No compatibility information available.', 'aqualuxe') . '</p>';
        }
        echo '</div>';
        
        echo '<div class="compatibility-section incompatible">';
        echo '<h4>' . esc_html__('Incompatible With', 'aqualuxe') . '</h4>';
        if (!empty($incompatible_with)) {
            echo '<p>' . esc_html($incompatible_with) . '</p>';
        } else {
            echo '<p>' . esc_html__('No incompatibility information available.', 'aqualuxe') . '</p>';
        }
        echo '</div>';
        
        if (!empty($compatibility_notes)) {
            echo '<div class="compatibility-section notes">';
            echo '<h4>' . esc_html__('Compatibility Notes', 'aqualuxe') . '</h4>';
            echo '<p>' . esc_html($compatibility_notes) . '</p>';
            echo '</div>';
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Include fish species in search
     * 
     * @param WP_Query $query The query.
     */
    public function include_fish_in_search($query) {
        if ($query->is_search() && $query->is_main_query()) {
            $post_types = $query->get('post_type');
            
            if (empty($post_types)) {
                $post_types = array('post', 'page', 'fish_species');
            } elseif (is_array($post_types)) {
                $post_types[] = 'fish_species';
            } else {
                $post_types = array($post_types, 'fish_species');
            }
            
            $query->set('post_type', $post_types);
        }
    }
}