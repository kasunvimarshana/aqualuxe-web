<?php
/**
 * RTL Support Class
 *
 * @package AquaLuxe\Modules\Multilingual
 */

namespace AquaLuxe\Modules\Multilingual;

/**
 * RTL Support Class
 */
class RTL {
    /**
     * Is RTL
     *
     * @var bool
     */
    private $is_rtl;

    /**
     * Constructor
     *
     * @param bool $is_rtl
     */
    public function __construct($is_rtl = false) {
        $this->is_rtl = $is_rtl;
        
        if ($this->is_rtl) {
            $this->init();
        }
    }

    /**
     * Initialize RTL support
     */
    private function init() {
        // Add RTL body class
        add_filter('body_class', [$this, 'add_rtl_body_class']);
        
        // Add RTL stylesheet
        add_action('wp_enqueue_scripts', [$this, 'enqueue_rtl_styles']);
        
        // Add RTL admin stylesheet
        add_action('admin_enqueue_scripts', [$this, 'enqueue_rtl_admin_styles']);
        
        // Set text direction
        add_filter('language_attributes', [$this, 'set_text_direction']);
        
        // Adjust Tailwind direction
        add_filter('aqualuxe_tailwind_config', [$this, 'adjust_tailwind_direction']);
    }

    /**
     * Add RTL body class
     *
     * @param array $classes
     * @return array
     */
    public function add_rtl_body_class($classes) {
        $classes[] = 'rtl';
        return $classes;
    }

    /**
     * Enqueue RTL styles
     */
    public function enqueue_rtl_styles() {
        // Get the mix manifest
        $mix_manifest = $this->get_mix_manifest();
        
        // Enqueue RTL stylesheet
        wp_enqueue_style(
            'aqualuxe-rtl',
            isset($mix_manifest['/css/rtl.css']) ? AQUALUXE_DIST_URI . 'css/' . ltrim($mix_manifest['/css/rtl.css'], '/') : AQUALUXE_DIST_URI . 'css/rtl.css',
            ['aqualuxe-style'],
            AQUALUXE_VERSION
        );
    }

    /**
     * Enqueue RTL admin styles
     */
    public function enqueue_rtl_admin_styles() {
        // Get the mix manifest
        $mix_manifest = $this->get_mix_manifest();
        
        // Enqueue RTL admin stylesheet
        wp_enqueue_style(
            'aqualuxe-rtl-admin',
            isset($mix_manifest['/css/rtl-admin.css']) ? AQUALUXE_DIST_URI . 'css/' . ltrim($mix_manifest['/css/rtl-admin.css'], '/') : AQUALUXE_DIST_URI . 'css/rtl-admin.css',
            ['aqualuxe-admin-style'],
            AQUALUXE_VERSION
        );
    }

    /**
     * Set text direction
     *
     * @param string $output
     * @return string
     */
    public function set_text_direction($output) {
        return $output . ' dir="rtl"';
    }

    /**
     * Adjust Tailwind direction
     *
     * @param array $config
     * @return array
     */
    public function adjust_tailwind_direction($config) {
        if (!isset($config['theme'])) {
            $config['theme'] = [];
        }
        
        if (!isset($config['theme']['direction'])) {
            $config['theme']['direction'] = 'rtl';
        }
        
        return $config;
    }

    /**
     * Get mix manifest
     *
     * @return array
     */
    private function get_mix_manifest() {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            return json_decode(file_get_contents($manifest_path), true);
        }
        
        return [];
    }

    /**
     * Is RTL
     *
     * @return bool
     */
    public function is_rtl() {
        return $this->is_rtl;
    }
}