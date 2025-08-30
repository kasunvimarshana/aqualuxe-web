<?php
/**
 * Template part for displaying the site header
 *
 * @package AquaLuxe
 */

// Get header layout from theme options
$header_layout = get_theme_mod('aqualuxe_header_layout', 'centered');
?>

<header id="masthead" class="site-header" role="banner">
    <div class="container mx-auto px-4">
        <?php 
        // Load the appropriate header layout
        switch ($header_layout) {
            case 'centered':
                get_template_part('template-parts/header/header', 'centered');
                break;
            case 'split':
                get_template_part('template-parts/header/header', 'split');
                break;
            case 'stacked':
                get_template_part('template-parts/header/header', 'stacked');
                break;
            default:
                get_template_part('template-parts/header/header', 'centered');
        }
        ?>
    </div>
</header><!-- #masthead -->

<?php
// Display breadcrumbs if enabled
if (get_theme_mod('aqualuxe_enable_breadcrumbs', true) && !is_front_page()) {
    get_template_part('template-parts/header/breadcrumbs');
}