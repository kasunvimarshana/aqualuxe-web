<?php
/**
 * AquaLuxe Performance Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Performance Module Class
 */
class AquaLuxe_Performance_Module extends AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'performance';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'Performance';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Optimizes the performance of the AquaLuxe theme with caching, minification, and lazy loading.';

    /**
     * Module version
     *
     * @var string
     */
    protected $module_version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Initialize module
     *
     * @return void
     */
    public function init() {
        // Include required files
        $this->includes();
        
        // Register hooks
        $this->register_hooks();
        
        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        
        // Register admin settings
        add_action( 'admin_init', array( $this, 'register_admin_settings' ) );
        
        // Enable features based on settings
        $this->enable_features();
    }

    /**
     * Include required files
     *
     * @return void
     */
    private function includes() {
        // Include helper functions
        require_once $this->get_module_dir() . 'inc/helpers.php';
        
        // Include caching functions
        require_once $this->get_module_dir() . 'inc/caching.php';
        
        // Include minification functions
        require_once $this->get_module_dir() . 'inc/minification.php';
        
        // Include lazy loading functions
        require_once $this->get_module_dir() . 'inc/lazy-loading.php';
        
        // Include critical CSS functions
        require_once $this->get_module_dir() . 'inc/critical-css.php';
        
        // Include preloading functions
        require_once $this->get_module_dir() . 'inc/preloading.php';
    }

    /**
     * Register hooks
     *
     * @return void
     */
    private function register_hooks() {
        // Register assets
        add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
        
        // Enqueue admin assets
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        
        // Add admin notices
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
        
        // Add AJAX handlers
        add_action( 'wp_ajax_aqualuxe_performance_clear_cache', array( $this, 'ajax_clear_cache' ) );
        add_action( 'wp_ajax_aqualuxe_performance_generate_critical_css', array( $this, 'ajax_generate_critical_css' ) );
    }

    /**
     * Register assets
     *
     * @return void
     */
    public function register_assets() {
        // Register styles
        wp_register_style(
            'aqualuxe-performance-admin',
            $this->get_module_uri() . 'assets/css/performance-admin.css',
            array(),
            $this->get_module_version()
        );
        
        // Register scripts
        wp_register_script(
            'aqualuxe-performance-admin',
            $this->get_module_uri() . 'assets/js/performance-admin.js',
            array( 'jquery' ),
            $this->get_module_version(),
            true
        );
    }

    /**
     * Enqueue admin assets
     *
     * @return void
     */
    public function enqueue_admin_assets( $hook ) {
        // Only enqueue on performance settings page
        if ( 'settings_page_aqualuxe-performance' !== $hook ) {
            return;
        }
        
        // Enqueue styles
        wp_enqueue_style( 'aqualuxe-performance-admin' );
        
        // Enqueue scripts
        wp_enqueue_script( 'aqualuxe-performance-admin' );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-performance-admin',
            'aqualuxePerformance',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'aqualuxe_performance' ),
                'i18n' => array(
                    'clearingCache' => __( 'Clearing cache...', 'aqualuxe' ),
                    'cacheCleared' => __( 'Cache cleared successfully!', 'aqualuxe' ),
                    'cacheClearError' => __( 'Error clearing cache.', 'aqualuxe' ),
                    'generatingCriticalCss' => __( 'Generating critical CSS...', 'aqualuxe' ),
                    'criticalCssGenerated' => __( 'Critical CSS generated successfully!', 'aqualuxe' ),
                    'criticalCssError' => __( 'Error generating critical CSS.', 'aqualuxe' ),
                    'confirm' => __( 'Are you sure?', 'aqualuxe' ),
                ),
            )
        );
    }

    /**
     * Add admin menu
     *
     * @return void
     */
    public function add_admin_menu() {
        add_options_page(
            __( 'Performance Settings', 'aqualuxe' ),
            __( 'Performance', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-performance',
            array( $this, 'admin_page' )
        );
    }

    /**
     * Register admin settings
     *
     * @return void
     */
    public function register_admin_settings() {
        // Register settings
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_enable_caching' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_cache_expiration' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_enable_browser_caching' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_enable_gzip' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_enable_minification' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_minify_html' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_minify_css' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_minify_js' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_combine_css' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_combine_js' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_enable_lazy_loading' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_lazy_load_images' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_lazy_load_iframes' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_lazy_load_videos' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_enable_critical_css' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_critical_css' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_enable_preloading' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_preload_fonts' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_preload_assets' );
        register_setting( 'aqualuxe_performance', 'aqualuxe_performance_dns_prefetch' );
        
        // Add settings sections
        add_settings_section(
            'aqualuxe_performance_caching',
            __( 'Caching', 'aqualuxe' ),
            array( $this, 'settings_section_caching' ),
            'aqualuxe_performance'
        );
        
        add_settings_section(
            'aqualuxe_performance_minification',
            __( 'Minification', 'aqualuxe' ),
            array( $this, 'settings_section_minification' ),
            'aqualuxe_performance'
        );
        
        add_settings_section(
            'aqualuxe_performance_lazy_loading',
            __( 'Lazy Loading', 'aqualuxe' ),
            array( $this, 'settings_section_lazy_loading' ),
            'aqualuxe_performance'
        );
        
        add_settings_section(
            'aqualuxe_performance_critical_css',
            __( 'Critical CSS', 'aqualuxe' ),
            array( $this, 'settings_section_critical_css' ),
            'aqualuxe_performance'
        );
        
        add_settings_section(
            'aqualuxe_performance_preloading',
            __( 'Preloading', 'aqualuxe' ),
            array( $this, 'settings_section_preloading' ),
            'aqualuxe_performance'
        );
        
        // Add settings fields
        add_settings_field(
            'aqualuxe_performance_enable_caching',
            __( 'Enable Caching', 'aqualuxe' ),
            array( $this, 'settings_field_enable_caching' ),
            'aqualuxe_performance',
            'aqualuxe_performance_caching'
        );
        
        add_settings_field(
            'aqualuxe_performance_cache_expiration',
            __( 'Cache Expiration', 'aqualuxe' ),
            array( $this, 'settings_field_cache_expiration' ),
            'aqualuxe_performance',
            'aqualuxe_performance_caching'
        );
        
        add_settings_field(
            'aqualuxe_performance_enable_browser_caching',
            __( 'Enable Browser Caching', 'aqualuxe' ),
            array( $this, 'settings_field_enable_browser_caching' ),
            'aqualuxe_performance',
            'aqualuxe_performance_caching'
        );
        
        add_settings_field(
            'aqualuxe_performance_enable_gzip',
            __( 'Enable GZIP Compression', 'aqualuxe' ),
            array( $this, 'settings_field_enable_gzip' ),
            'aqualuxe_performance',
            'aqualuxe_performance_caching'
        );
        
        add_settings_field(
            'aqualuxe_performance_enable_minification',
            __( 'Enable Minification', 'aqualuxe' ),
            array( $this, 'settings_field_enable_minification' ),
            'aqualuxe_performance',
            'aqualuxe_performance_minification'
        );
        
        add_settings_field(
            'aqualuxe_performance_minify_html',
            __( 'Minify HTML', 'aqualuxe' ),
            array( $this, 'settings_field_minify_html' ),
            'aqualuxe_performance',
            'aqualuxe_performance_minification'
        );
        
        add_settings_field(
            'aqualuxe_performance_minify_css',
            __( 'Minify CSS', 'aqualuxe' ),
            array( $this, 'settings_field_minify_css' ),
            'aqualuxe_performance',
            'aqualuxe_performance_minification'
        );
        
        add_settings_field(
            'aqualuxe_performance_minify_js',
            __( 'Minify JavaScript', 'aqualuxe' ),
            array( $this, 'settings_field_minify_js' ),
            'aqualuxe_performance',
            'aqualuxe_performance_minification'
        );
        
        add_settings_field(
            'aqualuxe_performance_combine_css',
            __( 'Combine CSS Files', 'aqualuxe' ),
            array( $this, 'settings_field_combine_css' ),
            'aqualuxe_performance',
            'aqualuxe_performance_minification'
        );
        
        add_settings_field(
            'aqualuxe_performance_combine_js',
            __( 'Combine JavaScript Files', 'aqualuxe' ),
            array( $this, 'settings_field_combine_js' ),
            'aqualuxe_performance',
            'aqualuxe_performance_minification'
        );
        
        add_settings_field(
            'aqualuxe_performance_enable_lazy_loading',
            __( 'Enable Lazy Loading', 'aqualuxe' ),
            array( $this, 'settings_field_enable_lazy_loading' ),
            'aqualuxe_performance',
            'aqualuxe_performance_lazy_loading'
        );
        
        add_settings_field(
            'aqualuxe_performance_lazy_load_images',
            __( 'Lazy Load Images', 'aqualuxe' ),
            array( $this, 'settings_field_lazy_load_images' ),
            'aqualuxe_performance',
            'aqualuxe_performance_lazy_loading'
        );
        
        add_settings_field(
            'aqualuxe_performance_lazy_load_iframes',
            __( 'Lazy Load iFrames', 'aqualuxe' ),
            array( $this, 'settings_field_lazy_load_iframes' ),
            'aqualuxe_performance',
            'aqualuxe_performance_lazy_loading'
        );
        
        add_settings_field(
            'aqualuxe_performance_lazy_load_videos',
            __( 'Lazy Load Videos', 'aqualuxe' ),
            array( $this, 'settings_field_lazy_load_videos' ),
            'aqualuxe_performance',
            'aqualuxe_performance_lazy_loading'
        );
        
        add_settings_field(
            'aqualuxe_performance_enable_critical_css',
            __( 'Enable Critical CSS', 'aqualuxe' ),
            array( $this, 'settings_field_enable_critical_css' ),
            'aqualuxe_performance',
            'aqualuxe_performance_critical_css'
        );
        
        add_settings_field(
            'aqualuxe_performance_critical_css',
            __( 'Critical CSS', 'aqualuxe' ),
            array( $this, 'settings_field_critical_css' ),
            'aqualuxe_performance',
            'aqualuxe_performance_critical_css'
        );
        
        add_settings_field(
            'aqualuxe_performance_enable_preloading',
            __( 'Enable Preloading', 'aqualuxe' ),
            array( $this, 'settings_field_enable_preloading' ),
            'aqualuxe_performance',
            'aqualuxe_performance_preloading'
        );
        
        add_settings_field(
            'aqualuxe_performance_preload_fonts',
            __( 'Preload Fonts', 'aqualuxe' ),
            array( $this, 'settings_field_preload_fonts' ),
            'aqualuxe_performance',
            'aqualuxe_performance_preloading'
        );
        
        add_settings_field(
            'aqualuxe_performance_preload_assets',
            __( 'Preload Assets', 'aqualuxe' ),
            array( $this, 'settings_field_preload_assets' ),
            'aqualuxe_performance',
            'aqualuxe_performance_preloading'
        );
        
        add_settings_field(
            'aqualuxe_performance_dns_prefetch',
            __( 'DNS Prefetch', 'aqualuxe' ),
            array( $this, 'settings_field_dns_prefetch' ),
            'aqualuxe_performance',
            'aqualuxe_performance_preloading'
        );
    }

    /**
     * Admin page
     *
     * @return void
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
            <div class="aqualuxe-performance-actions">
                <button type="button" class="button button-secondary aqualuxe-performance-clear-cache"><?php esc_html_e( 'Clear Cache', 'aqualuxe' ); ?></button>
                <button type="button" class="button button-secondary aqualuxe-performance-generate-critical-css"><?php esc_html_e( 'Generate Critical CSS', 'aqualuxe' ); ?></button>
            </div>
            
            <form action="options.php" method="post">
                <?php
                settings_fields( 'aqualuxe_performance' );
                do_settings_sections( 'aqualuxe_performance' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Settings section caching
     *
     * @return void
     */
    public function settings_section_caching() {
        echo '<p>' . esc_html__( 'Configure caching settings to improve page load speed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings section minification
     *
     * @return void
     */
    public function settings_section_minification() {
        echo '<p>' . esc_html__( 'Configure minification settings to reduce file sizes.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings section lazy loading
     *
     * @return void
     */
    public function settings_section_lazy_loading() {
        echo '<p>' . esc_html__( 'Configure lazy loading settings to defer loading of non-critical resources.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings section critical CSS
     *
     * @return void
     */
    public function settings_section_critical_css() {
        echo '<p>' . esc_html__( 'Configure critical CSS settings to improve above-the-fold rendering.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings section preloading
     *
     * @return void
     */
    public function settings_section_preloading() {
        echo '<p>' . esc_html__( 'Configure preloading settings to improve resource loading.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Settings field enable caching
     *
     * @return void
     */
    public function settings_field_enable_caching() {
        $value = get_option( 'aqualuxe_performance_enable_caching', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_enable_caching" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable page caching', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Cache pages to improve load times for repeat visitors.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field cache expiration
     *
     * @return void
     */
    public function settings_field_cache_expiration() {
        $value = get_option( 'aqualuxe_performance_cache_expiration', 86400 );
        ?>
        <select name="aqualuxe_performance_cache_expiration">
            <option value="3600" <?php selected( $value, 3600 ); ?>><?php esc_html_e( '1 hour', 'aqualuxe' ); ?></option>
            <option value="21600" <?php selected( $value, 21600 ); ?>><?php esc_html_e( '6 hours', 'aqualuxe' ); ?></option>
            <option value="43200" <?php selected( $value, 43200 ); ?>><?php esc_html_e( '12 hours', 'aqualuxe' ); ?></option>
            <option value="86400" <?php selected( $value, 86400 ); ?>><?php esc_html_e( '1 day', 'aqualuxe' ); ?></option>
            <option value="604800" <?php selected( $value, 604800 ); ?>><?php esc_html_e( '1 week', 'aqualuxe' ); ?></option>
        </select>
        <p class="description"><?php esc_html_e( 'How long to keep cached pages before refreshing.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable browser caching
     *
     * @return void
     */
    public function settings_field_enable_browser_caching() {
        $value = get_option( 'aqualuxe_performance_enable_browser_caching', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_enable_browser_caching" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable browser caching', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Add browser caching headers to static resources.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable gzip
     *
     * @return void
     */
    public function settings_field_enable_gzip() {
        $value = get_option( 'aqualuxe_performance_enable_gzip', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_enable_gzip" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable GZIP compression', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Compress resources to reduce file sizes.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable minification
     *
     * @return void
     */
    public function settings_field_enable_minification() {
        $value = get_option( 'aqualuxe_performance_enable_minification', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_enable_minification" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable minification', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Minify resources to reduce file sizes.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field minify html
     *
     * @return void
     */
    public function settings_field_minify_html() {
        $value = get_option( 'aqualuxe_performance_minify_html', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_minify_html" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Minify HTML', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Remove whitespace and comments from HTML.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field minify css
     *
     * @return void
     */
    public function settings_field_minify_css() {
        $value = get_option( 'aqualuxe_performance_minify_css', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_minify_css" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Minify CSS', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Remove whitespace and comments from CSS.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field minify js
     *
     * @return void
     */
    public function settings_field_minify_js() {
        $value = get_option( 'aqualuxe_performance_minify_js', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_minify_js" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Minify JavaScript', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Remove whitespace and comments from JavaScript.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field combine css
     *
     * @return void
     */
    public function settings_field_combine_css() {
        $value = get_option( 'aqualuxe_performance_combine_css', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_combine_css" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Combine CSS files', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Combine multiple CSS files into one to reduce HTTP requests.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field combine js
     *
     * @return void
     */
    public function settings_field_combine_js() {
        $value = get_option( 'aqualuxe_performance_combine_js', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_combine_js" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Combine JavaScript files', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Combine multiple JavaScript files into one to reduce HTTP requests.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable lazy loading
     *
     * @return void
     */
    public function settings_field_enable_lazy_loading() {
        $value = get_option( 'aqualuxe_performance_enable_lazy_loading', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_enable_lazy_loading" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable lazy loading', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Defer loading of non-critical resources.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field lazy load images
     *
     * @return void
     */
    public function settings_field_lazy_load_images() {
        $value = get_option( 'aqualuxe_performance_lazy_load_images', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_lazy_load_images" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Lazy load images', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Defer loading of images until they are visible in the viewport.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field lazy load iframes
     *
     * @return void
     */
    public function settings_field_lazy_load_iframes() {
        $value = get_option( 'aqualuxe_performance_lazy_load_iframes', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_lazy_load_iframes" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Lazy load iframes', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Defer loading of iframes until they are visible in the viewport.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field lazy load videos
     *
     * @return void
     */
    public function settings_field_lazy_load_videos() {
        $value = get_option( 'aqualuxe_performance_lazy_load_videos', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_lazy_load_videos" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Lazy load videos', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Defer loading of videos until they are visible in the viewport.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable critical css
     *
     * @return void
     */
    public function settings_field_enable_critical_css() {
        $value = get_option( 'aqualuxe_performance_enable_critical_css', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_enable_critical_css" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable critical CSS', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Inline critical CSS to improve above-the-fold rendering.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field critical css
     *
     * @return void
     */
    public function settings_field_critical_css() {
        $value = get_option( 'aqualuxe_performance_critical_css', '' );
        ?>
        <textarea name="aqualuxe_performance_critical_css" rows="10" class="large-text code"><?php echo esc_textarea( $value ); ?></textarea>
        <p class="description"><?php esc_html_e( 'Enter critical CSS to be inlined in the head of the page.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field enable preloading
     *
     * @return void
     */
    public function settings_field_enable_preloading() {
        $value = get_option( 'aqualuxe_performance_enable_preloading', true );
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_performance_enable_preloading" value="1" <?php checked( $value, true ); ?>>
            <?php esc_html_e( 'Enable preloading', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Preload critical resources to improve load times.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field preload fonts
     *
     * @return void
     */
    public function settings_field_preload_fonts() {
        $value = get_option( 'aqualuxe_performance_preload_fonts', '' );
        ?>
        <textarea name="aqualuxe_performance_preload_fonts" rows="5" class="large-text code"><?php echo esc_textarea( $value ); ?></textarea>
        <p class="description"><?php esc_html_e( 'Enter font URLs to preload, one per line.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field preload assets
     *
     * @return void
     */
    public function settings_field_preload_assets() {
        $value = get_option( 'aqualuxe_performance_preload_assets', '' );
        ?>
        <textarea name="aqualuxe_performance_preload_assets" rows="5" class="large-text code"><?php echo esc_textarea( $value ); ?></textarea>
        <p class="description"><?php esc_html_e( 'Enter asset URLs to preload, one per line.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Settings field dns prefetch
     *
     * @return void
     */
    public function settings_field_dns_prefetch() {
        $value = get_option( 'aqualuxe_performance_dns_prefetch', '' );
        ?>
        <textarea name="aqualuxe_performance_dns_prefetch" rows="5" class="large-text code"><?php echo esc_textarea( $value ); ?></textarea>
        <p class="description"><?php esc_html_e( 'Enter domains to prefetch DNS, one per line.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Admin notices
     *
     * @return void
     */
    public function admin_notices() {
        // Check if cache was cleared
        if ( isset( $_GET['aqualuxe_cache_cleared'] ) && $_GET['aqualuxe_cache_cleared'] === '1' ) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Cache cleared successfully!', 'aqualuxe' ); ?></p>
            </div>
            <?php
        }
        
        // Check if critical CSS was generated
        if ( isset( $_GET['aqualuxe_critical_css_generated'] ) && $_GET['aqualuxe_critical_css_generated'] === '1' ) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Critical CSS generated successfully!', 'aqualuxe' ); ?></p>
            </div>
            <?php
        }
    }

    /**
     * AJAX clear cache
     *
     * @return void
     */
    public function ajax_clear_cache() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_performance' ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
        }
        
        // Check if user has permission
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'You do not have permission to clear cache.', 'aqualuxe' ) ) );
        }
        
        // Clear cache
        $result = aqualuxe_performance_clear_cache();
        
        // Check if cache was cleared
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }
        
        // Return success
        wp_send_json_success( array( 'message' => __( 'Cache cleared successfully!', 'aqualuxe' ) ) );
    }

    /**
     * AJAX generate critical CSS
     *
     * @return void
     */
    public function ajax_generate_critical_css() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_performance' ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
        }
        
        // Check if user has permission
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'You do not have permission to generate critical CSS.', 'aqualuxe' ) ) );
        }
        
        // Generate critical CSS
        $result = aqualuxe_performance_generate_critical_css();
        
        // Check if critical CSS was generated
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }
        
        // Return success
        wp_send_json_success( array(
            'message' => __( 'Critical CSS generated successfully!', 'aqualuxe' ),
            'critical_css' => $result,
        ) );
    }

    /**
     * Enable features
     *
     * @return void
     */
    private function enable_features() {
        // Check if caching is enabled
        if ( $this->get_setting( 'enable_caching', true ) ) {
            // Enable page caching
            add_action( 'template_redirect', 'aqualuxe_performance_cache_page', 5 );
            
            // Enable browser caching
            if ( $this->get_setting( 'enable_browser_caching', true ) ) {
                add_action( 'send_headers', 'aqualuxe_performance_browser_caching' );
            }
            
            // Enable GZIP compression
            if ( $this->get_setting( 'enable_gzip', true ) ) {
                add_action( 'init', 'aqualuxe_performance_enable_gzip' );
            }
        }
        
        // Check if minification is enabled
        if ( $this->get_setting( 'enable_minification', true ) ) {
            // Enable HTML minification
            if ( $this->get_setting( 'minify_html', true ) ) {
                add_action( 'template_redirect', 'aqualuxe_performance_minify_html', 10 );
            }
            
            // Enable CSS minification
            if ( $this->get_setting( 'minify_css', true ) ) {
                add_filter( 'style_loader_src', 'aqualuxe_performance_minify_css_src', 10, 2 );
            }
            
            // Enable JavaScript minification
            if ( $this->get_setting( 'minify_js', true ) ) {
                add_filter( 'script_loader_src', 'aqualuxe_performance_minify_js_src', 10, 2 );
            }
            
            // Enable CSS combining
            if ( $this->get_setting( 'combine_css', true ) ) {
                add_action( 'wp_enqueue_scripts', 'aqualuxe_performance_combine_css', 9999 );
            }
            
            // Enable JavaScript combining
            if ( $this->get_setting( 'combine_js', true ) ) {
                add_action( 'wp_enqueue_scripts', 'aqualuxe_performance_combine_js', 9999 );
            }
        }
        
        // Check if lazy loading is enabled
        if ( $this->get_setting( 'enable_lazy_loading', true ) ) {
            // Enable image lazy loading
            if ( $this->get_setting( 'lazy_load_images', true ) ) {
                add_filter( 'the_content', 'aqualuxe_performance_lazy_load_images', 99 );
                add_filter( 'post_thumbnail_html', 'aqualuxe_performance_lazy_load_images', 99 );
                add_filter( 'widget_text', 'aqualuxe_performance_lazy_load_images', 99 );
            }
            
            // Enable iframe lazy loading
            if ( $this->get_setting( 'lazy_load_iframes', true ) ) {
                add_filter( 'the_content', 'aqualuxe_performance_lazy_load_iframes', 99 );
                add_filter( 'widget_text', 'aqualuxe_performance_lazy_load_iframes', 99 );
            }
            
            // Enable video lazy loading
            if ( $this->get_setting( 'lazy_load_videos', true ) ) {
                add_filter( 'the_content', 'aqualuxe_performance_lazy_load_videos', 99 );
                add_filter( 'widget_text', 'aqualuxe_performance_lazy_load_videos', 99 );
            }
            
            // Add lazy loading script
            add_action( 'wp_enqueue_scripts', 'aqualuxe_performance_enqueue_lazy_load_script' );
        }
        
        // Check if critical CSS is enabled
        if ( $this->get_setting( 'enable_critical_css', true ) ) {
            // Add critical CSS
            add_action( 'wp_head', 'aqualuxe_performance_add_critical_css', 1 );
            
            // Defer non-critical CSS
            add_filter( 'style_loader_tag', 'aqualuxe_performance_defer_non_critical_css', 10, 4 );
        }
        
        // Check if preloading is enabled
        if ( $this->get_setting( 'enable_preloading', true ) ) {
            // Add preload links
            add_action( 'wp_head', 'aqualuxe_performance_add_preload_links', 1 );
            
            // Add DNS prefetch
            add_action( 'wp_head', 'aqualuxe_performance_add_dns_prefetch', 1 );
        }
    }

    /**
     * Get default settings
     *
     * @return array
     */
    protected function get_default_settings() {
        return array(
            'active' => true,
            'enable_caching' => true,
            'cache_expiration' => 86400,
            'enable_browser_caching' => true,
            'enable_gzip' => true,
            'enable_minification' => true,
            'minify_html' => true,
            'minify_css' => true,
            'minify_js' => true,
            'combine_css' => true,
            'combine_js' => true,
            'enable_lazy_loading' => true,
            'lazy_load_images' => true,
            'lazy_load_iframes' => true,
            'lazy_load_videos' => true,
            'enable_critical_css' => true,
            'enable_preloading' => true,
        );
    }
}