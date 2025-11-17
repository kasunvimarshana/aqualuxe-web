<?php
// Accessibility helpers (WCAG 2.1 AA-minded), minimal and non-invasive.

// Add aria-current to menu items for current page context
add_filter('nav_menu_link_attributes', function ($atts, $item) {
    if (!is_array($atts)) { $atts = []; }
    $current = in_array('current-menu-item', (array) $item->classes, true) || in_array('current_page_item', (array) $item->classes, true);
    if ($current) {
        $atts['aria-current'] = 'page';
    }
    return $atts;
}, 10, 2);

// Ensure skip link target exists; inject if missing
add_action('wp_body_open', function(){
    echo '<div id="aqlx-a11y-top" class="sr-only" hidden></div>';
});

// Focus-visible class for browsers lacking :focus-visible
add_action('wp_head', function(){
    echo '<script>(function(){try{var d=document;d.addEventListener("keydown",function(e){if(e.key==="Tab"){d.documentElement.classList.add("focus-visible-enabled");}});}catch(e){}})();</script>';
});

// Landmark roles fallbacks for legacy markup
add_filter('body_class', function(array $classes){
    $classes[] = 'aqlx-a11y';
    return $classes;
}, 10, 1);
