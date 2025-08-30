<?php
// Subscriptions: WooCommerce Subscriptions integration, fallback if plugin missing
defined('ABSPATH') || exit;
add_action('init', function() {
    if (class_exists('WC_Subscriptions')) {
        // Register subscription product type, hooks, etc.
    } else {
        // Fallback: show notice or hide subscription features
    }
});
