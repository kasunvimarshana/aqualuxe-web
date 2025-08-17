<?php
/**
 * Template Name: Terms of Service Page
 *
 * This is the template that displays the terms of service page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Get the terms of service page content template part
    get_template_part('template-parts/pages/terms-of-service', 'content');
    ?>
</main><!-- #main -->

<?php
get_footer();