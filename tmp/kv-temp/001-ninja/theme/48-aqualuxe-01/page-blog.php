<?php
/**
 * Template Name: Blog Page
 *
 * This is the template that displays the blog page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Get the blog page content template part
    get_template_part('template-parts/pages/blog', 'content');
    ?>
</main><!-- #main -->

<?php
get_footer();