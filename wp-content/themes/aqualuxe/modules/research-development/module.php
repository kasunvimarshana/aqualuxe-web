<?php
/**
 * Research & Development Module
 *
 * Handles R&D initiatives, sustainability projects, and innovation tracking
 *
 * @package AquaLuxe\Modules\ResearchDevelopment
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Research_Development_Module
 *
 * Manages R&D and sustainability initiatives
 */
class AquaLuxe_Research_Development_Module {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Research_Development_Module
     */
    private static $instance = null;

    /**
     * Module configuration
     *
     * @var array
     */
    private $config = array(
        'enabled' => true,
        'research_areas' => array(
            'aquatic_conservation' => 'Aquatic Conservation',
            'sustainable_breeding' => 'Sustainable Breeding Programs',
            'water_quality_innovation' => 'Water Quality Innovation',
            'eco_friendly_products' => 'Eco-Friendly Product Development',
            'marine_biotechnology' => 'Marine Biotechnology',
            'coral_restoration' => 'Coral Reef Restoration',
            'aquaculture_sustainability' => 'Sustainable Aquaculture'
        ),
        'project_statuses' => array(
            'concept' => 'Concept Phase',
            'research' => 'Research Phase',
            'development' => 'Development Phase',
            'testing' => 'Testing Phase',
            'implementation' => 'Implementation',
            'completed' => 'Completed',
            'on_hold' => 'On Hold'
        ),
        'sustainability_goals' => array(
            'carbon_neutral' => 'Carbon Neutral Operations',
            'zero_waste' => 'Zero Waste Initiative',
            'renewable_energy' => 'Renewable Energy',
            'sustainable_packaging' => 'Sustainable Packaging',
            'water_conservation' => 'Water Conservation'
        )
    );

