<?php
/**
 * AquaLuxe Internationalization
 * Loads theme textdomain and translation files.
 */
add_action('after_setup_theme', function() {
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
});
