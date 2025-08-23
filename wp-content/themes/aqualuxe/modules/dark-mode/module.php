<?php
// Dark mode toggle with persistent preference and ARIA support
add_action('aqualuxe_enqueue_module_assets', function() {
    wp_enqueue_script('aqualuxe-darkmode', aqualuxe_get_asset('js/darkmode.js'), [], null, true);
    wp_enqueue_style('aqualuxe-darkmode', aqualuxe_get_asset('css/darkmode.css'), [], null);
});
add_action('wp_footer', function() {
    echo '<button id="dark-mode-toggle" aria-pressed="false" aria-label="Toggle dark mode"><span class="icon-moon"></span></button>';
});
