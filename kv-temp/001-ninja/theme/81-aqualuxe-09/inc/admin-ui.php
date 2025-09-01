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

    add_submenu_page(
        'aqualuxe',
        __('Provision Site','aqualuxe'),
        __('Provision Site','aqualuxe'),
        'manage_options',
        'aqualuxe-provision',
        function(){
            echo '<div class="wrap"><h1>'.esc_html__('Provision Site','aqualuxe').'</h1>';
            echo '<p>'.esc_html__('Create pages, menus, and assignments per the AquaLuxe business outline. Safe to run multiple times.','aqualuxe').'</p>';
            $url = wp_nonce_url( admin_url('admin-post.php?action=aqualuxe_provision_run'), 'aqualuxe_provision');
            echo '<a href="'.esc_url($url).'" class="button button-primary">'.esc_html__('Run Provisioning','aqualuxe').'</a>';
            echo '</div>';
        }
    );
});
