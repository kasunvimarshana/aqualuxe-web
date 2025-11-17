<?php

/**
 * Theme hooks
 *
 * @package AquaLuxe
 */

defined('ABSPATH') || exit;

/**
 * Header hook
 */
function aqualuxe_header()
{
    get_template_part('template-parts/header');
}
add_action('aqualuxe_header', 'aqualuxe_header');

/**
 * Footer hook
 */
function aqualuxe_footer()
{
    get_template_part('template-parts/footer');
}
add_action('aqualuxe_footer', 'aqualuxe_footer');

/**
 * Before content hook
 */
function aqualuxe_before_content()
{
    echo '<div class="container"><div class="content-wrapper">';
}
add_action('aqualuxe_before_content', 'aqualuxe_before_content');

/**
 * After content hook
 */
function aqualuxe_after_content()
{
    echo '</div></div>';
}
add_action('aqualuxe_after_content', 'aqualuxe_after_content');
