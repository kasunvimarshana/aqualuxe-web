<?php
/**
 * Dark Mode functionality
 *
 * @package AquaLuxe
 * @subpackage AquaLuxe/inc/modules/dark-mode
 */

class AquaLuxe_Dark_Mode {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_footer', array($this, 'add_dark_mode_toggle_button'));
    }

    public function enqueue_assets() {
        wp_enqueue_style('aqualuxe-dark-mode', AQUALUXE_THEME_URI . '/assets/dist/css/dark-mode.css', array(), AQUALUXE_VERSION);
        wp_enqueue_script('aqualuxe-dark-mode', AQUALUXE_THEME_URI . '/assets/dist/js/dark-mode.js', array('jquery'), AQUALUXE_VERSION, true);
    }

    public function add_dark_mode_toggle_button() {
        echo '<button id="dark-mode-toggle" class="fixed bottom-4 right-4 p-2 rounded-full bg-gray-800 text-white dark:bg-white dark:text-gray-800">Toggle Dark Mode</button>';
    }
}

new AquaLuxe_Dark_Mode();
