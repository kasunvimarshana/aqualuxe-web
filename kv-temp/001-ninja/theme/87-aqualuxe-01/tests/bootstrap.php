<?php
// Minimal bootstrap to load helpers without full WP.
define('AQUALUXE_DIR', __DIR__ . '/..');
define('AQUALUXE_ASSETS_DIST', __DIR__ . '/../assets/dist');
define('AQUALUXE_ASSETS_URI', '/wp-content/themes/aqualuxe/assets/dist');
define('AQUALUXE_INC', __DIR__ . '/../inc');
define('AQUALUXE_MODULES', __DIR__ . '/../modules');
require_once __DIR__ . '/../inc/helpers.php';
require_once __DIR__ . '/../inc/autoload.php';
