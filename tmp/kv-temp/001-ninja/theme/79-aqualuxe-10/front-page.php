<?php
/**
 * Front Page Wrapper
 * Ensures WordPress uses the enhanced front page template without manual assignment.
 */

// Prevent direct access.
if (! defined('ABSPATH')) { exit; }

$tpl = get_template_directory() . '/templates/front-page.php';
if (file_exists($tpl)) {
    require $tpl;
    return;
}

// Fallback to default page template if custom template missing.
get_header();
echo '<main class="container mx-auto px-4 py-12"><p>' . esc_html__('Front page template not found.', 'aqualuxe') . '</p></main>';
get_footer();
