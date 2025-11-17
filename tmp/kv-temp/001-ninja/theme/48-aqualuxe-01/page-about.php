<?php
/**
 * Template Name: About Page
 *
 * This is the template that displays the about page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Get the about page content template part
    get_template_part('template-parts/pages/about', 'content');
    ?>
</main><!-- #main -->

<?php
get_footer();