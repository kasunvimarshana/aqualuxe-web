<?php
/**
 * Template Name: FAQ Page
 *
 * This is the template that displays the FAQ page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Get the FAQ page content template part
    get_template_part('template-parts/pages/faq', 'content');
    ?>
</main><!-- #main -->

<?php
get_footer();