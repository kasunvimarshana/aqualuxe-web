<?php
/**
 * AquaLuxe Shortcodes Class
 *
 * Handles shortcodes for the theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Shortcodes Class
 */
class AquaLuxe_Shortcodes {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Shortcodes
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Shortcodes
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
        // Register shortcodes
        add_shortcode('water_parameter_calculator', array($this, 'water_parameter_calculator_shortcode'));
        add_shortcode('tank_volume_calculator', array($this, 'tank_volume_calculator_shortcode'));
        add_shortcode('stocking_calculator', array($this, 'stocking_calculator_shortcode'));
        add_shortcode('fish_compatibility_checker', array($this, 'fish_compatibility_checker_shortcode'));
        add_shortcode('fish_care_guide', array($this, 'fish_care_guide_shortcode'));
        add_shortcode('fish_species_grid', array($this, 'fish_species_grid_shortcode'));
    }

    /**
     * Water Parameter Calculator shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function water_parameter_calculator_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'tab' => 'ph-adjustment', // Default tab: ph-adjustment, hardness-adjustment, medication-dosage
            'title' => __('Water Parameter Calculator', 'aqualuxe'),
            'class' => '',
        ), $atts);
        
        // Sanitize attributes
        $tab = sanitize_key($atts['tab']);
        $title = sanitize_text_field($atts['title']);
        $class = sanitize_html_class($atts['class']);
        
        // Enqueue required assets
        wp_enqueue_style('aqualuxe-calculator');
        wp_enqueue_script('aqualuxe-calculator');
        
        // Start output buffering
        ob_start();
        
        // Include the calculator template
        include_once AQUALUXE_DIR . '/template-parts/calculators/water-parameter-calculator.php';
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Tank Volume Calculator shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function tank_volume_calculator_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'tab' => 'rectangular', // Default tab: rectangular, cylindrical, bowfront
            'title' => __('Tank Volume Calculator', 'aqualuxe'),
            'class' => '',
        ), $atts);
        
        // Sanitize attributes
        $tab = sanitize_key($atts['tab']);
        $title = sanitize_text_field($atts['title']);
        $class = sanitize_html_class($atts['class']);
        
        // Enqueue required assets
        wp_enqueue_style('aqualuxe-calculator');
        wp_enqueue_script('aqualuxe-calculator');
        
        // Start output buffering
        ob_start();
        
        // Include the calculator template
        include_once AQUALUXE_DIR . '/template-parts/calculators/tank-volume-calculator.php';
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Stocking Calculator shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function stocking_calculator_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'title' => __('Fish Stocking Calculator', 'aqualuxe'),
            'class' => '',
        ), $atts);
        
        // Sanitize attributes
        $title = sanitize_text_field($atts['title']);
        $class = sanitize_html_class($atts['class']);
        
        // Enqueue required assets
        wp_enqueue_style('aqualuxe-calculator');
        wp_enqueue_script('aqualuxe-calculator');
        
        // Start output buffering
        ob_start();
        
        // Include the calculator template
        include_once AQUALUXE_DIR . '/template-parts/calculators/stocking-calculator.php';
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Fish Compatibility Checker shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function fish_compatibility_checker_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'title' => __('Fish Compatibility Checker', 'aqualuxe'),
            'limit' => 20, // Maximum number of fish to display
            'class' => '',
        ), $atts);
        
        // Sanitize attributes
        $title = sanitize_text_field($atts['title']);
        $limit = absint($atts['limit']);
        $class = sanitize_html_class($atts['class']);
        
        // Enqueue required assets
        wp_enqueue_style('aqualuxe-compatibility');
        wp_enqueue_script('aqualuxe-compatibility');
        
        // Start output buffering
        ob_start();
        
        // Include the compatibility checker template
        include_once AQUALUXE_DIR . '/template-parts/compatibility/compatibility-checker.php';
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Fish Care Guide shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function fish_care_guide_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'id' => 0, // Fish species ID
            'show_tabs' => 'care,specs', // Tabs to show: care, specs, compatibility, breeding
            'title' => '', // Custom title
            'class' => '',
        ), $atts);
        
        // Sanitize attributes
        $id = absint($atts['id']);
        $show_tabs = sanitize_text_field($atts['show_tabs']);
        $title = sanitize_text_field($atts['title']);
        $class = sanitize_html_class($atts['class']);
        
        // If no ID provided, return error message
        if (empty($id)) {
            return '<p class="error">' . esc_html__('Please specify a fish species ID.', 'aqualuxe') . '</p>';
        }
        
        // Check if fish species exists
        $post = get_post($id);
        if (empty($post) || 'fish_species' !== $post->post_type) {
            return '<p class="error">' . esc_html__('Invalid fish species ID.', 'aqualuxe') . '</p>';
        }
        
        // Parse tabs to show
        $tabs_to_show = explode(',', $show_tabs);
        
        // Enqueue required assets
        wp_enqueue_style('aqualuxe-fish-species');
        
        // Start output buffering
        ob_start();
        
        // Include the fish care guide template
        include_once AQUALUXE_DIR . '/template-parts/fish-species/care-guide.php';
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Fish Species Grid shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function fish_species_grid_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts(array(
            'category' => '', // Fish category slug
            'care_level' => '', // Care level slug
            'temperament' => '', // Temperament (peaceful, semi-aggressive, aggressive)
            'limit' => 8, // Number of fish to display
            'orderby' => 'title', // Sort by field
            'order' => 'ASC', // Sort order
            'columns' => 4, // Number of columns
            'class' => '',
        ), $atts);
        
        // Sanitize attributes
        $category = sanitize_text_field($atts['category']);
        $care_level = sanitize_text_field($atts['care_level']);
        $temperament = sanitize_text_field($atts['temperament']);
        $limit = absint($atts['limit']);
        $orderby = sanitize_key($atts['orderby']);
        $order = sanitize_key($atts['order']);
        $columns = absint($atts['columns']);
        $class = sanitize_html_class($atts['class']);
        
        // Validate columns
        $columns = max(1, min(6, $columns));
        
        // Enqueue required assets
        wp_enqueue_style('aqualuxe-fish-species');
        
        // Build query args
        $args = array(
            'post_type' => 'fish_species',
            'posts_per_page' => $limit,
            'orderby' => $orderby,
            'order' => $order,
        );
        
        // Add tax query
        $tax_query = array();
        
        if (!empty($category)) {
            $tax_query[] = array(
                'taxonomy' => 'fish_category',
                'field' => 'slug',
                'terms' => explode(',', $category),
            );
        }
        
        if (!empty($care_level)) {
            $tax_query[] = array(
                'taxonomy' => 'care_level',
                'field' => 'slug',
                'terms' => explode(',', $care_level),
            );
        }
        
        if (!empty($tax_query)) {
            $args['tax_query'] = array_merge(array('relation' => 'AND'), $tax_query);
        }
        
        // Add meta query for temperament
        if (!empty($temperament)) {
            $args['meta_query'] = array(
                array(
                    'key' => '_temperament',
                    'value' => explode(',', $temperament),
                    'compare' => 'IN',
                ),
            );
        }
        
        // Start output buffering
        ob_start();
        
        // Run the query
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            echo '<div class="aqualuxe-fish-species-grid columns-' . esc_attr($columns) . ' ' . esc_attr($class) . '">';
            
            while ($query->have_posts()) {
                $query->the_post();
                
                // Include the fish species grid item template
                include AQUALUXE_DIR . '/template-parts/fish-species/grid-item.php';
            }
            
            echo '</div>';
        } else {
            echo '<p class="no-fish-found">' . esc_html__('No fish species found.', 'aqualuxe') . '</p>';
        }
        
        wp_reset_postdata();
        
        // Return the buffered content
        return ob_get_clean();
    }
}