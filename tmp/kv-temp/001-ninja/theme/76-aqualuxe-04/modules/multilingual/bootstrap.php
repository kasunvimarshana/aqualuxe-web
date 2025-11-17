<?php
defined('ABSPATH') || exit;

// Minimal multilingual hooks via WordPress core (no plugin dependency).
// Provide textdomain and a filter to switch locale per tenant if needed.
add_filter('locale', function ($locale) {
    // Placeholder for multitenant per-domain locale. Extend as needed.
    return $locale;
});
