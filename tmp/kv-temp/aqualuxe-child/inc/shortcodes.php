<?php

/**
 * Shortcodes
 */

// Example: Display year dynamically
function aqualuxe_year_shortcode()
{
    return date('Y');
}
add_shortcode('aqualuxe_year', 'aqualuxe_year_shortcode');
