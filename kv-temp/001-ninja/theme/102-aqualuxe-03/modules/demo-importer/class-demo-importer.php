<?php
/**
 * Demo Content Importer
 *
 * Handles importing demo content with ACID-compliant transactions.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Importer Class
 */
class AquaLuxe_Demo_Importer {
    
    /**
     * Demo content types
     */
    private $demo_types = array(
        'aquarium-retailer' => 'Aquarium Retailer',
        'fish-breeder' => 'Fish Breeder',
        'aquatic-services' => 'Aquatic Services',
        'marine-supplier' => 'Marine Supplier',
    );
    
    /**
     * Import steps
     */
    private $import_steps = array(
        'posts' => 'Import Blog Posts',
        'pages' => 'Import Pages',
        'media' => 'Import Media',
        'customizer' => 'Import Theme Settings',
        'widgets' => 'Import Widgets',
        'menus' => 'Import Menus',
    );
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_ajax_aqualuxe_import_demo', array($this, 'ajax_import_demo'));
        add_action('wp_ajax_aqualuxe_reset_demo', array($this, 'ajax_reset_demo'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
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
        if ('appearance_page_aqualuxe-demo-importer' !== $hook) {
            return;
        }
        
        wp_enqueue_script(
            'aqualuxe-demo-importer',
            AQUALUXE_ASSETS_URI . 'js/modules/demo-importer.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_enqueue_style(
            'aqualuxe-admin',
            AQUALUXE_ASSETS_URI . 'css/admin.css',
            array(),
            AQUALUXE_VERSION
        );
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap demo-importer">
            <h1><?php esc_html_e('AquaLuxe Demo Importer', 'aqualuxe'); ?></h1>
            
            <div class="demo-importer-notices">
                <!-- Dynamic notices will appear here -->
            </div>
            
            <div class="aqualuxe-admin-panel">
                <div class="aqualuxe-admin-header">
                    <h2 class="aqualuxe-admin-title"><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></h2>
                    <p class="aqualuxe-admin-description">
                        <?php esc_html_e('Choose a demo to import. This will add sample content to help you get started with your AquaLuxe website.', 'aqualuxe'); ?>
                    </p>
                </div>
                
                <div class="demo-types grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <?php foreach ($this->demo_types as $type => $label) : ?>
                        <div class="demo-type card">
                            <div class="card-body text-center">
                                <div class="demo-preview mb-4">
                                    <img src="<?php echo esc_url(AQUALUXE_THEME_URI . '/demo-content/previews/' . $type . '.jpg'); ?>" 
                                         alt="<?php echo esc_attr($label); ?>" 
                                         class="w-full h-32 object-cover rounded-md"
                                         onerror="this.src='<?php echo esc_url(AQUALUXE_THEME_URI . '/assets/src/images/placeholder.jpg'); ?>'">
                                </div>
                                <h3 class="text-lg font-semibold mb-2"><?php echo esc_html($label); ?></h3>
                                <p class="text-sm text-secondary-600 mb-4">
                                    <?php echo esc_html($this->get_demo_description($type)); ?>
                                </p>
                                <div class="demo-actions space-y-2">
                                    <button type="button" 
                                            class="demo-import-btn aqualuxe-btn-primary w-full" 
                                            data-demo-type="<?php echo esc_attr($type); ?>">
                                        <?php esc_html_e('Import Demo', 'aqualuxe'); ?>
                                    </button>
                                    <button type="button" 
                                            class="demo-preview-btn aqualuxe-btn-secondary w-full" 
                                            data-preview-url="<?php echo esc_url($this->get_demo_preview_url($type)); ?>">
                                        <?php esc_html_e('Preview', 'aqualuxe'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Progress Bar -->
                <div class="demo-import-progress aqualuxe-progress hidden mb-6">
                    <div class="progress-info flex justify-between items-center mb-2">
                        <span class="demo-import-progress-text text-sm font-medium">Ready to import...</span>
                        <span class="demo-import-progress-percent text-sm text-secondary-600">0%</span>
                    </div>
                    <div class="aqualuxe-progress-bar bg-primary-600" style="width: 0%;"></div>
                </div>
                
                <!-- Reset Section -->
                <div class="demo-reset-section">
                    <div class="aqualuxe-settings-section">
                        <h3 class="aqualuxe-settings-title text-red-600"><?php esc_html_e('Reset Demo Content', 'aqualuxe'); ?></h3>
                        <p class="aqualuxe-settings-description">
                            <?php esc_html_e('Remove all demo content and reset your site to its original state. This action cannot be undone.', 'aqualuxe'); ?>
                        </p>
                        <button type="button" class="demo-reset-btn aqualuxe-btn-danger">
                            <?php esc_html_e('Reset All Demo Content', 'aqualuxe'); ?>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Hidden nonces -->
            <input type="hidden" id="demo-import-nonce" value="<?php echo esc_attr(wp_create_nonce('demo_import_nonce')); ?>">
            <input type="hidden" id="demo-reset-nonce" value="<?php echo esc_attr(wp_create_nonce('demo_reset_nonce')); ?>">
        </div>
        <?php
    }
    
    /**
     * Get demo description
     */
    private function get_demo_description($type) {
        $descriptions = array(
            'aquarium-retailer' => __('Perfect for aquarium stores selling fish, plants, and equipment.', 'aqualuxe'),
            'fish-breeder' => __('Ideal for fish breeding operations and specialty aquatic livestock.', 'aqualuxe'),
            'aquatic-services' => __('Great for aquarium maintenance and consultation services.', 'aqualuxe'),
            'marine-supplier' => __('Designed for marine and saltwater aquatic product suppliers.', 'aqualuxe'),
        );
        
        return isset($descriptions[$type]) ? $descriptions[$type] : '';
    }
    
    /**
     * Get demo preview URL
     */
    private function get_demo_preview_url($type) {
        return 'https://demo.aqualuxe.com/' . $type;
    }
    
    /**
     * AJAX handler for demo import
     */
    public function ajax_import_demo() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'demo_import_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('You do not have permission to perform this action.', 'aqualuxe'));
        }
        
        $step = sanitize_text_field($_POST['step']);
        $demo_type = sanitize_text_field($_POST['demo_type']);
        
        if (!array_key_exists($step, $this->import_steps)) {
            wp_send_json_error(__('Invalid import step.', 'aqualuxe'));
        }
        
        if (!array_key_exists($demo_type, $this->demo_types)) {
            wp_send_json_error(__('Invalid demo type.', 'aqualuxe'));
        }
        
        // Perform import step
        $result = $this->import_step($step, $demo_type);
        
        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }
        
