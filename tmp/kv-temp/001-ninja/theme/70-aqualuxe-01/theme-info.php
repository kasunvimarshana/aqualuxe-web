<?php
/**
 * Theme Information and Status
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
defined('ABSPATH') || exit;

/**
 * AquaLuxe Theme Information Class
 */
class AquaLuxe_Theme_Info {
    
    /**
     * Theme version
     */
    const VERSION = '1.0.0';
    
    /**
     * Theme name
     */
    const NAME = 'AquaLuxe';
    
    /**
     * Theme author
     */
    const AUTHOR = 'AquaLuxe Development Team';
    
    /**
     * Theme description
     */
    const DESCRIPTION = 'Premium WordPress theme for luxury aquatic retail businesses';
    
    /**
     * Get theme information
     *
     * @return array Theme information
     */
    public static function get_theme_info() {
        return [
            'name' => self::NAME,
            'version' => self::VERSION,
            'author' => self::AUTHOR,
            'description' => self::DESCRIPTION,
            'features' => self::get_features(),
            'requirements' => self::get_requirements(),
            'status' => self::get_status()
        ];
    }
    
    /**
     * Get theme features
     *
     * @return array Theme features
     */
    public static function get_features() {
        return [
            'responsive_design' => true,
            'woocommerce_support' => true,
            'dark_mode' => true,
            'rtl_support' => true,
            'multilingual' => true,
            'accessibility' => true,
            'seo_optimized' => true,
            'performance_optimized' => true,
            'custom_post_types' => true,
            'theme_customizer' => true,
            'page_builder_ready' => true,
            'gutenberg_support' => true,
            'lazy_loading' => true,
            'smooth_animations' => true,
            'social_integration' => true
        ];
    }
    
    /**
     * Get theme requirements
     *
     * @return array Theme requirements
     */
    public static function get_requirements() {
        return [
            'wordpress' => '5.9+',
            'php' => '7.4+',
            'mysql' => '5.6+',
            'memory_limit' => '128M',
            'max_execution_time' => '30',
            'file_uploads' => true,
            'recommended_plugins' => [
                'woocommerce' => 'WooCommerce',
                'contact-form-7' => 'Contact Form 7',
                'yoast-seo' => 'Yoast SEO',
                'wpml' => 'WPML (optional)',
                'elementor' => 'Elementor (optional)'
            ]
        ];
    }
    
    /**
     * Get theme status
     *
     * @return array Theme status information
     */
    public static function get_status() {
        return [
            'installation_complete' => self::check_installation(),
            'assets_compiled' => self::check_assets(),
            'database_setup' => self::check_database(),
            'configuration_ready' => self::check_configuration(),
            'plugins_compatible' => self::check_plugins(),
            'performance_score' => self::get_performance_score()
        ];
    }
    
