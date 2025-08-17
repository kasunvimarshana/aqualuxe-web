<?php
/**
 * Template Name: Privacy Policy Page
 *
 * This is the template that displays the privacy policy page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Get the privacy policy page content template part
    get_template_part('template-parts/pages/privacy-policy', 'content');
    ?>
</main><!-- #main -->

<?php
get_footer();