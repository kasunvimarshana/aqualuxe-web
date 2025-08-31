<?php
declare(strict_types=1);

// Dark mode handled in assets and enqueue; provide shortcode for toggle.
add_shortcode('aqlx_dark_toggle', static function () {
    return '<button class="text-sm px-3 py-1 rounded border" data-aqlx-toggle-dark>' . esc_html__('Toggle Dark', 'aqualuxe') . '</button>';
});
