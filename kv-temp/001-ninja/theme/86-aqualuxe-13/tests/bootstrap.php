<?php
// Minimal bootstrap to load helpers without full WP.
define('AQUALUXE_DIR', __DIR__ . '/..');
define('AQUALUXE_ASSETS_DIST', __DIR__ . '/../assets/dist');
define('AQUALUXE_ASSETS_URI', '/wp-content/themes/aqualuxe/assets/dist');
require_once __DIR__ . '/../inc/helpers.php';
