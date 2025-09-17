<?php
/**
 * R&D Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\RD;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * R&D Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'RD';

    /**
     * Instance
     *
     * @var Module
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize module
     */
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_shortcode('aqualuxe_research_projects', array($this, 'research_projects_shortcode'));
        add_shortcode('aqualuxe_sustainability_initiatives', array($this, 'sustainability_initiatives_shortcode'));
    }

    /**
     * Register R&D post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Research Projects', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Research Project', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('R&D Projects', 'Admin Menu text', 'aqualuxe'),
            'add_new_item'          => __('Add New Project', 'aqualuxe'),
            'edit_item'             => __('Edit Project', 'aqualuxe'),
            'view_item'             => __('View Project', 'aqualuxe'),
            'all_items'             => __('All Projects', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'research'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 31,
            'menu_icon'          => 'dashicons-microscope',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        );

        register_post_type('aqualuxe_research', $args);
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Research Categories
        register_taxonomy('aqualuxe_research_category', array('aqualuxe_research'), array(
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'research-category'),
        ));

        // Research Status
        register_taxonomy('aqualuxe_research_status', array('aqualuxe_research'), array(
            'hierarchical'      => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'research-status'),
        ));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_research_details',
            __('Research Details', 'aqualuxe'),
            array($this, 'research_details_meta_box'),
            'aqualuxe_research',
            'normal',
            'high'
        );
    }

    /**
     * Research details meta box
     */
    public function research_details_meta_box($post) {
        wp_nonce_field('aqualuxe_research_nonce', 'aqualuxe_research_nonce');
        
        $start_date = get_post_meta($post->ID, '_research_start_date', true);
        $end_date = get_post_meta($post->ID, '_research_end_date', true);
        $budget = get_post_meta($post->ID, '_research_budget', true);
        $team_lead = get_post_meta($post->ID, '_research_team_lead', true);
        $funding_source = get_post_meta($post->ID, '_research_funding_source', true);
        $sustainability_impact = get_post_meta($post->ID, '_research_sustainability_impact', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="research_start_date"><?php _e('Start Date', 'aqualuxe'); ?></label></th>
                <td><input type="date" name="research_start_date" id="research_start_date" value="<?php echo esc_attr($start_date); ?>" /></td>
            </tr>
            <tr>
                <th><label for="research_end_date"><?php _e('End Date', 'aqualuxe'); ?></label></th>
                <td><input type="date" name="research_end_date" id="research_end_date" value="<?php echo esc_attr($end_date); ?>" /></td>
            </tr>
            <tr>
                <th><label for="research_budget"><?php _e('Budget', 'aqualuxe'); ?></label></th>
                <td><input type="number" name="research_budget" id="research_budget" value="<?php echo esc_attr($budget); ?>" step="0.01" /></td>
            </tr>
            <tr>
                <th><label for="research_team_lead"><?php _e('Team Lead', 'aqualuxe'); ?></label></th>
                <td><input type="text" name="research_team_lead" id="research_team_lead" value="<?php echo esc_attr($team_lead); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="research_funding_source"><?php _e('Funding Source', 'aqualuxe'); ?></label></th>
                <td><input type="text" name="research_funding_source" id="research_funding_source" value="<?php echo esc_attr($funding_source); ?>" style="width: 100%;" /></td>
            </tr>
            <tr>
                <th><label for="research_sustainability_impact"><?php _e('Sustainability Impact', 'aqualuxe'); ?></label></th>
                <td><textarea name="research_sustainability_impact" id="research_sustainability_impact" rows="4" style="width: 100%;"><?php echo esc_textarea($sustainability_impact); ?></textarea></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['aqualuxe_research_nonce']) || !wp_verify_nonce($_POST['aqualuxe_research_nonce'], 'aqualuxe_research_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'research_start_date',
            'research_end_date',
            'research_budget',
            'research_team_lead',
            'research_funding_source',
            'research_sustainability_impact'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_singular('aqualuxe_research') || is_post_type_archive('aqualuxe_research')) {
            wp_enqueue_script('aqualuxe-research', $this->get_url() . '/assets/research.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('aqualuxe-research', $this->get_url() . '/assets/research.css', array(), '1.0.0');
        }
    }

    /**
     * Research projects shortcode
     */
    public function research_projects_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 6,
            'category' => '',
            'status' => ''
        ), $atts);

        $args = array(
            'post_type' => 'aqualuxe_research',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish'
        );

        if (!empty($atts['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'aqualuxe_research_category',
                'field' => 'slug',
                'terms' => $atts['category']
            );
        }

        if (!empty($atts['status'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'aqualuxe_research_status',
                'field' => 'slug',
                'terms' => $atts['status']
            );
        }

        $projects = new \WP_Query($args);
        
        ob_start();
        if ($projects->have_posts()) {
            echo '<div class="aqualuxe-research-grid">';
            while ($projects->have_posts()) {
                $projects->the_post();
                $this->load_template('research-card', array('post_id' => get_the_ID()));
            }
            echo '</div>';
            wp_reset_postdata();
        }
        return ob_get_clean();
    }

    /**
     * Sustainability initiatives shortcode
     */
    public function sustainability_initiatives_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 4
        ), $atts);

        $args = array(
            'post_type' => 'aqualuxe_research',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_research_sustainability_impact',
                    'value' => '',
                    'compare' => '!='
                )
            )
        );

        $initiatives = new \WP_Query($args);
        
        ob_start();
        if ($initiatives->have_posts()) {
            echo '<div class="aqualuxe-sustainability-initiatives">';
            while ($initiatives->have_posts()) {
                $initiatives->the_post();
                $this->load_template('sustainability-card', array('post_id' => get_the_ID()));
            }
            echo '</div>';
            wp_reset_postdata();
        }
        return ob_get_clean();
    }
}