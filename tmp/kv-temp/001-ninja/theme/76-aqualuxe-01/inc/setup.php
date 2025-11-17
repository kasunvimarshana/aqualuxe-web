<?php
defined('ABSPATH') || exit;

// Image sizes
add_action('after_setup_theme', function () {
    add_image_size('aqlx-thumb', 480, 320, true);
    add_image_size('aqlx-hero', 1920, 800, true);
});
