<?php
/** Load enabled modules via config */
if (!defined('ABSPATH')) { exit; }

$modules_cfg = AQUALUXE_DIR . 'modules/modules.json';
$default = [
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
];

$config = [];
if (file_exists($modules_cfg)) {
    $config = json_decode(file_get_contents($modules_cfg), true) ?: [];
}
if (!$config) { $config = $default; }
foreach ($config as $module => $enabled) {
    if (!$enabled) continue;
    $bootstrap = AQUALUXE_DIR . 'modules/' . sanitize_key($module) . '/bootstrap.php';
    if (file_exists($bootstrap)) require_once $bootstrap;
}
