<?php
/**
 * Dark Mode Preferences Class
 *
 * @package AquaLuxe\Modules\DarkMode
 */

namespace AquaLuxe\Modules\DarkMode;

/**
 * Dark Mode Preferences Class
 */
class Preferences {
    /**
     * Cookie name
     *
     * @var string
     */
    private $cookie_name = 'aqualuxe_dark_mode';

    /**
     * Default mode
     *
     * @var string
     */
    private $default_mode = 'auto';

    /**
     * Constructor
     */
    public function __construct() {
        // Get default mode from settings
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['dark-mode'] ?? null;
        
        if ($module) {
            $this->default_mode = $module->get_setting('default_mode', 'auto');
        }
    }

    /**
     * Get mode
     *
     * @return string
     */
    public function get_mode() {
        // Check for cookie
        if (isset($_COOKIE[$this->cookie_name])) {
            $mode = sanitize_key($_COOKIE[$this->cookie_name]);
            
            // Validate mode
            if (in_array($mode, ['light', 'dark', 'auto'])) {
                return $mode;
            }
        }
        
        // Return default mode
        return $this->default_mode;
    }

    /**
     * Set mode
     *
     * @param string $mode
     * @return bool
     */
    public function set_mode($mode) {
        // Validate mode
        if (!in_array($mode, ['light', 'dark', 'auto'])) {
            return false;
        }
        
        // Set cookie
        setcookie($this->cookie_name, $mode, time() + YEAR_IN_SECONDS, '/');
        
        return true;
    }

    /**
     * Is dark mode
     *
     * @return bool
     */
    public function is_dark_mode() {
        $mode = $this->get_mode();
        
        // If mode is auto, check system preference
        if ($mode === 'auto') {
            // We can't detect system preference server-side
            // This will be handled by JavaScript
            return false;
        }
        
        return $mode === 'dark';
    }

    /**
     * Get cookie name
     *
     * @return string
     */
    public function get_cookie_name() {
        return $this->cookie_name;
    }
}