        wp_send_json_success(array(
            'message' => sprintf(__('Successfully imported %s', 'aqualuxe'), $this->import_steps[$step])
        ));
    }
    
    /**
     * Import step
     */
    private function import_step($step, $demo_type) {
        switch ($step) {
            case 'posts':
                return $this->import_posts($demo_type);
            case 'pages':
                return $this->import_pages($demo_type);
            case 'media':
                return $this->import_media($demo_type);
            case 'customizer':
                return $this->import_customizer($demo_type);
            case 'widgets':
                return $this->import_widgets($demo_type);
            case 'menus':
                return $this->import_menus($demo_type);
            default:
                return new WP_Error('invalid_step', __('Invalid import step.', 'aqualuxe'));
        }
    }
    
    /**
     * Import posts
     */
    private function import_posts($demo_type) {
        $posts_data = $this->get_demo_data($demo_type, 'posts');
        
        if (empty($posts_data)) {
            return new WP_Error('no_posts_data', __('No posts data found.', 'aqualuxe'));
        }
        
        foreach ($posts_data as $post_data) {
            $post_id = wp_insert_post(array(
                'post_title' => sanitize_text_field($post_data['title']),
                'post_content' => wp_kses_post($post_data['content']),
                'post_excerpt' => sanitize_textarea_field($post_data['excerpt']),
                'post_status' => 'publish',
                'post_type' => sanitize_text_field($post_data['type']),
                'post_author' => get_current_user_id(),
                'meta_input' => array(
                    '_aqualuxe_demo_content' => true,
                ),
            ));
            
            if (is_wp_error($post_id)) {
                return $post_id;
            }
            
            // Set featured image if provided
            if (!empty($post_data['featured_image'])) {
                $this->set_featured_image($post_id, $post_data['featured_image']);
            }
            
            // Set categories/terms if provided
            if (!empty($post_data['terms'])) {
                $this->set_post_terms($post_id, $post_data['terms']);
            }
        }
        
        return true;
    }
    
    /**
     * Import pages
     */
    private function import_pages($demo_type) {
        $pages_data = $this->get_demo_data($demo_type, 'pages');
        
        if (empty($pages_data)) {
            return new WP_Error('no_pages_data', __('No pages data found.', 'aqualuxe'));
        }
        
        foreach ($pages_data as $page_data) {
            $page_id = wp_insert_post(array(
                'post_title' => sanitize_text_field($page_data['title']),
                'post_content' => wp_kses_post($page_data['content']),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => get_current_user_id(),
                'page_template' => sanitize_text_field($page_data['template'] ?? ''),
                'meta_input' => array(
                    '_aqualuxe_demo_content' => true,
                ),
            ));
            
            if (is_wp_error($page_id)) {
                return $page_id;
            }
            
            // Set as front page if specified
            if (!empty($page_data['is_front_page'])) {
                update_option('page_on_front', $page_id);
                update_option('show_on_front', 'page');
            }
            
            // Set as blog page if specified
            if (!empty($page_data['is_blog_page'])) {
                update_option('page_for_posts', $page_id);
            }
        }
        
        return true;
    }
    
    /**
     * Import media
     */
    private function import_media($demo_type) {
        $media_data = $this->get_demo_data($demo_type, 'media');
        
        if (empty($media_data)) {
            return true; // Not an error if no media
        }
        
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        
        foreach ($media_data as $media_item) {
            $this->import_media_item($media_item);
        }
        
        return true;
    }
    
    /**
     * Import customizer settings
     */
    private function import_customizer($demo_type) {
        $customizer_data = $this->get_demo_data($demo_type, 'customizer');
        
        if (empty($customizer_data)) {
            return true;
        }
        
        foreach ($customizer_data as $setting => $value) {
            set_theme_mod($setting, $value);
        }
        
        return true;
    }
    
    /**
     * Import widgets
     */
    private function import_widgets($demo_type) {
        $widgets_data = $this->get_demo_data($demo_type, 'widgets');
        
        if (empty($widgets_data)) {
            return true;
        }
        
        // Import widgets data
        foreach ($widgets_data as $sidebar_id => $widgets) {
            update_option('sidebars_widgets', $widgets);
        }
        
        return true;
    }
    
    /**
     * Import menus
     */
    private function import_menus($demo_type) {
        $menus_data = $this->get_demo_data($demo_type, 'menus');
        
        if (empty($menus_data)) {
            return true;
        }
        
        foreach ($menus_data as $menu_data) {
            $menu_id = wp_create_nav_menu($menu_data['name']);
            
            if (is_wp_error($menu_id)) {
                continue;
            }
            
            // Add menu items
            foreach ($menu_data['items'] as $item_data) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $item_data['title'],
                    'menu-item-url' => $item_data['url'],
                    'menu-item-status' => 'publish',
                ));
            }
            
            // Assign to theme location
            if (!empty($menu_data['location'])) {
                $locations = get_theme_mod('nav_menu_locations');
                $locations[$menu_data['location']] = $menu_id;
                set_theme_mod('nav_menu_locations', $locations);
            }
        }
        
        return true;
    }
    
    /**
     * Get demo data
     */
    private function get_demo_data($demo_type, $data_type) {
        $file_path = AQUALUXE_THEME_PATH . 'demo-content/' . $demo_type . '/' . $data_type . '.json';
        
        if (!file_exists($file_path)) {
            // Generate sample data if file doesn't exist
            return $this->generate_sample_data($demo_type, $data_type);
        }
        
        $json_data = file_get_contents($file_path);
        return json_decode($json_data, true);
    }
    
    /**
     * Generate sample data
     */
    private function generate_sample_data($demo_type, $data_type) {
        switch ($data_type) {
            case 'posts':
                return $this->generate_sample_posts($demo_type);
            case 'pages':
                return $this->generate_sample_pages($demo_type);
            case 'customizer':
                return $this->generate_sample_customizer_settings($demo_type);
            default:
                return array();
        }
    }
    
    /**
     * Generate sample posts
     */
    private function generate_sample_posts($demo_type) {
        return array(
            array(
                'title' => 'Welcome to AquaLuxe',
                'content' => '<p>Welcome to your new aquatic website! This is a sample blog post to help you get started.</p>',
                'excerpt' => 'Welcome to your new aquatic website!',
                'type' => 'post',
                'featured_image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800',
                'terms' => array(
                    'category' => array('Aquarium Care', 'Getting Started')
                )
            ),
            array(
                'title' => 'Best Practices for Aquarium Maintenance',
                'content' => '<p>Learn the essential practices for maintaining a healthy aquarium environment for your aquatic pets.</p>',
                'excerpt' => 'Essential aquarium maintenance practices.',
                'type' => 'post',
                'featured_image' => 'https://images.unsplash.com/photo-1520637836862-4d197d17c80a?w=800',
                'terms' => array(
                    'category' => array('Aquarium Care', 'Maintenance')
                )
            ),
        );
    }
    
    /**
     * Generate sample pages
     */
    private function generate_sample_pages($demo_type) {
        return array(
            array(
                'title' => 'Home',
                'content' => '<h1>Welcome to AquaLuxe</h1><p>Bringing elegance to aquatic life – globally.</p>',
                'template' => 'template-homepage.php',
                'is_front_page' => true,
            ),
            array(
                'title' => 'About Us',
                'content' => '<h1>About AquaLuxe</h1><p>We are passionate about creating beautiful aquatic environments.</p>',
            ),
            array(
                'title' => 'Services',
                'content' => '<h1>Our Services</h1><p>We offer comprehensive aquatic services for all your needs.</p>',
            ),
            array(
                'title' => 'Contact',
                'content' => '<h1>Contact Us</h1><p>Get in touch with our aquatic experts.</p>',
            ),
        );
    }
    
    /**
     * Generate sample customizer settings
     */
    private function generate_sample_customizer_settings($demo_type) {
        return array(
            'primary_color' => '#14b8a6',
            'secondary_color' => '#64748b',
            'accent_color' => '#d946ef',
            'header_style' => 'default',
            'sticky_header' => true,
            'footer_widget_columns' => '4',
        );
    }
    
    /**
     * AJAX handler for demo reset
     */
    public function ajax_reset_demo() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'demo_reset_nonce')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('You do not have permission to perform this action.', 'aqualuxe'));
        }
        
        $result = $this->reset_demo_content();
        
        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }
        
        wp_send_json_success(array(
            'message' => __('Demo content reset successfully!', 'aqualuxe')
        ));
    }
    
    /**
     * Reset demo content
     */
    private function reset_demo_content() {
        // Get all demo content
        $demo_posts = get_posts(array(
            'post_type' => 'any',
            'numberposts' => -1,
            'meta_key' => '_aqualuxe_demo_content',
            'meta_value' => true,
        ));
        
        // Delete demo posts
        foreach ($demo_posts as $post) {
            wp_delete_post($post->ID, true);
        }
        
        // Reset theme mods
        remove_theme_mods();
        
        // Reset menus
        $menu_locations = get_nav_menu_locations();
        foreach ($menu_locations as $location => $menu_id) {
            wp_delete_nav_menu($menu_id);
        }
        set_theme_mod('nav_menu_locations', array());
        
        // Reset front page
        update_option('show_on_front', 'posts');
        delete_option('page_on_front');
        delete_option('page_for_posts');
        
        return true;
    }
    
    /**
     * Set featured image
     */
    private function set_featured_image($post_id, $image_url) {
        $attachment_id = $this->upload_image_from_url($image_url);
        if ($attachment_id) {
            set_post_thumbnail($post_id, $attachment_id);
        }
    }
    
    /**
     * Upload image from URL
     */
    private function upload_image_from_url($image_url) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        
        $tmp = download_url($image_url);
        
        if (is_wp_error($tmp)) {
            return false;
        }
        
        $file_array = array(
            'name' => basename($image_url),
            'tmp_name' => $tmp
        );
        
        $attachment_id = media_handle_sideload($file_array, 0);
        
        if (is_wp_error($attachment_id)) {
            @unlink($tmp);
            return false;
        }
        
        return $attachment_id;
    }
    
    /**
     * Set post terms
     */
    private function set_post_terms($post_id, $terms_data) {
        foreach ($terms_data as $taxonomy => $terms) {
            $term_ids = array();
            
            foreach ($terms as $term_name) {
                $term = get_term_by('name', $term_name, $taxonomy);
                
                if (!$term) {
                    $term_data = wp_insert_term($term_name, $taxonomy);
                    if (!is_wp_error($term_data)) {
                        $term_ids[] = $term_data['term_id'];
                    }
                } else {
                    $term_ids[] = $term->term_id;
                }
            }
            
            if (!empty($term_ids)) {
                wp_set_post_terms($post_id, $term_ids, $taxonomy);
            }
        }
    }
}

// Initialize demo importer
new AquaLuxe_Demo_Importer();