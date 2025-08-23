<?php
// Enqueue assets
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('aqualuxe-main', aqualuxe_get_asset('css/main.css'), [], null);
    wp_enqueue_script('aqualuxe-main', aqualuxe_get_asset('js/main.js'), ['jquery'], null, true);
    // Add module assets
    do_action('aqualuxe_enqueue_module_assets');
});
// Add Open Graph, schema.org, ARIA, SEO meta, etc.
add_action('wp_head', function() {
    // ...Open Graph, schema, etc. (see SEO module)
});
