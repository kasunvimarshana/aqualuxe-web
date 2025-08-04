<?php

/**
 * Docker-specific configuration overrides
 */

// Disable SSL verification for development
add_filter('http_request_args', function ($args) {
    $args['sslverify'] = false;
    return $args;
});

// Set timezone
date_default_timezone_set('UTC');

// Debug settings
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

// Prevent file editing
define('DISALLOW_FILE_EDIT', true);

// Disable plugin and theme updates
define('WP_AUTO_UPDATE_CORE', false);
define('AUTOMATIC_UPDATER_DISABLED', true);
