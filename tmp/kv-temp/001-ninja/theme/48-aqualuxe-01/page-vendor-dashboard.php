<?php
/**
 * Template Name: Vendor Dashboard Page
 *
 * This is the template that displays the vendor dashboard.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Get the vendor dashboard template part
    get_template_part('template-parts/vendors/vendor', 'dashboard');
    ?>
</main><!-- #main -->

<?php
get_footer();