<?php
/**
 * Template Name: Shop Page
 *
 * This is the template that displays the shop page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Get the shop page content template part
    get_template_part('template-parts/pages/shop', 'content');
    ?>
</main><!-- #main -->

<?php
get_footer();