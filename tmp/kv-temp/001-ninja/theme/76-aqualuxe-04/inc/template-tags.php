<?php
defined('ABSPATH') || exit;

function aqlx_container_class(): string {
    $width = (int) get_theme_mod('aqualuxe_container_width', 1200);
    return 'container max-w-screen-2xl mx-auto px-4';
}

function aqlx_posted_on(): void {
    echo '<span class="posted-on">' . esc_html(get_the_date()) . '</span>';
}
