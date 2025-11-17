<?php
/** Admin UI pages */
if (!defined('ABSPATH')) { exit; }

add_action('admin_menu', function(){
    add_menu_page(
        __('AquaLuxe', 'aqualuxe'),
        __('AquaLuxe', 'aqualuxe'),
        'manage_options',
        'aqualuxe',
        function(){ include AQUALUXE_DIR . 'templates/admin-dashboard.php'; },
        'dashicons-water',
        3
    );
});
