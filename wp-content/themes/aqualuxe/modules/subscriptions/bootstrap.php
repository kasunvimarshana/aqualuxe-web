<?php
/** Subscriptions module: membership CPT + role gating (non-payment placeholder) */
if (!defined('ABSPATH')) { exit; }

add_action('init', function(){
    register_post_type('membership', [
        'label' => __('Memberships','aqualuxe'),
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title','editor','thumbnail','custom-fields'],
        'has_archive' => true
    ]);
});

add_action('init', function(){
    add_role('aqualuxe_member', __('AquaLuxe Member','aqualuxe'), ['read'=>true]);
});

add_shortcode('ax_membership_cta', function(){
    if (is_user_logged_in()) {
        return '<p class="p-4 bg-emerald-50 dark:bg-emerald-900/30 rounded">'.esc_html__('You are logged in. Members get exclusive content.','aqualuxe').'</p>';
    }
    $url = wp_login_url();
    return '<a class="ax-btn" href="'.esc_url($url).'">'.esc_html__('Join / Sign in','aqualuxe').'</a>';
});