    /**
     * Get instance
     *
     * @return AquaLuxe_Research_Development_Module
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
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_aqualuxe_submit_research_proposal', array($this, 'handle_research_proposal'));
        add_action('wp_ajax_nopriv_aqualuxe_submit_research_proposal', array($this, 'handle_research_proposal'));
        add_action('wp_ajax_aqualuxe_join_research_project', array($this, 'handle_project_join'));
        add_action('wp_ajax_aqualuxe_submit_sustainability_report', array($this, 'handle_sustainability_report'));
        
        // Admin hooks
        add_action('add_meta_boxes', array($this, 'add_research_meta_boxes'));
        add_action('save_post', array($this, 'save_research_meta_data'));
        
        // Public hooks
        add_filter('the_content', array($this, 'add_research_project_details'));
        
        // Shortcodes
        add_shortcode('aqualuxe_research_projects', array($this, 'research_projects_shortcode'));
        add_shortcode('aqualuxe_sustainability_tracker', array($this, 'sustainability_tracker_shortcode'));
        add_shortcode('aqualuxe_research_proposal_form', array($this, 'research_proposal_form_shortcode'));
        add_shortcode('aqualuxe_innovation_showcase', array($this, 'innovation_showcase_shortcode'));
    }

    /**
     * Register custom post types
     */
    public function register_post_types() {
        // Research Projects
        register_post_type('aqualuxe_research', array(
            'labels' => array(
                'name' => __('Research Projects', 'aqualuxe'),
                'singular_name' => __('Research Project', 'aqualuxe'),
                'add_new' => __('Add New Project', 'aqualuxe'),
                'add_new_item' => __('Add New Research Project', 'aqualuxe'),
                'edit_item' => __('Edit Research Project', 'aqualuxe'),
                'new_item' => __('New Research Project', 'aqualuxe'),
                'view_item' => __('View Research Project', 'aqualuxe'),
                'search_items' => __('Search Research Projects', 'aqualuxe'),
                'not_found' => __('No research projects found', 'aqualuxe'),
                'not_found_in_trash' => __('No research projects found in trash', 'aqualuxe'),
                'menu_name' => __('R&D Projects', 'aqualuxe')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments'),
            'menu_icon' => 'dashicons-lightbulb',
            'rewrite' => array('slug' => 'research'),
            'capability_type' => 'post',
            'show_in_rest' => true,
            'menu_position' => 25
        ));

        // Sustainability Initiatives
        register_post_type('aqualuxe_sustainability', array(
            'labels' => array(
                'name' => __('Sustainability Initiatives', 'aqualuxe'),
                'singular_name' => __('Sustainability Initiative', 'aqualuxe'),
                'add_new' => __('Add New Initiative', 'aqualuxe'),
                'add_new_item' => __('Add New Sustainability Initiative', 'aqualuxe'),
                'edit_item' => __('Edit Sustainability Initiative', 'aqualuxe'),
                'new_item' => __('New Sustainability Initiative', 'aqualuxe'),
                'view_item' => __('View Sustainability Initiative', 'aqualuxe'),
                'search_items' => __('Search Sustainability Initiatives', 'aqualuxe'),
                'not_found' => __('No sustainability initiatives found', 'aqualuxe'),
                'not_found_in_trash' => __('No sustainability initiatives found in trash', 'aqualuxe'),
                'menu_name' => __('Sustainability', 'aqualuxe')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'menu_icon' => 'dashicons-admin-site-alt3',
            'rewrite' => array('slug' => 'sustainability'),
            'capability_type' => 'post',
            'show_in_rest' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_research'
        ));

        // Innovation Ideas/Proposals
        register_post_type('aqualuxe_innovation', array(
            'labels' => array(
                'name' => __('Innovation Ideas', 'aqualuxe'),
                'singular_name' => __('Innovation Idea', 'aqualuxe'),
                'add_new' => __('Add New Idea', 'aqualuxe'),
                'add_new_item' => __('Add New Innovation Idea', 'aqualuxe'),
                'edit_item' => __('Edit Innovation Idea', 'aqualuxe'),
                'new_item' => __('New Innovation Idea', 'aqualuxe'),
                'view_item' => __('View Innovation Idea', 'aqualuxe'),
                'search_items' => __('Search Innovation Ideas', 'aqualuxe'),
                'not_found' => __('No innovation ideas found', 'aqualuxe'),
                'not_found_in_trash' => __('No innovation ideas found in trash', 'aqualuxe'),
                'menu_name' => __('Innovation Ideas', 'aqualuxe')
            ),
            'public' => false,
            'show_ui' => true,
            'supports' => array('title', 'editor', 'custom-fields'),
            'menu_icon' => 'dashicons-star-filled',
            'capability_type' => 'post',
            'show_in_rest' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_research'
        ));

        // Research Publications
        register_post_type('aqualuxe_publication', array(
            'labels' => array(
                'name' => __('Research Publications', 'aqualuxe'),
                'singular_name' => __('Research Publication', 'aqualuxe'),
                'add_new' => __('Add New Publication', 'aqualuxe'),
                'add_new_item' => __('Add New Research Publication', 'aqualuxe'),
                'edit_item' => __('Edit Research Publication', 'aqualuxe'),
                'new_item' => __('New Research Publication', 'aqualuxe'),
                'view_item' => __('View Research Publication', 'aqualuxe'),
                'search_items' => __('Search Research Publications', 'aqualuxe'),
                'not_found' => __('No research publications found', 'aqualuxe'),
                'not_found_in_trash' => __('No research publications found in trash', 'aqualuxe'),
                'menu_name' => __('Publications', 'aqualuxe')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'menu_icon' => 'dashicons-media-document',
            'rewrite' => array('slug' => 'publications'),
            'capability_type' => 'post',
            'show_in_rest' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_research'
        ));
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Research Areas
        register_taxonomy('research_area', array('aqualuxe_research', 'aqualuxe_innovation'), array(
            'labels' => array(
                'name' => __('Research Areas', 'aqualuxe'),
                'singular_name' => __('Research Area', 'aqualuxe'),
                'search_items' => __('Search Research Areas', 'aqualuxe'),
                'all_items' => __('All Research Areas', 'aqualuxe'),
                'parent_item' => __('Parent Research Area', 'aqualuxe'),
                'parent_item_colon' => __('Parent Research Area:', 'aqualuxe'),
                'edit_item' => __('Edit Research Area', 'aqualuxe'),
                'update_item' => __('Update Research Area', 'aqualuxe'),
                'add_new_item' => __('Add New Research Area', 'aqualuxe'),
                'new_item_name' => __('New Research Area Name', 'aqualuxe'),
                'menu_name' => __('Research Areas', 'aqualuxe')
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'research-area')
        ));

        // Project Status
        register_taxonomy('project_status', array('aqualuxe_research'), array(
            'labels' => array(
                'name' => __('Project Status', 'aqualuxe'),
                'singular_name' => __('Status', 'aqualuxe'),
                'menu_name' => __('Project Status', 'aqualuxe')
            ),
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'project-status')
        ));

        // Sustainability Goals
        register_taxonomy('sustainability_goal', array('aqualuxe_sustainability'), array(
            'labels' => array(
                'name' => __('Sustainability Goals', 'aqualuxe'),
                'singular_name' => __('Sustainability Goal', 'aqualuxe'),
                'menu_name' => __('Goals', 'aqualuxe')
            ),
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'sustainability-goal')
        ));

        // Publication Types
        register_taxonomy('publication_type', array('aqualuxe_publication'), array(
            'labels' => array(
                'name' => __('Publication Types', 'aqualuxe'),
                'singular_name' => __('Publication Type', 'aqualuxe'),
                'menu_name' => __('Types', 'aqualuxe')
            ),
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'publication-type')
        ));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-research-development',
            AQUALUXE_ASSETS_URI . '/js/modules/research-development.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script('aqualuxe-research-development', 'aqualuxe_rd', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_rd_nonce'),
            'research_areas' => $this->config['research_areas'],
            'project_statuses' => $this->config['project_statuses'],
            'messages' => array(
                'submitting' => __('Submitting proposal...', 'aqualuxe'),
                'success' => __('Proposal submitted successfully!', 'aqualuxe'),
                'error' => __('Submission failed. Please try again.', 'aqualuxe'),
                'joining' => __('Joining project...', 'aqualuxe'),
                'joined' => __('Successfully joined the project!', 'aqualuxe')
            )
        ));

        wp_enqueue_style(
            'aqualuxe-research-development',
            AQUALUXE_ASSETS_URI . '/css/modules/research-development.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Add meta boxes for research projects
     */
    public function add_research_meta_boxes() {
        add_meta_box(
            'research_details',
            __('Research Project Details', 'aqualuxe'),
            array($this, 'research_details_meta_box'),
            'aqualuxe_research',
            'normal',
            'high'
        );

        add_meta_box(
            'sustainability_metrics',
            __('Sustainability Metrics', 'aqualuxe'),
            array($this, 'sustainability_metrics_meta_box'),
            'aqualuxe_sustainability',
            'normal',
            'high'
        );

        add_meta_box(
            'innovation_details',
            __('Innovation Details', 'aqualuxe'),
            array($this, 'innovation_details_meta_box'),
            'aqualuxe_innovation',
            'normal',
            'high'
        );
    }

    /**
     * Research details meta box
     */
    public function research_details_meta_box($post) {
        wp_nonce_field('research_details_meta', 'research_details_nonce');
        
        $start_date = get_post_meta($post->ID, '_research_start_date', true);
        $end_date = get_post_meta($post->ID, '_research_end_date', true);
        $budget = get_post_meta($post->ID, '_research_budget', true);
        $team_lead = get_post_meta($post->ID, '_research_team_lead', true);
        $progress = get_post_meta($post->ID, '_research_progress', true);
        $impact_score = get_post_meta($post->ID, '_environmental_impact_score', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="research_start_date"><?php _e('Start Date', 'aqualuxe'); ?></label></th>
                <td><input type="date" id="research_start_date" name="research_start_date" value="<?php echo esc_attr($start_date); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="research_end_date"><?php _e('Expected End Date', 'aqualuxe'); ?></label></th>
                <td><input type="date" id="research_end_date" name="research_end_date" value="<?php echo esc_attr($end_date); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="research_budget"><?php _e('Budget ($)', 'aqualuxe'); ?></label></th>
                <td><input type="number" id="research_budget" name="research_budget" value="<?php echo esc_attr($budget); ?>" class="regular-text" step="0.01"></td>
            </tr>
            <tr>
                <th><label for="research_team_lead"><?php _e('Team Lead', 'aqualuxe'); ?></label></th>
                <td><input type="text" id="research_team_lead" name="research_team_lead" value="<?php echo esc_attr($team_lead); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="research_progress"><?php _e('Progress (%)', 'aqualuxe'); ?></label></th>
                <td><input type="number" id="research_progress" name="research_progress" value="<?php echo esc_attr($progress); ?>" class="regular-text" min="0" max="100"></td>
            </tr>
            <tr>
                <th><label for="environmental_impact_score"><?php _e('Environmental Impact Score (1-10)', 'aqualuxe'); ?></label></th>
                <td><input type="number" id="environmental_impact_score" name="environmental_impact_score" value="<?php echo esc_attr($impact_score); ?>" class="regular-text" min="1" max="10"></td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save research meta data
     */
    public function save_research_meta_data($post_id) {
        if (!isset($_POST['research_details_nonce']) || !wp_verify_nonce($_POST['research_details_nonce'], 'research_details_meta')) {
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
            'research_progress',
            'environmental_impact_score'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }

    /**
     * Handle research proposal submission
     */
    public function handle_research_proposal() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_rd_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }

        $title = sanitize_text_field($_POST['title'] ?? '');
        $description = sanitize_textarea_field($_POST['description'] ?? '');
        $research_area = sanitize_text_field($_POST['research_area'] ?? '');
        $proposer_name = sanitize_text_field($_POST['proposer_name'] ?? '');
        $proposer_email = sanitize_email($_POST['proposer_email'] ?? '');
        $expected_impact = sanitize_textarea_field($_POST['expected_impact'] ?? '');

        if (empty($title) || empty($description) || empty($proposer_email)) {
            wp_send_json_error(__('Please fill in all required fields.', 'aqualuxe'));
        }

        $proposal_id = wp_insert_post(array(
            'post_type' => 'aqualuxe_innovation',
            'post_title' => $title,
            'post_content' => $description,
            'post_status' => 'pending',
            'meta_input' => array(
                '_proposer_name' => $proposer_name,
                '_proposer_email' => $proposer_email,
                '_expected_impact' => $expected_impact,
                '_submission_date' => current_time('mysql'),
                '_proposal_status' => 'under_review'
            )
        ));

        if ($proposal_id && !is_wp_error($proposal_id)) {
            // Set research area taxonomy
            if (!empty($research_area)) {
                wp_set_object_terms($proposal_id, $research_area, 'research_area');
            }

            // Send notification to admin
            $this->send_proposal_notification($proposal_id, $proposer_name, $proposer_email);

            wp_send_json_success(array(
                'message' => __('Research proposal submitted successfully!', 'aqualuxe'),
                'proposal_id' => $proposal_id
            ));
        } else {
            wp_send_json_error(__('Failed to submit proposal.', 'aqualuxe'));
        }
    }

    /**
     * Research projects shortcode
     */
    public function research_projects_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'posts_per_page' => 6,
            'research_area' => '',
            'status' => 'any'
        ), $atts);

        $args = array(
            'post_type' => 'aqualuxe_research',
            'posts_per_page' => $atts['posts_per_page'],
            'post_status' => 'publish'
        );

        if (!empty($atts['research_area'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'research_area',
                    'field' => 'slug',
                    'terms' => $atts['research_area']
                )
            );
        }

        $projects = new WP_Query($args);

        if (!$projects->have_posts()) {
            return '<p>' . __('No research projects found.', 'aqualuxe') . '</p>';
        }

        ob_start();
        ?>
        <div class="research-projects-grid">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($projects->have_posts()): $projects->the_post(); ?>
                    <div class="research-project-card bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                        <?php if (has_post_thumbnail()): ?>
                            <div class="project-thumbnail">
                                <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="project-content p-6">
                            <h3 class="project-title text-xl font-bold mb-2">
                                <a href="<?php the_permalink(); ?>" class="text-primary-600 hover:text-primary-800">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            
                            <div class="project-meta mb-4">
                                <?php 
                                $progress = get_post_meta(get_the_ID(), '_research_progress', true);
                                $team_lead = get_post_meta(get_the_ID(), '_research_team_lead', true);
                                ?>
                                <?php if ($progress): ?>
                                    <div class="progress-bar mb-2">
                                        <div class="progress-label text-sm text-gray-600">Progress: <?php echo esc_html($progress); ?>%</div>
                                        <div class="progress-track bg-gray-200 rounded-full h-2">
                                            <div class="progress-fill bg-primary-500 h-2 rounded-full" style="width: <?php echo esc_attr($progress); ?>%"></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($team_lead): ?>
                                    <div class="team-lead text-sm text-gray-600">
                                        <strong><?php _e('Team Lead:', 'aqualuxe'); ?></strong> <?php echo esc_html($team_lead); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="project-excerpt mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <div class="project-areas">
                                <?php 
                                $areas = get_the_terms(get_the_ID(), 'research_area');
                                if ($areas && !is_wp_error($areas)):
                                ?>
                                    <div class="research-areas">
                                        <?php foreach ($areas as $area): ?>
                                            <span class="area-tag inline-block bg-primary-100 text-primary-800 text-xs px-2 py-1 rounded mr-2 mb-1">
                                                <?php echo esc_html($area->name); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }

    /**
     * Sustainability tracker shortcode
     */
    public function sustainability_tracker_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'display' => 'dashboard' // dashboard, goals, metrics
        ), $atts);

        ob_start();
        ?>
        <div class="sustainability-tracker">
            <h3 class="text-2xl font-bold mb-6"><?php _e('Sustainability Dashboard', 'aqualuxe'); ?></h3>
            
            <div class="sustainability-metrics grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <?php
                $metrics = array(
                    'carbon_reduction' => array(
                        'label' => __('Carbon Reduction', 'aqualuxe'),
                        'value' => '35%',
                        'target' => '50%',
                        'icon' => '🌱'
                    ),
                    'water_conservation' => array(
                        'label' => __('Water Conservation', 'aqualuxe'),
                        'value' => '42%',
                        'target' => '60%',
                        'icon' => '💧'
                    ),
                    'waste_reduction' => array(
                        'label' => __('Waste Reduction', 'aqualuxe'),
                        'value' => '28%',
                        'target' => '40%',
                        'icon' => '♻️'
                    ),
                    'renewable_energy' => array(
                        'label' => __('Renewable Energy', 'aqualuxe'),
                        'value' => '65%',
                        'target' => '80%',
                        'icon' => '⚡'
                    )
                );

                foreach ($metrics as $metric_id => $metric):
                    $percentage = (int) filter_var($metric['value'], FILTER_SANITIZE_NUMBER_INT);
                ?>
                    <div class="metric-card bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                        <div class="metric-icon text-3xl mb-2"><?php echo $metric['icon']; ?></div>
                        <h4 class="metric-label text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                            <?php echo esc_html($metric['label']); ?>
                        </h4>
                        <div class="metric-value text-2xl font-bold text-primary-600 mb-2">
                            <?php echo esc_html($metric['value']); ?>
                        </div>
                        <div class="metric-target text-sm text-gray-500">
                            Target: <?php echo esc_html($metric['target']); ?>
                        </div>
                        <div class="metric-progress mt-3">
                            <div class="progress-track bg-gray-200 rounded-full h-2">
                                <div class="progress-fill bg-green-500 h-2 rounded-full" style="width: <?php echo esc_attr($percentage); ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="sustainability-initiatives">
                <h4 class="text-lg font-semibold mb-4"><?php _e('Current Initiatives', 'aqualuxe'); ?></h4>
                <?php
                $initiatives = get_posts(array(
                    'post_type' => 'aqualuxe_sustainability',
                    'posts_per_page' => 3,
                    'post_status' => 'publish'
                ));

                if ($initiatives):
                ?>
                    <div class="initiatives-list space-y-4">
                        <?php foreach ($initiatives as $initiative): ?>
                            <div class="initiative-item bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                <h5 class="font-medium text-green-800 dark:text-green-200">
                                    <?php echo esc_html($initiative->post_title); ?>
                                </h5>
                                <p class="text-sm text-green-600 dark:text-green-300 mt-1">
                                    <?php echo esc_html(wp_trim_words($initiative->post_content, 20)); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-600"><?php _e('No sustainability initiatives currently active.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Research proposal form shortcode
     */
    public function research_proposal_form_shortcode($atts = array()) {
        ob_start();
        ?>
        <form id="research-proposal-form" class="research-proposal-form">
            <div class="form-group mb-4">
                <label for="proposal-title"><?php _e('Project Title', 'aqualuxe'); ?> *</label>
                <input type="text" id="proposal-title" name="title" class="form-control" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="research-area-select"><?php _e('Research Area', 'aqualuxe'); ?></label>
                <select id="research-area-select" name="research_area" class="form-control">
                    <option value=""><?php _e('Select research area', 'aqualuxe'); ?></option>
                    <?php foreach ($this->config['research_areas'] as $area_id => $area_name): ?>
                        <option value="<?php echo esc_attr($area_id); ?>"><?php echo esc_html($area_name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group mb-4">
                <label for="proposal-description"><?php _e('Project Description', 'aqualuxe'); ?> *</label>
                <textarea id="proposal-description" name="description" class="form-control" rows="6" required></textarea>
            </div>
            
            <div class="form-group mb-4">
                <label for="expected-impact"><?php _e('Expected Environmental Impact', 'aqualuxe'); ?></label>
                <textarea id="expected-impact" name="expected_impact" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="form-row grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label for="proposer-name"><?php _e('Your Name', 'aqualuxe'); ?> *</label>
                    <input type="text" id="proposer-name" name="proposer_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="proposer-email"><?php _e('Your Email', 'aqualuxe'); ?> *</label>
                    <input type="email" id="proposer-email" name="proposer_email" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <?php _e('Submit Research Proposal', 'aqualuxe'); ?>
            </button>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Innovation showcase shortcode
     */
    public function innovation_showcase_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'limit' => 6
        ), $atts);

        $innovations = get_posts(array(
            'post_type' => 'aqualuxe_research',
            'posts_per_page' => $atts['limit'],
            'meta_query' => array(
                array(
                    'key' => '_research_progress',
                    'value' => 80,
                    'compare' => '>='
                )
            )
        ));

        if (empty($innovations)) {
            return '<p>' . __('No completed innovations to showcase yet.', 'aqualuxe') . '</p>';
        }

        ob_start();
        ?>
        <div class="innovation-showcase">
            <h3 class="text-2xl font-bold mb-6"><?php _e('Innovation Showcase', 'aqualuxe'); ?></h3>
            <div class="innovations-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($innovations as $innovation): ?>
                    <div class="innovation-card bg-gradient-to-br from-blue-50 to-teal-50 dark:from-blue-900/20 dark:to-teal-900/20 p-6 rounded-lg shadow-lg">
                        <h4 class="innovation-title text-lg font-semibold mb-2">
                            <?php echo esc_html($innovation->post_title); ?>
                        </h4>
                        <p class="innovation-description text-sm text-gray-600 dark:text-gray-300 mb-4">
                            <?php echo esc_html(wp_trim_words($innovation->post_content, 15)); ?>
                        </p>
                        <div class="innovation-impact">
                            <?php 
                            $impact_score = get_post_meta($innovation->ID, '_environmental_impact_score', true);
                            if ($impact_score):
                            ?>
                                <div class="impact-score flex items-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 mr-2"><?php _e('Impact:', 'aqualuxe'); ?></span>
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 10; $i++): ?>
                                            <span class="star <?php echo $i <= $impact_score ? 'text-yellow-400' : 'text-gray-300'; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Send proposal notification
     */
    private function send_proposal_notification($proposal_id, $proposer_name, $proposer_email) {
        $admin_email = get_option('admin_email');
        $subject = sprintf(__('New Research Proposal: %s', 'aqualuxe'), get_the_title($proposal_id));
        $message = sprintf(
            __('A new research proposal has been submitted by %s (%s). Please review it in the WordPress admin.', 'aqualuxe'),
            $proposer_name,
            $proposer_email
        );

        wp_mail($admin_email, $subject, $message);
    }

    /**
     * Add sustainability metrics meta box
     */
    public function sustainability_metrics_meta_box($post) {
        // Implementation for sustainability metrics
        echo '<p>' . __('Sustainability metrics interface would be implemented here.', 'aqualuxe') . '</p>';
    }

    /**
     * Innovation details meta box
     */
    public function innovation_details_meta_box($post) {
        // Implementation for innovation details
        echo '<p>' . __('Innovation details interface would be implemented here.', 'aqualuxe') . '</p>';
    }

    /**
     * Handle sustainability report submission
     */
    public function handle_sustainability_report() {
        // Implementation for sustainability reporting
        wp_send_json_error(__('Feature coming soon.', 'aqualuxe'));
    }

    /**
     * Handle project join request
     */
    public function handle_project_join() {
        // Implementation for joining research projects
        wp_send_json_error(__('Feature coming soon.', 'aqualuxe'));
    }

    /**
     * Add research project details to content
     */
    public function add_research_project_details($content) {
        if (!is_singular('aqualuxe_research') || is_admin()) {
            return $content;
        }

        $progress = get_post_meta(get_the_ID(), '_research_progress', true);
        $team_lead = get_post_meta(get_the_ID(), '_research_team_lead', true);
        $budget = get_post_meta(get_the_ID(), '_research_budget', true);
        $impact_score = get_post_meta(get_the_ID(), '_environmental_impact_score', true);

        $details = '<div class="research-project-details bg-gray-50 dark:bg-gray-800 p-6 rounded-lg mb-6">';
        $details .= '<h4 class="text-lg font-semibold mb-4">' . __('Project Details', 'aqualuxe') . '</h4>';
        $details .= '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">';
        
        if ($progress) {
            $details .= '<div><strong>' . __('Progress:', 'aqualuxe') . '</strong><br>' . esc_html($progress) . '%</div>';
        }
        if ($team_lead) {
            $details .= '<div><strong>' . __('Team Lead:', 'aqualuxe') . '</strong><br>' . esc_html($team_lead) . '</div>';
        }
        if ($budget) {
            $details .= '<div><strong>' . __('Budget:', 'aqualuxe') . '</strong><br>$' . number_format($budget, 2) . '</div>';
        }
        if ($impact_score) {
            $details .= '<div><strong>' . __('Impact Score:', 'aqualuxe') . '</strong><br>' . esc_html($impact_score) . '/10</div>';
        }
        
        $details .= '</div></div>';

        return $details . $content;
    }
}

// Initialize module
AquaLuxe_Research_Development_Module::get_instance();