    /**
     * Check if theme is properly installed
     *
     * @return bool Installation status
     */
    private static function check_installation() {
        $required_files = [
            'style.css',
            'index.php',
            'functions.php',
            'screenshot.png'
        ];
        
        foreach ($required_files as $file) {
            if (!file_exists(get_template_directory() . '/' . $file)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check if assets are compiled
     *
     * @return bool Assets status
     */
    private static function check_assets() {
        $asset_files = [
            'assets/dist/css/app.css',
            'assets/dist/js/app.js'
        ];
        
        foreach ($asset_files as $file) {
            if (!file_exists(get_template_directory() . '/' . $file)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check database setup
     *
     * @return bool Database status
     */
    private static function check_database() {
        // Check if theme options are set
        $theme_options = get_option('aqualuxe_theme_options', []);
        return !empty($theme_options);
    }
    
    /**
     * Check configuration readiness
     *
     * @return bool Configuration status
     */
    private static function check_configuration() {
        // Check if menus are assigned
        $nav_menus = get_nav_menu_locations();
        
        // Check if widgets are set
        $sidebar_widgets = wp_get_sidebars_widgets();
        
        return !empty($nav_menus) && !empty($sidebar_widgets);
    }
    
    /**
     * Check plugin compatibility
     *
     * @return array Plugin compatibility status
     */
    private static function check_plugins() {
        $plugins = [
            'woocommerce' => class_exists('WooCommerce'),
            'contact_form_7' => class_exists('WPCF7'),
            'yoast_seo' => class_exists('WPSEO_Options'),
            'wpml' => class_exists('SitePress'),
            'elementor' => class_exists('Elementor\\Plugin')
        ];
        
        return $plugins;
    }
    
    /**
     * Get performance score
     *
     * @return int Performance score (0-100)
     */
    private static function get_performance_score() {
        $score = 100;
        
        // Check if assets are minified
        if (!self::check_assets()) {
            $score -= 20;
        }
        
        // Check if caching is enabled
        if (!self::is_caching_enabled()) {
            $score -= 15;
        }
        
        // Check if images are optimized
        if (!self::are_images_optimized()) {
            $score -= 10;
        }
        
        // Check if lazy loading is enabled
        if (!get_option('aqualuxe_lazy_loading', true)) {
            $score -= 10;
        }
        
        return max(0, $score);
    }
    
    /**
     * Check if caching is enabled
     *
     * @return bool Caching status
     */
    private static function is_caching_enabled() {
        return (
            class_exists('WP_Rocket') ||
            class_exists('W3TC') ||
            class_exists('WpFastestCache') ||
            function_exists('wp_cache_get')
        );
    }
    
    /**
     * Check if images are optimized
     *
     * @return bool Image optimization status
     */
    private static function are_images_optimized() {
        return (
            class_exists('EWWW_Image_Optimizer') ||
            class_exists('ShortPixel') ||
            class_exists('Smush')
        );
    }
    
    /**
     * Get system requirements check
     *
     * @return array System requirements status
     */
    public static function check_system_requirements() {
        $requirements = self::get_requirements();
        $status = [];
        
        // WordPress version
        $status['wordpress'] = version_compare(get_bloginfo('version'), '5.9', '>=');
        
        // PHP version
        $status['php'] = version_compare(PHP_VERSION, '7.4', '>=');
        
        // Memory limit
        $memory_limit = wp_convert_hr_to_bytes(ini_get('memory_limit'));
        $status['memory_limit'] = $memory_limit >= wp_convert_hr_to_bytes('128M');
        
        // File uploads
        $status['file_uploads'] = ini_get('file_uploads');
        
        return $status;
    }
    
    /**
     * Display theme information in admin
     */
    public static function display_admin_info() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $info = self::get_theme_info();
        $requirements = self::check_system_requirements();
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html($info['name']); ?> Theme Information</h1>
            
            <div class="aqualuxe-admin-grid">
                <!-- Theme Status -->
                <div class="aqualuxe-admin-card">
                    <h2>Theme Status</h2>
                    <ul class="aqualuxe-status-list">
                        <?php foreach ($info['status'] as $key => $status) : ?>
                            <li class="<?php echo $status ? 'status-ok' : 'status-error'; ?>">
                                <span class="status-icon"><?php echo $status ? '✅' : '❌'; ?></span>
                                <?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- System Requirements -->
                <div class="aqualuxe-admin-card">
                    <h2>System Requirements</h2>
                    <ul class="aqualuxe-status-list">
                        <?php foreach ($requirements as $key => $status) : ?>
                            <li class="<?php echo $status ? 'status-ok' : 'status-error'; ?>">
                                <span class="status-icon"><?php echo $status ? '✅' : '❌'; ?></span>
                                <?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Features -->
                <div class="aqualuxe-admin-card">
                    <h2>Theme Features</h2>
                    <ul class="aqualuxe-feature-list">
                        <?php foreach ($info['features'] as $feature => $enabled) : ?>
                            <?php if ($enabled) : ?>
                                <li>
                                    <span class="feature-icon">✨</span>
                                    <?php echo esc_html(ucfirst(str_replace('_', ' ', $feature))); ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Quick Actions -->
                <div class="aqualuxe-admin-card">
                    <h2>Quick Actions</h2>
                    <div class="aqualuxe-actions">
                        <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">
                            Customize Theme
                        </a>
                        <a href="<?php echo admin_url('themes.php?page=theme-options'); ?>" class="button">
                            Theme Options
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=wc-admin'); ?>" class="button">
                            WooCommerce Setup
                        </a>
                        <a href="https://aqualuxetheme.com/docs" class="button" target="_blank">
                            Documentation
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-admin-footer">
                <p>
                    <strong><?php echo esc_html($info['name']); ?></strong> 
                    version <?php echo esc_html($info['version']); ?> 
                    by <?php echo esc_html($info['author']); ?>
                </p>
                <p><?php echo esc_html($info['description']); ?></p>
            </div>
        </div>
        
        <style>
        .aqualuxe-admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .aqualuxe-admin-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .aqualuxe-admin-card h2 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #23282d;
            border-bottom: 2px solid #0073aa;
            padding-bottom: 10px;
        }
        
        .aqualuxe-status-list,
        .aqualuxe-feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .aqualuxe-status-list li,
        .aqualuxe-feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .status-ok {
            color: #0073aa;
        }
        
        .status-error {
            color: #d63638;
        }
        
        .aqualuxe-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .aqualuxe-admin-footer {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
        }
        </style>
        <?php
    }
}

// Initialize theme information display
if (is_admin()) {
    add_action('admin_menu', function() {
        add_theme_page(
            'AquaLuxe Theme Info',
            'Theme Info',
            'manage_options',
            'aqualuxe-theme-info',
            ['AquaLuxe_Theme_Info', 'display_admin_info']
        );
    });
}
?>
