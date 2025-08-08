<?php
/**
 * Plugin Name: Debug Headers Sent
 * Description: Helps identify where headers are being sent early
 * Version: 1.0
 */

// Only run this in debug mode
if (defined('WP_DEBUG') && WP_DEBUG) {
    // Add a shutdown function to check if headers have been sent
    add_action('shutdown', function() {
        if (headers_sent($file, $line)) {
            error_log("Headers sent in $file on line $line");
        }
    }, 0);
}