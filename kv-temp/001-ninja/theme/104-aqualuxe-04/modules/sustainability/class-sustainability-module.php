<?php
/**
 * Sustainability Module
 * 
 * Handles R&D and sustainability initiatives functionality
 * 
 * @package AquaLuxe
 * @subpackage Modules
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sustainability Module Class
 */
class AquaLuxe_Sustainability_Module {
    
    /**
     * Module configuration
     */
    private $config = [
        'enabled' => true,
        'carbon_tracking' => true,
        'eco_certifications' => ['organic', 'sustainable', 'eco-friendly'],
        'research_categories' => ['breeding', 'conservation', 'water-quality', 'nutrition'],
        'impact_metrics' => ['carbon_footprint', 'water_usage', 'waste_reduction', 'energy_efficiency']
    ];
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize module
     */
    private function init() {
        if (!$this->is_enabled()) {
            return;
        }
        
        $this->setup_hooks();
        $this->register_post_types();
        $this->register_taxonomies();
    }
    
    /**
     * Check if module is enabled
     */
    private function is_enabled() {
        return $this->config['enabled'] && apply_filters('aqualuxe_sustainability_enabled', true);
    }
    
    /**
     * Setup hooks
     */
    private function setup_hooks() {
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_filter('aqualuxe_dashboard_modules', [$this, 'add_dashboard_widget']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_shortcode('sustainability_report', [$this, 'render_sustainability_report']);
        add_shortcode('research_projects', [$this, 'render_research_projects']);
        add_shortcode('eco_certifications', [$this, 'render_eco_certifications']);
    }
    
    /**
     * Register sustainability-related post types
     */
    public function register_post_types() {
        // Research Project post type
        register_post_type('research_project', [
            'labels' => [
                'name' => esc_html__('Research Projects', 'aqualuxe'),
                'singular_name' => esc_html__('Research Project', 'aqualuxe'),
                'add_new' => esc_html__('Add New Project', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Research Project', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Research Project', 'aqualuxe'),
                'new_item' => esc_html__('New Research Project', 'aqualuxe'),
                'view_item' => esc_html__('View Research Project', 'aqualuxe'),
                'search_items' => esc_html__('Search Research Projects', 'aqualuxe'),
                'not_found' => esc_html__('No research projects found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No research projects found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'research'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 29,
            'menu_icon' => 'dashicons-lightbulb',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments'],
            'show_in_rest' => true,
        ]);
        
        // Sustainability Report post type
        register_post_type('sustainability_report', [
            'labels' => [
                'name' => esc_html__('Sustainability Reports', 'aqualuxe'),
                'singular_name' => esc_html__('Sustainability Report', 'aqualuxe'),
                'add_new' => esc_html__('Add New Report', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Sustainability Report', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Sustainability Report', 'aqualuxe'),
                'new_item' => esc_html__('New Sustainability Report', 'aqualuxe'),
                'view_item' => esc_html__('View Sustainability Report', 'aqualuxe'),
                'search_items' => esc_html__('Search Sustainability Reports', 'aqualuxe'),
                'not_found' => esc_html__('No sustainability reports found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No sustainability reports found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=research_project',
            'query_var' => true,
            'rewrite' => ['slug' => 'sustainability-reports'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true,
        ]);
        
        // Eco Initiative post type
        register_post_type('eco_initiative', [
            'labels' => [
                'name' => esc_html__('Eco Initiatives', 'aqualuxe'),
                'singular_name' => esc_html__('Eco Initiative', 'aqualuxe'),
                'add_new' => esc_html__('Add New Initiative', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Eco Initiative', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Eco Initiative', 'aqualuxe'),
                'new_item' => esc_html__('New Eco Initiative', 'aqualuxe'),
                'view_item' => esc_html__('View Eco Initiative', 'aqualuxe'),
                'search_items' => esc_html__('Search Eco Initiatives', 'aqualuxe'),
                'not_found' => esc_html__('No eco initiatives found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No eco initiatives found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=research_project',
            'query_var' => true,
            'rewrite' => ['slug' => 'eco-initiatives'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Research Categories
        register_taxonomy('research_category', ['research_project'], [
            'labels' => [
                'name' => esc_html__('Research Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Research Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Research Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Research Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Research Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Research Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Research Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Research Category Name', 'aqualuxe'),
                'menu_name' => esc_html__('Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'research-category'],
            'show_in_rest' => true,
        ]);
        
        // Sustainability Tags
        register_taxonomy('sustainability_tag', ['research_project', 'sustainability_report', 'eco_initiative'], [
            'labels' => [
                'name' => esc_html__('Sustainability Tags', 'aqualuxe'),
                'singular_name' => esc_html__('Sustainability Tag', 'aqualuxe'),
                'search_items' => esc_html__('Search Sustainability Tags', 'aqualuxe'),
                'all_items' => esc_html__('All Sustainability Tags', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Sustainability Tag', 'aqualuxe'),
                'update_item' => esc_html__('Update Sustainability Tag', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Sustainability Tag', 'aqualuxe'),
                'new_item_name' => esc_html__('New Sustainability Tag Name', 'aqualuxe'),
                'menu_name' => esc_html__('Tags', 'aqualuxe'),
            ],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'sustainability-tag'],
            'show_in_rest' => true,
        ]);
        
        // Initiative Status
        register_taxonomy('initiative_status', ['eco_initiative'], [
            'labels' => [
                'name' => esc_html__('Initiative Status', 'aqualuxe'),
                'singular_name' => esc_html__('Status', 'aqualuxe'),
            ],
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => false,
            'show_in_rest' => false,
        ]);
    }
    
    /**
     * Enqueue module assets
     */
    public function enqueue_assets() {
        if (is_singular(['research_project', 'sustainability_report', 'eco_initiative']) ||
            is_post_type_archive(['research_project', 'sustainability_report', 'eco_initiative'])) {
            
            wp_enqueue_script(
                'aqualuxe-sustainability',
                aqualuxe_asset('js/modules/sustainability.js'),
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-sustainability', 'aqualuxeSustainability', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_sustainability'),
                'config' => $this->config,
            ]);
        }
    }
    
    /**
     * Add admin menu items
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=research_project',
            esc_html__('Impact Dashboard', 'aqualuxe'),
            esc_html__('Impact Dashboard', 'aqualuxe'),
            'manage_options',
            'sustainability-dashboard',
            [$this, 'render_impact_dashboard']
        );
        
        add_submenu_page(
            'edit.php?post_type=research_project',
            esc_html__('Sustainability Settings', 'aqualuxe'),
            esc_html__('Settings', 'aqualuxe'),
            'manage_options',
            'sustainability-settings',
            [$this, 'render_admin_settings']
        );
    }
    
    /**
     * Render impact dashboard
     */
    public function render_impact_dashboard() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Sustainability Impact Dashboard', 'aqualuxe'); ?></h1>
            
            <div class="sustainability-dashboard">
                <div class="metrics-grid">
                    <div class="metric-card">
                        <h3><?php esc_html_e('Carbon Footprint', 'aqualuxe'); ?></h3>
                        <div class="metric-value">
                            <span class="number"><?php echo esc_html($this->get_carbon_footprint()); ?></span>
                            <span class="unit">kg CO2</span>
                        </div>
                        <div class="metric-trend">
                            <span class="trend-indicator positive">↓ 15%</span>
                            <span class="trend-label"><?php esc_html_e('vs last year', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                    
                    <div class="metric-card">
                        <h3><?php esc_html_e('Water Conservation', 'aqualuxe'); ?></h3>
                        <div class="metric-value">
                            <span class="number"><?php echo esc_html($this->get_water_saved()); ?></span>
                            <span class="unit"><?php esc_html_e('liters saved', 'aqualuxe'); ?></span>
                        </div>
                        <div class="metric-trend">
                            <span class="trend-indicator positive">↑ 23%</span>
                            <span class="trend-label"><?php esc_html_e('vs last year', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                    
                    <div class="metric-card">
                        <h3><?php esc_html_e('Waste Reduction', 'aqualuxe'); ?></h3>
                        <div class="metric-value">
                            <span class="number"><?php echo esc_html($this->get_waste_reduction()); ?></span>
                            <span class="unit">%</span>
                        </div>
                        <div class="metric-trend">
                            <span class="trend-indicator positive">↑ 18%</span>
                            <span class="trend-label"><?php esc_html_e('vs last year', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                    
                    <div class="metric-card">
                        <h3><?php esc_html_e('Research Projects', 'aqualuxe'); ?></h3>
                        <div class="metric-value">
                            <span class="number"><?php echo esc_html($this->get_active_projects_count()); ?></span>
                            <span class="unit"><?php esc_html_e('active', 'aqualuxe'); ?></span>
                        </div>
                        <div class="metric-trend">
                            <span class="trend-indicator positive">↑ 3</span>
                            <span class="trend-label"><?php esc_html_e('new this quarter', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="initiatives-overview">
                    <h2><?php esc_html_e('Current Initiatives', 'aqualuxe'); ?></h2>
                    <div class="initiatives-list">
                        <?php $this->render_initiatives_list(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render admin settings page
     */
    public function render_admin_settings() {
        if (isset($_POST['submit'])) {
            // Handle settings update
            $this->update_config([
                'carbon_tracking' => isset($_POST['carbon_tracking']),
                'eco_certifications' => array_map('sanitize_text_field', $_POST['eco_certifications'] ?? []),
                'research_categories' => array_map('sanitize_text_field', $_POST['research_categories'] ?? []),
            ]);
            
            echo '<div class="notice notice-success"><p>' . esc_html__('Settings updated successfully!', 'aqualuxe') . '</p></div>';
        }
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Sustainability Settings', 'aqualuxe'); ?></h1>
            
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Carbon Tracking', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="carbon_tracking" value="1" <?php checked($this->config['carbon_tracking']); ?> />
                            <p class="description"><?php esc_html_e('Enable carbon footprint tracking', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Eco Certifications', 'aqualuxe'); ?></th>
                        <td>
                            <?php foreach ($this->config['eco_certifications'] as $cert): ?>
                                <label>
                                    <input type="checkbox" name="eco_certifications[]" value="<?php echo esc_attr($cert); ?>" checked />
                                    <?php echo esc_html(ucfirst($cert)); ?>
                                </label><br>
                            <?php endforeach; ?>
                            <p class="description"><?php esc_html_e('Select available eco certifications', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Render sustainability report shortcode
     */
    public function render_sustainability_report($atts) {
        $atts = shortcode_atts([
            'year' => date('Y'),
            'type' => 'summary',
        ], $atts);
        
        $reports = get_posts([
            'post_type' => 'sustainability_report',
            'posts_per_page' => 1,
            'meta_query' => [
                [
                    'key' => 'report_year',
                    'value' => $atts['year'],
                    'compare' => '=',
                ],
            ],
        ]);
        
        if (empty($reports)) {
            return '<p>' . esc_html__('No sustainability report available for this year.', 'aqualuxe') . '</p>';
        }
        
        $report = $reports[0];
        
        ob_start();
        ?>
        <div class="sustainability-report">
            <h2><?php echo esc_html($report->post_title); ?></h2>
            
            <div class="report-summary">
                <?php echo wp_kses_post($report->post_content); ?>
            </div>
            
            <div class="impact-metrics">
                <div class="metric">
                    <span class="metric-label"><?php esc_html_e('CO2 Reduction', 'aqualuxe'); ?></span>
                    <span class="metric-value"><?php echo esc_html(get_post_meta($report->ID, 'co2_reduction', true)); ?>%</span>
                </div>
                <div class="metric">
                    <span class="metric-label"><?php esc_html_e('Water Saved', 'aqualuxe'); ?></span>
                    <span class="metric-value"><?php echo esc_html(get_post_meta($report->ID, 'water_saved', true)); ?> L</span>
                </div>
                <div class="metric">
                    <span class="metric-label"><?php esc_html_e('Waste Reduced', 'aqualuxe'); ?></span>
                    <span class="metric-value"><?php echo esc_html(get_post_meta($report->ID, 'waste_reduced', true)); ?>%</span>
                </div>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render research projects shortcode
     */
    public function render_research_projects($atts) {
        $atts = shortcode_atts([
            'count' => 6,
            'category' => '',
            'status' => 'active',
        ], $atts);
        
        $args = [
            'post_type' => 'research_project',
            'posts_per_page' => intval($atts['count']),
            'post_status' => 'publish',
        ];
        
        if (!empty($atts['category'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'research_category',
                    'field' => 'slug',
                    'terms' => $atts['category'],
                ],
            ];
        }
        
        if (!empty($atts['status'])) {
            $args['meta_query'] = [
                [
                    'key' => 'project_status',
                    'value' => $atts['status'],
                    'compare' => '=',
                ],
            ];
        }
        
        $projects = get_posts($args);
        
        if (empty($projects)) {
            return '<p>' . esc_html__('No research projects found.', 'aqualuxe') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="research-projects-grid">
            <?php foreach ($projects as $project): ?>
                <div class="research-project-card">
                    <?php if (has_post_thumbnail($project->ID)): ?>
                        <div class="project-image">
                            <?php echo get_the_post_thumbnail($project->ID, 'medium'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="project-content">
                        <h3 class="project-title">
                            <a href="<?php echo esc_url(get_permalink($project->ID)); ?>">
                                <?php echo esc_html($project->post_title); ?>
                            </a>
                        </h3>
                        
                        <div class="project-meta">
                            <span class="project-status"><?php echo esc_html(get_post_meta($project->ID, 'project_status', true)); ?></span>
                            <span class="project-duration"><?php echo esc_html(get_post_meta($project->ID, 'project_duration', true)); ?></span>
                        </div>
                        
                        <div class="project-excerpt">
                            <?php echo wp_trim_words($project->post_excerpt ?: $project->post_content, 20); ?>
                        </div>
                        
                        <div class="project-progress">
                            <?php $progress = get_post_meta($project->ID, 'completion_percentage', true); ?>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo esc_attr($progress); ?>%"></div>
                            </div>
                            <span class="progress-text"><?php echo esc_html($progress); ?>% <?php esc_html_e('Complete', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render eco certifications shortcode
     */
    public function render_eco_certifications($atts) {
        ob_start();
        ?>
        <div class="eco-certifications">
            <?php foreach ($this->config['eco_certifications'] as $cert): ?>
                <div class="certification-badge">
                    <div class="cert-icon">
                        <img src="<?php echo esc_url(AQUALUXE_THEME_URI . '/assets/dist/images/certifications/' . $cert . '.svg'); ?>" 
                             alt="<?php echo esc_attr(ucfirst($cert)); ?>" />
                    </div>
                    <div class="cert-label">
                        <?php echo esc_html(ucfirst(str_replace('-', ' ', $cert))); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Get carbon footprint metric
     */
    private function get_carbon_footprint() {
        return get_option('aqualuxe_carbon_footprint', '1,245');
    }
    
    /**
     * Get water saved metric
     */
    private function get_water_saved() {
        return get_option('aqualuxe_water_saved', '45,678');
    }
    
    /**
     * Get waste reduction metric
     */
    private function get_waste_reduction() {
        return get_option('aqualuxe_waste_reduction', '87');
    }
    
    /**
     * Get active projects count
     */
    private function get_active_projects_count() {
        return wp_count_posts('research_project')->publish;
    }
    
    /**
     * Render initiatives list
     */
    private function render_initiatives_list() {
        $initiatives = get_posts([
            'post_type' => 'eco_initiative',
            'posts_per_page' => 5,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => 'initiative_status',
                    'value' => 'active',
                    'compare' => '=',
                ],
            ],
        ]);
        
        if ($initiatives) {
            foreach ($initiatives as $initiative) {
                $progress = get_post_meta($initiative->ID, 'completion_percentage', true);
                $impact = get_post_meta($initiative->ID, 'environmental_impact', true);
                
                echo '<div class="initiative-item">';
                echo '<h4>' . esc_html($initiative->post_title) . '</h4>';
                echo '<div class="initiative-progress">';
                echo '<div class="progress-bar"><div class="progress-fill" style="width: ' . esc_attr($progress) . '%"></div></div>';
                echo '<span>' . esc_html($progress) . '% complete</span>';
                echo '</div>';
                echo '<div class="initiative-impact">' . esc_html($impact) . '</div>';
                echo '</div>';
            }
        }
    }
    
    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget($widgets) {
        $widgets['sustainability'] = [
            'title' => esc_html__('Sustainability & R&D', 'aqualuxe'),
            'callback' => [$this, 'render_dashboard_widget'],
            'priority' => 50,
        ];
        
        return $widgets;
    }
    
    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        $active_projects = $this->get_active_projects_count();
        $active_initiatives = wp_count_posts('eco_initiative')->publish;
        
        echo '<div class="aqualuxe-dashboard-widget">';
        echo '<div class="stats-row">';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . intval($active_projects) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Research Projects', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . intval($active_initiatives) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Eco Initiatives', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Get module configuration
     */
    public function get_config() {
        return $this->config;
    }
    
    /**
     * Update module configuration
     */
    public function update_config($config) {
        $this->config = array_merge($this->config, $config);
        update_option('aqualuxe_sustainability_config', $this->config);
    }
}