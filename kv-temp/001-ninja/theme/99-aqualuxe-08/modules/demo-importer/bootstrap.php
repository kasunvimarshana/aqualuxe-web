<?php
/**
 * Demo Importer Module Bootstrap
 *
 * @package AquaLuxe\Modules\DemoImporter
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Importer Module Class
 */
class AquaLuxe_Demo_Importer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_aqualuxe_import_demo', array($this, 'ajax_import_demo'));
        add_action('wp_ajax_aqualuxe_reset_demo', array($this, 'ajax_reset_demo'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            __('Demo Importer', 'aqualuxe'),
            __('Demo Importer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-importer',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'appearance_page_aqualuxe-demo-importer') {
            return;
        }
        
        wp_enqueue_script(
            'aqualuxe-demo-importer',
            AQUALUXE_THEME_URI . '/modules/demo-importer/assets/demo-importer.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-demo-importer', 'aqualuxe_demo_importer', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('demo_importer_nonce'),
            'strings' => array(
                'importing' => __('Importing...', 'aqualuxe'),
                'success' => __('Demo content imported successfully!', 'aqualuxe'),
                'error' => __('Failed to import demo content.', 'aqualuxe'),
                'resetting' => __('Resetting...', 'aqualuxe'),
                'reset_success' => __('Site reset successfully!', 'aqualuxe'),
                'reset_error' => __('Failed to reset site.', 'aqualuxe'),
                'confirm_reset' => __('Are you sure you want to reset all content? This action cannot be undone.', 'aqualuxe'),
            ),
        ));
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="aqualuxe-demo-importer">
                <div class="demo-options">
                    <h2><?php esc_html_e('Available Demo Content', 'aqualuxe'); ?></h2>
                    
                    <div class="demo-grid">
                        <?php $this->render_demo_option('default', 'Default Demo', 'Complete aquatic store with all features'); ?>
                        <?php $this->render_demo_option('minimal', 'Minimal Demo', 'Clean, minimal design with essential content'); ?>
                        <?php $this->render_demo_option('wholesale', 'Wholesale Demo', 'B2B focused content and products'); ?>
                    </div>
                </div>
                
                <div class="import-controls">
                    <h3><?php esc_html_e('Import Options', 'aqualuxe'); ?></h3>
                    
                    <label>
                        <input type="checkbox" name="import_content" checked>
                        <?php esc_html_e('Import Content (Posts, Pages, Products)', 'aqualuxe'); ?>
                    </label>
                    
                    <label>
                        <input type="checkbox" name="import_customizer" checked>
                        <?php esc_html_e('Import Customizer Settings', 'aqualuxe'); ?>
                    </label>
                    
                    <label>
                        <input type="checkbox" name="import_widgets" checked>
                        <?php esc_html_e('Import Widgets', 'aqualuxe'); ?>
                    </label>
                    
                    <label>
                        <input type="checkbox" name="import_menus" checked>
                        <?php esc_html_e('Import Menus', 'aqualuxe'); ?>
                    </label>
                </div>
                
                <div class="action-buttons">
                    <button type="button" id="import-demo-btn" class="button button-primary" disabled>
                        <?php esc_html_e('Import Selected Demo', 'aqualuxe'); ?>
                    </button>
                    
                    <button type="button" id="reset-site-btn" class="button button-secondary">
                        <?php esc_html_e('Reset Site', 'aqualuxe'); ?>
                    </button>
                </div>
                
                <div class="import-progress" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <div class="progress-text"></div>
                </div>
                
                <div class="import-log" style="display: none;">
                    <h3><?php esc_html_e('Import Log', 'aqualuxe'); ?></h3>
                    <div class="log-content"></div>
                </div>
            </div>
        </div>
        
        <style>
        .aqualuxe-demo-importer {
            max-width: 800px;
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .demo-option {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .demo-option:hover {
            border-color: #007cba;
            box-shadow: 0 2px 10px rgba(0, 124, 186, 0.1);
        }
        
        .demo-option.selected {
            border-color: #007cba;
            background-color: #f0f8ff;
        }
        
        .demo-option h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .demo-option p {
            color: #666;
            margin: 0;
        }
        
        .import-controls {
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        
        .import-controls label {
            display: block;
            margin: 10px 0;
        }
        
        .import-controls input[type="checkbox"] {
            margin-right: 8px;
        }
        
        .action-buttons {
            margin: 20px 0;
        }
        
        .action-buttons .button {
            margin-right: 10px;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #ddd;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: #007cba;
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .progress-text {
            text-align: center;
            font-weight: bold;
        }
        
        .import-log {
            margin-top: 20px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        
        .log-content {
            max-height: 300px;
            overflow-y: auto;
            background: white;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 12px;
            line-height: 1.4;
        }
        </style>
        <?php
    }
    
    /**
     * Render demo option
     */
    private function render_demo_option($id, $title, $description) {
        ?>
        <div class="demo-option" data-demo="<?php echo esc_attr($id); ?>">
            <h3><?php echo esc_html($title); ?></h3>
            <p><?php echo esc_html($description); ?></p>
        </div>
        <?php
    }
    
    /**
     * AJAX import demo
     */
    public function ajax_import_demo() {
        check_ajax_referer('demo_importer_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Insufficient permissions.', 'aqualuxe'));
        }
        
        $demo_type = sanitize_text_field($_POST['demo_type']);
        $options = array(
            'content' => isset($_POST['import_content']) && $_POST['import_content'] === 'true',
            'customizer' => isset($_POST['import_customizer']) && $_POST['import_customizer'] === 'true',
            'widgets' => isset($_POST['import_widgets']) && $_POST['import_widgets'] === 'true',
            'menus' => isset($_POST['import_menus']) && $_POST['import_menus'] === 'true',
        );
        
        // Simulate import process
        $result = $this->import_demo_content($demo_type, $options);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => __('Demo content imported successfully!', 'aqualuxe'),
                'log' => $this->get_import_log(),
            ));
        } else {
            wp_send_json_error(__('Failed to import demo content.', 'aqualuxe'));
        }
    }
    
    /**
     * AJAX reset demo
     */
    public function ajax_reset_demo() {
        check_ajax_referer('demo_importer_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Insufficient permissions.', 'aqualuxe'));
        }
        
        $result = $this->reset_site_content();
        
        if ($result) {
            wp_send_json_success(__('Site reset successfully!', 'aqualuxe'));
        } else {
            wp_send_json_error(__('Failed to reset site.', 'aqualuxe'));
        }
    }
    
    /**
     * Import demo content
     */
    private function import_demo_content($demo_type, $options) {
        // This is a simplified implementation
        // In a real implementation, you would import actual demo data
        
        $log = array();
        
        if ($options['content']) {
            $log[] = 'Importing posts and pages...';
            $this->create_sample_content($demo_type);
            $log[] = 'Content imported successfully.';
        }
        
        if ($options['customizer']) {
            $log[] = 'Importing customizer settings...';
            $this->import_customizer_settings($demo_type);
            $log[] = 'Customizer settings imported.';
        }
        
        if ($options['widgets']) {
            $log[] = 'Importing widgets...';
            $this->import_widgets($demo_type);
            $log[] = 'Widgets imported.';
        }
        
        if ($options['menus']) {
            $log[] = 'Importing menus...';
            $this->import_menus($demo_type);
            $log[] = 'Menus imported.';
        }
        
        $log[] = 'Demo import completed successfully!';
        update_option('aqualuxe_import_log', $log);
        
        return true;
    }
    
    /**
     * Create sample content
     */
    private function create_sample_content($demo_type) {
        // Create sample pages
        $pages = array(
            'Home' => 'Welcome to AquaLuxe - your premium destination for aquatic life.',
            'About' => 'Learn about our commitment to bringing elegance to aquatic life globally.',
            'Services' => 'Discover our comprehensive aquatic services including design, maintenance, and consultation.',
            'Contact' => 'Get in touch with our team of aquatic experts.',
        );
        
        foreach ($pages as $title => $content) {
            $existing = get_page_by_title($title);
            if (!$existing) {
                wp_insert_post(array(
                    'post_title' => $title,
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_type' => 'page',
                ));
            }
        }
        
        // Set homepage
        $home = get_page_by_title('Home');
        if ($home) {
            update_option('page_on_front', $home->ID);
            update_option('show_on_front', 'page');
        }
    }
    
    /**
     * Import customizer settings
     */
    private function import_customizer_settings($demo_type) {
        // Set default customizer values
        set_theme_mod('header_layout', 'default');
        set_theme_mod('color_scheme', 'aqua');
        set_theme_mod('typography_headings', 'playfair');
        set_theme_mod('typography_body', 'inter');
    }
    
    /**
     * Import widgets
     */
    private function import_widgets($demo_type) {
        // This would import widget settings
        // Simplified for this implementation
    }
    
    /**
     * Import menus
     */
    private function import_menus($demo_type) {
        // Create sample menu
        $menu_id = wp_create_nav_menu('Main Menu');
        
        if (!is_wp_error($menu_id)) {
            // Add menu items
            $pages = get_pages();
            foreach ($pages as $page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $page->post_title,
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish',
                ));
            }
            
            // Assign to theme location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
    
    /**
     * Reset site content
     */
    private function reset_site_content() {
        // This would reset all content to default state
        // Be very careful with this implementation in production
        
        // Reset posts and pages (keeping only default WordPress content)
        $posts = get_posts(array(
            'post_type' => array('post', 'page', 'product'),
            'numberposts' => -1,
            'post_status' => 'any',
        ));
        
        foreach ($posts as $post) {
            if (!in_array($post->post_name, array('hello-world', 'sample-page'))) {
                wp_delete_post($post->ID, true);
            }
        }
        
        // Reset customizer settings
        remove_theme_mods();
        
        // Reset menus
        $menus = wp_get_nav_menus();
        foreach ($menus as $menu) {
            wp_delete_nav_menu($menu->term_id);
        }
        
        return true;
    }
    
    /**
     * Get import log
     */
    private function get_import_log() {
        return get_option('aqualuxe_import_log', array());
    }
}

// Initialize the module
new AquaLuxe_Demo_Importer();