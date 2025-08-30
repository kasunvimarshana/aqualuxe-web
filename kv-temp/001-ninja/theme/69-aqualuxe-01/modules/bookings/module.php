<?php
// Bookings: Calendar, booking forms, WC Bookings support
defined('ABSPATH') || exit;
add_action('init', function() {
    if (class_exists('WC_Bookings')) {
        // Register booking product type, hooks, etc.
    } else {
        // Fallback: show notice or hide booking features
    }
});
