<?php
/**
 * Template Name: Services Page
 *
 * This is the template that displays the services page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Get the services page content template part
    get_template_part('template-parts/pages/services', 'content');
    ?>
</main><!-- #main -->

<?php
get_footer();