<?php
// Basic SEO helpers and schema hooks
\add_action('wp_head', function(){
    echo '<meta name="format-detection" content="telephone=no">' . "\n";
});
\add_filter('language_attributes', function($atts){
    // Ensure proper dir attr is present for accessibility
    if (is_rtl()) { $atts .= ' dir="rtl"'; }
    return $atts;
});
