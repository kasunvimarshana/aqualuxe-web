<?php
/**
 * Template Name: Contact Page
 *
 * This is the template that displays the contact page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Get the contact page content template part
    get_template_part('template-parts/pages/contact', 'content');
    ?>
</main><!-- #main -->

<?php
get_footer();