<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>
<div class="container mx-auto my-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-wrap -mx-4">

        <div id="primary" class="w-full px-4">
            <main id="main" class="site-main" role="main">
                <?php woocommerce_content(); ?>
            </main><!-- #main -->
        </div><!-- #primary -->

    </div><!-- .flex -->
</div><!-- .container -->
<?php
get_footer();
