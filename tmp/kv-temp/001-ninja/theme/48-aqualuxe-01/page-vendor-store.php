<?php
/**
 * Template Name: Vendor Store Page
 *
 * This is the template that displays a vendor's store.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Get the vendor store template part
    get_template_part('template-parts/vendors/vendor', 'store');
    ?>
</main><!-- #main -->

<?php
get_footer();