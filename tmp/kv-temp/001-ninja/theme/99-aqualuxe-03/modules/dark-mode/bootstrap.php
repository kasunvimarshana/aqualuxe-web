<?php
/**
 * Dark Mode Module Bootstrap
 *
 * @package AquaLuxe\Modules\DarkMode
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dark Mode Module Class
 */
class AquaLuxe_Dark_Mode {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_toggle'));
        add_action('wp_ajax_toggle_dark_mode', array($this, 'ajax_toggle_dark_mode'));
        add_action('wp_ajax_nopriv_toggle_dark_mode', array($this, 'ajax_toggle_dark_mode'));
        add_filter('body_class', array($this, 'body_class'));
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_THEME_URI . '/modules/dark-mode/assets/dark-mode.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-dark-mode', 'aqualuxe_dark_mode', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dark_mode_nonce'),
            'current_mode' => $this->get_current_mode(),
        ));
    }
    
    /**
     * Get current dark mode state
     */
    public function get_current_mode() {
        if (isset($_COOKIE['aqualuxe_dark_mode'])) {
            return $_COOKIE['aqualuxe_dark_mode'] === 'dark' ? 'dark' : 'light';
        }
        return 'light';
    }
    
    /**
     * Add body class for dark mode
     */
    public function body_class($classes) {
        if ($this->get_current_mode() === 'dark') {
            $classes[] = 'dark';
        }
        return $classes;
    }
    
    /**
     * Render dark mode toggle
     */
    public function render_toggle() {
        $current_mode = $this->get_current_mode();
        $toggle_text = $current_mode === 'dark' ? __('Light Mode', 'aqualuxe') : __('Dark Mode', 'aqualuxe');
        ?>
        <div id="dark-mode-toggle" class="fixed bottom-6 right-6 z-50">
            <button 
                class="bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300"
                aria-label="<?php echo esc_attr($toggle_text); ?>"
                title="<?php echo esc_attr($toggle_text); ?>"
            >
                <svg class="w-6 h-6 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                <svg class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </button>
        </div>
        <?php
    }
    
    /**
     * AJAX handler for toggle
     */
    public function ajax_toggle_dark_mode() {
        check_ajax_referer('dark_mode_nonce', 'nonce');
        
        $mode = sanitize_text_field($_POST['mode']);
        $mode = $mode === 'dark' ? 'dark' : 'light';
        
        // Set cookie for 30 days
        setcookie('aqualuxe_dark_mode', $mode, time() + (30 * DAY_IN_SECONDS), '/');
        
        wp_send_json_success(array(
            'mode' => $mode,
            'message' => $mode === 'dark' ? __('Dark mode enabled', 'aqualuxe') : __('Light mode enabled', 'aqualuxe'),
        ));
    }
}

// Initialize the module
new AquaLuxe_Dark_Mode();