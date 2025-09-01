<?php
/** Load enabled modules via config */
if (!defined('ABSPATH')) { exit; }

$modules_cfg = AQUALUXE_DIR . 'modules/modules.json';
if (!file_exists($modules_cfg)) {
    // Default minimal modules config
    file_put_contents($modules_cfg, json_encode([
        'dark-mode' => true,
        'multilingual' => true,
        'wishlist' => true,
        'quick-view' => true,
        'filtering' => true,
        'subscriptions' => false,
        'bookings' => false,
        'events' => true,
        'wholesale' => true,
        'auctions' => false,
        'trade-ins' => true,
        'franchise' => false,
        'affiliate' => false
    ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
}

$config = json_decode(file_get_contents($modules_cfg), true) ?: [];
foreach ($config as $module => $enabled) {
    if (!$enabled) continue;
    $bootstrap = AQUALUXE_DIR . 'modules/' . sanitize_key($module) . '/bootstrap.php';
    if (file_exists($bootstrap)) require_once $bootstrap;
}
